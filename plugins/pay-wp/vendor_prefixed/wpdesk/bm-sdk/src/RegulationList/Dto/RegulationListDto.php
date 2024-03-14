<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\RegulationList\Dto;

use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationList;
final class RegulationListDto extends \WPPayVendor\BlueMedia\Common\Dto\AbstractDto
{
    /**
     * @var RegulationList
     * @Type("WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationList")
     */
    private $regulationList;
    /**
     * @return RegulationList
     */
    public function getRegulationList() : \WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationList
    {
        return $this->regulationList;
    }
    public function getRequestData() : \WPPayVendor\BlueMedia\Serializer\SerializableInterface
    {
        return $this->getRegulationList();
    }
}
