<?php

require_once(__DIR__ . '/../settings/settings.php');
require_once(__DIR__ . '/../user/user.php');
require_once(__DIR__ . '/../utils/utils.php');

class CBroDisplayChat
{
    private $chat = null;
    private $guid;
    private $id;

    public function __construct($ch)
    {
        if (is_null($ch)) {
            return;
        }

        $this->chat = $ch;
        $this->guid = $ch->get_guid();
        $this->id = $ch->get_id();
    }

    public function generate_code(
        $container_id = null,
        $static = false,
        $is_child_chat = false,
        $chat_title = null,
        $ext_id = null
    ) {
        if (is_null($this->chat)) {
            return "";
        }

        $site_domain = CBroUtils::get_site_domain();
        $site_user_avatar_url = CBroUser::avatar_url();
        $profile_url = CBroUser::profile_url();

        $permissions = array();

        if (CBroUser::can_delete()) {
            array_push($permissions, 'delete');
        }

        if (CBroUser::can_ban()) {
            array_push($permissions, 'ban');
        }

        // ChatbroLoader parameters
        $params = "siteDomain: '" . base64_encode($site_domain) . "'";

        if ($is_child_chat && $chat_title) {
            $params .= ", parentEncodedChatId: '{$this->id}', chatTitle: '{$chat_title}'";

            if ($ext_id) {
                $params .= ", extId: '{$ext_id}'";
            }
        } else {
            $params .= ", encodedChatId: '{$this->id}'";
        }

        $sig_source = "";

        if (CBroUser::is_logged_in()) {
            $sig_source = $site_domain
                . CBroUser::id()
                . CBroUser::display_name()
                . $site_user_avatar_url
                . $profile_url
                . implode('', $permissions);

            $params .= ", siteUserFullName: '"
                . CBroUser::display_name()
                . "', siteUserExternalId: '"
                . CBroUser::id() . "'";

            if ($site_user_avatar_url != "") {
                $params .= ", siteUserAvatarUrl: '" . base64_encode($site_user_avatar_url) . "'";
            }

            if ($profile_url != '') {
                $params .= ", siteUserProfileUrl: '" . base64_encode($profile_url) . "'";
            }
        } else {
            $sig_source = $site_domain;
        }

        $signature = md5($sig_source . $this->guid);

        if ($container_id) {
            $params .= ", containerDivId: '{$container_id}'";
        }

        if ($static) {
            $params .= ", isStatic: true";
        } else {
            $params .= ", isStatic: false";
        }

        $params .= ", signature: '{$signature}'";
        $params .= ", platform: '" . CBroUtils::get_platform() . "'";

        if (!empty($permissions)) {
            $params .= ", permissions: ['" . implode("','", $permissions) . "']";
        }

        ob_start();
        ?>
        <script id="chatBroEmbedCode">
            /* Chatbro Widget Embed Code Start */
            function ChatbroLoader(chats, async) {
                function decodeProp(prop) {
                    if (chats.hasOwnProperty(prop)) chats[prop] = atob(chats[prop]);
                };
                decodeProp('siteDomain');
                decodeProp('siteUserAvatarUrl');
                decodeProp('siteUserProfileUrl');
                async = !1 !== async;
                var params = {
                    embedChatsParameters: chats instanceof Array ? chats : [chats],
                    lang: navigator.language || navigator.userLanguage,
                    needLoadCode: "undefined" == typeof Chatbro,
                    embedParamsVersion: localStorage.embedParamsVersion,
                    chatbroScriptVersion: localStorage.chatbroScriptVersion
                },
                    xhr = new XMLHttpRequest;
                xhr.withCredentials = !0;
                xhr.onload = function () {
                    eval(xhr.responseText);
                };
                xhr.onerror = function () {
                    console.error("Chatbro loading error");
                };
                xhr.open("GET", "https://www.chatbro.com/embed.js?" +
                    btoa(unescape(encodeURIComponent(JSON.stringify(params)))), async);
                xhr.send();
            }
            /* Chatbro Widget Embed Code End */
            if (typeof chatBroHistoryPage === 'undefined' || !chatBroHistoryPage) ChatbroLoader({ <?php echo $params; ?> });
        </script>
        <?php

        $code = ob_get_contents();
        ob_end_clean();

        return $code;
    }

    public function get_sitewide_popup_chat_code()
    {
        return $this->generate_sitewide_popup_code();
    }

    public function generate_sitewide_popup_code()
    {
        if (is_null($this->chat)) {
            return;
        }

        if (!CBroUser::can_view($this->chat->get_display_to_guests())) {
            return;
        }

        switch ($this->chat->get_display()) {
            case '':
            case 'everywhere':
                break;

            case 'frontpage_only':
                if (!CBroUtils::is_front_page()) {
                    return;
                }
                break;

            case 'except_listed':
            case 'only_listed':
                if (!CBroUtils::check_path($this->chat->get_display(), $this->chat->get_selected_pages())) {
                    return;
                }
                break;

            default:
                return;
        }

        return $this->generate_code();
    }

    public function get_chat_code()
    {
        return $this->generate_code();
    }

    public function get_static_chat_code()
    {
        $encoded_guid = $this->id;
        $container_id = "chatbro-{$encoded_guid}-" . rand(0, 99999);
        $code = $container_id ? "<div id=\"{$container_id}\"></div>" : "";

        return $code . $this->generate_code($container_id, true);
    }

    public function get_child_chat_code($is_static, $chat_title, $ext_id)
    {
        if (!$is_static) {
            return $this->generate_code(null, false, true, $chat_title, $ext_id);
        } else {
            $encoded_guid = $this->id;
            $container_id = "chatbro-{$encoded_guid}-" . rand(0, 99999);
            $code = $container_id ? "<div id=\"{$container_id}\"></div>" : "";

            return $code . $this->generate_code($container_id, true, true, $chat_title, $ext_id);
        }
    }
}

?>