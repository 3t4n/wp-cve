<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationListResponse;

use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationList;
final class RegulationListResponse extends \WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationList
{
    /**
     * @var Regulations
     * @Type("WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationListResponse\Regulations")
     */
    private $regulations;
    /**
     * @return Regulations
     */
    public function getRegulations() : \WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationListResponse\Regulations
    {
        return $this->regulations;
    }
}
