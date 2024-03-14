<?php
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_style('rm-jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css');
?>
    <div class="rmagic">

    <!-----Operations bar Starts----->

    <div class="operationsbar">
        <div class="rmtitle"><?php echo esc_html(RM_UI_Strings::get("TITLE_CSTATUS_MANAGER")); ?></div>
    
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo esc_html(RM_UI_Strings::get("LABEL_BACK")); ?></a></li>
                
                <li><a href="<?php echo admin_url('admin.php?page=rm_form_add_cstatus&rm_form_id='.$data->form_id); ?>"><?php echo esc_html(RM_UI_Strings::get("LABEL_NEW_STATUS")); ?></a></li>
                <li id="rm-delete-cstatus" class="rm_deactivated" onclick="jQuery.rm_do_action('rm_cstatus_manager_form', 'rm_cstatus_remove')"><a href="javascript:void(0)"><?php echo esc_html(RM_UI_Strings::get("LABEL_DELETE")); ?></a></li>
                <li><a target="_blank" href="https://registrationmagic.com/manage-user-registration-status-wordpress-custom-labels/"><?php _e('Documentation','custom-registration-form-builder-with-submission-manager'); ?></a></li>
                <li class="rm-form-toggle">
                    <?php if (count($data->forms) !== 0)
                    {
                        echo esc_html(RM_UI_Strings::get('LABEL_TOGGLE_FORM'));
                        ?>
                        <select id="rm_form_dropdown" name="form_id" onchange = "rm_load_page(this, 'form_manage_cstatus')">
                            <?php
                            foreach ($data->forms as $form_id => $form)
                                if ($data->form_id == $form_id) { ?>
                                    <option value=<?php echo esc_html($form_id); ?> selected><?php echo esc_html($form); ?></option>
                                <?php } else { ?>
                                    <option value=<?php echo esc_html($form_id); ?>><?php echo esc_html($form); ?></option>;
                            <?php } ?>
                        </select>
                        <?php
                    } 
                    ?>
                </li>
            </ul>
        </div>

    </div>
    <!--  Operations bar Ends----->


    <!-------Content area Starts----->

    <?php
    if(count($data->custom_status) === 0){
        ?><div class="rmnotice-container">
            <div class="rmnotice">
        <?php echo esc_html(RM_UI_Strings::get('MSG_NO_FORM_CSTATUS_MAN')); ?>
            </div>
        </div>
           
        <div class="rmnotice-container rm-custom-status-notice" style="margin-top: 10px;display: inline-block;width: 100%;">
            <div class="rmnotice"><?php esc_html_e('You can assign statuses to registrations from the ','custom-registration-form-builder-with-submission-manager'); ?><a href="admin.php?page=rm_submission_manage"><?php esc_html_e('inbox','custom-registration-form-builder-with-submission-manager'); ?></a></div>
        </div>
            
            <?php
    }
   else
    {?>
    <div class="rmnotice-container rm-custom-status-notice"  style="margin-bottom: 20px;display: inline-block;width: 100%;">
        <div class="rmnotice"><?php esc_html_e('You can assign statuses to registrations from the ','custom-registration-form-builder-with-submission-manager'); ?><a href="admin.php?page=rm_submission_manage"><?php esc_html_e('inbox','custom-registration-form-builder-with-submission-manager'); ?></a></div>
    </div>
    
    <div class="form-cstatus-table-wrapper">
        <form action="" method="post" id="rm_cstatus_manager_form">
        <table class="rm-form-cstatus rmagic-table">
            <tr>
                <th><input type="checkbox" id="rm_cstatus_select_all" onchange="selectAll(this)" /></th>
                <th>Color</th>
                <th>Label</th>
                <th>&nbsp;</th>
            </tr>
       <?php foreach($data->custom_status as $key=>$custom_status): ?>
            <tr>
                <td><input class="rm-cstatus-index" type="checkbox" name="rm_cstatus_index[]" value="<?php echo esc_attr($key); ?>"/></td>
                <td><div class="rm-cstatus-color" style="background-color: #<?php echo esc_attr($custom_status['color']); ?>">&nbsp;<span style="border-color: #<?php echo esc_attr($custom_status['color']); ?>"></span></div></td>
                <td><?php echo esc_html($custom_status['label']); ?></td>
                <td><a href="<?php echo admin_url('admin.php?page=rm_form_add_cstatus&rm_form_id='.$data->form_id.'&cstatus_id='.$key); ?>"><?php echo esc_html(RM_UI_Strings::get('LABEL_VIEW')); ?></a></td>
            </tr>     
       <?php endforeach; ?>
        </table>
            <input type="hidden" id="rm_slug_input_field" name="remove_cstatus" />    
        </form>    
    </div>
    <?php 
    }
?>
</div>

<script>
function selectAll(obj){
    if(jQuery(obj).is(':checked')){
        jQuery('.rm-cstatus-index').attr('checked',true);
        jQuery('#rm-delete-cstatus').removeClass('rm_deactivated');
    }
    else{
        jQuery('.rm-cstatus-index').attr('checked',false);
        jQuery('#rm-delete-cstatus').addClass('rm_deactivated');
    }
}
jQuery(document).ready(function(){
    jQuery('.rm-cstatus-index').change(function(){
        if(jQuery('.rm-cstatus-index:checked').length>0){
           jQuery('#rm-delete-cstatus').removeClass('rm_deactivated');
           return;
        }
        jQuery('#rm-delete-cstatus').addClass('rm_deactivated');
        jQuery('#rm_cstatus_select_all').attr('checked',false);
    });
});
</script>

</div>