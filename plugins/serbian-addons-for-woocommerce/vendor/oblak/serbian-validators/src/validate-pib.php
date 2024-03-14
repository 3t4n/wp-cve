<?php

namespace Oblak;

/**
 * Check is the Taxpayer Identification Number (PIB) is valid
 *
 * @param  int|string $pib
 * @return bool
 */
function validatePIB($pib) {
    $digits = str_split(substr((string) $pib, 0, 8));

    $sum = 10;

    foreach ($digits as $digit) {
        $sum = ($sum + (int) $digit) % 10;
        if ($sum === 0) {
            $sum = 10;
        }

        $sum = ($sum * 2) % 11;
    }
    $sum = (11 - $sum) % 10;

    return (count(str_split($pib)) == 9) && ($sum === (int) substr($pib, 8, 1));
}
