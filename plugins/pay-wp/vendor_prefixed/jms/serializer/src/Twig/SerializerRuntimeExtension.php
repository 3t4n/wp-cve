<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Twig;

use WPPayVendor\Twig\TwigFilter;
use WPPayVendor\Twig\TwigFunction;
/**
 * @author Asmir Mustafic <goetas@gmail.com>
 */
final class SerializerRuntimeExtension extends \WPPayVendor\JMS\Serializer\Twig\SerializerBaseExtension
{
    /**
     * @return TwigFilter[]
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
     */
    public function getFilters()
    {
        return [new \WPPayVendor\Twig\TwigFilter($this->serializationFunctionsPrefix . 'serialize', [\WPPayVendor\JMS\Serializer\Twig\SerializerRuntimeHelper::class, 'serialize'])];
    }
    /**
     * @return TwigFunction[]
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
     */
    public function getFunctions()
    {
        return [new \WPPayVendor\Twig\TwigFunction($this->serializationFunctionsPrefix . 'serialization_context', '\\JMS\\Serializer\\SerializationContext::create')];
    }
}
