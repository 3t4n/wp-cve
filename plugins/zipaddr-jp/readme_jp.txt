=== zipaddr-jp ===
Contributors: ta_terunuma
Donate link: https://zipaddr2.com/wordpress/
Tags: zipaddr, zip, address, plugin, ajax, cross-domain
Requires at least: 3.7
Tested up to: 5.7
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==
****************************************
  zipaddr-jp for Wordpress の概説
****************************************

zipaddr(ジップアダー)日本語版は以下の環境で動作します。
Wordpress 3.7 以上
PHP 5.x
MySQL 未使用
文字コード UTF-8

はじめにzipaddrはサービス・プロバイダで、無料のhttps://zipaddr.com/と有償のhttps://zipaddr2.com/があります。
当プラグインはWordpressとzipaddrをアプリケーション連携させます。
具体的には次のようなjavascript文を1行、生成します。
&lt;script src="https://zipaddr.com/js/zipaddrx.js"   charset="UTF-8"&gt;&lt;/script&gt; or
&lt;script src="https://zipaddr2.com/js/zipaddr3.js"  charset="UTF-8"&gt;&lt;/script&gt; or
&lt;script src="https://zipaddr2.com/js/zipaddr30.js" charset="UTF-8"&gt;&lt;/script&gt;

主な動作手順は次のようになります。
1.起動設定後に郵便番号の入力を待ちます。
2.Wordpress側は郵便番号検索リクエストをzipaddr側に送ります。
3.zipaddrでは郵便番号からDBを検索して都道府県、市区町村、地域、データをjsonp形式で返します。
4.Wordpress側にはガイダンスで検索の途中データが表示されます。
5.最終的にWordpress側画面の都道府県、市区町村、地域、の各欄にデータが埋め込まれます。
zipaddr-jpの詳細説明は、https://zipaddr2.com/wordpress/ を参照して下さい。

[重要]
zipaddr-jpを使用中に起こった如何なる損害も自己責任となります。



== Installation ==
【インストール】

解凍して得られたフォルダ「zipaddr-jp」を、Wordpressの`/wp-content/plugins/`フォルダに設置する。
アップデートする場合は「zipaddr-jp」を上書きします。
一旦zipaddr-jpを停止してからアップデートし、有効化を行ってください。



== Changelog ==
【更新履歴】

= 1.25 =
[2021/06/19]
・WPFormsをサポートしました。

= 1.24 =
[2021/06/19]
・設定オプションの見直しを行ないました。

= 1.23 =
[2019/07/06]
・Ninja Formsをサポートしました。

= 1.22 =
[2018/05/26]
・一部サーバーの切替を行ないました。

= 1.21 =
[2018/03/03]
・コーディングの全面見直しを行ないました。

= 1.20 =
[2017/11/02]
・welcartのV-UP対応を行ないました。

= 1.19 =
[2016/12/19]
・無条件にモジュールを挿入する機能を追加しました。

= 1.18 =
[2016/09/23]
・モジュール統合とplaceholderの設定機能を追加しました。

= 1.17 =
[2016/01/30]
・Welcartのモジュールを固定(zipaddrx.js)にしました。

= 1.16 =
[2015/12/12]
・バグ修正をしました。

= 1.15 =
[2015/11/11]
・Welcart管理画面に対応しました。

= 1.14 =
[2015/8/22]
・郵便番号区切り文字の指定を可能にしました。

= 1.13 =
[2015/6/20]
・スピードアップ版の提供とガイダンス出力を7桁標準に変更しました。

= 1.12 =
[2015/4/29]
・Trust Formの標準サポートとバグ修正をしました。

= 1.11 =
[2015/3/16]
・HELPのリンク先を充実させました。

= 1.10 =
[2015/1/1]
・システム拡張用の識別子を設定する機能を追加しました。

= 1.9 =
[2014/12/7]
・郵便番号入力後のフォーカス機能を追加しました。

= 1.8 =
[2014/9/6]
・ガイダンス画面の文字サイズ変更機能を追加しました。

= 1.7 =
[2014/3/30]
・御社サイト内で郵便番号簿を管理するモードを追加しました。

= 1.0.6 =
[2014/1/25]
・ガイダンス画面の表示位置変更機能を追加しました。

= 1.0.5 =
[2014/1/21]
・SSL関連のバグを修正しました。

= 1.0.4 =
[2014/1/7]
・対象の適用範囲を拡大しました。

= 1.0.3 =
[2014/1/6]
・設定ファイルの格納場所を変更しました。

= 1.0.2 =
[2014/1/5]
・稼働環境の設定機能を追加しました。

= 1.0.1 =
[2013/12/6]
・初期バージョンとして公開しました。
