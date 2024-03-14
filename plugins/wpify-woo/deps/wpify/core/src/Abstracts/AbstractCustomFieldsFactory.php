<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WpifyWooDeps\Wpify\Core\Interfaces\CustomFieldsFactoryInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractCustomFieldsFactory extends AbstractComponent implements CustomFieldsFactoryInterface
{
    /** @var string $type Type of the custom fields (cpt, taxonomy, etc.) */
    private $type;
    /** @var string $entity_name Name of the ctp, taxonomy, etc. */
    private $entity_name;
    /** @var array $custom_fields Array of the custom fields */
    private $custom_fields = array();
    /**
     * CustomFieldsFactory constructor.
     *
     * @param string $type
     * @param string $entity_name
     * @param array  $custom_fields
     */
    public function __construct(string $type, string $entity_name, array $custom_fields)
    {
        $this->set_type($type);
        $this->set_entity_name($entity_name);
        $this->set_custom_fields($custom_fields);
    }
    public function get_type() : string
    {
        return $this->type;
    }
    public function set_type(string $type) : void
    {
        $this->type = $type;
    }
    public function get_entity_name() : string
    {
        return $this->entity_name;
    }
    public function set_entity_name(string $entity_name) : void
    {
        $this->entity_name = $entity_name;
    }
    public function get_custom_fields() : array
    {
        return $this->custom_fields;
    }
    public function set_custom_fields(array $custom_fields) : void
    {
        $this->custom_fields = $custom_fields;
    }
}
