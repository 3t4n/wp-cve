<?php

namespace rnpdfimporter\api;

use rnpdfimporter\core\Loader;

class PDFImporterApi
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetTemplateList(){

        global $wpdb;
        $templates=$wpdb->get_results("select template.id Id,template.name TemplateName,config.name FormName,config.original_id OriginalId from ".$this->Loader->PDFImporterTable." template
            left join  ".$this->Loader->FormConfigTable." config
            on config.id=template.form_used
            ");

        return $templates;
    }

    public function GetPDFURL($entryId,$templateId,$perpetualLink=false)
    {
        $data=[
            'templateid'=>$templateId,
            'entryid'=>$entryId,
            'nonce'=>wp_create_nonce('rnpdfimporter_'.$entryId.'_'.$templateId.'_1'),
            'use_original_entry'=>true
        ];

        $data=json_encode($data);
        return esc_attr(admin_url( 'admin-ajax.php' )) .'?action='.esc_attr($this->Loader->Prefix).'_public_create_pdf&data='.esc_attr($data);

    }


}