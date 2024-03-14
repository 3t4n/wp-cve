<?php


class RavenBreadcrumbsErrorHandler extends Raven_Breadcrumbs_ErrorHandler
{
    public function install()
    {
        restore_error_handler();
        $this->existingHandler = set_error_handler(array($this, 'handleError'), E_ALL);
        return $this;
    }
}
