<?php
/**
 *  Utility functions for 3D Rotate Tool PHP Integration Script
 */

class Rotate_Utils {

    /**
     * Writes the string to file
     *
     * @param $path string The full path with filename
     * @param $content string The content to write
     * @return int
     */
    public static function write_file($path,$content)
    {
        return file_put_contents($path,$content);
    }

    /**
     * Merges the loaded settings.ini arrays
     *
     * @param $settings_parent
     * @param array $settings_local The array key values that take precedense
     * @return array
     */
    public static function merge_settings($settings_parent,$settings_local)
    {
       $result = array();

       //sections in settings.ini
       $arrays_to_merge = array("player","config","system","product");

       foreach($arrays_to_merge as $array_to_merge)
       {
           //if category/array is not defined in source/parent array, create an empty entry
           if(isset($settings_parent[$array_to_merge]) == FALSE) $settings_parent[$array_to_merge] = array();

           if(isset($settings_local[$array_to_merge]))
           {
               //if we have settings defined, merge them
               $result[$array_to_merge] = array_merge($settings_parent[$array_to_merge],$settings_local[$array_to_merge]);
           }
           else
           {
               //if we do not have settings defined, use defaults
               $result[$array_to_merge] = $settings_parent[$array_to_merge];
           }
       }

       return $result;
    }


    /**
     * Reads serialized array from file and returns it
     *
     * @param string $filename
     * @return array]null
     */
    public static function file_to_array($filename)
    {
        $content = file_get_contents($filename);
        $array = unserialize($content);
        if (is_array($array))
        {
            return $array;
        }
        else
        {
            return null;
        }
    }

    /**
     * Serializes array to file
     *
     * @param $array
     * @param $filename
     */
    public static function array_to_file($array,$filename)
    {
        $content = serialize($array);
        self::write_file($filename,$content);
    }

}