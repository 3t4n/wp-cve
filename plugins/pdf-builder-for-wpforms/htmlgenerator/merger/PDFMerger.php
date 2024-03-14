<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/19/2018
 * Time: 5:33 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\merger;
use Dompdf\Dompdf;
use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\PDFDocumentOptions;
use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;

class PDFMerger
{

    public $pageType;
    public $orientation;
    public $dompdf;
    /** @var Loader */
    public $Loader;

    public function __construct($loader)
    {
        $this->Loader=$loader;
    }


    /**
     * @param $itemsToMerge array()
     * @param $options PDFDocumentOptions
     * @throws \Exception
     */
    public function Merge($itemsToMerge,$options)
    {
        $pdfData=[];

        $isFirst=true;
        foreach ($itemsToMerge as $item)
        {
            $data=$this->GetPDFData($item,$options);
            if($data==null)
                continue;
            $this->PrepareToMerge($data,$isFirst);
            $pdfData[]=$data;
            $isFirst=false;
            
        }


        $this->Generate($pdfData,$options);


    }

    /**
     * @param $item
     * @param $options PDFDocumentOptions
     * @return PDFData
     */
    private function GetPDFData($entryId,$pageOptions)
    {
        $entryRetriever=$this->Loader->CreateEntryRetriever();
        if (!$entryRetriever->InitializeFromEntryId($entryId))
        {
            return null;
        }


        $generator = (new PDFGenerator($this->Loader, $pageOptions, $entryRetriever));
        $generator->Generate();
        return new PDFData($entryId,$generator->html,$generator->fieldDictionary);

    }

    /**
     * @param $data PDFData
     */
    private function PrepareToMerge(&$data,$skipDocumentBreak)
    {
        $html=$data->HTML;
        $html=\str_replace('<html>','',$html);
        $html=\str_replace('</html>','',$html);
        if($skipDocumentBreak)
            $html=\str_replace("<body class='pdfBody'>","<div class='pdfBody'>",$html);
        else
            $html=\str_replace("<body class='pdfBody'>",'<div class="pdfDocumentBreak pdfBody">',$html);
        $html=\str_replace('</body>','</div>',$html);
        $html=\str_replace('pdfField_','pdfField_2_'.$data->EntryId,$html);
        $data->HTML=$html;
        $newDictionary=array();
        foreach($data->Dictionary as $key=>$value)
        {
            $newDictionary[str_replace('pdfField_','pdfField_2_'.$data->EntryId,$key)]=$value;
        }

        $data->Dictionary=$newDictionary;
    }

    /**
     * @param $pdfData
     * @param $pageOptions PDFDocumentOptions
     */
    private function Generate($pdfData,$pageOptions)
    {
        $this->dompdf = new Dompdf();
        $this->dompdf->set_option('enable_remote', TRUE);
        $this->dompdf->getOptions()->setTempDir($this->Loader->DIR.'vendor/dompdf/dompdf/temp');
        $this->dompdf->set_option( 'dpi' , '96');
        $this->dompdf->setPaper($pageOptions->DocumentSettings->PageType, $pageOptions->DocumentSettings->Orientation);



        $this->dompdf->loadHtml($this->MergeHTML($pdfData),$this->MergeDictionary($pdfData));
        $this->dompdf->render();


    }

    public function Output()
    {
        return $this->dompdf->output();
    }

    public function Stream()
    {
        $this->dompdf->stream("Merged PDFs", array("Attachment" => false));
    }

    private function MergeHTML($pdfData)
    {
        $html='<html><body><style>.pdfDocumentBreak{page-break-before:always;}</style>';
        foreach($pdfData as $data)
        {
            $html.=$data->HTML;
        }
        $html.='</body></html>';

        return $html;
    }

    private function MergeDictionary($pdfData)
    {
        $mergedDictionary=array();
        foreach($pdfData as $data)
        {
            foreach($data->Dictionary as $key=>$value)
            {
                $mergedDictionary[$key]=$value;
            }
        }

        return $mergedDictionary;
    }


}

class PDFData{
    public $HTML;
    public $Dictionary;
    public $EntryId;

    public function __construct($entryId,$HTML, $Dictionary)
    {
        $this->EntryId=$entryId;
        $this->HTML=$HTML;
        $this->Dictionary=$Dictionary;
    }


}