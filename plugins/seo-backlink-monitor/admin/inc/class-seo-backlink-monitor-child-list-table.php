<?php
/**
 * Administration API: WP_List_Table class
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 */

/**
 * Base class for displaying a list of items in an ajaxified HTML table.
 *
 * @since 3.1.0
 */

class SEO_Backlink_Monitor_Child_WP_List_Table extends SEO_Backlink_Monitor_Parent_WP_List_Table {

	private $plugin_name;

	public function __contruct( $plugin_name ) {
		// Set parent defaults
		parent::__construct(
			[
				'plural'   => 'links',
				'singular' => 'link',
				'ajax'     => true,
			]
		);
		$this->plugin_name = $plugin_name;
	}

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
				return $item[ $column_name ];
			case 'status':
			case 'linkTo':
			case 'linkFrom':
			case 'date':
				return SEO_Backlink_Monitor_Helper::return_formatted_by_type($column_name, $item[ $column_name ], $item);
			case 'follow':
			case 'dateRefresh':
			case 'anchorText':
				return SEO_Backlink_Monitor_Helper::return_combined_formatted_by_type($column_name, $item);
			case 'actions':
				return
					'<a href="'.admin_url('admin.php?page='.SEO_BLM_PLUGIN.'&edit=' . intval($item['id'])) . '" class="seo-blm-edit-link" title="' . esc_attr__('Edit and/or add Note', 'seo-backlink-monitor' ) . '">'
						. '<span class="dashicons dashicons-edit"></span>'
					.'</a> ' .
					'<a href="#" class="seo-blm-refresh-link" data-id="' . intval($item['id']) .'" title="' . esc_attr__('Refresh', 'seo-backlink-monitor' ) . '">'
					. '<span class="dashicons dashicons-update"></span>'
					.'</a> ' .
					'<a href="#" class="seo-blm-delete-link" data-id="' . intval($item['id']) .'" title="' . esc_attr__('Delete', 'seo-backlink-monitor' ) . '">'
					. '<span class="dashicons dashicons-trash"></span>'
					.'</a>';
			default:
				return print_r( $item, true );
		}
	}

	public function get_columns() {

		return [
			'dateRefresh' => __('Refreshed', 'seo-backlink-monitor' ),
			'date'        => __('Date', 'seo-backlink-monitor' ),
			'linkTo'      => __('Link To', 'seo-backlink-monitor' ),
			'linkFrom'    => __('Link From', 'seo-backlink-monitor' ),
			'anchorText'  => __('Anchor Text & Notes', 'seo-backlink-monitor' ),
			'follow'      => __('Follow', 'seo-backlink-monitor' ),
			'status'      => __('Status', 'seo-backlink-monitor' ),
			'actions'     => '',
		];
	}

	public function get_sortable_columns()
	{
		return [
			'dateRefresh' => [ 'dateRefresh', true ],
			'date'        => [ 'date', true ],
			'linkTo'      => [ 'linkTo', true ],
			'linkFrom'    => [ 'linkFrom', true ],
			'anchorText'  => [ 'anchorText', true ],
			'follow'      => [ 'follow', true ],
			'status'      => [ 'status', true ],
		];
	}

	public function prepare_items() {
		global $wpdb;
		$settings = get_option(SEO_BLM_OPTION_SETTINGS);
		$ADMIN = new SEO_Backlink_Monitor_Admin();
		if (!$settings) {
			$settings = $ADMIN->get_settings_default();
		} elseif (!isset($settings['resultItemsPerPage'])) {
			$settings['resultItemsPerPage'] = $ADMIN->get_settings_default()['resultItemsPerPage'];
		}
		$per_page = intval($settings['resultItemsPerPage']);
		$columns = $this->get_columns();
		$hidden = [];
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$this->process_bulk_action();
		$data = ( get_option( SEO_BLM_OPTION_LINKS ) ) ? get_option( SEO_BLM_OPTION_LINKS ) : false;
		$search_data = [];
		$main_search_data = [];

		if( $data ) {
			if ( isset( $_REQUEST['search_field'] ) && strlen($_REQUEST['search_field']) > 0 && isset( $_REQUEST['search_column'] ) && strlen($_REQUEST['search_column']) > 0 && ! isset( $_REQUEST['deletion_id'] ) ) {
				$search_text = sanitize_text_field( $_REQUEST['search_field'] );
				$search_column = sanitize_text_field( $_REQUEST['search_column'] );
				foreach ( $data as $key => $value ) {
					if (isset($value[$search_column]) && strpos( strtolower($value[$search_column]), strtolower($search_text) ) !== false) {
						array_push( $search_data, $value );
					}
				}
				$main_search_data = $search_data;
			} elseif ( isset( $_REQUEST['deletion_id'] ) && ! empty( $_REQUEST['deletion_id'] ) ) {
				$deletion_id = sanitize_text_field( $_REQUEST['deletion_id'] );
				foreach ( $data as $key => $value ) {
					if ( (int) $value['id'] === (int) trim($deletion_id) ) {
						unset( $data[ $key ] );
					}
				}
				update_option( SEO_BLM_OPTION_LINKS, $data );
				$main_search_data = $data;
			} else {
				$main_search_data = $data;
			}
		}

		function usort_reorder( $a, $b ) {
			$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'date';
			$order = ( ! empty( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'asc';
			$result = strcmp( $a[ $orderby ], $b[ $orderby ] );
			return ( 'asc' === $order ) ? $result : -$result;
		}
		if( $main_search_data ) {
			usort( $main_search_data, 'usort_reorder' );
		}

		$current_page = $this->get_pagenum();
		$total_items = count( $main_search_data );

		if ( $main_search_data ) {
			$main_search_data = array_slice( $main_search_data,( ( $current_page-1 ) * $per_page ), $per_page );
		}

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
				'orderby'     => !empty( $_REQUEST['orderby'] ) && '' != $_REQUEST['orderby'] ? $_REQUEST['orderby']: 'date',
				'order'       => !empty( $_REQUEST['order'] ) && '' != $_REQUEST['order'] ?     $_REQUEST['order']:   'asc'
			]
		);
		$this->items = $main_search_data;
	}

	public function display() {
		echo '<input type="hidden" id="order" name="order" value="' . $this->_pagination_args['order'] . '" />';
		echo '<input type="hidden" id="orderby" name="orderby" value="' . $this->_pagination_args['orderby'] . '" />';
		parent::display();
	}

	public function ajax_response() {

		check_ajax_referer( 'seo-blm-ajax-custom-list-nonce', 'seo_blm_ajax_custom_list_nonce' );

		$this->prepare_items();

		extract( $this->_args );
		extract( $this->_pagination_args, EXTR_SKIP );

		ob_start();
		if ( ! empty( $_REQUEST['no_placeholder'] ) )
			$this->display_rows();
		else
			$this->display_rows_or_placeholder();
		$rows = ob_get_clean();

		ob_start();
		$this->print_column_headers();
		$headers = ob_get_clean();

		ob_start();
		$this->pagination('top');
		$pagination_top = ob_get_clean();

		ob_start();
		$this->pagination('bottom');
		$pagination_bottom = ob_get_clean();

		$response = [
			'rows' => $rows
		];
		$response['pagination']['top'] = $pagination_top;
		$response['pagination']['bottom'] = $pagination_bottom;
		$response['column_headers'] = $headers;

		if ( isset( $total_items ) )
			$response['total_items_i18n'] = sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) );

		if ( isset( $total_pages ) ) {
			$response['total_pages'] = $total_pages;
			$response['total_pages_i18n'] = number_format_i18n( $total_pages );
		}

		wp_die( json_encode( $response ) );
	}
}
