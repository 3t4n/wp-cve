<?php

namespace rnwcinv\ImportExport;

use rnwcinv\pr\utilities\FontManager;
use rnwcinv\utilities\ArrayUtils;
use rnwcinv\utilities\FileManager;

class TemplateImporter
{
    /** @var \ZipArchive  */
    private $zip;
    private $tempPath='';
    public $CachedImages;
    public function __construct()
    {
        $this->zip=new \ZipArchive();


    }


    public function Import($path)
    {
        $this->CachedImages=[];
        if(!file_exists($path))
            return;

        $this->zip=new \ZipArchive();
        $this->zip->open($path);
        $options=json_decode($this->zip->getFromName('Options.json'));
        $this->ParseCSSImages($options);
        if($options===false)
            return '';

        if(!\RednaoWooCommercePDFInvoice::IsPR())
        {
            global $wpdb;
            $result=$wpdb->get_var('select count(*) from '.\RednaoWooCommercePDFInvoice::$INVOICE_TABLE);
            if($result>0)
            {
                echo "<div class='alert alert-danger'>
                    Sorry this version support only one pdf template, please remove your existing template first  or  <a target='_blank' href='https://wooinvoice.rednao.com/getit/'>click here to get the full version</a>
                </div>";
                return null;
            }
        }

        if(isset($options->NeedsPR)&&$options->NeedsPR&&!\RednaoWooCommercePDFInvoice::IsPR())
        {
            echo "<div class='alert alert-danger'>
                    Sorry this template use features of the full version so it can't be installed. <a target='_blank' href='https://wooinvoice.rednao.com/getit/'>Click here to get the full version</a>
                </div>";
            return null;
        }


        if(isset($options->BackgroundImage))
        {
            $this->ImportImage($options,$options->BackgroundImage,'BackgroundImage','BackgroundFile');

        }

        $this->ImportFields($options);
        $this->ImportFonts();


        global $wpdb;
        $name=$options->name;
        $nameToTry=$name;
        $count=1;
        do{
            $result=$wpdb->get_var($wpdb->prepare('select count(*) from '.\RednaoWooCommercePDFInvoice::$INVOICE_TABLE.' where name=%s',$nameToTry));
            if($result>0)
            {
                $nameToTry=$name.' ('.$count.')';
                $count++;
            }
        }while($result>0);

        $wpdb->insert(\RednaoWooCommercePDFInvoice::$INVOICE_TABLE,array(
            'name'=>$nameToTry,
            'attach_to'=>json_encode($options->attach_to),
            'options'=>json_encode($options->options),
            'html'=>$options->html,
            'conditions'=>json_encode($options->conditions),
            'type'=>$options->type,
            'extensions'=>json_encode($options->extensions),
            'pages'=>json_encode($options->pages)
        ));

        update_option('REDNAO_PDF_INVOICE_EDITED',true);
        $this->zip->close();

    }


    public function GetTempPath(){
        if($this->tempPath=='')
        {
            $fileManager=new FileManager();
            $this->tempPath=$fileManager->GetTemporalFolderPath();
        }

        return $this->tempPath;
    }
    private function ImportImage($options,$fileName,$imageURLProperty,$imageFileId)
    {
        $fileName=sanitize_file_name($fileName);

        $cachedItem=ArrayUtils::Find($this->CachedImages,function ($item)use($fileName){
            if($item->FileName==$fileName)
            {
                return $item;
            }
        });

        if($cachedItem==null)
        {
            $extension=pathinfo($fileName,PATHINFO_EXTENSION);
            if(!in_array(strtolower($extension),['jpg','jpeg','png','bpm','svg']))
                return false;

            $image=$this->zip->getFromName('Images/'.$fileName);
            if($image==false)
                return false;

            $path=$this->GetTempPath();
            $imagePath=$path.$fileName;

            if(!file_put_contents($imagePath,$image))
                return false;

            $files=array(
                'name'=>$fileName,
                'tmp_name'=>$imagePath
            );
            if($_FILES==null)
                $_FILES=array();
            $_FILES['ImportImage']=$files;

            $result=\media_handle_upload("ImportImage",0,array(),array( 'test_form' => false ,'action'=>'rn_file_upload'));
            if(\is_wp_error($result))
                return false;

            $url=wp_get_attachment_url($result);

            $cachedItem=new \stdClass();
            $this->CachedImages[]=$cachedItem;
            $cachedItem->FileName=$fileName;
            $cachedItem->FileId=$result;
            $cachedItem->URL=$url;


        }

        if($options!=null)
        {
            $options->{$imageURLProperty}='';
            $options->{$imageFileId}='';

            $options->{$imageURLProperty}=$cachedItem->URL;
            $options->{$imageFileId}=$cachedItem->FileId;
        }

        return $cachedItem;

    }

