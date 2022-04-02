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
    
    public function getInstitutions() {
        $sql = "SELECT * FROM institution";
        
        $query = mysqli_query($this->con,$sql);
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
    
    public function getRegions() {
        $sql = "SELECT * FROM region";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    public function getREC() {
        $sql = "SELECT *, a.id AS recid FROM rec_list a 
                INNER JOIN region b ON a.region_id = b.id";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getInsitution() {
        $sql = "SELECT * FROM institution";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getInsti($insti) {
        $sql = "SELECT * FROM institution WHERE id = '$insti'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function getSecretary() {
        $sql = "SELECT * FROM membership_users a 
                INNER JOIN phrepuser b ON a.memberID = b.username
                WHERE a.groupID = '5' ORDER BY b.lname";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
    }
    
    public function selectSecretary($id) {
        $sql = "SELECT * FROM membership_users a 
                INNER JOIN phrepuser b ON a.memberID = b.username
                WHERE b.id = '$id'";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;        
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
    
    public function select_record($table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql .= "SELECT * FROM " . $table . " WHERE " . $condition;
        $query = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_array($query);
        return $row;
    }
    
    public function select_all($table) {
        $sql = "SELECT *  FROM $table";
        
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
    
function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function sendEmail($emailto, $ecpath, $nameTo, $subject, $secEmail) {
        //notifyMemberApproval($memberID);

        $currDir = dirname(__FILE__);
        require "{$currDir}/mailer/PHPMailerAutoload.php";

            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

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
//                            );                                    // TCP port to connect to

            $mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP');
            $mail->addAddress("$emailto", '');               // Name is optional
            $mail->addReplyTo('nationalethicscommittee.ph@gmail.com', 'NEC');
            // $mail->addCC('');
            // $mail->addBCC('');                                                       //BCC

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
//            header("location:sec_dashboard_active.php");
        }
    }
function replicateavg1($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2016') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('7','8','9','10') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}

function replicateavg($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2017', '2018', '2019', '2020') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('5','6','7','8','9','10') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}
    
function saveSmam($schoolid, $fieldname){
        $sql = "SELECT SUM($fieldname) AS sumval1
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2015') AND month IN ('7','8','9','10')";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $sql1 = "SELECT SUM($fieldname) AS sumval2
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2016') AND month IN ('5','6')";
        
        $result1 = mysqli_query($this->con,$sql1);
        $row1 = mysqli_fetch_array($result1);
        
        $fileid = ($row["sumval1"]+$row1["sumval2"])/6;
        $this->replicateavg($fileid,$schoolid,$fieldname);
        $this->replicateavg1($fileid,$schoolid,$fieldname);
}
function savet1novdec($schoolid, $fieldname){
        $sql = "SELECT AVG($fieldname) AS sumval1
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2015') AND month IN ('11','12')";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row["sumval1"];
        $this->savet1novdecin($fileid,$schoolid,$fieldname);
}

function savet1novdecin($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2016', '2017', '2018', '2019', '2020') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('11', '12') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}

function savet1janapr($schoolid, $fieldname){
        $sql = "SELECT AVG($fieldname) AS sumval1
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2016') AND month IN ('1', '2', '3', '4')";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row["sumval1"];
        $this->savet1janaprin($fileid,$schoolid,$fieldname);
}
function savet1janaprin($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2017', '2018', '2019', '2020') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('1', '2', '3', '4') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}
function savet2janoct($schoolid, $fieldname){
        $sql = "SELECT SUM($fieldname) AS sumval1
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2015') AND month IN ('7','8','9','10')";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $sql1 = "SELECT SUM($fieldname) AS sumval2
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2016') AND month IN ('1','2','3','4','5','6')";
        
        $result1 = mysqli_query($this->con,$sql1);
        $row1 = mysqli_fetch_array($result1);
        
        $fileid = ($row["sumval1"]+$row1["sumval2"])/10;
//        echo $fileid;
        $this->replicatetype3($fileid,$schoolid,$fieldname);
        $this->replicatetype33($fileid,$schoolid,$fieldname);
}

function replicatetype3($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2016') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('7','8','9','10') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}

function replicatetype33($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2017', '2018', '2019', '2020') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('1','2','3','4','5','6','7','8','9','10') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}

function savet2novdec($schoolid, $fieldname){
        $sql = "SELECT AVG($fieldname) AS sumval1
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2015') AND month IN ('11', '12')";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $fileid = $row["sumval1"];
        $this->savet2novdecin($fileid,$schoolid,$fieldname);
}

function savet2novdecin($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2016','2017', '2018', '2019', '2020') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('11', '12') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}
function savet4jandec($schoolid, $fieldname){
        $sql = "SELECT SUM($fieldname) AS sumval1
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2015') AND month IN ('7','8','9','10','11','12')";
        
        $result = mysqli_query($this->con,$sql);
        $row = mysqli_fetch_array($result);
        
        $sql1 = "SELECT SUM($fieldname) AS sumval2
                FROM smam WHERE school_id = '$schoolid' AND year IN ('2016') AND month IN ('1','2','3','4','5','6')";
        
        $result1 = mysqli_query($this->con,$sql1);
        $row1 = mysqli_fetch_array($result1);
        
        $fileid = ($row["sumval1"]+$row1["sumval2"])/12;
//        echo $fileid;
        $this->savejandec4($fileid,$schoolid,$fieldname);
        $this->savejandec44($fileid,$schoolid,$fieldname);
}

function savejandec4($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2016') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('7','8','9','10','11','12') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}
function savejandec44($fileid, $schoolid,$fieldname){
        $sql = "UPDATE `smam` SET `$fieldname` = '$fileid' WHERE "
                . "`smam`.`year` IN ('2017', '2018', '2019', '2020') AND `smam`.`school_id` = $schoolid AND `smam`.`month` IN ('1','2','3','4','5','6','7','8','9','10','11','12') AND $fieldname = '0'";
        echo $sql;
        $result = mysqli_query($this->con,$sql);
//        $row = mysqli_fetch_array($result);
//        
//        return TRUE;
}

public function getUnAssignedSec() {
        $sql = "SELECT *, b.id as pid FROM membership_users a 
                INNER JOIN phrepuser b ON a.memberID = b.username
                WHERE a.groupID = '5' AND b.id NOT IN (SELECT c.secretary FROM rec_list c)";
        
        $query = mysqli_query($this->con,$sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }


    
}
$obj = new UploadOperation();

date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));
$date = date("Y-m-d", strtotime("now"));

if(isset($_POST["saverec"])){
    $erc = array(
        "erc_name" => $_POST["ercname"],
        "erc_initials" => $_POST["ercinit"],
        "erc_status" => '1',
        "erc_level" => $_POST["erclevel"],
        "insti_id" => $_POST["insti"],
        "region_id" => $_POST["region"]
    );
    
    if($obj->insert_record("rec_list", $erc)){
        header("location:co_addrec.php");
    }       
    
}

if(isset($_POST["updaterec"])){
    
    $where = array(
        "id" => $_POST["id"]
    );
    
    $erc = array(
        "erc_name" => $_POST["ercname"],
        "erc_initials" => $_POST["ercinit"],
        "erc_status" => '1',
        "erc_level" => $_POST["erclevel"],
        "insti_id" => $_POST["insti"],
        "region_id" => $_POST["region"]
    );
    
    if($obj->update_record("rec_list", $where, $erc)){
        header("location:co_addrec.php");
    }       
    
}



if(isset($_POST["saveinsti"])){
    $inst = array(
        "`desc`" => $_POST['institution']
    );
    if($obj->insert_record("institution", $inst)){
        header("location:co_add_institution.php");
    }       
}


if(isset($_POST["updateinsti"])){
    
    $where = array(
        "`id`" => $_POST["id"]
    );
    
    $erc = array(
        "`desc`" => $_POST["institution"]
    );
    
    if($obj->update_record("institution", $where, $erc)){
        header("location:co_add_institution.php");
    }       
    
} 

if(isset($_POST["savesec"])){
    $phrepuser = array(
        "username" => $_POST["username"],
        "title" => $_POST["title"],
        "fname" => $_POST["fname"],
        "mname" => $_POST["mname"],
        "lname" => $_POST["lname"],
        "pnum" => $_POST["pnum"],
        "institution" => $_POST["institution"]
    );
    $membershipuser = array(
        "memberID" => $_POST["username"],
        "passMD5" => $_POST["password"],
        "email" => $_POST["email"],
        "signupDate" => $date,
        "groupID" => '5',
        "isBanned" => '0',
        "isApproved" => '1',
    );
    if($obj->insert_record("phrepuser", $phrepuser)){
        header("location:co_regsecretary.php");
    } 
    if($obj->insert_record("membership_users", $membershipuser)){
        header("location:co_regsecretary.php");
    }      
}

if(isset($_POST["updatesec"])){
    
    $where = array(
        "id" => $_POST["id"]
    );
    
    $phrepuser = array(
        "username" => $_POST["username"],
        "title" => $_POST["title"],
        "fname" => $_POST["fname"],
        "mname" => $_POST["mname"],
        "lname" => $_POST["lname"],
        "pnum" => $_POST["pnum"],
        "institution" => $_POST["institution"]
    );
    
    if($obj->update_record("phrepuser", $where, $phrepuser)){
        header("location:co_regsecretary.php");
    } 
    
    $where1 = array(
        "memberID" => $_POST["usernameid"]
    );
    $membership = array(
        "memberID" => $_POST["username"],
        "email" => $_POST["email"]
    );
    
    if($obj->update_record("membership_users", $where1, $membership)){
        header("location:co_regsecretary.php");
    }
}

if(isset($_POST["savesec"])){
    
    $emailTo = $_POST["email"];
    $nameTo = $_POST[fname].' '.$_POST[mname].' '.$_POST[lname];
    $subject = $_POST["subject"];
    
    if($obj->sendEmail($emailto, $ecpath, $nameTo, $subject, $secEmail)){
        
    }
}

if (isset($_GET["deletesec"])) {
    if (isset($_GET["id"])) {
        if(isset($_GET["u"])){
            $id = $_GET["id"];
            $username = $_GET["u"];
            $where = array("id" => $id);
            if ($obj->delete_record("phrepuser", $where)) {
                header("location:co_regsecretary.php");
            }      
            $where1 = array("memberID" => $username);
            if ($obj->delete_record("membership_users", $where1)) {
                header("location:co_regsecretary.php");
            }
        }
    }
}



if(isset($_POST["saveassignsec"])){
    
    $where = array("id" => $_POST["id"]);
    $field = array("secretary" => $_POST["sec"]);
    
    if($obj->update_record("rec_list", $where, $field)){
        header("location:co_addrec.php");
    }       
    
}





if(isset($_POST["savetype1"])){
    
    $schoolid = $_POST["schoolid"];
    $fieldname = $_POST["fieldname"]; 
    
    if($obj->saveSmam($schoolid, $fieldname)){
        header("location:smam.php");
    }       
    
}


if(isset($_POST["savenovdec"])){
    
    $schoolid = $_POST["schoolid"];
    $fieldname = $_POST["fieldname"]; 
    
    if($obj->savet1novdec($schoolid, $fieldname)){
        header("location:smam.php");
    }       
    
}

if(isset($_POST["savejanapr"])){
    
    $schoolid = $_POST["schoolid"];
    $fieldname = $_POST["fieldname"]; 
    
    if($obj->savet1janapr($schoolid, $fieldname)){
        header("location:smam.php");
    }       
    
}
if(isset($_POST["savejanoct"])){
    
    $schoolid = $_POST["schoolid"];
    $fieldname = $_POST["fieldname"]; 
    
    if($obj->savet2janoct($schoolid, $fieldname)){
        header("location:smam.php");
    }       
    
}
if(isset($_POST["savenovdec2"])){
    
    $schoolid = $_POST["schoolid"];
    $fieldname = $_POST["fieldname"]; 
    
    if($obj->savet2novdec($schoolid, $fieldname)){
        header("location:smam.php");
    }       
    
}
if(isset($_POST["savejandec4"])){
    
    $schoolid = $_POST["schoolid"];
    $fieldname = $_POST["fieldname"]; 
    
    if($obj->savet4jandec($schoolid, $fieldname)){
        header("location:smam.php");
    }       
    
}


