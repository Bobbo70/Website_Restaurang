<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.cms.php");
require_once(LIBRARY_PATH . "/class.user.php");

// get ID of the menu to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

// get database user connection
$menu_user = new USER();

// user id session
$stmt = $menu_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

// get database connection
$week_menu = new MENU();

// set ID property of menu to be edited
$week_menu->id = $id;
 
// read the details of menu to be edited
$week_menu->readOne();

// header
$title = "Ändra lunchmeny";
require_once(TEMPLATES_PATH . "/header_cms.php");

echo"<div class='container'>
		<div class='row'>
			<div class='col-md-12'>";
				echo "<div class='right-button-margin'>";
					echo "<a href='/cms/index.php' class='btn btn-default pull-left'>Tillbaks till menyer</a>";
				echo "</div>";

					// if the form was submitted
					if($_SERVER["REQUEST_METHOD"] == "POST" ) {
						// set menu property values
						$weekNumber = $_POST['vecka'];
						$veckodag = $_POST['veckodag'];
						$alt_1 = $_POST['alt_1'];
						$alt_2 = $_POST['alt_2'];
						$alt_veg = $_POST['alt_veg'];
						$sallad = $_POST['alt_sallad'];
						$pasta = $_POST['veckans_pasta'];						
					 
						// update the menu
						if($week_menu->updatemenu($id,$weekNumber,$veckodag,$alt_1,$alt_2,$alt_veg,$sallad,$pasta)){
							echo "<div class='alert alert-success alert-dismissable'>";
								echo "Dagens rätt är uppdaterad.";
							echo "</div>";
						}
						// if unable to update the menu, tell the user
						else{
							echo "<div class='alert alert-danger alert-dismissable'>";
								echo "Något blev fel, kunde inte uppdatera dagens rätt.";
							echo "</div>";
						}
					}
					
		echo"</div>"; // Col md 12
	echo"</div>"; // Row
echo"</div>"; // Container
?>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
				<table class='table table-hover table-responsive table-bordered'>
			 
					<tr>
						<td>Vecka</td>
						<td><input type="text" name="vecka" value="<?php echo $week_menu->vecka; ?>" class="form-control" /></td>
					</tr>
			 
					<tr>
						<td>Veckodag</td>
						<td><input type="text" name="veckodag" value="<?php echo $week_menu->veckodag; ?>" class="form-control" /></td>
					</tr>
			 
					<tr>
						<td>Alt 1</td>
						<td><input type="text" name="alt_1" value="<?php echo $week_menu->alt_1; ?>" class="form-control" /></td>
					</tr>
			 
					<tr>
						<td>Alt 2</td>
						<td><input type="text" name="alt_2" value="<?php echo $week_menu->alt_2; ?>" class="form-control" /></td>
					</tr>
					
					<tr>
						<td>Alt veg</td>
						<td><input type="text" name="alt_veg" value="<?php echo $week_menu->alt_veg; ?>" class="form-control" /></td>
					</tr>
					
					<tr>
						<td>Sallad</td>
						<td><input type="text" name="alt_sallad" value="<?php echo $week_menu->alt_sallad; ?>" class="form-control" /></td>
					</tr>
					
					<tr>
						<td>Pasta</td>
						<td><input type="text" name="veckans_pasta" value="<?php echo $week_menu->veckans_pasta; ?>" class="form-control" /></td>
					</tr>
					
					<tr>
						<td></td>
						<td>
							<button type="submit" class="btn btn-primary">Uppdatera</button>
						</td>
					</tr>
			 
				</table>
			</form>
		</div> <!-- Col md 6 -->
	</div> <!-- Row menu -->
</div> <!-- Container -->
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>