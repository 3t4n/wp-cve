=== Bulk Term Editor ===
Contributors: yuyahoshino
Donate link: 
Tags: term, taxonomy, register, add, edit
Requires at least: 4.0
Tested up to: 6.2.2
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

You can register or edit terms in bulk. Copy cells in the spreadsheet, all that remains is to paste to this plugin.

== Description ==

How it works:

1. Click 'Tools > Bulk Term Editor'.

2. Select a taxonomy.
If you selected a taxonomy which has terms, it will read those into the field.

3. Get your data ready.
Prepare your data by spreadsheet such as Excel.
If you selected a taxonomy which has terms at the step 2, it's easy to copy that data into the spreadsheet.

4. Copy the cells.
You may copy all lines, but the more lines there are, the more times spends.
Copying the minimum necessary lines is the best, but safer to copy all lines.

5. Paste to this plugin.
Paste it into the 'Term' field of this plugin.

6. Execute.
Clicking the 'Edit' button executes it.

= Delete =

Entering a '*' in beginning of line will delete.

= Change the term slug =

Entering a '>New slug' after the term slug will change the term slug.

= Add =

When the term slug is a blank or new, add.

== Installation ==

1. Upload 'bulk-term-editor' folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. This is the initial screen.

== Changelog ==

= Version 1.1.3 =

* Updated translations.

= Version 1.1.2 =

* Verify operation with the latest version
* Change plugin information

= Version 1.1.1 =

* Fixed a bug related to 'term_order'.

= Version 1.1.0 =

* This plugin became able to update 'term_order'.
* And enable 'orderby=term_order' by 'WP_Term_Query'.

= Version 1.0.1 =

* Updated Japanese translation.

= Version 1.0 =

* Initial release.

== Upgrade notice ==

