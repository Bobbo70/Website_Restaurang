<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.cms.php");
require_once(LIBRARY_PATH . "/class.user.php");

// get ID of the menu to be read
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

// get database user connection
$menu_user = new USER();

// user id session
$stmt = $menu_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

// get database connection
$week_menu = new MENU();

// set ID property of menu to be read
$week_menu->id = $id;

// read the details of menu to be read
$week_menu->readOne();

// header
$title = 'Meny';
require_once(TEMPLATES_PATH . "/header_cms.php");

// read menu button
echo"<div class='container'>
		<div class='row'>
			<div class='col-md-12'>";
				echo "<div class='right-button-margin'>";
					echo "<a href='/cms/index.php' class='btn btn-primary pull-left'>";
						echo "<span class='glyphicon glyphicon-list'></span> Tillbaks till menyer";
					echo "</a>";
				echo "</div>";
		echo"</div>"; // Col md 12
	echo"</div>"; // Row
echo"</div>"; // Container

echo"<div class='container'>
		<div class='row'>
			<div class='col-md-6'>";
				// HTML table for displaying a menu details
				echo "<table class='table table-hover table-responsive table-bordered'>";
				 
					echo "<tr>";
						echo "<td>Vecka</td>";
						echo "<td>{$week_menu->vecka}</td>";
					echo "</tr>";
				 
					echo "<tr>";
						echo "<td>Veckodag</td>";
						echo "<td>{$week_menu->veckodag}</td>";
					echo "</tr>";
				 
					echo "<tr>";
						echo "<td>Alt 1</td>";
						echo "<td>{$week_menu->alt_1}</td>";
					echo "</tr>";
				 
					echo "<tr>";
						echo "<td>Alt 2</td>";
						echo "<td>{$week_menu->alt_2}</td>";
					echo "</tr>";
					
					echo "<tr>";
						echo "<td>Alt veg</td>";
						echo "<td>{$week_menu->alt_veg}</td>";
					echo "</tr>";
					
					echo "<tr>";
						echo "<td>Sallad</td>";
						echo "<td>{$week_menu->alt_sallad}</td>";
					echo "</tr>";
					
					echo "<tr>";
						echo "<td>Pasta</td>";
						echo "<td>{$week_menu->veckans_pasta}</td>";
					echo "</tr>";					
					
					
				echo "</table>";
		echo"</div>"; // Col md 6
	echo"</div>"; // Row menu
echo"</div>"; // Container
?>
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>