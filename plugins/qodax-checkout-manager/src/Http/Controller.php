<?php

namespace Qodax\CheckoutManager\Http;

use Qodax\CheckoutManager\Contracts\HttpResponseInterface;

if ( ! defined('ABSPATH')) {
    exit;
}

abstract class Controller
{
    public function json(array $data): HttpResponseInterface
    {
        return new JsonResponse($data);
    }
}