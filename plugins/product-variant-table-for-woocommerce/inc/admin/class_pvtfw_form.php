<?php

if( !class_exists('PVTFW_FORM' )):

    class PVTFW_FORM {

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
        *====================================================
        * Layout Form
        *====================================================
        **/
        function layout(){

            $qty_layout = PVTFW_COMMON::pvtfw_get_options()->qty_layout;
            $showTableHeader = PVTFW_COMMON::pvtfw_get_options()->showTableHeader;
            $cartNotice = PVTFW_COMMON::pvtfw_get_options()->cartNotice;
            $scrollToTop = PVTFW_COMMON::pvtfw_get_options()->scrollToTop;
            $showSubTotal = PVTFW_COMMON::pvtfw_get_options()->showSubTotal;
            $fullTable = PVTFW_COMMON::pvtfw_get_options()->fullTable;
            $scrollableTableX = PVTFW_COMMON::pvtfw_get_options()->scrollableTableX;
            $table_min_width =  PVTFW_COMMON::pvtfw_get_options()->table_min_width;
            $available_title_text = PVTFW_COMMON::pvtfw_get_options()->available_title_text;
            $curTab = PVTFW_COMMON::pvtfw_get_options()->curTab;

            // print_r(PVTFW_COMMON::pvtfw_get_options());
    ?>
            <div class="form-section" id="layout">
                <h3>Element Settings</h3>
                <div class="detail"><?php _e('Control different elements like notices & input layout', 'product-variant-table-for-woocommerce'); ?></div>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php 
                                echo __('Quantity Field Layout', 'product-variant-table-for-woocommerce'); 
                            ?>
                        </th>
                        <td>
                            <select class="regular-ele-width" name='pvtfw_variant_table_qty_layout'>
                                <option value="basic"
                                    <?php echo $qty_layout == 'basic' ? "selected" : ''; ?>>Basic Input</option>
                                <option value="plus/minus"
                                    <?php echo $qty_layout == 'plus/minus' ? "selected" : ''; ?>>+/- Input</option>
                            </select>
                            <span class="red-remark"><?php _e("Note: Many themes remove default WooCommerce hooks. So, +/- input may not work on your theme.", "product-variant-table-for-woocommerce"); ?></span>
                        </td>
                    </tr>

                    <!-- After Element Hook -->
                    <?php do_action('pvtfw_admin_before_scroll_to_top'); ?>
                    <!-- After Element Hook -->
                    
                    <tr valign="top" data-child="redirect-child">
                        <th scope="row"><?php echo __('Scroll To Top', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <label><input type='checkbox' name='pvtfw_variant_table_scroll_to_top'
                                    <?php echo $scrollToTop ? "checked='checked'" : ''; ?> />
                                <?php echo __('Take me to top of page after product successfully carted', 'product-variant-table-for-woocommerce'); ?></label>
                            <span class="info-remark"><?php _e("Note: This feature will not work if you enabled <code>Redirect to the cart page after successful addition</code> from <code>WooCommerce > Settings > Products > General > Add to cart behaviour</code>.", "product-variant-table-for-woocommerce"); ?></span>
                        </td>
                    </tr>
                    <tr valign="top" data-child="redirect-child">
                        <th scope="row"><?php echo __('Cart Confirmation Notice', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <label><input type='checkbox' name='pvtfw_variant_table_cart_notice'
                                    <?php echo $cartNotice ? "checked='checked'" : ''; ?> />
                                <?php echo __('Display notice after product successfully carted', 'product-variant-table-for-woocommerce'); ?></label>
                            <span class="info-remark"><?php _e("Note: This feature will not work if you enabled <code>Redirect to the cart page after successful addition</code> from <code>WooCommerce > Settings > Products > General > Add to cart behaviour</code>.", "product-variant-table-for-woocommerce"); ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo __('Add Subtotal Column', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <label><input type='checkbox' name='pvtfw_variant_table_sub_total'
                                    <?php echo $showSubTotal ? "checked='checked'" : ''; ?> />
                                <?php echo __('Display Subtotal on changing quantity', 'product-variant-table-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <?php do_action('pvtfw_admin_element'); ?>
                </table>

                <!-- After Element Hook -->
                <?php do_action('pvtfw_admin_after_element'); ?>
                <!-- After Element Hook -->

                <!-- Table Settings -->

                <h3>Table Customization Options</h3>
                <div class="detail"><?php _e('Visual customizations of table header, width, scrollbar, etc.', 'product-variant-table-for-woocommerce'); ?></div>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php echo __('Table Header', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <label><input type='checkbox' name='pvtfw_variant_table_show_table_header'
                                    <?php echo $showTableHeader ? "checked='checked'" : ''; ?> />
                                <?php echo __('Show header of the variation table', 'product-variant-table-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo __('Available Options Title', 'product-variant-table-for-woocommerce'); ?><?php PVTFW_COMMON::badge(); ?></th>
                        <td>
                            <label><input type='checkbox' name='pvtfw_variant_table_show_available_options_text'
                                    <?php echo $available_title_text ? "checked='checked'" : ''; ?> />
                                <?php echo __('Show Available Options Title of the variation table', 'product-variant-table-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo __('Stop Table Breakdown ', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <label><input type='checkbox' name='pvtfw_variant_table_full_table'
                                    <?php echo $fullTable ? "checked='checked'" : ''; ?> />
                                <?php echo __('Keep table layout same as large screen', 'product-variant-table-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo __('Horizontal Scrollbar', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <label><input data-parent="scrollbar" type='checkbox' name='pvtfw_variant_table_scrollable_x'
                                    <?php echo $scrollableTableX ? "checked='checked'" : ''; ?> />
                                <?php echo __('Display a horizontal scrollbar with the table', 'product-variant-table-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <tr valign="top" data-child="scrollbar-child">
                        <th scope="row"><?php echo __('Table Minimum Width', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <input class="small-ele-width" type="number" name="pvtfw_variant_table_min_width"
                                value="<?php echo $table_min_width; ?>"><span>px</span>
                        </td>
                    </tr>
                </table>

                <!-- After Element Hook -->
                <?php do_action('pvtfw_admin_after_table_customization'); ?>
                <!-- After Element Hook -->
                
                <?php if($curTab == 'layout'): ?> 
                    <input type="hidden" name="pvtfw_variant_table_tab" value="layout">   
                <?php endif; ?>
            </div>
    <?php
        }


        /**
        *==========================================================================================
        * Callback function from plugin settng page. To display columns name in plugin setting page
        *==========================================================================================
        **/
        function create_list_of_columns($id) {
            $columns = PVTFW_COMMON::get_default_columns();
            $values = get_option('pvtfw_variant_table_columns',$columns);
            $labels = PVTFW_COMMON::get_columns_labels();
        ?>
        <ul style='margin-top: 5px;' class='checklist sortable' id='<?php echo $id; ?>'>
            <?php
            // print_r($columns);
            
                $i = 0;
                foreach ($values as $key => $value){ $i++;
                    $checked = " ";
                    if (isset($values[$key]) && $values[$key] == 'on') {
                        $checked = " checked='checked' ";
                    }
            ?>
                    <li id="list_item_<?php echo $i; ?>" class='ui-state-default'>
                        <span class="pvt-item-reorder-nav ui-sortable-handle">
                            &nbsp;
                        </span>
                        <label>
                            <input type='hidden' value='off' name='pvtfw_variant_table_columns[<?php echo $key; ?>]'>
                            <input type='checkbox' name='pvtfw_variant_table_columns[<?php echo $key; ?>]' <?php echo $checked; ?> />
                            <?php echo $labels[$key]; ?>
                        </label>
                        <div class="pvt-item-reorder-nav pvt-ud-arrow ui-sortable-handle">
                            <button type="button" class="pvt-move-up pvt-move-disabled" tabindex="-1" aria-hidden="true">Move up</button>
                            <button type="button" class="pvt-move-down" tabindex="0" aria-hidden="false">Move down</button>
                        </div>
                    </li> 
            <?php
                }
            ?>
        </ul>
        <?php
        }


        /**
        *====================================================
        * Setting Form
        *====================================================
        **/
        function setting(){
            $place = PVTFW_COMMON::pvtfw_get_options()->table_place;
            $showAvailableOptionBtn = PVTFW_COMMON::pvtfw_get_options()->showAvailableOptionBtn;
            $curTab = PVTFW_COMMON::pvtfw_get_options()->curTab;
    ?>
            <div class="form-section" id="settings">
                <h3><?php _e('Basic Settings', 'product-variant-table-for-woocommerce'); ?></h3>
                <div class="detail"><?php _e('Settings for positioning variation table', 'product-variant-table-for-woocommerce'); ?></div>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php echo __('Where to Place Variation Table', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <select class="regular-ele-width" name='pvtfw_variant_table_place'>
                                <option value="woocommerce_after_single_product_summary_9"
                                    <?php echo $place == 'woocommerce_after_single_product_summary_9' ? "selected" : ''; ?>>After
                                    product summary ( before description )</option>
                                <option value="woocommerce_before_single_product_summary_10"
                                    <?php echo $place == 'woocommerce_before_single_product_summary_10' ? "selected" : ''; ?>>Before
                                    single product summary ( before product summary )</option>
                                <option value="woocommerce_single_product_summary_11"
                                    <?php echo $place == 'woocommerce_single_product_summary_11' ? "selected" : ''; ?>>After product
                                    price</option>
                                <option value="woocommerce_single_product_summary_41"
                                    <?php echo $place == 'woocommerce_single_product_summary_41' ? "selected" : ''; ?>>After product
                                    short description</option>
                                <option value="woocommerce_after_single_product_summary_11"
                                    <?php echo $place == 'woocommerce_after_single_product_summary_11' ? "selected" : ''; ?>>After
                                    product description</option>
                                <option value="woocommerce_after_single_product_10"
                                    <?php echo $place == 'woocommerce_after_single_product_10' ? "selected" : ''; ?>>After
                                    single product</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <?php echo __('Select Columns to Show in the Variation Table', 'product-variant-table-for-woocommerce'); ?>
                            <a class="pvt-reset-column-link" onclick="return confirm('<?php esc_html_e( 'Are you sure to reset?', 'product-variant-table-for-woocommerce' ) ?>')" href="<?php echo $this->reset_link('pvtfw_reset_columns'); ?>" title="<?php _e('Reset Columns', 'product-variant-table-for-woocommerce'); ?>"><span class="dashicons dashicons-update-alt" style="color: #f80000;"></span></a>
                        </th>
                        <td>
                            <!-- Hello -->
                            <?php $this->create_list_of_columns('product-variant-table-columns');?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo __('Available Options Button', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <label><input type='checkbox' name='pvtfw_variant_table_show_available_options_btn'
                                    <?php echo $showAvailableOptionBtn ? "checked='checked'" : ''; ?> />
                                <?php echo __('Show "Available Options" button to scroll to the variation table when clicked', 'product-variant-table-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo __('Available Options Button Text', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <?php
                                    //Getting Available Button Text
                                    $available_btn_text =  PVTFW_COMMON::pvtfw_get_options()->available_btn_text;
                                    if(!$available_btn_text){
                                        $available_text = __('Available options', 'product-variant-table-for-woocommerce');
                                    } 
                                    else{
                                        $available_text = $available_btn_text;
                                    }

                                ?>
                            <input class="regular-ele-width" type="text" name="pvtfw_variant_table_available_options_btn_text"
                                value="<?php echo $available_text; ?>">
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo __('Cart Button Text', 'product-variant-table-for-woocommerce'); ?></th>
                        <td>
                            <?php
                                //Getting Cart Text
                                $btn_text =  PVTFW_COMMON::pvtfw_get_options()->cart_btn_text;
                                if(!$btn_text){
                                    $text = __('Add To Cart', 'product-variant-table-for-woocommerce');
                                } 
                                else{
                                    $text = $btn_text;
                                }

                            ?>
                            <input class="regular-ele-width" type="text" name="pvtfw_variant_table_cart_btn_text" value="<?php echo $text; ?>">
                        </td>
                    </tr>
                    <?php do_action('pvtfw_admin_main'); ?>
                </table>
                <?php if($curTab == 'settings' || $curTab == ''): ?> 
                    <input type="hidden" name="pvtfw_variant_table_tab" value="settings">
                <?php endif; ?>
            </div>
                
            <!-- After Main Hook -->
            <?php do_action('pvtfw_admin_after_main'); ?>
            <!-- After Main Hook -->
    <?php
        }


        /**
         * ====================================================
         * Update column position after drag n drop
         * ====================================================
         */
        function order_list(){
            global $pvtfw_variant_table_columns;

            $list = $pvtfw_variant_table_columns;
            $new_order = $_POST['list_item'];
            $new_list = array();

            foreach($new_order as $v){
                if(isset($list[$v])){
                    $new_list[$v] = $list[$v];
                }
            }
            update_option('pvtfw_variant_table_columns',$new_list);
        }

        /**
        * ====================================================
        * Perform Reset Columns Settings
        * ====================================================
        **/
        function reset_columns_setting(){

            // Condition starts from here

            if( isset( $_GET['action'] ) && ('pvtfw_reset_columns' === $_GET['action']) ){

                //In our file that handles the request, verify the nonce.
                $nonce = $_REQUEST['_wpnonce'];
                if ( ! wp_verify_nonce( $nonce, 'pvtfw-reset-column-settings' ) ) {
                    die( __( 'Security check', 'product-variant-table-for-woocommerce' ) ); 
                } else {
                    
                    delete_option('pvtfw_variant_table_columns');
                    update_option('pvtfw_variant_table_tab', '');
                    wp_safe_redirect( admin_url( 'admin.php?page=pvtfw_variant_table' ) );
                    exit();

                }

            }
            
        }

        /**
        * ====================================================
        * Perform Reset All Settings
        * ====================================================
        **/

        function reset_all_setting(){

            // Condition starts from here

            if( isset( $_GET['action'] ) && ('pvtfw_reset_all' === $_GET['action']) ){

                //In our file that handles the request, verify the nonce.
                $nonce = $_REQUEST['_wpnonce'];
                if ( ! wp_verify_nonce( $nonce, 'pvtfw-reset-all-settings' ) ) {
                    die( __( 'Security check', 'product-variant-table-for-woocommerce' ) ); 
                } else {
                    
                    $free_options_array = PVTFW_COMMON::plugin_options();

                    if( ( PVTFW_TABLE::is_pvtfw_pro_Active() ) ){
                        $pro_options_array = PVTFW_PRO_COMMON::plugin_options();
                        $options_array = array_merge( $free_options_array, $pro_options_array );
                    }
                    else{
                        $options_array = $free_options_array;
                    }

                    foreach ($options_array as $key => $option) {
                        delete_option($option);
                        continue;
                    }
                    wp_safe_redirect( admin_url( 'admin.php?page=pvtfw_variant_table' ) );
                    exit();

                }

            }
            
        }


        /**
         * ====================================================
         * Reset only columns order
         * ====================================================
         */

        public static function reset_link($action){
            // Making Nonce URL for Reset Link

            $current_page = 'pvtfw_variant_table';

            $nonce = wp_create_nonce( 'pvtfw-reset-all-settings' );
            if( $action === 'pvtfw_reset_columns' ){
                $nonce = wp_create_nonce( 'pvtfw-reset-column-settings' );
            }

            $reset_url_args = array(
                'action'   => $action,
                '_wpnonce' => $nonce,
            );

            $action_url_args = array(
                'page'    => $current_page,
            );

            $reset_url  = add_query_arg( wp_parse_args( $reset_url_args, $action_url_args ), admin_url( 'admin.php' ) );

            return $reset_url;
        }


        /**
        *====================================================
        * Register function
        *====================================================
        **/

        function register(){
            add_action('wp_ajax_list_update_order', array( $this, 'order_list'));
            add_action('pvtfw_admin_section', array($this, 'layout'), 97 );
            add_action('pvtfw_admin_section', array($this, 'setting'), 98 );
            add_action('pvtfw_admin_section', array($this, 'reset_columns_setting'), 99 );
            add_action('pvtfw_admin_section', array($this, 'reset_all_setting'), 99 );
        }

    }

    PVTFW_FORM::instance();

endif;