<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"CLASS","PROPERTY"})
 *
 * @final
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY)]
/* final */
class ReadOnlyProperty implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
    use AnnotationUtilsTrait;
    /**
     * @var bool
     */
    public $readOnly = \true;
    public function __construct(array $values = [], bool $readOnly = \true)
    {
        $this->loadAnnotationParameters(\get_defined_vars());
    }
}
