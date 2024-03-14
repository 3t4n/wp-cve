<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// toggle button CSS
wp_enqueue_style( 'awplife-npg-toggle-button-css', NPG_PLUGIN_URL . 'css/toogle-button.css' );
wp_enqueue_style( 'awplife-npg-font-awesome-css', NPG_PLUGIN_URL . 'css/font-awesome.min.css' );
wp_enqueue_style( 'awplife-npg-go-to-top-css', NPG_PLUGIN_URL . 'css/go-to-top.css' );
// JS
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'awplife-npg-go-to-top-js', NPG_PLUGIN_URL . 'js/go-to-top.js', array( 'jquery' ), '', true );

// load settings
$gallery_settings       = unserialize( base64_decode( get_post_meta( $post->ID, 'awl_lg_settings_' . $post->ID, true ) ) );
$photo_image_gallery_id = $post->ID;

?>

<!-- Return to Top -->
<a href="javascript:" id="return-to-top"><i class="fa fa-chevron-up"></i></a>
<style>
.wp-color-result::after {
	height: 25px;
}
.wp-picker-container input.wp-color-picker[type="text"] {
	width: 80px !important;
	height: 22px !important;
	float: left;
	font-size: 11px !important;
}
.iris-border .iris-palette-container {
	bottom: 6px;
}
.wp-core-ui .button, .wp-core-ui .button.button-large, .wp-core-ui .button.button-small, a.preview, input#publish, input#save-post {
	height: auto !important;
	padding: 0 12px !important;
}
/* Edit Permalink Removed */
#edit-slug-box {
	display: none !important;
}
</style>
<div>
	<p class="bg-title"><?php esc_html_e( '1. Gallery Thumbnail Size', 'new-photo-gallery' ); ?></p></br>
	<?php
	if ( isset( $gallery_settings['gal_thumb_size'] ) ) {
		$gal_thumb_size = $gallery_settings['gal_thumb_size'];
	} else {
		$gal_thumb_size = 'thumbnail';
	}
	?>
	<select id="gal_thumb_size" name="gal_thumb_size">
		<option value="thumbnail" 
		<?php
		if ( $gal_thumb_size == 'thumbnail' ) {
			echo 'selected=selected';}
		?>
		>Thumbnail - 150 x 150</option>
		<option value="medium" 
		<?php
		if ( $gal_thumb_size == 'medium' ) {
			echo 'selected=selected';}
		?>
		>Medium - 300 x 169</option>
		<option value="large" 
		<?php
		if ( $gal_thumb_size == 'large' ) {
			echo 'selected=selected';}
		?>
		>Large - 840 x 473</option>
		<option value="full" 
		<?php
		if ( $gal_thumb_size == 'full' ) {
			echo 'selected=selected';}
		?>
		>Full Size - 1280 x 720</option>
	</select><br>
	<p><?php esc_html_e( 'Select gallery thumbnails size to display into gallery<br> Note: Thumbnail setting will not work with video gallery, video poster fetch directly from YouTube and Vimeo server.', 'new-photo-gallery' ); ?></p>
</div><br>

