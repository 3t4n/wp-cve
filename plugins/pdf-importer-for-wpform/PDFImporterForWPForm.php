<?php

/**
 * Plugin Name: PDF Importer for WPForm
 * Plugin URI: http://smartforms.rednao.com/getit
 * Description: Import a pdf and fill it with your entry information.
 * Author: RedNao
 * Author URI: http://rednao.com
 * Version: 1.3.54
 * Text Domain: rednaopdfimporter
 * Domain Path: /languages/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Slug: pdf-importer-for-wpform
 */

use rnpdfimporter\api\PDFImporterApi;
use rnpdfimporter\core\Integration\Adapters\WPForm\Loader\WPFormSubLoader;
use rnpdfimporter\core\Loader;
require_once dirname(__FILE__).'/AutoLoad.php';

new WPFormSubLoader('rnpdfimporter','rednaopdfimpwpform',26,12,basename(__FILE__),array(
    'ItemId'=>16,
    'Author'=>'Edgar Rojas',
    'UpdateURL'=>'https://formwiz.rednao.com',
    'FileGroup'=>'PDFImporterForWPForm'
));




if(!function_exists('RNPDFImporter'))
{
    function RNPDFImporter()
    {
        global $RNPDFImporter;
        if ($RNPDFImporter == null)
        {
            $RNPDFImporter = new PDFImporterApi(Loader::$Loader);
        }
        return $RNPDFImporter;
    }
}