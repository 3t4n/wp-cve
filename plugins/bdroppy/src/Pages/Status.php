<?php


namespace BDroppy\Pages;



use BDroppy\Init\Core;
use BDroppy\Pages\EndPoints\AdminEndPoints;

class Status extends BasePage
{
    public $system;
    public function loadTheme()
    {
        $system = $this->core->getSystem();
        echo "status";
    }

    public function getScript(): array
    {

        return [];
    }

    public function getStyle(): array
    {
        return [];
    }

}