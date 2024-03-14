<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Itn\Dto;

use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
use WPPayVendor\BlueMedia\Itn\ValueObject\Itn;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\JMS\Serializer\Annotation\XmlList;
use WPPayVendor\JMS\Serializer\Annotation\Type;
final class ItnDto extends \WPPayVendor\BlueMedia\Common\Dto\AbstractDto implements \WPPayVendor\BlueMedia\Serializer\SerializableInterface
{
    /**
     * @var Itn
     * @Type("WPPayVendor\BlueMedia\Itn\ValueObject\Itn")
     * @XmlList(inline = true, entry = "transaction")
     */
    private $itn;
    /**
     * @return Itn
     */
    public function getItn() : \WPPayVendor\BlueMedia\Itn\ValueObject\Itn
    {
        return $this->itn;
    }
    public function getRequestData() : \WPPayVendor\BlueMedia\Serializer\SerializableInterface
    {
        return $this->getItn();
    }
}
