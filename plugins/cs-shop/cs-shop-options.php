<?php
/**
 * WordPress 設定項目定義
 * User: cottonspace
 * Date: 12/04/13
 */

/**
 * 設定項目定義
 */
$plugin_options = array(
    "楽天アフィリエイト" => array(
        "楽天アフィリエイトID" => "csshop_rakuten_aid",
        "楽天デベロッパーID" => "csshop_rakuten_did"
    ),
    "Amazon" => array(
        "アクセスキーID" => "csshop_amazon_access_id",
        "シークレットアクセスキー" => "csshop_amazon_secret_id",
        "アソシエイトID" => "csshop_amazon_assoc"
    ),
    "Yahoo!ショッピング" => array(
        "アプリケーションID" => "csshop_yahoo_appid",
        "アフィリエイトID" => "csshop_yahoo_affiliate_id"
    ),
    "LinkShare" => array(
        "トークン" => "csshop_linkshare_token"
    ),
    "ValueCommerce" => array(
        "トークン" => "csshop_valuecommerce_token"
    )
);

/**
 * 旧バージョンで使用していた定義(アンインストール時の設定削除に使用)
 */
$obsolete_options = array(
    "csshop_linkshare_md_host",
    "csshop_linkshare_md_user",
    "csshop_linkshare_md_pass"
);
