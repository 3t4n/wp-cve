<?php
if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

return [
    'title'              => esc_html__('EDD Beauty Grid', 'ultimate-store-kit'),
    'required'           => true,
    'default_activation' => true,
    'has_style'          => true,
    'has_script'         => true,
];
