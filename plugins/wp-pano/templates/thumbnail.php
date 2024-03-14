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
<div id="wppano_post_wrapper">
	<div id="wppano_post_title"><h1><?php the_title();?></h1></div>
	<div id="wppano_post_content">
		<?php //$thumbnail = image_downsize( get_post_thumbnail_id( $id ), 'full');  //$thumbnail[0] - url
			$thumbnail = wp_get_attachment_url( get_post_thumbnail_id( $id ));		?>
		<img src="<?php echo $thumbnail; ?>" class="wp-pano-img">
		<div class="image-description"> <?php the_content(); ?> </div>
		<?php // the_content(); ?>
	</div>
	<div class="wp-pano-close-icon" onclick="wppano_close_post();"></div>
	<div class="wheelzoom" onclick="wheelzoom(document.querySelector('img.wp-pano-img'), {maxzoom:1});"></div>
	<script>
		var description = false;
		
		jQuery('img').hover( function() {
			description = true;
			jQuery('.image-description').fadeIn( 500 );
		}, function() {
			description = false;
			setTimeout(hide_description, 100);
		});
		jQuery('.image-description').hover( function() {
			description = true;
		}, function() {
			description = false;
			setTimeout(hide_description, 100);
		});
		function hide_description() {
			if(!description) jQuery('.image-description').fadeOut( 100 );
		}
		var load = false;
		jQuery('img').load(function() {
			if(!load) {
				if( jQuery('.image-description').html().length < 4 ) jQuery('.image-description').remove();;
				load = true;
				var _this = jQuery(this);
				_this.trigger('wheelzoom.reset');
				var container = jQuery('#wppano_overlay');
				var wrapper = jQuery('#wppano_post_wrapper');
				var wrapper_height = wrapper.height()-_this.height();
				var ratio=_this.width() / _this.height();
				var pratio=container.width() / container.height();
				if ( ratio < pratio ) {
					var css;
					if( _this.height() > container.height()-150-wrapper_height )
						css={height: container.height()-150-wrapper_height, width: 'auto'};
					else
						css={height: _this.height(), width: 'auto'};
					_this.css(css);
				};
				jQuery('.wheelzoom').trigger('click');
			};
		});
	</script>
</div>
<?php wp_die();?>