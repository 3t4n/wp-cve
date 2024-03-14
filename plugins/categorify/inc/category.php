<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {exit; }


class Categorify_Category{
	
	public function __construct()
	{
		add_action( 'add_attachment', array( $this, 'categorifyAddAttachmentCategory' ) );
		add_action( 'edit_attachment', array( $this, 'categorifySetAttachmentCategory' ) );
		add_filter( 'ajax_query_attachments_args', array( $this, 'categorifyQueryAttachmentsArgs' ) ); 
		add_action( 'wp_ajax_save-attachment-compat', array( $this, 'categorifySaveAttachmentCompat' ), 0 );
	}
	
	public function categorifyAddAttachmentCategory( $postID ) 
	{
        $categorifyFolder 	= isset($_REQUEST["ccFolder"]) ? sanitize_text_field($_REQUEST["ccFolder"]) : null;
        if(is_null($categorifyFolder)){
            $categorifyFolder = isset($_REQUEST["cc_categorify_folder"]) ? sanitize_text_field($_REQUEST["cc_categorify_folder"]) : null;
        }
        if($categorifyFolder !== null){
            $categorifyFolder = (int)$categorifyFolder;
            if($categorifyFolder > 0){
                wp_set_object_terms($postID, $categorifyFolder, CATEGORIFY_TAXONOMY, false);
            }
        }
    }


    public function categorifySetAttachmentCategory($postID)
	{
		
        $taxonomy 		= apply_filters( 'categorify_taxonomy', CATEGORIFY_TAXONOMY );

		// остановить здесь, если медиа уже прикрепилена к категории
        if (wp_get_object_terms($postID, $taxonomy)) {
            return;
        }

		// получить категорию по умолчанию, если меда не прикреплена к какой-либо категории
        $postCategory 	= array(get_option( 'default_category' ));

		// прикрепить к категории, если категория по умолчанию установлена
        if($postCategory){
            wp_set_post_categories($postID, $postCategory);
        }
    }
	
	public static function categorifyGetTermsValues( $keys = 'ids' )
	{
        $mediaTerms = get_terms( CATEGORIFY_TAXONOMY, array(
            'hide_empty' => 0,
            'fields'     => 'id=>slug',
        ));
		
        $mediaValues = array();
		
        foreach ( $mediaTerms as $key => $value ){
            $mediaValues[] = ( $keys === 'ids' )
                ? $key
                : $value;
        }

        return $mediaValues;
    }
	
	public function categorifyQueryAttachmentsArgs( $query = array() )
	{
        $taxquery 			= array();
        $taxonomies 		= get_object_taxonomies( 'attachment', 'names' );
        $taxquery 			= array_intersect_key( $taxquery, array_flip( $taxonomies ) );
        $query 				= array_merge( $query, $taxquery );
        $query['tax_query'] = array( 'relation' => 'AND' );

        foreach($taxonomies as $taxonomy){
            if(isset($query[$taxonomy]) && is_numeric($query[$taxonomy])){
                if($query[ $taxonomy ] > 0){
                    array_push( $query['tax_query'], array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'id',
                        'terms'    => $query[$taxonomy],
                        'include_children'  => false
                    ));
                }else{
                    $allTermsIDs = self::categorifyGetTermsValues('ids');
                    array_push( $query[ 'tax_query' ], array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'id',
                        'terms'    => $allTermsIDs,
                        'operator' => 'NOT IN',
                    ) );
                }
                
            }
            unset($query[$taxonomy]);
        }

        return $query;
    }
	


    public function categorifySaveAttachmentCompat()
	{
		// continue if has ID
        if(!isset($_REQUEST['id'])){
            wp_send_json_error();
        }
		
		// continue if this ID is absolute integer
        if(!$id = absint($_REQUEST['id'])){
            wp_send_json_error();
        }
		
		// continue if have attachments and their ids
        if(empty($_REQUEST['attachments']) || empty($_REQUEST['attachments'][$id])){
            wp_send_json_error();
        }
		
        $attachmentData = sanitize_text_field($_REQUEST['attachments'][$id]);
       
        if(current_user_can('edit_post', $id)){
			check_ajax_referer('update-post_'.$id, 'nonce');
		}
        
		if(!current_user_can('edit_post', $id)){
            wp_send_json_error();
        }

        $post = get_post($id, ARRAY_A);

        if('attachment' != $post['post_type']){
            wp_send_json_error();
        }

        $post = apply_filters('attachment_fields_to_save', $post, $attachmentData);

        if(isset($post['errors'])){
            $errors = $post['errors']; 
            unset($post['errors']);
        }

        wp_update_post($post);

        foreach(get_attachment_taxonomies($post) as $taxonomy){

            if(isset($attachmentData[$taxonomy])){
                wp_set_object_terms($id, array_map('trim', preg_split('/,+/', $attachmentData[$taxonomy])), $taxonomy, false);
            }else if(isset($_REQUEST['tax_input']) && isset($_REQUEST['tax_input'][$taxonomy])){
                wp_set_object_terms($id, sanitize_text_field($_REQUEST['tax_input'][$taxonomy]), $taxonomy, false);
            }else{
                wp_set_object_terms($id, '', $taxonomy, false);
            }
            
        }

        if(!$attachment = wp_prepare_attachment_for_js($id)){
            wp_send_json_error();
        }

        wp_send_json_success($attachment);
    }
	
}

new Categorify_Category();