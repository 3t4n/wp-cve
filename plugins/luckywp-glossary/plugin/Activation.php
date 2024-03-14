<?php

namespace luckywp\glossary\plugin;

use luckywp\glossary\core\base\BaseObject;
use luckywp\glossary\core\Core;

class Activation extends BaseObject
{

    public function init()
    {
        register_activation_hook(Core::$plugin->fileName, [$this, 'activate']);
    }

    public function activate()
    {
        Core::$plugin->settings->install();
    }
}
