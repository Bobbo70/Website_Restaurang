<?php 
session_start();

// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
require_once(LIBRARY_PATH . "/class.cms.php");
require_once(LIBRARY_PATH . "/class.user.php");

// get database user connection
$menu_user = new USER();

// user id
if(isset($_SESSION['user_session'])){ 
$stmt = $menu_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
}

// header
$title= "Restaurang karl Kontakt - Hitta oss i Halmstad";
$metadescription= "Kontaka oss för fri offert";
$seolink= "http://restaurangkarl.se/kontakt";
require_once(TEMPLATES_PATH . "/header.php");
require_once(INCLUDE_PATH . "/formmail.php");
?>
<section id="boxcontent">
	<div class="container">
		<h2 class="hidden">Kontakta oss</h2>	
			<div class="row">
  				<div class="col-md-5">			
					<h3>Kontakta oss</h3>
						<p>Ring eller maila till oss om ni har några frågor eller vill göra en bokning.
						Kontakta oss gärna via kontaktformuläret som finns här, så återkomer vi så fort vi kan. Tack!<br />
						Vi finns här för er.<br /><br />
						
						Telefonnr: 035-70120<br />
						Vardagar mellen kl 08:00 - 16:00<br />
						E-post: info@restaurangkarl.se <br /><br />
																		
						Välkommna önskar<br />
						Kim och Hai</p>
				</div>
			
				<div class="col-md-7">				
					<h3>Kontaktformulär</h3>
					
						<!-- Delete message from unsubscribe -->
							<?php
							if(isset($_GET['raderat_id'])){
								?>		
								<div class="alert alert-success">
									<button class="close" data-dismiss="alert">&times;</button>
									<strong>Lyckad!</strong> dina kontakt uppgifter är nu raderat från! <a href="index.php">Restaurang Karl</a>
								</div>
								<?php
							}
							?>
						
					<form id="contactForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" role="form" data-toggle="validator" >
						
						<!-- Error and confirm message from validering -->
							<?php
							if(isset($error)){
								foreach($error as $error)
								{
							?>
									<div class="alert alert-danger">
										<i class="glyphicon glyphicon-warning-sign"></i>&nbsp;<?php echo $error; ?>
									</div>
									<?php
								}
							}
							else if(isset($msg)) echo $msg;
							?>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="name" >Namn *</label>
									<input id="name" type="text" name="name" class="form-control" placeholder="Skriv in ditt namn" value="<?php if(isset($error)) {echo $name;}?>" required="required" data-error="Ditt namn är obligatoriskt." />
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="email" >E-post *</label>
									<input id="email" type="email" name="email" class="form-control" placeholder="Din mailadress för att kontakta dig" value="<?php if(isset($error)){echo $email;}?>" required="required" data-error="Din E-postadress är obligatoriskt." />
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="phone" >Telefonnr *</label>
									<input id="phone" type="tel" name="phone" class="form-control" placeholder="Skriv in ditt telefonnummer" value="<?php if(isset($error)){echo $phone;}?>" required="required" data-error="Ditt telefonnummer är obligatoriskt." />
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="subject" >Ämne *</label>
									<input id="subject" type="text" name="subject" class="form-control" placeholder="Skriv ditt ämne" value="<?php if(isset($error)) {echo $subject;}?>" required="required" data-error="Ange ditt änme till oss." />
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="message" >Meddelande *</label>
									<textarea id="message" name="message" class="form-control" placeholder="Skriv ditt meddelande till oss" rows="4" required="required" data-error="Skriv ditt meddelande till oss."><?php if(isset($error)){echo $message;}?></textarea>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha']['publickey'];?>"></div>
								</div>								
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<p><strong>* Obligatoriska fält vi behöver för att kontakta dig!</strong><br />
								<input type="hidden" name="samtycke_form" value="Nej" />
								<input type="checkbox" name="samtycke_form" value="Ja" required="required" data-error="Du måste godkänna."/><strong>Ja jag samtycker</strong></p>
								<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-12">
								<p>
								SAMTYCKE<br />
								<a href="https://www.datainspektionen.se/dataskyddsreformen/" target="_blank"><span class="gdpr">Allmänna dataskyddsförordningen</span></a> 
								är en ny EU-förordning som ska stärka skyddet av personuppgifter för privatpersoner i hela EU.
								Jag godkänner att Restaurang Karl lagrar mina personuppgifter för att kunna besvara min förfrågan eller beställning. 
								Dina personuppgifter sparas bara så länge det behövs för att fullgöra avtalet. 
								Restaurang Karl delar inte dina personuppgifter med tredjepart” eller motsvarande. Jag kan när som helst ändra mitt samtycke genom att klicka på  
								länken "Radera" som finns i mailet, skicka e-post till info@restaurangkarl.se eller ringa oss på 035-701 20.
								Du får en bekräftelse/kopia på mailet du skickat till Restaurang Karl. 
								<br />
								Prenumerera på veckans lunchmney via mail från Restaurang Karl!<br />
								Jag godkänner att Restaurang Karl lagrar min epostadress för att kunna skicka nyhetsbrevet med veckans lunchmeny som kommer ut 
								en gång per vecka. Jag kan när som helst ändra mitt samtycke genom att klicka på  
								länken "Avregistrera" som finns i nyhetsbrevet, skicka e-post till info@restaurangkarl.se eller ringa oss på 035-701 20.
								<br />
								<input type="hidden" name="samtycke" value="Nej" />
								<input type="checkbox" name="samtycke" value="Ja" /><strong>Ja jag samtycker, skicka mig lunchmeny via e-post!</strong></p></br>
							</div>										
							<div class="col-md-4">
								<button id="submit" type="submit" name="submit" class="btn btn-success btn-send" /> 
									<i class="glyphicon glyphicon-envelope"></i> &nbsp; Skicka meddelande 
								</button>
							</div>
						</div>
					</form>					
				</div>
			</div>
	</div> <!-- Container -->

	<div class="container">
		<h2 class="hidden">Hitta till oss</h2>
			<div class="row">
				<div class="col-md-12">
					<h3>Hitta till oss</h3>
					<div class="google-maps">
						<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d8767.502912630843!2d12.8638441!3d56.6763819!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x8d25ddc0c922a1!2sRestaurang+Karl!5e0!3m2!1ssv!2sse!4v1499011134621" width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
	</div>
</section>

<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>