<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/* Weaver Xtreme - admin Subtheme
 *
 *  __ added - 12/10/14
 * This is the intro form. It won't have any options because it will be outside the main form
 */

function weaverx_admin_subthemes(): void
{
    require_once(dirname(__FILE__) . '/admin-subthemes4.php');
    weaverx_admin_subthemes4();
}

