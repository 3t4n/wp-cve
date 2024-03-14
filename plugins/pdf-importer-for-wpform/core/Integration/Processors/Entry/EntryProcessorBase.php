<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:02 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry;


use rnpdfimporter\api\PDFImporterApi;
use rnpdfimporter\core\htmlgenerator\generators\PDFGenerator;
use rnpdfimporter\core\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\DateTimeEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\UserEntryItem;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\DateFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\TextFieldSettings;
use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\UserFieldSettings;
use rnpdfimporter\core\Loader;
use rnpdfimporter\core\pr\Utilities\Printer\Printer;
use rnpdfimporter\JPDFGenerator\JPDFGenerator;

abstract class EntryProcessorBase
{
    /** @var Loader */
    public $Loader;
    public abstract  function InflateEntryItem(FieldSettingsBase $field,$entryData);
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    /**
     * @param $entryItems EntryItemBase []
     */
    public function SaveEntryToDB($originalFormId,&$entryItems,$originalEntryId,$raw=null){

        $itemsToSave=array();
        foreach($entryItems as $item)
        {
            $itemsToSave[]=$item->GetObjectToSave();
        }


        global $wpdb;
        $id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable.' where original_id=%d',$originalFormId));
        if($id===false)
            return 0;


        $entryId=null;
        if($originalEntryId!=null&& $originalEntryId!=''&&$originalEntryId!=0)
            $entryId=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->RECORDS_TABLE.' where original_id=%s',$originalEntryId));


        if($entryId!=null)
        {
            $wpdb->update($this->Loader->RECORDS_TABLE,array('entry'=>\json_encode($itemsToSave)),array('id'=>$entryId));
            return $entryId;
        }else
        {
            $seqKey = $this->Loader->Prefix . '_seq_' . $id . 'seq';
            $seqId = \get_option($seqKey, 1);
            $date = \date('c');

            $wpdb->insert($this->Loader->RECORDS_TABLE, array(
                'form_id' => $id,
                'original_id' => $originalEntryId,
                'date' => $date,
                'user_id' => \get_current_user_id(),
                'entry' => \json_encode($itemsToSave),
                'seq_num' => $seqId,
                'raw' => \json_encode($raw)
            ));

            $entryId = $wpdb->insert_id;
            $this->MaybePrint($id, $entryId);
            $seqId++;
            \update_option($seqKey,$seqId);
        }

        $factory=$this->Loader->CreateEntryRetriever()->GetFieldSettingsFactory();

        $entryItems[]=(new SimpleTextEntryItem())->Initialize(
            (new TextFieldSettings())->Initialize('___seq','Sequence Number','___seq')
        )->SetValue($seqId);

        $entryItems[]=(new UserEntryItem())->Initialize(
            (new UserFieldSettings())->Initialize('___usr','Sequence Number','___seq')
        )->SetUserId(\get_current_user_id());


        $date=time();
        $entryItems[]=(new DateTimeEntryItem())->Initialize(
            (new DateFieldSettings())->Initialize('___date','Sequence Number','___seq')
        )->SetValue(date('c',$date))->SetUnix($date);



        $seqId++;
        \update_option($seqKey,$seqId);
        return $wpdb->insert_id;
    }

    public function MaybeUpdateEmailBody($message,$originalFormId,$originalEntryId,$originalFields=null)
    {
        $matches=null;
        preg_match_all('/\\[bpdfimporter_download_link([^\\]]*)\\]/',$message,$matches,PREG_SET_ORDER);



        $entryRetriever=new WPFormEntryRetriever($this->Loader);
        $entryRetriever->InitializeFromEntryId($originalEntryId);
        if(count($matches)==0)
            return $message;


        $links=[];
        foreach($matches as $currentMatch)
        {
            $attrs=shortcode_parse_atts($currentMatch[1]);
            if(!is_array($attrs))
                $attrs=[];

            $templateId='';
            if(isset($attrs['template_id']))
                $templateId=$attrs['template_id'];

            $messageLink='Download PDF';
            if(isset($attrs['message']))
                $messageLink=$attrs['message'];

            $linkURL=RNPDFImporter()->GetPDFURL($originalEntryId,$templateId);

            $links[]='<a href="'.esc_attr($linkURL).'">'.esc_html($messageLink).'</a>';
            $message=str_replace($currentMatch[0],implode(",",$links),$message);

        }

        return $message;
    }

    public function AddPDFLink($content){

        if(!$this->Loader->IsPR())
            return '';

        if(!isset($content['entry_id'])){
            global $RNWPImporterCreatedEntry;
            if($RNWPImporterCreatedEntry!=null&&$RNWPImporterCreatedEntry['OriginalId']!=0)
                $content['entry_id']=$RNWPImporterCreatedEntry['OriginalId'];
        }

        $entryRetriever=$this->Loader->CreateEntryRetriever();

        if(!$entryRetriever->InitializeFromOriginalEntryId($content['entry_id']))
            return '';

        global $wpdb;
        $entry=$wpdb->get_row($wpdb->prepare('select form_id,id from '.$this->Loader->RECORDS_TABLE.' where original_id=%s',$content['entry_id']));
        if($entry===false)
            return '';


        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,attach_to_email AttachToEmail 
                    from ".$this->Loader->FormConfigTable." form
                    join ".$this->Loader->PDFImporterTable." template
                    on form.id=template.form_used
                    where form.id=%s"
            ,$entry->form_id));


        $links=[];
        foreach($result as $currentTemplate)
        {
            $generator=new JPDFGenerator($this->Loader);
            $generator->LoadByTemplateId($currentTemplate->Id);
            $generator->LoadEntry($entryRetriever);

            $data=array(
                'entryid'=>$entry->id,
                'templateid'=>$currentTemplate->Id,
                'nonce'=>\wp_create_nonce($this->Loader->Prefix.'_'.$entry->id.'_'.$currentTemplate->Id)
            );

            $url=admin_url('admin-ajax.php').'?data='.\json_encode($data).'&action='.$this->Loader->Prefix.'_public_create_pdf';
            $links[]='<a target="_blank" href="'.esc_attr($url).'">'.\esc_html($generator->GetFileName()).'</a>';
        }


        return implode($links);

    }


    /**
     * @param $entry EntryItemBase[]
     */
    public function GeneratePDF($entry)
    {

    }

    /**
     * @param $entryData
     * @param $fields FieldSettingsBase[]
     * @return EntryItemBase[]
     */
    public function InflateEntry($entryData,  $fields)
    {
        $entryItemList=array();
        foreach($entryData as $entryDataItem)
        {
            foreach($fields as $fieldItem)
            {
                if($fieldItem->Id==$entryDataItem->_fieldId)
                {
                    $entryItemList[]=$this->InflateEntryItem($fieldItem,$entryDataItem);
                }
            }

        }

        return $entryItemList;

    }

    protected function MaybePrint($formId,$entryId)
    {
        return;
        if(!$this->Loader->IsPR())
            return;
        $printer=new Printer();

        $entryRetriever=$this->Loader->CreateEntryRetriever();
        if (!$entryRetriever->InitializeFromEntryId($entryId))
        {
            return null;
        }

        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,template.pages Pages, template.document_settings DocumentSettings,styles Styles,form_id FormId
                    from ".$this->Loader->TEMPLATES_TABLE." template
                    where template.form_id=%s"
            ,$formId));

        foreach($result as $templateSettings)
        {
            $templateSettings->Pages=\json_decode($templateSettings->Pages);
            $templateSettings->DocumentSettings=\json_decode($templateSettings->DocumentSettings);

            if(!isset($templateSettings->DocumentSettings->PrintWhenCreated)||!$templateSettings->DocumentSettings->PrintWhenCreated)
                continue;

            $generator=(new PDFGenerator($this->Loader,$templateSettings,$entryRetriever));
            $printer=new Printer();
            $printer->PrintPDF($generator->GetFileName(),$generator->GetPrintableOutput(),$templateSettings->DocumentSettings->PrinterId);

        }


    }


}