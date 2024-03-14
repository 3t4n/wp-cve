<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/* Weaver Xtreme - admin Save/Restore
 *  __ added - 12/10/14
 * This will come after the Options form has been closed, and is used for non-SAPI options
 *
 */

function weaverx_ts_admin_saverestore(): void
{
    require_once(dirname(__FILE__) . '/admin-saverestore4.php');
    weaverx_ts_admin_saverestore4();      // Legacy version
}
