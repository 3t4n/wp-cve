<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/18/2017
 * Time: 6:09 PM
 */

//require_once RednaoWooCommercePDFInvoice::$DIR.'vendor/smarty/Smarty.class.php';

use rnDompdf\Dompdf;
use rnwcinv\compatibility\DocumentOptionsCompatibility;
use rnwcinv\hooks\PDFCreated\PDFCreatedHook;
use rnwcinv\htmlgenerator\DocumentGenerator;
use rnwcinv\htmlgenerator\DocumentOptionsDTO;
use rnwcinv\htmlgenerator\FieldDTO;
use rnwcinv\htmlgenerator\OrderValueRetriever;
use rnwcinv\htmlgenerator\PageOptionsDTO;


use rnwcinv\pr\CustomField\utilities\CustomFieldValueRetriever;
use rnwcinv\pr\Manager\TagManager;
use rnwcinv\pr\Translation\PDFTranslatorFactory;
use rnwcinv\pr\Translation\WPMLTranslator;
use rnwcinv\utilities\InvoiceInitialDataGenerator;

class RednaoPDFGenerator
{
    public $folders=array();
    public $tempFolder;
    /** @var \rnwcinv\htmlgenerator\FieldDTO[]  */
    public $pageType;
    public $orientation;
    /**
     * @var Dompdf
     */
    protected $dompdf;
    public $extensions;
    public $pdfName;
    private $invoiceNumber;
    private $formattedInvoiceNumber;
    /** @var ValueRetriever OrderValueRetriever */
    public $orderValueRetriever;
    /** @var DocumentGenerator  */
    public $htmlGenerator;
    private $date;
    public $html=null;
    public $fieldDictionary=null;
    public $pageWidth;
    public $pageHeight;
    /** @var DocumentOptionsDTO */
    public $options;
    public $PreviousResult=null;
    private $SkipSavingPDF=false;

    public function __construct($options,$useTestData=false,$order=null)
    {
        error_reporting(E_ERROR );
        if($options==null)
            return;

        $this->options=$options;

        $order=$this->MaybeFilterOrder($order);

        $this->orderValueRetriever=new OrderValueRetriever($this,$options,$useTestData,$order,null);


        $options=DocumentOptionsCompatibility::execute($options);

        if($useTestData==false)
        {
            $this->IncreaseInvoiceCount();
        }
        if(!isset($options->containerOptions))
            throw new Exception('Container options not defined');
        $this->order=$order;
        $this->extensions=$options->extensions;
        $this->htmlGenerator=new DocumentGenerator($options,$this->orderValueRetriever);
        if(RednaoWooCommercePDFInvoice::IsPR())
        {

            $orderId='';
            if($order!=null)
                $orderId=$this->orderValueRetriever->GetId();
            if(isset($options->invoiceTemplateId))
                $this->orderValueRetriever->translator=PDFTranslatorFactory::GetTranslator($options->invoiceTemplateId,$orderId); //new WPMLTranslator($options->invoiceTemplateId,$orderId);
            else
                $this->orderValueRetriever->translator=PDFTranslatorFactory::GetTranslator(0,0);
        }


        if(isset($options->containerOptions->PDFFileName)&&trim($options->containerOptions->PDFFileName)!='')
        {
            $this->pdfName=str_replace('/', '_', $options->containerOptions->PDFFileName);
        }else{
            $this->pdfName='';
        }




        if(!isset($options->containerOptions->pageSize)||!isset($options->containerOptions->pageSize->type))
            throw new Exception("Page size was not defined");
        $this->pageType=$options->containerOptions->pageSize->type;
        if($this->pageType=='custom')
        {
            $this->pageWidth = $options->containerOptions->pageSize->width;
            $this->pageHeight = $options->containerOptions->pageSize->height;
        }
        if(isset($options->containerOptions->orientation))
            $this->orientation=$options->containerOptions->orientation;
    }

    public function SetSkipSavingPDF(){
        return $this->SkipSavingPDF=true;
    }

    public function GetFormattedInvoiceNumber(){
        return $this->formattedInvoiceNumber;
    }

    public function GetInvoiceDate(){
        return $this->date;
    }

    public function GetInvoiceTemplateId(){
        if($this->options==null)
            return 0;
        return $this->options->invoiceTemplateId;
    }

