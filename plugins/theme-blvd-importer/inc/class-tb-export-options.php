<?php
/**
 * Extend the Theme_Blvd_Export, which holds the
 * basic structure for exporting.
 *
 * See Theme_Blvd_Export class documentation at
 * /inc/tb-class-export.php
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 */
class Theme_Blvd_Export_Options extends Theme_Blvd_Export {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id A unique ID for this exporter
	 */
	public function __construct( $id, $args = array() ) {
		parent::__construct( $id, $args );
	}

	/**
	 * Output content to be exported.
	 *
	 * @since 1.0.0
	 */
	public function export() {

		// Get the settings to export.
		$settings = get_option($this->id);

		// Output the XML file content
		if ( $settings ) {
			include_once( TB_IMPORTER_PLUGIN_DIR . '/inc/options-export.php' );
		}
	}

	/**
	 * If settings are empty, cancel the export.
	 *
	 * @since 1.0.0
	 */
	protected function cancel() {
		if ( ! get_option( $this->id ) ) {
			// By setting $do_cancel to true, it will
			// trigger the cancel in the parent class.
			$this->do_cancel = true;
		}
	}

}
