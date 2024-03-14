<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;

use Google\Web_Stories_Dependencies\AmpProject\Format;
use Google\Web_Stories_Dependencies\AmpProject\Html\Attribute;
use Google\Web_Stories_Dependencies\AmpProject\Html\Tag as Element;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\AttributeList;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Identifiable;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;
/**
 * Tag class StyleAmpCustomLengthCheck.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read string $specName
 * @property-read bool $unique
 * @property-read string $mandatoryParent
 * @property-read array $attrs
 * @property-read array<string> $attrLists
 * @property-read array<int> $cdata
 * @property-read array<string> $htmlFormat
 * @property-read string $descriptiveName
 */
final class StyleAmpCustomLengthCheck extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'style amp-custom-length-check';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => Element::STYLE, SpecRule::SPEC_NAME => 'style amp-custom-length-check', SpecRule::UNIQUE => \true, SpecRule::MANDATORY_PARENT => Element::HEAD, SpecRule::ATTRS => [Attribute::AMP_CUSTOM_LENGTH_CHECK => [SpecRule::MANDATORY => \true, SpecRule::VALUE => [''], SpecRule::DISPATCH_KEY => 'NAME_DISPATCH'], Attribute::TYPE => [SpecRule::VALUE_CASEI => ['text/css']]], SpecRule::ATTR_LISTS => [AttributeList\NonceAttr::ID], SpecRule::CDATA => [SpecRule::MAX_BYTES => -1], SpecRule::HTML_FORMAT => [Format::AMP, Format::AMP4ADS, Format::AMP4EMAIL], SpecRule::DESCRIPTIVE_NAME => 'style amp-custom-length-check'];
}