    private function ImportFields($options)
    {
        foreach($options->pages as $currentPage)
        {
            foreach($currentPage->fields as $currentField)
            {
                if($currentField->type=='image')
                {
                    $this->ImportImage($currentField,$currentField->URL_ID,'URL','URL_ID');

                }

            }
        }
    }

    function EndsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }

    private function ImportFonts()
    {
        if(!\RednaoWooCommercePDFInvoice::IsPR())
            return;

        $fontsToImport=[];
        $fontManager=new FontManager();
        for($i=0;$i<$this->zip->count();$i++)
        {
            $name=$this->zip->getNameIndex($i);
            if(strpos($name,'Fonts/')!==0||!$this->EndsWith($name,'.ttf'))
            {
                continue;
            }

            $fontName=substr($name,0,-4);
            $fontName=str_replace('Fonts/','',$fontName);
            $fontName=sanitize_file_name($fontName);

            $property='';
            if($this->EndsWith($fontName,'__bold'))
            {
                $fontName=str_replace('__bold','',$fontName);
                $property='HasBold';
            }else if($this->EndsWith($fontName,'__italic'))
            {
                $fontName=str_replace('__italic','',$fontName);
                $property='HasItalic';

            }else if($this->EndsWith($fontName,'__bolditalic'))
            {
                $fontName=str_replace('__bolditalic','',$fontName);
                $property='HasBoldItalic';
            }else{
                $property='HasMain';
            }


            $fontToUse=ArrayUtils::Find($fontsToImport,function ($item)use($fontName){
                return $item->Name==$fontName;
            });

            if($fontToUse==null)
            {
                $fontToUse=new \stdClass();
                $fontToUse->Name=$fontName;
                $fontToUse->HasMain=false;
                $fontToUse->HasBoldItalic=false;
                $fontToUse->HasItalic=false;
                $fontToUse->HasBold=false;
                $fontsToImport[]=$fontToUse;
            }

            $fontToUse->{$property}=true;
        }

        foreach($fontsToImport as $currentFont)
        {
            $fontName=$currentFont->Name;
            if($this->zip->locateName('Fonts/'.$fontName.'.ttf')!==false)
                $this->UploadFont($this->zip->getFromName('Fonts/'.$fontName.'.ttf'),$fontName.'.ttf');

            if($currentFont->HasBold)
                $this->UploadFont($this->zip->getFromName('Fonts/'.$fontName.'__bold.ttf'),$fontName.'__bold.ttf');

            if($currentFont->HasItalic)
                $this->UploadFont($this->zip->getFromName('Fonts/'.$fontName.'__italic.ttf'),$fontName.'__italic.ttf');

            if($currentFont->HasBoldItalic)
                $this->UploadFont($this->zip->getFromName('Fonts/'.$fontName.'__bolditalic.ttf'),$fontName.'__bolditalic.ttf');
        }

        if(count($fontsToImport))
        {
            delete_option('rnwcinv_'.'fontlist2');
        }

    }

    public function UploadFont($data,$fontName)
    {
        $fontManager=new FontManager();
        if(file_exists($fontManager->folderPath.$fontName))
            return;

        file_put_contents($fontManager->folderPath.$fontName,$data);

    }

    private function ParseCSSImages($options)
    {
        $styles=$options->options->styles;
        $matches=[];
        preg_match_all('/@@@@File(.*)(?=File@@@@)File@@@@/mU',$styles,$matches,PREG_SET_ORDER);
        foreach($matches as $currentMatch)
        {
            if(count($currentMatch)!=2)
                continue;

            $item=$this->ImportImage(null,$currentMatch[1],'','');

            $styles=str_replace($currentMatch[0],'url('.$item->URL.')/*FileId:'.$item->FileId.'*/ !important;',$styles);

        }

        $options->options->styles=$styles;
    }

}