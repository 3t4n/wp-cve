<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( 'WP' ) || exit;

class TokensTable extends \WP_List_Table {

	/**
	 * Prepare the items for the table to process
	 *
	 * @return never
	 */
	public function prepare_items() {

		$this->process_bulk_action();

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data	  = $this->table_data();
		$cur_page = $this->get_pagenum();
		$per_page = 10;

		\usort( $data, [ $this, 'sort_data'] );
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
			'cb'      => '<input type="checkbox"/>',
//			'host'    => _x( 'Host name',    'Column Header' ),
			'name'    => _x( 'Name',         'Column Header' ),
			'created' => _x( 'Created',      'Column Header' ),
			'expires' => _x( 'Expires',      'Column Header' ),
			'full'    => _x( 'Full Access',  'Column Header' ),
			'known'   => _x( 'Known',        'Column Header' ),
			'active'  => '<strong style="color: orangered;">' . _x( 'Active', 'Column Header' ) . '</strong>',
		];
		return $columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return array
	 */
	public function get_hidden_columns(): array {
		return [];
	}

	/**
	 * Define the sortable columns
	 *
	 * @return array
	 */
	public function x_get_sortable_columns(): array {
		return [
			'name'      => [ 'name'     => false ],
		];
	}

	/**
	 * Get the table data
	 */
	private function table_data(): array {
		$data = [];
		$has_active = false;
		$active_key = Main::$active_key;

		foreach ( UAPI::tokens() as $token ) {
			$aknown_key = Main::pf . 'token.' . Main::$cpanel_user . ( Main::$remote_cpanel ? '@' . Main::$host_name  : '' ) . '.' . $token->name;
			$active     = get_option( $active_key ) === $token->name;
			$data[] = [
//				'host'       => \explode( '@', $active_key, 2 )[1] ?? Main::$host_name,
				'name'       => $token->name,
				'created'    => $token->create_time,
				'expires'    => $token->expires_at,
				'full'       => $token->has_full_access,
				'known'      => \boolval( get_option( $aknown_key ) ),
				'active'     => $active,
			];
			$has_active = $has_active || $active;
		}

		if ( Main::$has_http && \defined( 'WF_CPANEL_API_TOKEN' ) ) {
			$known  = \is_string( 'WF_CPANEL_API_TOKEN' ) && ! empty( 'WF_CPANEL_API_TOKEN' );
			$active = $known && Main::$has_http && ! Main::$use_exec;
			$data[] = [
//				'host'       => \explode( '@', $active_key, 2 )[1] ?? Main::$host_name,
				'name'    => 'WF_CPANEL_API_TOKEN',
				'created' => 0,
				'expires' => 0,
				'full'    => true,
				'known'   => $known,
				'active'  => $active && ! $has_active,
			];
			$has_active = $has_active || $active;
		}

		if ( Main::$has_exec ) {
			$data[] = [
//				'host'    => '',
				'name'    => UAPI::exec,
				'created' => 0,
				'expires' => 0,
				'full'    => true,
				'known'   => true,
				'active'  => ! $has_active,
			];
		}
		return $data;
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return ! $item['active'] && $item['created'] ?
			\sprintf(
				'<input type="checkbox" name="name[]" value="%1$s"/>',
				\esc_attr( $item['name'] ),
			) :
		'';
	}

