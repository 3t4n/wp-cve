=== Kama WP Smiles ===
Stable tag: trunk
Tested up to: 5.6
Contributors: Tkama
Official website: http://wp-kama.ru?p=185
Requires at least: 3.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: comments, smiles, posts, optimization

Replace WP smilies (emoticons) to pretty dynamic one. Automaticaly add smiley block to comment form and in visual/HTML editor on admin. You can set your own package of smiles or select preferred from existing list.


== Description ==

Kama WP Smiles adds block with smiles to comment form in your theme and to HTML/visual editor in admin panel. All that allows you easily add smiles into comment or post content. The plugin replace original WordPress emoticons by new pretty dinamic one (gif).

With Kama WP Smiles visitors of you blog will have easy instrument to add smiles in comments and you will have opportunity to add smiles while writing posts or answer comments.

On plugin settings page, you can choose which of accessible smiles will appear in the smiles block. And you can tune smiles special code like :) which will be replaced on smile image in content. Moreover you can add your own package of any images and use it as smiles.

If you don't enjoy the plugin, you can just delete it. On uninstall, plugin will clean up after itself.

[Official plugin page](http://wp-kama.ru/?p=185)




== Frequently Asked Questions ==

= Plugin don't add smile block to comment form =

May be, you comment form textarea HTML tag have not default ID attribute. Specify comment form ID attribute tag on settings page. Default is `comment`


= I have HTML tag &lt;var&gt; where it needn't replace smile code to smile image =

Add exceptions tags on settings page in which no need to replace smile code to smile. Default is `code, pre, style, script, textarea`.


= How can I add smile block to comment form by myself? =

To add smile block to comment form or any other textarea form. Leave empty comment ID field on settings page and use next code in your theme:

    `
	&lt;?php echo kws_get_smiles_html( $textarea_id ); ?&gt;
	`



== Screenshots ==

1. Admin panel settings page.
2. Comment form with smile block in theme.
3. Comment form with smile block in admin panel.
4. Post html/visual editor with smile block in admin panel.





===== TODO =====

Поддержка Quick Chat и BbPress

На мой взгляд - это:

1. Кнопки (в настройках) > Раскрытие списка смайлов (выпадающий список) > при подведении к значку ИЛИ нажатию на него (или так или так) — (переключатель в настройках!!!)

— Когда нажимаешь на значок смайла > раскрывается меню > !!! НЕ РЕАЛИЗОВАНО !!!!
(не всегда удобно когда подводишь и раскрывается список, порой даже мешает)


2.
— Выпадающий список > наверное тоже самое что пункт 1. только со значком рядом " стрелочка вниз " - (">" только повёрнутая вниз) —---( справа или слева, лучше справа)

Т.е Отображается смайл и рядом с ним значок (">" только повёрнутая вниз) в виде кнопки (НЕ КРИТИЧНО при полной реализации п.1) - такой переключатель в настройках тоже был бы многим удобен и полезен (или так или так).


4.
— Возможность (в настройках плагина) Расширять и сужать сетку смайлов, при раскрытии меню смайлов = отображать количество колонок и столбцов !!!
Т.е - (нажимаешь на смайл или подводишь мышь к нему) = > отображение сетки = (8 столбцов + 16 строк) или (16 столбцов + 16 строк) ..... например (1x2-20) (1-20x1) (2x20) (3x4) (4x3) (5x32) (16x20) итд итп! т.е произвольные параметры в настройках плагина (кол-во столбцов = " X " ; кол-во строк = " X ") !!!


8.
- Безопасность:

/wp-content/plugins/kama-wp-smile/smiles/*.gif
Папки:
/wp-content - есть возможность скрыть с
помощью сторонних плагинов
/plugins/ - есть возможность скрыть с помощью сторонних плагинов

/kama-wp-smile/smiles/*.gif - Название плагина даёт определить по коду страницы или открытию смайла в новом что установлен wordpress.

Рекомендую сменить название (Убрать wp из имени папки, .... не плагина!)
- Сделать вообще просто папку Smile или (Kama)

Например /wp-content/plugins/smile/smiles/*.gif - так точно сразу не определишь!!!
Например /wp-content/plugins/kama/smiles/*.gif - так точно сразу не определишь!!!ъ
т.е будет в коде отображаться /kama/smiles/*.gif
т.е будет в коде отображаться *****/*****/kama/smiles/*.gif

Спасибо за внимание.




== Changelog ==

= 1.9.13 =
* NEW: filter `kwsmile__insert_smile_space`.

= 1.9.11 =
* NEW: filter `kws_get_opt`.
* NEW: filter `kwsmile_pack_path_url`.

= 1.9.9 =
* NEW: `uninstall.php` now delete `wp-content/plugins/kama-wp-smile-packs` folder if it was created.

= 1.9.8.1 =
* FIX: Compatability with PHP 7.4

= 1.9.8 =
* FIX: special smiles match pattern fix. Ex: `=D` in `https://youtube.com/watch?v=DIhskiHOybw`

= 1.9.7 =
* CHG: now, the option `use_smiles` is not disabled. But it has no affect on content - it's disables with hooks on the fly.

= 1.9.6 =
* CHG: 'languages' folder deleted from the plugin

= 1.9.5 =
* CHG: version upgrade check everywhere - it's fast
* ADD: move localisation to translate.wordpress.org

= 1.9.3 - 1.9.4 =
* FIX: select "smile list position" option conflict in admin area

= 1.9.2 =
* ADD: select "smile list position" option

= 1.9.1 =
* FIX: Bug from 1.9.0
* CHG: a little default styles

= 1.9.0 =
* ADD: new pack of smiles: for dark background. New 200+ qip smiles
* ADD: Now you can add your own smiles, just create folder '/wp-content/plugins/kama-wp-smile-packs' and add your folder with smiles images.
* ADD: All code translated to english and ru_RU localisation was added...
* IMP: fixed vulnerabilities in code
* CHG: rename main functions: kama_sm_get_smiles_code() to kws_get_smiles_html(), km_convert_smilies() to kws_convert_smiles(). If you use this functions in your theme you need to fix your code.
* FIX: some minor fixes
* FIX: plugin uninstall


= 1.8.1 =
* FIX: unused option 'not_insert' deleted. It hides smiles on update...
* ADD: Option to set prefix and suffix for smile code. Now you can change: (:smile:) to *smile*
* CNG: Split main class to Kama_WP_Smiles{} and Kama_WP_Smiles_Admin{}

= 1.8.0 =
* CHANGE: smile wrapper tag was `*name*` become `(:smile:)` in order to correctly work with markdown.
* IMPROVE: regex improvements and now find/replace smiles to Img more then 50 times faster.

= 1.7.3 (18.06.2015) =
* FIX: some refactoring. And bugfix with ';)' (wink smile) when special char like &nbsp; end with ')' we got &nbsp;) where ';)' was replaced by smile.

= 1.7.1 (11.05.2015) =
* FIX: add "kws-wrapper" css class to front-end comment smiles block in order to change styles of the block out of admin settings page.

= 1.6.9 (8.05.2015) =
* ADD: ability to sort order of smiles. So, you can set order of how smiles will be shown in block..

= 1.6.8 (5.05.2015) =
* CHANGE: CSS styles changes. Now only additional css saves into DB and default options uses everywhere.

= 1.6.7 (5.5.2015) =
* FIX: Adaptation to Emoji icons added in WP 4.2

= 1.6.6.1 (6.09.2014) =
* Adaptation to WP 4.0

= 1.6.0 (24.01.2014) =
* Images in smile block now is not image and not downloading with page. It save HTML requests.
* Now select used smiles in admin panel more comfortable.
* New principle to add smile block to comment form.
* CSS styles and JS scripts now adding direct to HTML document. It save HTML requests.
* Added smile block in admin panel.
* On uninstall, plugin will remove all it settings and smiles code strings from posts and comments content.
* Improve plugin PHP code.

= 1.5.0 =
* Add ability to specify exceptions tags in which plugin wiil not replace smile sode to smile image.

