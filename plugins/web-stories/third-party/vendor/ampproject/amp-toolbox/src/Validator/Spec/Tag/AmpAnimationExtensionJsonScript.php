<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;

use Google\Web_Stories_Dependencies\AmpProject\Extension;
use Google\Web_Stories_Dependencies\AmpProject\Format;
use Google\Web_Stories_Dependencies\AmpProject\Html\Attribute;
use Google\Web_Stories_Dependencies\AmpProject\Html\Tag as Element;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\AttributeList;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Identifiable;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;
/**
 * Tag class AmpAnimationExtensionJsonScript.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read string $specName
 * @property-read string $mandatoryParent
 * @property-read array<array> $attrs
 * @property-read array<string> $attrLists
 * @property-read array<array<array<string>>> $cdata
 * @property-read array<string> $htmlFormat
 * @property-read array<string> $satisfies
 * @property-read array<string> $requiresExtension
 */
final class AmpAnimationExtensionJsonScript extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'amp-animation extension .json script';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => Element::SCRIPT, SpecRule::SPEC_NAME => 'amp-animation extension .json script', SpecRule::MANDATORY_PARENT => Extension::ANIMATION, SpecRule::ATTRS => [Attribute::TYPE => [SpecRule::MANDATORY => \true, SpecRule::DISPATCH_KEY => 'NAME_VALUE_PARENT_DISPATCH', SpecRule::VALUE_CASEI => ['application/json']]], SpecRule::ATTR_LISTS => [AttributeList\NonceAttr::ID], SpecRule::CDATA => [SpecRule::DISALLOWED_CDATA_REGEX => [[SpecRule::REGEX => '<!--', SpecRule::ERROR_MESSAGE => 'html comments']]], SpecRule::HTML_FORMAT => [Format::AMP, Format::AMP4ADS], SpecRule::SATISFIES => ['amp-animation extension .json script'], SpecRule::REQUIRES_EXTENSION => [Extension::ANIMATION]];
}
