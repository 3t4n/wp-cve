<?php

namespace WP_VGWORT;
// TODO Add Screen Limit for Notifications (eg only show on metis-message)
/**
 * class that handles notifications for custom plugin actions
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Notifications {
	/**
	 * @var array an array of all notifications that need to be displayed
	 */
	private array $notifications = [];

	/**
	 * @var array an array of all registered by key notifications (one will be displayed if notice key in request)
	 */
	private array $notifications_by_key = [];

	/**
	 * constructor
	 */
	public function __construct() {
		// add hook to display notices
		add_action( 'admin_notices', [ $this, 'display_notices' ] );
	}

	/**
	 * add an admin notice to display
	 *
	 * @param string $message message to display
	 * @param string $type    error or success, defaults to error
	 *
	 * @return void
	 */
	public function add_notice( string $message, string $type = 'error' ): void {
		array_push( $this->notifications, array( 'message' => $message, 'type' => $type ) );
	}

	/**
	 * registers a new notification by key (will be displayed when corresponding notice key is in request)
	 *
	 * @param $key     string notification key
	 * @param $message string text to display in the notification
	 * @param $type    string error or success, defaults to error
	 *
	 * @return void
	 */
	public function add_notice_by_key( string $key, string $message, string $type = 'error' ): void {
		$classes = "notice notice-$type $key";

		$this->notifications_by_key[ $key ] = [ 'message' => $message, 'classes' => $classes ];
	}

	/**
	 * displays an admin notice
	 *
	 * @param string $message
	 * @param string $type
	 *
	 * @return void
	 */
	public function display_notices(): void {
		if ( count( $this->notifications ) ) {
			foreach ( $this->notifications as $notification ) {
				?>
                <div class="notice notice-<?php echo esc_attr( $notification['type'] ); ?>">
                    <p><?php esc_html_e( $notification['message'] ) ?> </p>
                </div>
				<?php
			}
		}

		$this->display_notice_by_key();
		$this->display_custom_error_notice();
		$this->display_custom_success_notice();
	}

	/**
	 * displays the admin notice mentioned in $_REQUEST
	 *
	 * @return void
	 */
	public function display_notice_by_key(): void {
		if ( isset( $_REQUEST['notice'] ) ) {
			$notification_key = sanitize_key( $_REQUEST['notice'] );
			unset( $_REQUEST['notice'] );
		} else {
			return;
		}

		if ( isset( $_REQUEST['custom_text'] ) ) {
			$custom_text = sanitize_text_field( urldecode( $_REQUEST['custom_text'] ) );
			unset( $_REQUEST['custom_text'] );
		} else {
			$custom_text = '';
		}

		if ( array_key_exists( $notification_key, $this->notifications_by_key ) ) {
			$item = $this->notifications_by_key[ $notification_key ];
			?>
            <div class="<?php echo esc_attr( $item['classes'] ); ?>">
                <p><?php esc_html_e( $item['message'] ) ?> <? esc_html_e( $custom_text ); ?></p>
            </div>
			<?php
		}
	}

	/**
	 * checks for a custom error message and displays it when called through hook
	 *
	 * @return void
	 */
	private function display_custom_error_notice(): void {
		if ( isset( $_REQUEST['custom_error_notice'] ) ) {
			$message = sanitize_text_field( urldecode( $_REQUEST['custom_error_notice'] ) );
			unset( $_REQUEST['custom_error_notice'] );
			?>
            <div class="notice notice-error">
                <p><?php esc_html_e( $message ) ?></p>
            </div>
			<?php
		}
	}

	/**
	 * checks for a custom error message and displays it when called through hook
	 *
	 * @return void
	 */
	private function display_custom_success_notice(): void {
		if ( isset( $_REQUEST['custom_success_notice'] ) ) {
			$message = sanitize_text_field( urldecode( $_REQUEST['custom_success_notice'] ) );
			unset( $_REQUEST['custom_success_notice'] );
			?>
            <div class="notice notice-success">
                <p><?php esc_html_e( $message ) ?></p>
            </div>
			<?php
		}
	}

	/**
     * displays a custom error message immediately
     *
	 * @param $error_message
	 *
	 * @return void
	 */
	public static function display_error_notice( $error_message ): void {
		?>
        <div class="notice notice-error">
            <p><?php esc_html_e( $error_message ); ?></p>
        </div>
		<?php
	}

	/**
	 * returns a list of all keyed notifications
	 *
	 * @return array list of all keyed notifications
	 */
	public function get_keyed_notifications(): array {
		return $this->notifications_by_key;
	}

	/**
	 * empty the keyed notifications array
	 *
	 * @return void
	 */
	public function empty_keyed_notifications(): void {
		$this->notifications_by_key = [];
	}

	/**
	 * empty the notifications array
	 *
	 * @return void
	 */
    public function empty_notifications(): void {
        $this->notifications = [];
    }
}
