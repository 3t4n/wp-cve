<?php

namespace luckywp\tableOfContents\core\wp;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

class Options extends BaseObject
{

    public function get($option, $default = false)
    {
        return get_option(Core::$plugin->prefix . $option, $default);
    }

    public function set($option, $value, $autoload = null)
    {
        return update_option(Core::$plugin->prefix . $option, $value, $autoload);
    }

    public function delete($option)
    {
        delete_option(Core::$plugin->prefix . $option);
    }
}
