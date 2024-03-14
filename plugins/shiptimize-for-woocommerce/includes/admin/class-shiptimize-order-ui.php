<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

require_once(SHIPTIMIZE_PLUGIN_PATH.'/includes/class-woo-shiptimize-order.php');
include_once(WP_PLUGIN_DIR.'/woocommerce/woocommerce.php');

/**
 * this class handles the ui actions for order management
 *
 *
 * @package Shiptimize.admin
 * @since   1.0.0
 */
class ShiptimizeOrderUI {

    public function __construct() {
        $this->add_filters();
        $this->add_actions();

    }

    /**
     * adds columns to the order view
     * adds buttons to export to shiptimize
     * https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns
     * columns available since wp 3.1
     *
     * @return void
     */
    public function add_filters(){

        if (OrderUtil::custom_orders_table_usage_is_enabled()) {
            add_filter( 'manage_woocommerce_page_wc-orders_columns', array($this,'order_columns') );
            add_filter( 'posts_join', array($this, 'query_filter_order_status_join') );
            add_filter( 'posts_where', array($this, 'query_filter_order_status_where') );
            add_filter( 'bulk_actions-woocommerce_page_wc-orders', array( $this, 'register_bulk_export' ) );
            add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', array( $this, 'handle_bulk' ), 10, 3 );
        } else {
            add_filter( 'manage_edit-shop_order_columns', array($this,'order_columns') );
            add_filter( 'posts_join', array($this, 'query_filter_order_status_join') );
            add_filter( 'posts_where', array($this, 'query_filter_order_status_where') );
            add_filter( 'bulk_actions-edit-shop_order', array( $this, 'register_bulk_export' ) );
            add_filter( 'handle_bulk_actions-edit-shop_order', array( $this, 'handle_bulk' ), 10, 3 );
        }

    }

    /**
     * WP actions for the order ui
     * @return void
     */
    public function add_actions(){

        if (OrderUtil::custom_orders_table_usage_is_enabled()) {
            add_action( 'manage_woocommerce_page_wc-orders_custom_column', array($this,'order_column_values_hpos'),25,2);
            add_action( 'restrict_manage_posts' , array($this, 'action_filter_order_status') );
            add_action( 'admin_head', array( $this, 'custom_js_to_head') );
            add_action( 'admin_init', array($this, 'admin_init'));
            add_action( 'wp_ajax_shiptimize_print_label', array($this, 'ajax_print_label') );
            add_action( 'wp_ajax_shiptimize_label_status', array($this, 'ajax_monitor_label_status') );
        }else{
            add_action( 'manage_shop_order_posts_custom_column', array($this,'order_column_values'));
            add_action( 'restrict_manage_posts' , array($this, 'action_filter_order_status') );
            add_action( 'admin_head', array( $this, 'custom_js_to_head') );
            add_action( 'admin_init', array($this, 'admin_init'));
            add_action( 'wp_ajax_shiptimize_print_label', array($this, 'ajax_print_label') );
            add_action( 'wp_ajax_shiptimize_label_status', array($this, 'ajax_monitor_label_status') );
        }

    }

    /**
     *
     */
    public function ajax_print_label() {
        $orderid = $_POST['orderid'];

        if (!$orderid) {
            die(json_encode(array('Error' => 'No order id, cannot print label')));
        }

        $labelResponse = json_encode(WooShiptimizeOrder::print_label(array($orderid)));
//    $response = new WP_REST_Response($labelResponse, 200);
        wp_send_json_success($labelResponse);

    }

    public function ajax_monitor_label_status() {
        global $shiptimize;
        $callbackurl = $_POST['callbackUrl'];

        WooShiptimize::log("requesting label status from $callbackurl");
        $response = WooShiptimize::get_api()->monitor_label_status($callbackurl);
        WooShiptimize::log("label_response " . var_export($response, true));
        if (isset($response->response->Finished) && $response->response->Finished == 100) {
            if (isset($response->response->ClientReferenceCodeList)) {
                foreach($response->response->ClientReferenceCodeList as $labelresult) {
                    $order = new WooShiptimizeOrder($labelresult->ShopItemId);
                    $status = ShiptimizeOrder::$LABEL_STATUS_NOT_REQUESTED;
                    $msg =  '';
                    $labelurl = '';

                    if ($labelresult->Error->Id == 0 ) {
                        $status = ShiptimizeOrder::$LABEL_STATUS_PRINTED;
                        $labelurl = $response->response->LabelFile; // all labels in this batch share the same url
                        $msg = $shiptimize->translate('labelprinted');
                        $order->set_tracking_id($labelresult->TrackingId, $labelurl);
                    }
                    else {
                        $status = ShiptimizeOrder::$LABEL_STATUS_ERROR;
                        $msg = $labelresult->Error->Info;
                    }

                    $order_meta = $order->get_order_meta();
                    $msg = $order_meta->message . $msg;
                    $labelresult->message = $msg;
                    $order->set_label_meta($labelresult->ShopItemId,$status,$labelurl,$msg);
                }
            }
        }

        die(json_encode($response));
    }


