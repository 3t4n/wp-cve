<?php

namespace S2WPImporter\Process;

use WC_Customer;

class Customer extends AbstractRecord implements IRecord
{
    /**
     * @var $item = [
     *      "id" => 5275122630822,
     *      "email" => "egnition_sample_84@egnition.com",
     *      "accepts_marketing" => false,
     *      "created_at" => "2021-05-12T13:40:34+03:00",
     *      "updated_at" => "2021-06-09T14:16:48+03:00",
     *      "first_name" => "Octavius",
     *      "last_name" => "Mullins",
     *      "orders_count" => 4,
     *      "state" => "disabled",
     *      "total_spent" => "50.30",
     *      "last_order_id" => 3891489505446,
     *      "note" => null,
     *      "verified_email" => true,
     *      "multipass_identifier" => null,
     *      "tax_exempt" => false,
     *      "phone" => "+33490242549",
     *      "tags" => "egnition-sample-data, referral",
     *      "last_order_name" => "#1021",
     *      "currency" => "MDL",
     *      "addresses" => [
     *          [
     *          "id" => 6447113175206,
     *          "customer_id" => 5275122630822,
     *          "first_name" => "Octavius",
     *          "last_name" => "Mullins",
     *          "company" => null,
     *          "address1" => "206-127 Egestas. Street",
     *          "address2" => null,
     *          "city" => "Palmerston",
     *          "province" => "Marseille",
     *          "country" => "France",
     *          "zip" => "33255",
     *          "phone" => "+33490242549",
     *          "name" => "Octavius Mullins",
     *          "province_code" => null,
     *          "country_code" => "FR",
     *          "country_name" => "France",
     *          "default" => true
     *          ],
     *          [
     *          "id" => 6532372136102,
     *          "customer_id" => 5275122630822,
     *          "first_name" => "Octavius",
     *          "last_name" => "Mullins",
     *          "company" => "ZWP",
     *          "address1" => "zwp street com",
     *          "address2" => "ap. 27, stair 2, 4th floor",
     *          "city" => "Nowhere",
     *          "province" => "Amazonas",
     *          "country" => "Peru",
     *          "zip" => "23921",
     *          "phone" => null,
     *          "name" => "Octavius Mullins",
     *          "province_code" => "PE-AMA",
     *          "country_code" => "PE",
     *          "country_name" => "Peru",
     *          "default" => false
     *          ]
     *      ],
     *      "accepts_marketing_updated_at" => "2021-05-12T13:40:34+03:00",
     *      "marketing_opt_in_level" => null,
     *      "tax_exemptions" => [],
     *      "admin_graphql_api_id" => "gid://shopify/Customer/5275122630822",
     *      "default_address" => [
     *          "id" => 6447113175206,
     *          "customer_id" => 5275122630822,
     *          "first_name" => "Octavius",
     *          "last_name" => "Mullins",
     *          "company" => null,
     *          "address1" => "206-127 Egestas. Street",
     *          "address2" => null,
     *          "city" => "Palmerston",
     *          "province" => "Marseille",
     *          "country" => "France",
     *          "zip" => "33255",
     *          "phone" => "+33490242549",
     *          "name" => "Octavius Mullins",
     *          "province_code" => null,
     *          "country_code" => "FR",
     *          "country_name" => "France",
     *          "default" => true
     *      ]
     * ]
     */
    protected $item;

    /**
     * @var WC_Customer
     */
    protected $customer;

    /**
     * Order constructor.
     *
     * @param             $item = $this->item
     * @param WC_Customer $customer
     */
    public function __construct($item, $customer)
    {
        $this->item = $item;
        $this->customer = $customer;
    }

    public function parse()
    {
        $this->customer->set_email(sanitize_email($this->item['email']));
        $this->customer->set_billing_email(sanitize_email($this->item['email']));

        $this->customer->set_first_name(sanitize_text_field($this->item['first_name']));
        $this->customer->set_last_name(sanitize_text_field($this->item['last_name']));

        $this->customer->set_date_created(sanitize_text_field($this->item['created_at']));
        $this->customer->set_date_modified(sanitize_text_field($this->item['updated_at']));

        $this->customer->set_is_paying_customer((int)$this->item['orders_count'] > 0);

        $this->setBillingAddress();
        $this->setShippingAddress();
    }

    public function save()
    {
        return $this->customer->save();
    }

    public function afterSave($objId)
    {
    }
    /*
    -------------------------------------------------------------------------------
    Internal
    -------------------------------------------------------------------------------
    */
    /**
     * @param 'billing'|'shipping' $type
     * @param $data = $this->item['default_address'] = [
     *    "id" => 6447113175206,
     *    "customer_id" => 5275122630822,
     *    "first_name" => "Octavius",
     *    "last_name" => "Mullins",
     *    "company" => null,
     *    "address1" => "206-127 Egestas. Street",
     *    "address2" => null,
     *    "city" => "Palmerston",
     *    "province" => "Marseille",
     *    "country" => "France",
     *    "zip" => "33255",
     *    "phone" => "+33490242549",
     *    "name" => "Octavius Mullins",
     *    "province_code" => null,
     *    "country_code" => "FR",
     *    "country_name" => "France",
     *    "default" => true
     * ]
     */
    protected function setAddress($type, $data)
    {
        $this->customer->{"set_{$type}_first_name"}(sanitize_text_field($data['first_name']) ?? null);
        $this->customer->{"set_{$type}_last_name"}(sanitize_text_field($data['last_name']) ?? null);

        if ($type === 'shipping') {
            $this->customer->{"set_{$type}_company"}(sanitize_text_field($data['company']) ?? null);
        }

        $this->customer->{"set_{$type}_address"}(sanitize_text_field($data['address1']) ?? null);
        $this->customer->{"set_{$type}_address_2"}(sanitize_text_field($data['address2']) ?? null);

        $this->customer->{"set_{$type}_country"}(sanitize_text_field($data['country_code']) ?? null);
        $this->customer->{"set_{$type}_state"}(sanitize_text_field($data['province']) ?? null);
        $this->customer->{"set_{$type}_city"}(sanitize_text_field($data['city']) ?? null);
        $this->customer->{"set_{$type}_postcode"}(sanitize_text_field($data['zip']) ?? null);

        if ($type === 'billing') {
            $this->customer->{"set_{$type}_phone"}(sanitize_text_field($data['phone']) ?? null);
            $this->customer->{"set_{$type}_email"}(sanitize_email($this->item['email']) ?? null);
        }
    }

    protected function setBillingAddress()
    {
        if (!empty($this->item['default_address'])) {
            $data = $this->item['default_address'];

            $this->setAddress('billing', $data);
        }
    }

    protected function setShippingAddress()
    {
        $addresses = (array)$this->item['addresses'];
        $data = !empty($addresses[1]) ? (array)$addresses[1] : $this->item['default_address'];

        if (!empty($data)) {
            $this->setAddress('shipping', $data);
        }
    }
}
