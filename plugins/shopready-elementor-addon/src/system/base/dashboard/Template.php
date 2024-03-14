<?php
namespace Shop_Ready\system\base\dashboard;

use WC_Product_Simple;

class Template
{


    public function register()
    {

        // product
        add_filter('pre_get_posts', [$this, 'woocommerce_product_query'], 100);
        add_filter('ajax_query_attachments_args', [$this, 'media_meta'], 100);
        //add_action('admin_init', [ $this, 'install_demo_content' ],10 ); 
        // add_filter('post_row_actions', [$this,'post_row_actions'], 12, 2);
        add_filter('template_include', [$this, 'product_editor_compatibilty_fix'], 101);
        // Ajax
        add_action('wp_ajax_shop_ready_dash_template_content', [$this, 'shop_ready_dash_template_content']);


        add_action('wp_ajax_shop_ready_dash_template_edit_content', [$this, 'shop_ready_dash_template_edit_content']);

    }
    /**
     * Hide Demo Image from Media Panel
     */
    public function media_meta($query)
    {

        $query['post__not_in'] = get_option('shop_ready_demo_attachment_ids');

        return $query;
    }

    function shop_ready_dash_template_edit_content()
    {

        $data = false;

        if (isset($_REQUEST['sr_edit_id']) && is_numeric($_REQUEST['sr_edit_id'])) {

            $id = sanitize_text_field($_REQUEST['sr_edit_id']);
            $data =
                sprintf(
                    '<iframe src="%s"></iframe>',
                    $this->get_edit_tempate_url($id)
                )
            ;
        }

        wp_send_json([
            'html' => $data,
            'id' => $id,
            'title' => get_the_title($id)
        ], 200);

        wp_die();

    }


    function shop_ready_dash_template_content()
    {

        $id = $this->get_tpl_id();

        $data = sprintf(
            '<iframe src="%s"></iframe>',
            $this->get_edit_tempate_url($id)
        );

        $this->update_template_option($id);

        wp_send_json([
            'html' => $data,
            'id' => $id,
            'title' => get_the_title($id)
        ], 200);

        wp_die();

    }

    public function update_template_option($id = '')
    {

        $templates = shop_ready_templates_config()->all();
        $type = sanitize_text_field($_REQUEST['sr_ds_template']);

        if (isset($templates[$type]) && is_numeric($id)) {

            $templates[$type]['id'] = $id;
            update_option('shop_ready_templates', wc_clean($templates));

        }


    }

    public function get_edit_tempate_url($id)
    {

        return $this->get_link(
            [
                'post' => $id,
                'action' => 'elementor',
                'sr_tpl' => 'shop_ready_dashboard',
                'tpl_type' => isset($_REQUEST['sr_ds_template']) ? sanitize_text_field($_REQUEST['sr_ds_template']) : 'unknown'
            ]
        );
    }

    public function woocommerce_product_query($query)
    {

        $product_ids = [];
        if ($query->is_main_query()) {

            $product_ids[] = get_option('shop_ready_simple_product_id');
            if (!empty($product_ids)) {
                $query->set('post__not_in', $product_ids);
            }

        }


    }

    public function get_tpl_id()
    {

        if (isset($_REQUEST['sr_ds_template'])) {

            return $this->get_new_page_id(sanitize_text_field($_REQUEST['sr_ds_template']));

        }

        return null;
    }

    public function get_new_page_id($value)
    {

        $templates = shop_ready_templates_config()->get($value);

        $title = $value;
        $title = is_array($templates) && isset($templates['title']) ? $templates['title'] : $value;

        $data = array(

            'post_type' => 'elementor_library',
            'post_title' => sprintf("%s page template", $title)

        );

        $id = wp_insert_post($data, false);
        update_post_meta($id, 'shop_ready_' . $value . '_page_tpl', $value);
        update_post_meta($id, 'shop_ready_page_tpl', $value);
        return $id;

    }

    public function get_link($params)
    {

        $url = add_query_arg(
            is_array($params) ? $params : [],
            admin_url('post.php')
        );

        return $url;
    }