    /**
     * if shiptimize_action is set to export-all then export all not already set as successfully exported
     *
     * @return mixed an object describing the success of the export
     */
    public function admin_init() {
        $shiptimize_export = filter_input(INPUT_GET, 'shiptimize_action');


        switch ($shiptimize_export) {
            case  'export-all' :
                return WooShiptimizeOrder::export_all();
            case 'export-this':
                $summary =  WooShiptimizeOrder::export(array($_GET['post']));
                wp_redirect(WooShiptimizeOrder::get_redirect_from_summary($summary));
                die();
            default:
                break;
        }
    }



    /**
     * Adds filters to select specific order status
     *
     */
    public function action_filter_order_status() {
        global $shiptimize;

        $type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : 'post' ;
        $status = isset( $_GET['shiptimize_status'] ) ? $_GET['shiptimize_status'] : '';
        if( $type == 'shop_order' ) {
            ?>
            <span class='shitpimize-status-filter'>
        <select name='shiptimize_status'>
          <option value=''> <?php echo $shiptimize->translate('All') ?> </option>
          <?php foreach(WooShiptimizeOrder::$status_text as $id => $status_text) { ?>
              <option value="<?php echo $id?>" <?php echo $status == $id ? 'selected' :'' ?>> <?php echo $status_text ?> </option>
          <?php } ?>
        </select>
      </span>
            <?php
        }
    }

    public function notice_area () {
        echo "<div id=\"shiptimize_notices\"></div>";
    }

    /**
     * filter the orders by status
     *
     * @return string - the join to include shiptimize meta
     */
    public function query_filter_order_status_join( $join ) {
        global $pagenow, $wpdb;

        $type = isset( $_GET['page'] )?  $_GET['page'] : 'wc_orders';
        $status = isset( $_GET['shiptimize_status'] ) ? $_GET['shiptimize_status'] : '';
        if( $type == 'shop_order' && $status ){
            $join .= " LEFT JOIN {$wpdb->prefix}shiptimize on {$wpdb->prefix}shiptimize.id = {$wpdb->prefix}posts.ID ";
        }

        return $join;
    }

    /**
     * filter the posts if status is set
     */
    public function query_filter_order_status_where( $where ) {
        global $wpdb;

        $type = isset( $_GET['page'] )?  $_GET['page'] : 'wc_orders';
        $status = isset( $_GET['shiptimize_status'] ) ? $_GET['shiptimize_status'] : '';
        if( $type == 'wc_orders' && $status ){
            $where .= $status != 1 ? ' AND ' .$wpdb->prefix.'shiptimize.status='.$status : ' AND ' .$wpdb->prefix.'shiptimize.status is null' ;
        }

        return $where;
    }

    /**
     * add the column headers
     */
    public function order_columns( $columns ) {
        global $shiptimize;
        ;

        $new_columns = array();
        foreach($columns as $key => $column) {
            $new_columns[$key] = $columns[$key];
            if($key === 'order_date') {
                $new_columns['shiptimize_status'] = SHIPTIMIZE_BRAND . ' ' . $shiptimize->translate('shiptimizecolumntitle');
            }
        }
        return $new_columns;

    }

    /**
     * add the column values
     * https://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column
     *
     * @param string column
     * @param int post_id
     */
    public function order_column_values_hpos($column_name, $order) {


        switch ($column_name) {
            case 'shiptimize_status':
                $order_meta = WooShiptimizeOrder::get_shipping_meta( $order->get_id() );
                echo $this->get_status_icon($order_meta ? $order_meta : (object)array('id' => $order->get_id(), 'message' => '', 'status' => ''));
                break;
        }
    }

    public function order_column_values( $column ) {
        global $post;

        switch ($column) {
            case 'shiptimize_status':
                $order_meta = WooShiptimizeOrder::get_shipping_meta( $post->ID );
                echo $this->get_status_icon($order_meta ? $order_meta : (object)array('id' => $post->ID, 'message' => '', 'status' => ''));
                break;
        }
    }

