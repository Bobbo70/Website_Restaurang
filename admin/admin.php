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

if ($userRow['user_type'] != 'admin') {
	header("location: cms/index.php");
}

// random password
$passlength = 10;
$pass = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*'),0,$passlength);

$userlength = 5;
$user = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),0,$userlength);

// verify code
$code = md5(uniqid(rand()));

// validate input form
$name = $email = $user_type ='';

// post input
if($_SERVER["REQUEST_METHOD"] == "POST" ){
	
	if(empty($_POST['name'])) {
		$error[] = "Skriv ditt namn!";
	}
 	else {
		$name = inputfield($_POST['name']);
		if (!preg_match('/^[a-züåöäA-ZÜÅÖÄ\s-]*$/',$name)) {
		$error[] = "Endast bokstaver som namn!";
		}
	}

	if(empty($_POST['email'])) {
		$error[] = "Skriv din E-postadress!";
	}	
	else {
		$email = inputfield($_POST['email']);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error[] = "Du måste ange en riktig E-postadress!";
		}
	}
	
	if(empty($_POST['user_type'])){
		$error[] = "Välj behörighet för konto";
	}
	else {
	$user_type = inputfield($_POST['user_type']);
	}
	
	// check errors before sending
	if(empty($error)){
		
		try
		{
			$stmt = $menu_user->runQuery("SELECT email 
									FROM cms_user
									WHERE email=:email ");
			$stmt->execute(array(':email'=>$email));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);

			if($row['email']==$email) {
				$error[] = "Förlåt, e-postadress är redan använd !";
			}
			else{
				
				if($menu_user->user_register($name,$user,$email,$user_type,$pass,$code)){
				
					$id = $menu_user->lasdID();		
					$key = base64_encode($id);
					$id = $key;
					
					$subject = "Aktivera ditt användarkonto";
					
					$message = "
						Hej $name!
						<br /><br />
						För att göra klart registreringen, klicka på den bifogade länken.
						Detta är en säkerhetsåtgärd för att kontrollera att din e-postadress verkligen är din och ingen annans.
						<br /><br />
						---------------------------------------------------------
						<br />
						E-post: ".$email."<br />
						Användarnamn: ".$user."<br />
						Lösenord: ".$pass."<br />
						---------------------------------------------------------
						<br /><br />
						<a href='http://www.restaurangkarl.se/admin/verify.php?id=$id&code=$code'>Klick HÄR för att aktivera ditt användarkonto </a> 
						<br /><br />
						Om du inte har någon vetskap om ett användarkonto på Restaurang Karl.se, är det möjligt att någon registrerat ett konto med din e-postadress utan ditt samtycke. 
						I sådana fall kan du bortse från detta mail, eller svara på detta mail.
						<br /><br />
						Välkommen!<br />
						Efter att du har aktivera ditt konto kan du ändra ditt lösenord och användarnamn. 
						<br /><br />
						Fungerar inte länken? Kopiera och klistra in den i din webbläsare: http://www.restaurangkarl.se/admin/verify.php?id=$id&code=$code
						<br /><br />
						Med vänliga hälsningar<br/>
						Webmaster<br/>";

					$message = wordwrap($message, 70);
					
					$menu_user->send_userMail($email, $name, $subject, $message);
						http_response_code(200);
						$msg = "
							<div class='alert alert-success'>
							<button class='close' data-dismiss='alert'>&times;</button>
								<strong>Ett aktiveringsmail är skickat till ".$email."!</strong>
							</div>";
					
				}
				else{
					http_response_code(500);
					$msg = "<div class='alert alert-danger'>Opps något gick fel. Kunde inte skicka ditt meddelande.</div>";
				}
			}
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
}
// Sanitize input form
function inputfield($data) {
		$data = trim($data);				// remove white spaces
		$data = stripcslashes($data);		// remove backslashes
		$data = (filter_var($data, FILTER_SANITIZE_STRING));
		return $data;
}

// header
$title= "Användar";
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
	
		<div class="col-md-3">		
			<h2 class="hidden">User info</h2>
				<h3>Dina uppgifter</h3>
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
												
						<br />
						
						<div class="control-group" style="text-align:left;">			
							<?php
							if ($userRow['user_id']==$userRow['user_id']){
								echo "<a class='btn btn-success' href='update.php?uppdatera_id=".$userRow['user_id']."'><i class='glyphicon glyphicon-edit'></i>&nbsp;Ändra</a>"; 
							}	
							?>			
						</div>

					</form>					
		</div> <!-- Col md 4 -->
		
		<div class="col-md-5">
			<h2 class="hidden">User info all</h2>
				<h3>Användare</h3>									
					<table class="table table-responsive table-no-bordered">
						
						<?php
						if(isset($_GET['raderat'])){
							?>
								<div class="alert alert-success">
								<button class="close" data-dismiss="alert">&times;</button>
								Användaren är nu raderat !
								</div>
							<?php
						}
						?>
						
						<?php
						$stmt = $menu_user->read_user();
						$stmt->execute();
						if($stmt->rowCount()>0){
							while($userRow=$stmt->fetch(PDO::FETCH_ASSOC)){
								
							?>
							<tr>
								<td>
								<?php echo $userRow['name']; echo '&nbsp;('; echo $userRow['user_type']; echo')'; ?>
								</td>
								
								<td>
								<?php 
								if($id = $userRow['user_type']=='admin' && $userRow['user_id']=='1') {
									echo'<label type="hidden" ><strong>Huvudkonto !</strong> går inte att radera! </label>';
								}
								else
									echo"<a class='btn btn-danger' href='delete.php?radera_id=".$userRow['user_id']."'><i class='glyphicon glyphicon-trash'></i>&nbsp;Radera</a>";?>
								</td>
							</tr>						
							<?php
								
							}
						}
						?>
					</table>					
		</div> <!-- Col md 4 -->
		
		<div class="col-md-4">
			<h2 class="hidden">Sign-up</h2>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<h3>Lägg till användare</h3>
					
					<?php
					if(isset($error)){
						foreach($error as $error){
						?>
							<div class="alert alert-danger">
								<i class="glyphicon glyphicon-warning-sign"></i>&nbsp;<?php echo $error; ?>
							</div>
						<?php
						}
					}
					else if(isset($msg)) echo $msg;
					?>

					<div class="form-group">
						<label for="name" class="control-label">Namn *</label>
						<input id="name" type="text" name="name" class="form-control" placeholder="Skriv in namnet" value="<?php if(isset($error)){echo $name;}?>" />
					</div>

						<input id="user" type="hidden" name="user" class="form-control" placeholder="Välj ett användarnamn" value="<?php if(isset($error)){echo $user;}?>" />
											
					<div class="form-group">
						<label for="email" class="control-label">E-Post *</label>
						<input id="email" type="email" name="email" class="form-control" placeholder="Skriv in e-postadressen" value="<?php if(isset($error)){echo $email;}?>" />
					</div>
					
					<div class="form-group">
						<label>Välj behörighet för konto *</label>
						<select class="form-control" name="user_type" id="user_type">
							<option value=""></option>
							<option value="user">Användarkonto</option>
							<option value="admin">Administratörkonto</option>
						</select>
					</div>
					
					<p><strong>*</strong>Obligatoriska fält</p>

					<hr>
					
					<div class="form-group">
						<button type="submit" class="btn btn-primary" name="btn-signup">
							<i class="glyphicon glyphicon-open-file"></i>&nbsp;Registrera ny användare
						</button>
					</div>

				</form>				
		</div> <!-- Col md 4 -->
		
	</div> <!-- Row -->
</div> <!-- Container -->				
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>