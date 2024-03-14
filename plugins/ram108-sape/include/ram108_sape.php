<?php

// class

class ram108_sape extends ram108_sape_plugin {

	function _init(){

		// include sape.php
		if ( $this->settings->user ) {
			@define( '_SAPE_USER', $this->settings->user );
			@include_once( realpath ( $_SERVER['DOCUMENT_ROOT'] ).'/'._SAPE_USER.'/sape.php');
		}

		// check if plugin ready
		if ( !class_exists('SAPE_base') ) return;

		// init
		$this->_sape_init();
		$this->_sape_context();
		$this->_sape_shortcode();
	}

	// SAPE INIT

	function _sape_init(){

		global $sape, $sape_context;

		$options = array(
			'multi_site' => true,
			'charset' => get_bloginfo('charset'),
			'show_counter_separately' => true,
		);

		if ( $this->settings->debug ) $options += array(
			'force_show_code' => true,
			'verbose' => true,
		);

		$sape = new SAPE_client( $options );
		$sape_context = new SAPE_context( $options );
	}

	// SAPE CONTEXT

	function _sape_context(){

		if ( $this->settings->context ) {
			if ( $this->settings->disable_texturize ) remove_filter('the_content', 'wptexturize');
			add_filter('the_content', array( $this, 'ram108_sape_context'), 9 );
		}

		if ( $this->settings->context_excerpt ) {
			if ( $this->settings->disable_texturize ) remove_filter('the_excerpt', 'wptexturize');
			add_filter('the_excerpt', array( $this, 'ram108_sape_context'), 9 );
		}
	}

	function ram108_sape_context( $text ){
		global $sape_context;
		return $sape_context->replace_in_text_segment( $text );
	}

	// SAPE SHORTCODE

	function _sape_shortcode(){

		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'the_excerpt', 'do_shortcode' );
		add_shortcode('sape', array( $this, 'ram108_sape_shortcode') );
	}

	function ram108_sape_shortcode( $args ){

		global $sape;

		$text = @$args['block'] ?
			$sape->return_block_links( @$args['count'] ? $args['count'] : NULL ) :
			$sape->return_links( @$args['count'] ? $args['count'] : NULL );

		return $text ? '<div class="ram108-slink">'.$text.'</div>' : '';
	}

	// OTHER

	function _wp_footer(){
		global $sape;
		if ( method_exists($sape, 'return_counter') ) echo $sape->return_counter();
	}

	function _register_scripts(){
		if ( !$this->settings->disable_css ) wp_enqueue_style( $this->id, plugins_url('style.css', _RAM108_SAPE ) );
	}
}

// init class

$ram108_sape = new ram108_sape;
