<?php
include "sample_dbtest.php";
$currDir = dirname(__FILE__);
require_once "{$currDir}/mailer/PHPMailerAutoload.php";

class UploadOperation extends Database
{
    function sendEmail3($emailto, $body, $subject) {
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
    
    public function fetch_record($table, $where) {
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql = "SELECT * FROM " . $table . " WHERE " . $condition;
        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
    }
    
    function getallclearance($datefrom) {

        $sql = "SELECT * FROM `ethical_clearance` WHERE ec_end >= '$datefrom'";

        $array = array();
        $query = mysqli_query($this->con, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
            $array[] = $row;
        }
        return $array;
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
    
    
    function emailprep($subid, $ecid, $ecexdate, $left) {
        $table = array("proposal", "phrepuser", "membership_users");
        $join_on = array("username", "id", "username", "memberID");
        $where = array("proposal.sub_id" => $subid);
        $getdata = $this->fetch_record_innerjoin($table, $join_on, $where);
        if ($getdata) {
            foreach ($getdata as $dt) {
                $resemail = $dt['email'];
                $change = array($dt['title'] . " " . $dt['lname'], $dt['prop_ptitle']);
//                print_r($change);
            }
        }

        $table1 = array("proposal", "submission", "rec_list", "phrepuser");
        $join_on1 = array("sub_id", "sub_id", "reclist_id", "id", "secretary", "id");
        $where1 = array("proposal.id" => $subid);
        $getdata1 = $this->fetch_record_innerjoin($table1, $join_on1, $where1);
        if ($getdata1) {
            foreach ($getdata1 as $dt1) {
                array_push($change, $dt1['title'] . " " . $dt1['fname'] . " " . $dt1['mname'] . " " . $dt1['lname'], $dt1['erc_name'], date("F j, Y", strtotime($ecexdate)), $left);
//                print_r($change); exit;
            }
        }        

        $where3 = array("id" => "15");
        $getTemplate = $this->fetch_record_with_where("email_templates", $where3);
        foreach ($getTemplate as $tplate) {
            $subject = $tplate['subject'];
            $template = $tplate['body'];
        }

        $find = array("{researcherName}", "{proposalTitle}", "{secretaryName}", "{ercName}", "{expirationDate}", "{daysCount}");
        $readytosend = str_replace($find, $change, $template);

        if ($this->sendEmail3($resemail, $readytosend, $subject)) {
//            header("location:sec_dashboard_active.php");
        }
    }

}

$obj = new UploadOperation();

date_default_timezone_set('Asia/Manila');
$datetime = date("Y-m-d H:i:s",strtotime("now"));
$datefrom = date("Y-m-d",strtotime("now"));

$getallactiveclearance = $obj->getallclearance($datefrom);
foreach($getallactiveclearance as $c){
    $datediff = date_diff(date_create($c["ec_end"]), date_create($datefrom));
    $daysleft = $datediff->format("%a");
    
    if($daysleft === '30'){
        $left = '1 month left';
        $subid = $c["sub_id"];
        $ecid = $c["ec_id"];
        $ecexdate = $c["ec_end"];
                
        if($obj->emailprep($subid,$ecid,$ecexdate,$left)){} 
    }
    else if($daysleft === '14'){
        $left = '2 weeks left';
        $subid = $c["sub_id"];
        $ecid = $c["ec_id"];
        $ecexdate = $c["ec_end"];
                
        if($obj->emailprep($subid,$ecid,$ecexdate,$left)){} 
    }
    else if($daysleft === '5'){
        $left = '5 days left';
        $subid = $c["sub_id"];
        $ecid = $c["ec_id"];
        $ecexdate = $c["ec_end"];
                
        if($obj->emailprep($subid,$ecid,$ecexdate,$left)){} 
    }
    else if($daysleft === '2'){
        $left = '2 days left';
        $subid = $c["sub_id"];
        $ecid = $c["ec_id"];
        $ecexdate = $c["ec_end"];
                
        if($obj->emailprep($subid,$ecid,$ecexdate,$left)){} 
    }
    else if($daysleft === '0'){
        $left = '0 days left';
        $subid = $c["sub_id"];
        $ecid = $c["ec_id"];
        $ecexdate = $c["ec_end"];
                
        if($obj->emailprep($subid,$ecid,$ecexdate,$left)){} 
    }
    else{
//        echo "Not on the conditions";
    }
}