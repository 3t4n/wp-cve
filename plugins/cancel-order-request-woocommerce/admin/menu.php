<?php

class pisol_corw_menu{

    public $plugin_name;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));

        add_action( 'wp_ajax_pisol_bogo_search_product', array( $this, 'search_product' ) );
    }

    function plugin_menu(){
        
        $this->menu = add_submenu_page(
            'woocommerce',
            __( 'Cancel order request', 'cancel-order-request-woocommerce'),
            __( 'Cancel order request', 'cancel-order-request-woocommerce'),
            'manage_options',
            'pisol-cancel-order-request',
            array($this, 'menu_option_page')
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));

 
    }

    public function bootstrap_style() {
        wp_enqueue_script( $this->plugin_name."_quick_save", plugin_dir_url( __FILE__ ) . 'js/pisol-quick-save.js', array('jquery'), $this->version, 'all' );
    }


    function menu_option_page(){
        if(function_exists('settings_errors')){
            settings_errors();
        }
        ?>
        <div class="bootstrap-wrapper clear">
        <div class="container-fluid mt-2">
            <div class="row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="row">
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/pi-web-solution.png"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <?php do_action($this->plugin_name.'_tab'); ?>
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
        <div class="col-12 col-sm-4 pt-3">

            
            <div class="bg-primary p-3 text-light text-center mb-3 pi-shadow promotion-bg">
                <h2 class="text-light font-weight-light h3"><span>Get Pro for <h2 class="h2 font-weight-bold my-2 text-light"><?php echo PISOL_CORW_PRICE; ?></h2> Buy Now !!</span></h2>
                <a class="btn btn-sm mb-2 btn-info text-uppercase" href="<?php echo esc_url(PISOL_CORW_BUY_URL); ?>" target="_blank">Buy Now</a>
                <a class="btn btn-sm mb-2 btn-warning text-uppercase" href="http://websitemaintenanceservice.in/cancel_demo/" target="_blank">Try Pro on demo site</a>
             
                <div class="inside">
                    PRO version offer more advanced features like:<br><br>
                    <ul class="text-left  h6 font-weight-light pisol-pro-feature-list">
                    <li class="border-top py-2 h6 font-weight-light">Allow <strong class="text-uppercase font-weight-bold">Partial order Cancellation</Strong> request</li>
                    <li class="border-top py-2 h6 font-weight-light">Disable cancellation option for <strong class="text-uppercase font-weight-bold">specific product</strong></li>
                    <li class="border-top py-2 h6 font-weight-light">Allow user to <strong>upload image file</strong> along with cancellation request</li>
                    <li class="border-top py-2 h6 font-weight-light">Give option to <strong>Withdraw cancellation request</strong></li>
                    <li class="border-top py-2 h6 font-weight-light">Disable cancellation request option based on the <strong>Payment method</strong></li>
                    <li class="border-top py-2 h6 font-weight-light">Disable cancellation request option based on the <strong>Customer group</strong></li>
                    <li class="border-top py-2 h6 font-weight-light">Set <strong>default action</strong> on repeat order</li>
                    <li class="border-top py-2 h6 font-weight-light">Redirect to cart or checkout page once repeat order product are added in cart</li>
                    <li class="border-top py-2 h6 font-weight-light">Auto process refund and issue refund in the Wallet balance (Support TerraWallet plugin)</li>
                    </ul>
                    <a class="btn btn-light text-uppercase" href="<?php echo  PISOL_CORW_BUY_URL; ?>" target="_blank">Buy Now</a>
                </div>
            </div>
        </div>
        
        <?php
    }

}       