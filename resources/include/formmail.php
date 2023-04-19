<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(LIBRARY_PATH . "/class.contact.php");
require_once(LIBRARY_PATH . "/class.phpmailer.php");

$contact_form = new FORM();

$code = md5(uniqid(rand()));

$recaptchaKey = $config['recaptcha']['privatkey'];
$userIP = $_SERVER['REMOTE_ADDR'];

// validate input form
$name = $email = $phone = $subject = $message = $samtycke_form = $samtycke ='';

if($_SERVER["REQUEST_METHOD"] == "POST" )
{
	if(empty($_POST['name'])) {
		$error[] = "Skriv ditt namn!";
	} 
	else {
		$name = inputfield($_POST['name']);
		if (!preg_match('/^[a-züåöäA-ZÜÅÖÄ\s-]*$/',$name)) {
		$error[] = "Endast bokstaver som namn!";
		}
	}

	if(empty($_POST['email'])) {
		$error[] = "Skriv din E-postadress!";
	}	
	else {
		$email = inputfield($_POST['email']);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error[] = "Du måste ange en riktig E-postadress!";
		}
	}

	if(empty($_POST['phone'])) {
		$error[] = "Skriv ditt telefonnummer!";
	}	
	else {
		$phone = inputfield($_POST['phone']);
		if(!preg_match('/^[+0-9]{2,5}[ -]?[0-9]{2,5}[ -]?[0-9]{2,5}[ -]?[0-9]{1,8}$/',$phone)){
		$error[] = "Telefonnummer kan bara vara siffror och + - ";
		}
	}
		
	if(empty($_POST['subject'])) {
		$error[] = "Skriv ditt Ämne!";
	}	
	else {
		$subject = inputfield($_POST['subject']);
		if (!preg_match('/^[a-züåöäA-ZÜÅÖÄ0-9\s-]*$/',$subject)) {
		$error[] = "Endast bokstaver och siffror som ämne!";
		}
	}
	
	if(empty($_POST['message'])) {
		$error[] = "Skriv ditt meddelande till oss!";
	}	
	else {
		$message = inputfield($_POST['message']);
	}
	
	if(empty($_POST['samtycke_form'])) {
		$error[] = "Du måste godkänna att vi sparar dina kontakt uppgifter!";
	} 
	else {
		$samtycke_form = inputfield($_POST['samtycke_form']);
		$samtycke_form = "Ja";
	}
	
	// If the Google reCAPTCHA box was not clicked  
	if(empty($_POST['g-recaptcha-response'])) {
			$error[] = 'Snälla, klicka i reCAPTCHA boxen. "Jag är inte en robot!" ';
	} 
	//Check if reCAPTCHA has been used
	if(isset($_POST['g-recaptcha-response'])) { 
		$responseKey = $_POST['g-recaptcha-response'];
			
		// Verify the reCAPTCHA code
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaKey&response=$responseKey&remoteip=$userIP';
		
		// Get reCAPTCHA
		$response = file_get_contents($url);
		$response = json_decode($response);
		
		// If the response comes back negative, it's a bot, error out
		if(!empty($response)) {
			if(!$response->success !=true) {
				$error[] = 'Robot verification gick fel. Försök igen.';
			}
		}
	}

	if(isset($_POST['samtycke'])){
		$samtycke = $_POST['samtycke'];
		
		if($_POST['samtycke'] == "Ja"){
			$samtycke = "Ja";
			$contact_form->email_register($name,$email,$samtycke,$code);
		}
		else{
			if($_POST['samtycke'] == "")
				$samtycke = "Nej";
		}
	}
	
	//check errors before sending
	if(empty($error)){
		
		try
		{	
			// Send contact form
			if ($contact_form->register($samtycke_form,$samtycke,$name,$email,$phone,$subject,$message,$code)) {

				$Id = $contact_form->lasdID();		
				$key = base64_encode($Id);
				$Id = $key;
				
				$subject_copy = "Kopia på ditt mail till Restaurang Karl";

				$body  = "<!DOCTYPE HTML>
				<html lang='sv'>
					<head>
						<MIME-Version: 1.0>
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
						<title>Prenumeration formulär från restaurangkarl.se</title>
						
						<style type='text/css'>
							body {margin: 0; padding: 0; min-width: 100%!important;}
							img {height: auto;}			
							.content {width: 100%; max-width: 700px;}
							.header {padding: 40px 10px 20px 10px;}
							.innerpadding {padding: 10px 30px 20px 30px;}
							.borderbottom {border-bottom: 1px solid #f2eeed;}
							.subhead {font-size: 14px; color: #ffffff; font-family: sans-serif; letter-spacing: 3px;}
							.h1, .h2, .h3, .bodycopy {color: #333333; font-family: sans-serif;}
							.h1 {font-size: 32px; line-height: 38px; font-weight: bold;}
							.h2 {padding: 0 0 15px 0; font-size: 20px; line-height: 24px; font-weight: bold;}
							.h3 {padding: 0 0 15px 0; font-size: 16px; line-height: 20px; font-weight: bold;}
							.bodycopy {font-size: 14px; line-height: 18px;}
							.bodycopy a {color: #000; text-decoration: underline;}
							.footer {padding: 20px 30px 15px 30px;}
							.footercopy {font-family: sans-serif; font-size: 12px; color: #ffffff;}
							.footercopy a {color: #ffffff; text-decoration: underline;}
													
							@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
								body[yahoo] .header img {width:300px;}
							}
							
							/*@media only screen and (min-device-width: 701px) {
								.content {width: 700px !important;}
								.col425 {width: 525px!important;}
								.col380 {width: 455px!important;}
							}*/
						</style>
					</head>

					<body yahoo bgcolor='#f6f8f1'>
						<table width='100%' bgcolor='#f6f8f1' border='0' cellpadding='0' cellspacing='0'>
							<tr>
								<td>								
									<!--[if (gte mso 9)|(IE)]>
									<table width='700' align='center' border='0' cellpadding='0' cellspacing='0'>
										<tr>
											<td>
									<![endif]-->								
									<table bgcolor='#ffffff' class='content' align='center' border='0' cellpadding='0' cellspacing='0'>
										
										<tr>
											<td bgcolor='#333333' class='header'>											
												<!--[if (gte mso 9)|(IE)]>
													<table width='525' align='center' border='0' cellpadding='0' cellspacing='0'>
														<tr>
															<td>
												<![endif]-->
												<table class='col525' align='center' border='0' cellpadding='0' cellspacing='0'>  
													<tr>
														<td height='70'>
															<table width='100%' border='0' cellspacing='0' cellpadding='0'>
																<tr>
																	<td>
																		<img class='fix' src='http://www.restaurangkarl.se/img/layout/loggo.gif' width='525' height='100' border='0' alt='Loggo Restaurang Karl' />
																	</td>
																</tr>
																<tr>
																	<td class='subhead' style='padding: 0 0 0 3px;'>
																		restaurangkarl.se
																	</td>
																</tr>
															</table>
														</td>
													</tr>								
												</table>												
												<!--[if (gte mso 9)|(IE)]>
															</td>
														</tr>
													</table>
												<![endif]-->												
											</td>
										</tr>
										
										<tr>
											<td class='innerpadding bodycopy borderbottom'>
												<table width='100%' border='0' cellspacing='0' cellpadding='0'>
													<tr>
														<td class='h2' align='center' colspan='2'>
															Kontaktformulär
														</td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>Datum:</strong> </td>
														<td> ". date('d-m-Y H:i') ." </td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>Namn:</strong> </td>
														<td> ". strip_tags($_POST['name']) ." </td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>E-post:</strong> </td>
														<td> ". strip_tags($_POST['email']) ." </td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>Telefonnr:</strong> </td>
														<td> ". strip_tags($_POST['phone']) ." </td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>Ämne:</strong> </td>
														<td> ". strip_tags($_POST['subject']) ." </td>
													</tr>
												</table>
											</td>
										</tr>
										
										<tr>
											<td class='innerpadding bodycopy borderbottom'>											  
												<strong>Hej. Restaurang Karl har fått ett meddelande från ". strip_tags($_POST['name']) ."!</strong>
												<br />
												Detta är ett mejl från kontaktformuläret från hemsidan.
											</td>
										</tr>
										
										<tr>
											<td class='innerpadding bodycopy borderbottom'>
												<strong>Meddelande</strong>
												<br />
												<I>" . strip_tags($_POST['message']) . "</i>
											</td>
										</tr>
										
										<tr>
											<td class='footer' bgcolor='#666666' color='#ffffff'>
												<table width='100%' border='0' cellspacing='0' cellpadding='0'>
													<tr>
														<td align='center' class='footercopy'>
															Axel Olssons gata 24, 302 27 Halmstad<br />
															Tel. 035-701 20<br />
															Fax 035-17 31 20<br />
															info@restaurangkarl.se<br />
															<br />
															&copy; Copyright 2017 <a href='http://www.restaurangkarl.se'>Restaurang Karl</a> Alla rättigheter reserverade.<br/>
														</td>
													</tr>
												</table>
											</td>
										</tr>										
									</table>
									
								<!--[if (gte mso 9)|(IE)]>
										</td>
									</tr>
								</table>
								<![endif]-->
									
								</td>
							</tr>
						</table>
					</body>
				</html>";
						
				// Send copy to sender from contact form
				$body_copy  = "<!DOCTYPE HTML>
				<html lang='sv'>
					<head>
						<MIME-Version: 1.0>
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
						<title>Kopia från kontaktformulär du skicka till Restaurang Karl</title>
							
						<style type='text/css'>
							body {margin: 0; padding: 0; min-width: 100%!important;}
							img {height: auto;}			
							.content {width: 100%; max-width: 700px;}
							.header {padding: 40px 10px 20px 10px;}
							.innerpadding {padding: 10px 30px 20px 30px;}
							.borderbottom {border-bottom: 1px solid #f2eeed;}
							.subhead {font-size: 14px; color: #ffffff; font-family: sans-serif; letter-spacing: 3px;}
							.h1, .h2, .h3, .bodycopy {color: #333333; font-family: sans-serif;}
							.h1 {font-size: 32px; line-height: 38px; font-weight: bold;}
							.h2 {padding: 0 0 15px 0; font-size: 20px; line-height: 24px; font-weight: bold;}
							.h3 {padding: 0 0 15px 0; font-size: 16px; line-height: 20px; font-weight: bold;}
							.bodycopy {font-size: 14px; line-height: 18px;}
							.bodycopy a {color: #000; text-decoration: underline;}
							.button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
							.button a {color: #ffffff; text-decoration: none;}
							.footer {padding: 20px 30px 15px 30px;}
							.footercopy {font-family: sans-serif; font-size: 12px; color: #ffffff;}
							.footercopy a {color: #ffffff; text-decoration: underline;}
							.unsubscribe {width:75%; text-align:center; display: block; margin: 10px auto; padding: 10px 50px; background: #333333; border-radius: 5px; text-decoration: none; font-weight: bold; font-size:14px; color:#ffffff;}
							
							@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
								body[yahoo] .buttonwrapper {background-color: transparent!important;}
								body[yahoo] .button {padding: 0px!important;}
								body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
								.header img {width:300px;}
							}
							
							/*@media only screen and (min-device-width: 701px) {
								.content {width: 700px !important;}
								.col425 {width: 525px!important;}
								.col380 {width: 455px!important;}
							}*/
						</style>

					</head>

					<body yahoo bgcolor='#f6f8f1'>
						<table width='100%' bgcolor='#f6f8f1' border='0' cellpadding='0' cellspacing='0'>
							<tr>
								<td>
									<!--[if (gte mso 9)|(IE)]>
									<table width='700' align='center' border='0' cellpadding='0' cellspacing='0'>
										<tr>
											<td>
									<![endif]-->								
									<table bgcolor='#ffffff' class='content' align='center' border='0' cellpadding='0' cellspacing='0'>
										
										<tr>
											<td bgcolor='#333333' class='header'>											
												<!--[if (gte mso 9)|(IE)]>
													<table width='525' align='center' border='0' cellpadding='0' cellspacing='0'>
														<tr>
															<td>
												<![endif]-->
												<table class='col525' align='center' border='0' cellpadding='0' cellspacing='0'>  
													<tr>
														<td height='70'>
															<table width='100%' border='0' cellspacing='0' cellpadding='0'>
																<tr>
																	<td>
																		<img class='fix' src='http://www.restaurangkarl.se/img/layout/loggo.gif' width='525' height='100' border='0' alt='Loggo Restaurang Karl' />
																	</td>
																</tr>
																<tr>
																	<td class='subhead' style='padding: 0 0 0 3px;'>
																		restaurangkarl.se
																	</td>
																</tr>
															</table>
														</td>
													</tr>								
												</table>												
												<!--[if (gte mso 9)|(IE)]>
															</td>
														</tr>
													</table>
												<![endif]-->												
											</td>
										</tr>
																				
										<tr>
											<td class='innerpadding bodycopy borderbottom'>
												<table width='100%' border='0' cellspacing='0' cellpadding='0'>
													<tr>
														<td class='h2' align='center' colspan='2'>
															Kopia på kontaktformulär
														</td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>Datum:</strong> </td>
														<td> ". date('d-m-Y H:i') ." </td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>Namn:</strong> </td>
														<td> ". strip_tags($_POST['name']) ." </td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>E-post:</strong> </td>
														<td> ". strip_tags($_POST['email']) ." </td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>Telefonnr:</strong> </td>
														<td> ". strip_tags($_POST['phone']) ." </td>
													</tr>
													<tr>
														<td style='width:200px; font-size:18px;'> <strong>Ämne:</strong> </td>
														<td> ". strip_tags($_POST['subject']) ." </td>
													</tr>
												</table>
											</td>
										</tr>
										
										<tr>
											<td class='innerpadding bodycopy borderbottom'>											  
												<strong>Hej ". strip_tags($_POST['name']) ."! Tack för ditt mail</strong>
												<br />
												Detta är en kopia på ditt mail som du skicka till Restaurang Karl via kontaktformuläret.
												Vi återkommer så snart vi kan med ett svar till dig. 
											</td>
										</tr>
										
										<tr>
											<td class='innerpadding bodycopy borderbottom'>	
												<strong>Kopia på ditt meddelande</strong>
												<br />
												<I>" . strip_tags($_POST['message']) . "</i>
											</td>
										</tr>
										
										<tr>
											<td class='innerpadding borderbottom'>											
												<table width='150' align='left' border='0' cellspacing='0' cellpadding='0'>
													<tr>
														<td height='150' style='padding: 0 20px 20px 0;'>
															<img class='fix' src='http://www.restaurangkarl.se/img/content/lunchkarl-mail.png' width='150' height='150' border='0' alt='Bild från lunchbuffe och restaurangen' />
														</td>
													</tr>
												</table>
												
												<!--[if (gte mso 9)|(IE)]>
													<table width='455' align='left' border='0' cellpadding='0' cellspacing='0'>
														<tr>
															<td>
												<![endif]-->						
												<table class='col455' align='left' border='0' cellspacing='0' cellpadding='0' style='width: 100%; max-width: 455px;'>
													<tr>
														<td>
															<table width='100%' border='0' cellspacing='0' cellpadding='0'>
																<tr>
																	<td class='bodycopy'>
																		Välkommen till Restaurangen Karl.<br />
																		Vi serverar lunch måndag - fredag kl 11:00 - 13:30 <br />																			
																		Telefon: 035-70120
																	</td>
																</tr>
																
																<tr>
																	<td style='padding: 10px 0 0 0;'>
																		<table class='buttonwrapper' bgcolor='#e05443' border='0' cellspacing='0' cellpadding='0'>
																			<tr>
																				<td class='button' height='45'>
																					<a href='http://www.restaurangkarl.se/lunch' target='_top'>Lunchmeny</a>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<!--[if (gte mso 9)|(IE)]>
															</td>
														</tr>
													</table>
												<![endif]-->											
											</td>
										</tr>

										<tr>
											<td class='innerpadding bodycopy'>
												<div class='h3' align='center'>Kontakta oss | Avregistrera dig</div>
												Du får det här mejlet för att du kontakta oss via restaurangkarl.se.
												Om du inte vill att vi sparar dina kontakt uppgifter elektronik så är det bara klicka på radera så raderas dina kontakt uppgifter från oss.
												<a href='http://www.restaurangkarl.se/kontakt/unsubscribe_Id.php?radera_kontakt=$Id&code=$code'><div class='unsubscribe'>Radera dig här!</div></a> 
												
												<br />
												Detta mail har skickats till: ". strip_tags($_POST['email']) ."
												<br />
												Tveka inte att höra av dig om det är något du undrar över.
												Om det inte var du som har skickat ett e-postmeddelanden till oss så tar vi bort meddelande. 
												<a href='mailto:info@restaurangkarl.se?subject=Ta%20bort%20mitt%20meddelande' target='_top'>Skicka E-post</a> till oss eller ring oss på 035-701 20 så hjälper vi dig.
											</td>
										</tr>
										
										<tr>
											<td class='footer' bgcolor='#666666' color='#ffffff'>
												<table width='100%' border='0' cellspacing='0' cellpadding='0'>
													<tr>
														<td align='center' class='footercopy'>
															<strong>Följ oss på facebook</strong>													
														</td>
													</tr>
													
													<tr>
														<td align='center' style='padding: 20px 0 0 0;'>
															<table border='0' cellspacing='0' cellpadding='0'>
																<tr>
																	<td width='48' style='text-align: center; padding: 0 10px 10px 10px;'>
																		<a href='http://www.facebook.com/restaurangkarl'>
																		<img src='http://www.restaurangkarl.se/img/content/facebook.png' width='48' height='48' alt='Facebook' border='0' />
																		</a>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													
													<tr>
														<td align='center' class='footercopy'>
															Axel Olssons gata 24, 302 27 Halmstad<br />
															Tel. 035-701 20<br />
															Fax 035-17 31 20<br />
															info@restaurangkarl.se<br />
															<br />
															&copy; Copyright 2017 <a href='http://www.restaurangkarl.se'>Restaurang Karl</a> Alla rättigheter reserverade.<br/>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										
									</table>									
									<!--[if (gte mso 9)|(IE)]>
											</td>
										</tr>
									</table>
									<![endif]-->
									
								</td>
							</tr>
						</table>
					</body>
				</html>";
				
				$body = wordwrap($body, 70);
				$body_copy = wordwrap($body_copy, 70);
				
				$phpmailer = new PHPMAIL();
				$phpmailer->send_mailform($subject, $body ,$email, $name); 
				$phpmailer->send_mailcopy($email, $name, $subject_copy, $body_copy);
					http_response_code(200);
					$msg = "<div class='alert alert-success'>
							<button class='close' data-dismiss='alert'>&times;</button>
							Tack för ditt mail.<br>Vi återkommer så fort vi kan med ett svar till dig. Kopia på mailet är skickat till din E-postadress.</div>";
			}	
			else{ 
			http_response_code(500);
			$msg = "<div class='alert alert-danger'>Opps något gick fel. Kunde inte skicka ditt meddelande.</div>";
			}	
		}		
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
}
// Sanitize input formate
//FILTER_SANITIZE_STRING debricated
function inputfield($data) {
		$data = trim($data);				// remove white spaces
		$data = stripcslashes($data);		// remove backslashes
		$data = htmlspecialchars($data);
		//$data = (filter_var($data, htmlspecialchars ));
		return $data;
}