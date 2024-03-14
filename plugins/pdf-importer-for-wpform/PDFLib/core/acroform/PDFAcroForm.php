<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\PDFContext;

class PDFAcroForm
{
    /** @var PDFDict */
    public $dict;
    public static function fromDict($dict)
    {
        return new PDFAcroForm($dict);
    }
    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict=$context->obj((object)array('Fields'=>array()));
        return new PDFAcroForm($dict);
    }

    public function __construct($dict)
    {
        $this->dict=$dict;
    }

    public function Fields(){
        $fields=$this->dict->lookup(PDFName::of('Fields'));
        if($fields instanceof PDFArray) return $fields;
        return null;
    }

    public function getFields(){
        $fields=$this->normalizedEntries();
        /** @var PDFArray $fields */
        $Fields=$fields['Fields'];

        $fields=ReferenceArray::withSize($Fields->size());
        for ($idx = 0, $len = $Fields->size(); $idx < $len; $idx++) {
            $ref = $Fields->get($idx);
            $dict = $Fields->lookup($idx, PDFDict::class);
            $fields[$idx] = new ReferenceArray([utils::createPDFAcroField($dict, $ref), $ref]);
        }

        return $fields;
    }

    public function getAllFields() {
        $allFields=new ReferenceArray();

        $pushFields =function ($fields)use($allFields,&$pushFields) {
          if (!$fields) return;
          for ($idx = 0, $len = $fields->length; $idx < $len; $idx++) {
            $field = $fields[$idx];
            $allFields[]=$field;
            $fieldModel= $field[0];
            if ($fieldModel instanceof PDFAcroNonTerminal) {
              $pushFields(utils::createPDFAcroFields($fieldModel->Kids()));
            }
        }
        };

        $pushFields($this->getFields());

        return $allFields;
    }

    public function addField($field) {
        $Fields= $this->normalizedEntries();
        if(isset($Fields['Fields']))
        {
            $Fields=$Fields['Fields'];
            $Fields->push($field);
        }

      }

      public function normalizedEntries() {
          $Fields = $this->Fields();

        if (!$Fields) {
            $Fields = $this->dict->context->obj([]);
            $this->dict->set(PDFName::of('Fields'), $Fields);
        }

        return $Fields['Fields'];
      }

}