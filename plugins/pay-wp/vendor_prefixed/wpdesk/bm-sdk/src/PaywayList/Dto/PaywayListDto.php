<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\PaywayList\Dto;

use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\PaywayList\ValueObject\PaywayList;
final class PaywayListDto extends \WPPayVendor\BlueMedia\Common\Dto\AbstractDto
{
    /**
     * @var PaywayList
     * @Type("WPPayVendor\BlueMedia\PaywayList\ValueObject\PaywayList")
     */
    private $paywayList;
    /**
     * @return PaywayList
     */
    public function getPaywayList() : \WPPayVendor\BlueMedia\PaywayList\ValueObject\PaywayList
    {
        return $this->paywayList;
    }
    public function getRequestData() : \WPPayVendor\BlueMedia\Serializer\SerializableInterface
    {
        return $this->getPaywayList();
    }
}
