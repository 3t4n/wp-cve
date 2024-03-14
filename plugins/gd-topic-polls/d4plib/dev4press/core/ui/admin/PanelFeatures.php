<?php

namespace Dev4Press\v43\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PanelFeatures extends Panel {
	protected $form = true;

	public $settings_class = '';

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->init_default_subpanels();
	}

	protected function init_default_subpanels() {
		$this->subpanels = array(
			'index' => array(
				'title' => __( 'Features Index', 'd4plib' ),
				'icon'  => 'ui-cabinet',
				'info'  => __( 'Main control panel for all the plugin individual features.', 'd4plib' ),
			),
		);
	}

	public function enqueue_scripts_early() {
		$this->a()->enqueue->js( 'mark' )->js( 'confirmsubmit' );
	}

	public function get_filter_counters() : array {
		return array(
			'all'       => array(
				'label'    => __( 'All', 'd4plib' ),
				'selector' => '._is-feature',
			),
			'always-on' => array(
				'label'    => __( 'Always On', 'd4plib' ),
				'selector' => '._is-feature._is-always-on',
			),
			'active'    => array(
				'label'    => __( 'Active', 'd4plib' ),
				'selector' => '._is-feature._is-active',
			),
			'disabled'  => array(
				'label'    => __( 'Disabled', 'd4plib' ),
				'selector' => '._is-feature:not(._is-active)',
			),
		);
	}

	public function get_filter_buttons() : array {
		return array(
			'all'              => array(
				'label'    => __( 'All', 'd4plib' ),
				'selector' => '._is-feature',
				'default'  => true,
			),
			'always-on'        => array(
				'label'    => __( 'Always On', 'd4plib' ),
				'selector' => '._is-feature._is-always-on',
			),
			'always-on-active' => array(
				'label'    => __( 'Always On + Active', 'd4plib' ),
				'selector' => '._is-feature._is-active',
			),
			'active'           => array(
				'label'    => __( 'Active', 'd4plib' ),
				'selector' => '._is-feature._is-active:not(._is-always-on)',
			),
			'disabled'         => array(
				'label'    => __( 'Disabled', 'd4plib' ),
				'selector' => '._is-feature:not(._is-active)',
			),
		);
	}

	public function get_filter_counters_for_override() : array {
		return array(
			'all'      => array(
				'label'    => __( 'All', 'd4plib' ),
				'selector' => '._is-feature',
			),
			'active'   => array(
				'label'    => __( 'Override Active', 'd4plib' ),
				'selector' => '._is-feature._is-active',
			),
			'disabled' => array(
				'label'    => __( 'Override Disabled', 'd4plib' ),
				'selector' => '._is-feature:not(._is-active)',
			),
		);
	}

	public function get_filter_buttons_for_override() : array {
		return array(
			'all'      => array(
				'label'    => __( 'All', 'd4plib' ),
				'selector' => '._is-feature',
				'default'  => true,
			),
			'active'   => array(
				'label'    => __( 'Override Active', 'd4plib' ),
				'selector' => '._is-feature._is-active',
			),
			'disabled' => array(
				'label'    => __( 'Override Disabled', 'd4plib' ),
				'selector' => '._is-feature:not(._is-active)',
			),
		);
	}
}
