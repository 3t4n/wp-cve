<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/23/2018
 * Time: 6:59 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;



use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\PDFControlBaseOptions;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\PDFTable\PDFTable;
use rednaoformpdfbuilder\htmlgenerator\utils\Formatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rednaoformpdfbuilder\pr\htmlgenerator\sectionGenerator\fields\PDFConditionalField;
use rednaoformpdfbuilder\pr\htmlgenerator\sectionGenerator\fields\PDFCustomField;
use rednaoformpdfbuilder\pr\htmlgenerator\sectionGenerator\fields\PDFFigure;
use rednaoformpdfbuilder\pr\htmlgenerator\sectionGenerator\fields\PDFIcon;
use rednaoformpdfbuilder\pr\htmlgenerator\sectionGenerator\fields\PDFImage;
use rednaoformpdfbuilder\pr\htmlgenerator\sectionGenerator\fields\PDFProductDetails;
use rednaoformpdfbuilder\pr\htmlgenerator\sectionGenerator\fields\PDFQRCode;

class FieldFactory
{
    /**
     * @param $loader Loader
     * @param $field PDFControlBaseOptions
     * @param $orderValueRetriever EntryRetrieverBase
     * @param $formatter Formatter
     * @return
     * @throws \Exception
     */
    public static function GetField($loader,$areaGenerator,$field,$retriever)
    {

        $type = $field->Type;
        switch ($type)
        {
            case 'Text':
                return new PDFText($loader,$areaGenerator,$field,$retriever);
            case 'Field':
                return new PDFFormItem($loader,$areaGenerator,$field,$retriever);
            case 'Separator':
                return new PDFSeparator($loader,$areaGenerator,$field,$retriever);
            case "Link":
                return new PDFLink($loader,$areaGenerator,$field,$retriever);
            case 'Summary':
                return new PDFSummary($loader,$areaGenerator,$field,$retriever);
            case "Image":
                return new PDFImage($loader,$areaGenerator, $field, $retriever);
            case "Table":
                return new PDFTable($loader,$areaGenerator,$field,$retriever);
            case 'FormImage':
                return new PDFFormImageItem($loader,$areaGenerator,$field,$retriever);


        }

        if($loader->IsPR())
        {
            switch($type)
            {
                case "QRCode":
                    return new PDFQRCode($loader,$areaGenerator,$field,$retriever);
                case "Icon":
                    return new PDFIcon($loader,$areaGenerator,$field,$retriever);
                case "Figure":
                    return new PDFFigure($loader,$areaGenerator,$field,$retriever);
                case "HTML":
                    return new PDFHtml($loader,$areaGenerator,$field,$retriever);
                case "InvoiceDetail":
                    return new PDFProductDetails($loader,$areaGenerator,$field,$retriever);
                case 'CustomField':
                    return new PDFCustomField($loader,$areaGenerator,$field,$retriever);
                case 'ConditionalField':
                    return new PDFConditionalField($loader,$areaGenerator,$field,$retriever);

            }
        }


        return null;

    }

}