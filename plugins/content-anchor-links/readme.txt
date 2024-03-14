=== Content Anchor Links ===
Contributors: xnau
Stable Tag: 1.6.3
Tags: anchor
Requires at least: 4.6.0
Tested up to: 5.5.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Donate link: https://xnau.com/work/wordpress-plugins/#donation-link

Easily add content anchors to your pages and posts

== Description ==

**Note: This plugin is not currently compatible with the Block Editor.**

This plugin adds a unique ID attribute to healings in your page and post content, and then makes those anchors available in the linking pop-up. This makes it very easy to add "jump" links to headings within the content.

= Using the Plugin =
This plugin doesn't use any settings or require any setup, just activate it and it's features are available.

The plugin works by adding a unique "id" to the headings in your content. When you open your content for editing, the plugin adds unique ids to all the h2 and h3 headings. After saving the content, those headings become available as "anchors" which can be linked to.

To create an anchor link, add the desired link text, select it and click the "add link" icon. If you begin typing the heading you want the link to jump to, the anchor link for that heading will appear in the search results. Choose that anchor to place your link.

If you want a link from another page to jump to a heading, you can add the anchor link (begins with a "#") to the regular page link and when that link is clicked on the user will be taken to that heading on the new page.

For example, let's say you have a page at `https://xnau.com/content-anchor-links/` that explains how to make a link list, and on that page is a tutorial with the heading "A Tutorial" If you look at the HTML for that page, you'll see that the heading has an "id", like this: `<h2 id="a-tutorial">A Tutorial</h2>` You can create a link that goes directly to that heading with a URL like this: `https://xnau.com/content-anchor-links/#a-tutorial`

== Installation ==

1. In the admin for your WordPress site, click on "add new" in the plug-ins menu.
2. Search for "participants database" in the WP plugin repository and install
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place `[pdb_record]` in your blog posts and pages to show the signup form
5. Additonal features and instructions can be found on the help tab of the plugin's settings page

**or**

1. Download the zip file
2. Click on "Add New" in the plugins menu
3. At the top of the "Add Plugins" page find and click the "Upload Plugin" button
4. Select the zip file on your computer and upload it
5. The plugin will install itself. Click on "activate" to activate the plugin

== Frequently Asked Questions ==

= What is the "linker"? =

The "linker" is the pop-up that appears when you select some text and click on the "add link" icon in the WordPress visual editor (or the "link" button in the text editor). When you begin typing in the linker, it tries to find content that matches the letters you typed. The Content Anchor Links plugin adds anchors from the current page or post to the list of suggestions that appears when the search happens. 

= I don't see the anchor links in the linker. =

First, be sure there are headings in your content. The plugin adds an ID attribute to all Heading 2 and Heading 3 elements in the content. Once you have the headings in place, save the page or post (either as a draft or as published content) which saves the ID attributes where the linker can find them. Now, select the text you want to add an anchor link to, then type in the first couple of letters of the heading you want the link to jump to. When the results come in, you'll see the anchor links at the bottom of the list. Select your anchor, and commit the link.

= Can I change the ID that the plugin put on one of my headings? =

Yes, as long as the ID is valid (see below) you can change the ID to anything you want. The plugin won't change any IDs that are already present.

= Can I create anchor links to images or other content? =

Yes, even though the plugin only adds IDs to headings, you can add an ID attribute to things like images, bullet lists, pullquotes, paragraphs, ...anything. Just add an ID attribute to the element and once the content is saved, the ID will show up in the linker as an anchor you can link to.  **Important:** the ID attribute value must be unique to the page it's on. If there is another element with the same ID, the anchor link won't work and you will possibly break other things as well.

= I added an ID attribute to something on the page, but it doesn't work. =

ID attributes have certain requirements in order to be valid and work as anchors. First, an ID attribute should start with a letter, and only contain letters, numbers, and '-','_','.'. That's it, no spaces, no other punctuation or symbols.

**Very important:** the ID must be unique. Having two of the same IDs on a page (this includes everything on the page, like menus, footers, etc.) will cause the ID to be invalid and can cause problems with page functionality, specifically javascript will often not work if a page has any duplicate IDs. Also, the HTML won't validate.

== Changelog ==

= 1.6.3 =
* compatibility update

= 1.6.2 =
* now supports ACF wysiwyg fields #15
* add fallback method if mb_string php module is unavailable #13

= 1.6.1 =
* id attribute now added to all headings h2 through h6
* tag name now shown in linker for clarity #11
* fixed error log warning message with empty query #12

= 1.6 =
* fixed bug where anchors weren't added to the linker in some cases #8
* anchors are now filtered by the search term in the linker #10
* better anchor slug generation for headings with special characters, punctuation, etc. #6

= 1.5 =
* fixed minor bugs

= 1.4 =
* improved the heading detection

= 1.3 =
* compatibility with utf-characters in headings
* fixed bug with invisible characters, whitespace in headings #3

= 1.2 =
* headings IDs: better handling of nested tags #2

= 1.1 =
* improved linker UI functionality

= 1.0 =
* initial public release


== Upgrade Notice ==

1.6 is a bugfix and feature release for all users