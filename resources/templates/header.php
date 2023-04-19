<!DOCTYPE HTML>
<html lang="sv">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	
	<title><?php echo $title; ?></title>
	<meta name="keywords" content="Halmstad, Lunch, Take Away, Catering">
	<meta name="description" content="<?php echo $metadescription; ?>" /> 
	<link rel="canonical" href="<?php echo $seolink; ?>" />
	
	<link rel="shortcut icon" href="http://www.restaurangkarl.se/favicon.ico" />
	
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"/>
	
	<link rel="stylesheet" href="/css/reset.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen"/>
	
	
	<script src="/js/modernizr.min.js"></script>
	<script src="/js/respond.min.js"></script>
	<script src="/js/prefixfree.min.js"></script>
	
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- include extern jQuery file but fall back to local file if extern one fails to load !-->
<script type="text/javascript">window.jQuery || document.write('<script type="text\/javascript" src="/js/jquery-3.4.1.min.js"><\/script>')</script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="/js/validator.js"></script>
</head>

<body id="myPagetop">

	<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "https://connect.facebook.net/sv_SE/sdk.js#xfbml=1&version=v2.12";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>
			
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
						<a class="navbar-brand" href="/"><img src="http://www.restaurangkarl.se/img/layout/loggo.gif" width="350" alt="Loggo Restaurang Karl"> </a>
						
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
							<li><a href="/" title="Hem">Hem</a></li>
							<li><a href="/lunch" title="Lunchmeny">Lunchmeny</a></li>
							<li><a href="/catering" title="Catering">Catering</a></li>
							<li><a href="/kontakt" title="Kontakt">Kontakt</a></li>
							<li><a href="http://facebook.com/restaurangkarl" title="Facebook Restaurang Karl" target="_blank"><img src="/img/content/facebook.png" width="25" height="25" alt="Facebook restaurang karl"/></a></li>
						</ul>
					</div>
				</div>
			</nav>
	</header>