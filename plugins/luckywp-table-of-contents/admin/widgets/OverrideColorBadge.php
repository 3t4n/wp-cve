<?php

namespace luckywp\tableOfContents\admin\widgets;

use luckywp\tableOfContents\core\base\Widget;

class OverrideColorBadge extends Widget
{

    /**
     * @var string
     */
    public $color;

    public function run()
    {
        if ($this->color) {
            $ico = '<b style="background:' . $this->color . '"></b>';
            return '<span class="lwptocColorBadge">' . (is_rtl() ? ($this->color . $ico) : ($ico . $this->color)) . '</span>';
        }
        return esc_html__('from scheme', 'luckywp-table-of-contents');
    }
}
