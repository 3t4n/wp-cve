<?php
function acf_flm_get_template_type(){
    global $post, $pagenow;

    // Post
    if($pagenow === 'post.php' && isset($post->ID))
        return $post->ID;

    if($pagenow === 'post-new.php')
        return 'new';

    // Taxonomy
    if($pagenow === 'term.php' && isset($_GET['tag_ID']))
        return 'term_' . filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);

    if($pagenow === 'edit-tags.php')
        return 'new';

    //User
    if($pagenow === 'user-new.php')
        return 'new';

    if($pagenow === 'user-edit.php')
        return 'user_' . filter_var($_GET['user_id'], FILTER_SANITIZE_NUMBER_INT);

    //Options pages
    if(isset($_GET['page']) && !empty($_GET['page'])){
        $page           = $_GET['page'];
        $options_pages  = acf_get_options_pages();

        if(array_key_exists($page, $options_pages))
            return $options_pages[$page]['post_id'];
    }    

    return false;
}


add_filter('acf/get_field_label', 'acf_flm_add_button_section', 99, 2);
function acf_flm_add_button_section($label, $field){

    /*
        Verification before adding button.
        Is not the best way but for the moment the only way to add the buttons only for the flexible parent of the page
    */

    //Check if is admin
    if( !is_admin() )
        return $label;

    //Check if it's the page of field group options
    if(get_post_type() == 'acf-field-group')
        return $label;        
        
    //Check if the field is a flexible content
    if( ($field['type'] != 'flexible_content') )
        return $label;

    //Check if the field has parent and if his parents are a group of fields
    if( !isset($field['parent']) || empty($field['parent'])  || !acf_get_field_group($field['parent']) )
        return $label;
    
    //Check if the field is a clone or not
    if( (isset($field['_clone'])) && (!empty($field['_clone'])) )
        return $label;

    //Get the template page
    $target = acf_flm_get_template_type();

    ob_start(); ?>

    <div style="font-weight:normal; display:none;" class="acf_flm_add_layout_section" data-target="<?php echo $target; ?>">
        <a class="acf-flm-btn-copy-all-layouts button" style="display: none;">
            <?php echo __( "Copy all layouts", 'acf-flexible-layouts-manager' ); ?>
            <input class="acf-flm-input-copy-all-layouts" type="text" value="" style="clip: rect(0,0,0,0); clip-path: rect(0,0,0,0); position: absolute;">
        </a>
        <a class="acf-flm-btn-past-layout button" style="display: none;">
            <?php echo __( "Paste layout(s)", 'acf-flexible-layouts-manager' ); ?>
        </a>
        <a class="acf-flm-btn-select-layout button" style="display: none;">
            <?php echo __( "Import layout(s)", 'acf-flexible-layouts-manager' ); ?>
        </a>
    </div>

    <?php $label .= ob_get_clean();

    return $label;
}