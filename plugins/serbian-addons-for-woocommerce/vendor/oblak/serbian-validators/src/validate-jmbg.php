<?php

namespace Oblak;

function validateJMBG($jmbg) {
    $day   = substr($jmbg, 0, 2);
    $month = substr($jmbg, 2, 2);
    $year  = (substr($jmbg, 5, 2) > date('y'))
        ? '1' . substr($jmbg, 4, 3)
        : '2' . substr($jmbg, 4, 3);

    if (!checkdate($month, $day, $year)) {
        return false;
    }

    if (in_array(substr($jmbg, 7, 2), ['60', '66'])) {
        return true;
    }

    return strlen($jmbg) == 13 && substr($jmbg, -1) == mod11(substr($jmbg, 0, 12));
}
