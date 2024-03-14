<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Error;

use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Error;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
/**
 * Error class BaseTagMustPreceedAllUrls.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $format
 * @property-read int $specificity
 */
final class BaseTagMustPreceedAllUrls extends Error
{
    /**
     * Code of the error.
     *
     * @var string
     */
    const CODE = 'BASE_TAG_MUST_PRECEED_ALL_URLS';
    /**
     * Array of spec data.
     *
     * @var array{format: string, specificity?: int}
     */
    const SPEC = [SpecRule::FORMAT => 'The tag \'%1\', which contains URLs, was found earlier in the document than the BASE element.', SpecRule::SPECIFICITY => 90];
}
