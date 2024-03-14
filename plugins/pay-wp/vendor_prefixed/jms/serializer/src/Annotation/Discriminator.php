<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Discriminator implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
    use AnnotationUtilsTrait;
    /** @var array<string> */
    public $map = [];
    /** @var string */
    public $field = 'type';
    /** @var bool */
    public $disabled = \false;
    /** @var string[] */
    public $groups = [];
    public function __construct(array $values = [], string $field = 'type', array $groups = [], array $map = [], bool $disabled = \false)
    {
        $this->loadAnnotationParameters(\get_defined_vars());
    }
}
