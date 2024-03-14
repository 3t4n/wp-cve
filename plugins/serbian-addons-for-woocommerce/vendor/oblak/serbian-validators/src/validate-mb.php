<?php

namespace Oblak;

function validateMB($mb) {
    $control_number = mod11(substr($mb, 0, 7));
    if ($control_number > 9) {
        $control_number = 0;
    }

    return strlen($mb) == 8 && substr($mb, -1) == $control_number;
}
