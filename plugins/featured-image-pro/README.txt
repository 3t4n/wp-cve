
=== Featured Image Pro Post Grid===
Contributors: nomadcoder
Donate link: https://www.paypal.com/paypalme/nomadnewyork
Tags: featured image, thumbnail grid
Requires at least: 4.4
Tested up to: 6.4
Stable tag: 6.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display a Masonry Thumbnail Grid of Featured Images, including captions and excerpts.

== Description ==

An easy and full-featured widget and shortcode that displays featured image thumbnails in a responsive masonry grid. You may also display captions & excerpts.

### What's changed?

We are always working to improve our plugins. We use them ourselves. We’ve integrated our premium plugin into the free plugin to focus on other projects.

You can now display excerpts as html.

You can now edit saved grids (bug fixed).

### Basic Usage
Basic shortcode: [featured_image_pro]

Use the widget to generate shortcode by clicking on the panel labeled 'View/Copy Shortcode', under the categories panel 'Categories'. You can then copy and past the basic shortcode into a post or page and edit the options manually if you wish.

Learn more about the shortcode options and view examples by clicking on the link below and visiting our documentation website.  All options are listed in the sidebar on the website.  Click on any option to see posts about the option and then choose a post to view shortcode examples.

[View Documentation](http://plugins.shooflysolutions.com/featured-image-pro/ "Featured Image Pro Website")

#### Options

A list of options can be found in your settings menu by clicking on the Featured Image Pro menu item.

### Supports
Widget:
* Posts
* Custom Post Types & Pages
* Subcaptions - Date, Author, Comment count
* Select display based on category
* Randomize display on load
* Display Captions under thumbnail, or hover
* Add colors to make your grids unique to your theme
* Flexible default settings and powerful options
* Display excerpts
* Customize the excerpt word count
* Borders around items
* Fixed height captions and excerpts for even grids.
* Ability to control item width and image sizes
* Filters for customization

Advanced Shortcode:
* Custom taxonomies
* Extensive Shortcode generator
* Ajax paging and expansion
* Responsive grids
* Hover images
* Default Images
* Custom classes
* Advanced Wordpress queries using metadata, custom taxonomies, authors and more.
* Subcaptions from metadata, custom taxonomies and more with the ability to cast and title subcaptions.
* Visit our website for more information and to see examples!
* Filtering by categories, tags & custom taxonomies
* Create galleries using images from the media library


### Video Tutorials
Your First Shortcode
[youtube https://youtu.be/7OENRHcCP6Y]

Featured Image Pro 2 Options Demo
[youtube https://youtu.be/pPEVhHPl_Ns]

Colors in Featured Image Pro 2
[youtube https://youtu.be/QgaU6SabDbc]

Making Even Grids
[youtube https://youtu.be/ZqwJlhm1BN4]

### Note for Upgrades
If you notice any issues with your upgrade, please <a href="mailto:support@shooflysolutions.com">contact us</a> with a link and/or screenshot along with your specific shortcode.

### Requirements
* Your theme must be enabled for post thumbnails.



This plugin gives you access to a widget and shortcode that displays featured image thumbnails in a masonry grid. You may also display captions & excerpts

This free plugin is not designed to show several pages of post thumbnails, rather it is designed to allow you to embed thumbnails into any page or to display a subset of thumbnails on a page. To show more posts on a page than the default value Blog pages show at most, use a fixed value for posts_per_page or try setting the posts_per_page value to ’999′.

#### Logo CC BY 3.0
Squares graphic by [Freepik](http://www.flaticon.com/authors/freepik) from [Flaticon](http://www.flaticon.com/) is licensed under [CC BY 3.0](http://creativecommons.org/licenses/by/3.0/ "Creative Commons BY 3.0"). Made with [Logo Maker](http://logomakr.com "Logo Maker")



Visit [Our Website](http://plugins.shooflysolutions.com/featured-image-pro/ "Featured Image Pro Website") for documentation and examples.


== Installation ==

Download the Featured Image Pro Widget Plugin

How to install this plugin

Method 1:

Select Plugins in the admin panel. Select 'Add New' Search for 'Featured Image Pro'. Click on 'Install Now'. Once the plugin has been installed, click on 'Activate'

Download the plugin. In the WordPress admin panel, click on Plugins. Select Add New to get to the “Install Plugins” page. Click on browse and choose the downloaded file.

Click on the “Install Now” button to install the plugin. Once the plugin is installed, select “Activate Plugin”.

Method 3: (Advanced Users):

Unzip the file. Using FTP, upload the featured-image-pro folder to the wp-content/plugins folder in your wordpress directory. In your plugins folder, find the plugin and click on activate.



== Frequently Asked Questions ==

### When I chose large images sizes, they don't shrink past resive, and/or some of them are larger than the window. What can I do?
This is a Masonry issue that only occurs when fitwidth is on. Because fitwidth usually helps to make images show up beautifully, we
enable it by default. If you notice issues, especially if you're using large images and they don't resize correctly, you can turn off
"fitwidth", and that should make things better.

### How can I make my images resize to scale?
The easiest way to do this is to set max-width and max-height to a pixel amount. That will allow width and height to be auto by
default.

### You didn't answer my question/I have feedback. How can I get in touch with you?
If we haven't answered your question here, then drop us a line at support@shooflysolutions.com. We love (non-spam) mail!

### My grid items overlap when I resize the window
We have secret settings that can help with that. They are not in the widget. You must use the shortcode. First, enable the  layoutonresize setting. You can also change the resize timer which is currently set to  500 (ms). You can Adjust this value. When the window resizes, the plugin will wait until the resizetimer has stopped before laying out the grid.

== Changelog ==
= 5.15 = 
= Security patch on url
= Fix logging error when there are no terms
= 5.14 =
+ Upgrade for php
= 5.13 =
= Do not output scripts if rest api =
= 5.12 =
= Update for missing javascript =
= 5.11 =
Fix shortcode generator again
= 5.10 =
= Fix widget issue - widget throws errors on some pages =
= Fix bulk delete  - bulk delete doesn't work
= 5.00 =
= Update jQuery
= Fix shortcode generator
= 4.09
= back up last fix
=4.08
= Don't show notifications to non admins
=4.07
=Error in media (image gallery) grid
=4.06
= Remove debug dumps!!!
= 4.05
= Fix excerpt when excerpt field is not entered
= 4.04
= Add option to display html in excerpt (htmlexcerpt=true)
= Fix shortcode grid editer
= 4.03
= Tested for WP 5.0
= 4.02
= Replace code for notifications
= 4.01
= Change default order to desc.
= Fix error displaying read more type
= Update menu
= 4.00 Integrate advanced functionality to plugin
= 3.15 Update nag msg & tested through version
= 3.14 Fix permalinks (replace get_the_permalink with get_permalink)
= 3.13
= Version push to force upgrade from 3.03
= 3.10
= Name change to featured image pro post grid
= Enqueue dashicons for comments subcaption
= Add padding and width to the subcaptions & wrap so that they do not overflow.
= Add option to place a link under the post in button/span/div. Ex: 'Buy Now' button. These are new options available in the widget grid item section & shortcode. excerpt_custom_link_text  & excerpt_custom_link_type which can be button/span/div
= 3.03
= Fix for 404 missing file
= 3.02
= Fix double animations in widget
= 3.01
= Fix double animations
= 3.0
= POSTS/CUSTOM POSTS
= Add pages and custom post types
= Add override check for post type in case it's hard coded in the theme or elsewhere (Make sure that if another theme or plugin calls pre_get_posts we override it)

= APPEARANCE  default options were modified for ease of use but can be modified
= Add boxshadow option. This defaults to true and may change the appearance of your grids.
= padimage now defaults to true and may change the appearance of existing grids
= excerpt text aligns to the left and may change the appearance of existing grids
= Add optional ability to align captions, excerpts, subcaptions

= EXCERPTS:
= Default excerpt align to the left - this is changed from prior versions and may change the appearance of your grids
= Correctly display the post excerpt if there is one instead of always pulling the excerpt from the post content
= Fix style of 'read more' for excerpts
= Fix bug where 'read more' text is outside of the fixed excerpt height.

= CAPTIONS
= Default captions to align center
= Add author and comment count to subcaptions
= Add ability to align the subcaptions and the excerpts.
= Add optional horizontal line under captions (not for hover captions)
= Optionally add link to subcaptions - this defaulted to true before.
= Correctly cast date caption.


= 2.1.1
- The options page was missing in 2.1 until recently. This update fixes that.
= 2.1
- Change 'excerpt' to 'showexcerpts' for consistancy. excerpt will still work
- Fix missing options page.
- Add filters for query attributes and returned object
- Fix shortcode generation in the widget
- Fix caption width when max width < item width and fixed height caption.
= 2.0
- Lots and lots of exciting new features and bug fixes
- Add subcaptions for author and date
- Colors! Featured Image Pro now offers color options for items and item links.
- Added a padimage class toggle that adds image padding to the item. Can be overriden
- Added tooltips to some of the widget items
- Add an option to display tooltips on images in the grid (defaults to true)
- Fix hover captions when item width is wider than the image
- Excerpt padding/line-height changes
- Fix hover captions when item width is wider than the image. The hover caption should only cover the image.
- Added a padimage class for a different style option, this can be overriden in custom css
- Excerpt padding/line-height changes
- To make the widget easier to use, changed width/height values to accept integer values. Percentages, em, etc can still be used in the  but this will be considered to be for advanced users.
- Added the ability to disable tooltips on the image
- To make the widget easier to use, try to calculate columnwidth when it isn't set. If there is no gutter, columnwidth will always default to 1. If itemwidth is set, columnwidth defaults to itemwidth, otherwise columnwidth = average width of items when there is a gutter and 1 if there is no gutter.
- Add an option to display tooltips on images in the grid (defaults to true)
- Excerpt HR - excerpthr now defaults to false. Set excerpthr = true to correct.
- Call layout after resizing when height is auto to prevent overlap.
- Fix some style issues
- Todo: Add minimum widths and heights for images
= 1.5
- Set animate to default to false
- Add shortcode generator to the widget
- Fix javascript to allow more than one featured-image-pro widget to be edited in the customizer.
- Move Images above Grid Items
= 1.4
= Fix masonry transitions using css only
= Set Minimum value of number items in widget to zero
= Add animation option
= Add a margin above widget
= Add accordion to widget form
= Fix category selection in widget so that 'apply' button doesn't pop up
= Fix orderby - remove 'randomize' setting
= Add animate setting
= 1.3
= Fixed borders
= Added style interoperabilty with Twenty Seventeen
= Cleaned up directory structure
= 1.2
= Fix hover over image
= Replace <h3> with <div> for captions.
= 1.1
= Wordpress Release
= 1.0
= Initial Release

== Screenshots ==

1. Custom categories and different ordering method (by title).

2. Captions with a fixed height.

3. With medium-sized images for a less grid-like feel.

4. Hovering captions and no space between images.

5. Getting shortcode from the widget.

== Support Available ==

Need more? Customization is available. Contact sales@shooflysolutions.com for more information.

