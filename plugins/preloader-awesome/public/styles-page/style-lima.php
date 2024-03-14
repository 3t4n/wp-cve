<div id="loader" class="pageload-overlay" data-opening="M -18 -26.90625 L -18 86.90625 L 98 86.90625 L 98 -26.90625 L -18 -26.90625 Z M 40 29.96875 C 40.01804 29.96875 40.03125 29.98196 40.03125 30 C 40.03125 30.01804 40.01804 30.03125 40 30.03125 C 39.98196 30.03125 39.96875 30.01804 39.96875 30 C 39.96875 29.98196 39.98196 29.96875 40 29.96875 Z">

	<?php 
		global $post;
		$preloader_awesome_loader_type = carbon_get_post_meta( $post->ID, 'preloader_awesome_loader_type' );
		$preloader_awesome_loader_css_type = carbon_get_post_meta( $post->ID, 'preloader_awesome_loader_css_type' );
		$preloader_awesome_loader_img = carbon_get_post_meta( $post->ID, 'preloader_awesome_loader_img' );
		$preloader_awesome_counter = carbon_get_post_meta( $post->ID, 'preloader_awesome_counter' );
		$preloader_awesome_progress = carbon_get_post_meta( $post->ID, 'preloader_awesome_progress' );
	?>

	<?php if($preloader_awesome_progress == 'yes') { ?>
		<div id="progress"></div>
	<?php } ?>

	<div class="ta-loader-assets">
		<?php 

		if($preloader_awesome_loader_type == 'img') {
			if(!empty($preloader_awesome_loader_img)) { ?>
				<img id="ta-gif" src="<?php echo esc_url($preloader_awesome_loader_img); ?>" alt="">
			<?php }
			else { ?>
				<div id="ta-gif" class="ta-css-load-1"></div>
			<?php } 
		}
		elseif($preloader_awesome_loader_type == 'css') {
			if(!empty($preloader_awesome_loader_css_type)) {
				preloader_awesome_custom_loader_css_page();
			}
			else { ?>
				<div id="ta-gif" class="ta-css-load-1"></div>
			<?php } 
		} ?>

		<?php if($preloader_awesome_counter == 'yes') { ?>
			<div id="progstat"></div>
		<?php } ?>
	</div>

	<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="xMidYMid slice">
		<path d="M -18 -26.90625 L -18 86.90625 L 98 86.90625 L 98 -26.90625 L -18 -26.90625 Z M 40 -25.6875 C 70.750092 -25.6875 95.6875 -0.7500919 95.6875 30 C 95.6875 60.750092 70.750092 85.6875 40 85.6875 C 9.2499078 85.6875 -15.6875 60.750092 -15.6875 30 C -15.6875 -0.7500919 9.2499078 -25.6875 40 -25.6875 Z"/>
	</svg>
</div><!-- /pageload-overlay -->

<?php 
	// loader animation time
	$preloader_awesome_anim_time = carbon_get_post_meta( $post->ID, 'preloader_awesome_anim_time' );
	if(!empty($preloader_awesome_anim_time)) {
		$preloader_awesome_anim_time = $preloader_awesome_anim_time;
	}
	else {
		$preloader_awesome_anim_time = 700;
	}
?>

<script>
(function () {

	var pageWrap = document.getElementById('ta-pageload'),
		pages = pageWrap.querySelector('div.container-pageload'),
		loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : <?php echo intval($preloader_awesome_anim_time); ?>, easingIn : mina.easeinout } );

	loader.show();

	function id(v){ return document.getElementById(v); }
	function loadbar() {
		var ovrl = id("loader"),
			<?php if($preloader_awesome_progress == 'yes') { ?>
				prog = id("progress"),
			<?php } 
			if($preloader_awesome_counter == 'yes') { ?>
				stat = id("progstat"),
			<?php } ?>
			gif = id("ta-gif"),
			img = document.images,
			c = 0,
			tot = img.length;

		if(tot == 0) return doneLoading();

		function imgLoaded(){
			c += 1;

			var perc = ((100/tot*c) << 0) +"%";

			<?php if($preloader_awesome_progress == 'yes') { ?>
				prog.style.width = perc;
			<?php }
			if($preloader_awesome_counter == 'yes') { ?>
				stat.innerHTML = ""+ perc;
			<?php } ?>
			if(c===tot) return doneLoading();
		}
		function doneLoading(){
			//ovrl.style.opacity = 0;
			setTimeout(function () {
				loader.hide();

				<?php if($preloader_awesome_counter == 'yes') { ?>
					stat.style.display = "none";
				<?php }
				if($preloader_awesome_progress == 'yes') { ?>
					prog.style.display = "none";
				<?php } ?>

				gif.style.display = "none";

				classie.removeClass(pages, 'show');
				classie.addClass(pages, 'show');


			}, 2000);
		}
		for(var i=0; i<tot; i++) {
			var tImg     = new Image();
			tImg.onload  = imgLoaded;
			tImg.onerror = imgLoaded;
			tImg.src     = img[i].src;
		}
	}
	document.addEventListener('DOMContentLoaded', loadbar, false);

})();
</script>