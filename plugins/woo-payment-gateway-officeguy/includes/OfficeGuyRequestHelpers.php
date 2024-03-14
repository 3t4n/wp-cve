<?php
class OfficeGuyRequestHelpers
{
    public static function Get($Name)
    {
        if (isset($_GET[$Name]))
            return $_GET[$Name];
        return null;
    }

    public static function Post($Name)
    {
        if (isset($_POST[$Name]))
            return $_POST[$Name];
        return null;
    }
}
