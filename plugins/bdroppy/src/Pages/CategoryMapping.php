<?php


namespace BDroppy\Pages;


class CategoryMapping extends BasePage
{

    public function loadTheme()
    {


        require "Template/CategoryMapping/index.php";
    }

    public function getScript(): array
    {

        return [
            ['name' => 'categoryMapping'],
        ];
    }

    public function getStyle(): array
    {

        return  [[
            'name' => 'dashboard'
        ]];
    }
}