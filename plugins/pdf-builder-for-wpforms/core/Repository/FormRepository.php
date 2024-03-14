<?php


namespace rednaoformpdfbuilder\core\Repository;


use rednaoformpdfbuilder\core\Loader;

class FormRepository
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetFormIdFromOriginalId($originalFormId)
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable.' where original_id=%s',$originalFormId));

    }

    public function GetTemplatesForForm($formId)
    {
        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,template.pages Pages, template.document_settings DocumentSettings,styles Styles,form_id FormId
                    from ".$this->Loader->TEMPLATES_TABLE." template
                    where form_id=%s"
            ,$formId));

        foreach($result as $currentRow)
        {
            $currentRow->Pages = \json_decode($currentRow->Pages);
            $currentRow->DocumentSettings = \json_decode($currentRow->DocumentSettings);
        }
        return $result;
    }

}