<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 6:20 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\PDFDocumentOptions;
use rednaoformpdfbuilder\htmlgenerator\utils\Formatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rednaoformpdfbuilder\pr\Managers\ConditionManager\ConditionManager;
use rednaoformpdfbuilder\pr\Utilities\FontManager;
use rednaoformpdfbuilder\Utils\Sanitizer;


class DocumentGenerator
{
    /**
     * @var PDFDocumentOptions
     */
    public $options;
    /** @var EntryRetrieverBase */
    public $orderValueRetriever;
    /** @var Loader */
    private $loader;
    public $Formatter;
    public $FieldsToHide;
    /** @var DocumentGenerator */
    public static $LatestDocument;
    public function __construct($loader,$options,$orderValueRetriever)
    {
        $this->FieldsToHide=[];
        $this->loader=$loader;
        $this->options = $options;
        $this->orderValueRetriever = $orderValueRetriever;
        $this->Formatter=new Formatter($options);
        self::$LatestDocument=$this;
    }

    public function Generate(){
        $html="<html><body class='pdfBody' data-page-id='1'>";
        if(isset($this->options->DocumentSettings->BaseStyles))
        {
            $html .= "<style>";
            $html.=$this->options->DocumentSettings->BaseStyles;
            $html .= "</style>";
        }
        $html.='<style>
                                @font-face{
                                    font-family:\'FontAwesome\';
                                    src:url("'.$this->loader->DIR .'css/fontAwesome/fonts/fontawesome-webfont.ttf");
                                    }
                                
                                
          .tablefield > tbody > tr > td {
                padding:3px;
            }       
                                    
    .PDFElement.CustomField,.PDFElement.ConditionalField {
                text-align:left;
                min-height: 40px;
             }
             
             .PDFElement.Text p{
                line-height: 1.2em;
             }
             .fileUploadItem{
                margin-bottom: 10px;
             }
             
             .fileUploadItem:last-child{
                margin-bottom: 0;
             }
             
             .PDFElement.CustomField .FieldLabel,.PDFElement.ConditionalField .FieldLabel{
                font-weight:bold;    
                vertical-align:top;            
             }
             
             .PDFElement.CustomField Table,.PDFElement.ConditionalField Table{
                width:100%;
             }
             
             .PDFElement.CustomField .FieldLabel .Top,.PDFElement.ConditionalField .FieldLabel .Top{              
                margin-bottom:2px;
             }
             .PDFElement.CustomField .FieldLabel .Bottom,,.PDFElement.ConditionalField .FieldLabel .Bottom{              
                margin-top:2px;
             }
             .PDFElement.CustomField .FieldLabel .Left,,.PDFElement.ConditionalField .FieldLabel .Left{              
                margin-right:2px;
             }
             .PDFElement.CustomField .FieldLabel .Right,,.PDFElement.ConditionalField .FieldLabel .Right{              
                margin-left:2px;
             }
                            
                                body{
                                    font-family:\'dejavu sans\';                                  
                                }
                           
                                @page{
                                    margin:0;
                                }                            
                 </style>';

        $this->AdjustStyles();
        $html.='<style>'.$this->options->Styles.'</style>';


        if($this->loader->IsPR())
        {
            $fontManager=new FontManager($this->loader);
            $fonts=$fontManager->GetAvailableFonts(false);
            $html.='<style>';
            $fontURL=$fontManager->GetFontPath();
            foreach($fonts as $currentFont){
                $html.= " @font-face{font-family:\"$currentFont\";
              src:url(\"".$fontURL.urlencode($currentFont).".ttf\");
                }";
            }
            $html.='</style>';
        }
        if($this->loader->IsPR()&&$this->orderValueRetriever!=null)
        {
            if(isset($this->options->DocumentSettings->Conditions))
            {
                foreach($this->options->DocumentSettings->Conditions as $currentCondition)
                {
                    $showWhenTrue=Sanitizer::SanitizeBoolean($currentCondition->ShowWhenTrue);
                    $conditionManager=new ConditionManager();
                    $isValid=$conditionManager->ShouldProcess($this->loader, $this->orderValueRetriever,$currentCondition);
                    if(($showWhenTrue&&!$isValid)||(!$showWhenTrue&&$isValid))
                    {
                        if(is_array($currentCondition->Fields))
                            foreach($currentCondition->Fields as $currentField)
                            {
                                $this->FieldsToHide[]=$currentField;
                            }
                    }

                }
            }




        }

        /** @var PageGenerator $tempPage */
        $tempPage=null;
        if($this->HasRepeatableHeader())
        {
            if($tempPage==null)
                $tempPage=new PageGenerator($this->loader,$this,null,$this->orderValueRetriever,-1);

            $areaGenerator=new AreaGenerator($tempPage,$this->loader, $this->GetRepeatableHeader(),$this->options->DocumentSettings->RepeatableHeaderField,$this->orderValueRetriever);
            $html.= $areaGenerator->Generate();
        }

        if($this->HasRepeatableFooter())
        {
            if($tempPage==null)
                $tempPage=new PageGenerator($this->loader,$this,null,$this->orderValueRetriever,-1);

            $areaGenerator=new AreaGenerator($tempPage,$this->loader, $this->GetRepeatableFooter(),$this->options->DocumentSettings->RepeatableFooterField,$this->orderValueRetriever);
            $html.= $areaGenerator->Generate();
        }

        for($i=0;$i<count($this->options->Pages);$i++)
        {
            $pageGenerator=new PageGenerator($this->loader,$this, $this->options->Pages[$i],$this->orderValueRetriever,$i);
            $html.=$pageGenerator->Generate();
        }


        $html.="</body></html>";
        return $html;
    }

    /**
     * @return FieldDTO[]
     */
    public function GetFieldsDictionary(){
        $dictionary=array();

        foreach($this->options->Pages as $page)
        {
            foreach ($page->Fields as $field)
            {
                $field->Page=$page;
                $dictionary['pdfField_' . $field->Id] = $field;
            }
        }

        return $dictionary;
    }


    public function HasRepeatableHeader()
    {
        return isset($this->options->DocumentSettings->ShowRepeatableHeader)&&$this->options->DocumentSettings->ShowRepeatableHeader==true;
    }

    public function HasRepeatableFooter()
    {
        return isset($this->options->DocumentSettings->ShowRepeatableFooter)&&$this->options->DocumentSettings->ShowRepeatableFooter==true;
    }

    public function GetRepeatableFooter()
    {
        if(isset($this->options->DocumentSettings->RepeatableFooter))
            return $this->options->DocumentSettings->RepeatableFooter;

        return null;
    }

    public function GetRepeatableHeader()
    {
        if(isset($this->options->DocumentSettings->RepeatableHeader))
            return $this->options->DocumentSettings->RepeatableHeader;

        return null;
    }

    private function AdjustStyles()
    {
        if(isset($this->options->DocumentSettings->BackgroundImageURL)&&$this->options->DocumentSettings->BackgroundImageURL!=''&&isset($this->options->DocumentSettings->BackgroundImageId)&&$this->options->DocumentSettings->BackgroundImageId!='')
        {
            $path=get_attached_file($this->options->DocumentSettings->BackgroundImageId);
            $this->options->Styles=str_replace($this->options->DocumentSettings->BackgroundImageURL,$path,$this->options->Styles);

        }


    }

}


