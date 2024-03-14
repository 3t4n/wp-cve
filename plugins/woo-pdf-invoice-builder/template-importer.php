<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 2/22/2018
 * Time: 8:03 AM
 */
if(!isset($_FILES['files']))
    return;

$importer=new \rnwcinv\ImportExport\TemplateImporter();
$importer->Import($_FILES['files']['tmp_name']);

return;
$content= file_get_contents($_FILES['files']['tmp_name']);
$content = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content);
$data=json_decode($content);
if($data===false)
    return;
global $wpdb;
$name=$data->name;
$nameToTry=$name;
$count=1;
do{
    $result=$wpdb->get_var($wpdb->prepare('select count(*) from '.RednaoWooCommercePDFInvoice::$INVOICE_TABLE.' where name=%s',$nameToTry));
    if($result>0)
    {
        $nameToTry=$name.' ('.$count.')';
        $count++;
    }
}while($result>0);

$wpdb->insert(RednaoWooCommercePDFInvoice::$INVOICE_TABLE,array(
    'name'=>$nameToTry,
    'attach_to'=>$data->attach_to,
    'options'=>$data->containerOptions,
    'html'=>$data->html,
    'conditions'=>$data->conditions,
    'type'=>$data->pageType,
    'extensions'=>$data->extensions,
    'pages'=>$data->pages
));