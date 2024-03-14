<?php

namespace Ikana\EmbedVideoThumbnail;

class Utils
{
    /**
     * @param $directory
     */
    public static function recursiveRemoveDirectory($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                self::recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}
