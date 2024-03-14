<?php

/*
  Plugin Name: LINE Auto Post
  Plugin URI: https://s-page.biz/line-auto-post/
  Description: WordPressの投稿をLINE公式アカウント経由で連動配信することができるプラグインです。
  Version: 1.0.8
  Author: growniche
  Author URI: https://www.growniche.co.jp/
*/

// WordPressの読み込みが完了してヘッダーが送信される前に実行するアクションに、
// LineAutoPostクラスのインスタンスを生成するStatic関数をフック
add_action('init', 'LineAutoPost::instance');

class LineAutoPost {

    /**
     * このプラグインのバージョン
     */
    const VERSION = '1.0.7';

    /**
     * このプラグインのID：Growniche Line Auto Post
     */
    const PLUGIN_ID = 'glap';

    /**
     * Credentialプレフィックス
     */
    const CREDENTIAL_PREFIX = self::PLUGIN_ID . '-nonce-action_';

    /**
     * CredentialAction：設定
     */
    const CREDENTIAL_ACTION__SETTINGS_FORM = self::PLUGIN_ID . '-nonce-action_settings-form';

    /**
     * CredentialAction：投稿
     */
    const CREDENTIAL_ACTION__POST = self::PLUGIN_ID . '-nonce-action_post';

    /**
     * CredentialName：設定
     */
    const CREDENTIAL_NAME__SETTINGS_FORM = self::PLUGIN_ID . '-nonce-name_settings-form';

    /**
     * CredentialName：投稿
     */
    const CREDENTIAL_NAME__POST = self::PLUGIN_ID . '-nonce-name_post';

    /**
     * (23文字)
     */
    const PLUGIN_PREFIX = self::PLUGIN_ID . '_';

    /**
     * OPTIONSテーブルのキー：ChannelAccessToken
     */
    const OPTION_KEY__CHANNEL_ACCESS_TOKEN = self::PLUGIN_PREFIX . 'channel-access-token';

    /**
     * 画面のslug：トップ
     */
    const SLUG__SETTINGS_FORM = self::PLUGIN_ID . '-settings-form';

    /**
     * 画面のslug：初期設定
     */
    const SLUG__INITIAL_CONFIG_FORM = self::PLUGIN_PREFIX . 'initial-config-form';

    /**
     * パラメータ名：ChannelAccessToken
     */
    const PARAMETER__CHANNEL_ACCESS_TOKEN = self::PLUGIN_PREFIX . 'channel-access-token';

    /**
     * パラメータ名：LINEメッセージ送信チェックボックス
     */
    const PARAMETER__SEND_CHECKBOX = self::PLUGIN_PREFIX . 'send-checkbox';

    /**
     * TRANSIENTキー(一時入力値)：ChannelAccessToken ※4文字+41文字以下
     */
    const TRANSIENT_KEY__TEMP_CHANNEL_ACCESS_TOKEN = self::PLUGIN_PREFIX . 'temp-channel-access-token';

    /**
     * TRANSIENTキー(不正メッセージ)：ChannelAccessToken
     */
    const TRANSIENT_KEY__INVALID_CHANNEL_ACCESS_TOKEN = self::PLUGIN_PREFIX . 'invalid-channel-access-token';

    /**
     * TRANSIENTキー(エラー)：LINEメッセージ送信失敗
     */
    const TRANSIENT_KEY__ERROR_SEND_TO_LINE = self::PLUGIN_PREFIX . 'error-send-to-line';

    /**
     * TRANSIENTキー(成功)：LINEメッセージ送信成功
     */
    const TRANSIENT_KEY__SUCCESS_SEND_TO_LINE = self::PLUGIN_PREFIX . 'success-send-to-line';

    /**
     * TRANSIENTキー(保存完了メッセージ)：設定
     */
    const TRANSIENT_KEY__SAVE_SETTINGS = self::PLUGIN_PREFIX . 'save-settings';

    /**
     * TRANSIENTのタイムリミット：5秒
     */
    const TRANSIENT_TIME_LIMIT = 5;

    /**
     * 通知タイプ：エラー
     */
    const NOTICE_TYPE__ERROR = 'error';

    /**
     * 通知タイプ：警告
     */
    const NOTICE_TYPE__WARNING = 'warning';

    /**
     * 通知タイプ：成功
     */
    const NOTICE_TYPE__SUCCESS = 'success';

    /**
     * 通知タイプ：情報
     */
    const NOTICE_TYPE__INFO = 'info';

