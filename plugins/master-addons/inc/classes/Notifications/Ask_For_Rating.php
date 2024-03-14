<?php

namespace MasterAddons\Inc\Classes\Notifications;

use MasterAddons\Inc\Classes\Notifications\Model\Notice;

if (!class_exists('Ask_For_Rating')) {
	/**
	 * Ask For Rating Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Ask_For_Rating extends Notice
	{

		/**
		 * Notice Content
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_content()
		{
			printf(
				'<h2 style="margin:0">Enjoying %1$s?</h2><p>%2$s</p>',
				esc_html__('Master Addons', 'master-addons'),
				__('A positive rating will keep us motivated to continue supporting and improving this free plugin, and will help spread its popularity.<br> Your help is greatly appreciated!', 'master-addons')
			);
		}

		/**
		 * Rate Plugin URL
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function plugin_rate_url()
		{
			return 'https://wordpress.org/support/plugin/master-addons/reviews/?filter=5';
		}

		/**
		 * Footer content
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function footer_content()
		{
?>
			<a class="button button-primary rate-plugin-button" href="<?php echo esc_url($this->plugin_rate_url()); ?>" rel="nofollow" target="_blank">
				<?php echo esc_html__('Rate Now', 'master-addons'); ?>
			</a>
			<a class="button notice-review-btn review-later jltma-notice-dismiss" href="#" rel="nofollow">
				<?php echo esc_html__('Later', 'master-addons'); ?>
			</a>
			<a class="button notice-review-btn review-done jltma-notice-disable" href="#" rel="nofollow">
				<?php echo esc_html__('I already did', 'master-addons'); ?>
			</a>
<?php
		}

		/**
		 * Intervals
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function intervals()
		{
			return array(7, 11, 15, 15, 10, 20, 25, 30);
		}
	}
}
