<?php
/**
 * Elementor Importer
 *
 * @package Demo Importer Plus
 */

namespace Elementor\TemplateLibrary;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\Elementor\Plugin' ) ) {
	return;
}

use Elementor\Core\Base\Document;
use Elementor\Core\Editor\Editor;
use Elementor\DB;
use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Core\Settings\Page\Model;
use Elementor\Modules\Library\Documents\Library_Document;
use Elementor\Plugin;
use Elementor\Utils;

/**
 * Elementor template library local source.
 *
 * Elementor template library local source handler class is responsible for
 * handling local Elementor templates saved by the user locally on his site.
 *
 * @since 1.0.0
 */
class Demo_Importer_Plus_Batch_Processing_Elementor extends Source_Local {

	/**
	 * Import
	 *
	 * @return void
	 */
	public function import() {
		$post_types = \Demo_Importer_Plus_Batch_Processing::get_post_types_supporting( 'elementor' );

		if ( empty( $post_types ) && ! is_array( $post_types ) ) {
			return;
		}

		$post_ids = \Demo_Importer_Plus_Batch_Processing::get_pages( $post_types );
		if ( empty( $post_ids ) && ! is_array( $post_ids ) ) {
			return;
		}

		foreach ( $post_ids as $post_id ) {
			$this->import_single_post( $post_id );
		}
	}
	/**
	 * Update post meta.
	 *
	 * @param  integer $post_id Post ID.
	 */
	public function import_single_post( $post_id = 0 ) {

		$is_elementor_post = get_post_meta( $post_id, '_elementor_version', true );
		if ( ! $is_elementor_post ) {
			return;
		}

		$imported_from_demo_site = get_post_meta( $post_id, '_demo_importer_enable_for_batch', true );
		if ( ! $imported_from_demo_site ) {
			return;
		}

		if ( defined( 'WP_CLI' ) ) {
			\WP_CLI::line( 'Elementor - Processing page: ' . $post_id );
		}

		if ( ! empty( $post_id ) ) {

			$data = get_post_meta( $post_id, '_elementor_data', true );

			if ( ! empty( $data ) ) {

				$ids_mapping = get_option( 'demo_importer_plus_cf7_ids_mapping', array() );
				if ( $ids_mapping ) {
					foreach ( $ids_mapping as $old_id => $new_id ) {
						$data = str_replace( '[contact-form-7 id=\"' . $old_id, '[contact-form-7 id=\"' . $new_id, $data );
						$data = str_replace( '"select_form":"' . $old_id, '"select_form":"' . $new_id, $data );
					}
				}

				if ( ! is_array( $data ) ) {
					$data = json_decode( $data, true );
				}
				$document = Plugin::$instance->documents->get( $post_id );
				if ( $document ) {
					$data = $document->get_elements_raw_data( $data, true );
				}

				$data = $this->process_export_import_content( $data, 'on_import' );

				$demo_url = DEMO_IMPORTER_PLUS_MAIN_DEMO_URI;

				$demo_data = get_option( 'demo_importer_plus_import_data', array() );
				if ( isset( $demo_url ) ) {
					$data = wp_json_encode( $data, true );
					if ( ! empty( $data ) ) {
						$site_url      = get_site_url();
						$site_url      = str_replace( '/', '\/', $site_url );
						$demo_site_url = $demo_url;
						$demo_site_url = str_replace( '/', '\/', $demo_site_url );
						$data          = str_replace( $demo_site_url, $site_url, $data );
						$data          = json_decode( $data, true );
					}
				}

				update_metadata( 'post', $post_id, '_elementor_data', $data );
				update_metadata( 'post', $post_id, '_demo_importer_plus_hotlink_imported', true );

				Plugin::$instance->files_manager->clear_cache();
			}
		}
	}
}
