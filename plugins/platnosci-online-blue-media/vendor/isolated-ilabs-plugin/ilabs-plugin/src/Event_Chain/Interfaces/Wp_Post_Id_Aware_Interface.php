<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces;

interface Wp_Post_Id_Aware_Interface
{
    public function get_post_id() : int;
}
