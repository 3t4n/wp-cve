=== SKT Themes Demo Import ===
Contributors: SKT Themes
Tags: import, content, demo, data, widgets, settings
Requires at least: 5.6
Tested up to: 6.3
Stable tag: 1.2
License: GPLv3 or later

Live demo content can be imported quickly in just one click including all widgets and settings.

== Description ==

Live demo content can be imported quickly in just one click including all widgets and settings. To establish a new website, this plugin provides a a basic layout plus it speed up the process of development.

In **APPEARANCE > SKT Import Content**, it will built up the page. 

The three files upload inputs will be presented if you are using the theme that do not have any import filed predefined.

Demo content XML file needs to be uploaded as first file is important, for the real demo import. 

The second one is not that much important. however for widgets import you will be asked for a WIE or JSON file. With the help of this you can create a file [Widget Importer & Exporter](https://wordpress.org/plugins/widget-importer-exporter/) plugin. 

The third option is also not mandatory. thus the customizer settings will be imported, select the DAT file that can be easily generated from [Customizer Export/Import](https://wordpress.org/plugins/customizer-export-import/) plugin (from the same theme if and only if the export file was created than only the customizer settings will be imported.). 

== Installation ==

**From your WordPress dashboard**

1. Go to the 'Plugins > Add New',
2. Check for 'SKT Demo Import' and Click on install.
3. Activate 'SKT Demo Import' via Plugins page.

You will get the access to actual import page if you have activated the plugin once in **Appearance -> Import Demo Content.**

== Frequently Asked Questions ==

= Where to find "Import Demo Content" page if once i have activated the plugin? =

In *wp-admin -> Appearance -> Import Demo Content* you will find the "import Demo Content" page.

= Where are the log files and the demo import files saved? =

To the default WordPress uploads directory, the demo import files will be saved. example is shown of that directory : `../wp-content/uploads/2016/03/`.

Even in the *wp-admin -> Media* section, the log file will be registered so that without any problem you can access it. 

= How demo imports can be predefined? =

For theme author this question is actually classified. You just have to add below mentioned code structure to predefine demo imports, with your theme's value (with the help of `skt-themes-demo-import/import_files` filter):

`
function SKT_import_files() {
	return array(
		array(
			'import_file_name'           => 'Demo Import 1',
			'import_file_url'            => 'http://www.your_domain.com/skt/demo-content.xml',
			'import_widget_file_url'     => 'http://www.your_domain.com/skt/widgets.json',
			'import_customizer_file_url' => 'http://www.your_domain.com/skt/customizer.dat',
			'import_preview_image_url'   => 'http://www.your_domain.com/skt/preview_import_image1.jpg',
			'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'your-textdomain' ),
		),
		array(
			'import_file_name'           => 'Demo Import 2',
			'import_file_url'            => 'http://www.your_domain.com/skt/demo-content2.xml',
			'import_widget_file_url'     => 'http://www.your_domain.com/skt/widgets2.json',
			'import_customizer_file_url' => 'http://www.your_domain.com/skt/customizer2.dat',
			'import_preview_image_url'   => 'http://www.your_domain.com/skt/preview_import_image2.jpg',
			'import_notice'              => __( 'A special note for this import.', 'your-textdomain' ),
		),
	);
}
add_filter( 'skt-themes-demo-import/import_files', 'SKT_import_files' );
`

You can set customizer import files, content import, and widgets. Also it is possible to justify the preview image, that can be used only if multiple demo imports are defined, by doing this the user will be able to check the difference between imports.

= How "Front page", "Posts page" and menu locations can be assigned authomatically after the importer is done? =

With the help of `skt-themes-demo-import/after_import` action hook you can do this. The code mentioned below that how it can appear like.

`
function SKT_after_import_setup() {
	// Assign menus to their locations.
	$main_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'primary-menu' => $main_menu->term_id,
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id = get_page_by_title( 'Home' );
	$blog_page_id  = get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'skt-themes-demo-import/after_import', 'SKT_after_import_setup' );
`

= What about using local import files (from theme folder)? =

The filter mentioned above example, you need to use same, but with some various array keys: `local_*`. The values have to be absolute paths (not URLs) to your import files. To use local import files, that reside in your theme folder, ensure that you are using the below code. Note: also ensure that the all import files are easy to read.

`
function SKT_import_files() {
	return array(
		array(
			'import_file_name'             => 'Demo Import 1',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'skt/demo-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'skt/widgets.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'skt/customizer.dat',
			'import_preview_image_url'     => 'http://www.your_domain.com/skt/preview_import_image1.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'your-textdomain' ),
		),
		array(
			'import_file_name'             => 'Demo Import 2',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'skt/demo-content2.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'skt/widgets2.json',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'skt/customizer2.dat',
			'import_preview_image_url'     => 'http://www.your_domain.com/skt/preview_import_image2.jpg',
			'import_notice'                => __( 'A special note for this import.', 'your-textdomain' ),
		),
	);
}
add_filter( 'skt-themes-demo-import/import_files', 'SKT_import_files' );
`

= How various "after import setups" can be managed in corresponding to which predefined import was actually choosen? =

By a theme author this question might be arrised  if they are looking to add various after import setups for various predefined demo imports. Consider that we have two demo imports predefined considering names: 'Demo Import 1' and 'Demo Import 2', now the after code import setup will be (using the `skt-themes-demo-import/after_import` filter):

`
function SKT_after_import( $selected_import ) {
	echo "This will be displayed on all after imports!";

	if ( 'Demo Import 1' === $selected_import['import_file_name'] ) {
		echo "This will be displayed only on after import if user selects Demo Import 1";

		// Set logo in customizer
		set_theme_mod( 'logo_img', get_template_directory_uri() . '/assets/images/logo1.png' );
	}
	elseif ( 'Demo Import 2' === $selected_import['import_file_name'] ) {
		echo "This will be displayed only on after import if user selects Demo Import 2";

		// Set logo in customizer
		set_theme_mod( 'logo_img', get_template_directory_uri() . '/assets/images/logo2.png' );
	}
}
add_action( 'skt-themes-demo-import/after_import', 'SKT_after_import' );
`

= Before the widgets get imported, can i add the same code? =

Yes it is possible to add same code before importing the widgets, use the `skt-themes-demo-import/before_widgets_import` action. Like in the example above, you can also target various predefined demo imports . Some example of the code is given below  `skt-themes-demo-import/before_widgets_import` action:

`
function SKT_before_widgets_import( $selected_import ) {
	echo "Add your code here that will be executed before the widgets get imported!";
}
add_action( 'skt-themes-demo-import/before_widgets_import', 'SKT_before_widgets_import' );
`

= Because I am a author of a theme, I am looking to modify the intro text of a plugin, So how it is possible? =

The intro text of the plugin can be modified by using the `skt-themes-demo-import/plugin_intro_text` filter:

`
function SKT_plugin_intro_text( $default_text ) {
	$default_text .= '<div class="SKT__intro-text">This is a custom text added to this plugin intro text.</div>';

	return $default_text;
}
add_filter( 'skt-themes-demo-import/plugin_intro_text', 'SKT_plugin_intro_text' );
`

To add some text in a separate "box", you should wrap your text in a div with a class of 'SKT__intro-text', like in the code example above.

= The generation of smaller images (thumbnails), how it can be deactivated during the content import =

This will enhance the time required to import the content (images), but it will only import the original sized images. With a filter, you will be able to deactivate or disable it. You just need to add the following code to your theme function.php file:

`add_filter( 'skt-themes-demo-import/regenerate_thumbnails_in_content_import', '__return_false' );`

= How to modify the title, location and other parameters of the plugin page? =

As a author of a plugin you might not like the "Import Demo Content" plugin's location page in *Appearance -> Import Demo Content*? with the below filter you can change it easily. Along with the location you will also be able to modify the title or the page/menu and other parameters too.

`
function SKT_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'SKT Demo Import' , 'skt-themes-demo-import' );
	$default_settings['menu_title']  = esc_html__( 'SKT Import Content' , 'skt-themes-demo-import' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'skt-themes-demo-import';

	return $default_settings;
}
add_filter( 'skt-themes-demo-import/plugin_page_setup', 'SKT_plugin_page_setup' );
`

= Because of a fatal error, I am not able to activate the plugin, what should I do? =

*Update: Admin error notice will be shown, mentioning that the minimal PHP version 5.6 is required for this plugin.*

It might shows the below error when you try to update the plugin:

*Plugin could not be activated because it triggered a fatal error*

Because your hosting server is making a use of outdated PHP version, this type of problem may arrise. This plugin needs to be updated with the PHP version of at least **5.6.x**, but we suggest to use version *5.6.x*. You can connect with your hosting company and ask them to update the PHP version for your site.


== Screenshots ==

1. screenshot1.png


== Changelog ==
**1.2**
- Compatibility with PHP version 8.
- Compatibility with WordPress 6.3.

**1.1**
- Compatibility with WordPress 6.0

**1.0.0**

- INITIAL RELEASE