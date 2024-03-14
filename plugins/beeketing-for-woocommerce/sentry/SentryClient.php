<?php

class SentryClient extends Raven_Client
{
    protected function registerDefaultBreadcrumbHandlers()
    {
        $handler = new RavenBreadcrumbsErrorHandler($this);
        $handler->install();
    }
}
