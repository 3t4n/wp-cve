<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


use Exception;
use rnpdfimporter\JPDFGenerator\JSONItem\Streams\RawJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\Streams\StreamJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\FieldJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields\ButtonPDFField;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields\ChoicePDFField;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields\FieldWithParent;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields\ImagePDFField;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields\SignaturePDFField;
use rnpdfimporter\JPDFGenerator\JSONItem\SubDictionary\PDFFields\TextPDFField;

class JSONFactory
{
    static function GetItem($generator,$parent,$object)
    {
        switch ($object->Type)
        {
            case 'XR':
                return new CrossRefSection($generator,$parent,$object);
            case 'AJ':
                return new ArrayJSONItem($generator, $parent,$object);
            case 'DI':
                return new DictionaryObjectItem($generator, $parent,$object);
            case 'PC':
                return new PrecompiledJSONItem($generator, $parent,$object);
            case 'PS':
                return new PrecompiledStringJSONItem($generator, $parent,$object);
            case 'SR':
                return new RawJSONItem($generator, $parent,$object);
            case "IO":
                return new IndirectObjectJsonItem($generator, $parent,$object);
            case 'FD':
                foreach( $object->Dict as $item)
                {
                    if($item->Key=='/FT')
                    {
                        switch ($item->Value->Text)
                        {
                            case '/Tx':
                                return new TextPDFField($generator, $parent,$object);
                            case '/Btn':
                                $auxDict=new DictionaryObjectItem($generator,$parent,$object);
                                $ff=$auxDict->GetNumberValue('/Ff',0);
                                if($ff&1<<16)
                                {
                                    return new ImagePDFField($generator, $parent,$object);
                                }else
                                    return new ButtonPDFField($generator, $parent,$object);
                                break;
                            case '/Ch':
                                return new ChoicePDFField($generator, $parent,$object);
                            case '/Sig':
                                return new SignaturePDFField($generator, $parent,$object);

                        }
                    }

                    if($item->Key=='/Parent')
                        return new FieldWithParent($generator,$parent,$object);


                }



                return new DictionaryObjectItem($generator,$parent,$object);
            case 'TR':
                return new TrailerJsonItem($generator,$parent,$object);
            case 'PR':
                return new PrecompiledRawJSONItem($generator,$parent,$object);

        }

        throw new Exception('Invalid object type '.$object->Type);

    }


    static function ObjectToJsonItem($generator,$parent,$object){
        if($object==null)
            return null;

        if($object instanceof \stdClass)
            $object=(array)$object;
        if(is_array($object))
        {
            if(array_keys($object) !== range(0, count($object) - 1))
            {
                $dic=new DictionaryObjectItem($generator,$parent,$object);
                foreach($object as $key=>$value)
                {
                    $value=JSONFactory::ObjectToJsonItem($generator,$dic,$value);
                    if($value!=null)
                        $dic->SetValue($key,$value);
                }

                return $dic;
            }else{
                $array=new ArrayJSONItem($generator,$parent,null);
                foreach($object as $value)
                {
                    $value=JSONFactory::ObjectToJsonItem($generator,$array,$value);
                    if($value!=null)
                        $array->Items[]=$value;

                }
            }
        }

        if($object instanceof JSONItemBase)
            return $object;

        return  RawStringJSONItem::CreateFromText($generator,$parent,$object);

    }


}