<?php


namespace rednaoformpdfbuilder\Utils\ImportExport;


use Exception;
use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\PDFDocumentOptions;
use rednaoformpdfbuilder\htmlgenerator\generators\FileManager;
use rednaoformpdfbuilder\pr\Utilities\FontManager;
use ZipArchive;

class Importer
{
    public $zipPath;
    /** @var Loader */
    private $loader;

    private $alreadyAddedFonts;
    /** @var ZipArchive */
    private $file;

    /** @var FontManager */
    private $fontManager;
    private $fileManager;
    private $extractedPath;

    /** @var PDFDocumentOptions */
    private $documentOptions;

    /**
     * Importer constructor.
     * @param $loader
     * @param $file ZipArchive
     * @throws \Exception
     */
    public function __construct($loader,$file)
    {
        $this->file=$file;
        $this->alreadyAddedFonts=array();
        $this->loader = $loader;
        $fileManager=new FileManager($loader);
        if($this->loader->IsPR())
            $this->fontManager=new FontManager($this->loader);
        $this->fileManager=new FileManager($this->loader);
        $this->extractedPath=$this->fileManager->GetTemporalFolderPath();

        $file->extractTo($this->extractedPath);
        $file->close();
    }

    private function InternalExecute(){
        $this->documentOptions=\json_decode(\file_get_contents($this->extractedPath.'export.json'));
        if($this->loader->IsPR())
            $this->ImportFonts();
        
        $this->ImportImages();


    }

    public function Execute(){
        $this->InternalExecute();
        return $this->SaveTemplate();
    }

    private function ImportFonts()
    {
        foreach (glob($this->extractedPath."fonts/*.ttf") as $filename) {
            try
            {
                $this->fontManager->AddFont(array(
                    'name' => \pathinfo($filename, \PATHINFO_FILENAME) . '.ttf',
                    'tmp_name' => $filename
                ));
            }catch(Exception $e)
            {

            }
        }
    }

    private function ImportImages()
    {
        foreach (glob($this->extractedPath."images/*") as $path) {
            $fileName=\pathinfo($path, \PATHINFO_FILENAME);
            $filename=\sanitize_file_name($fileName);
            $extension=\pathinfo($path, \PATHINFO_EXTENSION);
            if($fileName=='_background')
            {
                $files=array(
                    'name'=>'_background.'.$extension,
                    'tmp_name'=>$path
                );
                if($_FILES==null)
                    $_FILES=array();
                $_FILES['ImportImage']=$files;

                $result=\media_handle_upload("ImportImage",0,array(),array( 'test_form' => false ,'action'=>'rn_file_upload'));

                if(\is_wp_error($result))
                    throw new Exception('Could not upload image error:'.$result->get_error_message());

                $url=wp_get_attachment_url($result);
                $this->documentOptions->DocumentSettings->BackgroundImageURL=$url;
                $this->documentOptions->DocumentSettings->BackgroundImageId=$result;


            }else{
                $id=\str_replace('img_','',$filename);
                $files=array(
                    'name'=>'_background.'.$extension,
                    'tmp_name'=>$path
                );
                if($_FILES==null)
                    $_FILES=array();
                $_FILES['ImportImage']=$files;
                $result=\media_handle_upload("ImportImage",0,array(),array( 'test_form' => false ,'action'=>'rn_file_upload'));
                $url=wp_get_attachment_url($result);

                foreach($this->documentOptions->Pages as $page)
                    foreach($page->Fields as $field)
                    {
                        if($field->Type=='Image'&&$field->URLId==$id)
                        {
                            $field->URLId=$result;
                            $field->URL=$url;
                        }
                    }

                if(\is_wp_error($result))
                    throw new Exception('Could not upload image error:'.$result->get_error_message());

            }
        }
    }

    private function SaveTemplate()
    {
        global $wpdb;
        $originalName=$this->documentOptions->Name;
        $nameToUse=$originalName;
        $i=0;
        while(true){
            $result=$wpdb->get_var($wpdb->prepare('select count(*) from '.$this->loader->TEMPLATES_TABLE.' where name=%s',$nameToUse));
            if($result===false)
            {
                throw new Exception('An error occurred querying the database');
            }
            if($result>0)
            {
                $i++;
                $nameToUse=$originalName."($i)";
            }else
                break;
        }


        $this->documentOptions->DocumentSettings->CreatedFromTemplate=true;

        $result=$wpdb->insert($this->loader->TEMPLATES_TABLE,array(
            'pages'=>\json_encode($this->documentOptions->Pages),
            'styles'=>$this->documentOptions->Styles,
            'document_settings'=>\json_encode($this->documentOptions->DocumentSettings),
            'form_id'=>$this->documentOptions->FormId,
            'name'=>$nameToUse
        ));


        if($result===false)
            throw new Exception('Could not insert template, please try again');

        return $wpdb->insert_id;

    }

    public function Destroy(){
        WP_Filesystem();
        global $wp_filesystem;
        $wp_filesystem->rmdir($this->extractedPath,true);

    }

    /**
     * @return PDFDocumentOptions
     */
    public function GetTemplateDocumentOptions()
    {
        $this->InternalExecute();
        return $this->documentOptions;

    }


}