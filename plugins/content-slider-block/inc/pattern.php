<?php
class CSBPattern{
	public function __construct(){
		add_action( 'plugins_loaded', [$this, 'onPluginsLoaded'] );
	}

	function onPluginsLoaded(){
		$patterns = wp_json_file_decode( __DIR__ . '/pattern.json', [ 'associative' => true ] );
		$patterns = csbIsPremium() ? $patterns : array_filter( $patterns, function( $item ) {
			return !isset($item['pro']) || !$item['pro'];
		} );

		// Register Pattern Category
		if ( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category( 'csbPattern', [ 'label' => __( 'Content Slider', 'content-slider-block' ) ] );
		}

		// Register Pattern
		if ( !empty( $patterns ) ) {
			foreach ( $patterns as $pattern ) {
				if ( function_exists( 'register_block_pattern' ) ) {
					register_block_pattern( $pattern['name'], [
						'title'			=> $pattern['title'],
						'content'		=> $pattern['content'],
						'description'	=> $pattern['description'],
						'categories'	=> [ 'csbPattern' ],
						'keywords'		=> $pattern['keywords'],
						'blockTypes'	=> $pattern['blockTypes'],
						'viewportWidth'	=> 1200
					] );
				}
			}
		}
	}
}
new CSBPattern();