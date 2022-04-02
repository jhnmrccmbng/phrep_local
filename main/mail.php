<?php $currDir = dirname(__FILE__);
        require "$currDir/mailer/PHPMailerAutoload.php";

        $mail = new PHPMailer;
        var_dump(PHPMailer::validateAddress('hbcornea@pchrd.dost.gov.ph'));

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
        $mail->addAddress('mail.my.doc@gmail.com');                                        // Name is optional //SENT TO
        $mail->addReplyTo('citdsadmin@pchrd.dost.gov.ph', 'PHREP');        // WHERE TO REPLY
        $mail->addCC('');                                                       // CC
        $mail->addBCC('');                                                      //BCC

        $mail->addAttachment('');                                               // Add attachments /var/tmp/file.tar.gz
        $mail->addAttachment('');                                               // Optional name /tmp/image.jpg', 'new.jpg
        $mail->isHTML(true);                                                    // Set email format to HTML

        $mail->Subject = '';
        $mail->Body = 'This is body';
        $mail->AltBody = '';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return TRUE;
        }
		
		?>