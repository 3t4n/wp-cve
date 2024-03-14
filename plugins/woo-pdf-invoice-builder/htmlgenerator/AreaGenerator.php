<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 11:25 AM
 */

namespace rnwcinv\htmlgenerator;


use rnwcinv\htmlgenerator\fields\FieldFactory;
use rnwcinv\htmlgenerator\fields\PDFField;

class AreaGenerator
{
    /** @var ContainerBaseDTO */
    private $options;

    /** @var FieldDTO[] */
    private $fields;
    public $tagGenerator;
    private $orderValueRetriever;

    /** @var PageGenerator $PageGenerator */
    private $PageGenerator;
    public function __construct($options, $fields,$orderValueRetriever,$pageGenerator)
    {

        $this->tagGenerator=new TagGenerator();
        $this->options = $options;
        $this->fields = $fields;
        $this->orderValueRetriever = $orderValueRetriever;
        $this->PageGenerator=$pageGenerator;
    }

    public function Generate(){
        $areaStyles=array('height'=>$this->options->height.'px','width'=>$this->options->width.'px');

        switch ($this->options->position)
        {
            case 'repeaterHeader':
                $areaStyles['position'] = 'fixed';
                $areaStyles['overflow'] = 'hidden';
                $areaStyles['top'] = '0px';
                //$areaStyles['background-color']='pink';
                break;
            case 'repeaterFooter':
                $areaStyles['position'] = 'fixed';
                $areaStyles['overflow'] = 'hidden';
                $areaStyles['bottom'] = '0px';
               // $areaStyles['background-color']='green';
                break;
            case 'documentHeader':
                $top=0;
                $areaStyles['position'] = 'relative';
               // $areaStyles['background-color']='red';
                if($this->PageGenerator->DocumentGenerator->HasRepeatableHeader())
                {
                    $headerOptions=$this->PageGenerator->DocumentGenerator->GetRepeatableHeader();
                    if($headerOptions!=null)
                    {
                        $top=$headerOptions->height;
                    }
                }
                $areaStyles['top']=$top.'px';
                break;
            case 'documentFooter':
                $bottom=0;
                if($this->PageGenerator->DocumentGenerator->HasRepeatableFooter())
                {
                    $footerOptions=$this->PageGenerator->DocumentGenerator->GetRepeatableFooter();
                    if($footerOptions!=null)
                    {
                        $bottom=$footerOptions->height;
                    }

                }
                $areaStyles['position'] = 'absolute';
                $areaStyles['overflow'] = 'hidden';
                $areaStyles['bottom'] = $bottom.'px';
                //$areaStyles['background-color']='red';
                break;
            case 'documentContent':
               // $areaStyles['background-color']='blue';
                $top=0;
                $areaStyles['position'] = 'relative';
                if($this->PageGenerator->DocumentGenerator->HasRepeatableHeader())
                {
                    $headerOptions=$this->PageGenerator->DocumentGenerator->GetRepeatableHeader();
                    if($headerOptions!=null)
                    {
                        $top=$headerOptions->height;
                    }
                }
                $areaStyles['top']=$top.'px';
                break;

            default:
                $areaStyles['position'] = 'relative';


        }



        $html=$this->tagGenerator->StartTag('div',$this->options->position,$areaStyles,array('data-section-type'=>$this->options->position));
        foreach($this->fields as $field)
        {
            /** @var PDFField $createdField */
            $createdField=FieldFactory::GetField($field,$this->orderValueRetriever);
            $html.=$createdField->GetHTML();

        }

        $html.='</div>';
        return $html;
    }

}