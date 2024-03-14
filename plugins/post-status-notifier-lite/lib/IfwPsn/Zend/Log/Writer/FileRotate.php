<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: FileRotate.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Zend/Log/Writer/Abstract.php';

require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Misc/Rotate/DirectoryIterator.php';
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Misc/Rotate/FilenameFormat.php';
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Misc/Rotate/FilenameFormatException.php';
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Misc/Rotate/RotateAbstract.php';
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Misc/Rotate/Rotate.php';
require_once IFW_PSN_LIB_ROOT . 'IfwPsn/Vendor/Misc/Rotate/RotateException.php';

use studio24\Rotate\Rotate;

class IfwPsn_Zend_Log_Writer_FileRotate extends IfwPsn_Vendor_Zend_Log_Writer_Stream
{
    /**
     * IfwPsn_Zend_Log_Writer_FileRotate constructor.
     * @param $file
     * @param int $size
     * @param int $keep
     * @throws IfwPsn_Vendor_Zend_Log_Exception
     */
    public function __construct($file, $size = 10, $keep = 10)
    {
        try {
            $rotate = new Rotate($file);
            $rotate->size(sprintf("%dMB", $size));
            $rotate->keep((int)$keep);
            $rotate->run();
        } catch (Exception $e) {
            // ignore file rotate on error
            trigger_error(sprintf('Exception in %s: %s', get_class($this), $e->getMessage()));
        }

        parent::__construct($file);
    }
}
