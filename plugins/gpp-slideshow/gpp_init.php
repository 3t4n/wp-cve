<?php

/*-----------------------------------------------------------------------------------*/
/* Register new post type */
/*-----------------------------------------------------------------------------------*/

add_action( 'init', 'gpp_gallery_create_type' );

function gpp_gallery_create_type() {

	$gallery = get_option('gpp_gallery');

	if ( empty ( $gallery['gallery'] ) )
		$sslug = "Gallery"; // if single name is empty
	else
		$sslug = $gallery['gallery']; // single name
	$sslugl = strtolower($sslug); // single name lowercase

	if ( empty ( $gallery['galleries'] ) )
		$slug = "Galleries"; // if plural name is empty
	else
		$slug = $gallery['galleries']; // plural name
	$slugl = strtolower($slug); // plural name lowercase

	$plugin_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	$plugin_dir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

	register_post_type('gallery',
		array(
			'labels'			=> array(
				'name'								=> __(''.$slug.''),
				'singular_name' 			=> __(''.$sslug.''),
				'add_new'							=> __('Add '.$sslug.''),
				'add_new_item'				=> __('Add '.$sslug.''),
				'new_item'						=> __('Add '.$sslug.''),
				'view_item'						=> __('View '.$slug.''),
				'search_items' 				=> __('Search '.$slug.''),
				'edit_item' 					=> __('Edit '.$sslug.''),
				'all_items'						=> __('All '.$slug.''),
				'not_found'						=> __('No '.$slug.' found'),
				'not_found_in_trash'	=> __('No '.$slug.' found in Trash')
			),
			'taxonomies'	=> array('gallery_collections'),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array( 'slug' => ''.$sslugl.'', 'with_front' => false ),
			'query_var' => true,
			'supports' => array('title','revisions','thumbnail','author'),
			'menu_icon'			=> ''.$plugin_url.'/img/icon.jpg',
			'has_archive' => ''.$slugl.''
		)
	);
}


/*-----------------------------------------------------------------------------------*/
/* Register taxonomy for new post type */
/*-----------------------------------------------------------------------------------*/

add_action( 'init', 'gpp_gallery_taxonomy', 0 );

function gpp_gallery_taxonomy() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Collections', 'taxonomy general name' ),
    'singular_name' => _x( 'Collection', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Collections' ),
    'all_items' => __( 'All Collections' ),
    'parent_item' => __( 'Parent Collection' ),
    'parent_item_colon' => __( 'Parent Collection:' ),
    'edit_item' => __( 'Edit Collection' ),
    'update_item' => __( 'Update Collection' ),
    'add_new_item' => __( 'Add New Collection' ),
    'new_item_name' => __( 'New Collection Name' ),
    'menu_name' => __( 'Collections' ),
  );

  register_taxonomy('gallery_collections',array('gallery'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'gallery_collection' ),
  ));
}

/*-----------------------------------------------------------------------------------*/
/* Add options page for new post type menu */
/*-----------------------------------------------------------------------------------*/

// Add options to admin_init
add_action('admin_init', 'gpp_gallery_options_init' );
// Add menu link to admin_menu
add_action('admin_menu', 'gpp_gallery_add_options_menu');

// Add the sub menu to the new post type
function gpp_gallery_add_options_menu() {
	add_submenu_page('edit.php?post_type=gallery', 'Options', 'Options', 'manage_options', 'gallery-options', 'gpp_gallery_options_page' );
}

