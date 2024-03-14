<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 10:23 AM
 */

namespace rnwcinv\compatibility;


use rnwcinv\htmlgenerator\DocumentOptionsDTO;
use rnwcinv\htmlgenerator\InvoiceNumberFormatDTO;
use rnwcinv\htmlgenerator\PageOptionsDTO;
use stdClass;

class DocumentOptionsCompatibility
{
    /**
     * @return DocumentOptionsDTO
     */
    public static function execute($options){
        $compatibility=new DocumentOptionsCompatibility();
        return $compatibility->InternalExecute($options);
    }

    /**
     * @param $options DocumentOptionsDTO
     * @return DocumentOptionsDTO
     */
    private function InternalExecute($options)
    {
        if(!property_exists($options,'pages')||!isset($options->pages))
        {
            $this->ConvertSingleToMultiPagesOptions($options);
        }

        if(!isset($options->myAccountDownload))
            $options->myAccountDownload=false;


        if(!isset($options->containerOptions->InvoiceNumberFormat))
            $options->containerOptions->InvoiceNumberFormat=$this->GetInvoiceNumberFormat($options);
        return $options;
    }

    /**
     * @param $options DocumentOptionsDTO
     */
    private function ConvertSingleToMultiPagesOptions($options)
    {
        /** @var DocumentOptionsDTO */
        $pageOptions=new stdClass();
        $pageOptions->contentOptions=$options->containerOptions->contentOptions;
        $pageOptions->headerOptions=$options->containerOptions->headerOptions;
        $pageOptions->footerOptions=$options->containerOptions->footerOptions;
        $pageOptions->fields=$options->containerOptions->fieldOptions;
        $options->pages=array($pageOptions);

    }

    /**
     * @param $options DocumentOptionsDTO
     */
    private function GetInvoiceNumberFormat($options)
    {
        /** @var InvoiceNumberFormatDTO $numberFormat */
        $numberFormat=new stdClass();
        foreach($options->pages as $page)
        {
            foreach($page->fields as $field)
            {
                if($field->type=='field'&&($field->fieldOptions->fieldType=='order_number'||$field->fieldOptions=='inv_number'))
                {
                    if($field->fieldOptions->fieldType=='order_number')
                        $numberFormat->type='wc';
                    else
                        $numberFormat->type='seq';

                    $numberFormat->digits=$field->fieldOptions->digits;
                    $numberFormat->prefix=$field->fieldOptions->prefix;
                    $numberFormat->sufix=$field->fieldOptions->sufix;

                    return $numberFormat;

                }
            }
        }

        $numberFormat->digits=5;
        $numberFormat->prefix='';
        $numberFormat->sufix='';

        return $numberFormat;

    }

}