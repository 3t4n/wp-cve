<?php

class ContactListQuery
{
    public function modify_post_title( $data )
    {
        
        if ( isset( $_POST ) && !isset( $_POST['_CL_INSERT'] ) && $data['post_type'] == CONTACT_LIST_CPT && !isset( $_POST['_cl_last_name'] ) && $data['post_content'] == 'imported' ) {
            // ...
        } elseif ( isset( $_POST ) && !isset( $_POST['_CL_INSERT'] ) && $data['post_type'] == CONTACT_LIST_CPT && isset( $_POST['_cl_last_name'] ) ) {
            $data['post_title'] = (( isset( $_POST['_cl_first_name'] ) ? sanitize_text_field( $_POST['_cl_first_name'] ) : '' )) . ' ' . (( isset( $_POST['_cl_last_name'] ) ? sanitize_text_field( $_POST['_cl_last_name'] ) : '' ));
        }
        
        return $data;
    }

}