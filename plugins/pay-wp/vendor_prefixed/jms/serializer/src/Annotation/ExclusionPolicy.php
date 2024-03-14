<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

use WPPayVendor\JMS\Serializer\Exception\RuntimeException;
/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class ExclusionPolicy implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
    use AnnotationUtilsTrait;
    public const NONE = 'NONE';
    public const ALL = 'ALL';
    /**
     * @var string|null
     */
    public $policy = 'NONE';
    public function __construct($values = [], ?string $policy = null)
    {
        $this->loadAnnotationParameters(\get_defined_vars());
        $this->policy = \strtoupper($this->policy);
        if (self::NONE !== $this->policy && self::ALL !== $this->policy) {
            throw new \WPPayVendor\JMS\Serializer\Exception\RuntimeException('Exclusion policy must either be "ALL", or "NONE".');
        }
    }
}
