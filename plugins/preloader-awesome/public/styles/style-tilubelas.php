<div id="loader" class="pageload-overlay" data-opening="m 40,-80 190,0 -305,290 C -100,140 0,0 40,-80 z">

	<?php 
		$preloader_awesome_loader_type_global = carbon_get_theme_option( 'preloader_awesome_loader_type_global' );
		$preloader_awesome_loader_css_type_global = carbon_get_theme_option( 'preloader_awesome_loader_css_type_global' );
		$preloader_awesome_loader_img_global = carbon_get_theme_option( 'preloader_awesome_loader_img_global' );
		$preloader_awesome_counter_global = carbon_get_theme_option( 'preloader_awesome_counter_global' );
		$preloader_awesome_progress_global = carbon_get_theme_option( 'preloader_awesome_progress_global' );
	?>

	<?php if($preloader_awesome_progress_global == 'yes') { ?>
		<div id="progress"></div>
	<?php } ?>

	<div class="ta-loader-assets">
		<?php 

		if($preloader_awesome_loader_type_global == 'img') {
			if(!empty($preloader_awesome_loader_img_global)) { ?>
				<img id="ta-gif" src="<?php echo esc_url($preloader_awesome_loader_img_global); ?>" alt="">
			<?php }
			else { ?>
				<div id="ta-gif" class="ta-css-load-1"></div>
			<?php } 
		}
		elseif($preloader_awesome_loader_type_global == 'css') {
			if(!empty($preloader_awesome_loader_css_type_global)) {
				preloader_awesome_custom_loader_css_global();
			}
			else { ?>
				<div id="ta-gif" class="ta-css-load-1"></div>
			<?php } 
		} ?>

		<?php if($preloader_awesome_counter_global == 'yes') { ?>
			<div id="progstat"></div>
		<?php } ?>
	</div>

	<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none" >
		<path d="m 75,-80 155,0 0,225 C 90,85 100,30 75,-80 z"/>
	</svg>
</div><!-- /pageload-overlay -->

<?php 
	// loader animation time
	$preloader_awesome_anim_time_global = carbon_get_theme_option( 'preloader_awesome_anim_time_global' );
	if(!empty($preloader_awesome_anim_time_global)) {
		$preloader_awesome_anim_time = $preloader_awesome_anim_time_global;
	}
	else {
		$preloader_awesome_anim_time = 1400;
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
			<?php if($preloader_awesome_progress_global == 'yes') { ?>
				prog = id("progress"),
			<?php } 
			if($preloader_awesome_counter_global == 'yes') { ?>
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

			<?php if($preloader_awesome_progress_global == 'yes') { ?>
				prog.style.width = perc;
			<?php }
			if($preloader_awesome_counter_global == 'yes') { ?>
				stat.innerHTML = ""+ perc;
			<?php } ?>
			if(c===tot) return doneLoading();
		}
		function doneLoading(){
			//ovrl.style.opacity = 0;
			setTimeout(function () {
				loader.hide();

				<?php if($preloader_awesome_counter_global == 'yes') { ?>
					stat.style.display = "none";
				<?php }
				if($preloader_awesome_progress_global == 'yes') { ?>
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