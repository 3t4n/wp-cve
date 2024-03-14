<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\EventDispatcher;

use WPPayVendor\JMS\Serializer\Context;
class ObjectEvent extends \WPPayVendor\JMS\Serializer\EventDispatcher\Event
{
    /**
     * @var mixed
     */
    private $object;
    /**
     * @param mixed $object
     */
    public function __construct(\WPPayVendor\JMS\Serializer\Context $context, $object, array $type)
    {
        parent::__construct($context, $type);
        $this->object = $object;
    }
    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }
}