<div>
	<p class="bg-title"><?php esc_html_e( '2. Columns Layout Settings', 'new-photo-gallery' ); ?></p>
	<p class="bg-lower-title"><?php esc_html_e( 'A. Column On Large Desktops', 'new-photo-gallery' ); ?></p></br>
	<?php
	if ( isset( $gallery_settings['col_large_desktops'] ) ) {
		$col_large_desktops = $gallery_settings['col_large_desktops'];
	} else {
		$col_large_desktops = 'col-lg-2';
	}
	?>
	<select id="col_large_desktops" name="col_large_desktops" class="form-control">
		<option value="col-lg-12" 
		<?php
		if ( $col_large_desktops == 'col-lg-12' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '1 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-lg-6" 
		<?php
		if ( $col_large_desktops == 'col-lg-6' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '2 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-lg-4" 
		<?php
		if ( $col_large_desktops == 'col-lg-4' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '3 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-lg-3" 
		<?php
		if ( $col_large_desktops == 'col-lg-3' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '4 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-lg-2" 
		<?php
		if ( $col_large_desktops == 'col-lg-2' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '6 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-lg-1" 
		<?php
		if ( $col_large_desktops == 'col-lg-1' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '12 Column', 'new-photo-gallery' ); ?></option>
	</select><br>
	<p><?php esc_html_e( 'Select gallery column layout for large desktop devices.', 'new-photo-gallery' ); ?></p>
</div>
<div>
	<p class="bg-lower-title"><?php esc_html_e( 'B. Column On Desktops', 'new-photo-gallery' ); ?></p></br>
	<?php
	if ( isset( $gallery_settings['col_desktops'] ) ) {
		$col_desktops = $gallery_settings['col_desktops'];
	} else {
		$col_desktops = 'col-md-3';
	}
	?>
	<select id="col_desktops" name="col_desktops" class="form-control">
		<option value="col-md-12" 
		<?php
		if ( $col_desktops == 'col-md-12' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '1 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-md-6" 
		<?php
		if ( $col_desktops == 'col-md-6' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '2 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-md-4" 
		<?php
		if ( $col_desktops == 'col-md-4' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '3 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-md-3" 
		<?php
		if ( $col_desktops == 'col-md-3' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '4 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-md-2" 
		<?php
		if ( $col_desktops == 'col-md-2' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '6 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-md-1" 
		<?php
		if ( $col_desktops == 'col-md-1' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '12 Column', 'new-photo-gallery' ); ?></option>
	</select><br>
	<p><?php esc_html_e( 'Select gallery column layout for desktop devices.', 'new-photo-gallery' ); ?></p>
</div>
<div>
	<p class="bg-lower-title"><?php esc_html_e( 'C. Column On Tablets', 'new-photo-gallery' ); ?></p></br>
	<?php
	if ( isset( $gallery_settings['col_tablets'] ) ) {
		$col_tablets = $gallery_settings['col_tablets'];
	} else {
		$col_tablets = 'col-sm-4';
	}
	?>
	<select id="col_tablets" name="col_tablets" class="form-control">
		<option value="col-sm-12" 
		<?php
		if ( $col_tablets == 'col-sm-12' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '1 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-sm-6" 
		<?php
		if ( $col_tablets == 'col-sm-6' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '2 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-sm-4" 
		<?php
		if ( $col_tablets == 'col-sm-4' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '3 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-sm-3" 
		<?php
		if ( $col_tablets == 'col-sm-3' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '4 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-sm-2" 
		<?php
		if ( $col_tablets == 'col-sm-2' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '6 Column', 'new-photo-gallery' ); ?></option>
	</select><br>
	<p><?php esc_html_e( 'Select gallery column layout for tablet devices.', 'new-photo-gallery' ); ?></p>
</div>
<div>
	<p class="bg-lower-title"><?php esc_html_e( 'D. Column On Phones', 'new-photo-gallery' ); ?></p></br>
	<?php
	if ( isset( $gallery_settings['col_phones'] ) ) {
		$col_phones = $gallery_settings['col_phones'];
	} else {
		$col_phones = 'col-xs-6';
	}
	?>
	<select id="col_phones" name="col_phones" class="form-control">
		<option value="col-xs-12" 
		<?php
		if ( $col_phones == 'col-xs-12' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '1 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-xs-6" 
		<?php
		if ( $col_phones == 'col-xs-6' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '2 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-xs-4" 
		<?php
		if ( $col_phones == 'col-xs-4' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '3 Column', 'new-photo-gallery' ); ?></option>
		<option value="col-xs-3" 
		<?php
		if ( $col_phones == 'col-xs-3' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '4 Column', 'new-photo-gallery' ); ?></option>
	</select><br>
	<p><?php esc_html_e( 'Select gallery column layout for phone devices.', 'new-photo-gallery' ); ?></p>
</div>

<!--start gallery tools settings -->
	<div>
		<p class="bg-title"><?php esc_html_e( '3. Lightbox Tool Color', 'new-photo-gallery' ); ?></p><br>&nbsp;&nbsp;
		<?php
		if ( isset( $gallery_settings['tool_color'] ) ) {
			$tool_color = $gallery_settings['tool_color'];
		} else {
			$tool_color = 'gold';
		}
		?>
		<input type="text" class="form-control" id="tool_color" name="tool_color" placeholder="type color name / code" value="<?php echo esc_attr( $tool_color ); ?>" default-color="<?php echo esc_attr( $tool_color ); ?>"><br>
		<p><?php esc_html_e( 'You can change color of lightbox tools for photo gallery.', 'new-photo-gallery' ); ?>
	</div>
<!--end gallery tools settings -->

<div>
	<p class="bg-title"><?php esc_html_e( '4. Title Color', 'new-photo-gallery' ); ?></p><br>&nbsp;&nbsp;
	<?php
	if ( isset( $gallery_settings['title_color'] ) ) {
		$title_color = $gallery_settings['title_color'];
	} else {
		$title_color = 'white';
	}
	?>
	<input type="text" class="form-control" id="title_color" name="title_color" placeholder="type color name / code" value="<?php echo esc_attr( $title_color ); ?>" default-color="<?php echo esc_attr( $title_color ); ?>"><br>
	<p><?php esc_html_e( 'You can change title color of image / photo.', 'new-photo-gallery' ); ?></p>
</div>

<!-- Start Hover Effect Settings -->
<div>
	<p class="bg-title"><?php esc_html_e( '5. Image Hover Effect Type', 'new-photo-gallery' ); ?></p></br>
	<p class="switch-field em_size_field">	
		<?php
		if ( isset( $gallery_settings['image_hover_effect_type'] ) ) {
			$image_hover_effect_type = $gallery_settings['image_hover_effect_type'];
		} else {
			$image_hover_effect_type = 'no';
		}
		?>
		<input type="radio" name="image_hover_effect_type" id="image_hover_effect_type1" value="no" 
		<?php
		if ( $image_hover_effect_type == 'no' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="image_hover_effect_type1"><?php esc_html_e( 'None', 'new-photo-gallery' ); ?></label>
		<input type="radio" name="image_hover_effect_type" id="image_hover_effect_type2" value="sg" 
		<?php
		if ( $image_hover_effect_type == 'sg' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="image_hover_effect_type2"><?php esc_html_e( 'Shadow & Glow', 'new-photo-gallery' ); ?></label>
		<p><?php esc_html_e( 'Select a image/photo hover effect type.', 'new-photo-gallery' ); ?></p>
	</p>
</div>

<!-- 4 -->
<div class="he_two">
	<label style="font-size: x-large; padding-left:15px;"><?php esc_html_e( 'Image Hover Effects', 'new-photo-gallery' ); ?></label><br><br>
	<?php
	if ( isset( $gallery_settings['image_hover_effect_four'] ) ) {
		$image_hover_effect_four = $gallery_settings['image_hover_effect_four'];
	} else {
		$image_hover_effect_four = 'hvr-box-shadow-outset';
	}
	?>
	<select name="image_hover_effect_four" id="image_hover_effect_four">
		<optgroup label="Shadow and Glow Transitions Effects" class="sg">
			<option value="hvr-grow-shadow" 
			<?php
			if ( $image_hover_effect_four == 'hvr-grow-shadow' ) {
				echo 'selected=selected';}
			?>
			><?php esc_html_e( 'Grow Shadow', 'new-photo-gallery' ); ?></option>
			<option value="hvr-float-shadow" 
			<?php
			if ( $image_hover_effect_four == 'hvr-float-shadow' ) {
				echo 'selected=selected';}
			?>
			><?php esc_html_e( 'Float Shadow', 'new-photo-gallery' ); ?></option>
			<option value="hvr-glow" 
			<?php
			if ( $image_hover_effect_four == 'hvr-glow' ) {
				echo 'selected=selected';}
			?>
			><?php esc_html_e( 'Glow', 'new-photo-gallery' ); ?></option>
			<option value="hvr-box-shadow-outset" 
			<?php
			if ( $image_hover_effect_four == 'hvr-box-shadow-outset' ) {
				echo 'selected=selected';}
			?>
			><?php esc_html_e( 'Box Shadow Outset', 'new-photo-gallery' ); ?></option>
			<option value="hvr-box-shadow-inset" 
			<?php
			if ( $image_hover_effect_four == 'hvr-box-shadow-inset' ) {
				echo 'selected=selected';}
			?>
			><?php esc_html_e( 'Box Shadow Inset', 'new-photo-gallery' ); ?></option>
		</optgroup>
	</select><br>
	<p class="he_two gal_settings"><?php esc_html_e( 'Set an image/photo hover effect on gallery.', 'new-photo-gallery' ); ?></p>
</div>
<!-- End Hover Effect Settings -->

<div>
	<p class="bg-title"><?php esc_html_e( '6. Effect Types On Change Image', 'new-photo-gallery' ); ?></p></br>
	<?php
	if ( isset( $gallery_settings['transition_effects'] ) ) {
		$transition_effects = $gallery_settings['transition_effects'];
	} else {
		$transition_effects = 'lg-fade';
	}
	?>
	<select id="transition_effects" name="transition_effects" class="form-control">
		<option value="none" 
		<?php
		if ( $transition_effects == 'none' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( 'None', 'new-photo-gallery' ); ?></option>
		<option value="lg-slide" 
		<?php
		if ( $transition_effects == 'lg-slide' ) {
			echo 'selected=selected';}
		?>
		>Slide</option>
		<option value="lg-fade" 
		<?php
		if ( $transition_effects == 'lg-fade' ) {
			echo 'selected=selected';}
		?>
		>Fade</option>
		<option value="lg-zoom-in" 
		<?php
		if ( $transition_effects == 'lg-zoom-in' ) {
			echo 'selected=selected';}
		?>
		>Zoom In</option>
		<option value="lg-zoom-in-big" 
		<?php
		if ( $transition_effects == 'lg-zoom-in-big' ) {
			echo 'selected=selected';}
		?>
		>Zoom In Big Effect</option>
	</select><br>
	<p><?php esc_html_e( 'Select custom effects for photo image gallery.', 'new-photo-gallery' ); ?></p>
</div>

<div>
	<p class="bg-title"><?php esc_html_e( '7. Thumbnails Spacing', 'new-photo-gallery' ); ?></p><br>
	<p class="switch-field em_size_field">
		<?php
		if ( isset( $gallery_settings['thumbnails_spacing'] ) ) {
			$thumbnails_spacing = $gallery_settings['thumbnails_spacing'];
		} else {
			$thumbnails_spacing = 1;
		}
		?>
		<input type="radio" name="thumbnails_spacing" id="thumbnails_spacing1" value="1" 
		<?php
		if ( $thumbnails_spacing == 1 ) {
			echo 'checked=checked';}
		?>
		>
		<label for="thumbnails_spacing1"><?php esc_html_e( 'Yes', 'new-photo-gallery' ); ?></label>
		<input type="radio" name="thumbnails_spacing" id="thumbnails_spacing2" value="0" 
		<?php
		if ( $thumbnails_spacing == 0 ) {
			echo 'checked=checked';}
		?>
		>
		<label for="thumbnails_spacing2"><?php esc_html_e( 'No', 'new-photo-gallery' ); ?></label>
		<p><?php esc_html_e( 'Hide gap / margin / padding / spacing between gallery thumbnails.', 'new-photo-gallery' ); ?></p>
	</p>
</div>

<div>
	<p class="bg-title"><?php esc_html_e( '8. Custom CSS', 'new-photo-gallery' ); ?></p></br>
	<?php
	if ( isset( $gallery_settings['custom-css'] ) ) {
		$custom_css = $gallery_settings['custom-css'];
	} else {
		$custom_css = '';
	}
	?>
	<textarea name="custom-css" id="custom-css" style="width: 100%; height: 120px;" placeholder="Type direct CSS code here. Don't use <style>...</style> tag."><?php echo $custom_css; ?></textarea><br>
	<p><?php esc_html_e( 'Apply own css on photo image gallery and dont use style tag.', 'new-photo-gallery' ); ?></p>
</div>

<?php wp_nonce_field( 'lg_save_settings', 'lg_save_nonce' ); ?>

<script>
//hover effect hide and show 
	var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
	if(effect_type == "no") {
		jQuery('.he_one').hide();
		jQuery('.he_two').hide();
	}
	
	if(effect_type == "sg") {
		jQuery('.he_one').hide();
		jQuery('.he_two').show();
	}
	
	//on change effect
	jQuery(document).ready(function() {
		// image hover effect hide show
		jQuery('input[name="image_hover_effect_type"]').change(function(){
			var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
			if(effect_type == "no") {
				jQuery('.he_one').hide();
				jQuery('.he_two').hide();
			}
			if(effect_type == "sg") {
				jQuery('.he_one').hide();
				jQuery('.he_two').show();
			}
		})	
	});

// start pulse on page load
	function pulseEff() {
		jQuery('#shortcode').fadeOut(600).fadeIn(600);
	};
	var Interval;
	Interval = setInterval(pulseEff,1500);

	// stop pulse
	function pulseOff() {
		clearInterval(Interval);
	}
	// start pulse
	function pulseStart() {
		Interval = setInterval(pulseEff,1500);
	}
</script>
	<hr>
	<div class="row" style="text-align: center;">
	<h1>Upgrade To Photo Gallery Premium in Just <strong>$15</strong></h1>
	<br>
	<a href="https://awplife.com/wordpress-plugins/photo-gallery-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Photo Gallery Premium Version Details</a>
	<a href="https://awplife.com/demo/photo-gallery-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Photo Gallery Premium Plugin Live Demo</a>
	<a href="https://awplife.com/demo/photo-gallery-premium-admin-demo/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Try Premium Version Before Buy</a>
	</div>
	<style>
	.awp_bale_offer {
		padding:30px;
	}
	.awp_bale_offer h1 {
		font-size:35px;
		color:#008EC2;
	}
	.awp_bale_offer h3 {
		font-size:25px;
		color:#008EC2;
	}
	.awplife-free-plugins { 
		margin: 5px !important;
	}
	</style>
	<div class="row awp_bale_offer" style="text-align: center;">
		<hr />
		<div>
			<h1>Plugin's Bundle Offer</h1>
			<h3>Get All 23 Premium Plugin ( Personal License) in just $179 </h3>
			<h3><strike>$399</strike> For $179 Only</h3>
		</div>
		<div class="">
			<a href="https://awplife.com/account/signup/all-premium-plugins" target="_blank" class="button button-primary button-hero">BUY NOW</a>
		</div>
	</div>
	<hr />
	<div style="text-align: center;">
		<p>
			<h2>Try Out Other Free WordPress Plugins</h2>
			<br>
			<a href="https://wordpress.org/plugins/new-album-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Album Gallery</a>
			<a href="https://wordpress.org/plugins/wp-flickr-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Flickr gallery</a>
			<a href="https://wordpress.org/plugins/animated-live-wall/" target="_blank" class="button button-primary awplife-free-plugins">Animated Live Wall</a>
			<a href="https://wordpress.org/plugins/blog-filter/" target="_blank" class="button button-primary awplife-free-plugins">Blog Filter</a>
			<a href="https://wordpress.org/plugins/new-contact-form-widget/" target="_blank" class="button button-primary awplife-free-plugins">Contact Form Widget</a>
			<a href="https://wordpress.org/plugins/customizer-login-page/" target="_blank" class="button button-primary awplife-free-plugins">Custom Login Page</a>
			<a href="https://wordpress.org/plugins/event-monster/" target="_blank" class="button button-primary awplife-free-plugins">Event Monster</a>
			<a href="https://wordpress.org/plugins/floating-news-headline/" target="_blank" class="button button-primary awplife-free-plugins">Floating News Headline</a>
			<a href="https://wordpress.org/plugins/new-photo-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Photo Gallery</a>
			<a href="https://wordpress.org/plugins/new-grid-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Grid Gallery</a>
			<a href="https://wordpress.org/plugins/hash-converter/" target="_blank" class="button button-primary awplife-free-plugins">Hash Converter</a>
			<a href="https://wordpress.org/plugins/new-image-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Image Gallery</a>
			<a href="https://wordpress.org/plugins/media-slider/" target="_blank" class="button button-primary awplife-free-plugins">Media Slider</a>
			<a href="https://wordpress.org/plugins/modal-popup-box/" target="_blank" class="button button-primary awplife-free-plugins">Modal Popup Box</a>
			<a href="https://wordpress.org/plugins/portfolio-filter-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Portfolio Filter Gallery</a>
			<a href="https://wordpress.org/plugins/abc-pricing-table/" target="_blank" class="button button-primary awplife-free-plugins">Pricing Table</a>
			<a href="https://wordpress.org/plugins/facebook-likebox-widget-and-shortcode/" target="_blank" class="button button-primary awplife-free-plugins">Facebook Likebox</a>
			<a href="https://wordpress.org/plugins/responsive-slider-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Responsive Slider Gallery</a>
			<a href="https://wordpress.org/plugins/right-click-disable-or-ban/" target="_blank" class="button button-primary awplife-free-plugins">Right Click Ban And Disable</a>
			<a href="https://wordpress.org/plugins/slider-responsive-slideshow/" target="_blank" class="button button-primary awplife-free-plugins">Slider Responsive Slideshow</a>
			<a href="https://wordpress.org/plugins/wp-instagram-feed-awplife/" target="_blank" class="button button-primary awplife-free-plugins">Instagram Feed</a>
			<a href="https://wordpress.org/plugins/new-social-media-widget/" target="_blank" class="button button-primary awplife-free-plugins">Social Media Icon Widget</a>
			<a href="https://wordpress.org/plugins/insta-type-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Instagram Type Gallery</a>
			<a href="https://wordpress.org/plugins/testimonial-maker/" target="_blank" class="button button-primary awplife-free-plugins">Testimonial</a>
			<a href="https://wordpress.org/plugins/new-video-gallery/" target="_blank" class="button button-primary awplife-free-plugins">Video Gallery</a>
			<a href="https://wordpress.org/plugins/weather-effect/" target="_blank" class="button button-primary awplife-free-plugins">Weather Effect</a>
		</p>
	</div>
