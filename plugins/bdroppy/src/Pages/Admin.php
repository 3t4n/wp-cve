<?php


namespace BDroppy\Pages;



use BDroppy\Init\Core;
use BDroppy\Models\Product;
use BDroppy\Models\Order as OrderModel;
use BDroppy\Pages\EndPoints\AdminEndPoints;

class Admin
{
    protected $core;
    protected $config;
    protected $loader;

    private $page;
    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->config = $core->getConfig();
        $this->loader = $core->getLoader();

        new AdminEndPoints($core);
        $this->loadPages();

        $this->loader->addAction( 'restrict_manage_posts', $this, 'customProductFilters',99,99 );
        $this->loader->addAction( 'parse_query', $this, 'productFilterQuery',99,99 );

        $this->loader->addFilter('manage_edit-shop_order_columns',$this,'addRemoteOrderIdColumn');
        $this->loader->addAction('manage_shop_order_posts_custom_column',$this,'addRemoteOrderIdField');

//        $this->loader->addFilter('manage_edit-shop_order_columns',$this,'addBdroppyProductColumn');
        $this->loader->addAction('manage_product_posts_custom_column',$this,'addBdroppyProductField' , 99);

        $this->loader->addAction( 'add_meta_boxes', $this, 'addMetaBox' );
        $this->loader->addAction( 'woocommerce_email_before_order_table', $this, 'emailDisplay' ,0,4);
        $this->loader->addAction( 'woocommerce_view_order', $this, 'displayTrackingInfo' ,0,4);

        $this->loader->addShortCode( 'bdroppy_order_tracking', $this, 'DisplayShortCodeBdroppyOrderTracking');

    }


    public function DisplayShortCodeBdroppyOrderTracking($param)
    {
        global $order;

        $id = isset($param['id']) ? $param['id'] : $order->get_id();;
        $link = get_post_meta( $id, 'tracking_url', true );
        if (!empty($link)){
            return $link;
        }else{
            return "this order($id) not have Tracking Link";
        }
    }

    public function addMetaBox()
    {
        if(get_current_screen()->action != 'add'){
            add_meta_box( 'bdroppy-shipment-tracking', __( 'Bdroppy Shipment Tracking', 'bdroppy-shipment-tracking' ), [$this, 'metaBox'], 'shop_order', 'side', 'high' );
        }
    }

    public function metaBox()
    {
        global $post;
        $link = get_post_meta( $post->ID, '_bdroppy_shipment_tracking_items', true );
        echo '<br>';
        echo '<p class="preview_tracking_link"><a href="'.$link.'" target="_blank">' . __( 'Click here to track your shipment', 'woocommerce-shipment-tracking' ) . '</a></p>';
    }

    public function displayTrackingInfo( $order_id )
    {
        $link = get_post_meta( $order_id, '_bdroppy_shipment_tracking_items', true );
        wc_get_template( 'myaccount/view-order.php', array( 'tracking_items' => $link ), 'bdroppy/', BDROPPY_PATH . 'src/Pages/Template/OrderTracking/' );
    }

    public function emailDisplay( $order, $sent_to_admin, $plain_text = null, $email = null )
    {
        if ( is_a( $email, 'WC_Email_Customer_Refunded_Order' ) ) return;

        $order_id = is_callable([$order, 'get_id']) ? $order->get_id() : $order->id;
        $link = get_post_meta( $order_id, '_bdroppy_shipment_tracking_items', true );
        if ( true === $plain_text ) {
            wc_get_template( 'email/plain/tracking-info.php', array( 'tracking_items' => $link ), 'bdroppy/', BDROPPY_PATH . 'src/Pages/Template/OrderTracking/' );
        } else {
            wc_get_template( 'email/tracking-info.php', array( 'tracking_items' => $link ), 'bdroppy/', BDROPPY_PATH . 'src/Pages/Template/OrderTracking/' );
        }

    }

    public function customProductFilters($post_type )
    {
        $all_products = '';
        $bdroppy_products = '';
        $other_products = '';

        if( isset( $_GET['bdroppy_filter'] ) ) {
            switch( $_GET['bdroppy_filter'] ) {
                case 'all_products':
                    $all_products = ' selected';
                    break;

                case 'bdroppy_products':
                    $bdroppy_products = ' selected';
                    break;
                case 'other_products':
                    $other_products = ' selected';
                    break;
            }
        }
        if( $post_type == 'product' ) {
            echo '<select name="bdroppy_filter">';
            echo '<option value="all_products"' . $all_products . '>All Products</option>';
            echo '<option value="bdroppy_products"' . $bdroppy_products . '>Bdroppy Prodcuts</option>';
            echo '<option value="other_products"' . $other_products . '>Other Prodcuts</option>';
            echo '</select>';
        }
    }

    public function productFilterQuery($query)
    {
        $filter = isset($_GET['bdroppy_filter']) ? $_GET['bdroppy_filter'] : false;

        $q_vars    = &$query->query_vars;
        if ( isset($q_vars['post_type']) && $q_vars['post_type'] == 'product' && $filter )  {
            if($filter == 'bdroppy_products'){
                $q_vars['post__in'] = Product::where('wc_product_id','!=',0)->pluck('wc_product_id');
            }else if($filter == 'other_product')
            {
                $q_vars['post__not_in'] = Product::where('wc_product_id','!=',0)->pluck('wc_product_id');
            }
        }
    }

    public function addBdroppyProductField($column )
    {

        if ( 'name' === $column )
        {
            global $post;
            if (Product::where('wc_product_id',$post->ID)->count()){
                echo  "<span class='bdroppy_flag'>added By <b>Bdroppy</b></span>";
            }

        }
    }

    public function addRemoteOrderIdColumn($columns ){

        return array_slice($columns, 0, 2, true) +
            ["bdroppy_order_id" => "BDroppy Order id"] +
            array_slice($columns, 2, count($columns) - 1, true) ;

    }

    public function addRemoteOrderIdField($column )
    {
        if ( 'bdroppy_order_id' === $column )
        {
            global $post;
            $order = OrderModel::WooCommerceOrderId($post->ID);
            if (count($order)){
                echo $order[0]["rewix_order_id"];
            }else{
                echo 'Not Set';
            }
        }
    }


    public function loadPages()
    {
        if($this->config->api->isLogin())
        {
            $tab = isset($_GET['tab'])? $_GET['tab'] : '';
            switch ($tab){
                case 'status':
                    $this->page = new Status($this->core);
                    break;
                case 'catalog':
                    $this->page = new Catalog($this->core);
                    break;
                case 'category-mapping':
                    $this->page = new CategoryMapping($this->core);
                    break;
                case 'order':
                    $this->page = new Order($this->core);
                    break;
                case 'setting':
                    $this->page = new Setting($this->core);
                    break;
                default :
                    $this->page =  new Dashboard($this->core);
            }
        }else{
            $this->page = new Login($this->core);
        }


    }

}

