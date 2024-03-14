<?php

namespace rnwcinv\ImportExport;

use rnwcinv\pr\utilities\FontManager;
use rnwcinv\utilities\ArrayUtils;
use rnwcinv\utilities\FileManager;

class TemplateExporter
{
    /** @var \ZipArchive  */
    private $zip;
    private $path='';
    private $fileCount=0;
    public $CachedImages;
    public function __construct()
    {
        $this->zip=new \ZipArchive();


    }


    public function Export($options)
    {
        $this->CachedImages=[];
        if(!$this->CreateFile($options))
            return '';

        $this->ParseCSSImages($options);
        if(isset($options->options->BackgroundFile)&&$options->options->BackgroundFile!='')
        {
            $attachmentId=$options->options->BackgroundFile;
            $file=get_attached_file($options->options->BackgroundFile);
            $options->options->BackgroundImage='';
            $options->options->BackgroundFile='';
            if($file!=false)
            {
                $this->AddImage($options,$file,'BackgroundImage');
            }

        }
        $needsPR=false;

        foreach($options->pages as $currentPage)
        {
            foreach($currentPage->fields as $currentField)
            {
                if($currentField->type=='image')
                {

                    $file=get_attached_file($currentField->URL_ID);
                    $currentField->URL_ID='';
                    $currentField->URL='';
                    if($file===false)
                        continue;
                    $this->AddImage($currentField,$file,'URL_ID');
                }

                if(in_array($currentField->type,['qrcode']))
                    $needsPR=true;
            }
        }

        if($this->ExportFonts($options->options->styles))
        {
            $needsPR=true;
        }


        $options->NeedsPR=$needsPR;
        $this->zip->addFromString('Options.json',json_encode($options));
        $this->zip->close();


        return $this->path;




    }

    private function CreateFile($options)
    {
        $name='';
        if(isset($options->name))
            $name=trim(sanitize_file_name($options->name)).'.zip';
        if($name=='')
            return false;

        $fileManager=new FileManager();
        $this->path=$fileManager->GetTemporalFolderPath().$name;
        $this->zip->open($this->path,\ZipArchive::CREATE|\ZipArchive::OVERWRITE);
        return true;
    }

    public function Destroy()
    {
        $fileManager=new FileManager();
        $fileManager->GetTempFolderRootPath();

        if(strpos($this->path, $fileManager->GetTempFolderRootPath())===false)
            return;
        if(is_dir($this->path))
            array_map('unlink', glob($this->path."/*.*"));
        else
            unlink($this->path);
        rmdir(dirname($this->path));
    }

    private function AddImage($options, $imagePath, $propertyName)
    {
        $cachedImage=ArrayUtils::Find($this->CachedImages,function ($item)use($imagePath){
            if($item->Path==$imagePath)
                return $item;
        });

        if($cachedImage==null) {
            $this->fileCount++;
            $fileName = 'File' . $this->fileCount . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);
            $this->zip->addFromString('Images/'.$fileName,file_get_contents($imagePath));

            $cachedImage=new \stdClass();
            $cachedImage->Path=$imagePath;
            $cachedImage->FileName=$fileName;
            $this->CachedImages[]=$cachedImage;
        }

        if($options!=null)
            $options->{$propertyName}=$cachedImage->FileName;

        return $cachedImage->FileName;

    }

    private function ExportFonts($styles)
    {
        $useCustomFonts=false;
        if(\RednaoWooCommercePDFInvoice::IsPR()) {
            $fontManager=new FontManager();
            $fonts=$fontManager->GetAvailableFonts(false);
            $matches = array();
            preg_match_all('/font-family:([^!]*) !important;/', $styles, $matches, PREG_SET_ORDER);

            foreach ($matches as $currentMatch) {
                if (count($currentMatch) != 2)
                    continue;

                $fontToExport=sanitize_file_name($currentMatch[1]);

                $fontToExport=ArrayUtils::Find($fonts,function ($item)use($fontToExport){
                    return $item->Name==$fontToExport;
                });

                if($fontToExport==null)
                    continue;

                $physicalBaseName=$fontToExport->Name;

                $fontName=$physicalBaseName.'.ttf';
                if(file_exists($fontManager->folderPath.$fontName))
                {
                    $useCustomFonts=true;
                    $this->zip->addFromString('Fonts/'.$fontName,file_get_contents($fontManager->folderPath.$fontName));
                }else{
                    continue;
                }

                if($fontToExport->HasBold)
                {
                    $fontName=$physicalBaseName.'__bold.ttf';
                    if(file_exists($fontManager->folderPath.$fontName))
                    {
                        $this->zip->addFromString('Fonts/'.$fontName,file_get_contents($fontManager->folderPath.$fontName));
                    }
                }

                if($fontToExport->HasItalic)
                {
                    $fontName=$physicalBaseName.'__italic.ttf';
                    if(file_exists($fontManager->folderPath.$fontName))
                    {
                        $this->zip->addFromString('Fonts/'.$fontName,file_get_contents($fontManager->folderPath.$fontName));
                    }
                }

                if($fontToExport->HasBoldItalic)
                {
                    $fontName=$physicalBaseName.'__bolditalic.ttf';
                    if(file_exists($fontManager->folderPath.$fontName))
                    {
                        $this->zip->addFromString('Fonts/'.$fontName,file_get_contents($fontManager->folderPath.$fontName));
                    }
                }



            }
        }

        return $useCustomFonts;
    }

    private function ParseCSSImages($options)
    {
        $styles=$options->options->styles;
        $matches=[];
        preg_match_all('/url\([^\)]*\)\/\*FileId:([^\*]*)[^;]*;/',$styles,$matches,PREG_SET_ORDER);

        foreach($matches as $currentMatch)
        {
            if(count($matches)!=2)
                continue;
            $file=get_attached_file($currentMatch[1]);
            if($file==false)
                continue;
            $fileName=$this->AddImage(null,$file,'');
            $styles=str_replace($currentMatch[0],'@@@@File'.$fileName.'File@@@@',$styles);

        }

        $options->options->styles=$styles;
    }


}