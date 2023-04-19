<?php
session_start();

// databas class
require_once(realpath(dirname(__FILE__) . "/resources/config.php"));
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
// get database menu connection
$week_menu = new MENU();

// read menu 
$stmt = $week_menu->runQuery("SELECT * FROM lunchmenu WHERE DATE(datum) = DATE(CURRENT_TIME() + INTERVAL 9 HOUR)");
$stmt->execute();

// header
$title= "Lunch - Take Away - Catering - Restaurang Halmstad - Restaurang karl";
$metadescription= "Restaurang karl ligger gångavstånd från Halmstad stadskärna, nära stadsbiblioteket i halmstad. Lunch måndag till fredag 11:00 till 14:00. Catering för alla tillfällen. Ni ringer vi kör ";
$seolink= "http://restaurangkarl.se";
require_once(TEMPLATES_PATH . "/header.php");
?>
<div id="slideshow" class="carousel slide" data-ride="carousel">
	<h2 class="hidden">Carousel</h2>
		<ol class="carousel-indicators">
			<li data-target="#slideshow" data-slide-to="0" class="active"></li>
			<li data-target="#slideshow" data-slide-to="1"></li>
			<li data-target="#slideshow" data-slide-to="2"></li>
			<li data-target="#slideshow" data-slide-to="3"></li>
		</ol>
		
		<div class="carousel-inner" role="listbox">
			<div class="item active">
				<img src="http://www.restaurangkarl.se/img/slide/slide1.jpg" alt="Dagens lunch">
				<div class="carousel-caption">
					<h3>Lunch</h3>
						<p>I dagens lunch ingår en stor salladsbuffe, hembakat bröd, dryck och kaffe...</p>
						<a class="btn btn-default" href="lunch">Läsa mera</a>
				</div>
			</div>
			<div class="item">
				<img src="img/slide/slide2.jpg" alt="Bröllop">
				<div class="carousel-caption">
					<h3>Bröllopscatering</h3>
						<p>Bröllopsplanering på gång! Vi hjälper gärna till med det...</p> 
						<a class="btn btn-default" href="catering">Läsa mera</a>
				</div>
			</div>
			<div class="item">
				<img src="img/slide/slide3.jpg" alt="Temafest">
				<div class="carousel-caption">
					<h3>Temafester</h3>
							<p>Nyår, påsk, sommar, kräftskiva, halloween, julbord...</p> 
							<a class="btn btn-default" href="catering">Läsa mera</a>
				</div>
			</div>
			<div class="item">
				<img src="img/slide/slide4.jpg" alt="Firmafest">
				<div class="carousel-caption">
					<h3>Firmafest / kalas</h3>
						<p>Vi ordnar maten till student och examen, födelsedagsfesten, sammankomster och andra festligheter!</p>
						<a class="btn btn-default" href="catering">Läsa mera</a>
				</div>
			</div>
		</div>
		<a class="left carousel-control" href="#slideshow" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#slideshow" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
</div>  <!-- Slideshow -->
		
