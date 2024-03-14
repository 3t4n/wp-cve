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
 * Attribute list class AmphtmlEngineAttrs.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read array $async
 * @property-read array<array<string>> $crossorigin
 * @property-read array<array<string>> $type
 */
final class AmphtmlEngineAttrs extends AttributeList implements Identifiable
{
    /**
     * ID of the attribute list.
     *
     * @var string
     */
    const ID = 'amphtml-engine-attrs';
    /**
     * Array of attributes.
     *
     * @var array<array>
     */
    const ATTRIBUTES = [Attribute::ASYNC => [SpecRule::MANDATORY => \true, SpecRule::VALUE => ['']], Attribute::CROSSORIGIN => [SpecRule::VALUE => ['anonymous']], Attribute::TYPE => [SpecRule::VALUE_CASEI => ['text/javascript']]];
}
