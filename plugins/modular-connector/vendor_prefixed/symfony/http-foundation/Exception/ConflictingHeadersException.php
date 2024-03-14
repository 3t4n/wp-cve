<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Exception;

/**
 * The HTTP request contains headers with conflicting information.
 *
 * @author Magnus Nordlander <magnus@fervo.se>
 * @internal
 */
class ConflictingHeadersException extends \UnexpectedValueException implements RequestExceptionInterface
{
}
