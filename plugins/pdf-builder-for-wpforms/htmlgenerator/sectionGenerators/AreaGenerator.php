<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 11:25 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators;




use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\DocumentOptions;
use rednaoformpdfbuilder\DTO\PDFControlBaseOptions;
use rednaoformpdfbuilder\DTO\SectionOption;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\FieldFactory;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\PDFFieldBase;

class AreaGenerator
{
    /** @var Loader */
    public $Loader;
    /** @var SectionOption */
    private $options;

    /** @var PDFControlBaseOptions[] */
    private $fields;
    public $tagGenerator;
    private $orderValueRetriever;
    /** @var PageGenerator */
    public $PageGenerator;
    public $IsEmpty;

    public function __construct($pageGenerator,$loader,$options, $fields,$orderValueRetriever)
    {
        $this->IsEmpty=true;
        $this->PageGenerator=$pageGenerator;
        $this->Loader=$loader;
        $this->tagGenerator=new TagGenerator();
        $this->options = $options;
        $this->fields = $fields;
        $this->orderValueRetriever = $orderValueRetriever;
    }

    public function Generate(){
        $areaStyles=array('height'=>$this->options->Height.'px','width'=>'100%');
        if($this->options->Type=='repeatableHeader'){
            $areaStyles['position'] = 'fixed';
            $areaStyles['overflow'] = 'hidden';
            $areaStyles['top'] = '0px';
        }else if($this->options->Type=='repeatableFooter')
        {
            $areaStyles['position'] = 'fixed';
            $areaStyles['overflow'] = 'hidden';
            $areaStyles['bottom'] = '0px';
        }else if($this->options->Type=='footer')
        {
            $bottom=0;
            if($this->PageGenerator->documentGenerator->HasRepeatableFooter())
            {

                $footerOptions=$this->PageGenerator->documentGenerator->GetRepeatableFooter();
                if($footerOptions!=null) {
                    $bottom = $footerOptions->Height;
                }
            }

            $areaStyles['position'] = 'absolute';
            $areaStyles['overflow'] = 'hidden';
            $areaStyles['bottom'] = $bottom.'px';
        }else{
            $areaStyles['position'] = 'relative';
            $top=0;
            if($this->PageGenerator->documentGenerator->HasRepeatableHeader())
            {
                $headerOptions=$this->PageGenerator->documentGenerator->GetRepeatableHeader();
                if($headerOptions!=null)
                    $top=$headerOptions->Height;
            }

            $areaStyles['top']=$top.'px';
        }

        $html=$this->tagGenerator->StartTag('div',$this->options->Type,$areaStyles,array('data-section-type'=>$this->options->Type));
        foreach($this->fields as $field)
        {
            /** @var PDFFieldBase $createdField */
            $createdField=FieldFactory::GetField($this->Loader,$this, $field,$this->orderValueRetriever);
            if($createdField!=null) {
                if(!$createdField->ShouldBeHidden()) {
                    $this->IsEmpty=false;
                    $html .= $createdField->GetHTML();
                }
            }

        }

        $html.='</div>';
        return $html;
    }

}