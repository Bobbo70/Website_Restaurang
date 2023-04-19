<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
require_once(LIBRARY_PATH . "/class.user.php");

// get database connection
$reset_user = new USER();

// get verify code
if(empty($_GET['id']) && empty($_GET['code'])){
	$reset_user->redirect(SITE_PATH. "admin/login.php");
}

if(isset($_GET['id']) && isset($_GET['code'])){
	$id = base64_decode($_GET['id']);
	$code = $_GET['code'];
	
	$stmt = $reset_user->runQuery("SELECT * FROM cms_user WHERE user_id=:uid AND tokenCode=:token");
	$stmt->execute(array(':uid'=>$id,':token'=>$code));
	$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if($stmt->rowCount() == 1){
		
		if(isset($_POST['btn-reset-pass'])){
			$pass = $_POST['pass'];
			$cpass = $_POST['confirm-pass'];
			
			if($cpass!==$pass){
				$msg = "<div class='alert alert-block'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Förlåt!</strong> Lösenordet är inte identiskt. 
						</div>";
			}
			else {
				$password = password_hash($cpass, PASSWORD_DEFAULT);
				$stmt = $reset_user->runQuery("UPDATE cms_user SET pass=:pass WHERE user_id=:uid");
				$stmt->execute(array(':pass'=>$password,':uid'=>$rows['user_id']));
				
				$msg = "<div class='alert alert-success'>
						<button class='close' data-dismiss='alert'>&times;</button>
						Lösenordet ändrat.
						</div>";
				header("refresh:5;/admin/login.php");
			}
		}	
	}
	else {
		$msg = "<div class='alert alert-success'>
				<button class='close' data-dismiss='alert'>&times;</button>
				Inget konto hittas, försök igen.
				</div>";		
	}
}

// header
$title= "Verifiera konto";
require_once(TEMPLATES_PATH . "/header_cms.php");
?>
<div class="container">
	<div class="row">
		<div class="col-md-6">	
			<h2 class="hidden">Återställ lösenord</h2>
		
				<div class="alert alert-success">
					<button class="close" data-dismiss="alert">&times;</button>
					<strong>Hej !</strong>  <?php echo $rows['name'] ?> Du är här för att återställa ditt lösenord som du har glömt.
				</div>
				
					<form class="form-signin" method="post">
						<h3 class="form-signin-heading">Återställa lösenord.</h3><hr />
						
						<?php
						if(isset($msg)){
							echo $msg;
						}
						?>
						
						<div class="form-group">
						<input type="password" name="pass" class="form-control" placeholder="Nytt Lösenord" required />
						</div>
						<div class="form-group">
						<input type="password" name="confirm-pass" class="form-control" placeholder="Bekräfta Nytt Lösenord" required />
						</div>
						<hr />
						<button class="btn btn-large btn-primary" type="submit" name="btn-reset-pass">Återställa ditt lösenord</button>
					</form>
					
		</div> <!-- Col md 6 -->
	</div> <!-- Row -->
</div> <!-- Container -->
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>