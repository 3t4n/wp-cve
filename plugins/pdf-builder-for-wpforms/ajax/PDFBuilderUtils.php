<?php

namespace rednaoformpdfbuilder\ajax;

use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;

class PDFBuilderUtils extends AjaxBase
{

    public function __construct($core, $prefix)
    {
        parent::__construct($core, $prefix, 'pdfbuilder_utils');
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('generate_pdf_from_original','GeneratePDFFromOriginal','administrator',false);
    }




    public function GeneratePDFFromOriginal(){
        if (class_exists('WPForms_Entry_Handler')) {
            $entryId=intval($_GET['entryid']);
            $templateId=intval($_GET['templateid']);
            $nonce=$_GET['nonce'];

            if(!wp_verify_nonce($nonce,'generate_'.$templateId.'_'.$entryId))
            {
                echo 'Invalid nonce, please refresh your screen and try again';
                return;
            }

            $entryRetriever=$this->Loader->CreateEntryRetriever();
            if(!$entryRetriever->InitializeFromOriginalEntryId($entryId)){
                echo 'Template not found';
                return;
            }

            global $wpdb;
            $templateSettings=$wpdb->get_row($wpdb->prepare(
                "select template.id Id,template.pages Pages, template.document_settings DocumentSettings,styles Styles,form_id FormId                  
                    from ".$this->Loader->TEMPLATES_TABLE." template                   
                    where template.id=%s",$templateId));

            if($templateSettings==null) {
                echo 'Template not found';
                return;
            }

            $templateSettings->Pages=\json_decode($templateSettings->Pages);
            $templateSettings->DocumentSettings=\json_decode($templateSettings->DocumentSettings);



            $generator=(new PDFGenerator($this->Loader,$templateSettings,$entryRetriever));
            $generator->GeneratePreview();




        }
        die();
    }

}