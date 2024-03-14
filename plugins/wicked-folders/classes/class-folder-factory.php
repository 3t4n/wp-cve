<?php

namespace Wicked_Folders;

use Exception;
use Wicked_Folders;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

class Folder_Factory {

    public static function get_folder( $id, $post_type ) {
        $taxonomy   = Wicked_Folders::get_tax_name( $post_type );
        $args       = array(
            'id'        => $id,
            'post_type' => $post_type,
            'taxonomy'  => $taxonomy,
        );

        if ( 0 === strpos( $id, 'dynamic_hierarchy' ) ) {
            $folder = new Post_Hierarchy_Dynamic_Folder( $args );
        } elseif ( 0 === strpos( $id, 'dynamic_author' ) ) {
            $folder = new Author_Dynamic_Folder( $args );
        } elseif ( 0 === strpos( $id, 'dynamic_date' ) ) {
            $folder = new Date_Dynamic_Folder( $args ); 
        } elseif ( 0 === strpos( $id, 'dynamic_term' ) ) {
            $folder = new Term_Dynamic_Folder( $args );
        } elseif ( 'unassigned_dynamic_folder' == $id ) {
            $folder = new Unassigned_Dynamic_Folder( $args );
        } elseif ( 'dynamic_root' == $id ) {
            $folder = new Folder( $args );                                            
        } elseif ( is_numeric( $id ) ) {
            $folder = new Term_Folder( $args );
        } else {
            $folder = new Folder( $args );
        }

        return apply_filters( 'Wicked_Folders\Folder_Factory\get_folder', $folder, $id, $post_type, $taxonomy );
    }
}
