<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 08-March-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\File;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Spreadsheet;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Exception as WriterException;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Content;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Meta;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\MetaInf;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Mimetype;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Settings;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Styles;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;
use ZipArchive;

class Ods extends BaseWriter
{
    /**
     * Private writer parts.
     *
     * @var Ods\WriterPart[]
     */
    private $writerParts = [];

    /**
     * Private PhpSpreadsheet.
     *
     * @var Spreadsheet
     */
    private $spreadSheet;

    /**
     * Create a new Ods.
     *
     * @param Spreadsheet $spreadsheet
     */
    public function __construct(Spreadsheet $spreadsheet)
    {
        $this->setSpreadsheet($spreadsheet);

        $writerPartsArray = [
            'content' => Content::class,
            'meta' => Meta::class,
            'meta_inf' => MetaInf::class,
            'mimetype' => Mimetype::class,
            'settings' => Settings::class,
            'styles' => Styles::class,
            'thumbnails' => Thumbnails::class,
        ];

        foreach ($writerPartsArray as $writer => $class) {
            $this->writerParts[$writer] = new $class($this);
        }
    }

    /**
     * Get writer part.
     *
     * @param string $pPartName Writer part name
     *
     * @return null|Ods\WriterPart
     */
    public function getWriterPart($pPartName)
    {
        if ($pPartName != '' && isset($this->writerParts[strtolower($pPartName)])) {
            return $this->writerParts[strtolower($pPartName)];
        }

        return null;
    }

    /**
     * Save PhpSpreadsheet to file.
     *
     * @param string $pFilename
     *
     * @throws WriterException
     */
    public function save($pFilename)
    {
        if (!$this->spreadSheet) {
            throw new WriterException('PhpSpreadsheet object unassigned.');
        }

        // garbage collect
        $this->spreadSheet->garbageCollect();

        // If $pFilename is php://output or php://stdout, make it a temporary file...
        $originalFilename = $pFilename;
        if (strtolower($pFilename) == 'php://output' || strtolower($pFilename) == 'php://stdout') {
            $pFilename = @tempnam(File::sysGetTempDir(), 'phpxltmp');
            if ($pFilename == '') {
                $pFilename = $originalFilename;
            }
        }

        $zip = $this->createZip($pFilename);

        $zip->addFromString('META-INF/manifest.xml', $this->getWriterPart('meta_inf')->writeManifest());
        $zip->addFromString('Thumbnails/thumbnail.png', $this->getWriterPart('thumbnails')->writeThumbnail());
        $zip->addFromString('content.xml', $this->getWriterPart('content')->write());
        $zip->addFromString('meta.xml', $this->getWriterPart('meta')->write());
        $zip->addFromString('mimetype', $this->getWriterPart('mimetype')->write());
        $zip->addFromString('settings.xml', $this->getWriterPart('settings')->write());
        $zip->addFromString('styles.xml', $this->getWriterPart('styles')->write());

        // Close file
        if ($zip->close() === false) {
            throw new WriterException("Could not close zip file $pFilename.");
        }

        // If a temporary file was used, copy it to the correct file stream
        if ($originalFilename != $pFilename) {
            if (copy($pFilename, $originalFilename) === false) {
                throw new WriterException("Could not copy temporary zip file $pFilename to $originalFilename.");
            }
            @unlink($pFilename);
        }
    }

    /**
     * Create zip object.
     *
     * @param string $pFilename
     *
     * @throws WriterException
     *
     * @return ZipArchive
     */
    private function createZip($pFilename)
    {
        // Create new ZIP file and open it for writing
        $zip = new ZipArchive();

        if (file_exists($pFilename)) {
            unlink($pFilename);
        }
        // Try opening the ZIP file
        if ($zip->open($pFilename, ZipArchive::OVERWRITE) !== true) {
            if ($zip->open($pFilename, ZipArchive::CREATE) !== true) {
                throw new WriterException("Could not open $pFilename for writing.");
            }
        }

        return $zip;
    }

    /**
     * Get Spreadsheet object.
     *
     * @throws WriterException
     *
     * @return Spreadsheet
     */
    public function getSpreadsheet()
    {
        if ($this->spreadSheet !== null) {
            return $this->spreadSheet;
        }

        throw new WriterException('No PhpSpreadsheet assigned.');
    }

    /**
     * Set Spreadsheet object.
     *
     * @param Spreadsheet $spreadsheet PhpSpreadsheet object
     *
     * @return $this
     */
    public function setSpreadsheet(Spreadsheet $spreadsheet)
    {
        $this->spreadSheet = $spreadsheet;

        return $this;
    }
}
