<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.user.php");

// get database connection
$update_user = new USER();

// user id session
$stmt = $update_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

$id = null;
if ( !empty($_GET['uppdatera_id'])) {
	$id = $_REQUEST['uppdatera_id'];
}
 
if ( null==$id ) {
	$update_user->redirect(SITE_PATH. "index.php");	
}

// post input
if(isset($_POST['btn-update-id'])){

	// keep track post values
	$name = $_POST['name'];
	$user = $_POST['user'];
	$email = $_POST['email'];
	
	// validate input
	$valid = true;
	
	if(empty($_POST['name'])) {
		$error[] = "Skriv ditt namn!";
		$valid = false;
	} 
	else {
		$name = inputfield($_POST['name']);
		if (!preg_match('/^[a-züåöäA-ZÜÅÖÄ\s-]*$/',$name)) {
		$error[] = "Endast bokstaver som namn!";
		$valid = false;
		}
	}
	
	if(empty($_POST['user'])) {
		$error[] = "Skriv ditt användarnamn som du vill ha!";
		$valid = false;
	} 
	else {
		$user = inputfield($_POST['user']);
		if (!preg_match('/^[a-züåöäA-ZÜÅÖÄ0-9\s-]*$/',$user)) {
		$error[] = "Endast bokstaver och siffror som användarnamn!";
		}
	}

	if(empty($_POST['email'])) {
		$error[] = "Skriv din E-postadress!";
		$valid = false;
	}
	else {
		$email = inputfield($_POST['email']);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error[] = "Du måste ange en riktig E-postadress!";
		$valid = false;
		}
	}
	
	if(empty($error)){
		
		if ($valid){				
			$stmt = $update_user->runQuery ("SELECT * FROM cms_user WHERE user = :user");
			$stmt->execute(array(':user'=>$user));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
		
			if($row['user'] == $userRow['user']){
				$valid = true;
			}
			else if ($row['user']==''){
				$valid = true;
			}
			else if($row['user']==$user ){
				$error[] = "Förlåt, användarnamnet är redan använd !";
				$valid = false;
			}
		}	
		if ($valid){				
			$stmt = $update_user->runQuery ("SELECT * FROM cms_user WHERE email = :email");
			$stmt->execute(array(':email'=>$email));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
		
			if ($row['email'] == $userRow['email']){
				$valid = true;
			}
			else if($row['email']==$email ){
				$error[] = "Förlåt, e-postadress är redan använd !";
				$valid = false;
			}					
		}
	}
	if ($valid){
		if ($update_user->user_update($id,$name,$user,$email)){
			$msg_user = "
					<div class='alert alert-success'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Lyckad</strong> uppdatering av dina uppgifter !
					</div>";
		}
	}
}
else{
	$stmt = $update_user->runQuery("SELECT * FROM cms_user WHERE user_id = ?");
	$stmt->execute(array($id));
	$data = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$name = $data['name'];
	$user = $data['user'];
	$email = $data['email'];
}

// change password		
// post input
if(isset($_POST['btn-reset-pass'])){
				
	// keep track post values
	$oldpassword = strip_tags($_POST['oldpassword']);
	$newpass = strip_tags($_POST['newpass']);
	$cpass = strip_tags($_POST['confirm-pass']);
	
	if(password_verify($oldpassword, $userRow['pass'])){
		$_SESSION['user_session'] = $userRow['user_id'];
		
		if($cpass!==$newpass){
			$msg = "<div class='alert alert-danger'>
						<button class='close' data-dismiss='alert'>&times;</button>
						<strong>Förlåt!</strong> Lösenordet är inte identiskt. 
					</div>";
		}
		else{
			$password = password_hash($cpass, PASSWORD_DEFAULT);
			$stmt = $update_user->runQuery("UPDATE cms_user SET pass=:pass WHERE user_id=:uid");
			$stmt->execute(array(':pass'=>$password,':uid'=>$userRow['user_id']));
			
			$msg = "<div class='alert alert-success'>
					<button class='close' data-dismiss='alert'>&times;</button>
					Lösenordet är ändrat.
					</div>";
		}
	}			
	else {
		$msg = "<div class='alert alert-danger'>
					<button class='close' data-dismiss='alert'>&times;</button>
					<strong>Förlåt!</strong> Gammalt lösenord är fel. 
				</div>";	
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
$title= "Inställningar";
require_once(TEMPLATES_PATH . "/header_cms.php");	
?>
<div class="container">
	<div class="row">
		<h2 class="hidden">Uppdatera user info</h2>	
		<div class="col-md-6">
			<form class="form-horizontal" action="update.php?uppdatera_id=<?php echo htmlspecialchars($id)?>" method="post">
				<h3>Ändra dina uppgifter</h3>
				
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
				if(isset($msg_user)) {
					echo $msg_user;
				}
				?>
									
				<div class="form-group">
					<label for="name" class="col-md-3 control-label">Namn</label>
					<div class="col-md-9">
						<input id="name" type="text" name="name" class="form-control" placeholder="Ditt namn" value="<?php echo !empty($name)?$name:'';?>">
					</div>						
				</div>
				
				<div class="form-group">
					<label for="user" class="col-md-3 control-label">Användarnamn</label>
					<div class="col-md-9">
						<input id="user" type="text" name="user" class="form-control" placeholder="Användernamn" value="<?php echo !empty($user)?$user:'';?>">
					</div>
				</div>
				  
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">E-postadress</label>
					<div class="col-md-9">
						<input id="email" type="email" name="email" class="form-control" placeholder="E-postadress" value="<?php echo !empty($email)?$email:'';?>">
					</div>
				</div>
				
				<hr>
				
				<div class="control-group" style="text-align:left;">
					<button type="submit" name="btn-update-id" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i>&nbsp;Ändra dina uppgifter</button>
				</div>
				
			</form>			
		</div> <!-- Col md 6 -->
		
		<div class="col-md-6 ">
			<form class="form-horizontal" action="update.php?uppdatera_id=<?php echo htmlspecialchars($id)?>" method="post">
				<h3>Ändra lösenord</h3>
					
					<?php
					if(isset($msg)){
						echo $msg;
					}
					?>
					<div class="form-group">
					<label for="password" class="col-md-4 control-label">Gammalt lösenord</label>
					<div class="col-md-8">
					<input type="password" name="oldpassword" class="form-control" placeholder="Gammalt lösenord" required />
					</div>
					</div>
					<div class="form-group">
					<label for="password" class="col-md-4 control-label">Nytt lösenord</label>
					<div class="col-md-8">
					<input type="password" name="newpass" class="form-control" placeholder="Nytt lösenord" required />
					</div>
					</div>
					<div class="form-group">
					<label for="password" class="col-md-4 control-label">Bekräfta nytt lösenord</label>
					<div class="col-md-8">
					<input type="password" name="confirm-pass" class="form-control" placeholder="Bekräfta nytt lösenord" required />
					</div>
					</div>
					<hr />
					<button class="btn btn-large btn-primary" type="submit" name="btn-reset-pass"><i class="glyphicon glyphicon-save"></i>&nbsp;Ändra ditt lösenord</button>
			</form>
		</div> <!-- Col md 4 -->
		
	</div> <!-- Row -->
</div> <!-- Container -->
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>