<?php

namespace WunderAuto\Settings;

/**
 * Class BaseSettingsPage
 */
class BaseSettings
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $caption;

    /**
     * @var int
     */
    public $sortOrder = 10;

    /**
     * @var array<string, string>
     */
    protected $values;

    /**
     * Default empty base implementation
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Register a tab
     *
     * @return void
     */
    protected function registerTab()
    {
        register_setting(
            $this->id,
            $this->id,
            ['sanitize_callback' => [$this, 'sanitize']] //@phpstan-ignore-line
        );
    }

    /**
     * @param string $sectionId
     * @param string $caption
     *
     * @return void
     */
    protected function addSection($sectionId, $caption)
    {
        add_settings_section(
            $sectionId,
            $caption,
            [$this, 'displaySection'], // @phpstan-ignore-line
            $this->id
        );
    }

    /**
     * @param string $sectionId
     * @param string $fieldId
     * @param string $caption
     *
     * @return void
     */
    protected function addField($sectionId, $fieldId, $caption)
    {
        add_settings_field(
            $fieldId,
            $caption,
            function ($field) use ($fieldId) {
                $this->displayField($fieldId, $field);
            },
            $this->id,
            $sectionId
        );
    }

    /**
     * Base implementation - intentionally left empty
     *
     * @param string $fieldId
     * @param string $field
     *
     * @return void
     */
    protected function displayField($fieldId, $field)
    {
    }

    /**
     * Renders an input field on a settings page/tab
     *
     * @param string                $type
     * @param string                $name
     * @param string                $id
     * @param string|int|null       $defaultValue
     * @param string|null           $description
     * @param array<string, string> $options
     *
     * @return void
     */
    protected function renderField($type, $name, $id, $defaultValue = null, $description = null, $options = [])
    {
        if (!is_array($this->values)) {
            $this->values = get_option($this->id);
        }

        $value = $defaultValue;
        $value = isset($this->values[$id]) ? (string)$this->values[$id] : (string)$value;
        switch ($type) {
            case 'text':
            case 'password':
                $size = isset($options['size']) ? (string)$options['size'] : '25';
                echo sprintf(
                    '<input type="' . $type . '" name="%s" id="%s" value="%s" size="%s">',
                    esc_attr($name),
                    esc_attr($name),
                    esc_attr($value),
                    esc_attr($size)
                );
                break;
            case 'checkbox':
                echo sprintf(
                    '<input type="checkbox" name="%1$s" id="%1$s" value="1" %2$s>',
                    esc_attr($name),
                    $value === '1' ? 'checked' : ''
                );
                if (isset($options['label'])) {
                    echo sprintf(
                        '<label for="%1$s">%2$s</label>',
                        esc_attr($name),
                        esc_html($options['label'])
                    );
                }
                break;
            case 'select':
                echo sprintf(
                    '<select name="%s" id="%s">',
                    esc_attr($name),
                    esc_attr($name)
                );

                foreach ($options as $key => $label) {
                    $selected = $key == $value ? 'selected' : '';
                    echo sprintf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr($key),
                        $selected,
                        esc_html($label)
                    );
                }

                echo "</select>";
                break;
        }

        if (is_string($description) && strlen($description)) {
            echo sprintf(
                '<p class="description">%s</p>',
                esc_html($description)
            );
        }
    }
}
