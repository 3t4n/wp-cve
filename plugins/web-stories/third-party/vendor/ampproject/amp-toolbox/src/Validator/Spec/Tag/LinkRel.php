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
 * Tag class LinkRel.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read string $specName
 * @property-read array<array> $attrs
 * @property-read array<string> $attrLists
 * @property-read string $specUrl
 * @property-read array<string> $disallowedAncestor
 * @property-read array<string> $htmlFormat
 */
final class LinkRel extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'link rel=';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => Element::LINK, SpecRule::SPEC_NAME => 'link rel=', SpecRule::ATTRS => [Attribute::HREF => [], Attribute::REL => [SpecRule::MANDATORY => \true, SpecRule::DISALLOWED_VALUE_REGEX => '(^|\\s)(canonical|components|import|manifest|modulepreload|preload|serviceworker|stylesheet|subresource)(\\s|$)']], SpecRule::ATTR_LISTS => [AttributeList\CommonLinkAttrs::ID], SpecRule::SPEC_URL => 'https://amp.dev/documentation/guides-and-tutorials/learn/spec/amphtml/#html-tags', SpecRule::DISALLOWED_ANCESTOR => ['TEMPLATE'], SpecRule::HTML_FORMAT => [Format::AMP, Format::AMP4ADS]];
}
