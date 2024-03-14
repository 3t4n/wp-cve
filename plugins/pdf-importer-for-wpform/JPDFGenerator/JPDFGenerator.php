<?php


namespace rnpdfimporter\JPDFGenerator;


use Exception;
use rnpdfimporter\core\db\TemplateRepository;
use rnpdfimporter\core\Integration\FileManager;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\FormulaEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rnpdfimporter\core\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rnpdfimporter\core\Loader;
use rnpdfimporter\DTO\DocumentManagerOptions;
use rnpdfimporter\JPDFGenerator\Appearance\Font\Font;
use rnpdfimporter\JPDFGenerator\JSONItem\ArrayJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\IndirectObjectJsonItem;
use rnpdfimporter\JPDFGenerator\JSONItem\JSONFactory;
use rnpdfimporter\JPDFGenerator\JSONItem\JSONItemBase;
use rnpdfimporter\JPDFGenerator\JSONItem\PrecompiledJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\RawStringJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\FieldJSONItem;
use rnpdfimporter\Lib\Cpdf\Cpdf;
use rnpdfimporter\Lib\Cpdf\FontMetrics;
use rnpdfimporter\pr\core\Parser\Elements\ParseMain;
use rnpdfimporter\SlateGenerator\SlateTextGenerator\SlateTextGenerator;
use rnpdfimporter\Utilities\Sanitizer;

class JPDFGenerator
{
    /** @var DocumentManagerOptions */
    public $Options;
    public $data;
    /** @var JSONItemBase[] */
    public $Items;
    /** @var FieldJSONItem[] */
    public $PDFFields;
    /** @var Loader */
    public $Loader;
    private $PDFFileName;
    /** @var EntryRetrieverBase */
    public $EntryRetriever;
    /** @var IndirectObjectJsonItem[] */
    public $IndirectObjects;
    public $LastIndirectObjectIndex=0;
    public $LastIndirectObjectNumber=0;
    public $InsertedIndirectObjects;
    /** @var Cpdf */
    public $CPDF;
    /** @var  IndirectObjectJsonItem[] */
    public $Pages;

    public function __construct($loader, $data=null)
    {
        error_reporting(E_ERROR | E_PARSE);
        $this->Pages=[];
        $this->InsertedIndirectObjects=[];
        $this->Fonts=[];
        $this->EntryRetriever=null;
        $this->Loader=$loader;
        $this->data=$data;
        $this->PDFFileName='';
        $this->IndirectObjects=[];
        $this->Items=[];

        if($data!=null)
            $this->GenerateItems();
    }

    public function LoadByTemplateId($templateId)
    {
        $templateRepository=new TemplateRepository($this->Loader);
        $template=$templateRepository->GetTemplateById($templateId);
        $this->Options=$template;
        if($template==null)
            return false;

        $fileName=$template->PDFURL;
        $fileManager=new FileManager($this->Loader);

        $filePath=$fileManager->GetPDFFolderPath().$fileName;
        if(!\file_exists($filePath))
            return false;

        $json=\json_decode(\file_get_contents($filePath));
        if($json===false)
            return false;

        $this->data=$json;

        $this->GenerateItems();;

        global $importerGenerator;
        $importerGenerator=$this;

    }

    public function GetDefaultFont(){

        $font=Sanitizer::GetStringValueFromPath($this->Options,['AdditionalSettings','Font']);
        if(!empty($font))
            return new Font($this,$font);
        else
            return new Font($this,'Default');



    }

    public function GetObjectByTag($tag)
    {
        $parts=explode(' ',$tag);
        if(count($parts)<2)
            return null;
        return $this->GetObjectByGenerationAndObjectNumber($parts[1],$parts[0]);

    }

    public function GetObjectByGenerationAndObjectNumber($generation,$objectNumber)
    {
        foreach($this->IndirectObjects as $currentObject)
            if($currentObject->GetObjectNumber()==$objectNumber&&$currentObject->GetGenerationNumber()==$generation)
                return $currentObject;

        return null;
    }
    private function GenerateItems()
    {
        if(!isset($this->data->Items))
            return;

        $this->PDFFields=[];
        $i=0;
        foreach($this->data->Items as $item)
        {

            $item=JSONFactory::GetItem($this,null,$item);
            $this->Items[]=$item;
            if($item instanceof IndirectObjectJsonItem)
            {
                $type=$item->GetValue('/Type');
                if($type!=null&&$type->GetText()=='/Page')
                {
                    $this->Pages[]=$item;
                }
                $this->LastIndirectObjectNumber=max($item->GetObjectNumber(),$this->LastIndirectObjectNumber);
                $this->IndirectObjects[] = $item;
                $this->LastIndirectObjectIndex=$i;
            }
            if($item instanceof IndirectObjectJsonItem&&$item->Object instanceof FieldJSONItem)
                $this->PDFFields[] = $item->Object;

            $i++;
        }


        $this->CPDF=new Cpdf(array(0,0,200,200),true,'',$this->Loader,$this);

    }

