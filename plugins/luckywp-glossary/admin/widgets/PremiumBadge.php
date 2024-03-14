<?php

namespace luckywp\glossary\admin\widgets;

use luckywp\glossary\core\base\Widget;
use luckywp\glossary\core\Core;
use luckywp\glossary\core\helpers\Html;

class PremiumBadge extends Widget
{

    public function run()
    {
        return Html::tag(
            Core::$plugin->buyUrl ? 'a' : 'span',
            esc_html__('Premium', 'luckywp-glossary'),
            [
                'class' => 'lwpglsPremiumBadge',
                'title' => esc_html__('It is available only in the premium version', 'luckywp-glossary'),
                'href' => Core::$plugin->buyUrl ? Core::$plugin->buyUrl : null,
                'target' => Core::$plugin->buyUrl ? '_blank' : null,
            ]
        );
    }
}
