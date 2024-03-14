=== Adjustly Nextpage ===
Contributors: PSDCovers, Elran Oded
Donate link: http://www.psdcovers.com/adjustly-nextpage/
Tags: Multiple Page, Multi-Page, Multipage, Split Post, Split Page, Page Break, Post Break, Next Page, Nextpage, Page, Previous Page, Prevpage, Navigation, Navigate, Collapse, Condense, Paginate, Pagination, Multi-part, Continued, Cont, More, Section, HTML Toolbar, Visual Toolbar, jQuery, Javascript
Requires at least: 3.3
Tested up to: 3.3.1
Stable tag: 0.1

Enables existing Wordpress functionality to allow page breaks in your posts and pages.

== Description ==

Developed internally for our Adjustly theme, this plugin allows authors to create multi-page posts and pages to the WordPress Visual and HTML toolbar. This is not a post-to-post navigation feature, this is specifically about adding page breaks to a single, very long page/post. We did not create any new features with this plugin, it simply brings back an existing feature to the toolbar.

<h4>Try the Demo</h4>

If you're interested in seeing what a default installation of Adjustly Nextpage has to offer, we have a verbose sample post located here.

<a href="http://www.psdcovers.com/adjustly-nextpage/">http://www.psdcovers.com/adjustly-nextpage/</a>


<h4>Usage</h4>

Usage is identical to adding the <i>more</i> separator and looks as follows:

<code>&#60;!--nextpage--&#62;</code>
	
That's it.  Wordpress already has support for treating this as a page break when viewing the article and will add a page navigation tool at the end of each page or post. In fact, <code>&#60;!--nextpage--&#62;</code> has been around for at least 4 years and has been commented out in <i>wp-admin/quicktags.js</i> since at least version 1.5.1

Wordpress knows your article is not really 2 or more individual pages so your entire page/post is still edited as a single, very long post, on the backend.  You will not be jumping from section to section, nothing changes with regards to how you currently edit pages and posts.

<h4>Notes</h4>

None yet.

== Screenshots ==

1. Here is the Nextpage button highlighted for both the Visual and HTML toolbars.

== Installation ==

1. Upload `adjustly-nextpage` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a new page or post, the nextpage button will appear in both the Visual and HTML toolbar
1. Leave a comment at http://www.psdcovers.com/adjustly-nextpage/

== Frequently Asked Questions ==

= Can I use my existing WordPress theme? =

Yes! However, this plugin makes use of Qtags which were introduced in Wordpress 3.3 so the functionality is limited to Wordpress installations 3.3 and above.

= Hey, I disabled the plugin but my pages are still multi-paged!  WTF! (wow that's funny) =

This plugin simply surfaces a feature that has been subdued by the Wordpress team in order to streamline the Visual and HTML editors.  The decision to hide a range of buttons was to reduce the amount of clutter in the admin UI and only leave the absolute minimum that users needed to create content.  If you need more tools you could read the codex and learn what other shortcodes exist etc.

In short, once you start using the <code>&#60;!--nextpage--&#62;</code> in your posts you are stuck with the results.  It's no different that deciding you no-longer want any <code>&#60;b&#62;</code> tags to work after using them in every single page and post for months on end.

When you disable this plugin, the "next page" buttons will disappear from the HTML and Visual editor toolbars but any pages and post that made use of <code>&#60;!--nextpage--&#62;</code> will continue to be treated as multi-page posts.  Remember, this functionality is built into Wordpress and has nothing to do with this plugin. This plugin only makes the button visible in the editors.

= I am new to Social, do you have a Facebook page? =

Yes we do, but as our brand identity... <a href='https://www.facebook.com/PSDCovers'>PSDCovers</a>. Please like!

= How about Twitter? =

Yes! <a href='https://twitter.com/psdcovers'>@psdcovers</a> does the twitter.  please follow!

== Changelog ==

= 0.1 =
* Unhides the Next Page button in the visual editor
* Adds a Next Page button to the HTML editor

== Troubleshooting ==

1. Hey, I have 3.3+ installed but it's not working!

If you can't see any pagination tools at the end of your post then it's likely that your theme was not made to accomodate this feature.  The fix is easy, add the following code to your single.php file inside your theme:

<code>&#60;?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'adjustly' ), 'after' => '</div>' ) ); ?&#62;</code>

Add this code just prior to the comments_template() code or move it around to see where it suits you best.  You may want to add the name of your theme to the code (remove "adjustly" and put your theme name in its place).  

You will aslo have to add the above code to page.php as well (code is identical).

== Upgrade Notice ==

None yet
