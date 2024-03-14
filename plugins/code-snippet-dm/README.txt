=== Code Snippet DM ===
Contributors: necroob
Donate link: devmaverick.com/donate
Tags: code, snippet, embed, code-snippet, display code
Requires at least: 4.0
Tested up to: 6.2
Requires PHP: 7.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Code Snippet DM enables you to display code snippets in a stylish way inside your content.

== Description ==

**Supports: Gutenberg, Elementor, TinyMCE and shortcode** 

Display your code snippets in a stylish way inside your content. The plugin uses shortcodes and also very intuitive TinyMCE interface.

**Starting with v2.0.3, the plugin offers support to be used via a custom Elementor Widget.**

**Starting with v2.0.1, the plugin offers support to be used via a custom Gutenberg block.**

It will continue to support the default way of adding the code snippet via shotcodes.

Code Snippet DM was inspired by the [Carbon](https://carbon.now.sh) project.
We created a simple way to display your code snippets but also have control over the way it appears, thus making it more stylish.
Also, on the front end in the top right you will find a `Copy Code` button that allows you to copy to clipboard the entired code snippet.

The plugin provides you with a TinyMCE button that will allow you to display the code or an option to use a shortcode.

You can open the shorcode `[dm_code_snippet]` and in the content inside and close it `[/dm_code_snippet]`.

= Elementor widget =

The Widget is called "Code Snippet DM" and can be found on the Basic list of Elementor Widget after you activate the plugin. Keywords that can be used to find it: code, snippet, dm. 

Once the widget is added in the Elementor editor, it can be customized via the sidebar settings that will appear for each widget. The sidebar will include all the settings that are available for the shortcode. Some of the settings will be applied in real time in your editor. 

The code styling (syntax highlight) and line numbers are only available on the front-end, in the editor you will not be able to preview the correct colors for the code or the line numbers. 

= Gutenberg block =

The block is called "Code Snippet DM" and can be found in the embed section of Gutenberg blocks. Keywords that can be used to find it: code, snippet, dm. 

Once the block is added in the Gutenberg editor, it can be customized via the sidebar settings that will appear for each block. The sidebar will include all the settings that are available for the shortcode. Some of the settings will be applied in real time in your editor. 

The code styling (syntax highlight) and line numbers are only available on the front-end, in the editor you will not be able to preview the correct colors for the code or the line numbers. 

= Shortcode Options =

There are a number of options that you can use in the shortcode:

* **background** with options **yes** or **no** will enable/disable the color background (Default **yes**)
* **background-mobile** with options **yes** or **no** will enable/disable the color background for mobile devices (Default **yes**)
* **bg-color** accepts any HEX, RGB or RGBA value to change the background color (Default **#abb8c3**)
* **theme** with options **light** or **dark** that changes the code editor theme (Default **dark**)
* **slim** with options **yes** or **no** that changes the code editor from default to a slim version, ideal for one-line code (Default **no**)
* **line-numbers** with options **yes** or **no** that will enable a left side line number for columns (Default **no**)
* **language** with options **clike**, **css**, **javascript**, **markup**,  **perl**, **php**, **python**, **ruby**, **sql**, **typescript**, **shell** will highlight the syntaxes based on what you select for your code (Default **php**)
* **wrap** with options **yes** or **no** will wrap the code or add a horizontal scroll bar to display it as it is (Default **no**)
* **height** allows you to input any value and will set the code snippet height to that value. Example: 500px. (Default is empty)
* **copy-text** this is used for the text shown on the copy button. If it's not added it will use the default option. (Default **Copy Code**)
* **copy-confirmed** this is used for the text shown after you click the copy button. If it's not added it will use the default option. (Default **Copied**)

**Example of shortcode:**

`[dm_code_snippet background="yes" background-mobile="yes" slim="no" line-numbers="no" bg-color="#abb8c3" theme="light" language="css" wrapped="no" height="500px" copy-text="Get the Code!" copy-confirmed="You have it!"]<pre>
.dm-code-snippet.dark {
  background: $default-bg;
  padding: 40px 35px 45px 35px;
  margin: 30px 0;
}</pre>
[/dm_code_snippet]`

**Important:** 
* If you use the shortcode directly, make sure to wrap the code in `<pre></pre>` as in the example above. 
* If you want to add HTML using the shortcode, you'll need to escape the HTML before pasting it in the shortcode. (You can easily find an escape tool online to convert the code)
* If you have code (not HTML) that uses '</' and you add it with the shortcode and not the and the TinyMCE button, you will need to escape '</' from your code in order to properly display it.

Use the TinyMCE button if you don't want to add the shortcode yourself. (see Screenshots)

== Installation ==

1. Upload `code-snippet-dm` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Start using the shortcode or TinyMCE button to display your code.

== Frequently Asked Questions ==

= Can I see a live demo? =

Sure! Check it out [here](https://apps.devmaverick.com/code-snippet/)

= The code indentation doesn't look right =

If you added code using directly the shortcode and not the TinyMCE option, make sure you wrapped your code into a `<pre></pre>` element like in the example above.

= I'm trying to add HTML and it's not working =

HTML code snippets can be added via the TinyMCE option, if you want to use the shortcode to display HTML content you'll need to escape the HTML content before adding it into the shortcode. 

= Can I use this plugin with WordPress Gutenberg editor? =

Yes. Starting from version 2.0.1 this plugin offers support for the Gutenberg editor. 

= Can I use this plugin with Elementor editor? =

Yes. Starting from version 2.0.3 this plugin offers support for the Elementor. 

= If I upgrade to v2.0.1, will my shortcodes continue to work? =

Yes. The plugin supports both the old way of adding code snippets via shortcodes and the new Gutenberg editor via a custom block. 

== Screenshots ==

1. DM Code Snippet TinyMCE button
2. How to add the code using the TinyMCE button
3. Example of shortcode added via TinyMCE
4. Front end example of a dark theme
5. Front end example of a dark theme with the background disabled
6. Front end example of a light theme
7. Slim option with one-line code
8. Line numbers enabled example
9. Gutenberg block with options in the sidebar
10. Gutenberg block for Code Snippet DM
11. Elementor widget with options in the sidebar
12. Elementor widget for Code Snippet DM

== Changelog ==

= 1.1 =
* Added option to customize the Copy button text and also the confirmation text after you click it.
* Fixed small content alignment issue.

= 1.2 =
* Added support for shortcodes inside the `[dm_code_snippet]`[your-shortcode]`[\dm_code_snippet]`.

= 1.3 =
* Added support for Ruby and Python.
* Added **slim** option that can be user for one-line code examples
* Fixed overall spacing issue

= 1.3.1 =
* Added support for Perl, SQL and TypeScript.
* Fixed margin issue where it might break the page layout and align the code element to the left

= 1.3.2 =
* Added support for 'height', allowing to set a max-height for the code snippet element.

= 1.3.4 =
* Fixed bug where on copy it will insert an extra empty line at the top of the code
* Added support for Bash/Shell

= 1.3.5 =
* Fixed recurring issue on Slim version where text was cut

= 1.3.6 =
* Added Line Numbers options to code snippets

= 2.0.1 =
* Added support for Gutenberg via custom block
* Improved performance on asset loading.
* Fix minor styling bugs on mobile

= 2.0.2 =
* Fixed character escape for all languages when added via the TinyMCE

= 2.0.3 =
* Added support for Elementor Builder by creating a Elementor Widget "Code Snippet DM"
