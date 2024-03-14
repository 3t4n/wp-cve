<?php

declare(strict_types=1);

namespace CKPL\Pay;

use CKPL\Pay\Exception\IncompatibilityException;
use CKPL\Pay\Exception\JsonFunctionException;
use function base64_decode;
use function base64_encode;
use function filter_var;
use function function_exists;
use function in_array;
use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use function parse_url;
use function sprintf;
use function str_repeat;
use function str_replace;
use function strlen;
use function strtr;
use const JSON_ERROR_NONE;

/**
 * @param array $array
 * @param bool  $prettyOutput
 * @param bool  $forceObject
 *
 * @throws IncompatibilityException
 *
 * @return string
 */
function json_encode_array(array $array, bool $prettyOutput = false, bool $forceObject = false): string
{
    if (!function_exists('\json_encode')) {
        throw new IncompatibilityException('Missing JSON extension.');
    }

    $options = JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES;

    if ($prettyOutput) {
        $options = $options | JSON_PRETTY_PRINT;
    }

    if ($forceObject) {
        $options = $options | JSON_FORCE_OBJECT;
    }

    return json_encode($array, $options);
}

/**
 * @param string $json
 * @param bool   $throwException
 *
 * @throws JsonFunctionException
 * @throws IncompatibilityException
 *
 * @return array|null
 */
function json_decode_array(string $json, bool $throwException = false): ?array
{
    if (!function_exists('\json_decode')) {
        throw new IncompatibilityException('Missing JSON extension.');
    }

    $result = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);

    if (JSON_ERROR_NONE !== json_last_error()) {
        $result = null;
    }

    if (null === $result && $throwException) {
        throw new JsonFunctionException(
            sprintf('JSON decode failed: %s', json_last_error_msg())
        );
    }

    return $result;
}

/**
 * @param string $url
 *
 * @return bool
 */
function validate_url(string $url): bool
{
    $result = true;

    if (empty($url) || false === filter_var($url, FILTER_VALIDATE_URL)) {
        $result = false;
    }

    if ($result) {
        $url = parse_url($url);

        if (!isset($url['scheme']) || !in_array($url['scheme'], ['https', 'http'])) {
            $result = false;
        }
    }

    return $result;
}

/**
 * @param string $data
 *
 * @return string
 */
function base64url_encode(string $data): string
{
    return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
}

/**
 * @param string $data
 *
 * @return string|null
 */
function base64url_decode(string $data): ?string
{
    $stack = strlen($data) % 4;

    if ($stack) {
        $length = 4 - $stack;
        $data .= str_repeat('=', $length);
    }

    return base64_decode(strtr($data, '-_', '+/'));
}
