<?php

/**
 * Main Class
 *
 * main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */

class WB_VC_BAIC_Main extends WPBakeryShortCode{
	
	static $count= 0;

	function __construct() {

		//parent::__construct( $settings ); // !Important to call parent constructor to active all logic for shortcode.
		
		add_shortcode('wb_vc_before_after_image_comparison', array( $this, 'before_after_image_comparison') );
		vc_add_shortcode_param( 'wbvcbaic_upgrade_pro_btn', 'wbvcebaic_pro_btn_settings_field' );

		//add_action( 'vc_before_init', array( $this, 'map_element' ) );
		 $this->map_element();
		// Register CSS and JS
		 $this->load_css_and_js();
        //add_action( 'wp_enqueue_scripts', array( $this, 'load_css_and_js' ) );
	}


	public function before_after_image_comparison( $atts, $content='' ){
		static::$count++;

		$css = '';
	    // Params extraction
	    $attr = shortcode_atts(
		            array(
		                'before_image' => '',
		                'after_image' => '',
		                'image_size'  => 'medium',
		                'css' => ''
		            ),
		            $atts
		        );

	    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, '' ), '', $atts );

		$id = 'wb_before_after_'.static::$count.'_'.time();
		$image_size = $attr['image_size'];
		$before_text = __('Before', 'before-after-image-comparison-slider-for-visual-composer');
		$after_text = __('After', 'before-after-image-comparison-slider-for-visual-composer');
		wp_enqueue_style('wb-vc-before-after-slider');

		wp_enqueue_script('wb-vc-before-after-slider-event-move');
		wp_enqueue_script('wb-vc-before-after-slider-library');
		wp_enqueue_script('wb-vc-before-after-slider-js');
		ob_start();
?>

		<div
			class="twentytwenty-container wb_vc_baic_container"
			id="<?php echo esc_attr( $id ); ?>"
			data-before-text = "<?php echo __('Before', 'before-after-image-comparison-slider-for-visual-composer'); ?>"
			data-after-text = "<?php echo __('After', 'before-after-image-comparison-slider-for-visual-composer'); ?>"
		>
			<?php echo wp_get_attachment_image( $attr['before_image'], $image_size ); ?>
			<?php echo wp_get_attachment_image( $attr['after_image'], $image_size ); ?>
		</div>
