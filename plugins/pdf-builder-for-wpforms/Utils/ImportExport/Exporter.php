<?php


namespace rednaoformpdfbuilder\Utils\ImportExport;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\PDFDocumentOptions;
use rednaoformpdfbuilder\htmlgenerator\generators\FileManager;
use rednaoformpdfbuilder\pr\Utilities\FontManager;
use ZipArchive;

class Exporter
{
    /** @var PDFDocumentOptions */
    private $documentOptions;
    public $rootPath;
    public $zipPath;
    /** @var ZipArchive */
    public $zip;
    /** @var Loader */
    private $loader;

    private $alreadyAddedFonts;

    public function __construct($loader,$documentOptions)
    {
        $this->alreadyAddedFonts=array();
        $this->loader = $loader;
        $this->documentOptions = $documentOptions;
        $fileManager=new FileManager($loader);
        $this->rootPath=$fileManager->GetTemporalFolderPath();
        $this->zipPath=$this->rootPath.'export.zip';
        $this->zip=new \ZipArchive();
    }


    public function Execute()
    {
        $this->zip->open($this->zipPath,\ZipArchive::CREATE|\ZipArchive::OVERWRITE);
        $this->zip->addEmptyDir('fonts');


        $this->SaveImages();
        $this->SaveFonts();

        $this->zip->addFromString('export.json',\json_encode($this->documentOptions));


        $this->zip->close();
        return $this->zipPath;
    }

    public function Destroy(){
        array_map('unlink', glob($this->rootPath."/*.*"));
        rmdir($this->rootPath);

    }

    private function SaveImages()
    {
        $this->zip->addEmptyDir('images');
        if(isset($this->documentOptions->DocumentSettings->BackgroundImageId)&&$this->documentOptions->DocumentSettings->BackgroundImageId!='')
        {
            $file=\get_attached_file($this->documentOptions->DocumentSettings->BackgroundImageId);
            if($file!='')
            {
                $extension=pathinfo($file, PATHINFO_EXTENSION);
                $this->zip->addFromString('images/_background.'.$extension,file_get_contents($file));
            }
        }

        foreach($this->documentOptions->Pages as $page)
            foreach($page->Fields as $field)
            {
                if($field->Type=='Image')
                {
                    $file=\get_attached_file($field->URLId);
                    if($file!='')
                    {
                        $extension=pathinfo($file, PATHINFO_EXTENSION);
                        $this->zip->addFromString('images/img_'.$field->URLId.'.'.$extension,file_get_contents($file));
                    }
                }
            }
    }

    private function SaveFonts()
    {
        $this->zip->addEmptyDir('fonts');
        $this->SearchForFonts($this->documentOptions->Styles);
        foreach($this->documentOptions->Pages as $page)
            foreach($page->Fields as $field)
            {
                if($field->Type=='Text')
                {
                    $this->SearchForFonts($field->Text);
                }
            }


    }


    private function SearchForFonts($textToSearch)
    {
        $matches=array();
        preg_match_all('/font-family:([^;|"]*)/',$textToSearch,$matches,PREG_SET_ORDER);
        foreach($matches as $currentMatch)
        {
            if($this->loader->IsPR())
                $this->AddFont($currentMatch[1]);
        }
    }

    private function AddFont($font)
    {
        $this->alreadyAddedFonts[]=$font;
        $font=\str_replace('!important','',$font);
        $font=\str_replace(' ','',$font);
        $font=sanitize_file_name($font);

        if(\in_array($font,$this->alreadyAddedFonts))
            return;

        $fontManager=new FontManager($this->loader);
        $fontPath=$fontManager->GetFontPath().$font.'.ttf';
        if(!\file_exists($fontPath))
            return;


        $this->zip->addFromString('fonts/'.$font.'.ttf',file_get_contents($fontPath));



    }


}