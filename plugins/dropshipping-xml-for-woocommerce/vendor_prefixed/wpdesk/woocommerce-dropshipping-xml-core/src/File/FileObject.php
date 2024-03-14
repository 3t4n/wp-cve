<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File;

use SplFileObject;
use RuntimeException;
/**
 * Class FileObject, file class.
 * @package WPDesk\Library\DropshippingXmlCore\File
 */
class FileObject extends \SplFileObject
{
    /**
     * @var array
     */
    private $path_info;
    public function __construct(string $file_location)
    {
        parent::__construct($file_location);
        $this->initPathInfo($file_location);
    }
    public function getContent() : string
    {
        return \file_get_contents($this->getRealPath());
    }
    public function getName() : string
    {
        return $this->path_info['filename'];
    }
    public function getDirLocation() : string
    {
        return $this->path_info['dirname'];
    }
    public function getMimeType() : string
    {
        $mime_type = \mime_content_type($this->getRealPath());
        return !empty($mime_type) ? $mime_type : '';
    }
    private function initPathInfo(string $file_location)
    {
        $this->path_info = \pathinfo($file_location);
    }
}
