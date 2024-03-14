<?php

namespace Shop_Ready\system\base\dashboard\controls;

use Illuminate\Support\MessageBag as MangocubeMessageBag;

class Api
{

    public $action_key = 'shop_ready_data_api_options';
    public $option_key = 'shop_ready_data_api';
    public $nonce = '_shop_ready_data_api';
    public $option_switch_key = 'shop_ready_data_api_switch';

    public $transform_apis = [];

    public function register()
    {

        add_action('admin_post_' . $this->action_key, [$this, '_ready_data_api_options']);
    }

    function _ready_data_api_options()
    {

        // Verify if the nonce is valid
        if (!isset($_POST[$this->nonce]) || !wp_verify_nonce(sanitize_text_field($_POST[$this->nonce]), $this->action_key)) {
            wp_redirect(sanitize_url($_SERVER['HTTP_REFERER']));
        }

        if (!array_key_exists($this->option_key, $_POST)) {

            wp_redirect(sanitize_url($_SERVER['HTTP_REFERER']));
            return;
        }

        $this->transform_data_api_options();

        $this->_parsist();

        if (wp_doing_ajax()) {

            wp_die();

        } else {

            $url = esc_url(sanitize_url($_SERVER['HTTP_REFERER']));
            $return_url = add_query_arg(
                array(
                    'nav' => $this->option_key,
                ),
                esc_url($url)
            );

            wp_redirect($return_url);
        }
    }

    function transform_data_api_options()
    {

        $new_array = [];

        $templates = shop_ready_api_config()->all();
        // sanitize_text_field array fields this method

        $user_data = array_map('sanitize_text_field', sanitize_text_field($_REQUEST[$this->option_key]));
        foreach ($templates as $key => $item) {

            if (isset($user_data[$key])) {
                $item['default'] = sanitize_text_field($user_data[$key]);
            } else {
                $item['default'] = '';
            }

            $new_array[$key] = $item;
        }

        $this->transform_apis = $new_array;

        unset($new_array);
        unset($templates);
        unset($user_data);
    }


    public function _parsist()
    {

        update_option($this->option_key, wc_clean($this->transform_apis));
    }
}