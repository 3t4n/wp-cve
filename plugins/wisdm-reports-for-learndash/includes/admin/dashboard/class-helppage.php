<?php
namespace WRLDAdmin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'HelpPage' ) ) {

	/**
	 * Class for showing tabs of WRLD.
	 */
	class HelpPage {

		public function __construct() {
			if (is_rtl()) {
				wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.rtl.css', array(), WRLD_PLUGIN_VERSION );
			} else {
				wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.css', array(), WRLD_PLUGIN_VERSION );
			}
		}

		public static function render() {
			?>
			<div class='wrld-dashboard-page-container'>
				<?php
					self::content_main();
					self::content_sidebar();
				?>
			</div>
			<?php
		}

		public static function content_main() {
			?>
				<div class='wrld-dashboard-page-content'>
					<div class='wrld-help-page-section'>
					<?php
						self::get_question_answer_collaps();
					?>
					</div>
					<div class='wrld-help-page-section'>
					<?php
						self::get_help_changelog_section();
					?>
					</div>
				</div>
			<?php
		}

		public static function content_sidebar() {
			?>
				<div class='wrld-dashboard-page-sidebar'>
					<?php self::sidebar_block_upgrade(); ?>
					<?php self::sidebar_block_connect(); ?>
				</div>
			<?php
		}

		public static function sidebar_block_upgrade() {
			if ( defined( 'LDRP_PLUGIN_VERSION' ) ) {
				return '';
			}
			?>
				<div class='wrld-sidebar-block'>
					<div class='wrld-sidebar-block-head'>
						<div class='wrld-sidebar-head-icon'>
							<span class='upgrade-icon'></span>
						</div>
						<div class='wrld-sidebar-head-text'>
							<span><?php esc_html_e( 'Upgrade your FREE Wisdm Reports Plugin to PRO!', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</div>
					</div>
					<div class='wrld-sidebar-body'>
						<div class='wrld-sidebar-body-text'>
							<span><?php esc_html_e( 'Click the button below to upgrade your FREE Wisdm Reports Plugin to PRO!', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</div>
						<a href="https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=wrld-help&utm_campaign=upgradetopro#pricing" target='__blank'>
							<button class='wrld-sidebar-body-button'><?php esc_html_e( 'Upgrade to PRO', 'learndash-reports-by-wisdmlabs' ); ?></button>
						</a>
					</div>
				</div>
			<?php
		}

		public static function sidebar_block_connect() {
			?>
			<div class='wrld-sidebar-block'>
					<div class='wrld-sidebar-block-head'>
						<div class='wrld-sidebar-head-icon'>
							<span class='contact-icon'></span>
						</div>
						<div class='wrld-sidebar-head-text'>
							<span><?php esc_html_e( 'Connect with us', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</div>
					</div>
					<div class='wrld-sidebar-body'>
						<div class='wrld-sidebar-body-text'>
							<span><?php esc_html_e( 'Shoot us an email at ', 'learndash-reports-by-wisdmlabs' ); ?></span>
							<span><a href='mailto:helpdesk@wisdmlabs.com'><strong>helpdesk@wisdmlabs.com</strong></a></span>
							<span><?php esc_html_e( ' and we would be delighted to help you out.', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</div>
						<br>
						<div class='wrld-sidebar-body-text'>
							<span><?php esc_html_e( 'Chat with us ', 'learndash-reports-by-wisdmlabs' ); ?></span>
							<span><a href='https://wisdmlabs.com/reports-for-learndash/'><strong><?php esc_html_e( 'here', 'learndash-reports-by-wisdmlabs' ); ?></strong></a></span>
						</div>
					</div>
				</div>
			<?php
		}



		public static function get_question_answer_collaps() {
			$help_articles = array(
				array(
					'title' => __( 'Plugin Overview, Installation & Updates', 'learndash-reports-by-wisdmlabs' ),
					'link'  => 'https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/plugin-overview-installation-updates-4/',
				),
				array(
					'title' => __( 'How to setup the LearnDash Reporting Dashboard', 'learndash-reports-by-wisdmlabs' ),
					'link'  => 'https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/how-to-set-up-the-learndash-reporting-dashboard/',
				),
				array(
					'title' => __( 'Configuration & Accessibility Settings', 'learndash-reports-by-wisdmlabs' ),
					'link'  => 'https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/configuration-accessibility-settings/',
				),
				array(
					'title' => __( 'All available reports', 'learndash-reports-by-wisdmlabs' ),
					'link'  => 'https://wisdmlabs.com/docs/product/wisdm-learndash-reports/gutenberg-blocks/',
				),
				array(
					'title' => __( 'How to create multiple/user-specific Dashboards?', 'learndash-reports-by-wisdmlabs' ),
					'link'  => 'https://wisdmlabs.com/docs/article/wisdm-learndash-reports/how-to-create-multiple-user-specific-dashboards/',
				),
				array(
					'title' => __( 'How to add the link to the Reports Dashboard to different places on your site?', 'learndash-reports-by-wisdmlabs' ),
					'link'  => 'https://wisdmlabs.com/docs/article/wisdm-learndash-reports/how-to-add-the-link-to-the-reports-dashboard-to-different-places-on-your-site/',
				),
				array(
					'title' => __( 'How to Configure the student quiz report page', 'learndash-reports-by-wisdmlabs' ),
					'link'  => 'https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/configuration-accessibility-settings/#header-menu-settings',
				),
				array(
					'title' => __( 'How to add student quiz report blocks to any page', 'learndash-reports-by-wisdmlabs' ),
					'link'  => 'https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/how-to-set-up-the-student-quiz-results-page/',
				),
			);
			?>
			<div class='wrld-section-head'>
				<div class='help-icon'></div>
				<div class='wrld-section-head-text'><span class='text'><?php esc_html_e( 'Need help?', 'learndash-reports-by-wisdmlabs' ); ?></span></div>
			</div>
			<div class='wrld-section-subhead'>
				<div class='wrld-section-subhead-text'>
					<?php esc_html_e( 'Refer the following links from the documentation of the plugin:', 'learndash-reports-by-wisdmlabs' ); ?>
				</div>
			</div>
			<ul class='wrld-help-link-wrapper'>
			<?php
			foreach ( $help_articles as $article ) {
				?>
					<li>
						<a class='wrld-help-page-links'  target="__blank" href="<?php echo esc_attr( $article['link'] ); ?>">
							<span><?php echo esc_html( $article['title'] ); ?></span>
						</a>
					</li>
				<?php
			}
			?>
			</ul>
			<?php
		}

		public static function get_help_changelog_section() {
			?>
		<div class='wrld-section-head'>
				<div class='help-icon'></div>
				<div class='wrld-section-head-text'><span class='text'><?php esc_html_e( 'Need to know about the latest WISDM Reports updates?', 'learndash-reports-by-wisdmlabs' ); ?></span></div>
			</div>
			<div class='wrld-section-subhead'>
				<div class='wrld-section-subhead-text'>
					<?php esc_html_e( 'Please check ', 'learndash-reports-by-wisdmlabs' ); ?>
					<a href='https://wisdmlabs.com/docs/article/wisdm-learndash-reports/changelog-reportings/changelog-reporting/' target='_blank'><?php esc_html_e( 'Changelog', 'learndash-reports-by-wisdmlabs' ); ?></a>
				</div>
			</div>
			<?php
		}
	}
}
