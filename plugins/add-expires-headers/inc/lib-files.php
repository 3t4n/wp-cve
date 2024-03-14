<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
/*
 *Getting Minify Require Files from Library
*/
$path = AEH_DIR. 'libs/matthiasmullie';
require_once $path . '/minify/src/Minify.php';
require_once $path . '/minify/src/CSS.php';
require_once $path . '/minify/src/JS.php';
require_once $path . '/minify/src/Exception.php';
require_once $path . '/minify/src/Exceptions/BasicException.php';
require_once $path . '/minify/src/Exceptions/FileImportException.php';
require_once $path . '/minify/src/Exceptions/IOException.php';
require_once $path . '/path-converter/src/ConverterInterface.php';
require_once $path . '/path-converter/src/Converter.php';
# use HTML minification
require_once (AEH_DIR. 'libs/mrclay/HTML.php');
