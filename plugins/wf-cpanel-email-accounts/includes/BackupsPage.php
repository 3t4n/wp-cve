<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( __NAMESPACE__ . '\Main' ) || exit;

abstract class BackupsPage extends Main {

	public    static function admin(): void {

		self::$page->BackupsPage = self::$pf . 'backup';

		\add_action( 'admin_menu', static function(): void {
			$user_email = \wp_get_current_user()->user_email;
			$user_domain = \explode( '@', $user_email )[1];
			$cap = \apply_filters( self::pf . 'capability', 'manage_options', self::$page->BackupsPage );
			$args = [ 'singular' => 'account', 'plural' => 'accounts', 'email' => $user_email, 'domain' => $user_domain ];

			if ( self::$is_subadmin || ( self::$domain_only && ! \in_array( self::$main_domain, [ self::$site_domain, $user_domain ] ) ) ) {
				return;
			}
			\add_submenu_page(
				self::$page->AccountsPage,
				_x( 'cPanel® Account Backups', 'Page title' ),
				_x(  'Account Backups', 'Submenu label' ),
				$cap,
				self::$page->BackupsPage,
				static function() use ( $args ): void {
					$args['creds'] = \request_filesystem_credentials( \add_query_arg( [ 'page' => self::$page->BackupsPage ], \admin_url( 'admin.php' ) ), '', false, false );
					$backups_table = new BackupsTable( $args );
					$backups_table->prepare_items();
?>
				<style>
					.wp-list-table   { min-width: 120ch; max-width: 99.9%; }
					.column-type     { width:  6ch; }
					.column-size     { width: 10ch; white-space: nowrap; }
					.column-num_size { width: 12ch; white-space: nowrap; }
					.column-started  { width: 36ch; }
					.column-mtime    { width: 12ch; white-space: nowrap; }
					.column-finished { width: 28ch; white-space: nowrap; }
					.column-ctime    { width: 12ch; white-space: nowrap; }
				</style>
				<div class="wrap">
					<h1 class="wp-heading-inline"><?php echo \get_admin_page_title(); ?></h1>
<?php
					if ( ! $backups_table->processing ) { ?>
						<a href="<?php echo \add_query_arg( [ 'action' => 'new-backup', '_wpnonce' => \wp_create_nonce( 'new-backup' ) ], \menu_page_url( self::$page->BackupsPage, false ) ); ?>" class="page-title-action"><?php _ex( 'Create new', 'Page Title Button' ); ?></a>
<?php
					} ?>
					<p><?php _e( 'A full account backup may be used to restore everything, including all data, or move to another web host also using cPanel®.' ); ?></p>
					<hr class="wp-header-end" />
					<form method="POST" action="">
						<input type="hidden" name="page" value="<?php echo self::$page->BackupsPage; ?>"/>
						<?php $backups_table->display(); ?>
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
			$action  = \sanitize_text_field( $_GET['action'] ?? '' );
		} );

		\add_filter( 'removable_query_args', static function( array $args ): array {

			if ( \sanitize_text_field( $_GET['page'] ?? '' ) === self::$page->BackupsPage ) {
				$args[] = 'action';
				$args[] = '_wpnonce';
			}
			return \array_unique( $args );
		} );
	}
}
