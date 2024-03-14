<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

class DangerousGood
{
    private string $contentId;
    private string $customDescription = '';
    private string $unCode = '';
    private int $dryIceTotalNetWeight = 0;
    public function __construct(string $contentId, string $customDescription = '', string $unCode = '', int $dryIceTotalNetWeight = 0)
    {
        $this->dryIceTotalNetWeight = $dryIceTotalNetWeight;
        $this->unCode = $unCode;
        $this->customDescription = $customDescription;
        $this->contentId = $contentId;
    }
    public function getAsArray() : array
    {
        $result = [];
        $result['contentId'] = $this->contentId;
        if ($this->customDescription) {
            $result['customDescription'] = $this->customDescription;
        }
        if ($this->unCode) {
            $result['unCode'] = $this->unCode;
        }
        if ($this->dryIceTotalNetWeight) {
            $result['dryIceTotalNetWeight'] = $this->dryIceTotalNetWeight;
        }
        return $result;
    }
}
