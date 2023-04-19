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

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// set number of records per page
$records_per_page = 5;
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// get database connection
$week_menu = new MENU();

// query weekmenu
$stmt = $week_menu->readAllweeks($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// header
$title= "Lunch Halmstad - Restaurang Karl";
$metadescription= "Lunch Take Away Halmstad | På Restaurang Karl erbjuder vi dagens lunch måndag till fredag mellan 11:00 och 14:00. Lunch inkl salladsbuffe, bröd, dryck, kaffe. Välj mellan dagens rätt | Vegetarisk | Pasta | sallad";
$seolink= "http://restaurangkarl.se/lunch";
require_once(TEMPLATES_PATH . "/header.php");
require_once(INCLUDE_PATH . "/newsletter.php");		
?>		
<section id="boxcontent">	
	
	<div class="container">
		<h2 class="hidden">Dagens lunch</h2>
			<div class="row">
				<div class="col-md-5 col-md-push-7">
					
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
					?>					
					
					<?php
					if(isset($_GET['raderat_epost'])){
						?>		
						<div class="alert alert-success">
							<button class="close" data-dismiss="alert">&times;</button>
							<strong>Lyckad!</strong> Du har nu avregistrerat dig från att få flera E-postutskick från Restaurang Karl. Din E-postadress är nu raderat !
						</div>
						<?php
					}
					if(isset($msg)) echo $msg;
					?>				
			
					<div class="text-center">
						<h3>Välkommen till<br>Restaurang Karl</h3>
							<p><strong>Öppettider </strong><br />
							Restaurang Karl Måndag - fredag kl 11:00 - 13:30 <br /><br />
							Telefon: 035-70120
							<br /> </p>
					</div>
					<br />
					
					<div class="well">
						<p>Till dagens lunch ingår alltid en salladsbuffé, bröd, dryck och kaffe.
						Önskar du Take Away så går det bra. Blanda din egen sallad från salladsbuffén. 
							Välkommen!
						</p>						
					</div>
					
						<img src="/img/content/salladsbuffe.jpg" class="img-rounded" style="display:inline; margin-bottom:10px" alt="Salladsbuffé" width="100%">
						
					<div class="well">
						<h4>Dagens lunch utkört</h4>
							<p>Vi kör ut lunchen till ditt företag om ni beställer fler än 5 portioner.
							Ring in din beställning före kl 09:30 samma dag.
							</p>
						
						<h4>Nya Priser från den 1 mars</h4>
							<p>Dagens lunch: 109,- kr i restaurangen<br /> 
							Take Away/utkörning: 95,- kr<br /> 
							Seniorer 99,- kr i restaurangen
							<br /> 
							LUNCHKUPONGER
							Häfte med 11 kuponger 1150,- kr<br />
							Häfte Senior med 11 kuponger 1145,- kr
							<br />
							HÄMTHÄFTE (Take Away)
							Häfte med 10 kuponger 900,- kr
							</p>
							
						<h4>Priser</h4>
							<p>Dagens lunch: 105,- kr i restaurangen<br /> 
							Take Away/utkörning: 90,- kr<br /> 
							Seniorer 95,- kr i restaurangen
							<br /> 
							LUNCHKUPONGER
							Häfte med 11 kuponger 1089,- kr
							<br />
							HÄMTHÄFTE (Take Away)
							Häfte med 10 kuponger 880,- kr
							</p>
					</div>
					
				</div>
			<!-- Lunch Menu -->
			
				<div class="col-md-7 col-md-pull-5">
					
					<?php		
					if($num>0){			
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
						{
							extract($row);
						?>
					<h4 class="hr-double"><strong>Dagens lunch vecka <?php echo $vecka +0; ?></strong></h4>
					<?php
						}
						?>
					 					
					<div class="well">
						<p><strong>Sallad:</strong> Vi har en dagens sallad varje dag.<br />
						<hr />
						<?php	
							$row=$stmt->fetch(PDO::FETCH_ASSOC);
							{
								extract($row);
							?>
						<strong>Veckans pasta: </strong><?php echo $veckans_pasta; ?></p>
						<?php
							}
							?>
					</div>
						<p>	L=Innehåller LAKTOS<br/>
							G=Innehåller GLUTEN<br/>
							Ä=Innehåller ÄGG<br/><br/>
						</P>
						<table class="table table-responsive table-no-bordered">
							<?php														
							$stmt->execute();
							if ($stmt->rowCount()>0 )
							{
								while ($row=$stmt->fetch(PDO::FETCH_ASSOC))
								{
									extract($row);
							?>
									<thead>
										<tr class="gray">
											<th><?php echo $veckodag;?></th>
											<th class="text-right"><?php setlocale(LC_ALL,'sv_SE.UTF-8'); echo date('d M', strtotime($datum)); ?></th>
										</tr>
									</thead>
															
									<tbody>
										<tr>
											<th class="col-xs-2 col-md-2">Alt 1</th>
											<td class="col-xs-10 col-md-10"><?php echo $alt_1; ?></td>
										</tr>
										<tr>
											<th class="col-xs-2 col-md-2">Alt 2</th>
											<td class="col-xs-10 col-md-10"><?php echo $alt_2; ?></td>
										</tr>
										<tr>
											<th class="col-xs-2 col-md-2">Vegetarisk</th>
											<td class="col-xs-10 col-md-10"><?php echo $alt_veg; ?></td>
										</tr>
										<tr>
											<th class="col-xs-2 col-md-2">Sallad</th>
											<td class="col-xs-10 col-md-10"><?php echo $alt_sallad; ?></td>
										</tr>
									</tbody>
								<?php
								}
							}
							?>					
						</table>						
					<?php
						// the page where this paging is used
						$page_url = "index.php?";
						 
						// count weekmenu in the database to calculate total pages
						$total_rows = $week_menu->countAllweeks();
						 
						// paging buttons here
						require_once(INCLUDE_PATH . "/paging.php");
					}

						// tell the user there are no menu
						else{
							echo "<div class='alert alert-info'>Ingen lunchmeny hittas.</div>";
						}
						?>
							<div class="well">
						<p>
						Vi försöker tillgodose all specialkost i den mån vi kan.
						Fråga oss gärna om dagens lunch om det är något du undrar över.<br />
						Så långt det går använder vi råvaror som kommer från Sverige. Vi gillar svenskt kött.
						All fisk som vi serverar kommer från ett livskraftig bestånd och har fiskats med omtanke om havsmiljön.
						
						</p>						
							</div>
				</div>
				
			</div>
	</div> <!-- Container -->
		
	<br class="clear"/>
	
		<div class="container">
			<h2 class="hidden">Matbilder</h2>
				<div class="row">
					<div class="col-sm-2 col-md-3">
						<img class="img-responsive" src="/img/content/bröd.jpg" alt="Bröd buffé"/>
					</div>
						<div class="col-sm-2 col-md-3">
							<img class="img-responsive" src="/img/content/image2.jpg" alt="image2 buffé"/>
						</div>
							<div class="col-sm-2 col-md-3">
								<img class="img-responsive" src="/img/content/image3.jpg" alt="image3 buffé"/>
							</div>
								<div class="col-sm-2 col-md-3">
									<img class="img-responsive" src="/img/content/image4.jpg" alt="image4 buffé"/>
								</div>
				</div>
		</div>	<!-- Container matbilder -->
		
		<br class="clear"/>
	
	<div class="container">
		<h2 class="hidden">Prenumerera på veckans lunchmeny</h2>
			<div class="row">
				<hr>
				<form id="contactForm" method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> role="form" data-toggle="validator" >
					
					<div class="text-center">
					<h3>Prenumerera på veckans lunchmeny</h3>
					</div>
					
						<div class="col-md-5">						
							<div class="form-group">
								<label for="name" class="col-md-3">Namn/Företag*</label>
								<div class="col-md-9">
								<input id="name" type="text" name="name" class="form-control" placeholder="Skriv in ditt namn eller företagsnamn" value="<?php if(isset($error)) {echo $name;}?>" required="required" data-error="Ditt namn är obligatoriskt." />
								<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" div class="col-md-3">E-post *</label>
								<div class="col-md-9">
								<input id="email" type="email" name="email" class="form-control" placeholder="Mailadress för att skicka meny till" value="<?php if(isset($error)){echo $email;}?>" required="required" data-error="Din E-postadress är obligatoriskt." />
								<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="col-md-9 col-md-offset-3">
								<div class="form-group">
									<p><strong>* Obligatoriska fält !</strong></p>
								</div>
							</div>
							<div class="col-md-9 col-md-offset-3">
								<div class="form-group">
									<div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha']['publickey'];?>" ></div>
								</div>								
							</div>
						</div>
						
						<div class="col-md-7">							
							<div class="col-md-12">
								<div class="form-group">
								<p>
								SAMTYCKE<br />
								<a href="https://www.datainspektionen.se/dataskyddsreformen/" target="_blank"><span class="gdpr">Allmänna dataskyddsförordningen</span></a> 
								är en ny EU-förordning som ska stärka skyddet av personuppgifter för privatpersoner i hela EU.
								Jag godkänner att Restaurang Karl lagrar min epostadress för att kunna skicka nyhetsbrevet med veckans lunchmeny som kommer ut 
								en gång per vecka. Restaurang Karl delar inte dina personuppgifter med tredjepart” eller motsvarande. Jag kan när som helst ändra mitt samtycke genom att klicka på  
								länken "Avregistrera" som finns i nyhetsbrevet, skicka e-post till info@restaurangkarl.se eller ringa oss på 035-701 20.
								<br />
								<input type="hidden" name="samtycke" value="Nej" />
								<input type="checkbox" name="samtycke" value="Ja" required="required" data-error="Du måste markera rutan, ja jag samtycker."/><strong>Ja jag samtycker. Skicka mig lunchmeny via e-post!</strong>
								<div class="help-block with-errors"></div>
								</p>
								</div>
							</div>
							
							<div class="col-md-4">
								<input id="submit" type="submit" name="submit" class="btn btn-success btn-send" value="Prenumerera" />
							</div>
						</div>	
				</form>				
				
			</div>
	</div> <!-- Container -->
			
</section>
<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>