=== Editor Templates ===
Contributors: jim912
Tags: edit, post, page, template 
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 0.1.2

You can customize the editing page by templates like themes.

== Description ==

You can customize the editing page by templates like themes.

== Installation ==
1. Upload the Plugin Memorandum folder to the plugins directory in your WordPress installation
2. Go to plugins list and activate "Editor Templates". 
3. Go to setting page and configulation meta box, layout.
4. Make template file(file name:{post-type).php) in wp-content/editor-templates directory.

 - Sample template file is bundled in plugin's "template-sample" directory.

== Changelog ==
* **0.1.2**
 * fixed : tpl_custom tag output invalid option element.

* **0.1.1**
 * fixed : tpl_post_thumbnail doesn't work in WordPress 3.5

* **0.1.0**
 * fixed : upload.js tickbox url bug

* **0.0.9**
 * fixed : css & js url
 * fixed : refine upload.js
 * new  : Multi language support.

* **0.0.8**
 * new  : support {post_type}-{post_name} template
 * new  : support {post_type}-{post_name} css
 * new  : support {post_type}-{post_name} javascript

* **0.0.7**
 * fixed : post meta doesn't update in private post

* **0.0.6**
 * fixed : template tag "tpl_custom" dosn't save input data when pending status selected.

* **0.0.5**
 * Compatible up to WordPress 3.4

* **0.0.4**
 * bugfix Warning Error.

* **0.0.3**
 * Multisite Support.
 * change metabox slug.
 * deletable Custom image(tpl_custom:type media)

* **0.0.2**
 * Support "default" parameter.
 * refine metabox priority

* **0.0.1**
 * Initial release


== Screenshots ==
1. Sample edit page - title, category, excerpt, post-thumbnail
2. Sample edit page - custom fields
3. Sample template source