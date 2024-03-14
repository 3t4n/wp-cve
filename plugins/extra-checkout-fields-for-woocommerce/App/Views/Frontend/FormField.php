<?php

namespace ECFFW\App\Views\Frontend;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class FormField
{
    /**
     * Form Field construct.
     */
    public function __construct()
    {
        add_filter('woocommerce_form_field_ecffw_header', array($this, 'element'), 10, 4);
        add_filter('woocommerce_form_field_ecffw_paragraph', array($this, 'element'), 10, 4);
        add_filter('woocommerce_form_field_ecffw_select', array($this, 'select'), 10, 4);
    }

    /**
     * Header and Paragraph Element.
     */
    public static function element($field, $key, $args, $value)
    {
        $id = esc_attr($args['id']) . '_field';
        $sort = $args['priority'] ? $args['priority'] : '';
        
        $field = '<div class="form-row form-row-wide" id="' . $id . '" data-priority="' . esc_attr($sort) . '">';
        $field .= '<' . esc_attr($args['tag']) . ' class="' . esc_attr(implode(' ', $args['label_class'])) . '">';
        $field .= esc_html($args['content']);
        $field .= '</' . esc_attr($args['tag']) . '>';
        $field .= '</div>';
        
        return $field;
    }

    /**
     * Select Field.
     */
    public function select($field, $key, $args, $value)
    {
        $field = '';
        $options = '';
		if (!empty($args['options'])) {
            if (!empty($args['placeholder'])) {
                $options .= '<option value="" selected="selected" disabled="disabled">' . esc_html($args['placeholder']) . '</option>';
            }
            
            foreach ($args['options'] as $option) {
                $selected =  $option['selected'] ? 'selected="selected"' : '';
                $options .= '<option value="' . esc_attr($option['value']) . '" ' . $selected  . '>' . esc_html($option['label']) . '</option>';
            }

            $field .= '<select name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="select ' . esc_attr(implode(' ', $args['input_class'])) . '">' . $options . '</select>';
        }

        return self::warp($field, $args);
    }

    /**
     * HTML Warp for Fields.
     */
    public static function warp($field, $args)
    {
        if (!empty($field)) {
            $field_html = '';
            $label_id = $args['id'];
            $sort = $args['priority'] ? $args['priority'] : '';
            $field_container = '<p class="form-row %1$s" id="%2$s" data-priority="' . esc_attr($sort) . '">%3$s</p>';
            
            if ($args['required']) {
                $args['class'][] = 'validate-required';
                $required = '&nbsp;<abbr class="required" title="' . esc_attr__('required', 'woocommerce') . '">*</abbr>';
            } else {
                $required = '&nbsp;<span class="optional">(' . esc_html__('optional', 'woocommerce') . ')</span>';
            }

            if ($args['label'] && 'checkbox' !== $args['type']) {
                $field_html .= '<label for="' . esc_attr($label_id) . '" class="' . esc_attr(implode(' ', $args['label_class'])) . '">' . wp_kses_post($args['label']) . $required . '</label>';
            }
            $field_html .= '<span class="woocommerce-input-wrapper">' . $field;

            if ($args['description']) {
                $field_html .= '<span class="description" id="' . esc_attr($args['id']) . '-description" aria-hidden="true">' . wp_kses_post($args['description']) . '</span>';
            }
            $field_html .= '</span>';

            $container_class = esc_attr(implode(' ', $args['class']));
            $container_id = esc_attr($args['id']) . '_field';
            return sprintf($field_container, $container_class, $container_id, $field_html);
        }

        return '';
    }
}
