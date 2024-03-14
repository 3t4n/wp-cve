<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class OrderNote
 * Adds an order note to the order that was updated.
 */
class OrderNote extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Add order note', 'wunderauto');
        $this->description = __('Add or append to order note', 'wunderauto');
        $this->group       = 'WooCommerce';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'type', 'key');
        $config->sanitizeObjectProp($config->value, 'content', 'textarea');
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $noteType    = $this->get('value.type');
        $noteContent = $this->getResolved('value.content');
        $order       = $this->resolver->getObject('order');

        if (!$noteContent || !$noteType || !$order) {
            return false;
        }

        if (!($order instanceof \WC_Order)) {
            return false;
        }

        $order->add_order_note($noteContent, (int)($noteType === 'customer'), false);

        return true;
    }
}
