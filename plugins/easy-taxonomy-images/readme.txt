=== Easy Taxonomy Images ===
Contributors: wpdevstudio
Tags: Category Image, Category Images, Categories Images, taxonomy image, taxonomy images, taxonomies images, category icon, categories icons, category logo, categories logos, admin, wp-admin, category image plugin, categories images plugin, category featured image, categories featured images, feature image for category, easy taxonomy images
Requires at least: 3.0.1
Tested up to: 4.3.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Have featured and cover images for tags, categories and custom taxonomies

== Description ==

Easy Taxonomy Images is very light plugin which allows you to have featured and or cover images for category tags and custom taxonomies

`Features`

Extremely lightweight

Support cover images & feature images for all category tags & custom taxonomies

easy to use settings


`How to use - Backend`

Install and activate the plugin

Navigate to Dashboard > Easy Taxonomy Images

Disable Taxonomies for which you do not need featured and or cover Image (there is option to keep both cover & feature or any of these or none of these for each taxonomy )

Set default feature Image & Cover Image

Now go to any of your chosen taxonomy edit/add page, there you can add feature and or cover image as enabled

`How to use - Frontend`

Easy Taxonomy Image provides you with two functions :

`taxonomy_featured_image` place it on any of the category templates it will display the feature image of the current category

`taxonomy_cover_image` place it on any of the category templates it will display the cover image of the current category

To fetch featured or cover image of any particular term just pass `term_id` as first argument in the function, like so :

`taxonomy_featured_image(34);` - display featured image term with id = 34

== Installation ==

1. Upload plugin zip contents to wp-contents/plugin directory and activate the plugin

== Screenshots ==

1. Settings - Disable taxonomies in feature image & category image section if you dont need for that particular taxonomy and also configure default images
2. Taxonomy Edit page - you can add remove / update feature & cover images here
3. Taxonomy Quick Edit page
4. Taxonomy Add page

== Changelog ==

= 1.0 =
* Initial Version.

= 1.0.1 =
* Removed depricated filters.


