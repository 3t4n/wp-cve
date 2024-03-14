<?php
 /**
  * 
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author     
  * @Websites: 
  *
  */
if( ! defined( 'ABSPATH' ) ) {
    die( DARKLUPLITE_ALERT_MSG );
}

if(!class_exists('DarklupLite_Image_Switch_Param')) {
	class DarklupLite_Image_Switch_Param {
		function __construct() {
			if(function_exists('vc_add_shortcode_param')) {
				vc_add_shortcode_param('imageswitch' , array(&$this, 'switch_settings_field' ));
			}
		}
	
		function switch_settings_field($settings, $value){

			ob_start();

			if(!empty($value)){
			    $hval = $value;
			}
			else{
			    $hval = '';
			}

			?>
			<div class="image-select-content-wrapper">
				<style>
					.darkluplite-image-select-item label {
						display: block;
					}
					.image-select-content-wrapper .darkluplite-image-select-item {
						width: 18%;
					    padding: 5px;
					    border: 2px solid #bababa;
					    margin: 5px;
					    float: left;
					}
					.image-select-content-wrapper .darkluplite-image-select-item img{
						width: 100%;
					}
					.darkluplite-image-select-item label input {
					    display: none;
					}
					.darkluplite-image-select-item {
						transition: all 0.7s;
						cursor: pointer;
					}
					.darkluplite-image-select-item.darkluplite_block-active, .darkluplite-image-select-item:hover {
					    border-color: #3700B3;
					    position: relative;
					}
				</style>
				<input type="hidden" value="<?php echo esc_attr( $hval ); ?>" id="imageradio<?php echo esc_attr($settings['param_name']); ?>" name="<?php echo esc_attr($settings['param_name']); ?>" class="wpb_vc_param_value wpb-input">
	            <?php 
	            foreach( $settings['options'] as $key => $option ):

	            	$active = '';
	            	if( $value == $key ) {
	            		$active = 'darkluplite_block-active';
	            	}

	            ?>
	            <div class="darkluplite-image-select-item <?php echo esc_attr( $active ); ?>">
	                <label for="darkluplite_switch_<?php echo esc_attr( $key ); ?>" class="image-item">
	                    <img src="<?php echo esc_url( $option['url'] ); ?>" />
	                    <input id="darkluplite_switch_<?php echo esc_attr( $key ); ?>" class="wpb_vc_param_value wpb-radioinput<?php echo esc_attr( $settings['param_name'] ) . ' ' .esc_attr( $settings['type'] ) . '_field'; ?>"  type="radio" name="image_radio_<?php echo esc_attr( $settings['param_name'] ); ?>" value="<?php echo esc_html( $key ); ?>" <?php checked( $value, $key ); ?> />
	                </label>
	            </div>
	            <?php 
	        	endforeach;
	            ?>

			<script>
			jQuery(".wpb-radioinput<?php echo esc_html($settings['param_name']); ?>").change(function(){
			    var s = jQuery(this).val();
			    jQuery("#imageradio<?php echo esc_html($settings['param_name']); ?>").val(s);
			    jQuery('.darkluplite_block-active').removeClass('darkluplite_block-active');
			    jQuery(this).closest('.darkluplite-image-select-item').addClass('darkluplite_block-active');

			});
			</script>
	        </div>
			<?php

			return ob_get_clean();
		}
		
	}
	
	$DarklupLite_Image_Switch_Param = new DarklupLite_Image_Switch_Param();

}