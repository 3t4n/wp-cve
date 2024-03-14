<?php
namespace Mnet\Admin;

use Mnet\Admin\MnetAuthManager;
use Mnet\MnetDbManager;
use Mnet\Admin\MnetBasicSlots;

class MnetAdBasicConfiguration
{
    public static function saveAdSlots()
    {
        MnetAuthManager::returnIfSessionExpired();
        $selected_slots = $_POST['selectedSlots'];
        $debug_mode = isset($_POST['debugMode']) ? $_POST['debugMode'] : 0;

        $basic_slot = new MnetBasicSlots($selected_slots, $debug_mode);
        $result = $basic_slot->save();
        \wp_send_json($result, 200);
    }

    public static function getAdSlots()
    {
        MnetAuthManager::returnIfSessionExpired();
        \wp_send_json(array('slots' => MnetDbManager::getAdSlots()), 200);
    }
}
