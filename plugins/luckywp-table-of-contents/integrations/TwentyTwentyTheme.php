<?php

namespace luckywp\tableOfContents\integrations;

use luckywp\tableOfContents\core\base\BaseObject;

class TwentyTwentyTheme extends BaseObject
{

    public function init()
    {
        add_action('lwptoc_before', function ($s) {
            return '<div>' . $s;
        });
        add_action('lwptoc_after', function ($s) {
            return $s . '</div>';
        });
    }
}
