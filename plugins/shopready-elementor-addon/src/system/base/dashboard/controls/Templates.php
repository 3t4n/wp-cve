<?php

namespace Shop_Ready\system\base\dashboard\controls;


class Templates
{

    public $action_key = 'shop_ready_templates_options';
    public $option_key = 'shop_ready_templates';
    public $nonce = '_shop_ready_templates';
    public $option_switch_key = 'shop_ready_templates_switch';
    public $option_presets_tpl_key = 'option_presets_tpl';
    public $option_presets_key = 'shop_ready_presets_switch';
    public $request_data = [];
    public $transform_templates = [];

    public function register()
    {

        add_action('admin_post_' . $this->action_key, [$this, '_ready_templates_options']);
        add_action('wp_ajax_shopready_template_option_delete', [$this, 'shop_ready_dash_template_option']);
    }

    public function shop_ready_dash_template_option()
    {

        if (defined('DOING_AJAX') && DOING_AJAX) {

            delete_option($this->option_key);

            echo esc_html('success');

        } else {

            echo esc_html('fail');

        }

        wp_die();
    }


    function _ready_templates_options()
    {

        // Verify if the nonce is valid
        if (!isset($_POST[$this->nonce]) || !wp_verify_nonce($_POST[$this->nonce], $this->action_key)) {
            wp_redirect(sanitize_url($_SERVER['HTTP_REFERER']));
        }

        if (!array_key_exists($this->option_key, $_POST)) {

            wp_redirect(sanitize_url($_SERVER['HTTP_REFERER']));
            return;
        }
        //sanitize here for array data

        $this->request_data = array_map('sanitize_text_field', $_REQUEST[$this->option_key]);
        $this->transform_templates_options();
        $this->transform_templates_switch_options();
        $this->transform_presets_switch_options();
        $this->transform_presets_tpl_options();


        $this->_parsist();

        if (wp_doing_ajax()) {

            wp_die();
        } else {

            $url = sanitize_url($_SERVER["HTTP_REFERER"]);
            $return_url = add_query_arg(
                array(
                    'nav' => $this->option_key,
                ),
                $url
            );

            wp_redirect(esc_url($return_url));
        }

    }

    function transform_templates_options()
    {

        $new_array = [];

        $templates = shop_ready_templates_config()->all();

        foreach ($templates as $key => $item) {

            if (isset($this->request_data[$key]) && is_numeric($this->request_data[$key])) {

                $item['id'] = $this->request_data[$key];
            } else {
                $item['id'] = '';
            }

            $new_array[$key] = $item;
        }

        $this->transform_templates['templates'] = $new_array;

        unset($new_array);
        unset($templates);
        unset($user_data);

    }



    public function transform_templates_switch_options()
    {

        $new_array = [];
        // check switch key exist
        $user_data = [];
        if (isset($_REQUEST[$this->option_switch_key])) {
            // sanitize here

            $user_data = array_map('sanitize_text_field', $_REQUEST[$this->option_switch_key]);
        }

        if (is_array($this->transform_templates)) {

            foreach ($this->transform_templates['templates'] as $key => $item) {

                if (isset($user_data[$key])) {
                    $item['active'] = 1;
                } else {
                    $item['active'] = 0;
                }

                $new_array[$key] = $item;
            }
        }

        $this->transform_templates['templates'] = $new_array;

    }

    public function transform_presets_switch_options()
    {

        $new_array = [];
        // check switch key exist
        $user_data = [];
        if (isset($_REQUEST[$this->option_presets_key])) {
            // sanitize here from form submitted data
            $user_data = array_map('sanitize_text_field', sanitize_text_field($_REQUEST[$this->option_presets_key]));
        }

        if (is_array($this->transform_templates)) {

            foreach ($this->transform_templates['templates'] as $key => $item) {

                if (isset($user_data[$key])) {
                    $item['presets_active'] = 1;
                } else {
                    $item['presets_active'] = 0;
                }

                $new_array[$key] = $item;
            }
        }

        $this->transform_templates['templates'] = $new_array;

    }

    public function transform_presets_tpl_options()
    {

        $new_array = [];
        // check switch key exist
        $user_data = [];
        if (isset($_REQUEST[$this->option_presets_tpl_key])) {
            // sanitize here from form submitted data
            $user_data = array_map('sanitize_text_field', sanitize_text_field($_REQUEST[$this->option_presets_tpl_key]));
        }

        if (is_array($this->transform_templates)) {

            foreach ($this->transform_templates['templates'] as $key => $item) {

                if (isset($user_data[$key])) {

                    $item['presets_active_path'] = $user_data[$key];
                    // Update Element Shop Archive grid option

                    if ($key == 'shop') {
                        update_option('wooready_products_archive_shop_grid_style', sanitize_text_field($user_data[$key]));
                    }

                    if ($key == 'cart') {
                        update_option('shop_ready_pro_cart_page_layout', sanitize_text_field($user_data[$key]));
                    }

                } else {
                    $item['presets_active_path'] = '';
                }

                $new_array[$key] = $item;
            }
        }

        $this->transform_templates['templates'] = $new_array;

    }

    public function _parsist()
    {

        update_option($this->option_key, wc_clean($this->transform_templates['templates']));

    }

}