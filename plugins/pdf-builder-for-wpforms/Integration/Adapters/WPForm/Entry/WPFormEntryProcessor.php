<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:03 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry;


use DateTime;
use DateTimeZone;
use Exception;
use rednaoformpdfbuilder\core\Managers\LogManager;
use rednaoformpdfbuilder\htmlgenerator\generators\FileManager;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormAddressEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormDateTimeEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormFileUploadEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormNameEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormSignatureEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\CheckBoxEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\DropDownEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\HtmlEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\RadioEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\RatingEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryProcessorBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rednaoformpdfbuilder\pr\Utilities\Activator;
use rednaoformpdfbuilder\Utils\Sanitizer;

class WPFormEntryProcessor extends EntryProcessorBase
{
    public function __construct($loader)
    {
        parent::__construct($loader);


        \add_action('wpforms_post_insert_',array($this,'UpdateOriginalEntryId'),10,2);
        \add_action('wpforms_process_entry_save',array($this,'SaveEntry'),10000000,4);
        \add_action('wpforms_entry_email_process',array($this,'ProcessEmail'),10,5);
        \add_filter('wpforms_emails_send_email_data',array($this,'AddAttachmentNew'),10,2);
        add_action('wpforms_pro_admin_entries_edit_submit_completed',array($this,'EditEntry'),10,4);
        add_action('wpforms_entry_details_sidebar_actions',array($this,'GenerateSideBarAction'),10,2);
        add_filter( "bulk_actions-entries", array($this,'AddBulkActions') );add_filter( 'handle_bulk_actions_entries',array($this,'HandleBulkAction'), 10, 3);
        add_action( 'admin_enqueue_scripts', array($this,'EnqueueScript'));


        //\add_action('wpforms_email_attachments',array($this,'AddAttachment'),10,2);

       /* \add_filter(
            'wpforms_tasks_entry_emails_trigger_send_same_process',array($this,'SendSameProcess'));*/
        \add_shortcode('bpdfbuilder_download_link',array($this,'AddPDFLink'));
        \add_action('init',array($this,'MaybeStartSession'));
    }

    public function AddBulkActions($actions){
        if(!$this->Loader->IsPR())
        {
            $actions['pdfbuilder_view_pdf'] ='Bulk view pdf (Diamond Version Required)';
            $actions['pdfbuilder_download_pdf'] ='Bulk download (Diamond Version Required)';
            return $actions;
        }
        $license=Activator::GetLicense($this->Loader);
        if($license->PriceType!=3)
            return $actions;
        $actions['pdfbuilder_view_pdf'] ='Bulk view pdf';
        $actions['pdfbuilder_download_pdf'] ='Bulk download';
        return $actions;
    }

    public function EnqueueScript(){
        global $typenow;
        $screen=get_current_screen();
        if($screen==null||$screen->id!='wpforms_page_wpforms-entries'||!$this->Loader->IsPR())
            return;



        $license=Activator::GetLicense($this->Loader);
        if($license->PriceType!=3)
            return;



        global $wpdb;
        $templates=$wpdb->get_results($wpdb->prepare('select temp.id Id, temp.name Name from '.$this->Loader->TEMPLATES_TABLE .' temp join '.$this->Loader->FormConfigTable.' form on form.id=temp.form_id where original_id=%d order by name',intval($_GET['form_id'])));

        if($templates>0)
        {
            \wp_enqueue_script('pdfbulder_bulk_manager',$this->Loader->URL.'js/dist/BulkManager.js');
            \wp_localize_script('pdfbulder_bulk_manager', 'bulkManagerVar', array(
                'templates' => $templates,
                'selectAPDFTemplate' => 'Select a pdf template'
            ));
        }

    }

