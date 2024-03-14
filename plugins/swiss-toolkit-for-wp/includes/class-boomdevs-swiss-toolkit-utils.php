<?php

if (!class_exists('BST_Utils_Configuration')) {
    class BST_Utils_Configuration
    {
        public static function isProActivated()
        {

            global $bst_pro_license;
            if ($bst_pro_license) {
                return $bst_pro_license->is_valid();
            }

            return false;
        }
    }
}
