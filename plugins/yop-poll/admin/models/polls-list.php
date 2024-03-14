<?php
class YOP_Poll_Polls_List extends YOP_Poll_List_Table {
	public function __construct() {
		parent::__construct(
			array(
				'singular' => esc_html__( 'poll', 'yop-poll' ),
				'plural'   => esc_html__( 'polls', 'yop-poll' ),
				'ajax'     => false,
			)
		);
	}
	public static function get_polls( $per_page = 10, $page_number = 1 ) {
		if ( true === isset( $_REQUEST['s'] ) ) {
			$_search_term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
			$_search_term = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $_search_term ) ) . '%';
			$sql = "SELECT * FROM {$GLOBALS['wpdb']->yop_poll_polls} WHERE `status` != 'deleted' AND `name` LIKE %s";
			$sql = $GLOBALS['wpdb']->prepare(
				$sql,
				$_search_term
			);
		} else {
			$sql = "SELECT * FROM {$GLOBALS['wpdb']->yop_poll_polls} WHERE `status` != 'deleted'";
		}
		$result = $GLOBALS['wpdb']->get_results( $sql, 'ARRAY_A' );
		foreach ( $result as &$row ) {
			$poll_meta = unserialize( $row['meta_data'] );
			if ( 'now' === $poll_meta['options']['poll']['startDateOption'] ) {
				$row['start_date'] = $row['added_date'];
			} else {
				$row['start_date'] = $poll_meta['options']['poll']['startDateCustom'];
			}
			if ( 'never' === $poll_meta['options']['poll']['endDateOption'] ) {
				$row['end_date'] = '2100-12-31 23:59:59';
			} else {
				$row['end_date'] = $poll_meta['options']['poll']['endDateCustom'];
			}
		}
		if ( true === isset( $_REQUEST['orderby'] ) ) {
			$order_by_sanitized = sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) );
			$order_direction_sanitized = isset( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : '';
		} else {
			$order_by_sanitized = 'adate';
			$order_direction_sanitized = 'desc';
		}
		switch ( $order_by_sanitized ) {
			case 'id': {
				if ( 'desc' === $order_direction_sanitized ) {
					usort(
						$result,
						function( $a, $b ) {
							return ( intval( $b['id'] ) > intval( $a['id'] ) ) ? 1 : -1;
						}
					);
				} else {
					usort(
						$result,
						function( $a, $b ) {
							return ( intval( $a['id'] ) > intval( $b['id'] ) ) ? 1 : -1;
						}
					);
				}
				break;
			}
			case 'name': {
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
			case 'status': {
				if ( 'desc' === $order_direction_sanitized ) {
					usort(
						$result,
						function( $a, $b ) {
							return strcasecmp( $b['status'], $a['status'] );
						}
					);
				} else {
					usort(
						$result,
						function( $a, $b ) {
							return strcasecmp( $a['status'], $b['status'] );
						}
					);
				}
				break;
			}
			case 'votes': {
				if ( 'desc' === $order_direction_sanitized ) {
					usort(
						$result,
						function( $a, $b ) {
							return ( intval( $b['total_submits'] ) > intval( $a['total_submits'] ) ) ? 1 : -1;
						}
					);
				} else {
					usort(
						$result,
						function( $a, $b ) {
							return ( intval( $a['total_submits'] ) > intval( $b['total_submits'] ) ) ? 1 : -1;
						}
					);
				}
				break;
			}
			case 'sdate': {
				if ( 'desc' === $order_direction_sanitized ) {
					usort(
						$result,
						function( $a, $b ) {
							return ( intval( strtotime( $b['start_date'] ) ) > intval( strtotime( $a['start_date'] ) ) ) ? 1 : -1;
						}
					);
				} else {
					usort(
						$result,
						function( $a, $b ) {
							return ( intval( strtotime( $a['start_date'] ) ) > intval( strtotime( $b['start_date'] ) ) ) ? 1 : -1;
						}
					);
				}
				break;
			}
			case 'edate': {
				if ( 'desc' === $order_direction_sanitized ) {
					usort(
						$result,
						function( $a, $b ) {
							return ( intval( strtotime( $b['end_date'] ) ) > intval( strtotime( $a['end_date'] ) ) ) ? 1 : -1;
						}
					);
				} else {
					usort(
						$result,
						function( $a, $b ) {
							return ( intval( strtotime( $a['end_date'] ) ) > intval( strtotime( $b['end_date'] ) ) ) ? 1 : -1;
						}
					);
				}
				break;
			}
			case 'adate': {
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
		return array_slice( $result, ( $page_number - 1 ) * $per_page, $per_page );
	}
	public static function record_count() {
		if ( true === isset( $_REQUEST['s'] ) ) {
			$_search_term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
			$_search_term = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $_search_term ) ) . '%';
			$sql = "SELECT COUNT(*) FROM {$GLOBALS['wpdb']->yop_poll_polls} WHERE `status` != 'deleted' AND `name` LIKE %s";
			$sql = $GLOBALS['wpdb']->prepare(
				$sql,
				$_search_term
			);
		} else {
			$sql = "SELECT COUNT(*) FROM {$GLOBALS['wpdb']->yop_poll_polls} WHERE `status` != 'deleted'";
		}
		return $GLOBALS['wpdb']->get_var( $sql );
	}
	public function no_items() {
		esc_html_e( 'No Polls Avalaible.', 'yop-poll' );
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
	public function column_name( $item ) {
		$title = '<strong>' . $item['name'] . '</strong>';
		$_token = wp_create_nonce( 'yop-poll-view-polls' );
		$actions = array(
			'edit'   => sprintf(
				'<a href="?page=%s&action=%s&poll_id=%s">' . esc_html__( 'Edit', 'yop-poll' ) . '</a>',
				'yop-polls',
				'edit',
				absint( $item['id'] )
			),
			'delete' => '<a href="#" class="yop-poll-delete-poll" data-id="' . esc_attr( absint( $item['id'] ) ) . '" data-token="' . esc_attr( $_token ) . '">' . esc_html__( 'Trash', 'yop-poll' ) . '</a>',
			'clone' => '<a href="#" class="yop-poll-clone-poll" data-id="' . esc_attr( absint( $item['id'] ) ) . '" data-token="' . esc_attr( $_token ) . '">' . esc_html__( 'Clone', 'yop-poll' ) . '</a>',
			'reset' => '<a href="#" class="yop-poll-reset-poll" data-id="' . esc_attr( absint( $item['id'] ) ) . '" data-token="' . esc_attr( $_token ) . '">' . esc_html__( 'Reset Votes', 'yop-poll' ) . '</a>',
		);
		return $title . $this->row_actions( $actions, false );
	}
	public function column_results( $item ) {
		return sprintf(
			'<a href="?page=%s&action=%s&poll_id=%s"><span class="dashicons dashicons-chart-bar"></span></a>',
			'yop-polls',
			'results',
			absint( $item['id'] )
		);
	}
	public function column_status( $item ) {
		return ucfirst( $item['status'] );
	}
	public function column_code( $item ) {
		return sprintf(
			'[yop_poll id="%s"]<span class="dashicons dashicons-admin-customizer yop-poll-get-shortcode" data-id="%s" title="' . esc_html__( 'Customize', 'yop-poll' ) . '"></span>',
			$item['id'],
			$item['id']
		);
	}
	public function column_votes( $item ) {
		return $item['total_submits'];
	}
	public function column_author( $item ) {
		$user_info = get_userdata( $item['author'] );
		return $user_info->user_login;
	}
	public function column_sdate( $item ) {
		$poll_meta = unserialize( $item['meta_data'] );
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		$start_date = '';
		if ( 'now' === $poll_meta['options']['poll']['startDateOption'] ) {
			$start_date = $item['added_date'];
		} else {
			if ( '' !== $poll_meta['options']['poll']['startDateCustom'] ) {
				$start_date = $poll_meta['options']['poll']['startDateCustom'];
			} else {
				$start_date = $item['added_date'];
			}
		}
		return date_i18n( $date_format . ' ' . $time_format, strtotime( $start_date ) );
	}
	public function column_edate( $item ) {
		$poll_meta = unserialize( $item['meta_data'] );
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		$end_date = '';
		if ( 'never' === $poll_meta['options']['poll']['endDateOption'] ) {
			$end_date = esc_html__( 'Never', 'yop-poll' );
		} else {
			if ( '' !== $poll_meta['options']['poll']['endDateCustom'] ) {
				$end_date = date_i18n(
					$date_format . ' ' . $time_format,
					strtotime( $poll_meta['options']['poll']['endDateCustom'] )
				);
			} else {
				$end_date = esc_html__( 'Never', 'yop-poll' );
			}
		}
		return $end_date;
	}
	public function get_columns() {
		$columns = array(
			'cb'               => '<input type="checkbox" />',
			'name'             => esc_html__( 'Name', 'yop-poll' ),
			'code'             => esc_html__( 'Shortcode', 'yop-poll' ),
			'status'           => esc_html__( 'Status', 'yop-poll' ),
			'results'          => esc_html__( 'Results', 'yop-poll' ),
			'votes'            => esc_html__( 'Votes', 'yop-poll' ),
			'author'           => esc_html__( 'Author', 'yop-poll' ),
			'sdate'            => esc_html__( 'Start Date', 'yop-poll' ),
			'edate'            => esc_html__( 'End Date', 'yop-poll' ),
		);
		return $columns;
	}
	public function get_sortable_columns() {
		$sortable_columns = array(
			'name' => array(
				'name',
				true,
			),
			'status' => array(
				'status',
				true,
			),
			'code' => array(
				'id',
				true,
			),
			'votes' => array(
				'votes',
				true,
			),
			'sdate' => array(
				'sdate',
				true,
			),
			'edate' => array(
				'edate',
				true,
			),
		);
		return $sortable_columns;
	}
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => esc_html__( 'Trash', 'yop-poll' ),
			'bulk-reset-votes' => esc_html__( 'Reset Votes', 'yop-poll' ),
		);
		return $actions;
	}
	public function get_bulk_actions_settings() {
		$button_settings = array(
			'_token' => wp_create_nonce( 'yop-poll-bulk-polls' ),
			'select-class' => 'yop-poll-polls-bulk-action-select',
			'button-text' => esc_html__( 'Apply', 'yop-poll' ),
			'button-class' => 'yop-poll-polls-bulk-action-button',
		);
		return $button_settings;
	}
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();
		$per_page     = $this->get_items_per_page( 'polls_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);
		$this->items = self::get_polls( $per_page, $current_page );
	}
}
