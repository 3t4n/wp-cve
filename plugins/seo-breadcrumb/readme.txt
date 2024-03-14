=== SEO BreadCrumb ===
Contributors:redsnow_
Tags: breadcrumb, topic path, microdata
Requires at least: 3.1
Tested up to: 3.7
Stable tag: 1.0.2

Adds the function to display breadcrumbs navigation that supports HTML5 micorodata.

== Description ==
This plugin adds the function to display breadcrumbs (topic path) navigation that supports HTML5 micorodata. You can use display styles, lots of parameters of styles and original plugin hooks of breadcrumbs navigation, and you can customize navigations flexibly.
Forked "[Prime Strategy Bread Crumb](http://wordpress.org/plugins/prime-strategy-bread-crumb/ "WordPress breadcrumb plugin")" 

= Examples =
**Default**
Template Tag
`
<?php if (function_exists('bread_crumb')) bread_crumb(); ?>
`
Output Sample
`
<div id="breadcrumb" class="bread_crumb">
    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="http://www.example.com/" itemprop="url">
            <span itemprop="title">Home</span>
        </a>  &gt; 
    </div>
    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="http://www.example.com/?cat=2" itemprop="url">
            <span itemprop="title">Seminar</span>
        </a>  &gt; 
    </div>
    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="http://www.example.com/?cat=4" itemprop="url">
            <span itemprop="title">Tokyo</span>
        </a>  &gt; 
    </div>
</div>
`

**List types**
Template Tag
`
<?php if (function_exists('bread_crumb')) bread_crumb('type=list'); ?>
`
Output sample
`
<div id="breadcrumb" class="bread_crumb">
    <ul>
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="level-1 top">
            <a href="http://www.example.com/" itemprop="url">
                <span itemprop="title">トップページ</span>
            </a> &gt; 
        </li>
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="level-2 sub">
            <a href="http://www.example.com/?cat=2" itemprop="url">
                <span itemprop="title">Seminar</span>
            </a> &gt; 
        </li>
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="level-3 sub">
            <a href="http://www.example.com/?cat=4" itemprop="url">
                <span itemprop="title">Tokyo</span>
            </a> &gt; 
        </li>
    </ul>
</div>
`
= Special Thanks =


== Installation ==

1. Upload SEO Bread Cumb plugin folder you downloaded to the plugin directory.
2. Go to the plugin menu of Admin, and activate "SEO Bread Crumb" plugin.
3. Add a template tag "bread_crumb" of page navigation to the place where you would like to display breadcrumbs navigation in your theme. See below about parametes you can specify by template tags.

= Parameters =

**type**
If you specify "string", output strings instead of list.
Default: list

**home_label**
Texts displayed on front page.
Default: home

**search_label**
Texts displayed on search results.
Default: Search Results of "%s" (%s : search strings)

**404_label**
Texts displayed on 404 page.
Default: 404 Not Found

**category_label**
Texts displayed on categories.
Default: %s (%s is a category label.)

**tag_label**
Texts displayed on tags.
Default: %s (%s is a tag label)

**taxonomy_label**
Texts displayed on taxonomies.
Default: %s (%s is a taxonomy label)

**author_label**
Texts displayed on authors' page.
Default: %s (%s is author's name)

**attachment_label**
Texts displayed on attachments.
Default: %s (%s is an attachment's name)

**year_label**
Texts displayed on Yearly Archives.
Default: %s (%s is a year)

**month_label**
Texts displayed on Monthly Archives. 
Default: %s (%s is monthly-display-type specified on date format)

**day_label**
Texts displayed on Daily Archives.
Default: %s (%s is a day)

**post_type_label**
Texts displayed on custom post type archives.
Default: %s  (%s is custom post type label)

**joint_string**
If you specify "string" on type, strings between texts.
Default: " &amp;gt; " ( > )

**navi_element**
Name of wrapper elements. You can select div or nav.
Default: div

**elm_class**
Name of wrapper class. If no wrapper element and type is "list", name of "ul" class will be displayed.
Default: bread_crumb

**elm_id**
Name of wrapper id. iF no wrapper element and type is "list", name of "ul" id will be displayed.
Default: breadcrumb

**li_class**
Name of class added to li if type is "list".
Default: none (no class)

**class_prefix**
prefix added to each class.
Default: none (no prefix)

**current_class**
Name of class added to breadcrumbs navigation on current page where you see.
Default: current

**indent**
Number of tab indent. Default: 0

**echo**
Output or not. Default: true (output).
If you specify 0 or false, return values as PHP.

**disp_current**
current page Output or not. Default: false (not).
If you specify 0 or false, return values as PHP.

== Changelog ==
= 1.0.2 =
* Update japan language
* Update readme.txt

= 1.0.1 =
* Update japan language

= 1.0.0 =
* Opening to the public

== Screenshots ==
1. Output Sample of a breadcrumbs navigation

== Links ==
https://github.com/nobuhiko/seo-breadcrumb
