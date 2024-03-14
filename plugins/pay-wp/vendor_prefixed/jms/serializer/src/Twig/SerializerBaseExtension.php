<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Twig;

use WPPayVendor\Twig\Extension\AbstractExtension;
abstract class SerializerBaseExtension extends \WPPayVendor\Twig\Extension\AbstractExtension
{
    /**
     * @var string
     */
    protected $serializationFunctionsPrefix;
    /**
     * @return string
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
     */
    public function getName()
    {
        return 'jms_serializer';
    }
    public function __construct(string $serializationFunctionsPrefix = '')
    {
        $this->serializationFunctionsPrefix = $serializationFunctionsPrefix;
    }
}
