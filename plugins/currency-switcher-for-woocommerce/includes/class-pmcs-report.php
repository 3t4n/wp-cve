<?php

class PMCS_Report {
	protected $last_data = array();
	protected $woocommerce_currencies = array();
	protected $order_currency_codes = array();
	protected $woocommerce_currency = '';
	protected $currency = '';
	protected $dashboard_report = null;

	public function __construct() {
		global $wpdb;
		$order_currency_codes = array();
		$sql = "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_order_currency' ORDER BY meta_value ASC";
		$results = $wpdb->get_results( $sql, ARRAY_A ); // @codingStandardsIgnoreLine
		foreach ( $results as $key => $r ) {
			$order_currency_codes[ $r['meta_value'] ] = $r['meta_value']; // @codingStandardsIgnoreLine
		}

		$this->order_currency_codes = $order_currency_codes;

		if ( isset( $_GET['currency'] ) ) {
			$this->currency = sanitize_text_field( wp_unslash( $_GET['currency'] ) );
			update_option( 'pmcs_report_admin_currency', $this->currency );
		} else {
			$this->currency = get_option( 'pmcs_report_admin_currency' );
		}

		if ( ! isset( $order_currency_codes[ $this->currency ] ) ) {
			$this->currency = key( $order_currency_codes );
		}

		add_filter( 'woocommerce_reports_get_order_report_data_args', array( $this, 'get_data' ) );
		add_filter( 'woocommerce_reports_get_order_report_query', array( $this, 'query' ) );
		add_action( 'wc_reports_tabs', array( $this, 'add_tab' ) );

		add_filter( 'wc_price_args', array( $this, 'filter_price_args' ), 95 );
		add_action( 'woocommerce_after_dashboard_status_widget', array( $this, 'woocommerce_after_dashboard_status_widget' ) );

	}

	/**
	 * Get sales report data.
	 *
	 * @return object
	 */
	private function get_sales_report_data() {
		include_once WC_ABSPATH . 'includes/admin/reports/class-wc-report-sales-by-date.php';

		$sales_by_date                 = new WC_Report_Sales_By_Date();
		$sales_by_date->start_date     = strtotime( date( 'Y-m-01', current_time( 'timestamp' ) ) );
		$sales_by_date->end_date       = current_time( 'timestamp' );
		$sales_by_date->chart_groupby  = 'day';
		$sales_by_date->group_by_query = 'YEAR(posts.post_date), MONTH(posts.post_date), DAY(posts.post_date)';

		return $sales_by_date->get_report_data();
	}

	public function woocommerce_after_dashboard_status_widget( $reports ) {

		$report_data = $this->dashboard_report;

		foreach ( $this->order_currency_codes as $code ) {
			$this->currency = $code;
			update_option( 'pmcs_report_admin_currency', $code );
			add_filter( 'wc_price_args', array( $this, 'filter_price_args' ), 95 );
			$_report_data = $this->get_sales_report_data();
			?>
			<li class="sales-this-month pmcs-report-li pmcs-currency-<?php echo esc_attr( $code ); ?>">
			<a href="<?php echo admin_url( 'admin.php?page=wc-reports&tab=orders&range=month&currency=' . $code ); ?>">
				<?php echo $reports->sales_sparkline( '', max( 7, date( 'd', current_time( 'timestamp' ) ) ) ); ?>
				<?php
					/* translators: %s: net sales */
					printf(
						__( '%s net sales this month', 'woocommerce' ),
						'<strong>' . wc_price( $_report_data->net_sales ) . '</strong>'
					);
				?>
				</a>
			</li>
			<?php
		}
	}

	public function filter_dashboard_report_data( $data ) {
		$this->dashboard_report = $data;
		$data = false;
		return $data;
	}

	public function filter_price_args( $args ) {
		$args['currency'] = $this->currency;
		return $args;
	}

	public function add_tab() {
		$link = admin_url( 'admin.php?page=wc-reports&tab=orders' );

		$names = get_woocommerce_currencies();
		?>
		<span class="nav-tab-- pmsc-report-tab">
			<span class="label"><?php printf( __( 'Reporting in %s', 'pmcs' ), $names[ $this->currency ] ); ?></span>
			<ul>
			<?php
			foreach ( $this->order_currency_codes as $code ) {
				$url = add_query_arg( array( 'currency' => $code ), $link );
				?>
				<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $code . ' - ' . $names[ $code ] ); ?></a></li>
			<?php } ?>
			</ul>
		</span>
		<?php
	}
	public function get_data( $data ) {
		$this->last_data = $data;
		return $data;
	}

	public function query( $query ) {
		global $wpdb;
		// {$wpdb->postmeta}
		// var_dump( $this->last_data );
		// var_dump( $query );
		$query['join'] .= " INNER JOIN {$wpdb->postmeta} as pmcs_currency ON ( posts.id = pmcs_currency.post_id AND pmcs_currency.meta_key = '_order_currency' )";
		$code = esc_sql( $this->currency );
		$query['where'] .= "  AND pmcs_currency.meta_value = '{$code}' ";
		return $query;
	}
}


function pmcs_report_init() {
	new PMCS_Report();
}
add_action( 'load-index.php', 'pmcs_report_init' );
add_action( 'load-woocommerce_page_wc-reports', 'pmcs_report_init' );


