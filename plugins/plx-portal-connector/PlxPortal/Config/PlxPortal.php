<?php

namespace PlxPortal\Config;

use PlxPortal\Admin\ContentMetaBox;
use PlxPortal\Admin\ReplacementsMetaBox;
use PlxPortal\Admin\TokenMetaBox;
use PlxPortal\Admin\InformationMetaBox;
use PlxPortal\Admin\Enqueue;
use PlxPortal\Api\Api;
use PlxPortal\Content\ContentCpt;
use PlxPortal\Content\AccessToken;
use PlxPortal\Content\Content;
use PlxPortal\Content\Replaceables;
use PlxPortal\Content\Shortcode;

class PlxPortal
{
    const TOKEN_META_FIELD = '_plx_portal_install_token';

    public function __construct()
    {
        $this->init();

        register_activation_hook(PLX_PORTAL_PLUGIN, array($this, 'install'));
        register_deactivation_hook(PLX_PORTAL_PLUGIN, array($this, 'uninstall'));
        add_action('upgrader_process_complete', array($this, 'afterUpdate'), 10, 2);
        add_action('update_option_siteurl', [$this, 'updateUrl'], 10, 2);
    }

    private function init()
    {
        new Enqueue();
        new ContentMetaBox();
        new ReplacementsMetaBox();
        new TokenMetaBox();
        new InformationMetaBox();
        new ContentCpt();
        new AccessToken();
        new Content();
        new Replaceables();
        new Shortcode();
        new Api();
    }

    public function install()
    {
        $body = ['url' => get_site_url()];

        $token = get_option(self::TOKEN_META_FIELD);

        if (isset($token)) {
            $body['token'] = $token;
        }

        $response = wp_remote_post('https://portal.plx.mk/api/register/install', array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'        => json_encode($body),
            'method'      => 'POST',
        ));

        $data = json_decode(wp_remote_retrieve_body($response));

        if (isset($data->token)) {
            update_option(self::TOKEN_META_FIELD, $data->token);
        }
    }

    public function afterUpdate()
    {
        $token = get_option(self::TOKEN_META_FIELD);

        if (!$token) {
            self::install();
        }
    }

    public function updateUrl($old, $new)
    {
        $token = get_option(self::TOKEN_META_FIELD);

        if (isset($token)) {
            $body = [
                'url' => $new,
                'token' => $token
            ];

            wp_remote_post('https://portal.plx.mk/api/register/install', array(
                'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
                'body'        => json_encode($body),
                'method'      => 'POST',
            ));
        }
    }

    public function uninstall()
    {
        $token = get_option(self::TOKEN_META_FIELD);

        if (isset($token)) {
            wp_remote_post('https://portal.plx.mk/api/register/uninstall', array(
                'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
                'body' => json_encode([
                    'url'   => get_site_url(),
                    'token' => $token
                ]),
                'method' => 'POST',
            ));

            delete_option(self::TOKEN_META_FIELD);
        }
    }
}
