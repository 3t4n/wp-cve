<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class PostSerialize implements \WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute
{
}
