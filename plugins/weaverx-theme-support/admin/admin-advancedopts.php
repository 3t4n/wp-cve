<?php
/** @noinspection PhpUndefinedConstantInspection */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/* Weaver Xtreme - admin Advanced Options
 *
 *  __ added: 12/9/14
 */

function weaverx_admin_advancedopts(): void
{
    require_once(dirname(__FILE__) . '/admin-advancedopts4.php');
    weaverx_admin_advancedopts4();      // Legacy version
}

