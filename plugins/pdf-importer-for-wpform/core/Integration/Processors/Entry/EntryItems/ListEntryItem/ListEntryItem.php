<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:50 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem;


use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rnpdfimporter\core\Utils\ArrayUtils;

class ListEntryItem extends EntryItemBase
{
    /** @var ListEntryRow[] */
    public $Rows;

    /**
     * ListEntryItem constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->Rows=[];
    }


    public function GetText()
    {
        $rowsText=[];
        foreach($this->Rows as $row)
        {
            $columnsText=[];
            foreach($row->Columns as $column)
            {
                if(trim($column->Label)!='')
                {
                    $columnsText[]=$column->Label;
                }

            }
            $rowsText[]=\implode(',',$columnsText);


        }

        $glue=', ';
        if(ArrayUtils::Find($this->Rows,function ($item){return count($item->Columns)>1;}))
            $glue='|';

        return \implode($glue,$rowsText);

    }

    protected function InternalGetObjectToSave()
    {
        $rows=[];
        foreach($this->Rows as $currentRow)
            $rows[]=$currentRow->GetObjectToSave();
        return (object)array(
            'Rows'=>$rows
        );
    }

    public function InitializeWithOptions($field, $options)
    {
        $this->Field=$field;
        $this->Rows=[];
        if(isset($options->Rows))
        {
            foreach($options->Rows as $currentRow)
            {
                $row=new ListEntryRow($this);
                $row->InitializeWithOptions($currentRow);
                $this->Rows[]=$row;
            }

        }
    }

    public function GetHtml($style = 'standard')
    {
        // TODO: Implement GetHtml() method.
    }


    public function GetCell($rowIndex,$columnIndex)
    {
        if(count($this->Rows)<$rowIndex)
            return '';
        $row=$this->Rows[$rowIndex];

        if(count($row->Columns)<$columnIndex)
            return '';

        return $row->Columns[$columnIndex]->Value;


    }

    public function CreateRow()
    {
        $row= new ListEntryRow($this);
        $this->Rows[]=$row;
        return $row;
    }

    public function AddRowWithValue($columnId,$label,$value)
    {
        $row=$this->CreateRow();
        $row->AddColumn($columnId,$label,$value);
    }
}