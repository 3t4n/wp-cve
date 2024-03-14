<?php

namespace wobel\classes\helpers;

use KhanhIceTea\Twigeval\Calculator;

class Formula
{
    public function calculate($input, $params)
    {
        $calculator = new Calculator(false);
        return $calculator->number($input, $params);
    }
}
