<?php

/**
 * Plugin Name:       Bulk Meta Editor
 * Plugin URI:        https://ariesdajay.com/bulk-meta-editor/
 * Description:       Bulk updates the metadata such as the title, description, canonical url, and the indexing of a page. Created for most Web Developers and SEO Specialists who do website audits. Currently supports Yoast as of the moment.
 * Version:           1.0.1
 * Requires at least: 5.6
 * Requires PHP:      5.6
 * Author:            Aries Dajay
 * Author URI:        https://ariesdajay.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bulk-meta-editor
 * Domain Path:       /languages
 */

/*
Bulk Meta Editor is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Bulk Meta Editor is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Bulk Meta Editor. If not, see {URI to Plugin License}.

*/

if ( !defined('ABSPATH') ) {
    exit("No script kiddies please! :D");
}

define('BME_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('BME_MAIN_FILE', __FILE__);

require BME_PLUGIN_PATH . 'src/bulk-meta-editor.php';
require BME_PLUGIN_PATH . 'src/notices.php';

use BulkMetaEditor\BulkMetaEditor;

new BulkMetaEditor();