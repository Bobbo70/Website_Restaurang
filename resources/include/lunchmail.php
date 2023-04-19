<?php
define('TIMEZONE','Europe/Stockholm');
date_default_timezone_set(TIMEZONE);

// databas class
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));

$contact_form = new MENU();

$code = md5(uniqid(rand()));

// validate input form
$subject = $message ='';

if($_SERVER["REQUEST_METHOD"] == "POST" )
{
	if(empty($_POST['subject'])) {
		$error[] = "Skriv ett ämne i veckomailet!";
	}	
	else {
		$subject = inputfield($_POST['subject']);
		if (!preg_match('/^[a-züåöäA-ZÜÅÖÄ0-9\s-]*$/',$subject)) {
		$error[] = "Endast bokstaver och siffror som ämne!";
		}
	}
	
	if(empty($_POST['message'])) {
		$error[] = "Skriv ett meddelande i lunchmailet!";
	}	
	else {
		$message = inputfield($_POST['message']);
	}	

	//check errors before sending
	if(empty($error)){
		
		try
		{			
			// Send lunch newsmail
			if($stmt = $contact_form->subscribers_email()){
				$stmt->execute();
				if($stmt->rowCount()>0){
					while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
						extract ($row);
						
						$name = $row['name'];
						$email = $row['email'];			
				
						$code = $row['tokenCode'];
						
						$Id = $row['id'];
						$key = base64_encode($Id);
						$Id = $key;
						
						$link = "http://www.restaurangkarl.se/lunch/unsubscribe.php?radera_email=$Id&code=$code";
								
						$subject = "$subject";	

						$newsletter  = "<!DOCTYPE HTML>
						<html lang='sv'>
							<head>
								<MIME-Version: 1.0>
								<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
								<title>Veckans lunchmeny från Restaurang Karl</title>
									
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
																				Lunchmeny
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
														<strong>Hej ". strip_tags($name) ."!</strong>
														<br />
														
														<br />
														<I>" . strip_tags($_POST['message']) . "</i>
														<hr />
															
														<I>" . ($_POST['matsedel']) . "</i>																								
													</td>
												</tr>
												
												<tr>
													<td class='innerpadding borderbottom bodycopy'>											
														<div class='h3' align='center'>Dagens lunch utkört</div>
														Vi kör ut lunchen till ditt företag om ni beställar fler än 5 portioner. 
														Ring in din beställning före kl 09:30 samma dag.
														Vi har även Take Away.<br>											
														Vi serverar lunch måndag - fredag kl 11:00 - 13:30 <br />																			
														Telefon: 035-70120																				
													</td>
												</tr>
												
												<tr>
													<td class='innerpadding bodycopy'>
														<div class='h3' align='center'>Kontakta oss | Avregistrera dig</div>
														Du får det här mailet för att att du har tackat ja till att prenumerera på veckans lunchmeny från Restaurang Karl.
														Vill du inte ha fler mailutskick så är det bara klicka avregistrera så avanmäler du dig enkelt från lunchmailet.
														<a href='".$link."'><div class='unsubscribe'>Avregistrera dig här!</div></a>
														
														<br />
														Detta lunchmailet har skickats till: ". strip_tags($email) ."
														<br />
														Tveka inte att höra av dig om det är något du undrar över. 
														Om det inte var du som har tackat ja till mail från oss kan du bara
														<a href='mailto:info@restaurangkarl.se?Subject=Kontakt%20från%20veckomail' target='_top'>Skicka E-post</a> till oss eller ring oss på 035-701 20 så hjälper vi dig.
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
																				<a href='http://wwww.facebook.com/restaurangkarl'>
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
				
						$newsletter = wordwrap($newsletter, 70);
	
						$contact_form->newsmail($email, $name, $subject, $newsletter);
						http_response_code(200);
						$msg = "<div class='alert alert-success'>Nyhetsbrevet är skickat med ämne: ".$subject." !</div>";
						$msgEpost[] = "Namn/företag <strong>".$name."</strong> E-post: <strong>".$email."</strong><br /><hr>";
					}
				}
			}
			else{ 
			http_response_code(500);
			$msg = "<div class='alert alert-danger'>Opps något gick fel. Kunde inte skicka nyhetsbrevet.</div>";
			}
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
}
// Sanitize input form
//FILTER_SANITIZE_STRING
function inputfield($data) {
		$data = trim($data);				// remove white spaces
		$data = stripcslashes($data);		// remove backslashes
		$data = htmlspecialchars($data);
		//$data = (filter_var($data, htmlspecialchars ));
		return $data;
}