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
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Identifiable;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;
/**
 * Tag class AmpImageSliderDivSecond.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read string $specName
 * @property-read string $mandatoryParent
 * @property-read array<array<bool>> $attrs
 * @property-read string $specUrl
 * @property-read array<string> $htmlFormat
 */
final class AmpImageSliderDivSecond extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'AMP-IMAGE-SLIDER > DIV [second]';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => Element::DIV, SpecRule::SPEC_NAME => 'AMP-IMAGE-SLIDER > DIV [second]', SpecRule::MANDATORY_PARENT => Extension::IMAGE_SLIDER, SpecRule::ATTRS => [Attribute::SECOND => [SpecRule::MANDATORY => \true]], SpecRule::SPEC_URL => 'https://amp.dev/documentation/components/amp-image-slider/', SpecRule::HTML_FORMAT => [Format::AMP]];
}
