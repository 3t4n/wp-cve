<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( 'WP' ) || exit;

abstract class NewEmail extends Main {

	public    static int     $tz_offset = 0;

	protected static bool    $batch_mode = false;

	private   static array   $batch_entries = [];

	private   static array   $success = [];

	public    static function admin(): void {

		self::$page->NewEmail = self::$pf . 'new-email';

		self::$tz_offset = \wp_timezone()->getOffset( new \DateTime );

		\add_action( 'admin_bar_menu', static function( \WP_Admin_Bar $bar ): void {
			$bar->add_node( [ 'parent' => 'new-content', 'id' => 'cpanel', 'title' => _x( 'Email address', 'Toolbar label' ), 'href' => \add_query_arg( [ 'page' => self::$pf . 'new-email' ], \admin_url( 'admin.php' ) ) ] );
		}, 121 );

		\add_action( 'admin_init', static function(): void {

			if ( ( $_GET['page'] ?? '' ) === self::$page->NewEmail ) {
				self::$batch_mode = \boolval( \get_user_option( 'wc-show-batch-entry' ) );
				self::save();
			}
		}, 11 );

		\add_action( 'current_screen', static function(): void {

			if ( ( $_GET['page'] ?? '' ) === self::$page->NewEmail ) {

				\add_filter( 'screen_settings', static function( string $html, \WP_Screen $screen ): string {

					$html .= \PHP_EOL . ' <fieldset class="' . self::$pf . '">';
					$html .= \PHP_EOL . '  <legend>' . _x( 'Show Forms:', 'Screen Options Legend' ) . '</legend>';
					$html .= \PHP_EOL . '  <ul style="margin: 0;">';

					foreach ( $screen->get_options() as $option ) {
						$html .= \PHP_EOL . '   <li style="float: left; padding-right: 1ch;"><label style=" line-height: 1;"><input type="checkbox" name="' . $option['option'] . '"' . \checked( $option['value'], true, false ) . 'value="' . true . '"/> ' . $option['label'] . '</label></li>';
					}
					$html .= \PHP_EOL . '  </ul>';
					$html .= \PHP_EOL . '  </fieldset>';
					$html .= \PHP_EOL . '<button type="submit" name="action" value="user-options" class="button button-secondary">' . _x( 'Save Settings', 'Screen Options Button Text' ) . '</button>';
					return $html;
				}, 10, 2 );

				\add_action( 'load-'. self::$screen->id, static function(): void {

					$options = [
						'batch-entry'       => [        '<strong>' . _x( 'Batch Entry',   'Form Heading' ) . '</strong>', false ],
						'new-forward'       => [ \sprintf( __(    '%1$s from' ), _x( 'New Forwarder', 'Form Heading' ) ),  true ],
						'default-forward'   => [ \sprintf( __( 'Default %1$s' ), _x( 'New Forwarder', 'Form Heading' ) ), false ],
						'new-failure'       => [ \sprintf( __(    '%1$s from' ), _x( 'New Failure',   'Form Heading' ) ), false ],
						'default-failure'   => [ \sprintf( __( 'Default %1$s' ), _x( 'New Failure',   'Form Heading' ) ), false ],
						'new-blackhole'     => [ \sprintf( __(    '%1$s from' ), _x( 'New Blackhole', 'Form Heading' ) ), false ],
						'default-blackhole' => [ \sprintf( __( 'Default %1$s' ), _x( 'New Blackhole', 'Form Heading' ) ), false ],
						'new-account'       => [                     _x( 'New Account &amp; Mailbox', 'Form Heading' )  ,  true ],
						'new-responder'     => [                     _x( 'New Autoresponder',         'Form Heading' )  , false ],
					];

					if ( self::$is_exceeded ) {
						unset( $options['batch-entry'] );
					}

					foreach ( $options as $option => $lbl_def ) {

						$opt     = \str_replace(
							[ 'default', 'forward', 'failure', 'blackhole', 'account', 'responder' ],
							[ 'def',     'forw',    'fail',    'black',     'acc',     'resp'      ],
							$option,
						);
						$option = 'wc-show-' . $option;
						$default = \apply_filters( self::$page->NewEmail .'_user-option', $lbl_def[1], $option, \get_current_user() );
						self::$screen->add_option( $opt,  [ 'option' => $option, 'default' => $default ] );
					}
					self::save_options();

					foreach ( $options as $option => $lbl_def ) {

						$opt     = \str_replace(
							[ 'default', 'forward', 'failure', 'blackhole', 'account', 'responder' ],
							[ 'def',     'forw',    'fail',    'black',     'acc',     'resp'      ],
							$option,
						);
						$option = 'wc-show-' . $option;
						$label   = $lbl_def[0];
						$default = \apply_filters( self::$page->NewEmail .'_user-option', $lbl_def[1], \get_current_user() );
						$value   = \get_user_option( $option );
						$value   = $value === false ? $default : $value;
						self::$screen->add_option( $opt,  [ 'label' => $label, 'option' => $option, 'value' => $value, 'default' => $default ] );
					}
				} );
			}
		} );

		\add_action( 'admin_menu', static function(): void {

			\add_submenu_page(
				self::$page->AccountsPage,
				_x( 'cPanel® Add New Email Address', 'Page Title' ),
				_x( 'Add New Email', 'Submenu Label' ),
				\apply_filters( self::pf . 'capability', 'manage_options' ),
				self::$page->NewEmail,
				static function(): void {
					$mail_domains = UAPI::mail_domains();

					if ( self::$domain_only ) {
						$mail_domains = \array_filter( $mail_domains, static function( string $mail_domain ): bool {
							return $mail_domain === self::$site_domain;
						} );
					}
					$readonly = \count( $mail_domains ) === 1 ? ' readonly="readonly"' : '';

					$dest_emails = \wp_list_pluck( \array_filter( (array) UAPI::email_forwarders(), static function( \stdClass $account ): bool {
						return (bool) \is_email( $account->dest );
					} ), 'dest' );
					$accounts = (array) UAPI::email_accounts();
					$used_emails = \array_merge(
						\wp_list_pluck( $accounts, 'email' ),
						[ \sanitize_email( UAPI::main_email_account()->email ) ],
					);
					$listed_emails = [];

					if ( self::$batch_mode ) {
						$listed_emails = \array_merge( \wp_list_pluck( \array_filter(
								self::parse_entries(), static fn( \stdClass $val ): bool =>
									$val->valid && $val->type === 'account'
						), 'email' ), [ \sanitize_email( $_POST['user'] . '@' . $_POST['domain'] ) ] );
					}

					if ( self::$domain_only ) {
						$accounts = \array_filter( $accounts, static function( \stdClass $account ): bool {
							return \str_ends_with( $account->email, '@' . self::$site_domain );
						} );
						$to_emails = \array_unique( \array_merge( \wp_list_pluck( $accounts, 'email' ), [ \wp_get_current_user()->user_email ], [ \get_bloginfo( 'admin_email' ) ] ) );
					} else {
						$to_emails = \array_unique( \array_merge( \wp_list_pluck( $accounts, 'email' ), $dest_emails, [ \wp_get_current_user()->user_email ], [ \get_bloginfo( 'admin_email' ) ], [ \sanitize_email( UAPI::main_email_account()->email ) ] ) );
					}

					$action  = \sanitize_key( $_GET['action'] ?? '' );
					$type    = \sanitize_key( $_GET['type'  ] ?? '' );
					$domain  = \sanitize_text_field( $_GET['domain'] ?? '' );
					$email   = \sanitize_email(      $_GET['email' ] ?? '' );
//					$pattern = '^[A-Za-z0-9_!#$%&\'*+/=?`{|}~^-]+(?:\.[A-Za-z0-9_!#$%&\'*+/=?`{|}~^-]+↵)*$';
					$pattern = '^[A-Za-z0-9_!#$%&\'\.*+/=?`{|}~^-]+(?:\.[A-Za-z0-9_!#$%&\'*+/=?`{|}~^-]+↵)*$';
					$no_user = 'No Such User Here';
					$in_user = __( $no_user, 'Fail Message' );
					$edit    = false;

					if ( $action === 'row-edit' ) {
						$edit = $type;
					}

					if ( $edit === 'responder' ) {
						$responder = UAPI::email_responder( $email );
						$responder->user       = \explode( '@', $email )[0];
						$responder->start_date = \date_i18n( 'Y-m-d', $responder->start );
						$responder->start_time = \date_i18n( 'H:i',   $responder->start );
						$responder->stop_date  = \date_i18n( 'Y-m-d', $responder->stop  );
						$responder->stop_time  = \date_i18n( 'H:i',   $responder->stop  );
						$to_emails = \array_unique( \array_merge( $to_emails, [ \sanitize_email( $responder->from ) ] ) );
					} else {
						$responder = new \stdClass;
						$responder->from       = \wp_get_current_user()->display_name;
						$responder->subject    = 'Re: %subject%';
						$responder->body       = \str_replace( '%div%', '--  ', \sprintf(
							_x( 'Hello %%from%% %%email%%,


%%div%%
Regards,
%%responder%%
%1$s',
								'Email Body Suggestion, %responder% will be expanded to Responder Name, %1$s = Site Name'
							),
							\get_bloginfo()
						) );
						$responder->user       = '';
						$responder->start_date = \date_i18n( 'Y-m-d' );
						$responder->start_time = \date_i18n( 'H:i' );
						$responder->stop_date  = \date_i18n( 'Y-m-d', \strtotime( '+1 weeks', \current_time( 'timestamp' ) ) );
						$responder->stop_time  = \date_i18n( 'H:i' );
						$responder->interval   = 24;
						$responder->is_html    = 0;
					}
					$options = self::$screen->get_options();
					self::$batch_mode = ! self::$is_exceeded && \boolval( $options['batch-entry']['value'] ?? '' );

					if ( self::$batch_mode ) {
						$options['def-forw' ]['value'] = false;
						$options['new-fail' ]['value'] = false;
						$options['def-fail' ]['value'] = false;
						$options['new-black']['value'] = false;
						$options['def-black']['value'] = false;
						$options['new-resp' ]['value'] = false;
						$one = false;

						foreach ( [ 'new-forw', 'new-acc' ] as $class ) {
							$one = $one || $options[ $class ]['value'];
						}

						if ( ! $one ) {
							$options['new-forw']['value'] = true;
						}
					}
					list ( $a, $b, $c, $e, $f, $i, $k, $l, $n, $r, $s, $u, $x ) = self::list;
					$uf  = self::get_use( $f . $r );
					$ua  = self::get_use( $c . $n );
					$ubf = self::get_use( $f . $r,  true );
					$uba = self::get_use( $c . $n,  true );
					$lf  = self::get_use( $f . $r, false, true );
					$la  = self::get_use( $c . $n, false, true );
					$lbf = self::get_use( $f . $r,  true, true );
					$lba = self::get_use( $c . $n,  true, true );
					$flt = \max( self::$batch_mode ?
						$lbf - $ubf - ( self::$success[ $f ] ?? 0 ) :
						$lf  - $uf
					, 0 );
					$alt = \max( self::$batch_mode ?
						$lba - $uba - ( self::$success[ $c . $n ] ?? 0 ) :
						$la  - $ua
					, 0 );
					$exd = self::$batch_mode ? self::$is_exceeded : false; ?>
		<div class="wrap">
			<style scoped="scoped">
<?php
					foreach ( $options as $class => $option ) {

						if ( ! $option['value'] ) {
							echo '.', $class, ' { display: none; }', \PHP_EOL;
						} else {
							echo '.', $class, ' { display: block !important; }', \PHP_EOL;
						}
					} ?>
				.form-table {
					width: 120ch;
				}
				.form-table th, .form-table td {
					padding: 0 1ch;
				}
				.form-table tbody th:nth-of-type(1), .form-table tbody td:nth-of-type(1) {
					width: 15%;
					white-space: nowrap;
					padding-right: 0;
					text-align: right;
				}
				.form-table tbody td input, .form-table tbody td select {
					width: calc( 100% - 2ch);
				}
				.form-table tbody td input:focus:invalid {
					background-color: LightYellow;
					border-color: Red;
				}
				.form-table thead th:nth-of-type(2), .form-table tbody td:nth-of-type(2) {
					width: 25%;
					padding-left: 0;
				}
				.form-table th:nth-of-type(3), .form-table td:nth-of-type(3) {
					width: 30%;
				}
				.form-table th:last-of-type, .form-table td:last-of-type {
					text-align: left;
				}
			</style>
			<h1 class="wp-heading-inline"><?php echo \get_admin_page_title(); ?></h1>
			<p><em>
<?php				/* translators: 1: Screen Options */
					\printf( _x( 'To see hidden forms, or use batch mode on this screen, open the %1$s drawer above, tick the boxes and save.', 'New Email info message' ), \__( 'Screen Options', 'default' ) ); ?>
			</em></p>
			<hr class="wp-header-end"/>
			<!--p><br /></p-->
<?php			if ( ! $edit ) {
					$batch_post = \in_array( $_POST['action'] ?? '', [ 'new-forward', 'new-account', 'batch-entry' ], true );
					$extra_chars = 0;

					foreach ( self::$batch_entries as $entries ) {
						$extra_chars += \strlen( $entries ) - 75;
					}
					$extra_lines = \intdiv( $extra_chars, 75 ); ?>
			<h2 class="batch-entry">֎ <?php _ex( 'Batch Entry', 'Form Heading' ); ?><?=!self::$is_pro?' &nbsp; ('.__('trial').') &nbsp; Go '.self::pro_link:''?></h2>
			<form id="batch-entry" class="batch-entry" name="batch-entry" method="post" action="">
				<input type="hidden" name="action" value="batch-entry">
				<table class="form-table">
					<tbody>
						<tr>
							<td style="text-align: left;">
								<textarea name="entries" cols="75" rows="<?=\count(self::$batch_entries)+1+$extra_lines?>" placeholder="<?php if ( ! $batch_post ) \printf( _x( 'Do not type here! Use &laquo;%1$s&raquo; or &laquo;%2$s&raquo;.', 'Batch Entry List Placeholder' ), _x( 'New Forwarder', 'Form Heading' ), _x( 'New Account &amp; Mailbox', 'Form Heading' ) ); ?>"<?php disabled( $batch_post, false ); ?>><?php foreach ( self::$batch_entries as $e ) { echo $e, \PHP_EOL; }; ?></textarea>
							</td>
							<td>
								<button type="submit" class="button-primary"<?php disabled( false ); ?>><?php _ex( 'Add All Entries', 'Button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<h2 class="new-forw def-forw">↪ <?php _ex( 'New Forwarder', 'Form Heading' ); ?></h2>
			<form id="new-forward" class="new-forw" name="new-forward" method="post" action="">
				<input type="hidden" name="action" value="new-forward">
				<textarea name="entries" cols="100" hidden="hidden"><?php foreach ( self::$batch_entries as $e ) { echo $e, \PHP_EOL; }; ?></textarea>
				<table class="form-table">
					<thead>
						<tr>
							<th scope="col" colspan="2">
								<label for="forward-user"><?php _ex( 'From Email', 'Form Field Label' ); ?> &nbsp; <?php \printf( '<small style="font-weight:normal;">' . _n( '(%1$d left)', '(%1$d left)', $flt ) . '</small>', $flt ); ?></label>
							</th>
							<!--th></th-->
							<th scope="col"> &nbsp;
								<label for="forward-to"><?php _ex( 'To Emails <small>(comma separated)</small>', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<input type="text" id="forward-user" name="user" class="user" autocomplete="on" required="required" pattern="<?php echo $pattern; ?>"/>@
							</td>
							<td>
								<select id="forward-domain" name="domain" required="required"<?=$readonly?>>
<?php
								foreach ( $mail_domains as $mail_domain ) { ?>
									<option value="<?php echo $mail_domain; ?>"><?php echo \idn_to_utf8( $mail_domain ); ?></option>
<?php
								} ?>
							</td>
							<td>
								<input type="text" id="forward-to" name="forward" autocomplete="on" required="required"/>
							</td>
							<td>
								<button type="submit" class="button-primary"<?php \disabled( $exd ); ?>><?php _ex( 'Add Forwarder', 'Button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
<?php			}
				$def_msg = $domain ? \trim( \str_replace( ':fail:','', UAPI::default_address( $domain ) ), ' "' ) : '';
				if ( $edit !== 'responder' ) { ?>
			<form id="default-forward" class="def-forw" name="default-forward" method="post" action="">
				<input type="hidden" name="action" value="default-forward">
				<table class="form-table">
					<thead>
						<tr>
							<th></th>
							<th scope="col">
								<label for="default-forward-domain"><?php _ex( 'From Domain', 'Form Field Label' ); ?></label>
							</th>
							<th scope="col">
								<label for="forward"><?php _ex( 'To Email', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="default">*@</td>
							<td>
								<select id="default-forward-domain" name="domain" required="required"<?=$readonly?>>
<?php
								foreach ( $mail_domains as $mail_domain ) { ?>
									<option value="<?php echo $mail_domain; ?>"><?php echo \idn_to_utf8( $mail_domain ); ?></option>
<?php
								} ?>
								</select>
							</td>
							<td>
								<input type="email" id="forward" name="forward" autocomplete="on" required="required" value="<?php echo \is_email( $def_msg ) ? $def_msg : ''; ?>"/>
							</td>
							<td>
								<button type="submit" class="button-primary"><?php _ex( 'Change Default to Forwarder', 'Button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
<?php			}

				if ( ! $edit ) { ?>
			<p class="new-forw def-forw"><?php _e( '&mdash; OR &mdash;' ); ?></p>
			<h2 class="new-fail def-fail">🚫 <?php _ex( 'New Failure', 'Form Heading' ); ?></h2>
			<form id="new-failure" class="new-fail" method="post" action="">
				<input type="hidden" name="action" value="new-failure">
				<table class="form-table">
					<thead>
						<tr>
							<th scope="col">
								<label for="fail-user"><?php _x( 'For Email', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
							<th scope="col">
								<label for="fail-msg"><?php _ex( 'Failure Message', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<input type="text" id="fail-user" name="user" autocomplete="on" required="required" pattern="<?php echo $pattern; ?>"/>@
							</td>
							<td>
								<select id="fail-domain" name="domain" required="required"<?=$readonly?>>
<?php
								foreach ( $mail_domains as $mail_domain ) { ?>
									<option value="<?php echo $mail_domain; ?>"><?php echo \idn_to_utf8( $mail_domain ); ?></option>
<?php
								} ?>
								</select>
							</td>
							<td>
								<input type="text" id="fail-msg" name="msg" autocomplete="off" placeholder="<?php echo $in_user; ?>" list="msg-list"/>
							</td>
							<td>
								<button type="submit" class="button-primary" name="type" value="email"><?php _ex( 'Add Failure', 'Button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
<?php			}
				if ( $edit !== 'responder' ) { ?>
			<form id="default-fail" class="def-fail" name="default-fail" method="post" action="">
				<input type="hidden" name="action" value="default-fail">
				<table class="form-table">
					<thead>
						<tr>
							<th></th>
							<th scope="col">
								<label for="default-fail-domain"><?php _ex( 'For Domain', 'Form Field Label' ); ?></label>
							</th>
							<th scope="col">
								<label for="default-fail-msg"><?php _ex( 'Failure Message', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>*@</td>
							<td>
								<select id="default-fail-domain" name="domain" required="required"<?=$readonly?>>
<?php
								foreach ( $mail_domains as $mail_domain ) { ?>
									<option value="<?php echo $mail_domain; ?>"><?php echo \idn_to_utf8( $mail_domain ); ?></option>
<?php
								} ?>
								</select>
							</td>
							<td>
								<input type="text" id="default-fail-msg" name="msg" autocomplete="off" placeholder="No Such User Here" value="<?php echo \is_email( $def_msg ) ? '' : $def_msg; ?>" list="msg-list"/>
								<datalist id="msg-list">
									<option><?php echo $in_user === $no_user ? $no_user : $in_user . '</option><option>' . $no_user; ?></option>
								</datalist>
							</td>
							<td>
								<button type="submit" class="button-primary"><?php echo $edit ? _x( 'Save Default Failure', 'Button' ) : _x( 'Change Default to Failure', 'Button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
<?php			}

				if ( ! $edit ) { ?>
			<p class="new-fail def-fail"><?php _e( '&mdash; OR &mdash;' ); ?></p>
			<h2 class="new-black def-black">⚫ <?php _ex( 'New Blackhole', 'Form Heading' ); ?></h2>
			<form id="new-black" class="new-black" method="post" action="">
				<input type="hidden" name="action" value="new-black">
				<table class="form-table">
					<thead>
						<tr>
							<th colspan="4" scope="col">
								<label for="black-user"><?php _ex( 'For Email', 'Form Field Label' ); ?></label>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<datalist id="black-list">
									<option>noreply</option>
								</datalist>
								<input type="text" id="black-user" list="black-list" name="user" placeholder="noreply" autocomplete="off" required="required" pattern="<?php echo $pattern; ?>"/>@
							</td>
							<td>
								<select id="black-domain" name="domain" required="required"<?=$readonly?>>
<?php
								foreach ( $mail_domains as $mail_domain ) { ?>
									<option value="<?php echo $mail_domain; ?>"><?php echo \idn_to_utf8( $mail_domain ); ?></option>
<?php
								} ?>
								</select>
							</td>
							<td></td>
							<td>
								<button type="submit" class="button-primary" name="type" value="email"><?php _ex( 'Add Blackhole', 'Button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
<?php			}

				if ( $edit !== 'responder' ) { ?>
			<form id="default-black" class="def-black" name="default-black" method="post" action="">
				<input type="hidden" name="action" value="default-black">
				<table class="form-table">
					<thead>
						<tr>
							<th></th>
							<th scope="col">
								<label for="default-black-domain"><?php _ex( 'For Domain', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>*@</td>
							<td>
								<select id="default-black-domain" name="domain" required="required"<?=$readonly?>>
<?php
								foreach ( $mail_domains as $mail_domain ) {
?>
									<option value="<?php echo $mail_domain; ?>"><?php echo \idn_to_utf8( $mail_domain ); ?></option>
<?php
								} ?>
								</select>
							</td>
							<td></td>
							<td>
								<button type="submit" class="button-primary"><?php _ex( 'Change Default to Blackhole', 'Button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
<?php			}

				if ( ! $edit ) { ?>
			<p class="new-black def-black"><?php _e( '&mdash; OR &mdash;' ); ?></p>
			<h2 class="new-acc"><big style="color: Green;">✉</big> <?php _ex( 'New Account &amp; Mailbox', 'Form Heading' ); ?></h2>
			<script>
				function validate() {
					let usr = document.forms['new-account']['user'].value;
					let dom = document.forms['new-account']['domain'].value;
					let eml = usr + '@' + dom;
					if ( [ '<?=\implode('\',\'',$used_emails)?>' ].includes( eml ) ) {
						alert( '<?php _ex( 'Account \' + eml + \' already exists', 'Alert Message' ); ?>' );
						return false;
					} else if ( [ '<?=\implode('\',\'',$listed_emails)?>' ].includes( eml ) ) {
						alert( '<?php _ex( 'Account \' + eml + \' already in batch queue', 'Alert Message' ); ?>' );
						return false;
					} else {
						return true;
					}
				}
			</script>
			<form id="new-account" class="new-acc" method="post" action="" onsubmit="return validate();">
				<input type="hidden" name="action" value="new-account">
				<textarea name="entries" cols="100" hidden="hidden"><?php foreach ( self::$batch_entries as $e ) { echo $e, \PHP_EOL; }; ?></textarea>
				<table class="form-table">
					<thead>
						<tr>
							<th scope="col" colspan="2">
								<label for="new-email"><?php _ex(' Email Address', 'Form Field Label' ); ?> &nbsp; <?php \printf( '<small style="font-weight:normal;">' . _n( '(%1$d left)',  '(%1$d left)', $alt ) . '</small>', $alt ); ?></label>
							</th>
							<!--th></th-->
							<th scope="col"> &nbsp;
								<label for="password"><?php _ex( 'Account Password', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<input type="text" id="new-email" name="user" autocomplete="off" required="required" pattern="<?php echo $pattern; ?>"/>@
							</td>
							<td>
								<select id="email-domain" name="domain" required="required"<?=$readonly?>>
<?php
								foreach ( $mail_domains as $mail_domain ) { ?>
									<option value="<?php echo $mail_domain; ?>"><?php echo \idn_to_utf8( $mail_domain ); ?></option>
<?php
								} ?>
								</select>
							</td>
							<td>
								<input type="password" id="password" name="password" minlength="8" autocomplete="new-password" placeholder="<?php _ex( 'Generate it', 'Password' ); ?>" title="<?php _ex( 'At least 8 characters, or empty to generate and send', 'Title text' ); ?>"/>
							</td>
							<td></td>
						</tr>
						<tr>
							<th colspan="2" scope="col" style="text-align: left;">
								<label for="name"><?php _ex( 'Send setup instructions to (name) (optional)', 'Form Field Label' ); ?>
							</th>
							<th scope="col">
								<label for="to"><?php _ex( 'To their current email address', 'Form Field Label' ); ?></label>
							</th>
							<th>
							</th>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left;">
								<input type="text" id="name" name="name" placeholder="<?php echo \wp_get_current_user()->display_name; ?>"/>
							</td>
							<td style="padding: 0 1ch;">
								<input type="email" id="to" name="to" placeholder="<?php _ex( 'Do not send', 'Send to email input placeholder' ); ?>"/>
							</td>
							<td>
								<button type="submit" class="button-primary"<?php \disabled( $exd ); ?>><?php _ex( 'Create New Account', 'Submit button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<p<?php if ( ! $edit ) echo ' class="new-resp"'; ?><?php _e( '&mdash; OR &mdash;' ); ?></p>
<?php			}
				$type = 'responder';

				if ( ! $edit || $edit === 'responder' ) { ?>
			<h2<?php if ( ! $edit ) echo ' class="new-resp"'; ?>>↩️ <?php if ( $edit ) _ex( 'Edit Autoresponder', 'Form Heading' ); else _ex( 'New Autoresponder', 'Form Heading' ); ?></h2>
			<form id="<?php echo $edit ? 'edit' : 'new'; ?>-<?php echo $type; ?>" class="new-resp" method="post" action="">
				<input type="hidden" name="action" value="<?php echo $edit ? 'edit' : 'new'; ?>-<?php echo $type; ?>"/>
				<table class="form-table">
					<thead>
						<tr>
							<th scope="col">
								<label for="<?php echo $type; ?>-user"><?php _ex( 'For Email', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
							<th colspan="2" scope="col">
								<label for="<?php echo $type; ?>-from"><?php _ex( 'Responder Name', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<input type="text" id="responder-user" name="user" autocomplete="on" required="required" value="<?php echo $responder->user; ?>" pattern="<?php echo $pattern; ?>"/>@
							</td>
							<td>
								<select id="<?php echo $type; ?>-domain" name="domain" required="required"<?=$readonly?>>
<?php
								foreach ( $mail_domains as $mail_domain ) { ?>
									<option value="<?php echo $mail_domain; ?>"><?php echo \idn_to_utf8( $mail_domain ); ?></option>
<?php
								} ?>
								</select>
							</td>
							<td colspan="2">
								<input type="text" id="<?php echo $type; ?>-from" name="from" list="from-list" autocomplete="off" placeholder="<?php _ex( 'Name', 'Form Field Placeholder'); ?>" required="required" value="<?php echo $responder->from; ?>" pattern="^[a-zA-Z]+(?:\s+[a-zA-Z]+)*$">
								<datalist id="from-list">
								<?php foreach ( \get_users( [
									'number'  => \max( \count( $to_emails ), 20 ),
									'orderby' => 'display_name',
								] ) as $list_user ) {
									if ( \in_array( \explode( '@', $list_user->user_email )[1], $mail_domains, true ) ) { ?>
									<option><?php echo $list_user->display_name; ?></option>
									<?php }
								}
								foreach ( $dest_emails as $dest_email ) { ?>
									<option><?php echo \mb_ucwords( \str_replace( '.', ' ', \explode( '@', $dest_email )[0] ) ); ?>
								<?php } ?>
								</datalist>
							</td>
							<td></td>
						</tr>
						<tr class="thead">
							<th colspan="3" scope="col" style="text-align: left;">
								<label for="<?php echo $type; ?>-subject"><?php _ex( 'Subject', 'Form Field Label' ); ?></label>
							</th>
							<th scope="col" title="<?php _ex( 'Interval before repeated to same recipient.', 'Form Field Tooltip' ); ?>">
								<label for="<?php echo $type; ?>-interval"><?php _ex( 'Interval', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
						<tr>
							<td colspan="3" style="text-align: left;">
								<input id="<?php echo $type; ?>-subject" type="text" name="subject" value="<?php echo $responder->subject; ?>" style="width: 50ch;" placeholder="Re: %subject%" autocomplete="off" list="subject-list" required="required"/>
								<datalist id="subject-list">
									<option>Re: %subject%</option>
								</datalist>
							</td>
							<td>
								<input id="<?php echo $type; ?>-interval" type="number" name="interval" value="<?php echo $responder->interval; ?>" style="width: 8ch;" autocomplete="off" required="required" title="<?php _ex( 'Interval before repeated to same recipient.', 'Form Field Tooltip' ); ?>"/> <?php _ex( 'hours', 'Input Field Unit' ); ?>
							</td>
							<td></td>
						</tr>
						<tr class="thead">
							<th colspan="2" scope="col" style="text-align: left;">
								<label for="<?php echo $type; ?>-start"><?php _ex( 'Start time', 'Form Field Label' ); ?></label>
							</th>
							<th colspan="2" scope="col">
								<label for="<?php echo $type; ?>-stop"><?php _ex( 'End time', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left;">
								<input id="<?php echo $type; ?>-start-date" type="date" name="start-date" value="<?php echo $responder->start_date; ?>" style="width: 16ch;" autocomplete="off" required="required"/>
								<input type="time" name="start-time" value="<?php echo $responder->start_time; ?>" style="width: 12ch;"/>
							</td>
							<td colspan="2">
								<input id="<?php echo $type; ?>-start-date" type="date" name="stop-date" value="<?php echo $responder->stop_date; ?>" style="width: 15ch;" autocomplete="off" required="required"/>
								<input type="time" name="stop-time" value="<?php echo $responder->stop_time; ?>" style="width: 11ch;"/>
							</td>
							<td></td>
						</tr>
						<tr class="thead">
							<th colspan="4" scope="col" style="text-align: left;">
								<label for="<?php echo $type; ?>-body"><?php _ex( 'Email Message', 'Form Field Label' ); ?></label>
							</th>
							<th></th>
						</tr>
						<tr>
							<td colspan="4" style="text-align: left;">
								<textarea id="<?php echo $type; ?>-body" name="body" rows="6" required="required" style="width: calc(100% - 2ch);"><?php echo $responder->body; ?></textarea>
							</td>
							<td style="vertical-align: bottom;">
								<button type="submit" class="button-primary"><?php if ( $edit ) _ex( 'Save Autoresponder', 'Button' ); else _ex( 'Create New Autoresponder', 'Button' ); ?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
<?php			} ?>
		</div>
<?php
				\add_action( 'admin_notices', static function(): void {
					$action = \sanitize_text_field( $_GET[ 'action' ] ?? '' );

					if ( $action ) { ?>
						<div class="notice notice-success is-dismissible">
							<p><?php echo \esc_attr( \ucfirst( $action ) ); ?> created!</p>
						</div>
<?php
					}
				} );
			}, 1 );
		} );
	}

	private   static function save_options(): void {

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && \sanitize_text_field( $_GET['page'] ?? '' ) === self::$page->NewEmail ) {
			$action  = \sanitize_key( $_POST['action' ] ?? '' );

			if ( $action === 'user-options' ) {

				if ( \wp_verify_nonce( $_POST['screenoptionnonce'], 'screen-options-nonce' ) ) {
					$user_id = \get_current_user_id();

					foreach ( self::$screen->get_options() as $option ) {
						$value = \boolval( $_POST[ $option['option'] ] ?? '' );

						if ( $value === \boolval( $option['default'] ?? '' ) ) {
							\delete_user_option( $user_id, $option['option'] );
						} else {
							\update_user_option( $user_id, $option['option'], $value );
						}
					}
				} else {
					\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
				}
			}
		}
	}

	private   static function save(): void {

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && \sanitize_text_field( $_GET['page'] ?? '' ) === self::$page->NewEmail ) {
			$action  = \sanitize_key( $_POST['action' ] ?? '' );
			$user    = \sanitize_text_field( $_POST['user'   ] ?? '' );
			$domain  = \sanitize_text_field( $_POST['domain' ] ?? '' );

			if ( $action && $domain && self::$domain_only && $domain !== self::$site_domain ) {
				\wp_die( \wpautop( \sprintf( _x( 'Invalid domain %1$s.', 'Die message' ), $domain ) ) );
			}
			$email   = $user && $domain ? \sanitize_email( $user . '@' . $domain ) : '';

			if ( self::$batch_mode ) {

				if ( $action === 'batch-entry' ) {
//					var_dump( self::parse_entries() );
					$success = [ 'forward' => 0, 'account' => 0 ];
					$entries = self::parse_entries();

					foreach ( $entries as $entry ) {

						if ( $entry->valid ) {

							if ( $entry->type === 'forward' ) {

								if ( UAPI::add_forwarder( $entry->email, $entry->dest, self::$batch_mode )->has_errors() ) {
									$entry->error = $result->get_error_message();
								} else {
									$success[ $entry->type ]++;
									$entry->type = 'ok';
								}
							} elseif ( $entry->type === 'account' ) {
								$current_user = \wp_get_current_user();

								if ( empty( $entry->pass ) ) {
									$entry->pass = \wp_generate_password( 12, false );
									$entry->name = $entry->name ?: $current_user->display_name;
									$entry->to   = $entry->to   ?: $current_user->user_email;
								}

								if ( $entry->to ) {
									$result = UAPI::add_account( $entry->email, $entry->pass, self::$batch_mode );

									if ( $result->has_errors() ) {
										$entry->error = $result->get_error_message();
									} else {
										$s_result = UAPI::send_settings( $entry->email, $entry->to );
										error_log( $s_result );
										$entry->to = $entry->name ? $entry->name . ' <' . $entry->to . '>' : $entry->to;
										$domain = \explode( '@', $entry->email )[1];
										$e_result = \wp_mail(
											$entry->to,
											'[' . \get_bloginfo() . '] ' . _x( 'New Email Account created', 'Email subject' ),
											__( 'Email Account:' ) . ' ' . $entry->email . \PHP_EOL .
											__( 'Email Account Password:' ) . ' ' . $entry>pass . \PHP_EOL . \PHP_EOL .
												__( 'Note 1: Not for login to the site!' ) . \PHP_EOL .
												\sprintf(
													_x( 'Note 2: You should also have received your client configuration settings for [%1$s].',
														'%1$s = domain' ),
													$domain
												) . \PHP_EOL .
												\sprintf(
													_x( 'Note 3: You should change this password from your webmail at %1$s', '%1$s = Webmail URL' ),
													self::$is_proisp ? 'https://webmail.proisp.no/' : 'https://mail.' . $domain . ':2096/',
												) . \PHP_EOL .
											self::guides(),
											[ 'From: ' . $current_user->display_name . ' <' . $current_user->user_email . '>' ]
										);
										error_log( $e_result );
										$success[ $entry->type ]++;
										self::$success[ $entry->type ] = $success[ $entry->type ];
										$entry->type = 'ok';
									}
								} else {
									$result = UAPI::add_account( $entry->email, $entry->pass, self::$batch_mode );

									if ( $result->has_errors() ) {
										$entry->error = $result->get_error_message();
									} else {
										$success[ $entry->type ]++;
										self::$success[ $entry->type ] = $success[ $entry->type ];
										$entry->type = 'ok';
									}
								}
							}
						}
					}

					foreach ( $entries as $entry ) {
						self::$batch_entries = [];

						if ( $entry->type === 'forward' ) {
							self::$batch_entries[] = \sprintf( '%03d', $entry->line ) . ' ' . $entry->type . ': ' . $entry->dest . ( $entry->error ? ' [' . $entry->error . ']' : '' );
						} elseif ( $entry->type === 'account' ) {
							self::$batch_entries[] = \sprintf( '%03d', $entry->line ) . ' ' . $entry->type . ': ' . ( $entry->pass ? \str_replace( '@', ':' . $entry->pass . '@', $entry->email ) : $entry->email ) . ( $entry->name ? $entry->name . ' (' . $entry->to . ')' : $entry->to ) . ( $entry->error ? ' [' . $entry->error . ']' : '' );
						}
					}

					\add_action( 'admin_notices', static function() use ( $success ): void {
						\printf(
							'<div class="notice notice-' . ( $success['forward'] || $success['account']  ? 'success' : 'error' ) . '"><p>' .
								_n( '%1$d forwarder created', '%1$d forwarders created', $success['forward'] ) . ', ' .
								_n( '%2$d account created', '%2$d accounts created', $success['account'] ) .
							'</p></div>',
							$success['forward'],
							$success['account'],
						);
					} );
				} else {
					self::batch_entry( $action, $email );
				}
			} else {

				if ( $action === 'new-forward' ) {
					$forward = \sanitize_text_field( $_POST['forward'] ?? '' );
					self::notice( $action, UAPI::add_forwarder( $email, $forward ), $domain, $email );
				} elseif ( $action === 'default-forward' ) {
					$forward = \sanitize_email( $_POST['forward'] ?? '' );
					self::notice( $action, UAPI::set_default_email( $domain, $forward ), $domain, $forward );
				} elseif ( $action === 'new-failure' ) {
					$message = \sanitize_text_field( $_POST['msg'] ?? '' );
					self::notice( $action, UAPI::add_fail( $email, $message ), $domain, $email );
				} elseif ( $action === 'default-fail' ) {
					$message = \sanitize_text_field( $_POST['msg'] ?? '' );
					self::notice( $action, UAPI::set_default_fail( $domain, $message ), $domain );
				} elseif ( $action === 'new-black' ) {
					self::notice( $action, UAPI::add_blackhole( $email ), $domain, $email );
				} elseif ( $action === 'default-black' ) {
					self::notice( $action, UAPI::set_default_blackhole( $domain ), $domain );
				} elseif ( $action === 'new-account' ) {
					$current_user = \wp_get_current_user();
					$password = \sanitize_text_field( $_POST['password'] ?? '' );
					$name     = \mb_ucwords( \sanitize_text_field( $_POST['name'] ?? '' ) );
					$to       = \sanitize_email( $_POST['to'] ?? '' );

					if ( empty( $password ) ) {
						$password = \wp_generate_password( 12, false );
						$name = $name ?: $current_user->display_name;
						$to   = $to   ?: ( \apply_filters( self::pf . 'send_emails_on_generated_password', true, __METHOD__ ) ? $current_user->user_email : '' );
					}

					if ( $to ) {
						$result = UAPI::add_account( $email, $password );

						if ( $result->has_errors() ) {
							self::notice( $action, $result, $domain, $email );
						} else {
							$s_result = UAPI::send_settings( $email, $to );
							error_log( $s_result );
							$to = $name ? $name . ' <' . $to . '>' : $to;
							$domain = \explode( '@', $email )[1];
							$e_result = \wp_mail(
								$to,
								'[' . \get_bloginfo() . '] ' . _x( 'New Email Account created', 'Email subject' ),
								__( 'Email Account:' ) . ' ' . $email . \PHP_EOL .
								__( 'Email Account Password:' ) . ' ' . $password . \PHP_EOL . \PHP_EOL .
									__( 'Note 1: Not for login to the site!' ) . \PHP_EOL .
									\sprintf(
										_x( 'Note 2: You should also have received your client configuration settings for [%1$s].',
											'%1$s = domain' ),
										$domain
									) . \PHP_EOL .
									\sprintf(
										_x( 'Note 3: You should change this password from your webmail at %1$s', '%1$s = Webmail URL' ),
										self::$is_proisp ? 'https://webmail.proisp.no/' : 'https://mail.' . $domain . ':2096/',
									) . \PHP_EOL .
								self::guides(),
								[ 'From: ' . $current_user->display_name . ' <' . $current_user->user_email . '>' ]
							);
							error_log( $e_result );
							self::notice( $action, $result, $domain, $email );
						}
					} else {
						self::notice( $action, UAPI::add_account( $email, $password ), $domain, $email );
					}
				} elseif ( $action === 'new-responder' || $action === 'edit-responder' ) {
					$from      = \sanitize_text_field( $_POST['from' ] ?? '' );
					$subject   = \sanitize_text_field( $_POST['subject' ] ?? '' );
					$body      = \sanitize_textarea_field( $_POST['body' ] ?? '' );
					$body      = \str_replace( '%responder%', $from, $body );
					$start     = \mysql2date( 'U', $_POST['start-date'] . ' ' . $_POST['start-time'] ) - self::$tz_offset;
					$stop      = \mysql2date( 'U', $_POST['stop-date' ] . ' ' . $_POST['stop-time' ] ) - self::$tz_offset;
					$interval  = \intval( $_POST['interval'] );
					self::notice( $action, UAPI::add_responder( $email, $from, $subject, $body, $start, $stop, $interval ), $domain, $email );
				}
			}
		}
	}

	private static function batch_entry( string $action, string $email ): void {
		$option_name  = self::pf . 'usage';
		delete_option( $option_name );

		$action = \substr( $action, 4 );
		$batch  = \explode( \PHP_EOL, \sanitize_textarea_field( $_POST['entries'] ) );
		$batch  = \array_filter( $batch, static fn( string $val ): bool => \boolval( \strlen( \trim( $val ) ) ) );
		$line   = \sprintf( '%03d', \count( $batch ) + 1 );

		if ( $action === 'forward' ) {
			$forward = \sanitize_text_field( $_POST['forward'] ?? '' );
			$batch[] = $line . ' ' . $action . ': ' . $email . ' ' . $forward;
		} elseif ( $action === 'account' ) {
			$password = \sanitize_text_field( $_POST['password'] ?? '' );
			$name     = \mb_ucwords( \sanitize_text_field( $_POST['name'] ?? '' ) );
			$to       = \sanitize_email( $_POST['to'] ?? '' );

			if ( $password ) {
				$email = \str_replace( '@', ':' . $password . '@', $email );
			}

			if ( $to && $name ) {
				$to = $name . ' (' . $to . ')';
			}
			$batch[] = $line . ' ' . $action . ': ' . $email . ' ' . $to;
		}
		self::$batch_entries = $batch;
	}

	private   static function notice( string $action, \WP_Error $result, string $domain = '', string $email = '' ): void {

		if ( $result->has_errors() && ! \in_array( $result->get_error_code(), [ 402, 429 ], true ) ) {
			\add_action( 'admin_notices', static function() use ( $result ): void {
				\printf( '<div class="notice notice-error"><p>%1$s</p></div>', \esc_html( $result->get_error_message() ) );
			} );
		} else {
			\add_action( 'current_screen', static function() use ( $result, $action, $domain, $email ): void {
				$args = [ 'source' => self::$page->NewEmail, 'action' => $action, 'domain' => $domain, 'email' => $email ];

				if ( $result->has_errors() ) {
					$args['r'] = [ 'new-forward' => 'add_forwarder', 'new-account' => 'add_account' ][ $action ];
				}
				\wp_safe_redirect( \add_query_arg( $args, \menu_page_url( self::$page->AccountsPage ) ) );
				exit;
			}, 999 );
		}
	}

	private   static function parse_entries(): array {
		$accounts = [];
		$batch = \explode( \PHP_EOL, \sanitize_textarea_field( $_POST['entries'] ) );

		foreach ( $batch as $entry ) {
			$account = new \stdClass;
			$account->valid = false;
			$efra = \explode( ' [', \trim( $entry ), 2 );
			$entr = $efra[0];
			$account->error = \trim( $efra[1] ?? '', ' ]' );
			$frac = \explode( ': ', \trim( $entr ), 2 );
			$ltyp = \explode( ' ', $frac[0] );
			$account->type = $ltyp[1] ?? $ltyp[0];
			$account->line = \is_numeric( $ltyp[0] ) ? \intval( $ltyp[0] ) : 0;
			$rest = \trim( $frac[1] ?? '' );

			if ( $rest ) {

				if ( $account->type === 'forward' ) {
					$frac = \explode( ' ', $rest, 2 );
					$account->email = $frac[0];
					$rest = \trim( $frac[1] ?? '' );

					if ( $rest ) {
						$account->dest  = $rest;
						$account->valid = \boolval( \is_email( $account->email ) );
					}
				} elseif ( $account->type === 'account' ) {
					$frac = \explode( ' ', $rest, 2 );
					$emlp = $frac[0];
					$usrf = \explode( '@', $emlp, 2 );
					$usep = $usrf[0];
					$dom  = $usrf[1] ?? '';
					$pwdf = \explode( ':', $usep, 2 );
					$user = $pwdf[0];
					$account->pass  = $pwdf[1] ?? '';
					$account->email = $user . '@' . $dom;
					$account->valid = true;
					$rest = \trim( $frac[1] ?? '' );

					if ( $rest ) {

						if ( \str_contains( $rest, ' (' ) && \str_ends_with( $rest, ')' ) ) {
							$tofr = \explode( ' (', $rest, 2 );
							$account->name = $tofr[0];
							$account->to   = \rtrim( $tofr[1] ?? '', ') ' );
						} else {
							$account->name = '';
							$account->to   = $rest;
						}
					} else {
						$account->name = '';
						$account->to   = '';
					}
				}
			}
			$accounts[] = $account;
		}
		return $accounts;
	}

	private   static function guides() {
		$guides['en_US'] = [
			__( 'NEW: Server information for email setup', 'Change account guide' ) => 'https://support.proisp.com/hc/en/articles/10203805481617-Serverinformasjon-for-oppsett-av-e-post-',
			__( 'IMAP vs POP3',    'New account guide' ) => 'https://www.proisp.eu/faq/difference-between-imap-and-pop/',
			__( 'GMail',           'New account guide' ) => 'https://www.proisp.eu/faq/set-up-email-for-reading-sending-gmail/',
			__( 'Android',         'New account guide' ) => 'https://www.proisp.eu/guides/setup-email-account-android/',
			__( 'Windows',         'New account guide' ) => 'https://www.proisp.eu/guides/emailaccount-windows-10/',
			__( 'iPhone',          'New account guide' ) => 'https://www.proisp.eu/guides/setup-sent-folder-email-iphone-ipad/',
			__( 'Office 365',      'New account guide' ) => 'https://www.proisp.eu/guides/setup-email-account-outlook-office-365/',
			__( 'Android Outlook', 'New account guide' ) => 'https://www.proisp.eu/guides/setup-email-account-outlook-android/',
//			__( 'Android GMail',   'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epost-gmail-app-android/',
			__( 'Outlook 2016',    'New account guide' ) => 'https://www.proisp.eu/guides/setup-email-account-outlook-2013-2016/',
			__( 'Apple',           'New account guide' ) => 'https://www.proisp.eu/guides/create-email-account-apple-mail/',
			__( 'Thunderbird',     'New account guide' ) => 'https://www.proisp.eu/guides/setup-email-account-mozilla-thunderbird/',
			__( 'Webmail',         'New account guide' ) => 'https://www.proisp.eu/guides/using-webmail-cpanel-web-hosting/',
		];
		$guides['nb_NO'] = [
			__( 'NEW: Server information for email setup', 'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/10203805481617-Serverinformasjon-for-oppsett-av-e-post-',
			__( 'IMAP vs POP3',          'New account guide' ) => 'https://www.proisp.no/oss/hva-er-forskjell-mellom-imap-og-pop/',
			__( 'GMail',                 'New account guide' ) => 'https://www.proisp.no/oss/sette-opp-epost-for-lesing-og-sending-hos-gmail/',
			__( 'Android',               'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epostkonto-android/',
			__( 'Change Android',        'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/10516458358673-Endre-e-postinnstillinger-i-Android',
			__( 'Windows',               'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epostkonto-windows-8-mail/',
			__( 'Change Windows Mail',   'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/10492750641553-Endre-e-postinnstillinger-i-Windows-Mail',
			__( 'iPhone',                'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epostkonto-iphone-ipad/',
			__( 'Change iPhone or iPad', 'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/10492291010833-Endre-e-postinnstillinger-i-iPhone-og-iPad-iOS-',
			__( 'iPhone GMail',          'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epost-gmail-app-iphone/',
			__( 'Office 365',            'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epostkonto-outlook-office-365/',
			__( 'Change Outlook 365',    'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/12210904639761-Endre-e-postinnstillinger-i-Outlook-365',
			__( 'Android Outlook',       'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epostkonto-outlook-android/',
			__( 'Change Outlook Mobile', 'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/12771489420305-Endre-e-postinnstillinger-i-Outlook-for-mobil',
			__( 'Android GMail',         'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epost-gmail-app-android/',
			__( 'Outlook 2016',          'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epostkonto-outlook-2013-2016/',
			__( 'Change Outlook',        'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/10848456186769-Endre-e-postinnstillinger-i-Outlook-',
			__( 'Apple',                 'New account guide' ) => 'https://www.proisp.no/guider/opprettelse-av-epostkonto-apple-mail/',
			__( 'Change Apple Mail',     'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/12604137875729--Endre-e-postinnstillinger-i-Apple-Mail',
			__( 'Thunderbird',           'New account guide' ) => 'https://www.proisp.no/guider/oppsett-epostkonto-mozilla-thunderbird/',
			__( 'Change Thunderbird',    'Change account guide' ) => 'https://support.proisp.com/hc/nb/articles/10492807781905-Endre-e-postinnstillinger-i-Thunderbird',
			__( 'Webmail',               'New account guide' ) => 'https://www.proisp.no/guider/bruk-webmail/',
		];
		$locale = \get_user_locale( \get_current_user_id() );
		$locale = \str_ends_with( $locale, '_NO' ) ? 'nb_NO' : 'en_US';
		$text = \PHP_EOL . __( 'Guides:' );

		foreach( $guides[ $locale ] as $label => $link ) {
			$text .= \PHP_EOL . $label . ': ' . $link;
		}
		return $text;
	}
}
