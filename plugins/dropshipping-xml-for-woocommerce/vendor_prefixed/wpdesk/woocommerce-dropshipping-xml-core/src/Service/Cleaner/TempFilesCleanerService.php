<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Cleaner;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
/**
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Cleaner
 */
class TempFilesCleanerService
{
    /**
     *
     * @var ImportDAO
     */
    private $import_dao;
    /**
     *
     * @var FileLocatorService
     */
    private $file_locator;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator)
    {
        $this->import_dao = $import_dao;
        $this->file_locator = $file_locator;
    }
    public function clean()
    {
        $dir = $this->file_locator->get_tmp_dir();
        $scan = \scandir($dir);
        if (\is_array($scan)) {
            $scanned_directory = \array_diff($scan, ['..', '.']);
            foreach ($scanned_directory as $files) {
                if (\is_dir($dir . $files) && !$this->import_dao->is_uid_exists($files)) {
                    $this->remove_dir_with_files($dir . $files);
                }
            }
        }
    }
    private function remove_dir_with_files(string $dir)
    {
        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                \rmdir($file->getRealPath());
            } else {
                \unlink($file->getRealPath());
            }
        }
        \rmdir($dir);
    }
}
