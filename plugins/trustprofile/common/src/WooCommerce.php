<?php
namespace Valued\WordPress;

use Exception;
use ReflectionMethod;
use RuntimeException;
use WC_Customer;
use WC_Product_Factory;
use WP_Comment_Query;
use WC_Comments;

class WooCommerce {
    const DEFAULT_ORDER_STATUS = ['wc-completed'];
    const DO_NOT_SEND = 0;
    const AFTER_EVERY_ORDER = 1;
    const AFTER_FIRST_ORDER = 2;
    const POPUP_OPTION = 3;

    private $plugin;

    public function __construct(BasePlugin $plugin) {
        $this->plugin = $plugin;
        add_action('woocommerce_order_status_changed', [$this, 'orderStatusChanged'], 10, 3);
        add_action('woocommerce_checkout_update_order_meta', [$this, 'set_order_language']);
        add_action('woocommerce_product_options_sku', [$this, 'addGtinOption']);
        add_action('woocommerce_admin_process_product_object', [$this, 'saveGtinOption']);
        add_action('init', [$this, 'activateSyncReviews']);
        register_deactivation_hook($this->plugin->getPluginFile(), [$this, 'deactivateSyncReviews']);
        add_action($this->getReviewsHook(), [$this, 'syncReviews']);
        add_action('wp_ajax_' . $this->getManualSyncAction(), [$this, 'manualReviewSync']);
        add_action('wp_ajax_' . $this->getProductKeysAction(), [$this, 'getProductKeys']);
        add_action('wp_head', [$this, 'addOrderDataJsonThankYouPage']);
    }

    public function activateSyncReviews() {
        if (!$this->isSyncedToday() && $this->isProductReviewsEnabled()) {
            add_action('admin_notices', [$this, 'autoSyncNotice']);
        }
        if (!wp_next_scheduled($this->getReviewsHook())) {
            wp_schedule_event(time(), 'twicedaily', $this->getReviewsHook());
        }
    }

    public function deactivateSyncReviews() {
        wp_clear_scheduled_hook($this->getReviewsHook());
    }

    public function orderStatusChanged(int $order_id, string $old_status, string $new_status) {
        if ($this->statusReached($new_status)) {
            $this->sendInvite($order_id);
        }
    }

    public function autoSyncNotice() {
        if (get_admin_page_title() == $this->plugin->getName()) {
            $class = 'notice notice-info';
            $message = __('Automatic product review sync did not run in the last 24 hours. Make sure that you have cron jobs configured, or sync manually.', 'webwinkelkeur');
            printf('<div class="%s"><p>%s</p></div>', esc_attr($class), esc_html($message));
        }
    }

    public function set_order_language($order_id) {
        /** @var WC_Order $order */
        $order = wc_get_order($order_id);
        if (!$order->get_meta('wpml_language') && defined('ICL_LANGUAGE_CODE')) {
            $order->update_meta_data('wpml_language', ICL_LANGUAGE_CODE);
            $order->save_meta_data();
        }
    }

