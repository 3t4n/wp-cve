<?php $id = get_the_ID();?>
<style>
#wppano_post_wrapper {
	height: auto; width: auto;
    max-width: 80%;
    max-height: 80%;
    top: 50%;
	left: 50%;
    display: table;
	}
	
#wppano_post_content {
	height: auto; width: auto;
	overflow: hidden;
	border-radius: 5px;
	color: #444;
	}
	
#wppano_post_content  ul li{	
	margin: 0;
	}
</style>
<div id="wppano_post_wrapper" style="width: 640px;" >
	<div id="wppano_post_title"><h1><?php the_title();?></h1></div>
	<div id="wppano_post_content" style="max-height: 100%;">
		<?php the_content(); ?>
	</div>
	<div class="wp-pano-close-icon" onclick="wppano_close_post();"></div>
	<script>
		jQuery('#wppano_post_content').ready(function() {
			var container = jQuery('#wppano_overlay');
			var wrapper = jQuery('#wppano_post_wrapper');
			var img = jQuery("#wppano_overlay img");
			var counter = 0;
			if (img.length) 
				img.on("load", function() {
					counter++;
					if ((counter == img.length)) {
						if (container.height() < wrapper.height()) {
							wrapper.css({display: 'block', height: '100%'});
							jQuery('#wppano_post_content').css({overflow: 'auto'});
						}
					}
				});
		});
	</script>	
</div>
<?php wp_die();?>