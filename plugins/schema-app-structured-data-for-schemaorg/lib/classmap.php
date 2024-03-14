<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Description of classmap
 *
 * @author mark
 */

$baseDir = plugin_dir_path( __FILE__ );
$schemaDir = "HunchSchema/";

require_once $baseDir . 'SchemaEditor.php';
require_once $baseDir . 'SchemaServer.php';
require_once $baseDir . 'SchemaSettings.php';
require_once $baseDir . 'SchemaFront.php';
require_once $baseDir . 'SchemaCron.php';
require_once $baseDir . $schemaDir . 'Thing.php';
require_once $baseDir . $schemaDir . 'Page.php';
require_once $baseDir . $schemaDir . 'Post.php';
require_once $baseDir . $schemaDir . 'Author.php';
require_once $baseDir . $schemaDir . 'Search.php';
require_once $baseDir . $schemaDir . 'Category.php';
require_once $baseDir . $schemaDir . 'Blog.php';
require_once $baseDir . $schemaDir . 'Tag.php';