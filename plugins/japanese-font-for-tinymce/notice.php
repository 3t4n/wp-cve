<?php
//1 is enable, 0 is disable unless written.
// config 1 is CDN
//conbfig 2 is font load mode
//config 3 is enable/disable gutenberg setting
//config 4 is load by header or footer. 0=header, 1=footer
$config1 = get_option('tinyjpfont_check_cdn');
$config2 = get_option('tinyjpfont_select');
$config3 = get_option('tinyjpfont_gutenberg');
$config4 = get_option('tinyjpfont_head');
$config5 = get_option('tinyjpfont_default_font');
$defaultvalue = "0";
$isknown = "";
//NOTICE DISMISS URL GENERATOR
function tinyjpfont_notice_dismiss_url($dismissid)
{
    $current_url = $_SERVER['REQUEST_URI'];
    $query = parse_url($current_url, PHP_URL_QUERY);
    if ($query) {
        $current_url .= '&' . $dismissid . "=true";
    } else {
        $current_url .= '?' . $dismissid . "=true";
    }
    return $current_url;
}
//Notice
function tinyjpfont_fix428_notice()
{
    $user_id = get_current_user_id();
    $dismissurl = tinyjpfont_notice_dismiss_url('tinyjpfont-fix428-notice-dismissed');
    if (!get_user_meta($user_id, 'tinyjpfont_fix428_notice_dismissed', 'dismissed') && current_user_can( 'manage_options' ) ) {
        echo '<div class="notice notice-info" style="padding:1%;"><strong>Japanese Font for WordPressからのお知らせです!</strong>(バージョン4.28 リリースノート)<br>
				・通知のDismissが、WordPressがサブディレクトリ下にインストールされている場合に正常に稼働しないバグを修正しました。T.さん、ご報告ありがとうございました。<br />
				<br><a href="' . $dismissurl . '">Dismiss(この通知を消す)</a></div>';
}
}
add_action('admin_notices', 'tinyjpfont_fix428_notice');

add_action('admin_init', 'tinyjpfont_fix428_notice_dismissed');
function tinyjpfont_fix428_notice_dismissed()
{
    $user_id = get_current_user_id();
    if (isset($_GET['tinyjpfont-fix428-notice-dismissed']))
        add_user_meta($user_id, 'tinyjpfont_fix428_notice_dismissed', 'true', true);
}
add_action('admin_init', 'tinyjpfont_fix428_notice_dismissed');

//Gutenberg Extra Notice
//Notice
function tinyjpfont_gutenberg_notice()
{
    $user_id = get_current_user_id();
    $config3 = get_option('tinyjpfont_gutenberg');
    $dismissurl = tinyjpfont_notice_dismiss_url('tinyjpfont-gutenberg-notice-dismissed');
    if (!get_user_meta($user_id, 'tinyjpfont_gutenberg_notice_dismissed', 'dismissed') && get_user_meta($user_id, 'tinyjpfont_install_notice_dismissed', 'dismissed') && $config3 == 0 && current_user_can( 'manage_options' ))
        echo '<div class="notice notice-warning" style="padding:1%;"><strong>Gutenberg(ブロックエディタ)対応機能が無効になっているようです</strong><br>
				Japanese Font for WordPressの一部機能がGutenberg(ブロックエディタ)上で動作しない状態となっています。<br />
				ダッシュボードのサイドバーにあるJapanese Font for WordPressの設定より、「Gutenberg対応機能の有効化」をお願いします。<br />
				なお、WordPressバージョン5.0より前のバージョンをお使いの方はこの通知を無視していただいて大丈夫です。<br />
				この通知はGutenberg対応機能が有効化され次第、自動的に消去されます。<br />
				<br><a href="' . $dismissurl . '">2019年よりWordPressを更新していない/ブロックエディタを使用していないのでこの通知を無視する</a></div>';
}
add_action('admin_notices', 'tinyjpfont_gutenberg_notice');

add_action('admin_init', 'tinyjpfont_gutenberg_notice_dismissed');
function tinyjpfont_gutenberg_notice_dismissed()
{
    $user_id = get_current_user_id();
    if (isset($_GET['tinyjpfont-gutenberg-notice-dismissed']))
        add_user_meta($user_id, 'tinyjpfont_gutenberg_notice_dismissed', 'true', true);
}
add_action('admin_init', 'tinyjpfont_gutenberg_notice_dismissed');

//INSTALL NOTICE

function tinyjpfont_install_notice()
{
    $user_id = get_current_user_id();
    $dismissurl = tinyjpfont_notice_dismiss_url('tinyjpfont-install-notice-dismissed');
    if (!get_user_meta($user_id, 'tinyjpfont_install_notice_dismissed', 'dismissed')  && current_user_can( 'manage_options' ))
        echo '<div class="notice notice-info" style="padding:1%;"><strong>Japanese Font for WordPressへようこそ!</strong><br>
                Japanese Font for WordPressのインストールありがとうございます!<br>
                さっそく新しく追加された7種類のフォントを試してみましょう!<br>
				(WordPress5.0以降に搭載されているブロックエディタ、Gutenbergでこれらのフォントを利用するにはJapanese Font for WordPressの設定にて
                Gutenberg対応モードを有効化する必要がありますのでご注意ください)<br>
				何か不具合等発見されましたら<a href="https://twitter.com/raspi0124">Twitter: @raspi0124</a> または raspi0124[at]gmail.com までお気軽にご連絡ください。<br>
				Japanese Font for WordPressをよろしくお願いします!<br>
				<br><a href="' . $dismissurl . '">Dismiss(この通知を消す)</a></div>';
}
add_action('admin_notices', 'tinyjpfont_install_notice');

function tinyjpfont_install_notice_dismissed()
{
    $user_id = get_current_user_id();
    if (isset($_GET['tinyjpfont-install-notice-dismissed']))
        add_user_meta($user_id, 'tinyjpfont_install_notice_dismissed', 'true', true);
}
add_action('admin_init', 'tinyjpfont_install_notice_dismissed');

function tinyjpfont_advanced_warning()
{
    $user_id = get_current_user_id();
    $dismissurl = tinyjpfont_notice_dismiss_url('tinyjpfont-advanced-warning-dismissed');
    if (!get_user_meta($user_id, 'tinyjpfont_advanced_warning_dismissed', 'dismissed') && is_plugin_active('tinymce-advanced/tinymce-advanced.php')  && current_user_can( 'manage_options' ))
        echo '<div class="notice is-dismissible notice-warning" style="padding:1%;"><strong>Advanced Editor Tools (旧名 TinyMCE Advanced)プラグインの設定をお願いします</strong><br>
				現在、Advanced Editor Tools (旧名 TinyMCE Advanced) プラグインがインストールされている環境においてJapanese Font for WordPressのクラシックエディタ上での動作を始めとする機能の動作に不具合が生じています。<br>
				お手数おかけしますが、<a href="https://diary.raspi0124.dev/post-4428/" target="_blank" rel="noopnener">こちらの記事の手順</a>に従って設定をお願いします。<br>
				<br><span style="float: right;"><a href="' . $dismissurl . '">設定を完了したのでこの通知を表示しない</a></span></div>';
}
add_action('admin_notices', 'tinyjpfont_advanced_warning');

function tinyjpfont_advanced_warning_dismissed()
{
    $user_id = get_current_user_id();
    if (isset($_GET['tinyjpfont-advanced-warning-dismissed']))
        add_user_meta($user_id, 'tinyjpfont_advanced_warning_dismissed', 'true', true);
}
add_action('admin_init', 'tinyjpfont_advanced_warning_dismissed');