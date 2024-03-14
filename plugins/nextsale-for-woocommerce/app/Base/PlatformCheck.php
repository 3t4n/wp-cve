<?php

namespace App\Base;

use App\Utils\Helper;
use App\Models\Shop as ShopModel;

class PlatformCheck extends Plugin
{
    /**
     * Register
     * @return void
     */
    public function register()
    {
        add_action('wp_head', [$this, 'check']);
    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function check()
    {
        $access_token = get_option('nextsale_access_token');
        $data = json_decode(get_option('nextsale_platform'));

        if (!$access_token) {
            return;
        }

        $platform = Helper::getPlatform();

        if (isset($data->platform, $data->timestamp)) {
            if ((int) $data->timestamp + getenv('PLATFORM_CHECK_INTERVAL') > time()) {
                // All set
                return;
            }

            if ($data->platform != $platform) {
                $this->sendUpdate();
            }

            $this->updateOption($platform);
            return;
        }

        $this->sendUpdate();
        $this->updateOption($platform);
    }

    /**
     * Update the option
     *
     * @param string $platform
     * @return void
     */
    private function updateOption($platform)
    {
        update_option('nextsale_platform', json_encode([
            'platform' => $platform,
            'timestamp' => time()
        ]));
    }

    /**
     * Send platform update
     *
     * @param string $platform
     * @return void
     */
    private function sendUpdate()
    {
        $data = ShopModel::get();
        Webhook::send(Webhook::SHOP_UPDATE, $data);
    }
}
