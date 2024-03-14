<?php

namespace Wicked_Folders;

use Exception;
use DirectoryIterator;
use Wicked_Folders;
use JsonSerializable;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

/**
 * Holds a collection of folders.
 */
class Folder_Collection extends Object_Collection implements JsonSerializable {

    const SORT_MODE_ALPHABETICAL 	= 'alpha';
	const SORT_MODE_CUSTOM 			= 'custom';

    public $post_type = false;

    public function __construct( $post_type = false, $sort_mode = self::SORT_MODE_CUSTOM ) {
        if ( $post_type ) {
            $this->post_type = $post_type;
            
            $folders = Wicked_Folders::get_folders( $this->post_type );

            foreach ( $folders as $folder ) {
                $this->add( $folder );
            }
        }

        // Sort folders
		self::SORT_MODE_ALPHABETICAL == $sort_mode ? $this->sort_by_name() : $this->sort_by_order();
    }

    /**
     * Add a folder.
     *
     * @param Folder
     *  The folder to add.
     */
    public function add( $item ) {
        $this->add_if( $item, 'Wicked_Folders\Folder' );
    }

    /**
     * Get a folder by ID.
     *
     * @param string $id
     *  The ID of the folder to get.
     *
     * @return Folder|bool
     *  The folder object or false if not found.
     */
    public function get( $id ) {
        foreach ( $this->items as $folder ) {
            if ( $folder->id == $id ) {
                return $folder;
            }
        }

        return false;
    }

    /**
     * Remove a folder.
     * 
     * @param Folder
     *  The folder to remove.
     */
    public function remove( $item ) {
        foreach ( $this->items as $index => $folder ) {
            if ( $folder->id === $item->id ) {
                unset( $this->items[ $index ] );

                break;
            }
        }
    }

    /**
     * Deletes the specified folders and removes them from the collection.
     * 
     * @param array $ids
     *  The IDs of the folders to delete.
     * 
     * @return Folder_Collection
     *  Folders that were changed (i.e. assigned to a new parent).
     */
    public function delete( $ids ) {
        $changed = new Folder_Collection();

        // Assign new parents before deleting
        foreach ( $this->items as $folder ) {
            if ( in_array( $folder->id, $ids ) ) {
                $new_parent = '0';

                // Assign a new parent to the folder's children
                $children = $this->get_children( $folder );
                
                // Get the folder's ancestors...
                $ancestors = $this->get_ancestors( $folder );

                // ...and find the first ancestor that is not being deleted
                if ( ! empty( $ancestors ) ) {
                    foreach ( $ancestors as $ancestor ) {
                        if ( ! in_array( $ancestor->id, $ids ) ) {
                            $new_parent = $ancestor->id;

                            break;
                        }
                    }
                }

                // Assign the new parent to the folder's children
                foreach ( $children as $child ) {
                    // Nothing to do if the child is being deleted too
                    if ( ! in_array( $child->id, $ids ) ) {
                        $child->parent = $new_parent;

                        $changed->add( $child );
                    }
                }
            }
        }

        // Now we can delete
        foreach ( $this->items as $folder ) {
            if ( in_array( $folder->id, $ids ) ) {
                $folder->delete();

                $this->remove( $folder );
            }
        }
        
        return $changed;
    }

    /**
     * Get the children of a folder.
     * 
     * @param Folder $folder
     *  The folder to get the children of.
     * 
     * @return Folder_Collection
     *  The folder's children.
     */
    public function get_children( $folder ) {
        $children = new Folder_Collection();

        foreach ( $this->items as $child ) {
            if ( $child->parent == $folder->id ) {
                $children->add( $child );
            }
        }

        return $children;
    }

    /**
     * Get the ancestors of a folder.
     * 
     * @param Folder $folder
     *  The folder to get the ancestors of.
     * 
     * @return Folder_Collection
     *  The folder's ancestors.
     */
    public function get_ancestors( $folder ) {
        $ancestors = new Folder_Collection();

        // Get the folder's parent
        $parent = $this->get( $folder->parent );

        if ( $parent ) {
            $ancestors->add( $parent );

            $parent_ancestors = $this->get_ancestors( $parent );

            // Add parent's ancestors
            foreach ( $parent_ancestors as $ancestor ) {
                $ancestors->add( $ancestor );
            }
        }

        return $ancestors;
    }

    /**
     * Saves all folders in the collection.
     */
    public function save() {
        foreach ( $this->items as $folder ) {
            $folder->save();
        }
    }

    /**
     * Sorts folders by name.  Note: for now, root level folders
     * are sorted by their order.
     */
    public function sort_by_name() {
        usort( $this->items, function( $a, $b ) {
            $a_order = intval( $a->order );
            $b_order = intval( $b->order );
            $a_name = strtoupper( $a->name );
            $b_name = strtoupper( $b->name );
            $a_parent = $a->parent;
            $b_parent = $b->parent;
        
            // Always sort root folders by their sort order
            if ( 'root' == $a_parent || 'root' == $b_parent ) {
                if ( 'root' == $a_parent && 'root' == $b_parent ) {
                    return $a_order < $b_order ? -1 : 1;
                }
        
                if ( 'root' == $a_parent ) {
                    $b_order = 1;
                }
        
                if ( 'root' == $b_parent ) {
                    $a_order = 1;
                }
        
                return $a_order < $b_order ? -1 : 1;
            }
        
            if ( $a_name == $b_name ) return 0;
            
            return $a_name < $b_name ? -1 : 1;
        } );
    }

    /**
     * Sort folders by their order.
     */
    public function sort_by_order() {
        usort( $this->items, function( $a, $b ) {
            $a_order = intval( $a->order );
            $b_order = intval( $b->order );
            $a_name = strtoupper( $a->name );
            $b_name = strtoupper( $b->name );
            $a_parent = $a->parent;
            $b_parent = $b->parent;
        
            // If the order is the same for both folders, sort by name
            if ( $a_order == $b_order ) {
                if ( $a_name == $b_name ) return 0;

                return $a_name < $b_name ? -1 : 1;
            }

            return $a_order < $b_order ? -1 : 1;
        } );
    }

	public function jsonSerialize(): array {
		$json = array();

		foreach ( $this->items as $folder ) {
			$json[] = $folder->jsonSerialize();
		}

		return $json;
	}
}
