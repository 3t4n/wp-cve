<?php

namespace wobef\classes\services\update;

interface Handler_Interface
{
    public static function get_instance();

    public function update($order_ids, $update_data);
}
