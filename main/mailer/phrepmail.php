<?php
                //notifyMemberApproval($memberID);
                
                $currDir = dirname(__FILE__);
                require "{$currDir}/PHPMailerAutoload.php";
                
                $name = "Hector";
                $emailTo = "hbcornea@pchrd.dost.gov.ph";
                $message = "blah blah";
                //$ecpath = $_POST["ecpath"];

                $mail = new PHPMailer;

                //$mail->SMTPDebug = 3;                             // Enable verbose debug output

				$mail->Timeout = 5;
				$mail->SMTPDebug =5;
				$mail->Debugoutput = 'html';
                $mail->isSendmail();                                    // Set mailer to use SMTP
//                $mail->Host = 'smtp-relay.gmail.com';               // Specify main and backup SMTP servers
//                $mail->SMTPAuth = false;                            // Enable SMTP authentication
//                $mail->Username = '';                               // SMTP username
//                $mail->Password = '';                               // SMTP password
//                $mail->SMTPSecure = 'TLS';                          // Enable TLS encryption, `ssl` also accepted
//                $mail->Port = 587;                                  // TCP port to connect to

                $mail->setFrom('citdsadmin@pchrd.dost.gov.ph', 'PHREP'); //WHO SENT
                $mail->addAddress($emailTo, '');        // Name is optional //SENT TO
                $mail->addReplyTo('citdsadmin@pchrd.dost.gov.ph', 'PHREP');  //WHERE TO REPLY
                $mail->addCC('hbcornea@pchrd.dost.gov.ph');                     ///CC
                $mail->addBCC('');                                              //BCC

                //$mail->addAttachment('../'.$ecpath.'');                           // Add attachments /var/tmp/file.tar.gz
                $mail->addAttachment('');                           // Optional name /tmp/image.jpg', 'new.jpg
                $mail->isHTML(true);                                // Set email format to HTML

                $mail->Subject = '';
                $mail->Body = $name.''.$message;
                $mail->AltBody = '';

                if (!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo 'success';
                }

?>
