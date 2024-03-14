<?php
namespace WPAdminify\Inc\Classes\Notifications\Model;

use WPAdminify\Inc\Classes\Helper;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Notice Model for Notification
 *
 * Jewel Theme <support@jeweltheme.com>
 */
abstract class Notice extends Notification {


	public $type  = 'notice';
	public $color = 'error';

	/**
	 * Get Key
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	final public function get_key() {
		return 'jltwp_adminify_notice_' . $this->get_id();
	}

	/**
	 * Footer content
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public function footer_content() {
		return '';
	}

	/**
	 * Notice Header
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public function notice_header() { ?>
		<div class="notice notice-wp-adminify is-dismissible notice-<?php echo esc_attr( $this->color ); ?> wp-adminify-notice-<?php echo esc_attr( $this->get_id() ); ?>">
			<button type="button" class="notice-dismiss wp-adminify-notice-dismiss"></button>
			<div class="notice-content-box">
		<?php
	}

	/**
	 * Notice Content
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public function notice_content() {
	}

	/**
	 * Notice Footer
	 *
	 * @return void
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public function notice_footer() {
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
	public function core_script( $trigger_time ) {
		?>
		<script>
			function jltwp_adminify_notice_action(evt, $this, action_type) {

				if (evt) evt.preventDefault();

				$this.closest('.notice-wp-adminify').slideUp(200);

				jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
					action: 'jltwp_adminify_notification_action',
					_wpnonce: '<?php echo esc_js( wp_create_nonce( 'jltwp_adminify_notification_nonce' ) ); ?>',
					action_type: action_type,
					notification_type: 'notice',
					trigger_time: '<?php echo esc_attr( $trigger_time ); ?>'
				});

			}

			// Notice Dismiss
			jQuery('body').on('click', '.notice-wp-adminify .wp-adminify-notice-dismiss', function(evt) {
				jltwp_adminify_notice_action(evt, jQuery(this), 'dismiss');
			});

			// Notice Disable
			jQuery('body').on('click', '.notice-wp-adminify .wp-adminify-notice-disable', function(evt) {
				jltwp_adminify_notice_action(evt, jQuery(this), 'disable');
			});
		</script>
		<?php
	}
}
