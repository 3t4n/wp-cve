<?php

namespace Memsource\Utils;

class ArrayUtils
{
    /**
     * Check that array contains only strings.
     *
     * @param array $array
     *
     * @return bool
     *
     * @see \MemsourceTests\Utils\ArrayUtilsTest
     */
    public function isScalarArrayOfStrings(array $array): bool
    {
        $filteredStringValues = array_filter(
            $array,
            static function ($var) {
                return is_string($var);
            }
        );

        if (count($array) !== count($filteredStringValues)) {
            return false;
        }

        if (array_keys($array) !== range(0, count($array) - 1)) {
            return false;
        }

        return true;
    }

    /**
     * Check if keys exists and have a value.
     * @param $data array source
     * @param $keys array keys
     * @return array source
     * @throws \InvalidArgumentException
     */
    public static function checkKeyExists(array $data, array $keys)
    {
        foreach ($keys as $key) {
            if (!isset($data[$key]) || $data[$key] === '') {
                throw new \InvalidArgumentException(sprintf('Missing %s.', $key));
            }
        }
        return $data;
    }
}
