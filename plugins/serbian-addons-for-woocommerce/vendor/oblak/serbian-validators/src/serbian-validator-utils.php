<?php

namespace Oblak;

/**
 * Checks if number is valid using check digit 11 function (mod11)
 *
 * @param  int      $number Number to check
 * @return number           Check digit
 */
function mod11($number) {
    $digits = array_reverse(str_split((string) $number));

    $sum = 0;
    foreach ($digits as $index => $digit) {
        $sum += (int) $digit * (($index % 6) + 2);
    }

    $remainder = $sum % 11;

    switch ($remainder) {
        case 0:
            return $remainder;
            break;
        case 1:
            return 0;
            break;
        default:
            return 11 - $remainder;
            break;
    }
}

/**
 * Checks if the number is valid using check digit 97 function (mod97)
 *
 * @param  int  $number Number to check
 * @param  int  $check  Check numbers
 * @return bool         True if valid, false otherwise
 */
function mod97($number, $check) {
    if ($check > 100) {
        return false;
    }

    $number *= 100;

    $remainder = $number % 97;

    return (98 - $remainder) === $check;
}

function getSerbianBanks() {
    return [
        '105',
        '115',
        '145',
        '150',
        '155',
        '160',
        '165',
        '170',
        '190',
        '200',
        '205',
        '220',
        '250',
        '265',
        '285',
        '295',
        '310',
        '325',
        '330',
        '340',
        '360',
        '370',
        '375',
        '380',
        '385',
    ];
}
