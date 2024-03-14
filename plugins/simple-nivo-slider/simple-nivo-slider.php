<?php
/*
Plugin Name: Simple Nivo Slider
Plugin URI: http://wordpress.org/extend/plugins/simple-nivo-slider/
Description: Gilbert Pellegrom's excellent Nivo Slider, made easily available for WordPress
Version: 0.5.6
Author: Thomas M Steenholdt
Author URI: http://www.tmus.dk/
License: GLP2
*/

/*  Copyright 2011  Thomas Munck Steenholdt  (email : tmus@tmus.dk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* plugin database version - used to identify the need to "upgrade" database */
define('SNIVO_DB_VERSION', 5);

/* supported effects */
define('SNIVO_EFFECTS', 'fold fade sliceDownRight sliceDownLeft sliceUpRight sliceUpLeft sliceUpDown sliceUpDownLeft slideInRight slideInLeft boxRandom boxRain boxRainReverse boxRainGrow boxRainGrowReverse');


/*
 * define default values for all plugin options
 * the function can return the entire array or the value of a single option if opt_name is specified
 */
function snivo_defaults($opt_name=null) {

	$defaults = Array(
		'snivo_category' => 1,
		'snivo_imagesize' => 'full',
		'snivo_shuffle' => 0,
		'snivo_nivo_effects' => explode(' ', constant('SNIVO_EFFECTS')),
		'snivo_nivo_slices' => 15,
		'snivo_nivo_boxcols' => 8,
		'snivo_nivo_boxrows' => 4,
		'snivo_nivo_animspeed' => 500,
		'snivo_nivo_pausetime' => 3000,
		'snivo_nivo_directionnav' => 1,
		'snivo_nivo_directionnavhide' => 1,
		'snivo_nivo_controlnav' => 1,
		'snivo_nivo_keyboardnav' => 1,
		'snivo_nivo_pauseonhover' => 1,
		'snivo_nivo_captionopacity' => 80
	);

	/* return entire array if $opt_name is empty */
	if (empty($opt_name))
		return $defaults;

	/* otherwise return the value of the specified option from the array */
	return $defaults[$opt_name];
	
};


/*
 * plugin activation hook
 */
function snivo_activate() {

	/* add/update all plugin options */
	update_option('snivo_db_version', constant('SNIVO_DB_VERSION'));
	foreach (snivo_defaults() as $opt_name => $default_value) {
		add_option($opt_name, $default_value);
	}

}


/*
 * plugin deactivation hook
 */
function snivo_deactivate() {

	/* remove deprecated options from the database on deactivation */ 
	delete_option('snivo_nivo_testtest');

}


/*
 * plugin uninstallation hook
 */
function snivo_uninstall() {

	/* delete all plugin options */
	delete_option('snivo_db_version');
	foreach (snivo_defaults() as $opt_name => $default_value) {
		delete_option($opt_name);
	}

}


/*
 * display an administrative notice when database is updated
 */
function snivo_update_notice() {
	?>
		<div class="updated fade"><p>
			<strong>Simple Nivo Slider:</strong> database was updated.
		</p></div>
	<?php
}


/*
 * handle plugin updates
 */
function snivo_update_helper() {

	/*
	 * if current database 'snivo_db_version' is lower that the plugin database version,
	 * update the plugin database by calling the deactivate() and activate() functions
	 */
	if (intval(get_option('snivo_db_version')) < constant('SNIVO_DB_VERSION')) {
		/* deactivate, activate will handle addition of new options to the database
		 * and update the db_version too */
		snivo_deactivate();
		snivo_activate();

		/* notify administrator of the update */
		add_action('admin_notices', 'snivo_update_notice');
	}

}


/*
 * plugin admin_init action function
 */
