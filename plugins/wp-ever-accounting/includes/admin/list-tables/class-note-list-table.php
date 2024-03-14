<?php
/**
 * Notes list table
 *
 * Admin notes list table, show all the note information.
 *
 * @since       1.1.0
 * @subpackage  EverAccounting\Admin\ListTables
 * @package     EverAccounting
 */

use EverAccounting\Models\Note;

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\EverAccounting_List_Table' ) ) {
	require_once dirname( __FILE__ ) . '/class-list-table.php';
}

/**
 * Class EverAccounting_Note_List_Table
 *
 * @since 1.1.0
 */
class EverAccounting_Note_List_Table extends EverAccounting_List_Table {
	/**
	 * Default number of items to show per page
	 *
	 * @since 1.1.0
	 * @var string
	 */
	public $per_page = 20;

	/**
	 * Total number of item found
	 *
	 * @since 1.1.0
	 * @var int
	 */
	public $total_count;

	/**
	 * Number of active items found
	 *
	 * @since 1.1.0
	 * @var string
	 */
	public $active_count;

	/**
	 *  Number of inactive items found
	 *
	 * @since 1.1.0
	 * @var string
	 */
	public $inactive_count;

	/**
	 * Get things started
	 *
	 * @param array $args Optional. Arbitrary display and query arguments to pass through the list table. Default empty array.
	 *
	 * @since  1.1.0
	 *
	 * @see WP_List_Table::__construct()
	 */
	public function __construct( $args = array() ) {
		$args = (array) wp_parse_args(
			$args,
			array(
				'singular' => 'note',
				'plural'   => 'notes',
			)
		);

		parent::__construct( $args );
	}

	/**
	 * Define which columns to show on this screen.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public function define_columns() {
		return array(
			'cb'           => '<input type="checkbox" />',
			'note'         => __( 'Note', 'wp-ever-accounting' ),
			'author'       => __( 'Author', 'wp-ever-accounting' ),
			'date_created' => __( 'Date Created', 'wp-ever-accounting' ),
			'actions'      => __( 'Actions', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Define sortable columns.
	 *
	 * @return array
	 * @since 1.1.0
	 */
	protected function define_sortable_columns() {
		return array(
			'note'         => array( 'note', false ),
			'author'       => array( 'author', false ),
			'date_created' => array( 'date_created', false ),
		);
	}

	/**
	 * Define bulk actions
	 *
	 * @return array
	 * @since 1.1.0
	 */
	public function define_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'wp-ever-accounting' ),
		);
	}

	/**
	 * Define primary column.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public function get_primary_column() {
		return 'note';
	}

	/**
	 * Renders the checkbox column in the notes list table.
	 *
	 * @param Note $note The current account object.
	 *
	 * @return string Displays a checkbox.
	 * @since  1.1.0
	 */
	public function column_cb( $note ) {
		return sprintf( '<input type="checkbox" name="note_id[]" value="%d"/>', esc_attr( $note->get_id() ) );
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param Note   $note The current note object.
	 * @param string $column_name The name of the column.
	 *
	 * @return string The column value.
	 * @since 1.1.0
	 */
	public function column_default( $note, $column_name ) {
		$note_id = $note->get_id();
		switch ( $column_name ) {
			case 'note':
				$note  = $note->get_note();
				$url   = eaccounting_admin_url(
					array(
						'action'  => 'view',
						'note_id' => $note_id,
					)
				);
				$value = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( $url ),
					$note
				);
				break;
			case 'author':
				$value = $note->get_author();
				break;
			case 'date_created':
				$value = esc_html( eaccounting_date( $note->get_date_created() ) );
				break;
			case 'actions':
				$tab      = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'customer' ) ) );
				$edit_url = eaccounting_admin_url(
					array(
						'tab'     => $tab,
						'action'  => 'edit',
						'note_id' => $note_id,
						'subtab'  => 'notes',
					)
				);
				$del_url  = eaccounting_admin_url(
					array(
						'tab'     => $tab,
						'action'  => 'delete',
						'note_id' => $note_id,
						'subtab'  => 'notes',
					)
				);
				$actions  = array(
					'edit'   => sprintf( '<a href="%s" class="dashicons dashicons-edit"></a>', esc_url( $edit_url ) ),
					'delete' => sprintf( '<a href="%s" class="dashicons dashicons-trash"></a>', esc_url( $del_url ) ),
				);
				$value    = $this->row_actions( $actions );
				break;
			default:
				return parent::column_default( $note, $column_name );
		}

		return apply_filters( 'eaccounting_note_list_table_' . $column_name, $value, $note );
	}

	/**
	 * Renders the message to be displayed when there are no items.
	 *
	 * @return void
	 * @since  1.1.0
	 */
	public function no_items() {
		esc_html_e( 'There is no notes found.', 'wp-ever-accounting' );
	}

	/**
	 * Process the bulk actions
	 *
	 * @return void
	 * @since 1.1.0
	 */
	public function process_bulk_action() {
		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'bulk-notes' ) && ! wp_verify_nonce( $nonce, 'note-nonce' ) ) {
			return;
		}

		$ids = isset( $_GET['note_id'] ) ? wp_parse_id_list( wp_unslash( $_GET['note_id'] ) ) : false;

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$ids = array_map( 'absint', $ids );
		$ids = array_filter( $ids );

		if ( empty( $ids ) ) {
			return;
		}

		$action = $this->current_action();
		foreach ( $ids as $id ) {
			switch ( $action ) {
				case 'delete':
					eaccounting_delete_note( $id );
					break;
				default:
					do_action( 'eaccounting_notes_do_bulk_action_' . $this->current_action(), $id );
			}
		}

		if ( $nonce ) {
			wp_safe_redirect(
				remove_query_arg(
					array(
						'note_id',
						'action',
						'_wpnonce',
						'_wp_http_referer',
						'action2',
						'paged',
					)
				)
			);
			exit();
		}
	}

	/**
	 * Retrieve all the data for the table.
	 * Setup the final data for the table
	 *
	 * @return void
	 * @since 1.1.0
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();

		$page     = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT, array( 'options' => array( 'default' => 1 ) ) );
		$search   = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
		$order    = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'DESC' ) ) );
		$orderby  = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING, array( 'options' => array( 'default' => 'id' ) ) );
		$status   = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
		$per_page = $this->per_page;

		$args = wp_parse_args(
			$this->query_args,
			array(
				'number'   => $per_page,
				'offset'   => $per_page * ( $page - 1 ),
				'per_page' => $per_page,
				'page'     => $page,
				'status'   => $status,
				'search'   => $search,
				'orderby'  => eaccounting_clean( $orderby ),
				'order'    => eaccounting_clean( $order ),
			)
		);

		$args        = apply_filters( 'eaccounting_note_table_query_args', $args, $this );
		$this->items = eaccounting_get_notes( $args );

		$this->total_count = eaccounting_get_notes( array_merge( $args, array( 'count_total' => true ) ) );

		$this->set_pagination_args(
			array(
				'total_items' => $this->total_count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $this->total_count / $per_page ),
			)
		);
	}
}
