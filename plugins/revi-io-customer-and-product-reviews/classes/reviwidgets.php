<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class reviwidgets
{
    var $REVI_API_URL;
    var $prefix;
    var $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->prefix = $this->wpdb->prefix;
        $this->REVI_API_URL = REVI_API_URL;

        $this->revimodel = new revimodel();

        $this->template_vars = array(
            'REVI_RATING_TYPE' => get_option('REVI_RATING_TYPE'),
        );

    }

    public function loadReviWidget($type, $data_view = array(), $id_product = null)
    {
        // Without apik key, do not load widget
        if (empty(get_option('REVI_API_KEY'))) {
            return;
        }

        if (!empty($data_view)) {
            $this->template_vars = array_merge($this->template_vars, $data_view);
        }

        if (!empty($id_product)) {
            if (REVI_LANGUAGE_PLUGIN == 'wpml') {
                $id_product = $this->revimodel->get_id_main_product($id_product);
            }

            $product_vars = array(
                'id_product' => $id_product,
                'product_info' => $this->revimodel->getReviProduct($id_product),
            );

            if ($type == 'product_list') {
                $product_vars['REVI_DISPLAY_PRODUCT_LIST_EMPTY'] = get_option('REVI_DISPLAY_PRODUCT_LIST_EMPTY');
                $product_vars['REVI_DISPLAY_PRODUCT_LIST_TEXT'] = get_option('REVI_DISPLAY_PRODUCT_LIST_TEXT');
            }

            $this->template_vars = array_merge($this->template_vars, $product_vars);
        }

        return $this->loadView('hook/' . $type . '.php', $this->template_vars);
    }

    function loadView($template_name, $variables = array())
    {
        extract($variables);

        if (!empty($template_name)) {
            $template_array = array('revi-io-customer-and-product-reviews/' . $template_name);
            if (!empty(locate_template($template_array))) {
                $template_file = locate_template($template_array);
            } else {
                $template_file = REVI_DIR . 'templates/' . $template_name;
            }

            ob_start();
            require($template_file);
            return ob_get_clean();
        }
    }
}
