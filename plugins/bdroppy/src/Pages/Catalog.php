<?php


namespace BDroppy\Pages;



use BDroppy\Pages\EndPoints\CatalogEndPoints;

class Catalog extends BasePage
{

    public function loadTheme()
    {
        require "Template/Catalog/index.php";
    }

    public function getScript(): array
    {

        return [
            ['name' => 'catalog'],
        ];
    }

    public function getStyle(): array
    {

        return  [[
            'name' => 'dashboard'
        ]];
    }
}