<?php

require_once WP_INTERNAL_LINKING_PLUGIN_DIR . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer as Writer;

/**
 * Class to work with Excel
 *
 * Class Wpil_Excel
 */
class Wpil_Excel
{
    /**
     * Export post data to Excel
     *
     * @param $post Wpil_Model_Post
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function exportPost($post)
    {
        //get links data
        $inbound_internal = $post->getInboundInternalLinks();
        $outbound_internal = $post->getOutboundInternalLinks();
        $outbound_external = $post->getOutboundExternalLinks();

        //create spreadsheet
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Link Whisper')
            ->setTitle(Wpil_Word::remove_emoji($post->getTitle()));

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        //set column width
        $sheet->getDefaultColumnDimension()->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(40);

        //merge cells
        $sheet->mergeCells('A5:C5')
            ->mergeCells('D5:F5')
            ->mergeCells('G5:H5');

        //set styles
        foreach (['A5', 'D5', 'G5'] as $cell) {
            $sheet->getStyle($cell)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($cell)->getFill()->setFillType('solid')
                ->getStartColor()->setRGB('4272fd');
            $sheet->getStyle($cell)->getFont()->setBold(true)->getColor()->setRGB('ffffff');
        }

        foreach (['A1', 'A2', 'A3', 'A6', 'B6', 'C6', 'D6', 'E6', 'F6', 'G6', 'H6'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        $sheet->getStyle('D5')->getFill()->getStartColor()->setRGB('2dc0fd');
        $sheet->getStyle('G5')->getFill()->getStartColor()->setRGB('a81ec1');

        //fill labels
        $sheet->setCellValue('A1', 'Title')
            ->setCellValue('A2', 'Type')
            ->setCellValue('A3', 'URL')
            ->setCellValue('A5', 'Inbound Internal Links' . (!empty($inbound_internal) ? ' (' . count($inbound_internal) . ')' : ''))
            ->setCellValue('D5', 'Outbound Internal Links' . (!empty($outbound_internal) ? ' (' . count($outbound_internal) . ')' : ''))
            ->setCellValue('G5', 'Outbound External Links' . (!empty($outbound_external) ? ' (' . count($outbound_external) . ')' : ''))
            ->setCellValue('A6', 'Anchor')
            ->setCellValue('B6', 'Title')
            ->setCellValue('C6', 'URL')
            ->setCellValue('D6', 'Anchor')
            ->setCellValue('F6', 'Title')
            ->setCellValue('E6', 'URL')
            ->setCellValue('F6', 'Anchor')
            ->setCellValue('G6', 'URL');

        //fill values
        $sheet->setCellValue('B1', Wpil_Word::remove_emoji($post->getTitle()))
            ->setCellValue('B2', $post->getType())
            ->setCellValue('B3', $post->getLinks()->view);

        $i = 6;
        foreach ($inbound_internal as $link) {
            $i++;
            $sheet->setCellValue('A' . $i, Wpil_Word::remove_emoji(substr($link->anchor, 0, 100)))
                ->setCellValue('B' . $i, Wpil_Word::remove_emoji($link->post->getTitle()))
                ->setCellValue('C' . $i, $link->post->getLinks()->view);
        }

        $i = 6;
        foreach ($outbound_internal as $link) {
            $i++;
            $sheet->setCellValue('D' . $i, Wpil_Word::remove_emoji(substr($link->anchor, 0, 100)))
                ->setCellValue('F' . $i, $link->url);

            if (!empty($link->post)) {
                $sheet->setCellValue('E' . $i, Wpil_Word::remove_emoji($link->post->getTitle()));
            }
        }

        $i = 6;
        foreach ($outbound_external as $link) {
            $i++;
            $sheet->setCellValue('G' . $i, Wpil_Word::remove_emoji(substr($link->anchor, 0, 100)))
                ->setCellValue('H' . $i, $link->url);
        }

        //download file
        $writer = new Writer\Xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $post->getTitle() . '.xls"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

        die;
    }
}
