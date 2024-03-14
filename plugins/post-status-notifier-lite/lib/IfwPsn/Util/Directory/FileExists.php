<?php
/**
 * Safely check if a file exists in a directory
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) 2014 ifeelweb.de
 * @version   $Id: FileExists.php 1312332 2015-12-19 13:29:57Z worschtebrot $
 * @package
 */
class IfwPsn_Util_Directory_FileExists 
{
    /**
     * @var string
     */
    private $_basedir;


    /**
     * @param string $basedir
     */
    final public function __construct($basedir)
    {
        $this->_basedir = $basedir;
    }

    /**
     * @param $file
     * @return bool
     */
    public function fileExists($file)
    {
        $filepath = $this->getFileRealpath($file);

        if (strpos($filepath, $this->_basedir) === 0 && strpos($filepath, '..') === false && file_exists($filepath)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileRealpath($file)
    {
        $file = basename($file);

        $filepath = $this->_basedir . DIRECTORY_SEPARATOR . $file;

        return realpath($filepath);
    }
}
