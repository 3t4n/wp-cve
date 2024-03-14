<?php // phpcs:ignore WordPress.NamingConventions
/**
 * YITH_WAPO_Blocks_List_Table Class.
 *
 * @package YITH\ProductAddOns
 * @since 4.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! class_exists( 'YITH_WAPO_Blocks_List_Table' ) ) {

    /**
     * YITH_WAPO_Blocks_List_Table Class
     *
     * @since 4.3.0
     */
    class YITH_WAPO_Blocks_List_Table extends WP_List_Table {

        /**
         * Construct
         *
         * @param array $args Args.
         * @author YITH <plugins@yithemes.com>
         * @since  2.0
         */
        public function __construct( $args = array() ) {
            parent::__construct(
                array(
                    'singular' => 'Blocks List',
                    'plural'   => 'Blocks Lists',
                    'ajax'     => false,
                )
            );

        }

        /**
         * Return the columns for the table
         *
         * @return array
         * @since  2.1.0
         */
        public function get_columns() {
            $columns = array(
                // translators: Column name in the block list.
                'name'       => esc_html__( 'Name', 'yith-woocommerce-product-add-ons' ),
                // translators: Column name in the block list.
                'priority'   => esc_html__( 'Priority', 'yith-woocommerce-product-add-ons' ),
                // translators: Column name in the block list.
                'show_on'    => esc_html__( 'Show on', 'yith-woocommerce-product-add-ons' ),
                // translators: Column name in the block list.
                'active'     => esc_html__( 'Active', 'yith-woocommerce-product-add-ons' ),
                'actions'    => esc_html__( '', 'yith-woocommerce-product-add-ons' ),
            );

            /**
             * APPLY_FILTERS: yith_wapo_block_list_columns
             *
             * Filter the columns of the blocks table.
             *
             * @param array $columns Columns
             *
             * @return array
             */
            return apply_filters( 'yith_wapo_block_list_columns', $columns );
        }

        /**
         * Column Default
         *
         * @param object|int $item The block.
         * @param string     $column_name Column name.
         */
        public function column_default( $block, $column_name ) {
            $output = '';

            if ( is_numeric( $block ) ) {
                $block = new YITH_WAPO_Block( array( 'id' => $block ) );
            }

            $nonce  = wp_create_nonce( 'wapo_action' );

            $block_id   = $block->get_id();
            $visibility = $block->get_visibility();

            switch ( $column_name ) {
                case 'name':

                    $url = add_query_arg(
                        array(
                            'page'     => 'yith_wapo_panel',
                            'tab'      => 'blocks',
                            'block_id' => $block_id,
                        ),
                        admin_url( 'admin.php' )
                    );
                    $output = '<a href=' . esc_url( $url ) . '>' . esc_html( $block->get_name() ) . '</a>';
                    break;
                case 'priority':
                    $priority = $block->get_priority() ;
                    $output   = apply_filters( 'yith_wapo_priority_on_column_default', esc_html( round( $priority ) ), $priority );
                    break;
                case 'show_on':

                    $included_products   = (array) $block->get_rule( 'show_in_products' );
                    $included_categories = (array) $block->get_rule( 'show_in_categories' );
                    $show_in             = $block->get_rule( 'show_in', 'all' );

                    if ( 'all' === $show_in ) {
                        // translators: Blocks table - Show on column.
                        $output = __( 'All products', 'yith-woocommerce-product-add-ons' );
                    } else {
                        $output =
                            // translators: Block options page, "Show on" column.
                            '<span class="wapo-text-dark">' . esc_html__( 'Products', 'yith-woocommerce-product-add-ons' ) . ': </span>';

                        if ( 'all' !== $show_in && is_array( $included_products ) ) {
                            foreach ( $included_products as $key => $value ) {
                                if ( $value > 0 ) {
                                    $_product = wc_get_product( $value );
                                    if ( is_object( $_product ) ) {
                                        $output .= '<a href="' . esc_attr( $_product->get_permalink() ) . '" target="_blank">'
                                            . esc_html( $_product->get_name() ). '</a>';
                                        if ( $key !== array_key_last( $included_products ) ) {
                                            $output .= ', ';
                                        }
                                    }
                                } else {
                                    $output .= '-';
                                }
                            }
                        } else {
                            $output =
                                // translators: Block options page, "Show on" column.
                            esc_html__( 'All products', 'yith-woocommerce-product-add-ons' );
                        }
                        $output .= '<br>';
                        $output .=
                            // translators: Block options page, "Show on" column.
                            '<span class="wapo-text-dark">' . esc_html__( 'Categories', 'yith-woocommerce-product-add-ons' ) . ': </span>';

                        if ( 'all' !== $show_in && is_array( $included_categories ) ) {
                            foreach ( $included_categories as $key => $value ) {
                                $category = get_term_by( 'id', $value, 'product_cat' );
                                if ( is_object( $category ) ) {
                                    $output .= '<a href="' . esc_attr( get_term_link( $category->term_id, 'product_cat' ) ) . '" target="_blank">'
                                        . esc_html( $category->name );
                                    if ( $key !== array_key_last( $included_categories ) ) {
                                        $output .= ', ';
                                    }
                                } else {
                                    $output .= '-';
                                }
                            }
                        } else {
                            $output =
                                // translators: Block options page, "Show on" column.
                            esc_html__( 'All categories', 'yith-woocommerce-product-add-ons' );
                        }
                    }



                    break;
                case 'active':

                    $output = yith_plugin_fw_get_field(
                        array(
                            'id'    => 'yith-wapo-active-block-' . $block_id,
                            'type'  => 'onoff',
                            'value' => '1' === $visibility ? 'yes' : 'no',
                        ),
                        true
                    );
                    break;
                case 'actions':

                    $actions = array(
                        'edit'   => array(
                            'title' => _x( 'Edit', '[ADMIN] Block list page (action)', 'yith-woocommerce-product-add-ons' ),
                            'action' => 'edit',
                            'url' => add_query_arg(
                                array(
                                    'page'     => 'yith_wapo_panel',
                                    'tab'      => 'blocks',
                                    'block_id' => $block_id,
                                ),
                                admin_url( 'admin.php' )
                            ),
                        ),
                        'duplicate' => array(
                            'title' => _x( 'Duplicate', '[ADMIN] Block list page (action)', 'yith-woocommerce-product-add-ons' ),
                            'action' => 'duplicate',
                            'icon' => 'clone',
                            'url'  => add_query_arg(
                                array(
                                    'page'        => 'yith_wapo_panel',
                                    'wapo_action' => 'duplicate-block',
                                    'block_id'    => $block_id,
                                    'nonce'       => $nonce,
                                ),
                                admin_url( 'admin.php' )
                            ),
                        ),
                        'delete' => array(
                            'title' => _x( 'Delete', '[ADMIN] Block list page (action)', 'yith-woocommerce-product-add-ons' ),
                            'action' => 'delete',
                            'icon' => 'trash',
                            'url'  => add_query_arg(
                                array(
                                    'page'        => 'yith_wapo_panel',
                                    'wapo_action' => 'remove-block',
                                    'block_id'    => $block_id,
                                    'nonce'       => $nonce,
                                ),
                                admin_url( 'admin.php' )
                            ),
                            'confirm_data' => array(
                                'title'               => _x( 'Confirm delete', '[ADMIN] Block list page (action)', 'yith-woocommerce-product-add-ons' ),
                                'message'             => _x( 'Are you sure you want to delete this block?', '[ADMIN] Block list page (action)', 'yith-woocommerce-product-add-ons' ),
                                'confirm-button'      => _x( 'Yes, delete', 'Delete confirmation action', 'yith-woocommerce-product-add-ons' ),
                                'confirm-button-type' => 'delete',
                            ),
                        ),
                        'move'   => array(
                            'title' => _x( 'Move', '[ADMIN] Block list page (action)', 'yith-woocommerce-product-add-ons' ),
                            'action' => 'move',
                            'icon' => 'drag',
                            'url'  => '#',
                        ),
                    );

                    $output = yith_plugin_fw_get_action_buttons( $actions, true );


                    break;

            }

            /**
             * APPLY_FILTERS: yith_wapo_block_list_output_column
             *
             * Filter the content of the default column in the blocks table.
             *
             * @param string     $output      Column output
             * @param string     $column_name Column name
             * @param WC_Product $product     Product object
             */
            echo wp_kses_post( apply_filters( 'yith_wapo_block_list_output_column', $output, $column_name, $block_id ) );
        }

        /**
         * Prepares the list of items for displaying.
         *
         * @uses WP_List_Table::set_pagination_args()
         *
         * @since 1.0.0
         */
        public function prepare_items() {
            if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                // _wp_http_referer is used only on bulk actions, we remove it to keep the $_GET shorter
                wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) ); // phpcs:ignore
                exit;
            }

            /**
             * APPLY_FILTERS: yith_wapo_blocks_list_per_page
             *
             * Filter the amount of items per page in the blocks table.
             *
             * @param int $per_page Number of items per page
             *
             * @return int
             */
            $per_page              = apply_filters( 'yith_wapo_blocks_list_per_page', 10 );
            $columns               = $this->get_columns();
            $hidden                = array();
            $sortable              = $this->get_sortable_columns();
            $this->_column_headers = array( $columns, $hidden, $sortable );

            $current_page = $this->get_pagenum();
            $status_type  = isset( $_GET['status_type'] ) ? sanitize_text_field( wp_unslash( $_GET['status_type'] ) ) : 'all';
            $visibility   = 'disabled' === $status_type ? 'no' : ( 'enabled' === $status_type ? 'yes' : false );

            $product   = isset( $_GET['yith_wapo_blocks_list_table_product'] ) ? sanitize_text_field( wp_unslash( $_GET['yith_wapo_blocks_list_table_product'] ) ) : '';
            $_product  = wc_get_product( $product );
            $variable  = null;
            if ( $_product instanceof WC_Product_Variation ) {
                $variable = $product;
            }

            if ( $product || $variable ) {
                $items       = YITH_WAPO_DB()->yith_wapo_get_blocks_by_product(
                    $product,
                    $variable,
                    $visibility, // visibility.
                    array(
                        'limit'  => $per_page,
                        'offset' => ( ( $current_page - 1 ) * $per_page ),
                        's'      => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    )
                );
            } else {
                $items       = YITH_WAPO_DB()->yith_wapo_get_blocks(
                    $visibility, // visibility.
                    array(
                        'limit'  => $per_page,
                        'offset' => ( ( $current_page - 1 ) * $per_page ),
                        's'      => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    )
                );
            }

            if ( $product || $variable ) {
                $total_items = count(
                    YITH_WAPO_DB()->yith_wapo_get_blocks_by_product(
                        $product,
                        $variable,
                        $visibility, // visibility.
                        array(
                            's'      => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                        )
                    )
                );
            } else {
                $total_items = count(
                    YITH_WAPO_DB()->yith_wapo_get_blocks(
                        $visibility, // visibility.
                        array(
                            's'      => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                        )
                    )
                );
            }

            $this->set_pagination_args(
                array(
                    'total_items' => $total_items,
                    'per_page'    => $per_page,
                    'total_pages' => ceil( $total_items / $per_page ),
                )
            );

            $this->items = $items;

        }

        /**
         * Generates content for a single row of the table.
         *
         * @since 3.1.0
         *
         * @param object|array $item The current item
         */
        public function single_row( $item ) {

            if ( is_numeric( $item ) ) {
                $item = new YITH_WAPO_Block( array( 'id' => $item ) );
            }

            $id       = $item->get_id();
            $priority = $item->get_priority();

            echo '<tr id=block-' . $id . ' class=block-element data-id=' . $id . ' data-priority=' . $priority . '>';
            $this->single_row_columns( $item );
            echo '</tr>';
        }

        /**
         * Return current view
         *
         * @return string
         * @since  1.0.0
         */
        public function get_current_view() {
            return empty( $_GET['status'] ) ? 'all' : sanitize_text_field( wp_unslash( $_GET['status'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        }

        /**
         * Message to be displayed when there are no items
         *
         * @since 3.1.0
         * @access public
         */
        function no_items() {
            // translators: When a user search in the blocks table and no blocks are found.
            _e( 'No blocks found', 'yith-woocommerce-product-add-ons' );
        }

        /**
         * Extra controls to be displayed between bulk actions and pagination.
         *
         * @param string $which Which.
         */
        protected function extra_tablenav( $which ) {
            if ( 'top' !== $which ) {
                return;
            }

            /**
             * APPLY_FILTERS: yith_wapo_blocks_list_filter_status
             *
             * Filter the array with the filter statuses for the blocks.
             *
             * @param array $statuses Filter statuses
             *
             * @return array
             */
            $filter_options = apply_filters(
                'yith_wapo_blocks_list_filter_status',
                array(
                    // translators: Status filter in the block list.
                    'all'         => esc_html__( 'All status', 'yith-woocommerce-product-add-ons' ),
                    // translators: Status filter in the block list.
                    'enabled'     => esc_html__( 'Enabled', 'yith-woocommerce-product-add-ons' ),
                    // translators: Status filter in the block list.
                    'disabled'    => esc_html__( 'Disabled', 'yith-woocommerce-product-add-ons' ),
                )
            );

            $status_type      = isset( $_REQUEST['status_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['status_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $product_selected = isset( $_GET['yith_wapo_blocks_list_table_product'] ) ? sanitize_text_field( wp_unslash( $_GET['yith_wapo_blocks_list_table_product'] ) ) : '';

            echo '<div class="alignleft actions">';

            yith_plugin_fw_get_field(
                array(
                    'id'      => 'yith_wapo_blocks_status_type',
                    'name'    => 'status_type',
                    'type'    => 'select',
                    'class'   => 'wc-enhanced-select',
                    'options' => $filter_options,
                    'value'   => $status_type,
                    'css'     => 'width: 150px',
                ), true
            );

            echo yith_plugin_fw_get_field(
                array(
                    'id'   => 'yith_wapo_blocks_list_table_product',
                    'name' => 'yith_wapo_blocks_list_table_product',
                    'type' => 'ajax-products',
                    'data' => array(
                        'action' => 'woocommerce_json_search_products_and_variations',
                        'security' => wp_create_nonce( 'search-products' )
                    ),
                    'value' => $product_selected,
                    'style'   => 'width: 270px;',
                )
            );

            // translators: Filter button in the block list.
            submit_button( esc_html__( 'Filter', 'yith-woocommerce-product-add-ons' ), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
            echo '</div>';

            wp_nonce_field( 'yith-wapo-blocks-table-list', 'yith_wapo_blocks_table_list' );
        }
    }
}
