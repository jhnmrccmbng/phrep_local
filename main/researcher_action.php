<?php
include "sample_dbtest.php";

class UploadOperation extends Database
{   
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
    
    function validateUser($table, $user, $profile){
        $sql = "";
        $condition = "";
        foreach ($user as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition;

        $query = mysqli_query($this->con, $sql);
        $numrows = mysqli_num_rows($query);
        if($numrows>0){
            foreach($query as $row){
                $count = count($row['id']);
                if ($row['id']>0){
                    $this->update_record($table, $user, $profile);
                    header("location:dashboard.php?msg=Record Updated");
                }
            }
            
        }
        
        else{
            $this->insert_record($table, $profile);
            header("location:dashboard.php?msg=Record Inserted");
        }   
}

    function countActives(){
        
        $i = 0;
        $getnewsubmit = $obj->getNewSubmitted($userid);
        if($getnewsubmit) {
            foreach ($getnewsubmit as $newsubmit) {
                $i = $i + 1;
            }
        }
        

        $x = 0;
        $maxVal = $obj->getMaxDate($userid);
            if($maxVal){
                foreach($maxVal as $maxvalue){                                          
                        $where = array("id" => $maxvalue['sa']);
                        $getstatid = $obj->fetch_record_with_where("proposal_status", $where);
                        if($getstatid){
                            foreach($getstatid as $statid){
                                if(($statid['status_action']) == '0'){
                                    $x = $x + 1;
                                    }  
                                else if(($statid['status_action']) == '1'){
                                    $x = $x + 1;
                                    }
                                else if(($statid['status_action']) == '2'){
                                    $x = $x + 1;
                                    }
                                else if(($statid['status_action']) == '3'){
                                    $x = $x + 1;
                                    }
                                else if(($statid['status_action']) == '11'){
                                    $x = $x + 1;
                                    }
                                else if(($statid['status_action']) == '8'){
                                    $x = $x + 1;
                                    }
                                else if(($statid['status_action']) == '14'){
                                    $x = $x + 1;
                                    }
                            }
                        }
                }
            }
            
            $a = $i + $x;
            
            return $a;
                                  
    }


    function getmagicword(){
        $sql = "SELECT theword FROM magicword where id = '1'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $theword = $row['theword'];
        return $theword;
    }
    
    function encrypt($string, $key){
        $string = openssl_encrypt($string, "AES-128-ECB", $key);
        // $string = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
        $string = $this->base64_url_encode($string);
        return $string;
    }
    function decrypt($string, $key){
        $string = $this->base64_url_decode($string);
        $string = openssl_decrypt($string, "AES-128-ECB", $key);
        // $string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));
        return $string;
    }
    function base64_url_encode($input) {
        $dirty = array("+", "/", "=");
        $clean = array(".", "_", "-");
        $cleanurl = str_replace($dirty, $clean, $input);
        
        return $cleanurl;
    }

    function base64_url_decode($input) {        
        $dirty = array("+", "/", "=");
        $clean = array(".", "_", "-");
        $cleanurl = str_replace($clean, $dirty, $input);
        
        return $cleanurl;
    }
  
    
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
        if (mysqli_query($this->con, $sql)) {
            return true;
        }
    }
    
