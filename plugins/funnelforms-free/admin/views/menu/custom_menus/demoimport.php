<div class="af2_demoimport_wrapper af2_card_table">
    <?php foreach($af2_custom_contents as $af2_custom_content) { ?>
        <?php if($af2_custom_content['active'] == -1) { ?>
            <div class="af2_card invisible">
                <div class="af2_card_block">
                </div>
            </div>
        <?php } else { ?>
            <div class="af2_card af2_modal_btn" data-target="af2_<?php _e($af2_custom_content['filename']); ?>">
                <div class="af2_card_block">
                    <h4><?php   _e($af2_custom_content['name']); ?></h4>
                    <p><?php  _e($af2_custom_content['description']); ?></p>
                </div>
            </div>
        <?php }; ?>

        <?php if(isset($af2_custom_content['filename']))  { ?>
        <div id="af2_<?php _e($af2_custom_content['filename']) ; ?>" class="af2_modal"
            data-class="af2_demoimport_modal"
            data-target="af2_demoimport_modal_<?php _e($af2_custom_content['filename']) ; ?>"
            data-sizeclass="moderate_size"
            data-bottombar="true"
            data-heading="<?php _e('Import demo?', 'funnelforms-free'); ?>"
            data-close="<?php _e('Close', 'funnelforms-free'); ?>">

            <!-- Modal content -->
            <div class="af2_modal_content">
                <h4><?php _e( $af2_custom_content['name']) ; ?></h4>
                <p><?php  _e($af2_custom_content['description']) ; ?></p>
            </div>

            <div class="af2_modal_bottombar">
                <div id="af2_demoimport_<?php _e($af2_custom_content['filename']) ; ?>" class="af2_btn af2_btn_primary af2_import_file" data-filename="<?php _e($af2_custom_content['filename']) ; ?>"><i class="fas fa-file-import"></i><?php _e('Import', 'funnelforms-free'); ?>
                    <span class="af2_hide loading">&nbsp;<i class="fas fa-circle-notch fa-spin"></i></span>
                </div>
            </div>
        </div>
        <?php }; ?>
    <?php }; ?>
</div>