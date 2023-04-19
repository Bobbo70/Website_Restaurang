<!DOCTYPE HTML>
<html lang="sv">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	
	<title><?php echo $title; ?></title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"/>
	
	<link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen"/>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans/Baumans" rel="stylesheet" type="text/css"/>
	
	<script src="/js/modernizr.min.js"></script>
	<script src="/js/respond.min.js"></script>
	<script src="/js/prefixfree.min.js"></script>
	
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- include extern jQuery file but fall back to local file if extern one fails to load !-->
<script type="text/javascript">window.jQuery || document.write('<script type="text\/javascript" src="/js/jquery-3.2.1.min.js"><\/script>')</script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- bootbox library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
</head>

<body>

	<header>
	<?php 
	// hämtar filnamn till aktuell sida 
	$current_file= basename($_SERVER['PHP_SELF']);
	?>
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainMenu">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<h1 class="hidden">Restaurang Karl</h1>
						<a class="navbar-brand" href="/"><img src="/img/layout/loggo.gif" width="350" alt="Loggo Restaurang Karl"></a>
					</div>
					<div class="collapse navbar-collapse" id="mainMenu">
						<ul class="nav navbar-nav navbar-right">
							<?php 
							echo"<li>";
								if(isset($_SESSION['user_session'])){ 
									echo "<a id='aktuell' class='dropdown-toggle' data-toggle='dropdown' title='Admin hem' ";
									echo "><span class='glyphicon glyphicon-user'></span>"; 
									echo " "; echo($userRow['name']);echo"<b class='caret'></b></a>";}
								echo"<ul class='dropdown-menu'>
										<li>";
											if(isset($_SESSION['user_session'])) {
												if ($userRow['user_type'] == 'admin') {
													echo"<a href='/admin/admin.php' title='Admin' ";
													if($current_file==basename('/admin.php')) {
													echo"id='aktuell' ";}
													echo"><span class='glyphicon glyphicon-cog'></span>&nbsp;Admin</a>"; 
												}
												else {
													echo"<a href='/admin/menu_user.php' title='Din sida' ";
													if($current_file==basename('/menu_user.php')) {
													echo"id='aktuell' ";}
													echo"><span class='glyphicon glyphicon-cog'></span>&nbsp;Din sida</a>"; 
												}
											}											
									echo"</li>									
										<li>";
											if(isset($_SESSION['user_session'])) {
											echo"<a href='/admin/logout.php?logout=true' title='Logga ut'>";
											echo"<span class='glyphicon glyphicon-log-out'></span>&nbsp;Logga ut</a>"; } 
									echo"</li>
									</ul>
								</li>";
							?>							
							<li><a href="/" title="Hem">Restaurang karl.se</a></li>							
						</ul>
					</div>
				</div>
			</nav>
	</header>
	
<!-- boxcontent -->
<section id="boxcontent">
	<div class="container">
		<h2 class="hidden">Admin menu</h2>
			<div class="row">
				<div class="col-md-12">
					<?php
					// show page header
					if(isset($_SESSION['user_session'])) {	
					echo "<nav class='cms-header navbar-inverse'>
							<div class='navbar-header'>
								<button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#cmsMenu'>
									<span class='icon-bar'></span>
									<span class='icon-bar'></span>
									<span class='icon-bar'></span>
								</button>										
								<h1>{$title}</h1>
							</div>
							<div class='collapse navbar-collapse' id='cmsMenu'>
								<ul class='nav navbar-nav cms-menu navbar-right'>";																						
									echo"<li><a href='/cms/index.php' ";
									if($current_file==basename('/index.php')) {
									echo"id='aktuell' ";}
									echo"><span class='glyphicon glyphicon-home'></span>&nbsp;Menyer</a> </li>";
									
									echo"<li><a href='/cms/veckans_lunchmail.php' "; 
									if($current_file==basename('/veckans_lunchmail.php')) {
									echo"id='aktuell' ";}
									echo"><span class='glyphicon glyphicon-envelope'></span>&nbsp;Nyhetsbrev</a> </li>";
									
									echo"<li><a href='/cms/prenumeranter.php' "; 
									if($current_file==basename('/prenumeranter.php')) {
									echo"id='aktuell' ";}
									echo"><span class='glyphicon glyphicon-pencil'></span>&nbsp;Prenumeranter</a> </li>";
									
									if ($userRow['user_type'] == 'admin') {
									echo"<li><a href='/admin/admin.php' ";
									if($current_file==basename('/admin.php')) {
									echo"id='aktuell' ";}
									echo"><span class='glyphicon glyphicon-cog'></span>&nbsp;Admin</a> </li>";
									}
									else {
										echo"<li><a href='/admin/menu_user.php' ";
										if($current_file==basename('/menu_user.php')) {
										echo"id='aktuell' ";}
										echo"><span class='glyphicon glyphicon-cog'></span>&nbsp;Din sida</a> </li>";
									}
									
									echo"<li><a href='/admin/logout.php?logout=true' title='Logga ut'>";
									echo"<span class='glyphicon glyphicon-log-out'></span>&nbsp;Logga ut</a></li>"; 
								echo "</ul>
							</div>
						</nav>";
					}
					?>
				</div>
			</div>
	</div> <!-- Container -->