    private function sendInvite($order_id) {
        global $wp_version;

        // invites enabled?
        if (!$this->plugin->getOption('invite')) {
            return;
        }

        $api_domain = $this->plugin->getDashboardDomain();
        $shop_id = $this->plugin->getOption('wwk_shop_id');
        $api_key = $this->plugin->getOption('wwk_api_key');

        if (!$shop_id || !$api_key) {
            return;
        }

        /** @var WC_Order $order */
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        if ($order->get_type() !== 'shop_order') {
            return;
        }

        $order_number = $order->get_order_number();

        $email = $order->get_billing_email();
        if (!preg_match('|@|', $email)) {
            return;
        }

        if (!apply_filters('webwinkelkeur_request_invitation', true, $order)) {
            return;
        }

        $invoice_address = $order->get_address('billing');
        $customer_name = $invoice_address['first_name']
            . ' ' . $invoice_address['last_name'];

        $delivery_address = $order->get_address('shipping');
        $phones = [
            $invoice_address['phone'] ?? null,
            $delivery_address['phone'] ?? null,
        ];
        $lang = $order->get_meta('wpml_language');

        $data = [
            'order'     => $order_number,
            'email'     => $email,
            'delay'     => $this->getInviteDelay(),
            'language'  => $lang,
            'client'    => 'wordpress',
            'customer_name' => $customer_name,
            'phone_numbers' => array_values(array_filter(array_unique($phones))),
            'order_total'   => $order->get_total(),
            'plugin_version' => $this->get_plugin_version('webwinkelkeur'),
            'platform_version' => 'wp-' . $wp_version . '-wc-' . $this->get_plugin_version('woocommerce'),
        ];
        if ($this->plugin->getOption('invite') == 2) {
            $data['max_invitations_per_email'] = 1;
        }

        $with_order_data = !$this->plugin->getOption('limit_order_data') && is_callable([$order, 'get_data']);
        if ($with_order_data) {
            $order_arr = $this->get_data($order, []);
            $customer_arr = !empty($order_arr['customer_id']) ? $this->get_data(new WC_Customer($order_arr['customer_id']), []) : [];
            $products = $this->get_product_data($order_arr);
            $order_data = [
                'order' => $order_arr,
                'customer' => $customer_arr,
                'products' => $products,
                'invoice_address' => $invoice_address,
                'delivery_address' => $delivery_address,
            ];

            $data['order_data'] = json_encode($this->filter_data($order_data));
        }

        // send invite
        $api = new API($api_domain, $shop_id, $api_key);
        try {
            if ($this->plugin->getOption('invite') == self::POPUP_OPTION && !$api->hasConsent($order_number)) {
                $this->insert_comment($order_id, __('Invite was not send as customer did not consent.'));
                return;
            }
        } catch (WebwinkelKeurAPIError $e) {
            $this->logApiError($e);
            $this->insert_comment(
                $order_id,
                sprintf(
                    __('The %s invitation could not be sent. %s', 'webwinkelkeur'),
                    $this->plugin->getName(), $e->getMessage()
                )
            );
            return;
        }

        try {
            $api->invite($data);
        } catch (WebwinkelKeurAPIError $e) {
            $this->logApiError($e);
            $this->insert_comment(
                $order_id,
                sprintf(
                    __('The %s invitation could not be sent.', 'webwinkelkeur'),
                    $this->plugin->getName()
                ) . ' ' . $e->getMessage()
            );
            return;
        }

        $this->insert_comment(
            $order_id,
            sprintf(
                __('An invitation was sent to %s dashboard.', 'webwinkelkeur'),
                $this->plugin->getName()
            )
        );
    }

    public function addGtinOption() {
        $gtin_handler = new GtinHandler();
        if (
            $gtin_handler->getActivePlugin()
            || !$this->isProductReviewsEnabled()
            || (
                $this->plugin->getOption('custom_gtin')
                && $this->plugin->getOption('custom_gtin') != GtinHandler::META_PREFIX . $this->getGtinMetaKey()
            )
        ) {
            return;
        }
        $label = 'GTIN';
        echo '<div class="options_group">';
        woocommerce_wp_text_input([
            'id' => $this->getGtinMetaKey(),
            'label' => $label,
            'placeholder' => '',
            'desc_tip' => true,
            'description' => sprintf(__('Add the %s for this product', 'webwinkelkeur'), $label),
        ]);
        echo '</div>';
    }

    public function saveGtinOption($product) {
        if (isset($_POST[$this->getGtinMetaKey()])) {
            $product->update_meta_data(
                $this->getGtinMetaKey(),
                wc_clean(wp_unslash($_POST[$this->getGtinMetaKey()]))
            );
        }
    }

    private function get_plugin_version($plugin_name) {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        // Create the plugins folder and file variables
        $plugin_folder = get_plugins('/' . $plugin_name);
        $plugin_file = $plugin_name . '.php';

        // If the plugin version number is set, return it
        if (isset($plugin_folder[$plugin_file]['Version'])) {
            return $plugin_folder[$plugin_file]['Version'];
        }
        return null;
    }

    private function insert_comment($order_id, $content) {
        wp_insert_comment([
            'comment_post_ID'   => $order_id,
            'comment_author'    => $this->plugin->getName(),
            'comment_content'   => $content,
            'comment_agent'     => $this->plugin->getName(),
            'comment_type'      => 'order_note',
        ]);
    }

    private function filter_data($value) {
        if (is_array($value)) {
            return array_map(function ($item) {
                return $this->filter_data($item);
            }, $value);
        }
        try {
            return $this->call_method($value, 'get_data');
        } catch (Exception $e) {
        }
        try {
            return $this->call_method($value, '__toString');
        } catch (Exception $e) {
        }
        if (is_object($value)) {
            return new \stdClass();
        }
        return $value;
    }

    private function get_data($value, $default = null) {
        try {
            return $this->call_method($value, 'get_data');
        } catch (Exception $e) {
            return $default;
        }
    }

