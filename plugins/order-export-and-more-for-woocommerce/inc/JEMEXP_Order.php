<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}


//***********************************
// 1.4.6 Updated to account for new
// Setters/getters in Woo 3.0
//***********************************


/**
 * Class Order
 *
 * CHANGE HISTORY
 * 1.5 - adding batching to the query engine
 */
class JEMEXP_Order extends JEMEXP_BaseEntity
{
    /**
     * @var string - holds the decimal sperator
     */
    private $decimalSeperator = "";

    /**
     * @var - holds indicator if we partially loaded the products
     * we use this to tell if we need to load them on the filters tab
     */
    private $productsPartiallyLoaded = false;

    /**
     * @var bool - shows if we have loaded the products
     * we use this to tell if we need to load
     */
    private $productsLoaded = false;

    /**
     * @var array - holds the array of products
     */
    private $productArray = array();


    /**
     * @var null - holds the # of decmal places
     */
    private $dp = null;

    /**
     * @var string - the transient holding the query
     */
    private $query_transient = 'jemxp_query';

    public $tempFileName = "export_pro_order_export.csv";


    //This is the data passed in from the front end
    private $formData = null;

    //Var to hold the meta data for a line item
    private $lineItemMeta = null;

    //Var to hold the meta data for a product
    private $productMeta = null;
    /**
     * This holds the Export Data Object!
     * @var $settings JEMEXP_Export_Data
     */
    public $settings;

    //TODO We should add this as an option in the future
    private $couponDelimiter = "|";

    //Line items we are extracting
    private $lineItemsToExtract = array();
    //Transient fields
    public $exportParams = null;
    public $fields_to_export = null;
    public $meta = null;
    public $product = null;
    public $item_meta = null;
    private $custom = null;
    public $filters = null;
    private $args = null;
    private $maxItems = null;
    private $max_order_num = null;

    private $data_engine = null;

    // This is the default settings of a ORDER object
    private static $default_settings = array(
        'order_settings' => array(
            'preview' => false,
            'new_order_settings' => array(),
            "orderStatus" => array(),
            'order_filters_status' => array(),
            'order_filters_fba' => array(),
            "product_filter" => array(),
            "category_filter" => array(),
            'coupon_filter' => array(),
            'any_coupons' => false,
            'customer_filters' => array(),
            'fields_to_export' => array(),
            'export_new_orders' => 'N',
            'starting_from_num' => '',
            'date_from' => "",
            'date_to' => "",
            'report_format' => array(
                'sort_by' => 'date',
                'order_by' => 'asc',
                'date_format' => 'F j,y',
                'time_format' => 'g:i m',
                'filename' => 'order-export.csv',
                'encoding' => 'UTF-8',
                'delimiter' => ',',
                'product_grouping' => 'rows',
                "content_type" => 'text/plain; charset="UTF-8"',
                'mime_version' => 'MIME-Version: 1.0',
                'email_from' => '',
                'line_break' => '\r\n',
            ),
        ),
    );

    public function __construct($params)
    {

        //We expect a export data object
        if (!isset($params) || get_class($params) != 'JEMEXP_Export_Data') {
            //just use an empty one!
            $params = new JEMEXP_Export_Data();
        }

        $this->settings = $params;
        //2.0.6 @simon fixing the temp directory issue
        $dir = wp_upload_dir();
        $fileName = $dir['basedir'] . '/' . $this->tempFileName;

        $this->tempFileName = $fileName;

        $this->id = "Order";
        $this->enabled = true;

        //load the fields into the array
        $this->fields = array();
        $this->fields = $this->load_fields();

        $this->filters = array();

        $this->productArray = array();
        $this->productsLoaded = false;
        $this->productsPartiallyLoaded = false;

        $this->maxItems = 0;

        //Hook up the ajax call!
        add_action('wp_ajax_JEMEXP_get_data_chunk', array($this, 'JEMEXP_get_data_chunk_ajax'));

        $this->data_engine = new JEMEXP_Data_Engine();

    }


    /**
     * This sets the export settings for this object
     *
     * @param $settings
     */
    public function set_export_settings($settings)
    {

    }

