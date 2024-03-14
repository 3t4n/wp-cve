<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/18/2017
 * Time: 6:09 PM
 */

namespace rednaoformpdfbuilder\htmlgenerator\generators;


use Dompdf\Dompdf;
use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\core\Managers\LogManager;
use rednaoformpdfbuilder\core\Managers\SlateTextGenerator;
use rednaoformpdfbuilder\DTO\DocumentOptions;

use rednaoformpdfbuilder\DTO\PDFDocumentOptions;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\DocumentGenerator;
use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rednaoformpdfbuilder\pr\Managers\ConditionManager\ConditionManager;
use rednaoformpdfbuilder\Utils\TextProcessor\TextProcessor;

class PDFGenerator
{
    public $folders=array();
    public $tempFolder;
    public $orientation;

    /** @var Loader  */
    public $Loader;
    /**
     * @var Dompdf
     */
    private $dompdf;
    public $extensions;
    public $pdfName;
    private $invoiceNumber;
    private $formattedInvoiceNumber;

    /** @var DocumentGenerator  */
    public $htmlGenerator;

    private $date;
    public $html=null;
    public $fieldDictionary=null;
    /** @var PDFDocumentOptions */
    public $options;
    /** @var EntryRetrieverBase */
    public $EntryRetriever;

    /**
     * RednaoPDFGenerator constructor.
     * @param $loader Loader
     * @param $options DocumentOptions
     * @param bool $useTestData
     * @param $order
     */
    public function __construct($loader, $options,$entryRetriever)
    {
        error_reporting(E_ERROR );
        TextProcessor::$Loader=$loader;
        $this->Loader=$loader;
        require_once $loader->DIR. 'vendor/autoload.php';
        $this->EntryRetriever=$entryRetriever;
        if($options==null)
            return;

        $this->options=$options;
        if(!isset($options->Pages))
            throw new \Exception('Pages options not defined');
        $this->htmlGenerator=new DocumentGenerator($this->Loader, $options,$entryRetriever);




        if(!isset($options->DocumentSettings->PageType)||!isset($options->DocumentSettings->Width))
            throw new \Exception("Page size was not defined");
    }

    public function GetInvoiceTemplateId(){
        if($this->options==null)
            return 0;
        return $this->options->Id;
    }



    public function GetFileName($index=0){
        $generator=new SlateTextGenerator($this->EntryRetriever);
        if(!isset($this->options->DocumentSettings->FormattedFileName)||!\is_object($this->options->DocumentSettings->FormattedFileName))
            return 'Document.pdf';

        $text= $generator->GetText($this->options->DocumentSettings->FormattedFileName);
        if($text=='')
            return 'Document';

        return sanitize_file_name($text);
    }

    public function GeneratePreview(){
  /*      ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
*/
        $this->Generate();
       // $this->dompdf->output();
        $this->dompdf->stream($this->GetFileName(), array("Attachment" => false));
    }

    public function Generate()
    {



        add_filter('wp_die_ajax_handler',array($this,'MaybeLogError'));


     //   error_reporting(E_ERROR);
        LogManager::LogDebug('Starting pdf generation');
        try
        {
            $this->dompdf = new Dompdf(null, $this);
            $this->dompdf->set_option('enable_remote', TRUE);
            $this->dompdf->set_option('logOutputFile', FALSE);
            $this->dompdf->getOptions()->setTempDir($this->Loader->DIR . 'vendor/dompdf/dompdf/temp')->setIsFontSubsettingEnabled(false);
            $this->dompdf->set_option('dpi', '96');
            $this->fieldDictionary = $this->htmlGenerator->GetFieldsDictionary();
            $this->html = $this->htmlGenerator->Generate();
            $this->dompdf->loadHtml($this->html, $this->fieldDictionary);
            $this->dompdf->setPaper($this->options->DocumentSettings->PageType, $this->options->DocumentSettings->Orientation);
            $this->dompdf->render();
        }catch (\Throwable $e)
        {
            LogManager::LogDebug("An error occurred while generating the pdf \n".$e->getMessage()."\n".$e->getTraceAsString());
            throw $e;
        }

        remove_filter('wp_die_ajax_handler',array($this,'MaybeLogError'));
        LogManager::LogDebug('PDF Generation ended');
    }

    public function MaybeLogError($handler)
    {
        $error =\error_get_last();
        LogManager::LogDebug('An error occurred while generating the pdf '.$error['message']);
        return $handler;
    }

    public function AlreadyRenderer(){
        return $this->dompdf!=null;
    }

    public function GetOutput(){
        if(!$this->AlreadyRenderer())
            $this->Generate();
        return $this->dompdf->output();
    }

    public function GetPrintableOutput(){
        if(!$this->AlreadyRenderer())
            $this->Generate();
        return $this->dompdf->printableOutput();
    }

    public function SaveInTempFolder()
    {
        $fileManager=new FileManager($this->Loader);
        $temp=$fileManager->GetTemporalFolderPath();

        $output=$this->GetOutput();

        $fileName=$this->GetFileName();

        $matches=array();

        if($this->Loader->IsPR())
        {
            \preg_match_all('/\\{field([^\\}]*)}/', $fileName, $matches, \PREG_SET_ORDER);
            foreach ($matches as $match)
            {
                $id = trim($match[1]);
                $value = $this->htmlGenerator->orderValueRetriever->GetHtmlByFieldId($id);
                $value = \strip_tags($value);

                $fileName = \str_replace($match[0], $value, $fileName);

            }
        }

        $fileName=preg_replace('/[^A-Za-z0-9_\-]/', '', $fileName);
        $path=$temp.$fileName.'.pdf';
        \file_put_contents($path,$output);
        return $path;
    }



    public function ShouldAttach()
    {
        if(!$this->Loader->IsPR())
            return true;

        $conditionManager=new ConditionManager();

        if(!isset($this->options->DocumentSettings->SkipGenerationCondition))
            return true;


        return !$conditionManager->ShouldSkip($this->Loader, $this->EntryRetriever,$this->options->DocumentSettings->SkipGenerationCondition);

    }


    private function GetUploadDir(){
        $uploadDir=wp_upload_dir();
        return $uploadDir['basedir'].'/'.$this->Loader->Prefix.'_temp';
    }

    public function Destroy(){
        if(strlen($this->tempFolder)<=0)
            return;
        $files = glob($this->tempFolder . '/*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($this->tempFolder);
    }


}