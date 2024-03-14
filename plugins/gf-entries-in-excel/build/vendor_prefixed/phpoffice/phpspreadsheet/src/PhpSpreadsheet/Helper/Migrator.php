<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 08-March-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Helper;

class Migrator
{
    /**
     * @var string[]
     */
    private $from;

    /**
     * @var string[]
     */
    private $to;

    public function __construct()
    {
        $this->from = array_keys($this->getMapping());
        $this->to = array_values($this->getMapping());
    }

    /**
     * Return the ordered mapping from old PHPExcel class names to new PhpSpreadsheet one.
     *
     * @return string[]
     */
    public function getMapping()
    {
        // Order matters here, we should have the deepest namespaces first (the most "unique" strings)
        $classes = [
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer\BSE\Blip::class,
            'PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer\SpContainer::class,
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer\BSE::class,
            'PHPExcel_Shared_Escher_DgContainer_SpgrContainer' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer::class,
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer::class,
            'PHPExcel_Shared_OLE_PPS_File' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\OLE\PPS\File::class,
            'PHPExcel_Shared_OLE_PPS_Root' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\OLE\PPS\Root::class,
            'PHPExcel_Worksheet_AutoFilter_Column_Rule' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column\Rule::class,
            'PHPExcel_Writer_OpenDocument_Cell_Comment' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Cell\Comment::class,
            'PHPExcel_Calculation_Token_Stack' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Token\Stack::class,
            'PHPExcel_Chart_Renderer_jpgraph' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph::class,
            'PHPExcel_Reader_Excel5_Escher' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xls\Escher::class,
            'PHPExcel_Reader_Excel5_MD5' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xls\MD5::class,
            'PHPExcel_Reader_Excel5_RC4' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xls\RC4::class,
            'PHPExcel_Reader_Excel2007_Chart' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xlsx\Chart::class,
            'PHPExcel_Reader_Excel2007_Theme' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xlsx\Theme::class,
            'PHPExcel_Shared_Escher_DgContainer' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer::class,
            'PHPExcel_Shared_Escher_DggContainer' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Escher\DggContainer::class,
            'CholeskyDecomposition' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\JAMA\CholeskyDecomposition::class,
            'EigenvalueDecomposition' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\JAMA\EigenvalueDecomposition::class,
            'PHPExcel_Shared_JAMA_LUDecomposition' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\JAMA\LUDecomposition::class,
            'PHPExcel_Shared_JAMA_Matrix' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\JAMA\Matrix::class,
            'QRDecomposition' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\JAMA\QRDecomposition::class,
            'PHPExcel_Shared_JAMA_QRDecomposition' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\JAMA\QRDecomposition::class,
            'SingularValueDecomposition' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\JAMA\SingularValueDecomposition::class,
            'PHPExcel_Shared_OLE_ChainedBlockStream' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\OLE\ChainedBlockStream::class,
            'PHPExcel_Shared_OLE_PPS' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\OLE\PPS::class,
            'PHPExcel_Best_Fit' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\BestFit::class,
            'PHPExcel_Exponential_Best_Fit' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\ExponentialBestFit::class,
            'PHPExcel_Linear_Best_Fit' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\LinearBestFit::class,
            'PHPExcel_Logarithmic_Best_Fit' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\LogarithmicBestFit::class,
            'polynomialBestFit' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\PolynomialBestFit::class,
            'PHPExcel_Polynomial_Best_Fit' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\PolynomialBestFit::class,
            'powerBestFit' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\PowerBestFit::class,
            'PHPExcel_Power_Best_Fit' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\PowerBestFit::class,
            'trendClass' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Trend\Trend::class,
            'PHPExcel_Worksheet_AutoFilter_Column' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column::class,
            'PHPExcel_Worksheet_Drawing_Shadow' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Drawing\Shadow::class,
            'PHPExcel_Writer_OpenDocument_Content' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Content::class,
            'PHPExcel_Writer_OpenDocument_Meta' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Meta::class,
            'PHPExcel_Writer_OpenDocument_MetaInf' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\MetaInf::class,
            'PHPExcel_Writer_OpenDocument_Mimetype' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Mimetype::class,
            'PHPExcel_Writer_OpenDocument_Settings' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Settings::class,
            'PHPExcel_Writer_OpenDocument_Styles' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Styles::class,
            'PHPExcel_Writer_OpenDocument_Thumbnails' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails::class,
            'PHPExcel_Writer_OpenDocument_WriterPart' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\WriterPart::class,
            'PHPExcel_Writer_PDF_Core' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Pdf::class,
            'PHPExcel_Writer_PDF_DomPDF' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf::class,
            'PHPExcel_Writer_PDF_mPDF' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class,
            'PHPExcel_Writer_PDF_tcPDF' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf::class,
            'PHPExcel_Writer_Excel5_BIFFwriter' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xls\BIFFwriter::class,
            'PHPExcel_Writer_Excel5_Escher' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xls\Escher::class,
            'PHPExcel_Writer_Excel5_Font' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xls\Font::class,
            'PHPExcel_Writer_Excel5_Parser' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xls\Parser::class,
            'PHPExcel_Writer_Excel5_Workbook' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xls\Workbook::class,
            'PHPExcel_Writer_Excel5_Worksheet' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xls\Worksheet::class,
            'PHPExcel_Writer_Excel5_Xf' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xls\Xf::class,
            'PHPExcel_Writer_Excel2007_Chart' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\Chart::class,
            'PHPExcel_Writer_Excel2007_Comments' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\Comments::class,
            'PHPExcel_Writer_Excel2007_ContentTypes' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\ContentTypes::class,
            'PHPExcel_Writer_Excel2007_DocProps' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\DocProps::class,
            'PHPExcel_Writer_Excel2007_Drawing' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\Drawing::class,
            'PHPExcel_Writer_Excel2007_Rels' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels::class,
            'PHPExcel_Writer_Excel2007_RelsRibbon' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\RelsRibbon::class,
            'PHPExcel_Writer_Excel2007_RelsVBA' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\RelsVBA::class,
            'PHPExcel_Writer_Excel2007_StringTable' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\StringTable::class,
            'PHPExcel_Writer_Excel2007_Style' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\Style::class,
            'PHPExcel_Writer_Excel2007_Theme' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\Theme::class,
            'PHPExcel_Writer_Excel2007_Workbook' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\Workbook::class,
            'PHPExcel_Writer_Excel2007_Worksheet' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet::class,
            'PHPExcel_Writer_Excel2007_WriterPart' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx\WriterPart::class,
            'PHPExcel_CachedObjectStorage_CacheBase' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Collection\Cells::class,
            'PHPExcel_CalcEngine_CyclicReferenceStack' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Engine\CyclicReferenceStack::class,
            'PHPExcel_CalcEngine_Logger' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Engine\Logger::class,
            'PHPExcel_Calculation_Functions' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Functions::class,
            'PHPExcel_Calculation_Function' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Category::class,
            'PHPExcel_Calculation_Database' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Database::class,
            'PHPExcel_Calculation_DateTime' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\DateTime::class,
            'PHPExcel_Calculation_Engineering' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Engineering::class,
            'PHPExcel_Calculation_Exception' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Exception::class,
            'PHPExcel_Calculation_ExceptionHandler' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\ExceptionHandler::class,
            'PHPExcel_Calculation_Financial' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Financial::class,
            'PHPExcel_Calculation_FormulaParser' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\FormulaParser::class,
            'PHPExcel_Calculation_FormulaToken' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\FormulaToken::class,
            'PHPExcel_Calculation_Logical' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Logical::class,
            'PHPExcel_Calculation_LookupRef' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\LookupRef::class,
            'PHPExcel_Calculation_MathTrig' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\MathTrig::class,
            'PHPExcel_Calculation_Statistical' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Statistical::class,
            'PHPExcel_Calculation_TextData' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\TextData::class,
            'PHPExcel_Cell_AdvancedValueBinder' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder::class,
            'PHPExcel_Cell_DataType' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\DataType::class,
            'PHPExcel_Cell_DataValidation' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\DataValidation::class,
            'PHPExcel_Cell_DefaultValueBinder' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder::class,
            'PHPExcel_Cell_Hyperlink' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\Hyperlink::class,
            'PHPExcel_Cell_IValueBinder' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\IValueBinder::class,
            'PHPExcel_Chart_Axis' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\Axis::class,
            'PHPExcel_Chart_DataSeries' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\DataSeries::class,
            'PHPExcel_Chart_DataSeriesValues' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::class,
            'PHPExcel_Chart_Exception' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\Exception::class,
            'PHPExcel_Chart_GridLines' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\GridLines::class,
            'PHPExcel_Chart_Layout' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\Layout::class,
            'PHPExcel_Chart_Legend' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\Legend::class,
            'PHPExcel_Chart_PlotArea' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\PlotArea::class,
            'PHPExcel_Properties' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\Properties::class,
            'PHPExcel_Chart_Title' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\Title::class,
            'PHPExcel_DocumentProperties' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Document\Properties::class,
            'PHPExcel_DocumentSecurity' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Document\Security::class,
            'PHPExcel_Helper_HTML' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Helper\Html::class,
            'PHPExcel_Reader_Abstract' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\BaseReader::class,
            'PHPExcel_Reader_CSV' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Csv::class,
            'PHPExcel_Reader_DefaultReadFilter' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\DefaultReadFilter::class,
            'PHPExcel_Reader_Excel2003XML' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xml::class,
            'PHPExcel_Reader_Exception' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Exception::class,
            'PHPExcel_Reader_Gnumeric' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Gnumeric::class,
            'PHPExcel_Reader_HTML' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Html::class,
            'PHPExcel_Reader_IReadFilter' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\IReadFilter::class,
            'PHPExcel_Reader_IReader' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\IReader::class,
            'PHPExcel_Reader_OOCalc' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Ods::class,
            'PHPExcel_Reader_SYLK' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Slk::class,
            'PHPExcel_Reader_Excel5' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xls::class,
            'PHPExcel_Reader_Excel2007' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xlsx::class,
            'PHPExcel_RichText_ITextElement' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\RichText\ITextElement::class,
            'PHPExcel_RichText_Run' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\RichText\Run::class,
            'PHPExcel_RichText_TextElement' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\RichText\TextElement::class,
            'PHPExcel_Shared_CodePage' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\CodePage::class,
            'PHPExcel_Shared_Date' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Date::class,
            'PHPExcel_Shared_Drawing' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Drawing::class,
            'PHPExcel_Shared_Escher' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Escher::class,
            'PHPExcel_Shared_File' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\File::class,
            'PHPExcel_Shared_Font' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Font::class,
            'PHPExcel_Shared_OLE' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\OLE::class,
            'PHPExcel_Shared_OLERead' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\OLERead::class,
            'PHPExcel_Shared_PasswordHasher' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\PasswordHasher::class,
            'PHPExcel_Shared_String' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\StringHelper::class,
            'PHPExcel_Shared_TimeZone' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\TimeZone::class,
            'PHPExcel_Shared_XMLWriter' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\XMLWriter::class,
            'PHPExcel_Shared_Excel5' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Xls::class,
            'PHPExcel_Style_Alignment' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Alignment::class,
            'PHPExcel_Style_Border' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Border::class,
            'PHPExcel_Style_Borders' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Borders::class,
            'PHPExcel_Style_Color' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Color::class,
            'PHPExcel_Style_Conditional' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Conditional::class,
            'PHPExcel_Style_Fill' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Fill::class,
            'PHPExcel_Style_Font' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Font::class,
            'PHPExcel_Style_NumberFormat' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\NumberFormat::class,
            'PHPExcel_Style_Protection' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Protection::class,
            'PHPExcel_Style_Supervisor' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Supervisor::class,
            'PHPExcel_Worksheet_AutoFilter' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter::class,
            'PHPExcel_Worksheet_BaseDrawing' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing::class,
            'PHPExcel_Worksheet_CellIterator' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\CellIterator::class,
            'PHPExcel_Worksheet_Column' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Column::class,
            'PHPExcel_Worksheet_ColumnCellIterator' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\ColumnCellIterator::class,
            'PHPExcel_Worksheet_ColumnDimension' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension::class,
            'PHPExcel_Worksheet_ColumnIterator' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\ColumnIterator::class,
            'PHPExcel_Worksheet_Drawing' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Drawing::class,
            'PHPExcel_Worksheet_HeaderFooter' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::class,
            'PHPExcel_Worksheet_HeaderFooterDrawing' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing::class,
            'PHPExcel_WorksheetIterator' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Iterator::class,
            'PHPExcel_Worksheet_MemoryDrawing' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::class,
            'PHPExcel_Worksheet_PageMargins' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\PageMargins::class,
            'PHPExcel_Worksheet_PageSetup' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::class,
            'PHPExcel_Worksheet_Protection' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Protection::class,
            'PHPExcel_Worksheet_Row' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Row::class,
            'PHPExcel_Worksheet_RowCellIterator' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator::class,
            'PHPExcel_Worksheet_RowDimension' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\RowDimension::class,
            'PHPExcel_Worksheet_RowIterator' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\RowIterator::class,
            'PHPExcel_Worksheet_SheetView' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::class,
            'PHPExcel_Writer_Abstract' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\BaseWriter::class,
            'PHPExcel_Writer_CSV' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Csv::class,
            'PHPExcel_Writer_Exception' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Exception::class,
            'PHPExcel_Writer_HTML' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Html::class,
            'PHPExcel_Writer_IWriter' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\IWriter::class,
            'PHPExcel_Writer_OpenDocument' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods::class,
            'PHPExcel_Writer_PDF' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Pdf::class,
            'PHPExcel_Writer_Excel5' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xls::class,
            'PHPExcel_Writer_Excel2007' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx::class,
            'PHPExcel_CachedObjectStorageFactory' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Collection\CellsFactory::class,
            'PHPExcel_Calculation' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Calculation::class,
            'PHPExcel_Cell' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\Cell::class,
            'PHPExcel_Chart' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Chart\Chart::class,
            'PHPExcel_Comment' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Comment::class,
            'PHPExcel_Exception' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Exception::class,
            'PHPExcel_HashTable' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\HashTable::class,
            'PHPExcel_IComparable' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\IComparable::class,
            'PHPExcel_IOFactory' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\IOFactory::class,
            'PHPExcel_NamedRange' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\NamedRange::class,
            'PHPExcel_ReferenceHelper' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\ReferenceHelper::class,
            'PHPExcel_RichText' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\RichText\RichText::class,
            'PHPExcel_Settings' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Settings::class,
            'PHPExcel_Style' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Style::class,
            'PHPExcel_Worksheet' => \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::class,
        ];

        $methods = [
            'MINUTEOFHOUR' => 'MINUTE',
            'SECONDOFMINUTE' => 'SECOND',
            'DAYOFWEEK' => 'WEEKDAY',
            'WEEKOFYEAR' => 'WEEKNUM',
            'ExcelToPHPObject' => 'excelToDateTimeObject',
            'ExcelToPHP' => 'excelToTimestamp',
            'FormattedPHPToExcel' => 'formattedPHPToExcel',
            'Cell::absoluteCoordinate' => 'Coordinate::absoluteCoordinate',
            'Cell::absoluteReference' => 'Coordinate::absoluteReference',
            'Cell::buildRange' => 'Coordinate::buildRange',
            'Cell::columnIndexFromString' => 'Coordinate::columnIndexFromString',
            'Cell::coordinateFromString' => 'Coordinate::coordinateFromString',
            'Cell::extractAllCellReferencesInRange' => 'Coordinate::extractAllCellReferencesInRange',
            'Cell::getRangeBoundaries' => 'Coordinate::getRangeBoundaries',
            'Cell::mergeRangesInCollection' => 'Coordinate::mergeRangesInCollection',
            'Cell::rangeBoundaries' => 'Coordinate::rangeBoundaries',
            'Cell::rangeDimension' => 'Coordinate::rangeDimension',
            'Cell::splitRange' => 'Coordinate::splitRange',
            'Cell::stringFromColumnIndex' => 'Coordinate::stringFromColumnIndex',
        ];

        // Keep '\' prefix for class names
        $prefixedClasses = [];
        foreach ($classes as $key => &$value) {
            $value = str_replace('PhpOffice\\', '\\PhpOffice\\', $value);
            $prefixedClasses['\\' . $key] = $value;
        }
        $mapping = $prefixedClasses + $classes + $methods;

        return $mapping;
    }

