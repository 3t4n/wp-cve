=== Payamito Core ===
Contributors: payamito
author URI: https://payamito.com
Tags: sms payamito
Requires at least: 5.0.0
Tested up to: 6.4.3
Requires PHP: 7.4
Stable tag: 2.1.8
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
افزونه ارسال پیامک پیامیتو

== Description ==
افزونه هسته پیامیتو
برای استفاده از افزونه های پیامیتو این به عنوان هسته استفاده خواهد شد.

ده ها افزونه به زودی ارائه خواهد شد که نیاز به این افزونه به عنوان هسته خود دارند.

و به راحتی می توان پیامک برای بخش های مختلف سایت اضافه کرد.

== Installation ==
1. Upload `payamito-core` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the \'Plugins\' menu in WordPress
3. Complete Payamito Settings


== Changelog ==
= 1.1.0 DECEMBER  2021 =
1. Fixed an issue adding menus to plugins in the file register_setting.PHP
2. Call the load_admin_pages function in the plugins_loaded hook
To interact more and prevent problems
3.add get_path_payamito function to access other plugins to path
4. The payamito_add_section filter was added to expand the plugin menu
5.Change in return value in two functions payamito_send_pattern and payamito_send
6.Action hook payamito_loaded was added to announce the full load of the plugin
7. function load_sections  deleted
8. file primary_settings deleted

= 1.1.1 January  2022 =
1.Library and Jquery plugins  'modals','tooltips','copy' were added

= 1.1.2 January  2022 =
1.Fixed an issue in payamito_resent_time_check function

2.Add payamito_is_request function to check type request
3.Add payamito_jalali_converter function to convert jalali date
4.This ensures `payamito_loaded` is called only after all other plugins
5.Library and Jquery plugins  'notification','spinner' were added
= 1.1.3 april  2022 =
Fixed function have previously defined

= 2.1.8 February  2024 =


