<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/* Weaver Xtreme - admin Main Options
 *
 *  __ added: 12/9/14
 * This function will start the main sapi form, which will be closed in admin-adminopts
 */

// ======================== Main Options > Top Level ========================
function weaverx_admin_mainopts(): void
{

    require_once(dirname(__FILE__) . '/admin-mainopts4.php');
    weaverx_admin_mainopts4();
}
