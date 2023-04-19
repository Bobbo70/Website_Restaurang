<footer class="cms">
	<h2 class="hidden">Footer</h2>
		<section class="copyright">
		<h3 class="hidden">Copyright notice</h3>
			<div class="wrapper">
				&copy;Copyright 2017 by <a href="http://www.restaurangkarl.se">Restaurang Karl</a> All Rights Reserved.
					<div class="uppdatering">
						<?php			/* senaste uppdatering */
							date_default_timezone_set('Europe/Stockholm');	// Om servertiden inte stämmer.
							print("<p>Uppdaterad &nbsp;");
							setlocale(LC_TIME,'sv_SE'); 
							echo strftime('%d %B %Y</p>',getlastmod()); 
						?>
					</div>
			</div>
		</section>
</footer>

</body>
</html>