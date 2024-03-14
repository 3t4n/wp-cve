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

<div id="wppano_post_wrapper" >
	<div id="wppano_post_title"><h1><?php the_title();?></h1></div>
	<div id="wppano_post_content" style="max-height: 100%">
		<?php the_content(); ?>
	</div>
	<div class="wp-pano-close-icon" onclick="wppano_close_post();"></div>
	<script>
		var load = false;
		jQuery('#wppano_post_content').ready(function() {
			var container = jQuery('#wppano_overlay');
			var wrapper = jQuery('#wppano_post_wrapper');
			if ( container.height() < wrapper.height() ) {
				wrapper.css({display: 'block', width: '600px', height: '100%'});
				jQuery('#wppano_post_content').css({overflow: 'auto'});
			};
		});
	</script>	
</div>
<?php wp_die();?>