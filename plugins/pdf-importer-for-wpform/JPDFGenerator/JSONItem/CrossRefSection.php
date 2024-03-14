<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


class CrossRefSection extends JSONItemBase
{

    public function InternalGetText()
    {
        $sections=$this->GetFromData('Sections');

        $str="xref\n";
        $i=0;
        foreach($sections as $currentSection)
        {

            $str.=$currentSection[0]->ref->objectNumber;
            $str.=" ";
            $str.=count($currentSection);
            $str.="\n";

            if($i==count($sections)-1)//add inserted objects to last section
            {
                /** @var IndirectObjectJsonItem $insertedObject */
                foreach($this->Generator->InsertedIndirectObjects as $insertedObject)
                {
                    $entry=new \stdClass();
                    $entry->offset=0;
                    $entry->deleted=false;
                    $entry->ref=new \stdClass();
                    $entry->ref->objectNumber=$insertedObject->GetObjectNumber();
                    $entry->ref->generationNumber=0;
                    $entry->ref->tag=$insertedObject->GetObjectNumber().' 0 R';
                    $currentSection[]=$entry;
                }
            }


            foreach($currentSection as $entry)
            {
                if($entry->ref->generationNumber==65535)
                {
                    $str.=$this->GetEntryText($entry);
                }else
                {

                    $object=$this->Generator->GetObjectByGenerationAndObjectNumber($entry->ref->generationNumber,$entry->ref->objectNumber);
                    if($object!=null)
                    {
                        $entry->offset=$object->Offset;
                    }
                    $str .= $this->GetEntryText($entry);
                }
            }

            $i++;

        }
        $this->Offset=\mb_strlen($str);
        return $str;
    }

    private function GetEntryText($entry)
    {
        $entryStr=\str_pad(\strval($entry->offset),10,'0',\STR_PAD_LEFT);
        $entryStr.=' ';
        $entryStr.=\str_pad(\strval($entry->ref->generationNumber),5,'0',\STR_PAD_LEFT);
        $entryStr.=' ';
        $entryStr.=$entry->deleted?'f':'n';
        $entryStr.=' ';
        $entryStr.="\n";
        return $entryStr;
    }


}