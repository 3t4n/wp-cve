<?php include FNSF_AF2_ALL_ICONS_PATH; ?>

<div id="af2_fontawesome_iconpicker" class="af2_modal"
    data-class="af2_fontawesome_iconpicker"
    data-target="af2_fontawesome_iconpicker"
    data-sizeclass="full_size"
    data-bottombar="true"
    data-heading="<?php _e('Select icon', 'funnelforms-free'); ?>"
    data-close="<?php _e('Close', 'funnelforms-free'); ?>">

  <!-- Modal content -->
  <div class="af2_modal_content">
    <div class="af2_iconpicker_search_wrapper">
      <i class="fas fa-search"></i>
      <input type="text" id="af2_iconpicker_search" class="af2_iconpicker_search" placeholder="<?php _e('Search...', 'funnelforms-free'); ?>">
    </div>
    <div class="af2_iconpicker_table">
        <?php foreach($af2_all_font_awesome_icons as $icon) { ?>
            <div class="af2_iconpicker_icon" data-iconid="<?php _e($icon); ?>">
                <i class="<?php _e($icon); ?>"></i>
            </div>
        <?php }; ?>
    </div>
  </div>

  <div class="af2_modal_bottombar">
    <div id="af2_iconpicker_save" class="af2_btn af2_btn_primary"><?php _e('Save', 'funnelforms-free'); ?></div>
  </div>

</div>