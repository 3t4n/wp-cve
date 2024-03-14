<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Extends core PMS_Submenu_Page base class to create and add custom functionality
 * for the reports section in the admin section
 *
 */
Class PMS_Submenu_Page_Reports extends PMS_Submenu_Page {

    /*
     * The start date to filter results
     *
     * @var string
     *
     */
    public $start_date;


    /*
     * The end date to filter results
     *
     * @var string
     *
     */
    public $end_date;


    /*
     * Array of payments retrieved from the database given the user filters
     *
     * @var array
     *
     */
    public $queried_payments = array();


    /*
     * Array with the formatted results ready for chart.js usage
     *
     * @var array
     *
     */
    public $results = array();


    /*
     * Method that initializes the class
     *
     */
    public function init() {

        // Enqueue admin scripts
        add_action( 'pms_submenu_page_enqueue_admin_scripts_before_' . $this->menu_slug, array( $this, 'admin_scripts' ) );

        // Hook the output method to the parent's class action for output instead of overwriting the
        // output method
        add_action( 'pms_output_content_submenu_page_' . $this->menu_slug, array( $this, 'output' ) );

        // Process different actions within the page
        add_action( 'init', array( $this, 'process_data' ) );

        // Output filters
        add_action( 'pms_reports_filters', array( $this, 'output_filters' ) );

        // Period reports table
        add_action( 'pms_reports_page_bottom', array( $this, 'output_reports_table' ) );

        add_action( 'admin_print_footer_scripts', array( $this, 'output_chart_js_data' ) );

    }


    /*
     * Method to enqueue admin scripts
     *
     */
    public function admin_scripts() {

        wp_enqueue_script( 'pms-chart-js', PMS_PLUGIN_DIR_URL . 'assets/js/admin/libs/chart/chart.min.js' );

    }


    /*
     * Method that processes data on reports admin pages
     *
     */
    public function process_data() {

        // Get current actions
        $action = !empty( $_REQUEST['pms-action'] ) ? sanitize_text_field( $_REQUEST['pms-action'] ) : '';

        // Get default results if no filters are applied by the user
        if( empty($action) && !empty( $_REQUEST['page'] ) && $_REQUEST['page'] == 'pms-reports-page' ) {

            $this->queried_payments = $this->get_filtered_payments();

            $results = $this->prepare_payments_for_output( $this->queried_payments );

        } else {

            // Verify correct nonce
            if( !isset( $_REQUEST['_wpnonce'] ) || !wp_verify_nonce( sanitize_text_field( $_REQUEST['_wpnonce'] ), 'pms_reports_nonce' ) )
                return;

            // Filtering results
            if( $action == 'filter_results' ) {

                $this->queried_payments = $this->get_filtered_payments();

                $results = $this->prepare_payments_for_output( $this->queried_payments );

            }

        }

        if( !empty( $results ) )
            $this->results = $results;

    }


    /*
     * Return an array of payments payments depending on the user's input filters
     *
     * @return array
     *
     */
    private function get_filtered_payments() {

        if( isset( $_REQUEST['pms-filter-time'] ) && $_REQUEST['pms-filter-time'] == 'custom' && !empty( $_REQUEST['pms-filter-time-start-date'] ) && !empty( $_REQUEST['pms-filter-time-end-date'] ) ){

            $this->start_date = sanitize_text_field( $_REQUEST['pms-filter-time-start-date'] );
            $this->end_date   = sanitize_text_field( $_REQUEST['pms-filter-time-end-date'] ) . ' 23:59:59';

        } else {

            if( empty( $_REQUEST['pms-filter-time'] ) || $_REQUEST['pms-filter-time'] == 'current_month' )
                $date = date("Y-m");
            else
                $date = sanitize_text_field( $_REQUEST['pms-filter-time'] );

            $date_time        = new DateTime( $date );
            $month_total_days = $date_time->format( 't' );

            $this->start_date = $date . '-01';
            $this->end_date   = $date . '-' . $month_total_days . ' 23:59:59';

        }

        $args = apply_filters( 'pms_reports_get_filtered_payments_args', array( 'status' => 'completed', 'date' => array( $this->start_date, $this->end_date ), 'order' => 'ASC', 'number' => '-1' ) );

        $payments = pms_get_payments( $args );

        return $payments;

    }


    /*
     * Get filtered results by date
     *
     * @param $start_date - has format Y-m-d
     * @param $end_date   - has format Y-m-d
     *
     * @return array
     *
     */
    private function prepare_payments_for_output( $payments = array() ) {

        $results = array();

        $first_day = new DateTime( $this->start_date );
        $first_day = $first_day->format('j');

        $last_day  = new DateTime( $this->end_date );
        $last_day  = $last_day->format('j');

        for( $i = $first_day; $i <= $last_day; $i++ ) {
            if( !isset( $results[$i] ) )
                $results[$i] = array( 'earnings' => 0, 'payments' => 0 );
        }

        if( !empty( $payments ) ) {
            foreach( $payments as $payment ) {
                $payment_date = new DateTime( $payment->date );

                $results[ $payment_date->format('j') ]['earnings'] += $payment->amount;
                $results[ $payment_date->format('j') ]['payments'] += 1;
            }
        }

        return apply_filters( 'pms_reports_get_filtered_results', $results, $this->start_date, $this->end_date );

    }


    /*
     * Method to output content in the custom page
     *
     */
    public function output() {
        $active_tab = 'pms-reports-page';
        include_once 'views/view-page-reports.php';

    }


    /*
     * Outputs the input filter's the admin has at his disposal
     *
     */
    public function output_filters() {

        echo '<label class="cozmoslabs-form-field-label" for="pms-reports-filter-month">' . esc_html__( 'Select Month', 'paid-member-subscriptions' ) . '</label>';

        echo '<select name="pms-filter-time" id="pms-reports-filter-month">';

            echo '<option value="current_month">' . esc_html__( 'Current month', 'paid-member-subscriptions' ) . '</option>';

            for ($i = 1; $i <= 12; $i++) {
                $month = date("Y-m", strtotime( date( 'Y-m-01' ) . " -$i months"));
                echo '<option value="' . esc_attr( $month ) . '" ' . ( !empty( $_GET['pms-filter-time'] ) ? selected( $month, sanitize_text_field( $_GET['pms-filter-time'] ), false ) : '' ) . '>' . esc_html( date( 'F', strtotime( $month ) ) ) . ' ' . esc_html( date( 'Y', strtotime( $month ) ) ) . '</option>';
            }

        echo '</select>';

    }


    /*
     * Outputs a summary with the payments and earnings for the selected period
     *
     */
    public function output_reports_table() {

        $payments_count  = count( $this->queried_payments );
        $payments_amount = 0;

        if( !empty( $this->queried_payments ) ) {
            foreach( $this->queried_payments as $payment )
                $payments_amount += $payment->amount;
        }

        echo '<div class="postbox cozmoslabs-form-subsection-wrapper">';
        echo '<h4 class="cozmoslabs-subsection-title">' . esc_html__( 'Summary', 'paid-member-subscriptions' ) . '</h4>';
            echo '<div class="inside">';

                echo '<div class="cozmoslabs-form-field-wrapper">';
                    echo '<label class="pms-form-field-label cozmoslabs-form-field-label" for="pms-reports-total-earnings">' . esc_html__( 'Total Earnings', 'paid-member-subscriptions' ) . '</label>';
                    echo '<input id="pms-reports-total-earnings" type="text" value="' . esc_html( pms_format_price( $payments_amount, pms_get_active_currency() ) ) . '" disabled />';
                    echo '<p class="cozmoslabs-description cozmoslabs-description-align-right">' . esc_html__( 'Total earnings for the selected period', 'paid-member-subscriptions' ) . '</p>';
                echo '</div>';

                echo '<div class="cozmoslabs-form-field-wrapper">';
                    echo '<label class="pms-form-field-label cozmoslabs-form-field-label" for="pms-reports-total-payments">' . esc_html__( 'Total Payments', 'paid-member-subscriptions' ) . '</label>';
                    echo '<input id="pms-reports-total-payments" type="text" value="' . esc_html( $payments_count ) . '" disabled />';
                    echo '<p class="cozmoslabs-description cozmoslabs-description-align-right">' . esc_html__( 'Total number of payments for the selected period', 'paid-member-subscriptions' ) . '</p>';
                echo '</div>';

            echo '</div>';
        echo '</div>';

    }


    /*
     * Output the javascript data as variables
     *
     */
    public function output_chart_js_data() {

        if( empty( $this->results ) )
            return;

        $results = $this->results;

        // Generate chart labels
        $chart_labels_js_array = $data_set_earnings_js_array = $data_set_payments_js_array = array();

        foreach( $results as $key => $details ) {

            $chart_labels_js_array[] = $key;
            $data_set_earnings_js_array[] = $details['earnings'];
            $data_set_payments_js_array[] = $details['payments'];

        }

        // Start ouput
        echo '<script type="text/javascript">';

            echo 'var pms_currency = "' . esc_html( html_entity_decode( pms_get_currency_symbol( pms_get_active_currency() ) ) ) . '";';

            echo 'var pms_chart_labels = ' . wp_json_encode( $chart_labels_js_array ) . ';';
            echo 'var pms_chart_earnings = ' . wp_json_encode( $data_set_earnings_js_array ) . ';';
            echo 'var pms_chart_payments = ' . wp_json_encode( $data_set_payments_js_array ) . ';';

        echo '</script>';

    }

}

global $pms_submenu_page_reports;

$pms_submenu_page_reports = new PMS_Submenu_Page_Reports( 'paid-member-subscriptions', __( 'Reports', 'paid-member-subscriptions' ), __( 'Reports', 'paid-member-subscriptions' ), 'manage_options', 'pms-reports-page', 20 );
$pms_submenu_page_reports->init();
