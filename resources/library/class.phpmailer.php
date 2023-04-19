<?php
class PHPMAIL
{	
	/* Send formmail to Restaurang Karl */
	function send_mail($subject, $body)
	{	
		$config = parse_ini_file(LIBRARY_PATH . '/config-mailerFile.ini.php'); 
		require_once(MAILER_PATH . '/autoload.php');
		
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
		$mail->IsSMTP();
		$mail->CharSet 		= 'utf-8';
		
		$mail->Host 		= $config['host'];
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $config['smtpsecure'];
		$mail->Username 	= $config['username'];
		$mail->Password 	= $config['password'];
		$mail->Port			= $config['port'];		
		$mail->SMTPDebug	= 0;		
		
		$mail->SetFrom		(($config['mailaddress']),'Restaurang Karl');
		$mail->AddAddress	(($config['mailaddress']),'Restaurang Karl');
		$mail->AddReplyTo	(($config['mailaddress']),'Restaurang Karl');
		
		$mail->Subject		= $subject;
		$mail->MsgHTML		($body);
		$mail->Send();
	}
	
	/* Send formmail newsletter.php och formmail.php to Restaurang Karl */
	function send_mailform($subject, $body, $email, $name)
	{	
		$config = parse_ini_file(LIBRARY_PATH . '/config-mailerFile.ini.php'); 
		require_once(MAILER_PATH . '/autoload.php');
		
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
		$mail->IsSMTP();
		$mail->CharSet 		= 'utf-8';
		
		$mail->Host 		= $config['host'];
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $config['smtpsecure'];
		$mail->Username 	= $config['username'];
		$mail->Password 	= $config['password'];
		$mail->Port			= $config['port'];	
		$mail->SMTPDebug	= 0;
		
		$mail->SetFrom		(($config['mailaddress']),'Restaurang Karl');
		$mail->AddAddress	(($config['mailaddress']),'Restaurang Karl');
		$mail->AddReplyTo	($email ,$name);
		
		$mail->Subject		= $subject;
		$mail->MsgHTML		($body);
		$mail->Send();
	}
	
	/* Send formmail copy newsletter.php och formmail.php to subscribe */
	function send_mailcopy($email, $name, $subject_copy, $body_copy)
	{	
		$config = parse_ini_file(LIBRARY_PATH . '/config-mailerFile.ini.php'); 
		require_once(MAILER_PATH . '/autoload.php');
		
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
		$mail->IsSMTP();
		$mail->CharSet = 'utf-8';
		
		$mail->Host 		= $config['host'];
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $config['smtpsecure'];
		$mail->Username 	= $config['username'];
		$mail->Password 	= $config['password'];
		$mail->Port			= $config['port'];	
		$mail->SMTPDebug	= 0;
		
		$mail->SetFrom		(($config['mailaddress']),'Restaurang Karl');
		$mail->AddAddress	($email ,$name);
		$mail->AddReplyTo	(($config['mailaddress']),'Restaurang Karl');
		
		$mail->Subject		= $subject_copy;
		$mail->MsgHTML		($body_copy);
		$mail->Send();
	}
}