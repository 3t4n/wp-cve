<?php
/**
 * Admin Menu hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksAdminMenu {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct() {

        # ENABLE LINK MANAGER
		if( ADTW()->getop('admin_menus_enable_link_manager') ) {
			add_filter( 
                'pre_option_link_manager_enabled', 
                '__return_true' 
            );
        }

		# REMOVE ITEMS
		if( ADTW()->getop('admin_menus_remove') ) {
			add_action( 
                'admin_menu', 
                [$this, 'removeItems'], 
                99990
            );
        }

		# REMOVE SUBITEMS
		if( ADTW()->getop('admin_submenus_remove') ) {
			add_action( 
                'admin_menu', 
                [$this, 'removeSubItems'], 
                99990
            );
        }
        add_action(
            'load-settings_page_admintweaks', 
            function(){
                add_filter(
                    'esc_html',
                    [$this, 'escHtml'],
                    10, 2
                );
            }
        ); 

		# SORT SETTINGS
		if( ADTW()->getop('admin_menus_sort_wordpress')
			|| ADTW()->getop('admin_menus_sort_plugins' ) ) 
        {
			add_action( 
                'admin_menu', 
                [$this, 'sortSettings'], 
                15 
            );
        }

		# BUBBLES
		if( ADTW()->getop('admin_menus_bubbles') 
            && ADTW()->getop('admin_menus_bubbles_cpts') 
            && ADTW()->getop('admin_menus_bubbles_status') ) 
        {
            add_action( 
                'admin_menu', 
                [$this, 'addBubbles'] 
            );
        }

		# RENAME POSTS
		if( ADTW()->getop('posts_rename_enable') ) {
			add_action( 
                'init', 
                [$this, 'objectLabel'],
                0 
            );
			add_action( 
                'admin_menu', 
                [$this, 'menuLabel'],
                0 
            );
		}

	}

    public function escHtml( $safe_text, $text ) {
        if ( $text && strpos($text, 'dontscape') !== false ) {
            return $text;
        }
        return $safe_text;
    }

	/**
	 * Remove menu items
	 */
	public function removeItems() {
        $items = array_keys(ADTW()->getMenus());
        $remove = ADTW()->getop('admin_menus_remove');
        foreach( $remove as $key ) {
            if ( isset( $items[$key] ) ) remove_menu_page( $items[$key] );
        }
	}

	/**
	 * Remove submenu items
	 */
	public function removeSubItems() {
        $remove = ADTW()->getop('admin_submenus_remove');
        foreach( $remove as $key ) {
            $key = str_replace('____', '=', $key);
            $key = str_replace('___', '?', $key);
            $key = str_replace('_php', '.php', $key);
            $toRemove = explode('__', $key);
            if ( count($toRemove) == 2 ) {
                remove_submenu_page($toRemove[0], $toRemove[1]);
            }
        }
	}

	/**
	 * Sort items in Settings menu
	 * - WordPress and Plugins are dealed separatedly
	 * http://wordpress.stackexchange.com/q/2331/12615
	 * 
	 */
	public function sortSettings() {
		global $submenu;

		if( !isset( $submenu['options-general.php'] ) )
			return;

		// Sort default items
		$default = array_slice( $submenu['options-general.php'], 0, 6, true );
		if( ADTW()->getop('admin_menus_sort_wordpress') ) {
			usort( $default, [$this, '_sortArrayASC'] );
        }

		// Sort rest of items
		$length = count( $submenu['options-general.php'] );
		$extra = array_slice( $submenu['options-general.php'], 6, $length, true );

		if( ADTW()->getop('admin_menus_sort_plugins') ) {
			usort( $extra, [$this, '_sortArrayASC'] );
        }
		// Apply
		$sep = array( array( '<b style="opacity:.3;">. . . . . . . . . . . . .</b>',  'manage_options', '#'));
		$submenu['options-general.php'] = array_merge( $default, $sep, $extra );
	}
	
    
	public function addBubbles() 
    {
		global $menu;
		$bubles = ADTW()->getop('admin_menus_bubbles_cpts');
        $status = ADTW()->getop('admin_menus_bubbles_status');
		foreach( $bubles as $pt ) {
			$cpt_count = wp_count_posts( $pt );

			if( isset( $cpt_count->$status ) ) 
            {
				$suffix = ( 'post' == $pt ) ? '' : "?post_type=$pt";
				$key = ADTW()->recursiveArraySearch( "edit.php$suffix", $menu );

				if( !$key )
					return;

				$menu[$key][0] .= sprintf(
						'<span class="update-plugins count-%1$s">
						<span class="plugin-count">%1$s</span>
					</span>', $cpt_count->$status
				);
			}
		}
	}
	


	/**
	 * Sort array by sub-value
	 * http://stackoverflow.com/a/1597788/1287812
	 * 
	 */
	private function _sortArrayASC( $item1, $item2 ) {
		if ($item1[0] == $item2[0]) return 0;
		return ( $item1[0] > $item2[0] ) ? 1 : -1;
	}


	/**
	 * Rename "Posts" in the global scope
	 * 
	 * @global type $wp_post_types
	 */
	public function objectLabel() {
		global $wp_post_types;

		$labels = &$wp_post_types['post']->labels;

		if ( ADTW()->getop('posts_rename_name') ) {
			$labels->name = ADTW()->getop('posts_rename_name');
        }
	}


	/**
	 * Rename "Posts" in the Admin Menu
	 * 
	 * @global type $menu
	 * @global type $submenu
	 */
	public function menuLabel() {
		global $menu, $submenu;

		if ( ADTW()->getop('posts_rename_name') ) {
			$menu[5][0] = ADTW()->getop('posts_rename_name');
			$submenu['edit.php'][5][0]  = ADTW()->getop('posts_rename_name');
        }
	}
	
}