<?php

/**
 * Creating a class to handle a WordPress shortcode is overkill in most cases.
 * However, it is extremely handy when your shortcode requires helper functions.
 */
class ContentAd__Includes__Shortcode {

    protected
        $atts = array(),
        $content = false,
        $output = false;

    protected static $defaults = array();

    public static function contentad_shortcode( $atts, $content = '', $tag = '' ) {
		
		if( isset($atts['tag']) ) { // If Template Tag ID is specified
			$atts = array(
				'meta_query' => array(
					'placement' => array(
						'key' => 'placement',
						'value' => 'in_function'
					),
					'status' => array(
						'key' => '_ca_widget_inactive',
						'compare' => 'NOT EXISTS'
					),
					'widget_id' => array(
						'key' => '_widget_id',
						'value' => $atts['tag']
					)
				)
			);
		} elseif( isset($atts['widget']) ) { // If Shortcode Widget ID is specified
			$atts = array(
				'meta_query' => array(
					'placement' => array(
						'key' => 'placement',
						'value' => 'in_shortcode'
					),
					'status' => array(
						'key' => '_ca_widget_inactive',
						'compare' => 'NOT EXISTS'
					),
					'widget_id' => array(
						'key' => '_widget_id',
						'value' => $atts['widget']
					)
				)
			);
		} else { // If no ID is specified, return all Template Tags (for backward compatibility)
			$atts = array(
				'meta_query' => array(
					'placement' => array(
						'key' => 'placement',
						'value' => 'in_function'
					)
				)
			);
		}

		$shortcode = new self( $atts, $content );
		return $shortcode->output;
    }

    private function __construct( $atts, $content ) {
        $this->atts = $atts;
        $this->content = $content;
        $this->init();
    }

    protected function init() {
        $this->output = ContentAd__Includes__API::get_ad_code($this->atts);
    }

    function get_att( $name ) {
        return array_key_exists( $name, $this->atts ) ? $this->atts[$name] : false;
    }

}