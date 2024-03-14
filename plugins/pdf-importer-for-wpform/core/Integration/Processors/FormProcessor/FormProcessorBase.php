<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:37 AM
 */

namespace rnpdfimporter\core\Integration\Processors\FormProcessor;


use rnpdfimporter\core\Loader;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\FormSettings;

abstract class FormProcessorBase
{
    /** @var Loader */
    public $Loader;
    /**
     * FormProcessorBase constructor.
     * @param $loader
     */
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public abstract function GetFormList();

    /**
     * @param $form FormSettings
     */
    public function SaveOrUpdateForm($form){
        global $wpdb;

        $id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable.' where original_id=%d',$form->OriginalId));

        if($id==null)
        {
            $wpdb->insert($this->Loader->FormConfigTable,array(
               'original_id'=>$form->OriginalId,
                'name'=>$form->Name==null?"":$form->Name,
                'notifications'=>\json_encode($form->EmailNotifications),
                'fields'=>\json_encode($form->Fields)
            ));
        }else{
            $wpdb->update($this->Loader->FormConfigTable,array(
                'name'=>$form->Name==null?"":$form->Name,
                'fields'=>\json_encode($form->Fields),
                'notifications'=>\json_encode($form->EmailNotifications),
            ),array(
                'original_id'=>$form->OriginalId
            ));
        }

    }

    public abstract function SyncCurrentForms();

    /**
     * @param $formId
     * @return FormSettings | null
     */
    public function GetFormByOriginalId($formId)
    {
        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare('select id Id,original_id OriginalId, name Name,fields Fields from '.$this->Loader->FormConfigTable.' where original_id=%d',$formId));
        if($result=='false'||\count($result)==0)
            return null;
        $result=$result[0];

        $formSettings=new FormSettings();
        $formSettings->Fields=\json_decode($result->Fields);
        $formSettings->Id=$result->Id;
        $formSettings->Name=$result->Name;
        $formSettings->OriginalId=$result->OriginalId;

        return $formSettings;

    }
}