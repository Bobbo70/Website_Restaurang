<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
require_once(LIBRARY_PATH . "/class.contact.php");
require_once(LIBRARY_PATH . "/class.phpmailer.php");

// get database connection
$reg_form = new FORM();

// contact_form id	
if(empty($_GET['radera_kontakt']) && empty($_GET['code']))
{
	$reg_form->redirect(SITE_PATH. "kontakt/index.php");
}

// get unsubscribe verification code
$code = $_GET['code'];
$stmt = $reg_form->runQuery ("SELECT name,email,tokenCode FROM contact_form WHERE tokenCode=:code");
$stmt->execute(array(':code'=>$code));
$row=$stmt->fetch(PDO::FETCH_ASSOC);

// send mail
if(isset($_POST['btn-del']))
{
	$body = "
			Meddelande från  ".strip_tags($row['name']).".
			<br />
			".strip_tags($row['email'])." Har raderat sina kontakt uppgifter från databasen.
			<br />
			";
	$subject = "Raderat från databas";
	
	$phpmailer = new PHPMAIL();
	$phpmailer->send_mail($subject, $body); 
	
	$id = base64_decode($_GET['radera_kontakt']);
	$reg_form->delete($id);
	$reg_form->redirect(SITE_PATH. "kontakt/index.php?raderat_id");
}

// header
$title= "Avregistrera från kontaktlista";
require_once(TEMPLATES_PATH . "/header.php");	
?>

	<section id="boxcontent">
		<h2 class="hidden">Radera E-post</h2>
			<div class="container">
				<div class="col-md-8">
	
					<?php
					if ($row['tokenCode'] == $code){
							echo "<div class='alert alert-danger'>
								<strong>Är du säker !</strong> på att du vill radera dina kontakt uppgifter från Restaurang Karls kontaktlist? 
								</div>";
					} 
					else if ($row['tokenCode'] != $code){	
							echo "<div class='alert alert-danger'>
								<strong>Hitta inte dina uppgifter!</strong>  
								</div>";
					}
					?>	

					<div class="clearfix"></div>

					<form class="form-horizontal">
						
								<div class="control-group">
									<label class="col-md-6 control-label">Din registrerad E-postaddress är:</label>
										<div class="controls">
											<label class="checkbox">
												<?php echo $row['email']; ?>
											</label>
										</div>
									<label class="col-md-6 control-label">Ditt namn är:</label>
										<div class="controls">
											<label class="checkbox">
												<?php echo $row['name']; ?>
											</label>
										</div>
								</div>
							
					</form>
				
					<hr>
					
					<p>
						<?php
						$stmt = $reg_form->runQuery("SELECT tokenCode FROM contact_form WHERE tokenCode=:code");
						$stmt->execute(array(':code'=>$code));
						$row=$stmt->fetch(PDO::FETCH_ASSOC);
							if($row['tokenCode'] == $code){
						?>
								<form method="post">
									<input type="hidden" name="id" value="<?php echo $row['contact_id']; ?>" />
									<button class="btn btn-large btn-primary" type="submit" name="btn-del"><i class="glyphicon glyphicon-trash"></i> &nbsp; Radera kontakt</button>
									<a href="index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; NEJ</a>
								</form>  
								<?php
							}
							else
							{
							?>
								<a href="index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Restaurang Karl.se</a>
								<?php
							}
							?>
					</p>
				</div>
			</div>
	</section>
</body>
</html>