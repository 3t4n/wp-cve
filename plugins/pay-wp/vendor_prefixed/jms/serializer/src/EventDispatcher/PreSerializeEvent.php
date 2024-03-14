<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\EventDispatcher;

class PreSerializeEvent extends \WPPayVendor\JMS\Serializer\EventDispatcher\ObjectEvent
{
    /**
     * @param array $params
     */
    public function setType(string $typeName, array $params = []) : void
    {
        $this->type = ['name' => $typeName, 'params' => $params];
    }
}
