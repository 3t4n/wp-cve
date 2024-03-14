<?php

namespace Dotdigital_WordPress_Vendor;

use Dotdigital_WordPress_Vendor\PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Dotdigital_WordPress_Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Dotdigital_WordPress_Vendor\Symplify\EasyCodingStandard\ValueObject\Set\SetList;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(SetList::PSR_12);
};
