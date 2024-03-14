<?php

namespace Memsource\Page;

abstract class AbstractPage
{
    abstract public function initPage();

    abstract public function renderPage();

    protected function adminUrl($path)
    {
        if (is_network_admin()) {
            return network_admin_url($path);
        }

        return admin_url($path);
    }
}
