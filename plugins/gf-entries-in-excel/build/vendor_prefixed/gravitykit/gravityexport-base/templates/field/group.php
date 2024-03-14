<?php
/**
 * Group template that renders all fields in the same spot.
 * @since $ver$
 * @var mixed[] $fields
 * @var GFExcel\Addon\AddonInterface $this
 */
echo '<div>';
foreach ($fields as $field) {
    $this->single_setting($field);
}
echo '</div>';
