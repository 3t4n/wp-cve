<?php
if(!current_user_can('manage_options'))
    die('Forbidden');
$rest_json = file_get_contents("php://input");
$options=stripslashes($_POST['data']);
$options=json_decode($options);
require_once 'PDFGenerator.php';

$pageOptions=$options->pageOptions;
$previewType=$options->previewType;
$orderNumberToPreview='';
if(isset($options->orderNumberToPreview))
    $orderNumberToPreview=$options->orderNumberToPreview;

$generator;
if($previewType=='orderNumber')
{
    $order=wc_get_order($orderNumberToPreview);
    if($order==false)
    {
        echo "invalid order number";
        die();
    }else{
        $generator=\rnwcinv\GeneratorFactory::GetGenerator($pageOptions,$order);
    }

}else
{
    $generator=new RednaoPDFGenerator($pageOptions,true,null);
}

$generator->GeneratePreview();
