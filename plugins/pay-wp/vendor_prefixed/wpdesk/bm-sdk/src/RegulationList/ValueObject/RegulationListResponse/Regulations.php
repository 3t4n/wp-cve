<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationListResponse;

use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\JMS\Serializer\Annotation\XmlList;
final class Regulations
{
    /**
     * @var Regulation
     * @XmlList(inline = true, entry = "regulation")
     * @Type("array<BlueMedia\RegulationList\ValueObject\RegulationListResponse\Regulation>")
     */
    private $regulation;
    /**
     * @return Regulation
     */
    public function getRegulation() : array
    {
        return $this->regulation;
    }
}
