<div class="af2_custom_builder_wrapper af2_formularbuilder_settings">

    <div id="af2_manage_fonts" class="af2_modal" 
        data-heading="<?=__('Funnelforms Fonts', 'af2_multilanguage')?>"
        data-close="<?=__('Close', 'af2_multilanguage')?>">
        
        <!-- Modal content -->
        <div class="af2_modal_content">
            <div class="af2_add_font_header">
                <form id="af2_upload_font" enctype="multipart/form-data">
                    <input type="file" name="af2FontFile" id="af2FontFile" accept=".ttf, .otf, .woff, .woff2">
                    <button type="button" class="af2_upload_file_button af2_btn af2_btn_secondary_outline"><?= __('Upload File', 'af2_multilanguage') ?></button>
                </form>
            </div>
            <div class="af2_font_wrapper_container">
                <?php foreach($af2_builder_custom_contents['files'] as $af2_font) { ?>
                    <div class="af2_font_wrapper">
                        <div class="af2_font_name"><?=$af2_font?></div>
                        <div class="af2_font_delete af2_btn af2_btn_primary" data-deletefile="<?=$af2_font?>"><i class="fas fa-trash"></i></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="af2_card_table">
        <div class="af2_card af2_builder_editable_object" data-editcontentid="general_settings">
            <div class="af2_card_block">
                <div class="af2_card_label">
                    <div class="af2_fb_settings_icon"><i class="fas fa-cog"></i></div>
                    <h5><?php _e('General settings', 'funnelforms-free'); ?></h5>
                </div>
            </div>
        </div>
        <div class="af2_card af2_builder_editable_object" data-editcontentid="desgin_settings">
            <div class="af2_card_block">
                <div class="af2_card_label">
                    <div class="af2_fb_settings_icon"><i class="fas fa-cog"></i></div>
                    <h5><?php _e('Design settings', 'funnelforms-free'); ?></h5>
                </div>
            </div>
        </div>
        <div class="af2_card af2_builder_editable_object" data-editcontentid="individual_colors">
            <div class="af2_card_block">
                <div class="af2_card_label">
                    <div class="af2_fb_settings_icon"><i class="fas fa-fill"></i></div>
                    <h5><?php _e('Custom colors', 'funnelforms-free'); ?></h5>
                </div>
            </div>
        </div>
        <div class="af2_card af2_builder_editable_object" data-editcontentid="font_sizes">
            <div class="af2_card_block">
                <div class="af2_card_label">
                    <div class="af2_fb_settings_icon"><i class="fas fa-text-height"></i></div>
                    <h5><?php _e('Font sizes', 'funnelforms-free'); ?></h5>
                </div>
            </div>
        </div>
        <div class="af2_card af2_builder_editable_object" data-editcontentid="border_radius">
            <div class="af2_card_block">
                <div class="af2_card_label">
                    <div class="af2_fb_settings_icon"><i class="fas fa-border-style"></i></div>
                    <h5><?php _e('Border radius', 'funnelforms-free'); ?></h5>
                </div>
            </div>
        </div>
        <div class="af2_card af2_builder_editable_object" data-editcontentid="contact_form">
            <div class="af2_card_block">
                <div class="af2_card_label">
                    <div class="af2_fb_settings_icon"><i class="fas fa-align-justify"></i></div>
                    <h5><?php _e('Contact form', 'funnelforms-free'); ?></h5>
                </div>
            </div>
        </div>
        <div id="af2_manage_fonts_btn" class="af2_card af2_modal_btn" data-target="af2_manage_fonts">
            <div class="af2_card_block">
                <div class="af2_card_label">
                    <div class="af2_fb_settings_icon"><i class="fas fa-font"></i></div>
                    <h5><?=__('Manage own fonts', 'af2_multilanguage')?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="af2_preview_wrapper mt50">
        <div class="af2_btn af2_btn_primary af2_show_preview"><?php _e('Show preview', 'funnelforms-free'); ?></div>
    </div>
</div>