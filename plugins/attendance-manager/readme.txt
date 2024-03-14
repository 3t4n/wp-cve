=== Attendance Manager ===
Contributors: tnomi
Donate link: http://attmgr.com
Tags: schedule, attendance, work, employee, online scheduling
Requires at least: 4.1
Tested up to: 6.3.2
Requires PHP: 5.5
Stable tag: 0.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Each user can do attendance management by themselves. 
管理者のほか、ユーザー自身も編集可能な出勤管理プラグイン。

== Description ==

Visit [The User's Guide (ja)](http://attmgr.com)/[(en)](http://attmgr.com/en/) for more info.

An administrator can do all users’ attendance management.<br>
And each user can do attendance management by themselves.

An attendance schedule is displayed by shortcords.<br>
* Today's staff<br>
* Weekly schedule<br>
* Monthly schedule<br>


管理者は全てのユーザーの出勤管理ができます。<br>
また、ユーザーも自分自身の出勤管理が可能です。<br>

出勤スケジュールはショートコードで表示されます。<br>
* 今日の出勤スタッフ<br>
* 週間スケジュール<br>
* 月間スケジュール<br>

== Installation ==

This plug-in makes several pages and data base tables automatically.<br>
このプラグインはいくつかのページとデータベーステーブルを自動的に作ります。

= Installation =

1. Donwload plugin file (“attendance-manager.zip”)<br>
プラグインファイル (“attendance-manager.zip”) をダウンロードします。

2. Upload plugin file from Administrator menu “Plugins > Add New > Upload Plugin”.<br>
管理画面「プラグイン > 新規追加 > プラグインのアップロード」からプラグインファイルをアップロードします。

3. Activate the plugin.<br>
プラグインを有効化します。

= Plugin set up =

1. Open the WordPress admin panel, and go to the plugin option page “Attendance Manager”.<br>
管理画面を開き「Attendance Manager」メニューを開きます。

2. Set up option item of some.<br>
オプション項目を設定します。

= User registration as "staff" =

1. Register staff of your workplace as user.<br>
職場のスタッフをユーザー登録します。

2. When registering user, check "This user is a staff".<br>
登録の際、「このユーザーはスタッフです」をチェックします。

3. In case of the registered user, check "This user is a staff" in  a profile edit page of that user.<br>
登録済みのユーザーの場合は、そのユーザーのプロフィール編集ページで「このユーザーはスタッフです」をチェックします。

= Post each staff’s introduction article =

Post each staff's introduction article. (For example into a "staff" category etc.)<br>
And insert short cord [attmgr_weekly id="xx"] to that article.<br>

* "id" is ID number of each user in your WordPress.

新たに各スタッフの紹介記事を投稿します。（例えば「スタッフ」カテゴリーなどに）
その記事に、ショートコード [attmgr_weekly id="xx"] を挿入します。

* "id" はあなたのサイトにおける各ユーザーのID番号です。

= Post a staff’s information =

Post each staff’s information article. (For example, into a “staff” category etc.)<br>
And insert short cord [attmgr_weekly id=”xx”] to that article.<br>
This short code displays the weekly schedule of this staff.<br>

各スタッフの紹介記事を投稿します。（例えば「スタッフ」カテゴリーなどに）<br>
その記事に、ショートコード [attmgr_weekly id=”xx”] を挿入します。<br>
このショートコードは、そのスタッフの週間スケジュールを表示するものです。<br>

* "id" is ID number of each user in your WordPress.<br>
"id" はあなたのサイトにおける各ユーザーのID番号です。

= Attendance management =

* An administrator does all the user's attendance management by a scheduler for admin.<br>
管理者は管理者用スケジューラから全てのユーザーの出勤管理を行ないます。

* A staff logs in and does the attendance management by a scheduler for a staff.<br>
スタッフはログインしてスタッフ専用スケジューラから自身の出勤管理を行ないます。

== Frequently Asked Questions ==

If you encounter some problems, please ask me.<br>
Visit [Trouble shooting (ja)](http://attmgr.com/setup/troubleshooting/) for more info.<br>


= When the number of staff increases, the schedule is not reflected. =

If the number of staff increases too much, the schedule may not be reflected.<br>
This may be the upper limit of the number of POST items in your PHP.<br>
In that case increase the value of "max_input_vars" in php.ini.<br>
e.g.)<br>
max_input_vars = 5000<br>

= スタッフ数を増えやしたらスケジュールが反映されなくなりました =

スタッフ数が増えすぎるとスケジュールが反映されないことがあります。<br>
それは、ご利用環境のPHPでのPOST項目数の上限かもしれません。<br>
その場合、php.iniの "max_input_vars"の値を増やしてみてください。<br>
例）<br>
max_input_vars = 5000<br>

== Screenshots ==

1. "Scheduler for Admin" page
2. "Scheduler for Staff" page
3. "Today's Staff" page
4. "Weekly schedule" page
5. "Monthly schedule" page
6. Plugin option

== Changelog ==

= 0.6.1 =

* Fixed warning in shortcode [attmgr_daily].

= 0.6.0 =

* Fixed a link error with the shortcode [attmgr_daily] when business hours exceed midnight.

= 0.5.9 =

* Fixed a shortcode error when editing with the block editor.

= 0.5.8 =

* Fixed cURL timeout issue.

= 0.5.7 =

* Fixed a vulnerability issue.

= 0.5.6 =

* 'Screen_icon' on the admin-page has been deleted. And fixed some PHP 'Notice'.

= 0.5.5 =

* Fixed some notices and warnings displayed in "WP_DEBUG" mode has been corrected.

= 0.5.4 =

* Bug fix in calendar navi.

= 0.5.3 =

* Shortcode '[attmgr_today_work id="xx"]' is added.
* The opening hours during midnight will be regarded as "Today".
* Bug fix in calendar.

= 0.5.2 =

* The link of each staff can be given by "Edit User: Website(user_url)".
* An option to use the user avatar on a staff's portrait was added.

= 0.5.1 =

* An "action" URL of the "Settings" form was changed.
* Action hook "parse_request" was changed to "template_redirect".
* These functions were changed, ATTMGR::current_page(), ATTMGR::current_user(), ATTMGR_Form::action(), ATTMGR_Form::access_control().

= 0.5.0 =

* A "Date/Time Format" was added to the plugin option.<br>
Several filter hook were added.<br>
 - 'attmgr_date_format'
 - 'attmgr_month_format'
 - 'attmgr_time_format'
 - 'attmgr_time_format_editor'

* The schedule table name is given from a filter.<br>
 - 'attmgr_schedule_table_name'

* Several filter hook parameter were changed.<br>
 - 'attmgr_shortcode_staff_scheduler'
 - 'attmgr_shortcode_admin_scheduler'
 - 'attmgr_shortcode_daily'
 - 'attmgr_shortcode_weekly'
 - 'attmgr_shortcode_weekly_all'
 - 'attmgr_shortcode_monthly_all'

* Bug fix about submit processing in the scheduler.

* Dutch translation (by Kleijheeg-san) was added.

= 0.4.5 =

* Parameter "guide" was added to short code `[attmgr_daily]`.<br>
usage: `[attmgr_daily guide="week"]`<br>
In this case, the link to each date in a week is shown.<br>
The value of parameter "guide" are "week" or "1week".<br>
In a case of "1week", the link to next week and previous week are not shown.<br>
Parameter "guide" may omit. If "guide" is omitted, the link to each date is not shown.

* Parameter "past" was added to short code `[attmgr_daily]` and `[attmgr_weekly_all]` and `[attmgr_monthly_all]`.<br>
usage(1): `[attmgr_daily guide="week" past="0"]`<br>
usage(2): `[attmgr_weekly_all past="0"]`<br>
In this case, the link to the past is not shown.<br>
Parameter "past" may omit, and default value of "past" is "true".

* "font-size" in the <th> of schedule table was changed.(front.css)

= 0.4.4 =

* Parameter "hide" was added to short code `[attmgr_weekly]`.<br>
usage: `[attmgr_weekly id="xx" hide="1"]`<br>
In this case, it doesn't show anything.<br>
Parameter "hide" may omit, and default value of "hide" is "false".

= 0.4.3 =

* Bug fix.
* Some filters were added.
* Media query is added to "front.css".

= 0.4.2 =

* Some filters were added.

= 0.4.1 =

* Bug fix in "Monthly schedule".

= 0.4.0 =

* When time table is up to the next day like "23:00~08:00", the schedule which continues from the previous day is displayed in "Today's staff" until end time. (In this case, It is until 8:00.)

* Time selection of a scheduler is helped.<br>
When the start time was chosen, standard end time is chosen automatically.<br>
And, the choices of end time are limited to time which is later from the start time.<br>
If start time which is later from the end time is chosen, end time would be reset.

= 0.3.1 =

* Bug fix.

= 0.3.0 =

* Some style classes were added.
* Bug fix.

= 0.2.0 =

* first release.

== Upgrade Notice ==

= 0.6.1 =

Fixed warning in shortcode [attmgr_daily].
