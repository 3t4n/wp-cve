<?php

if( !class_exists('PVTFW_SETTINGS' )):

    class PVTFW_SETTINGS {

        protected static $_instance = null;

        function __construct(){

            $this->register();

        }


        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }


        /**
        * ====================================================
        * Settings menu item
        * ====================================================
        **/
        function menu() {
            $parent_slug = 'wpxtension'; // "Pages"
            $page_title = __( 'Product Variation Table', 'product-variant-table-for-woocommerce' );
            $menu_title = __( 'Variation Table', 'product-variant-table-for-woocommerce' );
            $capability = 'manage_options';
            $menu_slug  = 'pvtfw_variant_table';
            $page = array( $this, 'setting_page_callback' );
            
            add_submenu_page( 
                $parent_slug,
                $page_title,
                $menu_title,
                $capability,
                $menu_slug,
                $page
            );
        }


        /**
        *====================================================
        * Plugin Setting Page
        *====================================================
        **/
        function setting_page_callback(){ 
            global $pvtfw_form;

            if ( ! current_user_can( 'manage_options' ) ) {
                return;
            }
            
        ?>
    
            

            <div class="wrap pvtfw-variant-table-setting-wrap">
                <h1>
                    <?php echo __('Product Variation Table for WooCommerce Settings', 'product-variant-table-for-woocommerce'); ?> 
                    <?php echo apply_filters( 'pvtfw_version_title', sprintf( 
                        '<small class="wpx-version-title">%s</small>', 
                        defined( 'PVTFW_VARIANT_TABLE_VERSION' ) ? PVTFW_VARIANT_TABLE_VERSION : ''
                    ) ); ?>
                </h1>
                <?php 
                    settings_errors(); 
                    $curTab = PVTFW_COMMON::pvtfw_get_options()->curTab; 
                    $active = "";
                ?>
                <!-- Here are our tabs -->
                <nav class="nav-tab-wrapper">
                    <?php 
                        $tab = "<a href='#settings' data-target='settings' class='nav-tab ".($curTab==='settings' ? 'nav-tab-active' : ($curTab==='' ? 'nav-tab-active' : null))."'> ".__('Settings', 'product-variant-table-for-woocommerce')."</a>";

                        $tab .= "<a href='#layout' data-target='layout' class='nav-tab ".($curTab==='layout' ? 'nav-tab-active' : null)."'> ".__('Layout', 'product-variant-table-for-woocommerce')."</a>";
                        echo apply_filters('pvtfw_admin_setting_tab', $tab, $curTab);
                    ?>
                </nav>

                <div id="poststuff">

                    <div id="post-body" class="metabox-holder columns-2">

                        <!-- main content -->
                        <div id="post-body-content">

                            <div class="tab-content">
                                <form method="post" action="options.php">
                                    <?php settings_fields( 'pvtfw_variant_table_settings' ); ?>
                                    <?php do_settings_sections( 'pvtfw_variant_table_settings' ); ?>
                                    <?php //switch($tab) :
                                    // case 'layout';
                                            // Form of Layout
                                            // $pvtfw_form->layout();
                                        //break;
                                        //default:
                                            // if(pvtfw_table()->isPVTFWPROActive() == true):
                                            // Form of Setting
                                            // $pvtfw_form->setting();
                                            // endif;
                                            do_action('pvtfw_admin_section');
                                    // break;
                                        //endswitch; 
                                    ?>
                                   <p class="submit submitbox psfw-setting-btn">
                                        <?php submit_button( __( 'Save Settings', 'product-variant-table-for-woocommerce' ), 'primary', 'pvtfw-save-settings', false);  ?>
                                        <a onclick="return confirm('<?php esc_html_e( 'Are you sure to reset?', 'product-variant-table-for-woocommerce' ) ?>')" class="submitdelete" href="<?php echo esc_url( PVTFW_FORM::reset_link('pvtfw_reset_all') ) ?>"><?php esc_attr_e( 'Reset All', 'product-variant-table-for-woocommerce' ); ?></a>
                                    </p>
                                </form>                                    
                            </div>
                            <!-- post-body-content -->

                    </div>

                    <!-- sidebar -->
                    <?php 

                        WPXtension_Sidebar::sidebar_start(); 

                        // Documentation Block
                        WPXtension_Sidebar::block(
                            'dashicons dashicons-text-page',
                            'Documentation',
                            'To know more about settings, Please check our <a href="https://wpxtension.com/doc-category/product-variation-table-for-woocommerce/" target="_blank">documentation</a>'
                        ); 

                        // Help & Support Block
                        WPXtension_Sidebar::block(
                            'dashicons dashicons-editor-help',
                            'Help & Support',
                            'Still facing issues with Product Variation Table for WooCommerce? Please <a href="https://wpxtension.com/submit-a-ticket/" target="_blank">open a ticket.</a>'
                        ); 

                        // Rating Block
                        WPXtension_Sidebar::block(
                            'dashicons dashicons-star-filled',
                            'Love Our Plugin?',
                            'We feel honored when you use our plugin on your site. If you have found our plugin useful and makes you smile, please consider giving us a <a href="https://wordpress.org/support/plugin/product-variant-table-for-woocommerce/reviews/" target="_blank">5-star rating on WordPress.org</a>. It will inspire us a lot.'
                        ); 

                        // Shortcode Block
                        WPXtension_Sidebar::block(
                            'dashicons dashicons-shortcode',
                            'Shortcode',
                            '<code>[pvtfw_table_display]</code> <p>Want to pass product id? Use it like the following:</p> <code>[pvtfw_table_display id="91"]</code>'
                        ); 

                        WPXtension_Sidebar::sidebar_end(); 

                    ?>
                    <!-- #sidebar -->
                </div>
            </div>
        <?php }


        /**
        * ====================================================
        * Register function
        * ====================================================
        **/
        public function register(){
            add_action( 'admin_menu', array( $this, 'menu' ), 99 );
        }

    }

    $pvtfw_settings = PVTFW_SETTINGS::instance();

endif;