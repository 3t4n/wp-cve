<?php

declare(strict_types=1);

namespace MyParcelNL\WooCommerce\includes\adapter;

use MyParcelNL\Sdk\src\Helper\SplitStreet;
use MyParcelNL\Sdk\src\Helper\ValidateStreet;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Recipient;
use WC_Order;
use WCMPBE_Settings;
use WPO\WC\MyParcelBE\Compatibility\Order as WCX_Order;

class RecipientFromWCOrder extends Recipient
{
    public const  BILLING                           = 'billing';
    public const  SHIPPING                          = 'shipping';
    private const MIN_STREET_ADDITIONAL_INFO_LENGTH = 10;

    /**
     * Parameter $type should always be one of two constants, either 'billing' or 'shipping'.
     *
     * @param  \WC_Order $order
     * @param  string    $originCountry
     * @param  string    $type
     *
     * @throws \Exception
     */
    public function __construct(WC_Order $order, string $originCountry, string $type)
    {
        $recipientDetails = $this->createAddress($order, $type);
        parent::__construct($recipientDetails, $originCountry);
    }

    /**
     * @param  \WC_Order $order
     * @param  string    $type
     *
     * @return array
     * @throws \JsonException
     */
    private function createAddress(WC_Order $order, string $type): array
    {
        return [
                'cc'          => $order->{"get_{$type}_country"}(),
                'city'        => $order->{"get_{$type}_city"}(),
                'company'     => $order->{"get_{$type}_company"}(),
                'postal_code' => $order->{"get_{$type}_postcode"}(),
                'region'      => $order->{"get_{$type}_state"}(),
                'person'      => $this->getPersonFromOrder($order, $type),
                'email'       => $order->get_billing_email(),
                'phone'       => $this->getPhoneNumberFromOrder($order),
            ] + $this->getAddressFromOrder($order, $type);
    }

    /**
     * @param  \WC_Order $order
     * @param  string    $type
     *
     * @return array
     * @throws \JsonException
     */
    private function getAddressFromOrder(WC_Order $order, string $type): array
    {
        $street       = WCX_Order::get_meta($order, "_{$type}_street_name") ?: null;
        $number       = WCX_Order::get_meta($order, "_{$type}_house_number") ?: null;
        $numberSuffix = WCX_Order::get_meta($order, "_{$type}_house_number_suffix") ?: null;
        $addressLine2 = $order->{"get_{$type}_address_2"}();
        $addressLine1 = $order->{"get_{$type}_address_1"}();
        $country      = $order->{"get_{$type}_country"}();
        $isNL         = AbstractConsignment::CC_NL === $country;
        $isBE         = AbstractConsignment::CC_BE === $country;

        $isUsingSplitAddressFields  = ! empty($street) || ! empty($number) || ! empty($numberSuffix);

        if (! $isNL && ! $isBE) {
            $fullStreet = $isUsingSplitAddressFields
                ? implode(' ', [$street, $number, $numberSuffix])
                : $addressLine1;

            return [
                'full_street'            => $fullStreet,
                'street_additional_info' => $addressLine2 ?? null,
            ];
        }

        $streetParts = $this->separateStreet($addressLine1, $order, $type);

        $addressLine2IsNumberSuffix = strlen($addressLine2) < self::MIN_STREET_ADDITIONAL_INFO_LENGTH;

        if (! $streetParts['number_suffix'] && $addressLine2IsNumberSuffix) {
            $streetParts['number_suffix'] = $order->{"get_{$type}_address_2"}();
            $addressLine2                  = null;
        }

        $fullStreet = implode(' ', [
                $streetParts['street'],
                $streetParts['number'],
                $streetParts['number_suffix'],
                $streetParts['box_separator'],
                $streetParts['box_number'],
            ]
        );

        if ($isUsingSplitAddressFields) {
            $fullStreet   = implode(' ', [$street, $number, $numberSuffix]);
            $addressLine2 = null;
        }

        return [
            'full_street'            => $fullStreet,
            'street_additional_info' => $addressLine2,
        ];
    }

    /**
     * Phone should always come from the billing address.
     *
     * @param  \WC_Order $order
     *
     * @return string|null
     */
    private function getPhoneNumberFromOrder(WC_Order $order): ?string
    {
        $connectPhone = WCMYPABE()->setting_collection->isEnabled(WCMPBE_Settings::SETTING_CONNECT_PHONE);

        return $connectPhone
            ? $order->get_billing_phone()
            : null;
    }

    /**
     * @param  \WC_Order $order
     * @param  string    $type
     *
     * @return string
     */
    private function getPersonFromOrder(WC_Order $order, string $type): string
    {
        $getFullName  = "get_formatted_{$type}_full_name";
        $getFirstName = "get_{$type}_first_name";
        $getLastName  = "get_{$type}_last_name";

        return method_exists($order, $getFullName)
            ? $order->{$getFullName}()
            : trim($order->{$getFirstName}() . ' ' . $order->{$getLastName}());
    }

    /**
     * @param  string    $street
     * @param  \WC_Order $order
     * @param  string    $type
     *
     * @return array
     */
    private function separateStreet(string $street, WC_Order $order, string $type): array
    {
        if ($order->{"get_{$type}_country"}() === AbstractConsignment::CC_BE) {
            foreach (SplitStreet::BOX_SEPARATOR_BY_REGEX as $boxRegex) {
                $street = preg_replace('#' . $boxRegex . '([0-9])#', SplitStreet::BOX_NL . ' ' . ltrim('$1'), $street);
            }
        }

        $regex = $order->{"get_{$type}_country"}() === AbstractConsignment::CC_BE
            ? ValidateStreet::SPLIT_STREET_REGEX_BE : ValidateStreet::SPLIT_STREET_REGEX_NL;

        preg_match($regex, $street, $separateStreet);

        return $separateStreet;
    }
}
