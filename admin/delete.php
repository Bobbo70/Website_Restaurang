<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.user.php");

// get database connection
$delete_user = new USER();

// user id session
$stmt = $delete_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

// delete user id
if(isset($_POST['btn-del'])){
	$id = $_GET['radera_id'];
	$delete_user->delete($id);
	if ($userRow['user_type'] != 'admin') {
		header("Location: login.php?raderat");	
		$delete_user->logout();
	}
	else {
		$delete_user->redirect(SITE_PATH. "admin/admin.php?raderat");	
	}
}

// header
$title = "Radera användare";
require_once(TEMPLATES_PATH . "/header_cms.php");
?>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h2 class="hidden">Radera användare</h2>
				
				<div class="alert alert-danger">
					<strong>Är du säker !</strong> på att du vill radera din inloggning som menyadministratör? 
				</div>
					
				<div class="clearfix"></div>
		
				<?php
				if(isset($_GET['radera_id'])){
					?>
						<form class="form-horizontal">
						<?php
						$stmt = $delete_user->runQuery ("SELECT * FROM cms_user WHERE user_id=:id");
						$stmt->execute(array(':id'=>$_GET['radera_id']));
						while($row=$stmt->fetch(PDO::FETCH_BOTH)){
							?>
							<div class="control-group">
								<label class="control-label"><strong>Menyadministratör</strong></label>
									<div class="controls">
										<label class="checkbox"><?php echo $row['name']; ?></label>
										<label class="checkbox"><?php echo $row['email']; ?></label>
									</div>
							</div>
							<?php
						}
						?>
						</form>
					<?php
				}
				?>
				
				<hr>
				
				<p>
					<?php
					if(isset($_GET['radera_id'])){
						?>
							<form method="post">
							<input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>" />
							<button type="submit" name="btn-del" class="btn btn-large btn-primary"><i class="glyphicon glyphicon-trash"></i> &nbsp; JA</button>
							<a href="/admin/admin.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; NEJ</a>
							</form>  
						<?php
					}
					else{
						?>
							<a href="/cms/index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Hem</a>
						<?php
					}
					?>
				</p>
			
		</div> <!-- Col md 6 -->
	</div> <!-- Row -->
</div> <!-- Container -->
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>