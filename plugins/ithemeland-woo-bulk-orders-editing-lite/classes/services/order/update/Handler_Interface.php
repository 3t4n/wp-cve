<?php

namespace wobel\classes\services\order\update;

interface Handler_Interface
{
    public static function get_instance();

    public function update($order_ids, $update_data);
}
