<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( __NAMESPACE__ . '\Main' ) || exit;

abstract class AccountsPage extends Main {

	public    static function admin(): void {

		self::$page->AccountsPage = self::$pf . 'accounts';

		\add_action( 'admin_menu', static function(): void {
			$user_email = \wp_get_current_user()->user_email;
			$user_domain = \explode( '@', $user_email )[1] ?? '';
			$cap = \apply_filters( self::pf . 'capability', 'manage_options', self::$page->AccountsPage );
			$args = [ 'singular' => 'email', 'plural' => 'emails' ];

			if ( \current_user_can( $cap ) ) {

				if ( self::$domain_only ) {
					$args['domain'] = self::$site_domain;
				}
			} elseif ( \str_ends_with( self::$site_domain, $user_domain ) ) {
				$args['email'] = $user_email;
			}
			$cap = \array_key_exists( 'email', $args ) ? 'cpanel' : $cap;
			\add_menu_page( _x( 'cPanel® Email Addresses', 'Page title' ), _x( 'cPanel® Email', 'Menu label' ), $cap, self::$page->AccountsPage, null, 'dashicons-email', 27 );
			\add_submenu_page(
				self::$page->AccountsPage,
				_x( 'cPanel® Email Addresses', 'Page title' ),
				_x(  'All Email Addresses', 'Submenu label' ),
				$cap,
				self::$page->AccountsPage,
				static function() use ( $args ): void {
					$accounts_table = new AccountsTable( $args );

					$accounts_table->prepare_items();
//					$accounts_table->search_box( 'search', 's' );
?>
				<style>
					.wp-list-table .column-domain {
						width: 24ch;
					}
					.wp-list-table .column-type {
						width: 18ch;
					}
					.wp-list-table .column-diskused,
					.wp-list-table .column-diskquota {
						width: 12ch;
					}
				</style>
				<div class="wrap">
					<h1 class="wp-heading-inline"><?php echo \get_admin_page_title(); ?></h1>
<?php
					if ( ! \array_key_exists( 'email', $args ) ) { ?>
					<a href="<?php echo \menu_page_url( self::$pf . 'new-email', false ); ?>" class="page-title-action"><?php _ex( 'Add new', 'Page Title Button for accounts' ); ?></a>
<?php
					} else {
						echo __( 'Limited to' ), ' <code>', self::email_to_utf8( $args['email'] ), '</code>';
					} ?>
					<hr class="wp-header-end" />
<?php
					$account = UAPI::main_email_account();
					$session = UAPI::create_main_webmail_session();
					$name    = \sanitize_key( $account->email );

					if ( self::$is_proisp ) {
						$action = 'https://webmail.proisp.no/rc/';
					} else {
						$action = 'https://' . $session->hostname . ':2096' . $session->token . '/login';
					} ?>
					<form name="<?=$name?>" id="<?=$name?>" action="<?=$action?>" method="post" class="webmail">
						<input form="<?=$name?>" type="hidden" name="session" value="<?=$session->session?>" />

					</form>
<?php
					foreach ( (array) UAPI::email_accounts() as $account ) {

						if ( ! self::$domain_only || \str_ends_with( $account->email, '@' . self::$site_domain ) ) {
							$session = UAPI::create_webmail_session( $account->email );
							$name     = \sanitize_key( $account->email );

							if ( self::$is_proisp ) {
								$action = 'https://webmail.proisp.no/rc/';
							} else {
								$action   = 'https://' . $session->hostname . ':2096' . $session->token . '/login';
							} ?>
							<form name="<?=$name?>" id="<?=$name?>" action="<?=$action?>" method="post" class="webmail">
								<input form="<?=$name?>" type="hidden" name="session" value="<?=$session->session?>"/>
							</form>
<?php
						}
					}
?>
					<form method="POST" action="">
						<input type="hidden" name="page" value="<?php echo self::$pf; ?>accounts"/>
						<?php $accounts_table->search_box( _x( 'Search Email', 'Button' ), 'search' );?>
						<?php $accounts_table->display(); ?>
					</form>
<?php
//					if ( ! \class_exists( 'WebFacing\cPanel\Main' ) && \current_user_can( 'install_plugins' ) ) {
?>
					<!--p><br /><small><strong><?php //_e( 'Note from the plugin author:' ); ?></strong> <?php //\printf(
						//_x( 'Also check out this complementary <a href="%2$s">%1$s</a> plugin.', '%1$ = Plugin Name, %2$s = url (also localize)' ),
						//'WebFacing™ – cPanel® Storage, resource usage and errors',
						//'https://wordpress.org/plugins/wf-cpanel-right-now-site-health/'
						//); ?><a></a></small></p-->
<?php
//					}
					if ( \current_user_can( 'install_plugins' ) ) {
?>
					<p><br /><small style="display: inline;; width: auto;"><strong><?php \printf( __( 'Hello from %1$s, the plugin author:' ), \explode( ' ', self::$plugin->Name )[0] ); ?></strong> <?php
						\printf(
						/* translators: 1: button text Have questions? */
							'<a href="https://wordpress.org/support/plugin/wf-cpanel-email-accounts/"><button>%1$s</button></a>',
							__( 'Have questions? Need support?' )
						); ?> &nbsp; | &nbsp; <?php
						\printf(
						/* translators: 1: button text Please review */
							'<a href="https://wordpress.org/support/plugin/wf-cpanel-email-accounts/reviews/#new-post" target="_blank"><button>%1$s</button></a>',
							__( 'Please review and rate it' )
						); ?> &nbsp; | &nbsp; <a href="https://paypal.me/knutsp"><button><?php _e( 'Donate to it' ); ?></button></a>
						&nbsp; | &nbsp; <?php _e( 'Thank you for using this plugin!' ); ?></small></p>
<?php
					}
?>
				</div>
<?php
				}
			);
		} );

		\add_action( 'admin_notices', static function(): void {
			$action  = \sanitize_text_field( $_GET['action'] ?? '' );
			$domain  = \idn_to_utf8(        \sanitize_text_field( $_GET['domain'] ?? '' ) );
			$email   = self::email_to_utf8( \sanitize_email(      $_GET['email' ] ?? '' ) );
			$message = [
				'new-forward'     => _x( 'New Forwarder',      'Form Heading' ),
				'default-forward' => _x( 'New Forwarder',      'Form Heading' ),
				'new-failure'     => _x( 'New Failure',        'Form Heading' ),
				'default-fail'    => _x( 'New Failure',        'Form Heading' ),
				'new-black'       => _x( 'New Blackhole',      'Form Heading' ),
				'default-black'   => _x( 'New Blackhole',      'Form Heading' ),
				'new-account'     => _x( 'New Account',        'Form Heading' ),
				'new-responder'   => _x( 'New Autoresponder',  'Form Heading' ),
				'edit-responder'  => _x( 'Edit Autoresponder', 'Form Heading' ),
			];

			if ( $action && \sanitize_text_field( $_GET['source'] ?? '' ) === self::$page->NewEmail ) { ?>
				<div class="notice notice-success is-dismissible">
					<p>
						<?php \printf( _x( '%1$s %2$s on %3$s.', 'Bulk Action Notice, %1$s = action, %2$s = email, %3$s = domain' ), $message[ $action ], ( $email ? \sprintf( _x( 'for %1$s', 'Bulk Action Notice fragment, %1$ = email' ), $email ) : '' ), $domain ) ?>
					</p>
<?php
				$act = \sanitize_key( $_GET['r'] ?? '' );

				if ( $act ) {
					$class = self::$is_pro ? Pro::class : parent::class; ?>
					<p><?=$class::$error->$act->count['description']?></p>
<?php
				} ?>
				</div>
<?php
			}
		} );

		\add_filter( 'removable_query_args', static function( array $args ): array {

			if ( \sanitize_text_field( $_GET['page'] ?? '' ) === self::$page->AccountsPage ) {
				$args[] = 'source';
				$args[] = 'action';
				$args[] = 'type';
				$args[] = 'email';
				$args[] = 'domain';
				$args[] = 'dest';
				$args[] = '_wpnonce';
			}
			return $args;
		} );

		\add_action( 'wp_ajax_' . self::$pf . 'password', static function(): void {
			$method   = \strtolower( $_SERVER['REQUEST_METHOD'] );
			$cap      = \apply_filters( self::pf . 'capability', 'manage_options', 'set_password' );
			$email    = \sanitize_email( $_POST['email' ] ?? '' );
			$password = $_POST['password'] ?? '';

			if ( $method === 'post' && $email && $password && ( \current_user_can( $cap ) || $email === \wp_get_current_user()->user_email ) ) {
				$result = UAPI::set_password( $email, $password );

				if ( $result->has_errors() ) {
					\esc_attr_e( $result->get_error_message() );
				} else {
					/* translators: 1: email */
					\printf( _x( 'Password changed for email account %1$s.', 'Result message' ), $email );
				}
			}
			\wp_die();
		} );

		\add_action( 'wp_ajax_' . self::$pf . 'quota', static function(): void {
			$method = \strtolower( $_SERVER['REQUEST_METHOD'] );
			$cap    = \apply_filters( self::pf . 'capability', 'manage_options', 'set_quota' );
			$email  = \sanitize_email( $_POST['email'] ?? '' );

			if ( $method === 'post' && $email && ( \current_user_can( $cap ) || $email === \wp_get_current_user()->user_email ) ) {
				$quota  = \intval( $_POST['quota'] );
				$result = UAPI::set_quota( $email, $quota );

				if ( $result->has_errors() ) {
					\esc_attr_e( $result->get_error_message() );
				} else {
					\printf( _x( 'Quota changed to %1$f GB for email account %2$s.', 'Result message' ), $quota * \MB_IN_BYTES / \GB_IN_BYTES, $email );
//					self::delete_transients();
				}
			} else {
				\wp_die( _x( 'Sorry, you are not allowed to send settings.', 'Notice error message' ) );
			}
			\wp_die();
		} );

		\add_action( 'wp_ajax_' . self::$pf . 'send', static function(): void {
			$method = \strtolower( $_SERVER['REQUEST_METHOD'] );
			$cap    = \apply_filters( self::pf . 'capability', 'manage_options', 'send_settings' );
			$email  = \sanitize_email( $_POST['email'] );

			if ( $method === 'post' && ( \current_user_can( $cap ) || $email === \wp_get_current_user()->user_email ) ) {
				$to     = \sanitize_email( $_POST['to'] );
				$result = UAPI::send_settings( $email, $to );

				if ( $result->has_errors() ) {
					\esc_attr_e( $result->get_error_message() );
				} else {
					\printf(
						_x( 'Client instructions for email account %1$s sent to %2$s.', 'Result message' ), self::email_to_utf8( $email ),
						\esc_attr( self::email_to_utf8( $to ) )
					);
				}
			} else {
				\wp_die( \sprintf(
					_x( 'Sorry, you are not allowed to send settings.', 'Notice error message' ),
					self::email_to_utf8( $email )
				) );
			}
			\wp_die();
		} );
	}
}
