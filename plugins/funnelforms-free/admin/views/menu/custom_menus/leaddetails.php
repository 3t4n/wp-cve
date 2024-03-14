<div class="af2_leaddetails_page_wrapper">
    <div class="af2_card af2_leaddetails">
        <div class="af2_card_block">
            <div class="af2_leaddetails_controller_buttons">
                <a class="af2_btn_link mb10" href="<?php _e(admin_url('/admin.php?page='.FNSF_LEADS_SLUG )); ?>">
                    <div id="af2_back_to_leads" class="af2_btn af2_btn_secondary"><i class="fas fa-arrow-left"></i><?php _e('Back to overview', 'funnelforms-free'); ?></div>
                </a>
                <?php include FNSF_AF2_MENU_HOOKS_SNIPPET; ?>
            </div>
            <div class="af2_leaddetails_table">
            <?php $i = 1; ?>
            <?php foreach($af2_custom_contents as $af2_custom_content) { ?>
                <div class="af2_leaddetails_table_row_wrapper">
                    <?php
                            $leadSty = 'first_row';
                            if($i % 2 == 0){ $leadSty = 'second_row';  }
                     ?>
                    <div class="af2_leaddetails_table_row af2_leaddetails_row_left <?php _e($leadSty); ?>">
                        <p><?php _e($af2_custom_content['label']); ?></p>
                    </div>
                    <div class="af2_leaddetails_table_row af2_leaddetails_row_right <?php _e($leadSty); ?>">
                        <p><?php _e( esc_html($af2_custom_content['value'])); ?></p>
                    </div>
                </div>
            <?php $i++; ?>
            <?php }; ?>
            </div>
        </div>
    </div>
</div>
