<?php
date_default_timezone_set ('Europe/Stockholm');

// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.cms.php");
require_once(LIBRARY_PATH . "/class.user.php");

// get database user connection
$menu_user = new USER();

// user id session
$stmt = $menu_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

// get database connection
$week_menu = new MENU();

// header
$title = "Skapa ny lunchmeny";
require_once(TEMPLATES_PATH . "/header_cms.php");

echo"<div class='container'>
		<div class='row'>
			<div class='col-md-12'>";
				echo "<div class='right-button-margin'>";
					echo "<a href='/cms/index.php' class='btn btn-default pull-left'>Tillbaks till menyer</a>";
				echo "</div>";
		
				// send menu
				if($_SERVER["REQUEST_METHOD"] == "POST" ) {
					// set menu property values
					$weekNumber = $_POST['vecka'];
					$veckodag = $_POST['veckodag'];
					$alt_1 = $_POST['alt_1'];
					$alt_2 = $_POST['alt_2'];
					$alt_veg = $_POST['alt_veg'];
					$sallad = $_POST['alt_sallad'];
					$pasta = $_POST['veckans_pasta'];					

					if (!preg_match('/^[0-9]{2}$/',$weekNumber)) {
						echo "<div class='alert alert-danger'>
								<i class='glyphicon glyphicon-warning-sign'></i>&nbsp;Veckonummer måste vara i 2 siffror (01-53) !
							 </div>";
					}
					else{
						
						try
						{	
							$datum=strtotime("Monday", strtotime(date("Y")."W".$weekNumber ));
							// create the menu
							if($week_menu->register($weekNumber,$datum,$veckodag,$alt_1,$alt_2,$alt_veg,$sallad,$pasta)){
								echo "<div class='alert alert-success'>Ny lunchmeny är skapad.</div>";
							}
							// if unable to create the menu, tell the user
							else{
								echo "<div class='alert alert-danger'>Kunde inte skapa ny lunchmeny.</div>";
							}
						}
						catch(PDOException $ex){
							echo $ex->getMessage();
						}
					}
				}

		echo"</div>"; // Col md 12
	echo"</div>"; // Row
echo"</div>"; // Container
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			 	<table class="table table-responsive table-bordered">
					
					<thead>
						<tr class="gray">
							<td class="col-md-2">Lunchmeny för vecka:</td>
							<td><input type="text" name="vecka" id="vecka" class="form-control" placeholder="Skriv veckonummer med två siffror (01-53)" /></td>
						</tr>
					</thead>
					
					<tr>
						<td>Veckans pasta</td>
						<td><input type="text" name="veckans_pasta" class="form-control" /></td>
					</tr>
					
					<tr>
						<td>Veckodag</td>
						<td><input type="hidden" name="veckodag[]" value="Måndag" readonly />Måndag</td>
					</tr>
							
					<tr>
						<td>Måndag Alt 1</td>
						<td><input type="text" name="alt_1[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Måndag Alt 2</td>
						<td><input type="text" name="alt_2[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Måndag vegetarisk</td>
						<td><input type="text" name="alt_veg[]" class="form-control" /></td>
					</tr>
					<tr>
						<td>Måndag Sallad</td>
						<td><input type="text" name="alt_sallad[]" class="form-control" /></td>
					</tr>
					
					<tr>
						<td class="col-md-2">Veckodag</td>
						<td><input type="hidden" name="veckodag[]" value="Tisdag" readonly />Tisdag</td>
					</tr>
					<tr>
						<td>Tisdag Alt 1</td>
						<td><input type="text" name="alt_1[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Tisdag Alt 2</td>
						<td><input type="text" name="alt_2[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Tisdag vegetarisk</td>
						<td><input type="text" name="alt_veg[]" class="form-control" /></td>
					</tr>
					<tr>
						<td>Tisdag sallad</td>
						<td><input type="text" name="alt_sallad[]" class="form-control" /></td>
					</tr>
					
					<tr>
						<td class="col-md-2">Veckodag</td>
						<td><input type="hidden" name="veckodag[]" value="Onsdag" readonly />Onsdag</td>
					</tr>
					<tr>
						<td>Onsdag Alt 1</td>
						<td><input type="text" name="alt_1[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Onsdag Alt 2</td>
						<td><input type="text" name="alt_2[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Onsdag vegetarisk</td>
						<td><input type="text" name="alt_veg[]" class="form-control" /></td>
					</tr>
					<tr>
						<td>Onsdag sallad</td>
						<td><input type="text" name="alt_sallad[]" class="form-control" /></td>
					</tr>
					
					<tr>
						<td class="col-md-2">Veckodag</td>
						<td><input type="hidden" name="veckodag[]" value="Torsdag" readonly />Torsdag</td>
					</tr>
					<tr>
						<td>Torsdag Alt 1</td>
						<td><input type="text" name="alt_1[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Torsdag Alt 2</td>
						<td><input type="text" name="alt_2[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Torsdag vegetarisk</td>
						<td><input type="text" name="alt_veg[]" class="form-control" /></td>
					</tr>
					<tr>
						<td>Torsdag sallad</td>
						<td><input type="text" name="alt_sallad[]" class="form-control" /></td>
					</tr>
					
					<tr>
						<td class="col-md-2">Veckodag</td>
						<td><input type="hidden" name="veckodag[]" value="Fredag" readonly />Fredag</td>
					</tr>
					<tr>
						<td>Fredag Alt 1</td>
						<td><input type="text" name="alt_1[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Fredag Alt 2</td>
						<td><input type="text" name="alt_2[]" class="form-control" /></td>
					</tr> 
					<tr>
						<td>Fredag vegetarisk</td>
						<td><input type="text" name="alt_veg[]" class="form-control" /></td>
					</tr>
					<tr>
						<td>Fredag sallad</td>
						<td><input type="text" name="alt_sallad[]" class="form-control" /></td>
					</tr>
					
					<tr>
						<td></td>
						<td>
							<button type="submit" class="btn btn-primary">Skapa meny</button>
						</td>
					</tr>
			 
				</table>
			</form>
		</div> <!-- Col md 12 -->
	</div> <!-- Row menu -->
</div> <!-- Container -->
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>