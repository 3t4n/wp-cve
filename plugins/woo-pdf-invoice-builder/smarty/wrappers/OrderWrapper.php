<?php
class OrderWrapper extends WrapperBase{
    /**
     * @var WC_Order
     */
    public $order;
    /**
     * OrderWrapper constructor.
     * @param $order WC_Order
     */
    public function __construct($order)
    {
        $this->order=$order;
        $this->primaryOrder=$order;
    }

    public function get($propertyName,$context='view')
    {
        return $this->GetValueFromObject($this->order,$propertyName,$context);
    }
}