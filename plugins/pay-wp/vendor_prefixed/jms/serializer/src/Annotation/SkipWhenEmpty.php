<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class SkipWhenEmpty implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
}
