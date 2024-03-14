<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */
namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;

use rednaoformpdfbuilder\DTO\FieldSummaryOptions;
use rednaoformpdfbuilder\htmlgenerator\tableCreator\HTMLTableCreator;
use rednaoformpdfbuilder\Utils\Sanitizer;

class PDFSummary extends PDFFieldBase
{

    /** @var FieldSummaryOptions */
    public $options;

    protected function InternalGetHTML()
    {
       $creator='<div class="SummaryTable">';



       $sortedFields=array();
       if($this->entryRetriever!=null)
       {
           $originalFields=$this->entryRetriever->OriginalFieldSettings;
           foreach ($originalFields as $formField)
           {
               foreach($this->options->Fields as $configuredFields)
                   if($configuredFields->Id==$formField->Id)
                       $sortedFields[]=$configuredFields;
           }


       }else
           $sortedFields=$this->options->Fields;

        $fieldsToSort=$this->options->Fields;

        $sortBy=$this->GetPropertyValue('SortBy');

        switch ($sortBy)
        {
            case 'form':
                $sortedFields=[];
                $dictionary=$this->entryRetriever->FieldDictionary;
                foreach($dictionary as $currentField)
                {
                    foreach($fieldsToSort as $field)
                    {
                        if($field->Id==$currentField->Field->Id)
                        {
                            $sortedFields[]=$field;

                        }
                    }
                }
                break;
            case 'alpha':
                usort($fieldsToSort,function($a,$b){
                    return strcmp($a->Label,$b->Label);
                });
                $sortedFields=$fieldsToSort;
                break;
            default:
                $sortedFields=$fieldsToSort;
        }



        if($this->GetPropertyValue('Format')=='compact')
        {
            $labelWidth=100;
            $valueWidth=100;
            $count=0;
            foreach($sortedFields as $field)
            {
                $labelWidth=Sanitizer::SanitizeNumber($this->GetPropertyValue('LabelWidth'),0);

                if($labelWidth==0)
                    $labelWidth=30;


                $valueWidth=100-$labelWidth-4;

                $value='';
                if($this->entryRetriever==null)
                    $value='<p>Value not available in preview</p>';
                else
                {
                    $style='standard';

                    if(isset($field->FieldSettings)&&isset($field->FieldSettings->Style)&&$field->FieldSettings->Style!='')
                    {
                        $style=$field->FieldSettings->Style;
                    }
                    $value = $this->entryRetriever->GetHtmlByFieldId($field->Id,$style);
                    if ($value==null||$value->IsEmpty())
                        if($this->GetPropertyValue("IncludeEmptyFields",false)==false)
                            continue;
                        else
                            $value='<div style="height: 30px"></div>';
                }

                $labelToUse=$field->Label;
                if(isset($field->FieldSettings->Label)&&trim($field->FieldSettings->Label)!='')
                    $labelToUse=$field->FieldSettings->Label;


                $creator.='<div style="vertical-align:top;width:100%;padding:2px" class="'.($count % 2 == 0?'CompactRow Odd':'CompactRow Even').'">';
                $creator.='<div class="CompactFieldLabel" style="vertical-align: top;width:'.$labelWidth.'% !important;display: inline-block"><p>'.esc_html($labelToUse).'</p></div>';
                $creator.='<div class="CompactFieldValue" style="vertical-align: top;width:'.$valueWidth.'% !important;display: inline-block">'.$value.'</div>';
                $creator.='</div>';
                $count++;
            }

        }else

       foreach($sortedFields as $field)
       {
           $value='';
           if($this->entryRetriever==null)
                $value='Value not available in preview';
           else
           {
               $style='standard';

               if(isset($field->FieldSettings)&&isset($field->FieldSettings->Style)&&$field->FieldSettings->Style!='')
               {
                   $style=$field->FieldSettings->Style;
               }
               $value = $this->entryRetriever->GetHtmlByFieldId($field->Id,$style);
               if ($value==null||$value->IsEmpty())
                   if($this->GetPropertyValue("IncludeEmptyFields",false)==false)
                    continue;
                   else
                       $value='<div style="height: 30px"></div>';
           }

           $labelToUse=$field->Label;

           if(isset($field->FieldSettings->Label)&&trim($field->FieldSettings->Label)!='')
               $labelToUse=$field->FieldSettings->Label;

           $creator.='<div class="FieldLabel">'.esc_html($labelToUse).'</div>';
           $creator.='<div class="FieldValue">'.$value.'</div>';
       }
       $creator.='</div>';
       return $creator;


    }
}