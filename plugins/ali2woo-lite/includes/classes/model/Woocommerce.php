<?php

/**
 * Description of Woocommerce
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;
use Throwable;
use \WC_Product_Factory;
use Automattic\WooCommerce\Utilities\OrderUtil;

class Woocommerce
{
    private static ?array $active_plugins = null;
    private Attachment $attachment_model;
    private Helper $helper;
    private ProductChange $product_change_model;

    public function __construct(
        Attachment $AttachmentModel, Helper $HelperModel, ProductChange $ProductChangeModel
    ) {
        $this->attachment_model = $AttachmentModel;
        $this->helper = $HelperModel;
        $this->product_change_model = $ProductChangeModel;
    }

    public static function is_woocommerce_installed(): bool
    {
        if (!self::$active_plugins) {
            self::$active_plugins = (array) get_option('active_plugins', array());
            if (is_multisite()) {
                self::$active_plugins = array_merge(self::$active_plugins, get_site_option('active_sitewide_plugins', array()));
            }
        }

        return in_array('woocommerce/woocommerce.php', self::$active_plugins) || array_key_exists('woocommerce/woocommerce.php', self::$active_plugins);
    }

    public function build_steps($product)
    {
        $steps = array('init');

        $override_product = !empty($product['override_product_id']) && $product['override_product_id'];
        $override_title_description = isset($product['override_title_description']) && $product['override_title_description'];
        $override_images = isset($product['override_images']) && $product['override_images'];

        $images_to_preload = Utils::get_all_images_from_product($product, true, !$override_product || $override_images, !$override_product || $override_title_description);
        foreach ($images_to_preload as $img_id => $image) {
            $steps[] = 'preload_images#' . $img_id;
        }

        if ($this->need_import_variations($product)) {
            $steps[] = 'variations#attributes';
            foreach ($product['sku_products']['variations'] as $variation) {
                $steps[] = 'variations#variation#' . $variation['id'];
            };
            $steps[] = 'variations#sync';
        } else {
            $steps[] = 'variations';
        }

        $images_data = $this->prepare_product_images($product);
        if ($images_data['thumb']) {
            $steps[] = 'images#' . md5($images_data['thumb']);
        }
        foreach ($images_data['images'] as $image_url) {
            $steps[] = 'images#' . md5($image_url);
        }

        $steps[] = 'description';
        $steps[] = 'finishing';

        return $steps;
    }

    private function need_import_variations($product, $product_type = false)
    {
        $product_type = $product_type ? $product_type : ((isset($product['product_type']) && $product['product_type']) ? $product['product_type'] : get_setting('default_product_type', 'simple'));
        return !a2wl_check_defined('A2WL_DO_NOT_IMPORT_VARIATIONS') &&
        $product_type !== "external" &&
        !empty($product['sku_products']['variations']) &&
        count($product['sku_products']['variations']) > 1;
    }

    private function is_product_exist($product_id)
    {
        global $wpdb;
        return !!$wpdb->get_row($wpdb->prepare("SELECT p.ID FROM $wpdb->posts p WHERE p.ID = %d and p.post_type='product' LIMIT 1", $product_id));
    }

    private function prepare_product_images($product)
    {
        $override_product = !empty($product['override_product_id']) && $product['override_product_id'];
        $override_images = isset($product['override_images']) && $product['override_images'];

        $thumb_url = '';
        $tmp_all_images = Utils::get_all_images_from_product($product);

        if (isset($product['thumb_id'])) {
            foreach ($tmp_all_images as $img_id => $img) {
                if ($img_id === $product['thumb_id'] && !in_array($img_id, $product['skip_images'])) {
                    $thumb_url = Utils::clear_url($img['image']);
                    break;
                }
            }
        }

        $result = array('thumb' => '', 'images' => array());

        if ((!$override_product || $override_images) && isset($product['images'])) {
            $image_to_load = array();
            foreach ($product['images'] as $image) {
                if (!in_array(md5($image), $product['skip_images'])) {
                    $image_to_load[md5($image)] = $image;
                }
            }

            foreach ($product['tmp_copy_images'] as $img_id => $source) {
                if (isset($tmp_all_images[$img_id]) && !in_array($img_id, $product['skip_images'])) {
                    $image_to_load[$img_id] = $tmp_all_images[$img_id]['image'];
                }
            }

            foreach ($product['tmp_move_images'] as $img_id => $source) {
                if (isset($tmp_all_images[$img_id]) && !in_array($img_id, $product['skip_images'])) {
                    $image_to_load[$img_id] = $tmp_all_images[$img_id]['image'];
                }
            }

            // if not thumb not checked, check first available image
            if (!$thumb_url && !empty($image_to_load)) {
                $tmp_images = array_values($image_to_load);
                $thumb_url = array_shift($tmp_images);
            }

            $result = array('thumb' => $thumb_url, 'images' => $image_to_load);
        }

        return $result;
    }

    public function add_product($product, $params = array()): array
    {
        if (!Woocommerce::is_woocommerce_installed()) {
            return ResultBuilder::buildError("Woocommerce is not installed");
        }

        global $wpdb;

        $step = isset($params['step']) ? $params['step'] : false;
        $product_id = isset($params['product_id']) ? $params['product_id'] : false;

        $override_product = !empty($product['override_product_id']) && $product['override_product_id'];
        $override_title_description = isset($product['override_title_description']) && $product['override_title_description'];
        $override_images = isset($product['override_images']) && $product['override_images'];
        $override_supplier = isset($product['override_supplier']) && $product['override_supplier'];
        $override_variations = !empty($product['override_variations']) ? $product['override_variations'] : array();

        if ($override_product && $override_supplier) {
            $product['skip_vars'] = array();
            $used_vars = array_map(function ($v) {return $v['external_variation_id'];}, $override_variations);
            foreach ($product['sku_products']['variations'] as $var) {
                if (!in_array($var['id'], $used_vars)) {
                    $product['skip_vars'][] = $var['id'];
                }
            }
        }

        if ($override_product) {
            $product_id = $product['override_product_id'];
        }

        $product_type = (isset($product['product_type']) && $product['product_type']) ? $product['product_type'] : get_setting('default_product_type', 'simple');
        $product_status = (isset($product['product_status']) && $product['product_status']) ? $product['product_status'] : get_setting('default_product_status', 'publish');

        $post_title = isset($product['title']) && $product['title'] ? $product['title'] : "Product " . $product['id'];

        $first_ava_var = false;
        $variations_active_cnt = 0;
        $total_quantity = 0;
        if (!empty($product['sku_products']['variations'])) {
            foreach ($product['sku_products']['variations'] as $variation) {
                // check quantity only for non-external import
                if (($product_type === "external" || intval($variation['quantity']) > 0) && !in_array($variation['id'], $product['skip_vars'])) {
                    if (!$first_ava_var) {
                        $first_ava_var = $variation;
                    }
                    $variations_active_cnt++;
                    $total_quantity += intval($variation['quantity']);
                }
            }
        }

        if ($step === false || $step === 'init') {
            do_action('a2wl_woocommerce_before_add_product', $product, $params);

            if ($product_type !== "external") {
                $product_type = $variations_active_cnt > 1 ? 'variable' : 'simple';
            }

            $tax_input = array('product_type' => $product_type);
            $categories = $this->build_categories($product);
            if ($categories) {
                $tax_input['product_cat'] = $categories;
            }

            $post = array(
                'post_title' => $post_title,
                'post_content' => '',
                'post_status' => 'draft',
                'post_name' => $post_title,
                'post_type' => 'product',
                'comment_status' => 'open',
                'tax_input' => $tax_input,
                'meta_input' => array('_stock_status' => 'instock',
                    '_sku' => empty($product['sku']) ? $product['id'] : $product['sku'],
                    '_visibility' => 'visible', // for old woocoomerce
                    '_product_url' => $product['affiliate_url'],
                    '_a2w_external_id' => $product['id'],
                    '_a2w_import_id' => $product['import_id'],
                    '_a2w_product_url' => $product['affiliate_url'],
                    '_a2w_original_product_url' => $product['url'],
                    '_a2w_seller_url' => (!empty($product['seller_url']) ? $product['seller_url'] : ''),
                    '_a2w_seller_name' => (!empty($product['seller_name']) ? $product['seller_name'] : ''),
                    '_a2w_last_update' => time(),
                    '_a2w_skip_meta' => array('skip_vars' => $product['skip_vars'], 'skip_images' => $product['skip_images']),
                    '_a2w_disable_sync' => 0,
                    '_a2w_disable_var_price_change' => isset($product['disable_var_price_change']) && $product['disable_var_price_change'] ? 1 : 0,
                    '_a2w_disable_var_quantity_change' => isset($product['disable_var_quantity_change']) && $product['disable_var_quantity_change'] ? 1 : 0,
                    '_a2w_disable_add_new_variants' => isset($product['disable_add_new_variants']) && $product['disable_add_new_variants'] ? 1 : 0,
                    '_a2w_orders_count' => (!empty($product['ordersCount']) ? intval($product['ordersCount']) : 0),
                    '_a2w_video' => !empty($product['video']) ? $product['video'] : '',
                    '_a2w_import_lang' => !empty($product['import_lang']) ? $product['import_lang'] : AliexpressLocalizator::getInstance()->language,
                ),
            );

            if ($override_product && $this->is_product_exist($product_id)) {
                // Override exist product.
                $post['ID'] = $product_id;

                // Prepare title and description
                if (!$override_title_description) {
                    unset($post['post_title']);
                    unset($post['post_content']);
                }

                // don't touch slug
                unset($post['post_name']);

                // Delete all images
                if ($override_images) {
                    $attachments = $wpdb->get_col($wpdb->prepare("SELECT p.ID FROM $wpdb->posts p WHERE p.post_type='attachment' AND p.post_parent=%d", $product_id));
                    $attachments = $attachments && is_array($attachments) ? $attachments : array();
                    foreach ($attachments as $attachment_id) {
                        Utils::delete_attachment($attachment_id, true);
                    }
                    delete_post_meta($product_id, "_thumbnail_id");
                    delete_post_meta($product_id, "_product_image_gallery");
                }

                // Delete variations
                $variations = $wpdb->get_col($wpdb->prepare("SELECT p.ID FROM $wpdb->posts p WHERE p.post_type='product_variation' AND p.post_parent=%d", $product_id));
                $variations = $variations && is_array($variations) ? $variations : array();
                foreach ($variations as $var_id) {
                    $var = new \WC_Product_Variation($var_id);
                    $var->delete(true);
                    Utils::delete_post_images($var_id);
                }

                // Delete variations attributes
                delete_post_meta($product_id, "_a2w_original_variations_attributes");

                $_product_attributes = get_post_meta($product_id, '_product_attributes', true);
                foreach ($_product_attributes as $attr_key => $attr) {
                    if (!empty($attr['is_variation'])) {
                        // unlink attr values
                        $delete_query = "DELETE tr FROM {$wpdb->term_relationships} tr INNER JOIN {$wpdb->term_taxonomy} tt on (tt.term_taxonomy_id=tr.term_taxonomy_id) WHERE tr.object_id=%d and tt.taxonomy=%s";
                        $wpdb->query($wpdb->prepare($delete_query, $product_id, $attr_key));

                        unset($_product_attributes[$attr_key]);
                    }
                }
                update_post_meta($product_id, '_product_attributes', $_product_attributes);

                delete_post_meta($product_id, "_a2w_skip_meta");

                $product_id = wp_update_post($post);
            } else {
                $product_id = wp_insert_post($post);
            }

            if (!empty($product['dimensions'])){
                if (!empty($product['dimensions']['weight'])){
                    update_post_meta($product_id, '_weight', $product['dimensions']['weight']);
                }

                if (!empty($product['dimensions']['width'])){
                    update_post_meta($product_id, '_width', $product['dimensions']['width']);
                }

                if (!empty($product['dimensions']['height'])){
                    update_post_meta($product_id, '_height', $product['dimensions']['height']);
                }

                if (!empty($product['dimensions']['length'])){
                    update_post_meta($product_id, '_length', $product['dimensions']['length']);
                }
            }

            if (!empty($product['extra_data'])){
                update_post_meta($product_id, '_a2w_extra_data', $product['extra_data']);
            }

            unset($post);

            // set default _aliexpress_sku_props
            if ($first_ava_var) {
                $aliexpress_sku_props_id_arr = array();
                foreach ($first_ava_var['attributes'] as $cur_var_attr) {
                    foreach ($product['sku_products']['attributes'] as $attr) {
                        if (isset($attr['value'][$cur_var_attr])) {
                            $aliexpress_sku_props_id_arr[] = isset($attr['value'][$cur_var_attr]['original_id']) ? $attr['value'][$cur_var_attr]['original_id'] : $attr['value'][$cur_var_attr]['id'];
                            break;
                        }
                    }
                }
                $aliexpress_sku_props_id = $aliexpress_sku_props_id_arr ? implode(";", $aliexpress_sku_props_id_arr) : "";
                if ($aliexpress_sku_props_id) {
                    update_post_meta($product_id, '_aliexpress_sku_props', $aliexpress_sku_props_id);
                }
            }

            if ($first_ava_var) {
                delete_post_meta($product_id, "_a2w_outofstock");
            } else {
                update_post_meta($product_id, "_a2w_outofstock", true);
            }

            //save seller_id and store_id
            if (!empty($product['seller_id'])) {
                update_post_meta($product_id, '_a2w_seller_id', $product['seller_id']);
            }

            if (!empty($product['store_id'])) {
                update_post_meta($product_id, '_a2w_store_id', $product['store_id']);
            }

            // set default shipping country from
            if ($first_ava_var && !empty($first_ava_var['country_code'])) {
                update_post_meta($product_id, '_a2w_country_code', $first_ava_var['country_code']);
            } else if (isset($product['local_seller_tag']) && strlen($product['local_seller_tag']) == 2) {
                update_post_meta($product_id, '_a2w_country_code', $product['local_seller_tag']);
            }

            // save shipping meta data
            $shipping_meta = new ProductShippingMeta($product_id);
            if (!empty($product['shipping_info']) && is_array($product['shipping_info'])) {
                $shipping_data = array();
                foreach ($product['shipping_info'] as $mk => $data) {
                    // if shipping data was saved without quantity
                    $shipping_data[$mk] = isset($data[0]['serviceName']) ? array(1 => $data) : $data;
                }
                $shipping_meta->save_data($shipping_data);
            }
            if (isset($product['shipping_default_method'])) {
                $shipping_meta->save_method($product['shipping_default_method'], false);
            }
            if (isset($product['shipping_to_country'])) {
                $shipping_meta->save_country_to($product['shipping_to_country'], false);
            }
            if (isset($product['shipping_from_country'])) {
                $shipping_meta->save_country_from($product['shipping_from_country'], false);
            }
            if (isset($product['shipping_cost'])) {
                $shipping_meta->save_cost($product['shipping_cost'], false);
            }
            $shipping_meta->save();

            // update global price
            $this->update_price($product_id, $first_ava_var);

            // update global stock
            if (get_option('woocommerce_manage_stock', 'no') === 'yes') {
                update_post_meta($product_id, '_manage_stock', 'yes');
                update_post_meta($product_id, '_stock_status', $total_quantity ? 'instock' : 'outofstock');
                update_post_meta($product_id, '_stock', $total_quantity);
            } else {
                delete_post_meta($product_id, '_manage_stock');
                delete_post_meta($product_id, '_stock_status');
                delete_post_meta($product_id, '_stock');
            }

            if (isset($product['attribute'])
                && $product['attribute']
                && !get_setting('not_import_attributes', false)
                && (!$override_product || $override_title_description)
            ) {
                $this->set_attributes($product_id, $product['attribute']);
            }

            if (isset($product['tags']) && $product['tags']) {
                wp_set_object_terms($product_id, array_map('sanitize_text_field', $product['tags']), 'product_tag');
            }

            $default_shipping_class = get_setting('default_shipping_class');
            if ($default_shipping_class) {
                wp_set_object_terms($product_id, intval($default_shipping_class), 'product_shipping_class');
            }

            if ($step !== false) {
                return $result = ResultBuilder::buildOk(array('product_id' => $product_id, 'step' => $step));
            }

        }

        if ($step !== false && !$this->is_product_exist($product_id)) {
            return ResultBuilder::buildError("Error! Processing processing product($product_id) not found");
        }

        if (substr($step, 0, strlen('preload_images')) === 'preload_images') {
            $images_to_preload = Utils::get_all_images_from_product($product, true);
            $cnt = 0;
            foreach ($images_to_preload as $img_id => $image) {
                $cnt++;
                if ($step === 'preload_images#' . $img_id) {
                    $title = !empty($post_title) ? ($post_title . ' ' . $cnt) : null;
                    $this->attachment_model->create_attachment($product_id, $image['image'], array('inner_post_id' => $product_id, 'title' => $title, 'alt' => $title, 'edit_images' => !empty($product['tmp_edit_images']) ? $product['tmp_edit_images'] : array()));
                }
            }
            return ResultBuilder::buildOk(array('product_id' => $product_id, 'step' => $step));
        }

        if ($step === false || substr($step, 0, strlen('images')) === 'images') {
            $images_data = $this->prepare_product_images($product);

            if (!empty($images_data['thumb']) || !empty($images_data['images'])) {
                $this->set_images($product, $product_id, $images_data['thumb'], $images_data['images'], true, $post_title, $params);
            }

            if ($step !== false) {
                return $result = ResultBuilder::buildOk(array('product_id' => $product_id, 'step' => $step));
            }

        }

        if ($step === false || substr($step, 0, strlen('variations')) === 'variations') {
            if ($this->need_import_variations($product, $product_type)) {
                foreach ($product['sku_products']['variations'] as &$var) {
                    $var['image'] = (!isset($var['image']) || in_array(md5($var['image']), $product['skip_images'])) ? '' : $var['image'];
                }
                $this->add_variation($product_id, $product, false, $params);
            }
            if ($step !== false) {
                return ResultBuilder::buildOk(array('product_id' => $product_id, 'step' => $step));
            }

        }

        if ($step === false || $step === 'description') {
            $post_arr = array(
                'ID' => $product_id,
                'post_status' => $product_status,
            );
            if (!$override_product || $override_title_description) {
                // if this is usual import or override with override_title_description flag, then update description
                $post_arr['post_content'] = (isset($product['description']) ? $this->build_description($product_id, $product) : '');
            }

            if ($override_product && !$override_supplier && $override_variations) {
                $variations_to_override = array();
                foreach ($override_variations as $v) {
                    $variations_to_override[$v['external_variation_id']] = $v['variation_id'];
                }

                $in_data = implode(",", array_map(function ($v) {global $wpdb;return "'" . $wpdb->_real_escape($v) . "'";}, array_keys($variations_to_override)));

                /**
                 * Above we have already updated original product with new override-product in db.
                 * And here we check if it has variant, then update that order.
                 * But if override-product is simple and original was variable
                 * Then query below will not return anything and related order item will not be updated
                 * Perhaps it's ok? However, each order item should have correct data for order fulfillment...
                 * Need to check this deeper
                 * I think in this case we should remove '_variation_id' in each order item meta,
                 * because it doesn't have variants anymore
                 */
                $new_variations_query = "SELECT pm.post_id as variation_id, pm.meta_value as external_variation_id FROM {$wpdb->postmeta} pm " .
                                        "INNER JOIN {$wpdb->posts} p on (p.ID=pm.post_id) " .
                                        "WHERE p.post_parent=%d and pm.meta_key='external_variation_id' and pm.meta_value in ($in_data)";

                $new_variations = $wpdb->get_results($wpdb->prepare($new_variations_query, $product_id), ARRAY_A);

                foreach ($new_variations as $v) {
                    if (isset($variations_to_override[$v['external_variation_id']])) {
                        if (OrderUtil::custom_orders_table_usage_is_enabled()) {
                            $update_query = "UPDATE {$wpdb->prefix}woocommerce_order_itemmeta oim " .
                                "INNER JOIN {$wpdb->prefix}woocommerce_order_items oi ON (oi.order_item_id=oim.order_item_id) " .
                                "INNER JOIN {$wpdb->prefix}wc_orders p ON (p.ID=oi.order_id) " .
                                "SET oim.meta_value=%d " .
                                "WHERE oim.meta_key='_variation_id' AND oim.meta_value=%d and not p.status in ('wc-completed', 'wc-cancelled', 'wc-refunded')";
                        } else {
                            $update_query = "UPDATE {$wpdb->prefix}woocommerce_order_itemmeta oim " .
                                "INNER JOIN {$wpdb->prefix}woocommerce_order_items oi ON (oi.order_item_id=oim.order_item_id) " .
                                "INNER JOIN {$wpdb->posts} p ON (p.ID=oi.order_id) " .
                                "SET oim.meta_value=%d " .
                                "WHERE oim.meta_key='_variation_id' AND oim.meta_value=%d and not p.post_status in ('wc-completed', 'wc-cancelled', 'wc-refunded')";
                        }

                        $wpdb->query($wpdb->prepare(
                            $update_query,
                            $v['variation_id'],
                            $variations_to_override[$v['external_variation_id']]
                        ));
                    }
                }
            }

            wp_update_post($post_arr);

            if ($step !== false) {
                return ResultBuilder::buildOk(array('product_id' => $product_id, 'step' => $step));
            }

        }

        if ($step === false || $step === 'finishing') {
            wc_delete_product_transients($product_id);

            delete_transient('wc_attribute_taxonomies');

            do_action('a2wl_add_product', $product_id);

            /**
             * todo: this function gives a warning because custom attributes like: pa_color, pa_shoe-size, etc
             * doesn't have taxonomies, but they should, therefore we suppress warnings as hot fix
             */
            @Utils::update_post_terms_count($product_id);
        }

        return apply_filters('a2wl_woocommerce_after_add_product', ResultBuilder::buildOk(array('product_id' => $product_id)), $product_id, $product, $params);
    }

    public function upd_product($product_id, $product, $params = array())
    {
        do_action('a2wl_woocommerce_upd_product', $product_id, $product, $params);
        
        global $wpdb;

        $wc_product = wc_get_product($product_id);

        if ($wc_product->get_status() == "trash" && !get_setting('untrash_product')) {
            return array("state" => "error", "message" => "I can not sync products placed in the trash");
        }

        // first, update some meta

        if (!empty($product['dimensions'])){
            if (!empty($product['dimensions']['weight'])){
                $wc_product->set_weight($product['dimensions']['weight']);
            }

            if (!empty($product['dimensions']['width'])){
                $wc_product->set_width($product['dimensions']['width']);
            }

            if (!empty($product['dimensions']['height'])){
                $wc_product->set_height($product['dimensions']['height']);
            }

            if (!empty($product['dimensions']['length'])){
                $wc_product->set_length($product['dimensions']['length']);
            }
        }

        if (!empty($product['affiliate_url']) && !a2wl_check_defined('A2WL_DISABLE_UPDATE_AFFILIATE_URL')) {
            if ($wc_product->is_type('external')) {
                /**
                 * @var \WC_Product_External $wc_product
                 */
                $wc_product->set_product_url($product['affiliate_url']);
            }
            $wc_product->update_meta_data('_a2w_product_url', $product['affiliate_url']);
        }

        if (!empty($product['url'])) {
            $wc_product->update_meta_data('_a2w_original_product_url', $product['url']);
        }

        if (!empty($product['ordersCount'])) {
            $wc_product->update_meta_data('_a2w_orders_count', intval($product['ordersCount']));
        }

        if (!empty($product['video'])) {
            $wc_product->update_meta_data('_a2w_video', $product['video']);
        }

        //save seller_id and store_id
        if (!empty($product['seller_id'])) {
            $wc_product->update_meta_data('_a2w_seller_id', $product['seller_id']);
        }

        if (!empty($product['store_id'])) {
            $wc_product->update_meta_data('_a2w_store_id', $product['store_id']);
        }

        if (!empty($product['extra_data'])){
            $wc_product->update_meta_data('_a2w_extra_data', $product['extra_data']);
        }

        // update shipping meta data
        $shipping_meta = new ProductShippingMeta($product_id);
        if (isset($product['shipping_default_method'])) {
            $shipping_meta->save_method($product['shipping_default_method'], false);
        }
        if (isset($product['shipping_to_country'])) {
            $shipping_meta->save_country_to($product['shipping_to_country'], false);
        }
        if (isset($product['shipping_cost'])) {
            $shipping_meta->save_cost($product['shipping_cost'], false);
        }
        $shipping_meta->save();

        $result = [
            "state" => "ok",
            "message" => "",
            "product_id" => $product_id
        ];

        $on_not_available_product = get_setting('on_not_available_product');
        $on_not_available_variation = (isset($params['on_not_available_variation'])? $params['on_not_available_variation'] : get_setting('on_not_available_variation'));
        $disable_add_new_variants = get_post_meta($product_id, '_a2w_disable_add_new_variants', true);
        $on_new_variation_appearance = $disable_add_new_variants ? "nothing" : (isset($params['on_new_variation_appearance'])? $params['on_new_variation_appearance'] : get_setting('on_new_variation_appearance'));

        // collect new variations
        $old_variations = $wpdb->get_col($wpdb->prepare("SELECT pm.meta_value FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm ON (p.ID=pm.post_id AND pm.meta_key='external_variation_id') WHERE post_parent = %d and post_type='product_variation' GROUP BY p.ID ORDER BY p.post_date desc", $product_id));
        if (!$old_variations) {
            $old_aliexpress_sku_props = get_post_meta($product_id, '_aliexpress_sku_props', true);
            if ($old_aliexpress_sku_props) {
                $external_variation_id = $product['id'] . '-' . implode('-', explode(';', $old_aliexpress_sku_props));
                $old_variations = array($external_variation_id);
            }
        }

        if ($old_variations){
            //previous version of API provided atrributes in another order
            //therefore here we check that new variants are not the old variants actually
            $matched_variations = [];
            foreach ($product['sku_products']['variations'] as $key => $variation) {
                if (!in_array($variation['id'], $old_variations)) {
                    $variation_parts = explode('-', $variation['id']);
                    $matched_old_variation = false;
                    foreach ($old_variations as $old_variation){
                        if ( Utils::string_contains_all($old_variation, $variation_parts) ){
                            $matched_old_variation = $old_variation;
                            break;
                        }
                    }

                    if ($matched_old_variation !== false){
                        $matched_variations[] = array('new' => $variation['id'], 'existed' => $matched_old_variation);
                        $product['sku_products']['variations'][$key]['id'] = $matched_old_variation;
                    }
                }
            }

            if (!empty($matched_variations)){
                a2wl_error_log('we matched the following vars during sync:');
                a2wl_error_log(print_r($matched_variations, true));
            }
        }

        $new_variations = array();
        if (!empty($product['sku_products']['variations']) && count($product['sku_products']['variations']) > 1) {
            // if have more then one variations
            foreach ($product['sku_products']['variations'] as $variation) {
                if (!in_array($variation['id'], $old_variations)) {
                    $new_variations[] = $variation['id'];
                }
            }
        }

        $term = wp_get_object_terms($product_id, 'product_type', array('fields' => 'names'));
        $product_type = is_array($term) && isset($term[0]) ? $term[0] : '';

        $first_ava_var = false;
        $has_aliexpress_ava_var = false;
        $variations_active_cnt = 0;
        $total_quantity = 0;
        if (!empty($product['sku_products']['variations'])) {
            foreach ($product['sku_products']['variations'] as $variation) {
                $has_aliexpress_ava_var = $has_aliexpress_ava_var || intval($variation['quantity']) > 0;
                if (($product_type === "external" || intval($variation['quantity']) > 0)
                    && !in_array($variation['id'], $product['skip_vars'])
                    && ($wc_product->get_status() == "trash" || $on_new_variation_appearance === 'add' || !in_array($variation['id'], $new_variations))
                ) {
                    if (!$first_ava_var) {
                        $first_ava_var = $variation;
                    }
                    $variations_active_cnt++;
                    $total_quantity += intval($variation['quantity']);
                }
            }
        }

        if (!$has_aliexpress_ava_var) {
            //save product changes for future email alerts
            $this->product_change_model->saveProductNotAvailable($product_id);
        }

        if ($product_type != 'external') {
            $new_product_type = $variations_active_cnt > 1 ? 'variable' : 'simple';
            if ($new_product_type != $product_type) {
                $this->changeProductType($product_id, $new_product_type);
                /**
                 * reload product here because when we change product type in woocommerce, the product is changed and setters are changed too
                 */
                $wc_product = wc_get_product($product_id);
            }
        }

        // Delete and then try to update _aliexpress_sku_props
        $wc_product->delete_meta_data('_aliexpress_sku_props');
        if ($first_ava_var) {
            $aliexpress_sku_props_id_arr = array();
            foreach ($first_ava_var['attributes'] as $cur_var_attr) {
                foreach ($product['sku_products']['attributes'] as $attr) {
                    if (isset($attr['value'][$cur_var_attr])) {
                        $aliexpress_sku_props_id_arr[] = isset($attr['value'][$cur_var_attr]['original_id']) ? $attr['value'][$cur_var_attr]['original_id'] : $attr['value'][$cur_var_attr]['id'];
                        break;
                    }
                }
            }
            $aliexpress_sku_props_id = $aliexpress_sku_props_id_arr ? implode(";", $aliexpress_sku_props_id_arr) : "";
            if ($aliexpress_sku_props_id) {
                $wc_product->update_meta_data('_aliexpress_sku_props', $aliexpress_sku_props_id);
            }
        }

        if ($first_ava_var) {
            $wc_product->delete_meta_data('_a2w_outofstock');
        } else {
            $wc_product->update_meta_data('_a2w_outofstock', true);
        }

        // set default shipping country from
        if ($first_ava_var && !empty($first_ava_var['country_code'])) {
            $wc_product->update_meta_data('_a2w_country_code', $first_ava_var['country_code']);
        } else if (isset($product['local_seller_tag']) && strlen($product['local_seller_tag']) == 2) {
            $wc_product->update_meta_data('_a2w_country_code', $product['local_seller_tag']);
        }

        // update variations
        if (!a2wl_check_defined('A2WL_DO_NOT_IMPORT_VARIATIONS') && !$wc_product->is_type('external') && !empty($product['sku_products']['variations']) && count($product['sku_products']['variations']) > 1) {
            foreach ($product['sku_products']['variations'] as &$var) {
                $var['image'] = (!isset($var['image']) || in_array(md5($var['image']), $product['skip_images'])) ? '' : $var['image'];
            }
            $this->add_variation($product_id, $product, true, $params);
        }

        // update global stock
        if (!$wc_product->is_type('external')) {
            if (get_option('woocommerce_manage_stock', 'no') === 'yes') {
                if ($total_quantity > 0 || in_array($on_not_available_product, array('zero', 'trash'))) {

                    $backorders = $wc_product->get_backorders();
                    $backorders = $backorders ? $backorders : 'no';

                    $wc_product->set_backorders($backorders);
                    $wc_product->set_manage_stock('yes');
                    $wc_product->set_stock_status($total_quantity ? 'instock' : 'outofstock');

                    if (!$product['disable_var_quantity_change']) {
                        $wc_product->set_stock_quantity($total_quantity);
                    }
                }
            } else {
                $wc_product->set_manage_stock('no');
                $wc_product->set_stock_status('');
                $wc_product->set_stock_quantity('');
            }
        }

        // update global price
        if (!$product['disable_var_price_change'] && ($first_ava_var || $on_not_available_product !== 'trash')) {
            $this->update_price($wc_product, $first_ava_var);
        }

        $productDeleted = false;
        if ($first_ava_var) {
            if ($wc_product->get_status() == "trash") {
                $wc_product->delete_meta_data('_a2w_autoremove');
                wp_untrash_post($product_id);
            }
            // product available >>>
            if ($wc_product->is_type('external') && $first_ava_var) {
                $init_status = $wc_product->get_meta('_a2w_init_product_status', true);
                if ($wc_product->get_status() !== $init_status) {
                    $wc_product->set_status($init_status);
                }
                $wc_product->delete_meta_data('_a2w_init_product_status');
            }
        } else {
            // product not available >>>
            if ($on_not_available_product === 'trash') {
                $wc_product->update_meta_data("_a2w_autoremove", 1);
                $wc_product->save_meta_data();
                $productDeleted = true;
            } else if ($on_not_available_product === 'zero') {
            //    $tmp_skip_meta = get_post_meta($product_id, "_a2w_skip_meta", true);

                foreach ($wc_product->get_children() as $var_id) {
                    if ($on_not_available_variation === "trash") {
                        $var = wc_get_product($var_id);
                        Utils::delete_post_images($var_id);
                        $var->delete(true);
                    } else if (!$product['disable_var_quantity_change'] && ($on_not_available_variation === "zero" || $on_not_available_variation === 'zero_and_disable')) {
                        $var = wc_get_product($var_id);

                        $backorders = $var->get_backorders();
                        $backorders = $backorders ? $backorders : 'no';

                        $var->set_status($on_not_available_variation === 'zero_and_disable' ? 'private' : $var->get_status());
                        $var->set_backorders($backorders);
                        $var->set_stock_quantity(0);
                        $var->set_stock_status('outofstock');
                        $var->save();
                    }
                }

                $cur_status = $wc_product->get_status();
                if ($wc_product->is_type('external') && $cur_status !== 'draft') {
                    $wc_product->update_meta_data('_a2w_init_product_status', $wc_product->get_status());
                    $wc_product->set_status('draft');
                }

               // update_post_meta($product_id, "_a2w_skip_meta", $tmp_skip_meta);
            }
        }

        //A2WL_FIX_RELOAD_IMAGES - special flag (for update only), if product images is disapear, reload it.
        if (a2wl_check_defined('A2WL_FIX_RELOAD_IMAGES') && isset($product['images'])) {
            $old_thumb_id = get_post_thumbnail_id($product_id);
            if ($old_thumb_id) {
                Utils::delete_attachment($old_thumb_id, true);
                $wc_product->delete_meta_data('_thumbnail_id');
            }

            $old_image_gallery = $wc_product->get_meta('_product_image_gallery', true);
            if ($old_image_gallery) {
                $image_ids = explode(",", $old_image_gallery);
                foreach ($image_ids as $image_id) {
                    Utils::delete_attachment($image_id, true);
                }
                $wc_product->delete_meta_data('_product_image_gallery');
            }

            $thumb_url = '';
            $image_to_load = array();
            foreach ($product['images'] as $image) {
                if (!in_array(md5($image), $product['skip_images'])) {
                    $image_to_load[] = $image;

                    if (!$thumb_url) {
                        // if not thumb not checked, check first available image
                        $thumb_url = $image;
                    }
                }
            }

            $this->set_images($product, $product_id, $thumb_url, $image_to_load, true, isset($product['title']) && $product['title'] ? $product['title'] : "Product " . $product['id']);
        }

        if (isset($params['manual_update']) && $params['manual_update'] && a2wl_check_defined('A2WL_FIX_RELOAD_DESCRIPTION') && !get_setting('not_import_description')) {
            $post_arr = array('ID' => $product_id, 'post_content' => (isset($product['description']) ? $this->build_description($product_id, $product) : ''));
            wp_update_post($post_arr);
        }

        wc_delete_product_transients($product_id);

        if (empty($params['skip_last_update'])) {
            $wc_product->update_meta_data('_a2w_last_update', time());
        }

        if ($productDeleted) {
            $wc_product->delete();
        } else {
            $wc_product->save();
        }

        do_action('a2wl_after_upd_product', $product_id, $product, $params);

        delete_transient('wc_attribute_taxonomies');

        return apply_filters('a2wl_woocommerce_after_upd_product', $result, $product_id, $product, $params);
    }

    public function build_description($product_id, $product)
    {
        $html = $product['description'];

        if (function_exists('mb_convert_encoding')) {
            $html = trim(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        } else {
            $html = htmlspecialchars_decode(utf8_decode(htmlentities($html, ENT_COMPAT, 'UTF-8', false)));
        }

        if (empty(trim($html))) {
            return trim($html);
        }

        if (function_exists('libxml_use_internal_errors')) {
            libxml_use_internal_errors(true);
        }
        if ($html && class_exists('DOMDocument')) {
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $dom->formatOutput = true;

            $elements = $dom->getElementsByTagName('img');
            for ($i = $elements->length; --$i >= 0;) {
                $e = $elements->item($i);

                if (isset($product['tmp_move_images'])) {
                    foreach ($product['tmp_move_images'] as $img_id => $source) {
                        if (isset($tmp_all_images[$img_id]) && !in_array($img_id, $product['skip_images'])) {
                            $image_to_load[$img_id] = $tmp_all_images[$img_id]['image'];
                        }
                    }
                }

                $img_id = md5($e->getAttribute('src'));
                if (in_array($img_id, $product['skip_images']) || isset($product['tmp_move_images'][$img_id])) {
                    $e->parentNode->removeChild($e);
                } else if (!get_setting('use_external_image_urls')) {
                    $tmp_title = isset($product['title']) && $product['title'] ? $product['title'] : "Product " . $product['id'];

                    // if have edited image, than user initial url
                    $clear_image_url = !empty($product['tmp_edit_images'][$img_id]) ? $e->getAttribute('src') : Utils::clear_image_url($e->getAttribute('src'));

                    $attachment_id = $this->attachment_model->create_attachment($product_id, $clear_image_url, array('inner_post_id' => $product_id, 'title' => $tmp_title, 'alt' => $tmp_title, 'edit_images' => !empty($product['tmp_edit_images']) ? $product['tmp_edit_images'] : array()));
                    $attachment_url = wp_get_attachment_url($attachment_id);
                    $e->setAttribute('src', $attachment_url);
                } else if (!empty($product['tmp_edit_images'][$img_id])) {
                    $e->setAttribute('src', $product['tmp_edit_images'][$img_id]['attachment_url']);
                }
            }

            $html = $dom->saveHTML();
        }

        $html = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $html);

        return html_entity_decode(trim($html), ENT_COMPAT, 'UTF-8');
    }

    public function set_images($product, $product_id, $thumb_url, $images, $update, $title = '', $params = array())
    {
        $step = isset($params['step']) ? $params['step'] : false;

        if ($thumb_url && $thumb_url != 'empty' && (!get_post_thumbnail_id($product_id) || $update)
            && ($step === false || $step === 'images#' . md5($thumb_url))) {
            try {
                $tmp_title = !empty($title) ? $title : null;
                $thumb_id = $this->attachment_model->create_attachment($product_id, $thumb_url, array('inner_post_id' => $product_id, 'title' => $tmp_title, 'alt' => $tmp_title, 'edit_images' => !empty($product['tmp_edit_images']) ? $product['tmp_edit_images'] : array()));
                if (is_wp_error($thumb_id)) {
                    a2wl_error_log("Can't download $thumb_url: " . print_r($thumb_id, true));
                } else {
                    set_post_thumbnail($product_id, $thumb_id);
                }
            } catch (Throwable $e) {
                a2wl_print_throwable($e);
            }
        }

        if ($images) {
            /*
             * todo: make a test for this thing!!
            $wc_product = wc_get_product( $product_id );
            $cur_product_image_gallery = $wc_product->get_meta('_product_image_gallery', true);
            */
            $cur_product_image_gallery = get_post_meta($product_id, '_product_image_gallery', true);
            $cur_product_image_gallery = $cur_product_image_gallery ? $cur_product_image_gallery : '';

            if (!$cur_product_image_gallery || $update) {
                $image_gallery_ids = $step !== false ? $cur_product_image_gallery : '';
                $cnt = 0;
                foreach ($images as $image_url) {
                    $cnt++;
                    if ($step === false || $step === 'images#' . md5($image_url)) {
                        if ($image_url == $thumb_url) {
                            continue;
                        }
                        try {
                            $tmp_title = !empty($title) ? ($title . ' ' . $cnt) : null;
                            $new_image_gallery_id = $this->attachment_model->create_attachment($product_id, $image_url, array('inner_post_id' => $product_id, 'title' => $tmp_title, 'alt' => $tmp_title, 'edit_images' => !empty($product['tmp_edit_images']) ? $product['tmp_edit_images'] : array()));
                            if (is_wp_error($new_image_gallery_id)) {
                                a2wl_error_log("Can't download $image_url" . print_r($new_image_gallery_id, true));
                            } else {
                                $image_gallery_ids .= $new_image_gallery_id . ',';
                            }
                        } catch (Throwable|\Exception $e) {
                            a2wl_print_throwable($e);
                        }
                    }
                }
                update_post_meta($product_id, '_product_image_gallery', $image_gallery_ids);
                /*
                 *    $wc_product = wc_get_product( $product_id );
                    $wc_product->update_meta_data('_product_image_gallery', $image_gallery_ids);
                    $wc_product->save_meta_data();
                 */
            }
        }
    }

    public function update_price(&$product, $variation, $rest_price = false): void
    {
        if (!is_a($product, 'WC_Product')) {
            $wc_product = wc_get_product($product);
        } else {
            $wc_product = $product;
        }

        if ($variation) {
            $price = $variation['price'] ?? 0;
            $regular_price = $variation['regular_price'] ?? $price;

            $wc_product->update_meta_data('_aliexpress_regular_price', $regular_price);
            $wc_product->update_meta_data('_aliexpress_price', $price);

            if (isset($variation['calc_price'])) {
                $price = $variation['calc_price'];
                $regular_price = $variation['calc_regular_price'] ?? $price;
            }

            $wc_product->set_regular_price($regular_price);
            if (round(abs($regular_price - $price), 2) == 0) {
                $wc_product->set_price($regular_price);
                $wc_product->set_sale_price('');
            } else {
                $wc_product->set_price($price);
                $wc_product->set_sale_price($price);
            }
        } else if ($rest_price) {
            $wc_product->set_regular_price(0);
            $wc_product->set_price(0);
            $wc_product->set_sale_price('');

            $wc_product->delete_meta_data('_aliexpress_regular_price');
            $wc_product->delete_meta_data('_aliexpress_price');
        }

        $wc_product->save_meta_data();
    }

    private function set_attributes($product_id, $attributes)
    {
        if (defined('A2WL_IMPORT_EXTENDED_ATTRIBUTE')) {
            $extended_attribute = filter_var(A2WL_IMPORT_EXTENDED_ATTRIBUTE, FILTER_VALIDATE_BOOLEAN);
        } else {
            $extended_attribute = get_setting('import_extended_attribute');
        }

        $attributes = apply_filters('a2wl_set_product_attributes', $attributes);

        if ($extended_attribute) {
            $this->helper->set_woocommerce_attributes($product_id, $attributes);
        } else {
            $tmp_product_attr = array();
            foreach ($attributes as $attr) {
                if (!isset($tmp_product_attr[$attr['name']])) {
                    $tmp_product_attr[$attr['name']] = is_array($attr['value']) ? $attr['value'] : array($attr['value']);
                } else {
                    $tmp_product_attr[$attr['name']] = array_merge($tmp_product_attr[$attr['name']], is_array($attr['value']) ? $attr['value'] : array($attr['value']));
                }
            }

            $product_attributes = array();
            foreach ($tmp_product_attr as $name => $value) {
                $product_attributes[str_replace(' ', '-', $name)] = array(
                    'name' => $name,
                    'value' => implode(', ', $value),
                    'position' => count($product_attributes),
                    'is_visible' => 1,
                    'is_variation' => 0,
                    'is_taxonomy' => 0,
                );
            }

            update_post_meta($product_id, '_product_attributes', $product_attributes);
        }
    }

    private function build_categories($product)
    {
        if (isset($product['categories']) && $product['categories']) {
            return is_array($product['categories']) ? array_map('intval', $product['categories']) : array(intval($product['categories']));
        } else if (isset($product['category_name']) && $product['category_name']) {
            $category_name = sanitize_text_field($product['category_name']);
            if ($category_name) {
                $cat = get_terms('product_cat', array('name' => $category_name, 'hide_empty' => false));
                if (empty($cat)) {
                    $cat = wp_insert_term($category_name, 'product_cat');
                    $cat_id = $cat['term_id'];
                } else {
                    $cat_id = $cat->term_id;
                }
                return array($cat_id);
            }
        }
        return array();
    }

    private function add_variation($product_id, $product, $is_update = false, $params = array())
    {
        global $wpdb;

        $has_new_variants = false;
        $is_price_changed = false;
        $is_stock_changed = false;

        $step = isset($params['step']) ? $params['step'] : false;

        $result = array('state' => 'ok', 'message' => '');
        $variations = $product['sku_products'];

        $disable_add_new_variants = get_post_meta($product_id, '_a2w_disable_add_new_variants', true);
        $on_new_variation_appearance = $disable_add_new_variants ? "nothing" : (isset($params['on_new_variation_appearance'])? $params['on_new_variation_appearance'] : get_setting('on_new_variation_appearance'));
        $on_not_available_variation = (isset($params['on_not_available_variation'])? $params['on_not_available_variation'] : get_setting('on_not_available_variation'));

        $localCurrency = strtoupper(get_setting('local_currency'));

        $woocommerce_manage_stock = get_option('woocommerce_manage_stock', 'no');

        if ($localCurrency === 'USD') {
            $localCurrency = '';
        }

        if ($localCurrency) {
            $currency_conversion_factor = 1;
        } else {
            $currency_conversion_factor = floatval(get_setting('currency_conversion_factor'));
        }

        if (a2wl_check_defined('A2WL_FIX_RELOAD_VARIATIONS')) {
            delete_post_meta($product_id, '_a2w_original_variations_attributes');
        }

        $deleted_variations_attributes = get_post_meta($product_id, '_a2w_deleted_variations_attributes', true);
        $deleted_variations_attributes = $deleted_variations_attributes && is_array($deleted_variations_attributes) ? $deleted_variations_attributes : array();

        $original_variations_attributes = get_post_meta($product_id, '_a2w_original_variations_attributes', true);
        $original_variations_attributes = $original_variations_attributes && is_array($original_variations_attributes) ? $original_variations_attributes : array();

        $attributes = array();
        $used_variation_attributes = array();

        $tmp_attributes = get_post_meta($product_id, '_product_attributes', true);
        if (!$tmp_attributes) {
            $tmp_attributes = array();
        }

        $not_remove_variation_attr = a2wl_check_defined('A2WL_NOT_REMOVE_VARIATION_ATTR');
        foreach ($tmp_attributes as $key => $attr) {
            if (!intval($attr['is_variation']) || $not_remove_variation_attr) {
                if (a2wl_check_defined('A2WL_FIX_VAR_PRODUCT_ATTRIBUTES')) {
                    // fix broken variation product attributes
                    $attributes[$attr['name']] = $attr;
                } else {
                    $attributes[$key] = $attr;
                }
            }
        }

        $old_swatch_type_options = get_post_meta($product_id, '_swatch_type_options', true);
        $old_swatch_type_options = $old_swatch_type_options ? $old_swatch_type_options : array();

        $swatch_type_options = array();

        //if names of variation attributes has been change, we need fix variation attribute names
        foreach ($variations['attributes'] as $key => $attr) {
            foreach ($original_variations_attributes as $ova_val) {
                if (sanitize_title($attr['name']) === sanitize_title($ova_val['name']) && !empty($ova_val['current_name'])) {
                    if (!isset($variations['attributes'][$key]['original_name'])) {
                        $variations['attributes'][$key]['original_name'] = $ova_val['name'];
                    }
                    $variations['attributes'][$key]['name'] = $ova_val['current_name'];

                    if (!empty($ova_val['values'])) {
                        foreach ($attr['value'] as $val_id => $val) {
                            foreach ($ova_val['values'] as $ova_val_key => $ova_val_val) {
                                if ($val['id'] == $ova_val_val['oroginal_id']) {
                                    $variations['attributes'][$key]['value'][$val_id]['name'] = $ova_val_val['name'];
                                }
                            }
                        }
                    }
                }
            }
        }

        $old_variations_tmp = $wpdb->get_results($wpdb->prepare("SELECT p.ID as id, pm.meta_value as external_variation_id FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm ON (p.ID=pm.post_id AND pm.meta_key='external_variation_id') WHERE post_parent = %d and post_type='product_variation' GROUP BY p.ID ORDER BY p.post_date desc", $product_id), ARRAY_A);
        $old_variations = array();
        foreach ($old_variations_tmp as $v) {
            $old_variations[$v['external_variation_id']] = $v;
        }

        foreach ($variations['attributes'] as $key => $attr) {
            $attr_tax = $this->helper->cleanTaxonomyName($attr['name']);
            $swatch_id = md5(sanitize_title($attr_tax));
            $variations['attributes'][$key]['tax'] = $attr_tax;
            $variations['attributes'][$key]['swatch_id'] = $swatch_id;
            $variations['attributes'][$key]['attribute_taxonomies'] = true;

            $used_variation_attributes[$attr_tax] = array('original_attribute_id' => $attr['id'], 'attribute_taxonomies' => true, 'values' => array());

            //added 03.02.2018 ---
            if (!empty($old_swatch_type_options) && isset($old_swatch_type_options[$swatch_id])) {
                $swatch_type_options[$swatch_id] = $old_swatch_type_options[$swatch_id];
            } /* end added */else {
                $swatch_type_options[$swatch_id]['type'] = 'radio';
                $swatch_type_options[$swatch_id]['layout'] = 'default';
                $swatch_type_options[$swatch_id]['size'] = 'swatches_image_size';

                $swatch_type_options[$swatch_id]['attributes'] = array();
            }

            $attr_values = array();
            foreach ($attr['value'] as &$val) {
                $swatch_value_id = md5(sanitize_title(strtolower(htmlspecialchars($val['name'], ENT_NOQUOTES))));
                $val['swatch_value_id'] = $swatch_value_id;

                $has_variation = false;
                foreach ($variations['variations'] as $variation) {
                    if ($is_update && $on_new_variation_appearance !== "add" && !isset($old_variations[$variation['id']])) {
                        // not need add attribute value if this update call and on_new_variation_appearance flag eq "nothing"
                        continue;
                    }

                    if (!in_array($variation['id'], $product['skip_vars'])) {
                        foreach ($variation['attributes'] as $va) {
                            if ($va == $val['id']) {
                                $has_variation = true;
                            }
                        }
                    }
                }

                if (!$has_variation && !a2wl_check_defined('A2WL_SKIP_REMOVED_VARIATIONS_CHECK')) {
                    unset($swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]);
                    continue;
                }

                $attr_values[] = $val['name'];

                $attr_image = "";

                if (get_setting('use_external_image_urls')) {
                    if (isset($val['thumb']) && $val['thumb']) {
                        $attr_image = $val['thumb'];
                    } else if (isset($val['image']) && $val['image']) {
                        $attr_image = $val['image'];
                    }
                } else {
                    if (isset($val['image']) && $val['image']) {
                        $attr_image = $val['image'];
                    } else if (isset($val['thumb']) && $val['thumb']) {
                        $attr_image = $val['thumb'];
                    }
                }

                $RELOAD_ATTR_IMAGES = a2wl_check_defined('A2WL_FIX_RELOAD_IMAGES') || a2wl_check_defined('A2WL_FIX_RELOAD_ATTR_IMAGES');

                //added 03.02.2018
                if (isset($swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]) && !$RELOAD_ATTR_IMAGES) {
                    continue;
                }

                //end added

                if ($attr_image || !empty($val['color'])) {
                    $swatch_type_options[$swatch_id]['type'] = 'product_custom';
                }

                $swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]['type'] = 'color';
                $swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]['color'] = empty($val['color']) ? '#FFFFFF' : $val['color'];
                $swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]['image'] = 0;

                if (($step === false || $step === 'variations#attributes') && $attr_image) {
                    $swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]['type'] = 'image';

                    $attr_image = Utils::clear_image_url($attr_image);

                    $old_attachment_id = !empty($old_swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]['image']) ? intval($old_swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]['image']) : 0;

                    if ($is_update && $RELOAD_ATTR_IMAGES) {
                        if (intval($old_attachment_id) > 0) {
                            Utils::delete_attachment($old_attachment_id, true);
                        }
                        $attachment_id = $this->attachment_model->create_attachment($product_id, $attr_image, array('inner_post_id' => $product_id, 'title' => null, 'alt' => null, 'edit_images' => !empty($product['tmp_edit_images']) ? $product['tmp_edit_images'] : array()));
                    } else if (!in_array(md5($attr_image), $product['skip_images'])) {
                        $attachment_id = $old_attachment_id ? $old_attachment_id : $this->attachment_model->create_attachment($product_id, $attr_image, array('inner_post_id' => $product_id, 'title' => null, 'alt' => null, 'edit_images' => !empty($product['tmp_edit_images']) ? $product['tmp_edit_images'] : array()));
                    }

                    if (!empty($attachment_id)) {
                        $swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]['image'] = $attachment_id; //+
                    } else if (!empty($old_attachment_id)) {
                        $swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]['image'] = $old_attachment_id; //+
                    }
                }
            }

            // if this is deleted attr or attr in product skip_attr meta, then not load this attribute
            $is_deleted_attr = false;
            $tmp_attr_name = sanitize_title($attr['name']);
            if (!empty($product['skip_attr'])) {
                $skip_attr = array_map('sanitize_title', is_array($product['skip_attr']) ? $product['skip_attr'] : array($product['skip_attr']));
                $is_deleted_attr = in_array($tmp_attr_name, $skip_attr);
            }
            foreach ($deleted_variations_attributes as $key_del_attr => $del_attr) {
                $del_name = sanitize_title(isset($del_attr['current_name']) ? $del_attr['current_name'] : $del_attr['name']);
                if ($del_name == $tmp_attr_name || $key_del_attr == $tmp_attr_name) {
                    $is_deleted_attr = true;
                }
            }

            if (($step === false || $step === 'variations#attributes') && !$is_deleted_attr) {
                $attributes[$attr_tax] = array(
                    'name' => $attr_tax,
                    'value' => '',
                    'position' => isset($tmp_attributes[$attr_tax]['position']) ? $tmp_attributes[$attr_tax]['position'] : count($attributes),
                    'is_visible' => isset($tmp_attributes[$attr_tax]['is_visible']) ? $tmp_attributes[$attr_tax]['is_visible'] : '0',
                    'is_variation' => '1',
                    'is_taxonomy' => '1',
                );
                $this->helper->add_attribute($product_id, $attr['name'], $attr_values);
            }
        }

        $used_attributes = array();
        foreach ($attributes as $a) {
            if ($a['is_taxonomy']) {
                $used_attributes[] = $a['name'];
            }
        }
        $this->helper->clean_woocommerce_product_attributes($product_id, $used_attributes);

        if ($step === false || $step === 'variations#attributes') {
            update_post_meta($product_id, '_product_attributes', $attributes);
        }

        if ($is_update && a2wl_check_defined('A2WL_FIX_RELOAD_VARIATIONS')) {
          //  $tmp_skip_meta = get_post_meta($product_id, "_a2w_skip_meta", true);

            $wc_product = wc_get_product($product_id);
            foreach ($wc_product->get_children() as $var_id) {
                $var = wc_get_product($var_id);
                Utils::delete_post_images($var_id);
                $var->delete(true);
            }

         //  update_post_meta($product_id, "_a2w_skip_meta", $tmp_skip_meta);
        }

        $old_vids_ids = array();
        foreach ($variations['variations'] as $variation) {
            if (in_array($variation['id'], $product['skip_vars'])) {
                continue;
            }

            $old_vids_ids[] = $wpdb->prepare("%s", $variation['id']);
        }


        $old_vids = [];
        if (!empty($old_vids_ids)) {
            $old_vids_ids = implode(', ', $old_vids_ids);
            $_old_vids = $wpdb->get_results($wpdb->prepare("SELECT p.ID, pm.meta_value as variation_id, p.post_status, pm_stock.meta_value as 'quantity', pm_regular.meta_value as 'regular_price', pm_sale.meta_value as 'price' FROM $wpdb->posts p " .
                "INNER JOIN $wpdb->postmeta pm ON (p.ID=pm.post_id AND pm.meta_key='external_variation_id' AND pm.meta_value IN ($old_vids_ids)) " .
                "LEFT JOIN $wpdb->postmeta pm_stock ON (p.ID=pm_stock.post_id AND pm_stock.meta_key='_stock') " .
                "LEFT JOIN $wpdb->postmeta pm_regular ON (p.ID=pm_regular.post_id AND pm_regular.meta_key='_aliexpress_regular_price') " .
                "LEFT JOIN $wpdb->postmeta pm_sale ON (p.ID=pm_sale.post_id AND pm_sale.meta_key='_aliexpress_price') " .
                "WHERE post_parent = %d and post_type='product_variation' order by post_date desc", $product_id), ARRAY_A);

            $old_vids = [];

            foreach ($_old_vids as $old_vid) {
                $old_vids[$old_vid['variation_id']] = $old_vid;
            }

            unset($_old_vids);
        }

        //variations foreach starts here

        $variation_images = array();
        foreach ($variations['variations'] as $variation) {
            $need_process = $step === false || $step === 'variations#variation#' . $variation['id'];

            if (in_array($variation['id'], $product['skip_vars'])) {
                continue;
            }
            unset($variation_id);

            $old_vid = $old_vids[$variation['id']] ?? false;

            if (!$old_vid && (!$is_update || $on_new_variation_appearance === 'add')) {

                // this is a new variant, thefore do NOT CHECK for $is_price_changed and $is_stock_changed

                if ($is_update && !$has_new_variants) {
                    $has_new_variants = array('variation_id' => $variation['id']);
                    a2wl_info_log('Has new variant! Variant ID: ' . $variation['id']);
                }

                if ($need_process) {
                    $tmp_variation = array(
                        'post_title' => 'Product #' . $product_id . ' Variation',
                        'post_content' => '',
                        'post_status' => in_array($variation['id'], $product['skip_vars']) && $on_not_available_variation === 'zero_and_disable' ? 'private' : 'publish',
                        'post_parent' => $product_id,
                        'post_type' => 'product_variation',
                        'meta_input' => array(
                            'external_variation_id' => $variation['id'],
                            '_sku' => $variation['sku'],
                        ),
                    );

                    $variation_id = wp_insert_post($tmp_variation);
                }

                // build _aliexpress_sku_props -->
                $aliexpress_sku_props_id_arr = array();
                foreach ($variation['attributes'] as $cur_var_attr) {
                    foreach ($variations['attributes'] as $attr) {
                        if (isset($attr['value'][$cur_var_attr])) {
                            $aliexpress_sku_props_id_arr[] = isset($attr['value'][$cur_var_attr]['original_id']) ? $attr['value'][$cur_var_attr]['original_id'] : $attr['value'][$cur_var_attr]['id'];
                            break;
                        }
                    }
                }
                $aliexpress_sku_props_id = $aliexpress_sku_props_id_arr ? implode(";", $aliexpress_sku_props_id_arr) : "";
                if ($need_process && $aliexpress_sku_props_id) {
                    update_post_meta($variation_id, '_aliexpress_sku_props', $aliexpress_sku_props_id);
                }
                // <-- build _aliexpress_sku_props

                $variation_attribute_list = array();
                foreach ($variation['attributes'] as $va) {
                    $attr_tax = "";
                    $attr_value = "";
                    foreach ($variations['attributes'] as $attr_key => $attr) {
                        $tmp_name = sanitize_title($attr['name']);

                        foreach ($attr['value'] as $val) {
                            if ($val['id'] == $va) {
                                $attr_tax = $attr['tax'];
                                $attr_value = $attr['attribute_taxonomies'] ? $this->helper->cleanTaxonomyName(htmlspecialchars($val['name'], ENT_NOQUOTES), false, false) : $val['name'];
                                // build original variations attributes
                                if (!isset($original_variations_attributes[$tmp_name])) {
                                    $original_variations_attributes[$tmp_name] = array('original_attribute_id' => $attr['id'], 'current_name' => $attr['name'], 'name' => !empty($attr['original_name']) ? $attr['original_name'] : $attr['name'], 'values' => array());
                                }

                                $original_variations_attributes[$tmp_name]['values'][$val['id']] = array(
                                    'id' => $val['id'],
                                    'name' => $val['name'],
                                    'oroginal_id' => isset($val['src_id']) ? $variations['attributes'][$attr_key]['value'][$val['src_id']]['id'] : $val['id'],
                                    'oroginal_name' => isset($val['src_id']) ? $variations['attributes'][$attr_key]['value'][$val['src_id']]['name'] : $val['name'],
                                );

                                break;
                            }
                        }
                        if ($attr_tax && $attr_value) {
                            break;
                        }
                    }

                    if ($attr_tax && $attr_value) {
                        $variation_attribute_list[] = array('key' => ('attribute_' . $attr_tax), 'value' => $attr_value);

                        // collect used variation attribute values
                        if (isset($used_variation_attributes[$attr_tax])) {
                            $used_variation_attributes[$attr_tax]['values'][] = $attr_value;
                        }
                    }
                }

                if ($need_process) {
                    foreach ($variation_attribute_list as $vai) {
                        update_post_meta($variation_id, sanitize_title($vai['key']), $vai['value']);
                    }
                }
                // upload set variation image
                if ($need_process && isset($variation['image']) && $variation['image']) {
                    $thumb_id = $this->attachment_model->create_attachment($product_id, $variation['image'], array('inner_post_id' => $variation_id, 'title' => null, 'alt' => null, 'edit_images' => !empty($product['tmp_edit_images']) ? $product['tmp_edit_images'] : array()));
                    set_post_thumbnail($variation_id, $thumb_id);
                }

            } else if ($old_vid) {

                if ($is_update) {
                    if (!$is_price_changed) {

                        if (!is_null($old_vid['price']) && !is_null($old_vid['regular_price'])) {

                            //make sure the price meta field and the regular_price meta field exist else don't check this variant

                            $price = floatval(isset($variation['price']) ? $variation['price'] : 0);
                            $regular_price = floatval(isset($variation['regular_price']) ? $variation['regular_price'] : 0);

                            $old_price = floatval(isset($old_vid['price']) ? $old_vid['price'] : 0);
                            $old_regular_price = floatval(isset($old_vid['regular_price']) ? $old_vid['regular_price'] : 0);

                            if ($price !== $old_price || $regular_price !== $old_regular_price) {
                                $is_price_changed = array('variation_id' => $variation['id'], 'old_price' => $old_price, 'price' => $price, 'old_regular_price' => $old_regular_price, 'regular_price' => $regular_price);

                                // if the currency is changed, then the new price value always will differ from the old price value!

                                a2wl_info_log('Prices are changed! Variant ID: ' . $variation['id'] . ' Old Prices: ' . $old_price . ', ' . $old_regular_price . ', New Prices:' . $price . ', ' . $regular_price);

                            }
                        }

                    }

                    if (!$is_stock_changed) {

                        if (!is_null($old_vid['quantity'])) {

                            //make sure the quantity meta field exists else don't check this variant

                            $quantity = intval($variation['quantity']);
                            $old_quantity = intval($old_vid['quantity']);

                            if ($quantity !== $old_quantity) {
                                $is_stock_changed = array('variation_id' => $variation['id'], 'old_quantity' => $old_quantity, 'quantity' => $quantity);
                                a2wl_info_log('Stock is changed! Variant ID: ' . $variation['id'] . ' Old Stock: ' . $old_quantity . ' New Stock: ' . $quantity);
                            }
                        }

                    }

                }

                $variation_id = $old_vid['ID'];

                if ($need_process && $old_vid['post_status'] === 'trash') {
                    wp_untrash_post($variation_id);
                }

                $aliexpress_sku_props_id = get_post_meta($variation_id, '_aliexpress_sku_props', true);
                $aliexpress_sku_props_id_arr = $aliexpress_sku_props_id ? explode(";", $aliexpress_sku_props_id) : array();

                foreach ($used_variation_attributes as $attr_tax => $v) {
                    $tmp_attr_name = 'attribute_' . sanitize_title($attr_tax);
                    if ($attr_value = get_post_meta($variation_id, $tmp_attr_name, true)) {
                        // collect used variation attribute values
                        $used_variation_attributes[$attr_tax]['values'][] = $attr_value;

                        // if user change variation atrributes values, then need update swatch(if new swatch not exist)
                        $curr_swatch_value_id = md5(sanitize_title(strtolower($attr_value)));
                        foreach ($aliexpress_sku_props_id_arr as $var_attr_id) {
                            foreach ($variations['attributes'] as $external_attr) {
                                if ($external_attr['tax'] === $attr_tax && isset($external_attr['value'][$var_attr_id]) && isset($external_attr['value'][$var_attr_id]['swatch_value_id'])) {
                                    $swatch_id = $external_attr['swatch_id'];
                                    $swatch_value_id = $external_attr['value'][$var_attr_id]['swatch_value_id'];

                                    if ($curr_swatch_value_id != $swatch_value_id && !isset($swatch_type_options[$swatch_id]['attributes'][$curr_swatch_value_id])) {
                                        if (isset($old_swatch_type_options[$swatch_id]['attributes'][$curr_swatch_value_id])) {
                                            $swatch_type_options[$swatch_id]['attributes'][$curr_swatch_value_id] = $old_swatch_type_options[$swatch_id]['attributes'][$curr_swatch_value_id];
                                            unset($swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]);
                                        } else if (isset($old_swatch_type_options[$swatch_id]['attributes'][$swatch_value_id])) {
                                            $swatch_type_options[$swatch_id]['attributes'][$curr_swatch_value_id] = $old_swatch_type_options[$swatch_id]['attributes'][$swatch_value_id];
                                            unset($swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]);
                                        } else if (isset($swatch_type_options[$swatch_id]['attributes'][$swatch_value_id])) {
                                            $swatch_type_options[$swatch_id]['attributes'][$curr_swatch_value_id] = $swatch_type_options[$swatch_id]['attributes'][$swatch_value_id];
                                            unset($swatch_type_options[$swatch_id]['attributes'][$swatch_value_id]);
                                        }
                                    }
                                }
                            }
                        }

                        // connect current attr and attr value to original
                        $original_attr = false;
                        $tmp_ids = explode('-', $variation['id']);
                        foreach ($variations['attributes'] as $orig_attr) {
                            if ($orig_attr['id'] == $v['original_attribute_id']) {
                                foreach ($orig_attr['value'] as $oav) {
                                    if (in_array($oav['id'], $tmp_ids)) {
                                        $original_attr = array(
                                            'id' => $oav['id'],
                                            'name' => $oav['name'],
                                            'attr_name' => $orig_attr['name'],
                                            'attr_original_name' => isset($orig_attr['original_name']) ? $orig_attr['original_name'] : $orig_attr['name'],
                                        );
                                        break;
                                    }
                                }
                            }
                        }

                        // build original variations attributes
                        $tmp_name = (strpos($tmp_attr_name, 'attribute_pa_') === 0) ? substr($tmp_attr_name, 13) : substr($tmp_attr_name, 10);
                        if (!isset($original_variations_attributes[$tmp_name])) {
                            $original_variations_attributes[$tmp_name] = array(
                                'original_attribute_id' => $v['original_attribute_id'],
                                'current_name' => isset($original_attr['attr_name']) ? $original_attr['attr_name'] : urldecode($tmp_name),
                                'name' => isset($original_attr['attr_original_name']) ? $original_attr['attr_original_name'] : urldecode($tmp_name),
                                'values' => array(),
                            );
                        } else {
                            $original_variations_attributes[$tmp_name]['original_attribute_id'] = $v['original_attribute_id'];
                            if (isset($original_attr['attr_original_name'])) {
                                $original_variations_attributes[$tmp_name]['name'] = $original_attr['attr_original_name'];
                            }
                        }

                        if (!isset($original_variations_attributes[$tmp_name]['values'])) {
                            $original_variations_attributes[$tmp_name]['values'] = array();
                        }

                        if ($original_attr) {
                            $original_variations_attributes[$tmp_name]['values'][$original_attr['id']] = array(
                                'id' => $original_attr['id'],
                                'name' => $attr_value,
                                'oroginal_id' => $original_attr['id'],
                                'oroginal_name' => $original_attr['name'],
                            );
                        }
                    } else {
                        // if attr not find in variation (for example user change Lang), then add new meta to connect attr to variation
                        foreach ($variation['attributes'] as $va) {
                            $attr_tax = "";
                            $attr_value = "";
                            foreach ($variations['attributes'] as $attr_key => $attr) {
                                if ($attr['id'] == $v['original_attribute_id']) {
                                    $tmp_name = sanitize_title($attr['name']);

                                    foreach ($attr['value'] as $val) {
                                        if ($val['id'] == $va) {
                                            $attr_tax = $attr['tax'];
                                            $attr_value = $attr['attribute_taxonomies'] ? $this->helper->cleanTaxonomyName(htmlspecialchars($val['name'], ENT_NOQUOTES), false, false) : $val['name'];
                                            // build original variations attributes
                                            if (!isset($original_variations_attributes[$tmp_name])) {
                                                $original_variations_attributes[$tmp_name] = array('original_attribute_id' => $attr['id'], 'current_name' => $attr['name'], 'name' => !empty($attr['original_name']) ? $attr['original_name'] : $attr['name'], 'values' => array());
                                            }

                                            $original_variations_attributes[$tmp_name]['values'][$val['id']] = array(
                                                'id' => $val['id'],
                                                'name' => $val['name'],
                                                'oroginal_id' => isset($val['src_id']) ? $variations['attributes'][$attr_key]['value'][$val['src_id']]['id'] : $val['id'],
                                                'oroginal_name' => isset($val['src_id']) ? $variations['attributes'][$attr_key]['value'][$val['src_id']]['name'] : $val['name'],
                                            );
                                            break;
                                        }
                                    }
                                    if ($attr_tax && $attr_value) {
                                        break;
                                    }
                                }
                            }

                            if ($attr_tax && $attr_value) {
                                if ($need_process) {
                                    update_post_meta($variation_id, sanitize_title('attribute_' . $attr_tax), $attr_value);
                                }

                                // collect used variation attribute values
                                if (isset($used_variation_attributes[$attr_tax])) {
                                    $used_variation_attributes[$attr_tax]['values'][] = $attr_value;
                                }
                            }
                        }
                    }
                }

                // A2WL_FIX_RELOAD_IMAGES(or A2WL_FIX_RELOAD_ATTR_IMAGES) - special flag (for update only), if variation images is disapear, reload it.
                if ($need_process && $is_update &&
                    (a2wl_check_defined('A2WL_FIX_RELOAD_IMAGES') || a2wl_check_defined('A2WL_FIX_RELOAD_ATTR_IMAGES')) &&
                    isset($variation['image']) && $variation['image']
                ) {
                    $old_thumb_id = get_post_thumbnail_id($variation_id);
                    if ($old_thumb_id) {
                        Utils::delete_attachment($old_thumb_id, true);
                        delete_post_meta($variation_id, '_thumbnail_id');
                    }
                    $thumb_id = $this->attachment_model->create_attachment($product_id, $variation['image'], array('inner_post_id' => $variation_id, 'title' => null, 'alt' => null, 'edit_images' => !empty($product['tmp_edit_images']) ? $product['tmp_edit_images'] : array()));
                    set_post_thumbnail($variation_id, $thumb_id);
                }
            }

            if (isset($variation_id)) {
                foreach ($old_variations as $k => $v) {
                    if (intval($v['id']) == intval($variation_id)) {
                        unset($old_variations[$k]);
                    }
                }

                if ($need_process) {
                    if ($variation && !empty($variation['extra_data'])){
                        update_post_meta($variation_id, '_a2w_extra_data', $variation['extra_data']);
                    }

                    if (!empty($variation['country_code'])) {
                        update_post_meta($variation_id, '_a2w_country_code', $variation['country_code']);
                    } else if (isset($product['local_seller_tag']) && strlen($product['local_seller_tag']) == 2) {
                        update_post_meta($variation_id, '_a2w_country_code', $product['local_seller_tag']);
                    }

                    if (isset($variation['skuId'])) {
                        update_post_meta($variation_id, '_a2w_ali_sku_id', $variation['skuId']);
                    }

                    if (isset($variation['skuIdStr'])) {
                        update_post_meta($variation_id, '_a2w_ali_sku_id_str', $variation['skuIdStr']);
                    }

                    if ($var_product = wc_get_product($variation_id)) {
                        $quantity = intval($variation['quantity']);
                        $backorders = $var_product->get_backorders();
                        $backorders = $backorders ? $backorders : 'no';

                        $var_product->set_status(!$quantity && $on_not_available_variation === 'zero_and_disable' ? 'private' : 'publish');

                        $var_product->set_backorders($backorders);
                        if ($woocommerce_manage_stock === 'yes' && (!$old_vid || !$product['disable_var_quantity_change'])) {
                            $var_product->set_stock_quantity($quantity);
                        }
                        $var_product->set_manage_stock($woocommerce_manage_stock);
                        $var_product->set_stock_status($quantity ? 'instock' : 'outofstock');

                        if (!$old_vid || !$product['disable_var_price_change']) {
                            $this->update_price($var_product, $variation);
                        }

                        $var_product->save();
                    }
                }
            }
        }

        //save product changes for future email alerts
        $this->product_change_model->save($product_id, $is_price_changed, $is_stock_changed, $has_new_variants);

        if ($step === false || $step === 'variations#attributes') {
            // update priduct swatches
            update_post_meta($product_id, '_swatch_type_options', $swatch_type_options);
            update_post_meta($product_id, '_swatch_type', 'pickers');
            update_post_meta($product_id, '_swatch_size', 'swatches_image_size');
        }

        if ($step === false || $step === 'variations#sync') {
            $original_variations_attributes = $this->fix_format_of_original_variations_attributes(
                $original_variations_attributes
            );

            update_post_meta($product_id, '_a2w_original_variations_attributes', $original_variations_attributes);

            // if this is new import, and product has skip_attr, then update woocomerce product skip meta
            if (!$is_update && !empty($product['skip_attr'])) {
                $tmp_original_variations_attributes_tmp = $original_variations_attributes;
                $skip_attr = array_map('sanitize_title', is_array($product['skip_attr']) ? $product['skip_attr'] : array($product['skip_attr']));
                foreach ($tmp_original_variations_attributes_tmp as $key => $values) {
                    if (!in_array($key, $skip_attr)) {
                        unset($tmp_original_variations_attributes_tmp[$key]);
                    }
                }
                update_post_meta($product_id, '_a2w_deleted_variations_attributes', $tmp_original_variations_attributes_tmp);
            }

            // delete old variations
            foreach ($old_variations as $old_variation) {
                if ($on_not_available_variation === 'trash' || $old_variation['external_variation_id'] == 'delete') {
                    $GLOBALS['a2wl_autodelete_variaton_lock'] = true;
                    wp_delete_post($old_variation['id']);
                    unset($GLOBALS['a2wl_autodelete_variaton_lock']);
                } else if ($on_not_available_variation === 'zero' || $on_not_available_variation === 'zero_and_disable') {
                    $var_product = wc_get_product($old_variation['id']);

                    $backorders = $var_product->get_backorders();
                    $backorders = $backorders ? $backorders : 'no';

                    $var_product->set_status($on_not_available_variation === 'zero_and_disable' ? 'private' : $var_product->get_status());
                    $var_product->set_backorders($backorders);
                    $var_product->set_manage_stock($woocommerce_manage_stock === 'yes' ? 'yes' : 'no');
                    $var_product->set_stock_status('outofstock');
                    $var_product->set_stock_quantity(0);
                    $var_product->save();
                }
            }

            // for simple variations attributes, update atributes values (save only used values)
            $need_update = false;
            foreach ($used_variation_attributes as $attr_tax => $uva) {
                if (!$uva['attribute_taxonomies'] && isset($attributes[$attr_tax])) {
                    $new_attr_values = array_unique($uva['values']);
                    asort($new_attr_values);
                    $attributes[$attr_tax]['value'] = implode("|", $new_attr_values);
                    if ($new_attr_values) {
                        $need_update = true;
                    }
                }
            }
            if ($need_update) {
                update_post_meta($product_id, '_product_attributes', $attributes);
            }

            \WC_Product_Variable::sync($product_id);
        }

        return $result;
    }

    private function fix_format_of_original_variations_attributes(array $original_variations_attributes): array {
        foreach ($original_variations_attributes as $key => $attribute) {
            if (isset($attribute['values'])) {
                foreach ($attribute['values'] as $key2 => $value) {
                    $original_variations_attributes[$key]['values'][$key2]['name'] = strtoupper($value['name']);
                }
            }
        }

        return $original_variations_attributes;
    }

    public function get_fulfilled_orders_data($only_not_delivered = true): array
    {
        global $wpdb;

        a2wl_init_error_handler();
        $filtered_items = [];

        try {
            $query =
                "SELECT pm1.meta_value as ext_order_id, pm3.order_item_id as order_item_id, pm3.order_id as order_id, " .
                    "pm2.meta_value as tracking_data FROM {$wpdb->prefix}woocommerce_order_itemmeta as pm1 " .
                "LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as pm2 " .
                    "ON (pm2.meta_key = '%s' and pm1.order_item_id=pm2.order_item_id) " .
                "LEFT JOIN {$wpdb->prefix}woocommerce_order_items as pm3 ON (pm1.order_item_id=pm3.order_item_id) " .
                "WHERE pm1.meta_key = '%s' AND pm1.meta_value <> ''";


             $result = $wpdb->get_results(sprintf(
                 $query,
                 Constants::order_item_tracking_data_meta(),
                 Constants::order_item_external_order_meta()
             ));

            //and then exclude orders that have been already delivered

            foreach ($result as $item) {
                $item->tracking_data = unserialize($item->tracking_data);
                $tracking_status = $item->tracking_data['tracking_status'] ?? false;
                if ($only_not_delivered && $tracking_status) {
                    if(WooCommerceOrderItem::check_is_delivered($tracking_status)){
                        continue;
                    }
                }

                $tmp_ext_order_id = unserialize($item->ext_order_id);
                if ($tmp_ext_order_id && is_array($tmp_ext_order_id)) {
                    $item->ext_order_id = $tmp_ext_order_id[0];
                }

                $filtered_items[] = $item;
            }
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
        }

        return $filtered_items;
    }

    public function get_fulfilled_orders_count(): int
    {
        global $wpdb;

        $query =
            "SELECT COUNT(*) as count FROM {$wpdb->prefix}woocommerce_order_itemmeta as pm1 " .
            "LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as pm2 " .
                "ON (pm2.meta_key = '%s' and pm1.order_item_id=pm2.order_item_id) " .
            "LEFT JOIN {$wpdb->prefix}woocommerce_order_items as pm3 ON (pm1.order_item_id=pm3.order_item_id) " .
            "WHERE pm1.meta_key = '%s' AND pm1.meta_value <> ''";

        $result = $wpdb->get_var(sprintf(
            $query,
            Constants::order_item_tracking_data_meta(),
            Constants::order_item_external_order_meta()
        ));

        return intval($result);
    }

    public function save_tracking_code($order_id, $ext_id, $tracking_codes, $carrier_name, $carrier_url, $tracking_status)
    {
        a2wl_init_error_handler();

        try {
            $result = true;

            $order = wc_get_order($order_id);

            if ($order !== false) {

                $order_items = $order->get_items();

                $count_ext_order_ids = 0;
                $count_tracking_codes = 0;
                $count_delivered = 0;
                $count_shipped = 0;

                foreach ($order_items as $item) {

                    $a2wl_order_item = new WooCommerceOrderItem($item);

                    $external_order_id = $a2wl_order_item->get_external_order_id();

                    if ($external_order_id) {

                        $count_ext_order_ids = $count_ext_order_ids + 1;

                        $check_tracking_codes = $a2wl_order_item->get_tracking_codes();

                        if (floatval($external_order_id) === floatval($ext_id)) {

                            $check_tracking_codes = array_unique(array_merge($check_tracking_codes, $tracking_codes));

                            $a2wl_order_item->update_tracking_data($check_tracking_codes, $carrier_name, $carrier_url, $tracking_status);

                            $a2wl_order_item->save();

                        }

                        if (!empty($check_tracking_codes)) {
                            $count_tracking_codes = $count_tracking_codes + 1;
                        }

                    }

                    if ($a2wl_order_item->is_delivered()) {
                        $count_delivered = $count_delivered + 1;
                    } else if ($a2wl_order_item->is_shipped()) {
                        $count_shipped = $count_shipped + 1;
                    }

                }

                $this->switchOrderStatus(
                    $order,
                    $count_ext_order_ids,
                    $count_tracking_codes,
                    $count_delivered,
                    $count_shipped
                );

            }

        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = false;
        }

        return $result;
    }

    public function delete_external_order_id($order_id, $ext_id)
    {
        a2wl_init_error_handler();
        try {
            $result = true;
            $order = wc_get_order($order_id);
            if ($order !== false) {
                $order_items = $order->get_items();
                foreach ($order_items as $item_id => $item) {
                    $a2wl_order_item = new WooCommerceOrderItem($item);
                    $external_order_id = $a2wl_order_item->get_external_order_id();
                    if ($external_order_id && floatval($external_order_id) === floatval($ext_id)) {
                        $a2wl_order_item->update_external_order("", true);
                    }
                }
            }
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = false;
        }

        return $result;
    }

    public function sync_order_with_aliexpress($order_id) {
        a2wl_init_error_handler();
        try {
            $result = ResultBuilder::buildOk();
            $order = wc_get_order($order_id);
            if(!$order) {
                $result = ResultBuilder::buildError('Order not found');
            } else {
                $external_order_ids = array();
                $order_items = $order->get_items();
                foreach ($order_items as $item) {
                    $a2wl_order_item = new WooCommerceOrderItem($item);
                    $external_order_id = $a2wl_order_item->get_external_order_id();
                    if(!empty($external_order_id)) {
                        $external_order_ids[] = $external_order_id;
                    }
                }

                $Aliexpress = new Aliexpress();
                foreach ($external_order_ids as $external_order_id) {
                    $apiResult = $Aliexpress->load_order($external_order_id);
                    $isNotAvailableOrder = $apiResult['state'] === 'error' &&
                        isset($apiResult['error_code']) && $apiResult['error_code'] === 404;
                    if ($isNotAvailableOrder) {
                        // remove external order id (decided to not make this, because it can erase data if token aliexpress account changed)
                        // $this->delete_external_order_id($order_id, $external_order_id);
                    } else if($apiResult['state'] === 'ok') {
                        $this->save_tracking_code(
                            $order_id,
                            $external_order_id,
                            $apiResult['order']['tracking_codes'],
                            $apiResult['order']['courier_name'],
                            '',
                            $apiResult['order']['tracking_status']
                        );
                    }
                }
            }
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        return $result;
    }

    public function get_sorted_products_ids($sort_type, $ids_count, $compare = false, $untrash = false)
    {
        global $wpdb;

        $autoremove_meta_key = $untrash ? '_a2w_autoremove' : '_a2w_skip_this_meta_check';

        if ($compare) {
            $operation = "=";
            $value = "";
            if (is_array($compare)) {
                if (isset($compare['value']) && isset($compare['compare']) && in_array($compare['compare'], array('<', '>', '=', 'like', '!='))) {
                    $value = $compare['value'];
                    $operation = $compare['compare'];
                }
            } else {
                $value = $compare;
            }

            if (!empty($operation) && !empty($value)) {
                $query = $wpdb->prepare("SELECT p.ID FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm1 ON (pm1.post_id=p.ID AND pm1.meta_key='_a2w_external_id') LEFT JOIN $wpdb->postmeta pm2 ON (pm2.post_id=p.ID AND pm2.meta_key=%s) LEFT JOIN $wpdb->postmeta pm3 ON (pm3.post_id=p.ID AND pm3.meta_key=%s) WHERE p.post_type = 'product' AND pm1.meta_value != '' AND (p.post_status != 'trash' || NOT pm3.meta_key IS NULL) AND (pm2.meta_value is null OR pm2.meta_value $operation %s) ORDER BY pm2.meta_value LIMIT %d", $sort_type, $autoremove_meta_key, $value, $ids_count);
            }
        } else {
            $query = $wpdb->prepare("SELECT p.ID FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm1 ON (pm1.post_id=p.ID AND pm1.meta_key='_a2w_external_id') LEFT JOIN $wpdb->postmeta pm2 ON (pm2.post_id=p.ID AND pm2.meta_key=%s) LEFT JOIN $wpdb->postmeta pm3 ON (pm3.post_id=p.ID AND pm3.meta_key=%s) WHERE p.post_type = 'product' AND pm1.meta_value != '' AND (p.post_status != 'trash' || NOT pm3.meta_key IS NULL) ORDER BY pm2.meta_value LIMIT %d", $sort_type, $autoremove_meta_key, $ids_count);
        }

        return $wpdb->get_col($query);
    }

    public function get_products_ids($page, $products_per_page)
    {
        $ids0 = get_posts(array(
            'post_type' => 'product',
            'fields' => 'ids',
            'offset' => $page * $products_per_page,
            'posts_per_page' => $products_per_page,
            'meta_query' => array(
                array(
                    'key' => '_a2w_external_id',
                    'compare' => 'EXISTS',
                ),
            ),
        ));
        foreach ($ids0 as $id) {
            $result[] = $id;
        }
        return $result;
    }

    public function get_products_count()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT count(DISTINCT post_id) from $wpdb->postmeta WHERE meta_key = '_a2w_external_id' and meta_value != '' and NOT meta_value IS NULL");
    }

    public function get_product_external_id($post_id)
    {
        $external_id = '';
        $post = get_post($post_id);
        if ($post) {
            if ($post->post_type === 'product') {
                $external_id = get_post_meta($post_id, "_a2w_external_id", true);
            } else if ($post->post_type === 'product_variation') {
                $external_id = get_post_meta($post->post_parent, "_a2w_external_id", true);
            }
        }
        return $external_id;
    }

    public function get_product_by_post_id($post_id, $with_vars = true)
    {
        global $wpdb;
        $product = array();

        $external_id = get_post_meta($post_id, "_a2w_external_id", true);
        if ($external_id) {
            $woocommerce_manage_stock = get_option('woocommerce_manage_stock', 'no');

            $product = array(
                'id' => $external_id,
                'post_id' => $post_id,
                'url' => get_post_meta($post_id, "_a2w_original_product_url", true),
                'affiliate_url' => get_post_meta($post_id, "_a2w_product_url", true),
                'seller_url' => get_post_meta($post_id, "_a2w_seller_url", true),
            );

            $cats = wp_get_object_terms($post_id, 'product_cat');
            if (!is_wp_error($cats) && $cats) {
                $product['category_id'] = $cats[0]->term_id;
            }

            $import_lang = get_post_meta($post_id, "_a2w_import_lang", true);
            $product['import_lang'] = $import_lang ? $import_lang : AliexpressLocalizator::getInstance()->language;

            $price = get_post_meta($post_id, "_aliexpress_price", true);
            $regular_price = get_post_meta($post_id, "_aliexpress_regular_price", true);

            $price = $price ? $price : 0;
            $regular_price = $regular_price ? $regular_price : 0;

            $product['price'] = $price ? $price : $regular_price;
            $product['regular_price'] = $regular_price ? $regular_price : $price;
            $product['discount'] = $product['regular_price'] ? 100 - round($product['price'] * 100 / $product['regular_price'], 2) : 0;

            $price = get_post_meta($post_id, "_price", true);
            $regular_price = get_post_meta($post_id, "_regular_price", true);

            $price = $price ? $price : 0;
            $regular_price = $regular_price ? $regular_price : 0;

            $product['calc_price'] = $price ? $price : $regular_price;
            $product['calc_regular_price'] = $regular_price ? $regular_price : $price;

            if ($woocommerce_manage_stock === 'yes') {
                $product['quantity'] = get_post_meta($post_id, "_stock", true);
            } else {
                $product['quantity'] = get_post_meta($post_id, '_stock_status', true) === 'outofstock' ? 0 : 1;
            }

            $original_product_url = get_post_meta($post_id, "_a2w_original_product_url", true);
            $product['original_product_url'] = $original_product_url ? $original_product_url : 'www.aliexpress.com/item//' . $product['id'] . '.html';

            $availability_meta = get_post_meta($post_id, "_a2w_availability", true);
            $product['availability'] = $availability_meta ? filter_var($availability_meta, FILTER_VALIDATE_BOOLEAN) : true;

            $a2wl_skip_meta = get_post_meta($post_id, "_a2w_skip_meta", true);

            $product['skip_vars'] = $a2wl_skip_meta && !empty($a2wl_skip_meta['skip_vars']) ? $a2wl_skip_meta['skip_vars'] : array();
            $product['skip_images'] = $a2wl_skip_meta && !empty($a2wl_skip_meta['skip_images']) ? $a2wl_skip_meta['skip_images'] : array();

            $shipping_meta = new ProductShippingMeta($post_id);
            $product['shipping_default_method'] = $shipping_meta->get_method();
            $product['shipping_to_country'] = $shipping_meta->get_country_to();
            $product['shipping_cost'] = $shipping_meta->get_cost();

            $product['disable_sync'] = get_post_meta($post_id, "_a2w_disable_sync", true);
            $product['disable_var_price_change'] = get_post_meta($post_id, "_a2w_disable_var_price_change", true);
            $product['disable_var_quantity_change'] = get_post_meta($post_id, "_a2w_disable_var_quantity_change", true);
            $product['disable_add_new_variants'] = get_post_meta($post_id, "_a2w_disable_add_new_variants", true);

            $product['sku_products']['attributes'] = array();
            $product['sku_products']['variations'] = array();
            if ($with_vars) {
                $variations = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_parent = %d and post_type='product_variation'", $post_id));
                if ($variations) {
                    foreach ($variations as $variation_id) {
                        $var = array('id' => get_post_meta($variation_id, "external_variation_id", true), 'attributes' => array());

                        $price = get_post_meta($variation_id, "_aliexpress_price", true);
                        $regular_price = get_post_meta($variation_id, "_aliexpress_regular_price", true);

                        $price = $price ? $price : 0;
                        $regular_price = $regular_price ? $regular_price : 0;

                        $var['price'] = $price ? $price : $regular_price;
                        $var['regular_price'] = $regular_price ? $regular_price : $price;
                        $var['discount'] = $var['regular_price'] ? 100 - round($var['price'] * 100 / $var['regular_price']) : 0;

                        $price = get_post_meta($variation_id, "_price", true);
                        $regular_price = get_post_meta($variation_id, "_regular_price", true);

                        $price = $price ? $price : 0;
                        $regular_price = $regular_price ? $regular_price : 0;

                        $var['calc_price'] = $price ? $price : $regular_price;
                        $var['calc_regular_price'] = $regular_price ? $regular_price : $price;

                        if ($woocommerce_manage_stock === 'yes') {
                            $var['quantity'] = get_post_meta($variation_id, "_stock", true);
                        } else {
                            $var['quantity'] = get_post_meta($variation_id, '_stock_status', true) === 'outofstock' ? 0 : 1;
                        }

                        $product['sku_products']['variations'][] = $var;
                    }
                } else {
                    $var = array('id' => $external_id . "-1", 'attributes' => array());
                    if (isset($product['price'])) {
                        $var['price'] = $product['price'];
                    }
                    if (isset($product['regular_price'])) {
                        $var['regular_price'] = $product['regular_price'];
                    }
                    if (isset($product['discount'])) {
                        $var['discount'] = $product['discount'];
                    }
                    if (isset($product['calc_price'])) {
                        $var['calc_price'] = $product['calc_price'];
                    }
                    if (isset($product['calc_regular_price'])) {
                        $var['calc_regular_price'] = $product['calc_regular_price'];
                    }
                    if (isset($product['quantity'])) {
                        $var['quantity'] = $product['quantity'];
                    }

                    $product['sku_products']['variations'][] = $var;
                }
            }
        }

        return $product;
    }

    public function get_product_id_by_external_id($external_id)
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_a2w_external_id' AND meta_value='%s' LIMIT 1", $external_id));
    }

    public function get_product_id_by_import_id($import_id)
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_a2w_import_id' AND meta_value='%s' LIMIT 1", $import_id));
    }

    public function get_product_tags($search = '')
    {
        $tags = get_terms('product_tag', array('search' => $search, 'hide_empty' => false));
        if (is_wp_error($tags)) {
            return array();
        } else {
            $result_tags = array();
            foreach ($tags as $tag) {
                $result_tags[] = $tag->name;
            }
            return $result_tags;
        }
    }

    public function get_categories()
    {
        $categories = get_terms("product_cat", array('hide_empty' => 0, 'hierarchical' => true));
        if (is_wp_error($categories)) {
            return array();
        } else {
            $categories = json_decode(json_encode($categories), true);
            $categories = $this->build_categories_tree($categories, 0);
            return $categories;
        }
    }

    private function build_categories_tree($all_cats, $parent_cat, $level = 1)
    {
        $res = array();
        foreach ($all_cats as $c) {
            if ($c['parent'] == $parent_cat) {
                $c['level'] = $level;
                $res[] = $c;
                $child_cats = $this->build_categories_tree($all_cats, $c['term_id'], $level + 1);
                if ($child_cats) {
                    $res = array_merge($res, $child_cats);
                }
            }
        }
        return $res;
    }

    private function switchOrderStatus($order, $aliexpressOrdersCount, $trackingCodesCount, $deliveredOrdersCount, $shippedOrdersCount): void
    {
        if ($aliexpressOrdersCount > 0) {
            if ($aliexpressOrdersCount === $trackingCodesCount) {
                if ($deliveredOrdersCount === $aliexpressOrdersCount) {

                    $order_status = get_setting('delivered_order_status');

                    if ($order_status !== "") {
                        $order->update_status($order_status);
                    }

                } elseif ($shippedOrdersCount === $aliexpressOrdersCount) {

                    $order_status = get_setting('tracking_code_order_status');

                    if ($order_status !== "") {
                        $order->update_status($order_status);
                    }
                }
            }
        }
    }

    /**
     * Change product type of existing wc product
     *
     * @param int     $product_id       - The product id.
     * @param string  $new_product_type - The new product type
     */
    private function changeProductType(int $product_id, string $new_product_type): void
    {
        // Get the correct product classname from the new product type
        $product_classname = WC_Product_Factory::get_product_classname( $product_id, $new_product_type );

        // Get the new product object from the correct classname
        $new_product = new $product_classname( $product_id );

        // Save product to database and sync caches
        $new_product->save();
    }
}
