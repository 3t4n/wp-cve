<?php

/*
Plugin Name: Post type archive in menu
Plugin URI: http://lukapeharda.com
Description: Add post type (custom) archive links easily through WP builtin menu system
Version: 1.0.1
Author: Luka Peharda
Author URI: http://lukapeharda.com
License: GPLv2 or later
*/

/**
 * Displays meta box with custom post type to Wordpress menu screen
 * @return void
 */
function addPostTypeArchivesMenu()
{
    /*
     * If theme doesn't support menus we'll escape
     */
    if (!current_theme_supports('menus')) {
		return;
	}

	/*
	 * Fetching public post typs
	 */
	$postTypes = get_post_types(array('public' => true));
	
	/*
	 * If there aren't any post type we'll escape
	 */
	if (count($postTypes) == 0) {
	    return;
	}
	?>
	<div id="archives-metabox" class="posttypediv">
		<ul id="archive-tabs" class="posttype-tabs add-menu-item-tabs">
			<li class="tabs"><a class="nav-tab-link" href="#archives-all"><?php _e('View All'); ?></a></li>
		</ul>

		<div id="archives-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
			<ul id="archiveschecklist" class="list:archives categorychecklist form-no-clear">
			<?php $a = 1000; foreach ($postTypes as $postType) : ?>
				<?php $postTypeObject = get_post_type_object($postType); ?>
				<?php
				    /*
				     * Skiping current post type if it doesn't support archive
				     */ 
				    if (false === $postTypeObject->has_archive) {
				        continue;
				    }
				?>
				<li>
					<label class="menu-item-title"><input type="checkbox" class="menu-item-checkbox" name="menu-item[-<?php echo $a; ?>][menu-item-object-id]" value="<?php echo $a; ?>"> <?php echo $postTypeObject->labels->name; ?></label>
					<input type="hidden" class="menu-item-url" name="menu-item[-<?php echo $a; ?>][menu-item-url]" value="<?php echo get_post_type_archive_link($postType); ?>">
					<input type="hidden" class="menu-item-type" name="menu-item[-<?php echo $a; ?>][menu-item-type]" value="post_type_archive" />
					<input type="hidden" class="menu-item-title" name="menu-item[-<?php echo $a; ?>][menu-item-title]" value="<?php echo $postTypeObject->labels->name; ?>" />
					<input type="hidden" class="menu-item-object" name="menu-item[-<?php echo $a; ?>][menu-item-object]" value="<?php echo esc_attr($postType); ?>" />
					<input type="hidden" class="menu-item-classes" name="menu-item[-<?php echo $a; ?>][menu-item-classes]" value="post-type-archive-<?php echo $postType; ?>" />
				</li>
			<?php $a = $a + 1; endforeach; ?>
			<?php if ($a === 1000) : ?>
				<li><?php _e('No results found.'); ?></li>
			<?php endif; ?>
			</ul>			
		</div>

		<p class="button-controls">
			<span class="list-controls">
				<a href="<?php echo esc_url(add_query_arg(array('archives-metabox-tab' => 'all', 'selectall' => 1), remove_query_arg($removed_args))); ?>#archives-metabox" class="select-all"><?php _e('Select All'); ?></a>
			</span>
			<span class="add-to-menu">
				<input type="submit" class="button-secondary submit-add-to-menu" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-post-type-menu-item" id="submit-archives-metabox" />
			</span>
		</p>

	</div>
	<?php
}

/**
 * Adds meta box to Wordpress menu screen
 * @return void
 */
function addMetaBox()
{
    add_meta_box('post-type-archives', __('Archives'), 'addPostTypeArchivesMenu', 'nav-menus', 'side', 'low');
}

/*
 * We'll attach to 'admin_menu' action to add our functionality
 */
add_action('admin_menu', 'addMetaBox');

/**
 * Changing link URL if needed (for different permalink structures)
 * 
 * @param object $menuItem
 * @return object
 */
function correctArchiveLink($menuItem)
{
      if ($menuItem->type != 'post_type_archive') {
          return $menuItem;
      }
      
      $postType = $menuItem->object;
      $menuItem->url = get_post_type_archive_link($postType);

      return $menuItem;
 }
 
 /*
  * Fix archives link when changing permalink structure
  */
 add_filter('wp_setup_nav_menu_item',  'correctArchiveLink');

/**
 * Checks to see if items needs to be made "current" in menu. It works for post archive page and for singular page
 * 
 * @param array $items
 * @return $items
 */

function makeItemCurrentInMenu($items)
{
    foreach ($items as $item) {
        
        if ('post_type_archive' != $item->type) {
            continue;
        }

		$postType = $item->object;
		if (!is_post_type_archive($postType)&& !is_singular($postType)) {
		    continue;
		}

		$item->current = true;
		$item->classes[] = 'current-menu-item';

		$ancestorId = (int) $item->db_id;
		$activeAncestors = array();

		while ($ancestorId = get_post_meta($ancestorId, '_menu_item_menu_item_parent', true)
		&& !in_array($ancestorId, $activeAncestors)) {
				$activeAncestors[] = $ancestorId;
		}

		foreach ($items as $key => $parent) {
            $classes = (array) $parent->classes;

            if ($parent->db_id == $item->menu_item_parent) {
                 $classes[] = 'current-menu-parent';
                 $items[$key]->current_item_parent = true;
            }

            if (in_array(intval($parent->db_id), $activeAncestors)) {
                 $classes[] = 'current-menu-ancestor';
                 $items[$key]->current_item_ancestor = true;
            }

            $items[$key]->classes = array_unique($classes);
		}

          }
     return $items;
}

/*
 * Attaching on menu generating to add "current" classes if needed
 */
add_filter('wp_nav_menu_objects', 'makeItemCurrentInMenu');