<?php

namespace Qodax\CheckoutManager\Http;

use Qodax\CheckoutManager\Contracts\HttpResponseInterface;

if ( ! defined('ABSPATH')) {
    exit;
}

class JsonResponse implements HttpResponseInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * JsonResponse constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function send()
    {
        wp_send_json($this->data);
    }
}