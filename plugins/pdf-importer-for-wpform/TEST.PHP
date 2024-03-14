<?php
header("Content-type:application/pdf");

$file= file_get_contents('C:\wamp64\www\smartforms\wp-content\plugins\pdf-importer-for-pdf-builder\test\cypress\fixtures\Document (5).pdf');
$parts=$parts=explode('CHANGETHISTEXT',$file);
echo $parts[0];
echo mb_convert_encoding('þÿ','8bit').  mb_convert_encoding('Špičák 151, Železná Ruda, PSČ, 340 04','UTF-16');
echo $parts[1];

