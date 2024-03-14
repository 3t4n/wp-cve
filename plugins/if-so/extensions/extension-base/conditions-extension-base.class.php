<?php
namespace IfSo\Addons\Base;
require_once (__DIR__ . '/extension-base.class.php');
require_once (__DIR__ . '/conditions-extension-initializer-base.class.php');
require_once (__DIR__ . '/conditions-extension-main-base.class.php');

class ConditionsExtension extends Extension{
    protected function set_default_initializer(){
        $this->default_initializer = ConditionsExtensionInitializer::class;
    }
}