    public function getlistproposal($userid) {
        $sql = "SELECT a.sub_id as sid, d.dpid as dp FROM `proposal` a 
                INNER JOIN submission b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON b.reclist_id = c.id
                LEFT JOIN (SELECT DISTINCT(subid) as dpid FROM proposal_post_approval) d ON a.sub_id = d.dpid
                WHERE a.username = '$userid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
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

public function getmaxvalue_with_where($col, $table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT MAX($col) as col FROM " . $table . " WHERE " . $condition;
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        if(count($row['col']) == 0){$a = 1;}
        
        else{$a = $row['col'] + 1;}
        
        return $a;
    }
function getmaxpropstat($subid) {
        $sql = "SELECT MAX(id) as sid FROM `proposal_status` WHERE sub_id = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['sid'];
        return $fileid;
}

function getmaxpastat($subid) {
        $sql = "SELECT MAX(pid) as ppid FROM `proposal_post_approval` WHERE subid = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['ppid'];
        return $fileid;
}

function getversionappeal($id) {
        $sql = "SELECT MAX(revision) as mpid FROM `document` WHERE sub_id = '$id' and kind = 'APL' and finaldoc = '0'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['mpid'];
        return $fileid;
}

function doctypetimes($id) {
        $sql = "SELECT MAX(doctypetimes) as mpid FROM `document` WHERE sub_id = '$id' and kind = 'APL' and finaldoc = '1'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['mpid'];
        return $fileid;
}

function getmaxrequest($sid, $paid) {
        $sql = "SELECT MAX(pa_id) as par FROM `proposal_post_approval` WHERE subid = '$sid' AND pa_request = '$paid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['par'];
        return $fileid;
}     
    function getNewSubmitted($userid){
        $sql = "SELECT * FROM proposal a
                WHERE a.sub_id NOT IN (SELECT b.sub_id FROM proposal_status b)
                AND a.username = '".$userid."' AND a.date_submitted is not null";
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    function getStep1($userid){
        $sql = "SELECT * FROM `submission` a 
                where a.sub_id NOT IN (SELECT sub_id FROM proposal)
                AND a.username = '$userid'";
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    } 

    function getStep2($userid){
        $sql = "SELECT *  FROM `proposal` WHERE `date_submitted` IS NULL and username = '$userid'";
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    } 
    
    function getMaxDate($userid){
        $sql = "SELECT *, MAX(b.id) AS sa FROM proposal a 
                INNER JOIN proposal_status b ON a.sub_id = b.sub_id
                INNER JOIN proposal_status_action c ON b.status_action = c.id
                WHERE a.username = '".$userid."' AND a.sub_id IN (SELECT d.sub_id FROM proposal_status d)
                GROUP BY a.sub_id ORDER BY a.date_submitted";
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
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
    public function fetch_record($table){
		$sql = "SELECT * FROM ".$table;
		$array = array();
		$query = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$array[] = $row;
		}
		return $array;
	}
    public function fetch_record_for_doctype($table1, $table2, $table3, $table4, $id, $id1, $id2, $id3, $id4, $id5, $id6, $doc){
//		$sql = "SELECT * FROM ".$table;
                $sql .= "SELECT * FROM ".$table1." a ";
                $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
                $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
                $sql .= "INNER JOIN ".$table4." d ON c.".$id5." = d.".$id6." ";
                $sql .= "WHERE a.sub_id = '".$id."' AND d.doctype = '".$doc."'";
		$array = array();
		$query = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($query)){
			$array[] = $row;
		}
		return $array;
	}
    function showingUploadedFiles($table1, $table2, $id1, $id2, $id, $username){
        
        $sql = "SELECT * FROM ".$table1." a
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                WHERE a.sub_id = '".$id."' and a.username = '".$username."'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    function showingUploadedFilesECE($subid, $userid, $ppaid){
        
        $sql = "SELECT * FROM document_postapproval a
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.sub_id = '$subid' AND a.username = '$userid'
                AND a.post_approval_type = '$ppaid' and a.finaldoc = '1'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }    

    function getapprovalletterinpa($sid, $ppaid){
        $sql = "SELECT * from document_postapproval a
        INNER JOIN document_type b ON a.doctype = b.docid        
        WHERE a.sub_id = $sid AND a.post_approval_type = $ppaid
        AND a.finaldoc = '1' AND a.doctype IN (15, 27, 30, 31)";

        $array = array();
        $query = mysqli_query($this->con,$sql);
                while($row = mysqli_fetch_assoc($query)){
            $array[] = $row;
            }
        return $array;      
        }
    
    function getmaxpostapproval($subid){
        $sql = "SELECT MAX(post_revision) as maxpr FROM `document` WHERE sub_id = '$subid' AND kind = 'SF' GROUP BY sub_id";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $maxpr = $row['maxpr'];
        return $maxpr;
    }    
    
    function getmaxdocx($subid, $doctype, $doctypex){
        $sql = "SELECT *, MAX(revision) as docmx FROM document WHERE sub_id = '$subid' AND doctype = '$doctype' and doctypetimes = '$doctypex'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $docmx = $row['docmx'];
        return $docmx;
    }    
     
    
    
    
    
    function getmaxecdocrev($subid){
        $sql = "SELECT MAX(revision) as maxrev FROM `document` WHERE sub_id = '$subid' AND kind = 'EC'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $maxrev = $row['maxrev'];
        return $maxrev;
    }       
    
    
    function getlaststatus($subid){
        $sql = "SELECT status_action as sa FROM proposal_status WHERE sub_id = '$subid' ORDER BY id  DESC LIMIT 1,1";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $sa = $row['sa'];
        return $sa;
    }     
    
    
    function grouptwo($subid){
        
        $sql = "SELECT * FROM `document` WHERE `sub_id` = '$subid' GROUP BY doctype, doctypetimes";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    function grouptwopa($subid){
        
        $sql = "SELECT * FROM `document_postapproval` WHERE `sub_id` = '$subid' GROUP BY doctype, doctypetimes";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function getdoctoresubmit($subid, $doctype, $doctypetimes){
        $sql = "SELECT * FROM document a 
                INNER JOIN (SELECT MAX(revision) as mrev FROM document WHERE sub_id = '$subid' AND doctype = '$doctype' AND doctypetimes = '$doctypetimes') b ON a.revision = b.mrev
                INNER JOIN document_type c ON a.doctype = c.docid
                WHERE a.sub_id = '$subid' AND a.doctype = '$doctype' AND a.doctypetimes = '$doctypetimes' AND a.finaldoc = '0'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array; 
        
    }
    
    function getdoctoresubmitpa($subid, $doctype, $doctypetimes, $ppaid){
        $sql = "SELECT * FROM document_postapproval a 
                INNER JOIN (SELECT MAX(revision) as mrev FROM document_postapproval WHERE sub_id = '$subid' AND doctype = '$doctype' AND doctypetimes = '$doctypetimes') b ON a.revision = b.mrev
                INNER JOIN document_type c ON a.doctype = c.docid
                WHERE a.doctype NOT IN (41) AND a.post_approval_type = '$ppaid' AND a.sub_id = '$subid' AND a.doctype = '$doctype' AND a.doctypetimes = '$doctypetimes' AND a.finaldoc = '0'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array; 
        
    }
        
    function getdoctorevise($subid,$doctype,$doctypetimes,$getmaxperdoc){
        
        $sql = "SELECT * FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid
                where a.sub_id = '$subid' and a.doctype = '$doctype' and a.doctypetimes = '$doctypetimes' and a.revision = '$getmaxperdoc' and a.finaldoc = '0' LIMIT 1";
        
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
    
    function upload_info($table, $fields, $id, $doctype, $useurl) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '0'";
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
                $sql .= " WHERE sub_id = ".$val['1']." AND doctype = '".$doctype."'";
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
    
function getmaxrl($id) {
        $sql = "SELECT MAX(revision) as rev FROM document WHERE sub_id = '$id' and kind = 'RL'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

function maxrevision($id, $ppaid) {
        $sql = "SELECT MAX(revision) as rev FROM document_postapproval WHERE sub_id = '$id' and post_approval_type = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

    function upload_info_finalReport($table, $fields, $id, $doctype, $useurl, $filename) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '".$doctype."' AND orig_filename = '".$filename."'";
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
                $sql .= " WHERE sub_id = ".$val['1']." AND doctype = '".$doctype."'";
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
    
    function upload_info_resubmission($table, $fields, $id, $doctype, $useurl, $revision) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '".$doctype."' AND revision = '".$revision."'";
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
                $sql .= " WHERE sub_id = ".$val['1']." AND doctype = '".$doctype."' AND revision = '".$revision."'";
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
    
        function upload_ethicalclearance($table, $fields, $id, $doctype, $useurl, $revision) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '".$doctype."' AND revision = '".$revision."' AND file_id = '0'";
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
                $sql .= " WHERE sub_id = ".$val['1']." AND doctype = '".$doctype."' AND revision = '".$revision."'";
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
    
    function upload_info_amendment($table, $fields, $id, $doctype, $useurl, $amend) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '".$doctype."' AND amend = '".$amend."'";
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
                $sql .= " WHERE sub_id = ".$val['1']." AND doctype = '".$doctype."' AND amend = '".$amend."'";
//                echo $sql ; 
                $query = mysqli_query($this->con,$sql);
                if($query){                
                     header("location:amend.php?id=".$id);
                        }
            }
            else{
                $sql = "";
                $sql .= "INSERT INTO ".$table;
                $sql .= " (".implode(",", array_keys($fields)).") VALUES ";
                $sql .= " ('".implode("','", array_values($fields))."')"; 
                $query = mysqli_query($this->con,$sql);
                    if($query){                
                     header("location:amend.php?id=".$id);
                        }
            }
        
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
        
    public function countActive($userid){
        $sql = "SELECT *, MAX(b.id) AS maxid FROM proposal a 
                INNER JOIN proposal_status b ON a.sub_id = b.sub_id
                WHERE a.username = '".$userid."' GROUP BY a.sub_id";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
    public function getReviseFile($subid, $maxrev){
        $sql = "SELECT *  FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid 
                WHERE a.sub_id = '".$subid."' AND a.kind IN ('MP', 'SF') AND a.finaldoc = '0' and a.revision = '".$maxrev."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    
    public function getReviseFilepost($subid, $maxrev){
        $sql = "SELECT *  FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid 
                WHERE a.sub_id = '$subid' AND a.kind IN ('ECF', 'SF') AND a.post_finaldoc = '0' and a.post_revision = '$maxrev'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    
    public function getMaxRevise($subid){
        $sql = "SELECT MAX(revision) AS maxrev FROM `document` WHERE `sub_id` = '".$subid."' and kind IN ('MP', 'SF') and finaldoc = '0'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
   public function getMaxRevisepost($subid){
        $sql = "SELECT MAX(post_revision) AS maxrev FROM `document` WHERE `sub_id` = '$subid' and kind IN ('ECF', 'SF') and post_finaldoc = '0'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
    public function getmaxdoc($subid){
        $sql = "SELECT MAX(revision) as maxrevision FROM document WHERE sub_id = '".$subid."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
     
    public function getmaxstatus($subid){
        $sql = "SELECT MAX(id) as sa FROM `proposal_status` where sub_id = '".$subid."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    public function getmaxstatuspa($subid){
        $sql = "SELECT a.status_action as st FROM `proposal_status` a 
                INNER JOIN (SELECT MAX(id) as mid, sub_id FROM proposal_status WHERE sub_id = '$subid') b ON a.id = b.mid
                WHERE a.sub_id = '$subid'";
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $maxrt = $row['st'];
        return $maxrt;
        
    }    
    
    public function getstat($subid, $status){
        $sql = "SELECT * FROM `proposal_status` where sub_id = '".$subid."' AND id = '".$status."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    public function getamendfile($subid){
        $sql = "SELECT *  FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid 
                WHERE a.sub_id = '$subid' AND a.kind IN ('MP', 'SF') AND a.finaldoc = '1' AND a.newsubmit = '0'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    public function getamendfilepa($subid){
        $sql = "SELECT *  FROM document_postapproval a 
                INNER JOIN document_type b ON a.doctype = b.docid 
                WHERE a.sub_id = '$subid' AND a.kind IN ('MP', 'SF') AND a.finaldoc = '1' AND a.newsubmit = '0'";
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
    
    public function getmaxrev($subid, $kind){
        $sql = "SELECT MAX(revision) as revmax FROM document where sub_id = '".$subid."' and kind = '$kind'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    
    public function getmaxrevlast($subid, $kind){
        $sql = "SELECT MAX(revision) as revmax FROM document where sub_id = '".$subid."' and kind = '$kind' and finaldoc = '0'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    } 
    
function getMaxRevFR($subid){
        $sql = "SELECT MAX(revision) as maxrevfr FROM document WHERE sub_id = '".$subid."' AND kind = 'FR'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrevfr'];
        return $fileid;
}     
function getmaxmessage($subid){
        $sql = "SELECT MAX(times) as maxtimes FROM message WHERE subid = '".$subid."'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxtimes'];
        return $fileid;
}    
function getmaxmessagepa($subid){
        $sql = "SELECT MAX(times) as maxtimes FROM message WHERE subid = '".$subid."' and notefor = 'incpa'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxtimes'];
        return $fileid;
}
function getmaxmessagepafinal($subid, $ppaid){
        $sql = "SELECT MAX(times) as maxtimes FROM messagepa WHERE subid = '".$subid."' and ppaid = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxtimes'];
        return $fileid;
}

function getmaxtimes($subid){
        $sql = "SELECT MAX(post_request_times) as maxrt FROM document WHERE sub_id = '".$subid."'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $maxrt = $row['maxrt'];
        return $maxrt;
}

function getmaxreq($subid){
        $sql = "SELECT MAX(pa_request) as reqmax FROM proposal_post_approval WHERE subid = '".$subid."'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $reqmax = $row['reqmax'];
        return $reqmax;
}

function maxsitevisit($subid, $ppaid){
        $sql = "SELECT MAX(repeatition) as reqmax FROM sitevisit WHERE subid = '".$subid."' AND post_approval_type = '".$ppaid."'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $reqmax = $row['reqmax'];
        return $reqmax;
}
    
    public function getamendedfile($subid){
        $sql = "SELECT * FROM document_postapproval a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE sub_id = '".$subid."' AND finaldoc = '1' AND newsubmit = '1'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    
    public function update_previous_document($subid,$doctype){
        $sql = "SELECT MAX(file_id) as fid FROM `document` WHERE doctype = '$subid' and sub_id = '$doctype' limit 1";
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['fid'];
        $whereu = array("file_id"=>$fileid);
        $fields = array("finaldoc"=>'1');
        
        $this->update_record("document", $whereu, $fields);
        
    }
    
function getmaxsupfilestimes($subid, $doctype) {
        $sql = "SELECT MAX(doctypetimes) as doctimes, doctype FROM document WHERE sub_id = '$subid' AND doctype = '$doctype' GROUP BY doctype";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $doctimes = $row['doctimes'];
        return $doctimes;
    } 
    
function getmaxsupfilestimespa($subid, $doctype, $ppaid) {
        $sql = "SELECT MAX(doctypetimes) as doctimes, doctype FROM document_postapproval WHERE sub_id = '$subid' AND doctype = '$doctype' AND post_approval_type = '$ppaid' GROUP BY doctype";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $doctimes = $row['doctimes'];
        return $doctimes;
    }     
    

    
    
function getmaxsupfilestimespaa($subid, $doctype, $ppaid) {
        $sql = "SELECT MAX(doctypetimes) as doctimes, doctype FROM document_postapproval WHERE sub_id = '$subid' AND doctype = '$doctype' AND post_approval_type = '$ppaid' GROUP BY doctype";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $doctimes = $row['doctimes'];
        return $doctimes;
    }     
    

     
function sendEmail($resEmail, $resfullname, $secEmail, $secfullname, $path, $subject, $body) {
        //notifyMemberApproval($memberID);

        $currDir = dirname(__FILE__);
        require "{$currDir}/mailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;

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

        $mail->setFrom($resEmail, $resfullname);           // WHO SENT
        $mail->addAddress($secEmail, $secfullname);                                        // Name is optional //SENT TO
        $mail->addReplyTo($resEmail, $resfullname);        // WHERE TO REPLY
        // $mail->addCC('hbcornea@pchrd.dost.gov.ph');                                                       // CC
        $mail->addBCC('hbcornea@pchrd.dost.gov.ph', 'dcaguila@pchrd.dost.gov.ph');                 //BCC

        $mail->addAttachment('../main/' . $path . '');                        // Add attachments /var/tmp/file.tar.gz
        // $mail->addAttachment('');                                               // Optional name /tmp/image.jpg', 'new.jpg
        $mail->isHTML(true);                                                    // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body = 'Dear'.$secEmail.', <br>'.$body;
        $mail->AltBody = '';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            header("location:sec_dashboard_active.php");
        }
    }
    
    public function getresearcherInfo($userid){
        $sql = "SELECT * FROM phrepuser a 
                INNER JOIN membership_users b ON a.username = b.memberID
                WHERE a.id = '".$userid."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    
    public function getsecretary($subid){
        $sql = "SELECT * FROM proposal a 
                INNER JOIN submission b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON b.reclist_id = c.id
                WHERE a.sub_id = '$subid'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    
    public function getSecInfo($userid){
        $sql = "SELECT * FROM phrepuser a 
                INNER JOIN membership_users b ON a.username = b.memberID
                WHERE a.id = '".$userid."'";
        $array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
        
    }
    
function getmaxbatch($id) {
        $sql = "SELECT MAX(batchnum) as bat FROM document WHERE sub_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getmaxrevisionpostapproval($id, $form, $ppaid) {
        $sql = "SELECT MAX(revision) as bat FROM document_postapproval WHERE sub_id = '$id' and post_approval_type = '$ppaid' AND kind = '$form'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getmaxrevision($id, $ppt) {
        $sql = "SELECT MAX(revision) as bat FROM document_postapproval WHERE sub_id = '$id' and post_approval_type = '$ppt' AND newsubmit = '0'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getmaxbatchforupload($id) {
        $sql = "SELECT MAX(batchnum) as bat FROM document WHERE sub_id = '$id' AND newsubmit = '0'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getmaxbatchforuploadpa($id, $ppaid) {
        $sql = "SELECT MAX(batchnum) as bat FROM document_postapproval WHERE sub_id = '$id' AND newsubmit = '0' AND post_approval_type = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getmaxbatchforincompleteuploadpa($id, $ppaid) {
        $sql = "SELECT MAX(batchnum) as bat FROM document_postapproval WHERE sub_id = '$id' AND post_approval_type = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getmaxbatchforuploadpost($id, $ppaid) {
        $sql = "SELECT MAX(batchnum) as bat FROM document_postapproval WHERE sub_id = '$id' AND newsubmit = '0' AND post_approval_type = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
}

function getmaxbatchforuploadincomplete($id) {
        $sql = "SELECT MAX(batchnum) as bat FROM document WHERE sub_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['bat'];
        return $bat;
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
    
function sendEmail1($emailto, $body, $subject) {
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
        $mail->addReplyTo('nationalethicscommittee.ph@gmail.com', 'NEC');        // WHERE TO REPLY
        // $mail->addCC('');                                                       // CC
        $mail->addBCC('hbcornea@pchrd.dost.gov.ph', 'dcaguila@pchrd.dost.gov.ph');                                                      //BCC

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

    public function getallpostapprovalrequest($userid) {
        $sql = "SELECT sub_id, d.dpid as dp FROM `proposal` a 
                LEFT JOIN (SELECT DISTINCT(subid) as dpid FROM proposal_post_approval) d ON a.sub_id = d.dpid
                WHERE a.username = $userid";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getmaxpropapp($id) {
        $sql = "SELECT MAX(pid) as pid FROM proposal_post_approval WHERE subid = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['pid'];
        return $fileid;
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
        
    function getMaxProposalStatus($subid){
        $sql = "SELECT MAX(id) as maxstatid FROM `proposal_status` WHERE sub_id = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxstatid'];
        return $fileid;
    }
    
}

$obj = new UploadOperation();

date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));

if(isset($_POST['updateprofile'])){
    
    $uname = $_POST['username2'];
    $title = $_POST['title'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $pnum = $_POST['pnum'];
    $intitu = $_POST['intitu'];
    
    $profile = array(
        "username" => $uname,
        "title" => $title,
        "fname" => $fname,
        "mname" => $mname,
        "lname" => $lname,
        "pnum" => $pnum,
        "institution" => $intitu
    );
    
    $user = array(
        "username" => $uname
    );

        
    if($obj->validateUser("phrepuser", $user, $profile)){
        header("location:dashboard.php?msg=Record Inserted");
    }
    
//    if($obj->validateUser("phrepuser",$profile)){
////	header("location:index.php?msg=Record Inserted");
//    }
    
    
    
}

if(isset($_FILES['image'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      // $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      $expensions= array("pdf");
      
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
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            

            //move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $doctypefinal = $resdoctype['0'];
//            print_r($doctypefinal);
            
            
            $uploadinfor = array(
            "file_id" => "",
            "sub_id" => $id,
            "revision" => "1",
            "file_name" => $rename_filename,
            "orig_filename" => $file_name,
            "file_type" => $file_type,
            "file_size" => $file_size,
            "date_uploaded" => $datetime,
            "date_modified" =>$datetime,
            "kind" => $_POST['kind'],
            "doctype" => $doctypefinal,
            "username" => $_POST['userid']
            );  
//            print_r($uploadinfor);
                if($obj->upload_info("document", $uploadinfor, $id, $doctypefinal, $useurl)){
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
            
      }
      else{
         print_r($errors);
      }
   }
   
if(isset($_FILES['addsupfile'])){
    
    // var_dump($_POST['urllink']); exit;

      $id = $_POST['submid'];
      $errors= array();      
      $file_name = $_FILES['addsupfile']['name'];
      $file_size = $_FILES['addsupfile']['size'];
      $file_tmp = $_FILES['addsupfile']['tmp_name'];
      $file_type = $_FILES['addsupfile']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['addsupfile']['name'])));
      
      // $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
////            echo $useurl;
//            $id = $cleanid;
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            
            
            
            //
            $doctypefinal = $resdoctype['0'];
            $where = array("sub_id" => $id,"doctype" => $doctypefinal);  
            $getdocfile = $obj->fetch_record_with_where("document", $where);
//            print_r($getdocfile); exit;
            if($obj->fetch_record_with_where("document", $where)){
                if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                    $maxsuppfile = $obj->getmaxsupfilestimes($id, $doctypefinal);
                    $getmaxb = $obj->getmaxbatchforuploadincomplete($id); 
//                    echo $getmaxb;exit;
        //            print_r($doctypefinal);

                    $uploadinfor = array(
                    //"file_id" => "",
                    "sub_id" => $id,
                    "revision" => "1",
                    "finaldoc" => "1",
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" =>$datetime,
                    "kind" => $_POST['kind'],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => $maxsuppfile+1,
                    "newsubmit" => '1',
                    "batchnum" => $getmaxb,
                    "path"=> $path_dir,
                    "username" => $_POST['userid']
                    );  
                    if($obj->insert_record("document", $uploadinfor)){
                        header("location:".$useurl."?id=".$_POST['submid']);
                    }

//                    echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
                    
                }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
                
            }
            else{
                if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                    $maxsuppfile = $obj->getmaxsupfilestimes($id, $doctypefinal);
                    $getmaxb = $obj->getmaxbatchforuploadincomplete($id); 
        //            print_r($doctypefinal);

                    $uploadinfor = array(
                    //"file_id" => "",
                    "sub_id" => $id,
                    "revision" => "1",
                    "finaldoc" => "1",
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" =>$datetime,
                    "kind" => $_POST['kind'],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => "1",
                    "batchnum" => $getmaxb,
                    "newsubmit" => '1',
                    "path"=> $path_dir,
                    "username" => $_POST['userid']
                    ); 
                    if($obj->insert_record("document", $uploadinfor)){
                        header("location:".$useurl."?id=".$_POST['submid']);
                    }

//                    echo "<pre>"; print_r($uploadinfor); echo "</pre>";exit;
                    
                }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
                
            }     
      }else{
         print_r($errors);
      }
}      


if(isset($_FILES['addsupfilepa'])){
    
    
      $id = $_POST['submid'];
      $errors= array();      
      $file_name = $_FILES['addsupfilepa']['name'];
      $file_size = $_FILES['addsupfilepa']['size'];
      $file_tmp = $_FILES['addsupfilepa']['tmp_name'];
      $file_type = $_FILES['addsupfilepa']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['addsupfilepa']['name'])));
      
      // $expensions= array("doc","docx","jpg","jpeg","png","pdf");
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
////            echo $useurl;
//            $id = $cleanid;
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            
            
            
            //
            $doctypefinal = $resdoctype['0'];
            $where = array("sub_id" => $id,"doctype" => $doctypefinal);  
            $getdocfile = $obj->fetch_record_with_where("document_postapproval", $where);
//            print_r($getdocfile); exit;
            if($obj->fetch_record_with_where("document_postapproval", $where)){
                if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                    $maxppaid = $obj->getMaxValueofppa($id);
                    $maxsuppfile = $obj->getmaxsupfilestimespa($id, $doctypefinal,$maxppaid);
                    $getmaxb = $obj->getmaxbatchforuploadpa($id,$maxppaid); 
//                    echo $getmaxb;exit;
        //            print_r($doctypefinal);

                    $uploadinfor = array(
                    //"file_id" => "",
                    "sub_id" => $id,
                    "revision" => "1",
                    "finaldoc" => "1",
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" =>$datetime,
                    "kind" => $_POST['kind'],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => $maxsuppfile+1,
                    "newsubmit" => '1',
                    "batchnum" => $getmaxb+1,
                    "path"=> $path_dir,
                    "username" => $_POST['userid'],
                    "post_approval_type" => $maxppaid
                    );  
                    if($obj->insert_record("document_postapproval", $uploadinfor)){
                        header("location:".$useurl."?id=".$_POST['submid']);
                    }

//                    echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
                    
                }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
                
            }
            else{
                if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                    $maxppaid = $obj->getMaxValueofppa($id);
                    $maxsuppfile = $obj->getmaxsupfilestimespa($id, $doctypefinal, $maxppaid);
                    $getmaxb = $obj->getmaxbatchforuploadpa($id,$maxppaid);
        //            print_r($doctypefinal);

                    $uploadinfor = array(
                    //"file_id" => "",
                    "sub_id" => $id,
                    "revision" => "1",
                    "finaldoc" => "1",
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" =>$datetime,
                    "kind" => $_POST['kind'],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => "1",
                    "batchnum" => $getmaxb+1,
                    "newsubmit" => '1',
                    "path"=> $path_dir,
                    "username" => $_POST['userid'],
                    "post_approval_type" => $maxppaid                        
                    ); 
                    if($obj->insert_record("document_postapproval", $uploadinfor)){
                        header("location:".$useurl."?id=".$_POST['submid']);
                    }

//                    echo "<pre>"; print_r($uploadinfor); echo "</pre>";exit;
                    
                }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
                
            }     
      }else{
         print_r($errors);
      }
}


if(isset($_FILES['addsupfilepaa'])){
    
    
      $id = $_POST['submid'];
      $errors= array();      
      $file_name = $_FILES['addsupfilepaa']['name'];
      $file_size = $_FILES['addsupfilepaa']['size'];
      $file_tmp = $_FILES['addsupfilepaa']['tmp_name'];
      $file_type = $_FILES['addsupfilepaa']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['addsupfilepaa']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
////            echo $useurl;
//            $id = $cleanid;
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            
            
            
            //
            $doctypefinal = $resdoctype['0'];
            $where = array("sub_id" => $id,"doctype" => $doctypefinal);  
            $getdocfile = $obj->fetch_record_with_where("document_postapproval", $where);
//            print_r($getdocfile); exit;
            if($obj->fetch_record_with_where("document_postapproval", $where)){
                if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                    $maxppaid = $obj->getMaxValueofppa($id);
                    $maxsuppfile = $obj->getmaxsupfilestimespa($id, $doctypefinal, $maxppaid);
                    $getmaxb = $obj->getmaxbatchforincompleteuploadpa($id,$maxppaid); 
                    $getmaxrevisionpa = $obj->maxrevision($id, $maxppaid);
//                    echo $getmaxb;exit;
        //            print_r($doctypefinal);

                    $uploadinfor = array(
                    //"file_id" => "",
                    "sub_id" => $id,
                    "revision" => $getmaxrevisionpa,
                    "finaldoc" => "1",
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" =>$datetime,
                    "kind" => $_POST['kind'],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => $maxsuppfile+1,
                    "newsubmit" => '1',
                    "batchnum" => $getmaxb,
                    "path"=> $path_dir,
                    "username" => $_POST['userid'],
                    "post_approval_type" => $maxppaid
                    );  
                    if($obj->insert_record("document_postapproval", $uploadinfor)){
                        header("location:".$useurl."?id=".$_POST['submid']);
                    }

//                    echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
                    
                }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
                
            }
            else{
                if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                    $maxppaid = $obj->getMaxValueofppa($id);
                    $maxsuppfile = $obj->getmaxsupfilestimespa($id, $doctypefinal, $maxppaid);
                    $getmaxb = $obj->getmaxbatchforuploadpa($id,$maxppaid);
                    $getmaxrevisionpa = $obj->maxrevision($id, $maxppaid);
        //            print_r($doctypefinal);

                    $uploadinfor = array(
                    //"file_id" => "",
                    "sub_id" => $id,
                    "revision" => $getmaxrevisionpa,
                    "finaldoc" => "1",
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" =>$datetime,
                    "kind" => $_POST['kind'],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => "1",
                    "batchnum" => $getmaxb,
                    "newsubmit" => '1',
                    "path"=> $path_dir,
                    "username" => $_POST['userid'],
                    "post_approval_type" => $maxppaid                        
                    ); 
                    if($obj->insert_record("document_postapproval", $uploadinfor)){
                        header("location:".$useurl."?id=".$_POST['submid']);
                    }

//                    echo "<pre>"; print_r($uploadinfor); echo "</pre>";exit;
                    
                }
            else{
                echo "FAILED";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($_FILES);
                print "</pre>"; exit;
            }
                
            }     
      }else{
         print_r($errors);
      }
}


//if(isset($_FILES['addsupfile'])){
//      $errors= array();      
//      $id = $_POST['submid'];
//      $file_name = $_FILES['addsupfile']['name'];
//      $file_size = $_FILES['addsupfile']['size'];
//      $file_tmp = $_FILES['addsupfile']['tmp_name'];
//      $file_type = $_FILES['addsupfile']['type'];
//      $file_ext=strtolower(end(explode('.',$_FILES['addsupfile']['name'])));
//      
//      $expensions= array("doc","docx","jpg","jpeg","png","pdf");
//      
//      if(in_array($file_ext,$expensions)=== false){
//         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
//      }
//      
//      if($file_size > 2097152) {
//         $errors[]='File size must be less than 2 MB';
//      }
//      
//      if(empty($errors)==true) {
//            
//            $urllink = $_POST['urllink'];
//            $useurl = substr($urllink, 0, strpos($urllink, "?"));
////            echo $useurl;
//            $id = $_POST['submid'];
//            $doctype = $_POST['doctype'];
//            $resdoctype = explode(',', $doctype);
////            print_r($resdoctype);
//            $temp = explode(".", $file_name);
//            $date = date("mdy", strtotime("now"));
//            date_default_timezone_set('Asia/Manila');
//            $datetime = date("Y-m-d H:i:s",strtotime("now"));
//            $times = "1";
//            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1']."".end($temp);
//            $path_dir = "uploads/main/".$rename_filename;
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
////            move_uploaded_file($file_tmp,"uploads/main/".$file_name);
//                $doctypefinal = $resdoctype['0'];
//    //            print_r($doctypefinal);
//
//                $uploadinfor = array(
//                //"file_id" => "",
//                "sub_id" => $id,
//                "revision" => "1",
//                "finaldoc" => "1",
//                "file_name" => $rename_filename,
//                "orig_filename" => $file_name,
//                "file_type" => $file_type,
//                "file_size" => $file_size,
//                "date_uploaded" => $datetime,
//                "date_modified" =>$datetime,
//                "kind" => $_POST['kind'],
//                "doctype" => $doctypefinal,
//                "path"=> $path_dir,
//                "username" => $_POST['userid']
//                );  
//    //           print_r($uploadinfor);
//                
//                if($obj->upload_info("document", $uploadinfor, $id, $doctypefinal, $useurl)){
//                }
//                
//            }
//            else{
//                echo "FAILED";
//                echo "</p>";
//                echo '<pre>';
//                echo 'Here is some more debugging info:';
//                print_r($_FILES);
//                print "</pre>"; exit;
//            }
//      }else{
//         print_r($errors);
//      }
//   }   

if(isset($_FILES['addsupfilepost'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['addsupfilepost']['name'];
      $file_size = $_FILES['addsupfilepost']['size'];
      $file_tmp = $_FILES['addsupfilepost']['tmp_name'];
      $file_type = $_FILES['addsupfilepost']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['addsupfilepost']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
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
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
//            move_uploaded_file($file_tmp,"uploads/main/".$file_name);
                $doctypefinal = $resdoctype['0'];
    //            print_r($doctypefinal);

                $uploadinfor = array(
                //"file_id" => "",
                "sub_id" => $id,
                "post_request_times" => $_POST['rqtimes'],
                "post_revision" => "1",
                "post_finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => $_POST['kind'],
                "doctype" => $doctypefinal,
                "path"=> $path_dir,
                "username" => $_POST['userid']
                );  
    //           print_r($uploadinfor);
                
                if($obj->insert_record("document", $uploadinfor)){
                     header("location:reuploadpost.php?id=".$id."&idt=".$_POST['rqtimes']);
                }
                else{echo "not inserted";}
                
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
   
   
   
if (isset($_GET['delete'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id"=>$id);
//            print_r($where);
            if($obj->delete_record("document",$where)){
                header("location:incomplete.php?id=".$subid);
            }
        }
        
    } 
}

if(isset($_POST['submitincomplete'])){
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => "0",
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php");
    }        
}


if(isset($_POST['submitincompletepa'])){
    $id = $_POST['submid'];
    
    $getstatus = $obj->getlaststatus($id);
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => $getstatus,
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php#approved");
    }
    
    $where = array(
        "sub_id" => $id,
        "post_approval_type" => $_POST['ppaid'],
        "revision" => $_POST['revision']
    );
    $change = array(
        "newsubmit" => "1"
    );
    if($obj->update_record("document_postapproval", $where, $change)){
        header("location:dashboard.php#approved");
    }
    
}

if(isset($_FILES['resubmit'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['resubmit']['name'];
      $file_size = $_FILES['resubmit']['size'];
      $file_tmp = $_FILES['resubmit']['tmp_name'];
      $file_type = $_FILES['resubmit']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['resubmit']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
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
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $revision = $resdoctype['2'] + 1;
            $rename_filename = $id."-".$date."-".$revision."-".$resdoctype['1'].".".end($temp);
            
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            $getmaxb = $obj->getmaxbatchforupload($id);    
                
            $doctypefinal = $resdoctype['0'];            
            
            $uploadinfor = array(
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
            "doctype" => $doctypefinal,
            "doctypetimes" => $resdoctype['3'],
            "batchnum" => $getmaxb+1,
            "newsubmit" => "1",
            "username" => $_POST['userid'],
            "path" => "uploads/main/".$rename_filename
            ); 
             
                if($obj->insert_record("document", $uploadinfor)){
                    header("location:reupload.php?id=".$_POST['submid']);
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
      }
      else{
         print_r($errors);
      }
   }

if(isset($_FILES['resubmitpa'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['resubmitpa']['name'];
      $file_size = $_FILES['resubmitpa']['size'];
      $file_tmp = $_FILES['resubmitpa']['tmp_name'];
      $file_type = $_FILES['resubmitpa']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['resubmitpa']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
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
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $revision = $resdoctype['2'] + 1;
            $rename_filename = $id."-".$date."-".$revision."-".$resdoctype['1'].".".end($temp);
            
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            $ppaid = $obj->getMaxValueofppa($id);
            $getmaxb = $obj->getmaxbatchforuploadpa($id,$ppaid);    
                
            $doctypefinal = $resdoctype['0'];            
            
            $uploadinfor = array(
            "sub_id" => $id,
            "revision" => $revision,
            "finaldoc" => "1",
            "post_approval_type" => $_POST['ppaid'],
            "file_name" => $rename_filename,
            "orig_filename" => $file_name,
            "file_type" => $file_type,
            "file_size" => $file_size,
            "date_uploaded" => $datetime,
            "date_modified" =>$datetime,
            "kind" => $_POST['kind'],
            "doctype" => $doctypefinal,
            "doctypetimes" => $resdoctype['3'],
            "batchnum" => $getmaxb+1,
            "newsubmit" => "1",
            "username" => $_POST['userid'],
            "path" => "uploads/main/".$rename_filename
            ); 
             
                if($obj->insert_record("document_postapproval", $uploadinfor)){
                    header("location:reuploadpa.php?id=".$_POST['submid']);
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
      }
      else{
         print_r($errors);
      }
   }   
   
if(isset($_FILES['imageiccsubmit'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['imageiccsubmit']['name'];
      $file_size = $_FILES['imageiccsubmit']['size'];
      $file_tmp = $_FILES['imageiccsubmit']['tmp_name'];
      $file_type = $_FILES['imageiccsubmit']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['imageiccsubmit']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = $_POST['docname'];
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = $_POST['revision'] + 1;
            $rename_filename = $id."-".$date."-".$times."-$resdoctype.".end($temp);
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            $doctypefinal = $resdoctype['0'];
            
            $uploadinfor = array(
            "file_id" => "",
            "sub_id" => $id,
            "finaldoc" => "1",
            "file_name" => $rename_filename,
            "orig_filename" => $file_name,
            "file_type" => $file_type,
            "file_size" => $file_size,
            "date_uploaded" => $datetime,
            "date_modified" =>$datetime,
            "kind" => $_POST['kind'],
            "doctype" => $doctype,
            "username" => $_POST['username'],
            "path" => "uploads/main/".$rename_filename
            );  
            print_r($uploadinfor);
            if($obj->upload_info("document", $uploadinfor, $id, $doctype, $useurl, $times)){
            } 
            
            $wherec = array(
                "sub_id" => $id, 
                "status_action" => "3", 
                "status_date" => $datetime,
                "status_username" => $_POST['username']);
            if($inserticc = $obj->insert_record("proposal_status", $wherec)){
                
            }
//            
            $update = array(
                "sent" => "0", 
                "submit" => "1", 
                "datetime" => $datetime);
            $whereig = array(
                "sub_id" => $id);
            if($insertindistat = $obj->update_record("indigenous", $whereig, $update)){
                
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
   
   
if(isset($_FILES['resubmitpost'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['resubmitpost']['name'];
      $file_size = $_FILES['resubmitpost']['size'];
      $file_tmp = $_FILES['resubmitpost']['tmp_name'];
      $file_type = $_FILES['resubmitpost']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['resubmitpost']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
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
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $revision = $resdoctype['2'] + 1;
            $rename_filename = $id."-".$date."-".$revision."-".$resdoctype['1'].".".end($temp);
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
//move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $doctypefinal = $resdoctype['0'];
//            print_r($doctypefinal);
            
            
            $uploadinfor = array(
            "file_id" => "",
            "sub_id" => $id,
            "post_request_times" => $_POST['rqtimes'],
            "post_revision" => $revision,
            "post_finaldoc" => "1",
            "file_name" => $rename_filename,
            "orig_filename" => $file_name,
            "file_type" => $file_type,
            "file_size" => $file_size,
            "date_uploaded" => $datetime,
            "date_modified" =>$datetime,
            "kind" => $_POST['kind'],
            "doctype" => $doctypefinal,
            "username" => $_POST['userid'],
            "path" => "uploads/main/".$rename_filename
            );  
//            print_r($uploadinfor); exit;
            if($obj->insert_record("document", $uploadinfor)){
                header("location:".$useurl."?id=".$id."&idt=".$_POST['rqtimes']);
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
   
   if(isset($_POST['resubmitfiles'])){
    $id = $_POST['submid'];
    
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => '14',
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php#revise");
    }
    
    $updateRL = array(
        "finaldoc" => '0'
    );
    $where = array(
        "sub_id" => $id,
        "kind" => 'RL'
    );
    if($obj->update_record("document", $where, $updateRL)){
        header("location:dashboard.php#revise");
    }
    
}

   
if(isset($_POST['submitappeal'])){
    $id = $_POST['submid'];
    
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => '26',
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php#revise");
    }
    
    $updateRL = array(
        "finaldoc" => '0',
        "newsubmit" => '0'
    );
    $where = array(
        "sub_id" => $id,
        "kind" => 'DAL'
    );
    if($obj->update_record("document", $where, $updateRL)){
        header("location:dashboard.php#revise");
    }
    
}
   
if(isset($_POST['resubmitfiles'])){
    $userid = $_POST['userid'];
    $subid = $_POST['submid'];
//    $userid = "3";
//    $subid = "7";
    
    $table = array("membership_users", "phrepuser", "rec_list", "submission", "proposal");
    $join_on = array("memberID", "username", "id", "secretary", "id", "reclist_id", "sub_id", "sub_id");
    $where = array("proposal.username" => $userid, "proposal.sub_id" => $subid);
    $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
    if($getdata){
        foreach($getdata as $dt){
            $secemail = $dt['email'];
            $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['erc_name'],$dt['prop_ptitle']);
//            print_r($change);
        }        
    }
    
    
    $table1 = array("proposal", "phrepuser");
    $join_on1 = array("username", "id");
    $where1 = array("proposal.sub_id" => $subid);
    $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
    if($getdata1){
        foreach($getdata1 as $dt1){
            array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname']);
//            print_r($change);
        }
    }
    $where3 = array("id" => "18");
    $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
    foreach ($getTemplate as $tplate) {
        $subject = $tplate['subject'];
        $template = $tplate['body'];
    }

    $find = array("{secretaryname}","{RECName}","{ptitle}","{researcherfullname}"); 
    $readytosend = str_replace($find, $change, $template);
    
    
    if($obj->sendEmail1($secemail, $readytosend, $subject)){
         header("location:dashboard.php");}

}
   if(isset($_POST['resubmitfilespa'])){
    $id = $_POST['submid'];
    
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => '19',
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php#approved");
    }
    
    $updateRL = array(
        "finaldoc" => '0'
    );
    $where = array(
        "sub_id" => $id,
        "kind" => 'RL'
    );
    if($obj->update_record("document_postapproval", $where, $updateRL)){
        header("location:dashboard.php#approved");
    }
    
}


if(isset($_POST['amend'])){
       $id = $_POST['submid'];
       header("location:amend.php?id=".$id);
   }
   
if(isset($_FILES['amendment'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['amendment']['name'];
      $file_size = $_FILES['amendment']['size'];
      $file_tmp = $_FILES['amendment']['tmp_name'];
      $file_type = $_FILES['amendment']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['amendment']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
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
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $revision = $resdoctype['2'] + 1;
            $rename_filename = $id."-".$date."-".$revision."-".$resdoctype['1'].".".end($temp);
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
                
            $ppaid = $obj->getMaxValueofppa($id);
//move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $doctypefinal = $resdoctype['0'];
//            print_r($doctypefinal);
            
            if($resdoctype['7'] == "document"){
                                            
                $uploadinfor = array(
                    "sub_id" => $id,
                    "revision" => "1",
                    "finaldoc" => "1",
                    "post_approval_type" => $_POST['ppaid'],
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" => $datetime,
                    "kind" => $_POST['kind'],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => $resdoctype['6'],
                    "batchnum" => "1",
                    "newsubmit" => "1",
                    "username" => $_POST['userid'],
                    "path" => "uploads/main/" . $rename_filename,
                    "documentfile_id" => $resdoctype['4'],
                    "tbl" => "1"
                );

                if ($obj->insert_record("document_postapproval", $uploadinfor)) {

                    $where = array("file_id" => $resdoctype['4']);
                    $fields = array("finaldoc" => "0");
                    if($obj->update_record($resdoctype['7'], $where, $fields)){
                        header("location:amend.php?id=".$id);
                    }
                }
            }
            else if($resdoctype['7'] == "document_postapproval"){
                                            
                $uploadinfor = array(
                    "sub_id" => $id,
                    "revision" => "1",
                    "finaldoc" => "1",
                    "post_approval_type" => $_POST['ppaid'],
                    "file_name" => $rename_filename,
                    "orig_filename" => $file_name,
                    "file_type" => $file_type,
                    "file_size" => $file_size,
                    "date_uploaded" => $datetime,
                    "date_modified" => $datetime,
                    "kind" => $_POST['kind'],
                    "doctype" => $doctypefinal,
                    "doctypetimes" => $resdoctype['6'],
                    "batchnum" => "1",
                    "newsubmit" => "1",
                    "username" => $_POST['userid'],
                    "path" => "uploads/main/" . $rename_filename,
                    "documentfile_id" => $resdoctype['4'],
                    "tbl" => "2"
                );

                if ($obj->insert_record("document_postapproval", $uploadinfor)) {

                    $where = array("file_id" => $resdoctype['4']);
                    $fields = array("finaldoc" => "0");
                    if($obj->update_record($resdoctype['7'], $where, $fields)){
                        header("location:amend.php?id=".$id);
                    }
                }
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
      }
      else{
         print_r($errors);
      }
   }
   
if(isset($_FILES['uploadamend'])){
        
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['uploadamend']['name'];
      $file_size = $_FILES['uploadamend']['size'];
      $file_tmp = $_FILES['uploadamend']['tmp_name'];
      $file_type = $_FILES['uploadamend']['type'];
      $file_exts = explode('.',$_FILES['uploadamend']['name']);
      $file_ext=strtolower(end($file_exts));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                $doctypefinal = $resdoctype['0'];
                
                $ifthere = array(
                "sub_id" => $id,
                "post_finaldoc" => "1",
                "kind" => "ARF",
                "doctype" => "17"
                ); 
                
                $ppaid = $obj->getMaxValueofppa($id);
                $getmaxb = $obj->getmaxbatchforuploadpost($id, $ppaid);
                $getrevpost = $obj->getmaxrevisionpostapproval($id, "ARF",$ppaid);
                
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => $getrevpost+1,
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => "ARF",
                "doctype" => $resdoctype['0'],
                "doctypetimes" => "1",
                "batchnum" => $getmaxb+1,
                "newsubmit" => "1",
                "path"=> $path_dir,
                "username" => $_POST['userid'],
                "post_approval_type" => $ppaid
                );  
                if($obj->fetch_record_with_where("document_postapproval", $ifthere)){
                    header("location:amend.php?id=".$id);
                }
                else{
                    if($obj->insert_record("document_postapproval", $uploadinfor)){
                        header("location:amend.php?id=".$id);
                    }                    
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
   
if(isset($_FILES['uploadecform'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['uploadecform']['name'];
      $file_size = $_FILES['uploadecform']['size'];
      $file_tmp = $_FILES['uploadecform']['tmp_name'];
      $file_type = $_FILES['uploadecform']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['uploadecform']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
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
            $resdoctype = explode(',', $doctype);
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $revision = $_POST['revmax'] + 1;
            $rename_filename = $id."-".$date."-".$revision."-".$_POST['docname'].".".end($temp);
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            //move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $dpath = "uploads/main/".$rename_filename;
            $doctypefinal = $resdoctype['0'];
//            print_r($doctypefinal);
            
            
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
            "doctype" => $doctypefinal,
            "username" => $_POST['userid'],
            "path" => $dpath
            );  
//            print_r($uploadinfor);
                if($obj->upload_ethicalclearance("document", $uploadinfor, $id, $doctypefinal, $useurl, $revision)){
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
   
if (isset($_GET['deleteamend'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id"=>$id);
//            print_r($where);
            if($obj->delete_record("document_postapproval",$where)){
                header("location:amend.php?id=".$subid);
            }
        }
        
    } 
}

if (isset($_GET['deletefr'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id"=>$id);
//            print_r($where);
            if($obj->delete_record("document",$where)){
                header("location:finalreport.php?id=".$subid);
            }
        }
        
    } 
}

if (isset($_GET['deleteethicalclerance'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id"=>$id);
//            print_r($where);
            if($obj->delete_record("document",$where)){
                header("location:extendec.php?id=".$subid);
            }
        }
        
    } 
}

if (isset($_GET['deleteamendedfile'])) {
    if (isset($_GET['subid'])) {
        if (isset($_GET['id'])){
            if(isset($_GET['doctype'])){
                if(isset($_GET['tb'])){
                    if(isset($_GET['idd'])){
                        $tb = $_GET['tb'];
                        $idd = $_GET['idd'];

                        if($tb == 1){ //DOCUMENT

                            $id = $_GET['id'];
                            $subid = $_GET['subid'];

                            $where = array("file_id"=>$id);

                            if($obj->delete_record("document_postapproval",$where)){
                                $where2 = array("file_id"=>$idd);
                                $fields = array("finaldoc" => "1"); 
                                if($obj->update_record("document", $where2, $fields)){
                                    header("location:amend.php?id=".$subid);
                                }   
                            }
                        }

                        else if($tb == 2){ //DOCUMENT_POSTAPPROVAL

                            $id = $_GET['id'];
                            $subid = $_GET['subid'];

                            $where = array("file_id"=>$id);

                            if($obj->delete_record("document_postapproval",$where)){
                                $where2 = array("file_id"=>$idd);
                                $fields = array("finaldoc" => "1"); 
                                if($obj->update_record("document_postapproval", $where2, $fields)){
                                    header("location:amend.php?id=".$subid);
                                }   
                            }
                        }
                        
                    }
                    
                    
                    
                    
                }              
            }
        }        
    } 
}

if(isset($_POST['submitfr'])){
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => "9",
        "status_date" => $datetime,
        "status_username" => $_POST['usrequestextensionerid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php");
    }
    
    $maxrev = $_POST['maxrevfr'] + 1;
    $updatefr = array(
        "revision" => $maxrev  
    );
    $wherefr = array(
        "sub_id" => "$id",
        "revision" => "0",
        "doctype" => "19"
    );
    if($obj->update_record("document", $wherefr, $updatefr)){}
}

if(isset($_POST['requestamend'])){
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => "7",
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php#approved");
    }
    
    $updateEFDoc = array(
        "finaldoc" => "0"
    );
    $wheref = array(
        "sub_id" => $id,
        "doctype" => "17",
        "newsubmit" => "0"
    );
    
    if($obj->update_record("document_postapproval", $wheref, $updateEFDoc)){
        header("location:dashboard.php#approved");
    }    
}

if(isset($_POST['requestextension'])){
//    var_dump($_POST); exit; 
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => "8",
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php#approved");
    }
    
    
    $whereecc = array(
        "sub_id" => $id
    );
    $setecc = array(
        "exp" => "1"
    );
    if($obj->update_record("ethical_clearance", $whereecc, $setecc)){
        header("location:dashboard.php#approved");
    }
    
//    $getmaxreq = $obj->getmaxreq($id);
//    $addpost = array(
//        "subid" => $id,
//        "pa_id" => '1',
//        "pa_request" => $getmaxreq+1,
//        "pa_date" => $datetime,
//        "pa_status" => "onreview"
//    );
//    if($obj->insert_record("proposal_post_approval", $addpost)){
//        header("location:dashboard.php");
//    }
    
//    $getmaxecdoc = $obj->getmaxecdoc($id);
    $whereeccdoc = array(
        "sub_id" => $id,
        "kind" => "EC"
    );
    $seteccdoc = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document", $whereeccdoc, $seteccdoc)){
        header("location:dashboard.php#approved");
    }
    
}



if(isset($_POST['finalreport'])){
//    var_dump($_POST); exit; 
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => "21",
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:dashboard.php#approved");
    }
    
    $where = array(
        "sub_id" => $id,
        "kind" => "RL"
    );
    $update = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document_postapproval", $where, $update)){
        header("location:dashboard.php#approved");
    }
    
}


//if(isset($_FILES['uploadfr'])){
//      $errors= array();      
//      $id = $_POST['submid'];
//      $file_name = $_FILES['uploadfr']['name'];
//      $file_size = $_FILES['uploadfr']['size'];
//      $file_tmp = $_FILES['uploadfr']['tmp_name'];
//      $file_type = $_FILES['uploadfr']['type'];
//      $file_ext=strtolower(end(explode('.',$_FILES['uploadfr']['name'])));
//      
//      $expensions= array("doc","docx");
//      
//      if(in_array($file_ext,$expensions)=== false){
//         $errors[]="extension not allowed, please choose a doc or docx file.";
//      }
//      
//      if($file_size > 10485760) {
//         $errors[]='File size must be less than 10 MB';
//      }
//      
//      if(empty($errors)==true) {
//            
//            $urllink = $_POST['urllink'];
//            $useurl = substr($urllink, 0, strpos($urllink, "?"));
////            echo $useurl;
//            $id = $_POST['submid'];
//            $doctype = $_POST['doctype'];
//            $resdoctype = explode(',', $doctype);
////            print_r($resdoctype);
//            $temp = explode(".", $file_name);
//            date_default_timezone_set('Asia/Manila');
//            $date = date("mdyHis", strtotime("now"));
//            $datetime = date("Y-m-d H:i:s",strtotime("now"));
//            $revision = "0";
//            $rename_filename = $file_name;
//            
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
//            
//            //move_uploaded_file($file_tmp,"uploads/main/".$file_name);
//            $dpath = "uploads/main/".$rename_filename;
//            $doctypefinal = $resdoctype['0'];
////            print_r($doctypefinal);
//            
//            
//            $uploadinfor = array(
//            "file_id" => "",
//            "sub_id" => $id,
//            "revision" => $revision,
//            "finaldoc" => "1",
//            "file_name" => $rename_filename,
//            "orig_filename" => $file_name,
//            "file_type" => $file_type,
//            "file_size" => $file_size,
//            "date_uploaded" => $datetime,
//            "date_modified" =>$datetime,
//            "kind" => $_POST['kind'],
//            "doctype" => $doctypefinal,
//            "username" => $_POST['userid'],
//            "path" => $dpath
//            );  
////            print_r($uploadinfor);
//         if($obj->upload_info_finalReport("document", $uploadinfor, $id, $doctypefinal, $useurl, $file_name)){
//        }
//        }
//        else{
//            echo "FAILED";
//            echo "</p>";
//            echo '<pre>';
//            echo 'Here is some more debugging info:';
//            print_r($_FILES);
//            print "</pre>"; exit;
//            }
//      }else{
//         print_r($errors);
//      }
//   }
   
if (isset($_GET['deleteunf'])) {
    if (isset($_GET['id'])) {
        if(isset($_GET['u'])){
            
            $key = $obj->getmagicword();
            $cleanid = $obj->decrypt($_GET['id'], $key);
            $cleanuid = $obj->decrypt($_GET['u'], $key);
                            
            $where = array("username" => $cleanuid, "sub_id" => $cleanid);
            if($obj->delete_record("submission", $where)){
                header("location:dashboard.php");
            }
        }
    }

}
   
if (isset($_GET['deleteunfp'])) {
    if (isset($_GET['id'])) {
        if(isset($_GET['u'])){
        $id = $_GET['id'];
        $u = $_GET['u'];
                
        $where = array("sub_id" => $id);
        if($obj->delete_record("submission", $where)){
            $obj->delete_record("sponsorlist", $where);
            $obj->delete_record("sponsor", $where);
            $obj->delete_record("researchfields", $where);
            $obj->delete_record("studentres", $where);
            $obj->delete_record("studentresdet", $where);
            $obj->delete_record("country_multi", $where);
            $obj->delete_record("nationwideres", $where);
            $obj->delete_record("nationregion", $where);
            $obj->delete_record("humansubject", $where);
            $obj->delete_record("hmnsubj", $where);
            $obj->delete_record("datacol", $where);
            $obj->delete_record("reviewcomdata", $where);
            $obj->delete_record("reviewcom", $where);
            $obj->delete_record("monetary_source", $where);
            $obj->delete_record("assessment_ans", $where);
            $obj->delete_record("risklevel", $where);
            $obj->delete_record("coninterest", $where);
            $obj->delete_record("riskapply", $where);
            $obj->delete_record("potenbenefits", $where);
            $obj->delete_record("proposal", $where);
            $obj->delete_record("document", $where);
            header("location:dashboard.php");
        }
        }
    }

}

if(isset($_POST['download'])){
    $file = "uploads/forms/";
    $file .= $_POST['dlfile'];
    
    if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
    }
    else{
        echo "WALA";
    }
    
}

if(isset($_FILES['uploadece'])){
        
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['uploadece']['name'];
      $file_size = $_FILES['uploadece']['size'];
      $file_tmp = $_FILES['uploadece']['tmp_name'];
      $file_type = $_FILES['uploadece']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['uploadece']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                
                $ppaid = $obj->getMaxValueofppa($id);
                
                $doctypefinal = $resdoctype['0'];
                $getmaxb = $obj->getmaxbatchforuploadpost($id, $ppaid);
                $maxsuppfile = $obj->getmaxsupfilestimespa($id, $doctypefinal, $ppaid);
                $getrevpost = $obj->getmaxrevision($id, $ppaid);
                
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => $getrevpost+1,
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => $_POST['kind'],
                "doctype" => $doctypefinal,
                "doctypetimes" => $maxsuppfile+1,
                "batchnum" => $getmaxb+1,
                "newsubmit" => "1",
                "post_approval_type" => $ppaid,
                "path"=> $path_dir,
                "username" => $_POST['userid']
                );  
                
                if($obj->insert_record("document_postapproval", $uploadinfor)){
                    header("location:extendec.php?id=".$id);
                }
                else{echo"Error";}
                
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

if(isset($_FILES['amendsup'])){
        
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['amendsup']['name'];
      $file_size = $_FILES['amendsup']['size'];
      $file_tmp = $_FILES['amendsup']['tmp_name'];
      $file_type = $_FILES['amendsup']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['amendsup']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                
                $doctypefinal = $resdoctype['0'];
                $getrevpost = $obj->getmaxrevision($id, "1");
                $ppaid = $obj->getMaxValueofppa($id);
                $getmaxb = $obj->getmaxbatchforuploadpost($id, $ppaid);
                $maxsuppfile = $obj->getmaxsupfilestimespa($id, $doctypefinal,$ppaid);
                
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => "1",
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => $_POST['kind'],
                "doctype" => $doctypefinal,
                "doctypetimes" => $maxsuppfile+1,
                "batchnum" => "1",
                "newsubmit" => "1",
                "post_approval_type" => $ppaid,
                "path"=> $path_dir,
                "username" => $_POST['userid'],
                "tbl" => "2"
                );  
                if($obj->insert_record("document_postapproval", $uploadinfor)){
                    header("location:amend.php?id=".$id);
                }
                else{echo"Error";}
                
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

   
   if(isset($_FILES['uploadecemain'])){
        
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['uploadecemain']['name'];
      $file_size = $_FILES['uploadecemain']['size'];
      $file_tmp = $_FILES['uploadecemain']['tmp_name'];
      $file_type = $_FILES['uploadecemain']['type'];
      $file_exts = explode('.',$_FILES['uploadecemain']['name']);
      $file_ext=strtolower(end($file_exts));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                
                $doctypefinal = $resdoctype['0'];
                
                $ifthere = array(
                "sub_id" => $id,
                "finaldoc" => "1",
                "kind" => "ECF",
                "doctype" => "21"
                ); 
                
                $ppaid = $obj->getMaxValueofppa($id);
                
                $getmaxb = $obj->getmaxbatchforuploadpost($id, $ppaid);
                $getrevpost = $obj->getmaxrevisionpostapproval($id, "ECF", $ppaid);
                
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => $getrevpost+1,
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => "ECF",
                "doctype" => $resdoctype['0'],
                "doctypetimes" => "1", 
                "batchnum" => $getmaxb+1,
                "newsubmit" => "1",
                "path"=> $path_dir,
                "username" => $_POST['userid'],
                "post_approval_type" => $ppaid
                );  
                
                // print_r($uploadinfor);
                
                if($obj->fetch_record_with_where("document_postapproval", $ifthere)){
                    header("location:extendec.php?id=".$id);
                }
                else{
                    if($obj->insert_record("document_postapproval", $uploadinfor)){
                        header("location:extendec.php?id=".$id);
                    }                    
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

   
   if(isset($_FILES['uploadprform'])){
        
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['uploadprform']['name'];
      $file_size = $_FILES['uploadprform']['size'];
      $file_tmp = $_FILES['uploadprform']['tmp_name'];
      $file_type = $_FILES['uploadprform']['type'];
      $file_exts = explode('.',$_FILES['uploadprform']['name']);
      $file_ext=strtolower(end($file_exts));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                
                $doctypefinal = $resdoctype['0'];
                
                $ifthere = array(
                "sub_id" => $id,
                "finaldoc" => "1",
                "kind" => "PRF",
                "doctype" => "28"
                ); 
                
                $ppaid = $obj->getMaxValueofppa($id);
                
                $getmaxb = $obj->getmaxbatchforuploadpost($id, $ppaid);
                $getrevpost = $obj->getmaxrevisionpostapproval($id, "PRF", $ppaid);
                
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => $getrevpost+1,
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => "PRF",
                "doctype" => $resdoctype['0'],
                "doctypetimes" => "1", 
                "batchnum" => $getmaxb+1,
                "newsubmit" => "1",
                "path"=> $path_dir,
                "username" => $_POST['userid'],
                "post_approval_type" => $ppaid
                );  
                
                print_r($uploadinfor);
                
                if($obj->fetch_record_with_where("document_postapproval", $ifthere)){
                    header("location:progressreport.php?id=".$id);
                }
                else{
                    if($obj->insert_record("document_postapproval", $uploadinfor)){
                        header("location:progressreport.php?id=".$id);
                    }                    
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
   
      
   if(isset($_FILES['uploadfrform'])){
        
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['uploadfrform']['name'];
      $file_size = $_FILES['uploadfrform']['size'];
      $file_tmp = $_FILES['uploadfrform']['tmp_name'];
      $file_type = $_FILES['uploadfrform']['type'];
      $file_exts = explode('.',$_FILES['uploadfrform']['name']);
      $file_ext=strtolower(end($file_exts));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                
                $doctypefinal = $resdoctype['0'];
                
                $ifthere = array(
                "sub_id" => $id,
                "finaldoc" => "1",
                "kind" => "FRF",
                "doctype" => "31"
                ); 
                
                $ppaid = $obj->getMaxValueofppa($id);
                
                $getmaxb = $obj->getmaxbatchforuploadpost($id, $ppaid);
                $getrevpost = $obj->getmaxrevisionpostapproval($id, "FRF", $ppaid);
                
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => $getrevpost+1,
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => "FRF",
                "doctype" => $resdoctype['0'],
                "doctypetimes" => "1", 
                "batchnum" => $getmaxb+1,
                "newsubmit" => "1",
                "path"=> $path_dir,
                "username" => $_POST['userid'],
                "post_approval_type" => $ppaid
                );  
                
                print_r($uploadinfor);
                
                if($obj->fetch_record_with_where("document_postapproval", $ifthere)){
                    header("location:finalreport.php?id=".$id);
                }
                else{
                    if($obj->insert_record("document_postapproval", $uploadinfor)){
                        header("location:finalreport.php?id=".$id);
                    }                    
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
   
   if (isset($_GET['deleteece'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id"=>$id);
//            print_r($where);
            if($obj->delete_record("document_postapproval",$where)){
                header("location:extendec.php?id=".$subid);
            }
        }
        
    } 
}

if (isset($_GET['erase'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $loc = $_GET['loc'];
            $where = array("file_id"=>$id);
//            print_r($where);
            if($obj->delete_record("document_postapproval",$where)){
                header("location:".$loc.".php?id=".$subid);
            }
        }
        
    } 
}


if(isset($_FILES['uploadpr'])){
        
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['uploadpr']['name'];
      $file_size = $_FILES['uploadpr']['size'];
      $file_tmp = $_FILES['uploadpr']['tmp_name'];
      $file_type = $_FILES['uploadpr']['type'];
      $file_exts = explode('.',$_FILES['uploadpr']['name']);
      $file_ext=strtolower(end($file_exts));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                
                $ppaid = $obj->getMaxValueofppa($id);
                
                $doctypefinal = $resdoctype['0'];
                $getmaxb = $obj->getmaxbatchforuploadpost($id, $ppaid);
                $maxsuppfile = $obj->getmaxsupfilestimespa($id, $doctypefinal, $ppaid);
//                $getrevpost = $obj->getmaxrevision($id, $ppaid);
//                if($getrevpost == '1'){$getrevpost = '1';}
                
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => "1",
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => $_POST['kind'],
                "doctype" => $doctypefinal,
                "doctypetimes" => $maxsuppfile+1,
                "batchnum" => $getmaxb+1,
                "newsubmit" => "1",
                "post_approval_type" => $ppaid,
                "path"=> $path_dir,
                "username" => $_POST['userid']
                );  
                
                if($obj->insert_record("document_postapproval", $uploadinfor)){
                    header("location:progressreport.php?id=".$id);
                }
                else{echo"Error";}
                
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
   

if(isset($_FILES['uploadfr'])){
        
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['uploadfr']['name'];
      $file_size = $_FILES['uploadfr']['size'];
      $file_tmp = $_FILES['uploadfr']['tmp_name'];
      $file_type = $_FILES['uploadfr']['type'];
      $fle_exts = explode('.',$_FILES['uploadfr']['name']);
      $file_ext=strtolower(end($fle_exts));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = "1";
            $rename_filename = $id."-".$date."-".$times."-".$resdoctype['1'].".".end($temp);
            $path_dir = "uploads/main/".$rename_filename;
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
                
                $ppaid = $obj->getMaxValueofppa($id);
                
                $doctypefinal = $resdoctype['0'];
                $getmaxb = $obj->getmaxbatchforuploadpost($id, $ppaid);
                $maxsuppfile = $obj->getmaxsupfilestimespa($id, $doctypefinal, $ppaid);
                $getrevpost = $obj->getmaxrevision($id, "1");
                
                $uploadinfor = array(
                "sub_id" => $id,
                "revision" => $getrevpost+1,
                "finaldoc" => "1",
                "file_name" => $rename_filename,
                "orig_filename" => $file_name,
                "file_type" => $file_type,
                "file_size" => $file_size,
                "date_uploaded" => $datetime,
                "date_modified" =>$datetime,
                "kind" => $_POST['kind'],
                "doctype" => $doctypefinal,
                "doctypetimes" => $maxsuppfile+1,
                "batchnum" => $getmaxb+1,
                "newsubmit" => "1",
                "post_approval_type" => $ppaid,
                "path"=> $path_dir,
                "username" => $_POST['userid']
                );  
                                
                if($obj->insert_record("document_postapproval", $uploadinfor)){
                    header("location:finalreport.php?id=".$id);
                }
                else{echo"Error";}
                
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
   
      

if (isset($_GET['d'])) {
    if (isset($_GET['p'])) {
        if (isset($_GET['u'])) {
            $subid = $_GET['p'];
            $user = $_GET['u'];
            $fields = array(
                "sub_id" => $subid,
                "status_action" => '17',
                "status_date" => $datetime,
                "status_username" => $user
            );
            if ($obj->insert_record("proposal_status", $fields)) {
                header("location:dashboard.php");
            }
        }
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
   
   $key = $obj->getmagicword(); 
   $id = $obj->encrypt($userid,$key);
   if($obj->update_record("phrepuser", $where, $fields)){
       header("location: edit_profile.php?u=".$id);
   }
   
   $where1 = array("memberID" => $username);
   $fields1 = array("email" => $_POST['email']);
   if($obj->update_record("membership_users", $where1, $fields1)){
       header("location: edit_profile.php?u=".$id);
   }
   
   
}


if (isset($_GET['deletedoc'])) {
    if (isset($_GET['d'])) {
        if (isset($_GET['id'])) {
            $fileid = $_GET['d'];
            $subid = $_GET['id'];
            $fields = array(
                "file_id" => $fileid
            );
            if ($obj->delete_record("document", $fields)) {
                header("location:incomplete.php?id=".$subid);
            }
        }
    }
}

if (isset($_GET['deletedocincpa'])) {
    if (isset($_GET['d'])) {
        if (isset($_GET['id'])) {
            $fileid = $_GET['d'];
            $subid = $_GET['id'];
            $fields = array(
                "file_id" => $fileid
            );
            if ($obj->delete_record("document_postapproval", $fields)) {
                header("location:incompletepa.php?id=".$subid);
            }
        }
    }
}



if (isset($_GET['deletedocpa'])) {
    if (isset($_GET['id'])) {
        $fileid = $_GET['id'];
        $sid = $_GET['sid'];
        $fields = array(
            "file_id" => $fileid
        );
        if ($obj->delete_record("document_postapproval", $fields)) {
            header("location:reuploadpa.php?id=" . $sid);
        }
    }
}


if (isset($_GET['amendpa'])) {
    if (isset($_GET['subid'])) {
        
        $uid = $_GET['uid'];
        $sid = $_GET['subid'];
        $where1 = array("subid" => $sid,"pa_request" => "2");
        $getexist = $obj->fetch_record_with_where("proposal_post_approval", $where1);
        
        if($getexist){//CHECKING IF PA_STATUS AND SUBID EXIST
            $getmaxreq = $obj->getmaxrequest($sid,"2");
            $where2 = array(
                "subid" => $sid,
                "pa_request" => "2",
                "pa_id" => $getmaxreq+1,
                "pa_status" => "onreview",
                "pa_date" => $datetime
            );
            if($obj->insert_record("proposal_post_approval", $where2)){
                header("location:amend.php?id=".$sid);
            }
            
            $where3 = array(
                "sub_id" => $sid,
                "status_action" => "22",
                "status_date" => $datetime,
                "status_username" => $uid
            );
            if($obj->insert_record("proposal_status", $where3)){
                header("location:amend.php?id=".$sid);
            }
            
        }
        else{ //IF NOT 
            $where2 = array(
                "subid" => $sid,
                "pa_request" => "2",
                "pa_id" => "1",
                "pa_status" => "onreview",
                "pa_date" => $datetime
            );
            if($obj->insert_record("proposal_post_approval", $where2)){
                header("location:amend.php?id=".$sid);
            } 
            
            $where3 = array(
                "sub_id" => $sid,
                "status_action" => "22",
                "status_date" => $datetime,
                "status_username" => $uid
            );
            if($obj->insert_record("proposal_status", $where3)){
                header("location:amend.php?id=".$sid);
            }
            
        }     
    }
}

if (isset($_GET['extendec'])) {
    if (isset($_GET['subid'])) {
        $sid = $_GET['subid'];
        
        $wheree = array("sub_id" => $_GET['subid']);
        $getreserr = $obj->fetch_record_with_where("proposal", $wheree);
        foreach($getreserr as $ress){$user = $ress['username'];}

        $where1 = array("subid" => $sid,"pa_request" => "1");
        $getexist = $obj->fetch_record_with_where("proposal_post_approval", $where1);
        
        if($getexist){//CHECKING IF PA_STATUS AND SUBID EXIST
            $getmaxreq = $obj->getmaxrequest($sid,"1");
            $where2 = array(
                "subid" => $sid,
                "pa_request" => "1",
                "pa_id" => $getmaxreq+1,
                "pa_status" => "onreview",
                "pa_date" => $datetime
            );
            if($obj->insert_record("proposal_post_approval", $where2)){
                header("location:extendec.php?id=".$sid);
            }   
            
            $where3 = array(
                "sub_id" => $sid,
                "status_action" => 22,
                "status_date" => $datetime,
                "status_username" => $user
            );
            if($obj->insert_record("proposal_status", $where3)){
                header("location:extendec.php?id=".$sid);
            }
            
            
        }
        else{ //IF NOT 
            $where2 = array(
                "subid" => $sid,
                "pa_request" => "1",
                "pa_id" => "1",
                "pa_status" => "onreview",
                "pa_date" => $datetime
            );
            if($obj->insert_record("proposal_post_approval", $where2)){
                header("location:extendec.php?id=".$sid);
            } 
            
            $where3 = array(
                "sub_id" => $sid,
                "status_action" => 22,
                "status_date" => $datetime,
                "status_username" => $user
            );
            if($obj->insert_record("proposal_status", $where3)){
                header("location:extendec.php?id=".$sid);
            }
        } 
    }
}

if (isset($_GET['prpa'])) {
    if (isset($_GET['subid'])) {
        $sid = $_GET['subid'];
        
        $wheree = array("sub_id" => $_GET['subid']);
        $getreserr = $obj->fetch_record_with_where("proposal", $wheree);
        foreach($getreserr as $ress){$user = $ress['username'];}
        
        $where1 = array("subid" => $sid,"pa_request" => "3");
        $getexist = $obj->fetch_record_with_where("proposal_post_approval", $where1);
        
        if($getexist){//CHECKING IF PA_STATUS AND SUBID EXIST
            $getmaxreq = $obj->getmaxrequest($sid,"3");
            $where2 = array(
                "subid" => $sid,
                "pa_request" => "3",
                "pa_id" => $getmaxreq+1,
                "pa_status" => "onreview",
                "pa_date" => $datetime
            );
            if($obj->insert_record("proposal_post_approval", $where2)){
                header("location:progressreport.php?id=".$sid);
            }  
            
            $where3 = array(
                "sub_id" => $sid,
                "status_action" => 22,
                "status_date" => $datetime,
                "status_username" => $user
            );
            if($obj->insert_record("proposal_status", $where3)){
                header("location:progressreport.php?id=".$sid);
            }            
            
        }
        else{ //IF NOT 
            $where2 = array(
                "subid" => $sid,
                "pa_request" => "3",
                "pa_id" => "1",
                "pa_status" => "onreview",
                "pa_date" => $datetime
            );
            if($obj->insert_record("proposal_post_approval", $where2)){
                header("location:progressreport.php?id=".$sid);
            } 
            
            $where3 = array(
                "sub_id" => $sid,
                "status_action" => 22,
                "status_date" => $datetime,
                "status_username" => $user
            );
            if($obj->insert_record("proposal_status", $where3)){
                header("location:progressreport.php?id=".$sid);
            } 
            
        } 
    }
}

if (isset($_GET['frpa'])) {
    if (isset($_GET['subid'])) {
        if(isset($_GET['uid'])){
        $sid = $_GET['subid'];
        $uid = $_GET['uid'];
        
        $sid = $_GET['subid'];
        $where1 = array("subid" => $sid,"pa_request" => "4");
        $getexist = $obj->fetch_record_with_where("proposal_post_approval", $where1);
        
        if($getexist){//CHECKING IF PA_STATUS AND SUBID EXIST
            $getmaxreq = $obj->getmaxrequest($sid,"4");
            $where2 = array(
                "subid" => $sid,
                "pa_request" => "4",
                "pa_id" => $getmaxreq+1,
                "pa_status" => "onreview",
                "pa_date" => $datetime
            );
            if($obj->insert_record("proposal_post_approval", $where2)){
                header("location:finalreport.php?id=".$sid);
            }

            $where3 = array(
                "sub_id" => $sid,
                "status_action" => "22",
                "status_date" => $datetime,
                "status_username" => $uid
            );
            if($obj->insert_record("proposal_status", $where3)){
                header("location:finalreport.php?id=".$sid);
            }
            
        }
        else{ //IF NOT 
            $where2 = array(
                "subid" => $sid,
                "pa_request" => "4",
                "pa_id" => "1",
                "pa_status" => "onreview",
                "pa_date" => $datetime
            );
            if($obj->insert_record("proposal_post_approval", $where2)){
                header("location:finalreport.php?id=".$sid);
            } 
            $where3 = array(
                "sub_id" => $sid,
                "status_action" => "22",
                "status_date" => $datetime,
                "status_username" => $uid
            );
            if($obj->insert_record("proposal_status", $where3)){
                header("location:finalreport.php?id=".$sid);
            }
            
        }
    }
    }
}

if(isset($_POST['amendtype'])){
    $subid = $_POST['subid'];
    $amendtype = $_POST['amendmenttype'];
    $amendicf = $_POST['amendicf'];
    
//    print_r($amendtype);exit;
    
    $getmaxid = $obj->getMaxValueofppa($subid);    
    
    
    $whered = array(
        "pid" => $getmaxid
    );
    if(($obj->delete_record("sub_request", $whered))){
        
    
        for($i=0;$i<count($amendtype);$i++){
            
            //KUNG DILI CHECK ANG ICF
            if((count($amendtype) == 1)&&($amendtype[$i]) == 1){
                $whered = array(
                    "pid" => $getmaxid
                );
                 if(($obj->delete_record("amendment_icf", $whered))){}
            }
            //KUNG DILI CHECK ANG ICF
            
            $where = array(
                "pid" => $getmaxid,
                "sreq_id" => $amendtype[$i]
            );
            if($obj->insert_record("sub_request", $where)){            
            }  
            
            if($amendtype[$i] == 2){
                
                $whered = array(
                    "pid" => $getmaxid
                );
                if(($obj->delete_record("amendment_icf", $whered))){         
                    for($i=0;$i<count($amendicf);$i++){
                        $where = array(
                            "pid" => $getmaxid,
                            "aicf_id" => $amendicf[$i]
                        );

                        if($obj->insert_record("amendment_icf", $where)){            
                        } 
                    }       
                }
            }
        }
    }     
}

if(isset($_POST['savesubreq'])){
    
    $maxpid = $obj->getMaxValueofppa($_POST['submid']);
    $subreq = $_POST['subramend'];
    
    $where = array(
        "pid" => $maxpid
    );
    if($obj->delete_record("sub_request", $where)){
        
    }
    
    
    for($i=0;$i<count($subreq);$i++){
        $where = array(
            "pid" => $maxpid,
            "sreq_id" => $subreq[$i]
        );
        //print_r($where);
        if($obj->insert_record("sub_request", $where)){
            header("location: progressreport.php?id=".$_POST['submid']);
        }
    }
    
    
}


if (isset($_GET['savepreport'])) {
    if (isset($_GET['subid'])) {
        $subid = $_GET['subid'];
        $u = $_GET['u'];
        
        $where = array(
            "sub_id" => $subid,
            "status_action" => "9",
            "status_date" => $datetime,
            "status_username" => $u
        );
        if($obj->insert_record("proposal_status", $where)){
            header("location:dashboard.php#approved");
        }
    }
}

 
if(isset($_FILES['appeal'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['appeal']['name'];
      $file_size = $_FILES['appeal']['size'];
      $file_tmp = $_FILES['appeal']['tmp_name'];
      $file_type = $_FILES['appeal']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['appeal']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $revision = $resdoctype['2'] + 1;
            $rename_filename = $id."-".$date."-".$revision."-".$resdoctype['1'].".".end($temp);
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            $doctypefinal = $resdoctype['0'];
            $maxappeal = ($obj->getversionappeal($id)) + 1;
            $doctypetimes = ($obj->doctypetimes($id)) + 1;
            
            $uploadinfor = array(
            "file_id" => "",
            "sub_id" => $id,
            "revision" => $maxappeal,
            "finaldoc" => "1",
            "file_name" => $rename_filename,
            "orig_filename" => $file_name,
            "file_type" => $file_type,
            "file_size" => $file_size,
            "date_uploaded" => $datetime,
            "date_modified" =>$datetime,
            "kind" => $_POST['kind'],
            "doctype" => $resdoctype['0'],
            "doctypetimes" => $doctypetimes,
            "newsubmit" => "1",
            "username" => $_POST['userid'],
            "path" => "uploads/main/".$rename_filename
            );  
                if($obj->insert_record("document", $uploadinfor)){
                    header("location:".$useurl."?id=".$id);
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

if (isset($_GET['erase'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $loc = $_GET['loc'];
            $where = array("file_id"=>$id);
//            print_r($where);
            if($obj->delete_record("document",$where)){
                header("location:".$loc.".php?id=".$subid);
            }
        }
        
    } 
}


if (isset($_GET['cancelpostapproval'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            
            
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $pid = $_GET['pid'];
            
            $where = array("id"=>$id);
            if($obj->delete_record("proposal_status",$where)){
                header("location:dashboard.php#approved");
            }
            
            $na = array("pid" => $pid);
            if($obj->delete_record("proposal_post_approval",$na)){
                header("location:dashboard.php#approved");
            }
            
        }
        
    } 
}

if(isset($_FILES['appealpost'])){
//    echo "<pre>";    var_dump($_POST); echo "</pre>"; exit;
    
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['appealpost']['name'];
      $file_size = $_FILES['appealpost']['size'];
      $file_tmp = $_FILES['appealpost']['tmp_name'];
      $file_type = $_FILES['appealpost']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['appealpost']['name'])));
      
       $expensions= array("pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, PDF file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {          
                    
          
            $ppaid = $obj->getMaxValueofppa($id);
            $getmaxb = $obj->getmaxbatchforuploadpa($id,$ppaid);  
                    
            $where = array(
                "sub_id" => $id,
                "post_approval_type" => $ppaid,
                "finaldoc" => "1",
                "doctype" => $_POST['request_type']
            );
            
            $find = $obj->fetch_record_with_where("document_postapproval", $where);
            if($find){
                header("location:".$_POST['page'].".php?id=".$id);
            }
            else{  
                      
                $where = array(
                    "sub_id" => $id,
                    "post_approval_type" => $ppaid,
                    "doctype" => $_POST['request_type']
                );
                $getmax = $obj->getmaxvalue_with_where("revision", "document_postapproval", $where);
                
            
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
//            echo $useurl;
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = explode(',', $doctype);
            $doctypefinal = $resdoctype['0']; 
//            print_r($resdoctype);
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
//            $revision = $resdoctype['2'] + 1;            
            $rename_filename = $id."-".$date."-".$getmax."-".$resdoctype['1'].".".end($temp);
//            echo $getmax; echo $rename_filename; exit;
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            $uploadinfor = array(
            "sub_id" => $id,
            "post_approval_type" => $_POST['ppaid'],
            "revision" => $getmax,
            "finaldoc" => "1",
            "file_name" => $rename_filename,
            "orig_filename" => $file_name,
            "file_type" => $file_type,
            "file_size" => $file_size,
            "date_uploaded" => $datetime,
            "date_modified" =>$datetime,
            "kind" => $_POST['kind'],
            "doctype" => $doctypefinal,
            "newsubmit" => "1",
            "username" => $_POST['userid'],
            "path" => "uploads/main/".$rename_filename
            ); 
            
                if($obj->insert_record("document_postapproval", $uploadinfor)){
                    header("location:".$_POST['page'].".php?id=".$_POST['submid']);
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
                
            }
          
          
          
      }
      else{
         print_r($errors);
      }
   } 
   
   if (isset($_GET['erase'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $loc = $_GET['loc'];
            $where = array("file_id"=>$id);
//            print_r($where);
            if($obj->delete_record("document_postapproval",$where)){
                header("location:".$loc.".php?id=".$subid);
            }
        }
        
    } 
}

   if (isset($_GET['appeal'])) {
    if (isset($_GET['subid'])) {
        if (isset($_GET['statact'])){
            $loc = $_GET['loc'];
            
            $values = array(
                "sub_id" => $_GET['subid'],
                "status_action" => $_GET['statact'],
                "status_date" => $datetime,
                "status_username" =>$_GET['u']
            );
            if($obj->insert_record("proposal_status",$values)){
                header("location:$loc.php?id=".$_GET['subid']);
            }
        }
        
    } 
}

?>