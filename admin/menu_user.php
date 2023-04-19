<?php
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

// header
$title= 'Din sida';
require_once(TEMPLATES_PATH . "/header_cms.php");
?>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<?php
			if (isset($_SESSION['success'])){
				echo $_SESSION['success'];
				unset($_SESSION['success']);
			}
			?>
		</div>
	</div>
	<div class="row">
	
		<div class="col-md-4">
		
			<h2 class="hidden">User info</h2>
				<h3>Dina registrerade uppgifter</h3>
						
					<form class="form-horizontal">
					
						<div class="control-group">
							<label class="control-label"><strong>Namn:</strong></label>
								<div class="controls">
									<label class="checkbox"><?php echo $userRow['name']; ?></label>
								</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><strong>Användarnamn:</strong></label>
								<div class="controls">
									<label class="checkbox"><?php echo $userRow['user'];?></label>
								</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><strong>E-postadress:</strong></label>
								<div class="controls">
									<label class="checkbox"><?php echo $userRow['email'];?></label>
								</div>
						</div>
												
						<hr>
						
						<div class="control-group" style="text-align:left;">			
							<?php
							if ($userRow['user_id']==$userRow['user_id']){
								echo "<a class='btn btn-success' href='update.php?uppdatera_id=".$userRow['user_id']."'><i class='glyphicon glyphicon-edit'></i>&nbsp;Ändra</a>"; 
							}	
							?>			
						</div>
						
					</form>					
		</div> <!-- Col md 4 -->
		
		<div class="col-md-4">
			<h2 class="hidden">User info all</h2>
				<h3>Användare</h3>									
					<table class="table table-responsive table-no-bordered">
						
						<?php
						$stmt->execute();
						if($stmt->rowCount()>0){
							$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
							?>
							<tr>
								<td>
								<?php echo $userRow['name']; echo '&nbsp;<i>('; echo $userRow['user_type']; echo'</i>)'; ?>
								</td>
								
								<td>
								<?php 
									echo"<a class='btn btn-danger' href='delete.php?radera_id=".$userRow['user_id']."'><i class='glyphicon glyphicon-trash'></i>&nbsp;Radera</a>";?>
								</td>
							</tr>						
							<?php
						}
						?>
					</table>					
		</div> <!-- Col md 4 -->
		
	</div> <!-- Row -->
</div> <!-- Container -->				
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>