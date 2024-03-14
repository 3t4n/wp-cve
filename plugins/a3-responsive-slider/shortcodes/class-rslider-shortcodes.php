<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * A3 Responsive Slider Shortcode
 *
 * Table Of Contents
 *
 * A3_Responsive_Slider_Shortcode()
 * init()
 * add_rslider_button()
 * rslider_generator_popup()
 * parse_shortcode_a3_responsive_slider()
 */

namespace A3Rev\RSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Shortcode
{
	
	public function __construct () {
		$this->init();
	}
	
	public function init () {
		add_action( 'media_buttons', array( $this, 'add_rslider_button'), 100 );
		add_action( 'admin_footer', array( $this, 'rslider_generator_popup') );
		add_shortcode( 'a3_responsive_slider', array( $this, 'parse_shortcode_a3_responsive_slider') );
		
		// Make track a3_responsive_slider shortcode used in content when save post
		add_action( 'save_post', array( $this, 'track_shortcode_is_used' ), 11, 3 );
		
	}
	
	public function track_shortcode_is_used( $post_ID, $post, $update ) {
		$is_post_edit_page = in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) );
        if ( ! $is_post_edit_page ) return;
		
		if ( empty( $post_ID ) || empty( $post ) || empty( $_POST ) ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( ! current_user_can( 'edit_post', $post_ID ) ) return;
		
		$post_type = get_post_type( $post_ID );
		$have_shortcode = false;
		$our_shortcode = 'a3_responsive_slider';
		
		// Remove old data for this post ID
		delete_post_meta( $post_ID, '_a3_slider_is_used' );
		delete_post_meta( $post_ID, '_a3_slider_is_used_' . $post_type );
			
		// Check if a3_responsive_slider shortcode is in the content
		if ( has_shortcode( $post->post_content, $our_shortcode ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER );
			if ( ! empty( $matches ) && is_array( $matches ) && count( $matches ) > 0 ) {
				foreach ( $matches as $shortcode ) {
					if ( $our_shortcode === $shortcode[2] ) {
						$attr = shortcode_parse_atts( $shortcode[3] );
						$my_attr = shortcode_atts( array(
			 							'id' 				=> 0
									), $attr );
						$slider_id = $my_attr['id'];
						if ( $slider_id > 0 ) {
							$have_shortcode = true;
							$a3_slider_is_used = get_post_meta( $post_ID, '_a3_slider_is_used', false );
							if ( is_array( $a3_slider_is_used ) && in_array( $slider_id, $a3_slider_is_used ) ) continue;
							
							add_post_meta( $post_ID, '_a3_slider_is_used', $slider_id );
							add_post_meta( $post_ID, '_a3_slider_is_used_' . $post_type , $slider_id );
						}
					}
				}
			}
		} 

	}
	
	public function add_rslider_button() {
		$is_post_edit_page = in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) );
        if ( ! $is_post_edit_page ) return;
		
		echo '<a href="#TB_inline?width=640&height=500&inlineId=a3-rslider-wrap" class="thickbox button a3-rslider-add-shortcode" title="' . __( 'Insert shortcode', 'a3-responsive-slider' ) . '"><span class="a3-rslider-add-shortcode_icon"></span>'.__( 'Sliders', 'a3-responsive-slider' ).'</a>';
	}
	
	public function rslider_generator_popup() {
		$is_post_edit_page = in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) );
        if ( ! $is_post_edit_page ) return;
		
		$list_sliders = get_posts( array(
			'posts_per_page'		=> -1,
			'orderby'				=> 'title',
			'order'					=> 'ASC',
			'post_type'				=> 'a3_slider',
			'post_status'			=> 'publish',
			'meta_query'			=> array( 
						array(
							'key'		=> '_a3_slider_id',
							'value'		=> 1,
							'compare'	=> '>=',
							'type'		=> 'NUMERIC',
						)
			),
		));
		
		?>
		<div id="a3-rslider-wrap" style="display:none">
       
        	<fieldset style="border:1px solid #DFDFDF; padding:0 20px; background: #FFF; margin-top:15px;"><legend style="font-weight:bold; font-size:14px;"><?php _e( 'Insert Responsive Slider', 'a3-responsive-slider' ); ?></legend>
            <div id="a3-rslider-content" class="a3-rslider-content a3-rslider-shortcode-popup-container" style="text-align:left;">
                <p><label for="rslider_id"><?php _e( 'Select Slider', 'a3-responsive-slider' ); ?>:</label> 
                    <select style="width:300px" id="rslider_id" name="rslider_id">
                    <?php
					echo '<option value="">'.__( 'Please select...', 'a3-responsive-slider' ).'</option>';	
					if ( is_array( $list_sliders ) && count( $list_sliders ) > 0 ) {
						foreach ( $list_sliders as $slider ) {
					?>
                    	<option value="<?php echo esc_attr( $slider->ID ); ?>" ><?php echo $slider->post_title; ?></option>
                    <?php

						}
					}
					wp_reset_postdata();
                    ?>
                    </select>
                </p>
                <p><label for="rslider_description"><?php _e( 'Description', 'a3-responsive-slider' ); ?>:</label> <textarea style="width:100%; height:60px" id="rslider_description" name="rslider_description" placeholder="<?php _e( 'Enter the description that show at bottom of Slider', 'a3-responsive-slider' ); ?>"></textarea></p>
                <p><label for="rslider_align"><?php _e( 'Slider Alignment', 'a3-responsive-slider' ); ?>:</label> 
                <select style="width:120px" id="rslider_align" name="rslider_align">
                	<option value="none" selected="selected"><?php _e( 'None', 'a3-responsive-slider' ); ?></option>
                    <option value="left-wrap"><?php _e( 'Left - wrap', 'a3-responsive-slider' ); ?></option>
                    <option value="left"><?php _e( 'Left - no wrap', 'a3-responsive-slider' ); ?></option>
                    <option value="center"><?php _e( 'Center', 'a3-responsive-slider' ); ?></option>
                    <option value="right-wrap"><?php _e( 'Right - wrap', 'a3-responsive-slider' ); ?></option>
                    <option value="right"><?php _e( 'Right - no wrap', 'a3-responsive-slider' ); ?></option>
                </select> <span class="description"><?php _e( 'Wrap is text wrap like images', 'a3-responsive-slider' ); ?></span></p>
				<p><label for="rslider_width"><?php _e( 'Slider Width', 'a3-responsive-slider' ); ?>:</label> <input style="width:50px;" size="10" id="rslider_width" name="rslider_width" type="text" value="300" /> 
                <select style="width:60px" id="rslider_width_type" name="rslider_width_type">
                	<option value="px" selected="selected">px</option>
                    <option value="%">%</option>
                </select>
                </p>
                <p><label for="rslider_tall_type"><?php _e( 'Slider Tall Type', 'a3-responsive-slider' ); ?>:</label> 
                <label style="width:auto; float:none"><input type="radio" name="rslider_tall_type" class="rslider_tall_type" id="rslider_tall_type_fixed" value="fixed" checked="checked" /> <?php _e( 'Fixed', 'a3-responsive-slider' ); ?></label> &nbsp;&nbsp;&nbsp;
                <label style="width:auto; float:none"><input type="radio" name="rslider_tall_type" class="rslider_tall_type" id="rslider_tall_type_dynamic" value="dynamic" /> <?php _e( 'Dynamic', 'a3-responsive-slider' ); ?></label>
                </p>
                <p class="rslider_tall_type_fixed_container"><label for="rslider_height"><?php _e( 'Slider Height', 'a3-responsive-slider' ); ?>:</label> <input style="width:50px;" size="10" id="rslider_height" name="rslider_height" type="text" value="250" /> px
                </p>
                <p><label for=""><strong><?php _e( 'Slider Margin', 'a3-responsive-slider' ); ?></strong>:</label><br /> 
                        <label for="rslider_margin_top" style="width:auto; float:none"><?php _e( 'Above', 'a3-responsive-slider' ); ?>:</label><input style="width:50px;" size="10" id="rslider_margin_top" name="rslider_margin_top" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="rslider_margin_bottom" style="width:auto; float:none"><?php _e( 'Below', 'a3-responsive-slider' ); ?>:</label> <input style="width:50px;" size="10" id="rslider_margin_bottom" name="rslider_margin_bottom" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="rslider_margin_left" style="width:auto; float:none"><?php _e( 'Left', 'a3-responsive-slider' ); ?>:</label> <input style="width:50px;" size="10" id="rslider_margin_left" name="rslider_margin_left" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="rslider_margin_right" style="width:auto; float:none"><?php _e( 'Right', 'a3-responsive-slider' ); ?>:</label> <input style="width:50px;" size="10" id="rslider_margin_right" name="rslider_margin_right" type="text" value="10" />px
                </p>
                <p><label for=""><strong><?php _e( 'Description Container Margin', 'a3-responsive-slider' ); ?></strong>:</label><br /> 
                        <label for="rs_desc_margin_top" style="width:auto; float:none"><?php _e( 'Above', 'a3-responsive-slider' ); ?>:</label><input style="width:50px;" size="10" id="rs_desc_margin_top" name="rs_desc_margin_top" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="rs_desc_margin_bottom" style="width:auto; float:none"><?php _e( 'Below', 'a3-responsive-slider' ); ?>:</label> <input style="width:50px;" size="10" id="rs_desc_margin_bottom" name="rs_desc_margin_bottom" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="rs_desc_margin_left" style="width:auto; float:none"><?php _e( 'Left', 'a3-responsive-slider' ); ?>:</label> <input style="width:50px;" size="10" id="rs_desc_margin_left" name="rs_desc_margin_left" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="rs_desc_margin_right" style="width:auto; float:none"><?php _e( 'Right', 'a3-responsive-slider' ); ?>:</label> <input style="width:50px;" size="10" id="rs_desc_margin_right" name="rs_desc_margin_right" type="text" value="10" />px
                </p>
			</div>
            <div style="clear:both;height:0px"></div>
            <p><input type="button" class="button button-primary" value="<?php _e( 'Insert Shortcode', 'a3-responsive-slider' ); ?>" onclick="a3_rslider_add_shortcode();"/> 
            <input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e('Cancel', 'a3-responsive-slider' ); ?>" />
			</p>
            </fieldset>
            
		</div>
        <script type="text/javascript">
		(function($) {
		$(document).ready(function() {
			$("input.rslider_tall_type").on('change',function() {
				if ( $("input.rslider_tall_type:checked").val() == 'fixed') {
					$(".rslider_tall_type_fixed_container").slideDown();
				} else {
					$(".rslider_tall_type_fixed_container").slideUp();
				}
			});
			
		});
		})(jQuery);
		function a3_rslider_add_shortcode(){
			var selected_rslider_id = jQuery("#rslider_id").val();
			if (selected_rslider_id == '') {
				alert('<?php _e( 'Please select Slider', 'a3-responsive-slider' ); ?>');
				return false;	
			}
			var rslider_description 	= jQuery("#rslider_description").val();
			var rslider_align 			= jQuery("#rslider_align").val();
			var rslider_width 			= jQuery("#rslider_width").val();
			var rslider_width_type 		= jQuery("#rslider_width_type").val();
			var rslider_tall_type 		= jQuery(".rslider_tall_type:checked").val();
			var rslider_height 			= jQuery("#rslider_height").val();
			var rslider_margin_top		= jQuery("#rslider_margin_top").val();
			var rslider_margin_bottom	= jQuery("#rslider_margin_bottom").val();
			var rslider_margin_left		= jQuery("#rslider_margin_left").val();
			var rslider_margin_right	= jQuery("#rslider_margin_right").val();
			var rs_desc_margin_top		= jQuery("#rs_desc_margin_top").val();
			var rs_desc_margin_bottom	= jQuery("#rs_desc_margin_bottom").val();
			var rs_desc_margin_left		= jQuery("#rs_desc_margin_left").val();
			var rs_desc_margin_right	= jQuery("#rs_desc_margin_right").val();
			
			var win = window.dialogArguments || opener || parent || top;
			win.send_to_editor('[a3_responsive_slider id="' + selected_rslider_id 
			+ '" inline_post="true' 
			+ '" description="' + rslider_description 
			+ '" align="' + rslider_align 
			+ '" width="' + rslider_width 
			+ '" width_type="' + rslider_width_type 
			+ '" tall_type="' + rslider_tall_type 
			+ '" height="' + rslider_height 
			+ '" margin_top="' + rslider_margin_top 
			+ '" margin_bottom="' + rslider_margin_bottom 
			+ '" margin_left="' + rslider_margin_left 
			+ '" margin_right="' + rslider_margin_right 
			+ '" desc_margin_top="' + rs_desc_margin_top 
			+ '" desc_margin_bottom="' + rs_desc_margin_bottom 
			+ '" desc_margin_left="' + rs_desc_margin_left 
			+ '" desc_margin_right="' + rs_desc_margin_right 
			+ '"]');
		}
		
		</script>
		<?php
	}
		
	public function parse_shortcode_a3_responsive_slider( $attributes ) {
		$attr = shortcode_atts( array(
			 						'id' 				=> '',
									'inline_post'		=> false,
									'description' 		=> '',
									'align'				=> 'none',
									'width'				=> 300,
									'width_type'		=> 'px',
									'tall_type'			=> 'fixed',
									'height'			=> 250,
									'margin_top'		=> 10,
									'margin_bottom'		=> 10,
									'margin_left'		=> 10,
									'margin_right'		=> 10,
									'desc_margin_top'	=> 10,
									'desc_margin_bottom'=> 10,
									'desc_margin_left'	=> 10,
									'desc_margin_right'	=> 10,
        						), $attributes );

		// XSS ok
		$align              = esc_attr( $attr['align'] );
		$width              = esc_attr( $attr['width'] );
		$width_type         = esc_attr( $attr['width_type'] );
		$tall_type          = esc_attr( $attr['tall_type'] );
		$height             = esc_attr( $attr['height'] );
		$margin_top         = esc_attr( $attr['margin_top'] );
		$margin_bottom      = esc_attr( $attr['margin_bottom'] );
		$margin_left        = esc_attr( $attr['margin_left'] );
		$margin_right       = esc_attr( $attr['margin_right'] );
		$desc_margin_top    = esc_attr( $attr['desc_margin_top'] );
		$desc_margin_bottom = esc_attr( $attr['desc_margin_bottom'] );
		$desc_margin_left   = esc_attr( $attr['desc_margin_left'] );
		$desc_margin_right  = esc_attr( $attr['desc_margin_right'] );

		
		$inline_post = $attr['inline_post'];
		$description = $attr['description'];

		
		$slider_id = $attr['id'];
		$slider_data = get_post( $slider_id );
		if ( $slider_data == NULL ) return '';
		
		$have_slider_id = get_post_meta( $slider_id, '_a3_slider_id' , true );
		if ( $have_slider_id < 1 ) return '';
		
		$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
						
		$slide_items = Data::get_all_images_from_slider_client( $attr['id'] );
		
		global $a3_rslider_template1_global_settings;
		
		$templateid = 'template1';
		
		$slider_template = 'template-1';
	
		global ${'a3_rslider_'.$templateid.'_dimensions_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
		
		$dimensions_settings = ${'a3_rslider_'.$templateid.'_dimensions_settings'};
		
		$output = '';
		$rslider_custom_style = '';
		$rslider_inline_style = '';
		$rslider_desc_container_style = '';
		$description_html = '';
		$rslider_shortcode_wrap = false;
			
		if ( $inline_post ) {
		
			if ( $align == 'center' ) $rslider_custom_style .= 'float:none;margin:auto;display:block;';
			elseif ( $align == 'left-wrap' ) $rslider_custom_style .= 'float:left;';
			elseif ( $align == 'right-wrap' ) $rslider_custom_style .= 'float:right;';
			else $rslider_custom_style .= 'float:'. $align .';';
				
			if( $align == 'left-wrap' || $align == 'right-wrap' ) $rslider_shortcode_wrap = true;
					
			if ( $width > 0 ) $rslider_custom_style .= 'width:' . $width . $width_type . ' !important;';
			if ( $tall_type == 'fixed' ) $rslider_inline_style .= 'height:'.$height.'px !important;';
			else $rslider_inline_style .= 'height:auto;';
			
			// override dimension from current template
			if ( $width > 0 ) {
				if ( $width_type == 'px' ) {
					$dimensions_settings['is_slider_responsive'] = 0;
					$dimensions_settings['slider_width'] = $width;
				} else {
					$dimensions_settings['is_slider_responsive'] = 1;
					$dimensions_settings['slider_wide_responsive'] = $width;
				}
			}
			if ( $tall_type == 'fixed' ) {
				$dimensions_settings['is_slider_tall_dynamic'] = 0;
				$dimensions_settings['slider_height_fixed'] = $height;
			} else {
				$dimensions_settings['is_slider_tall_dynamic'] = 1;
			}
			
				if ( $margin_top >= 0 ) $rslider_inline_style .= 'margin-top:'.$margin_top.'px;';
				if ( $margin_bottom >= 0 ) $rslider_inline_style .= 'margin-bottom:'.$margin_bottom.'px;';
				if ( $margin_left >= 0 ) $rslider_inline_style .= 'margin-left:'.$margin_left.'px;';
				if ( $margin_right >= 0 ) $rslider_inline_style .= 'margin-right:'.$margin_right.'px;';
			
			if ( $desc_margin_top >= 0 ) $rslider_desc_container_style .= 'margin-top:'.$desc_margin_top.'px;';
			if ( $desc_margin_bottom >= 0 ) $rslider_desc_container_style .= 'margin-bottom:'.$desc_margin_bottom.'px;';
			if ( $desc_margin_left >= 0 ) $rslider_desc_container_style .= 'margin-left:'.$desc_margin_left.'px;';
			if ( $desc_margin_right >= 0 ) $rslider_desc_container_style .= 'margin-right:'.$desc_margin_right.'px;';
			
			if ( trim( $description ) != '' )
				$description_html = '<div style="clear:both;"></div><div class="a3-rslider-description-container a3-rslider-description-container-'.$slider_template.'" style="'.$rslider_desc_container_style.'"><div class="a3-rslider-description-container-bg"></div><div class="a3-rslider-shortcode-description">'.stripslashes( $description ).'</div></div><div style="clear:both;"></div>';
			
		}
		
		$device_detect = new Mobile_Detect();
		if ( $device_detect->isMobile() ) {
			$rslider_custom_style = '';
			$rslider_inline_style = '';
			$rslider_desc_container_style = '';
			$rslider_shortcode_wrap = false;
		
			$dimensions_settings['is_slider_responsive'] = 1;
			$dimensions_settings['slider_wide_responsive'] = 100;
			$rslider_custom_style .= 'width:100% !important;';
			if ( $inline_post ) {
				if ( $tall_type == 'fixed' ) $rslider_inline_style .= 'height:'.$height.'px !important;';
				else $rslider_inline_style .= 'height:auto;';
				
				if ( $tall_type == 'fixed' ) {
					$dimensions_settings['is_slider_tall_dynamic'] = 0;
					$dimensions_settings['slider_height_fixed'] = $height;
				} else {
					$dimensions_settings['is_slider_tall_dynamic'] = 1;
				}
				
					if ( $margin_top >= 0 ) $rslider_inline_style .= 'margin-top:'.$margin_top.'px;';
					if ( $margin_bottom >= 0 ) $rslider_inline_style .= 'margin-bottom:'.$margin_bottom.'px;';
				
				if ( $desc_margin_top >= 0 ) $rslider_desc_container_style .= 'margin-top:'.$desc_margin_top.'px;';
				if ( $desc_margin_bottom >= 0 ) $rslider_desc_container_style .= 'margin-bottom:'.$desc_margin_bottom.'px;';
				
				if ( trim( $description ) != '' )
					$description_html = '<div style="clear:both;"></div><div class="a3-rslider-description-container a3-rslider-description-container-'.$slider_template.'" style="'.$rslider_desc_container_style.'"><div class="a3-rslider-description-container-bg"></div><div class="a3-rslider-shortcode-description">'.stripslashes( $description ).'</div></div><div style="clear:both;"></div>';
			}
		}
		
		if ( ! $rslider_shortcode_wrap ) $output .= '<div style="clear:both;"></div>';
		
		$output .= Display::dispay_slider( $slide_items, $slider_template, $dimensions_settings, $slider_settings, $rslider_custom_style, $rslider_inline_style, $description_html );
		
		if ( ! $rslider_shortcode_wrap ) $output .= '<div style="clear:both;"></div>';
		
		return $output;
	}
	
	public function get_all_posts_use_shortcode_slider( $slider_id, $post_type = '' ) {
		global $wpdb;
		if ( trim( $post_type ) != '' ) $meta_key_search = '_a3_slider_is_used_'.$post_type;
		else $meta_key_search = '_a3_slider_is_used';
		
		$all_posts = $wpdb->get_results("SELECT * FROM ".$wpdb->postmeta." WHERE meta_key='".$meta_key_search."' AND meta_value='".$slider_id."' ");
		if ( $all_posts ) return $all_posts;
		else return false;
	}
	
	public function get_post_count_use_shortcode_slide( $slider_id, $post_type = '' ) {
		global $wpdb;
		if ( trim( $post_type ) != '' ) $meta_key_search = '_a3_slider_is_used_'.$post_type;
		else $meta_key_search = '_a3_slider_is_used';
		
		$post_count = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->postmeta." WHERE meta_key='".$meta_key_search."' AND meta_value='".$slider_id."' ");
		return $post_count;
	}
	
	public function show_all_posts_use_shortcode_slider( $slider_id ) {
		$post_types = get_post_types( array( 'public' => true, '_builtin' => true ) , 'objects' );	
		$custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false ) , 'objects' );
		
		$all_post_types = array_merge( $post_types, $custom_post_types );
		
		if ( is_array( $all_post_types ) && count( $all_post_types ) > 0 ) {
			foreach ( $all_post_types as $post_type => $post_type_data ) {
				
				// Don't show for some post type
				if ( in_array( $post_type, array( 'attachment', 'revision', 'nav_menu_item', 'a3_slider' ) ) ) continue;
				
				$all_posts = $this->get_all_posts_use_shortcode_slider( $slider_id, $post_type );
				if ( $all_posts && is_array( $all_posts ) && count( $all_posts ) > 0 ) {
				?>
                	<div class="a3rev_panel_inner">
                    	<h3><?php _e( 'All', 'a3-responsive-slider' ); ?> <?php echo $post_type_data->labels->name; ?> <?php _e( 'have this slider is embed into content', 'a3-responsive-slider' ); ?></h3>
                        <table class="form-table"><tbody>
                        	<?php
							foreach ( $all_posts as $post_meta ) {
								$my_post = get_post( $post_meta->post_id );
								// Debug
								/*preg_match_all( '/' . get_shortcode_regex() . '/s', $my_post->post_content, $matches, PREG_SET_ORDER );
								echo '<pre>';
								var_dump($matches);
								echo '</pre>';*/
                            	if ( $my_post ) {
							?>		
                            <tr valign="top">
                                <td class="forminp forminp-text">
                                    <div class="a3_slider_used_on_post a3_slider_used_on_post_<?php echo $my_post->ID; ?>">
                                    	<span title="<?php _e( 'Remove the shortcode from the content of this post', 'a3-responsive-slider' ); ?>" href="#" class="a3_slider_remove_shortcode" slider-id="<?php echo $slider_id; ?>" post-id="<?php echo $my_post->ID; ?>" >[<?php _e( 'Remove Shortcode', 'a3-responsive-slider' ); ?>]</span> 
                                        <a href="<?php echo get_edit_post_link( $my_post->ID ); ?>"><?php echo $my_post->post_title; ?></a>
                                    </div>
                                </td>
                            </tr>
                            <?php
								}
							}
							?>
                        </tbody></table>
                    </div>
                <?php
				}
				
			}
		}
	}				
}
