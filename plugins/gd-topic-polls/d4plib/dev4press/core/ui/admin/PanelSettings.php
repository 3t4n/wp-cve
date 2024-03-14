<?php

namespace Dev4Press\v43\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PanelSettings extends Panel {
	protected $form = true;
	protected $form_multiform = true;

	public $settings_class = '';

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->init_default_subpanels();
	}

	protected function init_default_subpanels() {
		$this->subpanels = array(
			'index' => array(
				'title' => __( 'Settings Index', 'd4plib' ),
				'icon'  => 'ui-cog',
				'info'  => __( 'All plugin settings are split into several panels, and you access each starting from the right.', 'd4plib' ),
			),
			'full'  => array(
				'title' => __( 'All Settings', 'd4plib' ),
				'icon'  => 'ui-cogs',
				'info'  => __( 'All plugin settings are displayed on this page, and you can use live search to find the settings you need.', 'd4plib' ),
			),
		);
	}

	public function enqueue_scripts_early() {
		$this->a()->enqueue->js( 'mark' )->js( 'confirmsubmit' );
	}
}
