<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
require_once(LIBRARY_PATH . "/class.user.php");

// get database connection
$verify_user = new USER();

// get verify code
if(empty($_GET['id']) && empty($_GET['code'])){
	$verify_user->redirect(SITE_PATH. "/admin/login.php");
}

if(isset($_GET['id']) && isset($_GET['code'])){
	$id = base64_decode($_GET['id']);
	$code = $_GET['code'];
	
	$statusY = 'Y';
	$statusN = 'N';
	
	$stmt = $verify_user->runQuery("SELECT user_id,user_status FROM cms_user WHERE user_id=:uid AND tokenCode=:code LIMIT 1");
	$stmt->execute(array(':uid'=>$id, ':code'=>$code));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt->rowCount() > 0){
		
		if($row['user_status']==$statusN){
			$stmt = $verify_user->runQuery("UPDATE cms_user SET user_status=:status WHERE user_id=:uid");
			$stmt->bindparam(':status',$statusY);
			$stmt->bindparam(':uid',$id);
			$stmt->execute();	
			
			$msg = "
				<div class='alert alert-success'>
					Din e-post adress är nu verifierad och ditt konto är aktiverat <a href='/admin/login.php'>Login här</a>
			    </div>
			       ";	
		}
		else{
			$msg = "
		        <div class='alert alert-info'>
				    Ditt Konto är redan aktiverat <a href='/admin/login.php'>Login här</a>
			    </div>
			       ";
		}
	}
	else{
		$msg = "
		    <div class='alert alert-warning'>
				<strong>Förlåt !</strong>  Inget Konto hittas.
			</div>
			   ";
	}	
}

// header
$title= "Verifiera konto";
require_once(TEMPLATES_PATH . "/header_cms.php");
?>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h1><?php echo $title; ?></h1>
			<?php if(isset($msg)) { echo $msg; } ?>
		</div> <!-- Col md 6 -->
	</div> <!-- Row -->
</div> <!-- Container -->
</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>