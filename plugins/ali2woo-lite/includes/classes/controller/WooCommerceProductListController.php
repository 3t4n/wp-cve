<?php
/* * class
 * Description of WooCommerceProductListController
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_admin_init
 *
 * @ajax: true
 */

namespace AliNext_Lite;;

class WooCommerceProductListController
{

    private $bulk_actions = array();
    private $bulk_actions_text = array();

    public function __construct()
    {
        add_action('admin_footer-edit.php', array($this, 'scripts'));
        add_action('load-edit.php', array($this, 'bulk_actions'));
        add_filter('post_row_actions', array($this, 'row_actions'), 2, 150);
        add_action('admin_enqueue_scripts', array($this, 'assets'));
        add_action('admin_init', array($this, 'init'));

        add_action('wp_ajax_a2wl_product_info', array($this, 'ajax_product_info'));
        add_action('wp_ajax_a2wl_sync_products', array($this, 'ajax_sync_products'));
        add_action('wp_ajax_a2wl_sync_products_reviews', array($this, 'ajax_sync_products_reviews'));

        add_action('wp_ajax_a2wl_get_product_id', array($this, 'ajax_get_product_id'));

        add_action('current_screen', array($this, 'setup_screen'));
    }

    public function setup_screen()
    {
        $screen_id = false;
        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
            $screen_id = isset($screen, $screen->id) ? $screen->id : '';
        }

        if ($screen_id == 'edit-product') {
            add_action('admin_notices', array($this, 'admin_notices'));
        }