<!--  -->
<?php
		return ob_get_clean();
	}

	public function load_css_and_js(){
		wp_register_style( 'wb-vc-before-after-slider', WB_VC_BAIC_URL . 'assets/vendors/twentytwenty/css/twentytwenty.css', array(), '1.0.0', 'all' );

		wp_register_script( 'wb-vc-before-after-slider-event-move', WB_VC_BAIC_URL . 'assets/vendors/twentytwenty/js/jquery.event.move.js', array( 'jquery' ), '1.0.0', true );
		wp_register_script( 'wb-vc-before-after-slider-library', WB_VC_BAIC_URL . 'assets/vendors/twentytwenty/js/jquery.twentytwenty.js', array( 'jquery' ), '1.0.0', true );
		wp_register_script( 'wb-vc-before-after-slider-js', WB_VC_BAIC_URL . 'assets/js/main.js', array( 'jquery' ), '1.0.0', true );
	}

	public function map_element(){
		vc_map(
			array(
			    'name' => __('Before After Image Comparison Slider', 'before-after-image-comparison-slider-for-visual-composer'),
			    'base' => 'wb_vc_before_after_image_comparison',
			    'description' => __('Compare Two Image for your before and after Effects', 'before-after-image-comparison-slider-for-visual-composer'),
			    'category' => __('Web Builders Elements', 'before-after-image-comparison-slider-for-visual-composer'),
			    "content_element" => true,
			    'front_enqueue_css'       => WB_VC_BAIC_URL . 'assets/css/front-enqueue-css.css',
			    'front_enqueue_js'	=>	array(WB_VC_BAIC_URL . 'assets/vendors/twentytwenty/js/jquery.event.move.js', WB_VC_BAIC_URL . 'assets/vendors/twentytwenty/js/jquery.twentytwenty.js', WB_VC_BAIC_URL . 'assets/js/main.js', WB_VC_BAIC_URL . 'assets/js/front_enqueue.js'),
			    'params' => array(
				    			array(
				                    'type' => 'attach_image',
			                        'holder' => 'img',
			                        'class' => 'wb-before-image',
			                        'heading' => __( 'Before Image', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'before_image',
			                        'value' => __( '', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'description' => __( 'Upload Before Image From Here. <br /><a target="_blank" href="'.WB_VC_BAIC_PRO_URL.'"">(Upgrade to PRO for More Features)</a>', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'admin_label' => false,
			                        'weight' => 0,
			                        'group' => '',
			                    ),
			                    array(
				                    'type' => 'attach_image',
			                        'holder' => 'img',
			                        'class' => 'wb-after-image',
			                        'heading' => __( 'After Image', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'after_image',
			                        'value' => __( '', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'description' => __( 'Upload After Image From Here. <br /><a target="_blank" href="'.WB_VC_BAIC_PRO_URL.'"">(Upgrade to PRO for More Features)</a>', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'admin_label' => false,
			                        'weight' => 0,
			                        'group' => '',
			                    ),
			                    
			                    array(
				                    'type' => 'dropdown',
			                        'heading' => __( 'Image Size', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'image_size',
			                        'value' => get_intermediate_image_sizes(),
			                        'std'	=> 'medium',
			                        'description' => __( 'Select Image Size from here. <br /><a target="_blank" href="'.WB_VC_BAIC_PRO_URL.'"">(Upgrade to PRO for More Features)</a>', 'before-after-image-comparison-slider-for-visual-composer' ),
			                    ),
			                    array(
				                    'type' => 'wbvcbaic_upgrade_pro_btn',
			                        'heading' => __( 'Orientation', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'wbvcbaic_orientation',
			                        'value' => 'Upgrade to PRO',
			                        'std'	=> 'medium',
			                        'description' => __( 'Change Orientation between <strong>Horizontal</strong> and <strong>Vertical</strong>. Default is <strong>Horizontal</strong>', 'before-after-image-comparison-slider-for-visual-composer' ),
			                    ),
			                    array(
				                    'type' => 'wbvcbaic_upgrade_pro_btn',
			                        'heading' => __( 'Default Offset', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'wbvcbaic_default_offset',
			                        'value' => 'Upgrade to PRO',
			                        'std'	=> 'medium',
			                        'description' => __( 'Default Slider Offset. Choose between 0 to 10. Default value is <strong>5</strong>', 'before-after-image-comparison-slider-for-visual-composer' ),
			                    ),
			                    array(
				                    'type' => 'wbvcbaic_upgrade_pro_btn',
			                        'heading' => __( 'Before Text', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'wbvcbaic_before_text',
			                        'value' => 'Upgrade to PRO',
			                        'std'	=> 'medium',
			                        'description' => __( 'Change Before Text from here.', 'before-after-image-comparison-slider-for-visual-composer' ),
			                    ),
			                    array(
				                    'type' => 'wbvcbaic_upgrade_pro_btn',
			                        'heading' => __( 'After Text', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'wbvcbaic_after_text',
			                        'value' => 'Upgrade to PRO',
			                        'std'	=> 'medium',
			                        'description' => __( 'Change After Text from here.', 'before-after-image-comparison-slider-for-visual-composer' ),
			                    ),
			                    array(
				                    'type' => 'wbvcbaic_upgrade_pro_btn',
			                        'heading' => __( 'Click to Move', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'wbvcbaic_click_to_move',
			                        'value' => 'Upgrade to PRO',
			                        'std'	=> 'medium',
			                        'description' => __( 'Move the slider on mouse click.', 'before-after-image-comparison-slider-for-visual-composer' ),
			                    ),
			                    array(
				                    'type' => 'wbvcbaic_upgrade_pro_btn',
			                        'heading' => __( 'Move on Hover', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'wbvcbaic_move_on_hover',
			                        'value' => 'Upgrade to PRO',
			                        'std'	=> 'medium',
			                        'description' => __( 'Move the slider on mouse hover.', 'before-after-image-comparison-slider-for-visual-composer' ),
			                    ),
			                    array(
			                        'type' => 'css_editor',
			                        'heading' => __( 'Css', 'before-after-image-comparison-slider-for-visual-composer' ),
			                        'param_name' => 'css',
			                        'group' => __( 'Design options', 'before-after-image-comparison-slider-for-visual-composer' ),
			                    ),  
				            ),
			));
	}

}