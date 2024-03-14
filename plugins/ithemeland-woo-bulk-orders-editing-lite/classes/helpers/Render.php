<?php

namespace wobel\classes\helpers;

class Render
{
    public static function html($file_dir, $data = [])
    {
        if (file_exists($file_dir)) {
            extract($data);
            ob_start();
            include $file_dir;
            return ob_get_clean();
        }
        return false;
    }
}