        remove_action('current_screen', array($this, 'setup_screen'));
    }

    public function admin_notices()
    {
        $daily_limits = get_transient('_a2w_daily_limits_warning');

        if ($daily_limits && isset($daily_limits['until']) && $daily_limits['until'] > time()) {
            ?>
        <div id="a2wl-daily-limits-warning-message" class="notice error is-dismissible">
            <p>You have reached your daily synchronization quota. You can synchronize up to <?php echo $daily_limits['limit']; ?> products per day.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
        <?php
        }
    }

    public function init()
    {
        
        list($this->bulk_actions, $this->bulk_actions_text) = apply_filters('a2wl_wcpl_bulk_actions_init', array($this->bulk_actions, $this->bulk_actions_text));
    }

    public function row_actions($actions, $post)
    {
        if ('product' === $post->post_type) {
            $external_id = get_post_meta($post->ID, "_a2w_external_id", true);
            if ($external_id) {
                $actions = array_merge($actions, array('a2wl_product_info' => sprintf('<a class="a2wl-product-info" id="a2wl-%1$d" data-external-id="%2$s" href="#">%3$s</a>', $post->ID, $external_id, 'Aliexpress Info')));
            }
        }

        return $actions;
    }

    public function assets()
    {

        wp_enqueue_style('a2wl-wc-pl-style', A2WL()->plugin_url() . '/assets/css/wc_pl_style.css', array(), A2WL()->version);

        wp_style_add_data('a2wl-wc-pl-style', 'rtl', 'replace');

        wp_enqueue_script('a2wl-wc-pl-script', A2WL()->plugin_url() . '/assets/js/wc_pl_script.js', ['jquery-ui-core', 'jquery-ui-dialog'], A2WL()->version);

        wp_enqueue_script('a2wl-sprintf-script', A2WL()->plugin_url() . '/assets/js/sprintf.js', array(), A2WL()->version);

        $lang_data = array(
            'please_wait_data_loads' => _x('Please wait, data loads..', 'Status', 'ali2woo'),
            'process_update_d_of_d' => _x('Process update %d of %d.', 'Status', 'ali2woo'),
            'process_update_d_of_d_erros_d' => _x('Process update %d of %d. Errors: %d.', 'Status', 'ali2woo'),
            'complete_result_updated_d_erros_d' => _x('Complete! Result updated: %d; errors: %d.', 'Status', 'ali2woo'),
        );

        $localizator = AliexpressLocalizator::getInstance();

        wp_localize_script('a2wl-wc-pl-script', 'a2wl_wc_pl_script',
            array('lang' => $lang_data,
                'lang_cookies' => AliexpressLocalizator::getInstance()->getLocaleCookies(false),
                'locale' => $localizator->getLangCode(),
                'currency' => $localizator->currency,
                'chrome_ext_import' => a2wl_check_defined('A2WL_CHROME_EXT_IMPORT'),
                'chrome_url' => A2WL()->chrome_url,
            )
        );
    }

    public function scripts()
    {
        global $post_type;

        if ($post_type == 'product') {

            foreach ($this->bulk_actions as $action) {
                $text = $this->bulk_actions_text[$action];
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('<option>').val('<?php echo $action; ?>').text('<?php echo $text; ?>').appendTo("select[name='action']");
                        jQuery('<option>').val('<?php echo $action; ?>').text('<?php echo $text; ?>').appendTo("select[name='action2']");
                    });
                </script>
                <?php
            }
        }
    }

    public function bulk_actions()
    {
        global $typenow;
        $post_type = $typenow;

        if ($post_type == 'product') {

            $wp_list_table = _get_list_table('WP_Posts_List_Table');
            $action = $wp_list_table->current_action();

            $allowed_actions = $this->bulk_actions;
            if (!in_array($action, $allowed_actions)) {
                return;
            }

            check_admin_referer('bulk-posts');

            // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
            if (isset($_REQUEST['post'])) {
                $post_ids = array_map('intval', $_REQUEST['post']);
            }

            if (empty($post_ids)) {
                return;
            }

            $sendback = remove_query_arg(array_merge($allowed_actions, array('untrashed', 'deleted', 'ids')), wp_get_referer());
            if (!$sendback) {
                $sendback = admin_url("edit.php?post_type=$post_type");
            }

            $pagenum = $wp_list_table->get_pagenum();
            $sendback = add_query_arg('paged', $pagenum, $sendback);

            $sendback = apply_filters('a2wl_wcpl_bulk_actions_perform', $sendback, $action, $post_ids);

            $sendback = remove_query_arg(array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view'), $sendback);

            wp_redirect($sendback);
            exit();
        }
    }

    public function ajax_product_info()
    {
        $result = array("state" => "ok", "data" => "");

        $post_id = isset($_POST['id']) ? $_POST['id'] : false;

        if (!$post_id) {
            $result['state'] = 'error';
            echo json_encode($result);
            wp_die();
        }

        $external_id = get_post_meta($post_id, "_a2w_external_id", true);

        $time_value = get_post_meta($post_id, '_a2w_last_update', true);
        $time_value = $time_value ? date("Y-m-d H:i:s", $time_value) : 'not updated';

        $product_url = get_post_meta($post_id, '_product_url', true);
        if (!$product_url) {
            $product_url = get_post_meta($post_id, '_a2w_original_product_url', true);
        }

        $content = array();

        $content[] = "Product: <a target='_blank' href='" . $product_url . "'>here</a>";

        $seller_url = get_post_meta($post_id, '_a2w_seller_url', true);
        $store_id = get_post_meta($post_id, '_a2w_store_id', true);
        $seller_id = get_post_meta($post_id, '_a2w_seller_id', true);
        $seller_name = get_post_meta($post_id, '_a2w_seller_name', true);

        if ($seller_url && $seller_name) {
            $content[] = "Seller: <a target='_blank' href='" . $seller_url . "'>" . $seller_name . "</a>";
        }

        if ($store_id){
            $content[] = "Store ID: <span class='a2wl_value'>" . $store_id . "</span>"; 
        }

        if ($seller_id){
            $content[] = "Seller ID: <span class='a2wl_value'>" . $seller_id . "</span>";   
        }

        $content[] = "External ID: <span class='a2wl_value'>" . $external_id . "</span>";
        $content[] = "Last auto-update: <span class='a2wl_value'>" . $time_value . "</span>";

        $content = apply_filters('a2wl_ajax_product_info', $content, $post_id, $external_id);
        $result['data'] = array('content' => $content, 'id' => $post_id);

        echo json_encode($result);
        wp_die();
    }

    public function ajax_sync_products()
    {
        a2wl_init_error_handler();
        try {
            /** @var $woocommerce_model  Woocommerce */ 
            $woocommerce_model = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');

            $ids = isset($_POST['ids']) ? (is_array($_POST['ids']) ? $_POST['ids'] : array($_POST['ids'])) : array();

            $on_price_changes = get_setting('on_price_changes');
            $on_stock_changes = get_setting('on_stock_changes');

            $products = array();
            foreach ($ids as $post_id) {
                $product = $woocommerce_model->get_product_by_post_id($post_id, false);
                if ($product) {
                    $product['disable_var_price_change'] = $product['disable_var_price_change'] || $on_price_changes !== "update";
                    $product['disable_var_quantity_change'] = $product['disable_var_quantity_change'] || $on_stock_changes !== "update";
                    $products[strval($product['id'])] = $product;
                }
            }

            $result = array("state" => "ok", "update_state" => array('ok' => count($ids), 'error' => 0));
            if (count($products) > 0) {
                $product_ids = array_map(function ($p) {
                    $complex_id = $p['id'] . ';' . $p['import_lang'];

                    $shipping_meta = new ProductShippingMeta($p['post_id']);

                    $country_to = $shipping_meta->get_country_to();
                    if (!empty($country_to)) {
                        $complex_id .= ';' . $country_to;
                    }

                    $method = $shipping_meta->get_method();
                    if (!empty($method)) {
                        $complex_id .= ';' . $method;
                    }

                    return $complex_id;
                }, $products);

                $apd_items = empty($_POST['apd_items']) ? array() : $_POST['apd_items'];

                foreach ($apd_items as $k => $adpi) {
                    $apd_items[$k]['apd'] = json_decode(stripslashes($adpi['apd']));
                }
                $data = empty($apd_items) ? array() : array('data' => array('apd_items' => $apd_items));

                $aliexpress_model = new Aliexpress();
                $sync_model = new Synchronize();

                $res = $aliexpress_model->sync_products($product_ids,
                    array_merge(array('manual_update' => 1, 'pc' => $sync_model->get_product_cnt()), $data)
                );
                if ($res['state'] === 'error') {
                    $result = $res;

                    // update daily limit warning
                    if ($result['error_code'] == 5001 && isset($result['time_left'])) {
                        set_transient('_a2w_daily_limits_warning', array('limit' => $result['call_limit'], 'until' => time() + $result['time_left']), time() + $result['time_left']);
                    }
                } else {
                    foreach ($res['products'] as $product) {
                        $product = array_replace_recursive($products[strval($product['id'])], $product);
                        $product = PriceFormula::apply_formula($product);
                        $woocommerce_model->upd_product($product['post_id'], $product, array('manual_update' => 1));
                    }

                    delete_transient('_a2w_daily_limits_warning');
                }
            }
        } catch (\Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo json_encode($result);
        wp_die();
    }

    public function ajax_sync_products_reviews()
    {
        a2wl_init_error_handler();
        try {
            /** @var $woocommerce_model  Woocommerce */ 
            $woocommerce_model = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');

            $ids = isset($_POST['ids']) ? (is_array($_POST['ids']) ? $_POST['ids'] : array($_POST['ids'])) : array();

            $error = 0;
            foreach ($ids as $post_id) {
                $external_id = $woocommerce_model->get_product_external_id($post_id);
                if ($external_id) {
                    try {
                        $reviews_model = new Review();
                        $reviews_model->load($post_id, true);
                    } catch (\Throwable $e) {
                        a2wl_print_throwable($e);
                        $error++;
                    } catch (\Exception $e) {
                        a2wl_print_throwable($e);
                        $error++;
                    }
                } else {
                    $error++;
                }
            }

            $result = array("state" => "ok", "update_state" => array('ok' => count($ids), 'error' => 0));
        } catch (\Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        } catch (\Exception $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo json_encode($result);
        wp_die();
    }

    public function ajax_get_product_id()
    {
        if (!empty($_POST['post_id'])) {
            /** @var $woocommerce_model  Woocommerce */ 
            $woocommerce_model = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');
            $id = $woocommerce_model->get_product_external_id($_POST['post_id']);
            if ($id) {
                $result = ResultBuilder::buildOk(array('id' => $id));
            } else {
                $result = ResultBuilder::buildError('uncknown ID');
            }
        } else {
            $result = ResultBuilder::buildError("get_product_id: waiting for ID...");
        }
        echo json_encode($result);
        wp_die();
    }

}
