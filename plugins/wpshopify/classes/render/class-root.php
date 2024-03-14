<?php

namespace ShopWP\Render;

use ShopWP\Utils;
use ShopWP\Options;

if (!defined('ABSPATH')) {
    exit();
}

class Root
{
    public function __construct($Template_Loader)
    {
        $this->Template_Loader = $Template_Loader;
    }

    public function generate_component_id($encoded_data_string)
    {
        return Utils::hash_rand($encoded_data_string);
    }

    public function encode_component_data($data)
    {
        return Utils::base64($data);
    }

    public function decode_component_data($string)
    {
        return Utils::base64($data, 'decode');
    }

    public function render_root_component($data)
    {
        return $this->Template_Loader
            ->set_template_data($data)
            ->get_template_part('components/root/element');
    }
}
