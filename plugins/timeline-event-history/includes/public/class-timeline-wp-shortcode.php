<?php

/**
 *
 */
class Timeline_WP_Shortcode {

	private $loader;

	function __construct() {

		
		$this->loader  = new Timeline_WP_Template_Loader();

		
		add_shortcode( 'timeline_wp', array( $this, 'timeline_wp_shortcode_handler' ) );
		add_shortcode( 'Timeline_wp', array( $this, 'timeline_wp_shortcode_handler' ) );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'add_timeline_wp_scripts' ) );
        // echo "Hello";
		return;
	}

	public function add_timeline_wp_scripts() {

	}

	public function timeline_wp_shortcode_handler( $atts ) {
        
		$default_atts = array(
			'id' => false,
			'align' => '',
		);

		
		$atts = wp_parse_args( $atts, $default_atts );

		if ( ! $atts['id'] ) {
			return esc_html__( 'Timeline not found.', timeline_wp );
		}

		// Check if is an old Timeline_WP post or new.
		$timeline = get_post( $atts['id'] );
		if ( 'timeline_wp' != get_post_type( $timeline ) ) {
			$timeline_wp_posts = get_posts( array(
				'post_type' => 'timeline_wp',
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key'     => 'timeline-wp-id',
						'value'   => $atts['id'],
						'compare' => '=',
					),
				),
			) );

			if ( empty( $timeline_wp_posts ) ) {
				return esc_html__( 'Gallery not found.', timeline_wp );
			}

			$atts['id'] = $timeline_wp_posts[0]->ID;

		}
		
		/* Get Timeline settings */
		$settings = get_post_meta( $atts['id'], 'timeline-wp-settings', true );
		$default  = Timline_WP_CPT_Fields_Helper::get_defaults();
		$settings = wp_parse_args( $settings, $default );
		
		ob_start();
		
        if (!empty($settings['type'])) {
            require TIMELINE_WP_TEMPLATES  . $settings['type'] . '/index.php';
        		}
    	$html = ob_get_clean();
    	return $html;
	}
}
new Timeline_WP_Shortcode();