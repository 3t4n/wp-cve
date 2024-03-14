<?php

namespace Wicked_Folders;

use Wicked_Folders;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Holds details about a screen's state.
 */
final class Screen_State extends JSON_Serializable_Object {

    public $screen_id           	    = false;
    public $user_id             	    = false;
    public $folder              	    = false;
	public $folder_type 			    = 'Wicked_Folders\Term_Folder';
    public $expanded_folders    	    = array( '0' );
    public $tree_pane_width     	    = 292;
	public $hide_assigned_items 	    = true;
	public $orderby 				    = 'wicked_folder_order';
	public $order 					    = 'asc';
	public $is_folder_pane_visible 	    = true;
	public $lang 					    = false;
	public $sort_mode 				    = Folder_Collection::SORT_MODE_CUSTOM;
	public $show_item_counts 		    = true;
	public $enable_ajax_nav 		    = true;
	public $can_add_folders 		    = true;
	public $enable_search 			    = true;
	public $enable_breadcrumbs 		    = true;
	public $include_children 			= false;
	public $sync_upload_folder_dropdown = true;
	public $is_folder_panel_busy 		= false;
	public $selected_folders 			= array();
    public $schema = array(
        'screenId' 					=> 'screen_id',
        'userId' 					=> 'user_id',
        'folder' 					=> 'folder',
        'expandedFolders' 			=> 'expanded_folders',
        'treePaneWidth' 			=> 'tree_pane_width',
        'orderBy' 					=> 'orderby',
        'order' 					=> 'order',
        'isFolderPaneVisible' 		=> 'is_folder_pane_visible',
        'lang' 						=> 'lang',
        'sortMode' 					=> 'sort_mode',
        'showItemCounts' 			=> 'show_item_counts',
        'enableAjaxNav' 			=> 'enable_ajax_nav',
        'canAddFolders' 			=> 'can_add_folders',
        'enableSearch' 				=> 'enable_search',
        'enableBreadcrumbs' 		=> 'enable_breadcrumbs',
        'includeChildren' 			=> 'include_children',
        'syncUploadFolderDropdown' 	=> 'sync_upload_folder_dropdown',
        'isFolderPanelBusy' 		=> 'is_folder_panel_busy',
        'selectedFolders' 			=> 'selected_folders',
    );

    public function __construct( $screen_id, $user_id, $lang = false ) {
        $this->screen_id    = $screen_id;
        $this->user_id      = $user_id;
		$this->lang 		= $lang;

        $state = get_user_meta( $user_id, 'wicked_folders_plugin_state', true );

        if ( isset( $state['screens'][ $screen_id ] ) ) {

            $screen_state = $state['screens'][ $screen_id ];

            if ( ! empty( $screen_state['folder'] ) ) {
                $this->folder = ( string ) $screen_state['folder'];
            } else {
				$this->folder = '0';
			}

			if ( isset( $screen_state['folder_type'] ) ) {
                $this->folder_type = ( string ) $screen_state['folder_type'];
            }

            if ( ! empty( $screen_state['expanded_folders'] ) ) {
                $this->expanded_folders = ( array ) $screen_state['expanded_folders'];
            }

            if ( isset( $screen_state['tree_pane_width'] ) ) {
                $this->tree_pane_width = ( int ) $screen_state['tree_pane_width'];
            }

			if ( isset( $screen_state['hide_assigned_items'] ) ) {
				$this->hide_assigned_items = ( bool ) $screen_state['hide_assigned_items'];
			}

			if ( isset( $screen_state['orderby'] ) ) {
				$this->orderby = $screen_state['orderby'];
			}

			if ( isset( $screen_state['order'] ) ) {
				$this->order = $screen_state['order'];
			}

			if ( isset( $screen_state['is_folder_pane_visible'] ) ) {
				$this->is_folder_pane_visible = $screen_state['is_folder_pane_visible'];
			}

			if ( isset( $screen_state['sort_mode'] ) ) {
				$this->sort_mode = $screen_state['sort_mode'];
			}

			// Is there a language variation specified?
			if ( $this->lang ) {
				// Is there a folder available for the language?
				if ( isset( $screen_state['langs'][ $this->lang ]['folder'] ) ) {
					$this->folder = $screen_state['langs'][ $this->lang ]['folder'];
				} else {
					// No folder found for the language so default to 'All Folders'
					$this->folder = '0';
				}

				if ( isset( $screen_state['langs'][ $this->lang ]['folder_type'] ) ) {
					$this->folder_type = $screen_state['langs'][ $this->lang ]['folder_type'];
				} else {
					$this->folder_type = 'Wicked_Folders\Term_Folder';
				}

				if ( isset( $screen_state['langs'][ $this->lang ]['expanded_folders'] ) ) {
					$this->expanded_folders = ( array ) $screen_state['langs'][ $this->lang ]['expanded_folders'];
				} else {
					$this->expanded_folders = array( '0' );
				}
			}
        }

		$this->expanded_folders = array_unique( $this->expanded_folders );

		// Filter tree pane width
		$this->tree_pane_width = apply_filters( 'wicked_folders_screen_state_tree_pane_width', $this->tree_pane_width, $this );

		$this->show_item_counts = ( bool ) get_option( 'wicked_folders_show_item_counts', true );

		$this->enable_ajax_nav = ( bool ) get_option( 'wicked_folders_enable_ajax_nav', true );		

		$this->enable_breadcrumbs = ( bool ) get_option( 'wicked_folders_show_breadcrumbs', true );

		$this->enable_search = ( bool ) get_option( 'wicked_folders_show_folder_search', true );

		$this->sync_upload_folder_dropdown = ( bool ) get_option( 'wicked_folders_sync_upload_folder_dropdown', true );

		/**
		 * Give others a chance to override the constructed screen state object.
		 *
		 * @since 2.18.4
		 *
		 * @param object $state
		 *  The current screen state instance.
		 */
		apply_filters( 'wicked_folders_construct_screen_state', $this );

        return $this;
    }

