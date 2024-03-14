<?php

namespace Qodax\CheckoutManager\Services;

use Qodax\CheckoutManager\DB\Repositories\CheckoutFieldRepository;
use Qodax\CheckoutManager\Factories\FieldFactory;

if ( ! defined('ABSPATH')) {
    exit;
}

class FieldService
{
    /**
     * @var CheckoutFieldRepository
     */
    private $checkoutFieldRepository;

    public function __construct(CheckoutFieldRepository $checkoutFieldRepository)
    {
        $this->checkoutFieldRepository = $checkoutFieldRepository;
    }

    /**
     * @return array
     */
    public function getCheckoutFields(string $section): array
    {
        $result = [];
        $dbFields = $this->checkoutFieldRepository->findBySection($section);

        remove_all_filters('woocommerce_checkout_fields');

        if (empty($dbFields)) {
            $fields = wc()->checkout()->get_checkout_fields($section);

            foreach ($fields as $fieldName => $field) {
                $result[] = FieldFactory::fromDefault($fieldName, $field);
            }
        } else {
            foreach ($dbFields as $field) {
                $result[] = FieldFactory::fromDB($field);
            }
        }

        return $result;
    }

    public function saveFields(array $fields, string $section)
    {
        $this->checkoutFieldRepository->deleteBySection($section);

        foreach ($fields as $field) {
            $field = $this->prepareFieldToSave($field);
            $id = $this->checkoutFieldRepository->insert($field, $section);
            $this->checkoutFieldRepository->insertDisplayRules($id, $field['displayRules'] ?? []);
        }
        $this->checkoutFieldRepository->deleteOldDisplayRules();
    }

    public function restoreFields(string $section)
    {
        $this->checkoutFieldRepository->deleteBySection($section);
    }

    private function prepareFieldToSave(array $field): array
    {
        $field['meta']['label'] = wp_unslash($field['meta']['label']);
        $field['meta']['placeholder'] = wp_unslash($field['meta']['placeholder']);

        return $field;
    }
}