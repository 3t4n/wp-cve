<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
interface Group_Interface_Aware_Interface
{
    public function get_group_interface() : Group_Interface;
}
