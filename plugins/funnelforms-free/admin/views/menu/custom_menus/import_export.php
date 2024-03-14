<div class="af2_import_export_wrapper">
    <div class="af2_card">
        <form id="af2_import" name="af2_import_form" enctype="multipart/form-data">
            <div class="af2_card_block af2_imp_exp_flex">
                <div class="af2_import_export_left_block">
                    <h4><?php _e('Import', 'funnelforms-free'); ?></h4>
                    <label id="af2_import_upload_label" data-selectedlabel="<?php _e('File chosen', 'funnelforms-free'); ?>" class="af2_btn af2_btn_secondary" for="af2_import_upload"><?php _e('Choose file...', 'funnelforms-free'); ?></label>
                    <input id="af2_import_upload" type="file" name="af2_import_data">
                </div>
                <button disabled type="submit" id="af2_import_button" class="af2_btn af2_btn_primary af2_btn_disabled"><i class="fas fa-file-import"></i><?php _e('Import', 'funnelforms-free'); ?>
                    <span class="af2_hide loading">&nbsp;<i class="fas fa-circle-notch fa-spin"></i></span>
                </button>
            </div>
        </form>
    </div>
    <div class="af2_card">
        <div class="af2_card_block af2_imp_exp_flex">
            <div class="af2_import_export_left_block">
                <h4><?php _e('Export', 'funnelforms-free'); ?></h4>
                <p><?php _e('Export all questions, contact forms and forms', 'funnelforms-free'); ?></p>
            </div>
            <div id="af2_export" class="af2_btn af2_btn_primary"><i class="fas fa-file-export"></i><?php _e('Export', 'funnelforms-free'); ?></div>
        </div>
    </div>
</div>