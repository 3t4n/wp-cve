<?php


namespace rednaoformpdfbuilder\core\Repository;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;

class EntryRepository
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    public function GetEntryIdFromOriginalId($originalId)
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->RECORDS_TABLE.' where original_id=%s',$originalId));

    }


    public function GetOriginalIdFromEntryId($entryId)
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare('select original_id from '.$this->Loader->RECORDS_TABLE.' where id=%s',$entryId));

    }



    public function GetFormIdByEntryId($entryId)
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare('select form_id from '.$this->Loader->RECORDS_TABLE.' where id=%s',$entryId));

    }

    public function GetPDFFileName($templateId,$entryId)
    {
        $templateRepository=new TemplateRepository($this->Loader);
        $templateSettings=$templateRepository->GetTemplateSettingsById($templateId);
        if($templateSettings==null)
            return null;

        $retriever=$this->Loader->CreateEntryRetriever();
        if(!$retriever->InitializeFromEntryId($entryId))
            return null;

        $generator=new PDFGenerator($this->Loader,$templateSettings,$retriever);
        return $generator->GetFileName();
    }

}