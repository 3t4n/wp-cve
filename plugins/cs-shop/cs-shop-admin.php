<?php
/**
 * WordPress プラグイン管理画面
 * User: cottonspace
 * Date: 12/04/08
 */

/**
 * 設定項目定義の取得
 */
require_once 'cs-shop-options.php';

// 管理メニュー登録
add_action('admin_menu', 'csshop_admin_menu');

// 管理メニュー選択時のフック処理
function csshop_admin_menu()
{
    add_options_page('CS Shop', 'CS Shop', 'manage_options', __FILE__, 'csshop_options');
}

// 設定画面表示
function csshop_options()
{
    // 設定項目定義の参照
    global $plugin_options;

    // 設定反映処理
    if (isset($_POST["save"])) {
        check_admin_referer('csshop-update-options');
        foreach (array_values($plugin_options) as $group_options) {
            foreach (array_values($group_options) as $option_id) {
                if (array_key_exists($option_id, $_POST)) {
                    if (empty($_POST[$option_id])) {
                        delete_option($option_id);
                    } else {
                        update_option($option_id, $_POST[$option_id]);
                    }
                }
            }
        }
        echo "<p>設定を保存しました。</p>\n";
    }

    // 設定変更画面を表示する
    echo <<< EOT
<div class="wrap">
<h2>CS Shop 設定</h2>
<form method="post" action="">
EOT;
    wp_nonce_field('csshop-update-options');
    $option_names = array();
    foreach ($plugin_options as $group_name => $group_options) {
        echo <<< EOT
<h3>{$group_name}</h3>
<table class="form-table">
EOT;
        foreach ($group_options as $option_name => $option_id) {
            array_push($option_names, $option_id);
            $option_value = get_option($option_id);
            echo <<< EOT
<tr valign="top">
    <th scope="row">{$option_name}</th>
    <td><input type="text" size="80" name="{$option_id}" value="{$option_value}" /></td>
</tr>
EOT;
        }
        echo <<< EOT
</table>
EOT;
    }
    $option_names_joined = join(",", $option_names);
    echo <<< EOT
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="{$option_names_joined}" />
<p class="submit">
    <input name="save" type="submit" class="button-primary" value="変更を保存" />
</p>
</form>
</div>
EOT;
}
