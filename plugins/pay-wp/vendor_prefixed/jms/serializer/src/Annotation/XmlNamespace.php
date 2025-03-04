<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class XmlNamespace implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
    use AnnotationUtilsTrait;
    /**
     * @Required
     * @var string|null
     */
    public $uri = null;
    /**
     * @var string
     */
    public $prefix = '';
    public function __construct(array $values = [], ?string $uri = null, string $prefix = '')
    {
        $this->loadAnnotationParameters(\get_defined_vars());
    }
}
