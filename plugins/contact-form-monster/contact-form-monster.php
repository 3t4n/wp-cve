<?php
/**
 * Plugin Name: Contact Form Monster 
 * Description: Contact Form Monster Forms plugin.
 * Version: 1.0.0
 * Author: Felix Moira
 * Author URI: 
 * License: GPLv2
 */
 
require_once(dirname(__FILE__).'/config.php');
require_once(YCF_CLASSES."ContactForm.php");

$contactObj = new ContactForm();