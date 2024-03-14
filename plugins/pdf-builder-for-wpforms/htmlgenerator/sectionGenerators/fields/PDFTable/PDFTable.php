<?php


namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\PDFTable;


use rednaoformpdfbuilder\DTO\RowItemOptions;
use rednaoformpdfbuilder\DTO\TableControlOptions;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\FieldFactory;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\PDFFieldBase;
use rednaoformpdfbuilder\htmlgenerator\tableCreator\HTMLTableCreator;

class PDFTable extends PDFFieldBase
{
    /** @var TableControlOptions */
    public $options;
    /** @var HTMLTableCreator */
    public $TableCreator;
    protected function InternalGetHTML()
    {
        $this->TableCreator=new HTMLTableCreator('tablefield','');
        $this->TableCreator->CreateTBody();
        $this->CreateRows();

        return $this->TableCreator->GetHTML();


    }

    private function CreateRows()
    {
        foreach($this->options->TableItem->Rows as $row)
        {
            $this->TableCreator->CreateRow();
            $this->CreateColumns($row);
        }
    }

    /**
     * @param $row RowItemOptions
     */
    private function CreateColumns($row)
    {

        foreach($row->Columns as $column)
        {
            if(count($column->Fields)>0)
            {
                $html='';
                foreach ($column->Fields as $field)
                {

                    $createdField = FieldFactory::GetField($this->Loader, $this->AreaGenerator, $field, $this->entryRetriever);
                    $html.=$createdField->GetHTML(true);

                }
                $this->TableCreator->CreateRawColumn($html, '', 'td', array('width' => $column->Width . '%'));
            }
            else
                $this->TableCreator->CreateTextColumn('','','td',array('width'=>$column->Width.'%'));

        }
    }
}