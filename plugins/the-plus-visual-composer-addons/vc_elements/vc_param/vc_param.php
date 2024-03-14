<?php 
if ( ! function_exists( 'pt_theplus_helper_vc_fonts' ) ) {
	function pt_theplus_helper_vc_fonts( $fonts_list ) {
	    $poppins->font_family = 'Poppins';
	    $poppins->font_types = '300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal';
	    $poppins->font_styles = 'regular';
	    $poppins->font_family_description = esc_html_e( 'Select font family', 'pt_theplus' );
	    $poppins->font_style_description = esc_html_e( 'Select font styling', 'pt_theplus' );
	    $fonts_list[] = $poppins;
		
		$overpass->font_family = 'Overpass';
	    $overpass->font_types = '300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal';
	    $overpass->font_styles = 'regular';
	    $overpass->font_family_description = esc_html_e( 'Select font family', 'pt_theplus' );
	    $overpass->font_style_description = esc_html_e( 'Select font styling', 'pt_theplus' );
	    $fonts_list[] = $overpass;
		
		$rubik->font_family = 'Rubik';
	    $rubik->font_types = '300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal';
	    $rubik->font_styles = 'regular';
	    $rubik->font_family_description = esc_html_e( 'Select font family', 'pt_theplus' );
	    $rubik->font_style_description = esc_html_e( 'Select font styling', 'pt_theplus' );
	    $fonts_list[] = $rubik;
		
		$worksans->font_family = 'Work Sans';
	    $worksans->font_types = '300 light regular:300:normal,400 regular:400:normal,500 bold regular:500:normal,600 bold regular:600:normal,700 bold regular:700:normal';
	    $worksans->font_styles = 'regular';
	    $worksans->font_family_description = esc_html_e( 'Select font family', 'pt_theplus' );
	    $worksans->font_style_description = esc_html_e( 'Select font styling', 'pt_theplus' );
	    $fonts_list[] = $worksans;
		
	    return $fonts_list;
	}
}
add_filter('vc_google_fonts_get_fonts_filter', 'pt_theplus_helper_vc_fonts');
	/*----------------------------heading title vc param-------------------------*/
	if (!class_exists('pt_theplus_heading_param')) {
		class pt_theplus_heading_param
		{
			function __construct()
			{
				if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('pt_theplus_heading_param', array(
                    $this,
                    'pt_theplus_heading_param_callback'
					));
				}
			}
			
			function pt_theplus_heading_param_callback($settings, $value)
			{
				$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
				$class      = isset($settings['class']) ? $settings['class'] : '';
				$text       = isset($settings['text']) ? $settings['text'] : '';
				$premium	=isset($settings['premium']) ? '<span class="premium-text">('.$settings['premium'].')</span>' : '';
				$type       = "";
				$output     = '<h4 class="wpb_vc_param_value ' . esc_attr($class) . '">' . $text . ' '.$premium.'</h4>';
				
				$output .= '<input type="hidden"  class="wpb_vc_param_value ' . esc_attr($param_name . ' ' . $type . ' ' . $class) . '" name="' . esc_attr($param_name) . '" value="' . $value . '" />';
				return $output;
			}
			
		}
		
		$pt_theplus_heading_param = new pt_theplus_heading_param();
	}
	
	/*----------------------------heading title vc param-------------------------*/
	
	/*----------------------------toggle on/off param-------------------------------*/
	if (!class_exists('pt_theplus_checkbox_param')) {
		
		class pt_theplus_checkbox_param
		{
			
			function __construct()
			{
				if (function_exists('vc_add_shortcode_param')) {
					vc_add_shortcode_param('pt_theplus_checkbox', array(
                    $this,
                    'pt_theplus_checkbox'
					));
				}
			}
			
			function pt_theplus_checkbox($settings, $value)
			{
				$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
				$type       = isset($settings['type']) ? $settings['type'] : '';
				$options    = isset($settings['options']) ? $settings['options'] : '';
				$class      = isset($settings['class']) ? $settings['class'] : '';
				
				$output      = $checked = '';
				$check_value = $param_name . $value;
				if (is_array($options) && !empty($options)) {
					foreach ($options as $key => $opts) {
						$checked         = "";
						$animation_class = 'right-active';
						$data_val        = $key;
						if ($value == $key) {
							$checked         = "checked";
							$animation_class = '';
						}
						
						$uniq_id = uniqid('pt_theplus_checkbox-' . rand());
						if (isset($opts['label']))
                        $label = $opts['label'];
						else
                        $label = '';
						
						$output .= '<div class="pt_theplus_checkbox_wrap">
						<input type="checkbox" name="' . esc_attr($param_name) . '" value="' . esc_attr($value) . '" class="wpb_vc_param_value ' . esc_attr($param_name) . ' ' . esc_attr($type) . ' ' . esc_attr($class) . ' pt_theplus_checkbox_flip" id="' . esc_attr($uniq_id) . '" ' . $checked . '>
						<label class="pt_theplus_checkbox" for="' . esc_attr($check_value) . '" data-tg-on="On" data-tg-off="Off" data-value="' . esc_attr($data_val) . '">
						<span class="button-animation ' . esc_attr($animation_class) . '"></span>
						</label>
						</div>';
					}
				}
				$output .= '<script type="text/javascript">
				jQuery("#' . esc_js($uniq_id) . '").next(".pt_theplus_checkbox").click(function(){
				var $self = jQuery(this),
				$button = $self.find(".button-animation"),
				$checkbox = $self.siblings("#' . esc_js($uniq_id) . '");
				
				$button.toggleClass("right-active");
				
				if($self.find(".button-animation").hasClass("right-active")) {
				$checkbox.removeAttr("checked").val("");
				} else {
				$checkbox.attr("checked","checked").val($self.data("value"));
				}
				
				$checkbox.trigger("change");
				});
				</script>';
				
				return $output;
				return $output;
			}
		}
		$pt_theplus_checkbox_param = new pt_theplus_checkbox_param();
	}
	/*----------------------------toggle on/off param-------------------------------*/
	/*------------------------------------ image radio selected-------------------------*/
