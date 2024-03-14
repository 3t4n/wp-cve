<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// toggle button CSS
wp_enqueue_style( 'awl-toogle-button-css', GG_PLUGIN_URL . 'css/toogle-button.css' );
wp_enqueue_style( 'awl-bootstrap-css', GG_PLUGIN_URL . 'css/bootstrap.css' );
wp_enqueue_style( 'awl-font-awesome-css', GG_PLUGIN_URL . 'css/font-awesome.css' );
// js
wp_enqueue_script( 'awl-bootstrap-js', GG_PLUGIN_URL . 'js/bootstrap.min.js' );

// load settings
$gg_settings = unserialize( base64_decode( get_post_meta( $post->ID, 'awl_gg_settings_' . $post->ID, true ) ) );

$grid_gallery_id = $post->ID;
?>
<style>
<!--color picker setting-->
.wp-color-result::after {
	height: 21px;
}
.wp-picker-container input.wp-color-picker[type="text"] {
	width: 80px !important;
	height: 22px !important;
	float: left;
}
.gg_settings {
	padding: 8px 0px 8px 8px !important;
	margin: 10px 10px 4px 0px !important;
}
.gg_settings label {
	font-size: 16px !important;
	 font-weight: bold;
}
.gg_comment_settings {
	font-size: 15px !important;
	padding-left: 4px;
	font: initial;
	margin-top: 5px;
	padding-left:14px;
}
</style>

