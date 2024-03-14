<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:39 AM
 */

namespace rnpdfimporter\core\Integration\Adapters\WPForm\FormProcessor;



use rnpdfimporter\core\Integration\Processors\FormProcessor\FormProcessorBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\EmailNotification;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldItem;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\ComposedFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\DateFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\DateTimeFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FileUploadFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\NumberFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\TimeFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\FormSettings;

class WPFormFormProcessor extends FormProcessorBase
{
    public function __construct($loader)
    {
        parent::__construct($loader);
        \add_action('wpforms_save_form',array($this,'FormIsSaving'),10,2);
    }

    public function FormIsSaving($formId,$forms){
        $forms['post_content']=\stripslashes($forms['post_content']);
        $forms=$this->SerializeForm($forms);
        $this->SaveOrUpdateForm($forms);
    }


    public function SerializeForm($forms){
        $fieldList=\json_decode( ($forms['post_content']));
        $formSettings=new FormSettings();

        if(isset($fieldList->settings)&&$fieldList->settings->notification_enable=='1')
        {
            foreach($fieldList->settings->notifications as $id=>$notification)
            {
                $formSettings->EmailNotifications[]=new EmailNotification($id,isset($notification->notification_name)?$notification->notification_name:'Default');
            }
        }



        if(isset($fieldList->fields))
            $fieldList=$fieldList->fields;
        else
            $fieldList=array();


        $formSettings->OriginalId=$forms['ID'];
        $formSettings->Name=$forms['post_title'];
        $formSettings->Fields=$this->SerializeFields($fieldList);


        return $formSettings;
    }

    public function SerializeFields($fieldList)
    {
        /** @var FieldSettingsBase[] $fieldSettings */
        $fieldSettings=array();
        foreach($fieldList as $field)
        {
            switch($field->type)
            {
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'payment-single':
                case 'textarea':
                case 'payment-total':
                case 'url':
                case 'richtext':
                case 'calculation':
                $fieldSettings[]=(new TextFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'radio':
                case 'checkbox':
                case 'payment-multiple':
                case 'select':
                case 'payment-select':
                    $settings=(new MultipleOptionsFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    foreach($field->choices as $choice)
                    {
                        $settings->AddOption($choice->label,$choice->value);
                    }
                $fieldSettings[]=$settings;
                    break;
                case 'number':
                case 'number-slider':
                    $fieldSettings[]=(new NumberFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'name':
                    $nameSettings=(new ComposedFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    switch ($field->format)
                    {
                        case 'simple':
                            $nameSettings->AddItem('Name','value','Name');
                            break;
                        case 'first-last':
                            $nameSettings->AddItem('FirstName','first','First Name');
                            $nameSettings->AddItem('LastName','last','Last Name');
                            break;
                        case 'first-middle-last':
                            $nameSettings->AddItem('FirstName','first','First Name');
                            $nameSettings->AddItem('MiddleName','middle','Middle Name');
                            $nameSettings->AddItem('LastName','last','Last Name');
                            break;
                    }

                    $fieldSettings[]=$nameSettings;
                    break;
                case 'address':

                    $addressSettings=(new ComposedFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem('Address1','address1','Address 1'))->AddCommaBefore());
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem('Address2','address2','Address 2'))->AddCommaBefore());
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem('City','city','City'))->AddCommaBefore());
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem('State','state','State'))->AddCommaBefore());
                    $addressSettings->AddComposedFieldItem((new ComposedFieldItem('Postal','postal','Postal'))->AddCommaBefore());


                    if($field->scheme=='international')
                    {
                        $addressSettings->AddComposedFieldItem((new ComposedFieldItem('Country','country','Country'))->AddCommaBefore());
                    }
                    $fieldSettings[]=$addressSettings;
                    break;
                case 'date-time':
                    switch ($field->format)
                    {
                        case 'date-time':
                            $dateSettings=(new DateTimeFieldSettings())->Initialize($field->id,$field->label,$field->type)
                            ->SetDateFormat($field->date_format)
                            ->SetTimeFormat($field->time_format);
                            $fieldSettings[]=$dateSettings;
                            break;
                        case 'time':
                            $dateSettings=(new TimeFieldSettings())->Initialize($field->id,$field->label,$field->type)
                            ->SetTimeFormat($field->time_format);

                            $fieldSettings[]=$dateSettings;
                            break;
                        case 'date':
                            $dateSettings=(new DateFieldSettings())->Initialize($field->id,$field->label,$field->type)
                            ->SetDateFormat($field->date_format);
                            $fieldSettings[]=$dateSettings;
                            break;
                    }

                    break;
                case 'file-upload':
                case 'signature':
                    $fieldSettings[]=(new FileUploadFieldSettings())->Initialize($field->id,isset($field->label)?$field->label:'',$field->type);
                    break;
            }
        }

        return $fieldSettings;
    }

    public function SyncCurrentForms()
    {
        global $wpdb;
        $results=$wpdb->get_results("select id ID, post_title,post_content from ".$wpdb->posts." where post_type='wpforms'",'ARRAY_A');
        foreach($results as $form)
        {
            $form=$this->SerializeForm($form);
            $this->SaveOrUpdateForm($form);
        }
    }

    public function GetFormList()
    {
        global $wpdb;

        return $wpdb->get_results("select form.id Id, post.post_title Name, form.fields Fields,original_id OriginalId,notifications Notifications from ".$wpdb->posts. " post join ". $this->Loader->FormConfigTable." form on post.id=form.original_id");
    }
}