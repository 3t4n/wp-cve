<?php

class ram108_sape_plugin {

	protected
		$id = 'ram108-sape',
		$settings;

	public
		$error = FALSE;

	public function __construct(){

		if ( FALSE == ( $this->settings = &$GLOBALS['ram108'] [$this->id] ['settings'] ) )
			$this->settings = $GLOBALS['ram108'] [$this->id] ['settings'] = new ram108_sape_settings;

		add_action('init', array( $this, '_init') );
		add_action('wp_head', array( $this, '_wp_head') );
		add_action('wp_footer', array( $this, '_wp_footer') );
		add_action('wp_enqueue_scripts', array( $this, '_register_scripts') );
	}

	public function _init(){
	}

	public function _wp_head(){
	}

	public function _wp_footer(){
	}

	public function _register_scripts(){
	}

	public function _error( $message ){
		$this->error []= $message;
	}
}