if ( ! class_exists( 'pt_theplus_Radio_Image_Param' ) ) {
class pt_theplus_Radio_Image_Param {
	function __construct() {
		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'radio_select_image', array( &$this, 'radio_image_settings_field' ), THEPLUS_PLUGIN_URL.'vc_elements/js/admin/pt-theplus-admin.js' );
		}
	}
		function radio_image_settings_field( $settings, $value ) {
			
					$options      = isset( $settings['options'] ) ? $settings['options'] : '';
					$useextension = ( isset( $settings['useextension'] ) && '' !== $settings['useextension'] ) ? $settings['useextension'] : 'true';
					$simple = ( isset( $settings['simple_mode'] ) && '' !== $settings['simple_mode'] ) ? $settings['simple_mode'] : true;
					

					$class      = isset( $settings['class'] ) ? $settings['class'] : '';

					$output = $selected = '';
					$css_option = str_replace( '#', 'hash-', vc_get_dropdown_option( $settings, $value ) );

					$output .= '<select name="'
							   . $settings['param_name']
							   . '" class="wpb_vc_param_value wpb-input wpb-select ' . $class
							   . ' ' .$settings['param_name']
							   . ' ' . $settings['type']
							   . ' ' . $css_option
							   . '" data-option="' . $css_option . '">';

					if ( is_array( $options ) ) {
						foreach ( $options as $key => $val ) {
							if ( 'true' !== $useextension ) {
								$temp          = pathinfo( $key );
								$temp_filename = $temp['filename'];
								$key           = $temp_filename;
							}

							if ( '' !== $css_option && $css_option === $key ) {
								$selected = ' selected="selected"';
							} else {
								$selected = '';
							}
							
							if($simple) {
								$tooltip = $key;
								$img_url = $val;
							} else {
								$tooltip = $val['tooltip'];
								$img_url = $val['src'];
							}

							$output .= '<option data-tooltip="'.esc_attr($tooltip).'"  data-img-src="' . esc_url($img_url) . '"  value="' . esc_attr($key) . '" ' . $selected . '>';
						}
					}
					$output .= '</select>';

					return $output;
				}
		}
	$pt_theplus_Radio_Image_Param = new pt_theplus_Radio_Image_Param();
}

/*------------------------------------ image radio selected-------------------------*/