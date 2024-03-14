<?php
$item = (!empty($item)) ? $item : $parent;
echo (!empty($column_provider) && is_object($column_provider)) ? $column_provider->get_item_columns($item, $columns) : '';