<?php

namespace CBChangeMailSender\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ProductEducation class.
 *
 * Class with Product Education related methods.
 *
 * @since 1.3.0
 */
class ProductEducation {

	/**
	 * Option key used to saved in `wp_options` table.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	const OPTION_KEY = 'cb_change_mail_sender_product_education';

	/**
	 * Nonce action for production education dismiss.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	const DISMISS_NONCE_ACTION = 'CB_CHANGE_MAIL_SENDER_PRODUCT_EDUCATION_DISMISS';

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * Hook Product Education functions.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	private function hooks() {

		add_action( 'wp_ajax_cb_change_mail_sender_product_education_dismiss', [ $this, 'product_education_dismiss_ajax' ] );
	}

	/**
	 * AJAX function for product education dismiss.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function product_education_dismiss_ajax() {

		if (
			! check_ajax_referer( self::DISMISS_NONCE_ACTION, 'nonce', false ) ||
			! current_user_can( 'manage_options' ) ||
			empty( $_POST['productEducationId'] )
		) {

			wp_send_json_error(
				esc_html__( 'Invalid request!', 'cb-mail' )
			);
		}

		$this->update_dismissed_option( htmlspecialchars( $_POST['productEducationId'] ) );

		wp_send_json_success();
	}

	/**
	 * Include the `$id` to the dismissed product education option.
	 *
	 * @since 1.3.0
	 *
	 * @param string $id ID of the product education banner dismissed.
	 *
	 * @return bool Whether or not the option was updated.
	 */
	private function update_dismissed_option( $id ) {

		$option = get_option( self::OPTION_KEY, [] );

		$dismissed        = empty( $option['dismissed'] ) ? [] : $option['dismissed'];
		$dismissed[ $id ] = true;

		$option['dismissed'] = $dismissed;

		return update_option( self::OPTION_KEY, $option, false );
	}

	/**
	 * Create product education banner.
	 *
	 * @since 1.3.0
	 *
	 * @param string   $id      Unique ID for the product education banner.
	 * @param string   $title   Title of the product education banner.
	 * @param string   $content Education banner content.
	 * @param string[] $button  {
	 *     Optional. An array of arguments for the button. If not provided, the product
	 *     education banner won't display a button.
	 *
	 *     @type string $url    URL of the button.
	 *     @type string $label  Label of the button.
	 *     @type string $target Target attribute of the `<a> used in button.
	 * }
	 *
	 * @return void
	 */
	public static function create_banner( $id, $title, $content, $button = [] ) {
		?>
		<div id="cb-change-mail-sender-product-education-<?php echo esc_attr( $id ); ?>"
			class="cb-change-mail-sender-product-education"
			 data-product-education-id="<?php echo esc_attr( $id ); ?>"
			 data-nonce="<?php echo esc_attr( wp_create_nonce( self::DISMISS_NONCE_ACTION ) ); ?>">

			<div class="cb-change-mail-sender-product-education-content">

				<span class="cb-change-mail-sender-product-education-dismiss">
					<button>
						<span class="dashicons dashicons-dismiss"></span>
					</button>
				</span>

				<h3><?php echo esc_html( $title ); ?></h3>

				<?php echo wp_kses_post( $content ); ?>

				<?php
				if ( ! empty( $button ) ) {
					// Default.
					$button_target = '_self';
					if ( ! empty( $button['target'] ) && in_array( $button['target'], [ '_blank', '_parent', '_top' ], true ) ) {
						$button_target = $button['target'];
					}
					?>
					<a class="cb-change-mail-sender-product-education-btn button button-primary" target="<?php echo esc_attr( $button_target ); ?>" href="<?php echo esc_url( $button['url'] ); ?>">
						<?php echo esc_html( $button['label'] ); ?>
					</a>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Whether or not a product education banner was previously dismissed.
	 *
	 * @since 1.3.0
	 *
	 * @param string $product_education_id Product Education ID to check.
	 *
	 * @return bool
	 */
	public static function is_banner_dismissed( $product_education_id ) {

		$option = get_option( self::OPTION_KEY, [] );

		if ( empty( $option ) ) {
			return false;
		}

		if ( empty( $option['dismissed'] ) || ! array_key_exists( $product_education_id, $option['dismissed'] ) ) {
			return false;
		}

		return true;
	}
}
