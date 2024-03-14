<?php

declare(strict_types=1);

namespace WcMipConnector\Helper;

defined('ABSPATH') || exit;

use FilterIterator;
use Iterator;

class FileNameFilter extends FilterIterator
{
    /** @var string */
    private $fileName;

    /**
     * @param Iterator $iterator
     * @param string $fileName
     */
    public function __construct(
        Iterator $iterator,
        string $fileName
    ) {
        parent::__construct($iterator);
        $this->fileName = $fileName;
    }

    /**
     * @return bool
     */
    public function accept(): bool
    {
        /** @var \DirectoryIterator $file */
        $file = $this->getInnerIterator()->current();

        if (empty($file)) {
            return false;
        }

        return \stripos($file->getFilename(), $this->fileName) !== false;
    }
}