<?php

defined('ABSPATH') || exit;

if (!class_exists('IZ_Purchase_Transaction', false)) {

    class IZ_Purchase_Transaction
    {

        public $gmt_offset;

        public function __construct()
        {
            if ('yes' == get_option('zettle_enable_purchase_processing')) {
                add_action('init', array($this, 'create_post_type'));
                add_action('manage_izettle_purchase_posts_custom_column', array($this, 'purchase_columns'), 10, 2);
                add_filter('manage_izettle_purchase_posts_columns', array($this, 'define_columns'));
                add_filter('manage_edit-izettle_purchase_columns', array($this, 'izettle_purchase_columns'));
                add_filter('bulk_actions-edit-izettle_purchase', array($this, 'define_bulk_actions'));
                add_filter('handle_bulk_actions-edit-izettle_purchase', array($this, 'handle_bulk_actions'), 10, 3);
                add_filter('izettle_insert_post', array($this, 'insert_post'), 10, 1);
                add_filter('izettle_update_post', array($this, 'izettle_update_post'), 10, 1);
                add_filter('post_row_actions', array($this, 'change_row_actions'), 10, 2);
                add_filter('post_date_column_status', array($this, 'change_date_headline'), 10, 4);
                add_filter('views_edit-izettle_purchase', array($this, 'adminViewsEdit'));
                add_action('admin_head', array($this, 'hide_add_new_button'));
                //   add_filter('woocommerce_admin_order_item_thumbnail', array($this, 'handle_bulk_actions'), 10, 3);
            }
        }

        public function hide_add_new_button()
        {
            if ('izettle_purchase' == get_post_type()) {
                echo '<style type="text/css">
            .page-title-action{display:none;}
            </style>';
            }
        }

        public function change_date_headline($status, $post, $column_name, $mode)
        {
            if ('izettle_purchase' == $post->post_type && 'date' == $column_name) {
                $status = '';
            }
            return $status;
        }

        public function remove_product_thumbnail($product_image, $item_id, $item)
        {
            return $product_image;
        }

        public function change_row_actions($actions, $post)
        {
            if ('izettle_purchase' == $post->post_type) {
                $actions = array();
            }
            return $actions;
        }

        public function define_columns($columns)
        {
            unset($columns['title']);
            unset($columns['date']);
            return $columns;
        }

        public function create_post_type()
        {
            $supports = false;

            $capabilities = array(

                "edit_post" => "edit_post",
                "read_post" => "read_post",

                "edit_posts" => "edit_posts",
                "edit_others_posts" => "edit_others_posts",
                "publish_posts" => "publish_posts",
                "read_private_posts" => "read_private_posts",

                "read" => "read",

                "edit_private_posts" => "edit_private_posts",
                "edit_published_posts" => "edit_published_posts",

                "delete_post" => "delete_post",
                "delete_posts" => "delete_posts",
                "delete_private_posts" => "delete_private_posts",
                "delete_published_posts" => "delete_published_posts",
                "delete_others_posts" => "delete_others_posts",

            );

            register_post_type('izettle_purchase',
                array(
                    'label' => __('Zettle purchases', 'woo-izettle-integration'),
                    'labels' => array(
                        'name' => __('Zettle purchases', 'woo-izettle-integration'),
                        'singular_name' => __('Zettle purchase', 'woo-izettle-integration'),
                        'search_items' => __('Search purchases', 'woo-izettle-integration'),
                        'menu_name' => __('Zettle purchases', 'woo-izettle-integration'),
                        'add_new' => __('Syncronize', 'woo-izettle-integration'),
                    ),
                    'public' => false,
                    'show_in_nav_menus' => true,
                    'show_in_admin_bar' => false,
                    'show_in_menu' => true,
                    'show_ui' => true,
                    'has_archive' => false,
                    'supports' => false,
                    'exclude_from_search' => true,
                    'register_meta_box_cb' => array($this, 'post_metabox_callback'),
                    'map_meta_cap' => true,
                    'capabilities' => $capabilities,
                )
            );
        }

        public function post_metabox_callback($post)
        {
            // remove_meta_box('submitdiv', 'izettle_purchase', 'side');
        }

        private function get_creation_date_gmt($item)
        {

            return $item->created ? $item->created : $item->timestamp;

        }

        private function get_creation_date($item)
        {

            $post_date_time = is_numeric($post_date_gmt = $this->get_creation_date_gmt($item)) ? $post_date_gmt : strtotime($post_date_gmt);
            $post_date = date('c', $post_date_time + (get_option('gmt_offset') * HOUR_IN_SECONDS));

        }

        private function get_content($item)
        {
            $content = json_encode($item, JSON_UNESCAPED_UNICODE);

            if (wc_string_to_bool(get_option('izettle_use_advanced_encoding'))) {
                $content = json_encode($item, JSON_INVALID_UTF8_IGNORE);
            } 

            return str_replace('\"', '', $content);

        }

        public function insert_post($uuid)
        {

            $post = get_page_by_title($uuid, 'OBJECT', 'izettle_purchase');

            $item = izettle_api()->get_purchase($uuid);

            WC_IZ()->logger->add(sprintf('insert_post: got purchase change %s', json_encode($item, JSON_INVALID_UTF8_IGNORE)));

            if (apply_filters('zettle_before_insert_purchase_post', true, $item, $post)) {

                if ($post) {
                    WC_IZ()->logger->add(sprintf('insert_post: Zettle purchase %s already synced in post %s', $uuid, $post->ID));
                    return false;
                }

                $post_id = wp_insert_post(
                    array(
                        'ID' => '',
                        'post_type' => 'izettle_purchase',
                        'post_title' => $item->purchaseUUID1,
                        'post_name' => $item->purchaseNumber,
                        'post_content' => $this->get_content($item),
                        'post_status' => 'publish',
                        'post_author' => '',
                        'post_date' => $this->get_creation_date($item),
                        'post_date_gmt' => $this->get_creation_date_gmt($item),
                        'comment_status' => 'closed',
                        'ping_status' => 'closed',
                    )
                );

                WC_IZ()->logger->add(sprintf('insert_post: Zettle purchase %s added to post %s', $item->purchaseUUID1, $post_id));

            }

            return $post_id;

        }

        public function izettle_update_post($post_id)
        {

            $uuid = get_the_title(get_post($post_id));

            if (!$uuid) {
                return $this->izettle_insert_post($uuid);
            }

            $item = izettle_api()->get_purchase($uuid);

            $post_id = wp_update_post(
                array(
                    'ID' => $post_id,
                    'post_type' => 'izettle_purchase',
                    'post_title' => $uuid,
                    'post_content' => $this->get_content($item),
                    'post_date' => $this->get_creation_date($item),
                    'post_date_gmt' => $this->get_creation_date_gmt($item),
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                )
            );

            WC_IZ()->logger->add(sprintf('izettle_update_post (%s): Zettle purchase "%s" updated post "%s"', $post_id, $item->purchaseUUID1, $post_id));
            return $post_id;

        }

        /**
         * remove views we don't need from post list
         * @param array $views
         * @return array
         */
        public function adminViewsEdit($views)
        {
            /* $views = array(
            'all' => $views['all'],
            );*/
            //WC_IZ()->logger->add($views);
            return $views;
        }

        public function handle_bulk_actions($redirect_to, $action, $ids)
        {
            foreach ($ids as $post_id) {
                do_action('izettle_process_' . $action, $post_id);
            }
            return esc_url_raw($redirect_to);
        }

        public function define_bulk_actions($actions)
        {
            unset($actions['edit']);
            $actions['wc_stockchange'] = __('Change stocklevel in WooCommerce', 'woo-izettle-integration');
            $actions['wc_order'] = __('Create order in WooCommerce', 'woo-izettle-integration');
            if (!empty(apply_filters('fortnox_get_pricelist', array()))) {
                $actions['fortnox'] = __('Change stocklevel in Fortnox', 'woo-izettle-integration');
            }
            $actions['remove_processed'] = __('Remove the processed information (stocklevels and orders will not be removed)', 'woo-izettle-integration');
            $actions['wc_stockchange_reverse'] = __('Reverse stocklevel change in WooCommerce', 'woo-izettle-integration');
            $actions['wc_order_update'] = __('Repair download (WooCommerce order updates if already created)', 'woo-izettle-integration');
            return $actions;
        }

        public function izettle_purchase_columns($columns)
        {
            $new_columns = array(
                "cb" => '<input type=""checkbox"" />',
                'purchase_number' => "Purchase",
                "purchase_time" => "Purchase time",
                "payment_type" => "Payment Type",
                "products" => "Products",
                "amount" => "Amount",
                "status" => "Status",
            );
            return array_merge($columns, $new_columns);
        }

        public function purchase_columns($column, $post_id)
        {
            global $post;

            $content = json_decode(WC_Zettle_Helper::fix_utf8_string($post->post_content));

            isset($content->refund) && $content->refund ? printf('<p style="color:Tomato;">') : '';

            $purchase_time = date('U', strtotime($content->created) + (get_option('gmt_offset') * HOUR_IN_SECONDS));

            switch ($column) {

                case 'purchase_number';
                    printf('#%s', $content->purchaseNumber);
                    break;
                case 'purchase_time';
                    printf('%s', date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $purchase_time));
                    break;
                case 'payment_type';
                    $payment_type = array();
                    foreach ($content->payments as $payment) {
                        if ($payment->type == 'IZETTLE_CARD') {
                            $payment_type[] = $payment->attributes->cardType;
                        } else {
                            $payment_type[] = $payment->type;
                        }
                    }
                    printf('%s', implode(', ', $payment_type));
                    break;
                case 'products':
                    $products = array();
                    foreach ($content->products as $product) {
                        $product_name = '';
                        if (isset($product->name)) {
                            $product_name = $product->name;
                        }
                        $products[] = $product->quantity . ' x ' . $product_name;
                    }
                    printf('%s', implode(', ', $products));
                    break;
                case 'amount':
                    printf('%s', wc_price(abs($content->amount) / 100, array('currency' => $content->currency)));
                    break;
                case 'status':
                    $changers = array(
                        '' => __('Not processed', 'woo-izettle-integration'),
                        'wc_stockchange' => __('WooCommerce stock change', 'woo-izettle-integration'),
                        'wc_order' => __('WooCommerce order', 'woo-izettle-integration'),
                        'wc_refund' => __('WooCommerce refund', 'woo-izettle-integration'),
                        'fortnox' => __('Fortnox stock change', 'woo-izettle-integration'),
                        'error' => __('Processing error', 'woo-izettle-integration'),
                        'partial_error' => __('Partial processing error', 'woo-izettle-integration'),
                        'remove_processed' => __('Ready to be re-processed', 'woo-izettle-integration'),
                        'reverse' => __('Processing reversed', 'woo-izettle-integration'),
                        'wc_order_update' => __('Download repaired', 'woo-izettle-integration'),
                    );

                    $status = get_post_meta($post_id, '_processed_with', true);
                    $tooltip = implode('<BR>', get_post_meta($post_id, '_processing_changes'));

                    if (!$tooltip) {
                        $timestamp = get_post_meta($post_id, '_processed_timestamp', true);
                        $tooltip = sprintf('%s', $timestamp ? date('Y-m-d H:i', $timestamp) : '');
                    }

                    if ($tooltip) {
                        printf('<mark class="purchase-status %s izettle-tip" data-tip="%s"><span>%s</span></mark>', esc_attr(sanitize_html_class('status-' . $status)), wp_kses_post($tooltip), esc_html($changers[$status]));
                    } else {
                        printf('<mark class="purchase-status %s"><span>%s</span></mark>', esc_attr(sanitize_html_class('status-' . $status)), esc_html($changers[$status]));
                    }
                    break;
                default:
                    break;
            }

            isset($content->refund) && $content->refund ? printf('</p>') : '';
        }
    }

    new IZ_Purchase_Transaction();

}
