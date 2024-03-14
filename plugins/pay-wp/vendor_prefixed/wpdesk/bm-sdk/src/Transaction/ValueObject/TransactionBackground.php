<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Transaction\ValueObject;

use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
/**
 * @AccessorOrder("custom",
 *     custom = {
 *      "receiverNRB",
 *      "receiverName",
 *      "receiverAddress",
 *      "orderID",
 *      "amount",
 *      "currency",
 *      "title",
 *      "remoteID",
 *      "bankHref",
 *     "returnURL",
 *      "hash"
 * })
 */
final class TransactionBackground extends \WPPayVendor\BlueMedia\Transaction\ValueObject\Transaction
{
}
