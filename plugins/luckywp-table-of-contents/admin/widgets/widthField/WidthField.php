<?php

namespace luckywp\tableOfContents\admin\widgets\widthField;

use luckywp\tableOfContents\core\base\Widget;
use luckywp\tableOfContents\core\Core;

class WidthField extends Widget
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
        $size = 75;
        $unit = '%';

        $value = Core::$plugin->settings->prepareWidth((string)$this->value, $matches);
        if (Core::$plugin->isCustomWidth($value)) {
            $type = 'custom';
            $size = $matches[1];
            $unit = $matches[2];
        } else {
            $type = $value;
        }

        return $this->render('widget', [
            'name' => $this->name,
            'value' => $value,
            'type' => $type,
            'size' => $size,
            'unit' => $unit,
        ]);
    }
}
