<?php
namespace WPAdminify\Inc\Classes\Notifications;

use WPAdminify\Inc\Classes\Notifications\Base\User_Data;
use WPAdminify\Inc\Classes\Notifications\Model\Notice;

if ( ! class_exists( 'Subscribe' ) ) {
	/**
	 * Subscribe Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Subscribe extends Notice {

		use User_Data;

		public $color = 'warning';

		/**
		 * Construct method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			parent::__construct();
			add_action( 'wp_ajax_jltwp_adminify_subscribe', array( $this, 'jltwp_adminify_subscribe' ) );
		}

		/**
		 * Subscribe method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltwp_adminify_subscribe() {
			check_ajax_referer( 'jltwp_adminify_subscribe_nonce' );

			$name  = ! empty( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
			$email = ! empty( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

			if ( ! is_email( $email ) ) {
				$email = get_bloginfo( 'admin_email' );
			}

			$author_obj = get_user_by( 'email', get_bloginfo( 'admin_email' ) );
			$user_id    = $author_obj->ID;

			// First Name & Last name .
			if ( ! empty( $name ) ) {
				$full_name = $name;
			} else {
				$full_name = $author_obj->display_name;
			}

			$response = $this->get_collect_data( $user_id, array(
				'first_name'              => $full_name,
				'email'                   => $email,
			) );

			if ( ! is_wp_error( $response ) && 200 === $response['response']['code'] && 'OK' === $response['response']['message'] ) {
				wp_send_json_success( 'Thanks for Subscribe!' );
			} else {
				wp_send_json_error( "Couldn't Subscribe" );
			}
		}

		/**
		 * Notice Header
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_header() {
			return '';
		}

		/**
		 * Notice footer
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_footer() {
			return '';
		}

		/**
		 * Set Title
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function set_title() {
			printf(
				'<h4>Wanna get some discount for %1$s? No Worries!! We got you!! Enter your email, we will send you the discount code?</h4>',
				esc_html__( 'WP Adminify', 'adminify' )
			);
		}

		/**
		 * Notice Content
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function notice_content() {
			$userdata = \wp_get_current_user();
			?>
			<div class="notice notice-wp-adminify is-dismissible notice-plugin-review notice-<?php echo esc_attr( $this->color ); ?> wp-adminify-notice-<?php echo esc_attr( $this->get_id() ); ?>">
				<button type="button" class="notice-dismiss wp-adminify-notice-dismiss"></button>
				<img width="70" height="70" src="<?php echo esc_url( WP_ADMINIFY_ASSETS_IMAGE . 'logos/menu-icon.svg' ); ?>" alt="<?php esc_attr_e( 'Logo', 'adminify' ); ?>">
				<div class="wp-adminify-subscribe-content">
					<?php $this->set_title(); ?>
					<form style="display:flex">
						<div class="wp-adminify-plugin-subscribe-input">
							<input type="text" id="name" name="name" placeholder="Name" value="<?php echo esc_attr( $userdata->display_name ); ?>">
						</div>
						<div class="wp-adminify-plugin-subscribe-input" style="padding-right:20px;padding-left:10px;">
							<input type="text" id="email" name="email" placeholder="Email" value="<?php echo esc_attr( $userdata->user_email ); ?>">
						</div>
						<button type="submit" class="button button-primary wp-adminify-subscribe-btn"><?php esc_html_e( 'Get Discount', 'adminify' ); ?></button>
					</form>
				</div>
			</div>
			<?php
		}

		/**
		 * Rate URL
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function plugin_rate_url() {
			return 'https://wordpress.org/plugins/' . WP_ADMINIFY_SLUG;
		}

		/**
		 * Footer content
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function footer_content() {
			?>
			<a class="button button-primary rate-plugin-button" href="<?php echo esc_url( $this->plugin_rate_url() ); ?>" rel="nofollow" target="_blank">
				<?php echo esc_html__( 'Rate Now', 'adminify' ); ?>
			</a>
			<a class="button notice-review-btn review-later wp-adminify-notice-dismiss" href="#" rel="nofollow">
				<?php echo esc_html__( 'Later', 'adminify' ); ?>
			</a>
			<a class="button notice-review-btn review-done wp-adminify-notice-disable" href="#" rel="nofollow">
				<?php echo esc_html__( 'I already did', 'adminify' ); ?>
			</a>
			<?php
		}

		/**
		 * Intervals
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function intervals() {
			return array( 5, 4, 10, 20, 15, 25, 30, 40, 60, 90, 150 );
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
			parent::core_script( $trigger_time );
			?>

			<script>
				jQuery(document).on('submit', '.wp-adminify-notice-subscribe .wp-adminify-subscribe-content form', function(e) {

					e.preventDefault();

					let form = jQuery(this);
					let name = form.find('input[name=name]').val();
					let email = form.find('input[name=email]').val();
					let formWrapper = form.closest('.wp-adminify-subscribe-content');

					formWrapper.css('opacity', '0.4').find('button').prop('disabled', true);

					jQuery.ajax({
							url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
							method: 'POST',
							crossDomain: true,
							data: {
								action: 'jltwp_adminify_subscribe',
								_wpnonce: '<?php echo esc_js( wp_create_nonce( 'jltwp_adminify_subscribe_nonce' ) ); ?>',
								name: name,
								email: email,
							}
						})
						.done(function(response) {
							formWrapper.hide().after('<p class="wp-adminify--notice-message"><strong>' + response.data + '</strong><p>');
							let subsTimeout = setTimeout(function() {
								jltwp_adminify_notice_action(null, form, 'disable');
								clearTimeout(subsTimeout);
							}, 1500);
						})
						.always(function() {
							formWrapper.css('opacity', '1').find('button').prop('disabled', false);
						})

				});
			</script>

			<?php
		}
	}
}
