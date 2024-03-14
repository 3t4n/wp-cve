<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Support;

use Modular\ConnectorDependencies\Illuminate\Support\Str;
/** @internal */
class Builder
{
    /**
     * Get required extra fields for Builder element
     *
     * @param $str
     * @param string $before
     * @param string $after
     *
     * @return array
     */
    public static function getExtraFields(bool $css_editor = \true)
    {
        $fields = [['type' => 'el_id', 'heading' => \__('Element ID', 'ares'), 'param_name' => 'el_id', 'description' => \sprintf(\__('Enter element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'ares'), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>'), 'value' => 'id-' . Str::random(8), 'save_always' => \true], ['type' => 'textfield', 'heading' => \__('Extra class name', 'ares'), 'param_name' => 'el_class', 'description' => \__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ares'), 'save_always' => \true]];
        if ($css_editor) {
            $fields[] = ['type' => 'css_editor', 'heading' => \__('CSS box', 'ares'), 'param_name' => 'css', 'group' => \__('Design Options', 'ares'), 'save_always' => \true];
        }
        $fields[] = ['type' => 'animation_style', 'heading' => \__('Animation Style', 'ares'), 'param_name' => 'animation', 'description' => \__('Choose your animation style', 'ares'), 'admin_label' => \true, 'weight' => 0, 'group' => 'Animation', 'save_always' => \true];
        return $fields;
    }
}
