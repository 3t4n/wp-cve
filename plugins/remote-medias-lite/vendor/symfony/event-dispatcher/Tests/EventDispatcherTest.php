<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPRemoteMediaExt\Symfony\Component\EventDispatcher\Tests;

use WPRemoteMediaExt\Symfony\Component\EventDispatcher\EventDispatcher;

class EventDispatcherTest extends AbstractEventDispatcherTest
{
    protected function createEventDispatcher()
    {
        return new EventDispatcher();
    }
}
