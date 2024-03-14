<?php

namespace ShopWP\Render;

use ShopWP\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Templates
{
    public $Template_Loader;

    public function __construct($Template_Loader)
    {
        $this->Template_Loader = $Template_Loader;
    }

    public function sanitize_user_data(
        $user_data = [],
        $type = false,
        $component_class = false
    ) {

      $combined_data = $component_class->$type($user_data);

      $vals_formatted = Utils_Data::standardize_layout_data($combined_data);

      if ($type !== 'cart' && $type !== 'cart_icon' && $type !== 'translator' && $type !== 'reviews') {
        
        if (empty($combined_data['query'])) {
            $query = $component_class->create_product_query($vals_formatted);
        } else {
            $query = $combined_data['query'];
        }

        $vals_formatted['query'] = $query;

      }

      return $vals_formatted;
      
    }

    public function params_client_render($params)
    {
        return [
            'data' => $params,
            'path' => 'components/wrapper/wrapper',
            'name' => 'client',
        ];
    }

    public function set_and_get_template($params)
    {
        if (empty($params['full_path'])) {
            return $this->Template_Loader->set_template_data($params['data'])->get_template_part($params['path'], $params['name']);
            
        } else {
            return $this->Template_Loader->set_template_data($params['data'])->get_template_part($params['full_path']);
        }
    }

    public function load($params)
    {
        return $this->set_and_get_template(
            $this->params_client_render($params)
        );
    }

}
