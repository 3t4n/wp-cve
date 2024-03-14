<?php

namespace Wicked_Folders;

use JsonSerializable;

/**
 * Represents a folder object.
 */
class Folder implements JsonSerializable {

    /**
     * The folder's ID.  The folder ID should be unique for a given post type
     * and taxonomy combination.
     *
     * @var string
     */
    public $id = false;

    /**
     * The ID of the user that owns the folder.
     *
     * @var int
     */
    public $owner_id = 0;

    /**
     * The display name of the owner.
     *
     * @var string
     */
    public $owner_name;

    /**
     * The ID of the folder's parent.
     *
     * @var string
     */
    public $parent = '0';

    /**
     * The folder's name.
     *
     * @var string
     */
    public $name;

    /**
     * The post type the folder belongs to.
     *
     * @var string
     */
    public $post_type;

    /**
     * The taxonomy the folder belongs to.
     *
     * @var string
     */
    public $taxonomy;

    /**
     * Whether or not the folder can be moved into other folders.
     *
     * @var boolean
     */
    public $movable = true;

    /**
     * Whether or not the folder can be edited.
     *
     * @var boolean
     */
    public $editable = true;

    /**
     * Whether or not the folder can be deleted.
     *
     * @var boolean
     */
    public $deletable = true;

    /**
     * Whether or not items can be assigned to the folder.
     *
     * @var boolean
     */
    public $assignable = true;

    /**
     * Whether or not the folder's sub folders should be lazy loaded.
     *
     * @var boolean
     */
    public $lazy = false;

    /**
     * The number of items in the folder.
     *
     * @var integer
     */
    public $item_count = 0;

    /**
     * Whether or not to display the number of items in the folder.
     *
     * @var bool
     */
    public $show_item_count = false;

    /**
     * The order of this folder relative to other folders with the same parent.
     *
     * @var int
     */
    public $order = 0;

    /**
     * The class name of the folder instance.
     * 
     * @since 2.18.19
     * 
     * @var string
     */
    public $type;
        
    /**
     * Whether or not the folder is a dynamic folder.
     * 
     * @since 3.0.1
     * 
     * @var bool
     */
    public $is_dynamic = false;
    
    public function __construct( array $args = array() ) {
        // TODO: throw error if ID argument is set and contains reserved characters
        // such as periods
        $args = wp_parse_args( $args, array(
            'parent'    => '0',
            'name'      => __( 'Untitled folder', 'wicked-folders' ),
        ) );

        foreach ( $args as $property => $arg ) {
            $this->{$property} = $arg;
        }

        if ( $this->post_type && ! $this->taxonomy ) {
            $this->taxonomy = Wicked_Folders::get_tax_name( $post_type );
        }

        // Change IDs to strings so that they compare correctly regardless of type
        $this->id       = ( string ) $this->id;
        $this->parent   = ( string ) $this->parent;
    }

    public function ancestors() {
        return array();
    }

    public function fetch_posts() {
        return array();
    }

    public function get_child_folders() {
        return array();
    }

    /**
     * Load the folder from the database.
     *
     * @return boolean
     *  True if the folder was successfully loaded, false otherwise.
     */
    public function fetch() {
        return false;
    }

    public function get_ancestor_ids( $id = false ) {
        return array();
    }

    public function jsonSerialize(): array {
        return array(
            'id'            => $this->id,
            'parent'        => $this->parent,
            'ownerId'       => $this->owner_id,
            'ownerName'     => $this->owner_name,
            'name'          => $this->name,
            'postType'      => $this->post_type,
            'taxonomy'      => $this->taxonomy,
            'movable'       => $this->movable,
            'editable'      => $this->editable,
            'deletable'     => $this->deletable,
            'assignable'    => $this->assignable,
            'lazy'          => $this->lazy,
            'itemCount'     => $this->item_count,
            'showItemCount' => $this->show_item_count,
            'order'         => $this->order,
            'type'          => get_class( $this ),
        );
    }

    public function from_json( $json ) {
        if ( isset( $json->id ) ) $this->id = $json->id;
        if ( isset( $json->parent ) ) $this->parent = $json->parent;
        if ( isset( $json->ownerId ) ) $this->owner_id = $json->ownerId;
        if ( isset( $json->name ) ) $this->name = $json->name;
        if ( isset( $json->postType ) ) $this->post_type = $json->postType;
        if ( isset( $json->taxonomy ) ) $this->taxonomy = $json->taxonomy;
        if ( isset( $json->movable ) ) $this->movable = $json->movable;
        if ( isset( $json->editable ) ) $this->editable = $json->editable;
        if ( isset( $json->assignable ) ) $this->assignable = $json->assignable;
        if ( isset( $json->lazy ) ) $this->lazy = $json->lazy;
        if ( isset( $json->itemCount ) ) $this->item_count = $json->itemCount;
        if ( isset( $json->showItemCount ) ) $this->show_item_count = $json->showItemCount;
        if ( isset( $json->order ) ) $this->order = $json->order;
    }
}
