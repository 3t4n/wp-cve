<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;

class FileUploadEntryItem extends EntryItemBase
{
    public $Path='';
    public $FileId='';
    public $Name='';
    public $URL='';

    public function GetText()
    {
        return $this->URL;
    }

    public function SetURL($value)
    {
        $this->URL=$value;
        return $this;
    }

    public function SetPath($value)
    {
        $this->Path=$value;
        return $this;
    }

    public function SetFileId($fileId)
    {
        $this->FileId=$fileId;
        return $this;
    }

    public function SetName($fileName)
    {
        $this->Name=$fileName;
        return $this;
    }

    protected function InternalGetObjectToSave()
    {
        return (object)array(
            'Path'=>$this->Path,
            'FileId'=>$this->FileId,
            'Name'=>$this->Name,
            'URL'=>$this->URL
        );
    }



    public function InitializeWithOptions($field,$options)
    {
        $this->Field=$field;
        if(isset($options->Path))
            $this->Path=$options->Path;

        if(isset($options->FileId))
            $this->FileId=$options->FileId;

        if(isset($options->URL))
            $this->URL=$options->URL;
    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }
}