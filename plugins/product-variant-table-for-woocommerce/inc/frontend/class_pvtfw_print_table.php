<?php

if( !class_exists('PVTFW_PRINT_TABLE' )):

    class PVTFW_PRINT_TABLE {

        protected static $_instance = null;

        public function __construct(){
            $this->register();
        }

        public static function instance() {
            if( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
        *====================================================
        * Render Table 
        * 
        * @revised in 1.4.20
        *====================================================
        **/
        public function print_table(){

            // Default is `false` to apply table markup and feature
            if( apply_filters( 'disable_pvt_to_apply', false ) ){
                return;
            }

            global $product;

            if( is_a( $product, 'WC_Product_Variable' ) ) {  

                // Get Available variations?
                $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

                $available_variations = $get_variations ? $product->get_available_variations() : false;

                // Don't do anything if variable product has an issue with setup like- price is missing
                if ( empty( $available_variations ) && false !== $available_variations ){
                    return;
                }

                $product_id =  $product->get_id();

                $atts =  array(
                    'id' => $product_id
                );

        ?>
        <h2 id="variant-table">
            <?php echo apply_filters('pvtfw_variant_table_varaints_heading', __('Available Options', 'product-variant-table-for-woocommerce')) ?>
        </h2>

        <?php

        // Hook to display anything before the table
        do_action('pvtfw_variation_table_before');

        // Scrollable classes adding
        $scrollableTableX = PVTFW_COMMON::pvtfw_get_options()->scrollableTableX;
        if($scrollableTableX == 'on') {
            $data = ['pvt-scroll-x'];
            $classes = PVTFW_COMMON::container( $data );
        }
        else{
            $classes = "";
        }
        ?>
        <div class="pvtfw_init_variation_table">
            <?php

            /**
             * @hook: pvtfw_before_table_block
             * 
             * { Before table block }
             * 
             */
            do_action('pvtfw_before_table_block');

            ?>
            <div class="pvtfw_variant_table_block <?php echo apply_filters('pvtfw_table_container_class', $classes, $scrollableTableX); ?>">
                <table class="variant">
                    <thead>
                        <tr>
                            <?php 
                            $showTableHeader = PVTFW_COMMON::pvtfw_get_options()->showTableHeader;
                            if($showTableHeader == "on"):
                                /**
                                 * Hook: pvtfw_table_header.
                                 *
                                 * @hooked pvtfw_print_table_header - 29
                                 * (inc/table-parts/content-thead.php)
                                 */
                                do_action('pvtfw_table_header', $atts);
                            endif;
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            /**
                             * Hook: pvtfw_table_body.
                             *
                             * @hooked pvtfw_print_table_data - 29
                             * (inc/table-parts/content-tbody.php)
                             */
                            do_action('pvtfw_table_body', $atts);
                        ?>
                    </tbody>
                </table>
            </div>

            <?php

            /**
             * @hook: pvtfw_after_table_block
             * 
             * { After table block }
             * 
             */
            do_action('pvtfw_after_table_block');

            ?>

        </div>

        <?php
                // Hook to display anything after the table
                do_action('pvtfw_variation_table_after');

            }
            else {
                wp_dequeue_script('pvtfw-frontend-scripts');
                wp_dequeue_script('pvtfw-frontend-style');
            }
        }
        /**
        *====================================================
        * Render Table as shortcode
        * 
        * @param      array   $atts   The atts
        * 
        * @return     <mixed>  ( Either display variation table using the global `$product->get_id()` or Product ID parameter from the shortcode )
        * 
        * @revised in 1.4.20
        *====================================================
        **/
        public function shortcode_print_table( $atts ){

            if( !empty( $atts ) && isset( $atts["id"] ) ){

                ob_start();

                $get_product = wc_get_product( absint( $atts["id"] ) );

                if ( is_a( $get_product, 'WC_Product_Variable' ) ) {

                    // Get Available variations?
                    $get_variations = count( $get_product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $get_product );

                    $available_variations = $get_variations ? $get_product->get_available_variations() : false;

                    // Don't do anything if variable product has an issue with setup like- price is missing
                    if ( empty( $available_variations ) && false !== $available_variations ){
                        return;
                    }

                    // Attributes to pass product id. If null, pass the current product id. Otherwise, will get data using given product id
                    $atts = array(
                        'id' => $atts["id"]
                    );

                    ?>

                    <h2 id="variant-table">
                        <?php echo apply_filters('pvtfw_variant_table_varaints_heading', __('Available Options', 'product-variant-table-for-woocommerce')) ?>
                    </h2>

                    <?php

                    // Hook to display anything before the table
                    do_action('pvtfw_variation_table_before');

                    // Scrollable classes adding
                    $scrollableTableX = PVTFW_COMMON::pvtfw_get_options()->scrollableTableX;
                    if($scrollableTableX == 'on') {
                        $data = ['pvt-scroll-x'];
                        $classes = PVTFW_COMMON::container( $data );
                    }
                    else{
                        $classes = "";
                    }
                    ?>

                    <div class="pvtfw_init_variation_table">

                        <?php 

                            /**
                             * @hook: pvtfw_before_table_block
                             * 
                             * { Before table block }
                             * 
                             */
                            do_action('pvtfw_before_table_block');

                        ?>

                        <div class="pvtfw_variant_table_block <?php echo apply_filters('pvtfw_table_container_class', $classes, $scrollableTableX); ?>">
                            <table class="variant">
                                <thead>
                                    <tr>
                                        <?php 
                                        $showTableHeader = PVTFW_COMMON::pvtfw_get_options()->showTableHeader;
                                        if($showTableHeader == "on"):
                                            /**
                                             * Hook: pvtfw_table_header.
                                             *
                                             * @hooked pvtfw_print_table_header - 99
                                             * (inc/table-parts/content-thead.php)
                                             */
                                            do_action('pvtfw_table_header', $atts);
                                        endif;
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        /**
                                         * Hook: pvtfw_table_body.
                                         *
                                         * @hooked pvtfw_print_table_data - 99
                                         * (inc/table-parts/content-tbody.php)
                                         */
                                        do_action('pvtfw_table_body', $atts);                
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <?php

                        /**
                         * @hook: pvtfw_after_table_block
                         * 
                         * { After table block }
                         * 
                         */
                        do_action('pvtfw_after_table_block');

                        ?>

                    </div>


                    <?php
                    // Hook to display anything after the table
                    do_action('pvtfw_variation_table_after');

                    
                }
                else {
                    wp_dequeue_script('pvtfw-frontend-scripts');
                    wp_dequeue_script('pvtfw-frontend-style');
                }

                return ob_get_clean();

            }
            else{
                ob_start();
                $this->print_table();
                return ob_get_clean();
            }

        }

        /**
        *====================================================
        * Allocate table
        *====================================================
        **/
        public static function allocation() {

            $place = PVTFW_COMMON::pvtfw_get_options()->table_place;
            $priority = strrchr($place, "_");
            $table['place'] = str_replace($priority, "", $place);
            $table['priority'] = str_replace("_", "", $priority);
            // add_action($place, array($this, 'print_table'), $priority);
            return $table;
        }

        public function available_options_title( $default ){

            if( PVTFW_COMMON::pvtfw_get_options()->available_title_text === 'on' ){
                return $default;
            }

            return false;

        }


        /**
        *====================================================
        * Register
        *====================================================
        **/
        public function register(){

            // callback from allocaiton
            $table = self::allocation();
            add_shortcode( 'pvtfw_table_display', array( $this, 'shortcode_print_table') );
            add_action($table['place'], array($this, 'print_table'), $table['priority']);

            // Available Options title
            add_action( 'pvtfw_variant_table_varaints_heading', array( $this, 'available_options_title' ) );
        }

    }


    $pvtfw_print_table = PVTFW_PRINT_TABLE::instance();
    

endif;