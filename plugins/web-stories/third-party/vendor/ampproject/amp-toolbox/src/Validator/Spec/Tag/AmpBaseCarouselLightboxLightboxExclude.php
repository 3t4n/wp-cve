<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;

use Google\Web_Stories_Dependencies\AmpProject\Format;
use Google\Web_Stories_Dependencies\AmpProject\Html\Attribute;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Identifiable;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;
/**
 * Tag class AmpBaseCarouselLightboxLightboxExclude.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read string $specName
 * @property-read array<array<bool>> $attrs
 * @property-read array<string> $htmlFormat
 * @property-read string $descriptiveName
 */
final class AmpBaseCarouselLightboxLightboxExclude extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'AMP-BASE-CAROUSEL lightbox [lightbox-exclude]';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => '$REFERENCE_POINT', SpecRule::SPEC_NAME => 'AMP-BASE-CAROUSEL lightbox [lightbox-exclude]', SpecRule::ATTRS => [Attribute::LIGHTBOX_EXCLUDE => [SpecRule::MANDATORY => \true]], SpecRule::HTML_FORMAT => [Format::AMP], SpecRule::DESCRIPTIVE_NAME => 'amp-base-carousel [lightbox-exclude] child'];
}
