<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Itn\ValueObject\ItnResponse;

use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
use WPPayVendor\JMS\Serializer\Annotation\Type;
/**
 * @AccessorOrder("custom",
 *     custom = {
 *      "orderID",
 *      "confirmation"
 * })
 */
final class TransactionConfirmed implements \WPPayVendor\BlueMedia\Serializer\SerializableInterface
{
    /**
     * @var string
     * @Type("string")
     */
    private $orderID;
    /**
     * @var string
     * @Type("string")
     */
    private $confirmation;
    /**
     * @return string
     */
    public function getOrderID() : string
    {
        return $this->orderID;
    }
    /**
     * @return string
     */
    public function getConfirmation() : string
    {
        return $this->confirmation;
    }
}
