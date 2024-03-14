<?php
defined('ABSPATH') || exit;
/**
 *
 * Field: Install_Button
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('CSF_Field_Install_Button')) {
    class CSF_Field_Install_Button extends CSF_Fields
    {
        public function __construct($field, $value = '', $unique = '', $where = '', $parent = '')
        {
            parent::__construct($field, $value, $unique, $where, $parent);
        }

        public function render()
        {
            echo $this->field_before();
            echo '<a class="btn inet-webkit-btn-install" target="_blank" href="' . $this->value . '"> Cài đặt</a>';
            echo $this->field_after();
        }
    }
}