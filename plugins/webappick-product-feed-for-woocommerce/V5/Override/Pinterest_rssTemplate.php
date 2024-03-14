<?php

namespace CTXFeed\V5\Override;


/**
 * Class Pinterest_rssTemplate
 *
 * @package    CTXFeed\V5\Override
 * @subpackage CTXFeed\V5\Override
 */
class Pinterest_rssTemplate {
	public function __construct() {
		add_filter( 'ctx_xml_header_template_variables', [
			$this,
			'ctx_pinterest_rss_xml_header_template_variables_callback'
		] );
		
		add_filter( 'woo_feed_get_pinterest_rss_pubDate_attribute', [
			$this,
			'woo_feed_get_pinterest_rss_pubDate_attribute_callback'
		], 9, 3 );
	}
	
	public function ctx_pinterest_rss_xml_header_template_variables_callback( $variables ) {
		
		$variables["{DateTimeNow}"] = date( 'r', strtotime( current_time( 'mysql' ) ) );
		
		return $variables;
	}
	
	public function woo_feed_get_pinterest_rss_pubDate_attribute_callback( $output ) {
		if ( ! empty( $output ) ) {
			return date( 'r', strtotime( $output ) );
		}
		
		return $output;
	}
}