<?php

namespace Attire\Blocks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Attire\Blocks\Util;

class AttireBlocksSettings
{
    private static $instance;
    private $settings_api;
    private $settings_stabs;
    private $settings_fields;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
            self::$instance->actions();
        }
        return self::$instance;
    }

    private function actions()
    {
        add_action('admin_head', array($this, 'custom_menu_icon'));
        add_action('admin_menu', array($this, 'register_custom_menu_page'));
        add_action('wp_ajax_atbs_verify_license', array($this, 'verify_license_key'));
        add_action('wp_ajax_atbs_disable_fe_assets', array($this, 'disable_fe_assets'));
    }

    function verify_license_key()
    {
        $key = sanitize_text_field($_REQUEST['key']);
        $response = Util::validate_license($key);
        echo $response;
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    function disable_fe_assets()
    {

	    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	    $data = json_encode($_POST['data']);
        if (empty($_POST['data'])) {
            update_option('__atbs_disabled_assets', []);
        } else {
            update_option('__atbs_disabled_assets', $data);
        }
        echo json_encode(array('success' => true, 'msg' => 'Settings updated successfully.'), JSON_FORCE_OBJECT);
        wp_die(); // this is required to terminate immediately and return a proper response
    }


    function register_custom_menu_page()
    {
        add_menu_page(
            __('Attire Blocks', 'attire-blocks'),
            'Attire Blocks',
            'manage_options',
            'attireblocks',
            array($this, 'admin_page_html'),
            'dashicons-atbs',
            6
        );
    }

    function admin_page_html()
    {
        include ATTIRE_BLOCKS_DIR_PATH . '/admin/admin-page.php';
    }

    function custom_menu_icon()
    {
        echo '<style> .dashicons-atbs {background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMTZwdCIgaGVpZ2h0PSIxNXB0IiB2aWV3Qm94PSIwIDAgMTYgMTUiIHZlcnNpb249IjEuMSI+CjxkZWZzPgo8Y2xpcFBhdGggaWQ9ImNsaXAxIj4KICA8cGF0aCBkPSJNIDQgMCBMIDE2IDAgTCAxNiA4IEwgNCA4IFogTSA0IDAgIi8+CjwvY2xpcFBhdGg+CjxjbGlwUGF0aCBpZD0iY2xpcDIiPgogIDxwYXRoIGQ9Ik0gMTUuNDc2NTYyIDYuOTUzMTI1IEMgMTUuODcxMDk0IDYuMjA3MDMxIDE1Ljc1IDYuMDU0Njg4IDE1LjA3NDIxOSA0Ljg3ODkwNiBDIDE0LjcxODc1IDQuMjU3ODEyIDE0LjQ0OTIxOSAzLjc5Mjk2OSAxNC4wOTc2NTYgMy4xNzE4NzUgQyAxMy43ODEyNSAyLjYyODkwNiAxMy40Njg3NSAyLjA4NTkzOCAxMy4xNjAxNTYgMS41NDI5NjkgQyAxMi4yNzczNDQgMCAxMi40MTc5NjkgMC4wODU5Mzc1IDEwLjY3MTg3NSAwLjA3MDMxMjUgQyAxMC4wNTQ2ODggMC4wNjY0MDYyIDkuNDM3NSAwLjA2MjUgOC44MjQyMTkgMC4wNTg1OTM4IEMgOC4xMjEwOTQgMC4wNTA3ODEyIDcuNTg5ODQ0IDAuMDQ2ODc1IDYuODkwNjI1IDAuMDQyOTY4OCBDIDUuNTU0Njg4IDAuMDMxMjUgNS4zNjcxODggMCA0LjkzMzU5NCAwLjcyMjY1NiBDIDQuOTE0MDYyIDAuNzYxNzE5IDQuOTA2MjUgMC44MDA3ODEgNC45MDIzNDQgMC44Mzk4NDQgQyA0Ljg5ODQzOCAxLjAwNzgxMiA1LjQxNDA2MiAxLjg1NTQ2OSA1LjUgMi4wMTE3MTkgQyA1Ljc1NzgxMiAyLjQ2MDkzOCA2LjAxNTYyNSAyLjkxMDE1NiA2LjI3MzQzOCAzLjM1OTM3NSBDIDYuNSAzLjM2MzI4MSA2LjczMDQ2OSAzLjM2MzI4MSA2Ljk1NzAzMSAzLjM2MzI4MSBDIDcuNTc0MjE5IDMuMzcxMDk0IDguMTkxNDA2IDMuMzc1IDguODA0Njg4IDMuMzc4OTA2IEMgMTAuNTUwNzgxIDMuMzk0NTMxIDEwLjQwNjI1IDMuMzA4NTk0IDExLjI5Mjk2OSA0Ljg0NzY1NiBDIDExLjYwNTQ2OSA1LjM5MDYyNSAxMS45MTc5NjkgNS45Mzc1IDEyLjIzMDQ2OSA2LjQ4MDQ2OSBDIDEyLjM0Mzc1IDYuNjc5Njg4IDEyLjQ2MDkzOCA2Ljg4MjgxMiAxMi41NzQyMTkgNy4wODIwMzEgTCAxNC4xMDE1NjIgNy4wOTM3NSBDIDE0LjI3NzM0NCA3LjA5Mzc1IDE1LjI1MzkwNiA3LjEyNSAxNS4zOTQ1MzEgNy4wMzkwNjIgQyAxNS40MjU3ODEgNy4wMTU2MjUgMTUuNDU3MDMxIDYuOTg0Mzc1IDE1LjQ4MDQ2OSA2Ljk1MzEyNSAiLz4KPC9jbGlwUGF0aD4KPGNsaXBQYXRoIGlkPSJjbGlwMyI+CiAgPHBhdGggZD0iTSAyIDAgTCAxNiAwIEwgMTYgMTEgTCAyIDExIFogTSAyIDAgIi8+CjwvY2xpcFBhdGg+CjxjbGlwUGF0aCBpZD0iY2xpcDQiPgogIDxwYXRoIGQ9Ik0gMTYgNy4yODkwNjIgTCAxNS4zNzg5MDYgOC4zOTA2MjUgTCAxNC44MjgxMjUgOS4zNjcxODggQyAxMy44OTg0MzggMTEuMDExNzE5IDE0LjA2NjQwNiAxMC45NTcwMzEgMTIuMjA3MDMxIDEwLjk0MTQwNiBDIDExLjQzMzU5NCAxMC45Mzc1IDEwLjg0NzY1NiAxMC45MzM1OTQgMTAuMDc4MTI1IDEwLjkyNTc4MSBDIDkuMzk4NDM4IDEwLjkyMTg3NSA4LjcxODc1IDEwLjkxNDA2MiA4LjAzOTA2MiAxMC45MTAxNTYgQyA2LjExNzE4OCAxMC44OTQ1MzEgNi4yNzczNDQgMTAuOTg4MjgxIDUuMzAwNzgxIDkuMjkyOTY5IEMgNC45NTcwMzEgOC42OTUzMTIgNC42MTMyODEgOC4wOTM3NSA0LjI2OTUzMSA3LjQ5NjA5NCBDIDMuODc4OTA2IDYuODE2NDA2IDMuNTgyMDMxIDYuMzAwNzgxIDMuMTkxNDA2IDUuNjE3MTg4IEMgMi4yNSAzLjk4MDQ2OSAyLjI4OTA2MiA0LjE0ODQzOCAzLjIxNDg0NCAyLjUwNzgxMiBMIDMuNzY1NjI1IDEuNTI3MzQ0IEwgNC4zODY3MTkgMC40Mjk2ODggQyAzLjkxMDE1NiAxLjMwODU5NCA0LjAyNzM0NCAxLjQ0OTIxOSA0Ljc5Mjk2OSAyLjc3NzM0NCBDIDUuMTgzNTk0IDMuNDYwOTM4IDUuNDgwNDY5IDMuOTc2NTYyIDUuODcxMDk0IDQuNjU2MjUgQyA2LjIxNDg0NCA1LjI1MzkwNiA2LjU1ODU5NCA1Ljg1NTQ2OSA2LjkwNjI1IDYuNDUzMTI1IEMgNy44Nzg5MDYgOC4xNDg0MzggNy43MjI2NTYgOC4wNTQ2ODggOS42NDQ1MzEgOC4wNzAzMTIgQyAxMC4zMjAzMTIgOC4wNzgxMjUgMTEgOC4wODIwMzEgMTEuNjc5Njg4IDguMDg1OTM4IEMgMTIuNDUzMTI1IDguMDkzNzUgMTMuMDM1MTU2IDguMDk3NjU2IDEzLjgwODU5NCA4LjEwNTQ2OSBDIDE1LjMxNjQwNiA4LjExNzE4OCAxNS40OTIxODggOC4xNTIzNDQgMTYgNy4yODkwNjIgIi8+CjwvY2xpcFBhdGg+CjxjbGlwUGF0aCBpZD0iY2xpcDUiPgogIDxwYXRoIGQ9Ik0gMCA0IEwgMTQgNCBMIDE0IDE1IEwgMCAxNSBaIE0gMCA0ICIvPgo8L2NsaXBQYXRoPgo8Y2xpcFBhdGggaWQ9ImNsaXA2Ij4KICA8cGF0aCBkPSJNIDEzLjc1IDExLjI3NzM0NCBMIDEzLjEyODkwNiAxMi4zNzg5MDYgTCAxMi41NzQyMTkgMTMuMzU5Mzc1IEMgMTEuNjQ4NDM4IDE1IDExLjgxNjQwNiAxNC45NDUzMTIgOS45NTMxMjUgMTQuOTMzNTk0IEMgOS4xODM1OTQgMTQuOTI1NzgxIDguNTk3NjU2IDE0LjkyMTg3NSA3LjgyODEyNSAxNC45MTQwNjIgQyA3LjE0ODQzOCAxNC45MTAxNTYgNi40Njg3NSAxNC45MDYyNSA1Ljc4OTA2MiAxNC44OTg0MzggQyAzLjg2NzE4OCAxNC44ODI4MTIgNC4wMjczNDQgMTQuOTc2NTYyIDMuMDUwNzgxIDEzLjI4MTI1IEMgMi43MDcwMzEgMTIuNjgzNTk0IDIuMzYzMjgxIDEyLjA4NTkzOCAyLjAxOTUzMSAxMS40ODQzNzUgQyAxLjYyODkwNiAxMC44MDQ2ODggMS4zMzIwMzEgMTAuMjg5MDYyIDAuOTQxNDA2IDkuNjA1NDY5IEMgMCA3Ljk2ODc1IDAuMDM5MDYyNSA4LjEzNjcxOSAwLjk2NDg0NCA2LjQ5NjA5NCBMIDEuNTE1NjI1IDUuNTE5NTMxIEwgMi4xMzY3MTkgNC40MTc5NjkgQyAxLjY2MDE1NiA1LjMwMDc4MSAxLjc3NzM0NCA1LjQzNzUgMi41NDI5NjkgNi43Njk1MzEgQyAyLjkzMzU5NCA3LjQ0OTIxOSAzLjIzMDQ2OSA3Ljk2NDg0NCAzLjYyMTA5NCA4LjY0NDUzMSBDIDMuOTY0ODQ0IDkuMjQ2MDk0IDQuMzA4NTk0IDkuODQzNzUgNC42NTIzNDQgMTAuNDQxNDA2IEMgNS42Mjg5MDYgMTIuMTM2NzE5IDUuNDY4NzUgMTIuMDQyOTY5IDcuMzkwNjI1IDEyLjA1ODU5NCBDIDguMDcwMzEyIDEyLjA2NjQwNiA4Ljc1IDEyLjA3MDMxMiA5LjQyOTY4OCAxMi4wNzQyMTkgQyAxMC4xOTkyMTkgMTIuMDgyMDMxIDEwLjc4NTE1NiAxMi4wODU5MzggMTEuNTU4NTk0IDEyLjA5Mzc1IEMgMTMuMDY2NDA2IDEyLjEwNTQ2OSAxMy4yNDIxODggMTIuMTQwNjI1IDEzLjc1IDExLjI3NzM0NCAiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8ZyBpZD0ic3VyZmFjZTEiPgo8ZyBjbGlwLXBhdGg9InVybCgjY2xpcDEpIiBjbGlwLXJ1bGU9Im5vbnplcm8iPgo8ZyBjbGlwLXBhdGg9InVybCgjY2xpcDIpIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiPgo8cGF0aCBzdHlsZT0iIHN0cm9rZTpub25lO2ZpbGwtcnVsZTpub256ZXJvO2ZpbGw6cmdiKDYxLjk2MDc4NCUsNjMuOTIxNTY5JSw2NS44ODIzNTMlKTtmaWxsLW9wYWNpdHk6MTsiIGQ9Ik0gNC44OTg0MzggMCBMIDE1Ljg3MTA5NCAwIEwgMTUuODcxMDk0IDcuMTI1IEwgNC44OTg0MzggNy4xMjUgWiBNIDQuODk4NDM4IDAgIi8+CjwvZz4KPC9nPgo8ZyBjbGlwLXBhdGg9InVybCgjY2xpcDMpIiBjbGlwLXJ1bGU9Im5vbnplcm8iPgo8ZyBjbGlwLXBhdGg9InVybCgjY2xpcDQpIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiPgo8cGF0aCBzdHlsZT0iIHN0cm9rZTpub25lO2ZpbGwtcnVsZTpub256ZXJvO2ZpbGw6cmdiKDYxLjk2MDc4NCUsNjMuOTIxNTY5JSw2NS44ODIzNTMlKTtmaWxsLW9wYWNpdHk6MTsiIGQ9Ik0gMi4yNSAwLjQyOTY4OCBMIDE2IDAuNDI5Njg4IEwgMTYgMTEuMDExNzE5IEwgMi4yNSAxMS4wMTE3MTkgWiBNIDIuMjUgMC40Mjk2ODggIi8+CjwvZz4KPC9nPgo8ZyBjbGlwLXBhdGg9InVybCgjY2xpcDUpIiBjbGlwLXJ1bGU9Im5vbnplcm8iPgo8ZyBjbGlwLXBhdGg9InVybCgjY2xpcDYpIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiPgo8cGF0aCBzdHlsZT0iIHN0cm9rZTpub25lO2ZpbGwtcnVsZTpub256ZXJvO2ZpbGw6cmdiKDYxLjk2MDc4NCUsNjMuOTIxNTY5JSw2NS44ODIzNTMlKTtmaWxsLW9wYWNpdHk6MTsiIGQ9Ik0gMCA0LjQxNzk2OSBMIDEzLjc1IDQuNDE3OTY5IEwgMTMuNzUgMTUgTCAwIDE1IFogTSAwIDQuNDE3OTY5ICIvPgo8L2c+CjwvZz4KPC9nPgo8L3N2Zz4K");background-repeat: no-repeat;background-position: center; }</style>';
    }
}