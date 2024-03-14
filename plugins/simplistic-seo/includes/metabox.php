<?php
// METABOX
//-----------------------------------------------------------------------

function sseo_register_metabox() {
    $active_post_types = sseo_get_active_post_type();
    add_meta_box( 'sseo-metabox', __('SEO settings', 'simplistic-seo'), 'sseo_render_metabox', $active_post_types, 'normal',
    'low' );
}

add_action( 'add_meta_boxes', 'sseo_register_metabox' );

function sseo_render_metabox() {

    global $post;
    $values = get_post_custom( $post->ID );
    $sseo_title = isset( $values['_sseo_title'] ) ? $values['_sseo_title'][0] : '';
    $sseo_title_default_string = esc_attr(get_option('sseo_title_pattern', '{pagetitle} – {sitetitle}'));
    $sseo_title_default = sseo_generate_title($sseo_title_default_string);
    $sseo_metadescription = isset( $values['_sseo_metadescription'] ) ? $values['_sseo_metadescription'][0] : '';
    $sseo_metadescription_default = sseo_generate_metadescription($post->ID, 'content');
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' ); ?>

<div id="sseo-meta-editor">
    <p class="post-attributes-label-wrapper"><label class="post-attributes-label"
            for="sseo-title"><?php _e('Title', 'simplistic-seo'); ?></label><span id="sseo-title-info"
            class="length-info"></span></p>
    <input type="text" name="sseo-title" id="sseo-title" value="<?php echo $sseo_title; ?>" />
    <div class="sseo-settings-input-placeholders">
        <p><?php _e('Placeholder:', 'simplistic-seo'); ?> <a class="sseo-input-placeholder"
                data-placeholder="{sitetitle}"
                data-target="sseo-title"><?php _e('Sitetitle', 'simplistic-seo'); ?></a><a
                class="sseo-input-placeholder" data-placeholder="{sitedesc}"
                data-target="sseo-title"><?php _e('Sitedescription', 'simplistic-seo'); ?></a><a
                class="sseo-input-placeholder" data-placeholder="{pagetitle}"
                data-target="sseo-title"><?php _e('Pagetitle', 'simplistic-seo'); ?></a></p>
    </div>
    <input type="hidden" name="sseo-pageid" id="sseo-pageid" value="<?php echo $_GET['post']; ?>" />
    <input type="hidden" name="sseo-title-default" id="sseo-title-default" value="<?php echo $sseo_title_default; ?>" />
    <p class="post-attributes-label-wrapper"><label class="post-attributes-label"
            for="sseo-metadescription"><?php _e('Metadescription', 'simplistic-seo'); ?></label><span
            id="sseo-metadescription-info" class="length-info"></span></p>
    <textarea name="sseo-metadescription" class="postbox"
        id="sseo-metadescription"><?php echo $sseo_metadescription; ?></textarea>
    <input type="hidden" name="sseo-metadescription-default" id="sseo-metadescription-default"
        value="<?php echo $sseo_metadescription_default; ?>" />
</div>
<div id="sseo-preview">
    <p class="post-attributes-label-wrapper post-attributes-label"><?php _e('Preview', 'simplistic-seo'); ?></p>
    <div id="sseo-google-preview-wrapper">
        <span
            id="sseo-preview-title"><?php if(!empty($sseo_title)): echo sseo_generate_title($sseo_title); else: echo $sseo_title_default; endif; ?></span>
        <span id="sseo-preview-url"><?php the_permalink(); ?><span id="sseo-preview-url-arrow"></span></span>
        <span
            id="sseo-preview-metadescription"><?php if(!empty($sseo_metadescription)): echo $sseo_metadescription; else: echo $sseo_metadescription_default; endif; ?></span>
    </div>
</div>
<div class="clear"></div>
<?php }


// REGISTER TERM META

add_action( 'admin_menu', 'add_meta_fields_to_terms' );

function add_meta_fields_to_terms() {
    register_meta( 'term', 'sseo_title', '___sanitize_term_meta_text' );
    register_meta( 'term', 'sseo_metadescription', '___sanitize_term_meta_text' );

    //registor category if selected
    $post_types = sseo_get_post_types();
    
    foreach ($post_types as $post_type){
        $option_name_category = 'sseo_activate_type_categorie'.$post_type->name;
        $categories = get_object_taxonomies($post_type->name);
       
            if (get_option( $option_name_category ) == 1){
                foreach( $categories as $categorie){

                    if ($categorie !== "product_shipping_class" && $categorie !== 'pa_filter'){
                        add_action( $categorie .'_edit_form', '___edit_form_field_term_meta_text' ); 
                        add_action( 'edit_' .$categorie ,   'save_term_meta' );
                        add_action( 'create_' . $categorie, 'save_term_meta' );
                    }
                     
                }                           
            }
    }
    
   
}

// SANITIZE DATA

function ___sanitize_term_meta_text ( $value ) {
    return sanitize_text_field ($value);
}

// GETTER (will be sanitized)
function ___get_term_meta_text( $term_id, $term_key ) {
	$value = get_term_meta( $term_id, $term_key, true );
	$value = ___sanitize_term_meta_text( $value );
	return $value;
}


function sseo_save_metabox($post_id) {
	// Bail if we're doing an auto save
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  // if our nonce isn't there, or we can't verify it, bail
  if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
  // if our current user can't edit this post, bail
  if( !current_user_can( 'edit_post',$post_id ) ) return;

	if( isset( $_POST['sseo-title'] ) )
		update_post_meta( $post_id, '_sseo_title', esc_html( $_POST['sseo-title'] ) );

  if( isset( $_POST['sseo-metadescription'] ) )
		update_post_meta( $post_id, '_sseo_metadescription', esc_html( $_POST['sseo-metadescription'] ) );
}

