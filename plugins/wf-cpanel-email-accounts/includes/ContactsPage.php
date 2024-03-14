<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( __NAMESPACE__ . '\Main' ) || exit;

abstract class ContactsPage extends Main {

	private   static string   $contact_file = '';

	public    static function admin(): void {

		self::$page->ContactsPage = self::$pf . 'contact';

		\add_action( 'admin_menu', static function(): void {

			if ( ! self::$domain_only && ( ! \is_multisite() || \is_super_admin() ) ) {

				\add_submenu_page(
					self::$page->AccountsPage,
					_x( 'cPanel® Contact Information', 'Page Title' ),
					_x( 'Contact Information', 'Submenu Label' ),
					'update_core',
					self::$page->ContactsPage,
					static function(): void {
//						$editable = \is_writeable( self::$contact_file );
						$has_feature = UAPI::has_features( ['updatecontact'] );
						$editable =  $has_feature && \current_user_can( 'install_plugins' );
						$domains  = UAPI::mail_domains();

						if ( $editable && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
							$action   = \sanitize_text_field( $_POST['action'] ?? '' );
							\check_admin_referer( $action );
							$emails   = \array_map( 'sanitize_email', $_POST['emails'] );
							$emails   = \array_slice( $emails, 0, 2 );
							$omails   = \array_map( 'sanitize_email', $_POST['omails'] );
							$omails   = \array_slice( $omails, 0, 2 );
							$password = \trim( $_POST['password'] ?? '' );
							self::notice( $omails, UAPI::set_contact_info( $emails[0], $emails[1], $omails[0], $omails[1], $password ) );
//							self::delete_transients();
						}
						$contact_info = UAPI::get_user_info();
						$primary   = $contact_info->contact_email ?? '';
						$secondary = $contact_info->contact_email_2 ?? '';

?>
			<div class="wrap">
				<style>
					.form-table {
						width: calc(30% + 32ch);
					}
					.form-table th, .form-table td {
						padding: 0 1ch;
					}
					input {
						color: initial !important;
					}
					input.hidden {
						display: none;
					}
					.form-table tbody td input:focus:invalid {
						background-color: LightYellow;
						border-color: Red;
					}
					p.info, span.info {
						font-weight: bold;
						color: DarkBlue;
					}
					p.warning, span.warning {
						font-weight: bold;
						color: OrangeRed;
					}
				</style>
				<h1 class="wp-heading-inline"><?php echo \get_admin_page_title(); ?></h1>
				<hr class="wp-header-end" />
				<h2><?php echo _x( 'Contact Email Addresses', 'Form Header' ); ?></h2>
				<p><?php _e( 'Addresses to receive account notifications.' ); ?> <?php echo $has_feature ? '' : __( 'This cPanel® configuration does not allow you to edit.' ); ?></p>
				<p<?php echo \in_array( \explode( '@', $primary )[1], $domains ) && ( empty( $secondary ) || \in_array( \explode( '@', $secondary )[1], $domains ) ) ? ' class="warning"' : ''; ?>><?php _e( 'You may use an email address on a domain hosted this server. However, this is not recommended, because you may not receive messages in case the server encounters problems. For example, if your mailbox exceeds its quota, you will not receive any new email, including notifications.' ); ?></p>
				<form id="edit-contact" name="emails" method="post" action="" autocomplete="off">
					<?php $action = 'edit-contact-email'; \wp_nonce_field( $action ) ; ?>
					<input type="hidden" name="action" value="<?=$action?>">
					<input type="hidden" name="omails[0]" value="<?=$primary?>">
					<input type="hidden" name="omails[1]" value="<?=$secondary?>">
					<input type="email" name="user" autocomplete="off" class="hidden" value="<?=UAPI::main_email_account()->email?>"/>
					<table class="form-table">
						<tr>
							<th>
								<label for="email1"><?php echo _x( 'Primary Address', 'Form Label' ); ?></label>
							</th>
							<td>
								<input type="email" id="email1"<?php echo $editable ? ' name="emails[0]" list="users" autocomplete="email"' : ' disabled="disabled"'; ?> value="<?php echo $primary; ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="email2"><?php _ex( 'Secondary Address', 'Form Label' ); ?></label>
							</th>
							<td>
								<input type="email" id="email2"<?php echo $editable ? ' name="emails[1]" list="users" autocomplete="email"' : ' disabled="disabled"'; ?> value="<?php echo $secondary; ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="password"><?php _ex( 'Account password (current)', 'Form Label' ); ?> <strong style="color: red;">*</strong></label>
							</th>
							<td style="white-space: nowrap;">
								<input type="password" id="password" name="password" required="required" placeholder="<?php _e( 'secret' ); ?>" autocomplete="new-password" spellcheck="false"/> <?php _e( '(required, not changed, not saved)' ); ?>
							</td>
						</tr>
						<tr>
							<td>
								<button type="submit" class="button-primary"<?php echo $editable ? '' : ' disabled="disabled"' ; ?>><?php \_e( 'Save Changes' ); ?></button>
							</td>
							<td></td>
						</tr>
					</table>
					<datalist id="users">
<?php
				foreach ( \array_unique( \array_merge( [ \get_bloginfo( 'admin_email' ), UAPI::main_email_account()->email ], \get_users( [ 'fields' => 'user_email', 'role' => 'administrator' ] ) ) ) as $user_email ) { ?>
						<option value="<?=$user_email?>"></option>
<?php
				} ?>
					</datalist>
					<p><?php echo UAPI::get_user_info()->notify_contact_address_change ? '<span class="info">' . __( 'Info: Email notifications, to both old and new address, on contact address change is active.' ) . '</span>' : '<span class="notice">' . __( 'Merknad: Email notification on contact address change is not active.' ) . '</span>'; ?></p>
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
				}, 3 );
			}
		} );
	}

	private static function notice( array $olds, \WP_Error $result ): void {

		if ( $result->has_errors() ) {
?>
				<div class="notice notice-error is-dismissible">
					<p><?php \printf(
						_x(
							'%1$s',
							'Notice error message'
						),
						\esc_html( $result->get_error_message() )
					); ?></p>
				</div>
<?php
		} else {
?>
				<div class="notice notice-success is-dismissible">
					<p><?php \printf(
						empty( $olds[0] ) && empty( $olds[1] ) ?
							_x(
								'Contact email address(es) added.',
								'Notice success message'
							) :
							_x(
								'Contact email address(es) changed or added to %1$s.',
								'Notice success message'
							)
						,
						'<code>' . \implode( '</code> ' . __( 'and' ) . ' <code>', \array_map( [ __NAMESPACE__ . '\Main', 'email_to_utf8' ], \array_filter( $olds, static fn( string $var ): bool => ! empty( $var ) ) ) ) . '</code>'
					); ?></p>
				</div>
<?php
		}
	}
}
