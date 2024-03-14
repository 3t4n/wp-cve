<?php

namespace luckywp\tableOfContents\admin\widgets\fontSizeField;

use luckywp\tableOfContents\core\base\Widget;
use luckywp\tableOfContents\core\Core;

class FontSizeField extends Widget
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $value;

    /**
     * @var int|float
     */
    public $defaultSize = 100;

    public function run()
    {
        $type = 'default';
        $size = $this->defaultSize;
        $unit = '%';

        $value = Core::$plugin->settings->prepareFontSize((string)$this->value, $matches);
        if ($value !== 'default') {
            $type = 'custom';
            $size = $matches[1];
            $unit = $matches[2];
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