add_action( 'save_post', 'sseo_save_metabox' );







function ___edit_form_field_term_meta_text( $term ) {


	$sseo_title_default_string = esc_attr(get_option('sseo_title_pattern', '{pagetitle} – {sitetitle}'));
	$sseo_title_default = sseo_generate_title($sseo_title_default_string);
	$sseo_metadescription_default = "";

    $sseo_title  = ___get_term_meta_text( $term->term_id, 'sseo_title' );
    
    $sseo_metadescription  = ___get_term_meta_text( $term->term_id, 'sseo_metadescription' );

    if ( ! $sseo_title )
        $sseo_title = ""; 
    
    if ( ! $sseo_metadescription )
        $sseo_metadescription = "";     
    
   
   wp_nonce_field( basename( __FILE__ ), 'term_meta_text_nonce' ); ?>
<div class="sseo-plugin-editor">
    <h2>SSEO</h2>
    <div id="sseo-meta-editor" class="sseo-meta-editor taxonomy">
        <p class="post-attributes-label-wrapper"><label class="post-attributes-label"
                for="sseo-title"><?php _e('Title', 'simplistic-seo'); ?></label><span id="sseo-title-info"
                class="length-info"></span></p>
        <input type="text" name="sseo-title" id="sseo-title" value="<?php echo $sseo_title; ?>" />
        <div class="sseo-settings-input-placeholders">
            <p><?php _e('Placeholder:', 'simplistic-seo'); ?> <a class="sseo-input-placeholder"
                    data-placeholder="{sitetitle}"
                    data-target="sseo-title"><?php _e('Sitetitle', 'simplistic-seo'); ?></a><a
                    class="sseo-input-placeholder" data-placeholder="{sitedesc}"
                    data-target="sseo-title"><?php _e('Sitedescription', 'simplistic-seo'); ?></a><a
                    class="sseo-input-placeholder" data-placeholder="{pagetitle}"
                    data-target="sseo-title"><?php _e('Pagetitle', 'simplistic-seo'); ?></a></p>
        </div>

        <input type="hidden" name="sseo-pageid" id="sseo-pageid" value="<?php echo $_GET['tag_ID']; ?>" />
        <input type="hidden" name="sseo-title-default" id="sseo-title-default"
            value="<?php echo $sseo_title_default; ?>" />
        <p class="post-attributes-label-wrapper"><label class="post-attributes-label"
                for="sseo-metadescription"><?php _e('Metadescription', 'simplistic-seo'); ?></label><span
                id="sseo-metadescription-info" class="length-info"></span></p>
        <textarea name="sseo-metadescription" class="postbox"
            id="sseo-metadescription"><?php echo $sseo_metadescription; ?></textarea>
        <input type="hidden" name="sseo-metadescription-default" id="sseo-metadescription-default"
            value="<?php echo $sseo_metadescription_default; ?>" />
    </div>
    <div id="sseo-preview">
        <p class="post-attributes-label-wrapper post-attributes-label"><?php _e('Preview', 'simplistic-seo'); ?></p>
        <div id="sseo-google-preview-wrapper">
            <span
                id="sseo-preview-title"><?php if(!empty($sseo_title)): echo sseo_generate_title($sseo_title); else: echo $sseo_title_default; endif; ?></span>
            <span id="sseo-preview-url"><?php echo get_category_link($_GET['tag_ID']); ?><span
                    id="sseo-preview-url-arrow"></span></span>
            <span
                id="sseo-preview-metadescription"><?php if(!empty($sseo_metadescription)): echo $sseo_metadescription; else: echo $sseo_metadescription_default; endif; ?></span>
        </div>
    </div>
</div>
<div class="clear"></div>

<?php }


// SAVE TERM META (on term edit & create)



function save_term_meta( $term_id ) {

    // verify the nonce --- remove if you don't care
    if ( ! isset( $_POST['term_meta_text_nonce'] ) || ! wp_verify_nonce( $_POST['term_meta_text_nonce'], basename( __FILE__ ) ) )
        return;
    
	
	// get the values
    $old_value_sseo_title  = ___get_term_meta_text( $term_id, 'sseo_title' );
    $new_value_sseo_title = isset( $_POST['sseo-title'] ) ? ___sanitize_term_meta_text ( $_POST['sseo-title'] ) : '';
    
    $old_value_sseo_metadescription  = ___get_term_meta_text( $term_id, 'sseo_metadescription' );
    $new_value_sseo_metadescription = isset( $_POST['sseo-metadescription'] ) ? ___sanitize_term_meta_text ( $_POST['sseo-metadescription'] ) : '';

		// save the values
    if ( $old_value_sseo_title && '' === $new_value_sseo_title ) {
    	delete_term_meta( $term_id, 'sseo_title' );
    } else if ( $old_value_sseo_title !== $new_value_sseo_title ) {
	    update_term_meta( $term_id, 'sseo_title', $new_value_sseo_title );
    }
        
   	if ( $old_value_sseo_metadescription && '' === $new_value_sseo_metadescription ) {
   		delete_term_meta( $term_id, 'sseo_metadescription' );
   	} else if ( $old_value_sseo_metadescription !== $new_value_sseo_metadescription ) {
	   	update_term_meta( $term_id, 'sseo_metadescription', $new_value_sseo_metadescription );
	  }
	  
} // end function

//https://gist.github.com/ms-studio/aeae733f5fd9fc524bbc
?>