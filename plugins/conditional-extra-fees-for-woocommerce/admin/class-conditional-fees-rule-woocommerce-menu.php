<?php

class Pi_cefw_Menu{

    public $plugin_name;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));
    }

    function plugin_menu(){
        if(apply_filters('pisol_cefw_admin_sub_menu', false)){
            $this->menu = add_submenu_page(
                'woocommerce',
                __( 'Conditional fees'),
                __( 'Conditional fees'),
                'manage_options',
                'pisol-cefw',
                array($this, 'menu_option_page'),
                6
            );
        }else{
            $this->menu = add_menu_page(
                __( 'Conditional fees'),
                __( 'Conditional fees'),
                'manage_options',
                'pisol-cefw',
                array($this, 'menu_option_page'),
                plugin_dir_url( __FILE__ ).'img/pi.svg',
                6
            );
        }

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
        
    }

    public function bootstrap_style() {
        add_thickbox();
        wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/conditional-fees-rule-woocommerce-admin.css', array(), $this->version, 'all' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style( 'jquery-ui',  plugins_url('css/jquery-ui.css', __FILE__));

        wp_enqueue_script( $this->plugin_name."_toast", plugin_dir_url( __FILE__ ) . 'js/jquery-confirm.min.js', array('jquery'), $this->version);

        wp_enqueue_style( $this->plugin_name."_toast", plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.min.css', array(), $this->version, 'all' );

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/conditional-fees-rule-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

        wp_localize_script( $this->plugin_name, 'cefw_variables',
            array( 
                '_wpnonce' => wp_create_nonce( 'cefw-actions' )
            )
	    );

        wp_enqueue_script( $this->plugin_name.'-additional-charges', plugin_dir_url( __FILE__ ) . 'js/extra-charge-additional-charges.js', array( 'jquery' ), $this->version, false );
		
	}

    function menu_option_page(){
        ?>
        <div class="bootstrap-wrapper">
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
        include_once 'help.php';
    }

    function promotion(){
        if(isset($_GET['tab']) && $_GET['tab'] === 'pi_cefw_add_rule') return;
        ?>
        <div class="col-12 col-md-4 mt-3">

       <div class="bg-dark p-3 text-light text-center mb-3 promotion-bg">
                <h2 class="text-light "><span>Get Pro for<br><h1 class="h1 font-weight-bold text-light my-0"><?php echo PI_CEFW_PRICE; ?></h1> <h4 class="my-0">Buy Now !!</h4></h2>
                <a class="btn btn-sm btn-danger text-uppercase mb-2" href="<?php echo PI_CEFW_BUY_URL; ?>" target="_blank">Click to Buy Now</a> <a class="btn btn-sm mb-2 btn-warning text-uppercase" href="https://websitemaintenanceservice.in/con_fees_demo/" target="_blank">Try Pro on demo site</a>
                <div class="inside">
                All the rules of the free version are available in the pro as well:<br><br>
                    <ul class="text-left">
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Postcode:</strong> You can apply an extra charge based on the specific postcode or range of postcode</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Product Tags:</strong> So you can add an extra charge if the specific tag of the product are present in the cart</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Quantity of product from specific category:</strong> When the quantity of product from a specific category is as per your comparison rule then you apply the extra charge
</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Quantity of product from specific tag:</strong> When the quantity of product with a specific tag is as per your comparison rule then you apply the extra charge</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Payment method:</strong> Apply extra charge when the user selects a specific payment method</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Day of the week:</strong> Apply extra charge on the specific day of the week</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Shipping method based extra fees:</strong> Apply fees based on the shipping method selected by the customer</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">First order:</strong> Don't charge fees if its customer first order on your site</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Last order total:</strong> Don't charge fees if its customer last order total was of more then $100 </li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Number of order placed in period:</strong> Don't charge fees if its customer has placed more then 5 order during the current month</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Total amount spend during period:</strong> Don't charge fees if its customer has spend more then 500$ order during the current month</li>
                    <li class="border-top py-1 font-weight-light h6"><strong class="text-primary">Combine multiple fees</strong> in to a single fees
                    </li>
                    <li class="border-top py-1 font-weight-light h6">Add <strong class="text-primary">Tool tip</strong> to describe the fee so user know what is this extra amount for
                    </li>
                    </ul>
                    <a class="btn btn-light" href="<?php echo PI_CEFW_BUY_URL; ?>" target="_blank">Click to Buy Now</a>
                </div>
            </div>
            </div>
        <?php
    }

    function isWeekend() {
        return (date('N', strtotime(date('Y/m/d'))) >= 6);
    }

}