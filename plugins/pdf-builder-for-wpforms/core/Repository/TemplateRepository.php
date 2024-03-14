<?php


namespace rednaoformpdfbuilder\core\Repository;


use rednaoformpdfbuilder\core\Loader;

class TemplateRepository
{
    /** @var Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetTemplateSettingsById($templateId)
    {
        global $wpdb;
        $result = $wpdb->get_row($wpdb->prepare(
            "select template.id Id,template.pages Pages, template.document_settings DocumentSettings,styles Styles,form_id FormId
                    from " . $this->Loader->TEMPLATES_TABLE . " template
                    where template.id=%s"
            , $templateId));

        if ($result == null)
            return null;

        $result->Pages = \json_decode($result->Pages);
        $result->DocumentSettings = \json_decode($result->DocumentSettings);

        return $result;
    }
}