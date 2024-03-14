<?php

namespace Qodax\CheckoutManager\Http\Controllers;

use Qodax\CheckoutManager\Contracts\HttpResponseInterface;
use Qodax\CheckoutManager\Http\Controller;
use Qodax\CheckoutManager\Http\Request;
use Qodax\CheckoutManager\Services\FieldService;

if ( ! defined('ABSPATH')) {
    exit;
}

class FieldsController extends Controller
{
    /**
     * @var FieldService
     */
    private $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    public function getFields(Request $request): HttpResponseInterface
    {
        return $this->json([
            'success' => true,
            'data' => $this->fieldService->getCheckoutFields($request->get('section'))
        ]);
    }

    public function save(Request $request): HttpResponseInterface
    {
        $this->fieldService->saveFields($request->get('fields'), $request->get('section'));

        return $this->json([
            'success' => true
        ]);
    }

    public function restoreDefaults(Request $request): HttpResponseInterface
    {
        $this->fieldService->restoreFields($request->get('section'));

        return $this->json([
            'success' => true
        ]);
    }
}