<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.cms.php");
require_once(LIBRARY_PATH . "/class.user.php");
require_once(LIBRARY_PATH . "/class.phpmailer.php");

// get database user connection
$menu_user = new USER();

// user id session
$stmt = $menu_user->read_usersession();
$stmt->execute();
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

$page = isset($_GET['page']) ? $_GET['page'] : 1;
// set number of records per page
$records_per_page = 5;
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

// header
$title = "Lunchmail";
require_once(TEMPLATES_PATH . "/header_cms.php");
require_once(INCLUDE_PATH . "/lunchmail.php");
?>

	<div class="container">
		<h2 class="hidden">Skicka lunchmeny till prenumeranter</h2>	
			<div class="row">  							
				<div class="col-md-6">				
					<h3>Skicka lunchmeny till prenumeranter</h3>
											
					<form id="contactForm" method="post" action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?> role="form" data-toggle="validator">
					
						<!-- Error and confirm message from validering -->
							<?php
							if(isset($error)){
								foreach($error as $error)
								{
							?>
									<div class="alert alert-danger">
										<i class="glyphicon glyphicon-warning-sign"></i>&nbsp;<?php echo $error; ?>
									</div>
									<?php
								}
							}
							else if(isset($msg)) echo $msg;
							?>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="hidden" name="matsedel" class="form-control" 
									value="<?php
									// get database connection
									$week_menu = new MENU();

									// query weekmenu
									$stmt = $week_menu->readAllweeks($from_record_num, $records_per_page);
									$num = $stmt->execute();
									if($num>0){
										$row=$stmt->fetch(PDO::FETCH_ASSOC);{
											extract($row); 
											?>						
											<strong>Lunchmeny för vecka <?php echo strip_tags ($vecka +0); ?> </strong>
											<hr />
										<?php
										}
										?>
										
											<strong>Sallad:</strong> Vi har en dagens sallad varje dag.<br />
											<br /><br />
										<?php	
											$row=$stmt->fetch(PDO::FETCH_ASSOC);{
												extract($row);
											?>
										
												<strong>Veckans pasta: </strong><?php echo strip_tags ($veckans_pasta); ?>
												<hr />
											<?php
											}											
											$stmt->execute();
											if ($stmt->rowCount()>0 ){
												while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
													extract($row);

													echo "<table border='0' cellpadding='0' cellspacing='0'>  
															<tr>
																<th>" . strip_tags ($veckodag) ." " . date('d M', strtotime($datum)) ."</th>
																
															</tr>
															<tr>
																<th align='left'>Alt 1</th>
																<td>" . strip_tags ($alt_1) ."</td>
															</tr>
															<tr>
																<th align='left'>Alt 2</th>
																<td>" . strip_tags ($alt_2) ."</td>
															</tr>
															<tr>
																<th align='left'>Vegetarisk</th>
																<td>" . strip_tags ($alt_veg) ."</td>
															</tr>
															<tr>
																<th align='left'>Sallad</th>
																<td>" . strip_tags ($alt_sallad) ."</td>
															</tr>	
														</table>
														<br />";																			
												}
											}
											
											?>
										"/>
											
										<p>Välj nedan vilken veckomeny som skall skickas i lunchmailet</p><br />
										<h4>Lunchmeny vecka <?php echo strip_tags ($vecka +0); ?> !	</h4>								
										<hr />
										<strong>Veckans pasta: </strong><?php echo strip_tags ($veckans_pasta); ?>
										<hr />
										<?php																						
										$stmt->execute();
										if ($stmt->rowCount()>0 ){
											while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
												extract($row);

												echo "<table border='0' cellpadding='0' cellspacing='0'>  
														<tr>
															<th>" . strip_tags ($veckodag) ." " . date('d M', strtotime($datum)) ."</th>
															
														</tr>
														<tr>
															<th align='left'>Alt 1</th>
															<td>" . strip_tags ($alt_1) ."</td>
														</tr>
														<tr>
															<th align='left'>Alt 2</th>
															<td>" . strip_tags ($alt_2) ."</td>
														</tr>
														<tr>
															<th align='left'>Vegetarisk</th>
															<td>" . strip_tags ($alt_veg) ."</td>
														</tr>
														<tr>
															<th align='left'>Sallad</th>
															<td>" . strip_tags ($alt_sallad) ."</td>
														</tr>															
													</table>
													<br />";																			
											}
										}
										?>
								</div>								
									<?php
										// the page where this paging is used
										$page_url = "veckans_lunchmail.php?";
										 
										// count weekmenu in the database to calculate total pages
										$total_rows = $week_menu->countAllweeks();
										 
										// paging buttons here
										require_once(INCLUDE_PATH . "/paging.php");
									}
										// tell the user there are no menu
										else{
											echo "<div class='alert alert-info'>Ingen lunchmeny hittas.</div>";
										}
									?>	
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="subject" >Ämne *</label>
									<input type="text" name="subject" class="form-control" placeholder="Skriv ett ämne i veckomailet" value="<?php if(isset($error)) {echo $subject;}?>" required="required" data-error="Ange ett änme i veckomailet." />
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="message" >Nyhetsmail *</label>
									<textarea name="message" class="form-control" placeholder="Skriv ett meddelande i veckans lunchmail och välj vilket vecka som skall skickas" rows="5" required="required" data-error="Skriv ett meddelande i veckomailet."><?php if(isset($error)){echo $message;} ?></textarea>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>						
						<div class="row">							
							<div class="col-md-4">
								<input id="submit" type="submit" name="submit" class="btn btn-success btn-send" value="Skicka nyhetsbrev" />
							</div>
						</div>
					</form>					
				</div>
				
				<div class="col-md-6">				
					<h3>Nyhetsbrev är skickat till...!</h3>
					<?php 
						if(isset($msgEpost)){
							foreach($msgEpost as $msgEpost){
									echo $msgEpost;
							}
						}
					?>
				</div>

			</div>
	</div> <!-- Container -->
	
<script src="/js/validator.js"></script>

</section> <!-- boxcontent -->
<?php require_once(TEMPLATES_PATH . "/footer_cms.php"); ?>