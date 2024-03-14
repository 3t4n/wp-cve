=== Auto Tag Generator ===
Contributors: fmzac
Donate link: http://fmzac.blogspot.com/2013/10/auto-tag-generator.html
Tags: tag, auto tag, tag generator
Requires at least: 3.0.1
Tested up to: 3.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically creates tags from post title, on update or publish.

== Description ==

This plugin automatically converts keywords in a post title to tags upon saving. It includes a user-editable list of words you want the plugin to ignore, which by default includes the more obviously-useless words like "I" or "wasn't." You can also reset the list back to defaults by deleting/empty the current list.

* Converts keywords in post titles to tags
* Includes user-editable list of words to be ignored
* Ignore list can be reset to default at any time
* Converts on save, not publish.
* Does not convert if there are already tags assigned.

Multi-blog adminstrators take note: this plugin is especially helpful if you're building a community-based site where tagging is important and your bloggers are not always diligent about tagging.

== Installation ==

1. Upload `AutoTagGenerator` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Open the Settings->Auto Tag Generator menu to configure.
4. That's it. Every new post will now be automatically tagged.

== Frequently Asked Questions ==

= Does this plugin convert all words in the title? =
No. There is a user-configurable list of ignore words, pre-populated with a host of common words, which can be used to fine-tune the resulting tags.

= Will this plugin overwrite my existing tags? =
No. The plugin checks for the existence of tags, and if there are none, writes them based on the title.

= When are the tags added? At publish or save? =
Tags are added to the post when it is saved, whether as a draft, update or by publishing, but not when the post is autosaved.

= Is there a way to reset the list of ignored words back to the original? =
Yes. Simply delete the current list of ignore words, and plugin will replace the list with its default ignore words collection (fmzac_ignore_words.txt)

= Will this plugin put tags on my old posts? =
Yes and No. It will not handle the process automatically, but if there is no tag assigned before then upon updating post, it will generate tags.

== Screenshots ==

1. screenshot 1
2. screenshot 2
3. screenshot 3
4. screenshot 4

== Changelog ==

= 1.0 =
Current version

== Upgrade Notice ==

= 1.0 =
Current version
