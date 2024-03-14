<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/9/2018
 * Time: 9:04 AM
 */

namespace rnwcinv\utilities;
use RednaoWooCommercePDFInvoice;
use rnwcinv\htmlgenerator\InvoiceNumberFormatDTO;
use rnwcinv\htmlgenerator\OrderValueRetriever;
use rnwcinv\pr\Manager\TagManager;

class InvoiceInitialDataGenerator
{

    /** @var OrderValueRetriever */
    private $orderValueRetriever;
    /**
     * @param $invoiceId
     * @param $orderId
     * @param $format InvoiceNumberFormatDTO
     */
    public function Create($invoiceId,$orderId, $format,$useFakeInvoiceId=false,$orderValueRetriever=null)
    {
        $this->orderValueRetriever=$orderValueRetriever;
        $initialData=new InitialData();
        if($invoiceId>0)
        {

            $order=wc_get_order($orderId);
            if($order==null)
                return $initialData;

            $initialData->InvoiceNumber=$order->get_meta( 'REDNAO_WCPDFI_INVOICE_ID',true);
            if($initialData->InvoiceNumber!='')
            {
                //old version of getting invoice and date
                $initialData->Date=$order->get_meta('REDNAO_WCPDFI_INVOICE_DATE',true);
                $initialData->FormattedInvoiceNumber=$this->FormatNumber($initialData->InvoiceNumber,$format);
                return $initialData;
            }

            if($format->type=='wc')
                $initialData->InvoiceNumber=$orderId;
            else{
                global $wpdb;
                $number=apply_filters('wcpdfi_get_latest_invoice_number',get_option($invoiceId. '_rednao_pdf_invoice_number',0),$invoiceId,true);

                if(is_numeric($number))
                    $number=intval($number);
                else
                    $number=0;

                $initialData->InvoiceNumber=$number+1;
                update_option($invoiceId. '_rednao_pdf_invoice_number',apply_filters('wcpdfi_update_latest_invoice_number',$initialData->InvoiceNumber,$invoiceId));
/*

                while(true)
                {
                    $number++;
                    $result=$wpdb->get_row($wpdb->prepare('select 1 from '.\RednaoWooCommercePDFInvoice::$INVOICES_CREATED_TABLE.' where invoice_id=%s and invoice_number=%d',$invoiceId,$number));
                    if ($wpdb->last_error) {
                        throw new \Exception('Next invoice number could not be generated');
                    }
                    if($result==null)
                        break;
                }
*/
               /* $initialData->InvoiceNumber=$number;
                update_site_option($invoiceId. '_rednao_pdf_invoice_number',$initialData->InvoiceNumber);*/
            }

            $initialData->Date=current_time('timestamp');
            $initialData->FormattedInvoiceNumber=$this->FormatNumber($initialData->InvoiceNumber,$format);
            $order->update_meta_data('_WCPDF_INVOICE_NUMBER_'.$invoiceId,$initialData->FormattedInvoiceNumber);
            $order->update_meta_data('_WCPDF_INVOICE_DATE_'.$invoiceId,$initialData->Date);


            if(RednaoWooCommercePDFInvoice::IsPR())
            {
                $addLinkInMeta=Sanitizer::GetValueFromPath($orderValueRetriever,['templateOptions','containerOptions','addLinkInMeta'],false);
                if($addLinkInMeta==true)
                {
                    $linkMetaName=Sanitizer::GetValueFromPath($orderValueRetriever,['templateOptions','containerOptions','linkMetaName'],false);
                    if($linkMetaName!='')
                    {
                        $pdfLink=WPDFIB()->GetPDF($orderId,$invoiceId,'link');
                        if($pdfLink!='')
                        {
                            $order->update_meta_data($linkMetaName,$pdfLink);
                        }

                    }




                }
            }
            $order->save();

            return $initialData;


        }

        if($useFakeInvoiceId)
        {
            $initialData->InvoiceNumber=1;
            $initialData->Date=current_time('timestamp');
            $initialData->FormattedInvoiceNumber=$this->FormatNumber(1,$format);
            return $initialData;
        }

        return null;


    }

    /**
     * @param $format InvoiceNumberFormatDTO
     */
    public function FormatNumber($value,$format){
        $prefix=$format->prefix;
        $sufix=$format->sufix;
        $digits=$format->digits;

        if(RednaoWooCommercePDFInvoice::IsPR())
        {
            $tagManager=new TagManager($this->orderValueRetriever);

            $prefix=$tagManager->Process($prefix);
            $sufix=$tagManager->Process($sufix);
        }

        if(is_numeric($value)&&$digits>0)
            $value=str_pad(intval($value),$digits,'0',STR_PAD_LEFT);

        return $prefix.$value.$sufix;
    }
}


class InitialData{
    public $InvoiceNumber;
    public $Date;
    public $FormattedInvoiceNumber;
}