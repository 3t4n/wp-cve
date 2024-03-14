<?php

namespace FluentConnect\App\Services\ThriveCart;

use FluentConnect\App\Services\Triggers\BaseTrigger;
use FluentConnect\Framework\Support\Arr;

class SubscriptionCancelledTrigger extends BaseTrigger
{
    public function __construct()
    {
        $this->triggerProvider = 'thrivecart';
        $this->triggerName = 'fluent_connector_thrivecart_event_order_subscription_cancelled';

        parent::__construct();

    }

    public function getInfo()
    {
        return [
            'title'                  => 'Subscription Cancelled on ThriveCart',
            'description'            => 'This will trigger when a subscription got cancelled on ThriveCart',
            'has_remote_integration' => true,
            'supported_fields'       => [
                'email',
                'name',
                'first_name',
                'last_name'
            ],
            'is_enabled' => $this->isEnabled(),
            'disabled_message' => 'Please configure ThriveCart integration first',
            'settings_defaults' => $this->getSettingsDefaults()
        ];
    }

    public function getSettingsDefaults()
    {
        return [
            'match_type'  => 'product_ids',
            'product_ids' => []
        ];
    }

    public function getSettingsFields($trigger)
    {
        $groupedProducts = ThriveCartHelper::getGroupedProductsOptions($trigger->integration_id, false);

        return [
            'product_ids' => [
                'type'        => 'input-grouped-options',
                'title'       => 'Select Target ThriveCart Products',
                'inline_help' => 'Select the products that you want to run this feed. Keep blank for any subscription cancelled on ThriveCart',
                'placeholder' => 'Select Products',
                'options'     => $groupedProducts,
                'show_id' => true,
                'is_multiple' => true
            ]
        ];
    }

    public function getFormattedData($trigger, $args)
    {
        $event = $args[0];
        $remoteAccount = Arr::get($event, 'thrivecart_account');
        if($remoteAccount != $trigger->remote_id) {
            return false; // Not Our Account
        }

        // check the product match
        if($targetProductIds = $trigger->settings['product_ids']) {
            $cancelledKey = 'product-' . Arr::get($event,'base_product');
            if(!in_array($cancelledKey, $targetProductIds)) {
                return false;
            }
        }

        $data = ThriveCartHelper::getDataFromCustomer(Arr::get($event, 'customer', []));

        if($data) {
            $data['__runner_hash'] = $event['order_id'];
            $data['__event'] = $event;
        }

        return $data;
    }

    public function isEnabled()
    {
        return true;
    }
}
