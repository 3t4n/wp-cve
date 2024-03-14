<?php
namespace WRLDAdmin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WhatsnewPage' ) ) {

	/**
	 * Class for showing tabs of WRLD.
	 */
	class WhatsnewPage {

		public function __construct() {
			if (is_rtl()) {
	            wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.rtl.css', array(), WRLD_PLUGIN_VERSION );
	            wp_enqueue_style( 'wrld_admin_whatsnew_tab_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/whatsnewtab.rtl.css', array(), WRLD_PLUGIN_VERSION );
			} else {
				wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.css', array(), WRLD_PLUGIN_VERSION );
	            wp_enqueue_style( 'wrld_admin_whatsnew_tab_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/whatsnewtab.css', array(), WRLD_PLUGIN_VERSION );
			}
		}

		public static function render() {

			?>
			<div class='wrld-whatsnew-container'>
				<?php
					 self::get_current_installed_version_banner();
					 self::get_post_section();
				?>
			</div>
			<?php
           
		}

		

		public static function get_current_installed_version_banner() {
			?>

                <div class="wrld-whatsnew-header">
                    <h2><?php esc_html_e( 'WISDM Reports for LearnDash', 'learndash-reports-by-wisdmlabs' ); ?>  <b> <?php esc_html_e( 'Version', 'learndash-reports-by-wisdmlabs' ); ?> <?php echo WRLD_PLUGIN_VERSION;  ?></b></h2></p>
                </div>
            <?php
		}

		public static function get_post_section() {
			include_once 'templates/post-container.php';
        }
	}
}