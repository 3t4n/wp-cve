<?php

namespace DhlVendor\WPDesk\Forms;

interface FieldRenderer
{
    /** @return string|array String or normalized array */
    public function render_fields(\DhlVendor\WPDesk\Forms\FieldProvider $provider, array $fields_data, string $name_prefix = '');
}
