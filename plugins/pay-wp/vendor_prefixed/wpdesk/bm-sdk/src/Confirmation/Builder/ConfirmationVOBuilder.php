<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Confirmation\Builder;

use WPPayVendor\BlueMedia\Serializer\Serializer;
use WPPayVendor\BlueMedia\Confirmation\ValueObject\Confirmation;
final class ConfirmationVOBuilder
{
    public static function build(array $data) : \WPPayVendor\BlueMedia\Confirmation\ValueObject\Confirmation
    {
        return (new \WPPayVendor\BlueMedia\Serializer\Serializer())->fromArray($data, \WPPayVendor\BlueMedia\Confirmation\ValueObject\Confirmation::class);
    }
}
