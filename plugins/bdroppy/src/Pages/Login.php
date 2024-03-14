<?php


namespace BDroppy\Pages;

use BDroppy\Init\Core;
use BDroppy\Pages\EndPoints\AdminEndPoints;
use BDroppy\Pages\EndPoints\LoginEndPoints;

class Login extends BasePage
{
    protected $core;
    protected $config;


    public function loadEndPoints()
    {
        new LoginEndPoints($this->core);

    }

    public function loadTheme()
    {
        require "Template/Login/index.php";
    }

    public function getScript(): array
    {
       return  [[
           'name' => 'login',
       ]];
    }

    public function getStyle(): array
    {
        return [['name'=>'login']];
    }
}