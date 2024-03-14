<div id="loader" class="pageload-overlay def">
	
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
	
</div><!-- /pageload-overlay -->

<script>
	(function(){
	var pageWrap = document.getElementById('ta-pageload'),
		pages = pageWrap.querySelector('div.container-pageload'),
		loaderTA = document.getElementById('loader');

	loaderTA.style.visibility = "visible";
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
			ovrl.style.opacity = 0;
			setTimeout(function(){
				<?php if($preloader_awesome_counter_global == 'yes') { ?>
					stat.style.display = "none";
				<?php }
				if($preloader_awesome_progress_global == 'yes') { ?>
					prog.style.display = "none";
				<?php } ?>
				ovrl.style.display = "none";
				loaderTA.style.visibility = "hidden";
				classie.removeClass(pages, 'show');
				classie.addClass(pages, 'show');
			}, 1200);
		}
		for(var i=0; i<tot; i++) {
			var tImg     = new Image();
			tImg.onload  = imgLoaded;
			tImg.onerror = imgLoaded;
			tImg.src     = img[i].src;
		}
	}
	document.addEventListener('DOMContentLoaded', loadbar, false);
}());
</script>