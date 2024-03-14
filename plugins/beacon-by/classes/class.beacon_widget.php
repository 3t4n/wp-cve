<?php defined( 'ABSPATH' ) or die( '' );


/**
 * Beacon widget class
 *
 * Beacon ebook widget
 *
 * @package Beacon Wordpress plugin
 * @author Beacon
**/
class Beacon_widget extends WP_Widget {

	function __construct() {

		parent::__construct(
			 
			'beacon_widget',
			__('Beacon Widget', 'beacon' ),
			array (
				'description' => __( 'Data capture form for your Beacon issues', 'beacon' )
			) );

	}


	function form( $instance ) {
	 
		$default = array( 
			'widget_code' =>  ''); 

		$code = get_option('beacon_promote_options');

		$data = array(
			'code' => $code,
			'data' => unserialize($code)
		);


		$output = $this->get_view('admin', $data);

		echo $output;

	}



	function widget ($args, $instance) {

		$css_src = BEACONBY_PLUGIN_URL . 'css/beacon-widget.css';

		wp_enqueue_style( 'beaconby-widget', $css_src); 

		$data = $args;
		$data['host'] = (isset($_SERVER) 
						&& array_key_exists('HTTP_HOST', $_SERVER))
			? esc_html($_SERVER['HTTP_HOST'])
			: '';

		$code = get_option('beacon_promote_options');
		$code = unserialize($code);
		$data['widget']  = $this->get_view('widget', $code);

		if (!$code)
		{
			$output = $this->get_view('not_configured', $data);
		}
		else
		{
			$output = $this->get_view('form', $data);
		}

		echo $output;

	}


	public static function get_view($view, $data = array()) {


		$view = BEACONBY_PLUGIN_PATH . "views/widget/$view.php";

		$data = ($data) ? $data : array();
		// extract($data);
		ob_start();
		require($view);			
		$view = ob_get_contents();
		ob_end_clean();


		return $view;

	}


}

