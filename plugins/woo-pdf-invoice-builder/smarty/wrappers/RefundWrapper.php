<?php
class RefundWrapper extends WrapperBase{
    /**
     * @var WC_Order
     */
    public $order;
    /**
     * @var WC_Order_Refund;
     */
    public $refundOrder;

    public function __construct($order)
    {
        $this->refundOrder=$order;
        $this->$order=new WC_Order($this->refundOrder->get_parent_id());
        $this->primaryOrder=$order;
    }

    public function get($propertyName,$context='view')
    {
        $value=$this->GetValueFromObject($this->refundOrder,$propertyName);
        if($value=='')
            return $this->GetValueFromObject($this->order);
    }
}