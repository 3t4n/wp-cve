<?php

/**
 * Settings container: SettingsDefinitionModifierAfter.
 *
 * @package WPDesk\AbstractShipping\Settings
 */
namespace UpsFreeVendor\WPDesk\AbstractShipping\Settings\DefinitionModifier;

use UpsFreeVendor\WPDesk\AbstractShipping\Exception\SettingsFieldNotExistsException;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
/**
 * Can decorate settings by adding settings field after given field.
 */
class SettingsDefinitionModifierAfter extends \UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition
{
    /**
     * Decorated settings definition.
     *
     * @var SettingsDefinition
     */
    private $decorated_settings_definition;
    /**
     * Field id.
     *
     * @var string
     */
    private $field_id_after;
    /**
     * New field id.
     *
     * @var string
     */
    private $new_field_id;
    /**
     * New field.
     *
     * @var array
     */
    private $new_field;
    /**
     * SettingsDefinitionModifierBefore constructor.
     *
     * @param SettingsDefinition $decorated_settings_definition Decorated settings definition,
     * @param string $field_id_after Field id before which should be settings added.
     * @param string $new_field_id New field id.
     * @param array  $new_field New field.
     */
    public function __construct(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition $decorated_settings_definition, $field_id_after, $new_field_id, array $new_field)
    {
        $this->decorated_settings_definition = $decorated_settings_definition;
        $this->field_id_after = $field_id_after;
        $this->new_field_id = $new_field_id;
        $this->new_field = $new_field;
    }
    /**
     * Returns modified form fields.
     *
     * @param array $form_fields
     *
     * @return array
     *
     * @throws SettingsFieldNotExistsException
     */
    public function get_form_fields()
    {
        $form_fields = $this->decorated_settings_definition->get_form_fields();
        if (isset($form_fields[$this->field_id_after])) {
            $modified_form_fields = [];
            foreach ($form_fields as $field_id => $field) {
                $modified_form_fields[$field_id] = $field;
                if ($field_id === $this->field_id_after) {
                    $modified_form_fields[$this->new_field_id] = $this->new_field;
                }
            }
            return $modified_form_fields;
        }
        throw new \UpsFreeVendor\WPDesk\AbstractShipping\Exception\SettingsFieldNotExistsException(\sprintf('Field %1$s not found in settings!', $this->field_id_after));
    }
    /**
     * Validate settings.
     *
     * @param SettingsValues $settings Settings values.
     *
     * @return bool
     */
    public function validate_settings(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return $this->decorated_settings_definition->validate_settings($settings);
    }
}
