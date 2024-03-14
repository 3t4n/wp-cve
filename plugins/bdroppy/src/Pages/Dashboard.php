<?php


namespace BDroppy\Pages;



use BDroppy\Init\Core;
use BDroppy\Pages\EndPoints\AdminEndPoints;

class Dashboard extends BasePage
{
    public $system;


    public function loadTheme()
    {
        require "Template/Dashboard/index.php";
    }

    public function getScript(): array
    {

        return [[
            'name' => 'dashboard'
        ]];
    }

    public function getStyle(): array
    {

        return  [[
            'name' => 'dashboard'
        ]];
    }
}