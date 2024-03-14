<?php

class Pi_Edd_Menu{

    public $plugin_name;
    public $version;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));
        add_action('admin_notices', array($this, 'criticalNotice'));
    }

    function criticalNotice(){
        $default_zone_id = get_option('pi_defaul_shipping_zone',0);
        if($default_zone_id == "" || $default_zone_id == 0 ){
            echo "<div class='notice notice-error is-dismissible'>
                        <h3>Estimate delivery date for Woocommerce</h3>
                        <p>You must select a <strong>Default shipping Zone</strong>, without this you wont see any estimated shipping date on the website </p>
                        <p>Go to <a href='".admin_url("admin.php?page=pi-edd&tab=basic_setting")."'>Plugin Settings</a> to correct this</p>
                        </div>";
        }

        if(!pisol_checking::checkZones()){
            echo "<div class='notice notice-error is-dismissible'>
                        <h3>Estimate delivery date for Woocommerce</h3>
                        <p>You must have shipping zones to use this setting, so create shipping zone in WooCommerce <a href='".admin_url("admin.php?page=wc-settings&tab=shipping")."'>Click here to set shipping zone</p>
                </div>";
        }
    }

    function plugin_menu(){
        
        $this->menu = add_submenu_page(
            'woocommerce',
            __( 'Estimate Date', 'pi-edd' ),
            'Estimate delivery date',
            'manage_options',
            'pi-edd',
            array($this, 'menu_option_page'),
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
 
    }

    public function bootstrap_style() {

		wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name."_calender", plugin_dir_url( __FILE__ ) . 'css/jquery-ui.multidatespicker.css', array(), $this->version, 'all' );
        wp_enqueue_script( $this->plugin_name."_calender", plugin_dir_url( __FILE__ ) . 'js/jquery-ui.multidatespicker.js', array('jquery','jquery-ui-core','jquery-ui-datepicker'), $this->version );
        wp_enqueue_script( $this->plugin_name."_jsrender", plugin_dir_url( __FILE__ ) . 'js/jsrender.min.js', array('jquery'), $this->version );
        wp_enqueue_script( $this->plugin_name."_translate", plugin_dir_url( __FILE__ ) . 'js/pisol-translate.js', array('jquery',$this->plugin_name."_jsrender"), $this->version );
        wp_enqueue_script( $this->plugin_name."_shipping", plugin_dir_url( __FILE__ ) . 'js/pisol-shipping.js', array('jquery',$this->plugin_name."_jsrender"), $this->version );
        wp_enqueue_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
        $calender = '
            jQuery(document).ready(function($){
                var saved_dates = $("#pi_edd_holidays").val();
                saved_dates = ((saved_dates != undefined && saved_dates != "") ? saved_dates.split(":") : ["1997/01/01"]);
                console.log(saved_dates);
                $("#pi-holiday-calender").multiDatesPicker({
                    dateFormat: "yy/mm/dd",
                    separator: ":",
                    onSelect: function(date){
                        var dates = $("#pi-holiday-calender").multiDatesPicker("getDates");
                        dates = dates.toString();
                        $("#pi_edd_holidays").val(dates.replace(/,/g, ":"));
                        pi_show_selected_dates();
                    },
                    maxPicks:6,
                    addDates: saved_dates
                });

                $("#reset-holidays").click(function(){
                    $("#pi_edd_holidays").val("");
                    $("#pi-holiday-calender").multiDatesPicker("resetDates");
                    pi_show_selected_dates();
                });

                pi_show_selected_dates();
               

                function pi_show_selected_dates(){
                    var saved_dates = $("#pi_edd_holidays").val();
                    var dates = ((saved_dates != undefined && saved_dates != "") ? saved_dates.split(":") : [])
                    dates = dates.map(function(date){ 
                        if(date != "1997/01/01"){
                            date = new Date(date);
                        return "<span class=\"bg-secondary py-2 px-3 text-light d-inline-block m-2\">"+date.toLocaleDateString("en-US")+"</span>"; 
                        }
                    });
                    $("#pi-selected-holidays").html(dates);
                }

            });
        ';
        wp_add_inline_script($this->plugin_name."_calender", $calender, 'after');

	}

    function menu_option_page(){
        if(function_exists('settings_errors')){
            settings_errors();
        }
        ?>
        <div class="bootstrap-wrapper">
        <div class="container mt-2">
            <div class="row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="row">
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/pi-web-solution.png"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center pisol-top-menu">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                                <a class=" px-3 text-light d-flex align-items-center  border-left border-right  bg-primary mr-0 ml-auto font-weight-bold" href="https://www.piwebsolution.com/woocommerce-estimated-delivery-date-per-product/">
                                <span class="dashicons dashicons-info"></span> User Guide
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pb-3 pt-0">
                    <div class="row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
    }

    function promotion(){
        ?>
        <?php if(  !is_plugin_active( 'estimate-delivery-date-for-woocommerce-pro/pi-edd-pro.php' ) ) : ?>
        
        <div class="col-12 col-sm-12 col-md-4 pt-3">
            
                <div class="bg-dark text-light text-center mb-3">
                    <a href="<?php echo PI_EDD_BUY_URL; ?>" target="_blank">
                    <?php  new pisol_promotion("pi_edd_installation_date"); ?>
                    </a>
                </div>
                <a href="javascript:void(0)" class="btn btn-danger btn-lg btn-block mb-3" id="hid-pro-feature">Hide Pro feature</a>

           <div class="bg-dark p-3 text-light text-center mb-3 promotion-bg">
                <h2 class="text-light mb-1"><span>Get Pro for<br><h1 class="h3 font-weight-bold text-light my-0"><?php echo PI_EDD_PRICE; ?></h1> <h4 class="py-1 my-0 text-light"></h4></h2>
                <a class="btn btn-danger btn-sm text-uppercase" href="<?php echo PI_EDD_BUY_URL; ?>" target="_blank">Buy Now !!</a><br>
                <a class="btn btn-light btn-sm text-uppercase mt-2" href="https://websitemaintenanceservice.in/edd_demo/" target="_blank">Try pro demo</a>
                <div class="inside mt-2">
                    PRO version offers more features:<br><br>
                    <ul class="text-left pisol-pro-feature-list">
                        <li class="border-top py-2 font-weight-light h6">Estimate date of the <strong class="text-primary">individual product</strong> based on the Product Preparation time of that product</li>
                        <li class="border-top py-2 font-weight-light h6">Estimate date <strong class="text-primary">cutoff time counter</strong> for product page estimate</li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">Estimate date of complete order</strong> as one date</li>
                        <li class="border-top py-2 font-weight-light h6">Change Estimate <strong class="text-primary">Date format</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Option to show estimate as a <strong class="text-primary">days count</strong></li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">Add unlimited holidays</strong></li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">Add Product preparation time</strong> in the estimated delivery date</li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">Option to specify exact product estimate date</strong>, if the product will be available to you on some future date for selling then you can't give estimate based on preparation time, in such case you can enter exact date E.g: if you have some seasonal product that comes on some fix date</li>
                        <li class="border-top py-2 font-weight-light h6">You can add different preparation time for <strong class="text-primary">each variation of a variable product</strong> </li>
                        <li class="border-top py-2 font-weight-light h6">Customize estimate messages with more control</li>
                        <li class="border-top py-2 font-weight-light h6">Disable Estimate on single <strong class="text-primary">Product</strong> page</li>
                        <li class="border-top py-2 font-weight-light h6">Disable Estimate on <strong class="text-primary">Category / Shop</strong> page</li>
                        <li class="border-top py-2 font-weight-light h6">Disable Estimate on the <strong class="text-primary">Cart</strong> page</li>
                        <li class="border-top py-2 font-weight-light h6">Disable Estimate on the <strong class="text-primary">Checkout</strong> page</li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">Multi-language support</strong>, add translation within the plugin</li>
                        <li class="border-top py-2 font-weight-light h6">Compatible with <strong class="text-primary">WPML, Polylang, and many more</strong> translation plugin</li>
                        <li class="border-top py-2 font-weight-light h6">Get response for support, well within <strong class="text-primary">24hr</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Estimated dates are included in <strong class="text-primary">order detail and order email</strong></li>
                        <li class="border-top py-2 font-weight-light h6">If you want you can count even today for calculating the estimate, This is done if the <strong class="text-primary">customer comes before the certain specified time</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Quick update the product preparation time, from the <strong class="text-primary">quick edit</strong> form of WordPress</li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">Disable estimate date</strong> for particular products</li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">One-click option</strong> to disable or enable estimate for all the product</li>
                        <li class="border-top py-2 font-weight-light h6">Add an estimated date in the <strong class="text-primary">order detail</strong> and order detail email send to customer and admin</li>
                        <li class="border-top py-2 font-weight-light h6">Show/hide complete order <strong class="text-primary">overall estimate on the checkout page</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Show/hide complete order <strong class="text-primary">overall estimate on the cart page</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Show overall order estimate as single date or range of date</li>
                        <!--<li class="border-top py-2 font-weight-light h6">It supports advanced dynamic shipping method plugin <strong class="text-primary"><a href="https://wordpress.org/plugins/advanced-free-flat-shipping-woocommerce" target="_blank" class="text-primary">WooCommerce conditional shipping & Advanced Flat rate shipping for WooCommerce</a></strong></li>-->
                        <li class="border-top py-2 font-weight-light h6">Show a <strong class="text-primary">different estimate when you are allowing for a back-order / allowing out of stock order</strong> (it will be the sum of product preparation time + extra time for out of order)</li>
                        <li class="border-top py-2 font-weight-light h6">Specify days in the week when your <strong class="text-primary">Shop/Shipping company is closed</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Show estimate below each of the supported shipping type methods, so user can select method as per there delivery requirement</li>
                        <li class="border-top py-2 font-weight-light h6">Compatible with <a href="https://wordpress.org/plugins/weight-based-shipping-for-woocommerce/" target="_blank" class="text-primary">WooCommerce Weight Based Shipping</a></li> 
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">Ajax loading of estimate</strong> of product/archive page to avoid page caching</li>
                        <li class="border-top py-2 font-weight-light h6">Have different wording for product estimate when the estimate date is next date E.g: <strong class="text-primary">Delivery by Tomorrow</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Have different wording for product estimate when the estimate date is same day E.g: <strong class="text-primary">Delivery by Today</strong></li>
                        <li class="border-top py-2 font-weight-light h6">Insert estimate message using short code [estimate_delivery_date id="product_id"]</li>
                        <li class="border-top py-2 font-weight-light h6"><strong class="text-primary">Add shipping icon</strong> in the estimate message shown on the product and archive page using {icon} short code, you can add custom icon image as well</li>
                    </ul>
                    <a class="btn btn-light" href="<?php echo PI_EDD_BUY_URL; ?>" target="_blank">Click to Buy Now</a>
                </div>
            </div>            
        </div>
        <?php endif; ?>
        <?php
    }

    function add() {
        if(date('N', strtotime(date('Y/m/d'))) >= 6){
            echo '<img class="img-fluid" src="'.plugin_dir_url( __FILE__ ).'img/weekends-6-file.svg">';
        }else{
            if(date('d')%2 == 0){
                include 'img/limited-2-file.php';
            }
        }
    }

}