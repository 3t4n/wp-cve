<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( __NAMESPACE__ . '\Main' ) || exit;

abstract class TokensPage extends Main {

	public    static function admin(): void {

		self::$page->TokensPage = self::$pf . 'tokens';

		\add_action( 'admin_menu', static function(): void {

			if ( ( new \WP_Http )->block_request( 'https://' . self::$host_name ) ) {
				return;
			}
			$user_email = \wp_get_current_user()->user_email;
			$user_domain = \explode( '@', $user_email )[1];
			$cap = \apply_filters( self::pf . 'capability', 'manage_options', self::$page->TokensPage );
			$args = [ 'singular' => 'token', 'plural' => 'tokens', 'email' => $user_email, 'domain' => $user_domain ];

			if ( self::$is_subadmin || ( self::$domain_only && ! \in_array( self::$main_domain, [ self::$site_domain, $user_domain ] ) ) ) {
				return;
			}
			\add_submenu_page(
				self::$page->AccountsPage,
				_x( 'cPanel® Access Tokens', 'Page title' ),
				_x(  'Access Tokens', 'Submenu label' ),
				$cap,
				self::$page->TokensPage,
				static function() use ( $args ): void {
					$tokens_table = new TokensTable( $args );
					$tokens_table->prepare_items();
?>
				<style>
					.wp-list-table   {}
/*					.wp-list-table .column-name { width: 52ch; }*/
					.wp-list-table .column-full,
					.wp-list-table .column-known,
					.wp-list-table .column-active { width: 11ch; }
				</style>
				<div class="wrap">
					<h1 class="wp-heading-inline"><?php echo \get_admin_page_title(); ?></h1>
					<a href="<?php echo \add_query_arg( [ 'action' => 'new-token', '_wpnonce' => \wp_create_nonce( 'new-token' ) ], \menu_page_url( self::$page->TokensPage, false ) ); ?>" class="page-title-action"><?php _ex( 'Create new', 'Page Title Button for tokens' ); ?></a>
					<p><?php _e( 'A token is needed to access cPanel® data via HTTP API.' ); ?></p>
					<hr class="wp-header-end"/>
					<form method="POST" action="">
						<input type="hidden" name="page" value="<?php echo self::$page->TokensPage; ?>"/>
						<?php $tokens_table->display(); ?>
					</form>
<?php
					if ( \current_user_can( 'install_plugins' ) ) {
?>
					<p><br /><small style="display: inline; width: auto;"><strong><?php \printf( __( 'Hello from %1$s, the author of this plugin:' ), \explode( ' ', self::$plugin->Name )[0] ); ?></strong> <?php
						\printf(
							'<a href="https://wordpress.org/support/plugin/wf-cpanel-email-accounts/"><button>%1$s</button></a>',
							__( 'Have questions? Need support?' )
						); ?> &nbsp; | &nbsp; <?php
						\printf(
							'<a href="https://wordpress.org/support/plugin/wf-cpanel-email-accounts/reviews/#new-post" target="_blank"><button>%1$s</button></a>',
							__( 'Please review and rate it' )
						); ?> &nbsp; | &nbsp; <a href="https://paypal.me/knutsp"><button><?php _e( 'Donate to it' ); ?></button></a>
						&nbsp; | &nbsp; <?php _e( 'Thank you very much for using this plugin!' ); ?></small></p>
<?php
					}
?>
				</div>
<?php
				}
			);
		} );

		\add_action( 'admin_notices', static function(): void {

			if ( \sanitize_title( $_GET['page'] ?? '' ) === self::$page->TokensPage ) {
				$method = \strtolower( $_SERVER['REQUEST_METHOD'] );

				if ( $method === 'get' ) {
					$action = \sanitize_key( $_GET['action'] ?? '' );

					if ( $action === 'new-token' ) {
						$cap   = \apply_filters( Main::pf . 'capability', 'manage_options', $action );

						if ( \current_user_can( $cap ) ) {

							if ( \wp_verify_nonce( $_GET['_wpnonce'], $action ) ) {?>
								<script>
									var data = {
										'action' : '<?php echo Main::$pf . $action; ?>',
										'name'   : prompt( '<?php echo __( 'Name for token:' ); ?>', '<?=\wp_get_current_user()->user_nicename?>' ),
										'expires': prompt( '<?php echo __( 'Expires in months (optional, leave blank for indefinetely):' ); ?>' )
									}
									if ( data.name ) {
										jQuery.post( ajaxurl, data, function( response) {
											alert( response );
											location.reload();
										} );
									}
								</script>
<?php
							} else {
								\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
							}
						} else { ?>
							<div class="notice notice-error is-dismissible">
								<p><?php _ex( 'Sorry, you are not allowed to create tokens.', 'Notice error message' ); ?></p>
							</div>
<?php
						}
					} ?>
					<div class="notice notice-info is-dismissible">
						<p><?php
							if ( \defined( 'WF_CPANEL_HOST' ) || \defined( 'WF_CPANEL_USER' ) ) {
								\printf( '<strong>%1$s:</strong> <code>%2$s</code> <strong>%3$s:</strong> <code>%4$s</code>. &nbsp; ',
									__( 'cPanel®: Host name' ),
									self::$host_name,
									__( 'User name' ),
									self::$cpanel_user,
								);
							}
							\printf(
								__( '<strong>Previous method used:</strong> <code>%1$s</code>. <strong>Response:</strong> <code>%2$s</code>' ),
								self::$has_http && ! self::$use_exec ? 'HTTP' : 'SHELL',
								UAPI::$response_message,
							);
						?></p>
					</div>
<?php
				}
			}
		} );

		\add_filter( 'removable_query_args', static function( array $args ): array {

			if ( \sanitize_title( $_GET['page'] ?? '' ) === self::$page->TokensPage ) {
				$args[] = 'action';
				$args[] = 'name';
				$args[] = '_wpnonce';
			}
			return \array_unique( $args );
		} );

		\add_action( 'wp_ajax_' . self::$pf . 'rename_token', static function(): void {
			$method = \strtolower( $_SERVER['REQUEST_METHOD'] );
			$cap    = \apply_filters( self::pf . 'capability', 'manage_options', 'rename_token' );
			$name   = \sanitize_text_field( $_POST['token'] ?? '' );
			$active = \boolval( $_POST['active'] ?? '' );

			if ( $method === 'post' && $name && \current_user_can( $cap ) ) {
				$new_name  = \sanitize_text_field( $_POST['new_name'] );
				$result = UAPI::token_rename( $name, $new_name );

				if ( $result->has_errors() ) {
					\esc_attr_e( $result->get_error_message() );
				} else {
					$aknown_key = self::pf . 'token.' . self::$cpanel_user . ( self::$remote_cpanel ? '@' . self::$host_name : '' ) . '.' . $name;
					$value = get_option( $aknown_key );
					delete_option( $aknown_key );

					if ( $value ) {
						$aknown_key = self::pf . 'token.' . self::$cpanel_user. ( self::$remote_cpanel ? '@' . self::$host_name : '' ) . '.' . $new_name;
						update_option( $aknown_key, $value );

						if ( $active ) {
//							$active_key = self::pf . 'token.active.' . UAPI::$cpanel_user;
							update_option( self::$active_key, $new_name );
						}

					}
					\printf( _x( 'Token %1$s renamed to %2$s.', 'Result message' ), $name, $new_name );
//					self::delete_transients();
				}
			} else {
				\wp_die( _x( 'Sorry, you are not allowed to rename tokens.', 'Notice error message' ) );
			}
			\wp_die();
		} );

		\add_action( 'wp_ajax_' . self::$pf . 'new-token', static function(): void {
			$method  = \strtolower( $_SERVER['REQUEST_METHOD'] );
			$cap     = \apply_filters( self::pf . 'capability', 'manage_options', 'new-token' );
			$name    = \sanitize_title( $_POST['name'] ?? '' );
			$expires = \intval( $_POST['expires'] ?? '' );
			$expires = $expires ? \time() + ( $expires * \MONTH_IN_SECONDS ) : 0;

			if ( $method === 'post' && $name && \current_user_can( $cap ) ) {
				$result = UAPI::token_add( $name, $expires );

				if ( $result->has_errors() ) {
					\esc_attr_e( $result->get_error_message() );
				} else {
					$aknown_key = self::pf . 'token.' . self::$cpanel_user . ( self::$remote_cpanel ? '@' . self::$host_name  : '' ) . '.' . $name;
					update_option( $aknown_key, $result->get_error_data() );
					\printf( _x( 'Token %1$s created.', 'Result message' ), $name );
//					self::delete_transients();
				}
			} else {
				\wp_die( _x( 'Sorry, you are not allowed to create tokens.', 'Notice error message' ) );
			}
			\wp_die();
		} );
	}
}
