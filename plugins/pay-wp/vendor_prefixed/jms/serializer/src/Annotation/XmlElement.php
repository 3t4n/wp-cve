<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class XmlElement implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
    use AnnotationUtilsTrait;
    /**
     * @var bool
     */
    public $cdata = \true;
    /**
     * @var string|null
     */
    public $namespace = null;
    public function __construct(array $values = [], bool $cdata = \true, ?string $namespace = null)
    {
        $this->loadAnnotationParameters(\get_defined_vars());
    }
}
