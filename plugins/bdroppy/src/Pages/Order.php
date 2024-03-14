<?php


namespace BDroppy\Pages;




class Order extends BasePage
{

    public function loadTheme()
    {
        require "Template/Order/index.php";
    }

    public function getScript(): array
    {

        return [
            ['name' => 'order'],
        ];
    }

    public function getStyle(): array
    {

        return  [[
            'name' => 'dashboard'
        ]];
    }
}