    private function call_method($obj, $name) {
        $method = new ReflectionMethod($obj, $name);
        if ($method->getNumberOfRequiredParameters() > 0) {
            throw new RuntimeException('Method requires parameters');
        }
        return @$method->invoke($obj);
    }

    private function statusReached(string $new_status): bool {
        $selected_statuses = $this->plugin->getOption('order_statuses') ?: WooCommerce::DEFAULT_ORDER_STATUS;
        foreach ($selected_statuses as $selected_status) {
            if ($new_status == preg_replace('/^wc-/', '', $selected_status)) {
                return true;
            }
        }
        return false;
    }

    private function get_product_data(array $order_arr) {
        $pf = new WC_Product_Factory();
        $products = [];
        foreach ($order_arr['line_items'] as $line_item) {
            $product_id = $line_item['product_id'];
            if (!empty($line_item['variation_id'])) {
                $product_id = $line_item['variation_id'];
            }

            $product = $pf->get_product($product_id);
            if (!$product) {
                continue;
            }
            $gtin_handler = new GtinHandler();
            $gtin_handler->setGtinMetaKey($this->getGtinMetaKey());
            $gtin_handler->setProduct($product);
            $products[] = [
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'url' => get_permalink($product->get_id()),
                'image_url' => $this->getProductImage($product->get_image_id()),
                'sku' => $product->get_sku(),
                'gtin' => $gtin_handler->getGtin(
                    $this->plugin->getOption('custom_gtin') ?: null
                ),
                'reviews_allowed' => $product->get_reviews_allowed(),
            ];
        }
        return $products;
    }

    public function manualReviewSync() {
        check_ajax_referer($this->getManualSyncNonce());
        try {
            $details = $this->doSyncReviews(isset($_POST['sync_all']) && $_POST['sync_all'] == 'yes');
            wp_send_json([
                'status' => true,
                'message' => $this->plugin->render('woocommerce_review_sync_status', [
                    'details' => $details,
                ]),
            ]);
        } catch (\Exception $e) {
            wp_send_json([
                'status' => false,
                'message' => htmlentities($e->getMessage()),
            ]);
        }
        wp_die();
    }

    public function syncReviews() {
        try {
            $this->doSyncReviews();
        } catch (\Exception $e) {
        }
    }

