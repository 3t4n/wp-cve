<?php
/**
 * Elementor Importer
 *
 * @package Demo Importer Plus
 */

namespace Elementor\TemplateLibrary;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// If plugin - 'Elementor' not exist then return.
if ( ! class_exists( '\Elementor\Plugin' ) ) {
	return;
}

use Elementor\Core\Base\Document;
use Elementor\DB;
use Elementor\Core\Settings\Page\Manager as PageSettingsManager;
use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Core\Settings\Page\Model;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Settings;
use Elementor\Utils;

/**
 * Elementor template library local source.
 *
 * Elementor template library local source handler class is responsible for
 * handling local Elementor templates saved by the user locally on his site.
 */
class Demo_Importer_Plus_Elementor_Pages extends Source_Local {

	/**
	 * Update post meta.
	 *
	 * @param  integer $post_id Post ID.
	 * @param  array   $data Elementor Data.
	 */
	public function import( $post_id = 0, $data = array() ) {

		if ( ! empty( $post_id ) && ! empty( $data ) ) {

			$data = wp_json_encode( $data, true );

			// Update WP form IDs.
			$ids_mapping = get_option( 'demo_importer_plus_cf7_ids_mapping', array() );
			if ( $ids_mapping ) {
				foreach ( $ids_mapping as $old_id => $new_id ) {
					$data = str_replace( '[contact-form-7 id=\"' . $old_id, '[contact-form-7 id=\"' . $new_id, $data );
					$data = str_replace( '"select_form":"' . $old_id, '"select_form":"' . $new_id, $data );
				}
			}

			$data = json_decode( $data, true );

			// Import the data.
			$data = $this->process_export_import_content( $data, 'on_import' );

			// Replace the site urls.
			$demo_data = get_option( 'demo_importer_plus_import_data', array() );
			if ( isset( $demo_data['site-url'] ) ) {
				$site_url      = get_site_url();
				$site_url      = str_replace( '/', '\/', $site_url );
				$demo_site_url = 'https:' . $demo_data['site-url'];
				$demo_site_url = str_replace( '/', '\/', $demo_site_url );
				$data          = str_replace( $demo_site_url, $site_url, $data );
			}

			// Replace the site urls.
			$demo_data = get_option( 'demo_importer_plus_import_data', array() );
			if ( isset( $demo_data['site-url'] ) ) {
				$data = wp_json_encode( $data, true );
				if ( ! empty( $data ) ) {
					$site_url      = get_site_url();
					$site_url      = str_replace( '/', '\/', $site_url );
					$demo_site_url = 'https:' . $demo_data['site-url'];
					$demo_site_url = str_replace( '/', '\/', $demo_site_url );
					$data          = str_replace( $demo_site_url, $site_url, $data );
					$data          = json_decode( $data, true );
				}
			}
			// Update processed meta.
			update_metadata( 'post', $post_id, '_elementor_data', $data );

			// !important, Clear the cache after images import.
			Plugin::$instance->posts_css_manager->clear_cache();

			return $data;
		}

		return array();
	}
}
