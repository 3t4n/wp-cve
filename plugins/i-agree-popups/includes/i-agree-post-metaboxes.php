<?php

/**
 * I Agree! Popups
 *
 * @package   I_Agree_Popups
 * @license   GPLv2 or later
**/

/**
 * Add Popup selection metabox to posts and pages
 *
 * @package I_Agree_Popups
**/
 
// ID of post or page being edited
$postID = get_the_ID();

class I_Agree_Post_Metaboxes {
    
    // Initialise functions
    public function init() {
        
        add_action( 'add_meta_boxes', array( $this, 'post_popup_config' ) );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ),  10, 2 );
        
    }

    /**
     * Register the Popup selection metabox for posts and pages
     *
     * @since 1.0
    **/
      
    public function post_popup_config() {
        $postTypeArgs = array('public' => true);
        $output = 'names'; 
        $operator = 'and';
        $getPostTypes = get_post_types($postTypeArgs, $output, $operator);
        $getPostType =  get_post_type();
        if (in_array($getPostType, $getPostTypes)) {
            $postType = $getPostType;
        } else {
            $postType = 'null';
        }
        add_meta_box(
            'tandc_popup',
            'I Agree! Popups',
            array( $this, 'render_meta_boxes' ),
            $postType,
            'side',
            'high'
        );
    }
     
    /**
     * Render the HTML for the Style metabox
     *
     * @since 1.0
    **/
    function render_meta_boxes( $post ) {
        
        // Custom Field
        $meta = get_post_custom( $post->ID );
        $popupID = ! isset( $meta['popupID'][0] ) ? '' : $meta['popupID'][0];
        wp_nonce_field( basename( __FILE__ ), 'tandc_popup' ); 
        
        // Metabox HTML            
?>

        <table class="form-table">
            <tr>
                <td class="popup_meta_box_td" colspan="6">
                    
<?php 
        // Call $post and store old data for retrieval later
        global $post;
        $post_old = $post;
        
        // Determine whether or not sitewide popups exist
        $sitewidePopups = array( 'numberposts' => -1, 'post_type' => 'i-agree-popup', 'meta_query' => array(
            array(
                'key' => 'sitewide',
                'value' => 'on',
                'compare' => 'LIKE'
            )
        ));
        $sitewidePopup = get_posts($sitewidePopups);
        $sitewidePopupExists = !empty($sitewidePopup);
        
        // Disallow Popup selection if a Sitewide popup exists
        if ($sitewidePopupExists) { 
        
            global $post;
            $post_old = $post;
            foreach ($sitewidePopup as $post) : setup_postdata( $post );  
?>
                <a href="<?php bloginfo('url'); ?>/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit">One of your popups</a> is currently set to appear sitewide and is overriding all other popups. If you wish to assign one to this specific post/page, follow the link above, uncheck the 'Sitewide' box and update.

<?php
            endforeach; 
            wp_reset_postdata();
            $post = $post_old;
            setup_postdata( $post );        
        
        } else { 
?>
                If you wish to assign a popup to this post/page, just select one from the list below.<br><br>
                <select name="popupID" id="popupID" style="width:100%;">
                <option value=""<?php if ($popupID == '') {?> selected<?php }?>>--- Select ---</option>

<?php 
            // Get all popups and display in a list for selection
            $args = array( 'posts_per_page' => -1, 'post_type' => 'i-agree-popup', 'meta_query' => array(
                array(
                    'key' => 'sitewide',
                    'value' => 'on',
                    'compare' => '!='
                )
            )); 
            $popups = get_posts($args); 
            global $post;
            $post_old = $post;
            foreach ($popups as $post) : setup_postdata( $post ); 
?>

                <option value="<?php echo get_the_ID(); ?>"<?php if ($popupID == get_the_ID()) {?> selected<?php }?>><?php the_title(); ?></option>
                
<?php

            endforeach; 
            wp_reset_postdata();
            $post = $post_old;
            setup_postdata( $post );    
    
?>

            </select>

<?php 
        } 
        wp_reset_postdata(); 
?>
                                   
                </td>
            </tr>
        </table>

<?php 
    }
    
    /**
     * Save Popup Metadata
     *
     * @since 1.0
    **/
    function save_meta_boxes ( $post_id ) {

        global $post;

        // Verify nonce
        if ( !isset( $_POST['tandc_popup'] ) || !wp_verify_nonce( $_POST['tandc_popup'], basename(__FILE__) ) ) {
            return $post_id;
        }

        // Check Autosave
        if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
            return $post_id;
        }

        // Don't save if only a revision
        if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
            return $post_id;
        }

        // Check permissions
        if ( !current_user_can( 'edit_post', $post->ID ) ) {
            return $post_id;
        }
        
        // Save this custom field
        $meta['popupID'] = ( isset( $_POST['popupID'] ) ? esc_textarea( $_POST['popupID'] ) : '' );

        foreach ( $meta as $key => $value ) {
            update_post_meta( $post->ID, $key, $value );
        }
        
    }
    
}