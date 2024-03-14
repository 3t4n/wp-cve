<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

class Xpro_Elementor_Demo_Export_Admin {


	public function __construct() {}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {

		if ( 'xpro-addons_page_xpro-elementor-addons-demo-export' == $hook_suffix ) {
			wp_enqueue_style( 'xpro-elementor-demo-export', XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/css/demo-export.css', array(), XPRO_ELEMENTOR_ADDONS_VERSION, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {

		if ( 'xpro-addons_page_xpro-elementor-addons-demo-export' == $hook_suffix ) {
			wp_enqueue_script( 'xpro-elementor-demo-export', XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/js/demo-export.js', array( 'jquery' ), XPRO_ELEMENTOR_ADDONS_VERSION, false );
			wp_localize_script(
				'xpro-elementor-demo-export',
				'xpro_elementor_demo_export_js_object',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

	/**
	 * Create admin pages in menu
	 *
	 * @since    1.0.0
	 */
	public function export_menu() {
		add_submenu_page(
			Xpro_Elementor_Addons::PAGE_SLUG,
			esc_html__( 'Demo Export', 'xpro-elementor-addons' ),
			esc_html__( 'Demo Export', 'xpro-elementor-addons' ),
			'export',
			'xpro-elementor-addons-demo-export',
			array( $this, 'export_screen' )
		);
	}

	/**
	 * The Admin Screen, placeholder div for ajax form
	 *
	 * @since    1.0.0
	 */
	public function export_screen() {
		?>
		<div id="xpro-elementor-demo-export-ajax-form-data">
		</div>
		<?php
	}

	/**
	 * Form Loading Ajax Callback
	 *
	 * @since    1.0.0
	 */
	public function form_load() {
		xpro_elementor_demo_export_form();
		exit;
	}

	/**
	 * Export Content
	 *
	 * @since    1.0.0
	 */
	public function export_content() {

		if ( empty( $_GET['page'] ) || 'xpro-elementor-addons-demo-export' != $_GET['page'] ) {
			return;
		}

		// If the 'download' URL parameter is set, a Theme Data ZIP export file returned.
		if ( isset( $_POST['xpro-elementor-demo-export-download'] ) ) {
			if ( ! current_user_can( 'export' ) ) {
				wp_die( esc_html__( 'Sorry, you are not allowed to export the content of this site.', 'xpro-elementor-addons' ) );
			}

			/*security check*/
			check_admin_referer( 'xpro-elementor-demo-export' );

			$args = array();

			if ( ! isset( $_POST['content'] ) || 'all' == $_POST['content'] ) {
				$args['content'] = 'all';
			} elseif ( 'posts' == $_POST['content'] ) {
				$args['content'] = 'post';

				if ( $_POST['cat'] ) {
					$args['category'] = absint( $_POST['cat'] );
				}

				if ( $_POST['post_author'] ) {
					$args['author'] = absint( $_POST['post_author'] );
				}

				if ( $_POST['post_start_date'] || $_POST['post_end_date'] ) {
					$args['start_date'] = sanitize_text_field( $_POST['post_start_date'] );
					$args['end_date']   = sanitize_text_field( $_POST['post_end_date'] );
				}

				if ( $_POST['post_status'] ) {
					$args['status'] = sanitize_text_field( $_POST['post_status'] );
				}
			} elseif ( 'pages' == $_POST['content'] ) {
				$args['content'] = 'page';

				if ( $_POST['page_author'] ) {
					$args['author'] = absint( $_POST['page_author'] );
				}

				if ( $_POST['page_start_date'] || $_POST['page_end_date'] ) {
					$args['start_date'] = sanitize_text_field( $_POST['page_start_date'] );
					$args['end_date']   = sanitize_text_field( $_POST['page_end_date'] );
				}

				if ( $_POST['page_status'] ) {
					$args['status'] = sanitize_text_field( $_POST['page_status'] );
				}
			} elseif ( 'attachment' == $_POST['content'] ) {
				$args['content'] = 'attachment';

				if ( $_POST['attachment_start_date'] || $_POST['attachment_end_date'] ) {
					$args['start_date'] = sanitize_text_field( $_POST['attachment_start_date'] );
					$args['end_date']   = sanitize_text_field( $_POST['attachment_end_date'] );
				}
			} else {
				$args['content'] = sanitize_text_field( $_POST['content'] );
			}
			if ( isset( $_POST['include_media'] ) && 1 == $_POST['include_media'] ) {
				$args['include_media'] = 1;
			}
			if ( isset( $_POST['widgets_data'] ) && 1 == $_POST['widgets_data'] ) {
				$args['widgets_data'] = 1;
			}
			if ( isset( $_POST['options_data'] ) && 1 == $_POST['options_data'] ) {
				$args['options_data'] = 1;
			}
			if ( isset( $_POST['settings_data'] ) && 1 == $_POST['settings_data'] ) {
				$args['settings_data'] = 1;
			}

			/**
			 * Create zip
			 */
			require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . '/libs/demo-export/function-create-zip.php';
			xpro_elementor_demo_export_ziparchive( $args );
			die();
		}
	}
}
