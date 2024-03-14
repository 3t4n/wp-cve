=== LuckyWP Glossary ===
Contributors: theluckywp
Donate link: https://theluckywp.com/product/glossary/
Tags: glossary, dictionary, wiki, terms, synonyms
Requires at least: 4.7
Tested up to: 5.4.2
Stable tag: 1.0.9
Requires PHP: 5.6.20
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The plugin implements the glossary/dictionary functionality with support of synonyms.

== Description ==

The "LuckyWP Glossary" plugin implements the glossary / dictionary functionality on the website: an alphabetical list of terms with definitions for these terms. The LuckyWP Glossary uses responsive web design and provides high compatibility with WordPress themes and plugins.

[youtube https://www.youtube.com/watch?v=FjQ86bG_qGA]

#### Synonyms

An unlimited number of synonyms can be added to each term. They are displayed in the list of terms equally with the main term and lead to the term page, which avoids the appearance of pages with the same content.

#### Alphabetical list of terms

A separate page displays a list of terms and their synonyms in alphabetical order with breakdown by letters. The list is displayed in several columns, but it uses responsive web design, which makes the page look correct on both desktops and mobile.

#### Highly compatible with WordPress themes

Thanks to responsive web design and correct integration with the theme, the glossary fits perfectly with most WordPress themes.

#### Highly compatible with WordPress plugins

When developing the plugin, the following approaches are used:

* terms are based on custom post type;
* the standard WordPress page is used as the archive page;
* to describe the term the standard WordPress editor is used.

Maximum use of the standard WordPress functionality provides high compatibility with most third-party WordPress plugins extending the editor’s capabilities, SEO plugins and etc.

#### User-friendly URL

For the term page an arbitrary URL structure is configured in a similar way to the configuration of the URL for posts.

#### Automatic placement of links to terms ([premium feature](https://theluckywp.com/product/glossary/))

The feature of automatic placement of links to the term page allows to improve the internal linking of the website, which will positively affect SEO.

Parameters of links placement can be changed in the plugin settings:

* choose in which types of posts to place links;
* сhoose how to place links: to all occurrences of the term or only to the first.

#### Customized access to the terms management ([premium feature](https://theluckywp.com/product/glossary/))

The plugin settings allows to specify which roles will have access to the terms management in the WordPress control panel.

== Installation ==

#### Installing from the WordPress control panel

1. Go to the page "Plugins > Add New".
2. Input the name "LuckyWP Glossary" in the search field
3. Find the "LuckyWP Glossary" plugin in the search result and click on the "Install Now" button, the installation process of plugin will begin.
4. Click "Activate" when the installation is complete.

#### Installing with the archive

1. Go to the page "Plugins > Add New" on the WordPress control panel
2. Click on the "Upload Plugin" button, the form to upload the archive will be opened.
3. Select the archive with the plugin and click "Install Now".
4. Click on the "Activate Plugin" button when the installation is complete.

#### Manual installation

1. Upload the folder `luckywp-glossary` to a directory with the plugin, usually it is `/wp-content/plugins/`.
2. Go to the page "Plugins > Add New" on the WordPress control panel
3. Find "LuckyWP Glossary" in the plugins list and click "Activate".

### After activation

After activation of the plugin you will be prompted to configure the archive page. We recommend to do this automatically by clicking the appropriate button. If you want to configure the archive page manually, you need to do the following:

* Create a new page.
* Specify in the settings of LuckyWP Glossary plugin the created page as the archive page.
* Add shortcode **[lwpglsTermsArchive]** to the page.

After the plugin is successfully installed and configured the menu item "Glossary" will appear in the menu of the WordPress control panel. Here you can manage the terms (add/edit/delete), and also you can change the plugin settings.

== Screenshots ==

1. Glossary page
2. Term page
3. Admin terms list
4. Edit screen of an glossary term
5. Glossary settings

== Changelog ==

= 1.0.9 — 2020-07-01 =
* Fixed: link for terms in "Draft" status was generated incorrectly.

= 1.0.8 — 2020-04-05 =
* Fixed: incorret work on use term permalink <code>%term%</code>.
* Minor code refactoring.

= 1.0.7 — 2019-11-09 =
+ Added option "Don't check shortcode [lwpglsTermsArchive] on glossary archive page".
* Fixed: in some cases warnings showed in admin panel.

= 1.0.6 — 2019-10-25 =
* Fix critical issues on the "Site Health" page.

= 1.0.5 — 2019-10-19 =
* Fix: in some cases, access to glossary in admin panel was checked incorrectly.
* Code refactoring.

= 1.0.4 — 2019-10-08 =
* Fix: notice on WP_DEBUG mode.

= 1.0.3 — 2019-08-01 =
* Implemented case-insensitive sort of terms on glossary archive page.
* Adapted for translate.wordpress.org.
* Added POT file.

= 1.0.2 — 2019-01-23 =
* Fix: Error on preview term page.

= 1.0.1 — 2018-07-09 =
* Adapted to WordPress 5.

= 1.0.0 — 2018-07-07 =
+ Initial release.