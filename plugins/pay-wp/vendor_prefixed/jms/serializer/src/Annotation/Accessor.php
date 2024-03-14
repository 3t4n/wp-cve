<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Accessor implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
    use AnnotationUtilsTrait;
    /**
     * @var string|null
     */
    public $getter = null;
    /**
     * @var string|null
     */
    public $setter = null;
    public function __construct(array $values = [], ?string $getter = null, ?string $setter = null)
    {
        $this->loadAnnotationParameters(\get_defined_vars());
    }
}
