<?php


namespace rnpdfimporter\ajax;


use Clegginabox\PDFMerger\PDFMerger;
use Exception;
use rnpdfimporter\ajax\AjaxBase;
use rnpdfimporter\classes\FPDMWrapper;
use rnpdfimporter\core\db\TemplateRepository;
use rnpdfimporter\core\Integration\FileManager;
use rnpdfimporter\core\Utils\JSONSanitizer;
use rnpdfimporter\JPDFGenerator\JPDFGenerator;
use rnpdfimporter\PDFLib\api\PDFDocument;
use stdClass;

class ImporterManager extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'importer';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('Save','Save');
        $this->RegisterPrivate('Delete','Delete');
        $this->RegisterPrivate('Preview','Preview');
        $this->RegisterPrivate('Export','Export');
        $this->RegisterPrivate('LivePreview','LivePreview');
        $this->RegisterPrivate('GetPDF','GetPDF');
        $this->RegisterPrivate('NeverShowAgain','NeverShowAgain');
        $this->RegisterPrivate('Import','Import');
    }

    public function Import(){

        if(!isset($_FILES['file']))
            $this->SendErrorMessage('No file was uploaded');

        $zipArchive=new \ZipArchive();
        if(!$zipArchive->open($_FILES['file']['tmp_name']))
        {
            $this->SendErrorMessage('Invalid file');
        }

        $options=json_decode($zipArchive->getFromName('Options.json'));
        if($options==null)
            $this->SendErrorMessage('Invalid file');

        $data=array();
        $data['field_settings']=\json_encode($options->FieldSettings);
        $data['name']=$options->TemplateName;
        $data['form_used']=$options->FormId;
        $data['pdf_name']=$options->PDFName;
        $data['pdf_file_name']=\json_encode($options->PDFFileName);
        $data['attach_to_email']=\json_encode($options->AttachToEmail);
        $data['skip_condition']=\json_encode($options->SkipCondition);
        $data['date_format']=$options->DateFormat;

        $encodedPDF=$zipArchive->getFromName('PDFData.json');
        if($encodedPDF==null)
            $this->SendErrorMessage('Invalid file');


        $fileManager=new FileManager($this->Loader);
        $fileManager->MaybeCreateFolder($fileManager->GetPDFFolderPath(),true);
        $fileName=wp_unique_filename($fileManager->GetPDFFolderPath(), "Template_".time().'.json');

        $filePath=$fileManager->GetPDFFolderPath().$fileName;

        if(\file_put_contents($filePath,$encodedPDF)===false)
        {
            $this->SendErrorMessage('Could not save pdf in '.$filePath);
        }

        global $wpdb;
        $data['pdf_file_path']=$fileName;
        if($wpdb->insert($this->Loader->PDFImporterTable, $data)===false)
            $this->SendErrorMessage('There was an error creating the template, please try again');

        $this->SendSuccessMessage('Done');
    }

    public function Export(){
        if(!isset($_POST['templateId']))
            $this->SendErrorMessage('Invalid template');

        if(!\is_numeric($_POST['templateId']))
            $this->SendSuccessMessage('Invalid Template');


        $templateId=\intval($_POST['templateId']);


        $templateRepository=new TemplateRepository($this->Loader);
        $template=$templateRepository->GetTemplateById($templateId);

        $uploadDir=wp_upload_dir();

        $fileManager=new FileManager($this->Loader);
        $path=wp_unique_filename($fileManager->GetTemporalFolderPath(),'export.zip');
        $path=$fileManager->GetTemporalFolderPath().$path;


        $pdfPath=$fileManager->GetPDFFolderPath().sanitize_file_name($template->PDFURL);

        if(!file_exists($pdfPath))
            $this->SendErrorMessage('The pdf was not found');

        $content=file_get_contents($pdfPath);
        if($content==false)
            $this->SendErrorMessage('The pdf was not found');


        $zipArchive=new \ZipArchive();
        $zipArchive->open($path,\ZipArchive::CREATE|\ZipArchive::OVERWRITE);

        $zipArchive->addFromString('Options.json',json_encode($template));
        $zipArchive->addFromString('PDFData.json',$content);


        $zipArchive->close();


        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=".$template->TemplateName.'.zip');
        header("Content-Length: " . filesize($path));
        readfile($path);

        unlink($path);



    }

    public function NeverShowAgain(){
        \update_option($this->Loader->Prefix.'never_show_add',true);
        $this->SendSuccessMessage(true);
    }

    public function Delete(){


        $this->ProcessRequest(array(
            'TemplateId'=>JSONSanitizer::PROPERTY_INTEGER
        ));
        $templateId=$this->GetRequired('TemplateId');
        $repository=new TemplateRepository($this->Loader);

        $repository->DeleteTemplate($templateId);
        $this->SendSuccessMessage('Done');
    }

    public function GetPDF(){

        $this->ProcessRequest(array(
            'TemplateId'=>JSONSanitizer::PROPERTY_INTEGER
        ));
        $templateId=$this->GetRequired('TemplateId');


        $templateRepository=new TemplateRepository($this->Loader);
        $template=$templateRepository->GetTemplateById($templateId);
        if($template==null)
        {
            $this->SendErrorMessage('Template was not found');
        }


        $repository=new TemplateRepository($this->Loader);
        $data=$repository->GetTemplateById($templateId);
        if($data==null)
            $this->SendErrorMessage('Template not found in database, can not load the pdf');

        $fileName=$data->PDFURL;
        $fileManager=new FileManager($this->Loader);

        $filePath=$fileManager->GetPDFFolderPath().$fileName;
        if(!\file_exists($filePath))
            $this->SendErrorMessage('Template was not found in directory '.$filePath);

        $json=\json_decode(\file_get_contents($filePath));
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename=download.pdf');

        $generator=new JPDFGenerator($this->Loader,$json);
        echo $generator->Output();


        die();

    }



    public function LivePreview(){
        $this->ProcessRequest(array(
            'Template'=>JSONSanitizer::PROPERTY_STRING
        ));


        $template=$this->GetRequired('Template');


        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename=download.pdf');

        $generator=new JPDFGenerator($this->Loader, $template);
        echo $generator->Output();




        die();



    }

    public function Preview(){

        if(!isset($_POST['templateId']))
            $this->SendErrorMessage('Invalid template');

        if(!\is_numeric($_POST['templateId']))
            $this->SendSuccessMessage('Invalid Template');

        $templateId=\intval($_POST['templateId']);


        $templateRepository=new TemplateRepository($this->Loader);
        $template=$templateRepository->GetTemplateById($templateId);
        if($template==null)
        {
            $this->SendErrorMessage('Template was not found');
        }


        $repository=new TemplateRepository($this->Loader);
        $data=$repository->GetTemplateById($templateId);
        if($data==null)
            $this->SendErrorMessage('Template not found in database, can not load the pdf');

        $fileName=$data->PDFURL;
        $fileManager=new FileManager($this->Loader);

        $filePath=$fileManager->GetPDFFolderPath().$fileName;
        if(!\file_exists($filePath))
            $this->SendErrorMessage('Template was not found in directory '.$filePath);


        $json=\json_decode(\file_get_contents($filePath));
        $generator=new JPDFGenerator($this->Loader, $json);
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename='.$generator->GetFileName());


        echo $generator->Output();


        die();



    }

    public function Save(){
        $this->ProcessRequest(array(
            'Template'=>JSONSanitizer::PROPERTY_STRING,
            'PDF'=>JSONSanitizer::PROPERTY_STRING
        ));


        $options=$this->GetRequired('Options');
        $pdf=$this->GetRequired('PDF');
        $name=$options->TemplateName;
        $data=array();
        $data['field_settings']=\json_encode($options->FieldSettings);
        $data['name']=$options->TemplateName;
        $data['form_used']=$options->FormId;
        $data['pdf_name']=$options->PDFName;
        $data['pdf_file_name']=\json_encode($options->PDFFileName);
        $data['attach_to_email']=\json_encode($options->AttachToEmail);
        $data['skip_condition']=\json_encode($options->SkipCondition);
        $data['date_format']=$options->DateFormat;
        $data['additional_settings']=json_encode($options->AdditionalSettings);

        $encodedPDF=\json_encode($pdf);
        $fileManager=new FileManager($this->Loader);
        $fileManager->MaybeCreateFolder($fileManager->GetPDFFolderPath(),true);
        $fileName=wp_unique_filename($fileManager->GetPDFFolderPath(), "Template_".time().'.json');

        global $wpdb;
        if($options->Id==0)
        {
            $count=$wpdb->get_var( $wpdb->prepare('select count(*) from '.$this->Loader->PDFImporterTable.' where name=%s',$options->TemplateName));

            if($count>0)
            {
                $this->SendErrorMessage('The name '.$options->TemplateName.' is already in use');
            }
        }


        $filePath=$fileManager->GetPDFFolderPath().$fileName;

        if(\file_put_contents($filePath,$encodedPDF)===false)
        {
            $this->SendErrorMessage('Could not save pdf in '.$filePath);
        }

        $data['pdf_file_path']=$fileName;
        global $wpdb;

        if($options->Id>0)
        {
            $lastFileName=$wpdb->get_var($wpdb->prepare('select pdf_file_path from '.$this->Loader->PDFImporterTable.' where id=%s',$options->Id));
            if($wpdb->update($this->Loader->PDFImporterTable, $data,array('id'=>$options->Id))===false)
                $this->SendErrorMessage('There was an error updating the template, please try again');

            if(\file_exists($fileManager->GetPDFFolderPath().$lastFileName))
                \unlink($fileManager->GetPDFFolderPath().$lastFileName);


        }else
        {
            if(!$this->Loader->IsPR())
            {
                $count=$wpdb->get_var('select count(*) from '.$this->Loader->PDFImporterTable);
                if($count>=1)
                    $this->SendErrorMessage('Sorry you can only create one template in the free version');
            }


            if($wpdb->insert($this->Loader->PDFImporterTable, $data)===false)
                $this->SendErrorMessage('There was an error updating the template, please try again');

            $options->Id=$wpdb->insert_id;
        }

        $this->SendSuccessMessage(array('Id'=>$options->Id));


    }


}