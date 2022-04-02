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
    
    function get_confirmation_info($table, $id, $username){
        $sql = "SELECT * FROM ".$table." WHERE sub_id='".$id."' AND username = '".$username."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
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
    
     
    function get_confirmation_joining_two($table1, $table2, $table3, $id, $username, $fid){
//        $sql = "SELECT * FROM ".$table." WHERE sub_id='".$id."' AND username = '".$username."'";
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.sub_id = b.sub_id ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$fid." = c.id ";
        $sql .= "WHERE a.sub_id = ".$id." and a.username = '".$username."'";
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
                WHERE a.sub_id = '$id' and a.username = '$userid'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }

    function get_date_duration($table, $id, $username){
        $sql= "SELECT * FROM ".$table." WHERE sub_id = ".$id." AND username = '".$username."'";
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;
    }
    
    function get_confirmation_joining_one($table1, $table2, $field1, $field2, $id, $username){
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$field1." = b.".$field2." ";
        $sql .= "WHERE a.sub_id = ".$id." and a.username = '".$username."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }

        function get_confirmation_joining_two_for_assess($table1, $table2, $table3, $id, $username, $fid, $partid){
//        $sql = "SELECT * FROM ".$table." WHERE sub_id='".$id."' AND username = '".$username."'";
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.sub_id = b.sub_id ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$fid." = c.id ";
        $sql .= "WHERE a.sub_id = ".$id." and a.username = '".$username."' ";
        $sql .= "AND c.part_id = '".$partid."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }

    function get_upload_info_sup($table1, $table2, $id, $kind, $username){
//        $sql = "SELECT * FROM ".$table1." a INNER JOIN ".$table2." b ON a.doctype = b.docid WHERE sub_id='".$id."' AND kind='".$kind."'";
        $sql = "SELECT * FROM ".$table1." a INNER JOIN ".$table2." b ON a.doctype = b.docid LEFT JOIN document_control c ON a.doctype = c.doctype WHERE a.sub_id='".$id."' AND a.kind='".$kind."' AND a.username = '".$username."'";
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
    
    function get_data_joined_two($table1, $table2, $field1, $field2, $id, $username){
        $sql= "SELECT * ";
        $sql .= "FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$field1." = b.".$field2." ";
        $sql .= "WHERE b.username = '".$username."'";
//        echo $sql;
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }


    
    function generatingCode($table1, $table2, $table3, $id1, $id2, $id3, $id4, $username, $id){
        $sql = "SELECT * FROM ".$table1." a ";
        $sql .= "INNER JOIN ".$table2." b ON a.".$id1." = b.".$id2." ";
        $sql .= "INNER JOIN ".$table3." c ON b.".$id3." = c.".$id4." ";
        $sql .= "WHERE a.username = '".$username."' AND a.sub_id = '".$id."'";
        
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
        if (mysqli_query($this->con, $sql)) {
            return true;
        }
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
        // echo $sql;
        
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
    
    function getResInfoEmail($id){
        
        $sql = "SELECT * FROM submission a 
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON a.reclist_id = c.id
                INNER JOIN phrepuser d ON b.username = d.id
                INNER JOIN membership_users e ON d.username = e.memberID
                WHERE a.sub_id = '$id'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }   
    
    function getSecInfoEmail($id){
        
        $sql = "SELECT * FROM submission a 
                INNER JOIN proposal b ON a.sub_id = b.sub_id
                INNER JOIN rec_list c ON a.reclist_id = c.id
                INNER JOIN phrepuser d ON c.secretary = d.id
                INNER JOIN membership_users e ON d.username = e.memberID
                WHERE a.sub_id = '$id'";
        
	$array = array();
	$query = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_assoc($query)){
		$array[] = $row;
		}
	return $array;        
    }

function sendEmail($emailto, $body, $subject) {
        
        $currDir = dirname(__FILE__);
        require "{$currDir}/mailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;

        $mail->isSendmail();    

        $mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP');           // WHO SENT
        $mail->addAddress($emailto);                                        // Name is optional //SENT TO
        $mail->addReplyTo('nationalethicscommittee.ph@gmail.com', 'NEC');        // WHERE TO REPLY
        // $mail->addCC('hbcornea@pchrd.dost.gov.ph');                                                       // CC
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

}

$obj = new UploadOperation();


if(isset($_POST['confirmsubmission'])){
    $key = $obj->getmagicword();
    $cleanid = $obj->decrypt($_POST['submid'], $key); 
}

//FOR SUBMISSION. IT WILL GET THE COMMENTS AND STAMP FOR THE DATE OF SUBMISSION//WILL ALSO GENERATE FINAL CODE
if(isset($_POST['confirmsubmission'])){
    $code = $_POST['code'];
    $id = $cleanid;
    $comment = $_POST['commentsforsec'];
    $username = $_POST['userid'];
    date_default_timezone_set('Asia/Manila');
    $datesubmit = date("Y-m-d H:i:s", strtotime("now"));
    
    $where = array("sub_id"=>$id, "username"=>$username);
    $fields = array("date_submitted"=>$datesubmit, "commentforsec"=>$comment, "code"=>$code);
    
    if($obj->update_record("proposal", $where, $fields)){
    }     
}


if(isset($_POST['confirmsubmission'])){
    $userid = $_POST['userid'];
    $subid = $cleanid;
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
    $where3 = array("id" => "2");
    $getTemplate = $obj->fetch_record_with_where("email_templates", $where3);
    foreach ($getTemplate as $tplate) {
        $subject = $tplate['subject'];
        $template = $tplate['body'];
    }

    $find = array("{secretaryname}","{RECName}","{ptitle}","{researcherfullname}"); 
    $readytosend = str_replace($find, $change, $template);
    
    if($obj->sendEmail($secemail, $readytosend, $subject)){
         header("location:dashboard.php");}
}


if(isset($_POST['confirmsubmission'])){
        
        //===============================================================================
        //Confirmation Email to Researcher
        //get researcher info

        $where = array("id" => $userid);
        $user_info = $obj->fetch_record_with_where("phrepuser", $where);
        foreach($user_info as $info){
            $where = array("memberID" => $info['username']);
            $user_email = $obj->fetch_record_with_where("membership_users", $where);
            // print_r($user_email); //researcher email
            $title_lastname = $info['title']." ".$info['lname'];
            foreach($user_email as $uemail){
                $res_email = $uemail['email'];
            }
        }
        // print_r($user_info);//researcher information
        // print_r($title_lastname);

        $where = array("sub_id" => $subid);
        $proposal_title = $obj->fetch_record_with_where("proposal", $where);
        foreach($proposal_title as $title){
            $p_title = $title['prop_ptitle'];
            $where = array("sub_id" => $title['sub_id']);
            $submission = $obj->fetch_record_with_where("submission", $where);
            foreach($submission as $subm){
                $where = array("id" => $subm['reclist_id']);
                $reclist = $obj->fetch_record_with_where("rec_list", $where);
                foreach($reclist as $ercs){
                    $erc = $ercs['erc_name'];
                }
            }
        }
        // print_r($p_title); //researcher proposal title
        // print_r($erc); //erc name
        $url = 'http://'.$_SERVER['HTTP_HOST'];

        $email_contents = array($title_lastname, $p_title, $erc, $url);
        // print_r($email_contents);

        $where = array("id" => "10");
        $getTemplate = $obj->fetch_record_with_where("email_templates", $where);
        foreach ($getTemplate as $tplate) {
            $subject = $tplate['subject'];
            $template = $tplate['body'];
        }

        $find = array("{researchername}","{proposaltitle}","{ercname}","{portal_url}"); 
        $readytosend = str_replace($find, $email_contents, $template);
        
        // echo $readytosend;

        if($obj->sendEmail($res_email, $readytosend, $subject)){
            header("location:dashboard.php");}

}
?>