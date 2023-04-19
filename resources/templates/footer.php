<footer>
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
							echo strftime('%e %B %Y</p>',getlastmod()); 
						?>
					</div>
			</div>
		</section>
				
		<div class="container wrapper">
		<h3 class="hidden">Info/kontakt Restaurang Karl</h3>
			<div class="row">
				<div class="col-md-4 col-md-push-2">
					<div class="fb-page" 
						data-href="https://www.facebook.com/RestaurangKarl" 
						data-width="340" data-small-header="false" 
						data-adapt-container-width="true" 
						data-hide-cover="false" 
						data-show-facepile="true">				
					</div>
				</div>					

					<div class="col-md-4 col-md-push-2">
						<h4>Kontakta oss</h4>
						<p><span class="glyphicon glyphicon-home"></span> Axel Olssons gata 24, 302 27 Halmstad<br>
						<span class="glyphicon glyphicon-phone-alt"></span> Tel. 035-701 20<br>
						
						<span class="glyphicon glyphicon-envelope"></span> info@restaurangkarl.se</p>
					</div>
			</div>
				<div class="container-fluid text-center">
					<a href="#myPagetop" title="Till toppen">
						<span class="glyphicon glyphicon-chevron-up"></span>
					</a>
				</div>			
		</div>
</footer>
	<script>
	$(document).ready(function(){
	  // Add smooth scrolling to all links in navbar + footer link
	  $(".navbar a, footer a[href='#myPagetop']").on('click', function(event) {
		// Make sure this.hash has a value before overriding default behavior
		if (this.hash !== "") {
		  // Prevent default anchor click behavior
		  event.preventDefault();

		  // Store hash
		  var hash = this.hash;

		  // Using jQuery's animate() method to add smooth page scroll
		  // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
		  $('html, body').animate({
			scrollTop: $(hash).offset().top
		  }, 900, function(){
	   
			// Add hash (#) to URL when done scrolling (default click behavior)
			window.location.hash = hash;
		  });
		} // End if
	  });
	  
	  $(window).scroll(function() {
		$(".slideanim").each(function(){
		  var pos = $(this).offset().top;

		  var winTop = $(window).scrollTop();
			if (pos < winTop + 600) {
			  $(this).addClass("slide");
			}
		});
	  });
	})
	</script>
</body>
</html>