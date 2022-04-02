<?php
include "../config.php";
$connect = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbDatabase);

include "sample_dbtest.php";
        $currDir = dirname(__FILE__);
        require_once "{$currDir}/mailer/PHPMailerAutoload.php";

class UploadOperation extends Database
{
    public function getCountBadge($name, $userid){
        switch ($name) {
            case "inc":
                $maxid = $this->getMaxPerSec($userid);
                if($maxid == ''){
                    echo 0; break;
                }
                else{
                    foreach($maxid as $mid){
                        $all .=  $mid['maxid'].', ';
                    }
                    $all = substr($all, 0, -2);
                    echo $this->countProp($all, "2");

                    break;                    
                }
            case "onrev":
                $maxid = $this->getMaxPerSecpa($userid);
                if($maxid == ''){
                    echo 0; break;
                }
                else{
                    foreach($maxid as $mid){
                        if($mid['ddd'] == NULL){
                            $a1 .=  $mid['maxid'].', ';
                        }                          
                    }
                    $a1a = substr($a1, 0, -2);
                    $ua = $this->countProp($a1a, "1");

                    $maxid = $this->getMaxPerSec($userid);
                    foreach($maxid as $mid){
                        if($mid['ddd'] == NULL){
                            $a2 .=  $mid['maxid'].', ';
                        }
                    }
                    $a2a = substr($a2, 0, -2);
                    $ass = $this->countProp($a2a, "3");

                    $maxid = $this->getMaxPerSec($userid);
                    foreach($maxid as $mid){
                        if($mid['ddd'] == NULL){
                            $a3 .=  $mid['maxid'].', ';
                        }
                    }
                    
                    $a3a = substr($a3, 0, -2);
                    $rsub = $this->countProp($a3a, "11");
                    
                    echo $ua+$ass+$rsub;

                    break;
                }
            case "addedfile":  
                
                $myrow = $this->gettingNewlySubmitted($userid);
                $num = count($myrow);
                $maxid = $this->getMaxPerSec($userid);
//                echo "<pre>"; print_r($maxid); echo "</pre>"; exit;
                if(count($maxid) == '0'){
                    echo $num; break;
                }
                else{
                    foreach($maxid as $mid){
                        $all .=  $mid['maxid'].', ';
                    }
                    $all = substr($all, 0, -2);
                    $c = $this->countProp($all, "0");
                    
                    foreach($maxid as $mid){
                            $all .=  $mid['maxid'].', ';
                        }
                    $all = substr($all, 0, -2);
                    $resub = $this->countProp($all, "14");

                    echo $c+$resub+$num;
                    
                    break;                    
                }
                
            case "appr":
                $maxid = $this->getMaxPerSec($userid);
                if($maxid == ''){
                    echo 0; break;
                }
                else{
                    foreach($maxid as $mid){
                        $all .=  $mid['maxid'].', ';
                    }
                    $all = substr($all, 0, -2);
                    $appr = $this->countProp($all, "6");
                    
                    foreach($maxid as $mid){
                        $b1 .=  $mid['maxid'].', ';
                    }
                    $b1b = substr($b1, 0, -2);
                    $exem = $this->countProp($b1b, "12");
                    
                    echo $appr+$exem;

                    break;
                }
            case "resub":
                $maxid = $this->getMaxPerSec($userid);
                if($maxid == ''){
                    echo 0; break;
                }
                else{
                    foreach($maxid as $mid){
                        $all .=  $mid['maxid'].', ';
                    }
                    $all = substr($all, 0, -2);
                    $a = $this->countProp($all, "5");
                    
                    foreach($maxid as $mid){
                        $b1 .=  $mid['maxid'].', ';
                    }
                    $b1b = substr($b1, 0, -2);
                    $b = $this->countProp($b1b, "13");
                    
                    foreach($maxid as $mid){
                        $b2 .=  $mid['maxid'].', ';
                    }
                    $b2b = substr($b2, 0, -2);
                    $c = $this->countProp($b2b, "15");
                    
                    foreach($maxid as $mid){
                        $b3 .=  $mid['maxid'].', ';
                    }
                    $b3b = substr($b3, 0, -2);
                    $d = $this->countProp($b3b, "17");
                    
                    foreach($maxid as $mid){
                        $b4 .=  $mid['maxid'].', ';
                    }
                    $b4b = substr($b4, 0, -2);
                    $e = $this->countProp($b4b, "25");
                    
                    echo $a+$b+$c+$d+$e;
                    break;
                }
            case "par":
                $maxid = $this->getMaxPerSec($userid);
                if($maxid == ''){
                    echo 0; break;
                }
                else{
                    foreach($maxid as $mid){
                        $b1 .=  $mid['maxid'].', ';
                    }
                    $b1b = substr($b1, 0, -2);
                    $amend = $this->countProp($b1b, "7");

                    $maxid = $this->getMaxPerSec($userid);
                    foreach($maxid as $mid){
                        $b2 .=  $mid['maxid'].', ';
                    }
                    $b2b = substr($b2, 0, -2);
                    $ce = $this->countProp($b2b, "8");

                    $maxid = $this->getMaxPerSec($userid);
                    foreach($maxid as $mid){
                        $b3 .=  $mid['maxid'].', ';
                    }
                    $b3b = substr($b3, 0, -2);
                    $frs = $this->countProp($b3b, "9");

                    echo $amend+$ce+$frs;

                    break;
                }
            default:
                echo "No data";
        }
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

function getmaxreq($subid){
        $sql = "SELECT MAX(pa_request) as reqmax FROM proposal_post_approval WHERE subid = '".$subid."'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $reqmax = $row['reqmax'];
        return $reqmax;
}

function getmaxmessageppaid($subid){
        $sql = "SELECT MAX(times) as t FROM messagepa WHERE subid = '".$subid."'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $reqmax = $row['reqmax'];
        return $reqmax;
}
    function getcountprop($user){
        
        $sql = "SELECT COUNT(sub_id) as countprop FROM rev_groups WHERE phrepuser_id = '$user' and primary_reviewer = '1' and review = '1' and confirmation = '1'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['countprop'];
        return $fileid;       
    }    
    public function countProp($mid, $status){
        $sql = "SELECT COUNT(id) as cid FROM `proposal_status` WHERE id IN ($mid) AND status_action = '$status'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['cid'];
        return $fileid;
    }
    
function getmaxpropstat($subid) {
        $sql = "SELECT MAX(id) as sid FROM `proposal_status` WHERE sub_id = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['sid'];
        return $fileid;
}
    
    function getmagicword(){
        $sql = "SELECT theword FROM magicword where id = '1'";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $theword = $row['theword'];
        return $theword;
    }
    
    public function encrypt($string, $key){
        
        $string = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
        return $string;
    }
    public function decrypt($string, $key){
        $string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));
        return $string;
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


