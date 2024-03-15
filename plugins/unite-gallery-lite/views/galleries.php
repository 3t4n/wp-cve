<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


	$objGalleries = new UniteGalleryGalleries();
	$arrGalleryTypes = $objGalleries->getArrGalleryTypesShort();
	$arrGalleries = $objGalleries->getArrGalleries("title");
	$headerTitle = __("Galleries", "unitegallery");
	
	require HelperUG::getPathTemplate("galleries");
?>