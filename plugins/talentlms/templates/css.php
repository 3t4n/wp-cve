<div class="container-fluid">
    <h2><i class="fa fa-square-check"></i>&nbsp;<?php esc_html_e('Edit CSS', 'talentlms'); ?></h2>
    
    <div id='action-message' class='<?php echo esc_attr($action_status); ?> tl-admin-edit-css-message-container fade'>
        <p><?php echo esc_html($action_message); ?></p>
    </div>      
    
    <h2><?php esc_html_e('Edit TalentLMS CSS', 'talentlms'); ?></h2>

    <div class="fileedit-sub">
        <div class="alignleft"><h3><?php esc_html_e('Editing', 'talentlms'); ?>: <span><strong><?php echo esc_html($presentCssFileName); ?></strong></span></h3></div>
        <br class="clear">
    </div>  
        
    <form name="talentlms-css-form" method="post" action="<?php echo esc_url(admin_url('admin.php?page=talentlms-css')); ?>">
        <input type="hidden" name="action" value="edit-css">
        <textarea cols="70" rows="25" name="tl-edit-css" id="tl-edit-css"><?php echo esc_textarea($customCssFileContent); ?></textarea>
        <p class="submit">
            <input class="button-primary" type="submit" name="Submit" value="<?php esc_html_e('Update', 'talentlms'); ?>" />
        </p>
    </form>
    
</div>
