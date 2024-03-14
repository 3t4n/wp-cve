<?php

namespace rednaoformpdfbuilder\api;

use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\core\Repository\TemplateRepository;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;

class PDFBuilderApi
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetTemplateList(){

        global $wpdb;
        $templates=$wpdb->get_results("select template.id Id,template.name TemplateName,config.name FormName,config.original_id OriginalId from ".$this->Loader->TEMPLATES_TABLE." template
            left join  ".$this->Loader->FormConfigTable." config
            on config.id=template.form_id
            ");

        return $templates;
    }

    public function GetPDFURL($entryId,$templateId)
    {
        return esc_attr(admin_url( 'admin-ajax.php' )) .'?action='.esc_attr($this->Loader->Prefix).'_generate_pdf_from_original&entryid='.esc_attr($entryId).
        '&templateid='.esc_attr($templateId).'&nonce='.esc_attr(wp_create_nonce('generate_'.$templateId.'_'.$entryId));

    }

    public function GetPDFGenerator($templateId,$entryId)
    {
        $templateRepository=new TemplateRepository($this->Loader);
        $templateSettings=$templateRepository->GetTemplateSettingsById($templateId);
        if($templateSettings==null)
            return null;

        $retriever=$this->Loader->CreateEntryRetriever();
        if(!$retriever->InitializeFromOriginalEntryId($entryId))
            return null;

        $generator=new PDFGenerator($this->Loader,$templateSettings,$retriever);
        return $generator;

    }

}