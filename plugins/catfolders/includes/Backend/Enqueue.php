<?php

namespace CatFolders\Backend;

use CatFolders\Core\Base;
use CatFolders\Classes\Vite;
use CatFolders\I18n;
use CatFolders\Traits\Singleton;
use CatFolders\Classes\Helpers;
class Enqueue extends Base {
	use Singleton;

	public function doHooks() {
		if ( ! parent::initialize() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminScripts' ) );
	}

	public function enqueueAdminScripts( $screenId ) {
		Vite::enqueueVite();

		if ( function_exists( 'get_current_screen' ) ) {
			if ( 'upload.php' === $screenId || 'media_page_mla-menu' === $screenId ) {
				wp_register_script( 'jquery-resizable', CATF_PLUGIN_URL . 'assets/js/jquery-resizable.min.js', array(), CATF_VERSION, true );
				wp_enqueue_script( 'jquery-resizable' );
			}
		}

		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );

		if ( wp_is_mobile() ) {
			wp_enqueue_script( 'jquery-touch-punch-fixed', CATF_PLUGIN_URL . 'assets/js/jquery.ui.touch-punch.js', array( 'jquery-ui-widget', 'jquery-ui-mouse' ), CATF_VERSION, false );
		}

		wp_style_add_data( 'module/catfolders/main.tsx', 'rtl', 'replace' );

		$license = get_option( 'catf_license', array() );
		$license = wp_parse_args( $license, array( 'status' => false ) );
		wp_localize_script(
			'module/catfolders/main.tsx',
			'catfData',
			array(
				'nonce'       => wp_create_nonce( 'catf_nonce' ),
				'apiSettings' => array(
					'rest_nonce' => wp_create_nonce( 'wp_rest' ),
					'rest_url'   => esc_url_raw( rest_url( 'CatFolders/v1' ) ),
				),
				'folders'     => array(
					array(
						'term_id'   => -1,
						'term_name' => 'All Folders',
					),
					array(
						'term_id'   => 0,
						'term_name' => 'Uncategorized',
					),
				),
				'license'     => $license,
				'mediaMode'   => Helpers::getMediaMode(),
				'settings'    => $this->userSettings,
				'pluginUrl'   => CATF_PLUGIN_URL,
				'i18n'        => apply_filters( 'catf_i18n', I18n::getTranslation() ),
				'adminUrl'    => get_admin_url(),
			)
		);
	}
}