	public function column_name( array $item ): string {
		$page = \sanitize_title( $_REQUEST['page'] );
		$actions = [];

		$actions = $actions + ( $item['known'] && ! $item['active'] ? [
			'row-activate'   => '<a href="' . \add_query_arg( [
				'page'     => \esc_attr( $page ),
				'name'    => $item['name'],
				'action'   => 'row-activate',
				'_wpnonce' => \wp_create_nonce( 'row-activate' ),
			], \admin_url( 'admin.php' ) ) . '">' . _x( 'Activate', 'Tokens List Row Action' ),
		] : [] );
		$actions = $actions + ( $item['created'] ? [
			'row-rename'   => '<a href="' . \add_query_arg( [
				'page'     => \esc_attr( $page ),
				'name'     => $item['name'  ],
				'active'   => $item['active'],
				'action'   => 'row-rename',
				'_wpnonce' => \wp_create_nonce( 'row-rename' ),
			], \admin_url( 'admin.php' ) ) . '">' . _x( 'Rename', 'Tokens List Row Action' ),
		] : [] );
		$actions = $actions + ( $item['created'] && ! $item['active'] ? [
			'row-delete'   => '<a href="' . \add_query_arg( [
				'page'     => \esc_attr( $page ),
				'name'    => $item['name'],
				'action'   => 'row-delete',
				'_wpnonce' => \wp_create_nonce( 'row-delete' ),
			], \admin_url( 'admin.php' ) ) . '">' . _x( 'Delete', 'Tokens List Row Action' ),
		] : [] );
		return ( $item['created'] ? '<strong>' . $item['name'] . '</strong>': $item['name'] ). $this->row_actions( $actions );
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
		$format = \get_option( 'time_format' );
		$dot    = \substr( $format, 1, 1 );
		$s      = '';//\str_ends_with( $format, $dot . 's' ) ? '' : $dot . 's';
		$format = 'l ' . \get_option( 'date_format' ) . ' ' . _x( '\a\t', 'Time divider' ) . ' ' . $format . $s;

		switch ( $column_name ) {
//			case 'name':
//				return $item[ $column_name ];
			case 'host':
				return $item[ $column_name ];
			case 'created':
				return $item[ $column_name ] ? \ucfirst( \wp_date( $format, $item[ $column_name ] ) ) : '&mdash;';
			case 'expires':
				$value = \ucfirst( \wp_date( $format, $item[ $column_name ] ) );
				return $item[ $column_name ] ? ( $item[ $column_name ] - \time() < \YEAR_IN_SECONDS ? '<strong style="color: orangered;">' . $value . '</strong>' : $value ) : __( 'Never' );
			case 'full':
				return $item[ $column_name ] ?  __( 'Yes' ) : __( 'No' );
			case 'known':
				return $item['name'] === UAPI::exec ? '&mdash;' : ( $item[ $column_name ] ?  __( 'Yes' ) : __( 'No' ) );
			case 'active':
				return $item[ $column_name ] ? '<strong style="color: orangered;">'. __( 'Yes' ) . '</strong>' : '<string style="color: darkblue;">' . __( 'No' ) . '</strong>';
			default:
				return \print_r( $item, true ) ;
		}
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 */
	private static function sort_data( array $a, array $b ): int {
		// Set defaults
		$orderby  = 'created';
		$order    = 'desc';

		// If orderby is set, use this as the sort column
		$orderby  = \sanitize_title( $_GET['orderby'] ?? $orderby );

		// If order is set use this as the order
		$order    = \sanitize_title( $_GET['order'] ?? $order );

		$result = $a[ $orderby ] <=> $b[ $orderby ];

		return $order === 'asc' ? $result : -$result;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions(): array {
		$actions = [
			'bulk-delete' => _x( 'Delete Tokens', 'Bulk Action Label' ),
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

			if ( $action === 'row-activate' ) {
				$cap   = \apply_filters( Main::pf . 'capability', 'manage_options', 'activate_token' );

				if ( \current_user_can( $cap ) ) {

					if ( \wp_verify_nonce( $_GET['_wpnonce'], $action ) ) {
						$token  = \sanitize_text_field( $_GET['name'  ] ?? '' );
						$shell = \str_starts_with( $token, UAPI::exec   ) && Main::$has_exec;
						$const = \str_starts_with( $token, 'wf_cpanel_' ) && Main::$has_http;
						$aknown_key = Main::pf . 'token.' . Main::$cpanel_user . ( Main::$remote_cpanel ? '@' . Main::$host_name  : '' ) . '.' . $token;

						if ( $shell || $const || get_option( $aknown_key ) ) {
							$active_key = Main::$active_key;

							if ( $const ) {
								delete_option( $active_key );
							} else {
								update_option( $active_key, $token );
							} ?>
							<div class="notice notice-success is-dismissible">
								<p><?php
									/* translators: 1: Type prefix */
									$type = $shell ? __( 'Method' ) : ( $const ? __( 'Constant' ) : __( 'Token' ) );
									/* translators: 1: Type prefix, 2: name of activated token */
									\printf( _x( '%1$s %2$s activated.', 'Row action message' ), $type, $const ? \strtoupper( $token ) : $token ); ?>
								</p>
							</div>
<?php
						}
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
?>
					<div class="notice notice-error is-dismissible">
						<p><?php _ex( 'Sorry, you are not allowed to activate tokens.', 'Notice error message' ); ?></p>
					</div>
<?php
				}
			} elseif ( $action === 'row-rename' ) {
				$cap   = \apply_filters( Main::pf . 'capability', 'manage_options', 'rename_token' );

				if ( \current_user_can( $cap ) ) {

					if ( \wp_verify_nonce( $_GET['_wpnonce'], $action ) ) {
						$token  = \sanitize_text_field( $_GET['name'  ] ?? '' );
						$active = \boolval(      $_GET['active'] ?? '' ); ?>
						<script>
							var data = {
								'action'  : '<?php echo Main::$pf; ?>rename_token',
								'token'   : '<?php echo $token; ?>',
								'new_name': prompt( '<?php echo __( 'New name for token:' ), ' ', $token; ?>', '<?=\wp_get_current_user()->user_nicename?>' ),
								'active'  : '<?php echo $active; ?>'
							}
							if ( data.token ) {
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
				} else {
?>
					<div class="notice notice-error is-dismissible">
						<p><?php _ex( 'Sorry, you are not allowed to rename tokens.', 'Notice error message' ); ?></p>
					</div>
<?php
				}
			} elseif ( $action === 'row-delete' ) {
				$cap   = \apply_filters( Main::pf . 'capability', 'manage_options', 'delete_token' );

				if ( \current_user_can( $cap ) ) {

					if ( \wp_verify_nonce( $_GET['_wpnonce'], $action ) ) {
						$token = \sanitize_text_field( $_GET['name'] ?? '' );
						$res = UAPI::token_delete( $token );

						if ( ! $res->has_errors() ) {
							$aknown_key = Main::pf . 'token.' . Main::$cpanel_user . ( Main::$remote_cpanel ? '@' . Main::$host_name  : '' ) . '.' . $token;
							$active_key = Main::$active_key;
							$active     = get_option( $active_key ) === $token;
							delete_option( $aknown_key );

							if ( $active ) {
								delete_option( $active_key );
							}
//							Main::delete_transients(); ?>
							<div class="notice notice-success is-dismissible">
								<p><?php
									/* translators: 1: name of deleted token */
									\printf( _x( 'Token %1$s deleted.', 'Row action message' ), $token  ); ?>
								</p>
							</div>
<?php
						}
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
?>
					<div class="notice notice-error is-dismissible">
						<p><?php _ex( 'Sorry, you are not allowed to delete tokens.', 'Notice error message' ); ?></p>
					</div>
<?php
				}
			}
		} elseif ( $method === 'post' ) {

			if ( $action === 'bulk-delete' ) {

				if ( \current_user_can( 'install_plugins' ) ) {

					if ( \wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
						$tokens = \array_map( 'sanitize_text_field', (array) $_POST['name'] );
						$deleted = 0;

						foreach ( $tokens as $token ) {
							$delete = ! UAPI::token_delete( $token )->has_errors();

							if ( $delete ) {
								$aknown_key = Main::pf . 'token.' . Main::$cpanel_user . ( self::$remote_cpanel ? '@' . self::$host_name  : '' ) . '.' . $token;
								$active_key = self::$active_key;	//Main::pf . 'token.active.' . Main::$cpanel_user;
								$active     = get_option( $active_key ) === $token;
								delete_option( $aknown_key );

								if ( $active ) {
									delete_option( $active_key );
								}
								$deleted++;
							}
						}
						if ( $deleted ) {
//							Main::delete_transients();
						}
?>
						<div class="notice notice-success is-dismissible">
							<p><?php \printf(
								_nx(
									'%1$d token deleted.',
									'%1$d tokens deleted.',
									\intval( $deleted ),
									/* translators: 1: number of tokens deleted */
									'Bulk action result notice'
								),
								\intval( $deleted ),
							); ?></p>
						</div>
<?php
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
?>
					<div class="notice notice-error is-dismissible">
						<p><?php _ex( 'Sorry, you are not allowed to delete tokens.', 'Notice error message' ); ?></p>
					</div>
<?php
				}
			}
		}
	}
}
