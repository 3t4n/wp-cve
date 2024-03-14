<?php
/**
 * Plugin Name: PDF Builder for WPForms
 * Plugin URI: http://smartforms.rednao.com/getit
 * Description: The first and only PDF drag and drop builder for WPForms
 * Author: RedNao
 * Author URI: http://rednao.com
 * Version: 1.2.98
 * Text Domain: pdf_builder
 * Domain Path: /languages/
 * Network: true
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Slug: pdf-for-wpforms
 */


if(function_exists('rednaoformpdfbuilder'))
    die('Looks like you already have a version of the plugin installed (perhaps the free version)? please deactivate/delete it before activating this version ');

use rednaoformpdfbuilder\Integration\Adapters\WPForm\Loader\WPFormSubLoader;

require_once plugin_dir_path(__FILE__).'autoload.php';
$loader=new WPFormSubLoader(__FILE__,array(
    'ItemId'=>12,
    'Author'=>'Edgar Rojas',
    'UpdateURL'=>'https://formwiz.rednao.com',
    'FileGroup'=>'PDFBuilderWPForm'
));

/**
 * @return \rednaoformpdfbuilder\api\PDFBuilderApi
 */
function RNPDFBuilder(){
    global $RNPDFBuilder;
    if($RNPDFBuilder==null)
    {
        $loader=null;
        $loader=apply_filters('pdfbuilder_get_loader',$loader);
        if($loader==null)
            return null;
        $RNPDFBuilder = new \rednaoformpdfbuilder\api\PDFBuilderApi($loader);
    }
    return $RNPDFBuilder;
}

