<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WP3CXW_Webinar_Form_List_Table extends WP_List_Table {

	public static function define_columns() {
		$columns = array(
			'title' => __( 'Title', '3cx-webinar' ),
			'shortcode' => __( 'Shortcode', '3cx-webinar' ),
			'author' => __( 'Author', '3cx-webinar' ),
			'date' => __( 'Date', '3cx-webinar' ),
		);

		return $columns;
	}

	function __construct() {
		parent::__construct( array(
			'singular' => 'post',
			'plural' => 'posts',
			'ajax' => false,
		) );
	}

	function prepare_items() {
		$per_page = $this->get_items_per_page( 'wp3cxw_webinar_forms_per_page' );

		$this->_column_headers = $this->get_column_info();

		$args = array(
			'posts_per_page' => $per_page,
			'orderby' => 'title',
			'order' => 'ASC',
			'offset' => ( $this->get_pagenum() - 1 ) * $per_page,
		);

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if ( 'title' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'title';
			} elseif ( 'author' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'author';
			} elseif ( 'date' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'date';
			}
		}

		if ( ! empty( $_REQUEST['order'] ) ) {
			if ( 'asc' == strtolower( $_REQUEST['order'] ) ) {
				$args['order'] = 'ASC';
			} elseif ( 'desc' == strtolower( $_REQUEST['order'] ) ) {
				$args['order'] = 'DESC';
			}
		}

		$this->items = WP3CXW_WebinarForm::find( $args );

		$total_items = WP3CXW_WebinarForm::count();
		$total_pages = ceil( $total_items / $per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $per_page,
		) );
	}

	function get_columns() {
		return get_column_headers( get_current_screen() );
	}

	function get_sortable_columns() {
		$columns = array(
			'title' => array( 'title', true ),
			'author' => array( 'author', false ),
			'date' => array( 'date', false ),
		);

		return $columns;
	}

	function get_bulk_actions() {
		return array();
	}

	function column_default( $item, $column_name ) {
		return '';
	}

	function column_title( $item ) {
		$url = admin_url( 'admin.php?page=wp3cxw&post=' . absint( $item->id() ) );
		$edit_link = add_query_arg( array( 'action' => 'edit' ), $url );

		$output = sprintf(
			'<strong><a class="row-title" href="%s" title="%s">%s</a></strong>',
			esc_url( $edit_link ),
			/* translators: %s: title of Webinar form */
			esc_attr( sprintf( __( 'Edit %s', '3cx-webinar' ), '&#8220;'.$item->title().'&#8221;' ) ), esc_html( $item->title() )
		);

		if (current_user_can( 'wp3cxw_edit_webinar_form', $item->id() ) ) {
			$config_validator = new WP3CXW_ConfigValidator( $item );
			$config_validator->restore();

			if ( $count_errors = $config_validator->count_errors() ) {
				$error_notice = sprintf(
					/* translators: %s: number of errors detected */
					_n(
						'%s configuration error detected',
						'%s configuration errors detected',
						$count_errors, '3cx-webinar' ),
					number_format_i18n( $count_errors ) );
				$output .= sprintf(
					'<div class="config-error"><span class="dashicons dashicons-warning" aria-hidden="true"></span> %s</div>',
					$error_notice );
			}
		}

		$actions = array(
			'edit' => sprintf( '<a href="%1$s">%2$s</a>',
				esc_url( $edit_link ),
				esc_html( __( 'Edit', '3cx-webinar' ) ) ) );

		if ( current_user_can( 'wp3cxw_edit_webinar_form', $item->id() ) ) {
			$copy_link = wp_nonce_url(
				add_query_arg( array( 'action' => 'copy' ), $url ),
				'wp3cxw-copy-webinar-form_' . absint( $item->id() ) );

			$actions = array_merge( $actions, array(
				'copy' => sprintf( '<a href="%1$s">%2$s</a>',
					esc_url( $copy_link ),
					esc_html( __( 'Duplicate', '3cx-webinar' ) )
				),
			) );
		}
		
		if ( current_user_can( 'wp3cxw_delete_webinar_form', $item->id() ) ) {
			$delete_link = wp_nonce_url(
				add_query_arg( array( 'action' => 'delete' ), $url ),
				'wp3cxw-delete-webinar-form_' . absint( $item->id() ) );

			$actions = array_merge( $actions, array(
				'delete' => sprintf( '<a href="%1$s">%2$s</a>',
					esc_url( $delete_link ),
					esc_html( __( 'Delete', '3cx-webinar' ) )
				),
			) );
		}		

		$output .= $this->row_actions( $actions );

		return $output;
	}

	function column_author( $item ) {
		$post = get_post( $item->id() );

		if ( ! $post ) {
			return;
		}

		$author = get_userdata( $post->post_author );

		if ( false === $author ) {
			return;
		}

		return esc_html( $author->display_name );
	}

	function column_shortcode( $item ) {
		$shortcodes = array( $item->shortcode() );

		$output = '';

		foreach ( $shortcodes as $shortcode ) {
			$output .= "\n" . '<span class="shortcode"><input type="text"'
				. ' onfocus="this.select();" readonly="readonly"'
				. ' value="' . esc_attr( $shortcode ) . '"'
				. ' class="large-text code" /></span>';
		}

		return trim( $output );
	}

	function column_date( $item ) {
		$post = get_post( $item->id() );

		if ( ! $post ) {
			return;
		}

		$t_time = mysql2date( get_option( 'date_format' ).' '.get_option( 'time_format' ), $post->post_date, true );
		$m_time = $post->post_date;
		$time = mysql2date( 'G', $post->post_date ) - get_option( 'gmt_offset' ) * 3600;
		$time_diff = time() - $time;
		if ( $time_diff > 0 && $time_diff < 24*60*60 ) {
			/* translators: %s: time since the creation of the Webinar form */
			$h_time = sprintf(__( '%s ago', '3cx-webinar' ), human_time_diff( $time ) );
		} else {
			$h_time = mysql2date( get_option( 'date_format' ), $m_time );
		}
		return '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
	}
}