<!-- Thumbnail Size -->
<p class="gg_settings gg_border">
	<p class="bg-title"><?php esc_html_e( '1. Grid Gallery Thumbnail Size', 'new-grid-gallery' ); ?></p><br>
	<?php
	if ( isset( $gg_settings['gal_thumb_size'] ) ) {
		$gal_thumb_size = $gg_settings['gal_thumb_size'];
	} else {
		$gal_thumb_size = 'medium';
	}
	?>
	<select id="gal_thumb_size" name="gal_thumb_size" class="" style="margin-left: 10px; width: 300px;">
		<option value="thumbnail" 
		<?php
		if ( $gal_thumb_size == 'thumbnail' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( 'Thumbnail – 150 × 150', 'new-grid-gallery' ); ?></option>
		<option value="medium" 
		<?php
		if ( $gal_thumb_size == 'medium' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( 'Medium – 300 × 169', 'new-grid-gallery' ); ?></option>
		<option value="large" 
		<?php
		if ( $gal_thumb_size == 'large' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( 'Large – 840 × 473', 'new-grid-gallery' ); ?></option>
		<option value="full" 
		<?php
		if ( $gal_thumb_size == 'full' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( 'Full Size – 1280 × 720', 'new-grid-gallery' ); ?></option>
	</select><br><br>
	<p class="gg_comment_settings"><?php esc_html_e( 'Select gallery thumnails size to display into gallery', 'new-grid-gallery' ); ?></p>
</p>

<!-- Columns Size 
<p class="gg_settings gg_border">
	<label><?php esc_html_e( 'Columns In Grid Gallery', 'new-grid-gallery' ); ?></label><br><br>
	<?php
	if ( isset( $gg_settings['col_large_desktops'] ) ) {
		$col_large_desktops = $gg_settings['col_large_desktops'];
	} else {
		$col_large_desktops = '3_Column';
	}
	?>
	<select id="col_large_desktops" name="col_large_desktops" class="form-control">
		<option value="1_column" 
		<?php
		if ( $col_large_desktops == '1_column' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '1 Column', 'new-grid-gallery' ); ?></option>
		<option value="2_column" 
		<?php
		if ( $col_large_desktops == '2_column' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '2 Column', 'new-grid-gallery' ); ?></option>
		<option value="3_column" 
		<?php
		if ( $col_large_desktops == '3_column' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '3 Column', 'new-grid-gallery' ); ?></option>
		<option value="4_column" 
		<?php
		if ( $col_large_desktops == '4_column' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '4 Column', 'new-grid-gallery' ); ?></option>
		<option value="5_column" 
		<?php
		if ( $col_large_desktops == '5_column' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '5 Column', 'new-grid-gallery' ); ?></option>
		<option value="6_column" 
		<?php
		if ( $col_large_desktops == '6_column' ) {
			echo 'selected=selected';}
		?>
		><?php esc_html_e( '6 Column', 'new-grid-gallery' ); ?></option>
	</select><br><br>
	<?php esc_html_e( 'Select gallery column layout for large desktop devices', 'new-grid-gallery' ); ?><a class="be-right" href="#"><?php esc_html_e( 'Go To Top', 'new-grid-gallery' ); ?></a>
</p>-->

<!-- Animation Speed -->
<p class="gg_settings gg_border range-slider">
	<p class="bg-title"><?php esc_html_e( '2. Animation Speed', 'new-grid-gallery' ); ?></p><br>
	<?php
	if ( isset( $gg_settings['animation_speed'] ) ) {
		$animation_speed = $gg_settings['animation_speed'];
	} else {
		$animation_speed = 400;
	}
	?>
	<input id="animation_speed" name="animation_speed" class="range-slider__range" type="range" value="<?php echo esc_attr( $animation_speed ); ?>" min="0" max="1000" step="50" style="width: 300px !important; margin-left: 10px;">
	<span class="range-slider__value">0</span>
	<p class="gg_comment_settings"><?php esc_html_e( 'Set animation speed', 'new-grid-gallery' ); ?></p>
</p>

<!-- hover effects -->
<div class="gg_border">
	<p class="gg_settings">
		<p class="bg-title"><?php esc_html_e( '3. Image Hover Effect Type', 'new-grid-gallery' ); ?></p><br>
		<?php
		if ( isset( $gg_settings['image_hover_effect_type'] ) ) {
			$image_hover_effect_type = $gg_settings['image_hover_effect_type'];
		} else {
			$image_hover_effect_type = 'no';
		}
		?>
		<p class="switch-field em_size_field">
			<input type="radio" name="image_hover_effect_type" id="image_hover_effect_type1" value="no" 
			<?php
			if ( $image_hover_effect_type == 'no' ) {
				echo 'checked=checked';}
			?>
			>
			<label for="image_hover_effect_type1"><?php esc_html_e( 'None', 'new-grid-gallery' ); ?></label>
			<input type="radio" name="image_hover_effect_type" id="image_hover_effect_type2" value="sg" 
			<?php
			if ( $image_hover_effect_type == 'sg' ) {
				echo 'checked=checked';}
			?>
			>
			<label for="image_hover_effect_type2"><?php esc_html_e( 'Shadow and Glow', 'new-grid-gallery' ); ?></label>
			<p class="gg_comment_settings"><?php esc_html_e( 'Select a image hover effect type', 'new-grid-gallery' ); ?></p>
		</p>
	</p>

	<!-- 2 -->
	<p class="he_two gg_settings" style="padding-left: 30px !important;">
		<label><?php esc_html_e( 'Image Hover Effects', 'new-grid-gallery' ); ?></label><br><br>
		<?php
		if ( isset( $gg_settings['image_hover_effect_four'] ) ) {
			$image_hover_effect_four = $gg_settings['image_hover_effect_four'];
		} else {
			$image_hover_effect_four = 'hvr-box-shadow-outset';
		}
		?>
		<select name="image_hover_effect_four" id="image_hover_effect_four">
			<optgroup label="Shadow and Glow Transitions Effects" class="sg">
				<option value="hvr-float-shadow" 
				<?php
				if ( $image_hover_effect_four == 'hvr-float-shadow' ) {
					echo 'selected=selected';}
				?>
				><?php esc_html_e( 'Float Shadow', 'new-grid-gallery' ); ?></option>
				<option value="hvr-shadow-radial" 
				<?php
				if ( $image_hover_effect_four == 'hvr-shadow-radial' ) {
					echo 'selected=selected';}
				?>
				><?php esc_html_e( 'Shadow Radial', 'new-grid-gallery' ); ?></option>
				<option value="hvr-box-shadow-outset" 
				<?php
				if ( $image_hover_effect_four == 'hvr-box-shadow-outset' ) {
					echo 'selected=selected';}
				?>
				><?php esc_html_e( 'Box Shadow Outset', 'new-grid-gallery' ); ?></option>
			</optgroup>
		</select><br><br>
		<p class="he_two gg_comment_settings"><?php esc_html_e( 'Set an image hover effect on gallery', 'new-grid-gallery' ); ?></p>
	</p>
</div>

<!-- Scroll Loading -->
<p class="gg_settings gg_border">
	<p class="bg-title"><?php esc_html_e( '4. Auto Scroll On Image', 'new-grid-gallery' ); ?></p><br>
	<?php
	if ( isset( $gg_settings['scroll_loading'] ) ) {
		$scroll_loading = $gg_settings['scroll_loading'];
	} else {
		$scroll_loading = 'true';
	}
	?>
	<p class="switch-field em_size_field">
		<input type="radio" name="scroll_loading" id="scroll_loading1" value="true" 
		<?php
		if ( $scroll_loading == 'true' ) {
			echo 'checked=checked';}
		?>
		>
			<label for="scroll_loading1"><?php esc_html_e( 'Yes', 'new-grid-gallery' ); ?></label>
		<input type="radio" name="scroll_loading" id="scroll_loading2" value="false" 
		<?php
		if ( $scroll_loading == 'false' ) {
			echo 'checked=checked';}
		?>
		>
			<label for="scroll_loading2"><?php esc_html_e( 'No', 'new-grid-gallery' ); ?></label>
		<p class="gg_comment_settings"><?php esc_html_e( 'Set yes or no for auto scroll on image', 'new-grid-gallery' ); ?></p>
	</p>
</p>

<!-- Navigation Buttons Position -->
<p class="gg_settings gg_border">
	<p class="bg-title"><?php esc_html_e( '5. Navigation Buttons Position', 'new-grid-gallery' ); ?></p><br>
	<?php
	if ( isset( $gg_settings['nbp_setting'] ) ) {
		$nbp_setting = $gg_settings['nbp_setting'];
	} else {
		$nbp_setting = 'in';
	}
	?>
	<?php
	if ( isset( $gg_settings['nbp_setting2'] ) ) {
		$nbp_setting2 = $gg_settings['nbp_setting2'];
	} else {
		$nbp_setting2 = 'left';
	}
	?>
	<p class="switch-field em_size_field">
		<input type="radio" name="nbp_setting2" id="nbp_setting2_1" value="left" 
		<?php
		if ( $nbp_setting2 == 'left' ) {
			echo 'checked=checked';}
		?>
		>
			<label for="nbp_setting2_1"><?php esc_html_e( 'Left', 'new-grid-gallery' ); ?></label>
		<input type="radio" name="nbp_setting2" id="nbp_setting2_2" value="right" 
		<?php
		if ( $nbp_setting2 == 'right' ) {
			echo 'checked=checked';}
		?>
		>
			<label for="nbp_setting2_2"><?php esc_html_e( 'Right', 'new-grid-gallery' ); ?></label>
		<br>
		<p class="gg_comment_settings"><?php esc_html_e( 'Select navigation buttons position for grid gallery', 'new-grid-gallery' ); ?></p>
	</p>
</p>

<!-- thumbnail title -->
<p class="gg_settings gg_border">
	<p class="bg-title"><?php esc_html_e( 'A. Title On Thumbnail', 'new-grid-gallery' ); ?></p>
	<p class="switch-field em_size_field">
		<?php
		if ( isset( $gg_settings['thumb_title'] ) ) {
			$thumb_title = $gg_settings['thumb_title'];
		} else {
			$thumb_title = 'show';
		}
		?>
		<input type="radio" name="thumb_title" id="thumb_title1" value="hide" 
		<?php
		if ( $thumb_title == 'hide' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="thumb_title1"><?php esc_html_e( 'Hide', 'new-grid-gallery' ); ?></label>
		<input type="radio" name="thumb_title" id="thumb_title2" value="show" 
		<?php
		if ( $thumb_title == 'show' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="thumb_title2"><?php esc_html_e( 'Show', 'new-grid-gallery' ); ?></label>
		<br><br>
		<p class="gg_comment_settings"><?php esc_html_e( 'You can hide / show title on grid gallery thumbnails', 'new-grid-gallery' ); ?></p>
	</p>	
</p>

<!-- Title On Image Preview -->
<p class="gg_settings gg_border switch-field em_size_field">
	<p class="bg-title"><?php esc_html_e( '6. Title On Image Preview', 'new-grid-gallery' ); ?></p><br>
	<?php
	if ( isset( $gg_settings['title_setting'] ) ) {
		$title_setting = $gg_settings['title_setting'];
	} else {
		$title_setting = 'show';
	}
	?>
	<p class="switch-field em_size_field">	
		<input type="radio" name="title_setting" id="title_setting1" value="hide" 
		<?php
		if ( $title_setting == 'hide' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="title_setting1"><?php esc_html_e( 'Hide', 'new-grid-gallery' ); ?></label>
		<input type="radio" name="title_setting" id="title_setting2" value="show" 
		<?php
		if ( $title_setting == 'show' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="title_setting2"><?php esc_html_e( 'Show', 'new-grid-gallery' ); ?></label>
		<p class="gg_comment_settings"><?php esc_html_e( 'You can hide / show title for grid gallery', 'new-grid-gallery' ); ?></p>
	</p>
	
	<p class="tfs gg_settings">
		<label><?php esc_html_e( ' Title Font Color', 'new-grid-gallery' ); ?></label><br><br>
		<?php
		if ( isset( $gg_settings['title_color'] ) ) {
			$title_color = $gg_settings['title_color'];
		} else {
			$title_color = 'white';
		}
		?>
		<p class="switch-field em_size_field tfs">
			<input type="radio" name="title_color" id="title_color1" value="white" 
			<?php
			if ( $title_color == 'white' ) {
				echo 'checked=checked';}
			?>
			>
			<label for="title_color1"><?php esc_html_e( 'White', 'new-grid-gallery' ); ?></label>
			<input type="radio" name="title_color" id="title_color2" value="black" 
			<?php
			if ( $title_color == 'black' ) {
				echo 'checked=checked';}
			?>
			>
			<label for="title_color2"><?php esc_html_e( 'Black', 'new-grid-gallery' ); ?></label>
			<input type="radio" name="title_color" id="title_color3" value="red" 
			<?php
			if ( $title_color == 'red' ) {
				echo 'checked=checked';}
			?>
			>
			<label for="title_color3"><?php esc_html_e( 'Red', 'new-grid-gallery' ); ?></label>
			<input type="radio" name="title_color" id="title_color4" value="blue" 
			<?php
			if ( $title_color == 'blue' ) {
				echo 'checked=checked';}
			?>
			>
			<label for="title_color4"><?php esc_html_e( 'Blue', 'new-grid-gallery' ); ?></label>
		</p>
		<p class="tfs gg_comment_settings"><?php esc_html_e( 'You can change color of title on full size of image for grid gallery', 'new-grid-gallery' ); ?></p>
	</p>
	
</p>
<!-- thumbnail border on image -->
<p class="gg_settings gg_border">
	<p class="bg-title"><?php esc_html_e( '7. Thumbnail Border On Image', 'new-grid-gallery' ); ?></p><br>
	<?php
	if ( isset( $gg_settings['thumbnail_border'] ) ) {
		$thumbnail_border = $gg_settings['thumbnail_border'];
	} else {
		$thumbnail_border = 'show';
	}
	?>
	<p class="switch-field em_size_field">
		<input type="radio" name="thumbnail_border" id="thumbnail_border1" value="hide" 
		<?php
		if ( $thumbnail_border == 'hide' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="thumbnail_border1"><?php esc_html_e( 'Hide', 'new-grid-gallery' ); ?></label>
		<input type="radio" name="thumbnail_border" id="thumbnail_border2" value="show" 
		<?php
		if ( $thumbnail_border == 'show' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="thumbnail_border2"><?php esc_html_e( 'Show', 'new-grid-gallery' ); ?></label>
		<p class="gg_comment_settings"><?php esc_html_e( 'You can hide / show thumbnail border on image for grid gallery', 'new-grid-gallery' ); ?></p>
	</p>
</p>

<!-- thumbnail spacing -->
<p class="gg_settings gg_border">
	<p class="bg-title"><?php esc_html_e( '8. Hide Image Spacing', 'new-grid-gallery' ); ?></p><br>
	<?php
	if ( isset( $gg_settings['no_spacing'] ) ) {
		$no_spacing = $gg_settings['no_spacing'];
	} else {
		$no_spacing = 'no';
	}
	?>
	<p class="switch-field em_size_field">
		<input type="radio" name="no_spacing" id="no_spacing1" value="yes" 
		<?php
		if ( $no_spacing == 'yes' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="no_spacing1"><?php esc_html_e( 'Yes', 'new-grid-gallery' ); ?></label>
		<input type="radio" name="no_spacing" id="no_spacing2" value="no" 
		<?php
		if ( $no_spacing == 'no' ) {
			echo 'checked=checked';}
		?>
		>
		<label for="no_spacing2"><?php esc_html_e( 'No', 'new-grid-gallery' ); ?></label>
		<p class="gg_comment_settings"><?php esc_html_e( 'Hide gap / spacing between gallery images', 'new-grid-gallery' ); ?></p>
	</p>
</p>

<!-- custom css -->
<p class="gg_settings gg_border">
	<p class="bg-title"><?php esc_html_e( '9. Custom CSS', 'new-grid-gallery' ); ?></p><br>
	<?php
	if ( isset( $gg_settings['custom-css'] ) ) {
		$custom_css = $gg_settings['custom-css'];
	} else {
		$custom_css = '';
	}
	?>
	<textarea name="custom-css" id="custom-css" style="width: 100%; height: 120px;" placeholder="Type direct CSS code here. Don't use <style>...</style> tag."><?php echo $custom_css; ?></textarea><br>
	<br>
	<p class="gg_comment_settings"><?php esc_html_e( 'Apply own css on grid gallery and dont use style tag', 'new-grid-gallery' ); ?></p>
</p>
<hr>

<?php
	// syntax: wp_nonce_field( 'name_of_my_action', 'name_of_nonce_field' );
	wp_nonce_field( 'gg_save_settings', 'gg_save_nonce' );
?>
<!-- Return to Top -->
<a href="javascript:" id="return-to-top"><i class="fa fa-chevron-up"></i></a>

<hr>
<script>
// ===== Scroll to Top ==== 
jQuery(window).scroll(function() {
	if (jQuery(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
			jQuery('#return-to-top').fadeIn(200);    // Fade in the arrow
		} else {
			jQuery('#return-to-top').fadeOut(200);   // Else fade out the arrow
		}
	});
	jQuery('#return-to-top').click(function() {      // When arrow is clicked
		jQuery('body,html').animate({
			scrollTop : 0                       // Scroll to top of body
		}, 500);
	});
	// title size range settings.  on change range value
	function updateRange(val, id) {
		jQuery("#" + id).val(val);
		jQuery("#" + id + "_text").val(val);
	}
	
	//color-picker
	(function( jQuery ) {
		jQuery(function() {
			// Add Color Picker to all inputs that have 'color-field' class
			jQuery('#title_color').wpColorPicker();
			jQuery('#desc_color').wpColorPicker();
			jQuery('#border_color').wpColorPicker();
		});
	})( jQuery );
	jQuery(document).ajaxComplete(function() {
		jQuery('#title_color,#decs_color,#border_color').wpColorPicker();
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
		Interval = setInterval(pulseEff,2000);
	}
	
	//hover effect hide and show 
	var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
	if(effect_type == "no") {
		jQuery('.he_one').hide();
		jQuery('.he_two').hide();
		jQuery('.he_ancer').show();
	}
	
	if(effect_type == "2d") {
		jQuery('.he_one').show();
		jQuery('.he_two').hide();
		jQuery('.he_ancer').hide();
	}
	
	if(effect_type == "sg") {
		jQuery('.he_one').hide();
		jQuery('.he_two').show();
		jQuery('.he_ancer').hide();
	}
	
	// on load title font hide show
	var title = jQuery('input[name="title_setting"]:checked').val();
	if(title == "hide"){
		jQuery('.tfs').hide();
		jQuery('.tfs_ancer').show();
	}
	if(title == "show"){
		jQuery('.tfs').show();
		jQuery('.tfs_ancer').hide();
	}
	
	// on load description font hide show
	var desc = jQuery('input[name="desc_setting"]:checked').val();
	if(desc == "hide"){
		jQuery('.dfs').hide();
		jQuery('.dfs_ancer').show();
	}
	if(desc == "show"){
		jQuery('.dfs').show();
		jQuery('.dfs_ancer').hide();
	}
	
	// on load image border hide show
	var border = jQuery('input[name="image_border"]:checked').val();
	if(border == "hide"){
		jQuery('.btc').hide();
		jQuery('.btc_ancer').show();
	}
	if(border == "show"){
		jQuery('.btc').show();
		jQuery('.btc_ancer').hide();
	}
	
	// on load link hide show
	var link = jQuery('input[name="image_link"]:checked').val();
	if(link == "none"){
		jQuery('.ilu').hide();
		jQuery('.ilu_ancer').show();
	}
	if(link == "image"){
		jQuery('.ilu').show();
		jQuery('.ilu_ancer').hide();
	}
	if(link == "title"){
		jQuery('.ilu').show();
		jQuery('.ilu_ancer').hide();
	}
	if(link == "desc"){
		jQuery('.ilu').show();
		jQuery('.ilu_ancer').hide();
	}
	
	// on load navigation button center hide show
	var button = jQuery('input[name="nbp_setting"]:checked').val();
	if(button == "in"){
		jQuery('.nbc').hide();
	}
	if(button == "out"){
		jQuery('.nbc').show();
	}
	
	//on change effect
	jQuery(document).ready(function() {
		// image hover effect hide show live
		jQuery('input[name="image_hover_effect_type"]').change(function(){
			var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
			if(effect_type == "no") {
				jQuery('.he_one').hide();
				jQuery('.he_two').hide();
				jQuery('.he_ancer').show();
			}
			
			if(effect_type == "2d") {
				jQuery('.he_one').show();
				jQuery('.he_two').hide();
				jQuery('.he_ancer').hide();
			}
			
			if(effect_type == "sg") {
				jQuery('.he_one').hide();
				jQuery('.he_two').show();
				jQuery('.he_ancer').hide();
			}
		});
		
		// title font size hide show live
		jQuery('input[name="title_setting"]').change(function(){
			var title = jQuery('input[name="title_setting"]:checked').val();
			if(title == "hide"){
					jQuery('.tfs').hide();
					jQuery('.tfs_ancer').show();
				}
				if(title == "show"){
					jQuery('.tfs').show();
					jQuery('.tfs_ancer').hide();
				}
		});
		
		// description font size hide show live
		jQuery('input[name="desc_setting"]').change(function(){
			var desc = jQuery('input[name="desc_setting"]:checked').val();
			if(desc == "hide"){
					jQuery('.dfs').hide();
					jQuery('.dfs_ancer').show();
				}
				if(desc == "show"){
					jQuery('.dfs').show();
					jQuery('.dfs_ancer').hide();
				}
		});
		
		// border settings hide show live
		jQuery('input[name="image_border"]').change(function(){
			var border = jQuery('input[name="image_border"]:checked').val();
			if(border == "hide"){
				jQuery('.btc').hide();
				jQuery('.btc_ancer').show();
			}
			if(border == "show"){
				jQuery('.btc').show();
				jQuery('.btc_ancer').hide();
			}
		});
		
		// border settings hide show live
		jQuery('input[name="image_link"]').change(function(){
			var link = jQuery('input[name="image_link"]:checked').val();
			if(link == "none"){
				jQuery('.ilu').hide();
				jQuery('.ilu_ancer').show();
			}
			if(link == "image"){
				jQuery('.ilu').show();
				jQuery('.ilu_ancer').hide();
			}
			if(link == "title"){
				jQuery('.ilu').show();
				jQuery('.ilu_ancer').hide();
			}
			if(link == "desc"){
				jQuery('.ilu').show();
				jQuery('.ilu_ancer').hide();
			}
		});
		
		// navigation button center hide show live
		jQuery('input[name="nbp_setting"]').change(function(){
			var button = jQuery('input[name="nbp_setting"]:checked').val();
			if(button == "in"){
				jQuery('.nbc').hide();
			}
			if(button == "out"){
				jQuery('.nbc').show();
			}
		});
	});
	
	
	//range slider
	var rangeSlider = function(){
	  var slider = jQuery('.range-slider'),
		  range = jQuery('.range-slider__range'),
		  value = jQuery('.range-slider__value');
		
	  slider.each(function(){

		value.each(function(){
		  var value = jQuery(this).prev().attr('value');
		  jQuery(this).html(value);
		});

		range.on('input', function(){
		  jQuery(this).next(value).html(this.value);
		});
	  });
	};
	rangeSlider();
</script>
<br>
<style>
	.awp_bale_offer {
		background-image: url("<?php echo esc_url( GG_PLUGIN_URL . '/img/awp-bale.jpg' ); ?>");
		background-repeat:no-repeat;
		padding:30px;
	}
	.awp_bale_offer h1 {
		font-size:35px;
		color:#FFFFFF;
	}
	.awp_bale_offer h3 {
		font-size:25px;
		color:#FFFFFF;
	}
</style>
<div class="row awp_bale_offer">
	<div class="col-md-6">
		<h1><?php esc_html_e( 'Plugin Bale Offer', 'new-grid-gallery' ); ?></h1>
		<h3><?php esc_html_e( 'Get All Premium Plugin - 23+ Premium Plugins ( Personal Licence) in just $179', 'new-grid-gallery' ); ?> </h3>
		<h4> <?php esc_html_e( '8+ gallery plugins, 3+ Slider Plugin , Event , Testimonial , Contact Form, Social media, Popup Box, Weather Effect, Social share', 'new-grid-gallery' ); ?> </h4>
		<h3><strike><?php esc_html_e( '$349', 'new-grid-gallery' ); ?></strike> <?php esc_html_e( 'For $179 Only', 'new-grid-gallery' ); ?></h3>
	</div>
	<div class="col-md-6">
		<a href="https://awplife.com/account/signup/all-premium-plugins" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'BUY NOW', 'new-grid-gallery' ); ?></a>
	</div>
</div>
<p class="">
	<br>
	<a href="https://awplife.com/wordpress-plugins/grid-gallery-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Premium Version Details', 'new-grid-gallery' ); ?></a>
	<a href="https://awplife.com/demo/grid-gallery-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Check Live Demo', 'new-grid-gallery' ); ?></a>
	<a href="https://awplife.com/demo/grid-gallery-premium-admin-demo/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Try Admin Demo', 'new-grid-gallery' ); ?></a>
</p>
