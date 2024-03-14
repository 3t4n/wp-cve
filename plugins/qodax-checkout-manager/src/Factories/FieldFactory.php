<?php

namespace Qodax\CheckoutManager\Factories;

use Qodax\CheckoutManager\Contracts\FieldDataPresenterInterface;
use Qodax\CheckoutManager\Includes\Presenter\DBFieldDataPresenter;
use Qodax\CheckoutManager\Includes\Presenter\WCFieldDataPresenter;
use Qodax\CheckoutManager\Includes\Fields\CheckoutField;

if ( ! defined('ABSPATH')) {
    exit;
}

class FieldFactory
{
    public static function fromDefault(string $name, array $data): CheckoutField
    {
        return self::makeField($data['type'] ?? 'text', new WCFieldDataPresenter($name, $data));
    }

    public static function fromDB(array $data): CheckoutField
    {
        return self::makeField($data['field_type'], new DBFieldDataPresenter($data));
    }

    public static function makeField(string $type, FieldDataPresenterInterface $dataPresenter): CheckoutField
    {
        return new CheckoutField($dataPresenter);
    }
}