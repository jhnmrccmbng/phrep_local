<?php


// for($i=0;$i<=2;$i++){

//notifyMemberApproval($memberID);

$currDir = dirname(__FILE__);
require "{$currDir}/mailer/PHPMailerAutoload.php";

$mail = new PHPMailer;
//var_dump(PHPMailer::validateAddress('hbcornea@pchrd.dost.gov.ph'));
$mail->isSendmail();
//$mail->Sendmail = '/sbin/sendmail';

//$mail->SMTPDebug = 3;                                                 // Enable verbose debug output


$mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP');                // WHO SENT
$mail->addAddress('mail.my.doc@gmail.com');                        // Name is optional //SENT TO
$mail->addReplyTo('nationalethicscommittee.ph@gmail.com', 'NEC');       // WHERE TO REPLY
//$mail->addCC('');                                                       // CC
//$mail->addBCC('');                                                      //BCC

//$mail->addAttachment('');                                               // Add attachments /var/tmp/file.tar.gz
$mail->isHTML(true);                                                    // Set email format to HTML

$mail->Subject = "This is a test email";
$mail->Body = "Your email settings are correct.";
$mail->AltBody = '';

if (!$mail->send()) {
    //echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    //return TRUE;
    echo 'successful';
}


// }


?>
