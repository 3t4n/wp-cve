<?php
$directoryNames = explode('/', plugin_basename( __FILE__ ));
define("YCF_MAIN_FILE", $directoryNames[0].".php");
define("YCF_PLUGIN_PREFIX", $directoryNames[0]);
define("YCF_PATH", dirname(__FILE__).'/');
define("YCF_CLASSES", YCF_PATH."classes/");
define("YCF_CLASSES_FORM", YCF_PATH."classes/form/");
define("YCF_FILES", YCF_PATH."files/");
define("YCF_CSS_PATH", YCF_PATH."css/");
define("YCF_VIEWS", YCF_PATH."views/");
define("YCF_JAVASCRIPT_PATH", YCF_PATH."js/");
define('YCF_URL', plugins_url('', __FILE__).'/');
define("YCF_JAVASCRIPT", YCF_URL."js/");
define("YCF_CSS_URL", YCF_URL."css/");
define("YCF_IMG_URL", YCF_URL."img/");
define("YCF_VERSION", 1.00);