    /**
     * 暗号化する時のパスワード：STRIPEの公開キーとシークレットキーの複合化で使用
     */
    const ENCRYPT_PASSWORD = 's9YQReXd';

    /**
     * 正規表現：ChannelAccessToken
     */
    const REGEXP_CHANNEL_ACCESS_TOKEN = '/^[a-zA-Z0-9+\/=]{100,}$/';

    /**
     * WordPressの読み込みが完了してヘッダーが送信される前に実行するアクションにフックする、
     * SimpleStripeCheckoutクラスのインスタンスを生成するStatic関数
     */
    static function instance() {
        return new self();
    }

    /**
     * 複合化：AES 256
     * @param edata 暗号化してBASE64にした文字列
     * @param string 複合化のパスワード
     * @return 複合化された文字列
     */
    static function decrypt($edata, $password) {
        $data = base64_decode($edata);
        $salt = substr($data, 0, 16);
        $ct = substr($data, 16);
        $rounds = 3; // depends on key length
        $data00 = $password.$salt;
        $hash = array();
        $hash[0] = hash('sha256', $data00, true);
        $result = $hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
            $result .= $hash[$i];
        }
        $key = substr($result, 0, 32);
        $iv  = substr($result, 32,16);
        return openssl_decrypt($ct, 'AES-256-CBC', $key, 0, $iv);
    }

    /**
     * crypt AES 256
     *
     * @param data $data
     * @param string $password
     * @return base64 encrypted data
     */
    static function encrypt($data, $password) {
        // Set a random salt
        $salt = openssl_random_pseudo_bytes(16);
        $salted = '';
        $dx = '';
        // Salt the key(32) and iv(16) = 48
        while (strlen($salted) < 48) {
          $dx = hash('sha256', $dx.$password.$salt, true);
          $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);
        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($salt . $encrypted_data);
    }

    /**
     * HTMLのOPTIONタグを生成・取得
     */
    static function makeHtmlSelectOptions($list, $selected, $label = null) {
        $html = '';
        foreach ($list as $key => $value) {
            $html .= '<option class="level-0" value="' . $key . '"';
            if ($key == $selected) {
                $html .= ' selected="selected';
            }
            $html .= '">' . (is_null($label) ? $value : $value[$label]) . '</option>';
        }
        return $html;
    }

    /**
     * 通知タグを生成・取得
     * @param message 通知するメッセージ
     * @param type 通知タイプ(error/warning/success/info)
     * @retern 通知タグ(HTML)
     */
    static function getNotice($message, $type) {
        return
            '<div class="notice notice-' . $type . ' is-dismissible">' .
            '<p><strong>' . esc_html($message) . '</strong></p>' .
            '<button type="button" class="notice-dismiss">' .
            '<span class="screen-reader-text">Dismiss this notice.</span>' .
            '</button>' .
            '</div>';
    }

    /**
     * コンストラクタ
     */
    function __construct() {
        // 特権管理者、管理者、編集者、投稿者の何れでもない場合は無視
        if (is_super_admin() || current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) {
            // 投稿(公開)した時のコールバック関数を定義
            add_action('publish_post', [$this, 'send_to_line'], 1, 6);
            // 投稿(公開)した際にLINE送信に失敗した時のメッセージ表示
            add_action('admin_notices', [$this, 'error_send_to_line']);
            // 投稿(公開)した際にLINE送信に成功した時のメッセージ表示
            add_action('admin_notices', [$this, 'success_send_to_line']);
            // 投稿画面にチェックボックスを表示
            add_action('add_meta_boxes', [$this, 'add_send_checkbox'], 10, 2 );
            // カスタム投稿の場合
            $custom_post_slags = array_keys(get_post_types(array( 'line_auto_post' => true )));
            foreach ($custom_post_slags as $custom_post_slag) {
                // カスタム投稿(公開)した時のコールバック関数を定義
                add_action('publish_'.$custom_post_slag, [$this, 'send_to_line'], 1, 6);
            }
        }
        // 管理画面を表示中、且つ、ログイン済、且つ、特権管理者or管理者or編集者or投稿者の場合
        if (is_admin() && is_user_logged_in() && (is_super_admin() || current_user_can('administrator') || current_user_can('editor') || current_user_can('author'))) {
            // 管理画面のトップメニューページを追加
            add_action('admin_menu', [$this, 'set_plugin_menu']);
            // 管理画面各ページの最初、ページがレンダリングされる前に実行するアクションに、
            // 初期設定を保存する関数をフック
            add_action('admin_init', [$this, 'save_settings']);
        }
    }

    function add_send_checkbox() {
        add_meta_box(
            // チェックボックスのID
            self::PARAMETER__SEND_CHECKBOX,
            // チェックボックスのラベル名
            'LINEメッセージ送信',
            // チェックボックスを表示するコールバック関数
            [$this, 'show_send_checkbox'],
            // 投稿画面に表示
            'post',
            // 投稿画面の右サイドに表示
            'advanced',
            // 優先度(最優先)
            'high'
        );
        // カスタム投稿の場合
        $custom_post_slags = array_keys(get_post_types(array( 'line_auto_post' => true )));
        foreach ($custom_post_slags as $custom_post_slag) {
            add_meta_box(
                // チェックボックスのID
                self::PARAMETER__SEND_CHECKBOX,
                // チェックボックスのラベル名
                'LINEメッセージ送信',
                // チェックボックスを表示するコールバック関数
                [$this, 'show_send_checkbox'],
                // カスタム投稿画面に表示
                $custom_post_slag,
                // 投稿画面の右サイドに表示
                'advanced',
                // 優先度(最優先)
                'high'
            );
        }
    }

    /**
     * LINEにメッセージを送信するチェックボックスを表示
     */
    function show_send_checkbox() {
        // nonceフィールドを生成・取得
        $nonce_field = wp_nonce_field(
            self::CREDENTIAL_ACTION__POST,
            self::CREDENTIAL_NAME__POST,
            true,
            false
        );
        echo
            '<p>' .
            $nonce_field .
            '<input type="checkbox" name="' . self::PARAMETER__SEND_CHECKBOX . '" value="ON">' .
            'LINEにメッセージを送信する' .
            '</p>';
    }

    /**
     * LINEメッセージを送信
     */
    function send_to_line($post_ID, $post){
        // ログインしていない場合は無視
        if (!is_user_logged_in()) return;
        // 特権管理者、管理者、編集者、投稿者の何れでもない場合は無視
        if (!is_super_admin() && !current_user_can('administrator') && !current_user_can('editor') && !current_user_can('author')) return;
        // nonceで設定したcredentialをPOST受信していない場合は無視
        if (!isset($_POST[self::CREDENTIAL_NAME__POST]) || !$_POST[self::CREDENTIAL_NAME__POST]) return;
        // nonceで設定したcredentialのチェック結果に問題がある場合
        if (!check_admin_referer(self::CREDENTIAL_ACTION__POST, self::CREDENTIAL_NAME__POST)) return;
        // LINEメッセージ送信チェックボックスにチェックがない場合は無視
        if ($_POST[self::PARAMETER__SEND_CHECKBOX] != 'ON') return;
        // ChannelAccessTokenをOPTIONSテーブルから取得
        $channel_access_token = self::decrypt(get_option(self::OPTION_KEY__CHANNEL_ACCESS_TOKEN), self::ENCRYPT_PASSWORD);
        // ChannelAccessTokenが設定されている場合
        if (strlen($channel_access_token) > 0) {
    		// 投稿のタイトルを取得
    		$title = sanitize_text_field($post->post_title);
            // 投稿の本文を取得
            $body = preg_replace("/( |　|\n|\r)/", "", strip_tags(sanitize_text_field($post->post_content)));
            // 投稿の本文の先頭30文字取得
            $body30 = mb_substr($body, 0, 30);
            // 投稿の本文を削った場合は点々を付ける
            if ($body30 != $body) {
                $body30 .= "…";
            }
            // 投稿のURLを取得
    		$link = get_permalink($post_ID);
    		// 本文を作成
    		// $message = $title."ブログを更新しました。".$title.$link;
    		$message = $title . "\r\n" . $body30 . "\r\n" . $link;
    		// LINEに送信
            $contents = file_get_contents(
                'https://api.line.me/v2/bot/message/broadcast',
                false,
                stream_context_create(
                    array(
                        'http' => array(
                            'method' => 'POST',
                            'header' => array(
                                'Content-Type: application/json;',
                                'Authorization: Bearer '.$channel_access_token
                            ),
                            'content' => json_encode(
                                array(
                                    "messages" => array(
                                        array(
                                            "type" => "text",
                                            "text" => $message
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            );
            // 送信に成功した場合
            if ($contents !== false) {
                // LINE送信に成功した旨をTRANSIENTに5秒間保持
                set_transient(self::TRANSIENT_KEY__SUCCESS_SEND_TO_LINE, 'LINEに送信しました', self::TRANSIENT_TIME_LIMIT);
            }
            // 送信に失敗した場合
            else {
                // LINE送信に失敗した旨をTRANSIENTに5秒間保持
                set_transient(self::TRANSIENT_KEY__ERROR_SEND_TO_LINE, 'LINEへの送信に失敗しました。', self::TRANSIENT_TIME_LIMIT);
            }
        }
    }

    /**
     * 投稿(公開)した際にLINE送信に失敗した時のメッセージ表示
     */
    function error_send_to_line() {
        // LINE送信に失敗した旨のメッセージをTRANSIENTから取得
        if (false !== ($error_send_to_line = get_transient(self::TRANSIENT_KEY__ERROR_SEND_TO_LINE))) {
            echo self::getNotice($error_send_to_line, self::NOTICE_TYPE__ERROR);
        }
    }

    /**
     * 投稿(公開)した際にLINE送信に成功した時のメッセージ表示
     */
    function success_send_to_line() {
        // LINE送信に成功した旨のメッセージをTRANSIENTから取得
        if (false !== ($success_send_to_line = get_transient(self::TRANSIENT_KEY__SUCCESS_SEND_TO_LINE))) {
            echo self::getNotice($success_send_to_line, self::NOTICE_TYPE__SUCCESS);
        }
    }

    /**
     * 管理画面メニューの基本構造が配置された後に実行するアクションにフックする、
     * 管理画面のトップメニューページを追加する関数
     */
    function set_plugin_menu() {
        // トップメニュー「LineAutoPost」を追加
        add_menu_page(
            // ページタイトル：
            'LineAutoPost',
            // メニュータイトル：
            'Line Auto Post',
            // 権限：
            // manage_optionsは以下の管理画面設定へのアクセスを許可
            // ・設定 > 一般設定
            // ・設定 > 投稿設定
            // ・設定 > 表示設定
            // ・設定 > ディスカッション
            // ・設定 > パーマリンク設定
            'manage_options',
            // ページを開いたときのURL(slug)：
            self::SLUG__SETTINGS_FORM,
            // メニューに紐づく画面を描画するcallback関数：
            [$this, 'show_settings'],
            // アイコン：
            // WordPressが用意しているカートのアイコン
            // ・参考（https://developer.wordpress.org/resource/dashicons/#awards）
            'dashicons-format-status',
            // メニューが表示される位置：
            // 省略時はメニュー構造の最下部に表示される。
            // 大きい数値ほど下に表示される。
            // 2つのメニューが同じ位置を指定している場合は片方のみ表示され上書きされる可能性がある。
            // 衝突のリスクは整数値でなく小数値を使用することで回避することができる。
            // 例： 63の代わりに63.3（コード内ではクォートを使用。例えば '63.3'）
            // 初期値はメニュー構造の最下部。
            // ・2 - ダッシュボード
            // ・4 - （セパレータ）
            // ・5 - 投稿
            // ・10 - メディア
            // ・15 - リンク
            // ・20 - 固定ページ
            // ・25 - コメント
            // ・59 - （セパレータ）
            // ・60 - 外観（テーマ）
            // ・65 - プラグイン
            // ・70 - ユーザー
            // ・75 - ツール
            // ・80 - 設定
            // ・99 - （セパレータ）
            // 但しネットワーク管理者メニューでは値が以下の様に異なる。
            // ・2 - ダッシュボード
            // ・4 - （セパレータ）
            // ・5 - 参加サイト
            // ・10 - ユーザー
            // ・15 - テーマ
            // ・20 - プラグイン
            // ・25 - 設定
            // ・30 - 更新
            // ・99 - （セパレータ）
            99
        );
    }

    /**
     * 初期設定画面を表示
     */
    function show_settings() {
        // 初期設定の保存完了メッセージ
        if (false !== ($complete_message = get_transient(self::TRANSIENT_KEY__SAVE_SETTINGS))) {
            $complete_message = self::getNotice($complete_message, self::NOTICE_TYPE__SUCCESS);
        }
        // ChannelAccessTokenの不正メッセージ
        if (false !== ($invalid_channel_access_token = get_transient(self::TRANSIENT_KEY__INVALID_CHANNEL_ACCESS_TOKEN))) {
            $invalid_channel_access_token = self::getNotice($invalid_channel_access_token, self::NOTICE_TYPE__ERROR);
        }
        // ChannelAccessTokenのパラメータ名
        $param_channel_access_token = self::PARAMETER__CHANNEL_ACCESS_TOKEN;
        // ChannelAccessTokenをTRANSIENTから取得
        if (false === ($channel_access_token = get_transient(self::TRANSIENT_KEY__TEMP_CHANNEL_ACCESS_TOKEN))) {
            // 無ければoptionsテーブルから取得
            $channel_access_token = self::decrypt(get_option(self::OPTION_KEY__CHANNEL_ACCESS_TOKEN), self::ENCRYPT_PASSWORD);
        }
        $channel_access_token = esc_html($channel_access_token);
        // nonceフィールドを生成・取得
        $nonce_field = wp_nonce_field(self::CREDENTIAL_ACTION__SETTINGS_FORM, self::CREDENTIAL_NAME__SETTINGS_FORM, true, false);
        // 送信ボタンを生成・取得
        $submit_button = get_submit_button('保存');
        // HTMLを出力
        echo <<< EOM
            <div class="wrap">
            <h2>初期設定</h2>
            {$complete_message}
            {$invalid_channel_access_token}
            <form action="" method='post' id="line-auto-post-settings-form">
                {$nonce_field}
                <p>
                    <label for="{$param_channel_access_token}">Channel Access Token：</label>
                    <input type="text" name="{$param_channel_access_token}" value="{$channel_access_token}"/>
                </p>
                {$submit_button}
            </form>
            </div>
EOM;
    }

    /**
     * 初期設定を保存するcallback関数
     */
    function save_settings() {
        // nonceで設定したcredentialをPOST受信した場合
        if (isset($_POST[self::CREDENTIAL_NAME__SETTINGS_FORM]) && $_POST[self::CREDENTIAL_NAME__SETTINGS_FORM]) {
            // nonceで設定したcredentialのチェック結果が問題ない場合
            if (check_admin_referer(self::CREDENTIAL_ACTION__SETTINGS_FORM, self::CREDENTIAL_NAME__SETTINGS_FORM)) {
                // ChannelAccessTokenをPOSTから取得
                $channel_access_token = trim(sanitize_text_field($_POST[self::PARAMETER__CHANNEL_ACCESS_TOKEN]));
                $valid = true;
                // ChannelAccessTokenが正しくない場合
                if (!preg_match(self::REGEXP_CHANNEL_ACCESS_TOKEN, $channel_access_token)) {
                    // ChannelAccessTokenの設定し直しを促すメッセージをTRANSIENTに5秒間保持
                    set_transient(self::TRANSIENT_KEY__INVALID_CHANNEL_ACCESS_TOKEN, "Channel Access Token が正しくありません。", self::TRANSIENT_TIME_LIMIT);
                    // 有効フラグをFalse
                    $valid = false;
                }
                // 有効フラグがTrueの場合(ChannelAccessTokenが入力されている場合)
                if ($valid) {
                    // 保存処理
                    // ChannelAccessTokenをoptionsテーブルに保存
                    update_option(self::OPTION_KEY__CHANNEL_ACCESS_TOKEN, self::encrypt($channel_access_token, self::ENCRYPT_PASSWORD));
                    // 保存が完了したら、完了メッセージをTRANSIENTに5秒間保持
                    set_transient(self::TRANSIENT_KEY__SAVE_SETTINGS, "初期設定の保存が完了しました。", self::TRANSIENT_TIME_LIMIT);
                    // (一応)ChannelAccessTokenの不正メッセージをTRANSIENTから削除
                    delete_transient(self::TRANSIENT_KEY__INVALID_CHANNEL_ACCESS_TOKEN);
                    // (一応)ユーザが入力したChannelAccessTokenをTRANSIENTから削除
                    delete_transient(self::TRANSIENT_KEY__TEMP_CHANNEL_ACCESS_TOKEN);
                }
                // 有効フラグがFalseの場合(ChannelAccessTokenが入力されていない場合)
                else {
                    // ユーザが入力したChannelAccessTokenをTRANSIENTに5秒間保持
                    set_transient(self::TRANSIENT_KEY__TEMP_CHANNEL_ACCESS_TOKEN, $channel_access_token, self::TRANSIENT_TIME_LIMIT);
                    // (一応)初期設定の保存完了メッセージを削除
                    delete_transient(self::TRANSIENT_KEY__SAVE_SETTINGS);
                }
                // 設定画面にリダイレクト
                wp_safe_redirect(menu_page_url(self::SLUG__SETTINGS_FORM), 303);
            }
        }
    }

} // end of class


?>
