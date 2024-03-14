<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Transaction\Builder;

use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
use WPPayVendor\BlueMedia\Configuration;
use WPPayVendor\BlueMedia\Hash\HashGenerator;
use WPPayVendor\BlueMedia\Serializer\Serializer;
use WPPayVendor\BlueMedia\Transaction\Dto\TransactionDto;
final class TransactionDtoBuilder
{
    public static function build(array $transactionData, \WPPayVendor\BlueMedia\Configuration $configuration) : \WPPayVendor\BlueMedia\Common\Dto\AbstractDto
    {
        $serializer = new \WPPayVendor\BlueMedia\Serializer\Serializer();
        $transactionDto = $serializer->serializeDataToDto($transactionData, \WPPayVendor\BlueMedia\Transaction\Dto\TransactionDto::class);
        $transactionDto->getTransaction()->setServiceId($configuration->getServiceId());
        $hash = \WPPayVendor\BlueMedia\Hash\HashGenerator::generateHash($transactionDto->getTransaction()->capitalizedArray(), $configuration);
        $transactionDto->getTransaction()->setHash($hash);
        return $transactionDto;
    }
}
