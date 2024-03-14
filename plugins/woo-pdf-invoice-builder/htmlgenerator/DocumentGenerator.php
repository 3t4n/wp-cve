<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 6:20 AM
 */

namespace rnwcinv\htmlgenerator;


use rnwcinv\pr\utilities\FontManager;
use rnwcinv\utilities\ArrayUtils;
use rnwcinv\utilities\Sanitizer;

class DocumentGenerator
{
    /**
     * @var DocumentOptionsDTO
     */
    public $options;
    /** @var OrderValueRetriever */
    private $orderValueRetriever;
    private $PagesToHide=[];
    public function __construct($options,$orderValueRetriever)
    {
        $this->options = $options;
        $this->orderValueRetriever = $orderValueRetriever;
    }

    public function HasRepeatableHeader()
    {
        return isset($this->options->containerOptions->showRepeatableHeader)&&$this->options->containerOptions->showRepeatableHeader==true;
    }

    public function GetRepeatableFooter()
    {
        if(isset($this->options->containerOptions->RepeatableFooter))
            return $this->options->containerOptions->RepeatableFooter;

        return null;
    }

    public function GetRepeatableHeader()
    {
        if(isset($this->options->containerOptions->RepeatableHeader))
            return $this->options->containerOptions->RepeatableHeader;

        return null;
    }

    public function HasRepeatableFooter()
    {
        return isset($this->options->containerOptions->showRepeatableFooter)&&$this->options->containerOptions->showRepeatableFooter==true;
    }

    public function Generate(){
        $this->ProcessPagesToHide();
        $this->BeforeGeneratingHTML();

        $html="<html><body class='pdfBody' data-page-id='1'>";
        $html.='<style>

                .table-meta
                {
                    margin-top: 10px;
                }
                .table-meta p{
                    margin:0;
                    padding:0;
                    vertical-align: top;
                    display: inline-block;
                }


                                @font-face{
                                    font-family:\'FontAwesome\';
                                    src:url("'.\RednaoWooCommercePDFInvoice::$DIR.'css/fontAwesome/fonts/fontawesome-webfont.ttf");
                                    }
                                    
                           .PDFElement table{
                            border-collapse: collapse;
                           }    
                         
                           
                           .PDFElement table td,.PDFElement table th{
                            padding:0;
                            margin:0;
                           }
                                    
                          .total .woocommerce-Price-amount {
							   display:block;
							}
    
                            
                                body{
                                    font-family:\'DejaVu Sans\';
                                    font-weight: normal;                                
                                    font-size: 14px;
                                }
                                p{
                                    line-height: 1em !important;
                                    margin:0;
                                    padding:0;
                                }
                                @page{
                                    margin:0;
                                }                            
                 </style>';
        $html.='<style>'.$this->options->containerOptions->styles.'</style>';

        if(\RednaoWooCommercePDFInvoice::IsPR())
        {
            $fontManager=new FontManager();
            $fonts=$fontManager->GetAvailableFonts(false);
            $html.='<style>';
            $fontURL=$fontManager->GetFontPath();
            foreach($fonts as $currentFont){
                $html.= $fontManager->GetFontFace($currentFont);
            }
            $html.='</style>';
        }

        $firstPage=null;

        $pagesCode='';
        for($i=0;$i<count($this->options->pages);$i++)
        {
            if(ArrayUtils::Some($this->PagesToHide,function ($item)use($i){return $item==$i+1;}))
                continue;
            $pageGenerator=new PageGenerator($this,$this->options->pages[$i],$this->orderValueRetriever,$i);
            if($firstPage==null)
                $firstPage=$pageGenerator;
            $pagesCode.=$pageGenerator->Generate();
        }


        if($this->HasRepeatableHeader())
        {
            $areaGenerator=new AreaGenerator($this->GetRepeatableHeader(),$this->options->containerOptions->RepeatableHeaderField,$this->orderValueRetriever,$firstPage);
            $html.= $areaGenerator->Generate();
        }

        if($this->HasRepeatableFooter())
        {
            $areaGenerator=new AreaGenerator($this->GetRepeatableFooter(),$this->options->containerOptions->RepeatableFooterField,$this->orderValueRetriever,$firstPage);
            $html.= $areaGenerator->Generate();
        }
        $html.=$pagesCode."</body></html>";
        return $html;
    }

    /**
     * @return FieldDTO[]
     */
    public function GetFieldsDictionary(){
        $dictionary=array();

        foreach($this->options->pages as $page)
        {
            foreach ($page->fields as $field)
                $dictionary['pdfField_'.$field->fieldID]=$field;
        }

        return $dictionary;
    }

    private function BeforeGeneratingHTML()
    {
        if($this->orderValueRetriever!=null&&!$this->orderValueRetriever->useTestData&&$this->orderValueRetriever->order!=null)
        {
            $currency=Sanitizer::SanitizeString($this->orderValueRetriever->order->get_meta('_woocs_order_currency',true),'');

            if($currency=='')
            {
                $currency = $this->orderValueRetriever->order->get_currency();
            }

            global $WOOCS;
            if($currency!=null&&$WOOCS!=null)
            {
                if(method_exists($WOOCS,'get_currencies'))
                {
                    $currencies=$WOOCS->get_currencies();
                    if(is_array($currencies)&&isset($currencies[$currency]))
                        $WOOCS->set_currency($currency);
                }

            }

        }
    }

    private function ProcessPagesToHide()
    {
         $this->PagesToHide=[];
        if(!\RednaoWooCommercePDFInvoice::IsPR()||$this->orderValueRetriever->useTestData)
            return;

        if(!isset($this->options->containerOptions->hidePageCondition))
            return;
        $pagesToHide=$this->options->containerOptions->hidePageCondition;
        if(count($pagesToHide)==0)
            return;

        require_once \RednaoWooCommercePDFInvoice::$DIR.'pr/conditions/ConditionManager.php';

        $manager=new \ConditionManager($this->orderValueRetriever);
        foreach($pagesToHide as $currentPageToHide)
        {
            if($manager->ShouldProcess($currentPageToHide))
            {
                $this->PagesToHide=array_merge($this->PagesToHide,explode(',',$currentPageToHide->pagesToAffect));
            }
        }
    }


}


