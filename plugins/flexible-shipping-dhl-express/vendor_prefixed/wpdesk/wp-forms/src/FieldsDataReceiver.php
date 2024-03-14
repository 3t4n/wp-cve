<?php

namespace DhlVendor\WPDesk\Forms;

use DhlVendor\Psr\Container\ContainerInterface;
/**
 * Some field owners can receive and process field data.
 * Probably should be used with FieldProvider interface.
 *
 * @package WPDesk\Forms
 */
interface FieldsDataReceiver
{
    /**
     * Set values corresponding to fields.
     *
     * @return void
     */
    public function update_fields_data(\DhlVendor\Psr\Container\ContainerInterface $data);
}
