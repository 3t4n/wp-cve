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
 * Tag class AmpCarouselLightboxChild.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read string $specName
 * @property-read array<array<string>> $attrs
 * @property-read array<string> $htmlFormat
 * @property-read string $descriptiveName
 */
final class AmpCarouselLightboxChild extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'AMP-CAROUSEL lightbox [child]';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => '$REFERENCE_POINT', SpecRule::SPEC_NAME => 'AMP-CAROUSEL lightbox [child]', SpecRule::ATTRS => [Attribute::LIGHTBOX_THUMBNAIL_ID => [SpecRule::VALUE_REGEX_CASEI => '^[a-z][a-z\\d_-]*']], SpecRule::HTML_FORMAT => [Format::AMP], SpecRule::DESCRIPTIVE_NAME => 'amp-carousel lightbox [lightbox-thumbnail-id] child'];
}