    /**
     * returns a representation of this status
     * @param mixed $order_meta- order metadata
     *
     * @returnstring containing the html representation of this order's status
     */
    public function get_status_icon($order_meta){
        global $shiptimize;

        $class = '';
        $msgclass = '';

        $message = $shiptimize->translate('Not Exported');

        if( $order_meta ) {

            $message = $order_meta->message;

            if( strlen($message > 100) ){
                $msgclass .= ' shiptimize-message-large';
            }

            switch ($order_meta->status) {
                case ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY:
                    $class = ' shiptimize-icon-success';
                    break;
                case ShiptimizeOrder::$STATUS_EXPORT_ERRORS:
                    $class = ' shiptimize-icon-error';
                    break;
                case ShiptimizeOrder::$STATUS_TEST_SUCCESSFUL:
                    $class = ' shiptimize-icon-test-successful';
                    break;
                case ShiptimizeOrder::$LABEL_STATUS_PRINTED:
                    $class = ' shiptimize-icon-print-printed';
                    break;

                case ShiptimizeOrder::$LABEL_STATUS_ERROR:
                    $class = ' shiptimize-icon-print-error';
                    break;

                case ShiptimizeOrder::$LABEL_STATUS_NOT_REQUESTED:
                    $class = ' shiptimize-icon-print-notprinted';
                    break;

                default: //Not exported or no status
                    $class .= ' shiptimize-icon-not-exported';
                    $message  .= $message ? '' : $shiptimize->translate('Not Exported');
            }

        } else {
            $class .= ' shiptimize-icon-not-exported';
        }

        if( isset($order_meta->pickup_label ) &&  $order_meta->pickup_label ){
            $message .= '<br/>'.$shiptimize->translate('Pickup Point') . ' -  ' . $order_meta->pickup_label;
        }

        // Also append the option to create a label
        if (!shiptimize_is_marketplace()) {
            $labelagree = get_option('shiptimize_labelagree');
            $btnlabellabel = $labelagree ? $shiptimize->translate('printlabel') : $shiptimize->translate('labellocked');
            $labelclick = $labelagree ? "shiptimize.printlabel(event,$order_meta->id);" : 'event.stopPropagation()';
            $labelbtn = "<span class=\"shiptimize-status shiptimize-tooltip-wrapper\">
          <span class=\"shiptimize-tooltip-reference\">
            <span class=\"button shiptimize-icon shiptimize-btn-label-print\"  title=\"label\" onclick='$labelclick'></span>
          </span>

          <span class=\"shiptimize-tooltip-message\">
            <span class=\"shiptimize-tooltip-message__arrow\"></span>
            <span class=\"shiptimize-tooltip__inner\">$btnlabellabel</span>
          </span>
        </span>";
        }
        else {
            $labelbtn = '';
        }

        return '<span class="shiptimize-status shiptimize-tooltip-wrapper">
      <span class="shiptimize-tooltip-reference ' . $msgclass . '">
        <span id="shiptimize-label' . $order_meta->id . '" class="shiptimize-icon ' . $class . '"></span>
      </span>
      <span class="shiptimize-tooltip-message">
        <span class="shiptimize-tooltip-message__arrow"></span>
        <span class="shiptimize-tooltip__inner" id="shiptimize-tooltip' . $order_meta->id . '">'.$message.'</span>
      </span>
    </span>' . $labelbtn;
    }


    /**
     * Adds an action to the bulk action menu
     *
     * @param array $bulk_actions - associative array of bulk actions
     * @return void
     */
    public function register_bulk_export($bulk_actions) {
        global $shiptimize;


        $bulk_actions['shiptimize_export'] = $shiptimize->translate( 'Export to' ) . ' Shiptimize';
        $bulk_actions['shiptimize_printlabel'] =  'Shiptimize: ' . $shiptimize->translate('printlabel');

        return $bulk_actions;
    }

    public function handle_bulk ($redirect_to, $doaction, $post_ids) {

        if( $doaction == 'shiptimize_export' ){
            return $this->bulk_export( $post_ids );
        }
        else if( $doaction == 'shiptimize_printlabel') {
            return $this->bulk_print_label ( $post_ids );
        }

        return $redirect_to;
    }