    private function doSyncReviews($sync_all = false) {
        if (!$this->isProductReviewsEnabled()) {
            throw new \RuntimeException("Product reviews are disabled");
        }
        if (!$this->plugin->isWoocommerceActivated()) {
            throw new \RuntimeException("WooCommerce is not active");
        }
        $api_domain = $this->plugin->getDashboardDomain();
        $shop_id = $this->plugin->getOption('wwk_shop_id');
        $api_key = $this->plugin->getOption('wwk_api_key');
        $api = new API($api_domain, $shop_id, $api_key);
        if ($sync_all) {
            $last_synced = null;
        } else {
            $last_synced = $this->plugin->getOption('last_synced') ?: null;
        }
        $reviews = $api->getReviews($last_synced);
        if (!$reviews->count()) {
            throw new \RuntimeException(sprintf(
                "No reviews to sync since %s",
                $last_synced ?: "forever"
            ));
        }
        $successes = 0;
        $errors = [];
        foreach ($reviews as $review) {
            try {
                $this->processReview($review);
                $successes++;
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
        if ($last_modified = (string) ($reviews[0]->modified ?? null)) {
            update_option($this->plugin->getOptionName('last_synced'), $last_modified);
        }
        update_option($this->plugin->getOptionName('last_executed_sync'), date(\DateTime::RFC3339));
        return [
            'successes' => $successes,
            'errors' => $errors,
        ];
    }

    private function processReview(\SimpleXMLElement $review) {
        if ($this->plugin->getOption('product_reviews_multisite') && is_multisite()) {
            $this->syncMultiSite($review);
        } else {
            $this->doProcessReview($review);
        }
    }

    private function syncMultiSite(\SimpleXMLElement $review) {
        $is_synced = false;
        foreach (get_sites() as $site) {
            switch_to_blog($site->blog_id);
            if ($this->processMultiSite($review)) {
                $is_synced = true;
            }
            restore_current_blog();
        }

        if (!$is_synced) {
            $this->failedInsertError((int) $review->products->product->external_id);
        }
    }

    private function processMultiSite(\SimpleXMLElement $review): bool {
        if (!wc_get_products([
            'sku' => sanitize_text_field((string) $review->products->product->product_ids->skus->sku),
            'id' => (int) $review->products->product->external_id,
        ])) {
            return false;
        }

        $this->doProcessReview($review);
        return true;
    }

    private function doProcessReview(\SimpleXMLElement $review) {
        $comment_data = $this->getCommentData($review);
        $comment_id = $this->getExistingComment(
            $comment_data['comment_post_ID'],
            $comment_data['comment_author_email'],
            (int) $review->review_id
        );
        if ((int) $review->deleted) {
            if ($comment_id) {
                if (!wp_delete_comment($comment_id)) {
                    throw new RuntimeException("Could not delete review: {$comment_id}");
                }
            }
        } elseif ($comment_id) {
            $comment_data['comment_ID'] = $comment_id;
            if (wp_update_comment($comment_data) === false) {
                throw new RuntimeException("Could not update review: {$comment_id}");
            }
        } else {
            if (!wp_insert_comment($comment_data)) {
                $this->failedInsertError($comment_data['comment_post_ID']);
            }
            WC_Comments::clear_transients($comment_data['comment_post_ID']);
        }
    }

    private function getExistingComment(int $post_id, string $author_email, int $review_id) {
        $args = [
            'post_id' => $post_id,
            'author_email' => $author_email,
            'type' => 'review',
            'meta_query' => [
                'key' => $this->getReviewIdMetaKey(),
                'value' => $review_id,
            ],
        ];
        $comments_query = new WP_Comment_Query($args);
        $comments = $comments_query->comments;
        return $comments[0]->comment_ID ?? null;
    }

    private function getCommentData(\SimpleXMLElement $review) {
        $pf = new WC_Product_Factory();
        $product_id = (int) $review->products->product->external_id;

        if (!$product = $pf->get_product($product_id)) {
            throw new RuntimeException(sprintf("No product with ID {$product_id}"));
        }

        if ($product->get_parent_id()) {
            $product_id = $product->get_parent_id();
        }

        $author_email = sanitize_text_field((string) $review->email);
        return [
            'comment_post_ID' => $product_id,
            'comment_author' => sanitize_text_field((string) $review->reviewer->name),
            'comment_author_email' => $author_email,
            'comment_content' => sanitize_text_field((string) $review->content),
            'comment_type' => 'review',
            'comment_meta' => [
                $this->getReviewIdMetaKey() => (int) $review->review_id,
                'rating' => (int) $review->ratings->overall,
            ],
            'comment_parent' => 0,
            'user_id' => get_user_by('email', $author_email)->ID ?? 0,
            'comment_date' => date('Y-m-d H:i:s', strtotime((string) $review->review_timestamp)),
            'comment_approved' => (int) $review->valid,
        ];
    }

    private function logApiError(WebwinkelKeurAPIError $e) {
        global $wpdb;
        $wpdb->insert($this->plugin->getInviteErrorsTable(), [
            'url' => $e->getURL(),
            'response' => $e->getMessage(),
            'time' => time(),
        ]);
    }

    private function getReviewsHook(): string {
        return "{$this->plugin->getSlug()}_reviews_cron";
    }

    private function getReviewIdMetaKey(): string {
        return "_{$this->plugin->getOptionName('review_id')}";
    }

    private function getGtinMetaKey(): string {
        return "_{$this->plugin->getOptionName('gtin')}";
    }

    public function getManualSyncAction(): string {
        return $this->plugin->getOptionName('manual_sync');
    }

    public function getManualSyncNonce(): string {
        return $this->plugin->getOptionName('manual-sync-data');
    }

    public function getProductKeysAction(): string {
        return $this->plugin->getOptionName('product_keys');
    }

    public function getProductKeys() {
        $selected_key = $_GET['selected_key'];
        wp_send_json([
            'status' => true,
            'data' => array_map(
                function (array $value) use ($selected_key): array {
                    $option_value = $value['type'] . $value['name'];
                    return [
                        'option_value' => $option_value,
                        'label' => sprintf('%s (e.g. "%s")', $value['name'], $value['example_value']),
                        'suggested' => $this->isValidGtin($value['example_value']),
                        'selected' => $option_value == $selected_key,
                    ];
                },
                array_merge($this->getProductMetaKeys(), $this->getCustomAttributes())
            ),
        ]);
        wp_die();
    }

    public function getProductMetaKeys(): array {
        global $wpdb;
        $meta_keys=[];
        $meta_keys_data = $wpdb->get_col("
            SELECT DISTINCT(pm.meta_key)
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE
                p.post_type = 'product'
                AND pm.meta_key <> ''
                AND pm.meta_value <> ''
        ");
        foreach ($meta_keys_data as $value) {
            $meta_keys[$value] =[
                'type' => 'meta_key',
                'name' => $value,
                'example_value' => substr($this->getMetaValue($value), 0, 15),
            ];
        }
        return $meta_keys;
    }

    private function getMetaValue(string $meta_key) {
        global $wpdb;
        $sql = "
            SELECT meta.meta_value
            FROM {$wpdb->postmeta} meta
            WHERE meta.meta_key = %s
            AND meta.meta_value <> ''
            ORDER BY meta.meta_id DESC
            LIMIT 1;
        ";
        return $wpdb->get_var($wpdb->prepare($sql, $meta_key));
    }

    private function getCustomAttributes(): array {
        global $wpdb;
        $custom_attributes = [];
        $sql = "
            SELECT meta.meta_id, meta.meta_key as name, meta.meta_value 
            FROM {$wpdb->postmeta} meta
            JOIN {$wpdb->posts} posts
            ON meta.post_id = posts.id 
            WHERE posts.post_type IN ('product', 'product_variation') 
            AND meta.meta_key='_product_attributes'
            ORDER BY posts.id DESC
            LIMIT 1000;
        ";

        $data = $wpdb->get_results($sql);
        foreach ($data as $value) {
            try {
                $product_attr = unserialize($value->meta_value, ['allowed_classes' => false]);
            } catch (\Throwable $e) {
                continue;
            }
            if (!is_array($product_attr)) {
                continue;
            }
            foreach ($product_attr as $arr_value) {
                $custom_attributes[$arr_value['name']] = [
                    'type' => 'custom_attribute',
                    'name' => $arr_value['name'],
                    'example_value' => substr($arr_value['value'], 0, 15),
                ];
            }
        }
        return $custom_attributes;
    }

    private function isValidGtin(string $value): bool {
        return preg_match('/^\d{8}(?:\d{4,6})?$/', $value);
    }

    public function getNextReviewSync(): string {
        return $this->getReviewSyncDate(wp_next_scheduled($this->getReviewsHook()));
    }

    public function getLastReviewSync(): string {
        return $this->getReviewSyncDate(strtotime(
            $this->plugin->getOption('last_executed_sync') ?: '',
        ));
    }

    private function getReviewSyncDate($date): string {
        if ($date) {
            return htmlentities(date("Y-m-d H:i:s", $date));
        }
        return __('Not registered.', 'webwinkelkeur');
    }

    private function isProductReviewsEnabled(): bool {
        return $this->plugin->getOption('product_reviews');
    }

    private function isSyncedToday(): bool {
        $last_sync = $this->plugin->getOption('last_executed_sync');
        if (!$last_sync) {
            return false;
        }
        return strtotime($last_sync) > strtotime('-24 hours');
    }

    public function addOrderDataJsonThankYouPage() {
        if (!function_exists('is_wc_endpoint_url')) {
            return;
        }

        if (!is_wc_endpoint_url('order-received') || $this->plugin->getOption('invite') != self::POPUP_OPTION) {
            return;
        }

        $shop_id = $this->plugin->getOption('wwk_shop_id');
        $api_key = $this->plugin->getOption('wwk_api_key');
        $order_id = absint(get_query_var(get_option('woocommerce_checkout_order_received_endpoint')));
        $order = wc_get_order($order_id);
        $order_data = [
            'webshopId' => $shop_id,
            'orderNumber' => $order->get_order_number(),
            'email' => $order->get_billing_email(),
            'firstName' => $order->get_billing_first_name(),
            'inviteDelay' => $this->getInviteDelay(),
        ];

        try {
            $order_data['signature'] = (new Hash($shop_id, $api_key, $order_data))->getHash();
        } catch (InvalidKeysException $e) {
        }

        echo sprintf(
            '<script type="application/json" id ="%s_order_completed">%s</script>',
            htmlentities(strtolower($this->plugin->getName())),
            json_encode($order_data, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)
        );
    }

    private function getInviteDelay(): int {
        $invite_delay = (int) $this->plugin->getOption('invite_delay');
        if ($invite_delay < 0) {
            $invite_delay = 0;
        }
        return $invite_delay;
    }

    private function getProductImage(string $image_id) {
        $image_array = wp_get_attachment_image_src($image_id);
        return $image_array[0] ?? null;
    }

    private function failedInsertError(int $product_id) {
        throw new RuntimeException(
            "Could not insert review for product: {$product_id}");
    }
}
