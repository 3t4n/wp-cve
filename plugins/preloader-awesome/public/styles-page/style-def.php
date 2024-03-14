<div id="loader" class="pageload-overlay def">
	
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
			ovrl.style.opacity = 0;
			setTimeout(function(){
				<?php if($preloader_awesome_counter == 'yes') { ?>
					stat.style.display = "none";
				<?php }
				if($preloader_awesome_progress == 'yes') { ?>
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