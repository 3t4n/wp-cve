<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Transaction\ValueObject;

use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
/**
 * @AccessorOrder("custom",
 *     custom = {
 *      "status",
 *      "redirecturl",
 *      "orderID",
 *      "remoteID",
 *      "hash"
 * })
 */
final class TransactionContinue extends \WPPayVendor\BlueMedia\Transaction\ValueObject\Transaction
{
    /**
     * @var string
     * @Type("string")
     */
    private $status;
    /**
     * @var string
     * @Type("string")
     */
    private $redirecturl;
    /**
     * @return string
     */
    public function getStatus() : string
    {
        return $this->status;
    }
    /**
     * @return string
     */
    public function getRedirectUrl() : string
    {
        return $this->redirecturl;
    }
}
