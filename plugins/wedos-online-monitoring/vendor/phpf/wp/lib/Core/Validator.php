<?php
namespace PHPF\WP\Core;

/**
 * Basic validation methods
 *
 * @author  Petr Stastny <petr@stastny.eu>
 * @license GPLv3
 */
class Validator
{
    /**
     * Check whether string contains only allowed chars
     *
     * @param mixed $tx value to be validated
     * @param string $chars string of allowed chars
     * @return bool
     */
    public static function allowedChars($tx, $chars)
    {
        $tx = (string)$tx;
        $txLength = mb_strlen($tx);

        for ($i = 0; $i < $txLength; $i++) {
            if (mb_strpos($chars, $tx[$i]) === false) {
                return false;
            }
        }

        return true;
    }
}
