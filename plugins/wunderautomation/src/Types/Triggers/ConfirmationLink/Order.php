<?php

namespace WunderAuto\Types\Triggers\ConfirmationLink;

use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Order
 */
class Order extends BaseConfirmationLink
{
    /**
     * Create
     */
    public function __construct()
    {
        parent::__construct();

        $this->title = __('Confirmation: Order', 'wunderauto');
        $this->addProvidedObject(
            'order',
            'order',
            __('The order associated with the clicked link', 'wunderauto'),
            true
        );
        $this->addProvidedObject(
            'user',
            'user',
            __(
                'The WordPress user that placed the order. Guest orders provides a valid but empty user object',
                'wunderauto'
            ),
            true
        );
        $this->addProvidedObject(
            'link',
            'link',
            __('The clicked link', 'wunderauto'),
            false
        );
    }
}
