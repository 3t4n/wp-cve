<?php

namespace Shop_Ready\system\base\dashboard\controls;

use Shop_Ready\system\base\dashboard\Form;

class Widgets extends Form
{

    public $action_key = 'shop_ready_components_options';
    public $option_key = 'shop_ready_components';
    public $nonce = '_shop_ready_components';

    public function register()
    {

        add_action('admin_post_' . $this->action_key, [$this, '_ready_components_options']);
    }

    public function senitize_validate_options($options = [], $all = false)
    {

        if (!is_array($options)) {
            return $options;
        }

        $return_options = [];

        foreach ($options as $key => $value) {

            if ($all) {

                if (isset($value['is_pro']) && $value['is_pro'] == 1) {
                    $return_options[$key] = 'on';
                } else {
                    $return_options[$key] = '';
                }
            } else {

                $return_options[$key] = sanitize_text_field($value);
            }
        }

        return $return_options;
    }

    function _ready_components_options()
    {

        // Verify if the nonce is valid
        if (!isset($_POST[$this->nonce]) || !wp_verify_nonce($_POST[$this->nonce], $this->action_key)) {
            wp_redirect(sanitize_url($_SERVER['HTTP_REFERER']));
        }

        if (!array_key_exists($this->option_key, $_POST)) {

            wp_redirect(sanitize_url($_SERVER['HTTP_REFERER']));
            return;
        }

        // sanitize_text_field see above
        $validate_options = $this->senitize_validate_options($_POST[$this->option_key]);
        update_option($this->option_key, wc_clean($validate_options));

        if (wp_doing_ajax()) {
            wp_die();
        } else {

            $url = sanitize_url($_SERVER['HTTP_REFERER']);
            $return_url = add_query_arg(
                array(
                    'nav' => $this->option_key,
                ),
                $url
            );

            wp_redirect($return_url);
        }
    }
}