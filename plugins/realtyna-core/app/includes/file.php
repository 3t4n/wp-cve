<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('RTCORE_File')):

/**
 * RTCORE File Class.
 *
 * @class RTCORE_File
 * @version	1.0.0
 */
class RTCORE_File extends RTCORE_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function read($path)
    {
        return file_get_contents($path);
    }

    public static function exists($path)
    {
        return file_exists($path);
    }

    public static function write($path, $content)
    {
        return file_put_contents($path, $content);
    }

    public static function download($url)
    {
        return wp_remote_retrieve_body(wp_remote_get($url, array('timeout'=>30)));
    }

    public static function extract($file, $dest)
    {
        if(!class_exists('ZipArchive'))
        {
            WP_Filesystem();

            $unzip = unzip_file($file, $dest);
            return ($unzip === true ? true : false);
        }

        $zip = new ZipArchive;
        if($zip->open($file) === true)
        {
            $zip->extractTo($dest);
            $zip->close();

            return true;
        }

        return false;
    }
}

endif;