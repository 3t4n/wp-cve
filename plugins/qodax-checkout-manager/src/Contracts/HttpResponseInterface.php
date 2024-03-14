<?php

namespace Qodax\CheckoutManager\Contracts;

if ( ! defined('ABSPATH')) {
    exit;
}

interface HttpResponseInterface
{
    public function send();
}