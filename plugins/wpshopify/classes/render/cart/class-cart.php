<?php

namespace ShopWP\Render;

if (!defined('ABSPATH')) {
    exit();
}

class Cart
{
    public $Templates;
    public $Defaults_Cart;

    public function __construct($Templates, $Defaults_Cart)
    {
        $this->Templates = $Templates;
        $this->Defaults_Cart = $Defaults_Cart;
    }

    public function cart_icon($data = [])
    {
        return $this->Templates->load([
            'skeleton' => false,
            'type' => 'cartIcon',
            'defaults' => 'cart',
            'data' => $this->Templates->sanitize_user_data(
                $data,
                'cart_icon',
                $this->Defaults_Cart
            )
        ]);
    }

    public function cart($data = [])
    {
        return $this->Templates->load([
            'skeleton' => false,
            'type' => 'cart',
            'defaults' => 'cart',
            'data' => $this->Templates->sanitize_user_data(
                $data,
                'cart',
                $this->Defaults_Cart
            )
        ]);
    }
}
