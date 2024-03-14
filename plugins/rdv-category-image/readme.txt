=== RDV Category Image ===
Contributors: RDV InfoTech
Tags: Category Image, category images, categories images, taxonomy image, taxonomy images, taxonomies images, category icon, categories icons, category logo, categories logos, admin, wp-admin, category image plugin, categories images plugin, category featured image, categories featured images, feature image for category, term image, tag image, term images, tag images 
Requires at least: 5.0
Tested up to: 6.4.2
Stable tag: 1.0.8
License: GPLv2 or later

Add an image to a category or taxonomy. Display a category image using either a template tag or shortcode.

== Description ==

RDV Category Image plugin allows you to add an image to a category, tag, or any custom taxonomies. Please review the code snippets below to display a category image on the category page template or any page or post.

- Use template tag rdv_category_image_url(); with php echo function to get the category image url and then use it in an image tag.

    `<?php if(function_exists('rdv_category_image_url')){ echo rdv_category_image_url(); } ?>`

- Use this template tag rdv_category_image(); in the category template file to display the category image directly.

    `<?php if(function_exists('rdv_category_image')){ rdv_category_image(); } ?>`

- Use the shortcode in page or post or a page builder template to display a category image. The basic shortcode without attributes [rdv_category_image] will only work on the category template page to display a specific category image. Use shortcode attributes term_id and size to display a specific category image and size.
	
    `
    [rdv_category_image]
    [rdv_category_image term_id="10"]
    [rdv_category_image size="thumbnail"]
    [rdv_category_image term_id="10" size="thumbnail"]
    `

== Screenshots ==

1. Settings page.
2. Add New Category page.
3. Edit Category page.

== Installation ==

1. Go to your admin area and select Plugins and Add New from the menu.
2. Search for "RDV Category Image".
3. Click install.
4. Click activate.
5. Click on the wp menu page named RDV Category Image and select the categories/taxonomies you want to enable category image. 

== Changelog ==

= 1.0.8 =
Compatibility: Tested up to WordPress 6.4.2

= 1.0.7 =
Compatibility: Tested up to WordPress 6.2.2

= 1.0.6 =
Compatibility: Tested up to WordPress 6.1.1

= 1.0.5 =
Compatibility: Tested up to WordPress 6.1

= 1.0.4 =
Compatibility: Tested up to WordPress 6.0.1

= 1.0.3 =
Documentation Update

= 1.0.2 =
Documentation Update

= 1.0.1 =
Updated documentation

= 1.0.0 =
The First Release