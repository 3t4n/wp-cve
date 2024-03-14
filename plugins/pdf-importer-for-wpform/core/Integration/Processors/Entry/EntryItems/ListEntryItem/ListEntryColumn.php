<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem;


class ListEntryColumn
{
    public $ColumnId;
    public $Value;
    public $Label;

    public function __construct($ColumnId='',$Label='',$Value='')
    {
        $this->ColumnId=$ColumnId;
        $this->Label=$Label;
        $this->Value=$Value;
    }

    public function InitializeWithOptions($column)
    {
        $this->ColumnId='';
        $this->Value='';
        $this->Label='';

        if(isset($column->ColumnId))
            $this->ColumnId=$column->ColumnId;

        if(isset($column->Value))
            $this->Value=$column->Value;

        if(isset($column->Label))
            $this->Label=$column->Label;


    }

    public function GetObjectToSave(){
        return (object) array(
            'ColumnId'=>$this->ColumnId,
            'Label'=>$this->Label,
            'Value'=>$this->Value
        );
    }


}