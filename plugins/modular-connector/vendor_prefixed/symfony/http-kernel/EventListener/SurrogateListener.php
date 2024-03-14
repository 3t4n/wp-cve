<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\HttpKernel\EventListener;

use Modular\ConnectorDependencies\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Modular\ConnectorDependencies\Symfony\Component\HttpKernel\Event\ResponseEvent;
use Modular\ConnectorDependencies\Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Modular\ConnectorDependencies\Symfony\Component\HttpKernel\HttpCache\SurrogateInterface;
use Modular\ConnectorDependencies\Symfony\Component\HttpKernel\KernelEvents;
/**
 * SurrogateListener adds a Surrogate-Control HTTP header when the Response needs to be parsed for Surrogates.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 * @internal
 */
class SurrogateListener implements EventSubscriberInterface
{
    private $surrogate;
    public function __construct(?SurrogateInterface $surrogate = null)
    {
        $this->surrogate = $surrogate;
    }
    /**
     * Filters the Response.
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $kernel = $event->getKernel();
        $surrogate = $this->surrogate;
        if ($kernel instanceof HttpCache) {
            $surrogate = $kernel->getSurrogate();
            if (null !== $this->surrogate && $this->surrogate->getName() !== $surrogate->getName()) {
                $surrogate = $this->surrogate;
            }
        }
        if (null === $surrogate) {
            return;
        }
        $surrogate->addSurrogateControl($event->getResponse());
    }
    public static function getSubscribedEvents() : array
    {
        return [KernelEvents::RESPONSE => 'onKernelResponse'];
    }
}
