=== Plugin Name ===
Contributors: nora0123456789
Donate link: https://wp-works.net/donations/
Tags: templates, Manage, TinyMCE, Editor
Requires at least: 4.0.1
Tested up to: 4.4
Stable tag: 4.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables to Handle ( insert, make, edit ) Templates on Editor.


== Description ==

Adds Custom Post Type "MTH Template", make content editing easier.

What you can do with MTH:
<ul>
	<li>insert and make templates on editors without refreshing page</li>
	<li>categorize templates with taxonomy "Group" for MTH Template</li>
	<li>Filter Templates with Group when you seach for a template to insert</li>
</ul>


カスタム投稿タイプ「MTH Template」を追加し、コンテンツ編集をより簡単にします。

「Magic Template Holder」でできること：

<ul>
	<li>編集ページでテンプレートを「挿入・作成」</li>
	<li>グループ分類可能</li>
	<li>検索用のテンプレートフィルター有り</li>
</ul>


== Installation ==

<ol>
	<li> upload to "/wp-content/plugins/magic-template-holder", or directly search and install from wp repository on your admin page. ( "Plugins" -> "Add New" )</li>
	<li>Activate.</li>
</ol>

<ol>
	<li> 「/wp-content/plugins/magic-template-holder」にアップロード、もしくは直接「プラグイン」->「新規追加」で検索しインストールする。</li>
	<li>プラグインページで有効化する。</li>
</ol>

== Frequently Asked Questions ==

= 使い方 =
For Making Templates, make new one at "MTH Templates" -> "Add New" like other post type, or you can make templates by clicking button "Make a new MTH Template". First Text box is for Title, Secont is for Group that you can set plural Group separating with comma, Textarea for Template Content that you can modify before clicking button "Make a new Template". As default, textarea in making-template-form has contents text. You can optionally select from contents text for the textarea.

once you create template, you can insert templates by clicking button "Insert a MTH Template( Left One )". Checkbox is for filter by Group, dropdown select is the list of templates filtered ( needs a second for set ) including drafts( appended "Draft" on title ).

投稿ページのように「MTH Templates」->「Add New」から新規テンプレートを作成、もしくは編集ページの「MTHテンプレートを作成」ボタンでコンテンツテキスト（部分的に選択可能）からテンプレートを直接作成できます。

作成したテンプレートは「MTHテンプレートを挿入」ボタンでコンテンツに挿入可能です。


= What this plugin for? =

Enable easy edits.

コンテンツ編集を楽にします。


== Changelog ==

= 1.0.12 =
* Fixed the Condition. now this works only in edit page and add new page.

= 1.0.11 =
* Fixed Save Filter.

= 1.0.10 =
* Changed the Author URL.

= 1.0.9 =
* Applied for PHP 5.5 or less.

= 1.0.8 =
* Fixed the Media Buttons Data.

= 1.0.7 =
* Set Media Buttons Again.
* Upgraded MTH Media Buttons so that the Button "Make a Template" is available also with selected contents for Visual Editor.

= 1.0.6 =
* Fixed Popup Z-Index.
* Fixed JavaScript for Quicktags.

= 1.0.5 =
* Fixed Contributer in "readme.txt".

= 1.0.4 =
* Change Media buttons into MCE buttons and QuickTags.

= 1.0.3 =
* Debugged Undefined Vars.

= 1.0.2 =
* Make the template filter for insert faster.

= 1.0.1 =
* Translated. (English)

= 1.0.0 =
* Released.

