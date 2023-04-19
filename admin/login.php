<?php
session_start();

// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
require_once(LIBRARY_PATH . "/class.user.php");

// get database connection
$user_Login = new USER();

// login-logout
if($user_Login->is_loggedin()!=""){
	$user_Login->redirect(SITE_PATH. "cms/index.php");
}

if(isset($_POST['btn-login'])){
	$user = strip_tags($_POST['txt_uname_email']);
	$email = strip_tags($_POST['txt_uname_email']);
	$pass = strip_tags($_POST['txt_password']);
		
	if($user_Login->login($user,$email,$pass)){
		$user_Login->redirect(SITE_PATH. "cms/index.php");
	}
}

// header
$title= 'Logga in';
require_once(TEMPLATES_PATH . "/header_cms.php");
?>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h1 class="hidden">Loginform</h1>
				<h1><?php echo $title; ?></h1>
				<?php
				if(isset($_GET['raderat'])){
					?>
						<div class="alert alert-success">
						<button class="close" data-dismiss="alert">&times;</button>
						<strong>Lyckad!</strong> din inloggning är nu raderat!</a>
						</div>
					<?php
				}
				
				if(isset($_GET['logout']))
				{ 	
					?>
					<div class="alert alert-danger">
					<a class="close" data-dismiss="alert">&times;</a>
					Du är nå utloggad från menysidan!.
					</div>
					<?php
				}
				
				if(isset($_GET['inactive'])){
					?>
						<div class="alert alert-warning">
							<button class="close" data-dismiss="alert">&times;</button>
							<strong>Förlåt!</strong> Detta konto är inte aktiverad! Gå till din E-post för att aktivera ditt konto. 
						</div>
					<?php
				}
				?>
						
				<form class="form-signin" method="post" id="login-form">
									
					<?php
						if(isset($_GET['error'])){
							?>
								<div class="alert alert-danger">
									<i class="glyphicon glyphicon-warning-sign"></i>&nbsp;
									<strong>Fel användarnamn eller lösenord! Försök igen!</strong> 
								</div>
							<?php
						}
					?>   
					
					<div class="form-group">
					<input type="text" name="txt_uname_email" class="form-control" placeholder="Ange användarnamn eller e-postadress" required />
					<span id="check-e"></span>
					</div>
					
					<div class="form-group">
					<input type="password" name="txt_password" class="form-control" placeholder="Ditt lösenord" required />
					</div>
				   
					<div class="form-group">
						<button type="submit" name="btn-login" class="btn btn-default">
							<i class="glyphicon glyphicon-log-in"></i> &nbsp; Logga in
						</button>
					</div>
					
					<label><a href="fpass.php">Glömt lösenordet ?</a></label>
					
				</form>			
		</div> <!-- Col md 6 -->
	</div> <!-- Row -->
</div> <!-- Container -->
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>