    public function Output()
    {
        $output='';
        $offset=0;
        foreach ($this->Items as $item)
        {
            $output.=$item->GetText($offset);
            $offset+=$item->Length;

        }


        return $output;
    }

    public function SaveInTempFolder()
    {
        $output=$this->Output();
        $fileManager=new FileManager($this->Loader);
        $path=$fileManager->GetTemporalFolderPath().$this->GetFileName();

        \file_put_contents($path,$output);
        return $path;
    }

    public function GetPDFFieldById($fieldId)
    {
        foreach($this->PDFFields as $field)
            if($field->GetId()==$fieldId)
                return $field;

            return null;
    }

    public function LoadEntry($entryRetriever)
    {
        $this->EntryRetriever=$entryRetriever;
        foreach($this->Options->FieldSettings as $fieldSetting)
        {
       /*     if($fieldSetting->MappedTo=='')
                continue;*/

            $field=$this->GetPDFFieldById($fieldSetting->Id);
            if($field==null)
                continue;

            $formField=null;
            if($fieldSetting->MappedTo=='___formula')
            {
                $value='';
                foreach($fieldSetting->Formulas as $currentFormula)
                {
                    if($currentFormula->Name=='value')
                    {
                        $parser=new ParseMain($entryRetriever,$currentFormula->Compiled);
                        $formField=new FormulaEntryItem($parser);
                    }
                }

            }else
                $formField=$entryRetriever->GetFieldById($fieldSetting->MappedTo);

            if($formField==null)
            {
                $formField=new SimpleTextEntryItem();
                $formField->InitializeWithString(null,'');
            }

            $field->SetFieldValue($formField);

        }

        $this->InsertCPDFObjects();



    }

    public function GetFileName()
    {
        if($this->PDFFileName!='')
            return $this->PDFFileName;
        if($this->EntryRetriever==null)
            return 'Document.pdf';


        $pdfFileName=$this->Options->PDFFileName;
        $slateTextGenerator=new SlateTextGenerator($this->Loader, $this->EntryRetriever);
        $name=$slateTextGenerator->GetText($pdfFileName);

        if($name=='')
            $name='Document';
        $name=\sanitize_file_name($name);
        $name.='.pdf';
        $this->PDFFileName=$name;

        return $this->PDFFileName;
    }

    public function GetNextIndirectObjectNumber(){
        return ++$this->LastIndirectObjectNumber;


    }

    public function InsertIndirectObject(IndirectObjectJsonItem $cs,$setObjectNumber=true)
    {
        array_splice($this->Items,$this->LastIndirectObjectIndex+1,0,array($cs));
        if($setObjectNumber)
        {
            $cs->SetObjectNumber(++$this->LastIndirectObjectNumber);
            $cs->SetGenerationNumber(0);
            $this->LastIndirectObjectNumber = max($this->LastIndirectObjectNumber, $cs->GetObjectNumber());
            $this->LastIndirectObjectIndex++;
        }
        $this->IndirectObjects[]=$cs;
        $this->InsertedIndirectObjects[]=$cs;

    }



    public function RemoveIndirectObject($objectNumber)
    {
        return;
        for($i=0;$i<count($this->Items);$i++)
        {
            if($this->Items[$i] instanceof IndirectObjectJsonItem&&$this->Items[$i]->GetObjectNumber()==$objectNumber)
            {
                array_splice($this->Items,$i,1);
                foreach($this->Pages as $currentPage)
                {
                    $annotValue=$currentPage->GetValue('/Annots');
                    if($annotValue instanceof ArrayJSONItem)
                    {
                        for($i=0;$i<count($annotValue->Items);$i++)
                        {
                            if($annotValue->Items[$i]->GetText()==$objectNumber.' 0 R') {
                                array_splice($annotValue->Items, $i, 1);
                                $i--;
                            }
                        }
                    }
                }
            }
        }
    }

    private function InsertCPDFObjects()
    {
        $objectsToAdd=['font','toUnicode','fontDescendentCID','cidSystemInfo','fontGIDtoCIDMap','fontDescriptor','contents','image'];

        foreach($this->CPDF->objects as $id=>$currentObject)
        {
            if(!isset($currentObject['t'])|| array_search($currentObject['t'],$objectsToAdd)===false)
                continue;



            $methodName='o_'.$currentObject['t'];
            $res=$this->CPDF->$methodName($id, 'out');


            $indirect=new IndirectObjectJsonItem($this,null,RawStringJSONItem::CreateFromText($this,null,$res));
            $indirect->SetObjectNumber($id);

            $this->InsertIndirectObject($indirect,false);




        }
    }

    /**
     * @param JSONItemBase
     */
    public function InsertAsIndirectObject($item)
    {
        if(!$item instanceof JSONItemBase)
            return null;
        $io=new IndirectObjectJsonItem($this,null,$item);
        $this->InsertIndirectObject($io);
        return $io;
    }


}