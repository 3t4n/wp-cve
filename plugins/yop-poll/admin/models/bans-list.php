<?php
class YOP_Poll_Bans_List extends YOP_Poll_List_Table {
	public function __construct() {
		parent::__construct(
			array(
				'singular' => esc_html__( 'ban', 'yop-poll' ),
				'plural'   => esc_html__( 'bans', 'yop-poll' ),
				'ajax'     => false,
			)
		);
	}
	public static function get_bans( $per_page = 10, $page_number = 1 ) {
		$sql_prepared = '';
		if ( true === isset( $_REQUEST['s'] ) ) {
			$_search_term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
		} else {
			$_search_term = '';
		}
		$sql = 'SELECT bans.id, bans.poll_id, bans.author, bans.b_by, bans.b_value, bans.added_date,'
						. ' polls.name'
						. " FROM {$GLOBALS['wpdb']->yop_poll_bans} as bans LEFT JOIN {$GLOBALS['wpdb']->yop_poll_polls} as polls"
						. ' ON bans.`poll_id` = polls.`id`';
		if ( '' !== $_search_term ) {
			$_search_term = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $_search_term ) ) . '%';
			$sql .= ' WHERE `b_value` LIKE %s';
			$sql_prepared = $GLOBALS['wpdb']->prepare(
				$sql,
				$_search_term
			);
		} else {
			$sql_prepared = $sql;
		}
		$result = $GLOBALS['wpdb']->get_results( $sql_prepared, 'ARRAY_A' );
		foreach ( $result as &$record ) {
			$user_info = get_userdata( $record['author'] );
			$record['author'] = $user_info->user_login;
		}
		if ( true === isset( $_REQUEST['orderby'] ) ) {
			$order_by_sanitized = sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) );
			$order_direction_sanitized = isset( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : '';
			switch ( $order_by_sanitized ) {
				case 'poll': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $b['name'], $a['name'] );
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $a['name'], $b['name'] );
							}
						);
					}
					break;
				}
				case 'author': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $b['username'], $a['username'] );
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $a['username'], $b['username'] );
							}
						);
					}
					break;
				}
				case 'ban_by': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $b['b_by'], $a['b_by'] );
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $a['b_by'], $b['b_by'] );
							}
						);
					}
					break;
				}
				case 'ban_value': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $b['b_value'], $a['b_value'] );
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $a['b_value'], $b['b_value'] );
							}
						);
					}
					break;
				}
				case 'date': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return ( intval( strtotime( $b['added_date'] ) ) > intval( strtotime( $a['added_date'] ) ) ) ? 1 : -1;
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return ( intval( strtotime( $a['added_date'] ) ) > intval( strtotime( $b['added_date'] ) ) ) ? 1 : -1;
							}
						);
					}
					break;
				}
				default: {
					break;
				}
			}
		}
		return array_slice( $result, ( $page_number - 1 ) * $per_page, $per_page );
	}
	public static function record_count() {
		$sql_prepared = '';
		if ( true === isset( $_REQUEST['s'] ) ) {
			$_search_term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
		} else {
			$_search_term = '';
		}
		$sql = 'SELECT COUNT(*)'
			. " FROM {$GLOBALS['wpdb']->yop_poll_bans} as bans LEFT JOIN {$GLOBALS['wpdb']->yop_poll_polls} as polls"
			. ' ON bans.`poll_id` = polls.`id`';
		if ( '' !== $_search_term ) {
			$_search_term = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $_search_term ) ) . '%';
			$sql .= ' WHERE `b_value` LIKE %s';
			$sql_prepared = $GLOBALS['wpdb']->prepare(
				$sql,
				$_search_term
			);
		} else {
			$sql_prepared = $sql;
		}
		return $GLOBALS['wpdb']->get_var( $sql_prepared );
	}
	public function no_items() {
		esc_html_e( 'No Records Avalaible.', 'yop-poll' );
	}
	public function column_default( $item, $column_name ) {
		return $item[ $column_name ];
	}
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="yop-poll-bans-bulk-action-checkbox" class="yop-poll-bans-bulk-action-checkbox" value="%s" />',
			$item['id']
		);
	}
	public function column_poll( $item ) {
		$poll_name = '';
		if ( '0' === $item['poll_id'] ) {
			$poll_name = esc_html__( 'All Polls', 'yop-poll' );
		} else {
			$poll_name = $item['name'];
		}
		$title   = '<strong>' . $poll_name . '</strong>';
		$actions = array(
			'edit'   => sprintf(
				'<a href="?page=%s&action=%s&ban_id=%s">' . esc_html__( 'Edit', 'yop-poll' ) . '</a>',
				'yop-poll-bans',
				'edit',
				absint( $item['id'] )
			),
			'delete' => '<a href="#" class="yop-poll-bans-delete" data-id="' . esc_attr( absint( $item['id'] ) ) . '" data-token="' . esc_attr( wp_create_nonce( 'yop-poll-delete-ban' ) ) . '">' . esc_html__( 'Trash', 'yop-poll' ) . '</a>',
		);
		return $title . $this->row_actions( $actions, false );
	}
	public function column_author( $item ) {
		return $item['author'];
	}
	public function column_ban_by( $item ) {
		return $item['b_by'];
	}
	public function column_ban_value( $item ) {
		return $item['b_value'];
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
			'poll'             => esc_html__( 'Poll', 'yop-poll' ),
			'author'           => esc_html__( 'Author', 'yop-poll' ),
			'ban_by'           => esc_html__( 'Ban By', 'yop-poll' ),
			'ban_value'        => esc_html__( 'Value', 'yop-poll' ),
			'date'             => esc_html__( 'Date', 'yop-poll' ),
		);
		return $columns;
	}
	public function get_sortable_columns() {
		$sortable_columns = array(
			'poll' => array(
				'poll',
				true,
			),
			'author' => array(
				'author',
				true,
			),
			'ban_by' => array(
				'ban_by',
				true,
			),
			'ban_value' => array(
				'ban_value',
				true,
			),
			'date' => array(
				'date',
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
			'_token'       => wp_create_nonce( 'yop-poll-bulk-bans' ),
			'select-class' => 'yop-poll-bans-bulk-action-select',
			'button-text'  => esc_html__( 'Apply', 'yop-poll' ),
			'button-class' => 'yop-poll-bans-bulk-action-button',
		);
		return $button_settings;
	}
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();
		$per_page     = $this->get_items_per_page( 'bans_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);
		$this->items = self::get_bans( $per_page, $current_page );
	}
}
