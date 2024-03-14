<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/19/2019
 * Time: 11:39 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor;



use rednaoformpdfbuilder\core\Managers\LogManager;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormDateFieldSettings;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormNameFieldSettings;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormSignatureFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\FormProcessor\FormProcessorBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\EmailNotification;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FileUploadFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\HtmlFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\MultipleOptionsFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\NumberFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\RatingFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\FormSettings;
use Svg\Tag\Text;

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

        LogManager::LogDebug('Serializing form '.($forms['post_content']));
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
        try{
            $formSettings->Fields=$this->SerializeFields($fieldList);
        }catch (\Exception $e)
        {
            LogManager::LogDebug('An error occurred while serializing field '.$e->getMessage());
            throw $e;
        }



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
                case 'signature':
                    $fieldSettings[]=(new WPFormSignatureFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'payment-single':
                case 'textarea':
                case 'payment-total':
                case 'url':
                case 'slider':
                case 'number-slider':
                case 'calculator':
                case 'number_format':
                    $fieldSettings[]=(new TextFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'rating':
                    $fieldSettings[]=(new RatingFieldSettings())->Initialize($field->id,$field->label,$field->type,intval($field->scale));
                    break;
                case 'radio':
                case 'checkbox':
                case 'payment-checkbox':
                case 'payment-multiple':
                case 'select':
                case 'payment-select':
                case 'likert_scale':
                    $settings=(new MultipleOptionsFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    foreach($field->choices as $choice)
                    {
                        $settings->AddOption($choice->label,$choice->value);
                    }
                $fieldSettings[]=$settings;
                    break;
                case 'number':
                    $fieldSettings[]=(new NumberFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'name':
                    $nameSettings=(new WPFormNameFieldSettings())->Initialize($field->id,$field->label,$field->type);

                    $nameSettings->Format=$nameSettings->GetStringValue($field,'format');
                    $fieldSettings[]=$nameSettings;
                    break;
                case 'address':
                    $addressField=(new WPFormAddressFieldSettings())->Initialize($field->id,$field->label,$field->type);

                    $isInternational=$addressField->GetValue($field,'scheme','international')=='international';

                    $addressField->SetHidePostal($addressField->GetBoolValue($field,['postal_hide']));
                    $addressField->SetHideAddress2($addressField->GetBoolValue($field,['address2_hide']));
                    $addressField->SetHideCountry($addressField->GetBoolValue($field,['country_hide'])||!$isInternational);

                    $addressField->SetStringProperty('Address1Label',$field,'address1_placeholder','Address 1');
                    $addressField->SetStringProperty('Address2Label',$field,'address2_placeholder','Address 2');
                    $addressField->SetStringProperty('CityLabel',$field,'city_placeholder','City');
                    $addressField->SetStringProperty('StateLabel',$field,'state_placeholder','State');
                    $addressField->SetStringProperty('ZipLabel',$field,'postal_placeholder','Zip');
                    $addressField->SetStringProperty('CountryLabel',$field,'country_placeholder','Country');



                    $fieldSettings[]=$addressField;
                    break;
                case 'date-time':
                    $fieldSettings[]=(new WPFormDateFieldSettings())->Initialize($field->id,$field->label,$field->type)
                        ->SetDateFormat($field->date_format)
                        ->SetTimeFormat($field->time_format);
                    break;
                case 'file-upload':
                    $fieldSettings[]=(new FileUploadFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'richtext':
                    $fieldSettings[]=(new HtmlFieldSettings())->Initialize($field->id,$field->label,$field->type);
                    break;
                case 'content':
                    $fieldSettings[]=(new HtmlFieldSettings())->Initialize($field->id,'Content',$field->type)->SetContent($field->content);
                    break;
                case 'html':
                    $fieldSettings[]=(new HtmlFieldSettings())->Initialize($field->id,'Content',$field->type)->SetContent($field->code);

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

        $rows= $wpdb->get_results("select form.id Id, post.post_title Name, form.fields Fields,original_id OriginalId,notifications Notifications from ".$wpdb->posts. " post join ". $this->Loader->FormConfigTable." form on post.id=form.original_id");
        return $rows;
    }
}