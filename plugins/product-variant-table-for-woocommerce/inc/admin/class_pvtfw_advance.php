<?php

if( !class_exists('PVTFW_ADVANCE' )):

    class PVTFW_ADVANCE {

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
        *==========================================================================================
        * Category Exclude & Include Advance Feature
        *==========================================================================================
        **/
        function filter_setting(){

            $curTab = PVTFW_COMMON::pvtfw_get_options()->curTab;
        ?>
            <div class="form-section" id="advanced">

                <?php do_action('pvtfw_advance_section_before'); ?>

                <h3><?php _e('Exclude/Include Settings', 'product-variant-table-for-woocommerce') ?></h3>
                <div class="detail"><?php _e('Show/Hide variation table for a specific group of categories/devices', 'product-variant-table-for-woocommerce-pro'); ?></div>
                <a href="https://wpxtension.com/product/product-variation-table-for-woocommerce/" target="_blank">
                    <img src="https://ps.w.org/product-variant-table-for-woocommerce/assets/exclude_include.png" alt="Exclude/Imclude Settings">
                </a>

                <?php do_action('pvtfw_advance_section_after'); ?>

                <?php if($curTab == 'advanced'): ?> 
                    <input type="hidden" name="pvtfw_variant_table_tab" value="advanced">   
                <?php endif; ?>
            </div>
        <?php
        }

        /**
        *==========================================================================================
        * Bulk Cart Advance Feature
        *==========================================================================================
        **/
        function bulk_cart(){
        ?> 
            <h3><?php _e('Bulk Cart, Pagination, & Search Settings', 'product-variant-table-for-woocommerce-pro'); ?></h3>
            <div class="detail"><?php _e('Add bulk cart for table and search facility', 'product-variant-table-for-woocommerce-pro'); ?></div>
            <a href="https://wpxtension.com/product/product-variation-table-for-woocommerce/" target="_blank">
                <img src="https://ps.w.org/product-variant-table-for-woocommerce/assets/bulk_cart.png" alt="Bulk Cart Settings">
            </a>
        <?php
        }

        /**
        *==========================================================================================
        * Thumbnail Advance Feature
        *==========================================================================================
        **/
        function thumbnail_resize_setting(){
        ?>
            <h3><?php _e('Thumbnail Settings', 'product-variant-table-for-woocommerce-pro-pro'); ?></h3>
            <div class="detail"><?php _e('Set your thumbnail width, height and popup', 'product-variant-table-for-woocommerce-pro'); ?></div>
            <a href="https://wpxtension.com/product/product-variation-table-for-woocommerce/" target="_blank">
                <img src="https://ps.w.org/product-variant-table-for-woocommerce/assets/thumbnail.png" alt="Thumbnail Settings">
            </a>
        <?php
        }

        /**
        *====================================================
        * Adding new tab to the setting
        *====================================================
        **/

        function new_setting_tab($tab, $curTab){

            $tab .= "<a href='#advanced' data-target='advanced' class='nav-tab ".($curTab==='advanced' ? 'nav-tab-active' : null)."'>".PVTFW_COMMON::badge('Pro', 'return').__('Advanced', 'product-variant-table-for-woocommerce')."</a>";
            
            return $tab;

        }


        /**
        * ====================================================
        * Register function
        * ====================================================
        **/
        public function register(){

            if( ! PVTFW_TABLE::is_pvtfw_pro_Active() ):

                add_action('pvtfw_admin_section', array( $this, 'filter_setting' ), 99);

                add_action('pvtfw_advance_section_after', array( $this, 'bulk_cart' ), 99);
                add_action('pvtfw_advance_section_after', array( $this, 'thumbnail_resize_setting' ), 100);

            endif;

            // Adding Advance Tab
            add_filter('pvtfw_admin_setting_tab', array( $this, 'new_setting_tab' ), 10, 2);
        }

    }

    $pvtfw_advance = PVTFW_ADVANCE::instance();

endif;