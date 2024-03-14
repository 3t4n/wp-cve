=== Footnotation ===
Contributors: vhbelvadi, Mista-Flo
Donate link: http://vhbelvadi.com/footnotation/
Tags: essay, academic, footnotes, endnotes, post, writing, editing
Requires at least: 2.0
Tested up to: 4.7.4
Stable tag: 1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An easy way to add footnotes to your posts.

== Description ==

Based on the now unsupported *fd-footnotes* plugin, *Footnotation* provides 
an easy way to add footnotes to your posts.

The syntax is retained from fd-footnotes which means it is natural, simple to 
understand, and degrades gracefully even if the plugin is removed. Bidirectional 
links are created automatically between a footnote and its position in the main 
content where the footnote was referenced from so that readers can refer to 
a footnote and return to their place in the text they were reading, with ease.

To make a footnote, type it inline within *arbitrarily numbered* square brackets:

     [7. Example footnote.]

The syntax is important. Each pair of square brackets must contain a number 
followed by a full stop and a space and then have the footnote text itself.

Neither do the numbers have to be in order nor do they have to be unique. All 
footnotes will be re-numbered automatically.

= Settings =

*Show footnotes only on single posts or pages:* This option hides the *list* of 
footnotes on the main blog page but retains their numbers which will link to the 
individual post/page URL directly.

*Collapse footnotes until they are clicked on:* This option hides footnotes 
initially, expanding them only when a footnote reference is clicked on.

*Match footnote marker colour to surrounding text:* This option makes sure the 
footnote reference marker matches the body text (academic style). The default
option is to have the footnote marker match the website’s default link colour 
(web style).

NB Anything from links to formatting to images may be included inside a 
footnote, *except square brackets*.

NB Multiple footnotes with the same text and number will leave you with incorrect 
footnotes. Make sure either that all footnotes have different content or, two or 
more footnotes with the same content all have different numbers.

== Installation ==

1. Copy the footnotation directory into wp-content/plugins or upload footnotations.zip via the 'Add new' plugins option.
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Include footnotes with ease while writing. (The number after the square bracket can be arbitrary.)
2. Footnote marker on the frontend. (You are offered two display styles to choose from.)
3. Footnotes displayed elegantly below the article.

== Changelog ==

= 1.2 =
* Front-end php notices corrected
* Minor code improvements
* With contributions by Mista-Flo

= 1.1 =
* Plugin icons added
* Now on github ~ https://github.com/vhbelvadi/Footnotation

= 1.0 =
* New options and styling added
* Initial fork off fd-footnotes

== Upgrade Notice ==

= 1.2 =
Maintenance update.

= 1.1 =
Introducing new plugin options and assets.

= 1.0 =
First release. Thanks for downloading, and thanks to John Watson for the original fd-footnotes plugin.
