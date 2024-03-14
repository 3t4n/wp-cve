<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\PaywayList\ValueObject\PaywayListResponse;

use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\JMS\Serializer\Annotation\XmlList;
use WPPayVendor\BlueMedia\PaywayList\ValueObject\PaywayList;
final class PaywayListResponse extends \WPPayVendor\BlueMedia\PaywayList\ValueObject\PaywayList
{
    /**
     * @XmlList(inline = true, entry = "gateway")
     * @Type("array<BlueMedia\PaywayList\ValueObject\PaywayListResponse\Gateway>")
     */
    private $gateways;
    /**
     * @return array
     */
    public function getGateways() : array
    {
        return $this->gateways;
    }
}
