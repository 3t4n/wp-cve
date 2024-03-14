<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class Expose implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
    use AnnotationUtilsTrait;
    /**
     * @var string|null
     */
    public $if = null;
    public function __construct(array $values = [], ?string $if = null)
    {
        $this->loadAnnotationParameters(\get_defined_vars());
    }
}
