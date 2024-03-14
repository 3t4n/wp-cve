=== DivUp Content ===

Contributors: bastywebb
Donate link: http://themeover.com/microthemer/
Tags: css grid, columns, column layout, split, classic editor
Requires at least: 2.8
Requires PHP: 5.6
Stable tag: trunk
Tested up to: 6.0.2

Wrap divs around classic editor content using divup shortcodes. Also works for Gutenberg, but you may prefer to use the group block.

== Description ==

**NOTE**: This plugin was originally created to solve the issue of splitting classic editor content into divs, using shortcodes. On Sept 27th, 2022, I tested to see how this plugin works with Gutenberg and it actually still works fine (because the divs are rendered AFTER Gutenberg does it's thing). But it's worth mentioning that there is a **Group** block in Gutenberg that serves the same purpose, and might be more intuitive than working with shortcodes for some users. That said, if you've disabled Gutenberg in favour of the classic editor, this plugin is still useful. And although I'm not adding new features, I will fix any bugs people report.

Now on to how it actually works:

Separate your WordPress post or page content into divs with (optional) **custom CSS classes and ids**. Adding the shortcode **[divup]** in between some content will split the content into 2 **separate divs**.

You can enter as many [divup] shortcodes to a post or page as you like. Great for creating **columns** of content for magazine style websites while keeping shortcode markup to an absolute minimum. DivUp Content never uses inline styles, but it does automatically give divs fiendishly clever classes like first, last, div-1, div-2, div-3, and div-odd, div-even, mul-3, mul-4 (multiple of 1,2,3,4 etc). You can then create your own style rules for the divs in style.css or with <a href="http://themeover.com/">Microthemer</a> (a visual design CSS plugin for customizing WordPress sites).

= 3 Column Example CSS =

The CSS for a **3 column layout** could be:

.divup-wrap { 
	display: grid;
	grid-template-columns: repeat(3, 1fr);
}

= 6 Column Example CSS =
For a **6 column layout** (with gaps), you would just change the CSS to:

.divup-wrap { 
	display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 2rem;
}

For more complex grid layouts with content spanning different numbers of columns and rows, you may find <a href="http://themeover.com/">Microthemer</a>'s visual controls for generating the CSS grid rules quite handy. Just select the .divup-wrap element when editing with Microthemer, and then position the grid items however you like using drag and drop.

== Features added ==

**NEWEST**:
Added support for adding custom HTML attributes to divs/spans. Use [startwrap custom='title: read this'] or [divup custom="data-src:image.jpg"] (the custom attribute can be applied to both [startwrap] and [divup] shortcodes.
You can also do[startwrap custom='title: read this|required:some value'] (seperate with a | pipe character for multiple)
When using [divup], two divs will be created. To apply custom attributes to the first and second divs use a comma e.g.[divup custom="data-src:image1.jpg, data-src:image2.jpg"]

**NEWER**:
Added support for span elements. Use [startwrap type='span'].
Added support for no superfluous child elements. Use [startwrap parent=0] or [startwrap parent='false'].

**NEW**: You can also add multiple [startwrap] and [endwrap] shortcodes to control how the divs are wrapped in a wrapper div. This overrides the original auto-wrapper functionality if you choose to use it.**


The best way to understand how DivUp Content works (including the advanced aspects) is to **copy and paste the ONE of the following dummy content examples below into a post or page and then inspect the html with your browser (Right-click > Inpsect) ** - paying attention to the CSS classes it automatically applies to the divs.


== Dummy Content 1 ==

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup id='id-a, id-b' class='class-a, class-b class-b2']

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup class='my-class diff ']

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup id='my-id' class='diff my-class2']

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup class='diff']

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.



== Dummy Content 2 ==

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[startwrap class="my-wrap-class another-wrap-class"]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup id='id-a, id-b' class='class-a, class-b class-b2']

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[endwrap]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[startwrap id="my-wrap-id" class="hello-wrap"]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup class='my-class diff ']

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup id='my-id' class='diff my-class2']

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup class='diff']

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[divup]

Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page. Some content on this page.

[endwrap]



== Installation ==


1. Upload `divup` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the [divup] shortcode in your posts or pages:


== Frequently Asked Questions ==

= Is This Plugin Supported? =
Yes. Just send an email, and I'd be happy to help. Or, if you think the answer to your question might benefit others, please post your question in the <a href="http://wordpress.org/tags/divup-content?forum_id=10">DivUp Content Forum</a> (which I actively monitor).  

== Screenshots ==


1. This is an example of how you could use the [divup] shorcode in your markup (note: the custom classes and ids aren't necessary for creating the following grid layouts, they're just there to show you that you can add custom classes and ids if you want to).
2. Given the markup example in the previous screenshot, this is how DivUp Content would wrap your content in divs with automatic and custom classes and ids.

== Changelog ==

2.7
* Fixed issue with comma separated divup attributes e.g. [divup id="first-div-id, second-div-id"] (DivUp was outputting an extra space in the id attribute).

2.6
* Added support for [startwrap custom='title: read this']. See explanation in description.

2.5
* Fixed undefined issue when custom classes or ids aren't used in [divup] shortcodes.

2.4
* Removed PHP4 compatibility, which fixed strict standards constructor error.
* Added support for span elements. Use [startwrap type='span']. The wrapper and all child elements will be spans instead of div elements.
* Added support for wrapper element with no superfluous child elements. Use [startwrap parent=0] or [startwrap parent='false'].

2.1 - The ids given to divs had a trailing space in them. This is invalid and so CSS targeting failed to work.

With version 2.0, you can now control when the wrapper div that wraps all the divup divs starts and ends. You can also separate the divs into multiple wrapper divs. The automatic ordinal classes will start from 1 again for each wrapper (although there is also a gloabl count of all the divs on the page). Use the new [startwrap] shortcode to begin your wrapper and the new [endwrap] shortcode to end your wrapper. Remember that every [startwrap] shortcode requires an accompanying [endwrap] else you are likely to break the layout of your page. The new [startwrap] and [endwrap] shortcodes are optional, if you don't use them DivUp Content will still function as it always has.
