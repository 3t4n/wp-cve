<?php

namespace AOP\App\Options;

use AOP\App\Options\Fields\Image;
use AOP\App\Options\Fields\Number;
use AOP\App\Options\Fields\Select;
use AOP\App\Options\Fields\Checkbox;
use AOP\App\Options\Fields\Subtitle;
use AOP\App\Options\Fields\Textarea;
use AOP\App\Options\Fields\TextField;
use AOP\App\Options\Fields\ColorPicker;
use AOP\App\Options\Fields\Description;
use AOP\App\Options\Fields\RadioButton;
use AOP\App\Options\Fields\HorizontalRule;
use AOP\App\Options\Fields\WysiwygEditor;
use AOP\Lib\Illuminate\Support\Collection;

class OptionsFields
{
    /**
     * @param $fields
     * @return Collection
     */
    public function run($fields)
    {
        return Collection::make($fields)->filter(function ($field) {
            return isset($field['type']);
        })->map(function ($field) {
            ($field['type'] === 'image') ? new Image($field) : null;
            ($field['type'] === 'title') ? new Subtitle($field) : null;
            ($field['type'] === 'radio') ? new RadioButton($field) : null;
            ($field['type'] === 'select_field') ? new Select($field) : null;
            ($field['type'] === 'number') ? new Number($field) : null;
            ($field['type'] === 'checkbox') ? new Checkbox($field) : null;
            ($field['type'] === 'textarea') ? new Textarea($field) : null;
            ($field['type'] === 'text_field') ? new TextField($field) : null;
            ($field['type'] === 'description') ? new Description($field) : null;
            ($field['type'] === 'color_picker') ? new ColorPicker($field) : null;
            ($field['type'] === 'wysiwyg_editor') ? new WysiwygEditor($field) : null;
            ($field['type'] === 'horizontal_rule') ? new HorizontalRule($field) : null;

        });
    }
}
