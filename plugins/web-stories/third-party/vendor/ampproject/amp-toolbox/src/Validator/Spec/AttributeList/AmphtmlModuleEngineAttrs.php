<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\AttributeList;

use Google\Web_Stories_Dependencies\AmpProject\Html\Attribute;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\AttributeList;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Identifiable;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
/**
 * Attribute list class AmphtmlModuleEngineAttrs.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read array $async
 * @property-read array $crossorigin
 * @property-read array $type
 */
final class AmphtmlModuleEngineAttrs extends AttributeList implements Identifiable
{
    /**
     * ID of the attribute list.
     *
     * @var string
     */
    const ID = 'amphtml-module-engine-attrs';
    /**
     * Array of attributes.
     *
     * @var array<array>
     */
    const ATTRIBUTES = [Attribute::ASYNC => [SpecRule::MANDATORY => \true, SpecRule::VALUE => ['']], Attribute::CROSSORIGIN => [SpecRule::MANDATORY => \true, SpecRule::VALUE => ['anonymous']], Attribute::TYPE => [SpecRule::MANDATORY => \true, SpecRule::DISPATCH_KEY => 'NAME_VALUE_DISPATCH', SpecRule::VALUE_CASEI => ['module']]];
}