	public function save() {

		$states = ( array ) get_user_meta( $this->user_id, 'wicked_folders_plugin_state', true );
		$existing_state = isset( $states['screens'][ $this->screen_id ] ) ? $states['screens'][ $this->screen_id ] : array();
		$state = array(
			'tree_pane_width' 			=> $this->tree_pane_width,
			'folder' 					=> $this->folder,
			'expanded_folders' 			=> $this->expanded_folders,
			'hide_assigned_items' 		=> $this->hide_assigned_items,
			'folder_type' 				=> $this->folder_type,
			'orderby' 					=> $this->orderby,
			'order' 					=> $this->order,
			'is_folder_pane_visible' 	=> $this->is_folder_pane_visible,
			'sort_mode' 				=> $this->sort_mode,
		);

		$state = array_merge( $existing_state, $state );

		if ( ! isset( $state['langs'] ) ) {
			$state['langs'] = array();
		}

		if ( ! isset( $states['screens'][ $this->screen_id ] ) ) {
			$states['screens'][ $this->screen_id ] = array();
		}

		if ( $this->lang ) {
			$state['langs'][ $this->lang ] = array(
				'folder' 			=> $this->folder,
				'folder_type' 		=> $this->folder_type,
				'expanded_folders' 	=> $this->expanded_folders,
			);
		}

		$states['screens'][ $this->screen_id ] = $state;

		update_user_meta( $this->user_id, 'wicked_folders_plugin_state', $states );
	}

	/**
	 * Checks if the current screen is filtered by a folder term. If so, changes
	 * the state's selected folder to the filtered one.
	 * 
	 * Checks if the selected folder exists. If not, changes the selected folder
	 * to the root folder.
	 * 
	 * Chcks if the selected folder is visible to the current user. If not, changes
	 * the selected folder to the root folder.
	 * 
	 * @param Folder_Collection $folders
	 *  The collection of folders to check against.
     * 
     * @param string $taxonomy
     *  The taxonomy name to check against.
	 */
	public function maybe_change_selected_folder( Folder_Collection $folders ) {
		$filtered_folder    = $this->get_url_filtered_folder( $folders->post_type );
        $taxonomy           = Wicked_Folders::get_tax_name( $folders->post_type );

		// Is the current screen filtered by a folder?
		if ( false !== $filtered_folder ) {
			$this->folder = $filtered_folder;
		}

        $folder = $folders->get( $this->folder );

		// Does the selected folder exist?
		if ( ! $folder ) {
			$this->folder = '0';
		} else {
            // Is the selected folder viewable
            $viewable = apply_filters( 'wicked_folders_can_view_folder', true, get_current_user_id(), $this->folder, $taxonomy );

            if ( ! $viewable ) {
                $this->folder = '0';
            }
        }

		// Save the state
        $this->save();
	}

	/**
	 * Checks the current URL for a folder filter and return's the ID of the
	 * folder if one exists.
	 */
	public function get_url_filtered_folder( $post_type ) {
        $taxonomy = Wicked_Folders::get_tax_name( $post_type );

        if ( isset( $_GET["wicked_{$post_type}_folder_filter"] ) ) {
            return ( string ) $_GET["wicked_{$post_type}_folder_filter"];
        }

		// Items can also be filtered using the folders column which uses
		// the folder taxonomy name as the parameter with the folder term
		// slug as the value
		$slug = isset( $_GET[ $taxonomy ] ) ? sanitize_text_field( $_GET[ $taxonomy ] ) : false;

		// The attachments page uses the 'taxonomy' parameter
		if ( isset( $_GET['taxonomy'] ) && isset( $_GET['term'] ) && $taxonomy == $_GET['taxonomy'] ) {
            $slug = sanitize_key( $_GET['term'] );
        }

		if ( $slug ) {
			$term = get_term_by( 'slug', $slug, $taxonomy );

			if ( $term ) {
				return ( string ) $term->term_id;
			}
		}

		return false;		
	}
}