    public function GenerateSideBarAction($entry,$formData){

        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,template.name Name
                    from ".$this->Loader->FormConfigTable." form
                    join ".$this->Loader->TEMPLATES_TABLE." template
                    on form.id=template.form_id
                    where original_id=%s"
            ,$formData['id']));

        if(!current_user_can('administrator')||!$this->Loader->IsPR())
            return;
        foreach($result as $pdfTemplate)
        {
            echo '
                <p class="wpforms-entry-star">
                    <a href="'.esc_attr(admin_url( 'admin-ajax.php' )) .'?action='.esc_attr($this->Loader->Prefix).'_generate_pdf_from_original&entryid='.esc_attr($entry->entry_id).
                '&templateid='.esc_attr($pdfTemplate->Id).'&nonce='.esc_attr(wp_create_nonce('generate_'.$pdfTemplate->Id.'_'.$entry->entry_id)).'">
                        <span class="dashicons dashicons-pdf"></span>View '.esc_html($pdfTemplate->Name).'
                    </a>
                </p>
            ';
        }
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
        if(!\rednaoformpdfbuilder\Utils\Sanitizer::SanitizeBoolean(get_option($this->Loader->Prefix.'_skip_save',false)))
            $entryId=$this->SaveEntryToDB($formData['id'],$serializeEntry,$entryId,array('Fields'=>$fields));


    }

    public function MaybeStartSession(){
        if (!session_id()&&!headers_sent()) {
            session_start([ 'read_and_close' => true]);
        }
    }

    public function AddPDFLink($attrs,$content){


        $message='Click here to download';
        if(isset($attrs['message']))
            $message=$attrs['message'];

        $templateId=null;
        $entryId=null;

        if(isset($attrs['templateid']))
            $templateId=$attrs['templateid'];

        if(isset($attrs['entryid']))
        {
            $entryId=$attrs['entryid'];
        }

        if($entryId==null)
        {

            if(!isset($_SESSION['PDFBuilder_Latest_Entry']))
            {
                return;
            }

            $entryId=$_SESSION['PDFBuilder_Latest_Entry'];
            if($entryId===null)
                return;
        }

        if($templateId==null)
        {
            if(!isset($_SESSION['WPForm_Generated_PDF']))
                return;
            $pdfData=$_SESSION['WPForm_Generated_PDF'];

            if(!isset($pdfData['TemplateId']))
                return;

            $templateId=$pdfData['TemplateId'];

        }


        $entrySource='Internal';
        if(isset($attrs['entrysource']))
        {
            $entrySource=$attrs['entrysource'];
        }


        $nonce=\wp_create_nonce('view_'.$entryId.'_'.$templateId.'_'.$entrySource);
        $url=admin_url('admin-ajax.php').'?action='.$this->Loader->Prefix.'_view_pdf'.'&nonce='.\urlencode($nonce).'&templateid='.$templateId.'&entryid='.$entryId.'&entrysource='.$entrySource;
        return "<a target='_blank' href='".esc_attr($url)."'>".\esc_html($message)."</a>";

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
        global $RNWPCreatedEntry;
        if(!isset($RNWPCreatedEntry)||!isset($RNWPCreatedEntry['Entry']))
            return;

        global $wpdb;
        $wpdb->update($this->Loader->RECORDS_TABLE,array(
            'original_id'=>$entryId
        ),array('id'=>$RNWPCreatedEntry['EntryId']));

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

        $serializeEntry=$this->SerializeEntry((Object)['fields'=>$fields],$formSettings);


        $pdfTemplates=array();
        if(isset($formData['meta']['pdfTemplates']))
            $pdfTemplates=$formData['meta']['pdfTemplates'];


        $entryId=$entry['id'];
        if(!\rednaoformpdfbuilder\Utils\Sanitizer::SanitizeBoolean(get_option($this->Loader->Prefix.'_skip_save',false)))
            $entryId=$this->SaveEntryToDB($formData['id'],$serializeEntry,isset(wpforms()->process->entry_id)?wpforms()->process->entry_id:0,array('Fields'=>$fields));



        $_SESSION['PDFBuilder_Latest_Entry']=$entryId;
        $pdfTemplates[]=array('EntryId'=>$entryId);
        $formData['meta']['pdfTemplates']=$pdfTemplates;
        global $RNWPCreatedEntry;
        $RNWPCreatedEntry=array(
            'Entry'=>$serializeEntry,
            'FormId'=>$formData['id'],
            'EntryId'=>$entryId,
            'Raw'=>json_decode( \json_encode(array('Fields'=>$fields))),
            "RawEntry" => $entry,
            "RawFormData" => $formData
        );
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

    public function AddAttachment($attachment,$target,$wpFormSettings)
    {

        $fm=new FileManager($this->Loader);
        $fm->RemoveTempFolders();

        global $wpdb;
        $entryRetriever=new WPFormEntryRetriever($this->Loader);

        $entryId='';
        $creationDate='';

        global $RNWPCreatedEntry;

        if(isset($wpFormSettings->entry_id)&&$wpFormSettings->entry_id!=0)
            $entryId=$wpFormSettings->entry_id;
        else
        {

            if($RNWPCreatedEntry!=null&&isset($RNWPCreatedEntry['EntryId']))
                $entryId=$RNWPCreatedEntry['EntryId'];
        }

        LogManager::LogDebug('Checking if pdf should be attached to email for entry '.($entryId==''?'null':$entryId));


        if(wpforms()->pro)
        {
            $entry=wpforms()->entry->get( $wpFormSettings->entry_id);
            if($entry!=null)
                $creationDate=$entry->date;
            else{
                $creationDate=Sanitizer::GetStringValueFromPath($RNWPCreatedEntry,['RawFormData','created'],'');

            }
        }

        if($creationDate=='')
            $creationDate=date('c');


        $formProcessor = new WPFormFormProcessor($this->Loader);
        $formSettings = $formProcessor->SerializeForm(array(
            "ID" => $wpFormSettings->form_data['id'],
            'post_title' => '',
            'post_content' => \json_encode(array('fields' => $wpFormSettings->form_data['fields']))
        ));
        $entryRetriever->InitializeByEntryItems($this->SerializeEntry($wpFormSettings,$formSettings),(object)[
            'entry_id'=>$entryId,
            'form_id'=>$wpFormSettings->form_data['id'],
            'fields'=>$wpFormSettings->fields
        ],null,$entryId,$creationDate);




        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,template.pages Pages, template.document_settings DocumentSettings,styles Styles,form_id FormId
                    from ".$this->Loader->FormConfigTable." form
                    join ".$this->Loader->TEMPLATES_TABLE." template
                    on form.id=template.form_id
                    where original_id=%s"
            ,$entryRetriever->GetFormId()));
        $files=[];


        if(is_wp_error($result))
        {
            LogManager::LogDebug('Error getting pdf templates for form '.$wpdb->last_error);
            return $attachment;
        }

        if(count($result)==0)
        {
            LogManager::LogDebug('No pdf templates found for form '.$entryRetriever->GetFormId());
            return $attachment;
        }

        if(!isset($RNWPCreatedEntry['CreatedDocuments'])){
            $RNWPCreatedEntry['CreatedDocuments']=[];
        }
        foreach($result as $templateSettings)
        {
            $templateSettings->Pages=\json_decode($templateSettings->Pages);
            $templateSettings->DocumentSettings=\json_decode($templateSettings->DocumentSettings);

            LogManager::LogDebug('Checking if template '.$templateSettings->Id.' should be attached to email');

            if(isset($templateSettings->DocumentSettings->Notifications)&&count($templateSettings->DocumentSettings->Notifications)>0)
            {
                global $WPFormEmailBeingProcessed;
                if(isset($WPFormEmailBeingProcessed))
                {
                    $found=false;
                    foreach($templateSettings->DocumentSettings->Notifications as $attachToNotificationId)
                    {
                        if($attachToNotificationId==$WPFormEmailBeingProcessed)
                            $found=true;
                    }

                    if(!$found)
                    {
                        LogManager::LogDebug('There is no email that matches the current template, skipping');
                        continue;
                    }
                }
            }




            $generator=(new PDFGenerator($this->Loader,$templateSettings,$entryRetriever));
            if(!$generator->ShouldAttach())
            {
                LogManager::LogDebug('There is a condition in the pdf preventing it from being added');
                continue;
            }
            $path=$generator->SaveInTempFolder();
            LogManager::LogDebug('PDF Generated at'.$path);

            $RNWPCreatedEntry['CreatedDocuments'][]=array(
                'TemplateId'=>$generator->options->Id,
                'Name'=>$generator->options->DocumentSettings->FileName
            );

            $path=apply_filters($this->Loader->Prefix.'_pdf_attached_to_email',$path,$RNWPCreatedEntry,$templateSettings->Id);


            $_SESSION['test']='1';
            $_SESSION['WPForm_Generated_PDF']=array(
                'TemplateId'=>$generator->options->Id,
                'EntryId'=>$RNWPCreatedEntry['EntryId']
            );

            if($path!='')
            {
                $this->MaybeSendToDrive($templateSettings,$generator);
                $attachment[] = $path;
            }

        }

        return $attachment;

    }

    public function SerializeEntry($entry, $formSettings)
    {

        /** @var EntryItemBase $entryItems */
        $entryItems=array();
        foreach($entry->fields as $key=>$value)
        {
            if($value['type']!='file-upload'&&(isset($value['visible'])&&!$value['visible']))
                continue;
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

            switch($currentField->SubType)
            {
                case 'signature':
                    $entryItems[]= (new WPFormSignatureEntryItem())->Initialize($currentField)->SetValue($value['value']);
                    break;
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'textarea':
                case 'url':
                case 'number':
                case 'number-slider':
                case 'calculator':
                case 'number_format':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['value']);
                    break;
                case 'rating':
                    $entryItems[]=(new RatingEntryItem())->Initialize($currentField)->SetValue($value['value']);
                    break;
                case 'payment-single':
                case 'payment-total':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['amount_raw']);
                    break;
                case 'radio':
                    $value=$value['value'];
                    $value=\explode("\n",$value);
                    $entryItems[]=(new RadioEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'checkbox':
                case 'payment-checkbox':
                case 'likert_scale':
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
                    if(isset($value['amount_raw']))
                        $amount=$value['amount_raw'];
                    $entryItems[]=(new DropDownEntryItem())->Initialize($currentField)->SetValue($value['value_choice'],$amount);
                    break;
                case 'payment-multiple':
                    if(!\is_array($value))
                    {
                        $value=[$value];
                    }
                    $amount=0;
                    if(isset($value['amount_raw']))
                        $amount=$value['amount_raw'];
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
                    $name='';
                    $names=[];
                    if(isset($value['value_raw'])&&isset($value['value_raw'][0])&&isset($value['value_raw'][0]['name']))
                    {
                        $name = $value['value_raw'][0]['name'];
                        foreach ($value['value_raw'] as $currentItem)
                            $names[]=$currentItem['name'];
                    }

                    $entryItems[]=(new WPFormFileUploadEntryItem())->InitializeWithValues($currentField, $value['value'],'','',$name,$names);
                    break;
                case 'richtext':
                    $entryItems[]=(new HtmlEntryItem())->Initialize($currentField)->SetValue($value['value']);
                    break;

            }
        }

        if(isset($formSettings))
            foreach($formSettings->Fields as $currentField)
            {
                if($currentField->SubType=='content')
                {
                    $currentField->Label='';
                    $entryItems[]=(new HtmlEntryItem())->Initialize($currentField)->ExecuteShortcodes()->SetValue($currentField->Content);
                }

                if($currentField->SubType=='html')
                {
                    $currentField->Label='';
                    $entryItems[]=(new HtmlEntryItem())->Initialize($currentField)->ExecuteShortcodes()->SetValue($currentField->Content);
                }
            }


        return $entryItems;

    }

    public function InflateEntryItem(FieldSettingsBase $field,$entryData)
    {
        $entryItem=null;
        switch($field->SubType)
        {
            case 'signature':
                $entryItem=new WPFormSignatureEntryItem();
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
            case 'number':
            case 'number-slider':
            case 'number_format':
            case 'calculator':
                $entryItem= new SimpleTextEntryItem();
                break;
            case 'rating':
                $entryItem=new RatingEntryItem();
                break;
            case 'radio':
                $entryItem= new RadioEntryItem();
                break;
            case 'checkbox':
            case 'payment-checkbox':
            case 'likert_scale':
                $entryItem= new CheckBoxEntryItem();
                break;
            case 'payment-multiple':
                $entryItem= new RadioEntryItem();
                break;
            case 'select':
                $entryItem= new DropDownEntryItem();
                break;
            case 'payment-select':
                $entryItem= new DropDownEntryItem();
                break;
            case 'credit-card':
                break;
            case 'name':
                $entryItem= new WPFormNameEntryItem();
                break;
            case 'address':
                $entryItem=  new WPFormAddressEntryItem();
                break;

            case 'date-time':
                $entryItem= new WPFormDateTimeEntryItem();
                break;
            case 'file-upload':
                $entryItem= new WPFormFileUploadEntryItem();
                break;
            case 'richtext':
                $entryItem= new HtmlEntryItem();
        }

        if($entryItem==null)
            throw new Exception("Invalid entry sub type ".$field->SubType);
        $entryItem->InitializeWithOptions($field,$entryData);
        return $entryItem;
    }


}