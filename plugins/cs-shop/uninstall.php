<?php
/**
 * WordPress プラグイン削除処理
 * User: cottonspace
 * Date: 12/04/08
 */

/**
 * 設定項目定義の取得
 */
require_once 'cs-shop-options.php';

/**
 * 全ての設定オプションを削除
 */
foreach (array_values($plugin_options) as $group_options) {
    foreach (array_values($group_options) as $option_id) {
        delete_option($option_id);
    }
}

/**
 * 旧バージョンで使用していた定義の削除
 */
foreach ($obsolete_options as $option_id) {
    delete_option($option_id);
}
