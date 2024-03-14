<?php

namespace luckywp\tableOfContents\admin\widgets;

use luckywp\tableOfContents\core\base\Widget;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use luckywp\tableOfContents\core\helpers\Html;

class PostTypes extends Widget
{

    /**
     * @var array
     */
    public $field;

    /**
     * @var array
     */
    public $containerOptions = [];

    public function run()
    {
        $value = Core::$plugin->settings->getValue($this->field['group'], $this->field['id'], [], false);
        if (!is_array($value)) {
            $value = [];
        }

        // Типы постов
        $postTypes = ArrayHelper::map(Core::$plugin->postTypes, 'name', 'labels.singular_name');

        // HTML
        $html = Html::beginTag('div', $this->containerOptions);
        $html .= Html::hiddenInput($this->field['name']);
        foreach ($postTypes as $postName => $postLabel) {
            $options = [
                'label' => $postLabel,
                'value' => $postName,
            ];
            $html .= '<p>' . Html::checkbox($this->field['name'] . '[]', in_array($postName, $value), $options) . '</p>';
        }
        $html .= '</div>';
        return $html;
    }
}
