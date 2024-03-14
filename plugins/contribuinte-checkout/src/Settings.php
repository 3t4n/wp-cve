<?php

namespace Checkout\Contribuinte;

class Settings
{
    /**
     * Settings page name
     * @var string
     */
    private $page = 'contribuintecheckout';

    /**
     * Options group and name
     * @var string
     */
    private $optionsName = 'contribuinte-checkout-options';

    /**
     * Settings constructor.
     * Creates the settings page
     */
    public function __construct()
    {
        //DropdownList items for most settings
        $itemsYesNo = [
            '0' => __('No', 'contribuinte-checkout'),
            '1' => __('Yes', 'contribuinte-checkout')
        ];

        //DropdownList items for error handling
        $itemsErrorHandling = [
            '0' => __('Reject the order and show customer an error', 'contribuinte-checkout'),
            '1' => __('Only show customer an warning message', 'contribuinte-checkout')
        ];

        //Add new settings
        register_setting(
            $this->optionsName,
            $this->optionsName,
            ''
        );

        //Add new section to settings page
        add_settings_section(
            'main_section',
            __('Main Settings', 'contribuinte-checkout'),
            [$this, 'sectionText'],
            $this->page
        );

        //Add text box to change VAT label
        add_settings_field(
            'text_box_vat_field_label',
            __('VAT field label', 'contribuinte-checkout'),
            [$this, 'settingTextBox'],
            $this->page,
            'main_section',
            [
                'id' => 'text_box_vat_field_label',
                'default' => __('VAT', 'contribuinte-checkout')
            ]
        );

        //Add text box to change VAT description
        add_settings_field(
            'text_box_vat_field_description',
            __('VAT field description', 'contribuinte-checkout'),
            [$this, 'settingTextBox'],
            $this->page,
            'main_section',
            [
                'id' => 'text_box_vat_field_description',
                'default' => __('VAT Number', 'contribuinte-checkout')
            ]
        );

        //Show option to make VAT required
        add_settings_field(
            'drop_down_is_required',
            __('VAT is required', 'contribuinte-checkout'),
            [$this, 'settingDropdownRender'],
            $this->page,
            'main_section',
            [
                'id' => 'drop_down_is_required',
                'items' => $itemsYesNo
            ]
        );

        //Show option to make VAT required on sales over 1000€
        add_settings_field(
            'drop_down_required_over_limit_price',
            __('VAT required on orders over 1000€', 'contribuinte-checkout'),
            [$this, 'settingDropdownRender'],
            $this->page,
            'main_section',
            [
                'id' => 'drop_down_required_over_limit_price',
                'items' => $itemsYesNo
            ]
        );

        //Show option to validade VAT
        add_settings_field(
            'drop_down_validate_vat',
            __('Validate VAT', 'contribuinte-checkout'),
            [$this, 'settingDropdownRender'],
            $this->page,
            'main_section',
            [
                'id' => 'drop_down_validate_vat',
                'items' => $itemsYesNo
            ]
        );

        //Choose what to do when VAT valdiation fails
        add_settings_field(
            'drop_down_on_validation_fail',
            __('Failed validation handling', 'contribuinte-checkout'),
            [$this, 'settingDropdownRender'],
            $this->page,
            'main_section',
            [
                'id' => 'drop_down_on_validation_fail',
                'items' => $itemsErrorHandling
            ]
        );

        //Only show vies setting if SoapClient class is available
        if (class_exists('SoapClient')) {
            //Show vies settings to use it or not
            add_settings_field(
                'drop_down_show_vies',
                __('Show VIES information', 'contribuinte-checkout'),
                [$this, 'settingDropdownRender'],
                $this->page,
                'main_section',
                [
                    'id' => 'drop_down_show_vies',
                    'items' => $itemsYesNo
                ]
            );
        }
    }

    /**
     * Adds a custom message to the top of the settings page
     */
    public function sectionText()
    {
        echo '<p>' . __('In the section bellow you can change the VAT input behaviour.', 'contribuinte-checkout') . '</p>';
    }

    /**
     * Renders the dropdown field
     * @param array $args array format: ['id' => 'field unique identifier']
     */
    public function settingDropdownRender($args)
    {
        $options = get_option($this->optionsName);

        echo "<select id='" . $args['id'] . "' name='" . $this->optionsName . "[" . $args['id'] . "]' style='min-width: 330px;'>";

        foreach ($args['items'] as $key => $value) {
            $selected = ($options[$args['id']] === $key) ? 'selected="selected"' : '';
            echo "<option value='$key' $selected> $value </option>";
        }

        echo "</select>";
    }

    /**
     * Renders the textbox field
     * @param $args
     */
    public function settingTextBox($args)
    {
        $options = get_option($this->optionsName);
        if (!empty($options[$args['id']])) {
            $value = $options[$args['id']];
        } else {
            $value = $args['default'];
        }

        echo "<input id='" . $args['id'] . "' name='" . $this->optionsName . "[" . $args['id'] . "]' size='40' type='text' value='" . $value . "' style='min-width: 330px;'/>";
    }

    /**
     * Renders settings page and saves the settings
     */
    public function renderPage()
    {
        //if settings were submitted, save them
        if (isset($_POST[$this->optionsName])) {
            add_settings_error($this->optionsName, 'settings_updated', __('Changes saved.', 'contribuinte-checkout'), 'updated');

            if (get_option($this->optionsName) === false) {
                add_option($this->optionsName, $this->sanitizeSettings($_POST[$this->optionsName]));
            } else {
                update_option($this->optionsName, $this->sanitizeSettings($_POST[$this->optionsName]));
            }
        }

        //Show success messages
        settings_errors();

        ?>
        <div class="wrap">
            <div class="icon32" id="icon-options-general"><br></div>
            <h2><?= __('Contribuinte Checkout', 'contribuinte-checkout') ?></h2>
            <form action="" method="post">
                <?php settings_fields($this->optionsName); ?>
                <?php do_settings_sections($this->page); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Sanitizes the array with the settings
     * @param array $inputArray Post data form settings page
     * @return array
     */
    private function sanitizeSettings($inputArray)
    {
        foreach ($inputArray as $key => $value) {
            if (is_numeric($value)) {
                $inputArray[$key] = (int)sanitize_text_field($value);
            } else {
                $inputArray[$key] = sanitize_text_field($value);
            }
        }

        return $inputArray;
    }
}