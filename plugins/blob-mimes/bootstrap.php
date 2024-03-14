<?php
/**
 * Lord of the Files: Bootstrap
 *
 * This file is only loaded if the server meets this plugin's minimum
 * requirements, allowing us to *not* explode ancient environments.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

use blobfolio\wp\bm\admin;

// Do not execute directly.
if (! \defined('ABSPATH')) {
	exit;
}

// Our autoloader.
require \LOTF_BASE_PATH . '/lib/autoload.php';

// Register hooks.
admin::init();
