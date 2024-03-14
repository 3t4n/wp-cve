<?php


namespace rnpdfimporter\core\db;


use rnpdfimporter\core\db\core\RepositoryBase;
use rnpdfimporter\core\Integration\FileManager;
use rnpdfimporter\DTO\DocumentManagerOptions;

class TemplateRepository extends RepositoryBase
{
    public function GetTemplateList(){
        return $this->DBManager->GetResults('select importer.id Id,importer.name Name,form.name FormName from '.$this->Loader->PDFImporterTable.' importer left join '.$this->Loader->FormConfigTable.' form on form.id=importer.form_used');
    }

    /**
     * @param $templateId
     * @return DocumentManagerOptions
     */
    public function GetTemplateById($templateId)
    {
         $data= $this->DBManager->GetResult('select date_format DateFormat,id Id,field_settings FieldSettings,name TemplateName,pdf_file_path PDFURL,form_used FormId,pdf_name PDFName,pdf_file_name PDFFileName,attach_to_email AttachToEmail,skip_condition SkipCondition,additional_settings AdditionalSettings  from '.$this->Loader->PDFImporterTable.' where id=%s',$templateId);
        if($data!=null)
            $data->FieldSettings=\json_decode($data->FieldSettings);

        $condition=\json_decode($data->SkipCondition);
        if($condition==false)
            $condition=[];
        $data->PDFFileName=\json_decode($data->PDFFileName);
        $data->AttachToEmail=\json_decode($data->AttachToEmail);

        if(empty($data->AdditionalSettings))
            $data->AdditionalSettings=null;
        else
            $data->AdditionalSettings=json_decode($data->AdditionalSettings);
        $data->SkipCondition=$condition;
        return $data;
    }

    public function MaybeDeleteTemplate($PDFURL)
    {
        $fileManager=new FileManager($this->Loader);
        if(\file_exists($fileManager->GetPDFFolderPath().$PDFURL))
        {
            \unlink($fileManager->GetPDFFolderPath().$PDFURL);
        };
    }

    public function DeleteTemplate($templateId)
    {
        $template=$this->GetTemplateById($templateId);
        if($template!=null)
        {
            global $wpdb;
            $wpdb->delete($this->Loader->PDFImporterTable,array('id'=>$templateId));
            $this->MaybeDeleteTemplate($template->PDFURL);
        }
    }

}