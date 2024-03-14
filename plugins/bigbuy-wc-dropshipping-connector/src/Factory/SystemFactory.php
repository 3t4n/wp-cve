<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

class SystemFactory
{
    /**
     * @param string $name
     * @param string $topic
     * @param string $web
     * @param int|null $id
     * @return \WC_Webhook
     * @throws \Exception
     */
    public function create(string $name, string $topic, string $web, int $id = null): \WC_Webhook
    {
        if ($id) {
            $webHook = new \WC_Webhook($id);
        } else {
            $webHook = new \WC_Webhook();
            $webHook->set_user_id(get_current_user_id());
        }

        $webHook->set_name($name);
        $webHook->set_status( 'active');
        $webHook->set_topic($topic);
        $webHook->set_delivery_url(get_home_url().$web);
        $webHook->set_secret(wp_generate_password(50, true, true));
        $webHook->set_api_version('wp_api_v3');
        $webHook->set_pending_delivery(0);

        return $webHook;
    }
}