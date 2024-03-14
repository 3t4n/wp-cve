<?php

namespace luckywp\tableOfContents\admin\widgets\skipHeadingLevelField;

use luckywp\tableOfContents\core\base\Widget;
use luckywp\tableOfContents\core\Core;

class SkipHeadingLevelField extends Widget
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $value;

    public function run()
    {
        return $this->render('field', [
            'name' => $this->name,
            'value' => Core::$plugin->skipHeadingLevelToArray($this->value),
            'items' => Core::$plugin->getHeadingsList(),
        ]);
    }
}
