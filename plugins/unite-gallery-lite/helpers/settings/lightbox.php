<?php


defined('UNITEGALLERY_INC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."lightbox.xml");

$settings->updateSelectToAlignHor("lightbox_textpanel_title_text_align");

