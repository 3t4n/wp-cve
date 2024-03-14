<?php

namespace Dev4Press\v43\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PanelWizard extends Panel {
	protected $sidebar = false;

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->init_default_subpanels();
	}

	public function show() {
		$this->load( 'content-wizard.php' );
	}

	public function enqueue_scripts() {
		$this->a()->e()->css( 'wizard' )->js( 'wizard' );
	}
}
