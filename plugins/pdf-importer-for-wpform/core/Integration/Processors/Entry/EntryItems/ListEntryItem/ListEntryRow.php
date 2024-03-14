<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem;


class ListEntryRow
{
    /** @var $ListEntryItem */
    public $ListEntryItem;
    /** @var ListEntryColumn[] */
    public $Columns;
    public function __construct($ListEntryItem)
    {
        $this->ListEntryItem=$ListEntryItem;
        $this->Columns=[];
    }


    public function AddColumn($ColumnId='',$Label='',$Value='')
    {
        $column=new ListEntryColumn($ColumnId,$Label,$Value);
        $this->Columns[]=$column;
        return $column;
    }

    public function CreateColumn()
    {
        $column=new ListEntryColumn();
        $this->Columns[]=$column;
        return $column;
    }

    public function InitializeWithOptions($currentRow)
    {
        $this->Columns=[];
        if(isset($currentRow->Columns))
        {
            foreach($currentRow->Columns as $currentColumn)
            {
                $column=new ListEntryColumn();
                $column->InitializeWithOptions($currentColumn);
                $this->Columns[]=$column;
            }
        }
    }

    public function GetObjectToSave(){
        $columns=[];
        foreach($this->Columns as $column)
        {
            $columns[]=$column->GetObjectToSave();
        }

        return (object)array('Columns'=>$columns);
    }
}