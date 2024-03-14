<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( __NAMESPACE__ . '\Main' ) || exit;

abstract class BoxesPage extends Main {

	public    static function admin(): void {

		self::$page->BoxesPage = self::$pf . 'boxes';

		\add_action( 'admin_menu', static function(): void {
			$accounts = (array) UAPI::email_accounts();

			if ( self::$domain_only ) {
				$accounts = \array_filter( $accounts, static function( \stdClass $account ): bool {
					return \str_ends_with( $account->email, '@' . self::$site_domain );
				} );
			}
			$email = \sanitize_email( $_POST['email'] ?? ( $_GET['email'] ?? ( \count( $accounts ) === 1 ? \reset( $accounts)->email : '' ) ) );
			$args = [ 'singular' => 'box', 'plural' => 'boxes', 'email' => $email ];
			$cap = \apply_filters( self::pf . 'capability', 'manage_options' );

			\add_submenu_page(
				self::$page->AccountsPage,
				$args['email'] ?
					\sprintf( _x( 'cPanel® Email Boxes for &laquo;%1$s&raquo;', 'Page title' ), self::email_to_utf8( $args['email'] ) ) :
					_x( 'cPanel® Email Boxes', 'Page title' ),
				_x( 'Email Boxes', 'Submenu label' ),
				$cap,
				self::$page->BoxesPage,
				function() use ( $args, $accounts ): void {
?>
				<div class="wrap">
					<h1 class="wp-heading-inline"><?php echo \get_admin_page_title(); ?></h1>
					<hr class="wp-header-end" />
<?php
					if ( empty( $args['email'] ) ) {
?>
					<form method="get" action="">
						<input type="hidden" name="page" value="<?php echo self::$page->BoxesPage; ?>">
						<select name="email">
<?php
							$account = UAPI::main_email_account();

							if ( ! self::$domain_only || \str_ends_with( $account->email, '@' . self::$site_domain ) ) {
?>
								<option value="<?php echo $account->email; ?>"><?php echo self::email_to_utf8( $account->email ); ?></option>
<?php
							}

							foreach ( $accounts as $account ) {
?>
								<option value="<?php echo $account->email; ?>"><?php echo Main::email_to_utf8( $account->email ); ?></option>
<?php
							}
?>
						</select>
						<button type="submit"><?php _ex( 'Select', 'Button label' ); ?></button>
					</form>
<?php
					} else {
						$cap = \apply_filters( self::pf . 'capability', 'manage_options' );

						if ( ! \current_user_can( $cap ) && \str_ends_with( $site_domain, $user_domain ) ) {
							$args['email'] = $user_email;
						}
						$boxes_table = new BoxesTable( $args );
						$boxes_table->prepare_items();
?>
					<form method="POST" action="">
						<input type="hidden" name="page" value="<?php echo self::$page->BoxesPage; ?>" />
<?php
						$boxes_table->display();
?>
					</form>

					<p><strong><?php _e('Delivery routing:'); ?></strong> <em><?=UAPI::trace($args['email'])?></em></p>
					<p><strong><?= _x( 'Current count of outgoing emails queue', 'Site Health Info' ); ?>:</strong> <em><?=UAPI::queued_emails($args['email'])?></em></p>
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
					}
?>
				</div>
<?php
				}
			);
		} );
	}
}