<div id="af2_answers_container" class="af2_answers_container af2_array_dropzone_before af2_array_draggable_restrict">
    <div id="af2_answer_wrapper_add">
        <i class="fas fa-plus"></i>
    </div>
</div>

<div id="af2_delete_answer_modal" class="af2_modal"
    data-class="af2_delete_answer_modal"
    data-target="af2_delete_answer_modal"
    data-sizeclass="moderate_size"
    data-bottombar="true"
    data-confirmationid="af2_confirm_deletion"
    data-heading="<?php _e('Confirm deletion?', 'funnelforms-free');?>"
    data-close="<?php _e('Cancel', 'funnelforms-free');?>">

  <!-- Modal content -->
  <div class="af2_modal_content">
        <p><?php _e( 'This answer may be linked in an active form. Please confirm that you want to delete this answer. We recommend you to check the connections in your forms.', 'funnelforms-free');?></p>
    </div>

    <div class="af2_modal_bottombar">
        <div id="af2_confirm_deletion" class="af2_btn af2_btn_secondary_outline"><?php _e('Delete', 'funnelforms-free');?></div>
    </div>
</div>
