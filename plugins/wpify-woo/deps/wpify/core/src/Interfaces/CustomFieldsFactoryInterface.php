<?php

namespace WpifyWooDeps\Wpify\Core\Interfaces;

/**
 * @package Wpify\Core
 */
interface CustomFieldsFactoryInterface
{
    /**
     * Get custom field value
     *
     * @param $id
     * @param $field
     *
     * @return mixed
     */
    public function get_field($id, $field);
    /**
     * Save custom field value
     *
     * @param $id
     * @param $field
     * @param $value
     *
     * @return mixed
     */
    public function save_field($id, $field, $value);
}
