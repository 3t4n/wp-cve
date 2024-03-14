<?php
namespace Codexpert\Restrict_Elementor_Widgets;

use Codexpert\Plugin\Base;

/**
 * @package Plugin
 * @subpackage Settings
 * @author codexpert <hello@codexpert.io>
 */
class Settings extends Base {

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
	
	public function init_menu() {
		
		$settings = [
			'id'            => $this->slug,
			'label'         => __( 'Restrict Elementor', 'restrict-elementor-widgets' ),
			'title'         => $this->name,
			'header'        => $this->name,
			'capability' 	=> 'manage_options',
			'icon'       	=> 'dashicons-privacy',
			'position'   	=> 59,
			'sections'      => [
				'rew-extensions' => [
					'id'        => 'rew-extensions',
					'label'     => __( 'Extensions', 'restrict-elementor-widgets' ),
					'icon'      => 'dashicons-forms',
					'color'		=> '#6941fb',
					'hide_form'	=> true,
					'fields'    => [],
				],
			],
		];

		new \Codexpert\Plugin\Settings( $settings );
	}
	
	public function tab_content( $section ) {
		if( 'rew-help' == $section['id'] ) {
			echo rew_get_template( 'settings/help' );
		}

		elseif( 'rew-extensions' == $section['id'] ) {
			echo rew_get_template( 'settings/extensions' );
		}
	}
}