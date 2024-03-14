<?php

if (!defined('WPINC')) {
    define( 'WPINC', 'wp-includes' );
}

if (!defined('CODOC_URL')) {
    define( 'CODOC_URL', 'https://codoc.jp' );
}
global $wp_version;
if (version_compare( $wp_version,'5.9') >= 0 ) {
    require_once( ABSPATH . WPINC . '/class-wp-http.php');
} else {
    require_once( ABSPATH . WPINC . '/class-http.php');
}

require_once( ABSPATH . WPINC . '/class-wp-error.php');

final class CodocUtil {
    public $codoc_url = CODOC_URL;
    public function __construct($params = ['usercode' => null, 'token' => null,'codoc_url' => null]) {
        $this->setAuthInfo(['usercode' => $params['usercode'], 'token' => $params['token']]);
        if (isset($params['codoc_url'])) {
            $this->codoc_url = $params['codoc_url'];
        }
        return $this;
    }
    
    public function callAPI($method,$path,$body = [],$headers = []) {
        $usercode = $this->usercode;
        $token    = $this->token;

        $headers['X-CodocToken'] = $token;
        $host = $this->codoc_url;
        $sslverify = true;

        if (preg_match('/local/',$this->codoc_url)) {
            $sslverify = false;
            $host = 'https://host.docker.internal';
        }
        $url = sprintf("%s/api/v1/cms/%s%s",$host,$usercode,$path);
        $http = new WP_Http();
        try {
            $response = $http->request(
                $url,
                [
                    'sslverify' => $sslverify,
                    'method'  => $method,
                    'timeout' => 10,
                    'headers' => $headers,
                    'body'    => $body,
                ]
            );
            if ( is_wp_error($response) || $response['response']['code'] != 200 ) {
                //何もしない
                //var_dump( $response );
            }
        } catch(Exception $e) {

        }
        if (!is_wp_error($response) and
            isset($response['body']) and
            is_string($response['body']) and
            is_array(json_decode($response['body'], true)) and
            (json_last_error() == JSON_ERROR_NONE)) {
            return json_decode($response['body']);
        } else {
            return null;
        }
    }
    public function callPaywallAPI($method,$path,$body = [],$headers = []) {
        $site_usercode = get_option(CODOC_USERCODE_OPTION_NAME);
        $paywall_token = $this->get_paywall_token_code();

        $host = $this->codoc_url;
        $sslverify = true;

        if (preg_match('/local/',$this->codoc_url)) {
            $sslverify = false;
            $host = 'https://host.docker.internal';
        }
        $url = sprintf("%s/api/v1/paywall/%s%s",$host,$site_usercode,$path);
        $http = new WP_Http();
        try {
            $response = $http->request(
                $url,
                [
                    'sslverify' => $sslverify,
                    'method'  => $method,
                    'timeout' => 10,
                    'headers' => $headers,
                    'body'    => $body,
                ]
            );
            if ( is_wp_error($response) || $response['response']['code'] != 200 ) {
                //何もしない
                //var_dump( $response );
            }
        } catch(Exception $e) {

        }
        if (!is_wp_error($response) and
            isset($response['body']) and
            is_string($response['body']) and
            is_array(json_decode($response['body'], true)) and
            (json_last_error() == JSON_ERROR_NONE)) {
            return json_decode($response['body']);
        } else {
            return null;
        }
    }
    public function setAuthInfo($params = [ "usercode" => null, "token" => null ]) {
        if ($params["usercode"]) {
            $this->usercode = $params["usercode"];
        }
        if ($params["token"]) {
            $this->token = $params["token"];
        }
        return true;
    }
    public function get_token($params = ["fetch_token_key" => null],$auth_info = ["usercode" => null, "token" => null]) {
        $this->setAuthInfo($auth_info);
        return $this->callAPI('GET','/token',[ "fetch_token_key" => $params["fetch_token_key"] ]);
    }
    public function get_user_info($params = [] ,$auth_info = ["usercode" => null, "token" => null]) {
        $this->setAuthInfo($auth_info);
        return $this->callAPI('GET','');
    }
    public function get_support_entry($params = [] ,$auth_info = ["usercode" => null, "token" => null]) {
        $this->setAuthInfo($auth_info);
        return $this->callAPI('GET','/support_entry');
    }
    public function sync_entry($params = [
        "post_title"       => null,
        "post_content"     => null,
        "post_status"      => null, # 0, 1
        "post_permalink"   => null,
        "codoc_entry_code" => null,
    ],$auth_info = ["usercode" => null, "token" => null]) {

        $this->setAuthInfo($auth_info);
        
        $post_content = $params['post_content'];
        preg_match('/wp:codoc\/codoc-block +?({.*})/',$post_content,$matches);
        // GUTENBERG で指定されたjson(記事情報)がない場合
        if (!$matches) {
            return;
        }
        // GUTENBERG で保存している内容を取得
        $codoc_info = json_decode($matches[1],true);
        // codoc タグがない場合はなにもしない
        //preg_match('/(<span +data-id="codoc-tag"[^>]+>(?:.+|)<\/span>)/',$post_content,$matches);
        preg_match('/(<(?:span|div)[^>]+data-id="codoc-tag"(?:[^>]+|)>(?:.+|)<\/(?:span|div)>)/',$post_content,$matches);       
        if (!$matches) {
            return;
        }
        // TODO: split or html parse? HTMLがつながってる場合はおかしくなる
        // classic(tinymce)版の場合p、もしくはdivにかこまれている
        $end_tag_regex = '/<(?:div|p)>::CODOC_WP_END_PAYWALL::<\/(?:div|p)>/';
        $before_splited  = preg_split($end_tag_regex,$post_content);
        $post_content = $before_splited[0];
        $splited  = preg_split('/(?:<(?:div|p)(?:[^>]+|)>|)<\!-- +wp:codoc\/codoc-block .*<\!-- +\/wp:codoc\/codoc-block +-->(?:<\/(?:div|p)>|)/s',$post_content);
        $status = $params['post_status']; # 0: 非公開 1: 公開 2:限定公開(パスワードの場合は限定公開で同期する)
        // codoc設定で限定公開を有効で、公開の場合は 限定公開にする
        if ($status == 1 and isset($codoc_info['statusLimited']) and $codoc_info['statusLimited']) {
            $status = 2;
        }
        # $body_free = '';
        # $body_paywalled = '';
        # if (count($splited) >= 2) {
        #   
        # }
        $binded_url = $params['post_permalink'];
        $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
        if (isset($CODOC_SETTINGS['str_replace_binded_url_from']) and
            isset($CODOC_SETTINGS['str_replace_binded_url_to']) and
            $CODOC_SETTINGS['str_replace_binded_url_from']) {
            $binded_url = str_replace(sanitize_text_field($CODOC_SETTINGS['str_replace_binded_url_from']),
                                      sanitize_text_field($CODOC_SETTINGS['str_replace_binded_url_to']),
                                      $binded_url);
        }
        $api_params = [
            'title'          => $params['post_title'],
            'body_free'      => $splited[0],
            'body_paywalled' => $splited[1],
            'status'         => $status,
            'binded_url'     => $binded_url,
            'show_price'     => isset($codoc_info['showPrice']) ? ($codoc_info['showPrice'] ? 1 : 0) : 0,
            'price'          => isset($codoc_info['price']) ? $codoc_info['price'] : 100,
            'limited'        => isset($codoc_info['limited']) ? ($codoc_info['limited'] ? 1 : 0) : 0,
            'limited_count'  => isset($codoc_info['limitedCount']) ? $codoc_info['limitedCount'] : 1,
            'affiliate_mode' => isset($codoc_info['affiliateMode']) ? ($codoc_info['affiliateMode'] ? 1 : 0) : 0,
            'affiliate_rate' => isset($codoc_info['affiliateRate']) ? $codoc_info['affiliateRate'] : '0.0500',
            'show_support'   => isset($codoc_info['showSupport']) ? ($codoc_info['showSupport'] ? 1 : 0) : 0,
            'show_paywalled_support'   => isset($codoc_info['showPaywalledSupport']) ? ($codoc_info['showPaywalledSupport'] ? 1 : 0) : 0,
            'subscriptions'  => isset($codoc_info['subscriptions']) ? array_keys($codoc_info['subscriptions']) : [],
        ];
        $entryCode = $params['codoc_entry_code'];
        $res = null;
        if ($entryCode) {
            $res = $this->callAPI('PUT','/entries/' . $entryCode ,$api_params);
        } else {
            $res = $this->callAPI('POST', '/entries', $api_params);
        }
        return $res;
    }
    
