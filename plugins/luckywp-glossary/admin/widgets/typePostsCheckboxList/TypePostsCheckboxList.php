<?php

namespace luckywp\glossary\admin\widgets\typePostsCheckboxList;

use luckywp\glossary\core\base\Widget;
use luckywp\glossary\core\Core;
use luckywp\glossary\core\helpers\Html;

class TypePostsCheckboxList extends Widget
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var
     */
    public $value;

    /**
     * @var string
     */
    public $desc;

    /**
     * @var bool
     */
    public $fake = false;

    public function run()
    {
        $html = '';

        // Отмеченные типы постов
        $selected = is_array($this->value) ? $this->value : [];

        // Типы постов
        $html .= Html::hiddenInput($this->name);
        foreach (Core::$plugin->postTypes as $postType) {
            $html .= '<p>';
            $name = $this->name . '[]';
            $options = [
                'label' => $postType->label,
                'value' => $postType->name,
            ];
            if ($this->fake) {
                $name = '';
                $options['label'] = '<span class="lwpglsColorMuted">' . $options['label'] . '</span>';
                $options['class'] = 'lwpglsColorMuted';
                $options['disabled'] = true;
            }
            $html .= Html::checkbox($name, in_array($postType->name, $selected), $options);
            $html .= '</p>';
        }

        // Описание
        if ($this->desc) {
            $html .= '<p class="description' . ($this->fake ? ' lwpglsColorMuted' : '') . '">' . $this->desc . '</p>';
        }

        return $html;
    }
}
