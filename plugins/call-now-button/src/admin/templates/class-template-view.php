<?php

namespace cnb\admin\templates;

// don't load directly
defined( 'ABSPATH' ) || die( '-1' );

use cnb\utils\CnbAdminFunctions;
use cnb\utils\CnbUtils;

class Template_View {

	public function header() {
		echo 'Templates';
	}
	/**
	 * @var Template[]
	 */
	private $templates;

	public function __construct() {
		global $cnb_domain;
		$this->templates = ( new Templates() )->get_templates();
		// Add domain
		if ($cnb_domain && ! is_wp_error($cnb_domain) && is_array( $this->templates ) && ! is_wp_error( $this->templates )) {
			foreach ( $this->templates as $template ) {
				$template->button->domain = $cnb_domain->id;
			}
		}
	}

	public function render() {
		global $cnb_domain;
		$admin_functions = new CnbAdminFunctions();
		// Register CSS/JS
		wp_enqueue_script( CNB_SLUG . '-templates' );
		if ( $this->templates && ! is_wp_error( $this->templates ) ) {
			wp_localize_script( CNB_SLUG . '-templates', 'cnb_templates_data', $this->templates );
			wp_localize_script( CNB_SLUG . '-templates', 'cnb_templates_ajax_data',
                [
                        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce( 'cnb-button-edit' ),
	                    'actionTypes' => $admin_functions->cnb_get_action_types(),
	                    'displayModes' => $admin_functions->get_display_modes(),
	                    'currentDomain' => $cnb_domain,
	                    'upgradeLink' => (new CnbUtils())->get_cnb_domain_upgrade(),
                ] );
		}

		add_action( 'cnb_header_name', array( $this, 'header' ) );
		do_action( 'cnb_header' );

		echo '<div id="call-now-button-app"></div>';

		do_action( 'cnb_footer' );
	}
}