    /**
     * Search in all files in given directory.
     *
     * @param string $path
     */
    private function recursiveReplace($path)
    {
        $patterns = [
            '/*.md',
            '/*.txt',
            '/*.TXT',
            '/*.php',
            '/*.phpt',
            '/*.php3',
            '/*.php4',
            '/*.php5',
            '/*.phtml',
        ];

        foreach ($patterns as $pattern) {
            foreach (glob($path . $pattern) as $file) {
                if (strpos($path, '/vendor/') !== false) {
                    echo $file . " skipped\n";

                    continue;
                }
                $original = file_get_contents($file);
                $converted = $this->replace($original);

                if ($original !== $converted) {
                    echo $file . " converted\n";
                    file_put_contents($file, $converted);
                }
            }
        }

        // Do the recursion in subdirectory
        foreach (glob($path . '/*', GLOB_ONLYDIR) as $subpath) {
            if (strpos($subpath, $path . '/') === 0) {
                $this->recursiveReplace($subpath);
            }
        }
    }

    public function migrate()
    {
        $path = realpath(getcwd());
        echo 'This will search and replace recursively in ' . $path . PHP_EOL;
        echo 'You MUST backup your files first, or you risk losing data.' . PHP_EOL;
        echo 'Are you sure ? (y/n)';

        $confirm = fread(STDIN, 1);
        if ($confirm === 'y') {
            $this->recursiveReplace($path);
        }
    }

    /**
     * Migrate the given code from PHPExcel to PhpSpreadsheet.
     *
     * @param string $original
     *
     * @return string
     */
    public function replace($original)
    {
        $converted = str_replace($this->from, $this->to, $original);

        // The string "PHPExcel" gets special treatment because of how common it might be.
        // This regex requires a word boundary around the string, and it can't be
        // preceded by $ or -> (goal is to filter out cases where a variable is named $PHPExcel or similar)
        $converted = preg_replace('~(?<!\$|->)(\b|\\\\)PHPExcel\b~', '\\' . \GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Spreadsheet::class, $converted);

        return $converted;
    }
}
