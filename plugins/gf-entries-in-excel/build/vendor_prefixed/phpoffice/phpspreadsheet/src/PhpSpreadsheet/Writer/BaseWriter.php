<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 08-March-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer;

abstract class BaseWriter implements IWriter
{
    /**
     * Write charts that are defined in the workbook?
     * Identifies whether the Writer should write definitions for any charts that exist in the PhpSpreadsheet object;.
     *
     * @var bool
     */
    protected $includeCharts = false;

    /**
     * Pre-calculate formulas
     * Forces PhpSpreadsheet to recalculate all formulae in a workbook when saving, so that the pre-calculated values are
     * immediately available to MS Excel or other office spreadsheet viewer when opening the file.
     *
     * @var bool
     */
    protected $preCalculateFormulas = true;

    /**
     * Use disk caching where possible?
     *
     * @var bool
     */
    private $useDiskCaching = false;

    /**
     * Disk caching directory.
     *
     * @var string
     */
    private $diskCachingDirectory = './';

    public function getIncludeCharts()
    {
        return $this->includeCharts;
    }

    public function setIncludeCharts($pValue)
    {
        $this->includeCharts = (bool) $pValue;

        return $this;
    }

    public function getPreCalculateFormulas()
    {
        return $this->preCalculateFormulas;
    }

    public function setPreCalculateFormulas($pValue)
    {
        $this->preCalculateFormulas = (bool) $pValue;

        return $this;
    }

    public function getUseDiskCaching()
    {
        return $this->useDiskCaching;
    }

    public function setUseDiskCaching($pValue, $pDirectory = null)
    {
        $this->useDiskCaching = $pValue;

        if ($pDirectory !== null) {
            if (is_dir($pDirectory)) {
                $this->diskCachingDirectory = $pDirectory;
            } else {
                throw new Exception("Directory does not exist: $pDirectory");
            }
        }

        return $this;
    }

    public function getDiskCachingDirectory()
    {
        return $this->diskCachingDirectory;
    }
}
