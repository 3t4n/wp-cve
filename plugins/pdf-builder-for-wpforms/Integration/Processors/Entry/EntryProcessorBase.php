<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:02 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\core\Repository\EntryRepository;
use rednaoformpdfbuilder\core\Repository\FormRepository;
use rednaoformpdfbuilder\core\Repository\LinkRepository;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rednaoformpdfbuilder\pr\Utilities\Printer\Printer;

abstract class EntryProcessorBase
{
    /** @var Loader */
    public $Loader;
    public abstract  function InflateEntryItem(FieldSettingsBase $field,$entryData);
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function MaybeUpdateEmailBody($message,$originalFormId,$originalEntryId,$originalFields=null)
    {
        $matches=null;
        preg_match_all('/\\[bpdfbuilder_download_link([^\\]]*)\\]/',$message,$matches,PREG_SET_ORDER);


        if(count($matches)==0)
            return $message;

        $entryRepository=new EntryRepository($this->Loader);
        $formRepository=new FormRepository($this->Loader);
        $linkRepository=new LinkRepository($this->Loader);


        $formId=$formRepository->GetFormIdFromOriginalId($originalFormId);
        $templates=$formRepository->GetTemplatesForForm($formId);

        if(count($templates)==0)
            return $message;
        if($formId==null)
            return $message;


        $entryId=$entryRepository->GetEntryIdFromOriginalId($originalEntryId);
        if($entryId==null){
            if($originalFields==null)
                return $message;

            $entryId=$this->SaveOriginalEntry($originalFormId,$originalEntryId,$originalFields);
            if($entryId==false)
                return $message;
        }

        $links=[];
        foreach($matches as $currentMatch)
        {
            $attrs=shortcode_parse_atts($currentMatch[1]);
            if(!is_array($attrs))
                $attrs=[];

            $templateId='';
            if(isset($attrs['template_id']))
                $templateId=$attrs['template_id'];
            $templates=$formRepository->GetTemplatesForForm($formId);
            foreach($templates as $currentTemplate)
            {
                if($currentTemplate->Id==$templateId||$templateId=='')
                {
                    $link=$linkRepository->GetOrCreateDownloadURL($currentTemplate->Id,$entryId);
                    $name=$entryRepository->GetPDFFileName($currentTemplate->Id,$entryId);

                    if($link==null||$name==null)
                        continue;
                    $links[]='<a href="'.$link.'">'.esc_html($name).'</a>';
                }
            }

            $message=str_replace($currentMatch[0],implode(",",$links),$message);

        }

        return $message;
    }

    public abstract function SerializeEntry($entry,$formSettings);

    public function SaveOriginalEntry($originalFormId,$originalEntryId,$originalFields)
    {
        $formSettings=$this->Loader->ProcessorLoader->FormProcessor->GetFormByOriginalId($originalFormId);
        if($formSettings==null)
            return false;

        $serializedEntry=$this->SerializeEntry($originalFields,$formSettings);
        if(count($serializedEntry)==0)
            return false;

        $entryId=$this->SaveEntryToDB($originalFormId,$serializedEntry,$originalEntryId,array('Fields'=>$originalFields));
        return $entryId;
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
        }else{

            if($originalEntryId!=null&&$originalEntryId>0)
            {
                $seqId=$originalEntryId;
            }else
            {
                $seqKey = $this->Loader->Prefix . '_seq_' . $id . 'seq';
                $seqId = \get_option($seqKey, 1);
                \update_option($seqKey,$seqId);
            }
            $date= \date('c');
            $wpdb->insert($this->Loader->RECORDS_TABLE,array(
                'form_id'=>$id,
                'original_id'=>$originalEntryId,
                'date'=> $date,
                'user_id'=>\get_current_user_id(),
                'entry'=>\json_encode($itemsToSave),
                'seq_num'=>$seqId,
                'raw'=>\json_encode($raw)
            ));
            $entryId=$wpdb->insert_id;
            $seqId++;
            $this->MaybePrint($id,$entryId);
        }

        return $entryId;
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

    /**
     * @param $templateSettings
     * @param $generator PDFGenerator
     * @return int
     */
    public function MaybeSendToDrive($templateSettings,$generator){

        if(file_exists($generator->Loader->DIR.'pr/addons/drive/DriveApi.php'))
        {
            if(isset($templateSettings->DocumentSettings)&&isset($templateSettings->DocumentSettings->DriveEnabled)
                &&$templateSettings->DocumentSettings->DriveEnabled==true&&
                $templateSettings->DocumentSettings->FolderToUse!=''&&
                $templateSettings->DocumentSettings->DriveJSONConfig!=''
            )
            {
                require_once $generator->Loader->DIR.'pr/addons/drive/DriveApi.php';
                try
                {
                    $dive = new \DriveApi($generator->Loader, $templateSettings->DocumentSettings->DriveJSONConfig);
                    $dive->InsertFile($generator->GetFileName(), $generator->GetOutput(), $templateSettings->DocumentSettings->FolderToUse);
                }catch (\Exception $e)
                {

                }
            }


        }
    }

    protected function MaybePrint($formId,$entryId)
    {
        if(!$this->Loader->IsPR())
            return;
        $printer=new Printer($this->Loader);

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
            $printer=new Printer($this->Loader);
            $printer->PrintPDF($generator->GetFileName(),$generator->GetPrintableOutput(),$templateSettings->DocumentSettings->PrinterId);

        }


    }


}