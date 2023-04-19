<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.cms.php");
require_once(LIBRARY_PATH . "/class.user.php");

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// set number of records per page
$records_per_page = 5;
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// get database class user connection
$menu_user = new USER();

// user id session
$stmt = $menu_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

// get database class cms connection
$week_menu = new MENU();

// query menu
$stmt = $week_menu->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if value was posted
if($_SERVER["REQUEST_METHOD"] == "POST" ) {
 
    // get database connection
	$week_menu = new MENU();
	
    // set menu id to be deleted
    $week_menu->id = $_POST['object_id'];
	
    // delete the menu
    if($week_menu->delete($id)){
        echo "<div class='alert alert-success'>";
		echo "Meny är raderat.";
		echo "</div>";
    }
	
    // if unable to delete the menu
    else{
        echo "Kunde inte radera meny.";
    }
}

// header
$title = 'Lunchmenyer';
require_once(TEMPLATES_PATH . "/header_cms.php");

echo"<div class='container'>
		<div class='row'>
			<div class='col-md-12'>";
				echo "<div class='right-button-margin'>";
					echo "<a href='/cms/nymeny.php' class='btn btn-default pull-left'>Skapa ny meny</a>";
						
					echo "<p class='blockquote-reverse' style='margin-top:5px;'>";
						echo "Användarsida för lunchmenyer<br> 
							  Här kan uppdatera /ändra / radera och lägga in ny matsedel";
					echo "</p>";
					
				echo "</div>";

					if(isset($_GET['raderat'])){						
						echo"<div class='alert alert-success'>
								<button class='close' data-dismiss='alert'>&times;</button>
								<strong>Lyckad!</strong> användaren är nu raderat !
							</div>";
					}
						
		echo"</div>"; // Col md 12
	echo"</div>"; // Row
echo"</div>"; // Container
					
echo"<div class='container'>
		<div class='row'>
			<div class='col-md-6'>";
				// display the menu if there are any
				if($num>0){
				 
					echo "<table class='table table-hover table-responsive table-bordered'>";
						echo "<thead>";
							echo "<tr class='gray'>";
								echo "<th>Vecka</th>";
								echo "<th>Veckodag</th>";
								echo "<th>Datum</th>";
							echo "</tr>";
						echo "</thead>";
						
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				 
							extract($row);
				 
							echo "<tr class='tr_hover'>";
								echo "<td>{$vecka}</td>";
								echo "<td>{$veckodag}</td>";
								echo "<td>{$datum}</td>";
				 
								echo "<td>";
									// read menu button
									echo "<a href='meny.php?id={$id}' class='btn btn-primary left-margin'>";
										echo "<span class='glyphicon glyphicon-list'></span> Läsa";
									echo "</a>";
									 
									// edit menu button
									echo "<a href='uppdatera.php?id={$id}' class='btn btn-info left-margin'>";
										echo "<span class='glyphicon glyphicon-edit'></span> Ändra";
									echo "</a>";
									 
									// delete menu button
									echo "<a delete-id='{$id}' class='btn btn-danger delete-object'>";
										echo "<span class='glyphicon glyphicon-remove'></span> Radera";
									echo "</a>";
								echo "</td>";
								
							echo "</tr>";

						}

					echo "</table>";

					// the page where this paging is used
					$page_url = "index.php?";
					 
					// count all menu in the database to calculate total pages
					$total_rows = $week_menu->countAll();
					 
					// paging buttons here
					require_once(INCLUDE_PATH . "/paging.php");
				}
				 
				// tell the user there are no menu
				else{
					echo "<div class='alert alert-info'>Ingen lunchmeny hittas.</div>";
					echo "<div class='right-button-margin'>";
					echo "<a href='/cms/index.php' class='btn btn-default pull-left'>Tillbaks till menyer</a>";
					echo "</div>";
				}
		echo"</div>"; // Col md 6
	echo"</div>"; // Row menu
echo"</div>"; // Container
?>


<script>
// JavaScript for deleting menu
$(document).on('click', '.delete-object', function(){
 
    var id = $(this).attr('delete-id');
 
    bootbox.confirm({
        message: "<h4>Är du säker?</h4>",
        buttons: {
            confirm: {
                label: '<span class="glyphicon glyphicon-ok"></span> JA',
                className: 'btn-danger'
            },
            cancel: {
                label: '<span class="glyphicon glyphicon-remove"></span> Nej',
                className: 'btn-primary'
            }
        },
        callback: function (result) {
            if(result==true){
                $.post('index.php', {
                    object_id: id
                }, function(data){
                    location.reload();
                }).fail(function() {
                    alert('Unable to delete.');
                });
            }
        }
    });
 
    return false;
});
</script>

</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>