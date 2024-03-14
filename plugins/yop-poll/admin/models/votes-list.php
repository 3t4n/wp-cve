<?php
class YOP_Poll_Votes_List extends YOP_Poll_List_Table {
	private static $_poll_id;
	private static $_order_by = array(
		'user_type',
		'email',
		'ipaddress',
		'date',
	);
	private static $_order = array(
		'ASC',
		'DESC',
	);
	public function __construct( $poll_id ) {
		self::$_poll_id = $poll_id;
		parent::__construct(
			array(
				'singular' => esc_html__( 'poll', 'yop-poll' ),
				'plural'   => esc_html__( 'polls', 'yop-poll' ),
				'ajax'     => false,
			)
		);
	}
	public static function get_votes( $per_page = 10, $page_number = 1 ) {
		$sql_prepared = '';
		if ( true === isset( $_REQUEST['s'] ) ) {
			$_search_term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
		} else {
			$_search_term = '';
		}
		$sql = "SELECT * FROM {$GLOBALS['wpdb']->yop_poll_votes} WHERE `status` != 'deleted' AND `poll_id` = %s";
		if ( '' !== $_search_term ) {
			$_search_term = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $_search_term ) ) . '%';
			$sql .= " AND ( `user_type` LIKE %s OR `user_email` LIKE %s OR `ipaddress` LIKE %s )";
			$sql_prepared = $GLOBALS['wpdb']->prepare(
				$sql,
				self::$_poll_id,
				$_search_term,
				$_search_term,
				$_search_term
			);
		} else {
			$sql_prepared = $GLOBALS['wpdb']->prepare(
				$sql,
				self::$_poll_id
			);
		}
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$order_sanitized = isset( $_REQUEST['order'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) ) : '';
			if ( false === in_array( $order_sanitized, self::$_order ) ) {
				$order_sanitized = 'ASC';
			}
			$order_by_sanitized = sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) );
			if ( false === in_array( $order_by_sanitized, self::$_order_by ) ) {
				$order_by_sanitized = 'added_date';
			}
			$sql_prepared .= ' ORDER BY ' . esc_sql( $order_by_sanitized );
			$sql_prepared .= ' ' . esc_sql( $order_sanitized );
		}
		$sql_prepared .= " LIMIT $per_page";
		$sql_prepared .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		$result = $GLOBALS['wpdb']->get_results( $sql_prepared, 'ARRAY_A' );
		return $result;
	}
	public static function record_count() {
		$sql_prepared = '';
		if ( true === isset( $_REQUEST['s'] ) ) {
			$_search_term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
		} else {
			$_search_term = '';
		}
		$sql = "SELECT COUNT(*) FROM {$GLOBALS['wpdb']->yop_poll_votes} WHERE `status` != 'deleted' AND `poll_id` = %s";
		if ( '' !== $_search_term ) {
			$_search_term = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $_search_term ) ) . '%';
			$sql .= " AND ( `user_type` LIKE %s OR `user_email` LIKE %s OR `ipaddress` LIKE %s )";
			$sql_prepared = $GLOBALS['wpdb']->prepare(
				$sql,
				self::$_poll_id,
				$_search_term,
				$_search_term,
				$_search_term
			);
		} else {
			$sql_prepared = $GLOBALS['wpdb']->prepare(
				$sql,
				self::$_poll_id
			);
		}
		return $GLOBALS['wpdb']->get_var( $sql_prepared );
	}
	public function no_items() {
		esc_html_e( 'No Votes Avalaible.', 'yop-poll' );
	}
	public function column_default( $item, $column_name ) {
		return $item[ $column_name ];
	}
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="yop-poll-polls-bulk-action-checkbox" class="yop-poll-polls-bulk-action-checkbox" value="%s" />',
			$item['id']
		);
	}
	public function column_user_type( $item ) {
		$title = '<strong>' . $item['user_type'] . '</strong>';
		$actions = array(
			'view-details' => '<a href="#" class="yop-poll-votes-view-details" data-id="' . esc_attr( absint( $item['id'] ) ) . '" data-token="' . esc_attr( wp_create_nonce( 'yop-poll-view-vote-details' ) ) . '">' . esc_html__( 'View Details', 'yop-poll' ) . '</a>',
			'delete' => '<a href="#" class="yop-poll-votes-delete" data-id="' . esc_attr( absint( $item['id'] ) ) . '" data-token="' . esc_attr( wp_create_nonce( 'yop-poll-delete-vote' ) ) . '" data-poll-id="' . esc_attr( $item['poll_id'] ) . '">' . esc_html__( 'Trash', 'yop-poll' ) . '</a>',
		);
		return $title . $this->row_actions( $actions, false );
	}
	public function column_username( $item ) {
		$username = '';
		if ( 'wordpress' === $item['user_type'] ) {
			$user_info = get_userdata( $item['user_id'] );
			$username = $user_info->user_login;
		}
		return $username;
	}
	public function column_email( $item ) {
		return $item['user_email'];
	}
	public function column_ipaddress( $item ) {
		return $item['ipaddress'];
	}
	public function column_date( $item ) {
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		$vote_date = date_i18n(
			$date_format . ' ' . $time_format,
			strtotime( $item['added_date'] )
		);
		return $vote_date;
	}
	public function get_columns() {
		$columns = array(
			'cb'               => '<input type="checkbox" />',
			'user_type'        => esc_html__( 'User Type', 'yop-poll' ),
			'username'         => esc_html__( 'Username', 'yop-poll' ),
			'email'            => esc_html__( 'Email', 'yop-poll' ),
			'ipaddress'        => esc_html__( 'Ipaddress', 'yop-poll' ),
			'date'             => esc_html__( 'Date', 'yop-poll' ),
		);
		return $columns;
	}
	public function get_sortable_columns() {
		$sortable_columns = array(
			'user_type' => array(
				'user_type',
				true,
			),
			'email' => array(
				'user_email',
				true,
			),
			'ipaddress' => array(
				'ipaddress',
				true,
			),
			'date' => array(
				'added_date',
				true,
			),
		);
		return $sortable_columns;
	}
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => esc_html__( 'Trash', 'yop-poll' ),
		);
		return $actions;
	}
	public function get_bulk_actions_settings() {
		$button_settings = array(
			'_token' => wp_create_nonce( 'yop-poll-bulk-votes' ),
			'select-class' => 'yop-poll-votes-bulk-action-select',
			'button-text' => esc_html__( 'Apply', 'yop-poll' ),
			'button-class' => 'yop-poll-votes-bulk-action-button',
		);
		return $button_settings;
	}
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();
		$per_page     = $this->get_items_per_page( 'votes_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);
		$this->items = self::get_votes( $per_page, $current_page );
	}
}
