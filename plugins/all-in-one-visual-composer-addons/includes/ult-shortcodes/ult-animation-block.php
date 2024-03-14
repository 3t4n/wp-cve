<?php 
	if (class_exists('WPBakeryShortCodesContainer')) {
		class WPBakeryShortCode_wdo_ult_animation_block extends WPBakeryShortCodesContainer {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_animation_block"	=> 'bounce',
				), $atts));
				wp_enqueue_style( 'wdo-animate-css', ULT_URL.'assets/css/animate.css');
				wp_enqueue_script( 'wdo-wow-js', ULT_URL.'/assets/js/wow.min.js', array('jquery'));
				wp_enqueue_script( 'wdo-custom-js', ULT_URL.'/assets/js/wdo-custom.js', array('jquery','wdo-wow-js'));
				ob_start(); ?>
					<div class="wdo-animation-container">
						<div class="animation-block wow <?php echo $wdo_animation_block; ?>">
							<?php echo do_shortcode($content); ?>
						</div>
					</div>
			<?php
			return ob_get_clean();
			}
		}
	}

	$animations = array(
		'bounce' => 'bounce',
		'flash' => 'flash',
		'pulse' => 'pulse',
		'rubberBand' => 'rubberBand',
		'shake' => 'shake',
		'swing' => 'swing',
		'tada' => 'tada',
		'wobble' => 'wobble',
		'jello' => 'jello',
		'bounceIn' => 'bounceIn',
		'bounceInDown' => 'bounceInDown',
		'bounceInUp' => 'bounceInUp',
		'bounceOut' => 'bounceOut',
		'bounceOutDown' => 'bounceOutDown',
		'bounceOutLeft' => 'bounceOutLeft',
		'bounceOutRight' => 'bounceOutRight',
		'bounceOutUp' => 'bounceOutUp',
		'fadeIn' => 'fadeIn',
		'fadeInDown' => 'fadeInDown',
		'fadeInDownBig' => 'fadeInDownBig',
		'fadeInLeft' => 'fadeInLeft',
		'fadeInLeftBig' => 'fadeInLeftBig',
		'fadeInRightBig' => 'fadeInRightBig',
		'fadeInUp' => 'fadeInUp',
		'fadeInUpBig' => 'fadeInUpBig',
		'fadeOut' => 'fadeOut',
		'fadeOutDown' => 'fadeOutDown',
		'fadeOutDownBig' => 'fadeOutDownBig',
		'fadeOutLeft' => 'fadeOutLeft',
		'fadeOutLeftBig' => 'fadeOutLeftBig',
		'fadeOutRightBig' => 'fadeOutRightBig',
		'fadeOutUp' => 'fadeOutUp',
		'fadeOutUpBig' => 'fadeOutUpBig',
		'flip' => 'flip',
		'flipInX' => 'flipInX',
		'flipInY' => 'flipInY',
		'flipOutX' => 'flipOutX',
		'flipOutY' => 'flipOutY',
		'fadeOutDown' => 'fadeOutDown',
		'lightSpeedIn' => 'lightSpeedIn',
		'lightSpeedOut' => 'lightSpeedOut',
		'rotateIn' => 'rotateIn',
		'rotateInDownLeft' => 'rotateInDownLeft',
		'rotateInDownRight' => 'rotateInDownRight',
		'rotateInUpLeft' => 'rotateInUpLeft',
		'rotateInUpRight' => 'rotateInUpRight',
		'rotateOut' => 'rotateOut',
		'rotateOutDownLeft' => 'rotateOutDownLeft',
		'rotateOutDownRight' => 'rotateOutDownRight',
		'rotateOutUpLeft' => 'rotateOutUpLeft',
		'rotateOutUpRight' => 'rotateOutUpRight',
		'slideInUp' => 'slideInUp',
		'slideInDown' => 'slideInDown',
		'slideInLeft' => 'slideInLeft',
		'slideInRight' => 'slideInRight',
		'slideOutUp' => 'slideOutUp',
		'slideOutDown' => 'slideOutDown',
		'slideOutLeft' => 'slideOutLeft',
		'slideOutRight' => 'slideOutRight',
		'zoomIn' => 'zoomIn',
		'zoomInDown' => 'zoomInDown',
		'zoomInLeft' => 'zoomInLeft',
		'zoomInRight' => 'zoomInRight',
		'zoomInUp' => 'zoomInUp',
		'zoomOut' => 'zoomOut',
		'zoomOutDown' => 'zoomOutDown',
		'zoomOutLeft' => 'zoomOutLeft',
		'zoomOutUp' => 'zoomOutUp',
		'hinge' => 'hinge',
		'rollIn' => 'rollIn',
		'rollOut' => 'rollOut'
	);

	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Animation Block',
			"description" => __("Add animated content on scroll.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_animation_block',
			"content_element" => true,
        	"show_settings_on_create" => true,
			"is_container" => true,
			"js_view" => 'VcColumnView',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/animation-block-icon.png',
			'params' => array(

					array(
						"type" => "dropdown",
						"heading" => "Animation Style",
						"param_name" => "wdo_animation_block",
						"value" => $animations,
						"description" => "Select animation style.Animation work when you scroll page.",
					),
					

			)
		) );
	}
 ?>