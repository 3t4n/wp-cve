<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

interface WADA_Layout_LayoutInterface
{
    public function display($returnAsString = false);
}