<section id="boxcontent">
	<div class="container">
		<h2 class="hidden">Välkommen</h2>
			<div class="row">
				<div class="col-md-12">
					
					<h3 class="text-center">VÄLKOMMEN TILL RESTAURANGEN MED DET GODA KÖKET<br />
					Öppet måndag - fredag <br /> ÖPPETTIDER kl 11:00 - 13:30 <br /> <strong>Telefon: 035-70120</strong></h3>
				</div>
			</div>
	</div> <!-- Container -->
	
	
	
	<div class="container">
		<div class="row">		
						
			<div class="col-md-4">
			
					<table class="table table-responsive table-no-bordered">
						<?php
						if ($stmt->rowCount()>0 )
						{
							while ($menuRow=$stmt->fetch(PDO::FETCH_ASSOC))
							{
						?>
								<h3>Lunch <?php echo $menuRow['veckodag'] ;?> vecka <?php echo $menuRow['vecka']+0; ?></h3>
									<thead>
										<tr class="gray">
											<th><?php echo $menuRow['veckodag'];?></th>
											<th class="text-right"><?php echo date('d M', strtotime($menuRow['datum'])); ?></th>
										</tr>
									</thead>
															
									<tbody>
										<tr>
											<th class="col-xs-2 col-md-2">Alt 1</th>
											<td class="col-xs-10 col-md-10"><?php echo $menuRow['alt_1'];?></td>
										</tr>
										<tr>
											<th class="col-xs-2 col-md-2">Alt 2</th>
											<td class="col-xs-10 col-md-10"><?php echo $menuRow['alt_2'];?></td>
										</tr>
										<tr>
											<th class="col-xs-2 col-md-2">Vegetarisk</th>
											<td class="col-xs-10 col-md-10"><?php echo $menuRow['alt_veg'];?></td>
										</tr>
										<tr>
											<th class="col-xs-2 col-md-2">Sallad</th>
											<td class="col-xs-10 col-md-10"><?php echo $menuRow['alt_sallad'];?></td>
										</tr>
										<tr>
											<th class="col-xs-2 col-md-2">Pasta</th>
											<td class="col-xs-10 col-md-10"><?php echo $menuRow['veckans_pasta'];?></td>
										</tr>
									</tbody>
							<?php
							}
						}else
							{  
								echo "<h3>Dagens rätt</h3><br /><p>Restaurangen har stängt lördag och söndag<br /> Välkommen tillbaks på måndag.
								  <br /><br />
								  Se nästa veckas <a class='btn btn-default' href='lunch'>Lunch här</p></a>"; 
							} 
							?>						
					</table>						
				
			</div>
			<div class="col-md-4">
				<div class="media">
					<h3>Ekologisk kaffe efter lunchen</h3>
						<div class="media-left">
							<img src="http://www.restaurangkarl.se/img/arvidnordquist/4_instagram.png" class="media-object" alt="Salladsbuffé" width="180px" height="180px">
							<br /><p>Vi försöker så långt det går att tänka på miljön. Nu serverar vi bara kaffe och te som är fairtrade</p>
						</div>
						<div class="media-body">
							<p>Arvid Nordquist tar nu nästa steg i deras hållbarhetsarbete och ställer om från fossilbaserat till växtbaserat material på deras kaffeförpackningar. 
							De nya förpackningarna består till minst 70% av växtbaserat material och halverar koldioxidutsläppet i förhållande till tidigare förpackningar</p>
						</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4 col-lg-4">
				<div class="media">
					<h3 class="media-heading">Catering</h3>
						<div class="media-left">
							<img src="/img/content/image5.jpg" class="media-object" alt="Mingelmat" width="180px" height="180px">
							<br /><a class="btn btn-default" href="catering">Läsa mera</a>
							<br />
							<br />
							
							<!-- Container nyårsmeny
								<p>Klicka här för nyårsmeny</p>
								<!-- Trigger the modal with a button
								<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Nyårsmeny 2021</button>
								<!-- Modal
								<div id="myModal" class="modal" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">

										<!-- Modal content
										<div class="modal-content nyår">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h1 class="modal-title text-center">Nyårsmeny</h1>
											</div>
							
											<div class="modal-body">	
												<H2>FULLBOKAT</H2>
												<p>Avhämtning på nyårsafton mellan kl 11:00-13:00</p>
												<h2>Förrätt</h2>
												<p>Toast Skagen med handskalade räkor och löjrom</p>
												<h2>Varmrätt</h2>
												<p>Helstekt oxfilé med haricots verts, potatisbakelse, <br>kallslagen bearnaisesås och rödvinssås</p>
												<h2>Dessert</h2>
												<p>Chokladpannacotta med hallongrädde</p>
												<hr>
												<p>Menypris: 3-rätter 395 kr / 2-rätter 335 kr </p>
												<hr>
												<p class="phone-black">Vi önskar din beställning på nyårsmenyn senast den 28/12 kl 15:00 <br>
												Maila in din beställning på info@restaurangkarl.se eller ring in din beställning
												<br />Restaurang Karl 035-70120 <br />
												Christian 0708-418477</p>
												<br />
												<p>Hämtas mellan kl 11:00-13:00 den 31/12</p>
							
											</div>
											
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Stäng meny</button>
											</div>
										</div>

									</div>
								</div>
							Container nyårsmeny END -->
						</div>
						
						<div class="media-body">		
							<p>Kontakta oss om ni behöver catering av maten till er fest. Vi fixar det mesta. 
							Allt ifrån frallor till frukostmötet till att ta hand hela tillställningen med mat, personal och uppdukning...</p>
						</div>
				</div>
			</div>
		</div> <!-- Row luncmeny / lunch hos oss / catering info -->
	</div> <!-- Container -->
	
		<br class="clear"/>
	
	<div class="container">	
		<div class="row">
			<h2 class="hidden">Kontakta oss för catering</h2>
				<div class="jumbotron">
					<h2>CATERING Ring 035-701 20 för info</h2>
						<p>Vi ordnar maten till student, examen, födelsedagsfesten
						och andra festligheter och sammankomster!
						Avhämtning eller catering.
						Gäller för både företag och privatpersoner. Minsta antal för att beställa catering är 20 personer.</p>
				</div>
		</div>
	</div> <!-- Container -->
		
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>