// Init plugin options to white list our options
function gpp_gallery_options_init(){
	register_setting( 'gpp_gallery_options', 'gpp_gallery', 'gpp_gallery_options_validate' );
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function gpp_gallery_options_validate($input) {

	// Safe text with no HTML tags
	$input['time'] =  wp_filter_nohtml_kses($input['time']);
	$input['speed'] =  wp_filter_nohtml_kses($input['speed']);
	$input['gallery'] =  wp_filter_nohtml_kses($input['gallery']);
	$input['galleries'] =  wp_filter_nohtml_kses($input['galleries']);
	$input['pages'] =  wp_filter_nohtml_kses($input['pages']);

	return $input;
}

// Function to print the content of the sub menu page
function gpp_gallery_options_page(){ ?>

  	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>

		<?php
			if ( isset ( $_GET['settings-updated'] ) && ( $_GET['settings-updated'] == true ) )
				echo "<div id=\"message\" class=\"updated\"><p>Permalinks must be updated each time you change slug names. <a class=\"button\" href=\"options-permalink.php\">Update Permalinks Now &raquo;</a></p></div>";
		?>

		<h2><?php gpp_gallery_slug(); ?> Options</h2>
		<form action="options.php" method="post">

			<?php settings_fields('gpp_gallery_options'); ?>
			<?php $options = get_option('gpp_gallery'); ?>

			<?php
				if (get_option('permalink_structure') <> '') { ?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="gpp_gallery[time]"><?php _e( 'Slideshow Display Time' ); ?></label></th>
					<td><input type="text" name="gpp_gallery[time]" value="<?php echo $options['time']; ?>" id="gpp_gallery[time]" /> <span class="description"><?php _e( 'Default value: 3500 (milliseconds)' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="gpp_gallery[speed]"><?php _e( 'Slideshow Speed' ); ?></label></th>
					<td><input type="text" name="gpp_gallery[speed]" value="<?php echo $options['speed']; ?>" id="gpp_gallery[speed]" /> <span class="description"><?php _e( 'Default value: 1000 (milliseconds)' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="gpp_gallery[captions]"><?php _e( 'Captions' ); ?></label></th>
					<td><input id="gpp_gallery[captions]" name="gpp_gallery[captions]" type="checkbox" value="1" <?php if (isset($options['captions'])) checked( '1', $options['captions'] ); ?> /> <span class="description"><?php _e( 'Check to show image captions by default' ); ?></span></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="gpp_gallery[pages]"><?php _e( 'Slideshow on all pages' ); ?></label></th>
					<td><input id="gpp_gallery[pages]" name="gpp_gallery[pages]" type="checkbox" value="1" <?php if (isset($options['pages'])) checked( '1', $options['pages'] ); ?> /> <span class="description"><?php _e( 'Check to show GPP slideshows on all category and archive pages.' ); ?> </span></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="gpp_gallery[gallery]"><?php _e( 'Singular Slug' ); ?></label></th>
					<td><input type="text" name="gpp_gallery[gallery]" value="<?php echo $options['gallery']; ?>" id="gpp_gallery[gallery]" /> <span class="description"><?php _e( 'Default value: Gallery. Changes titles and url structure.' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="gpp_gallery[galleries]"><?php _e( 'Plural Slug' ); ?></label></th>
					<td><input type="text" name="gpp_gallery[galleries]" value="<?php echo $options['galleries']; ?>" id="gpp_gallery[galleries]" /> <span class="description"><?php _e( 'Default value: Galleries.  Changes titles and url structure.' ); ?></span></td>
				</tr>

			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>

			<?php gpp_gallery_credits(); ?>

			<?php } else { ?>
			<div id="message" class="error"><p>Before you can proceed, please set your Permalinks to something other than the Default setting.  <a class="button" href="options-permalink.php">Change Permalinks Now &raquo;</a></p></div>
			<?php } ?>

		</form>

		</div>
<?php
}


/*-----------------------------------------------------------------------------------*/
/* Add instructions page for new post type menu */
/*-----------------------------------------------------------------------------------*/

// Add menu link to admin_menu
add_action('admin_menu', 'gpp_gallery_add_instructions_menu');

// Add the sub menu to the new post type
function gpp_gallery_add_instructions_menu() {
	add_submenu_page('edit.php?post_type=gallery', 'Instructions', 'Instructions', 'manage_options', 'gallery-instructions', 'gpp_gallery_instructions_page' );
}

// Function to print the content of the sub menu page
function gpp_gallery_instructions_page(){ ?>

  	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>

		<h2>Instructions for using <?php gpp_gallery_slug(); ?></h2>

		<h3>Basic Usage</h3>
		<p>Activate the plugin and follow the prompts for configuring the plugin and updating your permalinks.  Visiting the permalinks page registers the new Gallery post type.  Next, visit the Gallery -> Add New page located in the left menu column, give your Gallery a title, use the Upload button to add images, click Save and exit the upload box.  When you save of preview the Gallery, your thumbnail images will appear below the upload button.</p>
		<p>Please note: It's best to not add Galleries to Sidebars or Footers, because the width of the Gallery will extend beyond the width of the Sidebar and Footer columns.  This plugin requires WordPress 3.1. and works best with <a href="http://graphpaperpress.com/themes/">Graph Paper Press themes</a>.</p>

		<h3>Adding Galleries to Posts and Pages</h3>
		<p>This plugin replaces the default [gallery] shortcode in WordPress with the new slideshow gallery display.  All existing WordPress instances of [gallery] will be replaced.  To add slideshow galleries to new Posts or Pages, just upload images as usual, and be sure to click the Insert Gallery button after uploading.</p>

		<h3>Adding Galleries to Widget Areas</h3>
		<p>This plugin adds a new widget for easily inserting specific Galleries into a widgetized area.  Your theme MUST support Widgets and it also MUST support widgets that span the full-page width.  Otherwise, the gallery will break your page design.  This plugin works best with the <a href="http://graphpaperpress.com/themes/base">Base theme framework</a> for WordPress.  It adds a homepage widget, so you can easily add specific galleries to the homepage.  You can also install the <a href="http://wordpress.org/extend/plugins/gpp-base-hook-widgets/">GPP Base Hook Widgets</a> plugin, which will add 12 new widgetized areas to the Base theme.</p>

		<h3>Featured Images</h3>
		<p>Always assign a Featured Image for each Gallery.  This Featured Image will become the image that represents the Gallery on the Archive page.</p>

		<h3>Image Dimensions</h3>
		<p>The theme files packaged with this plugin use fluid, percentage-based css widths to integrate as best as possible with a wide variety of themes.  We do make some initial suggestions for image widths when adding new posts, but ultimately you must determine the best image sizes that fit your theme best.  This plugin doesn't resize any images you add; It merely displays whatever size images you tell it to use.</p>

		<h3>Template Files</h3>
		<p>This plugin contains two pre-built template files:<p>
		<ul>
			<li><strong>single-gallery.php</strong> - Used to display single entries.</li>
			<li><strong>archive-gallery.php</strong> - Used to display an archive page listing all single entries.</li>
		</ul>
		<p>These template file are located in the plugin's theme folder.  If you want to customize the default template files that ship with this plugin and protect your modifications from being lost when plugin updates are released, please follow these steps:</p>
		<ol>
			<li>Copy single-gallery.php and archive-gallery.php into your active theme folder.</li>
			<li>Modify the template files as needed.</li>
		</ol>

		<h3>Troubleshooting</h3>
		<ol>
			<li><strong>Slideshow Images Hidden</strong> - Some themes add CSS to img tags that conflict with the slideshow.  Open up your theme's style.css file and add this to the very bottom: <code>#gpp_slideshow { height: 600px }</code>  Change 600 to the maximum height that you want to allocate for the slideshow.</li>
			<li><strong>Vertical Images</strong> - The slideshow area will automatically resize to fit the maximum height and width of all of the images in the gallery.  If your gallery contains vertical images and you want to constrain the height of the slideshow, you will need to crop your vertical images to the height of your horizontal images.  Crop them in an image editor and then upload them into WordPress.</li>
			<li><strong>Slideshow won't play</strong> - This is caused by a javascript conflict.  Deactivate your plugin, one-by-one, to find the culprit.  If your theme already has slideshow functionality built into it, this plugin might conflict with it, too.  It is recommended that you use this plugin with the <a href="http://graphpaperpress.com/themes/base/">Base theme framework</a> from <a href="http://graphpaperpress.com">Graph Paper Press</a>.</li>
			<li><strong>Slideshow images stacked on top of each other</strong> - This is caused by a javascript conflict.  Deactivate your plugin, one-by-one, to find the culprit.  If your theme already has slideshow functionality built into it, this plugin might conflict with it, too.  It is recommended that you use this plugin with the <a href="http://graphpaperpress.com/themes/base/">Base theme framework</a> from <a href="http://graphpaperpress.com">Graph Paper Press</a>.</li>
		</ol>

		</div>

		<?php gpp_gallery_credits(); ?>

<?php }

/*-----------------------------------------------------------------------------------*/
/* Add single template for new post type */
/*-----------------------------------------------------------------------------------*/

add_action('template_redirect', 'gpp_gallery_single_template', 5);

function gpp_gallery_single_template() {

	$post_type = get_query_var('post_type');

	if($post_type == 'gallery') {

		// Only add stylesheet on specific post type templates
		add_action('wp_print_styles', 'gpp_gallery_stylesheet');
		gpp_gallery_stylesheet();

		if (file_exists(STYLESHEETPATH . '/single-' . $post_type . '.php')) return;
		load_template(GPP_GALLERY_PLUGIN_DIR . '/theme/single-' . $post_type . '.php');
		exit;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Add archive template for new post type */
/*-----------------------------------------------------------------------------------*/

add_action('template_redirect', 'gpp_gallery_archive_template', 4);

function gpp_gallery_archive_template() {

	$post_type = get_query_var('post_type');
	if ($post_type == '')
		$post_type = 'gallery';

	if (is_post_type_archive($post_type) && $post_type == 'gallery') {

		// Only add stylesheet on specific post type templates
		add_action('wp_print_styles', 'gpp_gallery_stylesheet');
		gpp_gallery_stylesheet();

		if (file_exists(STYLESHEETPATH . '/archive-' . $post_type . '.php')) return;
		load_template(GPP_GALLERY_PLUGIN_DIR . '/theme/archive-' . $post_type . '.php');
		exit;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Add taxonomy template for new post type */
/*-----------------------------------------------------------------------------------*/

add_action('template_redirect', 'gpp_gallery_taxonomy_template', 3);

function gpp_gallery_taxonomy_template() {

	if ( is_tax('gallery_collections') ) {

		// Only add stylesheet on specific taxonomy-collection.php template
		add_action('wp_print_styles', 'gpp_gallery_stylesheet');
		gpp_gallery_stylesheet();

		if (file_exists(STYLESHEETPATH . '/taxonomy-gallery_collection.php')) return;
		load_template(GPP_GALLERY_PLUGIN_DIR . '/theme/taxonomy-gallery_collection.php');
		exit;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Admin messages if specific GPP themes aren't installed */
/*-----------------------------------------------------------------------------------*/

//add_action('admin_notices', 'gpp_gallery_admin_message');

function gpp_gallery_admin_message() {

	// installed and required theme arrays
	$installed_themes = array();
	$required_themes = array('Base', 'Modularity', 'F8 Lite', 'Modularity Lite', 'F8 Static', 'Workaholic', 'Fullscreen', 'Gridline', 'Berlin');
	//$required_themes = array('Testing Theme', 'Another Test Theme');
	$themes = get_themes();
	$theme_names = array_keys($themes);

	// create an array of install theme names
	foreach ($theme_names as $theme_name) {
		$name = $themes[$theme_name]['Name'];
    if($name){
      $installed_themes[] = $name;
    }
	}

	// message to show in admin if no required themes are found
	if (!array_intersect($installed_themes, $required_themes)) {
		echo '<div id="message" class="updated"><p>The GPP Slideshow plugin works best with <a href="http://graphpaperpress.com" title="visit Graph Paper Press">Graph Paper Press themes</a>, like <a href="http://graphpaperpress.com/themes/base/" title="Base theme by Graph Paper Press">Base</a> or <a href="http://graphpaperpress.com/themes/modularity/" title="Modularity theme by Graph Paper Press">Modularity</a> or any of their respective child themes.  It will likely work with other themes, too, but you might have to change your Media Settings for seamless integration.  Installing a <a href="http://graphpaperpress.com/themes/">Graph Paper Press theme</a> will remove this notice.</p></div>';
	}

//print_r(array_intersect($installed_themes, $required_themes));

}