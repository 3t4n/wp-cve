<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( 'WP' ) || exit;

class BoxesTable extends \WP_List_Table {

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
		$per_page = 50;

		if ( Main::$domain_only ) {
			$data = \array_filter( $data, static function( $row ): bool {
				return \str_ends_with( $row['email'], '@'. Main::$site_domain );
			} );
		}

		\usort( $data, 'self::sort_data' );
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
			'email'	       => _x( 'Email',    'Column Header' ),
			'box_guid'     => 'GUID',
			'box_name'     => _x( 'Mailbox',  'Column Header' ),
			'messages'     => _x( 'Messages', 'Column Header' ),
			'diskused'     => _x( 'Used',     'Column Header' ),
			'diskused_num' => 'DUN',
		];
		return $columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return array
	 */
	public function get_hidden_columns(): array {
		return [ 'email', 'box_guid', 'diskused_num' ];
	}

	/**
	 * Define the sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns(): array {
		return [
			'box_name'  => [ 'box_name', false ],
			'messages'  => [ 'massages', false ],
			'diskused'  => [ 'diskused_num', false ],
		];
	}

	/**
	 * Get the table data
	 */
	private function table_data(): array {
		$data = [];
		$email = UAPI::main_email_account()->email === $this->_args['email'] ? '' : $this->_args['email'];

		foreach ( UAPI::email_mailboxes( $email ) as $box ) {
			$data[] = [
				'email'        => $this->_args['email'],
				'box_guid'     => $box->guid,
				'box_name'     => $box->mailbox,
				'messages'     => (string) \intval( $box->messages ),
				'diskused'     => \size_format( $box->vsize ),
				'diskused_num' => (string) $box->vsize,
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
		return $item['messages'] ?
			\sprintf(
				'<input type="checkbox" name="guid[]" value="%1$s" />',
				\esc_attr( $item['box_guid'] ),
			) :
		'';
	}

	/**
	 * Render the bulk edit checkbox
	 */
	public function column_messages( array $item ): string {
		$page = \sanitize_text_field( $_REQUEST['page'] );
		$actions = $item['messages'] ? [
			'row-delete'   => '<a href="' . \add_query_arg( [
				'page'     => \esc_attr( $page ),
				'email'    => $this->_args['email'],
				'box'      => $item['box_name'],
				'guid'     => $item['box_guid'],
				'action'   => 'row-delete',
				'_wpnonce' => \wp_create_nonce( 'row-delete' ),
			], \admin_url( 'admin.php' ) ) . '">' . _x( 'Delete messages', 'List Table Row Action' ),
		] :
		[];
		return $item[ 'messages' ] . '<br />' . $this->row_actions( $actions );
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
			case 'box_name':
				return '<span title="Native name: ' . $item[ $column_name ] . '.">' . self::display_mailbox( $item[ $column_name ] ) . '</span>';
			case 'messages':
			case 'diskused':
				return $item[ $column_name ];
			default:
				return \print_r( $item, true ) ;
		}
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 */
	private static function sort_data( array $a, array $b ): int {
		// Set defaults
		$orderby  = 'box_name';
		$order    = 'asc';

		// If orderby is set, use this as the sort column
		$orderby  = \sanitize_text_field( $_GET['orderby'] ?? $orderby );

		// If order is set use this as the order
		$order    = \sanitize_text_field( $_GET['order'] ?? $order );

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
			'bulk-delete' => _x( 'Delete Messages Older Than 1 Year', 'Bulk Action' ),
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

		if ( $method === 'post' ) {

			if ( $action === 'bulk-delete' ) {

				if ( \current_user_can( 'manage_options' ) ) {

					if ( \wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
						$guids = \array_map( 'sanitize_text_field', $_POST['guid'] ?? [] );
						$deleted = 0;
						foreach ( $guids as $guid ) {
							$email = UAPI::main_email_account()->email === $this->_args['email'] ? '' : $this->_args['email'];
							$res = UAPI::delete_messages( $email, $guid );
							$deleted++;
						}
?>
						<div class="notice notice-success is-dismissible">
							<p><?php \printf(
								_nx(
									'%1$d mailbox reduced.',
									'%1$d mailboxes reduced.',
									\intval( $deleted ),
									'Action result notice, %1$d = number of mailboxes'
								),
								\intval( $deleted ),
							); ?></p>
						</div>
<?php
					}
				}
			}
		} else {

			if ( $action === 'row-delete' ) {

				if ( \current_user_can( 'manage_options' ) || $this->_args['email'] === \wp_get_current_user()->user_email ) {

					if ( \wp_verify_nonce( $_GET['_wpnonce'], 'row-delete' ) ) {
						$guid  = \sanitize_text_field( $_GET['guid'] ?? '' );
						$box   = \sanitize_text_field( $_GET['box' ] ?? '' );
						$res = UAPI::delete_messages( UAPI::main_email_account()->email === $this->_args['email'] ? '' : $this->_args['email'], $guid, 'savedbefore 1h' );
//						Main::delete_transients();
?>
							<div class="notice notice-success is-dismissible">
								<p><?php \printf(
									_x( 'All messages older than 1 hour in mailbox %1$s for %2$s deleted.', 'Notice success' ),
									$box,
									Main::email_to_utf8( $this->_args['email'] ),
								); ?></p>
							</div>
<?php
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
					\wp_die( _x( 'You are not allowed to delete messages.', 'Die message' ) );
				}
			}
		}
	}

	private static function display_mailbox( string $box ): string {
		$parts = \explode( '.', \trim( $box ), 2 );
		$box = \count( $parts ) > 1 && $parts[0] === 'INBOX' ? $parts[1] : $box;
		$box = \ucfirst( \strtolower( $box ) );
		$box_names = [
			'Inbox'   => _x( 'Inbox',           'Box name' ),
			'Archive' => _x( 'Archive',         'Box name' ),
			'Drafts'  => _x( 'Drafts',          'Box name' ),
			'Sent'    => _x( 'Sent',            'Box name' ),
			'Spam'    => _x( 'Spam',            'Box name' ),
			'Junk'    => _x( 'Junk',            'Box name' ),
			'Trash'   => _x( 'Trash',           'Box name' ),
			'Starred' => _x( 'Starred',         'Box name' ),
		];
		return \array_key_exists( $box, $box_names ) ? $box_names[ $box ] : $box;
	}
}
