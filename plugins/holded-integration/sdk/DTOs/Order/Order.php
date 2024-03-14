<?php

declare(strict_types=1);

namespace Holded\SDK\DTOs\Order;

class Order implements \JsonSerializable
{
    /** @var string */
    public $holdedId;

    /** @var string */
    public $contact_code;

    /** @var string */
    public $contact_name;

    /** @var string */
    public $marketplace;

    /** @var string */
    public $contact_email;

    /** @var string */
    public $contact_phone;

    /** @var string */
    public $contact_mobile;

    /** @var string */
    public $contact_stateid;

    /** @var string */
    public $contact_tradename;

    /** @var string */
    public $contact_company;

    /** @var string */
    public $contact_vat;

    /** @var string */
    public $contact_other;

    /** @var string */
    public $contact_address;

    /** @var string */
    public $contact_city;

    /** @var string Postal Code */
    public $contact_cp;

    /** @var string */
    public $contact_province;

    /** @var string */
    public $contact_provincecode;

    /** @var string */
    public $contact_country;

    /** @var string */
    public $contact_countrycode;

    /** @var string */
    public $desc;

    /** @var int|false */
    public $date;

    /** @var int|false */
    public $datestart;

    /** @var string */
    public $notes;

    /** @var ?string */
    public $saleschannel;

    /** @var string */
    public $language;

    /** @var ?string */
    public $pmtype;

    /** @var Item[] */
    public $items;

    /** @var string Shipping Address */
    public $shipping_ad;

    /** @var string Postal Code */
    public $shipping_cp;

    /** @var string City */
    public $shipping_ci;

    /** @var string */
    public $shipping_pr;

    /** @var string Country */
    public $shipping_co;

    /** @var string Country code */
    public $shipping_cocode;

    /** @var string */
    public $shipping_name;

    /** @var string */
    public $shipping_contact_code;

    /** @var string */
    public $shipping_phone;

    /** @var string */
    public $shipping_email;

    /** @var int */
    public $taxesEnabled;

    /** @var int */
    public $priceWithTaxesIncluded;

    /** @var string */
    public $holdedBuild;

    /** @var string */
    public $siteUrl;

    /** @var string */
    public $currency;

    /** @var float */
    public $currencyChange;

    /** @var string */
    public $customer;

    /** @var string */
    public $orderStatus;

    /** @var string */
    public $orderId;

    /** @var string */
    public $orderNumber;

    /** @var string */
    public $orderDate;

    /** @var string */
    public $store;

    /** @var string */
    public $totalPaid;

    /** @var string */
    public $paymentMethod;

    public function jsonSerialize()
    {
        return [
            'marketplace'            => $this->marketplace,
            'contact_code'           => $this->contact_code,
            'contact_name'           => $this->contact_name,
            'contact_email'          => $this->contact_email,
            'contact_phone'          => $this->contact_phone,
            'contact_mobile'         => $this->contact_mobile,
            'contact_stateid'        => $this->contact_stateid,
            'contact_tradename'      => $this->contact_tradename,
            'contact_company'        => $this->contact_company,
            'contact_vat'            => $this->contact_vat,
            'contact_other'          => $this->contact_other,
            'contact_address'        => $this->contact_address,
            'contact_city'           => $this->contact_city,
            'contact_cp'             => $this->contact_cp,
            'contact_province'       => $this->contact_province,
            'contact_provincecode'   => $this->contact_provincecode,
            'contact_country'        => $this->contact_country,
            'contact_countrycode'    => $this->contact_countrycode,
            'desc'                   => $this->desc,
            'date'                   => $this->date,
            'datestart'              => $this->datestart,
            'notes'                  => $this->notes,
            'saleschannel'           => $this->saleschannel,
            'language'               => $this->language,
            'pmtype'                 => $this->pmtype,
            'items'                  => $this->items,
            'shipping_ad'            => $this->shipping_ad,
            'shipping_cp'            => $this->shipping_cp,
            'shipping_ci'            => $this->shipping_ci,
            'shipping_pr'            => $this->shipping_pr,
            'shipping_co'            => $this->shipping_co,
            'shipping_cocode'        => $this->shipping_cocode,
            'shipping_contact_code'  => $this->shipping_contact_code,
            'shipping_name'          => $this->shipping_name,
            'shipping_phone'         => $this->shipping_phone,
            'shipping_email'         => $this->shipping_email,
            'taxesEnabled'           => $this->taxesEnabled,
            'priceWithTaxesIncluded' => $this->priceWithTaxesIncluded,
            'holdedBuild'            => $this->holdedBuild,
            'siteUrl'                => $this->siteUrl,
            'currency'               => $this->currency,
            'currencyChange'         => $this->currencyChange ?? null,
            'customer'               => $this->customer,
            'orderStatus'            => $this->orderStatus,
            'orderId'                => $this->orderId,
            'orderNumber'            => $this->orderNumber,
            'orderDate'              => $this->orderDate,
            'store'                  => $this->store,
            'totalPaid'              => $this->totalPaid,
            'paymentMethod'          => $this->paymentMethod,
        ];
    }
}
