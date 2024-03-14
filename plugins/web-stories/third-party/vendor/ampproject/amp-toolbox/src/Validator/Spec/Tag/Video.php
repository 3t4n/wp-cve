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
use Google\Web_Stories_Dependencies\AmpProject\Protocol;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Identifiable;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;
/**
 * Tag class Video.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read array $attrs
 * @property-read string $specUrl
 * @property-read string $mandatoryAncestor
 * @property-read string $mandatoryAncestorSuggestedAlternative
 * @property-read array<string> $htmlFormat
 */
final class Video extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'VIDEO';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => Element::VIDEO, SpecRule::ATTRS => [Attribute::AUTOPLAY => [], Attribute::CONTROLS => [], Attribute::HEIGHT => [], Attribute::LOOP => [], Attribute::MUTED => [SpecRule::DEPRECATION => 'autoplay', SpecRule::DEPRECATION_URL => 'https://amp.dev/documentation/components/amp-video/'], Attribute::PLAYSINLINE => [], Attribute::POSTER => [], Attribute::PRELOAD => [], Attribute::SRC => [SpecRule::DISALLOWED_VALUE_REGEX => '__amp_source_origin', SpecRule::VALUE_URL => [SpecRule::PROTOCOL => [Protocol::DATA, Protocol::HTTPS], SpecRule::ALLOW_RELATIVE => \false]], Attribute::WIDTH => []], SpecRule::SPEC_URL => 'https://amp.dev/documentation/components/amp-video/', SpecRule::MANDATORY_ANCESTOR => Element::NOSCRIPT, SpecRule::MANDATORY_ANCESTOR_SUGGESTED_ALTERNATIVE => Extension::VIDEO, SpecRule::HTML_FORMAT => [Format::AMP]];
}
