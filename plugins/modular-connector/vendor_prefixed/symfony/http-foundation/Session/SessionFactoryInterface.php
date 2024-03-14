<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Session;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 * @internal
 */
interface SessionFactoryInterface
{
    public function createSession() : SessionInterface;
}
