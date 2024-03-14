<?php

namespace ShopMagicVendor\WPDesk\Forms;

interface FieldRenderer
{
    /** @return string|array String or normalized array */
    public function render_fields(FieldProvider $provider, array $fields_data, string $name_prefix = '');
}
