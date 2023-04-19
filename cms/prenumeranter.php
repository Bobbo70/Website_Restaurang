<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.cms.php");
require_once(LIBRARY_PATH . "/class.user.php");
require_once(LIBRARY_PATH . "/class.contact.php");

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// set number of records per page
$records_per_page = 10;
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// get database class user connection
$menu_user = new USER();

// user id session
$stmt = $menu_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

// get database class contact connection
$subscribers = new FORM();

// query subscribers
$stmt = $subscribers->contactNewsletter($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if value was posted
if($_SERVER["REQUEST_METHOD"] == "POST" ) {
 
    // set subscribers id to be deleted
	$subscribers->id = $_POST['object_contactid'];

    // delete the subscribers
	if($subscribers->deleteSubscriber($id)){
        echo "<div class='alert alert-success'>";
		echo "Prenumerant är raderat.";
		echo "</div>";
    }
    // if unable to delete the subscribers
    else{
        echo "Kunde inte radera Prenumerant.";
    }
}

// header
$title = "Prenumeranter";
require_once(TEMPLATES_PATH . "/header_cms.php");

echo"<div class='container'>
		<div class='row'>
			<div class='col-md-10'>";
				// display the subscribers if there are any
				if($num>0){
					
					echo "<table class='table table-hover table-responsive table-bordered'>";
						echo "<thead>";
							echo "<tr class='gray'>";
								echo "<th>Id</th>";
								echo "<th>Namn</th>";
								echo "<th>E-post</th>";
							echo "</tr>";
						echo "</thead>";
						
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				 
							extract($row);
							
						echo "<tr class='tr_hover'>";
							echo "<td>{$id}</td>";
							echo "<td>{$name}</td>";
							echo "<td>{$email}</td>";
			 
							echo "<td>";
								// delete subscribers button
								echo "<a delete-id='{$id}' class='btn btn-danger delete-object'>";
									echo "<span class='glyphicon glyphicon-remove'></span> Radera";
								echo "</a>";
							echo "</td>";
						echo "</tr>";
						
						}
						
					echo "</table>";
				
					// the page where this paging is used
					$page_url = "prenumeranter.php?";
					 
					// count all subscribers in the database to calculate total pages
					$total_rows = $subscribers->countAll();
					 
					// paging buttons here
					require_once(INCLUDE_PATH . "/paging.php");
				}
				
		echo"</div>"; // Col md 6
	echo"</div>"; // Row subscribers
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
                $.post('prenumeranter.php', {
                    object_contactid: id
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