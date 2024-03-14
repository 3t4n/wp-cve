=== Yes/No Chart ===
Contributors: kohseiworks, tnomi
Donate link: https://kohsei-works.com/plugins
Tags: yesno, chart, questionnaire, question, answer, q&a, diagnosis
Requires at least: 4.4
Tested up to: 6.2.2
Requires PHP: 5.5
Stable tag: 1.0.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin provides the function to create a set of questions to answer with "yes / no (/or other)". Yes/Noチャートを作れるプラグインです。


== Description ==

This plugin provides the function to create a set of questions to answer with "yes / no (/or other)". <br>
Requires PHP: 5.5<br>
Yes/Noチャートを作れるプラグインです。<br>
必須PHPバージョン：5.5<br>

The guide is here. ガイドはこちらです<br>
[日本語(ja)](https://kohsei-works.com/plugins)


== Installation ==

This plug-in makes several pages and data base tables automatically.<br>
このプラグインはデータベーステーブルを自動的に作ります

= Installation =

1. Donwload plugin zip file.<br>
プラグインファイルをダウンロードします

2. Upload plugin file from Administrator menu “Plugins > Add New > Upload Plugin”.<br>
管理画面「プラグイン > 新規追加 > プラグインのアップロード」からプラグインファイルをアップロードします

3. Activate the plugin.<br>
プラグインを有効化します


= Set questions =

1. Open a menu "Yes / No: Question Set". <br>
"Question Set" is a group that brings together questions.<br>
メニュー「Yes/No: 設問セット」を開きます。

2. Insert the name of new question set in the top line, and "Update".<br>
When it is added, open "Add Question". <br>
新しい設問セットを追加したら「設問追加」を開きます。

3. Enter the question contents.<br>
You can choose "Question (with branch)" and "Result (No branch)". <br>
設問を入力します。「設問（分岐あり）」か「結果（分岐なし）」を選べます。

4. You can edit the question from list of "Questions". <br>
設問を追加したら、リストのリンクをクリックして設問を編集できます。

5. Select a "Branch" from the questions in the same set.<br>
You can set up to 10 branches "Yes, No" and others.<br>
A question without "Branch" is used as "result" and it is a end point.<br>
同じセットの設問から分岐先を選択します。<br>
分岐は「はい・いいえ」のほか10個まで設定できます。 <br>
分岐のない設問は「結果（終点）」になります。

6. After setting all, Insert short-code on any page.<br>
設定が済んだら任意のページにショートコードを挿入します。


== Frequently Asked Questions ==

* If you encounter some problems, please ask me.<br>
ご不明な点がありましたらお問い合わせください

= Can not set questions in the "Editor" role?<br>"編集者"権限でも設問を設定できませんか？ =

If you want the "Editor" role to also set the question, add the following code to the theme "functions.php".<br>
"編集者"権限でも設問を設定させる場合は、お使いのテーマの"functions.php" に次のようにコードを追加してください。<br>
<br>

<code>add_filter('yesno_allow_menu', 'my_menu_role',99 );
function my_menu_role( $args ){
	return 'edit_pages';
}
add_filter('yesno_allow_generate', 'my_generate_role', 99 );
function my_generate_role( $args ){
	return current_user_can('edit_pages');
}</code>
<br>
* See the document on roles.<br>
[https://codex.wordpress.org/Roles_and_Capabilities](https://codex.wordpress.org/Roles_and_Capabilities)


== Screenshots ==

1. List of "Question Set": 設問セットのリスト
2. List of "Questions" belonging to the set: セット内の設問のリスト
3. Form for adding new "Question": 設問を追加するフォーム
4. Adding new question as a "Result": 「結果」として設問を追加
5. Select a "Branch" from the questions in the same set: 同じセットの設問から分岐先を選択
6. You can set up to 10 branches "Yes, No" and others: 分岐先は「はい・いいえ」など10個まで設定可能
7. After setting all, Insert short-code on any page: 設定が済んだら任意のページにショートコードを挿入
8. It will start with the first question: 最初の設問から開始
9. A question without "Branch" is used as "result" and it is a end point: 分岐のない設問は「結果（終点）」です
10. If you make a "Branch" to the first question, it is "Once again": 最初の質問への分岐を作ると「もう１度」です


== Changelog ==

= 1.0.12 =

* Fixed shortcode parameter security issue.

= 1.0.11 =

* Fixed a bug when Quotation marks (single or double) were used in "Label" of the question choices.

= 1.0.10 =

* In yesno.js, automatic scrollback was turned off by default.

= 1.0.9 =

* The scroll has been added that return to the top of the "Yes/No Chart" block after clicking the "Yes/No" button.

= 1.0.8 =

* The text of the "back" button in yesno.js was localized by the translation file.

= 1.0.7 =

* After displaying the result, the child elements of '# choices' will be removed with transparency.

= 1.0.6 =

* When creating the table, collation of text type column is set to "utf8_general_ci".

= 1.0.5 =

* Roles that can set question can be changed by "yesno_allow_generate" filter.
* Small bug fix.

= 1.0.4 =

* "Redirect to(URL)" is saved without entering a question.

= 1.0.3 =

* Problem with the back button fixed.

= 1.0.2 =

* "yesno.js" has been slightly fixed.

= 1.0.1 =

* "Requires at least" was changed to "4.4".
* This plugin update information feed has been added.

= 1.0.0 =

* First release.


== Upgrade Notice ==

= 1.0.12 =

Fixed shortcode parameter security issue.
