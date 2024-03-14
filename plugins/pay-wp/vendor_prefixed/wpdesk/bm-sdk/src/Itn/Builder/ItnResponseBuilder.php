<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Itn\Builder;

use WPPayVendor\BlueMedia\Configuration;
use WPPayVendor\BlueMedia\Common\Enum\ClientEnum;
use WPPayVendor\BlueMedia\Hash\HashGenerator;
use WPPayVendor\BlueMedia\Itn\ValueObject\Itn;
use WPPayVendor\BlueMedia\Itn\ValueObject\ItnResponse\ItnResponse;
use WPPayVendor\BlueMedia\Serializer\Serializer;
final class ItnResponseBuilder
{
    public static function build(\WPPayVendor\BlueMedia\Itn\ValueObject\Itn $itn, bool $transactionConfirmed, \WPPayVendor\BlueMedia\Configuration $configuration) : \WPPayVendor\BlueMedia\Itn\ValueObject\ItnResponse\ItnResponse
    {
        $confirmation = $transactionConfirmed ? \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::STATUS_CONFIRMED : \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::STATUS_NOT_CONFIRMED;
        $hashData = ['serviceID' => $configuration->getServiceId(), 'orderID' => $itn->getOrderId(), 'confirmation' => $confirmation];
        $itnResponseData = ['serviceID' => $configuration->getServiceId(), 'transactionsConfirmations' => ['transactionConfirmed' => ['orderID' => $itn->getOrderId(), 'confirmation' => $confirmation]], 'hash' => \WPPayVendor\BlueMedia\Hash\HashGenerator::generateHash($hashData, $configuration)];
        $serializer = new \WPPayVendor\BlueMedia\Serializer\Serializer();
        return $serializer->fromArray($itnResponseData, \WPPayVendor\BlueMedia\Itn\ValueObject\ItnResponse\ItnResponse::class);
    }
}
