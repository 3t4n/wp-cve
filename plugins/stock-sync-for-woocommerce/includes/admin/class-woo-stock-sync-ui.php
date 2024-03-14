<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Stock_Sync_Ui {
	private $_pagination_args;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register page
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
	}

	/**
	 * Add pages
	 */
	public function admin_menu() {
		add_submenu_page(
			'woocommerce',
			__( 'Stock Sync', 'woo-stock-sync' ),
			__( 'Stock Sync', 'woo-stock-sync' ),
			'manage_woocommerce',
			'woo-stock-sync-report',
			array( $this, 'output' )
		);
	}

	/**
	 * Output page
	 */
	public function output() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : '';

		switch ( $action ) {
			case 'update':
				return $this->update();
			case 'push_all':
				return $this->push_all();
			case 'tools':
				return $this->tools();
			case 'log':
				return $this->log();
		}

		return $this->report();
	}

	/**
	 * Tools page
	 */
	public function tools() {
		$tabs = $this->tabs();
		$urls = $this->urls();

		include( __DIR__ . '/views/tools.html.php' );
	}

	/**
	 * Update page
	 */
	public function update() {
		$tabs = $this->tabs();
		$urls = $this->urls();
		$tabs['tools']['active'] = true;

		include( __DIR__ . '/views/update.html.php' );
	}

	/**
	 * Push all page
	 */
	public function push_all() {
		$tabs = $this->tabs();
		$urls = $this->urls();
		$tabs['tools']['active'] = true;

		include( __DIR__ . '/views/push-all.html.php' );
	}

	/**
	 * Log page
	 */
	public function log() {
		$tabs = $this->tabs();
		$urls = $this->urls();

		$sites = array_values( woo_stock_sync_sites() );
		$this->sites = $sites;

		$per_page = 20;

		$filter_by_product_id = false;
		if ( isset( $_GET['product_id'] ) && ( $filter_by_product = wc_get_product( $_GET['product_id'] ) ) ) {
			$filter_by_product_id = $filter_by_product->get_id();
		}

		$log_types = [
			'' => __( 'All logs', 'woo-stock-sync' ),
			'errors_warnings' => __( 'Errors & warnings', 'woo-stock-sync' ),
			'errors' => __( 'Errors', 'woo-stock-sync' ),
		];

		$log_type = isset( $_GET['log_type'] ) && isset( $log_types[$_GET['log_type']] ) ? $_GET['log_type'] : '';

		$log_level = 0;
		switch ( $log_type ) {
			case 'errors':
				$log_level = 2;
				break;
			case 'errors_warnings':
				$log_level = 1;
				break;
		}

		$results = Woo_Stock_Sync_Logger::get_all( $this->get_pagenum(), $per_page, $filter_by_product_id, $log_level );

		$logs = $results->logs;
		$total_items = $results->total;
		$pages = $results->max_num_pages;

		// Log table nag
		$log_table_exists = Woo_Stock_Sync_Logger::table_exists();

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page' => $per_page,
			'total_pages' => $pages,
		) );
		
		$pagination = $this->pagination();

		include( __DIR__ . '/views/log.html.php' );
	}

	/**
	 * Report page
	 */
	public function report() {
		$tabs = $this->tabs();
		$urls = $this->urls();

		$sites = woo_stock_sync_sites();
		$this->sites = $sites;

		$query = wss_product_query();
		$query->set( 'page', 1 );
		$query->set( 'limit', 100 );
		$query->set( 'paginate', false );

		do_action( 'woo_stock_sync_report_query', $query, $this );

		$search_query = '';
		if ( isset( $_GET['wss_product_search'] ) && ! empty( trim( $_GET['wss_product_search'] ) ) ) {
			$data_store = WC_Data_Store::load( 'product' );
			$search_product_ids = $data_store->search_products( $_GET['wss_product_search'] );

			$search_query = $_GET['wss_product_search'];
			$query->set( 'include', $search_product_ids );
		}

		$results = $query->get_products();

		$products = $query->get( 'paginate' ) ? $results->products : $results;
		$total_items = $query->get( 'paginate' ) ? $results->total : count( $results );
		$pages = $query->get( 'paginate' ) ? $results->max_num_pages : 1;

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page' => $query->get( 'limit' ),
			'total_pages' => $pages,
		) );
		
		$products_with_children = array();
		foreach ( $products as $key => $product ) {
			$products_with_children[] = $product;

			if ( $product->get_type() === 'variable' ) {
				foreach ( $product->get_children() as $children ) {
					$children = wc_get_product( $children );
					if ( ! $children || ! $children->exists() ) {
						continue;
					}

					$products_with_children[] = $children;
				}
			}
		}

		// Check when the last update was done
		$last_updated = get_option( 'woo_stock_sync_last_updated', null );
		if ( ! $last_updated ) {
			$last_updated = __( 'never', 'woo-stock-sync' );
		} else {
			$last_updated = sprintf( __( '%s ago (%s)', 'woo-stock-sync' ), human_time_diff( $last_updated ), wss_format_datetime( $last_updated ) );
		}
		
		$products_json = array_map( 'wss_product_to_json', $products_with_children );

		// Show error msg if there is more than 100 products
		$product_count = (array) wp_count_posts( 'product' );
		$product_count = array_sum( $product_count );
		$limit_reached = $product_count > apply_filters( 'woo_stock_sync_report_limit', 100 );

		$pagination = $this->pagination();

		include( __DIR__ . '/views/report.html.php' );
	}

	/**
	 * An internal method that sets all the necessary pagination arguments
	 */
	protected function set_pagination_args( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'total_items' => 0,
				'total_pages' => 0,
				'per_page'    => 0,
			)
		);

		if ( ! $args['total_pages'] && $args['per_page'] > 0 ) {
			$args['total_pages'] = ceil( $args['total_items'] / $args['per_page'] );
		}

		// Redirect if page number is invalid and headers are not already sent.
		if ( ! headers_sent() && ! wp_doing_ajax() && $args['total_pages'] > 0 && $this->get_pagenum() > $args['total_pages'] ) {
			wp_redirect( add_query_arg( 'paged', $args['total_pages'] ) );
			exit;
		}

		$this->_pagination_args = $args;
	}

	/**
	 * Output pagination
	 */
	public function pagination() {
		if ( empty( $this->_pagination_args ) ) {
			return;
		}

		$total_items = $this->_pagination_args['total_items'];
		$total_pages = $this->_pagination_args['total_pages'];

		$output = '<span class="displaying-num">' . sprintf(
			/* translators: %s: Number of items. */
			_n( '%s item', '%s items', $total_items ),
			number_format_i18n( $total_items )
		) . '</span>';

		$current = $this->get_pagenum();
		$removable_query_args = wp_removable_query_args();

		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$current_url = remove_query_arg( $removable_query_args, $current_url );

		$page_links = [];

		$total_pages_before = '<span class="paging-input">';
		$total_pages_after  = '</span></span>';

		$disable_first = false;
		$disable_last  = false;
		$disable_prev  = false;
		$disable_next  = false;

		if ( 1 == $current ) {
			$disable_first = true;
			$disable_prev  = true;
		}
		if ( 2 == $current ) {
			$disable_first = true;
		}
		if ( $total_pages == $current ) {
			$disable_last = true;
			$disable_next = true;
		}
		if ( $total_pages - 1 == $current ) {
			$disable_last = true;
		}

		if ( $disable_first ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='first-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( remove_query_arg( 'paged', $current_url ) ),
				__( 'First page' ),
				'&laquo;'
			);
		}

		if ( $disable_prev ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='prev-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', max( 1, $current - 1 ), $current_url ) ),
				__( 'Previous page' ),
				'&lsaquo;'
			);
		}

		$html_current_page = sprintf(
			"%s<input class='current-page' id='current-page-selector' type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' /><span class='tablenav-paging-text'>",
			'<label for="current-page-selector" class="screen-reader-text">' . __( 'Current Page' ) . '</label>',
			$current,
			strlen( $total_pages )
		);

		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
		$page_links[] = $total_pages_before . sprintf(
			/* translators: 1: Current page, 2: Total pages. */
			_x( '%1$s of %2$s', 'paging' ),
			$html_current_page,
			$html_total_pages
		) . $total_pages_after;

		if ( $disable_next ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='next-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', min( $total_pages, $current + 1 ), $current_url ) ),
				__( 'Next page' ),
				'&rsaquo;'
			);
		}

		if ( $disable_last ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='last-page button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
				__( 'Last page' ),
				'&raquo;'
			);
		}

		$pagination_links_class = 'pagination-links';
		$output .= "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';

		if ( $total_pages ) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}
		$this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

		return $this->_pagination;
	}

	/**
	 * Gets the current page number.
	 */
	public function get_pagenum() {
		$pagenum = isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 0;

		if ( isset( $this->_pagination_args['total_pages'] ) && $pagenum > $this->_pagination_args['total_pages'] ) {
			$pagenum = $this->_pagination_args['total_pages'];
		}

		return max( 1, $pagenum );
	}

	/**
	 * URLs to common tasks
	 */
	private function urls() {
		return [
			'update' => add_query_arg( [
				'page' => 'woo-stock-sync-report',
				'action' => 'update',
			], admin_url( 'admin.php' ) ),
			'push_all' => add_query_arg( [
				'page' => 'woo-stock-sync-report',
				'action' => 'push_all',
			], admin_url( 'admin.php' ) ),
			'report' => add_query_arg( [
				'page' => 'woo-stock-sync-report',
				'action' => '',
			], admin_url( 'admin.php' ) ),
		];
	}

	/**
	 * Tabs
	 */
	private function tabs() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : '';

		$tabs = [];

		// Products
		$tabs['products'] = [
			'title' => __( 'Products', 'woo-stock-sync' ),
			'url' => add_query_arg( [
				'page' => 'woo-stock-sync-report',
				'action' => '',
			], admin_url( 'admin.php' ) ),
			'active' => empty( $action ),
		];

		// Log
		$tabs['log'] = [
			'title' => __( 'Log', 'woo-stock-sync' ),
			'url' => add_query_arg( [
				'page' => 'woo-stock-sync-report',
				'action' => 'log',
			], admin_url( 'admin.php' ) ),
			'active' => ( $action === 'log' ),
		];

		// Tools
		$tabs['tools'] = [
			'title' => __( 'Tools', 'woo-stock-sync' ),
			'url' => add_query_arg( [
				'page' => 'woo-stock-sync-report',
				'action' => 'tools',
			], admin_url( 'admin.php' ) ),
			'active' => ( $action === 'tools' ),
		];

		// Settings
		$tabs['settings'] = [
			'title' => __( 'Settings', 'woo-stock-sync' ),
			'url' => admin_url( 'admin.php?page=wc-settings&tab=woo_stock_sync' ),
			'active' => false,
		];

		return $tabs;
	}
}
