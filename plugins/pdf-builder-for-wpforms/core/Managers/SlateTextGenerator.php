<?php


namespace rednaoformpdfbuilder\core\Managers;



use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rednaoformpdfbuilder\Utils\Sanitizer;

class SlateTextGenerator
{
    /** @var EntryRetrieverBase */
    public $Retriever;

    /**
     * SlateTextGenerator constructor.
     * @param $retriver EntryRetrieverBase
     */
    public function __construct($retriever)
    {
        $this->Retriever=$retriever;
    }

    public function GetText($content)
    {
        if(!isset($content->document)||!isset($content->document->nodes))
            return '';

        $text='';

        foreach($content->document->nodes as $paragraph)
        {
            if($paragraph->type!='paragraph')
                continue;
            foreach ($paragraph->nodes as $node)
            {
                switch ($node->object)
                {
                    case 'text':
                        $text .= $this->GetValueFromTextNode($node);
                        break;
                    case 'inline':
                        $text .= $this->GetValueFromFieldNode($node);
                        break;
                }
            }
        }

        return $text;

    }

    private function GetValueFromTextNode($node)
    {
        if(!isset($node->leaves))
            return '';

        $text='';
        foreach($node->leaves as $leaf)
        {
            $text.=$leaf->text;
        }

        return $text;
    }

    private function GetValueFromFieldNode($node)
    {
        if($this->Retriever==null)
            return '';
        $fieldData=$node->data;
        switch ($fieldData->SubType)
        {
            case 'field':
                $field= $this->Retriever->GetValueById($fieldData->FieldId);
                if($field!=null)
                    return $field->GetHtml()->ToText();
                return '';
            case 'fixed':
                switch($fieldData->FieldId)
                {
                    case 'submission_date':
                        $dateIntegration=new DateIntegration($this->Model->Loader);
                        return $dateIntegration->GetTimezonedDateFromUTCDate(date('c',$this->Model->Entry->UnixDate));
                    case 'submission_number':
                        return $this->Model->Entry->FormattedSequence;
                    case 'submission_total':
                        return $this->Model->Entry->Total;

                }
            case 'custom_field':
                global $wpdb;

                $code=$wpdb->get_var($wpdb->prepare('select code from '.$this->Retriever->Loader->CUSTOM_FIELD .' where id=%s',Sanitizer::GetStringValueFromPath($node,['data','FieldId'])));
                if($code=='')
                    return '';

                $Retriever=$this->Retriever;
                $Actions=$this;
                $Entry=new \stdClass();
                foreach($this->Retriever->EntryItems as $item)
                {
                    if(isset($item->Value))
                    {
                        $propertyName='Field_' . $item->Field->Id;
                        $Entry->$propertyName = $item->Value;
                    }
                }

                try
                {

                    $value = eval($code);
                }catch (\Exception $e)
                {
                    return '';
                }

                return $value;

        }

    }


}