    function post_thumbnail( $params = [
        "file_path"        => null,
        "boundary"         => null,
        "codoc_entry_code" => null,
    ], $auth_info = ["usercode" => null, "token" => null] ) {

        $this->setAuthInfo($auth_info);

        $file_path  = $params['file_path'];
        $boundary   = $params['boundary']; # ランダム変数24桁 wp_generate_password(24);
        $res = null;
        if (is_readable($file_path)) {
            $name = 'file';

            $payload = '';
                $payload .= '--' . $boundary;
                $payload .= "\r\n";
                $payload .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . basename( $file_path ) . '"' . "\r\n";
                $payload .= "Content-Type: application/octet-stream\r\n";
                $payload .= "Content-Transfer-Encoding: binary\r\n";
                $payload .= "\r\n";
                $payload .= file_get_contents( $file_path );
                $payload .= "\r\n";

                $payload .= '--' . $boundary . '--';

                $entryCode = $params['codoc_entry_code'];
                $res = $this->callAPI(
                    'POST',
                    '/entries/' . $entryCode . '/thumbnail',
                    $payload,
                    ['content-type' => 'multipart/form-data; boundary=' . $boundary]
                );
        }
        return $res;
    }
    
    function reset_thumbnail( $params = [
        "codoc_entry_code" => null,
    ],$auth_info = ["usercode" => null, "token" => null] ) {

        $this->setAuthInfo($auth_info);

        $entryCode = $params['codoc_entry_code'];
        $res = $this->callAPI(
            'POST',
            '/entries/' . $entryCode . '/thumbnail',
            [ "reset" => 1 ]
        );
        return $res;
    }
    
