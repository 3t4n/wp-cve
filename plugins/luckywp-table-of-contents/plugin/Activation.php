<?php

namespace luckywp\tableOfContents\plugin;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

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
