<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/16/2019
 * Time: 5:47 AM
 */

namespace rednaoformpdfbuilder\ajax;



use Exception;
use rednaoformpdfbuilder\DTO\DocumentOptions;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\Utils\ImportExport\Exporter;
use rednaoformpdfbuilder\Utils\ImportExport\Importer;
use ZipArchive;


class TemplateListAjax extends AjaxBase
{
    public function __construct($core, $prefix)
    {
        parent::__construct($core, $prefix, 'template_list');
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('export','Export');
        $this->RegisterPrivate('import','Import');
        $this->RegisterPrivate('delete_template','Delete');
        $this->RegisterPrivate('duplicate_template','Duplicate');
        $this->RegisterPrivate('NeverShowAgain','NeverShowAgain');


    }


    public function Duplicate(){
        $id=$this->GetRequired('Id');
        global $wpdb;
        $result=$wpdb->get_row($wpdb->prepare('select pages,styles,document_settings,form_id,name from '. $this->Loader->TEMPLATES_TABLE.' where id=%d',$id));
        if($result==null)
            $this->SendErrorMessage('Invalid form, please try again');




        if(!$wpdb->insert($this->Loader->TEMPLATES_TABLE,[
            'pages'=>$result->pages,
            'styles'=>$result->styles,
            'document_settings'=>$result->document_settings,
            'form_id'=>$result->form_id,
            'name'=>$result->name.' (Copy)'
        ]))
            $this->SendErrorMessage('The template could not be inserted, please try again');

        $this->SendSuccessMessage(['id'=>$wpdb->insert_id]);


    }

    public function NeverShowAgain(){
        \update_option($this->Loader->Prefix.'never_show_add',true);
        $this->SendSuccessMessage(true);
    }

    public function Delete(){
        $id=$this->GetRequired('Id');
        global $wpdb;
        $result=$wpdb->delete($this->Loader->TEMPLATES_TABLE,array('id'=>$id));
        if($result===false)
            $this->SendErrorMessage('An error occurred, please try again');
        $this->SendSuccessMessage('Template deleted successfully');
    }

    public function Import(){
        if(!isset($_FILES['ImportFile']))
            return;

        $zipArchive=new ZipArchive();
        if($zipArchive->open($_FILES['ImportFile']['tmp_name'])!==true)
            return;

        $id=0;
        try
        {
            $importer = new Importer($this->Loader, $zipArchive);
            $id = $importer->Execute();
        }catch(Exception $e)
        {
            $this->SendErrorMessage('An error occurred:'.$e->getMessage());

        }
        $importer->Destroy();
        $this->SendSuccessMessage(array('TemplateId'=>$id));
    }


    public function Export(){
        if(!isset($_GET['template_id'])||!\is_numeric($_GET['template_id']))
        {
            echo "Invalid request";
            die();
        }

        $templateId=floatval($_GET['template_id']);


        global $wpdb;
        $template=$wpdb->get_results($wpdb->prepare('select pages Pages, styles Styles,document_settings DocumentSettings, form_id FormId, name Name from '.$this->Loader->TEMPLATES_TABLE.' where id=%d',$templateId));
        if($template===false||count($template)==0)
        {
            echo 'Invalid template id';
            die();
        }
        if(!class_exists('ZipArchive'))
        {
            echo __("Sorry, the plugin could not generate a zip archive to export the template because the extension Zip Extension is not installed in your site. Please contact your hosting/server support and ask them to enable it for your site");
            return;
        }

        $template=$template[0];
        $template->Pages=\json_decode($template->Pages);
         $template->DocumentSettings=\json_decode($template->DocumentSettings);

        $exporter=new Exporter($this->Loader,$template);
        $zipPath=$exporter->Execute();

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=export.zip");
        header("Content-Length: " . filesize($zipPath));
        readfile($zipPath);

        $exporter->Destroy();

      /*  header('Content-Type: application/json');         # its a text file
        header('Content-Disposition: attachment;filename='.$template->Name.'.json');

        echo \json_encode($template);*/


    }
}