    /**
     * @param $order WC_Order
     */
    public static function GetOptionsForOrder($order,$invoiceId=-1)
    {
        if($invoiceId!=-1||!RednaoWooCommercePDFInvoice::IsPR())
            return self::GetPageOptionsById($invoiceId);

        require_once RednaoWooCommercePDFInvoice::$DIR.'pr/conditions/ConditionManager.php';
        global $wpdb;
        $result=$wpdb->get_results("SELECT invoice_id FROM ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE);
        foreach($result as $currentInvoice)
        {
            $option=RednaoPDFGenerator::GetPageOptionsById($currentInvoice->invoice_id);
            $retriever=new OrderValueRetriever(null,$option,false,$order,null);
            $manager=new ConditionManager($retriever);
            if($manager->ShouldProcess(json_decode($option->conditions))||!apply_filters( 'rednao_wcpdfinvoice_should_process', true,$order->get_id(),$option ))
            {
                return $option;
            }
        }

        echo __('Sorry there is any pdf template suitable for this order, please make sure you configured a pdf template and that the template does not have conditions or that the condition is valid for this order',"wooinvoicebuilder");
    }

    public static function GetPageOptionsById($invoiceTemplateId = -1)
    {
        global $wpdb;

        if ($invoiceTemplateId == -1)
        {

            $invoiceData = $wpdb->get_results("SELECT invoice_id,attach_to,options,html,extensions,pages,conditions FROM ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE." WHERE invoice_id=(SELECT max(invoice_id) FROM ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE.")");
        } else
        {
            $invoiceData = $wpdb->get_results($wpdb->prepare("SELECT invoice_id,attach_to,options,html,extensions,pages,conditions FROM ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE." WHERE invoice_id=%s", $invoiceTemplateId));
        }

        if (count($invoiceData) > 0)
        {
            $data = $invoiceData[0];

            $attachTo = $data->attach_to;
            if ($attachTo === '')
            {
                $attachTo = '["customer_completed_order"]';
            }
            $attachTo = json_decode($attachTo);

            $extensions = json_decode($data->extensions);
            if ($extensions == false)
                $extensions = array();

            /** @var DocumentOptionsDTO $pageOptions */
            $pageOptions=new stdClass();
            $pageOptions->attachTo=$attachTo;
            $pageOptions->containerOptions=json_decode($data->options);
            $pageOptions->invoiceTemplateId=$data->invoice_id;
            $pageOptions->extensions=$extensions;
            $pageOptions->pages=json_decode($data->pages);
            $pageOptions->conditions=$data->conditions;
            return $pageOptions;
        }
        return null;


    }

    public static function GetPageOptionsByEmailId($emailId)
    {
        global $wpdb;
        $invoiceDataList = $wpdb->get_results("SELECT invoice_id,attach_to,options,html,extensions,conditions,pages FROM ".RednaoWooCommercePDFInvoice::$INVOICE_TABLE." WHERE attach_to LIKE '%" . esc_sql($wpdb->esc_like($emailId)) . "%'");
        $pageOptionsList = array();
        foreach ($invoiceDataList as $invoiceData)
        {

            $data = $invoiceData;

            $attachTo = $data->attach_to;
            if ($attachTo === '')
            {
                $attachTo = '["customer_completed_order"]';
            }
            $attachTo = json_decode($attachTo);

            $extensions = json_decode($data->extensions);
            if ($extensions == false)
                $extensions = array();

            /** @var DocumentOptionsDTO $pageOptions */
            $pageOptions=new stdClass();
            $pageOptions->attachTo=$attachTo;
            $pageOptions->containerOptions=json_decode($data->options);
            $pageOptions->invoiceTemplateId=$data->invoice_id;
            $pageOptions->extensions=$extensions;
            $pageOptions->conditions=$data->conditions;
            $pageOptions->pages=json_decode($data->pages);

            $pageOptionsList[]=$pageOptions;
        }
        return $pageOptionsList;
    }

    public function GetFileName($index=0){

        if($this->orderValueRetriever->useTestData)
        {
            return 'Preview';
        }


        if($this->pdfName=='')
        {
            $fileName=$this->orderValueRetriever->get('order_number');
            if($index>1)
                $fileName.='_'.$index;
            return 'Invoice_'.$fileName;

        }

        if(!RednaoWooCommercePDFInvoice::IsPR())
            return $this->pdfName;

        $tagManager=new TagManager($this->orderValueRetriever);
        $name= $tagManager->Process($this->pdfName);



        if(trim($name)=='')
        {
            $name= 'Document';
        }
        $name=str_replace('/','_',$name);
        $name=sanitize_file_name($name);

        if($index>1)
            $name.='_'.$index;

        return $name;



    }
    public function GenerateAttachment($path,&$attachments,$index,$secure=false)
    {

        $this->Generate(true);
        if($secure)
            $this->MaybeSetPassword();

        $path.=$this->GetFileName($index).'.pdf';

        $output=$this->dompdf->output();
        $this->SavePDF();

        file_put_contents ($path, $output );


        $attachments[]=$path;
    }

    public function GetOrderId(){
        return $this->orderValueRetriever->GetId();
    }

    public function GeneratePreview($saveToDatabase=false){
        $this->Generate($saveToDatabase);
        if(get_option('rnwcinv_enable_page_debug',false)==true)
        {
            echo str_replace('</body>','<div style="position: absolute;top:0;left:0;padding:5px">
                    <strong style="color:red">Important:</strong>
                    This page is displayed becuase you have the page debug enabled, to see the pdf instead go to WC Invoice/Settings/Debug and disable "Show html instead of pdf"
                    </div></body>',$this->html) ;
            die();
        }
        $this->dompdf->stream($this->GetFileName(), array("Attachment" => false));
    }

    public function MaybeSetPassword(){
        if(\rnwcinv\utilities\Sanitizer::GetValueFromPath($this->options,['containerOptions','PasswordProtect','enabled'],false)==true)
        {
            $tagManager=new TagManager($this->orderValueRetriever);
            $password=\rnwcinv\utilities\Sanitizer::GetValueFromPath($this->options,['containerOptions','PasswordProtect','password'],'');

            if(trim($password)!='') {
                $name = $tagManager->Process($password);
                /** @var Cpdf $cpdf */
                $cpdf = $this->dompdf->getCanvas()->get_cpdf();
                $cpdf->setEncryption($name, $name);
            }
        }

    }

    public function Generate($getFromDatabase=false,$skipDomPDFRenderer=false,$readOnly=false,$dbResultToUse=null)
    {
        ini_set('display_errors', 0);
        require RednaoWooCommercePDFInvoice::$DIR.'/vendor/autoload.php';
        $this->dompdf = new Dompdf();
        $this->dompdf->set_option('enable_remote', TRUE);

        $enableFontSubsetting=true;
        if(isset($this->options->containerOptions->disableFontSubsetting)&&$this->options->containerOptions->disableFontSubsetting===true)
        {
            $enableFontSubsetting=false;
        }
        $this->dompdf->getOptions()->setTempDir(RednaoWooCommercePDFInvoice::$DIR.'vendor/dompdf/dompdf/temp')->setIsFontSubsettingEnabled($enableFontSubsetting);
        $this->dompdf->set_option( 'dpi' , '96');

       /* echo $html;
        echo "------------------------------------------------";
        echo json_encode($this->GetFieldDictionary());
        return;*/
        global $wpdb;
        $alreadyRecorded=false;
        if($getFromDatabase)
        {
            $result=null;
            if($dbResultToUse==null)
            {
                $result = $wpdb->get_row($wpdb->prepare('select invoice_number,formatted_invoice_number,UNIX_TIMESTAMP(date) date,html,fields_dictionary from ' . RednaoWooCommercePDFInvoice::$INVOICES_CREATED_TABLE . " 
                                where order_id=%s and invoice_id=%s", $this->order->get_id(), $this->options->invoiceTemplateId));

            }else{
                $result=$dbResultToUse;
            }
            $this->PreviousResult = $result;
            if($result!=false)
            {
                $alreadyRecorded=true;
                $this->invoiceNumber=$result->invoice_number;
                $date=$result->date;
                if(!is_numeric($date))
                    $date=0;
                else
                    $date=intval($date);

                $this->date=strtotime(get_date_from_gmt(date('Y-m-d H:i:s', $date)));
                $this->formattedInvoiceNumber=apply_filters('rnwcinv_get_formatted_invoice_number',$result->formatted_invoice_number);
                $this->html=$result->html;
                $this->fieldDictionary=json_decode($result->fields_dictionary,true);
            }else{
                if($readOnly)
                    return false;
            }

        }

        if(!$alreadyRecorded)
        {
            $data=(new InvoiceInitialDataGenerator())->Create($this->GetTemplateId(),$this->GetOrderId(),$this->options->containerOptions->InvoiceNumberFormat,true,$this->orderValueRetriever);
            $this->invoiceNumber=$data->InvoiceNumber;
            $this->date=$data->Date;
            $this->formattedInvoiceNumber=apply_filters('rnwcinv_get_formatted_invoice_number',$data->FormattedInvoiceNumber);
        }

/*
        if(!$getFromDatabase||!$alreadyRecorded)
        {
            $this->fieldDictionary  = $this->htmlGenerator->GetFieldsDictionary();
            $this->html = $this->htmlGenerator->Generate();
        }*/

        $this->fieldDictionary  = $this->htmlGenerator->GetFieldsDictionary();
        $this->html = $this->htmlGenerator->Generate();

        if ($getFromDatabase && !$alreadyRecorded)
        {

            $wpdb->insert(RednaoWooCommercePDFInvoice::$INVOICES_CREATED_TABLE, array(
                'invoice_id' => $this->options->invoiceTemplateId,
                'invoice_number' => $this->invoiceNumber,
                'order_id' => $this->order->get_id(),
                'date' => date("Y-m-d h:i:s", $this->date),
                'formatted_invoice_number'=>$this->formattedInvoiceNumber,
                'fields_dictionary' => json_encode($this->fieldDictionary)

            ));


        }


       // $dictionary=json_encode($dictionary);
        if(!$skipDomPDFRenderer)
        {
           $this->render();
        }



        if(!$alreadyRecorded&&$getFromDatabase)
            do_action('rnwcinv_pdf_created',new PDFCreatedHook($this));



        if($getFromDatabase&&!$readOnly)
            $this->SavePDF($getFromDatabase);
        return true;

    }

    public function GetPageSize(){
        $size=$this->pageType;
        if($size=='custom')
            $size=array(0,0,$this->pageWidth*.75,$this->pageHeight*.75);
        return $size;
    }

    public function render(){
        $useRTL=\rnwcinv\utilities\Sanitizer::GetStringValueFromPath($this->options,['containerOptions','useRTL']);

        global $wooUseRTL;
        $wooUseRTL=$useRTL=='1';
        $this->dompdf->loadHtml($this->html, $this->fieldDictionary);


        $this->dompdf->setPaper($this->GetPageSize(), $this->orientation);
     //   $this->dompdf->getOptions()->setDefaultPaperSize($size);
        $this->dompdf->render();

    }

    public function AlreadyRenderer(){
        return $this->dompdf->getDom()!=null;
    }

    public function GetOutput(){
        if(!$this->AlreadyRenderer())
            $this->render();
        return $this->dompdf->output();
    }

    public function GetPrintableOutput(){
        if(!$this->AlreadyRenderer())
            $this->render();
        return $this->dompdf->printableOutput();
    }


    private function GetTempFolder()
    {
        $directory=$this->GetUploadDir();
        if(!is_dir($directory))
            mkdir($directory,0777,true);
        $count=0;
        while(true)
        {
            $count+=1;
            if($count==10)
                die();
            $tempFolderName=$directory.'/'.time().'_'.uniqid();
            if(is_dir())
                continue;

            mkdir($tempFolderName);
            array_push($this->folders,$tempFolderName);
            break;

        }


        return $tempFolderName;
    }

    private function GetUploadDir(){
        $uploadDir=wp_upload_dir();
        return $uploadDir['basedir'].'/sf_pdfs';
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

    private function GetProcessedHtml()
    {
        $html=$this->ProcessFields($this->html);
        $html=str_replace('@FONTAWESOME@',RednaoWooCommercePDFInvoice::$DIR.'css/fontAwesome/fonts/fontawesome-webfont.ttf',$html);

        $fieldPattern='/<pdfconverter options="([^"]*)"> ?<\/pdfconverter>/';
        preg_match_all($fieldPattern,$html, $matches, PREG_SET_ORDER);
        $index=0;
        require_once RednaoWooCommercePDFInvoice::$DIR.'smarty/converter/PDFConverter.php';
        foreach($matches as $match)
        {
            $index++;
            $options=json_decode(htmlspecialchars_decode($match[1]),true);
            if($options!=null)
            {
                $id='converter_'.$index;
                $this->smarty->assign($id,PDFConverter::GetConverterByType($options['type'],$options,$this->useTestData,$this->order,$this->fields,$this->translator));
                $html=str_replace($match[0],'{@$'.$id.'@}',$html);
            }


        }

        return $this->smarty->fetch('eval:'.$html);
    }

    private function GetFieldById($match,$content)
    {
        foreach($this->fields as $field){
            if($field['fieldID']==$match)
                return $this->GetFieldByType($field['type'],$field,$content);
        }
        return null;
    }



    private function ProcessFields($html)
    {
        $fieldPattern='/<pdffield [^>]*>/';
        $idpattern='/<pdffield id="([^"]*)">/';
        $matchOffset=0;
        while(preg_match($fieldPattern,$html, $matches, PREG_OFFSET_CAPTURE,$matchOffset))
        {
            $match=$matches[0];
            $offset=$match[1];

            //this needs to change if tags can have other tags
            $closeOffset=strpos($html,'</pdffield>',$offset);

            preg_match_all($idpattern,$match[0],$idmatch,PREG_SET_ORDER);
            $id=$idmatch[0][1];

            $contentStartIndex=$offset+strlen($match[0]);

            $this->smarty->assign('field_'.$id,$this->GetFieldById($id,substr($html,$contentStartIndex,$closeOffset-$contentStartIndex)));
            $html=substr_replace($html,'{@$'.'field_'.$id.'@}',$offset,$closeOffset+11-$offset);


        }

        return $html;
    }


    public function GetTemplateId(){
        if($this->options==null)
            return 0;
        if(isset($this->options->invoiceTemplateId))
            return $this->options->invoiceTemplateId;
        return 0;
    }



    public function GetExtensionOptions($extensionId)
    {
        foreach ($this->extensions as $extension)
        {
            if($extension->extensionId==$extensionId)
            {
                return $extension;
            }
        }

        return null;
    }

    private function SavePDF()
    {
        if($this->SkipSavingPDF)
            return;
        if(file_exists(RednaoWooCommercePDFInvoice::$DIR.'pr/addons/drive/DriveApi.php')&&$this->orderValueRetriever->useTestData==false)
        {
            require_once RednaoWooCommercePDFInvoice::$DIR.'pr/addons/drive/DriveApi.php';
            $driveOptions=$this->GetExtensionOptions('drive');
            if($driveOptions!=null&&$driveOptions->enabled==true)
            {
                $dive=new DriveApi($driveOptions->jsonConfig);
                $dive->InsertFile($this->GetFileName(),$this->dompdf->output(),$driveOptions->folderToUse);
            }

        }
    }

    private function IncreaseInvoiceCount()
    {
        $count=get_option('woopdfinvoicecount',0);
        $count+=1;
        update_option('woopdfinvoicecount',$count);
    }

    /**
     * @param $order WC_Order
     */
    protected function MaybeFilterOrder($order)
    {

        if($this->options->conditions!=''&&RednaoWooCommercePDFInvoice::IsPR()&&$order!=null)
        {
            $condition=$this->options->conditions;
            if(is_scalar($condition))
                $condition=json_decode($this->options->conditions);
            if(isset($condition->RemoveProductsThatDoesNotMatch)&&$condition->RemoveProductsThatDoesNotMatch)
            {
                if(!($order instanceof \rnwcinv\pr\MultiplePagesPDFGenerator\SplittedOrder)&&get_class($order)!='rnadvanceemailingwc\pr\SplittedOrder')
                    $order=new WC_Order($order->get_id());
                require_once RednaoWooCommercePDFInvoice::$DIR.'pr/conditions/ConditionManager.php';

                $retriever=new OrderValueRetriever($this,$this->options,false,$order,null);
                $manager=new ConditionManager($retriever);
                $previousItem=CustomFieldValueRetriever::$lineItem;
                foreach($order->get_items() as $item)
                {
                    CustomFieldValueRetriever::$lineItem=$item;
                    if(!$manager->ShouldProcess($condition,$item))
                    {
                        $order->remove_item($item->get_id());
                    }
                }
                CustomFieldValueRetriever::$lineItem=$previousItem;
            }

        }

        return $order;


    }


}