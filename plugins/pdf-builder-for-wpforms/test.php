<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/23/2017
 * Time: 7:10 AM
 */



spl_autoload_register('RedNaoWCInvLoader');
function RedNaoWCInvLoader($className)
{
    if(strpos($className,'rednaoformpdfbuilder\\')!==false)
    {
        $NAME=basename(\dirname(__FILE__));
        $DIR=dirname(__FILE__);
        $path=substr($className,20);
        $path=str_replace('\\','/', $path);
        require_once $DIR.$path.'.php';
    }
}

\rednaoformpdfbuilder\core\Managers\LogManager::SetShouldLog(false);

require 'vendor/autoload.php';

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$HTML=
    <<<XML
  <html>
  <body>
  <img style="max-width:400px;display:inline-block;" src="C:/wamp64/www/smartforms/delete.png"/>
  <img style="max-width:400px;display:inline-block;" src="C:/wamp64/www/smartforms/delete2.png"/>
  </body>
</html>
XML;

//$HTML=str_replace('@FONT@','C:/Users/Edgar/Downloads/font-awesome-4.7.0/font-awesome-4.7.0/fonts/fontawesome-webfont.ttf',$HTML);
$dompdf->set_option( 'dpi' , '96');
//$dompdf->getOptions()->setTempDir('C:/wamp/www/smartforms/wp-content/plugins/woo-pdf-invoice-builder/vendor/dompdf/dompdf/temp');

$dompdf->loadHtml($HTML,json_decode("[]",false));

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("dompdf", array("Attachment" => false));


