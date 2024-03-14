<?php
namespace Codexpert\Restrict_Elementor_Widgets;

use Codexpert\Plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Front
 * @author codexpert <hello@codexpert.io>
 */
class Front extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'REW_DEBUG' ) && REW_DEBUG ? '' : '.min';

		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/front{$min}.css", REW ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/front{$min}.js", REW ), [ 'jquery' ], $this->version, true );
		
		$localized = [
			'ajaxurl'	=> admin_url( 'admin-ajax.php' )
		];
		wp_localize_script( $this->slug, 'REW', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function restrict_render_section( $should_render, $object )	{

		$settings = $object->get_settings_for_display();
		
		if ( !isset( $settings['rew_enable_restriction'] ) ) return $should_render;

		if ( rew_is_eligible( $settings ) ) return $should_render;
		
		rew_render_message( $settings );
		
		return false;
	}

	public function restrict_render_widgets( $content, $widget )	{

		$settings = $widget->get_settings_for_display();

		if ( ! isset( $settings['rew_enable_restriction'] ) ) return $content;

		extract( $settings );

		if ( rew_is_eligible( $settings ) ) return $content;

		return rew_render_message( $settings, false );
	}
}