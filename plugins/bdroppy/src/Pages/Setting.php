<?php


namespace BDroppy\Pages;



use BDroppy\Pages\EndPoints\CatalogEndPoints;

class Setting extends BasePage
{

    public function loadTheme()
    {
        require "Template/Setting/index.php";
    }

    public function getScript(): array
    {

        return [
            ['name' => 'setting'],
        ];
    }

    public function getStyle(): array
    {

        return  [[
            'name' => 'dashboard'
        ]];
    }
}