    /**
     * Printing Labels is an assynchronous process
     * At this time it's only possible to print 1 label at a time
     */
    public function bulk_print_label($post_ids) {
        global $shiptimize;

        $summary = WooShiptimizeOrder::print_label($post_ids);

        # Too Many labels
        if ( isset( $summary->response->ErrorList ) && count( $summary->response->ErrorList ) > 0 && $summary->response->ErrorList[0]->Id ==  6 ) {
            return admin_url( "edit.php?post_type=shop_order&paged=" . filter_input( INPUT_GET, 'paged' ) . '&Error=' . $shiptimize->translate( 'multiorderlabelwarn' ) );
        }

        # Other high level errors
        if ( isset($summary->response->ErrorList) && count($summary->response->ErrorList) > 0 && $summary->response->ErrorList[0]->Id > 0 ) {
            return admin_url("edit.php?post_type=shop_order&paged=" . filter_input(INPUT_GET, 'paged') . '&Error=' . $summary->ErrorList[0]->Info);
        }

        if ( $summary->httpCode == 500 ){
            return admin_url("edit.php?post_type=shop_order&paged=" . filter_input(INPUT_GET, 'paged') . '&Error=Fatal API error. Contact support with the order ids you just tried to print');
        }

        $urlString = '';

        WooShiptimize::log ( "bulk_export summary " . var_export($summary, true) );
        if (isset($summary->orderresponse) && !isset($summary->response)) {
            $summary->response = $summary->orderresponse;
        }

        if (isset($summary->response)) {
            if(isset($summary->response->CallbackURL)) {
                $urlString .= '&CallbackURL=' . $summary->response->CallbackURL;
            }
            else if (isset($summary->Error) && $summary->Error->Id > 0) {
                $urlString .='&Error=' . $summary->Error->Info;
            }
            elseif (isset($summary->ErrorList))  {
                $errors = '';
                foreach($summary->ErrorList as  $error) {
                    WooShiptimize::log ( "bulk_export summary ");
                    $errors .= $error->Info . ' ';
                }
                $urlString .= '&Error=' . $errors;
            }
        }


        return admin_url("edit.php?post_type=shop_order&paged=" . filter_input(INPUT_GET, 'paged') . $urlString);
    }

    /**
     * Do the bulk export
     */
    public function bulk_export( $post_ids ) {
        $summary = WooShiptimizeOrder::export($post_ids);
        $urlString = '';


        WooShiptimize::log( "bulk_export summary " . var_export($summary, true) );


        foreach((array)$summary as $key => $value) {
            if(!is_array($value)){
                $urlString .= '&shiptimize_' . $key . '=' . $value;
            }
        }

        return admin_url("edit.php?post_type=shop_order&paged=" . filter_input(INPUT_GET, 'paged') . $urlString);
    }


    public function custom_js_to_head() {
        global $shiptimize;

        if(isset($_GET['shiptimize_nOrders'])){
            $summary = (object)array(
                'n_success' => filter_input(INPUT_GET, 'shiptimize_n_success'),
                'n_errors' => filter_input(INPUT_GET, 'shiptimize_n_errors'),
                'login_url' => filter_input(INPUT_GET, 'shiptimize_login_url'),
                'nOrders' => filter_input(INPUT_GET, 'shiptimize_nOrders'),
                'nInvalid' => filter_input(INPUT_GET, 'shiptimize_nInvalid'),
                'message' => filter_input(INPUT_GET, 'shiptimize_message')
            );
            $export_message = WooShiptimizeOrder::get_export_summary($summary);
        }

        $class = 'page-title-action shiptimize-export-btn ' . SHIPTIMIZE_BRAND;

        $is_single_order  = isset($_GET['post']);
        $export_url =  $is_single_order ? admin_url('edit.php?post_type=shop_order&shiptimize_action=export-this&post='.$_GET['post']) : admin_url('edit.php?post_type=shop_order&shiptimize_action=export-all');
        $export_link_text = ($is_single_order ?  $shiptimize->translate('Export to') : $shiptimize->translate("Export Preset Orders to") ) . ' ' .  SHIPTIMIZE_BRAND;

        ?>
        <script>
            var shiptimize_label_request = '<?php echo $shiptimize->translate('requestinglabel') ?>';
            var shiptimize_label_click = '<?php echo $shiptimize->translate('labelclick'); ?>';
            var shiptimize_label_label = '<?php echo $shiptimize->translate('label');?>';

            jQuery(function(){
                let eHeader = jQuery("body.post-type-shop_order .wrap h1");
                eHeader.append('<a href="<?php echo $export_url ?>" class="<?php echo $class ?>"><?php echo $export_link_text ?></a>');

                <?php if($is_single_order) { ?>
                eHeader.append('<a href="#!" onclick="shiptimize.printlabel(event,<?php echo $_GET['post'] ?>)" class="page-title-action shiptimize-btn-print-label"><?php echo $shiptimize->translate('printlabel') ?></a>');
                <?php } ?>

                <?php if( isset($summary )) { ?>
                var eShiptimize = jQuery("<?php echo str_replace(array("\n","\""), array("","\\\""), $export_message) ?>");
                jQuery(".wp-header-end").before(eShiptimize);
                shiptimize.exportSuccess("<?php echo $summary->login_url?>");
                console.log(eShiptimize.get(0));
                <?php } ?>


            });
        </script>
        <?php
    }

}

new ShiptimizeOrderUI();