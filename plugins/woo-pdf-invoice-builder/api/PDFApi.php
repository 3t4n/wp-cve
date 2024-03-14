<?php

namespace rnwcinv\api;


class PDFApi
{
    public function GetPDF($orderId,$templateId,$mode='output')
    {
        if($mode=='link')
        {
            $order = \wc_get_order( $orderId);
            if($order==null)
                return '';


            global $wpdb;
            $result=$wpdb->get_row($wpdb->prepare("SELECT 1 FROM ".\RednaoWooCommercePDFInvoice::$INVOICE_TABLE." WHERE invoice_id=%s",$templateId));
            if($result==null)
                return '';


            $guid=$order->get_meta('_pdf_guid_'.$templateId,true);
            if($guid=='')
            {
                $guid = bin2hex(openssl_random_pseudo_bytes(16));
                $order->update_meta_data('_pdf_guid_'.$templateId,$guid);
                $order->save();
            }



            $url=admin_url( 'admin-ajax.php' ).'?'.http_build_query([
                    'action'=>'rninv_get_pdf',
                    'r'=>base64_encode($orderId.'|'.$templateId.'|'.$guid)
                ]);
            return $url;
        }

        require_once \RednaoWooCommercePDFInvoice::$DIR. 'PDFGenerator.php';
        $order=\wc_get_order($orderId);
        $generator=new \RednaoPDFGenerator(\RednaoPDFGenerator::GetPageOptionsById($templateId),false,$order);
        if(!$generator->Generate(true,true,true))
        {
            echo "Document not found!";
            return;
        }

        switch($mode){
            case 'download':
                header("Content-type: application/pdf");
                header("Content-disposition: attachment; filename=".basename($generator->GetFileName()).'.pdf');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                echo $generator->GetOutput();
                break;
            case 'output':
                return $generator->GetOutput();
                break;
            case 'display':
                header("Content-type: application/pdf");
                header("Content-disposition: inline; filename=".basename($generator->GetFileName()).'.pdf');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                echo $generator->GetOutput();
                break;
            default:
                throw new \Exception('Unknown mode');
        }



    }

}