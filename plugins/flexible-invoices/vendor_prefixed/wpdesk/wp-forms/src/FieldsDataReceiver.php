<?php

namespace WPDeskFIVendor\WPDesk\Forms;

use WPDeskFIVendor\Psr\Container\ContainerInterface;
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
     * @param ContainerInterface $data
     *
     * @return void
     */
    public function update_fields_data(\WPDeskFIVendor\Psr\Container\ContainerInterface $data);
}
