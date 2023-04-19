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
$title= "Catering Halmstad - Restaurang Karl";
$metadescription= "Catering | Event i Halmstad - Catering för alla tillfällen, fest/party, bröllop, student, julbord, nyårsmeny, jubileum, företagsfest m.m Kontakta oss för fri offert - Vi fixar det mesta";
$seolink= "http://restaurangkarl.se/catering";
require_once(TEMPLATES_PATH . "/header.php");
?>
<section id="boxcontent">
	<h2 class="hidden">Catering</h2>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="well">
						<h3>Catering - Ni ringer vi kör!</h3>
							<p>Kontakta oss om ni behöver hjälp med maten till er fest. Vi fixar det mesta. 
							Allt ifrån frallor till frukostmötet till att ta hand om hela tillställningen med mat, personal och uppdukning.
							Vill ni ha en kock på plats eller hjälp med serveringen som tar hand om hela eventet så löser vi det. 
							<br />
							Nedan har ni några menyförslag att välja på. Kontakta oss gärna om ni har andra önskemål.
							Behöver ni en lokal för eran tillställning så går det att abonnera restaurangen. Kontakta oss för ett offert.
							<br />
							<br />
							Vi följer folkhälsomyndighetens rekommendationer. 
							Och har därför utfört olika åtgärdar för att du som gäst skall kunde besöka oss på ett säkert sätt. 
							Tillsammans kan vi hjälpas åt för att minimera risken för smittspridning. 
							</p>
					</div>
				</div>
			</div>
		</div> <!-- Container -->		
		
	<!-- Container Julbord 
		<div class="container">
			<h2 class="hidden">Catering Julen 2022</h2>
				<div class="row">
					<div class="col-md-3">
						<img class="img-responsive" src="/img/content/julgranskulor.gif" alt="Julgranskulor"/>
					</div>
					<div class="col-md-9">
						<div class="xmas">
						<h3>JULEN 2022</h3>
							<p>Nu är det jul igen!
							Planerar ni en julfest för personalen, eller en julfest med familjen och goda vänner?
							Hör av Er till oss. Vi har lokalen och den goda julmaten. Vill ni hellre vara på Eran arbetsplats eller hemma så löser vi givetvis det.
							Ring 035-701 20 för info eller skicka ett meddelande via kontakformuläret </br> <a href="/kontakt" title="Kontakt" alt="Kontaktformulär"> Länk till kontakformulär </a></p>
														
							<br />
							
							<h4><strong>Karl store catering julbuffé 299,- kr </h4>
							<p>Minimibeställning 20 portioner</strong><br />
							3 sorter sill, stekt strömming, gubbröra, ägghalvor med räkröra, 
							varmrökt lax, gravad lax, romsås, hovmäsarsås, 
							julskinka, lökkorv, leverpastej, rökt fläskfilé, spickeskinka, 
							rödbetssallad, senap, cheddarost, vörtbröd, knäckebröd, smör.
							Janssons Frestelse, grönkål, rödkål, köttbullar, prinskorv

							<br />
							Lägg till ris à la malta med saftsås 35,- kr
							</p>
							
							<h4><strong>Karls lilla catering julbuffé 199,- kr</h4>
							<p>Minimibeställning 20 portioner</strong><br />
							3 sorter sill, ägghalvor med räkröra, varmrökt lax, romsås,
							julskinka, lökkorv, leverpastej, rödbetssallad, senap, cheddarost, vörtbröd, smör.
							Janssons Frestelse, grönkål, köttbullar, prinskorv

							<br />
							Lägg till ris à la malta med saftsås 35,- kr
							</p>							
							
							<h4>Jultallrik med småvarmt 215,- kr</h4>
							<p>
							3 sorter sill, ägghalvor med räkröra, kallrökt lax, pepparrotskräm,
							julskinka, lökkorv, leverpastej, rödbetssallad, senap, cheddarost, vörtbröd, smör, 
							Janssons Frestelse, grönkål, köttbullar, prinskorv

							<br />
							Lägg till ris à la malta med saftsås 35,- kr
							</p>							
							
							<h4><strong>Julbord hos oss på Restaurang Karl 399,- kr</h4>
							<p>Det går att abonnera restaurangen för Er firmafest. Kontakta oss för offert.</strong><br />
							Glögg, pepparkaka
							6 sorter sill, ägg, 2 sorter lax med tillhörande såser, 6 sorter kallskuret, diverse tillbehör som hör till julbordet. Janssons Frestelse, köttbullar, prinskorv, revben, grönkål, rödkål.
							Ostbricka, frukt, godis, ris à la Malta, saftsås. 
							Kaffe	
	
							</p>
						</div>
					</div>
				</div>
		</div> 
	Container Julbord -->		
		
	<!-- påsk Container 
		
		<div class="container"> 
			<h2 class="hidden">Påskbuffé</h2>
				<div class="row">
					
					<div class="col-md-9">
						<div class="easter">
						<h3>Catering påskbuffé</h3>							
							<p>
							Branteviksill, senapssill, romsill, stekt strömming, ägghalvor, rökt lax, gravad lax, romsås, 
							hovmästersås, rostbiff, örtgriljerad skinka, rökt kalkonbröst, paj, krämig potatissallad, 
							kuvertbröd, fröknäcke, smör.
							Jansson Frestelse, köttbullar, prinskorv
							</p>
							<p class="price">Pris 225,- kr/person. Minimum 8 pers</p>
							<br>
							<br>
							<h3>Catering påsktallrik</h3>							
							<p>
							Branteviksill, senapssill, ägghalvor, skagenröra, lax, rostbiff, örtgriljerad skinka, rökt kalkon, 
							paj, krämig potatissallad, kuvertbröd, fröknäcke, brieost, smör
							</p>
							<p class="price">Pris 165,- kr/person. Minimum 8 pers</p>
							<br>
							<br>
							<p><strong>Vi vill ha din beställning senast 2 dagar innan.<br>
								Får du oväntat besök så ring oss så skall vi se vad vi kan hjälpa till med<br>
								Catering till påsk går att få från och med den 22 mars till den 11 april</strong></p>

						</div>
					</div>
				</div>
		</div> 
		
	 påsk Container -->		
	
			
	<!-- student Container
	
		<div class="container"> 
			<h2 class="hidden">Student</h2>
				<div class="well col-md-7 col-md-offset-2">										
					<div class="text-center">
					<h2>Studentbuffé</h2>							
						<p>
						Ugnsbakad pastramilax, limeyoghurt. <br />
						Flankstek med granatäpple, balsamvinäger & ruccola.<br />
						Caesearsallad med kyckling, bacon, krutonger, parmesan & caesardressing.<br />
						Sommarpotatissallad.<br />
						Melonsallad med fetaost & oliver.<br />
						Focaccia med rosmarin & flingsalt, smör.<br />
						<strong>Dessert:</strong><br />
						Jordgubbsmousse, proseccomousse, choklad & jordgubb<br />
						</p>
						<br />
						<p class="price">Pris 199 kr/person. Minimum 10 pers</p>
					</div>
				</div>
			</div>
		</div> 		
		
	student Container -->
		
		<div class="container">
			<h2 class="hidden">Meny</h2>
				<div class="row">
					<div class="col-md-4">
						<h3>Karls kalla buffé </h3>
						<p>Chilimarinerad kycklingfilé,
						Fläskfilé med örtpesto
						Ugnsbakad lax, romsås, 
						Potatissallad,
						Grönsallad,
						Ananas, Melon, Druvor,
						Pastasallad,
						Brieost, ädelost, smör, bröd.</p>
						<p class="price">Pris 275 kr/person</p>
					</div>
						<div class="col-md-4">
							<h3>Karls lilla buffé</h3>
							<p>Chilimarinerad kycklingfilé,
							Rostbiff,
							Potatissallad,
							Grönsallad,
							Ananas, melon, druvor,
							Brieost,
							Smör, bröd.</p>
							<p class="price">199 kr/person</p>
						</div>						
							<div class="col-md-4">
								<h3>Förslag på varmrätter</h3>
								<p>Fläskfilé Charlemange, potatisgratäng, 
								grönsallad.</p>
								<p class="price">170 kr/person</p>
								<br>
								<p>Ugnsbakad lax, romsås, kokt potatis, 
								grönsallad, sparris och gröna ärtor.</p>
								<p class="price">Kontakta oss för menypris</p>																
							</div>
							
				</div>
		</div> <!-- Container -->
		
		<br class="clear"/>	
		
		<div class="container">
			<h2 class="hidden">Meny</h2>
				<div class="row">
			<!--	<div class="col-md-4">
						<h3>Italiensk buffé</h3>
						<p>Salvia & citronmarinerad kyckling med romansallad, hyvlad parmesan, krutonger<br />
						Fläskfilé med pesto<br />
						Lufttorkad skinka, Salami, Mortadella<br />
						Pastasallad<br />
						Marinerade kronärtskockshjärtan, paprika, zucchini, rödlök <br />
						Marinerade oliver <br />
						Tomat & mozzarellasallad<br />
						Ostbricka med 2 ostar & marmelad<br />
						Focaccia med rosmarin & flingsalt, smör</p>
						<p class="price">255 kr/person</p>
					</div> -->
					
						<!--	<div class="col-md-4">
							<h3>Sommarbuffé</h3>
							<p>Honung & chiliglaserade kamben,
							Kycklingspett,
							Fläskfilé,
							Potatisgratäng,
							Coleslaw,
							Barbecuesås,
							Bearnaisesås,
							Grönsallad,
							Melonsallad,
							Focaccia, smör</p>
							<p class="price">245 kr/person</p>
						</div> -->
							
							<!--
							<div class="col-md-4">
								<h3>Smörgåstårtor</h3>
								<p>Välj storlek mellan 6 – 10 och 15 bitar. </p> <p class="price">75 kr/biten</p> <br />
								<p><strong>Smörgåstårta med fyllning av lax, räkor & ägg</strong><br /> Garneras med kallrökt lax, handskalade räkor och ägg.<br />
								<strong>Smörgåstårta med fyllning av skinka, ananas & bostongurka</strong><br /> Garneras med rökt skinka, ost och köttbullar.<br />
								</p>
							</div>
							-->
				</div>			
		</div>	<!-- Container -->

		<br class="clear"/>
		
		<div class="container">
			<h2 class="hidden">Matbilder</h2>
				<div class="row">
					<div class="col-sm-2 col-md-3">
						<img class="img-responsive" src="/img/content/image1.jpg" alt="image1 buffé"/>
					</div>
						<div class="col-sm-2 col-md-3">
							<img class="img-responsive" src="/img/content/image2.jpg" alt="image2 buffé"/>
						</div>
							<div class="col-sm-2 col-md-3">
								<img class="img-responsive" src="/img/content/image6.jpg" alt="image6 buffé"/>
							</div>
							<!--	<div class="col-sm-2 col-md-3">
									<img class="img-responsive" src="/img/content/image4.jpg" alt="image4 buffé"/>
								</div> -->
				</div>
		</div>	<!-- Container -->
		
		<br class="clear"/>
		
	<div class="container">
		<h2 class="hidden">Catering fest</h2>
			<div class="row">
				<div class="jumbotron">
					<h2>CATERING Ring 035-701 20 för info</h2>
						<p>Vi ordnar maten till student, examen, födelsedagsfesten
						och andra festligheter och sammankomster!
						Avhämtning eller catering.
						Gäller för både företag och privatpersoner. Minsta antal för att beställa catering är 20 personer. Utkörning 300 kr
						</p>
				</div>
			</div>
	</div> <!-- Container -->

</section>
<?php require_once(TEMPLATES_PATH . "/footer.php"); ?>