<?php 
$post_types = get_post_types(array('public' => true));
$available_posts = [];
foreach($post_types as $post_type){
    if($post_type == 'attachment'){continue;}
    $post_type_data = get_post_type_object($post_type);
    $available_posts[$post_type]['label'] = $post_type_data->label;
    $items = get_posts( array( 'numberposts' => -1, 'post_type' => $post_type, 'suppress_filters' => false ) ); foreach($items as $item){
        $available_posts[$post_type]['posts'][$item->ID] = $item->post_title;
    }
}
$dataSettingName = cmicpt_get_settings_name();
$cmicptData = json_decode(get_site_option($dataSettingName));

?>
<div class="wrap cmicpt-wrap">
    <h2>Current Menu Item for Custom Post Types</h2>
    <?php if(isset($_GET['cmicpt-message']) && $_GET['cmicpt-message'] == 'settings_saved'):?>
    <div class="notice notice-success"> 
    	<p><strong>Settings saved.</strong></p>
    </div>
    <?php endif;?>

    <?php if(isset($_GET['cmicpt-message']) && $_GET['cmicpt-message'] == 'invalid_nonce'):?>
    <div class="notice notice-error"> 
    	<p><strong>Settings not saved. Please regresh the page and try again.</strong></p>
    </div>
    <?php endif;?>

    <?php if((function_exists('pll_current_language') && !pll_current_language()) || (function_exists('icl_object_id') && ICL_LANGUAGE_CODE == 'all')):?>
        <div id="setting-error-settings_updated" class="error settings-error"> 
            <p><strong>Please select a language from the admin bar.</strong></p>
        </div>
    <?php else:?>
    <form method="post" action="options-general.php?page=current-menu-item-cpt">
       
        <table class="form-table">
            <tbody>                
                <tr>   
                    <th colspan="2">
                        <h3>Assign custom post types to pages</h3>
                        <p>Select which page will be active in the nav menu when you are on the single page of a custom post type.</p>
                    </th>
                </tr>
                
                <?php foreach($postTypes as $postType):?>
                <tr>
                    <th scope="row">
                        <label title="<?php echo $postType->labels->name;?> (<?php echo $postType->name;?>)" for="<?php echo $postType->name;?>">Assign "<strong><?php echo $postType->labels->name;?></strong>" to</label>
                    </th>
                    <td>
                        <select name="<?php echo $postType->name;?>" id="<?php echo $postType->name;?>">
                            <option value=""></option>
                            <?php foreach($available_posts as $post_types):?>
                                <optgroup label="<?php echo $post_types['label'];?>">
                                    <?php foreach($post_types['posts'] as $page_id => $page_title): ?>
                                        <option value="<?php echo $page_id;?>"<?php if(!empty($cmicptData->{$postType->name}) && $cmicptData->{$postType->name} == $page_id):?> selected="selected"<?php endif;?>><?php echo $page_title;?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                
                            <?php endforeach;?>	
                        </select>
                    </td>
                </tr>
                <?php endforeach;?>
                
                <tr>   
                    <th colspan="2"><h3>Options</h3></th>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="custom_class_name">Custom Item Class</label>
                    </th>
                    <td>
                        <input class="regular-text ltr" type="text" name="custom_class_name" id="custom_class_name" value="<?php echo (isset($cmicptClass->item)) ? esc_attr($cmicptClass->item) : '';?>" /><br />
                        <small>You can enter multiple classes separated by a space. The default class is <em>current-menu-item</em>.</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="custom_parent_class_name">Custom Parent Class</label>
                    </th>
                    <td>
                        <input class="regular-text ltr" type="text" name="custom_parent_class_name" id="custom_parent_class_name" value="<?php echo (isset($cmicptClass->parent)) ? esc_attr($cmicptClass->parent) : '';?>" /><br />
                        <small>You can enter multiple classes separated by a space. The default class is <em>current-menu-ancestor</em>.</small>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="show_builtin_post_types">Show built-in Post Types?</label>
                    </th>
                    <td>
                        <input name="show_builtin_post_types" type="checkbox" id="show_builtin_post_types" value="1" <?php echo (isset($cmicptClass->showBuiltin) && $cmicptClass->showBuiltin == 1) ? 'checked="checked"' : '';?> />                        
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field( 'cmicpt_save_settings', 'cmicpt_token', false ); ?>
    
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" /></p>


    </form>
    <?php endif;?>
    
    <div class="cmicpt-leave-review">Found this plugin useful? <a href="https://wordpress.org/plugins/current-menu-item-for-custom-post-types/#reviews" target="_blank">Consider leaving a review on Wordpress.org</a>. Thank you :)</div>
    
</div>