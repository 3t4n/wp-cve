<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:03 AM
 */

namespace rnpdfimporter\core\Integration\Adapters\WPForm\Entry;


use DateTime;
use DateTimeZone;
use Exception;
use rnpdfimporter\core\Integration\Adapters\WPForm\Entry\EntryItems\WPFormAddressEntryItem;
use rnpdfimporter\core\Integration\Adapters\WPForm\Entry\EntryItems\WPFormDateTimeEntryItem;
use rnpdfimporter\core\Integration\Adapters\WPForm\Entry\EntryItems\WPFormFileUploadEntryItem;
use rnpdfimporter\core\Integration\Adapters\WPForm\Entry\EntryItems\WPFormNameEntryItem;
use rnpdfimporter\core\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rnpdfimporter\core\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\CheckBoxEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ComposedEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\DateEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\DateTimeEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\DropDownEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\FileUploadEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\RadioEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\TimeEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryProcessorBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpdfimporter\JPDFGenerator\JPDFGenerator;
use rnpdfimporter\pr\Managers\ConditionManager\ConditionManager;
use rnpdfimporter\Utilities\Sanitizer;

class WPFormEntryProcessor extends EntryProcessorBase
{
    public function __construct($loader)
    {
        parent::__construct($loader);


        \add_action('wpforms_post_insert_',array($this,'UpdateOriginalEntryId'),10,2);
        \add_action('wpforms_process_entry_save',array($this,'SaveEntry'),10000000,4);
        \add_action('wpforms_entry_email_process',array($this,'ProcessEmail'),10,5);
        \add_filter('wpforms_emails_send_email_data',array($this,'AddAttachmentNew'),10,2);
        \add_shortcode('bpdfimporter_download_link',array($this,'AddPDFLink'));
        \add_action('wpforms_entry_details_sidebar_actions',array($this,'GenerateSideBarAction'),10,2);
        \add_action('wpforms_pro_admin_entries_edit_submit_completed',array($this,'EditEntry'),10,4);

        //    \add_action('wpforms_email_attachments',array($this,'AddAttachment'),10,2);


/*
        \add_filter(
           'wpforms_tasks_entry_emails_trigger_send_same_process',array($this,'SendSameProcess'));
*/

    }

