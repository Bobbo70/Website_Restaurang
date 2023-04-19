<?php
session_start();

// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
require_once(LIBRARY_PATH . "/class.user.php");

// get database connection
$fpass_user = new USER();

// login-logout
if($fpass_user->is_loggedin()!=""){
	$fpass_user->redirect(SITE_PATH. "cms/index.php");
}

// post input
if(isset($_POST['btn-submit'])){
	$email = $_POST['txt_email'];
	
	$stmt = $fpass_user->runQuery("SELECT user_id FROM cms_user WHERE email=:email LIMIT 1");
	$stmt->execute(array(':email'=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);	
	if($stmt->rowCount() == 1){
		
		$id = base64_encode($row['user_id']);
		$code = md5(uniqid(rand()));
		
		$stmt = $fpass_user->runQuery("UPDATE cms_user SET tokenCode=:token WHERE email=:email");
		$stmt->execute(array(':token'=>$code,':email'=>$email));
		
		$message= "
				   Hej , $email
				   <br /><br />
				   Vi har fått en förfrågan på att återställa ditt lösenord, om det var du så är det bara att klicka på bifogade länk, för att återställa ditt lösenord. Om det inte var du så ignorera detta mail.
				   <br /><br />
				   Klicka på följande länk för att återställa ditt lösenord. 
				   <br /><br />
				   <a href='http://www.restaurangkarl.se/admin/resetpass.php?id=$id&code=$code'>Klick här för att återställa ditt lösenord</a>
				   <br />";
		$subject = "Återställning av Lösenord";
		
		$fpass_user->send_mail($email, $subject, $message);
		
		$msg = "<div class='alert alert-success'>
				<button class='close' data-dismiss='alert'>&times;</button>
					Restaurang Karl.se har skickat ett mail till ".$email.".
                    Snälla klick på länken, återställa lösenordet i mailet för att skapa ett nytt lösenord. 
			  	</div>";
	}
	else{
		$msg = "<div class='alert alert-danger'>
				<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Förlåt!</strong>  kunde inte hitta din registrerad e-postadress. 
			    </div>";
	}
}

// header
$title= "Glömt lösenordet";
require_once(TEMPLATES_PATH . "/header_cms.php");
?>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h1 class="hidden">Glömt lösenord</h1>
			<h1><?php echo $title; ?></h1>
				<form class="form-signin" method="post">
							
					<?php
					if(isset($msg)){
						echo $msg;
					}
					else{
						?>
							<div class="alert alert-info">
							Skriv in din e-postadress som du har registrera. Du vill få en länk för att skapa ett nytt lösenord via mail.!
							</div>  
						<?php
					}
					?>
				
					<div class="input-group">
						<span class="input-group-addon">@</span>
						<input type="email" name="txt_email" class="form-control" placeholder="Ange din e-postadress" required />
					</div>
					
					<hr />
					<div class="form-group">
					<button type="submit" name="btn-submit" class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i>&nbsp;Skapa nytt lösenord</button>
					</div>
					<label>Är du här av misstag, Gå tillbaks till logga in ! <a href="login.php"><i class="glyphicon glyphicon-log-in"></i>&nbsp;Logga in</a></label>
					
				</form>				
		</div> <!-- Col md 6 -->
	</div> <!-- Row -->
</div> <!-- Container -->
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>