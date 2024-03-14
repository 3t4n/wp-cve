<?php

namespace AOP\App\Options\Fields;

use AOP\App\Validation;

/**
 * Trait TraitValidation
 *
 * @package AOP\App\Options\Fields
 */
trait TraitValidation
{
    /**
     * @return array
     */
    private function allowedHtmlTextField()
    {
        return [
            'a' => [
                'href' => [],
                'title' => [],
                'class' => [],
                'target' => [],
                'rel' => []
            ],
            'abbr' => [
                'title' => []
            ],
            'b' => [],
            'br' => [],
            'em' => [],
            's' => [],
            'strike' => [],
            'strong' => [],
            'pre' => []
        ];
    }

    /**
     * @return string[]
     */
    private function allowedProtocols()
    {
        return ['http', 'https'];
    }

    /**
     * @param $value
     *
     * @return string
     */
    private function validatedTextField($value)
    {
        $validatedValue = wp_kses(
            preg_replace('/<(script|style)>.*?<\/\1>/', '', $value),
            $this->allowedHtmlTextField(),
            $this->allowedProtocols()
        );

        if ($validatedValue !== $value) {
            if (empty($validatedValue)) {
                $message = __('The value on this settings field is not allowed.', 'admin-options-pages');
            } else {
                $message = __('Some of the value on this settings field is not allowed.', 'admin-options-pages');
            }

            add_settings_error(
                $this->settingsName,
                'aop-textfield',
                sprintf(
                    '%1$s <code><label for="%2$s">%2$s</label></code>',
                    $message,
                    $this->settingsName
                ),
                'error'
            );
        }

        return $validatedValue;
    }

    /**
     * @param $value
     *
     * @return string
     */
    private function validatedColor($value)
    {
        if (substr($value, 0, 1) !== '#') {
            $value = '#' . $value;
        }

        $validatedValue = (new Validation)->isColorHex($value) ? $value : $this->optionValue;

        if ($validatedValue !== $value) {
            $message = __('This is not a correct formatted Hex color.', 'admin-options-pages');

            add_settings_error(
                $this->settingsName,
                'aop-colorpicker',
                sprintf(
                    '%1$s <code><label for="%2$s">%2$s</label></code>',
                    $message,
                    $this->settingsName
                ),
                'error'
            );
        }

        return $validatedValue;
    }
}
