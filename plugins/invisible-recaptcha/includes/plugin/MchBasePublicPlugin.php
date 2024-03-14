<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Plugin;

abstract class MchBasePublicPlugin extends MchBasePlugin
{

	public abstract function enqueuePublicScriptsAndStyles();
	public abstract function registerAfterSetupThemeHooks();

	protected function __construct()
	{
		parent::__construct();

		add_action('wp_enqueue_scripts', array( $this, 'enqueuePublicScriptsAndStyles' ));
		add_action('after_setup_theme', array( $this, 'registerAfterSetupThemeHooks' ));

	}

	public function initializePlugin()
	{
		parent::initializePlugin();
		//$this->enqueuePublicScriptsAndStyles();
	}

	public static function registerShortCode($tagName, $callBackHandler)
	{
		add_shortcode($tagName, $callBackHandler );
	}

	private function __clone()
	{}

	private function __wakeup()
	{}

}