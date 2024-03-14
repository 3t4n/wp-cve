<?php

declare(strict_types=1);

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class FileHealthReport
{
    /** @var int|null */
    public $UnprocessedFilesCount;

    /** @var string|null */
    public $DateProcessOfLastProcessedFile;

    /** @var string|null */
    public $DateAddOfLastImportedFile;

    /** @var string|null */
    public $DateAddOfLastProcessedFile;

    /** @var float|null */
    public $LatestProcessingTimePerProductMIN;

    /** @var float|null */
    public $LatestProcessingTimePerProductMAX;

    /** @var float|null */
    public $LatestProcessingTimePerProductAVG;

    /** @var int|null */
    public $TotalProductInLastFiles;
}