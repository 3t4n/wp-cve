<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Error;

use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Error;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
/**
 * Error class TagNotAllowedToHaveSiblings.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $format
 * @property-read int $specificity
 */
final class TagNotAllowedToHaveSiblings extends Error
{
    /**
     * Code of the error.
     *
     * @var string
     */
    const CODE = 'TAG_NOT_ALLOWED_TO_HAVE_SIBLINGS';
    /**
     * Array of spec data.
     *
     * @var array{format: string, specificity?: int}
     */
    const SPEC = [SpecRule::FORMAT => 'Tag \'%1\' is not allowed to have any sibling tags (\'%2\' should only have 1 child).', SpecRule::SPECIFICITY => 109];
}
