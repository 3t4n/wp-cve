<?php
namespace WRLDAdmin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Gutenbergblocks' ) ) {

	/**
	 * Class for showing tabs of WRLD.
	 */
	class Gutenbergblocks {

		public function __construct() {
			if (is_rtl()) {
				wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.rtl.css', array(), WRLD_PLUGIN_VERSION );
	            wp_enqueue_style( 'wrld_admin_gutenberg_tab_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/gutenberg-block.rtl.css', array(), WRLD_PLUGIN_VERSION );
				wp_enqueue_style( 'wrld_admin_whatsnew_tab_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/whatsnewtab.rtl.css', array(), WRLD_PLUGIN_VERSION );
			} else {
				wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.css', array(), WRLD_PLUGIN_VERSION );
	            wp_enqueue_style( 'wrld_admin_gutenberg_tab_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/gutenberg-block.css', array(), WRLD_PLUGIN_VERSION );
				wp_enqueue_style( 'wrld_admin_whatsnew_tab_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/whatsnewtab.css', array(), WRLD_PLUGIN_VERSION );
			}

			wp_enqueue_script( 'wrld_admin_dashboard_gutenberg_block', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/js/gutenberg_block.js', array(), WRLD_PLUGIN_VERSION, true );


			update_option( 'wrld_gutenbergblocks_visited', true );
		}

		public static function render() {

			?>
			<div class='wrld-gutenberg-container'>
				<?php
					 self::get_current_installed_version_banner();
					 self::get_post_section();
				?>
			</div>
			<?php
           
		}

		

		public static function get_current_installed_version_banner() {
			?>

                <div class="wrld-gutenberg-header">
                    <p><?php esc_html_e( 'Refer to this for a list all reporting blocks.', 'learndash-reports-by-wisdmlabs' ); ?> <a href="https://wisdmlabs.com/docs/product/wisdm-learndash-reports/gutenberg-blocks/" target="_blank"><?php esc_html_e( 'Documentation', 'learndash-reports-by-wisdmlabs' ); ?><span class="dashicons dashicons-arrow-right-alt2"></span></a></p>
                </div>
            <?php
		}

		public static function get_post_section() {
			include_once 'templates/gutenbergblock-container.php';
        }
	}
}