function snivo_admin_init() {

	register_setting('snivo-settings', 'snivo_category', 'intval');
	register_setting('snivo-settings', 'snivo_imagesize');
	register_setting('snivo-settings', 'snivo_shuffle', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_effects');
	register_setting('snivo-settings', 'snivo_nivo_slices', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_boxcols', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_boxrows', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_animspeed', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_pausetime', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_directionnav', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_directionnavhide', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_controlnav', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_keyboardnav', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_pauseonhover', 'intval');
	register_setting('snivo-settings', 'snivo_nivo_captionopacity', 'intval');

	snivo_update_helper();

}


/*
 * plugin admin_menu action function
 */
function snivo_admin_menu() {

	if (function_exists('add_submenu_page'))
	        add_submenu_page('plugins.php','Simple Nivo Slider configuration', 'Simple Nivo Slider', 'manage_options', 'snivo_menu', 'snivo_admin_options');

}


/*
 * this is the function that actually provides the slider (called from theme)
 */
function simple_nivo_slider($id='slider', $category='') {

	# make sure the theme has post-thumbnail support
	if (!current_theme_supports('post-thumbnails'))
		add_theme_support('post-thumbnails');

	?>
		<script type="text/javascript">
			jQuery(window).load(function() {
			        jQuery('#<?php echo $id?>').nivoSlider({
			                effect: '<?php echo implode(',', get_option('snivo_nivo_effects', snivo_defaults('snivo_nivo_effects'))) ?>',
			                slices: <?php echo get_option('snivo_nivo_slices', snivo_defaults('snivo_nivo_slices')) ?>,
			                boxCols: <?php echo get_option('snivo_nivo_boxcols', snivo_defaults('snivo_nivo_boxcols')) ?>,
			                boxRows: <?php echo get_option('snivo_nivo_boxrows', snivo_defaults('snivo_nivo_boxrows')) ?>,
			                animSpeed: <?php echo get_option('snivo_nivo_animspeed', snivo_defaults('snivo_nivo_animspeed')) ?>,
			                pauseTime: <?php echo get_option('snivo_nivo_pausetime', snivo_defaults('snivo_nivo_pausetime')) ?>,
			                startSlide: 0,
			                directionNav: <?php echo (get_option('snivo_nivo_directionnav', snivo_defaults('snivo_nivo_directionnav')) == 1) ? 'true' : 'false' ?>,
			                directionNavHide: <?php echo (get_option('snivo_nivo_directionnavhide', snivo_defaults('snivo_nivo_directionnavhide')) == 1) ? 'true' : 'false' ?>,
			                controlNav: <?php echo (get_option('snivo_nivo_controlnav', snivo_defaults('snivo_nivo_controlnav')) == 1) ? 'true' : 'false' ?>,
			                keyboardNav: <?php echo (get_option('snivo_nivo_keyboardnav', snivo_defaults('snivo_nivo_keyboardnav')) == 1) ? 'true' : 'false' ?>,
			                pauseOnHover: <?php echo (get_option('snivo_nivo_pauseonhover', snivo_defaults('snivo_nivo_pauseonhover')) == 1) ? 'true' : 'false' ?>,
			                captionOpacity: <?php printf("%0.1f", get_option('snivo_nivo_captionopacity', snivo_defaults('snivo_nivo_captionopacity'))/100) ?>
			        });
			});
		</script>
		<div id="<?php echo $id?>">
	<?php

	# build the post query string, then perform the query (default category is specified in plugin settings)
	$query = 'posts_per_page=-1&';
	if (empty($category))
		$query .= 'cat='.get_option('snivo_category');
	else
		$query .= 'category_name='.$category;

	# perform the query, shuffle if shuffle enabled
	if (get_option('snivo_shuffle', snivo_defaults('snivo_shuffle')))
		shuffle(query_posts($query));
	else
		query_posts($query);

	# process the results
	while (have_posts()) {

		the_post();

		if(!has_post_thumbnail())
			break;

		# determine link
		$link = trim(get_post_meta(get_the_id(), 'simple_nivo_link', true));
		if (empty($link))
			$link = get_permalink();

		# build 'a' opening and closing tags, if wanted
		$linkhead = $linktail = '';
		if (strtolower($link) != 'none') {
			$linkhead = "<a href=\"$link\">";
			$linktail = "</a>";
		}

		list($imgsrc) = wp_get_attachment_image_src(get_post_thumbnail_id(), get_option('snivo_imagesize', snivo_defaults('snivo_imagesize')));

		$caption = get_post_meta(get_the_id(), 'simple_nivo_caption', true);

		?>
		<?php echo $linkhead ?><img src="<?php echo $imgsrc ?>" alt="" title="<?php echo $caption ?>" /><?php echo $linktail."\n" ?>
		<?php

	}

	wp_reset_query();

	?>
		</div>
	<?php
} 


/*
 * handle snivo shortcode tags
 */
function snivo_shortcode($atts) {

	extract(shortcode_atts(array(
		'id' => 'slider',
		'category' => '',
	), $atts));

	# place call to main plugin function using specified options
	simple_nivo_slider($id, $category);

}


/*
 * plugin administrative options page
 */
function snivo_admin_options() {

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	?>
		<div class="wrap">
		<h2>Simple Nivo Slider configuration</h2>
		<form method="post" action="options.php" id="simple-nivo-slider-conf">
			<?php settings_fields('snivo-settings'); ?>

			<p><table class="form-table">

			<tr><th scope="row">Post category</th><td>
			<select name="snivo_category">
				<option value="1">Select a category</option> 
	 		<?php 
				$category = get_option('snivo_category');
				$categories=  get_categories(); 
				foreach ($categories as $cat) {
					$option = '<option value="'.$cat->term_id.'"';
					if ($cat->term_id == $category)
						$option .= ' selected="selected"';
					$option .= '>';
					$option .= $cat->cat_name.'('.$cat->category_count.')';
					$option .= '</option>';
					echo $option;
				}
			?>
			</select>
			</td></tr>

			<tr><th scope="row">Image Size</th><td>
			<select name="snivo_imagesize">
	 		<?php 
				$imagesize = get_option('snivo_imagesize');
				$imagesizes = get_intermediate_image_sizes();
				$imagesizes[] = 'full';
				foreach (array_reverse($imagesizes) as $tmpsize) {
					$option = '<option value="'.$tmpsize.'"';
					if ($tmpsize == $imagesize)
						$option .= ' selected="selected"';
					$option .= '>';
					$option .= $tmpsize;
					if ($tmpsize == snivo_defaults('snivo_imagesize'))
						$option .= ' (default)';
					$option .= '</option>';
					echo $option;
				}
			?>
			</select>
			</td></tr>

			<tr><th scope="row">Enabled effects</th><td>
			<?php
				$all_effects = explode(' ', constant('SNIVO_EFFECTS'));
				$enabled_effects = get_option('snivo_nivo_effects');
				foreach ($all_effects as $effect) {
					?>
					<input type="checkbox" name="snivo_nivo_effects[]" value="<?php echo $effect ?>"<?php if (in_array($effect, $enabled_effects)) echo ' checked="checked"'?>><?php echo $effect ?><br/>
					<?php
				}
			?>
			</td></tr>

			<tr><th scope="row">Number of slices</th><td>
			<input type="text" name="snivo_nivo_slices" size="3" value="<?php echo get_option('snivo_nivo_slices') ?>">
			</td></tr>

			<tr><th scope="row">Number of box columns</th><td>
			<input type="text" name="snivo_nivo_boxcols" size="3" value="<?php echo get_option('snivo_nivo_boxcols') ?>">
			</td></tr>

			<tr><th scope="row">Number of box rows</th><td>
			<input type="text" name="snivo_nivo_boxrows" size="3" value="<?php echo get_option('snivo_nivo_boxrows') ?>">
			</td></tr>

			<tr><th scope="row">Transition speed</th><td>
			<input type="text" name="snivo_nivo_animspeed" size="5" value="<?php echo get_option('snivo_nivo_animspeed') ?>"> ms
			</td></tr>

			<tr><th scope="row">Delay between transitions</th><td>
			<input type="text" name="snivo_nivo_pausetime" size="5" value="<?php echo get_option('snivo_nivo_pausetime') ?>"> ms
			</td></tr>

			<tr><th scope="row">Caption opacity</th><td>
			<input type="text" name="snivo_nivo_captionopacity" size="3" value="<?php echo get_option('snivo_nivo_captionopacity') ?>"> %
			</td></tr>

			<tr><th scope="row">Options</th><td>
			<input type="checkbox" name="snivo_shuffle" value="1"<?php if (get_option('snivo_shuffle') == 1) echo ' checked="checked"'?>>Shuffle image order<br/>
			<input type="checkbox" name="snivo_nivo_directionnav" value="1"<?php if (get_option('snivo_nivo_directionnav') == 1) echo ' checked="checked"'?>>Show directional navigation links<br/>
			<input type="checkbox" name="snivo_nivo_directionnavhide" value="1"<?php if (get_option('snivo_nivo_directionnavhide') == 1) echo ' checked="checked"'?>>Show directional navigation links only on hover<br/>
			<input type="checkbox" name="snivo_nivo_controlnav" value="1"<?php if (get_option('snivo_nivo_controlnav') == 1) echo ' checked="checked"'?>>Enable control navigation<br/>
			<input type="checkbox" name="snivo_nivo_keyboardnav" value="1"<?php if (get_option('snivo_nivo_keyboardnav') == 1) echo ' checked="checked"'?>>Enable keyboard navigation<br/>
			<input type="checkbox" name="snivo_nivo_pauseonhover" value="1"<?php if (get_option('snivo_nivo_pauseonhover') == 1) echo ' checked="checked"'?>>Pause slider on hover<br/>
			</td></tr>

			</table></p>
 
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
 
		</form>
		</div>
	<?php
}

/* install/uninstall */
register_activation_hook( __FILE__, 'snivo_activate' );
register_deactivation_hook( __FILE__, 'snivo_deactivate' );
register_uninstall_hook( __FILE__, 'snivo_uninstall' );

/* actions */
add_action('admin_init', 'snivo_admin_init' );
add_action('admin_menu', 'snivo_admin_menu');

/* shortcodes */
add_shortcode('snivo', 'snivo_shortcode');

/* stylesheets */
wp_enqueue_style('nivo-slider', plugins_url('/nivo-slider/nivo-slider.css', __FILE__));
wp_enqueue_style('simple-nivo-slider', plugins_url('/styles.css', __FILE__));

/* scripts */
wp_enqueue_script('nivo-slider', plugins_url('/nivo-slider/jquery.nivo.slider.pack.js', __FILE__), array('jquery'));

?>
