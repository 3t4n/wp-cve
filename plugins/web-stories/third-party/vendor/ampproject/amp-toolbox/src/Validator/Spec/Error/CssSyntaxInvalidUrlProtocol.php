<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Error;

use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Error;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
/**
 * Error class CssSyntaxInvalidUrlProtocol.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $format
 * @property-read int $specificity
 */
final class CssSyntaxInvalidUrlProtocol extends Error
{
    /**
     * Code of the error.
     *
     * @var string
     */
    const CODE = 'CSS_SYNTAX_INVALID_URL_PROTOCOL';
    /**
     * Array of spec data.
     *
     * @var array{format: string, specificity?: int}
     */
    const SPEC = [SpecRule::FORMAT => 'CSS syntax error in tag \'%1\' - invalid url protocol \'%2:\'.', SpecRule::SPECIFICITY => 74];
}
