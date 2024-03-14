<?php

namespace luckywp\tableOfContents\admin\widgets\widget;

use luckywp\tableOfContents\plugin\Shortcode;

class Widget extends \luckywp\tableOfContents\core\base\Widget
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $inputName;

    /**
     * @var string
     */
    public $value;

    /**
     * @var array
     */
    public $instance;

    public function run()
    {
        return $this->render('form', [
            'id' => $this->id,
            'inputName' => $this->inputName,
            'value' => $this->value,
            'attrs' => Shortcode::attrsFromJson($this->value),
        ]);
    }

    /**
     * @param array $attrs
     * @return string
     */
    public static function overrideHtml($attrs)
    {
        return (new self())->render('_override', [
            'attrs' => $attrs,
        ]);
    }
}
