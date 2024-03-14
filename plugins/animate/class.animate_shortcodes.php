<?php
if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

if ( !class_exists('Animate_shortcodes') ) {

class Animate_shortcodes{
        function __construct(){
		add_shortcode('animate', array($this, 'animate'));
	}

	public static function animate( $atts, $content = null){
		ob_start();
                $content = do_shortcode(trim($content));
                $atts = array_map( 'esc_attr', (array)$atts );

		$a = shortcode_atts( array(
			'style'                 => '',
                        'data-wow-duration'     => '',
                        'data-wow-delay'        => '',
                        'data-wow-offset'       => '',
                        'data-wow-iteration'    => '',
			'infinitely'		=> 'no',
		        'custom_class'          => ''
		), $atts );

		$data_wow_duration 	= ($a["data-wow-duration"]) 	? 'data-wow-duration="'.$a["data-wow-duration"].'s"' : '' ;
		$data_wow_delay 	= ($a["data-wow-delay"]) 	? 'data-wow-delay="'.$a["data-wow-delay"].'s"' : '' ;
		$data_wow_offset        = ($a["data-wow-offset"])       ? 'data-wow-offset="'.$a["data-wow-offset"].'"' : '' ;
		$data_wow_iteration = '';

		if ($a["infinitely"] == 'yes'){
			$data_wow_iteration = 'data-wow-iteration="infinite"';
		}elseif($a["data-wow-iteration"]){
			$data_wow_iteration = 'data-wow-iteration="'.$a["data-wow-iteration"].'"';
		}
		?>
		<section class="<?php echo get_option('animate_option_boxClass'); ?> <?php echo $a["style"];?> <?php echo $a["custom_class"];?>" <?php echo $data_wow_duration; ?> <?php echo $data_wow_delay; ?> <?php echo $data_wow_offset; ?> <?php echo $data_wow_iteration; ?>><?php echo $content; ?></section>
		<?php
		return ob_get_clean();
	}
}
}

