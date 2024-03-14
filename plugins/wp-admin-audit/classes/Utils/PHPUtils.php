<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_PHPUtils
{
    /**
     * from https://stackoverflow.com/questions/19986156/multidimensional-php-array-key-exists
     * @param string $key
     * @param array $array
     * @return bool
     */
    public static function multi_array_key_exists($key, $array) {
        if (array_key_exists($key, $array)) {
            return true;
        } else {
            foreach ($array as $nested) {
                if (is_array($nested) && self::multi_array_key_exists($key, $nested))
                    return true;
            }
        }
        return false;
    }

    /**
     * Implodes one-dimensional arrays and multi-dimensional arrays.
     * Does sorting for one-dimensional arrays.
     * Only cares for array-keys for multi-dimensional arrays (when they are associative).
     * @param $arrayVal
     * @param array $objectFlattenHelpers
     * @param string $separator
     * @return string
     */
    public static function flattenArray($arrayVal, $objectFlattenHelpers = array(), $separator=', '){
        $returnVal = $arrayVal;
        if(is_array($arrayVal)){
            $depth = self::array_depth($arrayVal);
            if($depth <= 1){
                sort($arrayVal);
                $returnVal = implode($separator, $arrayVal);
            }else{
                $isAssoc = self::isAssoc($arrayVal);
                $returnVal = self::recursive_implode($arrayVal, $separator, $isAssoc, true, $objectFlattenHelpers);
            }
        }
        return strval($returnVal);
    }

    /**
     * from https://stackoverflow.com/questions/262891/is-there-a-way-to-find-out-how-deep-a-php-array-is/263621#263621
     * @param $array
     * @return int
     */
    public static function array_depth($array) {
        $max_indentation = 1;

        $array_str = print_r($array, true);
        $lines = explode("\n", $array_str);

        foreach ($lines as $line) {
            $indentation = (strlen($line) - strlen(ltrim($line))) / 4;

            if ($indentation > $max_indentation) {
                $max_indentation = $indentation;
            }
        }

        return intval(ceil(($max_indentation - 1) / 2) + 1);
    }


    /**
     * from https://gist.github.com/jimmygle/2564610
     * Recursively implodes an array with optional key inclusion
     *
     * Example of $include_keys output: key, value, key, value, key, value
     *
     * @access  public
     * @param   array   $array         multi-dimensional array to recursively implode
     * @param   string  $glue          value that glues elements together
     * @param   bool    $include_keys  include keys before their values
     * @param   bool    $trim_all      trim ALL whitespace from string
     * @return  string  imploded array
     */
    public static function recursive_implode($array, $glue = ',', $include_keys = false, $trim_all = true, $objectFlattenHelpers = array()) {
        $glued_string = '';

        $array = (array) $array;
        // Recursively iterates array and adds key/value to glued string
        array_walk_recursive($array, function($value, $key) use ($glue, $include_keys, $objectFlattenHelpers, &$glued_string)
        {
            if($include_keys){
                $glued_string .= $key.$glue;
            }
            if(is_object($value)){
                $flatterFound = false;
                foreach($objectFlattenHelpers as $className => $flattenCallable){
                    if($value instanceof $className){
                        if(is_callable($flattenCallable)){
                            $flatterFound = true;
                            try {
                                $value = call_user_func($flattenCallable, $value);
                            } catch (Exception $e) {
                                WADA_Log::error('recursive_implode Error in callable for class ' . $className . ': ' . $e->getMessage());
                                $flatterFound = false;
                            }
                            break;
                        }else{
                            WADA_Log::error('recursive_implode Callable for class ' . $className . ' is not callable');
                        }
                    }
                }
                if(!$flatterFound){
                    if(method_exists($value, '__toString')){
                        $value = (string) $value;
                    }else{
                        $value = serialize($value);
                    }
                }
            }
            $glued_string .= $value.$glue;
        });

        // Removes last $glue from string
        if(strlen($glue) > 0){
            $glued_string = substr($glued_string, 0, -strlen($glue));
        }

        // Trim ALL whitespace
        if($trim_all){
            $glued_string = preg_replace("/(\s)/ixsm", '', $glued_string);
        }

        return (string) $glued_string;
    }

    /**
     * from https://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential/173479#173479
     * @param array $arr
     * @return bool
     */
    public static function isAssoc(array $arr) {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * @param array<object|array> $objArray
     * @param string $property
     * @param bool $asc
     * @return array
     */
    public static function sortObjArrayByProperty($objArray, $property, $asc=true){
        usort($objArray, function($a, $b) use($property, $asc) {
            if(is_array($a)){
                return ($asc ? 1 : -1) * strcmp($a[$property], $b[$property]);
            }else{
                return ($asc ? 1 : -1) * strcmp($a->$property, $b->$property);
            }
        });
        return $objArray;
    }

}