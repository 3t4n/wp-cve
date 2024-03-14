<?php

namespace luckywp\tableOfContents\integrations;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

class RankMath extends BaseObject
{

    public function init()
    {
        add_filter('rank_math/researches/toc_plugins', function ($plugins) {
            $plugins['luckywp-table-of-contents/luckywp-table-of-contents.php'] = Core::$plugin->getName();
            return $plugins;
        });
    }
}
