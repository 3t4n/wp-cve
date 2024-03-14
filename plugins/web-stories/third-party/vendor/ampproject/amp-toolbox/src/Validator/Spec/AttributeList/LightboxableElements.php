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
 * Attribute list class LightboxableElements.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read array $lightbox
 * @property-read array<string> $lightboxThumbnailId
 */
final class LightboxableElements extends AttributeList implements Identifiable
{
    /**
     * ID of the attribute list.
     *
     * @var string
     */
    const ID = 'lightboxable-elements';
    /**
     * Array of attributes.
     *
     * @var array<array>
     */
    const ATTRIBUTES = [Attribute::LIGHTBOX => [], Attribute::LIGHTBOX_THUMBNAIL_ID => [SpecRule::VALUE_REGEX_CASEI => '^[a-z][a-z\\d_-]*']];
}
