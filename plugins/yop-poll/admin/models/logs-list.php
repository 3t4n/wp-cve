<?php
class YOP_Poll_Logs_List extends YOP_Poll_List_Table {
	public function __construct() {
		parent::__construct(
			array(
				'singular' => esc_html__( 'poll', 'yop-poll' ),
				'plural'   => esc_html__( 'polls', 'yop-poll' ),
				'ajax'     => false,
			)
		);
	}
	public static function get_logs( $per_page = 10, $page_number = 1 ) {
		$sql_prepared = '';
		if ( true === isset( $_REQUEST['s'] ) ) {
			$_search_term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
		} else {
			$_search_term = '';
		}
		$sql = "SELECT logs.*, polls.name FROM {$GLOBALS['wpdb']->yop_poll_logs}"
					. " as logs LEFT JOIN {$GLOBALS['wpdb']->yop_poll_polls} as polls"
					. ' ON logs.`poll_id` = polls.`id`';
		if ( '' !== $_search_term ) {
			$_search_term = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $_search_term ) ) . '%';
			$sql .= " WHERE ( `name` LIKE %s OR `user_email` LIKE %s OR `ipaddress` LIKE %s )";
			$sql_prepared = $GLOBALS['wpdb']->prepare(
				$sql,
				$_search_term,
				$_search_term,
				$_search_term
			);
		} else {
			$sql_prepared = $sql;
		}
		$result = $GLOBALS['wpdb']->get_results( $sql_prepared, 'ARRAY_A' );
		foreach ( $result as &$record ) {
			if ( 'wordpress' === $record['user_type'] ) {
				$user_info = get_userdata( $record['user_id'] );
				$record['username'] = $user_info->user_login;
			} else {
				$record['username'] = '';
			}
			$message = unserialize( $record['vote_message'] );
			$record['message'] = $message[0];
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
				case 'username': {
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
				case 'email': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $b['user_email'], $a['user_email'] );
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $a['user_email'], $b['user_email'] );
							}
						);
					}
					break;
				}
				case 'utype': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $b['user_type'], $a['user_type'] );
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $a['user_type'], $b['user_type'] );
							}
						);
					}
					break;
				}
				case 'ipaddress': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $b['ipaddress'], $a['ipaddress'] );
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $a['ipaddress'], $b['ipaddress'] );
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
				case 'message': {
					if ( 'desc' === $order_direction_sanitized ) {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $b['message'], $a['message'] );
							}
						);
					} else {
						usort(
							$result,
							function( $a, $b ) {
								return strcasecmp( $a['message'], $b['message'] );
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
		$sql = "SELECT COUNT(*) FROM {$GLOBALS['wpdb']->yop_poll_logs}"
					. " as logs LEFT JOIN {$GLOBALS['wpdb']->yop_poll_polls} as polls"
					. ' ON logs.`poll_id` = polls.`id`';
		if ( '' !== $_search_term ) {
			$_search_term = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $_search_term ) ) . '%';
			$sql .= " WHERE ( `name` LIKE %s OR `user_email` LIKE %s OR `ipaddress` LIKE %s )";
			$sql_prepared = $GLOBALS['wpdb']->prepare(
				$sql,
				$_search_term,
				$_search_term,
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
			'<input type="checkbox" name="yop-poll-logs-bulk-action-checkbox" class="yop-poll-logs-bulk-action-checkbox" value="%s" />',
			$item['id']
		);
	}
	public function column_poll( $item ) {
		$title = '<strong>' . $item['name'] . '</strong>';
		$actions = array(
			'view-details' => '<a href="#" class="yop-poll-logs-view-details" data-id="' . esc_attr( absint( $item['id'] ) ) . '" data-token="' . esc_attr( wp_create_nonce( 'yop-poll-view-log-details' ) ) . '">' . esc_html__( 'View Details', 'yop-poll' ) . '</a>',
			'delete' => '<a href="#" class="yop-poll-logs-delete" data-id="' . esc_attr( absint( $item['id'] ) ) . '" data-token="' . esc_attr( wp_create_nonce( 'yop-poll-delete-log' ) ) . '">' . esc_html__( 'Trash', 'yop-poll' ) . '</a>',
		);
		return $title . $this->row_actions( $actions, false );
	}
	public function column_username( $item ) {
		return $item['username'];
	}
	public function column_email( $item ) {
		return $item['user_email'];
	}
	public function column_user_type( $item ) {
		return $item['user_type'];
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
	public function column_message( $item ) {
		$message = unserialize( $item['vote_message'] );
		return $message[0];
	}
	public function get_columns() {
		$columns = array(
			'cb'               => '<input type="checkbox" />',
			'poll'             => esc_html__( 'Poll', 'yop-poll' ),
			'username'         => esc_html__( 'Username', 'yop-poll' ),
			'email'            => esc_html__( 'Email', 'yop-poll' ),
			'user_type'        => esc_html__( 'User Type', 'yop-poll' ),
			'ipaddress'        => esc_html__( 'Ip Address', 'yop-poll' ),
			'date'             => esc_html__( 'Date', 'yop-poll' ),
			'message'          => esc_html__( 'Message', 'yop-poll' ),
		);
		return $columns;
	}
	public function get_sortable_columns() {
		$sortable_columns = array(
			'poll' => array(
				'poll',
				true,
			),
			'username' => array(
				'username',
				true,
			),
			'email' => array(
				'email',
				true,
			),
			'user_type' => array(
				'utype',
				true,
			),
			'ipaddress' => array(
				'ipaddress',
				true,
			),
			'date' => array(
				'date',
				true,
			),
			'message' => array(
				'message',
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
			'_token' => wp_create_nonce( 'yop-poll-bulk-logs' ),
			'select-class' => 'yop-poll-logs-bulk-action-select',
			'button-text' => esc_html__( 'Apply', 'yop-poll' ),
			'button-class' => 'yop-poll-logs-bulk-action-button',
		);
		return $button_settings;
	}
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();
		$per_page     = $this->get_items_per_page( 'logs_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);
		$this->items = self::get_logs( $per_page, $current_page );
	}
}
