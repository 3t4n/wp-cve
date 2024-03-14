<?php

declare (strict_types=1);
namespace Prokerala\Common\Api\Exception;

final class TokenExpiredException extends \Prokerala\Common\Api\Exception\AuthenticationException implements \Prokerala\Common\Api\Exception\RetryableExceptionInterface
{
}
