<?php

class Fnsf_Af2AjaxCategory {

    function __construct() {}

    public function fnsf_add_category() {

        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $category_label = sanitize_text_field($_POST['label']);;

        if(trim($category_label) == '') wp_die();

        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;

        $categories_id = intval(get_option('af2_categories'));
        $post = get_post($categories_id);
        
        $categories = fnsf_af2_get_post_content($post);

        foreach($categories as $category) {
            if($category['label'] == $category_label) wp_die();
        }
        
        $category_id = $this->fnsf_get_category_id($categories, 0);

        array_push($categories, array(
            'id' => $category_id,
            'label' => $category_label,
            'elements' => array(),
        ));

        wp_update_post( array('ID' => $categories_id, 'post_content' => urlencode(serialize($categories))) );

        wp_die();
    }

    public function fnsf_delete_category() {

        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $category_id = sanitize_text_field($_POST['id']);
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;

        $categories_id = intval(get_option('af2_categories'));
        $post = get_post($categories_id);
        
        $categories = fnsf_af2_get_post_content($post);

        for($i = 0; $i < sizeof($categories); $i++) {
            if($categories[$i]['id'] == $category_id) {
                array_splice($categories, $i, 1);
                break;
            }
        }
        
        wp_update_post( array('ID' => $categories_id, 'post_content' => urlencode(serialize($categories))) );

        wp_die();
    }

    public function fnsf_update_category() {

        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $category_id = sanitize_text_field($_POST['id']);
        $element_id = sanitize_text_field($_POST['elementid']);

        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;

        $categories_id = intval(get_option('af2_categories'));
        $post = get_post($categories_id);
        
        $categories = fnsf_af2_get_post_content($post);

        for($i = 0; $i < sizeof($categories); $i++) {
			$index = array_search($element_id, $categories[$i]['elements']);
			while($index !== false) {
				array_splice($categories[$i]['elements'], $index, 1);
				$index = array_search($element_id, $categories[$i]['elements']);
			}
        }

        if($category_id != null) {
            for($i = 0; $i < sizeof($categories); $i++) {
                if($categories[$i]['id'] == $category_id) {
                    array_push($categories[$i]['elements'], $element_id);
                    break;
                }
            }
        }
        
        
        wp_update_post( array('ID' => $categories_id, 'post_content' => urlencode(serialize($categories))) );

        wp_die();
    }

    private function fnsf_get_category_id($categories, $id) {
        foreach($categories as $category) {
            if($category['id'] == $id) return $this->fnsf_get_category_id($categories, $id+1);
        }
        return $id;
    }
}