    public function post_row_actions($actions, $post)
    {

        if (!class_exists('woocommerce')) {
            return $actions;
        }

        if ($post->post_type != 'product') {
            return $actions;
        }

        global $product;
        $config = shop_ready_templates_config()->all();
        $_tpl_type = 'single';

        $shop_editor_post_id = $post->ID;

        if ($product->get_type() == 'variable') {

            $variable_tpl = shop_ready_find_template_by_name('variable_single');
            if (is_array($variable_tpl) && isset($variable_tpl['id'])) {
                $_tpl_type = 'variable_single';
                $shop_editor_post_id = $variable_tpl['id'];
            }

        }

        if ($product->get_type() == 'grouped' && shop_ready_find_template_by_name('grouped_single')) {

            $group_tpl = shop_ready_find_template_by_name('variable_single');

            if (is_array($group_tpl) && isset($group_tpl['id'])) {
                $_tpl_type = 'grouped_single';
                $shop_editor_post_id = $group_tpl['id'];
            }

        }

        $actions['edit_with_shop_ready'] = sprintf(
            '<a href="%s"> %s </a>',
            $this->get_link(
                [
                    'post' => $shop_editor_post_id,
                    'action' => 'elementor',
                    'sr_tpl' => 'shop_ready_dashboard',
                    'tpl_type' => $_tpl_type,
                    'sr_single_product_id' => $post->ID,

                ]
            ),
            esc_html__('Edit With ShopReady', 'shopready-elementor-addon')
        );

        return $actions;
    }

    public function product_editor_compatibilty_fix($template)
    {

        if ($this->is_single_product_tpl()) {

            wp_enqueue_script('wc-single-product');
            $template = shop_ready_app_config()->get('views')['single_editor'] . '/editor.php';

        }

        return $template;

    }

    public function is_single_product_tpl()
    {

        return isset($_GET['sr_tpl']) && isset($_GET['tpl_type']) && $_GET['sr_tpl'] == 'shop_ready_dashboard' && in_array($_GET['tpl_type'], ['single']);
    }

    function product_exists_by_slug($slug = 'shop-ready-simple-demo-product-tpl-1-5', $value = 'simple')
    {

        $meta = array(

            array(
                'key' => 'shop_ready_template',
                'value' => $value,
                'compare' => '==',
            )

        );

        $array_content = shop_ready_post_exists_by_slug($slug, 'product', $meta);

        if (!is_array($array_content)) {
            return false;
        }

        if (isset($array_content[0])) {

            return $array_content[0]->ID;
        }

        return false;

    }

    public function install_demo_content()
    {


        if (isset($_GET['page']) && $_GET['page'] == SHOP_READY_SETTING_PATH) {

            if (class_exists('WooCommerce')) {

                $demo_imgs = get_option('shop_ready_demo_attachment_ids');
                $feature_img = isset($demo_imgs[0]) ? $demo_imgs[0] : '';
                // simple product demo
                if (!$this->product_exists_by_slug('shop-ready-simple-demo-product-tpl-1-5', 'simple')) {

                    $product = new WC_Product_Simple();
                    $product->set_name('SR Simple product template LayOut');
                    $product->set_slug('shop-ready-simple-demo-product-tpl-1-5');
                    $product->set_sku('simple-demo-product-8596874c');
                    $product->add_meta_data('shop_ready_template', 'simple');
                    $product->set_status('publish');
                    $product->set_catalog_visibility('visible');
                    $product->set_price(1019.99);
                    $product->set_regular_price(1019.99);
                    $product->set_sale_price(819.99);
                    $product->set_manage_stock(true);

                    $product->set_weight(50);
                    $product->set_width(800);
                    $product->set_length(800);
                    $product->set_featured(true);
                    $product->set_height(800);
                    $product->set_sold_individually(false);
                    $product->set_purchase_note("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.");
                    $product->set_image_id($feature_img);
                    $product->set_gallery_image_ids($demo_imgs);

                    $product->save();
                    wc_update_product_stock($product, 2000);
                    update_option('shop_ready_simple_product_id', $product->get_id());
                }


            }
        }
    }

}