<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class MaxDepth implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
    use AnnotationUtilsTrait;
    /**
     * @Required
     * @var int
     */
    public $depth;
    public function __construct($values = [], int $depth = 0)
    {
        $this->loadAnnotationParameters(\get_defined_vars());
    }
}
