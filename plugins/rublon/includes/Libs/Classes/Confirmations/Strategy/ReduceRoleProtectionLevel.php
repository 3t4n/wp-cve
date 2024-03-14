<?php

namespace Rublon_WordPress\Libs\Classes\Confirmations\Strategy;

use Rublon_WordPress\Libs\Classes\Confirmations\RublonConfirmStrategy;

class RublonConfirmStrategy_ReduceRoleProtectionLevel extends RublonConfirmStrategy
{

    protected $action = 'ReduceRoleProtectionLevel';
    protected $label = 'Reduce the role protection level';
    protected $confirmMessage = 'Do you want to reduce the role protection level?';


    /**
     * Logic is handled by the old RublonHelper code.
     */


}
