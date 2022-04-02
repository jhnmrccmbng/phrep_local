<?php

include "../config.php";
$connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbDatabase);

error_reporting(E_ALL & ~E_NOTICE);
include "sample_dbtest.php";

class UploadOperation extends Database
{
    public function insert_record($table, $fields) {
        $sql = "";
        $sql .= "INSERT INTO " . $table;
        $sql .= " (" . implode(",", array_keys($fields)) . ") VALUES ";
        $sql .= " ('" . implode("','", array_values($fields)) . "')";
        $query = mysqli_query($this->con, $sql);
        if ($query) {
            return true;
        }
    }  
    public function fetch_record($table) {
        $sql = "SELECT * FROM " . $table;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    public function getUser($table, $where){
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    function showingUploadedFiles($table1, $table2, $id1, $id2, $id){
        
        $sql = "SELECT * FROM ".$table1." a
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                WHERE a.sub_id = '".$id."'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function getcountprop($user){
        
        $sql = "SELECT COUNT(sub_id) as countprop FROM rev_groups WHERE phrepuser_id = '$user' and primary_reviewer = '1' and review = '1'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['countprop'];
        return $fileid;       
    }
    
    function revincomingcount($userid){
        $forconfirm = $this->gettingDocToBeConfirmed($userid);
            if($forconfirm){
                $a = 0;
                foreach($forconfirm as $subm){
                    if(($subm['status_action'] == 11) || $subm['status_action'] == 3){   
                        $a = $a + 1;
                    } 
                }
                
            }
        
        $confirmed = $this->gettingConfirmedDoc($userid);
            if($confirmed){
            $b = 0;
            foreach($confirmed as $subm){
                $where = array("subid" => $subm['sub_id']);
                $getproposalnotinpa = $this->fetch_record_with_where("proposal_post_approval", $where);
                    if($getproposalnotinpa){}
                    else{
                        if(($subm['pa_status'] == "onreview")||$subm['pa_status'] == null){
                            if($subm['evaluation_submitted'] == 0 && $subm['decision'] == 0){
                                $b = $b + 1;
                        }
                        }
                    }
                }
        }
       
        $confirmed = $this->gettingConfirmedDoc($userid);
            if($confirmed){
                $c = 0;
                foreach($confirmed as $subm){
                                            
                    $where = array("subid" => $subm['sub_id']);
                    $getproposalnotinpa = $this->fetch_record_with_where("proposal_post_approval", $where);
                        if($getproposalnotinpa){}
                        else{
                            if($subm['evaluation_submitted'] == 1){   
                                $propstat = $this->getMaxProposalStatus($subm['sub_id']);    
                                $where2 = array("id" => $propstat);
                                $getstat = $this->fetch_record_with_where("proposal_status", $where2);
                                    if($getstat){
                                        foreach($getstat as $gt){
                                            if($gt['status_action'] == "3"){
                                                $c = $c + 1;
                                            }
                                            else if($gt['status_action'] == "5"){
                                                $c = $c + 1; 
                                            }
                                            else if($gt['status_action'] == "14"){
                                                $c = $c + 1; 
                                            }
                                        }
                                    }                             
                            }
                        }
                }
            }
        
        return $a + $b + $c;
            
    }
    
    
    function revapprovedcount($userid){
        $confirmed = $this->gettingConfirmedDoc($userid);
            if($confirmed){
                $a = 0;
                foreach($confirmed as $subm){
                    if($subm['evaluation_submitted'] == 1){                                                
                        $propstat = $this->getMaxProposalStatus($subm['sub_id']); 
                        $where2 = array("id" => $propstat);
                        $getstat = $this->fetch_record_with_where("proposal_status", $where2);
                            if($getstat){
                                foreach($getstat as $gt){
                                    if($gt['status_action'] == "6"){
                                        $a = $a + 1;
                                    }
                                    else if($gt['status_action'] == "12"){
                                        $a = $a + 1;
                                    }
                                }
                            }                                       
                    } 
                }
        }
        
        $allprop = $this->getallpostapprovalrequest();
        $b = 0;
            foreach($allprop as $prop){
            if($prop['dp'] != null){                                                        
                $req = $this->getmaxpropapp($prop['sub_id']);
                $where = array("pid" => $req);
                                                
                $requ = $this->fetch_record_with_where("proposal_post_approval", $where);                                                
                foreach($requ as $r){
                    $pid = $r['pa_id'];
                    $where2 = array("id" => $r['pa_request']);
                    $rtime = $this->fetch_record_with_where("post_approval_reqtype",$where2);
                        foreach($rtime as $rti){$timee = $rti['par_desc'];}
                }  
                $maxpid = $this->getmaxpostrequest($prop['sub_id']);
                $maxrevp = $this->getmaxrevperpidandsubid($prop['sub_id'], $maxpid);
                $where = array("ppa_id" => $maxpid, "sub_id" => $prop['sub_id'], "review" => $maxrevp, "phrepuser_id" => $userid);
                $getallassigned = $this->fetch_record_with_where("rev_groupspa", $where);
                foreach($getallassigned as $assigned){
                    if($assigned['confirmation'] == 0){ 
                        
                        $maxxid = $this->getMaxProposalStatus($prop['sub_id']);
                                                        
                        $where11 = array("id" => $maxxid);
                        $getproposals = $this->fetch_record_with_where("proposal_status", $where11);
                        foreach($getproposals as $pppp){
                            if($pppp['status_action'] == '3'){
                                $b = $b + 1;
                            }
                        }
                    }
                    else if($assigned['confirmation'] == 1){   
                        if($assigned['evaluation_submitted'] == 0){
                            
                            $maxxid = $this->getMaxProposalStatus($prop['sub_id']);

                            $where11 = array("id" => $maxxid);
                            $getproposals = $this->fetch_record_with_where("proposal_status", $where11);
                            foreach($getproposals as $pppp){
                                if($pppp['status_action'] == '3'){ 
                                $b = $b + 1;
                                }
                            }
                        }
                    else{
                        $getmaxid = $this->getMaxProposalStatus($assigned['sub_id']);
                        $where = array("id" => $getmaxid);
                        $statf = $this->fetch_record_with_where("proposal_status", $where);
                        foreach($statf as $fstat){
                            if(($fstat['status_action'] == "6")||($fstat['status_action'] == "23")){}
                            else{
                                $b = $b + 1; 
                            }
                        }
                    }
                    }
                }
            } 
            }
        
            $allprop = $this->getallpostapprovalrequest();
            $c = 0;
                foreach($allprop as $prop){
                    if($prop['dp'] != null){                                              
                        $req = $this->getmaxpropapp($prop['sub_id']);
                        $where = array("pid" => $req);
                        $requ = $this->fetch_record_with_where("proposal_post_approval", $where);                                                
                        foreach($requ as $r){
                            $pid = $r['pa_id'];
                            $where2 = array("id" => $r['pa_request']);
                            $rtime = $this->fetch_record_with_where("post_approval_reqtype",$where2);
                            foreach($rtime as $rti){$timee = $rti['par_desc'];}
                        }              
                        $maxpid = $this->getmaxpostrequest($prop['sub_id']);
                        $maxrevp = $this->getmaxrevperpidandsubid($prop['sub_id'], $maxpid);
                        $where = array("ppa_id" => $maxpid, "sub_id" => $prop['sub_id'], "review" => $maxrevp, "phrepuser_id" => $userid);
                        $getallassigned = $this->fetch_record_with_where("rev_groupspa", $where);
                                                
                        foreach($getallassigned as $assigned){
                            $where2 = array("sub_id" => $prop['sub_id']);
                            $proposal = $this->fetch_record_with_where("proposal", $where2);
                                foreach($proposal as $propp){
                                    $where3 = array("id" => $propp['username']);
                                    $getname = $this->fetch_record_with_where("phrepuser", $where3);
                                        foreach($getname as $n){$tname = $n['title'];$fname = $n['fname'];$mname = $n['mname'];$lname = $n['lname'];}
                                }
                                                    
                                if(($assigned['confirmation'] == 1)&&($assigned['evaluation_submitted'] == 1)){  
                                    $getmaxid = $this->getMaxProposalStatus($assigned['sub_id']);
                                    $where = array("id" => $getmaxid);
                                    $statf = $this->fetch_record_with_where("proposal_status", $where);
                                                            
                                    foreach($statf as $fstat){
                                        if($fstat['status_action'] == "23"){
                                            $c = $c + 1;   
                                        }
                                    }
                                }
                        }      
                    } 
                }
        
        return $a+$b+$c;       
    }    
    
    function getifunfinishedform($qtype, $subid, $rev, $form){
        
        $sql = "SELECT COUNT(idq) as qc FROM rev_questions 
        WHERE qtype = '$qtype' AND idq 
        NOT IN (SELECT idq FROM rev_answers WHERE sub_id = '$subid' 
                AND revid = '$rev' AND revform_id = '$form')";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['qc'];
        return $fileid;       
    }
       
    function getifunfinishedformsub($subid, $rev, $form){
        
        $sql = "SELECT COUNT(idq) as cq FROM rev_subanswers WHERE idq IN (1,2,3,4,5,6) AND sub_id = '$subid' AND revid = '$rev' AND revform_id = '$form'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['cq'];
        return $fileid;       
    }    
    
    function checkereval($num, $subid, $revid, $revform){
        
        $sql = "SELECT COUNT(idq) as ccc FROM rev_answers WHERE idq IN $num AND sub_id = '$subid' AND revid = '$revid' AND revform_id = '$revform'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['ccc'];
        return $fileid;         
    }    
    
    function checkwithsub($subid, $revid, $revform){
        $a = 0;
        for($i=1;$i<=6;$i++){
            if($i == 3){
                $where = array("idq" => $i, "sub_id" => $subid, "revid" => $revid, "revform_id" => $revform, "ansdesc" => "No");
                $witsub = $this->fetch_record_with_where("rev_answers", $where);
                if($witsub){
                    $a = $a + 1;
                }
            }
            else{
                $where = array("idq" => $i, "sub_id" => $subid, "revid" => $revid, "revform_id" => $revform, "ansdesc" => "Yes");
                $witsub = $this->fetch_record_with_where("rev_answers", $where);
                if($witsub){
                    $a = $a + 1;
                }
                
            }
        }
     return $a;   
              
    }    
    
    function getallprop($subid){
        
        $sql = "SELECT *, a.status_action as sa FROM proposal_status a
                INNER JOIN(SELECT *, MAX(id) as mid FROM proposal_status GROUP BY sub_id) b ON a.id = b.mid
                INNER JOIN submission c ON a.sub_id = c.sub_id
                INNER JOIN rec_groups d ON c.reclist_id = d.rec_list_id
                INNER JOIN proposal e ON a.sub_id = e.sub_id
                INNER JOIN proposal_status_action f ON a.status_action = f.id
                WHERE d.phrepuser_id = '$subid' AND d.type_id = '1' ORDER BY e.id";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    } 
    
    function reviewedForm($id, $userid){
        
        $sql = "SELECT DISTINCT(revform_id) as rf FROM rev_answers WHERE sub_id = '$id' and revid = '$userid'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    } 
            
    function gettocomment($userid, $subid){
        
        $sql = "SELECT MAX(review) as mxr FROM `rev_groups` where phrepuser_id = '$userid' and sub_id = '$subid'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function gettocommentpa($userid, $subid, $pid){
        
        $sql = "SELECT MAX(review) as mxr FROM `rev_groupspa` where phrepuser_id = '$userid' and sub_id = '$subid' AND ppa_id = '$pid'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function getppaidmax($subid){
        $sql = "SELECT MAX(pid) as pidd FROM proposal_post_approval WHERE subid = '$subid'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['pidd'];
        return $fileid;
    }
    
    function getmaxpspersidsa($subid, $sa){
        $sql = "SELECT MAX(id) as mid  FROM proposal_status WHERE sub_id = '$subid' and status_action = '$sa'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['mid'];
        return $fileid;
    }
            
    function checkformsevaluated($subid, $revid){
        $sql = "SELECT COUNT(DISTINCT(revform_id)) as cform FROM rev_answers WHERE sub_id = '$subid' and revid = '$revid'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['cform'];
        return $fileid;
    }
    
    
    public function encrypt($string, $key){
        
        $string = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
        return $string;
    }
    public function decrypt($string, $key){
        $string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));
        return $string;
    }
    
    function checkreview($subid, $revid){
        $sql = "SELECT * FROM rev_groups WHERE sub_id = '$subid' and phrepuser_id = '$revid'";
                
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array; 
    }
    
    
    function maxsubsitevisit($subid, $ppaid){
        $sql = "SELECT MAX(submission) as sub FROM sitevisit_decision WHERE subid = '$subid' and ppaid = '$ppaid' and final = '0'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['sub'];
        return $fileid;
    }
    
    
    
    function fetchmaxdocsitevisit($subid, $ppaid){
        $sql = "SELECT MAX(revision) as rev FROM document_sitevisit WHERE sub_id = '$subid' and post_approval_type = '$ppaid' and finaldoc = '0'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
    }    
    
    function checkifcommented($userid, $subid, $maxrev){
        
        $sql = "SELECT * FROM rev_comment a 
                INNER JOIN (SELECT MAX(review) as mrv FROM rev_groups WHERE phrepuser_id = '$userid' AND sub_id = '$subid') b ON a.version = b.mrv WHERE a.phrepuser_id = '$userid' and a.version = '$maxrev' and a.sub_id = '$subid'";
                
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function listcomments($userid, $subid){
        
        $sql = "SELECT * FROM rev_comment WHERE sub_id = '$subid' AND phrepuser_id = '$userid'";
                
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }    
    
    function listcommentspa($userid, $subid){
        
        $sql = "SELECT * FROM rev_commentpa WHERE sub_id = '$subid' AND phrepuser_id = '$userid'";
                
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    } 
    
function getMaxValueofppa($ppa) {
        $sql = "SELECT MAX(pid) as mpid FROM `proposal_post_approval` WHERE subid = '$ppa'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['mpid'];
        return $fileid;
}    

function getmaxvalueofcountpercommentrev($id, $subid, $ppaid) {
        $sql = "SELECT MAX(version) as ccm FROM `rev_commentpa` WHERE phrepuser_id = '$id' AND sub_id = '$subid' AND ppa_id = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['ccm'];
        return $fileid;
} 

function getmaxpropapp($id) {
        $sql = "SELECT MAX(pid) as pid FROM proposal_post_approval WHERE subid = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['pid'];
        return $fileid;
}

function listexceptnewcom($id, $subid, $ppaid) {
        $sql = "SELECT MAX(countcom) as ccm FROM `rev_commentpa` WHERE phrepuser_id = '$id' AND sub_id = '$subid' AND ppa_id = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['ccm'];
        return $fileid;
}
    
    function showingUploadedFiless($table1, $table2, $id1, $id2, $id, $username){
        
        $sql = "SELECT * FROM ".$table1." a
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                WHERE a.sub_id = '".$id."' AND a.username = '".$username."' AND a.finaldoc = '1'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
function fetchQuestionsReview($qtype, $except) {
        $sql = "SELECT *  FROM rev_questions WHERE qtype = '$qtype' $except";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function fetchQuestionsReviewspecific($qtype,$idq, $except) {
        $sql = "SELECT *  FROM rev_questions WHERE idq = '$idq' AND qtype = '$qtype' $except";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function fetchquestionsanswered($qtype, $subid, $revid) {
        $sql = "SELECT *, a.idq as aidq FROM rev_questions a 
                LEFT JOIN rev_answers b ON a.idq = b.idq AND b.sub_id = '$subid' AND b.revid = '$revid'
                WHERE a.qtype = '$qtype' order by a.idq";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function fetchReviewer($subid,$userid) {
        $sql = "SELECT * FROM review_type a 
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN rev_groups c ON b.sub_id = c.sub_id
                INNER JOIN phrepuser d ON c.phrepuser_id = d.id
                INNER JOIN membership_users e ON d.username = e.memberID
                INNER JOIN review_type_duedate f ON b.sub_id = f.subid
                WHERE c.sub_id = '$subid' AND c.phrepuser_id = '$userid' AND f.id = (SELECT MAX(id) FROM review_type_duedate WHERE subid = '$subid')";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

    function fetch_record_innerjoin($tables, $join_on, $where) {
        $a = 0; $b = 0; $c = 1;
        $mainTable = $tables[$a];
        $sql = "SELECT * FROM $mainTable"; 
        for($i = 1; $i<count($tables);$i++) {
            $mainTable = $tables[$a];
            $curTable = $tables[$i];
            $joinField1 = $join_on[$b];
            $joinField2 = $join_on[$c];
            $sql.= " INNER JOIN $curTable ON $mainTable.$joinField1 = $curTable.$joinField2";
        $a++; $b=$b+2; $c=$c+2;    
        }
        
        $condition = " WHERE ";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql = $sql.$condition;
//        echo $sql."<br>";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
function fetchAnswerType($atype) {
        $sql = "SELECT atcode FROM `rev_anstype` WHERE `idat` = $atype";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['atcode'];
        return $fileid;
}
    
function getmaxEFdoc($subid){
        $sql = "SELECT MAX(revision) as maxEF  FROM `document` WHERE `sub_id` = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxEF'];
        return $fileid;
}

function getmaxreviewer($id) {
        $sql = "SELECT MAX(review) as rev FROM rev_groups WHERE sub_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}
    
    
    public function fetch_record_with_where($table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
        public function fetch_emailbody_with_where($table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition;
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['body'];
        return $fileid;
    }
    
    function getDatatoSend($subid, $userid){
        
        $sql = "SELECT * FROM submission a 
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN rev_groups c ON b.sub_id = c.sub_id
                INNER JOIN phrepuser d ON c.phrepuser_id = d.id
                INNER JOIN membership_users e ON d.username = e.memberID
                INNER JOIN rec_list f ON a.reclist_id = f.id
                WHERE a.sub_id = '$subid' AND d.id = '$userid'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }    
    
    function checkingUploadFiles($table1, $table2, $id1, $id2, $id3, $id){
        
        $sql = "SELECT * FROM ".$table1." a
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                where a.sub_id = '".$id."' AND b.doctype = '".$id3."'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    public function gettingProposalForConfirmation($table1, $table2, $table3, $table4, $id1, $id2, $id3, $id4, $id5, $id6, $username, $confirmation) {
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
        $sql .= "INNER JOIN ".$table4." d ON c.".$id5." = d.".$id6." ";
        $sql .= "WHERE a.id = '".$username."' AND b.confirmation = '".$confirmation."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    } 
    
    public function gettingProposalForConfirmationss($table1, $table2, $table3, $table4, $id1, $id2, $id3, $id4, $id5, $id6, $username, $confirmation) {
        $sql = "SELECT *, MAX(review) AS rev FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
        $sql .= "INNER JOIN ".$table4." d ON c.".$id5." = d.".$id6." ";
        $sql .= "WHERE a.id = '".$username."' AND b.confirmation = '".$confirmation."' GROUP BY c.sub_id";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function gettingCompleteProposal($userid){
        $sql = "SELECT * FROM  proposal_status a 
                INNER JOIN (SELECT MAX(id) as mid FROM proposal_status GROUP BY sub_id) b ON a.id = mid
                INNER JOIN submission c ON a.sub_id = c.sub_id
                INNER JOIN proposal d ON a.sub_id = d.sub_id
                INNER JOIN rec_groups e ON c.reclist_id = e.rec_list_id
                WHERE e.phrepuser_id = '$userid' AND e.type_id = '1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;   
    }
    
    public function gettingChairman($userid){
        $sql = "SELECT *, c.id as cid FROM phrepuser a 
                INNER JOIN rec_groups b ON a.id = b.phrepuser_id
                INNER JOIN rec_groups_type c ON b.type_id = c.id
                WHERE a.id = '$userid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;   
    }
    
    public function gettingDocToBeConfirmed($userid) {
        $sql = "SELECT * FROM rev_groups a 
                INNER JOIN (SELECT MAX(review) as mxr, sub_id FROM rev_groups GROUP BY sub_id) b ON a.sub_id = b.sub_id
                INNER JOIN (SELECT * FROM proposal_status x 
                                INNER JOIN (SELECT MAX(id) as mid, sub_id as sid FROM proposal_status GROUP BY sub_id) y ON x.id = y.mid) c ON c.sid = a.sub_id
                INNER JOIN phrepuser d ON a.phrepuser_id = d.id
                INNER JOIN proposal e ON a.sub_id = e.sub_id
                INNER JOIN review_type f ON a.sub_id = f.sub_id
                INNER JOIN (SELECT * FROM review_type_duedate t 
                        INNER JOIN (SELECT MAX(id) as iddd FROM review_type_duedate GROUP BY subid) w ON t.id = w.iddd) g ON a.sub_id = g.subid
                WHERE a.phrepuser_id = '$userid' AND a.confirmation = '0' AND a.evaluation_type = '0' AND a.evaluation_submitted = '0' AND a.decision = '0' AND a.eval_date = '0'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    function getmaxcol($subid){
        
        $sql = "SELECT MAX(rev) as maxcol FROM collated_suggestion WHERE sub_id = '$subid'";
        
	$result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxcol'];
        return $fileid;          
    }   
    
    function getsuggestioncol($subid,$rev){
        
        $sql = "SELECT * FROM collated_suggestion WHERE sub_id = '$subid' AND rev = '$rev'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
            
    function getpassrev($subid, $userid){
        
        $sql = "SELECT * FROM rev_answers a 
                INNER JOIN phrepuser b ON a.revid = b.id
                INNER JOIN reviewform_type c ON a.revform_id = c.rft_id
                WHERE a.sub_id = '$subid' AND b.id = '$userid' GROUP BY a.sub_id,a.revform_id";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    public function gettingConfirmedDoc($userid) {
        $sql = "SELECT *  FROM rev_groups a
                INNER JOIN (SELECT sub_id, MAX(id) as mid FROM rev_groups WHERE phrepuser_id = '$userid' GROUP BY sub_id) b ON b.mid = a.id
                INNER JOIN phrepuser c ON a.phrepuser_id = c.id
                INNER JOIN proposal d ON a.sub_id = d.sub_id
                INNER JOIN review_type e ON d.sub_id = e.sub_id
                INNER JOIN review_type_list g ON e.rt_id = g.id
                INNER JOIN (SELECT * FROM review_type_duedate a 
                            INNER JOIN (SELECT MAX(id) as iddd FROM review_type_duedate GROUP BY subid) b ON a.id = b.iddd) f ON f.subid = d.sub_id
                WHERE a.confirmation = '1' AND a.phrepuser_id = '$userid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getallpostapprovalrequest() {
        $sql = "SELECT sub_id, d.dpid as dp FROM `proposal` a 
                LEFT JOIN (SELECT DISTINCT(subid) as dpid FROM proposal_post_approval) d ON a.sub_id = d.dpid";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    } 
    
    function getmaxpost($subid){
        $sql = "SELECT MAX(post_request_times) as pr  FROM `document` WHERE `sub_id` = '".$subid."' AND post_finaldoc = '1'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $pr = $row['pr'];
        return $pr;
        
}

    function getmaxpostrequest($subid){
        $sql = "SELECT MAX(pid) as pid  FROM `proposal_post_approval` WHERE `subid` = '".$subid."'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $pr = $row['pid'];
        return $pr;
        
}

    function getmaxrevperpidandsubid($subid, $ppaid){
        $sql = "SELECT MAX(review) as rev  FROM `rev_groupspa` WHERE `sub_id` = '".$subid."' AND ppa_id = '".$ppaid."'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $pr = $row['rev'];
        return $pr;
        
}

    public function getpostdoc($subid, $getmaxpostdoc) {
        $sql = "SELECT * FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.sub_id = '$subid' and a.post_request_times = '$getmaxpostdoc' and a.post_finaldoc = '1' order by a.date_uploaded";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    } 
    
    public function getpostapp($subid) {
        $sql = "SELECT count(subid) as pr FROM proposal_post_approval WHERE subid = '$subid' AND pa_status = 'onreview'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $pr = $row['pr'];
        return $pr;      
    }
    
    public function getReviewFiles($rev, $subid, $id, $confirmation) {
        $sql = "SELECT * FROM phrepuser a 
                INNER JOIN rev_groups b ON a.id = b.phrepuser_id
                INNER JOIN proposal c ON b.sub_id = c.sub_id 
                INNER JOIN review_type d ON c.sub_id = d.sub_id
                WHERE a.id = '$id' AND b.confirmation = '$confirmation' AND c.sub_id = '$subid' AND b.review = '$rev'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function update_record($table, $where, $fields) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        foreach ($fields as $key => $value) {
            $sql .= $key . "='" . $value . "', ";
        }
        $sql = substr($sql, 0, -2);
        $sql = "UPDATE " . $table . " SET " . $sql . " WHERE " . $condition;
//        echo $sql;
        if (mysqli_query($this->con, $sql)) {
            return true;
        }
    }
    public function human_filesize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
    
    function getAssignedProposalForReview($result){
        $keys = array_keys($result);
        $fields = array_values($result);
        $sql = "SELECT * FROM ".$fields['0']." ".$keys['0']." 
                    INNER JOIN ".$fields['1']." ".$keys['1']." ON ".$keys['0'].".".$fields['5']." = ".$keys['1'].".".$fields['5']."
                    INNER JOIN ".$fields['2']." ".$keys['2']." ON ".$keys['1'].".".$fields['6']." = ".$keys['2'].".".$fields['7']."
                    INNER JOIN ".$fields['3']." ".$keys['3']." ON ".$keys['1'].".".$fields['5']." = ".$keys['3'].".".$fields['5']."
                    INNER JOIN ".$fields['4']." ".$keys['4']." ON ".$keys['3'].".".$fields['8']." = ".$keys['4'].".".$fields['9']."
                    WHERE ".$keys['3'].".".$fields['5']." = '".$fields['10']."' AND ".$keys['2'].".".$keys['11']." = '".$fields['11']."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array; 
    }
 

function getMaxRevision($subid){
        $sql = "SELECT MAX(revision) as maxrev  FROM `document` WHERE `sub_id` = '".$subid."' AND kind = 'MP'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrev'];
        return $fileid;
        
}

function getmaxbatch($id) {
        $sql = "SELECT MAX(batchnum) as bat FROM document WHERE sub_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getmaxbatchpa($id, $ppaid) {
        $sql = "SELECT MAX(batchnum) as bat FROM document_postapproval WHERE sub_id = '$id' AND post_approval_type = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getdocbybatch($id, $i) {
        $sql = "SELECT * FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.batchnum = '$i' AND a.sub_id = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}
function getdocbybatchpa($id, $i, $ppaid) {
        $sql = "SELECT * FROM document_postapproval a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.batchnum = '$i' AND a.sub_id = '$id' AND a.post_approval_type = '$ppaid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getrlifdonesubmitfiles($subid){
        $sql = "SELECT MAX(finaldoc) as rl FROM `document` where kind = 'RL' and sub_id = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $rl = $row['rl'];
        return $rl;
}
function getURL(){
//        $host= gethostname(); 
//        $ip = gethostbyname($host); 
//        $server = $ip.'/phrep'; 
        $server = 'http://122.53.108.130/phrep'; 
    
        return $server;
}

    function showingUploadedFilesforAmendment($table1, $table2, $id1, $id2, $id, $i){
        
        $sql = "SELECT * FROM $table1 a 
                INNER JOIN $table2 b ON a.$id1 = b.$id2 
                WHERE a.sub_id = '$id' and a.revision = '$i' and a.kind IN ('MP', 'SF')";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function ordinalize($num) {
        $suff = 'th';
        if ( ! in_array(($num % 100), array(11,12,13))){
            switch ($num % 10) {
                case 1:  $suff = 'st'; break;
                case 2:  $suff = 'nd'; break;
                case 3:  $suff = 'rd'; break;
            }
            return "{$num}{$suff}";
        }
        return "{$num}{$suff}";
    }
    
    function exclusiveForAssignedProposal($username, $subid, $maxrev){
        $sql = "SELECT *, c.username as pi, e.id as rvid FROM phrepuser a 
                INNER JOIN rev_groups b ON a.id = b.phrepuser_id
                INNER JOIN proposal c ON b.sub_id = c.sub_id
                INNER JOIN review_type d ON c.sub_id = d.sub_id
                INNER JOIN (SELECT * FROM review_type_duedate a 
                            INNER JOIN (SELECT MAX(id) as iddd FROM review_type_duedate GROUP BY subid) b ON a.id = b.iddd) f ON f.subid = c.sub_id
                INNER JOIN review_type_list e ON d.rt_id = e.id
                WHERE a.id = '".$username."' AND b.sub_id = '".$subid."' AND b.review = '".$maxrev."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
    function passedEvaluation($username, $subid, $maxrev){
        $sql = "SELECT * FROM rev_groups a 
                INNER JOIN phrepuser b ON a.phrepuser_id = b.id
                WHERE a.sub_id = '".$subid."' AND b.id = '".$username."' AND a.review = '$maxrev'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
        function gettingEvaluationQuestion($username){
        $sql = "SELECT *, d.id AS qid FROM phrepuser a 
                INNER JOIN rec_groups b ON a.id = b.phrepuser_id
                INNER JOIN rec_forms c ON b.rec_list_id = c.rec_list_id
                INNER JOIN rec_question_eval d ON c.id = d.form_id
                WHERE a.username = '".$username."' AND d.question_dependent_to = '0'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
        function gettingEvaluationAnswers($questionId){
        $sql = "SELECT *, c.id AS ansid FROM rec_question_eval a 
                INNER JOIN rec_evaluation_form b ON a.id = b.question_id
                INNER JOIN rec_answer_kind c ON b.answer_id = c.id
                WHERE a.id = '".$questionId."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
        function getPropStatus($subid, $id){
        $sql = "SELECT *  FROM `proposal_status` WHERE `id` = '$id' AND `sub_id` = '$subid'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
        function getMaxProposalStatus($subid){
        $sql = "SELECT MAX(id) as maxstatid FROM `proposal_status` WHERE sub_id = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxstatid'];
        return $fileid;
    }
    
    
        function getifApproved($subid){
        $sql = "SELECT *, id as pid FROM proposal_status
                WHERE status_action IN ('6') AND sub_id = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['pid'];
        return $fileid;
        
    }    
    
        function gettingSubQuestions($questionId){
        $sql = "SELECT * FROM rec_question_eval a 
                INNER JOIN rec_evaluation_form b ON a.id = b.question_id
                INNER JOIN rec_answer_kind c ON b.answer_id = c.id
                WHERE a.question_dependent_to = '".$questionId."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
    function getsuggestrevtype($revtype){
        $sql = "SELECT * FROM review_type_list 
                WHERE id not in ('$revtype')";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }    
    
    function getSuggested($userid, $id){
        $sql = "SELECT * FROM `review_suggest` a
                INNER JOIN review_type_list b ON a.rev_suggest = b.id
                WHERE a.reviewer = '$userid' AND subid = '$id'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    } 
    
    function getSecretary($id){
        $sql = "SELECT * FROM phrepuser a 
                INNER JOIN membership_users b ON a.username = b.memberID
                WHERE a.id = '$id'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
    public function delete_record($table,$where){
            $sql = "";
            $condition = "";
            foreach ($where as $key => $value){
                $condition .= $key . "='". $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            $sql = "DELETE FROM ".$table." WHERE ".$condition;
//            echo $sql;
            if(mysqli_query($this->con,$sql)){
                return true;
            }
        }
        
    public function delete_recordnot($subid,$revid){
        $sql = "DELETE FROM rev_subanswers WHERE idq IN (1,2,3,4,5,6) AND sub_id = '$subid' AND revid = '$revid'";
        if(mysqli_query($this->con,$sql)){
                return true;
        }
    }
    
    function countSuppUploaded($table1, $table2, $table3, $id1, $id2, $id3, $id4, $id5, $id6, $id, $username){
        
        $sql = "SELECT * FROM ".$table1." a 
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                WHERE a.".$id4." = '".$id."' AND b.".$id3." NOT IN 
                (SELECT c.".$id3." FROM ".$table3." c WHERE c.".$id4." = '".$id."' and c.".$id5." = '".$username."' AND c.".$id6." = 'SF')";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    function get_data_joined_two($userid){
        $sql= "SELECT * FROM proposal a 
                INNER JOIN phrepuser b ON a.username = b.id
                INNER JOIN membership_users c ON b.username = c.memberID
                WHERE a.sub_id = '$userid'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function get_confirmation_info($table, $id, $username){
        $sql = "SELECT * FROM ".$table." WHERE sub_id='".$id."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function get_confirmation_joining_two($table1, $table2, $table3, $id, $username, $fid){
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.sub_id = b.sub_id ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$fid." = c.id ";
        $sql .= "WHERE a.sub_id = ".$id."";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function get_date_duration($table, $id, $username){
        $sql= "SELECT * FROM ".$table." WHERE sub_id = ".$id."";
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }  
    
    function checkifDonereview($id, $userid, $form){
        $sql= "SELECT count(revform_id) as rfid FROM rev_answers WHERE sub_id = '".$id."' AND revid = '".$userid."' AND revform_id = '".$form."'";
	$result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rfid'];
        return $fileid;
    }
    
    function get_confirmation_joining_one($table1, $table2, $field1, $field2, $id, $username){
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$field1." = b.".$field2." ";
        $sql .= "WHERE a.sub_id = ".$id."";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function gettingMonetarylist($id, $userid){
        $sql = "SELECT * FROM proposal a 
                INNER JOIN monetary_source b ON a.sub_id = b.sub_id
                WHERE a.sub_id = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }   
    
    function get_confirmation_joining_two_for_assess($table1, $table2, $table3, $id, $fid, $partid){
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.sub_id = b.sub_id ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$fid." = c.id ";
        $sql .= "WHERE a.sub_id = '$id'";
        $sql .= "AND c.part_id = '$partid'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    function upload_info($table, $fields, $id, $doctype, $useurl, $revision, $puserid) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '".$doctype."' AND revision = '".$revision."' AND username = '".$puserid."'";
        $qry = mysqli_query($this->con,$sql);
        $row = mysqli_num_rows($qry);
//        echo $row;
            if($row > 0){ 
                $sql = "UPDATE ".$table." SET ";
                $cnt = count($fields);
                for($i=2; $i<$cnt; $i++){
                    $val = array_values($fields);
                    $key = array_keys($fields);
                    $sql .= $key[$i]. " = '" .$val[$i]. "', ";
                }
                $sql = substr($sql, 0, -2);
                $sql .= " WHERE sub_id = ".$val['1']." AND doctype = '".$doctype."' AND revision = '".$revision."' AND username = '".$puserid."'";
//                echo $sql ; 
                $query = mysqli_query($this->con,$sql);
                if($query){                
                     header("location:".$useurl."?id=".$id);
                        }
            }
            else{
                $sql = "";
                $sql .= "INSERT INTO ".$table;
                $sql .= " (".implode(",", array_keys($fields)).") VALUES ";
                $sql .= " ('".implode("','", array_values($fields))."')"; 
                $query = mysqli_query($this->con,$sql);
                    if($query){                
                     header("location:".$useurl."?id=".$id);
                        }
            }
        
    }
    
function sendEmail3($emailto, $body, $subject) {
        //notifyMemberApproval($memberID);
        $currDir = dirname(__FILE__);
        require "{$currDir}/mailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;
        // var_dump(PHPMailer::validateAddress($emailto));

        //$mail->SMTPDebug = 3;                                                 // Enable verbose debug output

                    $mail->isSendmail();                                                       // Set mailer to use SMTP
//                    $mail->Host = 'smtp-relay.gmail.com';                                   // Specify main and backup SMTP servers
//                    $mail->SMTPAuth = false;                                                // Enable SMTP authentication
//                    $mail->Username = '';                                                   // SMTP username
//                    $mail->Password = '';                                                   // SMTP password
//                    $mail->SMTPSecure = 'ssl';                                              // Enable TLS encryption, `ssl` also accepted
//                    $mail->Port = '465';                                                      // TCP port to connect to
//                    $mail->SMTPOptions = array(
//                                'ssl' => array(
//                                    'verify_peer' => false,
//                                    'verify_peer_name' => false,
//                                    'allow_self_signed' => true
//                                )
//                            );
//        $emailtoo = filter_var($emailto, FILTER_VALIDATE_EMAIL);
        $mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP');           // WHO SENT
        $mail->addAddress($emailto);                                        // Name is optional //SENT TO
        $mail->addReplyTo('citdsadmin@pchrd.dost.gov.ph', 'PHREP');        // WHERE TO REPLY
        // $mail->addCC('');                                                       // CC
        $mail->addBCC('hbcornea@pchrd.dost.gov.ph','dcaguila@pchrd.dost.gov.ph');                                                      //BCC

        // $mail->addAttachment('');                                               // Add attachments /var/tmp/file.tar.gz
        // $mail->addAttachment('');                                               // Optional name /tmp/image.jpg', 'new.jpg
        $mail->isHTML(true);                                                    // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = '';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return TRUE;
        }
    } 
    
function sendEmail($emailto, $body, $subject) {
        //notifyMemberApproval($memberID);

        $currDir = dirname(__FILE__);
        require "{$currDir}/mailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;
        // var_dump(PHPMailer::validateAddress($emailto));

        //$mail->SMTPDebug = 3;                                                 // Enable verbose debug output

                    $mail->isSendmail();                                                       // Set mailer to use SMTP
//                    $mail->Host = 'smtp-relay.gmail.com';                                   // Specify main and backup SMTP servers
//                    $mail->SMTPAuth = false;                                                // Enable SMTP authentication
//                    $mail->Username = '';                                                   // SMTP username
//                    $mail->Password = '';                                                   // SMTP password
//                    $mail->SMTPSecure = 'ssl';                                              // Enable TLS encryption, `ssl` also accepted
//                    $mail->Port = '465';                                                      // TCP port to connect to
//                    $mail->SMTPOptions = array(
//                                'ssl' => array(
//                                    'verify_peer' => false,
//                                    'verify_peer_name' => false,
//                                    'allow_self_signed' => true
//                                )
//                            );                                                      // TCP port to connect to
//        $emailtoo = filter_var($emailto, FILTER_VALIDATE_EMAIL);
        $mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP');           // WHO SENT
        $mail->addAddress($emailto);                                        // Name is optional //SENT TO
        $mail->addReplyTo('nationalethicscommittee.ph@gmail.com', 'NEC');        // WHERE TO REPLY
        // $mail->addCC('');                                                       // CC
        // $mail->addBCC('');                                                      //BCC

        // $mail->addAttachment('');                                               // Add attachments /var/tmp/file.tar.gz
        // $mail->addAttachment('');                                               // Optional name /tmp/image.jpg', 'new.jpg
        $mail->isHTML(true);                                                    // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = '';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return TRUE;
        }
    }
    public function getproposalinfo($id) {
        $sql = "SELECT * FROM proposal a 
                INNER JOIN phrepuser b on a.username = b.id
                INNER JOIN review_type c on a.sub_id = c.sub_id
                INNER JOIN (SELECT * FROM review_type_duedate a 
                            INNER JOIN (SELECT MAX(id) as iddd FROM review_type_duedate GROUP BY subid) b ON a.id = b.iddd) d ON d.subid = a.sub_id
                WHERE a.sub_id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
function getMaxRevisionEC($subid, $username){
        $sql = "SELECT MAX(revision) as maxrevec  FROM `document_sitevisit` WHERE `sub_id` = '".$subid."' AND kind = 'SVD' and username = '$username'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrevec'];
        return $fileid;
}


















// --------------------------------------------------
// added by JM

function getQuestions($qtype, $subid, $revid){

        $sql = "SELECT *, a.idq as aidq FROM rev_questions a 
                LEFT JOIN rev_answers b ON a.idq = b.idq AND b.sub_id = '$subid' AND b.revid = '$revid'
                WHERE a.qtype = '$qtype' order by a.idq";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}


function getifunfinishedformsub_newversion($subid, $rev, $form){

    $sql = "SELECT COUNT(idq) as cq FROM rev_subanswers WHERE idq >= 29 AND idq <= 46 AND sub_id = '$subid' AND revid = '$rev' AND revform_id = '$form'";

    $result = mysqli_query($this->con,$sql);
    $row = mysqli_fetch_array($result);

    $fileid = $row['cq'];
    return $fileid;       
}


 function checkwithsub_newversion($subid, $revid, $revform){
        $a = 0;
        for($i=29;$i<=46;$i++){  
            if($i == 3){
                $where = array("idq" => $i, "sub_id" => $subid, "revid" => $revid, "revform_id" => $revform, "ansdesc" => "No");
                $witsub = $this->fetch_record_with_where("rev_answers", $where);
                if($witsub){
                    $a = $a + 1;
                }
            }
            else{
                $where = array("idq" => $i, "sub_id" => $subid, "revid" => $revid, "revform_id" => $revform, "ansdesc" => "Yes");
                $witsub = $this->fetch_record_with_where("rev_answers", $where);
                if($witsub){
                    $a = $a + 1;
                }
                
            }
        }
     return $a;   
              
    }        

 function getifunfinishedform_newversion($qtype, $subid, $rev, $form){
        
        $sql = "SELECT COUNT(idq) as qc FROM rev_questions WHERE qtype = '$qtype' AND idq NOT IN (SELECT idq FROM rev_answers WHERE sub_id = '$subid' AND revid = '$rev' AND revform_id = '$form')";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['qc'];
        return $fileid;       
    }



















    
}

$obj = new UploadOperation();

date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));


if(isset($_FILES['image'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
//            echo $useurl;
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
//            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $resdoctype = "EvaluationForm";
            $rename_filename = $id."-".$date."-".$times."-$resdoctype.".end($temp);
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            //move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $doctypefinal = $doctype;
//            print_r($doctypefinal);
            $revision = $_POST['maxef'];
            
            $uploadinfor = array(
            "file_id" => "",
            "sub_id" => $id,
            "revision" => $revision,
            "finaldoc" => "1",
            "file_name" => $rename_filename,
            "orig_filename" => $file_name,
            "file_type" => $file_type,
            "file_size" => $file_size,
            "date_uploaded" => $datetime,
            "date_modified" =>$datetime,
            "kind" => $_POST['kind'],
            "doctype" => $doctype,
            "username" => $_POST['userid']
            );  
//            print_r($uploadinfor);
         if($obj->upload_info("document", $uploadinfor, $id, $doctypefinal, $useurl, $revision, $_POST['userid'])){
        }
        }
        else{
            echo "FAILED";
            echo "</p>";
            echo '<pre>';
            echo 'Here is some more debugging info:';
            print_r($_FILES);
            print "</pre>"; exit;
            }
      }else{
         print_r($errors);
      }
   }
   
   
//if (isset($_GET['passevaluation'])) {
//    if (isset($_GET['id'])) {
//        if(isset($_GET['u'])){
//            $id = $_GET['id'];
//            $u = $_GET['u'];
//            $update = array("evaluation_submitted"=>"1", "decision"=> $_POST['decision']);
//            $where = array("sub_id"=>$id, "phrepuser_id"=>$u);
//            if($obj->update_record("rev_groups",$where, $update)){
//                header("location:rev_dashboard.php");
//            }
//        }
//    }
//}

if(isset($_POST['passevaluation'])){
    $id = $_POST['submid'];
    $userid = $_POST['userid'];
    $maxEF = $_POST['maxef'];
    
    $update = array("evaluation_submitted"=>"1", "decision"=> $_POST['decision']);
    $where = array("sub_id"=>$id, "phrepuser_id"=>$userid, "review"=>$maxEF);
    print_r($where);
    print_r($update);
    
    if($obj->update_record("rev_groups",$where, $update)){
        header("location:rev_dashboard.php#review");
    }
    
}

if (isset($_GET['confirm'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['u'])) {
            $id = $_GET['id'];
            $u = $_GET['u'];

            $update = array("confirmation" => "1");
            $where = array("sub_id" => $id, "phrepuser_id" => $u);
            if ($obj->update_record("rev_groups", $where, $update)) {
                header("location:rev_dashboard.php#new");
            }

//            $insert = array("sub_id" => $id, "status_action" => "3", "status_date" => $datetime, "status_username" => $u);
//            if ($obj->insert_record("proposal_status", $insert)) {
//                header("location:rev_dashboard.php#review");
//            }
        }
    }
}

if (isset($_GET['upd'])) {
    if (isset($_GET['p'])) {
        if (isset($_GET['r'])) {
            if (isset($_GET['s'])) {
                if (isset($_GET['u'])) {
                    $p = $_GET['p'];
                    $r = $_GET['r'];
                    $s = $_GET['s'];
                    $u = $_GET['u'];

                    $update = array("confirmation" => "1");
                    $where = array("ppa_id" => $p, "sub_id" => $s, "review" => $r, "phrepuser_id" => $u);
                    if ($obj->update_record("rev_groupspa", $where, $update)) {
                        header("location:rev_dashboard.php#approved");
                    }                    
                }                
            }
        }
    }
}

if (isset($_GET['confirm'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['u'])) {
            $id = $_GET['id'];
            $u = $_GET['u'];
            
            $getdata = $obj->fetchReviewer($id,$u);
            if($getdata){
                foreach($getdata as $dt){
                    $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle'],date('F d, Y',strtotime($dt['rt_duedate'])));
//                    print_r($change);
                }  
            }
            $table1 = array("proposal", "submission", "rec_list", "phrepuser", "membership_users");
            $join_on1 = array("sub_id", "sub_id", "reclist_id", "id", "secretary", "id", "username", "memberID");
            $where1 = array("proposal.sub_id" => $id);
            $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
            if($getdata1){
                foreach($getdata1 as $dt1){
                    $secemail = $dt1['email'];
                    array_push($change,$dt1['title']." ".$dt1['lname'], $dt1['erc_name']);
                    print_r($change); echo "<br>";
                }
            }
            
            $where3 = array("id" => "6");
            $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
            foreach ($getTemplate as $tplate) {
                $subject = $tplate['subject'];
                $template = $tplate['body'];
            }
     
            $find = array("{reviewerName}","{proposalTitle}","{reviewDueDate}","{secretariatName}","{recname}"); 
            $readytosend = str_replace($find, $change, $template);

            if($obj->sendEmail3($secemail, $readytosend, $subject)){                 
            }
            
        }
    }
}

if (isset($_GET['decline'])) {
  
    if (isset($_GET['id'])) {
        if(isset($_GET['u'])){
        $id = $_GET['id'];
        $u = $_GET['u'];
                
        $update = array("confirmation"=> "2");
        $where = array("sub_id"=>$id, "phrepuser_id"=>$u);
        if($obj->update_record("rev_groups",$where,$update)){
            header("location:rev_dashboard.php");
        }

        // jm : code
        // $sql = "DELETE FROM rev_groups WHERE  sub_id='".$id."' AND phrepuser_id= '".$u."'";
        // $query = mysqli_query($connect, $sql);
        
        // return header("location:rev_dashboard.php");
        }

    }
}

if(isset($_POST['suggest'])){
    $suggest = $_POST['suggested'];
    $suggested = explode(',', $suggest);
    $reviewer = $_POST['reviewer'];
    $subid = $_POST['subid'];
    $datesuggest = $datetime;
    
    $suggestedna = array("reviewer" => $reviewer, "subid" => $subid, "rev_suggest" => $suggested['0'], "datesuggested" => $datesuggest);
    
//    print_r($suggest);
    
    if($obj->insert_record("review_suggest",$suggestedna)){
        header("location:reviewproposal.php?id=".$subid);
    }
    
    
}
if(isset($_POST['suggest'])){//send email
    $subid = $_POST['subid'];
    $emailto = $_POST['secemail'];
    $subject = $_POST['subject'];
    $suggest = $_POST['suggested'];
    $suggested = explode(',', $suggest);
    $rec = $_POST['rec'];
    $ptitle = $_POST['ptitle'];
    $fullname = $_POST['fullname'];
    $template = $_POST['templateSuggest'];
    
    $find = array("{subject}","{REC}","{proposalTitle}","{reviewType}","{nameofSender}");
    $change = array($subject,$rec,$ptitle,$suggested['1'],$fullname);
    $readytosend = str_replace($find, $change, $template);
    
    if($obj->sendEmail($emailto, $readytosend, $subject)){
        header("location:reviewproposal.php?id=".$subid);
    }
    
}
if (isset($_GET['deletesug'])) {
    if (isset($_GET['id'])) {
        if(isset($_GET['u'])){
        $id = $_GET['id'];
        $u = $_GET['u'];
                
        $where = array("reviewer" => $u, "subid" => $id);
        if($obj->delete_record("review_suggest", $where)){
            header("location:reviewproposal.php?id=".$id);
        }
        }
    }

}

if(isset($_POST['submitreview'])){
//    echo '<pre>';var_dump($_POST);echo '</echo>';exit;
    $where = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "revform_id" => "1");
    $asa = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "revform_id" => "1");
    
    if(($obj->delete_record("rev_answers", $where))&&($obj->delete_record("rev_subanswers", $asa))){
        
        for($i=1; $i<=11; $i++){
        $q = $_POST['inlineRadioOptions'.$i];
        $s = explode(",", $q);
        $ans = array(
            "idq" => $s['0'],
            "ansdesc" => $s['1'],
            "sub_id" => $_POST['subid'],
            "revid" => $_POST['revid'],
            "revform_id" => '1'
        );
//        print_r($ans); echo "<br>";
        if($obj->insert_record("rev_answers", $ans)){
//        header("location:sec_register_reviewer.php");
        } 
    }
        
    $concerns = array(
        "idq" => "12",
        "ansdesc" => $_POST['text12'],
        "sub_id" => $_POST['subid'],
        "revid" => $_POST['revid'],
        "revform_id" => '1'
    );
        if($obj->insert_record("rev_answers", $concerns)){
//        header("location:sec_register_reviewer.php");
        } 
//    print_r($ans);
    
//    echo "<pre>"; var_dump($_POST); echo "</pre>";
    
    for($i=1; $i<=6; $i++){
//        $t = $_POST['text'. $i'];
        if(isset($_POST['text'. $i])) {
            if($_POST['text'. $i] == ""){
//                echo 'NULL';
            }
            else{
                $anst = array(
                    "idq" => $i,
                    "subansdesc" => $_POST['text'. $i],
                    "sub_id" => $_POST['subid'],
                    "revid" => $_POST['revid'],
                    "revform_id" => '1'
                );
                if($obj->insert_record("rev_subanswers", $anst)){
        //        header("location:sec_register_reviewer.php");
                } 
            }
        }
//        print_r($t);
//    print_r($anst); echo "<br>";
    }
    header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
    
    //RECOMMENDATION
    
//    $update = array("evaluation_type"=>$_POST['evaltype'] ,"evaluation_submitted"=>"1", "decision"=> $_POST['recommendation'], "eval_date" => $_POST['evaldate']);
//    $where = array("sub_id"=>$_POST['subid'], "phrepuser_id"=>$_POST['revid'], "review"=>$_POST['maxef']);
//    
//    if($obj->update_record("rev_groups",$where, $update)){
//        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
//    }
    
    
//    exit;
        
    }
}



if(isset($_POST['savereview-newversion'])){
    
    $where = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "revform_id" => "3");
    $asa = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "revform_id" => "3");

    if(($obj->delete_record("rev_answers", $where))&&($obj->delete_record("rev_subanswers", $asa))){
       for($i=29; $i<=46; $i++){
          $q = $_POST['inlineRadioOptions'.$i];
        //        echo $q;
          if($q != ''){
            $s = explode(",", $q);
            $ans = array(
                "idq" => $s['0'],
                "ansdesc" => $s['1'],
                "sub_id" => $_POST['subid'],
                "revid" => $_POST['revid'],
                "revform_id" => '3'
            );
        //            print_r($ans); echo "<br>";
            if($obj->insert_record("rev_answers", $ans)){
               header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
           } 
       }
   }

    if($_POST['text46'] != ''){
    $concerns = array(
        "idq" => "46",
        "ansdesc" => $_POST['text46'],
        "sub_id" => $_POST['subid'],
        "revid" => $_POST['revid'],
        "revform_id" => '3'
    );
        if($obj->insert_record("rev_answers", $concerns)){
        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
        } 
//    print_r($concerns);
        
    }    


   for($i=29; $i<=46; $i++){
//        $t = $_POST['text'. $i'];
    if(isset($_POST['text'. $i])) {
        if($_POST['text'. $i] == ""){
               // echo 'xxx';
        }
        else{
            $anst = array(
                "idq" => $i,
                "subansdesc" => $_POST['text'. $i],
                "sub_id" => $_POST['subid'],
                "revid" => $_POST['revid'],
                "revform_id" => '3'
            );
            if($obj->insert_record("rev_subanswers", $anst)){
                header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
            } 
//            echo "<br>"; print_r($anst);                
        }
    }
//        print_r($t);
//    print_r($anst); echo "<br>";
}

}
}









if(isset($_POST['savereview'])){
//    echo '<pre>';var_dump($_POST);echo '</echo>';exit;
    $where = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "revform_id" => "1");
    $asa = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "revform_id" => "1");
    
    if(($obj->delete_record("rev_answers", $where))&&($obj->delete_record("rev_subanswers", $asa))){
    
        for($i=1; $i<=11; $i++){
        $q = $_POST['inlineRadioOptions'.$i];
//        echo $q;
        if($q != ''){
            $s = explode(",", $q);
            $ans = array(
                "idq" => $s['0'],
                "ansdesc" => $s['1'],
                "sub_id" => $_POST['subid'],
                "revid" => $_POST['revid'],
                "revform_id" => '1'
            );
//            print_r($ans); echo "<br>";
            if($obj->insert_record("rev_answers", $ans)){
           header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
            } 
        }
            
    }
    if($_POST['text12'] != ''){
    $concerns = array(
        "idq" => "12",
        "ansdesc" => $_POST['text12'],
        "sub_id" => $_POST['subid'],
        "revid" => $_POST['revid'],
        "revform_id" => '1'
    );
        if($obj->insert_record("rev_answers", $concerns)){
        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
        } 
//    print_r($concerns);
        
    }    
//    
////    echo "<pre>"; var_dump($_POST); echo "</pre>";
//    
    for($i=1; $i<=6; $i++){
//        $t = $_POST['text'. $i'];
        if(isset($_POST['text'. $i])) {
            if($_POST['text'. $i] == ""){
               // echo 'xxx';
            }
            else{
                $anst = array(
                    "idq" => $i,
                    "subansdesc" => $_POST['text'. $i],
                    "sub_id" => $_POST['subid'],
                    "revid" => $_POST['revid'],
                    "revform_id" => '1'
                );
                if($obj->insert_record("rev_subanswers", $anst)){
                header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
                } 
//            echo "<br>"; print_r($anst);                
            }
        }
//        print_r($t);
//    print_r($anst); echo "<br>";
    }
//    
//    //RECOMMENDATION
//    
//    $update = array("evaluation_type"=>$_POST['evaltype'] ,"evaluation_submitted"=>"1", "decision"=> $_POST['recommendation'], "eval_date" => $_POST['evaldate']);
//    $where = array("sub_id"=>$_POST['subid'], "phrepuser_id"=>$_POST['revid'], "review"=>$_POST['maxef']);
////    print_r($where);
////    print_r($update);
//    
//    if($obj->update_record("rev_groups",$where, $update)){
//        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
//    }
    
    
//    exit;
        
        
    }
}

if(isset($_POST['savereviewconsent'])){
//    echo '<pre>';var_dump($_POST);echo '</echo>';exit;
    $where = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "revform_id" => "2");
    $asa = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "idq" => "13");
    
    if(($obj->delete_record("rev_answers", $where))&&($obj->delete_record("rev_subanswers", $asa))){
        for($i=13; $i<=27; $i++){
        $q = $_POST['inlineRadioOptions'.$i];
        if(isset($q) || !empty($q)){ 
        $s = explode(",", $q);
            $ans = array(
                "idq" => $s['0'],
                "ansdesc" => $s['1'],
                "sub_id" => $_POST['subid'],
                "revid" => $_POST['revid'],
                "revform_id" => "2"
            );
            
        if($obj->insert_record("rev_answers", $ans)){
        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
        }
//         print_r($ans); echo "<br>";         
        }
    } #exit;
    if($_POST['text13']){
        $nouna = array(
        "idq" => "13",
        "subansdesc" => $_POST['text13'],
        "sub_id" => $_POST['subid'],
        "revid" => $_POST['revid'],
        "revform_id" => '2'
        );
//        print_r($nouna); echo "<br>";    
        if($obj->insert_record("rev_subanswers", $nouna)){
        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
        }      
    }
    if($_POST['text28']){        
        $concerns = array(
        "idq" => "28",
        "ansdesc" => $_POST['text28'],
        "sub_id" => $_POST['subid'],
        "revid" => $_POST['revid'],
        "revform_id" => '2'
    );
//        print_r($concerns); exit;
        if($obj->insert_record("rev_answers", $concerns)){
        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
        } 
    }
//    print_r($ans);
//    
    //RECOMMENDATION
//    $update = array("evaluation_type"=>$_POST['evaltype'] ,"evaluation_submitted"=>"1", "decision"=> $_POST['recommendation'], "eval_date" => $_POST['evaldate']);
//    $where = array("sub_id"=>$_POST['subid'], "phrepuser_id"=>$_POST['revid'], "review"=>$_POST['maxef']);
//    print_r($where);
//    print_r($update);
    
//    if($obj->update_record("rev_groups",$where, $update)){
//        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
//    }    
    }    
}

if(isset($_POST['submitreviewconsent'])){
    $where = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "revform_id" => "2");
    $asa = array("sub_id" => $_POST['subid'], "revid" => $_POST['revid'], "idq" => "13");
    if(($obj->delete_record("rev_answers", $where))&&($obj->delete_record("rev_subanswers", $asa))){
        
        for($i=13; $i<=27; $i++){
        $q = $_POST['inlineRadioOptions'.$i];
        if(isset($q) || !empty($q)){ 
        $s = explode(",", $q);
            $ans = array(
                "idq" => $s['0'],
                "ansdesc" => $s['1'],
                "sub_id" => $_POST['subid'],
                "revid" => $_POST['revid'],
                "revform_id" => "2"
            );
            
        if($obj->insert_record("rev_answers", $ans)){
//        header("location:sec_register_reviewer.php");
        }
//        print_r($ans); echo "<br>";  
        }
         
    }
    if($_POST['text13']){
        $nouna = array(
        "idq" => "13",
        "subansdesc" => $_POST['text13'],
        "sub_id" => $_POST['subid'],
        "revid" => $_POST['revid'],
        "revform_id" => "2"
        );
//        print_r($nouna); echo "<br>";    
        if($obj->insert_record("rev_subanswers", $nouna)){
//        header("location:sec_register_reviewer.php");
        }      
    }
        
        $concerns = array(
        "idq" => "28",
        "ansdesc" => $_POST['text28'],
        "sub_id" => $_POST['subid'],
        "revid" => $_POST['revid'],
        "revform_id" => '2'
    );
//        print_r($concerns);
        if($obj->insert_record("rev_answers", $concerns)){
        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
        } 
        
    //RECOMMENDATION
//    $update = array("evaluation_type"=>$_POST['evaltype'] ,"evaluation_submitted"=>"1", "decision"=> $_POST['recommendation'], "eval_date" => $_POST['evaldate']);
//    $where = array("sub_id"=>$_POST['subid'], "phrepuser_id"=>$_POST['revid'], "review"=>$_POST['maxef']);
////    print_r($where);
////    print_r($update);
//    
//    if($obj->update_record("rev_groups",$where, $update)){
//        header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
//    }    
    }
}

if(isset($_POST['updatesugg'])){
    echo "<pre>"; var_dump($_POST); echo "</pre>";exit;
    
    $subid = $_POST['subid'];
    $suggestedcm = $_POST['sugbycm'];
//    echo $suggestedcm; exit;
//    print_r($_POST['sugbycm']);exit;
    
    $where = array("sub_id" => $subid);
    $updatesuggcm = array("chair_suggest" => $suggestedcm);
    
//    print_r($suggest);
    
    if($obj->update_record("proposal", $where, $updatesuggcm)){
        header("location:rev_dashboard.php#new");
    }
    
    
}

//if(isset($_POST['undosugg'])){
////    var_dump($_POST);exit;
//    
//    $subid = $_POST['subid'];
////    echo $suggestedcm; exit;
////    print_r($_POST['sugbycm']);exit;
//    
//    $where = array("sub_id" => $subid);
//    $updatesuggcm = array("chair_suggest" => "0");
//    
////    print_r($suggest);
//    
//    if($obj->update_record("proposal", $where, $updatesuggcm)){
//        header("location:rev_dashboard.php#new");
//    }
//    
//    
//}

if (isset($_GET['sed'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $str = $_GET['str'];
                
        $where = array("sub_id" => $id);
        $fields = array("chair_suggest" => $str);
        if($obj->update_record("proposal", $where, $fields)){
            header("location:rev_dashboard.php#new");
        }
    }

}
if (isset($_GET['sfl'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $str = $_GET['str'];
                
        $where = array("sub_id" => $id);
        $fields = array("chair_suggest" => $str);
        if($obj->update_record("proposal", $where, $fields)){
            header("location:rev_dashboard.php#new");
        }
    }

}
if (isset($_GET['sep'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $str = $_GET['str'];
                   
        $where = array("sub_id" => $id);
        $fields = array("chair_suggest" => $str);
        if($obj->update_record("proposal", $where, $fields)){
            header("location:rev_dashboard.php#new");
        }
    }

}
if (isset($_GET['undosug'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
                                   
        $where = array("sub_id" => $id);
        $fields = array("chair_suggest" => "0");
        if($obj->update_record("proposal", $where, $fields)){
            header("location:rev_dashboard.php#new");
        }
        $whereid = array("sub_id" => $id);
        if($obj->delete_record("suggest_reviewers", $whereid)){
            header("location:rev_dashboard.php#new");
        }
        if($obj->delete_record("rev_exemption_reason", $whereid)){
            header("location:rev_dashboard.php#new");
        }

    }

}

if(isset($_POST['submitcomment'])){
//    echo "<pre>"; var_dump($_POST); echo "</pre>";exit;
  
  $values = array(
      "version" => $_POST['maxrev'],
      "sub_id" => $_POST['subid'],
      "phrepuser_id" => $_POST['userid'],
      "comment" => $_POST['revcomment']
  );
  
  if($obj->insert_record("rev_comment", $values)){
      header("location: reviewproposal.php?id=".$_POST['subid']."#evaluation");
  }
  
  $updateeval = array(
      "evaluation_type" => "3",
      "evaluation_submitted" => "1",
      "decision" => $_POST['recommendation'],
      "eval_date" => strtotime("now")
  );
  $whereeval = array("review" => $_POST['maxrev'], "phrepuser_id" => $_POST['userid']);
  
  if($obj->update_record("rev_groups", $whereeval, $updateeval)){
      header("location:reviewproposal.php?id=".$_POST['subid']."#evaluation");
  }
}

if(isset($_POST['submitcommentpa'])){
//    echo "<pre>"; var_dump($_POST); echo "</pre>";exit;
  
  $themaxidforpp = $obj->getMaxValueofppa($_POST['subid']); //GETTING MAX PPA
  $thecountofcommentperrev = $obj->getmaxvalueofcountpercommentrev($_POST['userid'],$_POST['subid'],$themaxidforpp);
  
  $values = array(
      "version" => $_POST['maxrev'],
      "sub_id" => $_POST['subid'],
      "phrepuser_id" => $_POST['userid'],
      "comment" => $_POST['revcomment'],
      "ppa_id" => $themaxidforpp,
      "countcom" => $thecountofcommentperrev+1
  );
  
  if($obj->insert_record("rev_commentpa", $values)){
      header("location: reviewproposalpostapproval.php?id=".$_POST['subid']."#evaluation");
  }
  
  $updateeval = array(
      "evaluation_type" => "3",
      "evaluation_submitted" => "1",
      "decision" => $_POST['recommendation'],
      "eval_date" => strtotime("now")
  );
  $whereeval = array("review" => $_POST['maxrev'], "phrepuser_id" => $_POST['userid'], "ppa_id" =>$themaxidforpp);
  
  if($obj->update_record("rev_groupspa", $whereeval, $updateeval)){
      header("location:reviewproposalpostapproval.php?id=".$_POST['subid']."#evaluation");
  }
}

if(isset($_POST['editcommentpa'])){
//    echo "<pre>"; var_dump($_POST); echo "</pre>";exit;
  
  
  $where = array("id" => $_POST['idc']);
  $values = array(
      "comment" => $_POST['revcomment'],
  );
  
  if($obj->update_record("rev_commentpa", $where, $values)){
      header("location: reviewproposalpostapproval.php?id=".$_POST['subid']."#evaluation");
  }
  
  $updateeval = array(
      "evaluation_type" => "3",
      "evaluation_submitted" => "1",
      "decision" => $_POST['recommendation'],
      "eval_date" => strtotime("now")
  );
  $whereeval = array("review" => $_POST['maxrev'], "phrepuser_id" => $_POST['userid']);
  
  if($obj->update_record("rev_groups", $whereeval, $updateeval)){
      header("location:reviewproposalpostapproval.php?id=".$_POST['subid']."#evaluation");
  }
}

if (isset($_GET['deletecomment'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $idc = $_GET['idc'];
        $subid = $_GET['subid'];
                                   
        $where = array("id" => $idc);
        if($obj->delete_record("rev_comment", $where)){
            header("location:reviewproposal.php?id=$subid#comment");
        }
    }

}

if(isset($_POST['submitrecommendation'])){
    
//    echo "<pre>"; var_dump($_POST); echo "</pre><br>"; exit;
    $where = array("sub_id" => $_POST['subid'], "phrepuser_id" => $_POST['userid'], "review" => "1");
    $fields = array("evaluation_submitted" => "1", "decision" => $_POST['recommendation'], "eval_date" => $_POST['evaldate']);
//    $updateevaluation = $obj->update_record("rev_groups", $where, $fields);
    if($obj->update_record("rev_groups", $where, $fields)){
       header("location: reviewproposal.php?id=".$_POST['subid']."#evaluation");
   }
}

if(isset($_POST['updateresprofile'])){
   $username = $_POST['username'];
   $userid = $_POST['userid'];
   
   $where = array("id" => $userid);
   $fields = array(
     "title" => $_POST['title'],
     "fname" => $_POST['fname'],
     "mname" => $_POST['mname'],
     "lname" => $_POST['lname'],
     "pnum" => $_POST['pnum'],
     "institution" => $_POST['insti']
   );
   
//   $key = $obj->getmagicword(); 
//   $id = $obj->encrypt($userid,$key);
   

   if($obj->update_record("phrepuser", $where, $fields)){
    echo '<script>alert("Welcome to Geeks for Geeks")</script>';
       header("location: rev_info.php?id=".$userid);
   }
   
   $where1 = array("memberID" => $username);
   $fields1 = array("email" => $_POST['email'], "passMD5" => md5($_POST['password']));

   if($obj->update_record("membership_users", $where1, $fields1)){

       header("location: rev_info.php?id=".$userid);
   }
   
   
}
if(isset($_POST['suggesting'])){
    
    echo "<pre>"; var_dump($_POST); echo "</pre><br>";
    
    $revcount = count($_POST['rev']);
    $reviewer = $_POST['rev'];
    echo $revcount;
    
    for($i=0; $i<$revcount; $i++){
        
        $reviewers = array(
            "sub_id" => $_POST['subid'],
            "phrepuser_id" => $reviewer[$i]
        );
//    print_r($reviewers);
        if($obj->insert_record("suggest_reviewers", $reviewers)){
            header("location: rev_dashboard.php#new");
        }
        
    }
    $where = array("sub_id" => $_POST['subid']);
    $fields = array("chair_suggest" => $_POST['str']);
    if($obj->update_record("proposal", $where, $fields)){
        header("location: rev_dashboard.php#new");
    }
}
if(isset($_POST['suggesting'])){
    
//    echo "<pre>"; var_dump($_POST); echo "</pre><br>";exit;
    $revcount = count($_POST['rev']);
    $reviewer = $_POST['rev'];
    
    $r .= "<ol>";
    for($i=0; $i<$revcount; $i++){
        
        $reviewers = array(
            "id" => $reviewer[$i]
        );
        
        $getrev = $obj->fetch_record_with_where("phrepuser", $reviewers);
        foreach($getrev as $revv){
            $r .= "<li>";
            $r .= $revv['title'].' '.$revv['fname'].' '.$revv['mname'].' '.$revv['lname'];
            $r .= "</li>";
        }
    }
    $r .= "</ol>";
//    echo $r;
    
    
        $table = array("proposal", "submission", "rec_list", "phrepuser", "membership_users");
        $join_on = array("sub_id", "sub_id", "reclist_id", "id", "secretary", "id", "username", "memberID");
        $where = array("proposal.sub_id" => $_POST['subid']);
        $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
        if($getdata){
            foreach($getdata as $dt){
                $secemail = $dt['email'];
                $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
//                print_r($change);exit;
            }        
        }
        
        $wherert = array("id" => $_POST['str']);
        $getrt = $obj->fetch_record_with_where("review_type_list", $wherert);
        foreach($getrt as $rtt){
            array_push($change, $rtt['rt_name']);
//            print_r($change);
        }
        
        array_push($change, $r); #print_r($change); exit;
        
        $wherech = array("id" => $_POST['userid']);
        $getdata1 = $obj->fetch_record_with_where("phrepuser", $wherech);
        if($getdata1){
            foreach($getdata1 as $dt1){
                $chr = $dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'];
                //print_r($change); 
            }
        }
        array_push($change, $chr); print_r($change);
//        exit;
        $where3 = array("id" => "17");
        $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
        foreach ($getTemplate as $tplate) {
            $subject = $tplate['subject'];
            $template = $tplate['body'];
        }

        $find = array("{recsec}","{proposaltitle}","{reviewtype}","{reviewers}","{chairman}"); 
        $readytosend = str_replace($find, $change, $template);

        if($obj->sendEmail3($secemail, $readytosend, $subject)){
             header("location:rev_dashboard.php");} 
    
}

if(isset($_FILES['sitevisit'])){
    // print_r($_FILES['sitevisit']);exit;
      $errors= array();      
      $id = $_POST['sitevisit'];
      $file_name = $_FILES['sitevisit']['name'];
      $file_size = $_FILES['sitevisit']['size'];
      $file_tmp = $_FILES['sitevisit']['tmp_name'];
      $file_type = $_FILES['sitevisit']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['sitevisit']['name'])));
      
      $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
//            echo $useurl;
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = $_POST['docname'];
//            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = $_POST['revision'] + 1;
            $rename_filename = $id."-".$date."-".$times."-$resdoctype.".end($temp);
            
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){ //RENAMED THE RENAME_FILENAME
            if(move_uploaded_file($file_tmp,"uploads/main/".$file_name)){
            
            //move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $doctypefinal = $resdoctype['0'];
//            print_r($doctypefinal);
            
            $ppaid = $obj->getmaxpropapp($id);
            
            $getsv = array(
                'sub_id' => $id,
                'post_approval_type' => $ppaid,
                'username' => $_POST['username'],
                'finaldoc' => '1'
            );
            $getsvv = $obj->fetch_record_with_where("document_sitevisit", $getsv);
            
            if(!$getsvv){
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => $times,
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => $_POST['kind'],
                "doctype" => $doctype,
                "username" => $_POST['userid'],
                "path" => "uploads/main/".$file_name,
                "post_approval_type" => $ppaid,
                "newsubmit" => "1"
                );  
    //            echo '<pre>';print_r($uploadinfor); echo '</pre>';exit;
                if($obj->upload_info("document_sitevisit", $uploadinfor, $id, $doctype, $useurl, $times, $_POST['userid'])){
                    header("location:rev_sitevisit.php?id=".$id);
                }    
            }
            else{ header("location:rev_sitevisit.php?id=".$id);}
            
//            $whereup = array(
//                "sub_id" => $id
//            );
//            $updatenewsubmit = array(
//                "newsubmit" => "0"
//            );
//            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
//               
//            }
           
           
        }
        else{
            echo "FAILED";
            echo "</p>";
            echo '<pre>';
            echo 'Here is some more debugging info:';
            print_r($_FILES);
            print "</pre>"; exit;
            }
      }else{
         print_r($errors);
      }
   }
   
   
if (isset($_GET['deletesv'])) {
    if (isset($_GET['fid'])) {
        $fid = $_GET['fid'];
        $subid = $_GET['subid'];
                                   
        $where = array("file_id" => $fid);
        if($obj->delete_record("document_sitevisit", $where)){
            header("location:rev_sitevisit.php?id=$subid");
        }
    }

}

if(isset($_POST['submitsitevisitreport'])){
//    var_dump($_POST);
    
    
    $getmaxsub = $obj->maxsubsitevisit($_POST['subid'], $_POST['ppaid']);
    
    
    $savesitedecision = array(
        "fileid" => $_POST['fileid'],
        "submission" => $getmaxsub + 1,
        "final" => '1',
        "subid" => $_POST['subid'],
        "ppaid" => $_POST['ppaid'],
        "decision" => $_POST['sitevisitdecision'],
        "username" => $_POST['username']
    );
    
    $savedsitedecision = $obj->fetch_record_with_where("sitevisit_decision", $savesitedecision);
    
    if($savedsitedecision){}
    else{
        if($obj->insert_record("sitevisit_decision", $savesitedecision)){
            header("location: rev_sitevisit.php?id=".$_POST['subid']);
        }
    }
    
    
}
 
   
if (isset($_GET['undosv'])) {
    if (isset($_GET['fid'])) {
        $fid = $_GET['fid'];
        $des = $_GET['des'];
        $subid = $_GET['subid'];
                                   
        $where = array("file_id" => $fid);
        if($obj->delete_record("document_sitevisit", $where)){
            header("location:rev_sitevisit.php?id=$subid");
        }    
        
        $where = array("id" => $des);
        if($obj->delete_record("sitevisit_decision", $where)){
            header("location:rev_sitevisit.php?id=$subid");
        }
    }

}

if(isset($_POST['submit_reasons'])){
    var_dump($_POST);
    $user_id = $_POST['userid'];
    $username = $_POST['username'];
    $subid = $_POST['subid'];
    $str = $_POST['str'];
    $reason = $_POST['exemption_reason'];
                   
        $where = array("sub_id" => $subid);
        $fields = array("chair_suggest" => $str);
        if($obj->update_record("proposal", $where, $fields)){
            $where = array(
                "sub_id" => $subid,
                "reason" => $reason,
                "user_id" => $user_id,
                "date" => $datetime
            );
            
            if($obj->insert_record("rev_exemption_reason", $where)){
                header("location:rev_dashboard.php#new");
            }

        }
}




?>