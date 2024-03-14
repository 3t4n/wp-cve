<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

interface WADA_Widget_WidgetInterface
{
    public function addIfActiveAndRelevant();
    public function display($returnAsString = false);
}