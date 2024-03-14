<div id="loader" class="pageload-overlay" data-opening="M 0,0 0,60 80,60 80,0 z M 80,0 40,30 0,60 40,30 z">

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

	<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
		<path d="M 0,0 0,60 80,60 80,0 Z M 80,0 80,60 0,60 0,0 Z"/>
	</svg>
</div><!-- /pageload-overlay -->

<?php 
	// loader animation time
	$preloader_awesome_anim_time = carbon_get_post_meta( $post->ID, 'preloader_awesome_anim_time' );
	if(!empty($preloader_awesome_anim_time)) {
		$preloader_awesome_anim_time = $preloader_awesome_anim_time;
		$preloader_awesome_anim_time_out = $preloader_awesome_anim_time * 2;
	}
	else {
		$preloader_awesome_anim_time = 700;
		$preloader_awesome_anim_time_out = 1400;
	}
?>

<script>
(function () {

	var pageWrap = document.getElementById('ta-pageload'),
		pages = pageWrap.querySelector('div.container-pageload'),
		loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : <?php echo intval($preloader_awesome_anim_time); ?>, speedOut : <?php echo intval($preloader_awesome_anim_time_out); ?>, easingIn : mina.easeinout, easingOut : mina.bounce } );

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