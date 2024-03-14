<?php

// no direct access
if(!defined("LAYOUTS_EDITOR_INC"))
	define("LAYOUTS_EDITOR_INC", true);

if(!defined("LAYOUTS_EDITOR_VERSION"))
	define("LAYOUTS_EDITOR_VERSION", "1.1.3");


$currentFile = __FILE__;
$currentFolder = dirname($currentFile);
$folderIncludesMain = $currentFolder."/inc_php/";


//include plugin files
require_once $folderIncludesMain . 'globals.class.php';
require_once $folderIncludesMain . 'admin.class.php';


 
?>