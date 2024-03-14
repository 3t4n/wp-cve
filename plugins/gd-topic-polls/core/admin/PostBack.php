<?php

namespace Dev4Press\Plugin\GDPOL\Admin;

use Dev4Press\Plugin\GDPOL\Basic\InstallDB;
use Dev4Press\v43\Core\Admin\PostBack as BasePostBack;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PostBack extends BasePostBack {
	protected function remove() {
		$data = $_POST['gdpoltools'];

		$remove  = isset( $data['remove'] ) ? (array) $data['remove'] : array();
		$message = 'nothing-removed';

		if ( ! empty( $remove ) ) {
			if ( isset( $remove['settings'] ) && $remove['settings'] == 'on' ) {
				$this->a()->settings()->remove_plugin_settings_by_group( 'settings' );
			}

			if ( isset( $remove['objects'] ) && $remove['objects'] == 'on' ) {
				$this->a()->settings()->remove_plugin_settings_by_group( 'objects' );
			}

			if ( isset( $remove['drop'] ) && $remove['drop'] == 'on' ) {
				InstallDB::instance()->drop();

				if ( ! isset( $remove['disable'] ) ) {
					$this->a()->settings()->mark_for_update();
				}
			} else if ( isset( $remove['truncate'] ) && $remove['truncate'] == 'on' ) {
				InstallDB::instance()->truncate();
			}

			if ( isset( $remove['disable'] ) && $remove['disable'] == 'on' ) {
				gdpol()->deactivate();

				wp_redirect( admin_url( 'plugins.php' ) );
				exit;
			}

			$message = 'removed';
		}

		wp_redirect( $this->a()->current_url() . '&message=' . $message );
		exit;
	}
}
