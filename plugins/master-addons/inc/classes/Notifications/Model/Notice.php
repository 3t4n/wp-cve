<?php

namespace MasterAddons\Inc\Classes\Notifications\Model;

use MasterAddons\Inc\Helper\Master_Addons_Helper;

// No, Direct access Sir !!!
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Notice Model for Notification
 *
 * Jewel Theme <support@jeweltheme.com>
 */
abstract class Notice extends Notification
{


	public $type  = 'notice';
	public $color = 'error';

	/**
	 * Get Key
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	final public function get_key()
	{
		return 'jltma_notice_' . $this->get_id();
	}

	/**
	 * Footer content
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public function footer_content()
	{
		return '';
	}

	/**
	 * Notice Header
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public function notice_header()
	{ ?>
		<div class="notice notice-jltma is-dismissible notice-<?php echo esc_attr($this->color); ?> jltma-notice-<?php echo esc_attr($this->get_id()); ?>">
			<button type="button" class="notice-dismiss jltma-notice-dismiss"></button>
			<div class="notice-content-box">
			<?php
		}

		/**
		 * Notice Content
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_content()
		{
		}

		/**
		 * Notice Footer
		 *
		 * @return void
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_footer()
		{
			?>
			</div>
			<?php echo  $this->footer_content(); ?>
		</div>
	<?php
		}

		/**
		 * Core Script
		 *
		 * @param [type] $trigger_time .
		 *
		 * @return void
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function core_script($trigger_time)
		{
	?>
		<script>
			function jltma_notice_action(evt, $this, action_type) {

				if (evt) evt.preventDefault();

				$this.closest('.notice-jltma').slideUp(200);

				jQuery.post('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
					action: 'jltma_notification_action',
					_wpnonce: '<?php echo esc_js(wp_create_nonce('jltma_notification_nonce')); ?>',
					action_type: action_type,
					notification_type: 'notice',
					trigger_time: '<?php echo esc_attr($trigger_time); ?>'
				});

			}

			// Notice Dismiss
			jQuery('body').on('click', '.notice-jltma .jltma-notice-dismiss', function(evt) {
				jltma_notice_action(evt, jQuery(this), 'dismiss');
			});

			// Notice Disable
			jQuery('body').on('click', '.notice-jltma .jltma-notice-disable', function(evt) {
				jltma_notice_action(evt, jQuery(this), 'disable');
			});
		</script>
<?php
		}
	}