    /**
     * populates the array the fields for this entity
     * For meta - we make the name the same as the key in the meta
     */
    private function load_fields()
    {

        $fields = array();

        //Basic Order Fields
        $fields['order_id'] = array(
            'name' => 'order_id',
            'placeholder' => __('Order ID', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'group' => 'Basic Order Details',
            'data_type' => 'post',
            'hide_from_filter' => false
        );

        $fields['order_date'] = array(
            'name' => 'order_date',
            'placeholder' => __('Order Date', 'order-export-and-more-for-woocommerce'),
            'group' => 'Basic Order Details',
            'data_type' => 'post',
            'type' => 'date',
            'hide_from_filter' => true
        );

        $fields['order_status'] = array(
            'name' => 'order_status',
            'placeholder' => __('Order Status', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'post',
            'type' => 'text',
            'group' => 'Basic Order Details',
            'hide_from_filter' => true  //we hide these two fields as they have their own filter not part of the broad dropdown
        );

        $fields['customer_note'] = array(

            'name' => 'customer_note',
            'placeholder' => __('Customer Note', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'post',
            'group' => 'Basic Order Details',
            'hide_from_filter' => false

        );

        //Checkout Informaion
        $fields['_order_total'] = array(
            'name' => '_order_total',
            'placeholder' => __('Order Total', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'postmeta',
            'type' => 'number',
            'group' => 'Checkout Information'
        );

        $fields['_order_shipping'] = array(
            'name' => '_order_shipping',
            'placeholder' => __('Order Shipping Amount', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'postmeta',
            'type' => 'number',
            'group' => 'Checkout Information'
        );

        $fields['_order_shipping_tax'] = array(
            'name' => '_order_shipping_tax',
            'placeholder' => __('Order Shipping Tax Amount', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'postmeta',
            'type' => 'number',
            'group' => 'Checkout Information'
        );

        $fields['_order_currency'] = array(

            'name' => '_order_currency',
            'placeholder' => __('Order Currency', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'postmeta',
            'type' => 'text',
            'group' => 'Checkout Information'
        );

        $fields['_order_discount'] = array(
            /* 'disabled' => true, */
            'name' => '_order_discount',
            'placeholder' => __('Order Discount', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'postmeta',
            'group' => 'Checkout Information'
        );


        $fields['_payment_method'] = array(
            /* 'disabled' => true, */
            'name' => '_payment_method',
            'placeholder' => __('Payment Gateway', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'group' => 'Checkout Information',
            'data_type' => 'postmeta'
        );

        $fields['_payment_method_title'] = array(
            /* 'disabled' => true, */
            'name' => '_payment_method_title',
            'placeholder' => __('Payment Method Title', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'group' => 'Checkout Information',
            'data_type' => 'postmeta'
        );

        $fields['_shipping_method'] = array(
            /* 'disabled' => true, */
            'name' => '_shipping_method',
            'placeholder' => __('Shipping Method', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'group' => 'Checkout Information',
            'data_type' => 'postmeta'
        );

        $fields['_shipping_method_title'] = array(
            /* 'disabled' => true, */
            'name' => '_shipping_method_title',
            'placeholder' => __('Shipping Method Title', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'group' => 'Checkout Information',
            'data_type' => 'postmeta'
        );

        $fields['_shipping_weight'] = array(
            /* 'disabled' => true, */
            'name' => '_shipping_weight',
            'placeholder' => __('Total Shipping Weight', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'basic',
            'group' => 'Checkout Information',
            'hide_from_filter' => true
        );
        $fields['coupon_code'] = array(
            'name' => 'coupon_code',
            'placeholder' => __('Coupon Code', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'group' => 'Checkout Information',
            'data_type' => 'basic',
            'hide_from_filter' => true
        );
        //Line Items
        $fields['item_name'] = array(
            'name' => 'item_name',
            'placeholder' => __('Product Name', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'type' => 'text',
            'hide_from_filter' => true
        );

        $fields['product_id'] = array(
            'name' => 'product_id',
            'placeholder' => __('Product ID', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'type' => 'number',
            'hide_from_filter' => true
        );

        $fields['sku'] = array(
            'name' => 'sku',
            'placeholder' => __('Product SKU', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'type' => 'text',
            'hide_from_filter' => true
        );

        $fields['product_categories'] = array(
            'name' => 'product_categories',
            'placeholder' => __('Product Categories', 'order-export-and-more-for-woocommerce'),
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'type' => 'text',
            'hide_from_filter' => true
        );

        $fields['item_qty'] = array(
            'name' => 'item_qty',
            'placeholder' => __('Quantity of items purchased', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );

        $fields['item_subtotal'] = array(
            'name' => 'item_subtotal',
            'placeholder' => __('Item price EXCL. tax', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );

        $fields['item_tax'] = array(
            'name' => 'item_tax',
            'placeholder' => __('Item tax', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );

        $fields['item_total'] = array(
            'name' => 'item_total',
            'placeholder' => __('Item price INCL. tax', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );

        $fields['item_tax_rate'] = array(
            'name' => 'item_tax_rate',
            'placeholder' => __('Item Tax Rate', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );


        $fields['item_variation'] = array(
            'name' => 'item_variation',
            'placeholder' => __('All Line Metadata', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );

        $fields['_length'] = array(
            'name' => '_length',
            'placeholder' => __('Length', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Product Details',
            'hide_from_filter' => true
        );

        $fields['_width'] = array(
            'name' => '_width',
            'placeholder' => __('Width', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Product Details',
            'hide_from_filter' => true
        );

        $fields['_height'] = array(
            'name' => '_height',
            'placeholder' => __('Height', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Product Details',
            'hide_from_filter' => true
        );

        $fields['item_weight'] = array(
            'name' => 'item_weight',
            'placeholder' => __('Weight', 'order-export-and-more-for-woocommerce'),
            'type' => 'number',
            'data_type' => 'line_item',
            'group' => 'Product Details',
            'hide_from_filter' => true
        );

        $fields['shipping_class'] = array(
            'name' => 'shipping_class',
            'placeholder' => __('Shipping Class', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'line_item',
            'group' => 'Product Details',
            'hide_from_filter' => true
        );

        $fields['product_type'] = array(
            'name' => 'product_type',
            'placeholder' => __('Product Type', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );

        $fields['product_category'] = array(
            'name' => 'product_category',
            'placeholder' => __('Product Category', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );

        $fields['tags'] = array(
            'name' => 'tags',
            'placeholder' => __('Tags', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'line_item',
            'group' => 'Line Item Details',
            'hide_from_filter' => true
        );


        //Shipping details
        $fields['customer_name'] = array(
            'name' => 'customer_name',
            'placeholder' => __('Customer Name', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'shipping',
            'group' => 'Shipping Details',
            'hide_from_filter' => true
        );

        $fields['_shipping_first_name'] = array(
            'name' => '_shipping_first_name',
            'placeholder' => __('Shipping First Name', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Shipping Details'
        );

        $fields['_shipping_last_name'] = array(
            'name' => '_shipping_last_name',
            'placeholder' => __('Shipping Last Name', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Shipping Details'
        );

        $fields['_shipping_address_line1'] = array(
            'name' => '_shipping_address_line1',
            'placeholder' => __('Shipping Address Line 1', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Shipping Details'
        );

        $fields['_shipping_address_line2'] = array(
            'name' => '_shipping_address_line2',
            'placeholder' => __('Shipping Address Line 2', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Shipping Details'
        );

        $fields['_shipping_city'] = array(
            'name' => '_shipping_city',
            'placeholder' => __('Shipping City', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Shipping Details'
        );

        $fields['_shipping_state'] = array(
            'name' => '_shipping_state',
            'placeholder' => __('Shipping State', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Shipping Details'
        );

        $fields['_shipping_postcode'] = array(
            'name' => '_shipping_postcode',
            'placeholder' => __('Shipping Zip/Postcode', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Shipping Details'
        );

        $fields['_shipping_country'] = array(
            'name' => '_shipping_country',
            'placeholder' => __('Shipping Country', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Shipping Details'
        );


        //Billing Details
        $fields['_billing_first_name'] = array(
            'name' => '_billing_first_name',
            'placeholder' => __('Billing First Name', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        $fields['_billing_last_name'] = array(
            'name' => '_billing_last_name',
            'placeholder' => __('Billing Last Name', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );
        $fields['_billing_address_line1'] = array(
            'name' => '_billing_address_line1',
            'placeholder' => __('Billing Address Line 1', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        $fields['_billing_address_line2'] = array(
            'name' => '_billing_address_line2',
            'placeholder' => __('Billing Address Line 2', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        $fields['_billing_city'] = array(
            'name' => '_billing_city',
            'placeholder' => __('Billing City', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        $fields['_billing_state'] = array(
            'name' => '_billing_state',
            'placeholder' => __('Billing State', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        $fields['_billing_postcode'] = array(
            'name' => '_billing_postcode',
            'placeholder' => __('Billing Zip/Postcode', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        $fields['_billing_country'] = array(
            'name' => '_billing_country',
            'placeholder' => __('Billing Country', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        $fields['_billing_email'] = array(
            'name' => '_billing_email',
            'placeholder' => __('Billing Email', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        $fields['_billing_phone'] = array(
            'name' => '_billing_phone',
            'placeholder' => __('Billing Phone Number', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'Billing Details'
        );

        //User details
        $fields['_customer_ip_address'] = array(
            'name' => '_customer_ip_address',
            'placeholder' => __('User IP Address', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'data_type' => 'postmeta',
            'group' => 'User Details',
            'hide_from_filter' => true
        );
        $fields['user_login'] = array(
            'name' => 'user_login',
            'placeholder' => __('User Login', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'group' => 'User Details',
            'data_type' => 'user',
            'hide_from_filter' => true
        );
        $fields['_customer_email'] = array(
            'name' => '_customer_email',
            'placeholder' => __('User Email', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'group' => 'User Details',
            'data_type' => 'user',
            'hide_from_filter' => true
        );
        $fields['user_role'] = array(
            'name' => 'user_role',
            'placeholder' => __('User Role', 'order-export-and-more-for-woocommerce'),
            'type' => 'text',
            'group' => 'User Details',
            'data_type' => 'user',
            'hide_from_filter' => true
        );


        return $fields;
    }

    /**
     * Gnerates the html output for the FILTER BY ANYTHING for the Order entity
     * (non-PHPdoc)
     * @see BaseEntity::generate_order_list_option()
     */
    public function generate_order_filter_by_anything()
    {
        $output = '';
        $fields = $this->load_fields();
        $current_group_name = "";


        //Single set of metadata - need to add what type of data they are!
        $postmeta = $this->data_engine->get_order_basic_meta();  //ALL DATA FROM POSTMETA
        $postmeta = array_fill_keys($postmeta, 'basic_meta');
        ksort($postmeta);


        foreach ($fields as $key => $value) {

            //is it a field we want to hide?
            if (isset($value['hide_from_filter']) && ($value['hide_from_filter'] == true)) {
                continue;
            }

            //Do we have a new group?
            if ($current_group_name != $value['group']) {
                //We want to close the previous group UNLESS it is the first one which we will know by the group name being blank
                if ($current_group_name != "") {
                    //close the previous group
                    $output .= "</optgroup>";
                }

                //Save the current group
                $current_group_name = $value['group'];

                //and open the group!
                $output .= "<optgroup label='" . $current_group_name . "'>";


            }

            //OK so lets create the entry for this field
            $output .= "<option value='" . $value['name'] . "' data-type='" . $value['type'] . "'" . " data-data-type='" . $value['data_type'] . "'>" . $value['placeholder'] . "</option>";
        }

        //Now lets add Metadata on the bottom

        //loop through all the fields - if they are found in the meta then remove the,

        foreach ($this->fields as $key => $val) {
            //see if it's in the meta
            if (array_key_exists($key, $postmeta)) {
                //remove it
                unset($postmeta[$key]);
                continue;
            }

        }

        //now let's add them in
        $output .= "<optgroup label='" . __('Custom Data', 'order-export-and-more-for-woocommerce') . "'>";
        foreach ($postmeta as $key => $val) {
            $output .= "<option value='" . $key . "' data-type='" . $val . "'>" . $key . "</option>";


        }
        $output .= "</optgroup>";


        return $output;

    }

    /**
     * Generates the html output for the filters for the Order entity
     * We pass in a prefix for HTML names/id's as we use this
     * both for the filter on export and also on the schedule
     * (non-PHPdoc)
     * @see BaseEntity::generate_filters()
     * @param string $prefix
     * @return string|void
     */
    public function generate_filters($prefix = "")
    {

        //filters on this object contains any filters we need to set....
        //lets create the array of order status
        $status = wc_get_order_statuses();

        //if filters is empty lets create a default set for
        if (!isset($this->filters['order-status'])) {
            $this->filters['order-status'] = array();
            foreach ($status as $key => $val) {
                $this->filters['order-status'][] = $key;
            }
        }

        $html = '<a href="#" id="order-filter-select-all-status" class="jem-select-all">Select All</a>   |   <a href="#" id="order-filter-select-none-status" class="jem-select-none">Select None</a>
		';

        foreach ($status as $key => $val) {

            $checked = "";

            if (isset($this->filters['order-status']) && in_array($key, $this->filters['order-status'])) {
                $checked = " checked";
            }
            $html .= '
				<div class="jem-order-status">
					<label><input type="checkbox" class="jem-checkbox" name="order-filter-order-status[]" value="' . $key . '" ' . $checked . '>' . $val . '</label>
				</div>

			';
        }


        //Any values for start/end date?
        $startDate = "";
        $endDate = "";
        $presetDate = "all";

        if (isset($this->filters['start-date'])) {
            $startDate = $this->filters['start-date'];
        }

        if (isset($this->filters['end-date'])) {
            $endDate = $this->filters['end-date'];
        }

        if (isset($this->filters['preset-date'])) {
            $presetDate = $this->filters['preset-date'];
        }


        //Any value for item formatting?
        $itemFormat = "row";  //default
        if (isset($this->filters['item-format'])) {
            $itemFormat = $this->filters['item-format'];
        }


        $ret = '
			<div>
				<h3 class="jem-filter-header">' . __('Date Filters', 'order-export-and-more-for-woocommerce') . '</h3>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-date-radio" class="jemex-order-date-filter" value="all"' . checked($presetDate, "all", false) . '><label for="something">All Dates</label></div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-date-radio" class="jemex-order-date-filter" value="today"' . checked($presetDate, "today", false) . '><label for="something">Today</label></div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-date-radio" class="jemex-order-date-filter" value="yesterday"' . checked($presetDate, "yesterday", false) . '><label for="something">Yesterday</label></div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-date-radio" class="jemex-order-date-filter" value="currweek"' . checked($presetDate, "currweek", false) . '><label for="something">Current Week Sunday - Saturday</label></div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-date-radio" class="jemex-order-date-filter" value="lastweek"' . checked($presetDate, "lastweek", false) . '><label for="something">Last Week  Sunday - Saturday</label></div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-date-radio" class="jemex-order-date-filter" value="currmonth"' . checked($presetDate, "currmonth", false) . '><label for="something">Current Month</label></div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-date-radio" class="jemex-order-date-filter" value="lastmonth"' . checked($presetDate, "lastmonth", false) . '><label for="something">Last Month</label></div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-date-radio" class="jemex-order-date-filter" value="custom"' . checked($presetDate, "custom", false) . '><label for="something">Custom Date Range</label></div>
			</div>
			<div class="filter-dates">
				<label>
				' . __('From Date', 'order-export-and-more-for-woocommerce') . '
				</label>
				<input id="' . $prefix . 'order-filter-start-date"  name="' . $prefix . 'order-filter-start-date" class="jemexp-datepicker" value="' . $startDate . '">
				<label>
				' . __('To Date', 'order-export-and-more-for-woocommerce') . '
				</label>
				<input id="' . $prefix . 'order-filter-end-date"  name="' . $prefix . 'order-filter-end-date" class="jemexp-datepicker" value="' . $endDate . '">
			</div>
			<div class="jemex-filter-section">
				<h3 class="jem-filter-header">' . __('Order Status', 'order-export-and-more-for-woocommerce') . '</h3>
				<p class="instructions">' . __('Select the order types you would like to export.', 'order-export-and-more-for-woocommerce') . '</p>
			</div>
						<div> ' . $html . '
						</div>
			<div class="jemex-filter-section">
				<h3 class="jem-filter-header">' . __('Item Format Rules', 'order-export-and-more-for-woocommerce') . '</h3>
				<p class="instructions">' . __('Select how you would like each item for an order formatted', 'order-export-and-more-for-woocommerce') . '</p>
			</div>
			<div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-item-format" class="jemex-order-item-format" value="row"' . checked($itemFormat, "row", false) . '><label for="something">Each item is on a seperate row</label></div>
				<div class="jemex-radio"><input type="radio" name="' . $prefix . 'order-filter-item-format" class="jemex-order-item-format" value="column"' . checked($itemFormat, "column", false) . '><label for="something">Each order is on one row, items are in individual cells</label></div>
			</div>
								';


        //****************************
        //Category
        //****************************

        //if we have not loaded the products then lets load them
        if ($this->productsLoaded != true) {

            $this->productsLoaded = true;
            list($this->productArray, $this->productsPartiallyLoaded) = jemexp_get_all_products();
        }


        $html = '<h4 class="jemex-h3-no-padding-left">Products</h4>';
        $html .= '<p class="instructions">Please select the products to include- leave blank to select ALL products</p>';
        //Loop thru and create the dropdown
        if (count($this->productArray) > 0) {
            $html .= '<select id="order-products-select" name="order-products-select[]" class="select2-product" multiple>';
            foreach ($this->productArray as $product) {
                if (isset($this->filters['products']) && in_array($product->ID, $this->filters['products'])) {
                    $html .= '<option value="' . $product->ID . '" selected>' . $product->post_title . '</option>';

                } else {
                    $html .= '<option value="' . $product->ID . '">' . $product->post_title . '</option>';

                }
            }

            $html .= '</select>';
        } else {
            $html .= '<p>No Products were found</p>';
        }

        $ret = $ret . $html;

        //****************************
        //Coupons
        //****************************

        $a = array(
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'asc',
            'post_type' => 'shop_coupon',
            'post_status' => 'publish',
        );

        $coupons = get_posts($a);

        $html = '<h4 class="jemex-h3-no-padding-left">Coupons</h4>';
        $html .= '<p class="instructions">Please select the Coupons to include - leave blank to select everything</p>';

        //Loop thru and create the dropdown
        if (count($coupons) > 0) {
            $html .= '<select id="order-coupons-select" name="order-coupons-select[]" class="select2-product" multiple>';
            foreach ($coupons as $coupon) {
                if (isset($this->filters['coupons']) && in_array($coupon->ID, $this->filters['coupons'])) {
                    $html .= '<option value="' . $coupon->post_title . '" selected>' . $coupon->post_title . '</option>';

                } else {
                    $html .= '<option value="' . $coupon->post_title . '">' . $coupon->post_title . '</option>';

                }
            }

            $html .= '</select>';
        } else {
            $html .= '<p>No Coupons were found</p>';
        }

        $ret = $ret . $html;


        return $ret;
    }

    /**
     * Creates the arguments to be used in the query
     * They get stored in a transient so each chunk does not have to recreate them
     * @return bool
     */
    public function create_args()
    {

        global $wpdb;

        global $woocommerce;

        //extract the filters
        //lets get the appropriate filters for this entity

        $statusFilters = $this->extract_status_filters();

        $dateFilters = $this->extract_date_filters();

        //Get the meta query
        $mq = $this->generate_metaquery_args();


        //TODO posts_per_page is hard coded at 250 - we need to make it a constant/setting or something

        $args = array('post_type' => 'shop_order', 'posts_per_page' => 250, 'post_status' => $statusFilters, 'meta_query' => $mq);

        //Add the date filter if it is set
        if ($dateFilters != null) {
            $args['date_query'] = $dateFilters;
        }

        //************************
        // PRODUCTS FILTERS
        //************************

        //any products??
        //if (isset($this->exportParams['order_settings']['product_filter']) && count($this->exportParams['order_settings']['product_filter']) > 0) {
        if (count($this->settings->getProductFilter()) > 0) {

            //Get the product ID's
            $ids = array_column($this->settings->getProductFilter(), 'id');

            $a = implode(",", $ids);

            $order_ids = $this->get_orders_for_products($a);

            if (is_array($order_ids)) {
                $args['post__in'] = $order_ids;

            }
        }

        //************************
        // PRODUCT CATEGORY FILTERS
        //************************

        //any category filters??
        //if (isset($this->exportParams['order_settings']['category_filter']) && count($this->exportParams['order_settings']['category_filter']) > 0) {
        if (count($this->settings->getCategoryFilter()) > 0) {

            //Get the category IDs
            $ids = array_column($this->settings->getCategoryFilter(), 'id');

            $a = implode(",", $ids);

            $order_ids = $this->get_orders_for_categories($a);

            if (is_array($order_ids)) {

                //There could also be some posts from Porduct
                if (isset($args['post__in']) && is_array($args['post__in'])) {
                    $args['post__in'] = array_merge($args['post__in'], $order_ids);
                    $args['post__in'] = array_unique($args['post__in']);
                } else {
                    $args['post__in'] = $order_ids;

                }

            }
        }

        //************************
        // COUPONS FILTERS
        //************************

        //Any coupons selected AND we are NOT doing ANY coupon?

        if (count($this->settings->getCouponFilter()) && ($this->settings->isAnyCoupons()) != true) {


            //lets get a list of orders for this product
            global $wpdb;

            //Get the coupon names from the array
            $ids = array_column($this->settings->getCouponFilter(), 'label');

            //Now get the order ID's that have these coupons

            if(!is_array($ids)){
                $ids = array($ids);
            }

            $ids_count = count($ids);
            $stringPlaceholders = implode(',', array_fill(0, $ids_count, '%s'));


            $sql = $wpdb->prepare("SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items
					WHERE order_item_type = 'coupon' AND order_item_name IN ({$stringPlaceholders})
					GROUP BY order_id;", $ids);

            $order_ids = $wpdb->get_col($sql);

            //check we got some orders
            if (is_array($order_ids) && count($order_ids) == 0) {
                return false;
            }

            //if there are already some args then merge (intersect)
            if (isset($args['post__in']) && is_array($args['post__in']) && count($args['post__in']) > 0) {
                $args['post__in'] = array_intersect($args['post__in'], $order_ids);
            } else {
                $args['post__in'] = $order_ids;

            }
        }

        //************************
        // ANY COUPON CHECKBOX SET
        //************************
        if ($this->settings->isAnyCoupons() == true) {


            $order_ids = $wpdb->get_col("SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items
            WHERE order_item_type = 'coupon'
            GROUP BY order_id;");

            //check we got some orders
            if (is_array($order_ids) && count($order_ids) == 0) {
                return false;
            }

            //if there are already some args then merge (intersect)
            if (isset($args['post__in']) && is_array($args['post__in']) && count($args['post__in']) > 0) {
                $args['post__in'] = array_intersect($args['post__in'], $order_ids);
            } else {
                $args['post__in'] = $order_ids;

            }

        }
        $this->args = $args;
    }


    /**
     * Gets a list of order ID's for an array of product(s)
     * @param $a
     * @return array|bool
     */
    public function get_orders_for_products($a)
    {
        //lets get a list of orders for this product
        global $wpdb;

        if(!is_array($a)){
            $a = array($a);
        }

        $products_count = count($a);
        $stringPlaceholders = implode(',', array_fill(0, $products_count, '%s'));

        $sql = $wpdb->prepare("SELECT order_id FROM {$wpdb->prefix}woocommerce_order_itemmeta woim
			        LEFT JOIN {$wpdb->prefix}woocommerce_order_items oi
					ON woim.order_item_id = oi.order_item_id
					WHERE meta_key = '_product_id' AND meta_value IN ({$stringPlaceholders})
					GROUP BY order_id;", $a);

        $order_ids = $wpdb->get_col($sql);

        //check we got some orders
        if (is_array($order_ids) && count($order_ids) == 0) {
            return false;
        }

        return $order_ids;

    }

    /**
     * Gets a list of order ID's for an array of product(s)
     * @param $a
     * @return array|bool
     */
    public function get_orders_for_categories($a)
    {
        //lets get a list of orders for these cats
        global $wpdb;

        if(!is_array($a)){
            $a = array($a);
        }

        //First let's get the products for these categories
        $categories_count = count($a);
        $stringPlaceholders = implode(',', array_fill(0, $categories_count, '%s'));


        $sql = $wpdb->prepare("SELECT ID FROM {$wpdb->prefix}posts
			        WHERE ID IN (
			        SELECT object_id from  {$wpdb->prefix}term_relationships
			        WHERE term_taxonomy_id IN ({$stringPlaceholders})
					)
					GROUP BY ID", $a);

        $product_ids = $wpdb->get_col($sql);

        //check we got some orders
        if (is_array($product_ids) && count($product_ids) == 0) {
            return false;
        }

        //Now get the orders for these products
        $product_ids = implode(",", $product_ids);
        $order_ids = $this->get_orders_for_products($product_ids);
        //check we got some orders
        if (is_array($order_ids) && count($order_ids) == 0) {
            return false;
        }

        return $order_ids;

    }

    public function generate_metaquery_args()
    {
        global $wpdb;

        //Let's set up the basic sql

        $mq = array();

        //foreach ($this->exportParams['order_settings']['order_filters_fba'] as $key => $val) {
        foreach ($this->settings->getOrderFiltersFba() as $key => $val) {
            //this is only for postmeta fields so skip if not
            if ($val['datatype'] != 'postmeta') {
                continue;
            }

            //Need to account for numbers!
            if($val['type'] == 'number' ){
                $type = 'DECIMAL';
            } else {
                $type = '';
            }

            if($val['select'] == 'greater'){
                $val['select'] = '>';
            }

            if($val['select'] == 'less'){
                $val['select'] = '<';
            }
            //Create the where clause
            $mq[] = array(
                'key' => $val['name'],
                'value' => trim($val['value']),
                'compare' => $val['select'],
                'type' => $type
            );
        }

        return $mq;
    }


    /**
     * This function gets the fields to preview
     * It returns null if it worked ok otherwise an error message
     * TODO - taken from JEM Exporter - at some point we will want to refactor
     * @param $data
     * @return null|string|void
     */
    public function preview_fields()
    {

        $order_status = $this->settings->getOrderStatus();
        if (empty($order_status)) $order_status = "processing";
        $orderby = $this->settings->getOrderBy();
        $order = wc_get_orders(array(
            'limit' => 25,
            'status' => $order_status,
            'order' => $orderby
        ));

        return $order;
    }

    /**
     * Process the orders and extracts the fields
     * Returns the max amount of line items and also the array of CSV output
     * This is where a lot of the heavy lifting is done
     * @param $orders
     * @return array (maxItems, output)
     */
    public function process_orders($orders)
    {
        //do we have any orders?
        if ($orders->have_posts()) {


            $output = array();

            //Lets keep track of the max # of line items in a single order
            //We do this so we know how many entries we need to make in the header rows!
            $maxItems = 1;
            //************************
            // Export NEW orders only?
            //************************
            $export_from = 0;
            $max_order_num = 0;

            if ($this->settings->getExportNewOrders() == true || $this->settings->getPreview() != true) {
                //do we have an order number?
                if (is_numeric((int)$this->settings->getStartingFromNum())) {

                    $export_from = (int)$this->settings->getStartingFromNum();

                }
            }

            while ($orders->have_posts()) {

                //ok looping each order

                $orders->the_post();

                //Not the best solution (custom SQL?) but just skip any order BEFORE the start from order number
                $order_id = get_the_ID();

                if ($export_from >= $order_id) {
                    continue;
                }

                //Keep track of the max order number
                if ($order_id > $max_order_num) {
                    $max_order_num = $order_id;
                }

                //The main order object
                $order_details = new WC_Order($order_id);

                //line Items associated with that order
                $order_items = $order_details->get_items();

                //For now let's always get meta - we can put this in  a cache later
                //$order_meta = $order_details->get_data();

                $order_meta = $fields = $this->data_engine->get_meta_for_order($order_id);

                //There are common fields that need to be gathered first and saved
                $common = $this->extract_common_fields($order_details, $order_items, $order_meta);

                $data = array();

                //Get the line items for this order
                //$this->lineItemsToExtract = $this->get_line_item_fields($this->exportParams['order_settings']['fields_to_export']);
                $this->lineItemsToExtract = $this->get_line_item_fields($this->settings->getFieldsToExport());

                //TODO we need to find out a new way to determine if we need to do this
                //Do we need to get the item meta data for this order
                //we do it once here as it is at the order level NOT the item level
                //if( isset($this->item_meta_array) && is_array($this->item_meta_array) && ( count($this->item_meta_array) > 0 ) ) {
                if (1 == 1) {

                    $all_line_items = $order_details->get_items(array('shipping', 'fee', 'tax', 'coupon'));

                }


                //Loop thru each item
                $i = 0;
                //First time thru we ALWAYS use the full list of fields for the columns
                $lineItems = $this->settings->getFieldsToExport();

                foreach ($order_items as $id => $item) {

                    //Reset the line item meta
                    $this->lineItemMeta = null;

                    //increment the count of items
                    $i++;

                    //if we are outputing by row simply add to output array otherwise build a single line
                    if ($this->settings->getProductGrouping() == "rows") {
                        $temp = $this->build_extract_row($this->settings->getFieldsToExport(), $order_details, $common, $item);

                        $data[] = $temp;

                    } else {
                        //OK so we are creating many columns for each order
                        //we repeat the item fields - the extract_fields function takes a param to determine which to process
                        $itemData = $this->build_extract_row($lineItems, $order_details, $common, $item);

                        //Next time thru we ONLY want line items
                        $lineItems = $this->lineItemsToExtract;

                        //Save our results into the array, basically add it on the END of the array - should only be ONE entry
                        $data = array_merge($data, $itemData);
                    }

                    //OK $data has all the fields, now output them to the csv file
                    //1.2 updated to output items by row or keep in one row, so we store this in an array instead of writing out

                    //fputcsv( $file, $data );
                }
                //If we're doing it by columns we need to wrap it in an array
                if ($this->settings->getProductGrouping() != "rows") {
                    $data = array($data);
                }
                //This keeps track of the row with the MOST items, so we know how big the header row needs to be!
                $maxItems = max($maxItems, $i);

                $output = array_merge($output, $data);
                //$output[] = $data;


            }


        }


        return array(
            "maxItems" => $maxItems,
            "max_order_num" => $max_order_num,
            "data" => $output
        );
    }

    /**
     * This function is called via AJAX and gets a chunk of data
     * It calls the main function where the main extract gets run
     * The purpose of this is to format the data coming from the form
     * into a common format soe the extract can use it
     *
     */

    public function JEMEXP_get_data_chunk_ajax()
    {
        check_ajax_referer('jemexp_saving_field');
        if (!current_user_can('administrator')) {
            wp_send_json_error(__('You are not allowed to run this action.', 'order-export-and-more-for-woocommerce'));
        }

        //We gather all the data
        $args = array();
        //Get the form fields
        $data = stripcslashes(urldecode(sanitize_text_field($_POST['export-data'])));

        //Load them into a data object
        $settings = json_decode($data, true);

        $this->settings->load_settings_from_array($settings['order_settings']);

        //$this->exportParams = json_decode($data, true);
        //load settings
        //$this->settings = jemxp_get_settings();
        $fields_to_export = $settings['order_settings']['fields_to_export'];

        if(!empty($fields_to_export))
		{
            $step = sanitize_text_field( $_POST['step'] );

            $settings['order_settings']['hook_code_valid'] = "";
            // save them
            update_option(JEMEXP_DOMAIN, json_encode($settings));

            //Now call the chunk!
            $ret = $this->JEMEXP_get_data_chunk($step);

            $settings['order_settings']['hook_code_valid'] = "1";
            // save them
            update_option(JEMEXP_DOMAIN, json_encode($settings));

        }
        else
        {
            $result = false;
            $msg = 'You need to select atleast one field to export.';
            $ret = array(
                'result'  => $result,
                'message' => $msg,
            );
        }
        wp_send_json($ret);

    }


    /**
     * This function is called as part of the main export and gets a chunk of data
     * This is where the main extract gets run
     * It is called from both the Browser (via the ajax function) and also via CRON
     *
     * @param $step - the step of the chunk
     * @return $result
     */

    public function JEMEXP_get_data_chunk($step)
    {
        //first step? Delete any transients from previous queries (just in case)
        //and also clear the temp file
        if ($step == 1) {
            delete_transient($this->query_transient);

            //If there is a file lingering around - zap it
            if (file_exists($this->tempFileName)) {
                unlink($this->tempFileName);
            }
        }
        //first lets see if we have a transient
        $transient = $this->load_transient();
        //if we didn't get a transient then we need to create the args
        if (false == $transient) {

            //we have no args so lets create them - it expects the export params as part of the constructor
            //to be loaded into $this->exportParams
            $this->create_args();

            //Set the max counters
            $this->maxItems = 1;
            $this->max_order_num = 0;

            //And save it!
            $this->save_transient();


        }

        //load settings
        //$this->settings = jemxp_get_settings();


        //By the time we get to here we either loaded the transient or created the fields we needed...

        //ok so we now have args, lets calc the offset
        //assume posts per page = 250
        //step 1 = offset 0 (0 * 25)
        //step 2 = offset 250 (1 * 250)
        //step3 = offset 500 (2 * 250)

        //@Simon 3.0 - if we're doing a preview we only reurn 25 records!!
        if ($this->settings->getPreview()) {
            $this->args['posts_per_page'] = 25;
        }


        $this->args['offset'] = $this->args['posts_per_page'] * ($step - 1);
        //*************************************
        //now get the data - RUN THE MAIN QUERY!
        //*************************************
        $orders = new WP_Query($this->args);
        // print_r($orders);
        //TODO need to cater for no records returned
        if (!$orders->have_posts()) {
            $result = array(
                'result' => true,
                'total' => 0,
                'message' => 'No records were returned'
            );
            //wp_send_json($result);
            return $result;
        }
        //We need to extract the fields from this data
        $result = $this->process_orders($orders);
        // print_r($result);
        //do we need to increment maxitems?
        if ($result['maxItems'] > $this->maxItems) {
            $this->maxItems = $result['maxItems'];
            $this->save_transient();
        }

        //Do we need to update max_order_num?
        if ($result['max_order_num'] > $this->max_order_num) {
            $this->max_order_num = $result['max_order_num'];
            $this->save_transient();
        }


        //Add the result to the file
        $file = fopen($this->tempFileName, "a");

        foreach ($result["data"] as $line) {
            fputcsv($file, $line, $this->settings->getDelimiter());

        }
        //$ret = fputcsv($file, $result["data"], $this->settings['delimiter'] );
        fclose($file);


        $total_posts = $orders->found_posts;

        //do we have all the data???

        //@Simon 3.0 are we doing a preview?
        if ($this->settings->getPreview() == true) {

            //Let's just send all the data back in a single message it should all fit!
            //Get the header row
            $headers = $this->create_header_order();
            //Convert from array to delimited string
            $headers = implode($this->settings->getDelimiter(), $headers);
            //Add the line break!
            $headers = $headers . PHP_EOL;
            //Get the main contents
            $fileContents = file_get_contents($this->tempFileName);

            $ret = array(
                'delimiter' => $this->settings->getDelimiter(),
                'order_count' => $orders->post_count,
                'headers' => $headers,
                'rows' => $fileContents,
                'result' => true
            );

            return $ret;

        }
        if (($orders->post_count + $this->args['offset']) >= $total_posts) {

            $complete = true;
            $download_url = add_query_arg('action', 'jemxp_download_batch_file', admin_url('admin-post.php'));

            //@simon 3.0
            //Update the saved order number if needed
            //If we're doing a preview we should not get here but just in case
            if ($this->settings->getExportNewOrders() == true &&
                $this->settings->getPreview() != true
            ) {
                $this->settings->setStartingFromNum($this->max_order_num);

                //save them
                update_option(JEMEXP_DOMAIN, $this->settings);

            }

            //And we need to add the header row to the top of the file..
            //Get the header row
            $headers = $this->create_header_order();
            //Convert from array to delimited string
            $headers = implode($this->settings->getDelimiter(), $headers);
            //Add the line break!
            $headers = $headers . PHP_EOL;
            //Get the main contents
            $fileContents = file_get_contents($this->tempFileName);

            //open, write the header & contents and close all in one
            file_put_contents($this->tempFileName, $headers . $fileContents);


        } else {
            $complete = false;
            $download_url = add_query_arg('url', '', admin_url());
        }

        $progress = $orders->post_count + $this->args['offset'];

        $progress = ($progress / $total_posts) * 100;

        $progress = intval($progress);

        //for now just return good!
        $result = array(
            'result' => true,
            'complete' => $complete,
            'step' => $step,
            'progress' => $progress,
            'total' => $total_posts,
            'retrieved' => $this->args['offset'] + $orders->post_count,
            'url' => $download_url,
            'max_order_num' => $this->max_order_num
        );


        //wp_send_json($result);
        return $result;

    }

    /*
     * Writes out the file
     */
    function download_file()
    {


        $this->load_transient();

        $output_fileName = $this->settings->getFilename();

        $output_fileName = str_replace('{{date}}', date('Y_m_d'), $output_fileName);
        $output_fileName = str_replace('{{time}}', date('H_i_s'), $output_fileName);
        $output_fileName = str_replace('{{type}}', $this->id, $output_fileName);

        $file = fopen($this->tempFileName, 'r');
        $contents = fread($file, filesize($this->tempFileName));
        $r = fclose($file);

        $this->write_headers($output_fileName);

        //Simon 3.0 - we now do this when we are complete!

        //Create the CSV header (column) row
        //do we need to create a new header row?
//        $header = $this->create_header_order();
//

//        //Write out the header
//        fputcsv($file, $header, $this->settings->getDelimiter());


        //now write it out
        $file = @fopen('php://output', 'w');

        fwrite($file, $contents);

        fclose($file);


    }


    /**
     */

    /**
     * This function gets the fields and various data items from the form
     * It returns null if it worked ok otherwise an error message
     * TODO - taken from JEM Exporter - at some point we will want to refactor
     * @param $data
     * @return null|string|void
     */
    function get_data_from_form($data)
    {

        $this->exportParams = json_decode($data, true);
        //load settings
        //$this->settings = jemxp_get_settings();


        return null;
    }


    /**
     * Extracts the main order fields and adds them to the array
     * this is where the hard work of getting the data out occurs
     * Additionally do any formatting here....
     * @param $exportFields
     * @param $order_details
     * @param $common
     * @param $item
     * @return array
     */
    private function build_extract_row($exportFields, $order_details, $common, $item)
    {

        $data = array();

        //we may want to get product meta - null indicates we have NOT retrieved it
        $this->productMeta = null;
        //$productMeta = null;

        //We may want to get the product - null indicates we have not retrieved it
        $product = null;

        // Go thru each field
        foreach ($exportFields as $key => $field) {

            switch ($field['id']) {

                case 'order_id' :
                    array_push( $data, apply_filters('jemexp_field_order_id',$common ['order_id'],$order_details,$item) );
                    break;

                case 'order_date' :
                    //format the date according to the user setting
                    $date = new DateTime($common ['order_date']);
                    $date = $date->format($this->settings->getDateFormat());
                    array_push( $data, apply_filters('jemexp_field_order_date',$date,$order_details,$item) );
                    break;

                case 'order_status' :
                    array_push( $data, apply_filters('jemexp_field_order_status',$common ['order_status'],$order_details,$item) );
                    break;

                case 'customer_name' :
                    array_push( $data, apply_filters('jemexp_field_customer_name',$common ['customer_name'],$order_details,$item) );
                    break;

                case '_customer_email' :
                    array_push( $data, apply_filters('jemexp_field_customer_email',$common ['_customer_email'],$order_details,$item) );
                    break;

                case '_order_total' :
                    array_push( $data, apply_filters('jemexp_field_order_total',$common ['_order_total'],$order_details,$item) );
                    break;

                case '_order_shipping' :
                    array_push( $data, apply_filters('jemexp_field_order_shipping',$common ['_order_shipping'],$order_details,$item) );
                    break;

                case '_order_shipping_tax' :
                    array_push( $data, apply_filters('jemexp_field_order_shipping_tax',$common ['_order_shipping_tax'],$order_details,$item) );
                    break;

                case '_order_currency' :
                    array_push( $data, apply_filters('jemexp_field_order_currency',$common ['_order_currency'],$order_details,$item) );
                    break;

                case '_order_discount' :
                    array_push( $data, apply_filters('jemexp_field_order_discount',$common ['_order_discount'],$order_details,$item) );
                    break;

                case 'coupon_code' :
                    //loop thru and make pipe delimited
                    $delim = "";
                    $ret = "";
                    foreach ($common['coupon_code'] as $coupon) {
                        $ret .= $delim . $coupon;
                        $delim = "|";
                    }
                    array_push( $data, apply_filters('jemexp_field_coupon_code',$ret,$order_details,$item) );
                    break;

                case '_payment_method' :
                    array_push( $data, apply_filters('jemexp_field_payment_method',$common ['_payment_method'],$order_details,$item) );
                    break;

                case '_payment_method_title' :
                    array_push( $data, apply_filters('jemexp_field_payment_method_title',$common ['_payment_method_title'],$order_details,$item) );
                    break;

                case '_shipping_method' :
                    //loop thru and make pipe delimited
                    $delim = "";
                    $ret = "";
                    foreach ($common['_shipping_method'] as $method) {
                        $ret .= $delim . $method['method_id'];
                        $delim = "|";
                    }
                    array_push( $data, apply_filters('jemexp_field_shipping_method',$ret,$order_details,$item) );
                    break;

                case '_shipping_method_title' :
                    array_push( $data, apply_filters('jemexp_field_shipping_method_title',$common ['_shipping_method_title'],$order_details,$item) );
                    break;

                case '_shipping_weight' :
                    //loop thru each item in the order
                    array_push( $data, apply_filters('jemexp_field_shipping_weight',$common ['_shipping_weight'],$order_details,$item) );
                    break;

                case 'customer_note' :
                    array_push( $data, apply_filters('jemexp_field_customer_note',$common ['customer_note'],$order_details,$item) );
                    break;


                case '_shipping_first_name' :
                    array_push( $data, apply_filters('jemexp_field_shipping_first_name',$common ['_shipping_first_name'],$order_details,$item) );
                    break;

                case '_shipping_last_name' :
                    array_push( $data, apply_filters('jemexp_field_shipping_last_name',$common ['_shipping_last_name'],$order_details,$item) );
                    break;

                case '_shipping_address_line1' :
                    array_push( $data, apply_filters('jemexp_field_shipping_address_line1',$common ['_shipping_address_line1'],$order_details,$item) );
                    break;

                case '_shipping_address_line2':
                    array_push( $data, apply_filters('jemexp_field_shipping_address_line2',$common ['_shipping_address_line2'],$order_details,$item) );
                    break;

                case '_shipping_city' :
                    array_push( $data, apply_filters('jemexp_field_shipping_city',$common ['_shipping_city'],$order_details,$item) );
                    break;

                case '_shipping_state' :
                    array_push( $data, apply_filters('jemexp_field_shipping_state',$common ['_shipping_state'],$order_details,$item) );
                    break;

                case '_shipping_country' :
                    array_push( $data, apply_filters('jemexp_field_shipping_country',$common ['_shipping_country'],$order_details,$item) );
                    break;

                case '_shipping_postcode' :
                    array_push( $data, apply_filters('jemexp_field_shipping_postcode',$common ['_shipping_postcode'],$order_details,$item) );
                    break;

                case '_billing_first_name' :
                    array_push( $data, apply_filters('jemexp_field_billing_first_name',$common ['_billing_first_name'],$order_details,$item) );
                    break;

                case '_billing_last_name' :
                    array_push( $data, apply_filters('jemexp_field_billing_last_name',$common ['_billing_last_name'],$order_details,$item) );
                    break;

                case '_billing_address_line1' :
                    array_push( $data, apply_filters('jemexp_field_billing_address_line1',$common ['_billing_address_line1'],$order_details,$item) );
                    break;

                case '_billing_address_line2' :
                    array_push( $data, apply_filters('jemexp_field_billing_address_line2',$common ['_billing_address_line2'],$order_details,$item) );
                    break;

                case '_billing_city' :
                    array_push( $data, apply_filters('jemexp_field_billing_city',$common ['_billing_city'],$order_details,$item) );
                    break;

                case '_billing_state' :
                    array_push( $data, apply_filters('jemexp_field_billing_state',$common ['_billing_state'],$order_details,$item) );
                    break;

                case '_billing_country' :
                    array_push( $data, apply_filters('jemexp_field_billing_country',$common ['_billing_country'],$order_details,$item) );
                    break;

                case '_billing_postcode' :
                    array_push( $data, apply_filters('jemexp_field_billing_postcode',$common ['_billing_postcode'],$order_details,$item) );
                    break;

                case '_billing_phone' :
                    array_push( $data, apply_filters('jemexp_field_billing_phone',$common ['_billing_phone'],$order_details,$item) );
                    break;

                case '_billing_email' :
                    array_push( $data, apply_filters('jemexp_field_billing_email',$common ['_billing_email'],$order_details,$item) );
                    break;

                //************************************************************************************
                // These are the line item fields
                //************************************************************************************
                case 'product_id' :
                    $pid = is_callable( array( $item, 'get_product_id' ) ) ? $item->get_product_id() : 0 ;
                    array_push( $data, apply_filters('jemexp_field_product_id',$pid,$order_details,$item) );
                    break;

                case 'sku' :
                    $product = $this->maybe_get_product_from_item($product, $item);
                    $sku = $product->get_sku();
                    array_push( $data, apply_filters('jemexp_field_sku',$sku,$order_details,$item) );
                    break;

                case 'product_categories' :
                    $product = $this->maybe_get_product_from_item($product, $item);
                    $cats = wc_get_product_category_list($item->get_product_id(), "|", " ", " ");
                    $cats = strip_tags($cats);
                    array_push( $data, apply_filters('jemexp_field_product_categories',$cats,$order_details,$item) );
                    break;

                case 'item_name' :
                    array_push( $data, apply_filters('jemexp_field_item_name',$item['name'],$order_details,$item) );
                    break;

                case 'item_qty' :
                    array_push( $data, apply_filters('jemexp_field_item_qty',$item['qty'],$order_details,$item) );

                    break;

                case 'item_subtotal' :
                    $temp = apply_filters('jemexp_field_item_subtotal',$item['line_subtotal'],$order_details,$item);
                    $temp = $this->format_price($temp);
                    array_push( $data, $temp );
                    break;

                case 'item_tax' :
                    $temp = apply_filters('jemexp_field_line_tax',$item['line_tax'],$order_details,$item);
                    $temp = $this->format_price($temp);
                    array_push( $data, $temp );
                    break;

                    case 'item_tax_rate' :
                        $temp = apply_filters('jemexp_field_item_tax_rate',$item['item_tax_rate'],$order_details,$item);
                        $temp = $this->format_price($temp);
                        array_push( $data, $temp );
                        break;

                    case 'item_total' :
                    //array_push ( $data, $item['line_total'] );
                    //Simon 2016-01-18
                    //Apparently Woo doesn't incl. tax here, although in the docs it says it does!!
                    //So lets call a function to get it - slower but seems the way Woo wants us to get data
                    $t = $order_details->get_item_total($item, true);
                    $t = $this->format_price($t);
                    array_push( $data, apply_filters('jemexp_field_item_total',$t,$order_details,$item) );
                    break;

                case 'item_variation' :


                    //lets see if we can get the meta - updtaed in woo 2.4
                    //$product = $order_details->get_product_from_item($item);
                    $product = $this->maybe_get_product_from_item($product, $item);

                    //$meta = new WC_Order_Item_Meta( $item['item_meta'], $product );
                    $meta = new WC_Order_Item_Meta($item, $product);


                    $meta_html = $meta->display(true, true, '_', ' | ');

                    //not all products have variations
                    if (!empty($meta_html)) {

                        //so the weird thing is the item knows it's variations, but when you spin up a WC_Product_variation it loses one!!!
                        array_push( $data, apply_filters('jemexp_field_item_variation',$meta_html,$order_details,$item) );
                    } else {
                        array_push( $data, apply_filters('jemexp_field_item_variation',"",$order_details,$item) );
                    }
                    break;

                case 'shipping_class' :

                    $product = $this->maybe_get_product_from_item($product, $item);

                    $class = $product->get_shipping_class();

                    array_push( $data, apply_filters('jemexp_field_shipping_class',$class,$order_details,$item) );
                    break;

                case 'product_type' :

                    $product = $this->maybe_get_product_from_item($product, $item);

                    $type = $product->get_type();

                    array_push( $data, apply_filters('jemexp_field_product_type',$type,$order_details,$item) );
                    break;

                case 'product_category' :

                    $product = $this->maybe_get_product_from_item($product, $item);

                    $ids = $product->get_category_ids();

                    //get the slugs
                    foreach ($ids as $k => $id) {
                        $term = get_term_by('id', $id, 'product_cat');
                        $ids[$k] = $term->name;
                    }
                    $cats = implode(" | ", $ids);

                    array_push( $data, apply_filters('jemexp_field_product_category',$cats,$order_details,$item) );
                    break;

                case 'tags' :


                    //$tags = wc_get_product_tag_list($item->get_product_id(), " | ");

                    $tags = get_the_terms($item->get_product_id(), "product_tag");

                    $ret = array();


                    foreach ($tags as $tag) {
                        $ret[] = $tag->name;
                    }

                    $ret = implode(" | ", $ret);

                    array_push( $data, apply_filters('jemexp_field_tags',$ret,$order_details,$item) );
                    break;

                //************************************************************************************
                // Everything else! Cater for the other types of meta - product, coupon, user
                //************************************************************************************
                default:

                    //OK we have a field that could be a line item type or something else. Let's process


                    //do we have to lookup PRODUCT meta?
                    if ($field['datatype'] == 'productmeta') {

                        //Do we need to get the product meta for this product?
                        if ($this->productMeta == null) {

                            //Do we have a variation
                            $id = $item->get_product_id();
                            $vid = $item->get_variation_id();

                            if($vid != 0){
                                $id = $vid;
                            }
                            $this->productMeta = $this->get_product_meta_for_product($id);
                        }

                        //OK so we either have a previously saved set of product meta or we just got it
                        //either way let's check if our field is there!
                        if (array_key_exists($field['id'], $this->productMeta)) {
                            $t = $this->productMeta[$field['id']];
                            array_push( $data, apply_filters('jemexp_field_'.$field['id'],$t->meta_value,$order_details,$item) );
                        } else {
                            array_push( $data, apply_filters('jemexp_field_'.$field['id'],'',$order_details,$item) );
                        }
                        break;
                    }

                    //LINE ITEM META
                    if ($field['datatype'] == 'lineitemmeta') {

                        //do we need to get the line item meta?
                        if ($this->lineItemMeta == null) {
                            $md = $item->get_meta_data();

                            //let's unpack the array into a simple array
                            foreach ($md as $key => $val) {
                                $itemMetaData = $md[$key]->get_data();
                                $k = $itemMetaData['key'];
                                $v = $itemMetaData['value'];

                                $this->lineItemMeta[$k] = $v;

                            }
                        }

                        //OK so we either have a previously saved set of line item meta or we just got it
                        //either way let's check if our field is there!
                        if (array_key_exists($field['id'], $this->lineItemMeta)) {
                            array_push( $data, apply_filters('jemexp_field_'.$field['id'],$this->lineItemMeta[$field['id']],$order_details,$item) );
                        } else {
                            array_push( $data, apply_filters('jemexp_field_'.$field['id'],'',$order_details,$item) );
                        }

                        break;

                    }


                    //CUSTOM FIELDS META
                    if ($field['datatype'] == 'customfields') {
                        array_push( $data, apply_filters('jemexp_field_'.$field['id'],'',$order_details,$item) );
                        break;

                    }


                    //If we have usermeta remove the "USER:" from the front
                    //We should find it in the common then
                    if ($field['datatype'] == 'usermeta') {
                        $field['id'] = str_replace("USER:", "", $field['id']);
                    }

                    //see if it is in the array if so use it, otherwise return an empty string..
                    if (array_key_exists($field['id'], $common)) {
                        array_push( $data, apply_filters('jemexp_field_user_'.$field['id'],$common[$field['id']],$order_details,$item) );

                    } else {
                        array_push( $data, apply_filters('jemexp_field_user_'.$field['id'],'',$order_details,$item) );
                    }

                    break;
            }//End switch

        }   //end foreach

        return $data;

    }

    function maybe_get_product_from_item($product, $item)
    {
        if ($product == null) {
            $product = wc_get_product($item->get_product_id());
        }

        return $product;
    }

    //This goes through the fields we are exporting and creates an array of JUST the line item fiwlds
    private
    function get_line_item_fields($fields)
    {
        $lineItems = array();
        foreach ($fields as $field) {
            if ($field['datatype'] == 'line_item' || $field['datatype'] == 'productmeta') {
                array_push($lineItems, $field);
            }
        }

        return $lineItems;

    }


    /*
     * This lopps through an array of line items and tries to find a value
     * Returns the value if found, otherwise NULL
     */
    function find_meta_item($needle, $haystack)
    {
        foreach ($haystack as $item) {
            if ($result = wc_get_order_item_meta($item->get_id(), $needle, true)) {
                return $result;
            }
        }

        //nothing found
        return null;
    }

    /**
     * This gets the common fields for the order
     * returns them in an array
     * We do this as if a user is outputting in rows we on;y do this once per order - otherwise we would
     * need to do it per row - so more efficient
     * @param $order_details
     * @param $order_items
     * @param $order_meta
     * @return array
     */
    private
    function extract_common_fields($order_details, $order_items, $order_meta)
    {

        $data = array();
        //we may want to get user meta - null indicates we have NOT retrieved it
        $userMeta = null;
        //Go thru each field
        // print_r($this->settings->getFieldsToExport());
        foreach ($this->settings->getFieldsToExport() as $key => $field) {
            switch ($field['id']) {

                case 'order_id' :
                    $data['order_id'] = is_callable(array($order_details, 'get_id')) ? $order_details->get_id() : $order_details->id;
                    break;

                case 'order_date' :
                    //$data['order_date'] = $order_details->order_date;
                    //v3 upgrade
                    $data['order_date'] = is_callable(array($order_details, 'get_date_created')) ? $order_details->get_date_created() : $order_details->date_created;
                    break;

                case 'order_status' :
                    $data['order_status'] = $order_details->get_status();
                    break;

                case 'customer_name' :
                    $data['customer_name'] = $this->get_customer_name(is_callable(array($order_details, 'get_id')) ? $order_details->get_id() : $order_details->id);
                    break;

                case '_customer_email' :
                    $data['_customer_email'] = is_callable(array($order_details, 'get_billing_email')) ? $order_details->get_billing_email() : $order_details->billing_email;
                    break;

                case '_order_total' :
                    $data['_order_total'] = $this->format_price($order_details->get_total());;
                    break;

                case '_order_shipping' :
                    //$data['_order_shipping'] = $order_details->order_shipping;
                    $data['_order_shipping'] = is_callable(array($order_details, 'get_shipping_total')) ? $order_details->get_shipping_total() : $order_details->order_shipping;
                    $data['_order_shipping'] = $this->format_price($data['_order_shipping'] );
                    break;


                case '_order_shipping_tax' :
                    //$data['_order_shipping_tax'] = $order_details->order_shipping_tax;
                    $data['_order_shipping_tax'] = $this->format_price( $order_details->get_shipping_tax() );
                    break;

                case '_order_currency' :
                    //$data ['order_ccy'] = $order_details->order_currency;
                    $data ['_order_currency'] = is_callable(array($order_details, 'get_currency')) ? $order_details->get_currency() : $order_details->order_currency;
                    break;

                case '_order_discount' :
                    //$data ['_order_discount'] = $order_details->cart_discount;
                    $data ['_order_discount'] = is_callable(array($order_details, 'get_discount_total')) ? $order_details->get_discount_total() : $order_details->cart_discount;
                    $data ['_order_discount'] = $this->format_price($data ['_order_discount']);
                    break;

                case 'coupon_code' :
                    $data ['coupon_code'] = $order_details->get_used_coupons();
                    break;

                case 'coupon_type' :
                    $data ['coupon_code'] = $order_details->get_used_coupons();
                    break;

                case '_payment_method' :
                    //$data ['payment_gateway'] = $order_details->payment_method_title;
                    $data ['_payment_method'] = is_callable(array($order_details, 'get_payment_method')) ? $order_details->get_payment_method() : $order_details->payment_method_title;
                    break;

                case '_payment_method_title' :
                    //$data ['payment_gateway'] = $order_details->payment_method_title;
                    $data ['_payment_method_title'] = is_callable(array($order_details, 'get_payment_method_title')) ? $order_details->get_payment_method_title() : $order_details->payment_method_title;
                    break;

                case '_shipping_method' :
                    $data ['_shipping_method'] = $order_details->get_shipping_methods();
                    break;

                case '_shipping_method_title' :
                    $data ['_shipping_method_title'] = $order_details->get_shipping_method();
                    break;

                case '_shipping_first_name' :
                    //$data ['_shipping_first_name'] = $order_details->shipping_first_name;
                    $data ['_shipping_first_name'] = is_callable(array($order_details, 'get_shipping_first_name')) ? $order_details->get_shipping_first_name() : $order_details->shipping_first_name;
                    break;

                case '_shipping_last_name' :
                    //$data ['_shipping_last_name'] = $order_details->shipping_last_name;
                    $data ['_shipping_last_name'] = is_callable(array($order_details, 'get_shipping_last_name')) ? $order_details->get_shipping_last_name() : $order_details->shipping_last_name;
                    break;


                case '_shipping_weight' :
                    //loop thru the items
                    $w = 0;

                    foreach ($order_items as $item) {
                        //get the product
                        $product = $order_details->get_product_from_item($item);

                        //Occasionally it turns out not to be an object
                        if (is_object($product)) {
                            if (!$product->is_virtual()) {
                                $w += $product->get_weight() * $item['qty'];
                            }
                        }

                    }
                    $data ['_shipping_weight'] = $w;
                    break;
                case 'customer_note' :
                    //$data ['customer_note'] = $order_details->customer_note;
                    $data ['customer_note'] = is_callable(array($order_details, 'get_customer_note')) ? $order_details->get_customer_note() : $order_details->customer_note;
                    break;


                case '_shipping_address_line1' :
                    //$data['shipping_addr_line1'] = $order_details->shipping_address_1;
                    $data['_shipping_address_line1'] = is_callable(array($order_details, 'get_shipping_address_1')) ? $order_details->get_shipping_address_1() : $order_details->shipping_address_1;
                    break;

                case '_shipping_address_line2' :
                    $data['_shipping_address_line2'] = is_callable(array($order_details, 'get_shipping_address_2')) ? $order_details->get_shipping_address_2() : $order_details->shipping_address_2;
                    break;
                case '_shipping_city' :
                    $data ['_shipping_city'] = is_callable(array($order_details, 'get_shipping_city')) ? $order_details->get_shipping_city() : $order_details->shipping_city;;
                    break;

                case '_shipping_state' :
                    $data ['_shipping_state'] = is_callable(array($order_details, 'get_shipping_state')) ? $order_details->get_shipping_state() : $order_details->shipping_state;
                    break;

                case '_shipping_country' :
                    $data ['_shipping_country'] = is_callable(array($order_details, 'get_shipping_country')) ? $order_details->get_shipping_country() : $order_details->shipping_country;
                    break;

                case '_shipping_postcode' :
                    $data ['_shipping_postcode'] = is_callable(array($order_details, 'get_shipping_postcode')) ? $order_details->get_shipping_postcode() : $order_details->shipping_postcode;
                    break;


                case '_billing_first_name' :
                    $data ['_billing_first_name'] = is_callable(array($order_details, 'get_billing_first_name')) ? $order_details->get_billing_first_name() : $order_details->billing_first_name;
                    break;

                case '_billing_last_name' :
                    $data ['_billing_last_name'] = is_callable(array($order_details, 'get_billing_last_name')) ? $order_details->get_billing_last_name() : $order_details->billing_last_name;
                    break;

                case '_billing_address_line1' :
                    $data['_billing_address_line1'] = is_callable(array($order_details, 'get_billing_address_1')) ? $order_details->get_billing_address_1() : $order_details->billing_address_1;
                    break;

                case '_billing_address_line2' :
                    $data['_billing_address_line2'] = is_callable(array($order_details, 'get_billing_address_2')) ? $order_details->get_billing_address_2() : $order_details->billing_address_2;
                    break;
                case '_billing_city' :
                    $data ['_billing_city'] = is_callable(array($order_details, 'get_billing_city')) ? $order_details->get_billing_city() : $order_details->billing_city;
                    break;

                case '_billing_state' :
                    $data ['_billing_state'] = is_callable(array($order_details, 'get_billing_state')) ? $order_details->get_billing_state() : $order_details->billing_state;
                    break;

                case '_billing_country' :
                    $data ['_billing_country'] = is_callable(array($order_details, 'get_billing_country')) ? $order_details->get_billing_country() : $order_details->billing_country;
                    break;

                case '_billing_postcode' :
                    $data ['_billing_postcode'] = is_callable(array($order_details, 'get_billing_postcode')) ? $order_details->get_billing_postcode() : $order_details->billing_postcode;
                    break;

                case '_billing_phone' :
                    $data ['_billing_phone'] = is_callable(array($order_details, 'get_billing_phone')) ? $order_details->get_billing_phone() : $order_details->billing_phone;
                    break;

                case '_billing_email' :
                    $data['_billing_email'] = is_callable(array($order_details, 'get_billing_email')) ? $order_details->get_billing_email() : $order_details->billing_email;
                    break;

                case '_customer_ip_address' :
                    $data['_customer_ip_address'] = is_callable(array($order_details, 'get_customer_ip_address')) ? $order_details->get_customer_ip_address() : $order_details->billing_email;
                    break;

                case 'user_login' :
                    //get the user ID
                    $id = $order_details->get_customer_id();
                    $userInfo = get_userdata($id);
                    if ($userInfo == false) {
                        $data['user_login'] = "";

                    } else {
                        $data['user_login'] = $userInfo->user_login;

                    }
                    break;

                case 'user_role' :
                    //get the user ID
                    $id = $order_details->get_customer_id();
                    $userInfo = get_userdata($id);

                    if ($userInfo == false) {
                        $data['user_role'] = "";

                    } else {
                        $roles = $userInfo->roles;
                        $data['user_role'] = $roles[0];

                    }
                    break;

                default:
                    //TODO - do we need to do this????

                    //Is it metadata
                    if ($field['datatype'] == 'postmeta') {
                        //is it in the order meta?
                        foreach ($order_meta as $metaData) {
                            if ($field['id'] == $metaData['meta_key']) {
                                $data[$field['id']] = $metaData['meta_value'];
                                break 2;  //Note the 2 so we break the foreach AND the switch
                            }
                        }
                    }

                    //do we have to lookup USER meta?
                    if ($field['datatype'] == 'usermeta') {

                        //We uhave "USER:" at the front for the user so let's get rid of it
                        $field['id'] = str_replace("USER:", "", $field['id']);

                        //Do we need to get the user meta for this order?
                        if ($userMeta == null) {
                            //first get the meta for this product
                            //TODO we should use some kind of cache here
                            $id = $order_details->get_customer_id();
                            $userMeta = array_map(function ($a) {
                                return $a[0];
                            }, get_user_meta($id));
                        }

                        if (isset($userMeta[$field['id']])) {
                            $data[$field['id']] = $userMeta[$field['id']];
                        } else {
                            $data[$field['id']] = "";
                        }

                        break;
                    }


                    //default is empty!
                    $data[$field['id']] = "";
                    break;

            }
        }
        return $data;
    }

    /**
     * Gets all the Product meta for an ID
     *
     * @param $pdctId - id of the product
     * @return array
     */
    public function get_product_meta_for_product($pdctId)
    {

        global $wpdb;

        $fields = $wpdb->get_results( $wpdb->prepare("select meta_key, meta_value from {$wpdb->postmeta} WHERE post_id = %d", $pdctId ), OBJECT_K );


        $ret = apply_filters('jemxp_get_product_meta_for_product', $fields);

        return $ret;
    }

    /**
     * This simply gets the status filters out of the data passed in
     */
    public
    function extract_status_filters()
    {

        $ret = array();

        //we do this so intellisense works!
        /** @var $data JEMEXP_Export_Data */
        $data = $this->settings;

        //If we didn't get ANY status then include them all
        //The WP_Query will NOT work unless you have at least one status
        $allStatuses = array();
        if ($data->getOrderStatus() == null || count($data->getOrderStatus()) == 0) {

            $status = wc_get_order_statuses();

            //the keys are the actual status we want!
            foreach ($status as $key => $val) {
                $allStatuses[] = $key;
            }

            //Save them in the export data object
            $data->setOrderStatus($allStatuses);
        }

        foreach ($data->getOrderStatus() as $key => $val) {
            array_push($ret, $val);
        }

        return $ret;
    }

    /**
     * Gets the date filter. We can have
     * No dates
     * Start only
     * End Only
     * Both
     */
    public
    function extract_date_filters()
    {

        $dq = array();

        //First NO dates
        //if ($this->exportParams['order_settings']['date_from'] == null && $this->exportParams['order_settings']['date_to'] == null) {
        if ($this->settings->getDateFrom() == null && $this->settings->getDateTo() == null) {
            return null;
        }

        //OK so now just set the before and afte IF they are populated
        if ($this->settings->getDateFrom() != null) {
            $dq['after'] = $this->settings->getDateFrom() . " 00:00:00";
        }

        if ($this->settings->getDateTo() != null) {
            $dq['before'] = $this->settings->getDateTo() . " 23:59:59";
        }

        if($this->settings->getSelectedRange() == 'predefined-range'){
            $predefined = $this->settings->getPredefinedDate();
            $pre_ranges = jemx_predefined_date_ranges_data();
            $dq['after'] = $pre_ranges[$predefined]['start_date'] . " 00:00:00";
            $dq['before'] = $pre_ranges[$predefined]['end_date'] . " 23:59:59";
        }

        $ret = array(
            $dq,
            'inclusive' => true
        );
        return $ret;
    }

    //TODO - I think I am making this obselete so we can delete it!
    /**
     * Lets get the Order filters - populate the filter variable in this object
     * Prefix is passed in, we use the prefix on the date pickers to keep them unique
     *
     * (non-PHPdoc)
     * @see BaseEntity::extract_filters()
     */
    public
    function extract_filters($post, $prefix = "")
    {


        //Preset Dates
        $this->filters['preset-date'] = (isset($post[$prefix . 'order-filter-date-radio']) ? $post[$prefix . 'order-filter-date-radio'] : '');

        //Date range
        $this->filters['start-date'] = (isset($post[$prefix . 'order-filter-start-date']) ? $post[$prefix . 'order-filter-start-date'] : '');
        $this->filters['end-date'] = (isset($post[$prefix . 'order-filter-end-date']) ? $post[$prefix . 'order-filter-end-date'] : '');


        //Order types to get

        $this->filters['order-status'] = array();

        //if we don't have any order statuses selected return false
        if (!isset($post['order-filter-order-status'])) {
            return __('There are no order statuses selected. Please select at least one order status', 'order-export-and-more-for-woocommerce');
        }
        foreach ($post['order-filter-order-status'] as $key => $val) {
            array_push($this->filters['order-status'], $val);
        }


        //Item formatting
        $this->filters['item-format'] = (isset($post[$prefix . 'order-filter-item-format']) ? $post[$prefix . 'order-filter-item-format'] : 'all');


        //get any products selected - blank means ALL
        $this->filters['products'] = array();

        if (isset($post['order-products-select'])) {
            foreach ($post['order-products-select'] as $selection) {
                $this->filters['products'][] = $selection;
            }
        }

        //get any coupons selected - blank means ALL
        $this->filters['coupons'] = array();

        if (isset($post['order-coupons-select'])) {
            foreach ($post['order-coupons-select'] as $selection) {
                $this->filters['coupons'][] = $selection;
            }
        }
    }


    /**
     * Gets the cust name for an oder
     * @param unknown $order_id
     * @return string
     */
    private
    function get_customer_name($order_id)
    {

        //no name?
        if (empty($order_id)) {
            return '';
        }

        $fname = get_post_meta($order_id, '_billing_first_name', true);
        $lname = get_post_meta($order_id, '_billing_last_name', true);

        return trim($fname . ' ' . $lname);


    }

    /**
     * This creates the header row JUST for the ORDER pieces
     * We add the ITEM pieces in later
     */
    private
    function create_header_order()
    {

        $data = array();

        $lineItems = array();

        $fields_to_export = $this->settings->getFieldsToExport();
        foreach ($fields_to_export as $key => $field) {

            //If it's a line item then save it
            if ($field['datatype'] == 'line_item' || $field['datatype'] == 'productmeta') {
                array_push($lineItems, $field['label']);
            }

            //do we have a custom label for this one?
            //TODO - is the placeholder getting passed over??
            //$val = ( isset($field['label'] ) ) ? $field['label'] : $this->fields[$key]['placeholder'];
            array_push($data, $field['label']);


        }

        //If we are outputting in COLUMNS then we need to repeat the lineitmes
        if ($this->settings->getProductGrouping() != "rows") {

            //Loop thru the max number of items we have
            for ($i = 0; $i < $this->maxItems - 1; $i++) {
                foreach ($lineItems as $item) {
                    array_push($data, $item);

                }
            }
        }

        return $data;
    }

    /**
     * This creates the ITEM pieces of the header row
     * @param int|number $qty - how may items to create
     * @return array
     */
    private
    function create_header_items($qty = 1)
    {

        // lets get the options for these labels
        $labels = get_option(JEMEXP_DOMAIN . '_' . $this->id . '_labels');

        $data = array();

        for ($i = 0; $i < $qty; $i++) {

            //Set the suffix if we have one order on a single row
            if ($qty > 1) {
                $suffix = "#" . strval($i + 1);
            } else {
                $suffix = "";
            }

            foreach ($this->settings->getFieldsToExport() as $key => $field) {

                //we only push include it if it is NOT an itemfield
                if ($field['datatype'] != 'line_item') {
                    continue;
                }

                //do we have a custum label for this one?
                //$val = ( isset($labels[ $key ] ) ) ? $labels[ $key ] : $this->fields[$key]['placeholder'];

                //$val = $val . $suffix;

                array_push($data, $field['label']);


            }


            //OK now lets add on the  product meta items
            if (isset($this->product_array)) {
                foreach ($this->product_array as $product) {
                    array_push($data, $product . $suffix);
                }
            }

            //The line item meta
            if (isset($this->item_meta_array)) {
                foreach ($this->item_meta_array as $item) {
                    array_push($data, $item . $suffix);
                }
            }
        }


        return $data;
    }


    /**
     * Converts price values into the appropriate format
     * @param $price
     * @return string
     */
    private
    function format_price($price)
    {

        //first see if a decimal separator has been saved. If not get one
        if ($this->decimalSeperator == "") {
            $separator = stripslashes(get_option('woocommerce_price_decimal_sep'));
            $this->decimalSeperator = $separator ? $separator : '.';
        }

        //now the number of decimal places
        if ($this->dp == null) {
            $this->dp = absint(get_option('woocommerce_price_num_decimals', 2));
        }

        //if the decimal separator is just a '.' then we already have it!
        if ($this->decimalSeperator == ".") {
            return $price;
        }


        //so lets get the new one
        $newPrice = number_format($price, $this->dp, $this->decimalSeperator, "");

        return $newPrice;
    }

    /**
     * This saves all the transient fields
     */
    private
    function save_transient()
    {

        //load the fields into an array
        $transient = array();
        $transient['export_data'] = serialize($this->settings);
        $transient['exportParams'] = $this->exportParams;   //TODO - do we still need this???
        $transient['args'] = $this->args;
        $transient['maxItems'] = $this->maxItems;
        $transient['max_order_num'] = $this->max_order_num;
        $transient['fields_to_export'] = $this->fields_to_export;
        $transient['meta'] = $this->meta;
        $transient['product'] = $this->product;
        $transient['item_meta'] = $this->item_meta;
        $transient['custom'] = $this->custom;
        $transient['filters'] = $this->filters;

        set_transient($this->query_transient, $transient, 600);

    }

    /**
     * Loads the transient fields
     */
    private
    function load_transient()
    {

        $ret = get_transient($this->query_transient);

        //check if we have a transient
        if (($ret == false) || ($ret['args'] == null)) {
            return false;
        }

        //ok so we have a transient - lets load it into the variables
        $this->settings = unserialize($ret['export_data']);
        $this->exportParams = $ret['exportParams'];
        $this->fields_to_export = $ret['fields_to_export'];
        $this->meta = $ret['meta'];
        $this->product = $ret['product'];
        $this->item_meta = $ret['item_meta'];
        $this->custom = $ret['custom'];
        $this->filters = $ret['filters'];
        $this->args = $ret['args'];
        $this->maxItems = $ret['maxItems'];
        $this->max_order_num = $ret['max_order_num'];
//        $this->order_meta_array = $ret['order_meta_array'];
//        $this->item_meta_array = $ret['item_meta_array'];
//        $this->product_array = $ret['product_array'];

        return true;

    }

    /**
     * Creates the HTML for the export page - this is where the Order specific stuff happens
     * @param $settings - this is the actual export settings
     * @return string
     */
    public
    function generate_export_html($settings)
    {

        //$this->settings = $settings;

        //create the top section & bottom buttons

        // Custom Query To Get Fields
        $custom_settings = $this->settings;
        $custom_fields_to_export = $this->settings->getFieldsToExport();
        ob_start();
        include('templates/export-date-range.php');
        $top_section_html = ob_get_clean();

        //Buttons
        ob_start();
        include('templates/export-bottom-buttons.php');
        $bottom_buttons_html = ob_get_clean();

        //now our basic details
        $html = $this->generate_basic_export($top_section_html, $bottom_buttons_html);
        return $html;
    }


    /**
     * Creates the HTML for an order export pages
     * It knows if we are running a single order screen
     * or building a scheduled one!
     * We reuse most of the HTML for the scheduled page and
     * add a few extra
     * @param $editing
     * @return string
     */
    public
    function generate_basic_export($top_section_html, $bottom_buttons_html)
    {

        //Get the metadata - they are used in the fields to export

        $basic_meta_data = $this->generate_basic_meta();
        $product_meta_data = $this->generate_product_meta();
        $user_meta_data = $this->generate_user_meta();
        $coupon_meta_data = $this->generate_coupon_meta();
        $line_item_meta_data = $this->generate_line_item_meta();

        //Let's get the filters ready
        $filter_html = $this->generate_order_filter_by_anything();

        //Let's get the list of fields to export
        $export_field_list = $this->generate_export_field_list();

        //Do we have any fields already chosen
        //TODO - this needs to get loaded from defaults/saved at somepoint
        $fields = array();
        if (

        is_array($this->settings->getFieldsToExport())
        ) {
            $fields = $this->settings->getFieldsToExport();
        }
        $f = "";
        foreach ($fields as $key => $val) {
            $f .= $this->generate_export_fields_from_settings($val['id'], $val['group'], $val['datatype'], $val['format'], $val['label'], $val['name']);
        }

        $export_fields_chosen = str_replace("\r\n", '', $f);
        //Do we have any FBA rows
        $existing_fba_rows = "";

        //We need a copy of the settings - the includes cannot access $this inside a function
        //At some point we should rethink how we do this

        $settings = $this->settings;

        ob_start();
        include('templates/export-basic-details.php');
        $html = ob_get_clean();

        return $html;

    }


    /**
     * This creates the <option> items for any multi select filters
     * It expects an array with two entries in each item id & label
     */
    public
    function generate_selected_filter($items)
    {

        $ret = "";
        //loop through all the save items
        foreach ($items as $key => $val) {
            $id = $val['id'];
            $label = $val['label'];

            $ret .= "<option value='{$id}' selected>{$label}</option>";
        }

        return $ret;
    }

    /**
     * Creates the <li> list of fields that can be exported
     */
    public
    function generate_export_field_list()
    {
        $fields = $this->load_fields();

        $ret = "";

        //This holds the group we're processing
        $group = "";

        foreach ($fields as $key => $value) {

            //do we have a new group
            if ($value['group'] != $group) {

                //if it's NOT our first group, close the previous
                if ($group != "") {
                    $ret .= "</div>";
                }

                //Save the group - replace space with dash and make it lowercase so we can
                //use it as an html class
                $group = $value['group'];
                $group_html = strtolower($value['group']);
                $group_html = str_replace(" ", "-", $group_html);
                $group_html .= "-group";

                //open the new group
                $ret .= "<div id='" . $group_html . "' style='display: none;' class='available-field-group'>";

            } //end of new group

            $ret .= $this->create_available_fields($value);
        }

        return $ret;
    }

    /**
     * This creates the HTML for any export fields which the user has saved as defaults to settings
     * TODO - this is also in the javascript - need to find a more elegant solution
     * @param string $id
     * @param string $group
     * @param string $datatype
     * @param string $format
     * @param string $label
     * @return string
     */
    public
    function generate_export_fields_from_settings($id = "", $group = "", $datatype = "", $format = "", $label = "", $name = "")
    {

        $ret = <<<ENDOFTEXT
<li class='ui-draggable ui-draggable-handle export-selected-field' data-key='$id'>
    <input type='hidden' class='jem-group' value='$group'>
    <input type='hidden' class='jem-id' value='$id'>
    <input type='hidden' class='jem-datatype' value='$datatype'>
    <input type='hidden' class='jem-format' value='$format'>
    <div class="selected-name">
        <i class="fa fa-bars" aria-hidden="true"></i>
$name <i class='tooltip_icon fa fa-question-circle' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='$id'></i></div>
    <div class="selected-placeholder">
        <input type=text class='placeholder-input' value='$label'>
    </div>

    <div class="selected-delete">
        <span class="fa fa-trash jem-delete-export-field" aria-hidden="true"></span>
    </div>

</li>


ENDOFTEXT;

        return $ret;
    }


    public
    function create_available_fields($value)
    {

        //TODO this is also in the javascript when we add a meta item
        //at some point we should go DRY and create a template - speed to market!
        //Can't we just copy it over?'
        $ret = "";

        $ret .= "<li class='li-state-default export-available-field' data-key='" . $value['name'] . "'>";
        $ret .= "<input type='hidden' class='jem-group' value='" . $value['group'] . "'>";
        $ret .= "<input type='hidden' class='jem-datatype' value='" . $value['data_type'] . "'>";
        $ret .= "<input type='hidden' class='jem-id' value='" . $value['name'] . "'>";
        $ret .= "<input type='hidden' class='jem-format' value='" . $value['type'] . "'>";
        $ret .= '<div class="selected-name">';
        $ret .= '<i class="fa fa-bars" aria-hidden="true"></i>';
        $ret .= $value['placeholder'];
        $ret .= " <i class='tooltip_icon fa fa-question-circle' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='".$value['name']."'></i></div>";

        $ret .= '<div class="selected-placeholder jem-hide">';
        $ret .= "<input type=text class='placeholder-input' value='" . $value['placeholder'] . "'>";
        $ret .= "</div>";

        $ret .= '<div class="selected-delete jem-hide">';
        $ret .= '<span class="fa fa-trash jem-delete-export-field" aria-hidden="true"></span>';
        $ret .= '</div>';

        $ret .= "</li>";

        return $ret;
    }

    public
    function generate_basic_meta()
    {
        $fields = $this->data_engine->get_order_basic_meta();

        $ret = "<select id='basic-meta' class='form-control jem-input-group-addon'>";
        $ret .= "<option value='' selected></option>";
        foreach ($fields as $val) {
            $ret .= "<option value='" . $val . "'>" . $val . "</option>";
        }

        $ret .= "</select>";

        return $ret;
    }

    /**
     * Generates product meta - icludes attributes etc as
     * well as order item meta from WooCommerce
     * @return string
     */
    public function generate_product_meta()
    {
        $productMeta = $this->data_engine->get_order_product_meta();

        //Think about this - I don't think we need it here
//        $wooItemMeta = $this->data_engine->get_woo_order_item_meta();
//
//        //Merge the arrays
//        $fields = array_merge($productMeta, $wooItemMeta);

        $ret = "<select id='product-meta' class='form-control jem-input-group-addon'>";
        $ret .= "<option value='' selected></option>";
        foreach ($productMeta as $val) {
            $ret .= "<option value='" . $val . "'>" . $val . "</option>";
        }

        $ret .= "</select>";

        return $ret;

    }

    public
    function generate_user_meta()
    {
        $fields = $this->data_engine->get_order_user_fields();

        $ret = "<select id='user-meta' class='form-control jem-input-group-addon'>";
        $ret .= "<option value='' selected></option>";
        foreach ($fields as $val) {
            $ret .= "<option value='USER:" . $val . "'>USER:" . $val . "</option>";
        }

        $ret .= "</select>";

        return $ret;

    }

    public
    function generate_coupon_meta()
    {
        $fields = $this->data_engine->get_order_coupon_fields();

        $ret = "<select id='coupon-meta' class='form-control jem-input-group-addon'>";
        $ret .= "<option value='' selected></option>";
        foreach ($fields as $val) {
            $ret .= "<option value='" . $val . "'>" . $val . "</option>";
        }

        $ret .= "</select>";

        return $ret;

    }


    public
    function generate_line_item_meta()
    {
        $fields = $this->data_engine->get_woo_order_item_meta();

        $ret = "<select id='line-item-meta' class='form-control jem-input-group-addon'>";
        $ret .= "<option value='' selected></option>";
        foreach ($fields as $val) {
            $ret .= "<option value='" . $val . "'>" . $val . "</option>";
        }

        $ret .= "</select>";

        return $ret;

    }

    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    private
    function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = $this->array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * This takes an array of settings and deeply applies
     * defaults to them
     * @param $settings
     * @return array $settings
     */
    public
    function apply_defaults($settings)
    {

        $settings = $this->array_merge_recursive_distinct(self::$default_settings, $settings);

        return $settings;
    }

}

?>
