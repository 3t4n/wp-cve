<?php

namespace luckywp\tableOfContents\admin\widgets\customizeSuccess;

use luckywp\tableOfContents\core\base\Widget;

class CustomizeSuccess extends Widget
{

    /**
     * @var string
     */
    public $after = '';

    public function run()
    {
        return $this->render('widget', [
            'after' => $this->after,
        ]);
    }
}
