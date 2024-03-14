<?php

namespace WpifyWooDeps\Wpify\Core\Traits;

use Exception;
use WpifyWooDeps\Wpify\Core\Interfaces\CustomFieldsFactoryInterface;
trait CustomFieldsTrait
{
    /** @var CustomFieldsFactoryInterface $custom_fields_factory */
    private $custom_fields_factory;
    /** @var array */
    private $custom_fields = array();
    /**
     * @return CustomFieldsFactoryInterface
     */
    public function get_custom_fields_factory() : CustomFieldsFactoryInterface
    {
        return $this->custom_fields_factory;
    }
    /**
     * @return mixed
     */
    public function get_custom_fields()
    {
        return $this->custom_fields;
    }
    /**
     * @param string $type
     * @param string $entity_name
     *
     * @throws Exception
     */
    protected function init_custom_fields(string $type, string $entity_name)
    {
        $this->custom_fields = $this->custom_fields();
        if (!empty($this->custom_fields)) {
            if (empty($this->custom_fields_factory())) {
                throw new Exception(__('You need to set custom fields factory to register custom fields', 'wpify'));
            }
            /** @var CustomFieldsFactoryInterface $factory */
            $this->custom_fields_factory = $this->plugin->create_component($this->custom_fields_factory(), ['type' => $type, 'entity_name' => $entity_name, 'custom_fields' => $this->custom_fields]);
            $this->custom_fields_factory->init();
        }
    }
    /**
     * Set custom fields for the post type
     * @return array
     */
    public function custom_fields()
    {
        return array();
    }
    /**
     * Set custom fields factory needed for custom fields registration / manipulation
     * @return string
     */
    public function custom_fields_factory() : ?string
    {
        return null;
    }
}