    public function getUserRole($table, $where){
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
    
    function getRECName($id){
        
        $sql = "SELECT * FROM rec_list WHERE secretary = '$id'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    public function insert_record_reviewer($table, $fields, $table1, $fields1) {
        $sql = "";
        $sql .= "INSERT INTO " . $table;
        $sql .= " (" . implode(",", array_keys($fields)) . ") VALUES ";
        $sql .= " ('" . implode("','", array_values($fields)) . "')";
        $query = mysqli_query($this->con, $sql);
        if ($query) {
            $last_id = mysqli_insert_id($this->con);
//            echo $last_id;
            $sql1 = "";
            $sql1 .= "INSERT INTO " . $table1;
            $sql1 .= " (phrepuser_id," . implode(",", array_keys($fields1)) . ") VALUES ";
            $sql1 .= " ('".$last_id."','" . implode("','", array_values($fields1)) . "')";
//            echo $sql1;
            $query1 = mysqli_query($this->con, $sql1);
            if($query1){
                return TRUE;                
            }
        }
    }
    
    function selectChairman($table1, $table2, $table3, $id1, $id2, $id3, $where){
        
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        
        $sql = "SELECT *, c.id AS puser FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "INNER JOIN ".$table3." c ON a.".$id3." = c.".$id2." ";
        $sql .= "WHERE " . $condition;
       
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    
    function gettingNewlySubmitted($userid){
        $sql = "SELECT *, a.sub_id as sid FROM submission a
                INNER JOIN rec_list c ON a.reclist_id = c.id
                INNER JOIN proposal d ON a.sub_id = d.sub_id
                INNER JOIN phrepuser e ON e.id= c.secretary
                WHERE e.id = '$userid' AND d.date_submitted is not null AND a.sub_id NOT IN (SELECT DISTINCT(f.sub_id) FROM proposal_status f)";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }  
    
    function gettingMaxValueStatus($table1, $table3, $table4, $table5, $table6, $id1, $id2, $id3, $id4, $id5, $id6, $username){
        $sql = "SELECT a.".$id1.", MAX(f.".$id2.") as sa FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table3." c ON a.".$id3." = c.".$id4." ";
        $sql .= "INNER JOIN ".$table4." d ON a.".$id1." = d.".$id1." ";
        $sql .= "INNER JOIN ".$table5." e ON e.".$id4." = c.".$id5." ";
        $sql .= "INNER JOIN ".$table6." f ON a.".$id1." = f.".$id1." ";
        $sql .= "WHERE e.".$id6." = '".$username."' GROUP BY a.".$id1."";
        
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    } 
    
    function gettingMaxValueForCount($username, $status){
        $sql = "SELECT MAX(a.status_action) FROM proposal_status a 
                INNER JOIN phrepuser b ON a.status_username = b.username
                WHERE b.id = '".$username."' AND a.status_action = '".$status."' GROUP BY a.sub_id";
                
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
    
    function getMemberReviewer($userid, $review){
        $sql = "SELECT * FROM rec_groups a 
                INNER JOIN rev_groups b ON a.phrepuser_id = b.phrepuser_id
                WHERE b.sub_id = '$userid' and b.review = '$review' GROUP BY a.phrepuser_id";
                
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    function getChairmanReviewer($userid, $review){
        $sql = "SELECT a.phrepuser_id as pcid FROM rec_groups a 
                INNER JOIN rev_groups b ON a.phrepuser_id = b.phrepuser_id
                WHERE a.type_id = 1 and b.sub_id = '$userid' and b.review = '$review' GROUP BY a.phrepuser_id";
                
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['pcid'];
        return $fileid;
    } 
    
    function gettingProposalByStatus($table1, $table3, $table4, $table5, $id1, $id3, $id4, $id5, $id6, $username, $id){
        $sql = "SELECT *, e.username AS userid  FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table3." c ON a.".$id3." = c.".$id4." ";
        $sql .= "INNER JOIN ".$table4." d ON a.".$id1." = d.".$id1." ";
        $sql .= "INNER JOIN ".$table5." e ON e.".$id4." = c.".$id5." ";
        $sql .= "WHERE e.".$id6." = '".$username."' AND a.".$id1." = '".$id."'";
                
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    function joiningTwoTables($table1, $table2, $id1, $id){
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id1." ";
        $sql .= "WHERE a.sub_id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }  
    
    function joiningTwoTabless($table1, $table2, $id1, $id2, $id){
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "WHERE a.sub_id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }  
    
        
    
    function joiningTwoTablesid($table1, $table2, $id1, $id2, $id){
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "WHERE a.sub_id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    function joiningThreeTables($table1, $table2, $table3, $id1, $id2, $id3, $id4, $username){
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
        $sql .= "WHERE a.id = '".$username."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    function joiningFourTables($table1, $table2, $table3, $table4, $table5, $id1, $id2, $id3, $id4, $id5, $id6, $id7, $id8, $username){
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
        $sql .= "INNER JOIN ".$table4." d ON a.".$id5." = d.".$id6." ";
        $sql .= "INNER JOIN ".$table5." e ON e.".$id7." = c.".$id8." ";
        $sql .= "WHERE e.username = '".$username."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    public function fetch_record_with_where_and_join($table1, $table2, $id1, $id2, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        
        $sql .= "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."  WHERE " . $condition;
       
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    function get_upload_info_sup($table1, $table2, $id, $kind){
        $sql = "SELECT * FROM ".$table1." a INNER JOIN ".$table2." b "
                . "ON a.doctype = b.docid LEFT JOIN document_control c "
                . "ON a.doctype = c.doctype "
                . "WHERE a.sub_id='".$id."' AND a.kind='".$kind."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    public function human_filesize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
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
    
    function showingUploadedFilesforAmendment($table1, $table2, $id1, $id2, $id, $i){
        
        $sql = "SELECT * FROM $table1 a 
                INNER JOIN $table2 b ON a.$id1 = b.$id2 
                WHERE a.sub_id = '$id' and a.revision = '$i' and a.kind IN ('MP')";
        
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
    
        
    function getpassrev($subid){
        
        $sql = "SELECT * FROM rev_answers a 
                INNER JOIN phrepuser b ON a.revid = b.id
                INNER JOIN reviewform_type c ON a.revform_id = c.rft_id
                WHERE a.sub_id = '$subid' GROUP BY a.revid, a.revform_id";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }
    
    function getpasscomment($subid){
        
        $sql = "SELECT * FROM rev_comment a 
                INNER JOIN phrepuser b ON a.phrepuser_id = b.id
                WHERE a.sub_id = '$subid'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
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
    
    function checkingUploadFiles($table1, $table2, $id1, $id2, $id3, $id){
        
        $sql = "SELECT * FROM ".$table1." a
                INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2."
                INNER JOIN document c ON a.sub_id = c.sub_id
                where a.sub_id = '".$id."' AND b.doctype = '".$id3."'";
        
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
        
    public function fetch_record($table,$where) {
        $sql = "";
        $condition = "";
            foreach ($where as $key => $value){
                $condition .= $key . "='". $value . "' AND ";
            }
        $condition = substr($condition, 0, -5);
        $sql = "SELECT * FROM ".$table." WHERE ".$condition;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    public function fetch_record_all($table) {
        
        $sql = "SELECT * FROM ".$table;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters['rand(0, $charactersLength - 1)'];
    }
    return $randomString;
}
    
    public function fetch_records($table) {
        $sql = "SELECT * FROM " . $table;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    public function insert_multiple($table,$keys, $values) {
        
        $sql = "INSERT INTO ".$table." (".implode (', ', $keys).") VALUES " . implode (', ', $values) . "";
//        echo $sql;
        $array = array();
        $query = mysqli_query($this->con, $sql);
    }



    // jm
    public function insert_multipleREVIEWER($table,$keys, $values) {
        
        $sql = "INSERT INTO ".$table." (".implode (', ', $keys).") VALUES " . implode (', ', $values) . "";
//        echo $sql;
        $array = array();
        $query = mysqli_query($this->con, $sql);
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
    
    public function update_multiple($table, $set, $fields, $count) {
        for ($i = 0; $i < $count; $i++) {
            
            $sql = "";
            $condition = "";
            foreach ($fields[$i] as $key => $value) {
                $condition .= $key . "='" . $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            $sql .= "UPDATE ".$table." SET ".$set." WHERE ".$condition."";
            
            $query = mysqli_query($this->con, $sql);
                if ($query) {
                    
                }
            
        }
        return TRUE;
    }

    public function getsuggestions($id) {
        $sql = "SELECT * FROM `review_suggest` a 
                INNER JOIN submission b ON a.subid = b.sub_id
                INNER JOIN proposal c ON b.sub_id = c.sub_id
                INNER JOIN review_type_list d ON a.rev_suggest = d.id
                INNER JOIN (SELECT MAX(review) as mxr, sub_id FROM rev_groups group by sub_id) e ON c.sub_id = e.sub_id
                WHERE b.reclist_id = '$id' order by a.datesuggested";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    } 
    
    public function getstatuspost($id) {
        $sql = "SELECT * FROM proposal_post_approval where subid = '$id' and pa_status = 'onreview'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    } 
    
    public function gettingProposalForConfirmation($table1, $table2, $table3,$id1, $id2, $id3, $id4, $username) {
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
        $sql .= "WHERE a.username = '".$username."' AND b.confirmation = '0'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    } 
    public function gettingForms($username) {
        $sql = "SELECT *, c.id as fid FROM phrepuser a 
                INNER JOIN rec_list b ON a.id = b.secretary
                INNER JOIN rec_forms c ON b.id = c.rec_list_id
                WHERE a.username = '".$username."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
//    public function getfilesadditional($username) {
//        $sql = "SELECT * FROM proposal_status a 
//                INNER JOIN proposal b ON a.sub_id = b.sub_id
//                INNER JOIN combased c ON b.sub_id = c.sub_id
//                INNER JOIN rec_list d ON c.inst_id = d.id
//                WHERE d.secretary = '".$username."' AND a.status_action = '0'";
//        
//        $query = mysqli_query($this->con,$sql);
//        while ($row = mysqli_fetch_assoc($query)) {
//            $array[] = $row;
//        }
//        return $array;        
//    }
    
    public function getproposalview($maxid) {
        $sql = "SELECT * FROM proposal_status a 
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN submission c ON b.sub_id = c.sub_id
                WHERE a.id = '".$maxid."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getFileID($subid, $famend, $kind) {
        $sql = "SELECT *  FROM `document` WHERE `sub_id` = '$subid' AND finalamend = '$famend' and kind = '$kind'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getARF($subid, $kind) {
        $sql = "SELECT *  FROM `document` WHERE `sub_id` = '$subid' AND kind = '$kind'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getproposalviewunconfirmed($maxid) {
        $sql = "SELECT *, b.sub_id AS sid FROM proposal_status a 
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN review_type c ON b.sub_id = c.sub_id
                INNER JOIN review_type_list d ON c.rt_id = d.id
                WHERE a.id = '".$maxid."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    } 
    
    public function getifconfirmed($subid, $maxid) {
        $sql = "SELECT COUNT(sub_id) AS cnt FROM `rev_groups` WHERE sub_id = '$subid' AND review = '$maxid' AND confirmation IN (0)";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['cnt'];
        return $fileid;       
    } 
    
    public function getifassgined($subid) {
        $sql = "SELECT count(sub_id) as y FROM proposal_status WHERE sub_id = '$subid' AND status_action IN ('3')";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $y = $row['y'];
        return $y;       
    }    
    
    public function getfilessubmitted($subid) {
        $sql = "SELECT *  FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.sub_id = '$subid' and a.finaldoc = '1' ORDER BY date_uploaded desc";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;       
    }
    
    public function getmaxrequesttimes($subid) {
        $sql = "SELECT MAX(post_request_times) AS prt FROM `document` WHERE sub_id = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $prt = $row['prt'];
        return $prt;       
    }
    
    public function getifrlisthere($subid, $kind, $mid) {
        $sql = "SELECT count(sub_id) as cfin FROM document_postapproval where kind = '$kind' and finaldoc = '1' and sub_id = '$subid' and post_approval_type = '$mid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $cfin = $row['cfin'];
        return $cfin;       
    }
    
    public function getifconfirmedfull($subid, $maxid) {
        $sql = "SELECT COUNT(sub_id) AS cnt FROM `rev_groups` WHERE sub_id = '$subid' AND review = '$maxid' AND confirmation IN (0) AND primary_reviewer = '1'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['cnt'];
        return $fileid;       
    }
    
    public function gettimesmsg($subid, $notefor) {
        $sql = "SELECT MAX(times) as tmsg FROM `message` WHERE subid = '$subid' AND notefor = '$notefor'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['tmsg'];
        return $fileid;       
    }
    
    public function gettimesmsgpa($subid, $ppaid) {
        $sql = "SELECT MAX(times) as tmsg FROM `messagepa` WHERE subid = '$subid' AND ppaid = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['tmsg'];
        return $fileid;       
    }

    public function checkifconfirmed($userid) {
        $sql = "SELECT * FROM rev_groups a 
                INNER JOIN submission b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON b.reclist_id = c.id
                WHERE c.secretary = '".$userid."' AND a.confirmation = '0'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
        public function getMax($subid) {
        $sql = "SELECT *, MAX(a.id) as maxid FROM proposal_status a 
                WHERE a.sub_id = '".$subid."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
        public function getMaxpa($subid) {
        $sql = "SELECT status_action as sa FROM proposal_status a 
                INNER JOIN (SELECT MAX(id) as maxid FROM proposal_status WHERE sub_id = '$subid') b ON a.id = b.maxid
                WHERE a.sub_id = '$subid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $bat = $row['sa'];
        return $bat;       
    }

        public function getMaxPerSec($userid) {
        $sql = "SELECT *, MAX(a.id) AS maxid, a.sub_id AS sid FROM proposal_status a 
                INNER JOIN submission b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON b.reclist_id = c.id
                WHERE c.secretary = '".$userid."' GROUP BY a.sub_id";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
        public function getMaxPerSecpa($userid) {
        $sql = "SELECT *, MAX(a.id) AS maxid, a.sub_id AS ssid, d.sid as ddd FROM proposal_status a 
                INNER JOIN submission b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON b.reclist_id = c.id
                LEFT JOIN (SELECT subid as sid FROM proposal_post_approval GROUP BY subid) d ON a.sub_id = d.sid
                WHERE c.secretary = '".$userid."' GROUP BY a.sub_id";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
        public function getMaxPerSecpost($userid) {
        $sql = "SELECT *, MAX(a.id) AS maxid, a.sub_id AS sid FROM proposal_status a 
                INNER JOIN submission b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON b.reclist_id = c.id
                WHERE c.secretary = '".$userid."' GROUP BY a.sub_id";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }


        public function getMaxDocumentStat($userid) {
        $sql = "SELECT * FROM proposal_status a 
                INNER JOIN (SELECT MAX(id) as mid FROM proposal_status GROUP BY sub_id) b ON a.id = b.mid
                INNER JOIN submission c ON a.sub_id = c.sub_id
                INNER JOIN rec_list d ON c.reclist_id = d.id
                INNER JOIN proposal e ON a.sub_id = e.sub_id
                INNER JOIN review_type f ON a.sub_id = f.sub_id
                INNER JOIN review_type_list g ON f.rt_id = g.id
                WHERE d.secretary = '$userid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }



    // jm's code---------------------------
    // pre
    public function getYearOnly_pre($userid){

        // $sql = "select submission.year as year_accepted, count(submission.year) as total_approved_proposal from proposal
                
        //         inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 6 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

        //         inner join submission on submission.sub_id = max_id_proposal_status.proposal_id
                
        //         group by submission.`year`";

        // $sql = "
        //     select submission.year as year_accepted, 
            
        //     count(submission.year) as total_approved_proposal

        //     from proposal
                
        //         inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 6 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

        //         inner join submission on submission.sub_id = max_id_proposal_status.proposal_id
                
        //         group by submission.`year`";

        // $sql = "
        //     select submission.year as year_accepted, 
            
        //     count(submission.year) as total_approved_proposal

        //     from proposal

        //         inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 6 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

        //         inner join submission on submission.sub_id = max_id_proposal_status.proposal_id 
                
        //         group by submission.`year`";

         // $sql = "


         // select submission.year as yearName, count(submission.year) as totalCountPerYear

         // from proposal 
         
         // inner join submission on submission.sub_id = proposal.sub_id

         // group by submission.year;

         // ";

       $sql = "

       select

       submitted_tbl.yearName, 
       submitted_tbl.submitted, 

       coalesce(accepted_tbl.accepted, '-') as accepted, 
       coalesce(approved_tbl.approved, '-') as approved, 
       coalesce(disapproved_tbl.disapproved, '-') as disapproved,
       coalesce(revision_tbl.revision, '-') as revision,

       coalesce(full_tbl.full, '-') as full,
       coalesce(expedited_tbl.expedited, '-') as expedited,
       coalesce(exempted_tbl.exempted, '-') as exempted

       from (
       select submission.year as yearName, count(submission.year) as submitted
       from proposal
       inner join submission on submission.sub_id = proposal.sub_id
       group by submission.year
       ) as submitted_tbl



       left join (
       select submission.year as yearName, count(submission.year) as accepted
       from proposal
       inner join submission on submission.sub_id = proposal.sub_id
       where submission.coding != ''
       group by submission.year
       ) as accepted_tbl on submitted_tbl.yearName = accepted_tbl.yearName


       left join(
       select submission.year as yearName, count(submission.year) as approved
       from proposal
       inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 6 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

       inner join submission on submission.sub_id = max_id_proposal_status.proposal_id 
       group by submission.year
       ) as approved_tbl on submitted_tbl.yearName = approved_tbl.yearName


       left join(
       select submission.year as yearName, count(submission.year) as disapproved
       from proposal
       inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 4 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

       inner join submission on submission.sub_id = max_id_proposal_status.proposal_id 
       group by submission.year
       ) as disapproved_tbl on submitted_tbl.yearName = disapproved_tbl.yearName


      left join(
       select submission.year as yearName, count(submission.year) as revision
       from proposal
       inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 5 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

       inner join submission on submission.sub_id = max_id_proposal_status.proposal_id 
       group by submission.year
       ) as revision_tbl on submitted_tbl.yearName = revision_tbl.yearName



       left join(
           select submission.year as yearName, count(submission.year) as full
           from proposal
           
           inner join submission on submission.sub_id = proposal.sub_id

           inner join review_type on review_type.sub_id = proposal.sub_id

           where submission.coding != '' AND review_type.rt_id = 3

           group by submission.year
           ) as full_tbl on submitted_tbl.yearName = full_tbl.yearName


       left join(
           select submission.year as yearName, count(submission.year) as expedited
           from proposal
           
            inner join submission on submission.sub_id = proposal.sub_id

           inner join review_type on review_type.sub_id = proposal.sub_id

           where submission.coding != '' AND review_type.rt_id = 1

           group by submission.year
           ) as expedited_tbl on submitted_tbl.yearName = expedited_tbl.yearName


        left join(
           select submission.year as yearName, count(submission.year) as exempted
           from proposal
           
            inner join submission on submission.sub_id = proposal.sub_id

           inner join review_type on review_type.sub_id = proposal.sub_id

           where submission.coding != '' AND review_type.rt_id = 2

           group by submission.year
           ) as exempted_tbl on submitted_tbl.yearName = exempted_tbl.yearName

       ";



        // $sql = "select * from review_type
        // inner join (
        // select * from proposal_status
        // where proposal_status.status_action = 3
        // group by proposal_status.sub_id
        // ) as prop on review_type.sub_id = prop.sub_id
        // inner join review_type_list on review_type.rt_id = review_type_list.id";

        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }

        return $array;

        // var_dump($array);
    }


    // post
    public function getYearOnly_post($userid){

        $sqlxxx = "select submission.year as year_accepted, count(submission.year) as total_approved_proposal from proposal
                
                inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 23 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

                inner join submission on submission.sub_id = max_id_proposal_status.proposal_id
                group by submission.`year`";


        $sql = "

           select

           submitted_tbl.yearName, 
           submitted_tbl.submitted,
           coalesce(accepted_tbl.accepted, '-') as accepted,
           coalesce(post_approved_tbl.post_approved, '-') as post_approved,
           coalesce(post_disapproved_tbl.post_disapproved, '-') as post_disapproved
           


           from (
           select submission.year as yearName, count(submission.year) as submitted
           from proposal

           inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 23 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

           inner join submission on submission.sub_id = proposal.sub_id
           group by submission.year
           ) as submitted_tbl


           left join (
           select submission.year as yearName, count(submission.year) as accepted
           from proposal

           inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 23 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

           inner join submission on submission.sub_id = proposal.sub_id
           where submission.coding != ''

           group by submission.year
           ) as accepted_tbl on submitted_tbl.yearName = accepted_tbl.yearName


           left join(
           select submission.year as yearName, count(submission.year) as post_approved
           from proposal
           inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 6 AND proposal_status.status_action = 23 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

           inner join submission on submission.sub_id = max_id_proposal_status.proposal_id 
           group by submission.year
           ) as post_approved_tbl on submitted_tbl.yearName = post_approved_tbl.yearName


           left join(
           select submission.year as yearName, count(submission.year) as post_disapproved
           from proposal
           inner join (select max(id) as max_status_id, proposal_status.sub_id as proposal_id from proposal_status where proposal_status.status_action = 4 AND proposal_status.status_action = 23 group by proposal_status.sub_id) as max_id_proposal_status on proposal.sub_id = max_id_proposal_status.proposal_id

           inner join submission on submission.sub_id = max_id_proposal_status.proposal_id 
           group by submission.year
           ) as post_disapproved_tbl on submitted_tbl.yearName = post_disapproved_tbl.yearName


        ";


        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }

        return $array;
    }


    // pre
    public function getMaxDocumentStat_new($userid, $year, $status) {

        if($status == 'submitted'){

             $sql = "SELECT * FROM proposal a 
                    INNER JOIN submission b ON a.sub_id = b.sub_id
                    
                    left JOIN review_type f ON a.sub_id = f.sub_id
                    left JOIN review_type_list g ON f.rt_id = g.id

                    WHERE b.year = '$year' ";

        }else if($status == 'accepted'){


            $sql = "SELECT * FROM proposal a 

                    INNER JOIN submission b ON a.sub_id = b.sub_id
                    INNER JOIN rec_list c ON b.reclist_id = c.id
                    
                    left JOIN review_type f ON a.sub_id = f.sub_id
                    left JOIN review_type_list g ON f.rt_id = g.id

                    WHERE b.year = '$year' AND c.secretary = '$userid' AND b.coding != ''";
        
        }else if($status == 'underwentfullreview' OR $status == 'underwentexpeditedreview' OR $status == 'underwentexemptedreview'){

                    if($status == 'underwentfullreview'){
                        
                        $reviewType = 3;

                    }else if($status == 'underwentexemptedreview'){

                        $reviewType = 2;
                    
                    }else{

                        $reviewType = 1;
                    }

            $sql = "SELECT * FROM proposal a 

                    INNER JOIN submission b ON a.sub_id = b.sub_id
                    INNER JOIN rec_list c ON b.reclist_id = c.id
                    
                    left JOIN review_type f ON a.sub_id = f.sub_id
                    left JOIN review_type_list g ON f.rt_id = g.id

                    WHERE b.year = '$year' AND c.secretary = '$userid' AND b.coding != '' AND f.rt_id = '$reviewType'";

        }else if($status == 'approved' OR $status == 'disapproved' OR $status == 'revision'){

            if($status == 'approved'){
             
                $action = 6;
            
            }else if($status == 'disapproved'){

                $action = 4;
            
            }else if($status == 'revision'){

                $action = 5;
            }

            $sql = "SELECT *, YEAR(`status_date`) AS theyear FROM proposal_status a 

                INNER JOIN (SELECT MAX(id) as mid FROM proposal_status where proposal_status.status_action = '$action' GROUP BY sub_id) b ON a.id = b.mid

                INNER JOIN submission c ON a.sub_id = c.sub_id
                INNER JOIN rec_list d ON c.reclist_id = d.id
                INNER JOIN proposal e ON a.sub_id = e.sub_id
                INNER JOIN review_type f ON a.sub_id = f.sub_id
                INNER JOIN review_type_list g ON f.rt_id = g.id
               
                WHERE c.year = '$year' AND d.secretary = '$userid'";

        }



        // $sql = "SELECT *, YEAR(`status_date`) AS theyear FROM proposal_status a 
        //         INNER JOIN (SELECT MAX(id) as mid FROM proposal_status where proposal_status.status_action = 6 GROUP BY sub_id) b ON a.id = b.mid
        //         INNER JOIN submission c ON a.sub_id = c.sub_id
        //         INNER JOIN rec_list d ON c.reclist_id = d.id
        //         INNER JOIN proposal e ON a.sub_id = e.sub_id
        //         INNER JOIN review_type f ON a.sub_id = f.sub_id
        //         INNER JOIN review_type_list g ON f.rt_id = g.id
               
        //         WHERE c.year = '$year' AND d.secretary = '$userid'";

       // $sql = "
       //          SELECT * FROM proposal p 
       //          INNER JOIN (select max(id) as mid from proposal_status where status_action = 6 group by sub_id) ps ON p.sub_id = ps.mid
       //          INNER JOIN submission s ON s.sub_id = p.sub_id

       //         ";

        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }

        return $array;        
    }

    // post
    public function getMaxDocumentStat_new_post($userid, $year) {

        $sql = "SELECT *, YEAR(`status_date`) AS theyear FROM proposal_status a 
                INNER JOIN (SELECT MAX(id) as mid FROM proposal_status where proposal_status.status_action = 23 GROUP BY sub_id) b ON a.id = b.mid
                INNER JOIN submission c ON a.sub_id = c.sub_id
                INNER JOIN rec_list d ON c.reclist_id = d.id
                INNER JOIN proposal e ON a.sub_id = e.sub_id
                INNER JOIN review_type f ON a.sub_id = f.sub_id
                INNER JOIN review_type_list g ON f.rt_id = g.id
               
                WHERE c.year = '$year' AND d.secretary = '$userid'";

       // $sql = "
       //          SELECT * FROM proposal p 
       //          INNER JOIN (select max(id) as mid from proposal_status where status_action = 6 group by sub_id) ps ON p.sub_id = ps.mid
       //          INNER JOIN submission s ON s.sub_id = p.sub_id

       //         ";



        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }

        return $array;        
    }


















// ---------------jm'scode end

    
    
public function getMaxDocumentStatw($userid) {
        $sql = "SELECT * FROM proposal_status a 
                INNER JOIN (SELECT MAX(id) as mid FROM proposal_status GROUP BY sub_id) b ON a.id = b.mid
                INNER JOIN submission c ON a.sub_id = c.sub_id
                INNER JOIN rec_list d ON c.reclist_id = d.id
                INNER JOIN proposal e ON a.sub_id = e.sub_id
                WHERE d.secretary = '$userid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
        public function getlistproposal($userid) {
        $sql = "SELECT a.sub_id as sid, d.dpid as dp FROM `proposal` a 
                INNER JOIN submission b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON b.reclist_id = c.id
                LEFT JOIN (SELECT DISTINCT(subid) as dpid FROM proposal_post_approval) d ON a.sub_id = d.dpid
                WHERE c.secretary = '$userid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
        public function getassignedrev($userid, $id) {
        $sql = "SELECT *, c.id AS puser FROM rec_list a 
                INNER JOIN rec_groups b ON a.id = b.rec_list_id
                INNER JOIN phrepuser c ON b.phrepuser_id = c.id
                INNER JOIN rev_groups d ON c.id = d.phrepuser_id
                WHERE b.type_id = '2' AND a.secretary = '".$userid."' AND d.sub_id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getevaluated($userid, $id, $maxReview) {
        $sql = "SELECT * FROM rec_list a 
                INNER JOIN submission b ON a.id = b.reclist_id
                INNER JOIN rev_groups c ON b.sub_id = c.sub_id
                WHERE c.evaluation_submitted IN (0) AND a.secretary = '".$userid."' AND c.sub_id = '".$id."' AND c.confirmation = '1' AND c.review = '$maxReview'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    public function getreviewerseval($userid, $id, $maxrev) {
        $sql = "SELECT *, c.id AS desid FROM phrepuser a 
                INNER JOIN rev_groups b ON a.id = b.phrepuser_id
                INNER JOIN rev_decision c ON b.decision = c.id
                INNER JOIN rec_groups d ON b.phrepuser_id = d.phrepuser_id
                INNER JOIN rec_list e ON d.rec_list_id = e.id
                WHERE b.sub_id = '".$id."' AND e.secretary = '".$userid."' AND b.review='".$maxrev."'";
        
//        $sql = "SELECT *, c.id AS desid FROM phrepuser a 
//                INNER JOIN rev_groups b ON a.id = b.phrepuser_id
//                INNER JOIN rev_decision c ON b.decision = c.id
//                INNER JOIN rec_groups d ON b.phrepuser_id = d.phrepuser_id
//                INNER JOIN rec_list e ON d.rec_list_id = e.id
//                INNER JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
//                WHERE b.sub_id = '".$id."' AND e.secretary = '".$userid."' AND b.review='".$maxrev."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getreviewersevalpa($userid, $id, $maxrev, $maxppaid) {
        $sql = "SELECT *, c.id AS desid FROM phrepuser a 
                INNER JOIN rev_groupspa b ON a.id = b.phrepuser_id
                INNER JOIN rev_decision c ON b.decision = c.id
                INNER JOIN rec_groups d ON b.phrepuser_id = d.phrepuser_id
                INNER JOIN rec_list e ON d.rec_list_id = e.id
                INNER JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
                WHERE b.sub_id = '".$id."' AND e.secretary = '".$userid."' AND b.review='".$maxrev."' AND b.ppa_id = '".$maxppaid."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }    
        public function getevaluationsfromreviewers($userid, $id, $maxrev) {
        $sql = "SELECT *, c.id AS desid FROM phrepuser a 
                INNER JOIN rev_groups b ON a.id = b.phrepuser_id
                INNER JOIN rev_decision c ON b.decision = c.id
                INNER JOIN rec_groups d ON b.phrepuser_id = d.phrepuser_id
                INNER JOIN rec_list e ON d.rec_list_id = e.id
                LEFT JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
                WHERE b.sub_id = '".$id."' AND e.secretary = '".$userid."' AND b.review ='".$maxrev."'";
        //                INNER JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }   
    
        public function getevaluationsfromreviewerspa($userid, $id, $maxrev, $ppaid) {
        $sql = "SELECT *, c.id AS desid FROM phrepuser a 
                INNER JOIN rev_groupspa b ON a.id = b.phrepuser_id
                INNER JOIN rev_decision c ON b.decision = c.id
                INNER JOIN rec_groups d ON b.phrepuser_id = d.phrepuser_id
                INNER JOIN rec_list e ON d.rec_list_id = e.id
                LEFT JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
                WHERE b.sub_id = '".$id."' AND e.secretary = '".$userid."' AND b.review ='".$maxrev."' AND b.ppa_id = '".$ppaid."'";
        //                INNER JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
        public function getprevreviewerseval($userid, $id, $maxrev) {
        $sql = "SELECT *, c.id AS desid FROM phrepuser a 
                INNER JOIN rev_groups b ON a.id = b.phrepuser_id
                INNER JOIN rev_decision c ON b.decision = c.id
                INNER JOIN rec_groups d ON b.phrepuser_id = d.phrepuser_id
                INNER JOIN rec_list e ON d.rec_list_id = e.id
                INNER JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
                WHERE b.sub_id = '".$id."' AND e.secretary = '".$userid."' AND b.review !='".$maxrev."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
        public function getprevreviewersevalsummary($userid, $id) {
        $sql = "SELECT *, c.id AS desid FROM phrepuser a 
                INNER JOIN rev_groups b ON a.id = b.phrepuser_id
                INNER JOIN rev_decision c ON b.decision = c.id
                INNER JOIN rec_groups d ON b.phrepuser_id = d.phrepuser_id
                INNER JOIN rec_list e ON d.rec_list_id = e.id
                INNER JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
                WHERE b.sub_id = '".$id."' AND e.secretary = '".$userid."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }    
    
        public function getprevreviewersevalpa($userid, $id) {
        $sql = "SELECT *, c.id AS desid FROM phrepuser a 
                INNER JOIN rev_groups b ON a.id = b.phrepuser_id
                INNER JOIN rev_decision c ON b.decision = c.id
                INNER JOIN rec_groups d ON b.phrepuser_id = d.phrepuser_id
                INNER JOIN rec_list e ON d.rec_list_id = e.id
                INNER JOIN rev_evaltype f ON b.evaluation_type = f.evaltype_id
                WHERE b.sub_id = '".$id."' AND e.secretary = '".$userid."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }    
    
        public function getDistinctEval($userid, $id) {
        $sql = "SELECT DISTINCT(revform_id) as ri, revid, b.evaltype_desc, a.revform_id FROM rev_answers a
                INNER JOIN rev_evaltype b ON a.revform_id = b.evaltype_id
                WHERE sub_id = '$id' AND revid = '$userid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }    
    
    public function getevaluationanswer($userid, $id) {
        $sql = "SELECT * FROM rev_groups a
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN review_type c ON b.sub_id = c.sub_id
                INNER JOIN review_type_list d ON c.rt_id = d.id
                INNER JOIN phrepuser e ON a.phrepuser_id = e.id
                WHERE a.sub_id='$id' AND a.phrepuser_id='$userid' AND a.review='1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    public function getevaluationanswerpa($userid, $id, $ppaid, $evt) {
        $sql = "SELECT * FROM rev_groupspa a
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN review_type c ON b.sub_id = c.sub_id
                INNER JOIN review_type_list d ON c.rt_id = d.id
                INNER JOIN phrepuser e ON a.phrepuser_id = e.id
                WHERE a.sub_id='$id' AND a.phrepuser_id='$userid' AND a.ppa_id='$ppaid' AND a.review='$evt'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getevaluationanswercomm($userid, $id, $r) {
        $sql = "SELECT * FROM rev_groups a
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN review_type c ON b.sub_id = c.sub_id
                INNER JOIN review_type_list d ON c.rt_id = d.id
                INNER JOIN phrepuser e ON a.phrepuser_id = e.id
                WHERE a.sub_id='$id' AND a.phrepuser_id='$userid' AND a.review='$r'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getprincipalinvestigator($id) {
        $sql = "SELECT * FROM proposal a 
                INNER JOIN phrepuser b ON a.username = b.id
                WHERE a.sub_id = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
  
    
    public function getKindofEval($userid, $id) {
        $sql = "SELECT * FROM rev_evaltype a 
                INNER JOIN rev_groups b ON a.evaltype_id = b.evaluation_type
                WHERE b.sub_id = '$id' AND b.phrepuser_id = '$userid' AND b.review = '1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    public function getKindofEvalcomm($userid, $id, $r) {
        $sql = "SELECT * FROM rev_evaltype a 
                INNER JOIN rev_groups b ON a.evaltype_id = b.evaluation_type
                WHERE b.sub_id = '$id' and b.phrepuser_id = '$userid' and b.review = '$r'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function gettingAnswers($userid,$id,$ev) {
        $sql = "SELECT * FROM rev_answers a 
                INNER JOIN rev_questions b ON a.idq = b.idq
                WHERE sub_id = '$id' and revid = '$userid' and revform_id = '$ev'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }    
    
    public function gettingAnswerscomm($userid,$id,$r) {
        $sql = "SELECT * FROM rev_comment WHERE 
                sub_id = '$id' and phrepuser_id = '$userid' and version = '$r'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    public function gettingAnswerscommpa($userid,$id,$r,$ppaid) {
        $sql = "SELECT * FROM rev_commentpa WHERE 
                sub_id = '$id' and phrepuser_id = '$userid' and version = '$r' and ppa_id = '$ppaid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
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
    

    public function getReciever($id) {
        $sql = "SELECT * FROM proposal a 
                INNER JOIN phrepuser b ON a.username = b.id
                INNER JOIN membership_users c ON c.memberID = b.username
                WHERE a.sub_id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function gatherUnconfirmed($id) {
        $sql = "SELECT * FROM proposal_status a 
                INNER JOIN (SELECT MAX(id) as mid FROM proposal_status GROUP BY sub_id) b ON a.id = b.mid
                INNER JOIN submission c ON a.sub_id = c.sub_id
                INNER JOIN rec_list d ON c.reclist_id = d.id
                INNER JOIN review_type e ON e.sub_id = a.sub_id
                INNER JOIN review_type_list f ON e.rt_id = f.id
                INNER JOIN proposal g ON a.sub_id = g.sub_id
                WHERE d.secretary = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getAdditonalReviewer($id) {
        $sql = "SELECT phrepuser_id FROM `rev_groups` where sub_id = '$id' and confirmation = '0'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }

function upload_info($table, $fields, $id, $doctype, $useurl, $times) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '".$doctype."' AND revision = '".$times."'";
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
                $sql .= " WHERE sub_id = ".$val['1']." AND doctype = '".$doctype."' AND revision = '".$times."'";
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
function upload_infopa($table, $fields, $id, $doctype, $useurl, $times, $ppaid) {
        
        $sql = "SELECT * FROM ".$table." WHERE sub_id = '".$id."' AND doctype = '".$doctype."' AND revision = '".$times."' AND post_approval_type = '".$ppaid."'";
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
                $sql .= " WHERE sub_id = ".$val['1']." AND doctype = '".$doctype."' AND revision = '".$times."'";
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
    
function getclearanceDate($id) {
        $sql = "SELECT *, MAX(ec_id) AS ecid FROM ethical_clearance WHERE sub_id = '$id' and exp != 1";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}   

function getdocumentforrevision($id) {
        $sql = "SELECT * FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.sub_id = '".$id."' AND a.kind IN ('MP', 'SF')
                AND a.finaldoc = '1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getdocumentforrevisionpa($id,$ppaid) {
        $sql = "SELECT * FROM document_postapproval a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.sub_id = '".$id."' AND a.kind IN ('MP', 'SF')
                AND a.finaldoc = '1' AND a.post_approval_type = '".$ppaid."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getdocumentforrevisionpost($id) {
        $sql = "SELECT * FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.sub_id = '$id' AND a.post_finaldoc = '1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
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

function getmaxpropapp($id) {
        $sql = "SELECT MAX(pid) as pid FROM proposal_post_approval WHERE subid = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['pid'];
        return $fileid;
}

public function getmaxvalue_only($col, $table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT MAX($col) as col FROM " . $table . " WHERE " . $condition;
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $col = $row['col'];
        return $col;
    }

function getmaxsubmission($id, $paid) {
        $sql = "SELECT MAX(submission) as sub FROM collated_suggestion_disapproval_post WHERE sub_id = '$id' AND paid = '$paid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        if(count($row['sub']) == 0){$a = 1;}
        
        else{$a = $row['sub'] + 1;}
        
        return $a;
}

function getmaxsitevisitsub($id, $ppaid) {
        $sql = "SELECT MAX(submission) as submiss FROM sitevisit_decision WHERE subid = '$id' AND ppaid = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['submiss'];
        return $fileid;
}

function maxrepsitevisit($id, $ppaid) {
        $sql = "SELECT MAX(repeatition) as rep FROM sitevisit WHERE subid = '$id' AND post_approval_type = '$ppaid'";
                  
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rep'];
        return $fileid;
}

function getrequesttype($id) {
        $sql = "SELECT * FROM `proposal_post_approval` a 
                INNER JOIN (SELECT MAX(pid) as paid FROM proposal_post_approval WHERE subid = '$id') b ON a.pid = b.paid
                WHERE a.subid = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getmaxreviewer($id) {
        $sql = "SELECT MAX(review) as rev FROM rev_groups WHERE sub_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}
function getmaxreviewerpaa($id, $ppaid) {
        $sql = "SELECT MAX(review) as rev FROM rev_groupspa WHERE sub_id = '$id' AND ppa_id = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

function maxreviewerpa($id, $ppaid) {
        $sql = "SELECT MAX(review) as rev FROM rev_groupspa WHERE sub_id = '$id' and ppa_id = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

function getmaxreviewerpa($id, $ppaid) {
        $sql = "SELECT MAX(review) as rev FROM rev_groupspa WHERE sub_id = '$id' AND ppa_id = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

function getlastname($id) {
        $sql = "SELECT b.lname as lname FROM proposal a INNER JOIN phrepuser b ON a.username = b.id WHERE a.sub_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['lname'];
        return $fileid;
}

function getreviewtype($id) {
        $sql = "SELECT rt_id as rt FROM review_type WHERE sub_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $rt = $row['rt'];
        return $rt;
}

function getmaxrll($id) {
        $sql = "SELECT MAX(revision) as rev FROM document WHERE sub_id = '$id' and finaldoc = '1' and kind = 'RL'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

function getmaxrllpa($id) {
        $sql = "SELECT MAX(revision) as rev FROM document_postapproval WHERE sub_id = '$id' and finaldoc = '1' and kind = 'RL'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

function document_decision_letter($id, $kind) {
        $sql = "SELECT MAX(revision) as rev FROM document_postapproval WHERE sub_id = '$id' and finaldoc = '1' and kind = '$kind'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

function getmaxrlpa($id, $ppaid) {
        $sql = "SELECT MAX(revision) as rev FROM document_postapproval WHERE sub_id = '$id' and finaldoc = '0' and kind = 'RL' and post_approval_type = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}


function getmaxrl($id) {
        $sql = "SELECT MAX(revision) as rev FROM document WHERE sub_id = '$id' and kind = 'RL'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}


function getdocbybatch($id, $i) {
        $sql = "SELECT * FROM document a 
                INNER JOIN document_type b ON a.doctype = b.docid
                WHERE a.batchnum = '$i' AND a.sub_id = '$id' AND a.postapproval = '0'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}
        
function ongoingreview($id) {
        $sql = "SELECT *, count(a.sub_id) as cid FROM rev_groups a
                WHERE a.review = (SELECT MAX(b.review) FROM rev_groups b where b.sub_id = '$id') 
                AND a.sub_id = '$id' AND a.confirmation IN (0)";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}
function ongoingreviewfull($id) {
        $sql = "SELECT *, count(a.sub_id) as cid FROM rev_groups a
                WHERE a.review = (SELECT MAX(b.review) FROM rev_groups b where b.sub_id = '$id') 
                AND a.sub_id = '$id' AND a.confirmation IN (0) AND a.primary_reviewer = '1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function evaluated($id) {
        $sql = "SELECT count(a.sub_id) as mited FROM rev_groups a 
                WHERE a.review = (SELECT MAX(b.review) FROM rev_groups b where b.sub_id = '$id') 
                AND a.sub_id = '$id' AND a.evaluation_submitted IN (0)";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function evaluatedfull($id) {
        $sql = "SELECT count(a.sub_id) as mited FROM rev_groups a 
                WHERE a.review = (SELECT MAX(b.review) FROM rev_groups b where b.sub_id = '$id') 
                AND a.sub_id = '$id' AND a.evaluation_submitted IN (0) AND a.primary_reviewer = '1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getmaxid($id) {
        $sql = "SELECT * FROM proposal_status a 
                INNER JOIN (SELECT MAX(id) as mid FROM proposal_status WHERE sub_id = '$id') b  ON a.id = b.mid";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getmaxordersubmission($id, $year) {
        $sql = "SELECT MAX(ordering) as maxor FROM `submission` WHERE reclist_id = '$id' and year = '$year'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxor'];
        return $fileid;
}

function getmaxyear($id) {
        $sql = "SELECT MAX(year) as year FROM `submission` WHERE reclist_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['year'];
        return $fileid;
}

function fetchproposal($id) {
        $sql = "SELECT * FROM proposal a 
                INNER JOIN review_type b ON a.sub_id = b.sub_id
                INNER JOIN (SELECT * FROM review_type_duedate a 
                            INNER JOIN (SELECT MAX(id) as iddd FROM review_type_duedate GROUP BY subid) b ON a.id = b.iddd) d ON d.subid = a.sub_id
                INNER JOIN review_type_list c ON b.rt_id = c.id
                WHERE a.sub_id = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getMaxReview($id) {
        $sql = "SELECT *, MAX(review) as maxid FROM rev_groups WHERE sub_id = '$id'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxid'];
        return $fileid;
}

function getAllReviewer($subid, $rev) {
        $sql = "SELECT * FROM rev_groups WHERE sub_id = '$subid' and review = '$rev'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getAllReviewersv($subid, $ppaid, $rev) {
        $sql = "SELECT * FROM rev_groupspa WHERE sub_id = '$subid' and ppa_id = '$ppaid' and review = '$rev'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}
function getAllReviewerpa($subid, $rev) {
        $sql = "SELECT * FROM rev_groups WHERE sub_id = '$subid' and review = '$rev' and evaluation_submitted = '1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}


function getAllReviewerpaa($subid, $rev) {
        $sql = "SELECT * FROM rev_groupspa WHERE sub_id = '$subid' and review = '$rev' and evaluation_submitted = '1'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}
    
function getStat($id) {
        $sql = "SELECT * FROM `proposal_status` WHERE id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getInfo($id) {
        $sql = "SELECT * FROM proposal a 
                INNER JOIN phrepuser b ON a.username = b.id
                INNER JOIN membership_users c ON b.username = c.memberID
                WHERE a.sub_id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function getSecInfo($id) {
        $sql = "SELECT * FROM phrepuser a
                INNER JOIN membership_users b ON a.username = b.memberID
                WHERE a.id = '".$id."'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function perRevision($id, $rev) {
        $sql = "SELECT *  FROM `document` WHERE `sub_id` = '$id' AND `revision` = '$rev' AND `kind` IN ('MP')";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function pullChairman($id, $userid, $typeid) {
        $sql = "SELECT * FROM combased a 
                INNER JOIN rec_list b ON a.inst_id = b.id
                INNER JOIN rec_groups c ON b.id = c.rec_list_id
                WHERE a.sub_id = '$id' and b.secretary = '$userid' AND c.type_id = '$typeid' LIMIT 1";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}


function showingEthicalForm($subid, $ppaid, $i) {
        $sql = "SELECT *  FROM document_postapproval a 
                INNER JOIN document_type b ON a.doctype = b.docid 
                WHERE a.sub_id = '$subid' AND a.finaldoc = '1' AND post_approval_type = '$ppaid' AND batchnum = '$i'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}


function showingEthicalFormpa($subid, $ppaid, $i) {
        $sql = "SELECT *  FROM document_postapproval a 
                INNER JOIN document_type b ON a.doctype = b.docid 
                WHERE a.sub_id = '$subid' AND post_approval_type = '$ppaid' AND batchnum = '$i'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}


function getIDNotAssigned($subid, $ercid) {

        $sqlprop = "SELECT * FROM review_type WHERE sub_id = '$subid'";
       
        $query = mysqli_query($this->con,$sqlprop);
        while ($row = mysqli_fetch_assoc($query)) {
            $arrayprop[] = $row;
        }

        foreach ($arrayprop as $ap){
             
             if($ap['rt_id'] === '1'){

                  $sql = "SELECT *, a.phrepuser_id as puser FROM `rec_groups` a WHERE a.phrepuser_id NOT IN (SELECT b.phrepuser_id FROM rev_groups b WHERE b.sub_id = '$subid') AND a.rec_list_id = '$ercid'";

             }else if($ap['rt_id'] === '3'){

                  $sql = "SELECT *, a.phrepuser_id as puser FROM `rec_groups` a WHERE a.phrepuser_id IN (SELECT b.phrepuser_id FROM rev_groups b WHERE b.sub_id = '$subid' AND b.primary_reviewer != '1') AND a.rec_list_id = '$ercid'";
                 
             }
        }
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }

       

        return $array;
}

function selectReviewer($id) {
        $sql = "SELECT * FROM phrepuser a 
                INNER JOIN membership_users b ON a.username = b.memberID
                WHERE id = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function fetchReviewers($id) {
        $sql = "SELECT *, c.id as uid FROM rec_list a 
                INNER JOIN rec_groups b ON a.id = b.rec_list_id
                INNER JOIN phrepuser c ON b.phrepuser_id = c.id
                INNER JOIN membership_users d ON c.username = d.memberID
                INNER JOIN rec_groups_type e ON b.type_id = e.id
                WHERE a.secretary = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
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

function getMaxRevisionEC($subid){
        $sql = "SELECT MAX(revision) as maxrevec  FROM `document` WHERE `sub_id` = '".$subid."' AND kind = 'EC'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrevec'];
        return $fileid;
}

function getMaxRevisions($subid, $kind){
        $sql = "SELECT MAX(revision) as maxrevec  FROM `document` WHERE `sub_id` = '".$subid."' AND kind = '$kind'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrevec'];
        return $fileid;
}

function getMaxRevisionECpa($subid){
        $sql = "SELECT MAX(revision) as maxrevec  FROM `document_postapproval` WHERE `sub_id` = '".$subid."' AND kind = 'EC'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrevec'];
        return $fileid;
}


function getMaxRevisionAAL($subid){
        $sql = "SELECT MAX(revision) as maxrevec  FROM `document_postapproval` WHERE `sub_id` = '".$subid."' AND kind = 'AAL'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrevec'];
        return $fileid;
}

function getMaxRevisionAL($subid, $kind){
        $sql = "SELECT MAX(revision) as maxrevec  FROM `document_postapproval` WHERE `sub_id` = '".$subid."' AND kind = '$kind'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrevec'];
        return $fileid;
}

function getMaxRevisionJL($subid){
        $sql = "SELECT MAX(revision) as maxrevec  FROM `document` WHERE `sub_id` = '".$subid."' AND kind = 'JL'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['maxrevec'];
        return $fileid;
}

function getMaxRevisionperpa($subid, $ppaid){
        $sql = "SELECT MAX(revision) as rev  FROM `document_postapproval` WHERE `sub_id` = '".$subid."' AND `post_approval_type` = '$ppaid'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['rev'];
        return $fileid;
}

function getDeclined($subid){
        $sql = "SELECT COUNT(confirmation) as confirm FROM `rev_groups`WHERE sub_id = '$subid' and confirmation = '2'";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row['confirm'];
        return $fileid;
}

function getevaluations($subid){
        $sql = "SELECT DISTINCT(phrepuser_id) as puid FROM `rev_groups` WHERE `sub_id` = ".$subid."";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
}

function fetch_documents_post_approval($wherein, $subid){
    $sql = "SELECT * FROM `document_postapproval` WHERE `sub_id` = ".$subid." AND `kind` IN ".$wherein;
    
    $query = mysqli_query($this->con,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $array[] = $row;
    }
    return $array;
}

public function inserting_multiple_data($table2, $field2, $count) {
        for ($i = 0; $i < $count; $i++) {
            $sql = "";
            $condition = "";
            foreach ($field2[$i] as $key => $value) {
                $condition .= $key . "='" . $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            $sql = " SELECT * FROM " . $table2 . " WHERE " . $condition;

            $query = mysqli_query($this->con, $sql);
            $row = mysqli_num_rows($query);

            if ($row > 0) {
                echo "INSERTED<br>";
            } else {
                $sql = "";
                $sql .= "INSERT INTO " . $table2;
                $sql .= " (" . implode(",", array_keys($field2[$i])) . ") VALUES ";
                $sql .= " ('" . implode("','", array_values($field2[$i])) . "')";
//                                echo $sql;
                $query = mysqli_query($this->con, $sql);
                if ($query) {
                    echo "&nbsp; added new in the country_list table!<br>";
                }
            }
        }
    }
function sendEmail3att($emailto, $body, $subject, $att) {
        //notifyMemberApproval($memberID);


        $mail = new PHPMailer;
                
        $mail->SMTPDebug = 3;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'citdsadmin@pchrd.dost.gov.ph';     // SMTP username
        $mail->Password = 'C1tds_4m1n';                       // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP');           // WHO SENT
        $mail->addAddress($emailto);                                        // Name is optional //SENT TO
        $mail->addReplyTo('nationalethicscommittee.ph@gmail.com', 'NEC');        // WHERE TO REPLY
        // $mail->addCC('');                                                       // CC
        $mail->addBCC('hbcornea@pchrd.dost.gov.ph','dcaguila@pchrd.dost.gov.ph');                                                      //BCC

        $mail->addAttachment($att);                                               // Add attachments /var/tmp/file.tar.gz
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
    
function sendEmail3($emailto, $body, $subject, $emailsec) {
        //notifyMemberApproval($memberID);


        $mail = new PHPMailer;
                
        // $mail->SMTPDebug = 3;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'citdsadmin@pchrd.dost.gov.ph';     // SMTP username
        $mail->Password = 'C1tds_4m1n';                       // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP');           // WHO SENT
        $mail->addAddress($emailto);                                        // Name is optional //SENT TO
        $mail->addReplyTo($emailsec, 'PHREP');        // WHERE TO REPLY
        $mail->addCC($emailsec);                                                       // CC
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

function sendEmail($emailto, $ecpath, $nameTo, $subject, $secEmail) {
        //notifyMemberApproval($memberID);

        $currDir = dirname(__FILE__);
        require "{$currDir}/mailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;
        
        // $mail->SMTPDebug = 3;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'citdsadmin@pchrd.dost.gov.ph';     // SMTP username
        $mail->Password = 'C1tds_4m1n';                       // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP');           // WHO SENT
        $mail->addAddress($emailto, '');                                        // Name is optional //SENT TO
        $mail->addReplyTo('nationalethicscommittee.ph@gmail.com', 'NEC');       // WHERE TO REPLY
        // $mail->addCC('');                                                       // CC
        $mail->addBCC('hbcornea@pchrd.dost.gov.ph', $secEmail);                 //BCC

        $mail->addAttachment('../main/' . $ecpath . '');                        // Add attachments /var/tmp/file.tar.gz
        // $mail->addAttachment('');                                               // Optional name /tmp/image.jpg', 'new.jpg
        $mail->isHTML(true);                                                    // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body = $nameTo. '<br>SAMPLE ONLY';
        $mail->AltBody = '';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            header("location:sec_dashboard_active.php");
        }
        
    }

function sendEmail1($emailto, $body, $subject) {
        //notifyMemberApproval($memberID);

        $currDir = dirname(__FILE__);
        require "{$currDir}/mailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;
        
        // $mail->SMTPDebug = 3;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'citdsadmin@pchrd.dost.gov.ph';     // SMTP username
        $mail->Password = 'C1tds_4m1n';                       // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        
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
    
function sendemailresubmission_to_reviewer($sub_id, $secid, $phrepuserid, $review){
     //EMAIL FOR RESUBMISSION

     //email sa secretariat
     $where = array("id" => $secid);
     $getphrepuser = $this->fetch_record("phrepuser", $where);
     foreach($getphrepuser as $gpu){
         $where = array("memberID" => $gpu['username']);
         $getemailsec = $this->fetch_record("membership_users", $where);
         foreach($getemailsec as $emsec){
             $emailsec = $emsec['email'];
         }
     }         
     
         
         $table = array("membership_users", "phrepuser", "rev_groups", "proposal");
         $join_on = array("memberID", "username", "id", "phrepuser_id", "sub_id", "sub_id", );
         $where = array("phrepuser.id" => $phrepuserid, "rev_groups.sub_id" => $sub_id, "rev_groups.review" => $review);
         $getdata = $this->fetch_record_innerjoin($table, $join_on, $where);
         if($getdata){
             foreach($getdata as $dt){
                 $revemail = $dt['email'];
                 $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
             //    print_r($change);
             }        
         }
         
         $table1 = array("phrepuser", "rec_list");
         $join_on1 = array("id", "secretary");
         $where1 = array("phrepuser.id" => $phrepuserid);
         $getdata1 = $this->fetch_record_innerjoin($table1, $join_on1, $where1);
         if($getdata1){
             foreach($getdata1 as $dt1){
                 array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
 //                print_r($change);
             }
         }
         
         $where3 = array("id" => "9");
         $getTemplate = $this->fetch_record_with_where("email_templates", $where3);
         foreach ($getTemplate as $tplate) {
             $subject = $tplate['subject'];
             $template = $tplate['body'];
         }
 
         $find = array("{reviewername}","{proposaltitle}","{secretaryname}","{ercname}"); 
         $readytosend = str_replace($find, $change, $template);
         if($this->sendEmail3($revemail, $readytosend, $subject, $emailsec)){
              header("location:sec_dashboard_active.php");
        } 
}
    
}

$obj = new UploadOperation();

date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));

if (isset($_POST['viewproposal'])) {
    $id = $_POST['submid'];
    header("location:uploadsuppfiles.php?id=".$id);
}
if (isset($_POST['completeresubmitted'])) {
    $id = $_POST['submid'];

   
    $complete = array(
        "sub_id" => $id,
        "status_action" => "3",
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
    }
    
    //additional due date
    $ddate = date('Y-m-d',strtotime($_POST['newddate']));
    //additional due date
    $newddate = array("rt_duedate" => $ddate, "subid" => $id);
    if($obj->insert_record("review_type_duedate", $newddate)){
        
    }
    //additional due date
    
    $getrt = $obj->getreviewtype($id);
//    echo $getrt; exit;
    if($getrt == '1'){ //INSERTING REVIEWERS WHO EVALUATES FOR EXPEDITED
        //SAVE REVIEWERS FROM REVISE AND RESUBMIT
        $maxrev = $obj->getmaxreviewer($_POST['submid']);
        $getreviewers = $obj->getMemberReviewer($_POST['submid'], $maxrev);
        foreach ($getreviewers as $reviwer) {

            $phrepid = $reviwer['phrepuser_id'];
            $review = $reviwer['review']+1;
            $primerev =  $reviwer['primary_reviewer'];

            if(($reviwer['type_id'] == '1')&&($reviwer['eval_date'] == '0')){           
                $revset = array(
                    "sub_id" => $_POST['submid'],
                    "review" => $review,
                    "phrepuser_id" => $phrepid,
                    "confirmation" => "1",
                    "primary_reviewer" => $primerev,
                    "evaluation_submitted" => "1"
                );
                if($obj->insert_record("rev_groups", $revset)){  
                    $obj->sendemailresubmission_to_reviewer($_POST['submid'], $_POST['userid'], $phrepid, $review);
                    
                }
                header("location:sec_dashboard_active.php");

            }
            else{                            
                $revset = array(
                    "sub_id" => $_POST['submid'],
                    "review" => $review,
                    "phrepuser_id" => $phrepid,
                    "confirmation" => "1",
                    "primary_reviewer" => $primerev
                );
                if($obj->insert_record("rev_groups", $revset)){
                    $obj->sendemailresubmission_to_reviewer($_POST['submid'], $_POST['userid'], $phrepid, $review);
                    
                }
                header("location:sec_dashboard_active.php");
            }


        }//SAVE REVIEWERS FROM REVISE AND RESUBMIT        
    }
    else if($getrt == '3'){ //INSERTING REVIEWERS WHO EVALUATE FOR FULL
        //SAVE REVIEWERS FROM REVISE AND RESUBMIT
        $maxrev = $obj->getmaxreviewer($_POST['submid']);
        $getreviewers = $obj->getMemberReviewer($_POST['submid'], $maxrev);
        foreach ($getreviewers as $reviwer) {
            
            $phrepid = $reviwer['phrepuser_id'];
            $review = $reviwer['review']+1;
            $primerev =  $reviwer['primary_reviewer'];
            
            if($reviwer['evaluation_submitted'] == '1'){
                $revset = array(
                    "sub_id" => $_POST['submid'],
                    "review" => $review,
                    "phrepuser_id" => $phrepid,
                    "confirmation" => "1",
                    "primary_reviewer" => $primerev
                );
                if($obj->insert_record("rev_groups", $revset)){   
                    $obj->sendemailresubmission_to_reviewer($_POST['submid'], $_POST['userid'], $phrepid, $review);
                    
                    
                }
            }

        }//SAVE REVIEWERS FROM REVISE AND RESUBMIT    
//            echo "<pre>"; print_r($revset); echo "</pre>"; exit;       
        header("location:sec_dashboard_active.php");
    }
        
    // header("location:sec_dashboard_active.php");
    
}


if (isset($_POST['completefirst'])) {
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => "1",
        "status_date" => $datetime,
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:sec_dashboard_active.php");
    }
    
    //GET REC
    $where2 = array("sub_id" => $id);
    $getrec = $obj->fetch_record_with_where("submission", $where2);
    if ($getrec) {
        foreach ($getrec as $rec) {
            $recs = $rec['reclist_id'];
        }
    }
    
    $year = date("Y",strtotime("now"));
    
    $getmaxyear = $obj->getmaxyear($recs);
    
    if($year == $getmaxyear){
        
        $getmaxorder = $obj->getmaxordersubmission($recs, $year);
        $addedorder = $getmaxorder + 1;
        $where3 = array("sub_id" => $id, "reclist_id" => $recs);
        $fields3 = array("ordering" => $addedorder);
        if($obj->update_record("submission", $where3, $fields3)){
            $lastname = $obj->getlastname($id);
            $year = date("Y",strtotime("now"));
            $coding = $year.'-'.$addedorder.'-'.ucfirst(strtolower($lastname));
            $where4 = array("sub_id" => $id, "reclist_id" => $recs);
            $fields4 = array("coding" => $coding, "year" => $year);       
            if($obj->update_record("submission", $where4, $fields4)){
                header("location:sec_dashboard_active.php");
            }
        }        
    }
    else{
        $yr = $year + 1;
        $where3 = array("sub_id" => $id, "reclist_id" => $recs);
        $addedorder = 1;
        $fields3 = array("ordering" => $addedorder);
        if($obj->update_record("submission", $where3, $fields3)){
            $lastname = $obj->getlastname($id);
            $year = date("Y",strtotime("now"));
            $coding = $year.'-'.$addedorder.'-'.ucfirst(strtolower($lastname));
            $where4 = array("sub_id" => $id, "reclist_id" => $recs);
            $fields4 = array("coding" => $coding, "year" => $year);       
            if($obj->update_record("submission", $where4, $fields4)){
                header("location:sec_dashboard_active.php");
            }
        }
    }
    
    

    
     
}

if (isset($_POST['complete'])) {
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => $_POST['statusaction'],
        "status_date" => $_POST['statusdate'],
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:sec_dashboard_active.php");
    }
    
    $newduedate = array(
        "subid" => $id,
        "rt_duedate" => date("Y-m-d", strtotime($_POST['newduedate']))
    );
    if($obj->insert_record("review_type_duedate", $newduedate)){
        header("location:sec_dashboard_active.php");
    }
     
}
if (isset($_POST['complete'])) { //POST-APPROVAL ACTIONS //to be double check ang gigamit na complete, might conflict.
    $id = $_POST['subids']; //GAMITA NING subids for search
    $review = $_POST['review'];
    $puserid = $_POST['puserid'];
    $primerev = $_POST['primerev'];
    $confirm = $_POST['confirm'];
    
    $count = count($id);
    
    for ($i = 0; $i < $count; $i++) {
        $revs[$i] = array(
            "sub_id" => $id[$i],
            "review" => $review[$i],
            "phrepuser_id" => $puserid[$i],
            "primary_reviewer" => $primerev[$i],
            "confirmation" => $confirm[$i]
        );
    }    
    if($obj->inserting_multiple_data("rev_groups", $revs, $count)){
//        header("location:sec_dashboard_active.php");
    }
    
    $cmRev = array(
        "evaluation_submitted" => "1",
        "decision" => "1"
    );
    $wherecm = array(
        "sub_id" => $_POST['subidss'],
        "review" => $_POST['reviews'],
        "phrepuser_id" => $_POST['puserids']
    );
    if($obj->update_record("rev_groups", $wherecm, $cmRev)){
        
    }
     
}

//if( (isset($_POST['complete'])) || (isset($_POST['completepa']))){//FOR EMAIL
//    $userid = $_POST['userid'];
//    $subid = $_POST['submid']; 
//    
//            
//    //email sa secretariat
//    $where = array("id" => $userid);
//    $getphrepuser = $obj->fetch_record("phrepuser", $where);
//    foreach($getphrepuser as $gpu){
//        $where = array("memberID" => $gpu['username']);
//        $getemailsec = $obj->fetch_record("membership_users", $where);
//        foreach($getemailsec as $emsec){
//            $emailsec = $emsec['email'];
//        }
//    }         
//        
//   
//    
//    $table = array("proposal", "phrepuser", "membership_users");
//    $join_on = array("username", "id", "username", "memberID");
//    $where = array("proposal.sub_id" => $subid);
//    $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
//    if($getdata){
//        foreach($getdata as $dt){
//            $secemail = $dt['email'];
//            $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
//            print_r($change);
//        }        
//    }
//    
//    $table1 = array("phrepuser", "rec_list");
//    $join_on1 = array("id", "secretary");
//    $where1 = array("phrepuser.id" => $userid);
//    $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
//    if($getdata1){
//        foreach($getdata1 as $dt1){
//            array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
//            print_r($change);
//        }
//    }
//    
//    $where3 = array("id" => "4");
//    $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
//    foreach ($getTemplate as $tplate) {
//        $subject = $tplate['subject'];
//        $template = $tplate['body'];
//    }
//
//    $find = array("{researchername}","{proposaltitle}","{secretaryname}","{ercname}"); 
//    $readytosend = str_replace($find, $change, $template);
//    
//    if($obj->sendEmail3($secemail, $readytosend, $subject, $emailsec)){
//         header("location:sec_dashboard_active.php");}    
//    
////    exit;
//}

//COMPLETE PARA SA POST APPROVAL

if (isset($_POST['completepa'])) {
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => $_POST['statusaction'],
        "status_date" => $_POST['statusdate'],
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:sec_dashboard_active.php#postapproval");
    }
    
    $newduedate = array(
        "subid" => $id,
        "rt_duedate" => date("Y-m-d", strtotime($_POST['newduedate']))
    );
    if($obj->insert_record("review_type_duedate", $newduedate)){
        header("location:sec_dashboard_active.php#postapproval");
    }
    
    $documenttype = array("41", "43", "44", "45", "46", "47", "48", "49", "50", "51", "52", "53", "54", "55", "56", "57", "58");
    
//  FINALDOC OF THOSE DOCTYPE ABOVE WILL TURN 0
    $a = 0;
    for($i=0;$i<count($documenttype); $i++){
        echo $i;
        $svlunfinal = array(
            "sub_id" => $id,
            "post_approval_type" => $_POST['ppaids'],
            "doctype" => $documenttype[$i]
        );  
        
        $kind = array(
            "finaldoc" => "0"
        );
        
        if($obj->update_record("document_postapproval", $svlunfinal, $kind)){
            header("location:sec_dashboard_active.php#postapproval");
        }
//        print_r($svlunfinal);
    } 
    $a++;
//  FINALDOC OF THOSE DOCTYPE ABOVE WILL TURN 0    
}
        
if (isset($_POST['completepa'])) { //POST-APPROVAL ACTIONS //to be double check ang gigamit na complete, might conflict.
    $id = $_POST['subids']; //GAMITA NING subids for search
    $ppaid = $_POST['ppaid'];
    $review = $_POST['review'];
    $puserid = $_POST['puserid'];
    $primerev = $_POST['primerev'];
    $confirm = $_POST['confirm'];
    
    $count = count($id);
    
    for ($i = 0; $i < $count; $i++) {
        $revs[$i] = array(
            "sub_id" => $id[$i],
            "ppa_id" => $ppaid[$i],
            "review" => $review[$i],
            "phrepuser_id" => $puserid[$i],
            "primary_reviewer" => $primerev[$i],
            "confirmation" => $confirm[$i]
        );
    }    
    if($obj->inserting_multiple_data("rev_groupspa", $revs, $count)){
//        header("location:sec_dashboard_active.php");
    }
    
    $cmRev = array(
        "evaluation_submitted" => "1",
        "decision" => "1"
    );
    $wherecm = array(
        "sub_id" => $_POST['subidss'],
        "review" => $_POST['reviews'],
        "phrepuser_id" => $_POST['puserids']
    );
    if($obj->update_record("rev_groupspa", $wherecm, $cmRev)){
        
    }
     
}

if (isset($_POST['completefr'])) {
    $id = $_POST['submid'];
    
    $complete = array(
        "sub_id" => $id,
        "status_action" => $_POST['statusaction'],
        "status_date" => $_POST['statusdate'],
        "status_username" => $_POST['userid']
    );
    if($obj->insert_record("proposal_status", $complete)){
        header("location:sec_dashboard_active.php");
    }
    
    $wherefr = array(
        "sub_id" => $id,
        "finaldoc" => "1",
        "doctype" => "14"
    );
    
    $updatefr = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document", $wherefr, $updatefr)){}
     
}


if (isset($_GET['expedited'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $u = $_GET['u'];
        
        $typeofreview = array(
            "sub_id" => $id,
            "rt_id" => '1',
            "rt_username" => $u
        );
        
        if($obj->insert_record("review_type",$typeofreview)){
            header("location:sec_expedited_confirm.php?id=$id");
        }
        
    }

}

if (isset($_GET['exempted'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $u = $_GET['u'];
        
        $typeofreview = array(
            "sub_id" => $id,
            "rt_id" => '2',
            "rt_username" => $u
        );
        
        if($obj->insert_record("review_type",$typeofreview)){

            header("location:sec_exempt_initial.php?id=$id");
        }
        
    // $complete = array(
    //     "sub_id" => $id,
    //     "status_action" => '6',
    //     "status_date" => $datetime,
    //     "status_username" => $u
    // );
    // if($obj->insert_record("proposal_status", $complete)){
    //     header("location:sec_dashboard_active.php#approved");
    // }
    }
    

}

if (isset($_GET['full'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $u = $_GET['u'];
        
        $typeofreview = array(
            "sub_id" => $id,
            "rt_id" => '3',
            "rt_username" => $u
        );
        
        if($obj->insert_record("review_type",$typeofreview)){
            header("location:sec_full_confirm.php?id=$id");
        }
        
    }

}

if (isset($_GET['fulldelete'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $u = $_GET['u'];
        
        $typeofreview = array(
            "sub_id" => $id,
            "rt_username" => $u
        );
        
        if($obj->delete_record("review_type",$typeofreview)){
            header("location:sec_dashboard_active.php#review");
        }
        
    }

}

if (isset($_GET['undo'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
            $typeofreview = array(
            "sub_id" => $id);
        
            if($obj->delete_record("review_type",$typeofreview)){
                 header("location:sec_dashboard_active.php#review");
            }

            $where = array("sub_id" => $id, "doctype" => "59");
            if($obj->delete_record("document",$where)){
                header("location:sec_dashboard_active.php#review");
           }
    } 
}

if (isset($_POST['assignexpedited'])) {
    $id = $_POST['sub_id'];
    $rev = $_POST['rev'];
    $chairman = $_POST['chairmanid'];
    $note = $_POST['notetocoreviewer'];
    $username = $_POST['userid'];//    
//    DUE DATE REVIEW_TYPE
    $duedate = date("Y-m-d", strtotime($_POST['duedate']));
        
        
    //email sa secretariat
    $where = array("id" => $username);
    $getphrepuser = $obj->fetch_record("phrepuser", $where);
    foreach($getphrepuser as $gpu){
        $where = array("memberID" => $gpu['username']);
        $getemailsec = $obj->fetch_record("membership_users", $where);
        foreach($getemailsec as $emsec){
            $emailsec = $emsec['email'];
        }
    }   
        
    
    //GETTING THE DATA IN MULTIDIMENSIONAL ARRAY
    $count = count($rev);
    for ($i = 0; $i < $count; $i++) {
        $revlist[$i] = array(
            "sub_id" => $id,
            "review" => "1",
            "phrepuser_id" => $rev[$i],
            "confirmation" => "0",
            "primary_reviewer" => "1"
        );
        if($obj->insert_record("rev_groups", $revlist[$i])){
//            header("location:sec_dashboard_active.php#review");
            //SEND EMAIL
            $table = array("review_type", "proposal", "rev_groups", "phrepuser", "membership_users");
            $join_on = array("sub_id", "sub_id", "sub_id", "sub_id", "phrepuser_id", "id", "username", "memberID");
            $where = array("rev_groups.sub_id" => $id, "rev_groups.phrepuser_id" => $rev[$i]);
            $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
            if($getdata){
                foreach($getdata as $dt){
                    $secemail = $dt['email']; echo $secemail;
                    $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle'],date('F d, Y',strtotime($duedate)));
//                    print_r($change);
                }  
            }
            $table1 = array("proposal", "submission", "rec_list", "phrepuser");
            $join_on1 = array("sub_id", "sub_id", "reclist_id", "id", "secretary", "id");
            $where1 = array("rec_list.secretary" => $username, "proposal.sub_id" => $id);
            $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
            if($getdata1){
                foreach($getdata1 as $dt1){
                    array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
                    print_r($change); echo "<br>";
                }
            }
            
            $where3 = array("id" => "5");
            $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
            foreach ($getTemplate as $tplate) {
                $subject = $tplate['subject'];
                $template = $tplate['body'];
            }

            $find = array("{reviewerName}","{proposalTitle}","{reviewDueDate}","{secretaryname}","{ercName}"); 
            $readytosend = str_replace($find, $change, $template);

            if($obj->sendEmail3($secemail, $readytosend, $subject, $emailsec)){
//                 header("location:sec_dashboard_active.php#review");                 
            }
              
    }
    }
}

if (isset($_POST['assignexpedited'])){ //Commentted for applying email
    $id = $_POST['sub_id'];
    $chairman = $_POST['chairmanid'];
    $note = $_POST['notetocoreviewer'];
    $username = $_POST['userid'];
    
    //SAVING NOTE
    $notetocorev = array(
        "sub_id" => $id,
        "sender" => $username,
        "message" => $note
    );
    if($obj->insert_record("note", $notetocorev)){
    
    }
    
    //SAVING CHAIRMAN  
    $assignchr = $_POST['revchr'];

    if($assignchr == '1'){

        $assignchairman = array(
            "sub_id" => $id,
            "review" => "1",
            "phrepuser_id" => $chairman,
            "confirmation" => "0",
            "primary_reviewer" => "1",
            "evaluation_submitted" => "0"
        );
        
        //email sa secretariat
        $where = array("id" => $username);
        $getphrepuser = $obj->fetch_record("phrepuser", $where);
        foreach($getphrepuser as $gpu){
            $where = array("memberID" => $gpu['username']);
            $getemailsec = $obj->fetch_record("membership_users", $where);
            foreach($getemailsec as $emsec){
                $emailsec = $emsec['email'];
            }
        }   
    
        if($obj->insert_record("rev_groups", $assignchairman)){
            //SEND EMAIL
                $table = array("review_type", "proposal", "rev_groups", "phrepuser", "membership_users");
                $join_on = array("sub_id", "sub_id", "sub_id", "sub_id", "phrepuser_id", "id", "username", "memberID");
                $where = array("rev_groups.sub_id" => $id, "rev_groups.phrepuser_id" => $chairman);
                $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
                if($getdata){
                    foreach($getdata as $dt){
                        $secemail = $dt['email']; echo $secemail;
                        $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle'],date('F d, Y',strtotime($duedate)));
    //                    print_r($change);
                    }  
                }
                $table1 = array("proposal", "submission", "rec_list", "phrepuser");
                $join_on1 = array("sub_id", "sub_id", "reclist_id", "id", "secretary", "id");
                $where1 = array("rec_list.secretary" => $username, "proposal.sub_id" => $id);
                $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
                if($getdata1){
                    foreach($getdata1 as $dt1){
                        array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
                        print_r($change); echo "<br>";
                    }
                }

                $where3 = array("id" => "5");
                $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
                foreach ($getTemplate as $tplate) {
                    $subject = $tplate['subject'];
                    $template = $tplate['body'];
                }

                $find = array("{reviewerName}","{proposalTitle}","{reviewDueDate}","{secretaryname}","{ercName}"); 
                $readytosend = str_replace($find, $change, $template);

                if($obj->sendEmail3($secemail, $readytosend, $subject, $emailsec)){
    //                 header("location:sec_dashboard_active.php#review");                 
                }
    //        header("location:sec_dashboard_active.php#review");
        }
        
    }
    else{
        $assignchairman = array(
            "sub_id" => $id,
            "review" => "1",
            "phrepuser_id" => $chairman,
            "confirmation" => "1",
            "primary_reviewer" => "0",
            "evaluation_submitted" => "1"
        );
        if($obj->insert_record("rev_groups", $assignchairman)){
            //SEND EMAIL
                $table = array("review_type", "proposal", "rev_groups", "phrepuser", "membership_users");
                $join_on = array("sub_id", "sub_id", "sub_id", "sub_id", "phrepuser_id", "id", "username", "memberID");
                $where = array("rev_groups.sub_id" => $id, "rev_groups.phrepuser_id" => $chairman);
                $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
                if($getdata){
                    foreach($getdata as $dt){
                        $secemail = $dt['email']; echo $secemail;
                        $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle'],date('F d, Y',strtotime($duedate)));
    //                    print_r($change);
                    }  
                }
                $table1 = array("proposal", "submission", "rec_list", "phrepuser");
                $join_on1 = array("sub_id", "sub_id", "reclist_id", "id", "secretary", "id");
                $where1 = array("rec_list.secretary" => $username, "proposal.sub_id" => $id);
                $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
                if($getdata1){
                    foreach($getdata1 as $dt1){
                        array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
                        print_r($change); echo "<br>";
                    }
                }

                $where3 = array("id" => "5");
                $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
                foreach ($getTemplate as $tplate) {
                    $subject = $tplate['subject'];
                    $template = $tplate['body'];
                }

                $find = array("{reviewerName}","{proposalTitle}","{reviewDueDate}","{secretaryname}","{ercName}"); 
                $readytosend = str_replace($find, $change, $template);

                if($obj->sendEmail3($secemail, $readytosend, $subject, $emailsec)){
    //                 header("location:sec_dashboard_active.php#review");                 
                }
    //        header("location:sec_dashboard_active.php#review");
        }
        
    } 
    
    ///DUE DATE REVIEW_TYPE
    $duedate = date("Y-m-d", strtotime($_POST['duedate']));
    
    $where = array(
        "sub_id" => $id
    );    
    
    $fields = array(
        "subid" => $id,
        "rt_duedate" => $duedate
    ); 
    if($obj->insert_record("review_type_duedate", $fields)){
        header("location:sec_dashboard_active.php#review");
    } 
    
    //STATUS UPDATE
    $propstatusupdate = array(
        "sub_id" => $id,
        "status_action" => "3",
        "status_username" => $username
    );
    
    if($obj->insert_record("proposal_status", $propstatusupdate)){
        header("location:sec_dashboard_active.php#review");
    }    
}

//if (isset($_POST['assignexpedited'])){
//    
//    
//}

if (isset($_POST['assignexpeditedadd'])) {

    $id = $_POST['sub_id'];
    $rev = $_POST['rev'];
    $username = $_POST['userid'];

    // GETTING THE DATA IN MULTIDIMENSIONAL ARRAY
    $count = count($rev);
    for ($i = 0; $i < $count; $i++) {
        $revlist[$i] = array(
            "sub_id" => $id,
            "review" => 1,
            "phrepuser_id" => $rev[$i],
            "confirmation" => 0
        );
    }


    // //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    // $values = array();
    //     foreach ($revlist as $rowValues) {
    //         foreach ($rowValues as $key => $rowValue) {
    //              $rowValues[$key] = mysql_real_escape_string($rowValues[$key]);
    //         }

    //         $values[] = "(" . implode(', ', $rowValues) . ")";
    //     }
        
    // //GETTING KEYS OF A MULTIDIMENSIONAL ARRAY    
    // $keys = array_keys($rowValues);

    // //SAVING CO-REVIEWER
    // if($obj->insert_multiple("rev_groups", $keys, $values)){
    //     header("location:sec_addassign_primrev.php?id=".$id);
    // }


    // added by jm:code here

    //kunin muna validation ng review type expi ba o full review
    $reviewType = "SELECT * FROM review_type WHERE sub_id = '$id'";
    $q = mysqli_query($connect, $reviewType) or die (mysqli_error($connect));
    
    $codes = $revlist;

    foreach ($codes as $code){
        
        // var_dump($code['phrepuser_id']);

        foreach($q as $q1){
            
            if($q1['rt_id'] == '1'){

                  $query1 = "INSERT INTO rev_groups (sub_id, review, phrepuser_id, confirmation, primary_reviewer) VALUES ('". $code['sub_id']."', '1',  '". $code['phrepuser_id']."', '0', '1')";

            }else if($q1['rt_id'] == '3'){

                  $query1 = "UPDATE rev_groups SET primary_reviewer = '1' WHERE phrepuser_id = '".$code['phrepuser_id']."'";

            }
        }

        $query = mysqli_query($connect, $query1) or die (mysqli_error($connect));
    }

        return header("location:sec_dashboard_active.php#review");

}

if (isset($_POST['assignprimary'])) {
    
    $id = $_POST['sub_id'];
    $prev = $_POST['prev'];
    $username = $_POST['userid'];
    
    //GETTING THE DATA IN MULTIDIMENSIONAL ARRAY
    $count = count($prev);
    for ($i = 0; $i < $count; $i++) {
        $revlist[$i] = array(
            "sub_id" => $id,
            "phrepuser_id" => $prev[$i]
        );
    }
    //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    $values = array();
        foreach ($revlist as $rowValues) {
            foreach ($rowValues as $key => $rowValue) {
                 $rowValues[$key] = mysql_real_escape_string($rowValues[$key]);
            }

            $values[] = "(" . implode(', ', $rowValues) . ")";
        }
    //GETTING KEYS OF A MULTIDIMENSIONAL ARRAY    
    $keys = array_keys($rowValues);
    
    $set = "primary_reviewer = '1'";
    if($obj->update_multiple("rev_groups", $set, $revlist, $count)){
        header("location:sec_dashboard_active.php#review");
    }
    
    $statusassignreview = array(
        "sub_id" => $id,
        "status_action" => "3",
        "status_username" => $username
    );
    
    if($obj->insert_record("proposal_status", $statusassignreview)){
        header("location:sec_dashboard_active.php#review");
    }
    
    //SAVING CO-REVIEWER
//    if($obj->insert_multiple("rev_groups", $keys, $values)){
//            
//        }
}

if (isset($_POST['assignaddprimary'])) {
    
    $id = $_POST['sub_id'];
    $prev = $_POST['prev'];
    $username = $_POST['userid'];
    
    //GETTING THE DATA IN MULTIDIMENSIONAL ARRAY
    $count = count($prev);
    for ($i = 0; $i < $count; $i++) {
        $revlist[$i] = array(
            "sub_id" => $id,
            "phrepuser_id" => $prev[$i]
        );
    }
    //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    $values = array();
        foreach ($revlist as $rowValues) {
            foreach ($rowValues as $key => $rowValue) {
                 $rowValues[$key] = mysql_real_escape_string($rowValues[$key]);
            }

            $values[] = "(" . implode(', ', $rowValues) . ")";
        }
    //GETTING KEYS OF A MULTIDIMENSIONAL ARRAY    
    $keys = array_keys($rowValues);
    
    $set = "primary_reviewer = '1'";
    if($obj->update_multiple("rev_groups", $set, $revlist, $count)){
        header("location:sec_dashboard_active.php#review");
    }
}

if (isset($_POST['assignfull'])) {
//    echo '<pre>'; var_dump($_POST); echo '</pre>';exit;
    $subid = $_POST['sub_id'];
    $reviewer = $_POST['reviewer'];
    $chairman = $_POST['chairman'];
    $username = $_POST['userid'];
//    $primaryrev = $_POST['prirev'];
        
    //email sa secretariat
    $where = array("id" => $username);
    $getphrepuser = $obj->fetch_record("phrepuser", $where);
    foreach($getphrepuser as $gpu){
        $where = array("memberID" => $gpu['username']);
        $getemailsec = $obj->fetch_record("membership_users", $where);
        foreach($getemailsec as $emsec){
            $emailsec = $emsec['email'];
        }
    }         
    
//    DUE DATE REVIEW_TYPE
    $duedate = date("Y-m-d", strtotime($_POST['duedate']));
    
    //GETTING THE DATA IN MULTIDIMENSIONAL ARRAY
    $count = count($reviewer);
    for ($i = 0; $i < $count; $i++) {
        $r = "prirev-".$reviewer[$i];
        $revlist[$i] = array(
            "sub_id" => $subid,
            "review" => "1",
            "phrepuser_id" => $reviewer[$i],
            "confirmation" => "0",
            "primary_reviewer" => $_POST[$r]
        );
//        echo '<pre>';print_r($revlist); echo '</pre>'; 
        if($obj->insert_record("rev_groups", $revlist[$i])){
                $table = array("review_type", "proposal", "rev_groups", "phrepuser", "membership_users");
                $join_on = array("sub_id", "sub_id", "sub_id", "sub_id", "phrepuser_id", "id", "username", "memberID");
                $where = array("rev_groups.sub_id" => $subid, "phrepuser.id" => $reviewer[$i]);
                $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
                if($getdata){
                    foreach($getdata as $dt){
                        $primaryrev = $dt['primary_reviewer']; if($primaryrev == '1'){$pr = "You are assigned as a <b>Primary Reviewer</b>.";} else{$pr="";}
                        $secemail = $dt['email']; #echo $secemail;
                        $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle'],date('F d, Y',strtotime($duedate)), $pr);
    //                    print_r($change);
                    }  
                }
                $table1 = array("proposal", "submission", "rec_list", "phrepuser");
                $join_on1 = array("sub_id", "sub_id", "reclist_id", "id", "secretary", "id");
                $where1 = array("rec_list.secretary" => $username, "proposal.sub_id" => $subid);
                $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
                if($getdata1){
                    foreach($getdata1 as $dt1){
                        array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
//                        print_r($change); echo "<br>"; exit;
                    }
                }

                $where3 = array("id" => "13");
                $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
                foreach ($getTemplate as $tplate) {
                    $subject = $tplate['subject'];
                    $template = $tplate['body'];
                }

                $find = array("{reviewerName}","{proposalTitle}","{reviewDueDate}","{primaryreviewer}","{secretaryname}","{ercName}"); 
                $readytosend = str_replace($find, $change, $template);

                if($obj->sendEmail3($secemail, $readytosend, $subject, $emailsec)){
    //                 header("location:sec_dashboard_active.php#review");                 
                }
            
            header("location:sec_dashboard_active.php");
        }
        
    }  
    //FOR CHAIRMAN
    $chairmanforfull = array(
        "sub_id" => $subid,
        "review" => "1",
        "phrepuser_id" => $chairman,
        "confirmation" => "0",
        "primary_reviewer" => $_POST['cmprime']
    );
        
    if($obj->insert_record("rev_groups", $chairmanforfull)){
                $table = array("review_type", "proposal", "rev_groups", "phrepuser", "membership_users");
                $join_on = array("sub_id", "sub_id", "sub_id", "sub_id", "phrepuser_id", "id", "username", "memberID");
                $where = array("rev_groups.sub_id" => $subid, "phrepuser.id" => $chairman);
                $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
                if($getdata){
                    foreach($getdata as $dt){
                        $primaryrev = $_POST['cmprime']; if($primaryrev == '1'){$pr = "You are assigned as a <b>Primary Reviewer</b>.";} else{$pr="";}
                        $secemail = $dt['email']; #echo $secemail;
                        $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle'],date('F d, Y',strtotime($duedate)), $pr);
    //                    print_r($change);
                    }  
                }
                $table1 = array("proposal", "submission", "rec_list", "phrepuser");
                $join_on1 = array("sub_id", "sub_id", "reclist_id", "id", "secretary", "id");
                $where1 = array("rec_list.secretary" => $username, "proposal.sub_id" => $subid);
                $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
                if($getdata1){
                    foreach($getdata1 as $dt1){
                        array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
//                        print_r($change); echo "<br>"; exit;
                    }
                }

                $where3 = array("id" => "13");
                $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
                foreach ($getTemplate as $tplate) {
                    $subject = $tplate['subject'];
                    $template = $tplate['body'];
                }

                $find = array("{reviewerName}","{proposalTitle}","{reviewDueDate}","{primaryreviewer}","{secretaryname}","{ercName}"); 
                $readytosend = str_replace($find, $change, $template);

                if($obj->sendEmail3($secemail, $readytosend, $subject, $emailsec)){
    //                 header("location:sec_dashboard_active.php#review");                 
                }    
        header("location:sec_dashboard_active.php");
    }  
//    exit;
    
    $statusassignreview = array(
        "sub_id" => $subid,
        "status_action" => "3",
        "status_username" => $username
    );    
    if($obj->insert_record("proposal_status", $statusassignreview)){
        header("location:sec_dashboard_active.php");
    }    
    
}

if (isset($_POST['assignfull'])) {
    $id = $_POST['sub_id'];
    $duedate = date("Y-m-d", strtotime($_POST['duedate']));
    $username = $_POST['userid'];
    $note = $_POST['notetocoreviewer'];
    
    
    $fields = array(
        "subid" => $id,
        "rt_duedate" => $duedate
    ); 
    if($obj->insert_record("review_type_duedate", $fields)){
        header("location:sec_dashboard_active.php");
    }
    
    $notetocorev = array(
        "sub_id" => $id,
        "sender" => $username,
        "message" => $note
    );
    if($obj->insert_record("note", $notetocorev)){
        header("location:sec_dashboard_active.php");
    }
    
}
if (isset($_POST['incomplete'])) {
    
//    echo '<pre>';var_dump($_POST); echo'</pre>';exit;
    
    $subid = $_POST['sub_id'];
    $username = $_POST['userid'];
    $incmsg = strip_tags($_POST['incompletemsg'],'<ol><ul><li>');
        
    $statusassignreview = array(
        "sub_id" => $subid,
        "status_action" => "2",
        "status_username" => $username
    );  
    
    if($obj->insert_record("proposal_status", $statusassignreview)){
        header("location:sec_dashboard_active.php");
    }     
        
    $gettimes = $obj->gettimesmsg($subid, "inc"); 
    
    $incomplete = array(
        "subid" => $subid,
        "notefor" => "inc",
        "times" => $gettimes+1,
        "message" => $incmsg
    );
    if($obj->insert_record("message", $incomplete)){
        header("location:sec_dashboard_active.php");
    } 
    
}
if(isset($_POST['incomplete'])){
    $userid = $_POST['userid'];
    $subid = $_POST['submid']; 
    $incmsg = strip_tags($_POST['incompletemsg'],'<ol><ul><li>');
    
        
    //email sa secretariat
    $where = array("id" => $userid);
    $getphrepuser = $obj->fetch_record("phrepuser", $where);
    foreach($getphrepuser as $gpu){
        $where = array("memberID" => $gpu['username']);
        $getemailsec = $obj->fetch_record("membership_users", $where);
        foreach($getemailsec as $emsec){
            $emailsec = $emsec['email'];
        }
    }         
        
    
    $table = array("proposal", "phrepuser", "membership_users");
    $join_on = array("username", "id", "username", "memberID");
    $where = array("proposal.sub_id" => $subid);
    $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
    if($getdata){
        foreach($getdata as $dt){
            $secemail = $dt['email'];
            $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
            print_r($change);
        }        
    }
    
    $table1 = array("phrepuser", "rec_list");
    $join_on1 = array("id", "secretary");
    $where1 = array("phrepuser.id" => $userid);
    $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
    if($getdata1){
        foreach($getdata1 as $dt1){
            array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name'], $incmsg);
            print_r($change);
        }
    }
    $where3 = array("id" => "12");
    $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
    foreach ($getTemplate as $tplate) {
        $subject = $tplate['subject'];
        $template = $tplate['body'];
    }

    $find = array("{researchername}","{proposaltitle}","{secretaryname}","{ercname}", "{incmsg}"); 
    $readytosend = str_replace($find, $change, $template);
    
    if($obj->sendEmail3($secemail, $readytosend, $subject, $emailsec)){
         header("location:sec_dashboard_active.php");} 
}



if (isset($_POST['incompletepa'])) {
    
//    echo '<pre>';var_dump($_POST); echo'</pre>';exit;
    
    $subid = $_POST['sub_id'];
    $username = $_POST['userid'];
    $incmsg = strip_tags($_POST['incompletemsg'],'<ol><ul><li>');
        
    $statusassignreview = array(
        "sub_id" => $subid,
        "status_action" => "24",
        "status_username" => $username
    );  
    
    if($obj->insert_record("proposal_status", $statusassignreview)){
        header("location:sec_dashboard_active.php#postapproval");
    }     
    
    $ppaid = $obj->getmaxpropapp($subid);
    $gettimes = $obj->gettimesmsgpa($subid, $ppaid); 
    
    $incomplete = array(
        "subid" => $subid,
        "ppaid" => $ppaid,
        "notefor" => "incpa",
        "times" => $gettimes+1,
        "message" => $incmsg,
        "date" => $datetime
    );
        
    if($obj->insert_record("messagepa", $incomplete)){
        header("location:sec_dashboard_active.php#postapproval");
    }
    
    $where = array(
        "sub_id" => $subid,
        "post_approval_type" => $_POST['ppaids'],
        "revision" => $_POST['revision']
    );
//    print_r($where);exit;
    $change = array(
        "newsubmit" => "0"
    );
    if($obj->update_record("document_postapproval", $where, $change)){
        header("location:sec_dashboard_active.php#postapproval");
    }
    
}
if(isset($_POST['incompletepa'])){
    $userid = $_POST['userid'];
    $subid = $_POST['submid']; 
    $incmsg = strip_tags($_POST['incompletemsg'],'<ol><ul><li>');
    
        
    //email sa secretariat
    $where = array("id" => $userid);
    $getphrepuser = $obj->fetch_record("phrepuser", $where);
    foreach($getphrepuser as $gpu){
        $where = array("memberID" => $gpu['username']);
        $getemailsec = $obj->fetch_record("membership_users", $where);
        foreach($getemailsec as $emsec){
            $emailsec = $emsec['email'];
        }
    }         
        
    
    $table = array("proposal", "phrepuser", "membership_users");
    $join_on = array("username", "id", "username", "memberID");
    $where = array("proposal.sub_id" => $subid);
    $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
    if($getdata){
        foreach($getdata as $dt){
            $secemail = $dt['email'];
            $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
            print_r($change);
        }        
    }
    
    $table1 = array("phrepuser", "rec_list");
    $join_on1 = array("id", "secretary");
    $where1 = array("phrepuser.id" => $userid);
    $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
    if($getdata1){
        foreach($getdata1 as $dt1){
            array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name'], $incmsg);
            print_r($change);
        }
    }
    $where3 = array("id" => "12");
    $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
    foreach ($getTemplate as $tplate) {
        $subject = $tplate['subject'];
        $template = $tplate['body'];
    }

    $find = array("{researchername}","{proposaltitle}","{secretaryname}","{ercname}", "{incmsg}"); 
    $readytosend = str_replace($find, $change, $template);
    
    if($obj->sendEmail3($secemail, $readytosend, $subject, $emailsec)){
         header("location:sec_dashboard_active.php#postapproval");} 
}

if (isset($_POST['saveForm'])) {
    
    $formid = $_POST['formid'];
    $qval = $_POST['qval'];
    
    $count = count($qval);
    for ($i = 0; $i < $count; $i++) {
        $qlist[$i] = array(
            "form_id" => $formid,
            "question_desc" => $qval[$i]
        );
    }
    
    //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    $values = array();
        foreach ($qlist as $rowValues) {
            foreach ($rowValues as $key => $rowValue) {
                 $rowValues[$key] = mysql_real_escape_string($rowValues[$key]);
            }

            $values[] = "(" . implode(', ', $rowValues) . ")";
        }
        
    //GETTING KEYS OF A MULTIDIMENSIONAL ARRAY    
    $keys = array_keys($rowValues);
    //SAVING CO-REVIEWER
    
    
    if($obj->insert_multiple("rec_question_eval", $keys, $values)){
//            header("location:sec_evaluation_edit.php");
        }
        
    
}
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
            
            $uploadinfor = array(
            "file_id" => "",
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
            "path" => "uploads/main/".$file_name
            );  
            print_r($uploadinfor);
            if($obj->upload_info("document", $uploadinfor, $id, $doctype, $useurl, $times)){
           }
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document", $whereup, $updatenewsubmit)){
               
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
   
   if(isset($_FILES['imagepa'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['imagepa']['name'];
      $file_size = $_FILES['imagepa']['size'];
      $file_tmp = $_FILES['imagepa']['tmp_name'];
      $file_type = $_FILES['imagepa']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['imagepa']['name'])));
      
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
            
            $uploadinfor = array(
            "file_id" => "",
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
            print_r($uploadinfor);
            if($obj->upload_info("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times)){
           }
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
               
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
   
   if(isset($_FILES['amendmentletter'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['amendmentletter']['name'];
      $file_size = $_FILES['amendmentletter']['size'];
      $file_tmp = $_FILES['amendmentletter']['tmp_name'];
      $file_type = $_FILES['amendmentletter']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['amendmentletter']['name'])));
      
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
            
            $uploadinfor = array(
            "file_id" => "",
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
            print_r($uploadinfor);
            if($obj->upload_info("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times)){
           }
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
               
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
   
   if(isset($_FILES['progressreportletter'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['progressreportletter']['name'];
      $file_size = $_FILES['progressreportletter']['size'];
      $file_tmp = $_FILES['progressreportletter']['tmp_name'];
      $file_type = $_FILES['progressreportletter']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['progressreportletter']['name'])));
      
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
            
            $uploadinfor = array(
            "file_id" => "",
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
            print_r($uploadinfor);
            if($obj->upload_info("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times)){
           }
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
               
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
   
  
   if(isset($_FILES['finalreportletter'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['finalreportletter']['name'];
      $file_size = $_FILES['finalreportletter']['size'];
      $file_tmp = $_FILES['finalreportletter']['tmp_name'];
      $file_type = $_FILES['finalreportletter']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['finalreportletter']['name'])));
      
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
            
            $uploadinfor = array(
            "file_id" => "",
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
            print_r($uploadinfor);
            if($obj->upload_info("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times)){
           }
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
               
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
   
   if(isset($_FILES['imageicc'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['imageicc']['name'];
      $file_size = $_FILES['imageicc']['size'];
      $file_tmp = $_FILES['imageicc']['tmp_name'];
      $file_type = $_FILES['imageicc']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['imageicc']['name'])));
      
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
                "status_action" => "15", 
                "status_date" => $datetime,
                "status_username" => $_POST['username']);
            if($inserticc = $obj->insert_record("proposal_status", $wherec)){
                
            }
            
            $whereig = array(
                "sub_id" => $id, 
                "sent" => "1", 
                "submit" => "0",
                "datetime" => $datetime);
            if($insertindistat = $obj->insert_record("indigenous", $whereig)){
                
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
   
   if(isset($_FILES['sendrl'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['sendrl']['name'];
      $file_size = $_FILES['sendrl']['size'];
      $file_tmp = $_FILES['sendrl']['tmp_name'];
      $file_type = $_FILES['sendrl']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['sendrl']['name'])));
      
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
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = $_POST['docname'];
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = $_POST['revision'] + 1;
            $rename_filename = $id."-".$date."-".$times."-$resdoctype.".end($temp);
            
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){ //RENAMED THE RENAME_FILENAME
            if(move_uploaded_file($file_tmp,"uploads/main/".$file_name)){
            
            $doctypefinal = $resdoctype['0'];
            
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
            "path" => "uploads/main/".$file_name
            );  
//            echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
            
            if($obj->upload_info("document", $uploadinfor, $id, $doctype, $useurl, $times)){
           }
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document", $whereup, $updatenewsubmit)){
               
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
   
   
   if(isset($_FILES['disapproval'])){
      $errors= array();      
      $id = $_POST['submid'];
      
      
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document", $whereup, $updatenewsubmit)){
               
            }
      
      
      
      $file_name = $_FILES['disapproval']['name'];
      $file_size = $_FILES['disapproval']['size'];
      $file_tmp = $_FILES['disapproval']['tmp_name'];
      $file_type = $_FILES['disapproval']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['disapproval']['name'])));
      
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
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = $_POST['docname'];
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
            $times = $_POST['revision'] + 1;
            $rename_filename = $id."-".$date."-".$times."-$resdoctype.".end($temp);
            
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){ //RENAMED THE RENAME_FILENAME
            if(move_uploaded_file($file_tmp,"uploads/main/".$file_name)){
            
            $doctypefinal = $resdoctype['0'];
            
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
            "newsubmit" => "1",
            "username" => $_POST['userid'],
            "path" => "uploads/main/".$file_name
            );  
//            echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
            
            if($obj->upload_info("document", $uploadinfor, $id, $doctype, $useurl, $times)){
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
   
      
   if(isset($_FILES['sendrlpa'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['sendrlpa']['name'];
      $file_size = $_FILES['sendrlpa']['size'];
      $file_tmp = $_FILES['sendrlpa']['tmp_name'];
      $file_type = $_FILES['sendrlpa']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['sendrlpa']['name'])));
      
      $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $postapprovaltype = $obj->getmaxpropapp($id);
          
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
            
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){ //RENAMED THE RENAME_FILENAME
            if(move_uploaded_file($file_tmp,"uploads/main/".$file_name)){
            
            $doctypefinal = $resdoctype['0'];
            
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
            "newsubmit" => "1",
            "post_approval_type" => $postapprovaltype
            );  
//            echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
            
            if($obj->upload_infopa("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times, $postapprovaltype)){
            }
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
               
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
   

      
   if(isset($_FILES['sendvisitpa'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['sendvisitpa']['name'];
      $file_size = $_FILES['sendvisitpa']['size'];
      $file_tmp = $_FILES['sendvisitpa']['tmp_name'];
      $file_type = $_FILES['sendvisitpa']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['sendvisitpa']['name'])));
      
      $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $postapprovaltype = $obj->getmaxpropapp($id);
          
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
            
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){ //RENAMED THE RENAME_FILENAME
            if(move_uploaded_file($file_tmp,"uploads/main/".$file_name)){
            
            $doctypefinal = $resdoctype['0'];
            
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
            "newsubmit" => "1",
            "post_approval_type" => $postapprovaltype
            );  
//            echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
            
            if($obj->upload_infopa("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times, $postapprovaltype)){
            }
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
               
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
   
   if(isset($_FILES['imagepostece'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['imagepostece']['name'];
      $file_size = $_FILES['imagepostece']['size'];
      $file_tmp = $_FILES['imagepostece']['tmp_name'];
      $file_type = $_FILES['imagepostece']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['imagepostece']['name'])));
      
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
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            //move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $doctypefinal = $resdoctype['0'];
//            print_r($doctypefinal);
            
            $uploadinfor = array(
            "file_id" => "",
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
            "path" => "uploads/main/".$rename_filename
            );  
            print_r($uploadinfor);
            if($obj->upload_info("document", $uploadinfor, $id, $doctype, $useurl, $times)){
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
   
   if(isset($_FILES['imagepost'])){
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['imagepost']['name'];
      $file_size = $_FILES['imagepost']['size'];
      $file_tmp = $_FILES['imagepost']['tmp_name'];
      $file_type = $_FILES['imagepost']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['imagepost']['name'])));
      
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
            $times = $_POST['maxrl'] + 1;
            $rename_filename = $id."-".$date."-".$times."-$resdoctype.".end($temp);
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            //move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $doctypefinal = $resdoctype['0'];
//            print_r($doctypefinal);
            
            $uploadinfor = array(
            "file_id" => "",
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
            "path" => "uploads/main/".$rename_filename
            );  
            print_r($uploadinfor);
            if($obj->upload_info("document", $uploadinfor, $id, $doctype, $useurl, $times)){
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

   
   if(isset($_FILES['imagepostpr'])){ //progress report
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['imagepostpr']['name'];
      $file_size = $_FILES['imagepostpr']['size'];
      $file_tmp = $_FILES['imagepostpr']['tmp_name'];
      $file_type = $_FILES['imagepostpr']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['imagepostpr']['name'])));
      
      $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $postapprovaltype = $obj->getmaxpropapp($id);
          
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
            
            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){
            
            //move_uploaded_file($file_tmp,"uploads/main/".$file_name);
            $doctypefinal = $resdoctype['0'];
//            print_r($doctypefinal);
            
            $uploadinfor = array(
            "file_id" => "",
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
            "path" => "uploads/main/".$rename_filename,
            "post_approval_type" => $postapprovaltype
                
            );  
            print_r($uploadinfor);
            if($obj->upload_infopa("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times, $postapprovaltype)){
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
   
   if (isset($_POST['clearance'])) {
      if((isset($_POST['stclearance']) == null)&&(isset($_POST['endclearance']) == null)){
          
      }
      else{
       $id = $_POST['submid'];
       $ecstart = date("Y-m-d", strtotime($_POST['stclearance']));
       $ecend = date("Y-m-d", strtotime($_POST['endclearance']));
       
       $clearance = array(
            "sub_id" => $id,
            "ec_start" => $ecstart,
            "ec_end" => $ecend,
            "date" => $datetime
       );
       
        if($obj->insert_record("ethical_clearance", $clearance)){
            header("location:sec_approve.php?id=".$id);
        }
      }   
   }
   
   if (isset($_POST['clearancepa'])) {
      if((isset($_POST['stclearance']) == null)&&(isset($_POST['endclearance']) == null)){
          
      }
      else{
       $id = $_POST['submid'];
       $ecstart = date("Y-m-d", strtotime($_POST['stclearance']));
       $ecend = date("Y-m-d", strtotime($_POST['endclearance']));
       
       $clearance = array(
            "sub_id" => $id,
            "ec_start" => $ecstart,
            "ec_end" => $ecend,
            "date" => $datetime
       );
       
        if($obj->insert_record("ethical_clearance", $clearance)){
            header("location:sec_approvepa.php?id=".$id);
        }
      }   
   }   
   
   
    if (isset($_POST['clearancepostece'])) {
      if((isset($_POST['stclearance']) == null)&&(isset($_POST['endclearance']) == null)){
          
      }
      else{
       $id = $_POST['submid'];
       $ecstart = date("Y-m-d", strtotime($_POST['stclearance']));
       $ecend = date("Y-m-d", strtotime($_POST['endclearance']));
       
       $clearance = array(
            "sub_id" => $id,
            "ec_start" => $ecstart,
            "ec_end" => $ecend,
            "date" => $datetime
       );
       
        if($obj->insert_record("ethical_clearance", $clearance)){
            header("location:sec_approve_post.php?id=".$id);
        }
      }   
   }
   
  if (isset($_GET['delc'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])) {
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("ec_id" => $id, "sub_id" => $subid);
                if ($obj->delete_record("ethical_clearance", $where)) {
                    header("location:sec_approve.php?id=".$subid);
                }
        }
    }
}

  if (isset($_GET['delcpost'])) {
    if (isset($_GET['id'])) {
        if (isset($_GET['subid'])) {
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("ec_id" => $id, "sub_id" => $subid);
                if ($obj->delete_record("ethical_clearance", $where)) {
                    header("location:sec_approve_post.php?id=".$subid);
                }
        }
    }
}

if (isset($_POST['reviseprop'])) {
//    echo "<pre>";var_dump($_POST);echo "<pre>"; exit;
    
    $subid = $_POST['submid'];
    $revise = $_POST['revise'];
//    $ddate = date('Y-m-d',strtotime($_POST['newddate']));
    
    
    
    $count = count($revise);
    for ($i = 0; $i < $count; $i++) {
        $revdoc[$i] = array(
            "sub_id" => $subid,
            "file_id" => $revise[$i]
        );
    }
    
    //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    $values = array();
        foreach ($revdoc as $rowValues) {
            foreach ($rowValues as $key => $rowValue) {
                 $rowValues[$key] = mysqli_real_escape_string($rowValues[$key]);
            }

            $values[] = "(" . implode(', ', $rowValues) . ")";
        }
    
    $keys = array_keys($rowValues);
    $set = "finaldoc = '0'";
    if($obj->update_multiple("document", $set, $revdoc, $count)){
        header("location:sec_revise.php?id=".$subid);
    }
    
    
    
    $revise = array(
            "sub_id" => $subid,
            "status_action" => "5",
            "status_date" => $datetime,
            "status_username" => $_POST['userid']
       );
       
    if($obj->insert_record("proposal_status", $revise)){
        header("location:sec_revise.php?id=".$subid);
    }
    
    //COLLATED SUGGESTIONS
    
    $collated = array(
      "sub_id" => $subid,
      "rev" => $_POST['rev'],
      "collated_desc" => $_POST['collatedsugg']
    );
    if($obj->insert_record("collated_suggestion", $collated)){
        header("location:sec_revise.php?id=".$subid);
    }
    
    
}

if (isset($_POST['reviseproppa'])) {
//    echo "<pre>";var_dump($_POST);echo "<pre>"; exit;
    
    $subid = $_POST['submid'];
    $revise = $_POST['revise'];
//    $ddate = date('Y-m-d',strtotime($_POST['newddate']));
    
    
    
    $count = count($revise);
    for ($i = 0; $i < $count; $i++) {
        $revdoc[$i] = array(
            "sub_id" => $subid,
            "file_id" => $revise[$i]
        );
    }
    
    //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    $values = array();
        foreach ($revdoc as $rowValues) {
            foreach ($rowValues as $key => $rowValue) {
                 $rowValues[$key] = mysqli_real_escape_string($rowValues[$key]);
            }

            $values[] = "(" . implode(', ', $rowValues) . ")";
        }
    
    $keys = array_keys($rowValues);
    $set = "finaldoc = '0'";
    if($obj->update_multiple("document_postapproval", $set, $revdoc, $count)){
        header("location:sec_revisepa.php?id=".$subid);
    }
    
    
    
    $revise = array(
            "sub_id" => $subid,
            "status_action" => "18",
            "status_date" => $datetime,
            "status_username" => $_POST['userid']
       );
       
    if($obj->insert_record("proposal_status", $revise)){
        header("location:sec_revisepa.php?id=".$subid);
    }
    
    //COLLATED SUGGESTIONS
    
    $collated = array(
      "sub_id" => $subid,
      "rev" => $_POST['rev'],
      "collated_desc" => $_POST['collatedsugg'],
      "post_approval" => "1",
      "pid" => $_POST['pid']
    );
    if($obj->insert_record("collated_suggestionpa", $collated)){
        header("location:sec_revisepa.php?id=".$subid);
    }
    
    $where = array(
        "subid" => $subid,
        "ppaid" => $_POST['pid']
    );
    $fields = array("final" => "0");
    if($obj->update_record("sitevisit_decision", $where, $fields)){
        header("location:sec_revisepa.php?id=".$subid);
    }
    
    $where1 = array(
        "sub_id" => $subid,
        "post_approval_type" => $_POST['pid']
    );
    $fields1 = array("finaldoc" => "0");
    if($obj->update_record("document_sitevisit", $where1, $fields1)){
        header("location:sec_revisepa.php?id=".$subid);
    }
    
    
}

if (isset($_POST['sitevisit'])) {
//    echo "<pre>";var_dump($_POST);echo "<pre>"; exit;
    
    $subid = $_POST['submid'];
//    $revise = $_POST['revise'];
    $pid = $_POST['pid'];
    $stclearance = date('Y-m-d',strtotime($_POST['stclearance']));
    $endclearance = date('Y-m-d',strtotime($_POST['endclearance']));
    
    
    $times_sitevisit = $obj->maxrepsitevisit($subid, $pid);
    
    
    $sitevisit = array(
        "subid" => $subid,
        "repeatition" => $times_sitevisit + 1,
        "post_approval_type" => $pid,
        "startdate" => $stclearance,
        "enddate" => $endclearance
    );
    
    if($obj->insert_record("sitevisit", $sitevisit)){
        header("location:sec_dashboard_active.php#postapproval");
        
    }
    
    $revise = array(
            "sub_id" => $subid,
            "status_action" => "27",
            "status_date" => $datetime,
            "status_username" => $_POST['userid']
       );
       
    if($obj->insert_record("proposal_status", $revise)){
        header("location:sec_dashboard_active.php#postapproval");
    }
    
    //COLLATED SUGGESTIONS
    
    $collated = array(
      "sub_id" => $subid,
      "rev" => $_POST['rev'],
      "collated_desc" => $_POST['collatedsugg'],
      "post_approval" => "1",
      "pid" => $pid
    );
    if($obj->insert_record("collated_suggestionpa", $collated)){
        header("location:sec_dashboard_active.php#postapproval");
    }
    
}

if (isset($_POST['reviseprop'])) {
    
//    echo '<pre>';var_dump($_POST);echo '</pre>';exit;
    //FOR EMAIL
    $pathec = $_POST['ecpath'];
    $userid = $_POST['userid'];
    $subid = $_POST['submid']; 
    
    $table = array("proposal", "phrepuser", "membership_users");
    $join_on = array("username", "id", "username", "memberID");
    $where = array("proposal.sub_id" => $subid);
    $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
    if($getdata){
        foreach($getdata as $dt){
            $secemail = $dt['email'];
            $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
//            print_r($change); exit;
        }        
    }
    
    $table1 = array("phrepuser", "rec_list");
    $join_on1 = array("id", "secretary");
    $where1 = array("phrepuser.id" => $userid);
    $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
    if($getdata1){
        foreach($getdata1 as $dt1){
            array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
//            print_r($change);
        }
    }
    $where3 = array("id" => "16");
    $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
    foreach ($getTemplate as $tplate) {
        $subject = $tplate['subject'];
        $template = $tplate['body'];
    }

    $find = array("{researchername}","{proposaltitle}","{secretaryname}","{ercname}"); 
    $readytosend = str_replace($find, $change, $template);
    
    if($obj->sendEmail3att($secemail, $readytosend, $subject, $pathec)){
         header("location:sec_dashboard_active.php#review");}    
    
//    exit;
    
    
}

///REVISE_POST_APPROVAL
if (isset($_POST['reviseproppa'])) {
    
    //    echo '<pre>';var_dump($_POST);echo '</pre>';exit;
        //FOR EMAIL
        $pathec = $_POST['ecpath'];
        $userid = $_POST['userid'];
        $subid = $_POST['submid']; 
        
        $table = array("proposal", "phrepuser", "membership_users");
        $join_on = array("username", "id", "username", "memberID");
        $where = array("proposal.sub_id" => $subid);
        $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
        if($getdata){
            foreach($getdata as $dt){
                $secemail = $dt['email'];
                $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
    //            print_r($change); exit;
            }        
        }
        
        $table1 = array("phrepuser", "rec_list");
        $join_on1 = array("id", "secretary");
        $where1 = array("phrepuser.id" => $userid);
        $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
        if($getdata1){
            foreach($getdata1 as $dt1){
                array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
    //            print_r($change);
            }
        }
        $where3 = array("id" => "16");
        $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
        foreach ($getTemplate as $tplate) {
            $subject = $tplate['subject'];
            $template = $tplate['body'];
        }
    
        $find = array("{researchername}","{proposaltitle}","{secretaryname}","{ercname}"); 
        $readytosend = str_replace($find, $change, $template);
        

        if($obj->sendEmail3att($secemail, $readytosend, $subject, $pathec)){
             header("location:sec_dashboard_active.php#review");}    
        
    //    exit;
        
        
    }

if (isset($_POST['reviseproppost'])) {
//    echo "<pre>";var_dump($_POST);echo "<pre>"; exit;
    
    $subid = $_POST['submid'];
    $revise = $_POST['revise'];
    $ddate = date('Y-m-d',strtotime($_POST['newddate']));
    
    //additional due date
//    $newddate = array("rt_duedate" => $ddate, "subid" => $subid);
//    if($obj->insert_record("review_type_duedate", $newddate)){
//        
//    }
    //additional due date
    
    
    $count = count($revise);
    for ($i = 0; $i < $count; $i++) {
        $revdoc[$i] = array(
            "sub_id" => $subid,
            "file_id" => $revise[$i]
        );
    }
    
    //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    $values = array();
        foreach ($revdoc as $rowValues) {
            foreach ($rowValues as $key => $rowValue) {
                 $rowValues[$key] = mysql_real_escape_string($rowValues[$key]);
            }

            $values[] = "(" . implode(', ', $rowValues) . ")";
        }
    
    $keys = array_keys($rowValues);
    
    $set = "finaldoc = '0'";
    if($obj->update_multiple("document_postapproval", $set, $revdoc, $count)){
        header("location:sec_dashboard_active.php#resubmitted");
    }
    
    
    $ppaid = $obj->getmaxpropapp($subid);
    
    $where = array("sub_id" => $subid, "post_approval_type" => $ppaid);
    $sets = array("newsubmit" => "0");
    if($obj->update_record("document_postapproval", $where, $sets)){        
    }
    
    
    
    $revise = array(
            "sub_id" => $subid,
            "status_action" => "18",
            "status_date" => $datetime,
            "status_username" => $_POST['userid']
       );
       
    if($obj->insert_record("proposal_status", $revise)){
        header("location:sec_dashboard_active.php#postapproval");
    }
    
    
//    //SAVE REVIEWERS FROM REVISE AND RESUBMIT
//    $maxrev = $obj->getmaxreviewer($_POST['submid']);
//    $getreviewers = $obj->getMemberReviewer($_POST['submid'], $maxrev);
//    foreach ($getreviewers as $reviwer) {
//        
//        $phrepid = $reviwer['phrepuser_id'];
//        $review = $reviwer['review']+1;
//        $primerev =  $reviwer['primary_reviewer'];
//        
//        if(($reviwer['type_id'] == '1')&&($reviwer['eval_date'] == '0')){           
//            $revset = array(
//                "sub_id" => $_POST['submid'],
//                "review" => $review,
//                "phrepuser_id" => $phrepid,
//                "confirmation" => "1",
//                "primary_reviewer" => $primerev,
//                "evaluation_submitted" => "1"
//            );
//            if($obj->insert_record("rev_groups", $revset)){
//            }
//            
//        }
//        else{            
//            $revset = array(
//                "sub_id" => $_POST['submid'],
//                "review" => $review,
//                "phrepuser_id" => $phrepid,
//                "confirmation" => "1",
//                "primary_reviewer" => $primerev
//            );
//            if($obj->insert_record("rev_groups", $revset)){
//            }
//        }
//        
//        
//    }  
}

if (isset($_POST['approveprop'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "6",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $approved)){
        header("location:sec_dashboard_active.php");
    }
    
    
}

if (isset($_POST['approvepropec'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "6",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $approved)){
        
        header("location:sec_dashboard_active.php#approved");
    }
    
    $whereec = array(
        "sub_id" => $subid,
        "doctype" => "15" //edited from 18
    );
    $updateec = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document", $whereec, $updateec)){
    } 
}


if (isset($_POST['disapprovedreview'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "25",
        "status_date" => $datetime,
        "status_username" => $userid
    );
        
    if($obj->insert_record("proposal_status", $approved)){
        header("location:sec_dashboard_active.php#approved");
    }
     
}


if (isset($_POST['approvepropecpost'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "6",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $approved)){
    }
    
    $whereec = array(
        "sub_id" => $subid,
        "post_request_times" => $_POST['maxrq']
    );
    $updateec = array(
        "post_finaldoc" => "0"
    );
    if($obj->update_record("document", $whereec, $updateec)){
    }  
    
    $wherestat = array(
        "subid" => $subid
    );
    $updatestat = array(
        "pa_status" => "done"
    );
    if($obj->update_record("proposal_post_approval", $wherestat, $updatestat)){
        
        header("location:sec_dashboard_active.php");
    }
}
if (isset($_POST['approvepropec'])) {
//    echo '<pre>';var_dump($_POST);echo '</pre>';exit;
    //FOR EMAIL
    $pathec = $_POST['ecpath'];
    $userid = $_POST['userid'];
    $subid = $_POST['submid']; 
    
    $table = array("proposal", "phrepuser", "membership_users");
    $join_on = array("username", "id", "username", "memberID");
    $where = array("proposal.sub_id" => $subid);
    $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
    if($getdata){
        foreach($getdata as $dt){
            $secemail = $dt['email'];
            $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
            print_r($change);
        }        
    }
    
    $table1 = array("phrepuser", "rec_list");
    $join_on1 = array("id", "secretary");
    $where1 = array("phrepuser.id" => $userid);
    $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
    if($getdata1){
        foreach($getdata1 as $dt1){
            array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
            print_r($change);
        }
    }
    $where3 = array("id" => "11");
    $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
    foreach ($getTemplate as $tplate) {
        $subject = $tplate['subject'];
        $template = $tplate['body'];
    }

    $find = array("{researchername}","{proposaltitle}","{secretaryname}","{ercname}"); 
    $readytosend = str_replace($find, $change, $template);
    
    if($obj->sendEmail3att($secemail, $readytosend, $subject, $pathec)){
         header("location:sec_dashboard_active.php#approved");}    
    
//    exit;
    
}

if (isset($_POST['approvepropecpa'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "6",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $approved)){
        
    }
    
    $whereec = array(
        "sub_id" => $subid,
        "doctype" => "21" //edited from 18
    );
    $updateec = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document_postapproval", $whereec, $updateec)){        
    }   
    
    $where2 = array(
        "pid" => $_POST['ppaid']
    );
    
    $update2 = array(
        "pa_status" => "done",
        "pa_datedone" => $datetime
    );
    
    if($obj->update_record("proposal_post_approval", $where2, $update2)){   
        header("location:sec_dashboard_active.php#approved");
    } 
    
}

if (isset($_POST['approvepropampa'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "6",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $approved)){
        
    }
    
    $whereec = array(
        "sub_id" => $subid,
        "doctype" => "17" //edited from 18
    );
    $updateec = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document_postapproval", $whereec, $updateec)){        
    }   
    
    $where2 = array(
        "pid" => $_POST['ppaid']
    );
    
    $update2 = array(
        "pa_status" => "done",
        "pa_datedone" => $datetime
    );
    
    if($obj->update_record("proposal_post_approval", $where2, $update2)){   
        header("location:sec_dashboard_active.php#postapproval");
    } 
    
}


if (isset($_POST['approvepropprpa'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "6",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $approved)){
        
    }
    
    $whereec = array(
        "sub_id" => $subid,
        "doctype" => "28" //edited from 18
    );
    $updateec = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document_postapproval", $whereec, $updateec)){        
    }   
    
    $where2 = array(
        "pid" => $_POST['ppaid']
    );
    
    $update2 = array(
        "pa_status" => "done",
        "pa_datedone" => $datetime
    );
    
    if($obj->update_record("proposal_post_approval", $where2, $update2)){   
        header("location:sec_dashboard_active.php#approved");
    } 
    
}


if (isset($_POST['approvepropfrpa'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "23",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $approved)){
        
    }
    
    $whereec = array(
        "sub_id" => $subid,
        "doctype" => "31"
    );
    $updateec = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document_postapproval", $whereec, $updateec)){        
    }   
    
    $where2 = array(
        "pid" => $_POST['ppaid']
    );
    
    $update2 = array(
        "pa_status" => "done",
        "pa_datedone" => $datetime
    );
    
    if($obj->update_record("proposal_post_approval", $where2, $update2)){   
        header("location:sec_dashboard_active.php#postapproval");
    } 
    
}

if (isset($_POST['exempt'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $approved = array(
        "sub_id" => $subid,
        "status_action" => "12",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $approved)){
        header("location:sec_dashboard_active.php");
    }
    
    $whereec = array(
        "sub_id" => $subid,
        "doctype" => "15"
    );
    $updateec = array(
        "finaldoc" => "0"
    );
    if($obj->update_record("document", $whereec, $updateec)){
        
    } 
    
    $revtypewhere = array("sub_id" => $subid, "rt_username" => $userid);
    $reviewtype = array("rt_id" => "2");
    if($obj->update_record("review_type", $revtypewhere, $reviewtype)){        
    }
}
if (isset($_POST['exempt'])) {
    $msg = $_POST['exempt_msg'];
    $subid = $_POST['submid'];
    // print_r($msg);exit;

    $exempt_msg = array(
        "sub_id" => $subid,
        "msg" => $msg
    );
    if($obj->insert_record("rev_exempt_suggest", $exempt_msg)){
        header("location:sec_dashboard_active.php");
    }
    

}

if (isset($_POST['approveprop'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    $emailto = $_POST['email'];
    $ecpath = $_POST['ecpath'];
    $nameTo = $_POST['oname'];
    $subject = $_POST['subject'];
    $secEmail = $_POST['secemail'];
    $secName = $_POST['secname'];
    
    
    if($obj->sendEmail($emailto, $ecpath, $nameTo, $subject, $secEmail)){
//        header("location:sec_dashboard_active.php");
    }
}

if (isset($_POST['approveprop'])) {
    
    $subid = $_POST['submid'];
    $docid = $_POST['docid'];
    
    $count = count($docid);
    for ($i = 0; $i < $count; $i++) {
        $revdoc[$i] = array(
            "sub_id" => $subid,
            "file_id" => $docid[$i]
        );
    }
    
    //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    $values = array();
        foreach ($revdoc as $rowValues) {
            foreach ($rowValues as $key => $rowValue) {
                 $rowValues[$key] = mysql_real_escape_string($rowValues[$key]);
            }

            $values[] = "(" . implode(', ', $rowValues) . ")";
        }
    
    $keys = array_keys($rowValues);
    
    $set = "finalamend = '0'";
    if($obj->update_multiple("document", $set, $revdoc, $count)){
        header("location:sec_dashboard_active.php");
    }
}

if (isset($_POST['approveprop'])) {
    
    $subid = $_POST['submid'];
    $docid = $_POST['arf'];
    
    $count = count($docid);
    for ($i = 0; $i < $count; $i++) {
        $revdoc[$i] = array(
            "sub_id" => $subid,
            "file_id" => $docid[$i]
        );
    }
    
    //GETTING THE VALUES IN A MULTIDIMENSIONAL ARRAY
    $values = array();
        foreach ($revdoc as $rowValues) {
            foreach ($rowValues as $key => $rowValue) {
                 $rowValues[$key] = mysql_real_escape_string($rowValues[$key]);
            }

            $values[] = "(" . implode(', ', $rowValues) . ")";
        }
    
    $keys = array_keys($rowValues);
    
    $set = "finaldoc = '0'";
    if($obj->update_multiple("document", $set, $revdoc, $count)){
        header("location:sec_dashboard_active.php");
    }
}

if (isset($_GET['delete'])) {
    if (isset($_GET['id'])) {
        if(isset($_GET['subid'])){
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id" => $id, "sub_id" => $subid);
            if ($obj->delete_record("document", $where)) {
                header("location:sec_decline.php?id=$subid");
            }            
        }
    }
} 

if (isset($_GET['deletertype'])) {
    if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $where = array("sub_id" => $id);
            if ($obj->delete_record("review_type", $where)) {
                header("location:sec_dashboard_active.php#review");
            }    
    }
}

if (isset($_GET['deletert'])) {
    if(isset($_GET['id'])){
        if(isset($_GET['u'])){
            if(isset($_GET['r'])){
                
                $r = $_GET['r'];
                $subid = $_GET['id'];
                $u = $_GET['u'];
                $where = array("sub_id" => $subid, "review" => $r);
                if ($obj->delete_record("rev_groups", $where)) {
//                header("location:sec_decline.php?id=$subid");
                }
                if ($obj->delete_record("note", $where)) {
//                header("location:sec_decline.php?id=$subid");
                }
                if ($obj->delete_record("review_type", $where)) {
//                header("location:sec_decline.php?id=$subid");
                }

                $fields = array("seen" => "1");
                $where = array("subid" => $subid);

                if ($obj->update_record("review_suggest", $where, $fields)) {
//                header("location:sec_decline.php?id=$subid");
                }

                $field = array("sub_id" => $subid, "status_action" => "1", "status_date" => $datetime, "status_username" => $u);
                if ($obj->insert_record("proposal_status", $field)) {
                    header("location:sec_dashboard_active.php#review");
                }
            }
            
                        
        }
    }
}


if (isset($_POST['decline'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    
    $declined = array(
        "sub_id" => $subid,
        "status_action" => "10",
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $declined)){
        header("location:sec_dashboard_active.php");
    }
    
    $wheredec = array(
        "sub_id" => $subid
    );
    $updatedec = array(
        "reclist_id" => "0",
        "rc_id" => "0"
    );
    if($obj->update_record("submission", $wheredec, $updatedec)){
        
    } 
      
}

if (isset($_POST['decline'])) {
    $subid = $_POST['submid'];
    $userid = $_POST['userid'];
    $emailto = $_POST['email'];
    $ecpath = $_POST['ecpath'];
    $nameTo = $_POST['oname'];
    $subject = $_POST['subject'];
    $secEmail = $_POST['secemail'];
    $secName = $_POST['secname'];
    
    
    if($obj->sendEmail($emailto, $ecpath, $nameTo, $subject, $secEmail)){
//        header("location:sec_dashboard_active.php");
    }
}

if(isset($_POST['saverev'])){
    
    $phrepuser = array(
        "username" => $_POST['username'],
        "title" => $_POST['title'],
        "fname" => $_POST['fname'],
        "mname" => $_POST['mname'],
        "lname" => $_POST['lname'],
        "pnum" => $_POST['pnum'],
        "institution" => $_POST['institution']
    );

    $membershipuser = array(
        "memberID" => $_POST['username'],
        "passMD5" => md5('phrep2020'),
        "email" => $_POST['email'],
        "signupDate" => $datetime,
        "groupID" => '6',
        "isBanned" => '0',
        "isApproved" => '1'
    );
    
   

    $recgroup = array(
        "rec_list_id" => $_POST['recid'],
        "type_id" => $_POST['membertype']
    );

    $obj->insert_record("membership_users", $membershipuser);
    $obj->insert_record_reviewer("phrepuser", $phrepuser, "rec_groups", $recgroup);

    header("location:sec_register_reviewer.php");
//     if($obj->insert_record_reviewer("phrepuser", $phrepuser, "rec_groups", $recgroup)){
// //        header("location:sec_register_reviewer.php");
        
//     } 

//     if($obj->insert_record("membership_users", $membershipuser)){
//         header("location:sec_register_reviewer.php");
//     }      
}

// if(isset($_POST['saverev'])){
    
// //    $emailTo = $_POST['email'];
// //    $nameTo = $_POST['fname'].' '.$_POST['mname'].' '.$_POST['lname'];
// //    $subject = $_POST['subject'];
// //    
// //    if($obj->sendEmail($emailto, $ecpath, $nameTo, $subject, $secEmail)){
// //        
// //    }

    
//     $revemail = $_POST['email'];
//     $revname = $_POST['title']. ' ' .$_POST['lname'];
//     $recname = $_POST['recname'];
//     $username = $_POST['username'];
    
//     $subject = $_POST['subject'];
//     $template = $_POST['tempbody'];
    
    
//     $find = array("{reviewername}","{recname}","{username}","{email}");
//     $change = array($revname,$recname,$username,$revemail);
//     $readytosend = str_replace($find, $change, $template);
    
//     if($obj->sendEmail1($revemail, $readytosend, $subject)){
//          header("location:sec_register_reviewer.php");}    
    
    
// }

// if(isset($_POST['completeresubmitted'])){ //EMAIL FOR RESUBMISSION
    
//     $userid = $_POST['userid'];
//     $subid = $_POST['submid'];             
            
//     //GETTING ALL THE REVIWERS
//     $getmaxrv = $obj->getMaxReview($subid);
//     $getallreviewer = $obj->getAllReviewer($subid, $getmaxrv);

    
//     foreach($getallreviewer as $gr){
//         //email sa secretariat
//         $where = array("id" => $userid);
//         $getphrepuser = $obj->fetch_record("phrepuser", $where);
//         foreach($getphrepuser as $gpu){
//             $where = array("memberID" => $gpu['username']);
//             $getemailsec = $obj->fetch_record("membership_users", $where);
//             foreach($getemailsec as $emsec){
//                 $emailsec = $emsec['email'];
//             }
//         } 
        
//         $table = array("membership_users", "phrepuser", "rev_groups", "proposal");
//         $join_on = array("memberID", "username", "id", "phrepuser_id", "sub_id", "sub_id", );
//         $where = array("phrepuser.id" => $gr['phrepuser_id'], "rev_groups.sub_id" => $subid, "rev_groups.review" => $getmaxrv);
//         $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
//         if($getdata){
//             foreach($getdata as $dt){
//                 $revemail = $dt['email'];
//                 $change = array($dt['title']." ".$dt['fname']." ".$dt['mname']." ".$dt['lname'],$dt['prop_ptitle']);
//             //    print_r($change);

//             $table1 = array("phrepuser", "rec_list");
//             $join_on1 = array("id", "secretary");
//             $where1 = array("phrepuser.id" => $userid);
//             $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
//             if($getdata1){
//                 foreach($getdata1 as $dt1){
//                     array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
//     //                print_r($change);
//                 }
//             }
            
//             $where3 = array("id" => "9");
//             $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
//             foreach ($getTemplate as $tplate) {
//                 $subject = $tplate['subject'];
//                 $template = $tplate['body'];
//             }
    
//             $find = array("{reviewername}","{proposaltitle}","{secretaryname}","{ercname}"); 
//             $readytosend = str_replace($find, $change, $template);
//             if($obj->sendEmail3($revemail, $readytosend, $subject, $emailsec)){
//                 //  header("location:sec_dashboard_active.php");
//                 }

//             }        
//         }
         
        
//     }
// //    echo "change"; print_r($change); echo "<br>";
// //    echo "find"; print_r($find);
    
// }


if(isset($_POST['download'])){
    $file = "uploads/main/";
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
if (isset($_POST['updaterev'])) {
//    echo "<pre>"; var_dump($_POST); echo "</pre>"; exit;
    $fields = array(
        "title" => $_POST['title'],
        "fname" => $_POST['fname'],
        "mname" => $_POST['mname'],
        "lname" => $_POST['lname'],
        "pnum" => $_POST['pnum'],
        "institution" => $_POST['institution']
    );
    $where = array(
      "id" => $_POST['id']  
    );
    
    if($obj->update_record("phrepuser", $where, $fields)){
         header("location:sec_register_reviewer.php?update=1&u=".$_POST['id']);
    }
    
    $fieldsm = array(
        "email" => $_POST['email']
    );
    $wherem = array(
        "memberID" => $_POST['usernameid']
    );
    
    if($obj->update_record("membership_users", $wherem, $fieldsm)){
         header("location:sec_register_reviewer.php?update=1&u=".$_POST['id']);
    }
    
}

if (isset($_GET['deleterev'])) {
    if (isset($_GET['i'])) {
        if (isset($_GET['m'])) {
            
            $id = $_GET['i'];
            $username = $_GET['m'];
            $rec = $_GET['r'];
            
            $where1 = array(
                "id" => $id
            );
            
            $where2 = array(
                "memberID" => $username
            );
            
            $where3 = array(
                "rec_list_id" => $rec,
                "phrepuser_id" => $id
            );
            
            
            if($obj->delete_record("phrepuser", $where1)){
                header("location:sec_register_reviewer.php");
                
            }
            if($obj->delete_record("membership_users", $where2)){
                header("location:sec_register_reviewer.php");                
            }
            
            if($obj->delete_record("rec_groups", $where3)){
                header("location:sec_register_reviewer.php");                
            }
            
        }
    }
}

if (isset($_POST['updateresprofile'])) {
//    echo "<pre>"; var_dump($_POST); echo "</pre>"; exit;
    $fields = array(
        "title" => $_POST['title'],
        "fname" => $_POST['fname'],
        "mname" => $_POST['mname'],
        "lname" => $_POST['lname'],
        "pnum" => $_POST['pnum'],
        "institution" => $_POST['insti']
    );
    $where = array(
      "id" => $_POST['userid']  
    );
    
    if($obj->update_record("phrepuser", $where, $fields)){
         header("location:sec_personal_info.php?id=".$_POST['userid']);
    }
    
    $fieldsm = array(
        "email" => $_POST['email']
    );
    $wherem = array(
        "memberID" => $_POST['username']
    );
    
    if($obj->update_record("membership_users", $wherem, $fieldsm)){
         header("location:sec_personal_info.php?id=".$_POST['userid']);
    }
    
}
if (isset($_GET['deleteccc'])) {
    if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id" => $id);
            if ($obj->delete_record("document", $where)) {
                header("location:sec_approve.php?id=".$subid);
            }    
    }
}

if (isset($_GET['deleteccccc'])) {
    if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id" => $id);
            if ($obj->delete_record("document", $where)) {
                header("location:sec_disapproved.php?id=".$subid);
            }    
    }
}

if (isset($_GET['deletecccc'])) {
    if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id" => $id);
            if ($obj->delete_record("document_postapproval", $where)) {
                header("location:sec_approvepa.php?id=".$subid);
            }    
    }
}

if (isset($_GET['deleteaal'])) {
    if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $subid = $_GET['subid'];
            $where = array("file_id" => $id);
            if ($obj->delete_record("document_postapproval", $where)) {
                header("location:sec_approve_amend.php?id=".$subid);
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

      
if(isset($_FILES['disapprove'])){
//       echo "<pre>"; var_dump($_POST); echo "</pre>"; exit;
       
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['disapprove']['name'];
      $file_size = $_FILES['disapprove']['size'];
      $file_tmp = $_FILES['disapprove']['tmp_name'];
      $file_type = $_FILES['disapprove']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['disapprove']['name'])));
      
      $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $postapprovaltype = $obj->getmaxpropapp($id);
          
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = $_POST['docname'];
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
//            $times = $_POST['revision'] + 1;
            
            $where = array(
                "sub_id" => $id,
                "post_approval_type" => $postapprovaltype,
                "doctype" => $doctype
            );
            $times = $obj->getmaxvalue_with_where("revision", "document_postapproval", $where);
            
            $rename_filename = $id."-".$date."-".$times."-$resdoctype.".end($temp);
            
            
            
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){ //RENAMED THE RENAME_FILENAME
            if(move_uploaded_file($file_tmp,"uploads/main/".$file_name)){
            
            $doctypefinal = $resdoctype['0'];
            
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
            "newsubmit" => "1",
            "post_approval_type" => $postapprovaltype
            );  
//            echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
            
            if($obj->upload_infopa("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times, $postapprovaltype)){
//               
            }
            
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
               
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

   
   
if (isset($_POST['disapprovepostapproval'])) {
//    echo "<pre>";var_dump($_POST);echo "<pre>"; exit;
    
    $subid = $_POST['submid'];  
    
    //PROPOSAL STATUS
    $revise = array(
            "sub_id" => $subid,
            "status_action" => $_POST['propstatus'],
            "status_date" => $datetime,
            "status_username" => $_POST['userid']
       );
       
    if($obj->insert_record("proposal_status", $revise)){
        header("location:sec_disapprovedpa.php?id=".$subid);
    }
    
    //COLLATED SUGGESTIONS
    
    $getppaid = $obj->getmaxpropapp($subid);
    $maxsubmission = $obj->getmaxsubmission($subid, $getppaid);
    
    $collated = array(
      "sub_id" => $subid,
      "paid" => $getppaid,
      "submission" => $maxsubmission,
      "collated_desc" => $_POST['collatedsugg']
    );
    if($obj->insert_record("collated_suggestion_disapproval_post", $collated)){
        header("location:sec_disapprovedpa.php?id=".$subid);
    }
    
}

if (isset($_POST['terminatepostapproval'])) {
//    echo "<pre>";var_dump($_POST);echo "<pre>"; exit;
    
    $subid = $_POST['submid'];  
    
    //PROPOSAL STATUS
    $revise = array(
            "sub_id" => $subid,
            "status_action" => $_POST['propstatus'],
            "status_date" => $datetime,
            "status_username" => $_POST['userid']
       );
       
    if($obj->insert_record("proposal_status", $revise)){
        header("location:sec_terminatepa.php?id=".$subid);
    }
    
    //COLLATED SUGGESTIONS
    
    $getppaid = $obj->getmaxpropapp($subid);
    $maxsubmission = $obj->getmaxsubmission($subid, $getppaid);
    
    $collated = array(
      "sub_id" => $subid,
      "paid" => $getppaid,
      "submission" => $maxsubmission,
      "collated_desc" => $_POST['collatedsugg']
    );
    if($obj->insert_record("collated_suggestion_disapproval_post", $collated)){
        header("location:sec_terminatepa.php?id=".$subid);
    }
    
}

      
if(isset($_FILES['terminate'])){
//       echo "<pre>"; var_dump($_POST); echo "</pre>"; exit;
       
      $errors= array();      
      $id = $_POST['submid'];
      $file_name = $_FILES['terminate']['name'];
      $file_size = $_FILES['terminate']['size'];
      $file_tmp = $_FILES['terminate']['tmp_name'];
      $file_type = $_FILES['terminate']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['terminate']['name'])));
      
      $expensions= array("doc","docx","jpg","jpeg","png","pdf");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 10485760) {
         $errors[]='File size must be less than 10 MB';
      }
      
      if(empty($errors)==true) {
            
            $postapprovaltype = $obj->getmaxpropapp($id);
          
            $urllink = $_POST['urllink'];
            $useurl = substr($urllink, 0, strpos($urllink, "?"));
            $id = $_POST['submid'];
            $doctype = $_POST['doctype'];
            $resdoctype = $_POST['docname'];
            $temp = explode(".", $file_name);
            date_default_timezone_set('Asia/Manila');
            $date = date("mdyHis", strtotime("now"));
            $datetime = date("Y-m-d H:i:s",strtotime("now"));
//            $times = $_POST['revision'] + 1;
            
            $where = array(
                "sub_id" => $id,
                "post_approval_type" => $postapprovaltype,
                "doctype" => $doctype
            );
            $times = $obj->getmaxvalue_with_where("revision", "document_postapproval", $where);
            
            $rename_filename = $id."-".$date."-".$times."-$resdoctype.".end($temp);
            
            
            
//            if(move_uploaded_file($file_tmp,"uploads/main/".$rename_filename)){ //RENAMED THE RENAME_FILENAME
            if(move_uploaded_file($file_tmp,"uploads/main/".$file_name)){
            
            $doctypefinal = $resdoctype['0'];
            
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
            "newsubmit" => "1",
            "post_approval_type" => $postapprovaltype
            );  
//            echo "<pre>"; print_r($uploadinfor); echo "</pre>"; exit;
            
            if($obj->upload_infopa("document_postapproval", $uploadinfor, $id, $doctype, $useurl, $times, $postapprovaltype)){
//               
            }
            
            $whereup = array(
                "sub_id" => $id
            );
            $updatenewsubmit = array(
                "newsubmit" => "0"
            );
            if($obj->update_record("document_postapproval", $whereup, $updatenewsubmit)){
               
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
if (isset($_GET['sendprogressnotif'])) {
    if (isset($_GET['e'])) {
        if (isset($_GET['b'])) {
            $cleane = str_replace(' ', '+', $_GET['e']);
            $e = $obj->decrypt($cleane, $obj->getmagicword());
            
            $cleanb = str_replace(' ', '+', $_GET['b']);
            $b = $obj->decrypt($cleanb, $obj->getmagicword());
            echo $b;
        }        
    }    
}

if (isset($_POST['sendnotifnow'])) {
//    var_dump($_POST);
    $emailto = $_POST['emailnotif'];
    $body = $_POST['sendreport'];
    $subject = $_POST['subject'];
    $emailsec = $_POST['emailsec'];
    
    if($obj->sendEmail3($emailto, $body, $subject, $emailsec)){
        header("location:sec_dashboard_active.php#approved");
    }
    
}

if (isset($_POST['exempting'])) {
    // var_dump($_POST);

    $pathec = $_POST['ecpath'];
    $userid = $_POST['userid'];
    $subid = $_POST['submid']; 
    
    $table = array("proposal", "phrepuser", "membership_users");
    $join_on = array("username", "id", "username", "memberID");
    $where = array("proposal.sub_id" => $subid);
    $getdata = $obj->fetch_record_innerjoin($table, $join_on, $where);
    if($getdata){
        foreach($getdata as $dt){
            $secemail = $dt['email'];
            $change = array($dt['title']." ".$dt['lname'],$dt['prop_ptitle']);
//            print_r($change); exit;
        }        
    }
    
    $table1 = array("phrepuser", "rec_list");
    $join_on1 = array("id", "secretary");
    $where1 = array("phrepuser.id" => $userid);
    $getdata1 = $obj->fetch_record_innerjoin($table1, $join_on1, $where1);
    if($getdata1){
        foreach($getdata1 as $dt1){
            array_push($change,$dt1['title']." ".$dt1['fname']." ".$dt1['mname']." ".$dt1['lname'], $dt1['erc_name']);
//            print_r($change);
        }
    }
    $where3 = array("id" => "19");
    $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
    foreach ($getTemplate as $tplate) {
        $subject = $tplate['subject'];
        $template = $tplate['body'];
    }

    $find = array("{researcher_lastname}","{ptitle}","{secretary_fullname}","{RECName}"); 
    $readytosend = str_replace($find, $change, $template);
    
    if($obj->sendEmail3att($secemail, $readytosend, $subject, $pathec)){
         header("location:sec_dashboard_active.php#review");   
    
    }  
    $where = array(
        "sub_id" => $subid,
        "status_action" => '6',
        "status_date" => $datetime,
        "status_username" => $userid
    );
    if($obj->insert_record("proposal_status", $where)){

    }
    

}