    public function EditEntry($formData,$response,$uploadedfields,$entry){
        $formProcessor=new WPFormFormProcessor($this->Loader);
        $formSettings=$formProcessor->SerializeForm(array(
            "ID"=>$formData['id'],
            'post_title'=>'',
            'post_content'=>\json_encode(array('fields'=>$formData['fields']))
        ));
        global $wpdb;
        $formSettings->Id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable." where original_id=%d",$formSettings->OriginalId));
        if($formSettings->Id==null)
            return;

        if(!isset($entry->fields))
            return;

        $fields=\json_decode($entry->fields,true);
        foreach($uploadedfields as $key=>$value)
        {
            $fields[$key]=$value;
        }
        $serializeEntry=$this->SerializeEntry($fields,$formSettings);


        $pdfTemplates=array();
        if(isset($formData['meta']['pdfTemplates']))
            $pdfTemplates=$formData['meta']['pdfTemplates'];


        $entryId=$entry->entry_id;
        if(!Sanitizer::SanitizeBoolean(get_option($this->Loader->Prefix.'_skip_save',false)))
            $entryId=$this->SaveEntryToDB($formData['id'],$serializeEntry,$entryId,array('Fields'=>$fields));


    }

    public function GenerateSideBarAction($entry,$formData){

        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,template.name Name
                    from ".$this->Loader->FormConfigTable." form
                    join ".$this->Loader->PDFImporterTable." template
                    on form.id=template.form_used
                    where original_id=%s"
            ,$formData['id']));


        foreach($result as $pdfTemplate)
        {
            $data=array(
                'entryid'=>$entry->entry_id,
                'templateid'=>$pdfTemplate->Id,
                'use_original_entry'=>true,
                'nonce'=>\wp_create_nonce($this->Loader->Prefix.'_'.$entry->entry_id.'_'.$pdfTemplate->Id.'_1')
            );

            echo '
                <p class="wpforms-entry-star">
                    <a href="'.esc_attr(admin_url( 'admin-ajax.php' )) .'?action='.esc_attr($this->Loader->Prefix).'_public_create_pdf&entryid='.esc_attr($entry->entry_id).
                '&data='.esc_attr(json_encode($data)).'">
                        <span class="dashicons dashicons-pdf"></span>View '.esc_html($pdfTemplate->Name).'
                    </a>
                </p>
            ';
        }
    }

    public function AddAttachmentNew($emailData,$wpform)
    {
        $emailData['attachments']=$this->AddAttachment($emailData['attachments'],null,$wpform);
        try
        {
            $emailData['message'] = $this->MaybeUpdateEmailBody($emailData['message'], $wpform->form_data['id'], $wpform->entry_id, $wpform->fields);
        }catch (Exception $e)
        {
        }
        return $emailData;
    }

    public function ProcessEmail($proces,$fields,$formdata,$notificationId,$context){
        global $WPFormEmailBeingProcessed;
        $WPFormEmailBeingProcessed=$notificationId;
        return $proces;
    }


    public function SendSameProcess($sameProcess)
    {
        return true;
    }
    public function UpdateOriginalEntryId($entryId,$formData)
    {
        if(!isset($formData['fields']))
            return;
        global $RNWPImporterCreatedEntry;
        if(!isset($RNWPImporterCreatedEntry)||!isset($RNWPImporterCreatedEntry['Entry']))
            return;

        global $wpdb;
        $wpdb->update($this->Loader->RECORDS_TABLE,array(
            'original_id'=>$entryId
        ),array('id'=>$RNWPImporterCreatedEntry['EntryId']));

    }

    public function SaveLittleEntry($fields,$entry,$formId,$formData,$entryId=0)
    {
        $this->SaveEntry($fields,$entry,$formId,$formData,0);
    }

    public function SaveEntry($fields,$entry,$formId,$formData,$entryId=0){
        $formProcessor=new WPFormFormProcessor($this->Loader);
        $formSettings=$formProcessor->SerializeForm(array(
            "ID"=>$formData['id'],
            'post_title'=>'',
            'post_content'=>\json_encode(array('fields'=>$formData['fields']))
        ));
        global $wpdb;
        $formSettings->Id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable." where original_id=%d",$formSettings->OriginalId));
        if($formSettings->Id==null)
            return;

        $entry=$this->SerializeEntry($fields,$formSettings);

        $pdfTemplates=array();
        if(isset($formData['meta']['pdfTemplates']))
            $pdfTemplates=$formData['meta']['pdfTemplates'];



        $entryId=$this->SaveEntryToDB($formData['id'],$entry,isset(wpforms()->process->entry_id)?wpforms()->process->entry_id:0,array('Fields'=>$fields));

        $pdfTemplates[]=array('EntryId'=>$entryId);
        $formData['meta']['pdfTemplates']=$pdfTemplates;
        global $RNWPImporterCreatedEntry;
        $RNWPImporterCreatedEntry=array(
            'Entry'=>$entry,
            'FormId'=>$formData['id'],
            'EntryId'=>$entryId,
            'OriginalId'=>isset(wpforms()->process->entry_id)?wpforms()->process->entry_id:0,
            'Raw'=>json_decode( \json_encode(array('Fields'=>$fields)))
        );
    }

    public function AddAttachment($attachment,$target,$wpFormSettings)
    {
        global $RNWPImporterCreatedEntry;
        if(!isset($RNWPImporterCreatedEntry)||!isset($RNWPImporterCreatedEntry['Entry']))
        {
            if($wpFormSettings!=null&&isset($wpFormSettings->fields)&&isset($wpFormSettings->form_data['fields']))
            {
                global $WPFormEmailBeingProcessed;
                $WPFormEmailBeingProcessed=$wpFormSettings->notification_id;
                if (!isset($RNWPImporterCreatedEntry))
                    $RNWPImporterCreatedEntry = array();
                $formProcessor=new WPFormFormProcessor($this->Loader);
                $formSettings=$formProcessor->SerializeForm(array(
                    "ID"=>$wpFormSettings->form_data['id'],
                    'post_title'=>'',
                    'post_content'=>\json_encode(array('fields'=>$wpFormSettings->form_data['fields']))
                ));

                global $wpdb;
                $RNWPImporterCreatedEntry['Entry'] = $this->SerializeEntry($wpFormSettings->fields,$formSettings);
                $RNWPImporterCreatedEntry['FormId']=$wpFormSettings->form_data['id'];
                $RNWPImporterCreatedEntry['EntryId']='';
                $RNWPImporterCreatedEntry['Raw']=json_encode($wpFormSettings->fields);
                $RNWPImporterCreatedEntry['OriginalId']=$wpFormSettings->entry_id;
                $RNWPImporterCreatedEntry['EntryId']=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->RECORDS_TABLE.' where original_id=%d',$wpFormSettings->entry_id));

            }else
                return $attachment;
        }



        global $wpdb;
        $fields=$wpdb->get_var($wpdb->prepare('select fields from '.$this->Loader->FormConfigTable.' where original_id=%s',$RNWPImporterCreatedEntry['FormId']));

        $entryRetriever=new WPFormEntryRetriever($this->Loader);



        $entryRetriever->InitializeByEntryItems($RNWPImporterCreatedEntry['Entry'],$RNWPImporterCreatedEntry['Raw'],$fields,isset($RNWPImporterCreatedEntry['OriginalId'])?$RNWPImporterCreatedEntry['OriginalId']:'');

        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,attach_to_email AttachToEmail,skip_condition SkipCondition
                    from ".$this->Loader->FormConfigTable." form
                    join ".$this->Loader->PDFImporterTable." template
                    on form.id=template.form_used
                    where original_id=%s"
            ,$RNWPImporterCreatedEntry['FormId']));
        $files=[];



        if(!isset($RNWPImporterCreatedEntry['CreatedDocuments'])){
            $RNWPImporterCreatedEntry['CreatedDocuments']=[];
        }
        foreach($result as $templateSettings)
        {


            if($this->Loader->IsPR()&&isset($templateSettings->SkipCondition))
            {
                $condition=json_decode($templateSettings->SkipCondition);
                $conditionManager=new ConditionManager();
                if($conditionManager->ShouldSkip($this->Loader, $entryRetriever,$condition))
                {
                    continue;
                }
            }


            $templateSettings->AttachToEmail=\json_decode($templateSettings->AttachToEmail);

            $generator=new JPDFGenerator($this->Loader);
            $generator->LoadByTemplateId($templateSettings->Id);
            $generator->LoadEntry($entryRetriever);
            $path=$generator->SaveInTempFolder();


            if(count($templateSettings->AttachToEmail)>0&&$this->Loader->IsPR())
            {
                global $WPFormEmailBeingProcessed;
                if(isset($WPFormEmailBeingProcessed))
                {
                    $found=false;
                    foreach($templateSettings->AttachToEmail as $attachToNotification)
                    {
                        if($this->Loader->PRLoader->ShouldProcessEmail($attachToNotification,$WPFormEmailBeingProcessed))
                            $found=true;


                    }

                    if(!$found)
                        continue;
                }
            }


            $RNWPImporterCreatedEntry['CreatedDocuments'][]=array(
                'TemplateId'=>$generator->Options->Id,
                'Name'=>$generator->GetFileName()
            );
            $attachment[]=$path;

        }

        return $attachment;

    }

    public function SerializeEntry($entry, $formSettings)
    {
        /** @var EntryItemBase $entryItems */
        $entryItems=array();
        foreach($entry as $key=>$value)
        {
            $currentField=null;
            foreach($formSettings->Fields as $field)
            {
                if($field->Id==$key)
                {
                    $currentField=$field;
                    break;
                }
            }

            if($currentField==null)
                continue;

            $found=false;
            switch ($currentField->Type)
            {
                case 'Composed':
                    $entryItems[]=(new ComposedEntryItem())->Initialize($currentField)->SetValue(json_decode(json_encode($value)));
                    $found=true;
                    break;
                case 'Date':
                    $entryItems[]=(new DateEntryItem())->Initialize($currentField)->SetUnix($value['unix'])->SetValue($value['value']);
                    $found=true;
                    break;
                case 'Time':
                    $entryItems[]=(new TimeEntryItem())->Initialize($currentField)->SetUnix(strtotime("01/01/1970 ". $value['value']))->SetValue($value['value']);
                    $found=true;
                    break;
                case 'DateTime':
                    $entryItems[]=(new DateTimeEntryItem())->Initialize($currentField)->SetUnix($value['unix'])->SetValue($value['value']);
                    $found=true;
                    break;
                case 'FileUpload':
                    $entryItems[]=(new FileUploadEntryItem())->Initialize($currentField)->SetURL($value['value']);
                    $found=true;
                    break;


            }

            if($found)
                continue;

            switch($currentField->SubType)
            {
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'textarea':
                case 'url':
                case 'number':
                case 'number-slider':
                case 'calculation':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['value']);

                    break;
                case 'richtext':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue(str_replace("\r"," \r",strip_tags($value['value'])));
                    break;
                case 'payment-single':
                case 'payment-total':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['amount']);
                    break;
                case 'radio':
                    $value=$value['value'];
                    $value=\explode("\n",$value);
                    $entryItems[]=(new RadioEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'checkbox':
                    $value=$value['value'];
                    $value=\explode("\n",$value);
                    $entryItems[]=(new CheckBoxEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'select':
                    $value=$value['value'];
                    $value=\explode("\n",$value);
                    $entryItems[]=(new DropDownEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'payment-select':
                    if(!\is_array($value))
                    {
                        $value=[$value];
                    }
                    $amount=0;
                    if(isset($value['amount']))
                        $amount=$value['amount'];
                    $entryItems[]=(new DropDownEntryItem())->Initialize($currentField)->SetValue($value['value_choice'],$amount);
                    break;
                case 'payment-multiple':
                    if(!\is_array($value))
                    {
                        $value=[$value];
                    }
                    $amount=0;
                    if(isset($value['amount']))
                        $amount=$value['amount'];
                    $entryItems[]=(new RadioEntryItem())->Initialize($currentField)->SetValue($value['value_choice'],$amount);
                    break;

                case 'credit-card':

                    break;
                case 'name':
                    switch ($currentField->Format)
                    {
                        case 'simple':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['value'],'');
                            break;
                        case 'first-last':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['first'],$value['last']);
                            break;
                        case 'first-middle-last':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['first'],$value['last'],$value['middle']);
                            break;
                    }
                    break;
                case 'address':
                    $country='';
                    if(isset($value['country']))
                        $country=$value['country'];
                    $entryItems[]=(new WPFormAddressEntryItem())->InitializeWithValues($currentField,$value['address1'],
                        $value['address2'],$value['city'],$value['state'],$value['postal'],$country);
                    break;
                case 'date-time':

                    $time='';
                    $date='';
                    $unix=0;
                    if(isset($value['time'])&&$value['time']!='')
                    {
                        $time=$value['time'];
                        $dateObject=DateTime::createFromFormat('m/d/Y '.$currentField->TimeFormat,'1/1/1970 ' .$time,new DateTimeZone('UTC'));
                        $unix=$value['unix'];

                    }else{
                        $time='';
                    }
                    if(isset($value['date'])&&$value['date']!='')
                    {
                        $date=$value['date'];
                        $dateObject=DateTime::createFromFormat($currentField->DateFormat.' H:i:s:u',$value['date'] . "0:00:00:0",new DateTimeZone('UTC'));
                        if($dateObject!=false)
                        {
                            $unix+=$dateObject->getTimestamp();
                        }

                        $unix=$value['unix'];

                    }else{
                        $date='';
                    }

                    $entryItems[]=(new WPFormDateTimeEntryItem())->InitializeWithValues($currentField,$value['value'],$date,$time,$unix);


                    break;
                case 'file-upload':
                    $mime='';
                    $entryItems[]=(new WPFormFileUploadEntryItem())->InitializeWithValues($currentField, $value['value'],$value['file'],$value['ext'],$value['file_original']);
                    break;
            }
        }


        return $entryItems;

    }

    public function InflateEntryItem(FieldSettingsBase $field,$entryData)
    {
        $entryItem=null;
        switch ($field->Type)
        {
            case 'Composed':
                $entryItem=(new ComposedEntryItem());
                break;
            case 'Date':
                $entryItem=(new DateEntryItem());
                break;
            case 'Time':
                $entryItem=(new TimeEntryItem());
                break;
            case 'DateTime':
                $entryItem=(new DateTimeEntryItem());
                break;
            case 'FileUpload':
                $entryItem=(new FileUploadEntryItem());
                break;


        }


        if($entryItem==null)
        {
            switch ($field->SubType)
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
                case 'number':
                case 'number-slider':
                case 'richtext':
                case 'calculation':
                    $entryItem = new SimpleTextEntryItem();
                    break;
                case 'radio':
                    $entryItem = new RadioEntryItem();
                    break;
                case 'checkbox':
                    $entryItem = new CheckBoxEntryItem();
                    break;
                case 'payment-multiple':
                    $entryItem = new RadioEntryItem();
                    break;
                case 'select':
                    $entryItem = new DropDownEntryItem();
                    break;
                case 'payment-select':
                    $entryItem = new DropDownEntryItem();
                    break;
                case 'credit-card':
                    break;
                case 'name':
                    $entryItem = new WPFormNameEntryItem();
                    break;
                case 'address':
                    $entryItem = new WPFormAddressEntryItem();
                    break;

                case 'date-time':
                    $entryItem = new WPFormDateTimeEntryItem();
                    break;
                case 'file-upload':
                    $entryItem = new WPFormFileUploadEntryItem();
                    break;
            }
        }

        if($entryItem==null)
            throw new Exception("Invalid entry sub type ".$field->SubType);
        $entryItem->InitializeWithOptions($field,$entryData);
        return $entryItem;
    }


}