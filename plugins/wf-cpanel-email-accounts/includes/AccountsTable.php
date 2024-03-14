<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( 'WP' ) || exit;

class AccountsTable extends \WP_List_Table {

	private static array $type_names;

	/**
	 * Prepare the items for the table to process
	 */
	public function prepare_items(): void {

		self::$type_names = [
			'_account'   => _x( 'Main Account', 'Email entry type' ),
			 'account'   => _x(      'Account', 'Email entry type' ),
			 'forwarder' => _x( 'Forwarder',    'Email entry type' ),
			 'default'   => _x( 'Default',      'Email entry type' ),
			 'responder' => _x( 'Responder',    'Email entry type' ),
		];


		$this->process_bulk_action();

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data     = $this->table_data();
		$cur_page = $this->get_pagenum();
		$per_page = 50;
		$args     = $this->_args;

		if ( \array_key_exists( 'email', $this->_args ) ) {
			$data = \array_filter( $data, static function( array $row ) use ( $args ): bool {
				return $row['email' ] === $args['email'];
			} );
		} elseif ( \array_key_exists( 'domain', $this->_args ) ) {
			$data = \array_filter( $data, static function( array $row ) use( $args ): bool {
				return $row['domain'] === $args['domain'];
			} );
		}

		\usort( $data, [ $this, 'sort_data' ] );
		$total_items  = \count( $data );

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page
		] );

		$data = \array_slice( $data, ( $cur_page - 1 ) * $per_page, $per_page );

		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$this->items = $data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 */
	public function get_columns(): array {
		$columns = [
			'cb'           => '<input type="checkbox" />',
			'domain'       => _x( 'Domain',      'Column Header' ),
			'email'	       => _x( 'Email',       'Column Header' ),
			'type'         => _x( 'Type',        'Column Header' ),
			'diskused'     => _x( 'Used',        'Column Header' ),
			'diskused_num' => _x( 'Used',        'Column Header' ) . ' N',
			'diskquota'    => _x( 'Quota',       'Column Header' ),
			'dest'         => _x( 'Destination', 'Column Header' ),
		];

		return $columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return array
	 */
	public function get_hidden_columns(): array {
		return Main::$is_debug ? [] : [ 'diskused_num' ];
	}

	/**
	 * Define the sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns(): array {
		return [
			'domain'    => [ 'domain',        false ],
			'email'     => [ 'email',         false ],
			'type'      => [ 'type',          false ],
			'diskused'  => [ 'diskused_num',  false ],
		];
	}

	/**
	 * Get the table data
	 */
	private function table_data(): array {
		$current_user = \wp_get_current_user();
		$cap = \apply_filters( Main::pf . 'capability', 'manage_options', 'Webmail button' );
		$data = [];
		$account = UAPI::main_email_account();
		$access  = $account->email === $current_user->user_email || \current_user_can( $cap );
		$data[] = [
			'domain'        => $account->domain,
			'email'         => $account->email,
			'type'          => '_account',
			'diskused'      => \size_format( $account->_diskused ),
			'diskused_num'  => (string) $account->_diskused,
			'diskquota'     => $account->diskquota,
			'dest'          => $access ? '<button form="' . \sanitize_key( $account->email ) . '" type="submit" formtarget="' . \sanitize_key( $account->email ) . '" class="button button-secondary webmail" title="' . _x( 'If disabled, please refresh this page.', 'Webmail button tooltip.' ) . '">' . __( 'cPanel® Webmail', 'Button Label' ) . '</button> <small style="vertical-align: bottom;">' . __( '(new tab)' ) . '</small>' : '',
		];

		foreach ( (array) UAPI::email_forwarders() as $account ) {
			$data[] = [
				'domain'       => \explode( '@', $account->dest )[1],
				'email'        => $account->html_dest,
				'type'         => 'forwarder',
				'diskused'     => '&mdash;',
				'diskused_num' => 0,
				'diskquota'    => '&mdash;',
				'dest'         => \trim( $account->html_forward, '"' ),
			];
		}

		foreach ( (array) UAPI::email_accounts( true ) as $account ) {
			$access  = $account->email === $current_user->user_email || ( \current_user_can( $cap ) && ! \email_exists( $account->email ) );
			$data[] = [
				'domain'       => $account->domain,
				'email'        => $account->email,
				'type'         => 'account',
				'diskused'     => \size_format( $account->_diskused ?: 0 ),
				'diskused_num' => $account->_diskused,
				'diskquota'    => $account->_diskquota ? \size_format( $account->_diskquota ) : $account->diskquota,
				'dest'         => $access ? '<button form="' . \sanitize_key( $account->email ) . '" type="submit" formtarget="' . \sanitize_key( $account->email ) . '" class="button button-secondary webmail" title="' . _x( 'If disabled, please refresh this page.', 'Webmail button tooltip.' ) . '">cPanel® Webmail</button> <small style="vertical-align: bottom;">' . __( '(new tab)' ) . '</small>' : '',
			];
		}

		foreach ( (array) UAPI::mail_domains() as $domain ) {
			$responders = (array) UAPI::email_responders( $domain );

			foreach ( $responders as $responder ) {
				$data[] = [
					'domain'       => $domain,
					'email'        => $responder->email,
					'type'         => 'responder',
					'diskused'     => '&mdash;',
					'diskused_num' => 0,
					'diskquota'    => '&mdash;',
					'dest'         => \esc_html( '↩️ ' . $responder->subject ),
				];
			}
			$data[] = [
				'domain'       => $domain,
				'email'        => '*@' . $domain,
				'type'         => 'default',
				'diskused'     => '&mdash;',
				'diskused_num' => 0,
				'diskquota'    => '&mdash;',
				'dest'         => \esc_html( \trim( UAPI::default_address( $domain ), ' "' ) ),
			];
		}

		if ( $_REQUEST['s'] ?? '' ) {
			$search = \mb_strtolower( \sanitize_text_field( $_REQUEST['s'] ) );
			$data   = \array_filter( $data, static fn( array $value ): bool => \str_contains( \mb_strtolower( $value['email'] ), $search ) );
		}
		return $data;
	}

	/**
	 * Render the bulk edit checkbox
	 */
	public function column_cb( $item ) {
		return $item['type'] !== '_account' &&
			( $item['type'] !== 'default' || ! \str_contains( $item[ 'dest' ], ':fail:' ) ) ?
			\sprintf (
				'<input type="checkbox" name="type[%1$s]" value="%2$s" /><input type="hidden" name="dest[%1$s]" value="%3$s" />',
				\esc_attr( $item['email'] ),
				\esc_attr( $item['type' ] ),
				$item['type'] === 'account' ? '' : \esc_attr( $item['dest' ] ),
			) :
			''
		;
	}

	public function column_email( array $item ): string {
		$page = \sanitize_text_field( $_REQUEST['page'] );

		if ( $item['type'] === '_account' ) {
			$actions = [
				'postboxes' => '<a href="' . \add_query_arg( [
					'page'     => Main::$pf . 'boxes',
					'email'    => $item['email']
				], \admin_url( 'admin.php' ) ) . '">' . _x( 'Show mailboxes','Link Table Row Action' ),
				'row-send'   => '<a href="' . \add_query_arg( [
					'page'     => \esc_attr( $page ),
					'type'     => $item['type'],
					'email'    => $item['email'],
					'action'   => 'row-send',
					'_wpnonce' => \wp_create_nonce( 'row-send' ),
				], \admin_url( 'admin.php' ) ) . '">' . _x( 'Send instructions', 'List Table Row Action' ),
			];
		} elseif ( $item['type'] === 'forwarder' ) {
			$actions = [
				'row-delete' => '<a href="' . \add_query_arg( [
					'page'     => \esc_attr( $page ),
					'type'     => $item['type'],
					'email'    => $item['email'],
					'dest'     => $item['dest'],
					'action'   => 'row-delete',
					'_wpnonce' => \wp_create_nonce( 'row-delete' )
				], \admin_url( 'admin.php' ) ) . '">' . __( 'Delete' ),
			];
		} elseif ( $item['type'] === 'default' ) {
			$actions = [
				'row-edit' => '<a href="' . \add_query_arg( [
					'page'     => Main::$pf . 'new-email',
					'type'     => $item['type'],
					'domain'   => $item['domain'],
					'action'   => 'row-edit',
					'_wpnonce' => \wp_create_nonce( 'row-edit' )
				], \admin_url( 'admin.php' ) ) . '#edit-default">' . __( 'Edit' ),
			];
			$actions = \array_merge( $actions, ( $actions = \str_contains( $item[ 'dest' ], ':fail:' ) ? [] : [
				'row-delete' => '<a href="' . \add_query_arg( [
					'page'     => \esc_attr( $page ),
					'type'     => $item['type'],
					'domain'   => $item['domain'],
					'action'   => 'row-delete',
					'_wpnonce' => \wp_create_nonce( 'row-delete' )
				], \admin_url( 'admin.php' ) ) . '">' . __( 'Remove' ),
			] ) );
		} elseif ( $item['type'] === 'account' ) {
			$actions = [
				'postboxes' => '<a href="' . \add_query_arg( [
					'page'     => Main::$pf . 'boxes',
					'email'    => $item['email']
				], \admin_url( 'admin.php' ) ) . '">' . _x( 'Show mailboxes','Link Table Row Action' ),
				'row-password' => '<a href="' . \add_query_arg( [
					'page'     => \esc_attr( $page ),
					'type'     => $item['type'],
					'email'    => $item['email'],
					'action'   => 'row-password',
					'_wpnonce' => \wp_create_nonce( 'row-password' ),
				], \admin_url( 'admin.php' ) ) . '">' . _x( 'Change Password', 'List Table Row Action' ),
				'row-quota' => '<a href="' . \add_query_arg( [
					'page'     => \esc_attr( $page ),
					'type'     => $item['type'],
					'email'    => $item['email'],
					'action'   => 'row-quota',
					'_wpnonce' => \wp_create_nonce( 'row-quota' ),
				], \admin_url( 'admin.php' ) ) . '">' . _x( 'Change Quota', 'List Table Row Action' ),
				'row-send'     => '<a href="' . \add_query_arg( [
					'page'     => \esc_attr( $page ),
					'type'     => $item['type'],
					'email'    => $item['email'],
					'action'   => 'row-send',
					'_wpnonce' => \wp_create_nonce( 'row-send' ),
				], \admin_url( 'admin.php' ) ) . '">' . _x( 'Send instructions', 'List Table Row Action' ),
				'row-delete'   => '<a href="' . \add_query_arg( [
					'page'     => \esc_attr( $page ),
					'email'    => $item['email'],
					'type'     => $item['type'],
					'action'   => 'row-delete',
					'_wpnonce' => \wp_create_nonce( 'row-delete' )
				], \admin_url( 'admin.php' ) ) . '">' . __( 'Delete' ),
			];
		} elseif ( $item['type'] === 'responder' ) {
			$actions = [
				'row-edit'   => '<a href="' . \add_query_arg( [
					'page'     => Main::$pf . 'new-email',
					'email'    => $item['email'],
					'type'     => $item['type'],
					'action'   => 'row-edit',
				], \admin_url( 'admin.php' ) ) . '#edit-responder">' . __( 'Edit' ),
				'row-delete'   => '<a href="' . \add_query_arg( [
					'page'     => \esc_attr( $page ),
					'email'    => $item['email'],
					'type'     => $item['type'],
					'action'   => 'row-delete',
					'_wpnonce' => \wp_create_nonce( 'row-delete' )
				], \admin_url( 'admin.php' ) ) . '">' . __( 'Delete' ),
			];
		}
		$format = $item['type'] === 'default' ? '%1$s<br />%4$s' : '<a href="mailto:%2$s" title="%3$s %1$s.">%1$s</a><br />%4$s';
		$email  = Main::email_to_utf8( $item['email'] );
		return Main::email_mx_self( $item['domain'] ) ?
			\sprintf( $format, $email, $item['email'], _x( 'Send email to', 'Link title text' ), $this->row_actions( $actions ) ) :
			\sprintf( '<abbr style="color: orangered;" title="%2$s">%1$s</abbr><br /> %3$s', $email, _x( 'Email not hosted on this server - this account will not receive remote incoming emails.', 'Warning title text' ), $this->row_actions( $actions ) )
		;
	}

	public function column_domain( array $item ): string {
		$domain = \idn_to_utf8( $item['domain'] );
		return Main::email_mx_self( $item['domain'] ) ? $domain : '<abbr style="color: orangered;" title="' . _x( 'Email not hosted on this server - this account will not receive remote incoming emails.', 'Warning title text' ) . '">' . $domain . '</abbr>';
	}

	public function column_diskused( array $item ): string {
		return '<a href="'. \add_query_arg( [ 'page'  => Main::$pf . 'boxes', 'email' => $item['email'] ], \admin_url( 'admin.php' ) ) . '" title="' . _x( 'View mailboxes.','Link title text' ) . '">' . $item[ 'diskused' ] . '</a>';
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  array  $item	       - Data
	 * @param  string $column_name - Current column name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'domain':
			case 'diskused':
			case 'diskused_num':
			case 'diskquota':
				return $item[ $column_name ];
			case 'type':
				return self::display_type( $item[ $column_name ] );
			case 'email':
			case 'dest':
				return \in_array( $item['type'], [ 'forwarder', 'default' ] ) && \str_contains( $item[ $column_name ], '@' ) ? '↪️ ' . $item[ $column_name ] : ( \str_replace( [ ':fail:',':blackhole:' ], [ '🚫', '⚫' ], $item[ $column_name ] ) );

			default:
				return \print_r( $item, true ) ;
		}
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 */
	private static function sort_data( array $a, array $b ): int {
		// Set defaults
		$orderby  = '';
		$order    = 'asc';
		$main_domain = Main::$main_domain;

		// If orderby is set, use this as the sort column
		$orderby  = \sanitize_text_field( $_GET['orderby'] ?? $orderby );
//		$orderby  = $orderby === 'diskused' ? 'diskused_num' : $orderby;

		// If order is set use this as the order
		$order    = \sanitize_text_field( $_GET['order'] ?? $order );

		$a_domain = \intval( $orderby === 'domain' && $main_domain !== $a['domain'] );
		$b_domain = \intval( $orderby === 'domain' && $main_domain !== $b['domain'] );

		if ( $orderby ) {
			$result = $a_domain . $a[ $orderby ] <=>
				      $b_domain . $b[ $orderby ]
			;
		} else {
			$result = ( $a['type'] === 'default' ? '~' : $a['type'] ) . $a['domain'] <=>
				      ( $b['type'] === 'default' ? '~' : $b['type'] ) . $b['domain']
			;
		}

		return $order === 'asc' ? $result : -$result;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions(): array {
		$actions = [
			'bulk-send'     => _x( 'Get Setup instructions', 'List Table Bulk Action' ),
//			'bulk-password' => _x( 'Change Password',        'List Table Row Action'  ),
			'bulk-delete'   => _x( 'Delete addresses',       'List Table Bulk Action' ),
		];
		return $actions;
	}

	/**
	 * Handle bulk actions.
	 *
	 * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
	 *
	 * @see $this->prepare_items()
	 */
	public function process_bulk_action(): void {

		$method = \strtolower( $_SERVER['REQUEST_METHOD'] );
		$action = $this->current_action();

		if ( $method === 'get' ) {

			if ( $action === 'row-password' ) {
				$email    = \sanitize_text_field( $_GET['email'] ?? '' );
?>
				<script>
					var data = {
						'action'  : '<?php echo Main::$pf; ?>password',
						'email'   : '<?php echo $email; ?>',
						'password': prompt( '<?php echo __( 'New password for:' ), ' ', $email; ?>' )
					}
					if ( data.password ) {
						jQuery.post( ajaxurl, data, function( response) {
							alert( response );
						} );
					}
				</script>
<?php
			} elseif ( $action === 'row-quota' ) {
				$email = \sanitize_text_field( $_GET['email'] ?? '' );
?>
				<script>
					var data = {
						'action'  : '<?php echo Main::$pf; ?>quota',
						'email'   : '<?php echo $email; ?>',
						'quota'   : prompt( '<?php echo __( 'New quota for:' ), ' ', $email; ?> (MB)' )
					}
					if ( data.quota ) {
						jQuery.post( ajaxurl, data, function( response) {
							alert( response );
							location.reload();
						} );
					}
				</script>
<?php
			} elseif ( $action === 'row-delete' ) {
				$cap   = \apply_filters( Main::pf . 'capability', 'manage_options', 'delete_email' );
				$email = \sanitize_text_field( $_GET['email'] ?? '' );

				if ( \current_user_can( $cap ) || $email === \wp_get_current_user()->user_email ) {

					if ( \wp_verify_nonce( $_GET['_wpnonce'], 'row-delete' ) ) {
						$type = \sanitize_key( $_GET['type' ] ?? '' );
						$dest = \sanitize_text_field( $_GET['dest' ] ?? '' );
						$dest = \str_replace( ' ', '+', $dest );

						if ( $type === 'forwarder' ) {
							$res = UAPI::delete_forward( $email, $dest );

							if ( $res->has_errors() ) {
								\printf( '<div class="notice notice-error"><p>%1$s</p></div>', \esc_html( $res->get_error_message() ) );
							} else {
?>
								<div class="notice notice-success is-dismissible">
									<p><?php \printf(
										/* translators: 1: email */
										_x( 'Email forwarder for %1$s removed.', 'Notice success message' ), Main::email_to_utf8( $email ) ); ?>
									</p>
								</div>
<?php
							}
						} elseif ( $type === 'account' ) {
							$res = UAPI::delete_account( $email );

							if ( $res->has_errors() ) {
								\printf( '<div class="notice notice-error"><p>%1$s</p></div>', \esc_html( $res->get_error_message() ) );
							} else {
?>
								<div class="notice notice-success is-dismissible">
									<p><?php \printf(
										/* translators: 1: email */
										_x( 'Email account %1$s deleted.', 'Notice success message' ),
									Main::email_to_utf8( $email ) ); ?>
									</p>
								</div>
<?php
							}
						} elseif ( $type === 'default' ) {
							$domain = \sanitize_text_field( $_GET['domain'] ?? '' );
							$res = UAPI::set_default_fail( $domain );

							if ( $res->has_errors() ) {
								\printf( '<div class="notice notice-error"><p>%1$s</p></div>', \esc_html( $res->get_error_message() ) );
							} else {
?>
								<div class="notice notice-success is-dismissible">
									<p><?php \printf(
										/* translators: 1: domain */
										_x( 'Default address reset for %1$s.', 'Notice success message' ),
										\idn_to_utf8( $domain )
									); ?></p>
								</div>
<?php
							}
						} elseif ( $type === 'responder' ) {
							$res = UAPI::delete_responder( $email );

							if ( $res->has_errors() ) {
								\printf( '<div class="notice notice-error"><p>%1$s</p></div>', \esc_html( $res->get_error_message() ) );
							} else {
?>
								<div class="notice notice-success is-dismissible">
									<p><?php \printf(
										/* translators: 1: email */
										_x( 'Responder deleted for %1$s.', 'Notice success message' ),
										Main::email_to_utf8( $email ) ); ?>
									</p>
								</div>
<?php
							}
						}
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
?>
					<div class="notice notice-error is-dismissible">
						<p><?php \printf(
							/* translators: 1: type, 2 = domain or email depending on type */
							_x( 'Sorry, you are not allowed to delete %1$s for %2$s.',
								'Notice error message' ),
								\mb_strtolower( self::$type_names[ $type ] ?? '' ),
								$domain ? \idn_to_utf8( $domain ) : Main::email_to_utf8( $email )
							); ?></p>
					</div>
<?php
				}
			} elseif ( $this->current_action() === 'row-send' ) {
				$cap = \apply_filters( Main::pf . 'capability', 'manage_options' );

				if ( ( \current_user_can( $cap ) || $email === \wp_get_current_user()->user_email ) ) {
					$type  = \sanitize_text_field( $_GET['type' ] );

					if ( \in_array( $type, [ '_account', 'account' ], true ) ) {
						$email = \sanitize_text_field( $_GET['email'] );
						$to    = \wp_get_current_user()->user_email;
?>
						<script>
							var data = {
								'action'  : '<?php echo Main::$pf; ?>send',
								'email'   : '<?php echo $email; ?>',
								'to': prompt( '<?php _e( 'Send instructions to:' )?>', '<?=$to?>' )
							}
							if ( data.to ) {
								jQuery.post( ajaxurl, data, function( response) {
									alert( response );
								} );
							}
						</script>
<?php
					}
				} else {
?>
					<div class="notice notice-error is-dismissible">
						<p><?php _ex( 'Sorry, you are not allowed to send settings.', 'Notice error message' ); ?></p>
					</div>
<?php
				}
			}
		} elseif ( $method === 'post' ) {

			if ( $this->current_action() === 'bulk-delete' ) {
				$cap    = \apply_filters( Main::pf . 'capability', 'manage_options' );

				if ( \current_user_can( $cap ) ) {

					if ( \wp_verify_nonce( $_POST['_wpnonce'] ?? '', 'bulk-' . $this->_args['plural'] ) ) {
						$types = \array_map( 'sanitize_key', (array) $_POST['type'] );
						$dests = \array_map( 'sanitize_text_field', (array) $_POST['dest'] );
						$deleted = 0;

						foreach ( $types as $email => $type ) {

							if ( \current_user_can( 'manage_options' ) || \in_array( \wp_get_current_user()->user_email, [ $email, $dests[ $email ] ], true ) ) {
								$class = Main::$is_pro ? Pro::class : Main::class;

								if ( $type === 'forwarder' ) {
									$res = UAPI::delete_forward( $email, $dests[ $email ] );

									if ( ! $res->has_errors() ) {
										$deleted++;
									}
								} elseif ( $type === 'account' ) {
									$res = UAPI::delete_account( $email );

									if ( ! $res->has_errors() ) {
										$deleted++;
									}
								} elseif ( $type === 'default' ) {
									$domain = \explode( '@', $email )[1];
									$res = UAPI::set_default_fail( $domain );

									if ( ! $res->has_errors() ) {
										$deleted++;
									}
								} elseif ( $type === 'responder' ) {
									$res = UAPI::delete_responder( $email );

									if ( ! $res->has_errors() ) {
										$deleted++;
									}
								}
							} else { ?>
								<div class="notice notice-error is-dismissible">
									<p><?php \printf(
										/* translators: 1: email */
										_x( 'Sorry, you are not allowed to delete email address %1$s.', 'Notice error message' ),
										Main::email_to_utf8( $email )
									); ?>
									</p>
								</div>
<?php
							}
						}
?>
						<div class="notice notice-success is-dismissible">
							<p><?php

								foreach ( $types as &$type ) {
									$type = \mb_strtolower( self::$type_names[ $type ] ?? '' );
								}
								\printf(
								_nx(
									/* translators: 1: email, 2: type */
									'%1$d email address type %2$s deleted.',
									/* translators: 1: email, 2: type */
									'%1$d email addresses type %2$s deleted.',
									\intval( $deleted ),
									'Action result notice, %1$d = number of emails, %2$s = types'
									),
								\intval( $deleted ),
								\implode( ', ', $types )
							); ?></p>
						</div>
<?php
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
?>
					<div class="notice notice-error is-dismissible">
						<p><?php _ex( 'Sorry, you are not allowed to delete email addresses.', 'Notice error message' ); ?></p>
					</div>
<?php
				}
//				Main::delete_transients();
			} elseif ( $this->current_action() === 'bulk-send' ) {
				$cap = \apply_filters( Main::pf . 'capability', 'manage_options' );

				if ( \current_user_can( $cap ) ) {

					if ( true || \wp_verify_nonce( $_GET['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
						$types = \array_map( 'sanitize_key', (array) $_POST['type'] );
						$to = \wp_get_current_user()->user_email;
						$sent = 0;

						foreach ( $types as $email => $type ) {

							if ( \in_array( $type, [ '_account', 'account' ], true ) ) {
								$res = UAPI::send_settings( $email, $to );

								if ( ! $res->has_errors() ) {
									$sent++;
								} else {
									error_log( $res );
								}
							}
						}
?>
						<div class="notice notice-success is-dismissible">
							<p><?php \printf(
								_nx(
									/* translators: 1: email */
									'%1$d email account client instructions sent to %2$s.',
									/* translators: 1: email */
									'%1$d email accounts client instructions sent to %2$s.',
									\intval( $sent ),
									/* translators: , 1 = number of accounts, 2 = destination email */
									'Notice success message'
								),
								\intval( $sent ),
								\esc_attr( Main::email_to_utf8( $to ) ) );
							?></p>
						</div>
<?php
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
?>
					<div class="notice notice-error is-dismissible">
						<p><?php _ex( 'Sorry, you are not allowed to send settings.', 'Notice error message' ); ?></p>
					</div>
<?php
				}
			} elseif ( 'bulk-password' === $this->current_action() ) {
				$email    = \sanitize_text_field( $_POST['email'] ?? '' );
?>
				<script>
					var data = {
						'action'  : '<?php echo Main::$pf; ?>bulk-password',
						'email'   : '<?php echo $email; ?>',
						'password': prompt( '<?php echo __( 'New password for:' ), ' ', $email; ?>' )
					}
					if ( data.password ) {
						jQuery.post( ajaxurl, data, function( response) {
							alert( response );
						} );
					}
				</script>
<?php
			}
		}
	}

	private static function display_type( string $type ): string {
		return \array_key_exists( $type, self::$type_names ) ? self::$type_names[ $type ] : \ucfirst( $type );
	}
}
