<?php

class CRP_Cache {

    public function __construct()
    {
        add_action( 'save_post', array( $this, 'updated_post' ), 11, 2 );
        add_action( 'transition_post_status', array( $this, 'updated_transition' ), 10, 3 );
    }

    public function updated_post( $id, $post )
    {
        $update_post_post_type = $post->post_type;
        $post_types = CustomRelatedPosts::setting( 'general_post_types' );

        if( in_array( $update_post_post_type, $post_types ) ) {
            $updated_data = CustomRelatedPosts::get()->relation_data( $post );

            $relations_from = CustomRelatedPosts::get()->relations_from( $id );
            $relations_to = CustomRelatedPosts::get()->relations_to( $id );
            $relation_ids = array_unique( array_merge( array_keys( $relations_to ), array_keys( $relations_from ) ) );

            foreach( $relation_ids as $relation_id ) {
                $target_relations_from = CustomRelatedPosts::get()->relations_from( $relation_id );
                $target_relations_to = CustomRelatedPosts::get()->relations_to( $relation_id );

                if( array_key_exists( $id, $target_relations_from ) ) {
                    $target_relations_from[$id] = $updated_data;
                    CustomRelatedPosts::get()->helper( 'relations' )->update_from( $relation_id, $target_relations_from );
                }
                if( array_key_exists( $id, $target_relations_to ) ) {
                    $target_relations_to[$id] = $updated_data;
                    CustomRelatedPosts::get()->helper( 'relations' )->update_to( $relation_id, $target_relations_to );
                }
            }
        }
    }

    public function updated_transition( $new_status, $old_status, $post ) {
        if ( $new_status != $old_status ) {
            // A function to perform actions any time any post changes status.
        }
    }
}