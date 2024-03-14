<?php

namespace luckywp\glossary\core\wp;

use luckywp\glossary\core\base\BaseObject;
use luckywp\glossary\core\Core;

class RewriteRules extends BaseObject
{

    public function init()
    {
        parent::init();
        add_action('wp_loaded', function () {
            if (Core::$plugin->options->get('flushRewriteRules')) {
                Core::$plugin->options->delete('flushRewriteRules');
                flush_rewrite_rules();
            }
        });
    }

    public static function flushAfterReload()
    {
        Core::$plugin->options->set('flushRewriteRules', true);
    }
}
