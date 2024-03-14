<?php

namespace HQRentalsPlugin\HQRentalsActions;

class HQRentalsAjaxHandler
{
    public function __construct()
    {
        add_action('wp_ajax_hqLogin', array($this, 'login'));
        //add_action('wp_ajax_nopriv_hqLogin', array($this, 'login') );
    }

    public function login()
    {
        return [
            'test' => 1,
            'fasd' => 1,
        ];
    }
}