    function filter_content( $params = [
        "post_content"     => null,
        "preview"          => null,
        "codoc_entry_code" => null,
        "codoc_settings"   => null,
        "is_amp_endpoint"  => null,
        "post_permalink"   => null,
        "codoc_support_entry_code" => null,
    ]) {
        $CODOC_SETTINGS = $params['codoc_settings'];

        $has_codoc_tag = '/(<(span|div)[^>]+data-id="codoc-tag"(?:[^>]+|)>(?:.+|)<\/(?:div|span)>)/';
        $post_content = $params['post_content'];
        // codoc タグがあるかどうか (202001:span -> div に変更)
        preg_match($has_codoc_tag,$post_content,$matches);

        $show_support_message = '';
        if (isset($CODOC_SETTINGS['show_support_message'])) {
            $show_support_message = $CODOC_SETTINGS['show_support_message'];
        }
        $show_support_categories = '';
        if (isset($CODOC_SETTINGS['show_support_categories'])) {
            $show_support_categories = $CODOC_SETTINGS['show_support_categories'];
        }
        $show_support_location  = 'bottom';
        if (isset($CODOC_SETTINGS['show_support_location'])) {
            $show_support_location = $CODOC_SETTINGS['show_support_location'];
        }
        // デフォルト値をうめておく
        if (!isset($CODOC_SETTINGS['support_button_text'])) {
            $CODOC_SETTINGS['support_button_text'] = 'サポートする';
        }
        if (!isset($CODOC_SETTINGS['show_like'])) {
            $CODOC_SETTINGS['show_like'] = 1;
        }
        if (!isset($CODOC_SETTINGS['show_about_codoc'])) {
            $CODOC_SETTINGS['show_about_codoc'] = 1;
        }
        if (!isset($CODOC_SETTINGS['show_powered_by'])) {
            $CODOC_SETTINGS['show_powered_by'] = 1;
        }
        if (!isset($CODOC_SETTINGS['show_created_by'])) {
            $CODOC_SETTINGS['show_created_by'] = 1;
        }
        if (!isset($CODOC_SETTINGS['show_copyright'])) {
            $CODOC_SETTINGS['show_copyright'] = 1;
        }
        $show_support_entry = (is_single() && preg_grep("/(?:$show_support_categories)/",array_map( function($c) { return $c->name; },get_the_category(get_post()->ID))));        
        if (!$matches && ($support_entry_code = $params['codoc_support_entry_code']) && $show_support_entry) {
            $support_tag = sprintf(
                '<div class="codoc-entries" data-without-body="1" data-support-message="%s" id="codoc-entry-%s" data-support-button-text="%s" data-show-like="%s" data-show-about-codoc="%s" data-show-powered-by="%s" data-show-created-by="%s" data-show-copyright="%s"></div>',
                htmlspecialchars($show_support_message,ENT_QUOTES),
                $support_entry_code,
                $CODOC_SETTINGS['support_button_text'],
                $CODOC_SETTINGS['show_like'],
                $CODOC_SETTINGS['show_about_codoc'],
                $CODOC_SETTINGS['show_powered_by'],
                $CODOC_SETTINGS['show_created_by'],
                $CODOC_SETTINGS['show_copyright']
            );
            $post_content = $show_support_location == 'bottom' ?
                          $post_content . $support_tag :
                          $support_tag  . $post_content ;
        }

        // data-wp-plugin-ver が無いタグは分割しない
        if (!$matches) {
            return $post_content;
        }
        
        // codocタグで分割 (202001: gutenbergで直前のdivタグを削除)
        $tag_regex = '/(?:<div(?:[^>]+|)>|)<(?:span|div)[^>]+data-id="codoc-tag"(?:[^>]+|)>(?:.+|)<\/(?:span|div)>(?:<\/div>|)/';
        $end_tag_regex = '/<(?:div|p)>::CODOC_WP_END_PAYWALL::<\/(?:div|p)>/';
        if ( $params['preview'] ) {
            $post_content = preg_replace($tag_regex,'<div class="codoc-continue">ここから上は無料で表示されます</div>',$post_content);
            $post_content = preg_replace($end_tag_regex,'<div class="codoc-continue">ここから下は無料で表示されます</div>',$post_content);
            return $post_content;
        }
        // codoc タグの前後で分ける
        $before_splited  = preg_split($end_tag_regex,$post_content);
        $splited  = preg_split($tag_regex,$before_splited[0]);
        // codoc タグにID属性のentrycodeとdata-without-body(無料分を非表示)をつける
        $entryCodeFormated = sprintf('"codoc-entry-%s" ',$params['codoc_entry_code']);

        // 文言系の設定をつける
        $tagAttributes = '';
        if (isset($CODOC_SETTINGS['show_like']) and $CODOC_SETTINGS['show_like'] != 1) {
            $tagAttributes = $tagAttributes . sprintf(' data-show-like="%s"',$CODOC_SETTINGS['show_like']);
        }
        if (isset($CODOC_SETTINGS['show_about_codoc']) and $CODOC_SETTINGS['show_about_codoc'] != 1) {
            $tagAttributes = $tagAttributes . sprintf(' data-show-about-codoc="%s"',$CODOC_SETTINGS['show_about_codoc']);
        }
        if (isset($CODOC_SETTINGS['show_powered_by']) and $CODOC_SETTINGS['show_powered_by'] != 1) {
            $tagAttributes = $tagAttributes . sprintf(' data-show-powered-by="%s"',$CODOC_SETTINGS['show_powered_by']);
        }
        if (isset($CODOC_SETTINGS['show_created_by']) and $CODOC_SETTINGS['show_created_by'] != 1) {
            $tagAttributes = $tagAttributes . sprintf(' data-show-created-by="%s"',$CODOC_SETTINGS['show_created_by']);
        }
        if (isset($CODOC_SETTINGS['show_copyright']) and $CODOC_SETTINGS['show_copyright'] != 1) {
            $tagAttributes = $tagAttributes . sprintf(' data-show-copyright="%s"',$CODOC_SETTINGS['show_copyright']);
        }
        if (isset($CODOC_SETTINGS['entry_button_text']) and $CODOC_SETTINGS['entry_button_text'] != '') {
            $tagAttributes = $tagAttributes . sprintf(' data-entry-button-text="%s"',$CODOC_SETTINGS['entry_button_text']);
        }
        if (isset($CODOC_SETTINGS['subscription_button_text']) and $CODOC_SETTINGS['subscription_button_text'] != '') {
            $tagAttributes = $tagAttributes . sprintf(' data-subscription-button-text="%s"',$CODOC_SETTINGS['subscription_button_text']);
        }
        if (isset($CODOC_SETTINGS['support_button_text']) and $CODOC_SETTINGS['support_button_text'] != '') {
            $tagAttributes = $tagAttributes . sprintf(' data-support-button-text="%s"',$CODOC_SETTINGS['support_button_text']);
        }
        if (isset($CODOC_SETTINGS['subscription_message']) and $CODOC_SETTINGS['subscription_message'] != '') {
            $tagAttributes = $tagAttributes . sprintf(' data-subscription-message="%s"',$CODOC_SETTINGS['subscription_message']);
        }
        if (isset($CODOC_SETTINGS['support_message']) and $CODOC_SETTINGS['support_message'] != '') {
            $tagAttributes = $tagAttributes . sprintf(' data-support-message="%s"',$CODOC_SETTINGS['support_message']);
        }
        if (isset($CODOC_SETTINGS['codoc_tag_attributes']) and $CODOC_SETTINGS['codoc_tag_attributes'] != '') {
            $tagAttributes = $tagAttributes . sprintf(' %s',$CODOC_SETTINGS['codoc_tag_attributes']);
        }
        
        $codoc_tag = preg_replace('/<((?:span|div)[^>]+)data-id="[^"]+"((?:[^>]+|))>/','<${1} ${2} data-without-body="1" id=' . $entryCodeFormated  . $tagAttributes . ">", $matches[1]);
        # THE THOR の page-lp.php が "   " をけずるための対策
        # "div   class" -> "div class"
        $codoc_tag = preg_replace('/div +class/','div class',$codoc_tag);
        
        if ($params['is_amp_endpoint']) {
            $format = preg_replace_callback('/(<(?:span|div)[^>]+>)((?:[^<>]+|))(<\/(?:span|div)>)/',function($matches) {
                $format = $matches[1] . '<a href="%%s">%s</a>' . $matches[3];
                return sprintf($format,($matches[2] ? $matches[2] : '続きを読む'));
            },$codoc_tag);
            $codoc_tag = sprintf($format,$params["post_permalink"]);
        }
            
        # タグの前後にHTMLを挿入
        if (!isset($CODOC_SETTINGS['str_before_codoc_tag'])) {
            $CODOC_SETTINGS['str_before_codoc_tag']  = '';
        }
        if (!isset($CODOC_SETTINGS['str_after_codoc_tag'])) {
            $CODOC_SETTINGS['str_after_codoc_tag']  = '';
        }
        // 無料部分のみ表示
        $post_content = $splited[0] . sprintf(
            '%s<div class="wp-block-codoc-codoc-block">%s</div>%s',
            $CODOC_SETTINGS['str_before_codoc_tag'],
            $codoc_tag,
            $CODOC_SETTINGS['str_after_codoc_tag']
        );
        // ::CODOC_WP_END_PAYWALL:: の後を足す
        if (isset($before_splited[1]) and $before_splited[1]) {
            $post_content .= $before_splited[1];
        }
        return $post_content;
    }
    /*

      // Cookieの中のトークンを取得
      $_CODOC->util->get_paywall_token_code();

     */
    function get_paywall_token_code() {
        if (isset($_COOKIE['codocTokenCode']) and $codocTokenCode = $_COOKIE['codocTokenCode']) {
            return $codocTokenCode;
        }
        return null;
    }
    /*

      $_CODOC->util->check_login($mode); // cookie / strict

     */
    function check_login($mode = 'cookie') {
        if ($codocTokenCode = $this->get_paywall_token_code()) {
            if ($mode == 'cookie') {
                // cookieの中にtokenがあればOK
                return true;
            }
            if ($mode == 'strict') {
                // ログイン状態を問い合わせ
                $res =  $this->callPaywallAPI('GET','/users',["paywall_token_code" => $codocTokenCode ]);
                if ($res->status) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }
    /*

      $_CODOC->util->check_owned($entry_code);

     */
    function check_owned($entry_code = null) {
        if (!$entry_code) {
            return false;
        }
        $res = $this->callPaywallAPI('GET','/entries/' . $entry_code, ["paywall_token_code" => $this->get_paywall_token_code(), "check_owned" => 1 ]);
        if ($res->status) {
            return true;
        }
        return false;
    }

    /*
      $_CODOC->util->health_check();
    */
    function health_check() {
        $sslverify = true;
        $host = $this->codoc_url;
        if (preg_match('/local/',$this->codoc_url)) {
            $sslverify = false;
            $host = 'https://host.docker.internal';
        }
        $url = sprintf("%s/codoc_health_check",$host);
        $http = new WP_Http();
        try {
            $response = $http->request(
                $url,
                [
                    'sslverify' => $sslverify,
                    'method'  => 'GET',
                    'timeout' => 10,
                    'headers' => [],
                    'body'    => [],
                ]
            );
        } catch(Exception $e) {

        }
        if ($response and $response['response']['code'] == '200') {
            return true;
        } else {
            return null;
        }
    }
}

