<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationListResponse;

use WPPayVendor\JMS\Serializer\Annotation\Type;
final class Regulation
{
    /**
     * @var string
     * @Type("string")
     */
    private $regulationID;
    /**
     * @var string
     * @Type("string")
     */
    private $url;
    /**
     * @var string
     * @Type("string")
     */
    private $type;
    /**
     * @var string
     * @Type("string")
     */
    private $language;
    /**
     * @return string
     */
    public function getRegulationID() : string
    {
        return $this->regulationID;
    }
    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }
    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }
    /**
     * @return string
     */
    public function getLanguage() : string
    {
        return $this->language;
    }
}
