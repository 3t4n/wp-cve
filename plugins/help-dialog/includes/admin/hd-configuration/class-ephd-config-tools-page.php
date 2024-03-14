<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog configuration page (Tools Tab)
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Config_Tools_Page {

	/**
	 * Get Tools View Config
	 *
	 * @return array
	 */

	public static function get_tools_view_config() {
		return array(

			// Shared
			'list_key' => 'tools',

			// Top Panel Item
			'label_text' => __( 'Tools', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-wrench',

			// Secondary Panel Items
			'secondary'  => array(

				// SECONDARY VIEW: EXPORT
				array(

					// Shared
					'list_key'   => 'export',
					'active'     => true,

					// Secondary Panel Item
					'label_text' => __( 'Export HD', 'help-dialog' ),

					// Secondary Boxes List
					'boxes_list' => self::get_export_boxes()
				),

				// SECONDARY VIEW: IMPORT
				array(

					// Shared
					'list_key'   => 'import',

					// Secondary Panel Item
					'label_text' => __( 'Import HD', 'help-dialog' ),

					// Secondary Boxes List
					'boxes_list' => self::get_import_boxes()
				),
			),
		);
	}

	/**
	 * Get boxes config for Tools panel, export subpanel
	 *
	 * @return array
	 */
	private static function get_export_boxes_config() {

		return [
			[
				'plugin'       => 'core',
				'icon'         => 'ephdfa ephdfa-upload',
				'title'        => esc_html__( 'Export HD Configuration', 'help-dialog' ),
				'desc'         => esc_html__( 'Export configuration including colors, fonts, labels, and features settings.', 'help-dialog' ),
				'custom_links' => self::get_export_button_html(),
				'button_id'    => 'ephd_core_export',
				'button_title' => esc_html__( 'Export Configuration', 'help-dialog' ),
			]
		];
	}

	/**
	 * Get config for boxes for Tools panel, import subpanel
	 * @return array
	 */
	private static function get_import_boxes_config() {
		return [
			[
				'plugin'       => 'core',
				'icon'         => 'ephdfa ephdfa-download',
				'title'        => esc_html__( 'Import HD Configuration', 'help-dialog' ),
				'desc'         => esc_html__( 'Import configuration including colors, fonts, labels, and features settings.', 'help-dialog' ),
				'button_id'    => 'ephd_core_import',
				'button_title' => esc_html__( 'Import Configuration', 'help-dialog' ),
			],
		];
	}

	/**
	 * Get boxes for Tools panel, export subpanel
	 *
	 * @return array
	 */
	private static function get_export_boxes() {
		$boxes = [];

		foreach ( self::get_export_boxes_config() as $box ) {

			$box['active_status'] = true;

			// box with the button
			$boxes[] = [
				'class' => 'ephd-kbnh__feature-container',
				'html'  => EPHD_HTML_Forms::get_feature_box_html( $box )
			];
		}

		foreach ( self::get_export_boxes_config() as $box ) {
			// panel that will be opened with the button
			$box_panel_class = 'ephd-kbnh__feature-panel-container ' . ( empty( $box['button_id'] ) ? '' : 'ephd-kbnh__feature-panel-container--' . $box['button_id'] );

			$boxes[] = [
				'title' => $box['title'],
				'class' => $box_panel_class,
				'html'  => apply_filters( 'ephd_config_page_export_import_panel_html', '', $box ),
			];
		}

		return $boxes;
	}

	/**
	 * Get boxes for Tools panel, import subpanel
	 *
	 * @return array
	 */
	private static function get_import_boxes() {
		$boxes = [];

		foreach ( self::get_import_boxes_config() as $box ) {

			$box['active_status'] = true;

			$boxes[] = [
				'class' => 'ephd-kbnh__feature-container',
				'html'  => EPHD_HTML_Forms::get_feature_box_html( $box )
			];
		}

		foreach ( self::get_import_boxes_config() as $box ) {
			// panel that will be opened with the button
			$box_panel_class = 'ephd-kbnh__feature-panel-container ' . ( empty( $box['button_id'] ) ? '' : 'ephd-kbnh__feature-panel-container--' . $box['button_id'] );

			$panel_html = '';

			if ( ! empty( $box['button_id'] ) && $box['button_id'] == 'ephd_core_import' ) {
				$panel_html = self::get_import_box();
			}

			$boxes[] = [
				'title' => $box['title'],
				'class' => $box_panel_class,
				'html'  => apply_filters( 'ephd_config_page_export_import_panel_html', $panel_html, $box ),
			];
		}

		return $boxes;
	}

	/**
	 * Get hidden block to make export working
	 *
	 * @return string
	 */
	private static function get_export_button_html() {

		ob_start(); ?>
        <form class="ephd-export-settings" action="" method="POST">
            <input type="hidden" name="_wpnonce_manage_hd" value="<?php echo esc_attr( wp_create_nonce( "_wpnonce_manage_hd" ) ); ?>"/>
            <input type="hidden" name="action" value="ephd_export_help_dialog"/>
            <input type="submit" class="ephd-primary-btn" value="<?php esc_html_e( 'Export Configuration', 'help-dialog' ); ?>"/>
        </form> <?php

		return ob_get_clean();
	}

	/**
	 * Get Import Box
	 *
	 * @return false|string
	 */
	private static function get_import_box() {

		ob_start(); ?>

        <!-- Import Config -->
        <div class="ephd-admin-info-box">
            <div class="ephd-admin-info-box__body">
                <p><?php echo __( 'This import will overwrite the following HD settings:', 'help-dialog' ); ?></p>
				<?php self::display_import_export_info(); ?>
                <form class="ephd-import-config" action="" method="POST" enctype="multipart/form-data">
                    <input class="ephd-form-label__input ephd-form-label__input--text" type="file" name="import_file" required><br>
                    <input type="hidden" name="_wpnonce_manage_hd" value="<?php echo wp_create_nonce( "_wpnonce_manage_hd" ); ?>"/>
                    <input type="hidden" name="action" value="ephd_import_help_dialog"/>
                    <input type="button" class="ephd-kbnh-back-btn ephd-default-btn" value="<?php esc_attr_e( 'Back', 'help-dialog' ); ?>"/>
                    <input type="submit" class="ephd-primary-btn" value="<?php esc_attr_e( 'Import Configuration', 'help-dialog' ); ?>"/><br/>
                </form>
            </div>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Import info
	 */
	private static function display_import_export_info() {  ?>
        <ul>
            <li><?php _e( 'Configuration for all text, styles, features.', 'help-dialog' ); ?></li>
        </ul>
        <p><?php _e( 'Instructions:', 'help-dialog' ); ?></p>
        <ul>
            <li><?php _e( 'Test import and export on your staging or test site before importing configuration in production.', 'help-dialog' ); ?></li>
            <li><?php _e( 'Always back up your database before starting the import.', 'help-dialog' ); ?></li>
            <li><?php _e( 'Preferably run import outside of business hours.', 'help-dialog' ); ?></li>
        </ul>   <?php
	}

}
