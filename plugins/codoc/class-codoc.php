<?php
require_once(plugin_dir_path( __FILE__ ) .'class-codoc-util.php');
final class Codoc {
    public function __construct() {
        add_action('plugins_loaded', [$this,'codoc_load_textdomain']);
        if (is_admin()) {
            // setting
            $this->add_settings();
        }

        // usercode, token はadminページのみDBから取得する
        $this->util = new CodocUtil([ 'usercode' => null, 'token' => null, 'codoc_url' => $this->get_codoc_url() ]);
        
        # the_content フィルタを実行しない状態
        $this->do_not_filter_the_content = false;

        // paywall用の本文非表示化とcodocタグへの属性追加
        #$priority = 999999999;
        $priority = 100000; # フィルターは最後に実施する default 10
        $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
        if (isset($CODOC_SETTINGS["debug_params"])) {
            $params_decoded = json_decode($CODOC_SETTINGS["debug_params"],true);
            if (isset($params_decoded["the_content_filter_priority"])) {
                $priority = $params_decoded["the_content_filter_priority"];
            }
        }
        // ショーココードの退避
        if (isset($CODOC_SETTINGS['shortcode_evacuation']) and $CODOC_SETTINGS['shortcode_evacuation']) {
            add_filter('the_content',[$this,'the_content_shortcode_evacuations'],0);
        }
        add_filter('the_content',[$this,'the_content'],$priority);
        // 2021-09-24 the_content を利用しない場合は個別に呼び出ししてもらう
        add_filter('codoc_the_content',[$this,'the_content'],$priority);
        
        // 2020-07-25 excerpt対応
        add_filter('excerpt_allowed_blocks',[$this,'excerpt_allowed_blocks']);
        
        $auth_info = get_option(CODOC_AUTHINFO_OPTION_NAME);

        if ($auth_info) {
            // データ同期 (save_postはis_adminで実行されないケースがある)
            add_action( 'save_post',        [$this,'save_post'], 20, 3 );
            add_action( 'added_post_meta',  [$this,'updated_post_meta'], 10, 3 );
            add_action( 'updated_post_meta',[$this,'updated_post_meta'], 10, 3 );
            add_action( 'deleted_post_meta',[$this,'deleted_post_meta'], 10, 4 );
        }
        global $pagenow;
        //管理画面でcodoc認証があり投稿画面の場合
        if (is_admin() and $auth_info and preg_match('/^post/',$pagenow)) {
            // Gutenberg のサポートがない場合は何もしない
            if ( function_exists( 'register_block_type' ) )  {
                // WPに登録ブロックとして認識させる（cgbは登録しないっぽい)
                \WP_Block_Type_Registry::get_instance()->register('codoc/codoc-block');
                // gutenberg
                require_once 'src/init.php';
                add_action('wp_loaded', function() {
                    $auth_info = get_option(CODOC_AUTHINFO_OPTION_NAME);
                    wp_localize_script('codoc-block-js', 'OPTIONS', array(
                        'codoc_url'      => $this->get_codoc_url(),
                        'codoc_usercode' => get_option(CODOC_USERCODE_OPTION_NAME),
                        'codoc_plugin_version' => CODOC_PLUGIN_VERSION,
                        'codoc_sdk_path'       => CODOC_SDK_PATH,
                        'codoc_account_is_pro' => isset($auth_info['account_is_pro']) ? $auth_info['account_is_pro'] : 0,
                    ));
                    wp_set_script_translations('codoc-block-js', 'codoc',plugin_dir_path( __FILE__ ) . 'languages');
                });
            }
            // tinymce
            $this->add_mce();
        } elseif(is_admin() and $auth_info and preg_match('/^edit/',$pagenow)) {
            // 記事編集ページ
        } elseif(!is_admin()) { // ブログ画面
            // codocのJS登録
            add_action( 'wp_enqueue_scripts', function() {
                $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
                $load_script_condition = '';
                if (isset($CODOC_SETTINGS["debug_params"]) and
                    $params_decoded = json_decode($CODOC_SETTINGS["debug_params"],true) and
                    isset($params_decoded["load_script_condition"])
                ) {
                    $load_script_condition = $params_decoded["load_script_condition"];
                }
                if ($load_script_condition == 'not_list_page') {
                    // not_list_pageの場合はトップページ等でスクリプトをロードしない
                    if (!(is_archive() or is_category() or is_home() or is_front_page())) {
                        wp_enqueue_script( 'codoc-injector-js',      $this->get_codoc_url() . '/js/cms.js' );
                    }
                } else {
                    wp_enqueue_script( 'codoc-injector-js',      $this->get_codoc_url() . '/js/cms.js' );
                }
            });
            //登録したscriptタグに属性をつける
            add_filter('script_loader_tag', [$this,'modifier_script_tag'],10,2);
            // tinymce用のショートコード
            add_shortcode('codoc', [$this, 'injector_shortcode']);
            // テーマをbodyにも反映
            add_filter('body_class', function($classes) {
                $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
                // デフォルトを変更
                $css_path = 'rainbow-square';
                if (isset($CODOC_SETTINGS["css_path"]) and $CODOC_SETTINGS["css_path"]) {
                    $css_path = $CODOC_SETTINGS["css_path"];
                }
                array_push($classes,sprintf( "codoc-theme-%s",$css_path));
                return $classes;
            });
            
        }
        return $this;
    }
    function codoc_load_textdomain() {
        load_plugin_textdomain('codoc', false, dirname(plugin_basename( __FILE__ )) . '/languages/');
    }
    public function get_codoc_url() {
        $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
        if (isset($CODOC_SETTINGS["debug_params"])) {
            $params_decoded = json_decode($CODOC_SETTINGS["debug_params"],true);
            if (isset($params_decoded["codoc_url"])) {
                return $params_decoded["codoc_url"];
            }
        }
        return CODOC_URL;
    }
    public function modifier_script_tag($tag,$handle) {
        if($handle !== 'codoc-injector-js') {
            return $tag;
        }
        $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
        $data_css = '';
        if ($css_path = $CODOC_SETTINGS['css_path']) {
            $data_css = sprintf(' data-css="%s" ',$css_path);
        }
        // connect用パラメータ
        $connect_attributes = '';
        if (isset($CODOC_SETTINGS["codoc_connect_code"])) {
            $connect_code = $CODOC_SETTINGS["codoc_connect_code"];
            $connect_attributes = sprintf(' data-connect-code="%s"',$connect_code);
            $tag = preg_replace('/cms\.js/','cms-connect.js',$tag);
        }
        if (isset($CODOC_SETTINGS["codoc_connect_registration_mode"]) and $CODOC_SETTINGS["codoc_connect_registration_mode"]) {
            $connect_attributes = $connect_attributes  .
                                  sprintf(' data-connect-registration-mode="%s"',$CODOC_SETTINGS["codoc_connect_registration_mode"]);
        }

        $usercode_attributes = '';
        $user_code = get_option(CODOC_USERCODE_OPTION_NAME);
        if ($user_code) {
            $usercode_attributes = sprintf(' data-usercode="%s"',$user_code);
        }

        $setting_attributes = '';
        if (isset($CODOC_SETTINGS['codoc_script_tag_attributes']) and $CODOC_SETTINGS['codoc_script_tag_attributes']) {
            $setting_attributes = $CODOC_SETTINGS['codoc_script_tag_attributes'];
        }
        //return str_replace(' src=', $data_css . ' defer src=', $tag);
        return preg_replace('/(src=[^>]+)/',' ${1} ' . $data_css . $connect_attributes . $usercode_attributes . $setting_attributes . ' defer',$tag);
    }
    public function injector_shortcode($atts) {
        global $post;
        ob_start();
        require 'views/codoc-injector.php';
        return ob_get_clean();
    }
    # settings / 設定関連
    public function add_settings() {
        global $CODOC_SETTINGS;
        add_action( 'admin_menu' ,function(){
            add_options_page(
                'codoc の設定',        //ページタイトル
                'codoc',           //設定メニューに表示されるメニュータイトル
                'edit_users',      //権限
                'codoc',           //設定ページのURL。options-general.php?page=codoc
                function() {
                    echo '<div class="codoc-settings">';
                    echo '<form method="post" action="options.php">';
                    settings_fields( 'codoc_option_group' );
                    do_settings_sections( 'codoc' );
                    submit_button(); // 送信ボタン
                    echo '</form></div>';
                }
            );
        });

        add_action('admin_print_styles', 'codoc_admin_styles');
        function codoc_admin_styles($hook) {
          wp_enqueue_style('codoc-options-style', plugins_url( 'codoc/css/codoc-options.css', __DIR__ ));
        }
        add_action( "admin_init", function() {
            global $CODOC_USERCODE;
            global $CODOC_SETTINGS;
            global $CODOC_TOKEN;
            global $CODOC_AUTHINFO;
            // codocからの認証データ処理
            if (isset($_GET['page']) and $_GET['page'] == 'codoc' and isset($_GET['fetch_token_key'])) {
                $key = sanitize_text_field($_GET['fetch_token_key']);
                $usercode = sanitize_text_field($_GET['usercode']);
                update_option(CODOC_USERCODE_OPTION_NAME,$usercode);
                //$data = $this->callAPI('GET','/token',[ "fetch_token_key" => $key ]);
                $data = $this->util->get_token([ "fetch_token_key" => $key ],["usercode" => $usercode, "token" => "1"]);
                if ($data->status and $token = $data->token) {
                    update_option(CODOC_TOKEN_OPTION_NAME,$token);
                    //$data = $this->callAPI('GET','');
                    $data = $this->util->get_user_info([],["usercode" => $usercode, "token" => $token]);
                    if ($data->status and $user = $data->user) {
                        $this->update_codoc_authinfo($user);
                    }
                    #$current_url  = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    #$current_url  = preg_replace('/(.*)fetch_token_key.*/','${1}&codoc_auth_finished=1',$current_url);
                    //add_settings_error( 'general', 'settings_updated', __( 'OK' ), 'success' );
                    $current_url  = admin_url('options-general.php') . '?page=codoc&codoc_auth_finished=1';

                    wp_redirect( $current_url);
                    exit;
                }
            }
            $CODOC_USERCODE = get_option(CODOC_USERCODE_OPTION_NAME);
            $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
            $CODOC_TOKEN    = get_option(CODOC_TOKEN_OPTION_NAME);
            $CODOC_AUTHINFO = get_option(CODOC_AUTHINFO_OPTION_NAME);
            if( !$CODOC_SETTINGS ) {
                //デフォルト値
                $CODOC_SETTINGS = array(
                    'css_path'  => '',
                );
                update_option( CODOC_SETTINGS_OPTION_NAME, $CODOC_SETTINGS );
            }
            if (!isset($CODOC_SETTINGS['str_replace_binded_url_from'])) {
                $CODOC_SETTINGS['str_replace_binded_url_from'] = '';
            }
            if (!isset($CODOC_SETTINGS['str_replace_binded_url_to'])) {
                $CODOC_SETTINGS['str_replace_binded_url_to'] = '';
            }
            if (!isset($CODOC_SETTINGS['str_before_codoc_tag'])) {
                $CODOC_SETTINGS['str_before_codoc_tag'] = '';
            }
            if (!isset($CODOC_SETTINGS['str_after_codoc_tag'])) {
                $CODOC_SETTINGS['str_after_codoc_tag'] = '';
            }

            if (!isset($CODOC_SETTINGS['always_show_support'])) {
                $CODOC_SETTINGS['always_show_support'] = '0';
            }
            if (!isset($CODOC_SETTINGS['show_support_message'])) {
                $CODOC_SETTINGS['show_support_message'] = '';
            }
            if (!isset($CODOC_SETTINGS['show_support_categories'])) {
                $CODOC_SETTINGS['show_support_categories'] = '';
            }
            if (!isset($CODOC_SETTINGS['show_support_location'])) {
                $CODOC_SETTINGS['show_support_location'] = 'bottom';
            }
            if (!isset($CODOC_SETTINGS['do_not_filter_the_content'])) {
                $CODOC_SETTINGS['do_not_filter_the_content'] = '0';
            }
            if (!isset($CODOC_SETTINGS['shortcode_evacuation'])) {
                $CODOC_SETTINGS['shortcode_evacuation'] = '0';
            }

            if (!isset($CODOC_SETTINGS['entry_button_text'])) {
                $CODOC_SETTINGS['entry_button_text'] = '';
            }
            if (!isset($CODOC_SETTINGS['subscription_button_text'])) {
                $CODOC_SETTINGS['subscription_button_text'] = '';
            }
            if (!isset($CODOC_SETTINGS['support_button_text'])) {
                $CODOC_SETTINGS['support_button_text'] = '';
            }
            if (!isset($CODOC_SETTINGS['subscription_message'])) {
                $CODOC_SETTINGS['subscription_message'] = '';
            }
            if (!isset($CODOC_SETTINGS['support_message'])) {
                $CODOC_SETTINGS['support_message'] = '';
            }
            if (!isset($CODOC_SETTINGS['show_like'])) {
                $CODOC_SETTINGS['show_like'] = '1';
            }
            if (!isset($CODOC_SETTINGS['show_about_codoc'])) {
                $CODOC_SETTINGS['show_about_codoc'] = '1';
            }
            if (!isset($CODOC_SETTINGS['show_powered_by'])) {
                $CODOC_SETTINGS['show_powered_by'] = '1';
            }
            if (!isset($CODOC_SETTINGS['show_created_by'])) {
                $CODOC_SETTINGS['show_created_by'] = '1';
            }
            if (!isset($CODOC_SETTINGS['show_copyright'])) {
                $CODOC_SETTINGS['show_copyright'] = '1';
            }
            if (!isset($CODOC_SETTINGS['codoc_tag_attributes'])) {
                $CODOC_SETTINGS['codoc_tag_attributes'] = '';
            }
            if (!isset($CODOC_SETTINGS['codoc_script_tag_attributes'])) {
                $CODOC_SETTINGS['codoc_script_tag_attributes'] = '';
            }
            if (!isset($CODOC_SETTINGS['codoc_connect_code'])) {
                $CODOC_SETTINGS['codoc_connect_code'] = '';
            }
            if (!isset($CODOC_SETTINGS['codoc_connect_registration_mode'])) {
                $CODOC_SETTINGS['codoc_connect_registration_mode'] = '';
            }
            if (!isset($CODOC_SETTINGS['debug_params'])) {
                $CODOC_SETTINGS['debug_params'] = '';
            }
            add_settings_section(
                'setting_section_id',    // id
                __('codoc Settings','codoc'),  // title
                [$this,'show_tadv_notice'],
                'codoc'                  // page
            );

            // 認証がある場合
            if ($CODOC_AUTHINFO) {
                // クリエイター情報だけ更新 (POST時に同期をとる)
                if (isset($_GET['page']) and $_GET['page'] == 'codoc' and isset($_GET['settings-updated'])) {
                    $data = $this->util->get_user_info([],[
                        "usercode" => get_option(CODOC_USERCODE_OPTION_NAME),
                        "token"    => get_option(CODOC_TOKEN_OPTION_NAME),
                    ]);
                    if ($data->status and $user = $data->user) {
                        $this->update_codoc_authinfo($user);
                        // 一度get_optionしてるのでリロードする
                        $CODOC_AUTHINFO = get_option(CODOC_AUTHINFO_OPTION_NAME);
                    }
                }

                register_setting(
                    'codoc_option_group',      // option group
                    CODOC_SETTINGS_OPTION_NAME // option name(DB)
                );
                add_settings_field(
                    'css_path',                    // id
                    __('Theme','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('You can change the design of the paywall by specifying a theme.<br />You can also specify CSS directly by selecting "Path Specification"','codoc') . '</div>';
                        echo sprintf('<div class="inputGroup"><input type="text" name="%s[css_path]" value="%s" id="codoc_css_path" readonly>',CODOC_SETTINGS_OPTION_NAME,$CODOC_SETTINGS['css_path']);
                        echo '' .
                        '<script type="text/javascript">                                              ' .
                        '    function gen_css_path(select) {                                          ' .
                        '        if (select.value == "dark" || select.value == "dark-square") {       ' .
                        '            document.getElementById("darkmode-caution").style.display="block";' .
                        '        } else {                                                             ' .
                        '            if (document.getElementById("darkmode-caution"))  {              ' .
                        '                document.getElementById("darkmode-caution").style.display="none";' .
                        '            }                                                                ' .
                        '        }                                                                    ' .
                        '        if (select.value == "path") {                                        ' .
                        '            document.getElementById("codoc_css_path").readOnly=false;        ' .
                        '            if (!document.getElementById("codoc_css_path").value.match(/\//g)) {' .
                        '            document.getElementById("codoc_css_path").value="";              ' .
                        '            }                                                                ' .
                        '        } else {                                                             ' .
                        '            document.getElementById("codoc_css_path").readOnly=true;         ' .
                        '            document.getElementById("codoc_css_path").value=select.value;    ' .
                        '        }                                                                    ' .
                        '    }                                                                        ' .
                        '</script>                                                                ' ;

                        echo sprintf('<select name="theme_name" onChange="gen_css_path(this)" id="codoc_theme_select">');
                        foreach (["rainbow","blue","red","green","black","dark","rainbow-square","blue-square","red-square","green-square","black-square","dark-square"] as $theme) {
                            if ($theme == "rainbow") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "rainbow" ? "selected" : ""),__('Rainbow colors','codoc'));
                            }
                            if ($theme == "blue") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "blue" ? "selected" : ""),__('Blue','codoc'));
                            }
                            if ($theme == "red") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "red" ? "selected" : ""),__('Red','codoc'));
                            }
                            if ($theme == "green") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "green" ? "selected" : ""),__('Green','codoc'));
                            }
                            if ($theme == "black") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "black" ? "selected" : ""),__('Black','codoc'));
                            }
                            if ($theme == "dark") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "dark" ? "selected" : ""),__('Dark mode','codoc'));
                            }
                            if ($theme == "rainbow-square") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "rainbow-square" ? "selected" : ""),__("Rainbow Colors / Square design",'codoc'));
                            }
                            if ($theme == "blue-square") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "blue-square" ? "selected" : ""),__('Blue / Square design','codoc'));
                            }
                            if ($theme == "red-square") {
                                echo sprintf('<option value="%s" %s>%s/option>', $theme, ($CODOC_SETTINGS["css_path"] == "red-square" ? "selected" : ""),__('Red / Square design','codoc'));
                            }
                            if ($theme == "green-square") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "green-square" ? "selected" : ""),__('Green / Square design','codoc'));
                            }
                            if ($theme == "black-square") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "black-square" ? "selected" : ""),__('Black / Square design','codoc'));
                            }
                            if ($theme == "dark-square") {
                                echo sprintf('<option value="%s" %s>%s</option>', $theme, ($CODOC_SETTINGS["css_path"] == "dark-square" ? "selected" : ""),__('Dark mode / Square design','codoc'));
                            }
                        }
                        echo sprintf('<option value="path" %s>%s</option>', (preg_match("/\//",$CODOC_SETTINGS["css_path"]) ? "selected" : ""),__('Path Specification','codoc'));
                        echo '<script type="text/javascript">gen_css_path(document.getElementById(\'codoc_theme_select\'))</script>';
                        echo sprintf('</select></div>');
                        echo '<p id="darkmode-caution" style="display:none">' . __('Please note that the theme for dark mode has white text color, and depending on the background color, the text may not be visible.','codoc'). '</p>';
                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );
                add_settings_field(
                    'paywall_text',                  // id
                    __('Texts in paywall','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        global $CODOC_AUTHINFO;
                        echo '<div class="excerpt">' . __('You can change, show and hide texts in your paywall.','codoc') . '</div>';
                        echo '<table class="innerTable"><tbody>';
                        echo '<tr><th>' . __('Display of likes','codoc') . '</th><td>';
                        echo sprintf('<input type="radio" value="1" name="%s[show_like]" id="show_like_on" %s><label for="show_like_on">' . __('Enable','codoc') . '</label>　',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['show_like'] == '1' ? "checked" : ""));
                        echo sprintf('<input type="radio" value="0" name="%s[show_like]" id="show_like_off" %s><label for="show_like_off">' . __('Disable','codoc') . '</label>　<br / >',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['show_like'] == '0' ? "checked" : ""));
                        echo '</td></tr>';

                        echo '<tr><th>' . __('Display of PoweredBy','codoc') . '</th><td>';
                        echo sprintf('<input type="hidden" value="1" name="%s[show_about_codoc]">',CODOC_SETTINGS_OPTION_NAME);
                        echo sprintf('<input type="hidden" value="1" name="%s[show_created_by]">',CODOC_SETTINGS_OPTION_NAME);
                        echo sprintf('<input type="hidden" value="1" name="%s[show_powered_by]">',CODOC_SETTINGS_OPTION_NAME);
                        if (isset($CODOC_AUTHINFO['account_is_pro']) and $CODOC_AUTHINFO['account_is_pro']) {
                        echo sprintf('<input type="radio" value="1" name="%s[show_copyright]" id="show_copyright_on" %s><label for="show_copyright_on">' . __('Enable','codoc') . '</label>　',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['show_copyright'] == '1' ? "checked" : ""));
                        echo sprintf('<input type="radio" value="0" name="%s[show_copyright]" id="show_copyright_off" %s><label for="show_copyright_off">' .  __('Disable','codoc') . '</label>　<br / >',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['show_copyright'] == '0' ? "checked" : ""));
                        } else {
                            echo __('Only PRO accounts can be disabled.','codoc');
                        }
                        echo '</td></tr>';
                        
                        echo '<tr><th>' . __('"Purchase Article" button text','codoc') . '</th><td>';
                        echo sprintf('<input type="text" name="%s[entry_button_text]" value="%s">',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['entry_button_text']));
                        echo '</td></tr>';
                        
                        echo '<tr><th>' . __('"Purchase Subscription" button text.','codoc') . '</th><td>';
                        echo sprintf('<input type="text" name="%s[subscription_button_text]" value="%s">',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['subscription_button_text']));
                        echo '</td></tr>';

                        echo '<tr><th>' . __('Support button text','codoc') . '</th><td>';
                        echo sprintf('<input type="text" name="%s[support_button_text]" value="%s">',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['support_button_text']));
                        echo '</td></tr>';
                        
                        echo '<tr><th>' . __('Text for "Articles Included in Subscription".','codoc') . '</th><td>';
                        echo sprintf('<input type="text" name="%s[subscription_message]" value="%s">',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['subscription_message']));
                        echo '</td></tr>';
                        
                        echo '<tr><th>' . __('Text for support description','codoc') . '</th><td>';                        
                        echo sprintf('<input type="text" name="%s[support_message]" value="%s">',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['support_message']));
                        echo '</td></tr>';
                        
                        echo '</tbody></table>';
                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );
                
                add_settings_field(
                    'codoc_tag_attributes',                  // id
                    __('Attributes for codoc tag','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('You can add specific attributes to codoc tags.','codoc') . '</div>';
                        echo sprintf('<input type="text" name="%s[codoc_tag_attributes]" value="%s">',CODOC_SETTINGS_OPTION_NAME,htmlspecialchars($CODOC_SETTINGS['codoc_tag_attributes']));
                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );
                add_settings_field(
                    'codoc_script_tag_attributes',                  // id
                    __('Attributes for codoc script tag','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('You can add specific attributes to codoc script tags.','codoc') . '</div>';
                        echo sprintf('<input type="text" name="%s[codoc_script_tag_attributes]" value="%s">',CODOC_SETTINGS_OPTION_NAME,htmlspecialchars($CODOC_SETTINGS['codoc_script_tag_attributes']));
                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );
                add_settings_field(
                    'str_replace_binded_url',                  // id
                    __('Permalink','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('You can replace the part of permalink registered on the codoc.','codoc') . '</div>';
                        echo sprintf('<input type="text" name="%s[str_replace_binded_url_from]" value="%s">',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['str_replace_binded_url_from']));
                        echo ('<span class="suptext">→</span>');
                        echo sprintf('<input type="text" name="%s[str_replace_binded_url_to]" value="%s">',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['str_replace_binded_url_to']));

                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );

                add_settings_field(
                    'str_around_codoc_tag',                    // id
                    __('HTML insertion','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('You can insert HTML before and after the codoc tag.','codoc') . '</div>';
                        echo sprintf('<div class="inputRow"><p>%s</p><textarea name="%s[str_before_codoc_tag]" cols="40">%s</textarea></div>',__('Before HTML','codoc'),CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['str_before_codoc_tag']));
                        echo sprintf('<div class="inputRow"><p>%s</p><textarea name="%s[str_after_codoc_tag]" cols="40">%s</textarea></div>',__('After HTML','codoc'),CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['str_after_codoc_tag']));

                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );

                add_settings_field(
                    'always_show_support_tag',                 // id
                    __('Automatic support insertion','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('It adds support functions to the post without inserting a codoc block.','codoc') . '</div>';
                        echo '<table class="innerTable"><tbody>';
                        echo '<tr><th>' . __('Automatic insertion','codoc') . '</th><td>';
                        echo sprintf('<input type="radio" value="1" name="%s[always_show_support]" id="always_show_support_on" %s><label for="always_show_support_on">' . __('Enable','codoc') . '</label>　',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['always_show_support'] == '1' ? "checked" : ""));
                        echo sprintf('<input type="radio" value="0" name="%s[always_show_support]" id="always_show_support_off" %s><label for="always_show_support_off">' . __('Disable','codoc') . '</label>　<br / >',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['always_show_support'] == '0' ? "checked" : ""));
                        echo '</td></tr>';
                        echo '<tr><th>'. __('Position','codoc') . '</th><td>';
                        echo sprintf('<input type="radio" value="top" name="%s[show_support_location]" id="show_support_location_top" %s><label for="show_support_location_top">' . __('TOP','codoc') . '</label>　',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['show_support_location'] == 'top' ? "checked" : ""));
                        echo sprintf('<input type="radio" value="bottom" name="%s[show_support_location]" id="show_support_location_bottom" %s><label for="show_support_location_bottom">' . __('Bottom','codoc') . '</label>　<br / >',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['show_support_location'] == 'bottom' ? "checked" : ""));
                        echo '</td></tr>';
                        echo '<tr><th>' . __('Description','codoc') . '</th><td>';
                        echo sprintf('<input type="text" placeholder="' . __('You can customize the description of the support.','codoc') . '" size="50%%" name="%s[show_support_message]" value="%s">',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['show_support_message']));
                        echo '</td></tr>';
                        echo '<tr><th>' . __('Category names','codoc') . '</th><td>';
                        echo sprintf('<input placeholder="' . __('Inserted into articles of categories, divided by &quot;|&quot;','codoc') . '" type="text" size="50%%" name="%s[show_support_categories]" value="%s"><br />',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['show_support_categories']));
                        echo '</td></tr>';
                        echo '</tbody></table>';

                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );
                add_settings_field(
                    'do_not_filter_the_content',                   // id
                    __('Disable plugin filter','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('You can disable filter processing that includes shortcodes from other plugins that interfere with the operation of codoc. Please use this only if codoc is not functioning properly.','codoc') . '</div>';
                        echo sprintf('<input type="radio" value="1" name="%s[do_not_filter_the_content]" id="do_not_filter_the_content_on" %s><label for="do_not_filter_the_content_on">' . __('Enable','codoc') .'</label>　',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['do_not_filter_the_content'] == '1' ? "checked" : ""));
                        echo sprintf('<input type="radio" value="0" name="%s[do_not_filter_the_content]" id="do_not_filter_the_content_off" %s><label for="do_not_filter_the_content_off">' . __('Disable','codoc') . '</label>　',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['do_not_filter_the_content'] == '0' ? "checked" : ""));
                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );

                add_settings_field(
                    'shortcode_evacuation',                   // id
                    __('Shortcode evacuation','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('The execution results of shortcodes written in the paid part are moved to the free part, and are used and displayed on the HTML during viewing of the paid part. Please use this if the shortcode does not work as expected in the paid part. Please note that the execution results of shortcodes in the paid part will be written in the source code.','codoc'). '</div>';
                        echo sprintf('<input type="radio" value="1" name="%s[shortcode_evacuation]" id="shortcode_evacuation_on" %s><label for="shortcode_evacuation_on">' . __('Enable','codoc') . '</label>　',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['shortcode_evacuation'] == '1' ? "checked" : ""));
                        echo sprintf('<input type="radio" value="0" name="%s[shortcode_evacuation]" id="shortcode_evacuation_off" %s><label for="shortcode_evacuation_off">' . __('Disable','codoc') . '</label>　',CODOC_SETTINGS_OPTION_NAME,($CODOC_SETTINGS['shortcode_evacuation'] == '0' ? "checked" : ""));
                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );

                add_settings_field(
                    'debug_params',                   // id
                    __('Debug Parameters','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        echo '<div class="excerpt">' . __('You can specify parameters for debugging. Please use this only upon request from codoc support.','codoc') . '</div>';
                        echo sprintf('<input placeholder="" type="text" size="50%%" name="%s[debug_params]" value="%s"><br />',CODOC_SETTINGS_OPTION_NAME,esc_html($CODOC_SETTINGS['debug_params']));
                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );

                add_settings_field(
                    'cretor_info',                   // id
                    __('Creator\'s Information','codoc'),  // title
                    function() {
                        global $CODOC_SETTINGS;
                        global $CODOC_AUTHINFO;
                        echo sprintf('<p><font id="codoc-update-creator-info-message"></font></p>',"");
                        // 変更を保存時に常に同期するのでこの表示は必要ないがUI上保持しておく
                        $script = "document.getElementById('codoc-update-creator-info-message').innerText='" . __("Please save changes to update the information.","codoc") . "';document.getElementById('codoc-update-creator-info-message').color='red';";
                        if (isset($CODOC_AUTHINFO['profile_image_url'])) {
                            echo sprintf('<img src="%s" width="40" height="40" />',$CODOC_AUTHINFO['profile_image_url']);
                        }
                        echo sprintf('<p>%s%s</p>',$CODOC_AUTHINFO['name'],((isset($CODOC_AUTHINFO['account_is_pro']) and $CODOC_AUTHINFO['account_is_pro']) ? ' [PRO] ' : ''));
                        if ($connect_code = $CODOC_SETTINGS['codoc_connect_code']) {

                            echo sprintf('<p>' .  __('External service integration completed (Integration code: %s) %s','codoc') . '</p>',
                                         $connect_code,
                                         ($CODOC_SETTINGS['codoc_connect_registration_mode'] == 'dedicated' ? '<br/> <strong>' . __('Set the audience as a private account.','codoc') . '</strong>' : ''));
                        }
                        echo sprintf('<p><a href="javascript:void(0);" onClick="' . $script . '">' . __('Update creator\'s Information','codoc') . '</a></p>');
                        echo ('<p>' . __('Please update each time if you change the logo or cover image on the codoc side.','codoc') . '</p>');
                    },
                    'codoc',                   //page
                    'setting_section_id'       //Section
                );                

                register_setting(
                    'codoc_option_group',      // option group
                    CODOC_USERCODE_OPTION_NAME
                );
                register_setting(
                    'codoc_option_group',      // option group
                    CODOC_TOKEN_OPTION_NAME
                );

                if (isset($_GET['codoc_auth_finished']) and $_GET['codoc_auth_finished']) {
                    add_settings_error( 'general', 'settings_updated', __( 'codoc authentication has been completed.' ,'codoc'), 'success' );
                }

                add_settings_field(
                    'codoc_auth',
                    __('Authentication','codoc'),
                    function() {
                        global $CODOC_USERCODE;
                        global $CODOC_TOKEN;
                        global $CODOC_AUTHINFO;
                        $script = "javascript:document.getElementById('codoc-usercode').value='-';document.getElementById('codoc-token').value='-';document.getElementById('codoc-auth-message').innerText='" . __('Please save changes to complete the unbinding.','codoc') . "';document.getElementById('codoc-auth-message').color='red';";
                        
                        echo sprintf('<p><font color="green" id="codoc-auth-message">' . __('Authorized as <strong>%s</strong>','codoc') . '</font></p>',$CODOC_AUTHINFO['email']);

                        echo sprintf('<input id="codoc-usercode" type="hidden" name="%s" value="%s">',CODOC_USERCODE_OPTION_NAME,$CODOC_USERCODE);
                        echo sprintf('<input id="codoc-token" type="hidden" name="%s" value="%s">',CODOC_TOKEN_OPTION_NAME,$CODOC_TOKEN);
                        echo sprintf('<p><a href="javascript:void(0);" onClick="' . $script . '">' . __('Unbind authorization','codoc') . '</p>');
                    },
                    'codoc',
                    'setting_section_id'
                );

            }

            // ない場合
            if (!$CODOC_AUTHINFO and !isset($_GET['auth_by_myself'])) {
                add_settings_field(
                    'codoc_auth',
                    __('Authentication','codoc'),
                    function() {
                        $theme = wp_get_theme();
                        $from = 'wp';
                        if ($theme->get('Name') === 'codoc') {
                            $from = 'wp_codoc';
                        }
                        //$current_url  = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                        $current_url  = admin_url('options-general.php') . '?page=codoc';
                        $direct_url    = sprintf("location.href='%s&auth_by_myself=1'",$current_url);
                        $login_url    = sprintf("location.href='%s'",$this->get_codoc_url() . '/me/token?from=' . $from . '&return_url=' . urlencode($current_url));
                        $register_url = sprintf("location.href='%s'",$this->get_codoc_url() . '/register?from=' . $from . '&return_url=' . urlencode($current_url));
                        // submitを消しておく
                        echo ('<script type="text/javascript">window.onload=function(){document.getElementById(\'submit\').style.display = \'none\'}</script>');
                        echo (__('Authentication is required to use codoc on WordPress.','codoc') . '<br />');
                        echo (__('You can authenticate by directly entering the user code and API token, or by logging in and registering with codoc.','codoc') . '<br /><br />');
                        echo sprintf('<input type="button" class="button button-primary" value="%s" onClick="%s">　',__('Authenticate by direct input','codoc'),$direct_url);
                        echo sprintf('<input type="button" class="button button-primary" value="%s" onClick="%s">　<input type="button" class="button button-primary" value="%s" onClick="%s"><br /><br />',__('Login and authenticate','codoc'),$login_url,__('Register and authenticate','codoc'),$register_url);
                        if (!$this->util->health_check()) {
                            echo sprintf('<p style="color: red;">Cannot communicate with the codoc server. Please allow communication to https://codoc.jp in your firewall settings on the server or WordPress side.</p>');
                        }

                    },
                    'codoc',
                    'setting_section_id'
                );
            }
            // ない場合かつ自分で認証する場合
            if (!$CODOC_AUTHINFO and (
                (isset($_GET['auth_by_myself']) and $_GET['auth_by_myself']) or
                (isset($_POST['auth_by_myself']) and $_POST['auth_by_myself'])
            )) {
                register_setting(
                    'codoc_option_group',      // option group
                    CODOC_USERCODE_OPTION_NAME
                );
                register_setting(
                    'codoc_option_group',      // option group
                    CODOC_TOKEN_OPTION_NAME
                );
                add_settings_field(
                    'codoc_usercode',
                    'ユーザーコード',
                    function() {
                        global $CODOC_USERCODE;
                        echo '<input type="hidden" name="auth_by_myself" value="1">';
                        echo sprintf('<input type="text" name="%s" value="%s">',CODOC_USERCODE_OPTION_NAME,$CODOC_USERCODE);
                    },
                    'codoc',
                    'setting_section_id'
                );
                add_settings_field(
                    'codoc_token',
                    'APIトークン',
                    function() {
                        global $CODOC_TOKEN;
                        echo sprintf('<input type="text" name="%s" value="%s">',CODOC_TOKEN_OPTION_NAME,$CODOC_TOKEN);
                    },
                    'codoc',
                    'setting_section_id'
                );
            }

            //　認証情報を保存
            add_action( 'update_option_' . CODOC_USERCODE_OPTION_NAME, function( $old_value, $new_value ) {
                global $CODOC_RE_AUTHORIZE;
                $CODOC_RE_AUTHORIZE = 1;
            },9,2); // $hook, $function_to_add, $priority, $accepted_args
            add_action( 'update_option_' . CODOC_TOKEN_OPTION_NAME, function( $old_value, $new_value ) {
                global $CODOC_RE_AUTHORIZE;
                $CODOC_RE_AUTHORIZE = 1;
            },10,2);

            add_action('update_option_' . CODOC_SETTINGS_OPTION_NAME,function(){
                $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
                if (isset($CODOC_SETTINGS['always_show_support']) and $CODOC_SETTINGS['always_show_support']) {
                    $data = $this->util->get_support_entry([],[
                        "usercode" => get_option(CODOC_USERCODE_OPTION_NAME),
                        "token"    => get_option(CODOC_TOKEN_OPTION_NAME),
                    ]);
                    if ($data and $data->status and $entry = $data->entry) {
                        update_option(CODOC_SUPPORT_ENTRYCODE_OPTION_NAME,$entry->code);
                    } else {
                        update_option(CODOC_SUPPORT_ENTRYCODE_OPTION_NAME,'');
                    }
                } elseif(isset($CODOC_SETTINGS['always_show_support']) and !$CODOC_SETTINGS['always_show_support']) {
                    update_option(CODOC_SUPPORT_ENTRYCODE_OPTION_NAME,'');
                }
            });
            
            add_action('updated_option',function(){
                global $CODOC_RE_AUTHORIZE;
                if ($CODOC_RE_AUTHORIZE) {
                    //$data = $this->callAPI('GET','');
                    $data = $this->util->get_user_info([],[
                        "usercode" => get_option(CODOC_USERCODE_OPTION_NAME),
                        "token"    => get_option(CODOC_TOKEN_OPTION_NAME),
                    ]);
                    if ($data->status and $user = $data->user) {
                        $this->update_codoc_authinfo($user);
                    } else {
                        update_option(CODOC_AUTHINFO_OPTION_NAME,'');
                    }
                }
            });

        });
        // プラグイン一覧に設定のリンクをいれる
        add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __FILE__ ) . 'codoc' . '.php' ),
                    function( $links ) {
                        $setting_link = sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( 'page', 'codoc', admin_url( 'options-general.php' ) ) ), esc_html( '設定' ) );
                        array_unshift( $links, $setting_link );

                        return $links;
                    }
        );

    }
    public function add_mce() {
        add_action( 'admin_enqueue_scripts', function() {
            if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
                return;
            }
            $current_screen = get_current_screen();
            if ( ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) || ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) ) {
                // Gutenberg Editor
                // なにもしない
            } else {
                // tinymce
                if ( get_user_option( 'rich_editing' ) == 'true' ) {
                    // プラグイン内で使うCSRF を生成
                    $path = plugins_url( 'codoc/src_mce/codoc-editor-onload.js', __DIR__ );
                    wp_enqueue_script(  'codoc-editor-onload', $path, array('jquery'), '', true );
                    // I just want to insert this global variables but i don't know how i can do it..
                    $auth_info = get_option(CODOC_AUTHINFO_OPTION_NAME);
                    wp_localize_script(
                        'codoc-editor-onload',
                        'CODOCEDITOR',
                        array(
                            'action' => 'codoc_shortcodes',
                            'nonce'  => wp_create_nonce( 'codoc_shortcodes' ),

                            'codoc_url'      => $this->get_codoc_url(),
                            'codoc_usercode' => get_option(CODOC_USERCODE_OPTION_NAME),
                            'codoc_plugin_version' => CODOC_PLUGIN_VERSION,
                            'codoc_sdk_path'       => CODOC_SDK_PATH,
                            'codoc_account_is_pro' => isset($auth_info['account_is_pro']) ? $auth_info['account_is_pro'] : 0,
                        )
                    );

                    // ここでtinymceのプラグイン追加
                    //wp_enqueue_style( 'codoc-admin-style', plugins_url( 'codoc/src_mce/codoc-admin.css', __DIR__ ) );
                    //add_editor_style( plugins_url( 'codoc/src_mce/codoc-admin.css', __DIR__ ) );
                    wp_enqueue_style( 'codoc-admin-style', $this->get_codoc_url() . CODOC_SDK_PATH . '.tinymce.css?c=' . date('Ymd') );
                    add_editor_style( $this->get_codoc_url() .  CODOC_SDK_PATH . '.tinymce.css' );

                    add_filter( 'mce_external_plugins', function( $plugin_array ) {
                        //$plugin_array['codoc'] = plugins_url( 'codoc/src_mce/codoc-editor.js', __DIR__ );
                        $plugin_array['codoc'] = $this->get_codoc_url() .  CODOC_SDK_PATH . '.tinymce.js?c=' . date('Ymd');
                        return $plugin_array;
                    } );
                    add_filter( 'mce_buttons', function( $buttons ) {
                        array_push( $buttons, "|", "codoc" );
                        return $buttons;
                    } );
                    // 非SSL環境の場合、なぜかhttps -> httpsになってしまうので対策
                    // コメントタグが除去されることがあるらしいのでvalid_elementsに必要なタグを追加 (EXPERIMENTAL)
                    add_filter( 'tiny_mce_before_init', function($settings) {
                        $settings['external_plugins'] = preg_replace('/"codoc":"http:/','"codoc":"https:',$settings['external_plugins']);
                        foreach (['valid_elements','extended_valid_elements'] as $valid_elements) {
                            if (isset($settings[$valid_elements])) {
                                $settings[$valid_elements] = $settings[$valid_elements] . ',div[*],p[*],img[*],--[*]';
                            }
                        }
                        $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
                        if (isset($CODOC_SETTINGS["debug_params"])) {
                            $params_decoded = json_decode($CODOC_SETTINGS["debug_params"],true);
                            if (isset($params_decoded["mce_valid_elements"])) {
                                $settings['valid_elements'] = $params_decoded["mce_valid_elements"];
                                $settings['extended_valid_elements'] = $params_decoded["mce_valid_elements"];
                            }
                        }

                        return $settings;
                    },1000000);
                }
            }
        } );
    }
    function update_codoc_authinfo($user) {
        global $CODOC_SETTINGS;
        global $CODOC_AUTHINFO;
        if (property_exists($user,'connect_code') and  $user->connect_code) {
            $CODOC_SETTINGS = get_option(CODOC_SETTINGS_OPTION_NAME);
            $CODOC_SETTINGS['codoc_connect_code'] = $user->connect_code;
            $CODOC_SETTINGS['codoc_connect_registration_mode'] = $user->connect_has_permission_dedicated_account ? 'dedicated' : '';
            update_option(CODOC_SETTINGS_OPTION_NAME,$CODOC_SETTINGS);
        }
        return update_option(CODOC_AUTHINFO_OPTION_NAME,[
            'email' => $user->email,
            'name'  => $user->name,
            'profile_image_url' => $user->profile_image_url,
            'cover_image_url'    => $user->cover_image_url,
            'connect_code' => property_exists($user,'connect_code') ? $user->connect_code : '',
            'connect_image_url' => property_exists($user,'connect_image_url') ? $user->connect_image_url : '',
            'account_is_pro' => property_exists($user,'account_is_pro') ? $user->account_is_pro : '',
            'created_at' => property_exists($user,'created_at') ? $user->created_at : 0,
        ]);
        $CODOC_AUTHINFO = get_option(CODOC_AUTHINFO_OPTION_NAME);
        return $CODOC_AUTHINFO;
    }
    // 保存用コンテンツにフィルターを実施する
    public function get_filtered_content($post_emulated) {
        // 記事ページであることをエミュレートしてフィルター実行
        $post_backuped = null;
        if (isset($GLOBALS['post'])) {
            $post_backuped = $GLOBALS['post'];
        }
        $GLOBALS['post'] = $post_emulated;

        $wp_query_backuped = null;
        if (isset($GLOBALS['wp_query'])) {
            $wp_query_backuped = $GLOBALS['wp_query'];
            $wp_query = $GLOBALS['wp_query'];
            $wp_query->is_single = true;
            $wp_query->is_singular = true;
            # in_the_loop は記事ページでは通常有効っぽいので true指定 ex: wp_ulike
            $wp_query->in_the_loop = true;
            # これも追加しておく
            $wp_query->is_main_query = true;
            
            $wp_query->post = $post_emulated;
            $wp_query->queried_object = $post_emulated;
            $wp_query->queried_object_id = $post_emulated->ID;

            $GLOBALS['wp_query'] = $wp_query;
        }
        // $this->the_content でオリジナルを返してもらうため
        $this->do_not_filter_the_content = true;
        $content = preg_replace(
            '/ (\/)?wp:codoc\/codoc-block /',' \\1wptmp:codoc/codoc-block ',
            $post_emulated->post_content
        );
        // post_metaに内容を保存し、無料パートにショートコードの退避をおこなう
        $codoc_settings = get_option(CODOC_SETTINGS_OPTION_NAME);
        if (isset($codoc_settings['shortcode_evacuation']) and $codoc_settings['shortcode_evacuation']) {
            $splited  = preg_split('/\/wptmp:codoc\/codoc-block/s',$content);
            if (isset($splited[0]) and isset($splited[1])) {
                // すべてのショートコードを $match_all にいれる
                preg_match_all('/\[[^\[\]]+?\](.+\[\/[^\[\]]+?\])?/',$splited[1],$match_all);
                // ショートコードをURLエンコードしてdivタグの中にいれておく(後でmetaの中のショートコードと付け合せ)
                $replaced = preg_replace_callback('/\[[^\[\]]+?\](.+\[\/[^\[\]]+?\])?/',function($matches) {
                    return sprintf ('<div class="codoc-evacuation-dests" data-shortcode="%s"></div>', urlencode($matches[0]));
                },$splited[1]);
                // 0番目に配列で入っているのでそこを退避対象の配列とする
                $evacuations  = $match_all[0];
                // ショートコードの中身をタグに退避
                $content = $splited[0] . '/wptmp:codoc/codoc-block' . $replaced;
                // 無料パートでショートコードを実行できるようにする
                update_post_meta($post_emulated->ID,'codoc_shortcode_evacuations',join('**codoc**',$evacuations));
            } else {
                update_post_meta($post_emulated->id,'codoc_shortcode_evacuations',"");
            }
        }
        #$content = do_shortcode( $content );
        $content_filtered = apply_filters( 'the_content', $content);
        $this->do_not_filter_the_content = false;

        $GLOBALS['post'] = $post_backuped;

        if ($wp_query_backuped) {
            $GLOBALS['wp_query'] = $wp_query_backuped;
        }

        $content_filtered = preg_replace(
            '/ (\/)?wptmp:codoc\/codoc-block /',' \\1wp:codoc/codoc-block ',
            $content_filtered
        );
        return $content_filtered;
    }

    public function save_post($post_ID,$post,$update) {
        if (preg_match('/^(auto-draft|inherit)$/',$post->post_status)) {
            return;
        }
        $entryCode = get_post_meta($post_ID,'codoc_entry_code',true);
        $postId    = get_post_meta($post_ID,'codoc_saved_post_id',true);
        // 保存されているIDと違う場合は破棄(duplicate pageなどでmeta情報をコピーされた可能性がある)
        if ($postId and $postId != $post_ID) {
            $entryCode = '';
        }
        $codoc_settings = get_option(CODOC_SETTINGS_OPTION_NAME);
        $post_content = (isset($codoc_settings['do_not_filter_the_content']) and $codoc_settings['do_not_filter_the_content']) ?
                      $post->post_content : $this->get_filtered_content($post);
        $res =  $this->util->sync_entry([
            "post_title"       => $post->post_title,
            "post_content"     => $post_content,
            // password が設定されてる場合は限定公開にする
            "post_status"      => $post->post_status == 'publish' ? ($post->post_password ? 2 : 1) : 0,
            "post_permalink"   => get_permalink($post_ID),
            "codoc_entry_code" => $entryCode,
            "codoc_settings"   => $codoc_settings,
        ],[
            "usercode" => get_option(CODOC_USERCODE_OPTION_NAME),
            "token"    => get_option(CODOC_TOKEN_OPTION_NAME),
        ]);
        $prudent_update_post_meta_entry_code = null;
        if (isset($codoc_settings["debug_params"])) {
            $params_decoded = json_decode($codoc_settings["debug_params"],true);
            if (isset($params_decoded["prudent_update_post_meta_entry_code"])) {
                $prudent_update_post_meta_entry_code = $params_decoded["prudent_update_post_meta_entry_code"];
            }
        }
        if ($prudent_update_post_meta_entry_code == 2) {
            error_log('CODOC:: ' . sprintf ("%s : %s : %s : %s", $post_ID, (is_object($res) ? "1" : 0),$res->status,$entryCode));
        }
        if (is_object($res) and $res->status and !$entryCode) {
            // 20230222 https://stackoverflow.com/questions/26640785/update-post-meta-not-work-only-when-save-data-not-for-update
            if ($prudent_update_post_meta_entry_code) {
                add_post_meta($post_ID,'codoc_entry_code',$res->entry->code);
                add_post_meta($post_ID,'codoc_saved_post_id',$post_ID);
            } else {
                update_post_meta($post_ID,'codoc_entry_code',$res->entry->code);
                update_post_meta($post_ID,'codoc_saved_post_id',$post_ID);
            }
        }
        return true;
    }
    function updated_post_meta( $meta_ID, $post_ID, $meta_key ) {
        // スルーされてる場合は他のメタ情報更新のタイミングにサムネイルをアップロード
        $has_to_resend = get_post_meta($post_ID,'codoc_post_thumbnail_invoking_entry_code',true);
        if ($has_to_resend != 1 and $meta_key != '_thumbnail_id') {
            return;
        }
        if ( has_post_thumbnail($post_ID) ) {
            // 新規投稿の場合、タイミングによってはcodocEntryCodeが取得できないので一旦スルーする
            if (!get_post_meta($post_ID,'codoc_entry_code',true)) {
                update_post_meta($post_ID,'codoc_post_thumbnail_invoking_entry_code',1);
                return;
            } else {
                update_post_meta($post_ID,'codoc_post_thumbnail_invoking_entry_code',0);
            }
            $attachment = wp_get_attachment_metadata( get_post_thumbnail_id($post_ID));
            $upload_dir = wp_upload_dir();
            $file_path  = sprintf ('%s/%s',$upload_dir['basedir'],$attachment['file']);

            return $this->util->post_thumbnail([
                "file_path"        => $file_path,
                "boundary"         => wp_generate_password(24),
                "codoc_entry_code" => get_post_meta($post_ID,'codoc_entry_code',true),
            ],[
                "usercode" => get_option(CODOC_USERCODE_OPTION_NAME),
                "token"    => get_option(CODOC_TOKEN_OPTION_NAME),
            ]);
        }
    }
    function deleted_post_meta( $meta_ids, $post_ID, $meta_key,$meta_value ) {
        if ($meta_key != '_thumbnail_id') {
            return;
        }
        return $this->util->reset_thumbnail(
            ["codoc_entry_code" => get_post_meta($post_ID,'codoc_entry_code',true)],
            [
                "usercode" => get_option(CODOC_USERCODE_OPTION_NAME),
                "token"    => get_option(CODOC_TOKEN_OPTION_NAME),
            ]
        );
    }
    // 退避されてるかどうかをmetaを使って確認し、無料エリアの一番最後にショートコードを追加
    function the_content_shortcode_evacuations( $post_content ) {
        if ($this->do_not_filter_the_content) {
            return $post_content;
        }
        $post = get_post();
        if (!$post) {
            return $post_content;
        }
        $evacuations_meta = get_post_meta($post->ID,'codoc_shortcode_evacuations',true);
        $evacuations = preg_split('/\*\*codoc\*\*/',$evacuations_meta);
        foreach ($evacuations as $evacuation) {
            $post_content = sprintf('<div class="codoc-evacuations" style="display:none;" data-shortcode="%s">%s</div>',
                                    urlencode($evacuation),$evacuation) . $post_content;
        }
        return $post_content;
    }
    function the_content( $post_content ) {
        if ( is_single() && in_the_loop() && is_main_query() ) {
        }
        // get_filtered_content から do_not_filter_the_contentを有効にされるパターン
        // オプションの設定値の意味合い（他のフィルタを無視）とは違うため注意
        if ($this->do_not_filter_the_content) {
            return $post_content;
        }
        $post = get_post();
        // 20211215 null になる場合がある
        if (!$post) {
            return $post_content;
        }
        # is_amp で実装しているテンプレート用
        $is_amp_endpoint = (function_exists('is_amp_endpoint') && is_amp_endpoint()) ? true :
                           ((function_exists('is_amp') && is_amp()) ? true : false);
        return $this->util->filter_content([
            "post_content"     => $post_content,
            "preview"          => is_preview(),
            "codoc_entry_code" => get_post_meta($post->ID,'codoc_entry_code',true),
            "codoc_settings"   => get_option(CODOC_SETTINGS_OPTION_NAME),
            "is_amp_endpoint"  => $is_amp_endpoint,
            "post_permalink"   => get_permalink($post->ID),
            "codoc_support_entry_code" => get_option(CODOC_SUPPORT_ENTRYCODE_OPTION_NAME),
        ]);
    }

    function excerpt_allowed_blocks ($allowed_blocks) {
        if (is_array($allowed_blocks)) {
            array_push($allowed_blocks,'codoc/codoc-block');
        }
        return $allowed_blocks;
    }

    function show_tadv_notice() {
        $name = 'Advanced Editor Tools';
        $name_escaped = preg_replace('/ /','+',$name);
        $install_url = network_admin_url( "plugin-install.php?tab=search&s=" . $name );
        $options_url = network_admin_url( "options-general.php?page=tinymce-advanced" );

        //<a href=\"%s\">%s</a> を有効にし、設定画面にて &quot;Keep paragraph tags in the Classic block and the Classic Editor&quot; を有効にしてください。
        //<a href=\"%s\">%s</a> の設定画面にて &quot;Keep paragraph tags in the Classic block and the Classic Editor&quot; を有効にしてください。
        $tadv_message_for_install = sprintf(__("Please enable %s and enable &quot;Keep paragraph tags in the Classic block and the Classic Editor&quot; on the settings screen.","codoc"),sprintf("<a href=\"%s\">%s</a>",$install_url,$name));
        $tadv_message_for_options = sprintf(__("Please enable &quot;Keep paragraph tags in the Classic block and the Classic Editor&quot; on the %s settings screen.","codoc"),sprintf("<a href=\"%s\">%s</a>",$options_url,$name));
        // クラシックエディタで advanced editor tools を入れてない場合は notice
        if (is_plugin_active('classic-editor/classic-editor.php') and
            !is_plugin_active('tinymce-advanced/tinymce-advanced.php')) {
            echo "<div class=\"notice notice-warning is-dismissible\"><p>" . $tadv_message_for_install . "</p></div>";
        }

        // advanced editor tools の設定を調査
        $no_autop = false;
        $tadv_admin_settings = get_option( 'tadv_admin_settings', false );
        if (isset($tadv_admin_settings['options']) and preg_match('/no_autop/',$tadv_admin_settings['options'])) {
            $no_autop = true;
        }
        // advanced editor tools が有効で no_autop (Keep paragraph tags in the Classic block and the Classic Editor) が無効
        if (is_plugin_active('tinymce-advanced/tinymce-advanced.php') and !$no_autop) {
            echo "<div class=\"notice notice-warning is-dismissible\"><p>" . $tadv_message_for_options . "</p></div>";
        }
    }
}
