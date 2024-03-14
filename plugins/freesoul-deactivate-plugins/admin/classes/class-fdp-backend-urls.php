<?php
/**
 * Class for the Backend URLs.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Class FDP Backend URLs
 *
 * Implemented by backend URLs templates.
 *
 * @version  1.0.0
 * @package  Freesoul Deactivate Plugins\Classes
 */
class FDP_Backend_Urls_Page extends FDP_Custom_Rows_Page {

	/**
	 * URLs.
	 *
	 * @var array $urls URLs
	 * @since  1.9.0
	 */	
	public $urls;

	/**
	 * Homme URL.
	 *
	 * @var string $home_url Home URL
	 * @since  1.9.0
	 */	
	public $home_url;

	/**
	 * Section ID.
	 *
	 * @var string $section_id Section ID
	 * @since  1.9.0
	 */	
	public $section_id;

	/**
	 * Output before section.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */
	public function before_section( $page_slug ) {
		?>
	<style id="fdp-custom-rows-css">
	.fdp-exact-filter{
	  margin-left: 15px;
	  margin-top: 3px;
	}
	.fdp-exact-filter-off{
	  opacity:0.6
	}
	</style>
		<?php
		$this->section_id = 'eos-dp-by-url-section';
		$this->urls       = eos_dp_get_option( 'eos_dp_by_admin_url' );
		if ( ! $this->urls || '' === $this->urls ) {
			$this->urls = array(
				array(
					'url'     => '',
					'plugins' => '',
				),
			);
		} else {
			$this->urls[] = array(
				'url'     => '',
				'plugins' => '',
			);
		}
		$this->home_url = get_home_url();
		?>
	<h2><?php esc_html_e( 'Uncheck the plugins you want to disable in the backend depending on the URL', 'freesoul-deactivate-plugins' ); ?></h2>
		<h2><span class="dashicons dashicons-warning"></span><?php esc_html_e( 'It will work only for the BACKEND', 'freesoul-deactivate-plugins' ); ?></h2>
		<div class="eos-dp-explanation">
			<p><?php esc_html_e( 'Use the star "*" as replacement of groups of characters.', 'freesoul-deactivate-plugins' ); ?></p>
			<p><?php printf( esc_html__( 'E.g. %1$s/wp-admin*example/ will match URLs as %2$s/wp-admin/an-example/, %3$s/wp-admin/another-example/...', 'freesoul-deactivate-plugins' ), esc_url( $this->home_url ), esc_url( $this->home_url ), esc_url( $this->home_url ) ); ?></p>
			<p><?php printf( esc_html__( 'You can use these options to disable plugins by URL query arguments. E.g. *?example-paramameter=true* will match URLS as %1$s/wp-admin?example-paramameter=true, %2$s/wp-admin/page-example/?example-paramameter=true...', 'freesoul-deactivate-plugins' ), esc_url( $this->home_url ), esc_url( $this->home_url ) ); ?></p>
		</div>
		<?php
	}
}
