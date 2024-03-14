<?php

class ViewTabSettings_cf7b {

  public function display( $settings ) {
    $action_type = isset($settings['action_type']) ? intval($settings['action_type']) : 0;
    $action_value = isset($settings['action_value']) ? $settings['action_value'] : 0;
    ?>
    <div id="cf7b-settings">
      <div class="cf7b-settings-header">
        <h2>Form Settings</h2>
        <span class="dashicons dashicons-arrow-down-alt2"></span>
      </div>

      <div class="cf7b_settings_content cf7b-hidden">
         <div class="cf7b_settings_content_section">
           <h3  class="cf7b_settings_content_section_title">Theme of form</h3>
           <div class="cf7b-settings-row">
              <select name="cf7b_active_theme" <?php echo !CF7B_PRO ? 'disabled' : ''?>>
                <option value="0">Theme not selected</option>
                <?php foreach ($settings['all_themes'] as $cf7b_theme ) { ?>
                <option value="<?php echo intval($cf7b_theme['id']) ?>" <?php if($cf7b_theme['id'] == $settings['active_theme']) { ?> selected <?php } ?>><?php echo esc_html($cf7b_theme['title']) ?></option>
                <?php } ?>
              </select>
             <?php if( !CF7B_PRO ) { ?>
               <a href="<?php echo CF7B_UPGRADE_PRO_URL ?>" class="cf7b-upgrade-mini-button" target="_blank">Upgrade Pro</a>
             <?php } ?>
           </div>
         </div>
         <div class="cf7b_settings_content_section">
           <h3  class="cf7b_settings_content_section_title">Action after submit</h3>
           <div class="cf7b-settings-row">
             <input type="radio" name="cf7b_action_after_submit" value="0" class="cf7b_action_after_submit" <?php echo ($action_type == 0) ? 'checked' : ''; ?>>
             <label>Stay on Form</label>
           </div>
           <div class="cf7b-settings-row">
             <input type="radio" name="cf7b_action_after_submit" value="1" class="cf7b_action_after_submit" <?php echo ($action_type == 1) ? 'checked' : ''; ?>>
             <label>Redirect to Page</label>
           </div>
           <div class="cf7b-settings-row">
             <input type="radio" name="cf7b_action_after_submit" value="2" class="cf7b_action_after_submit" <?php echo ($action_type == 2) ? 'checked' : ''; ?>>
             <label>Redirect to Post</label>
             <?php if( !CF7B_PRO ) { ?>
               <a href="<?php echo CF7B_UPGRADE_PRO_URL ?>" class="cf7b-upgrade-mini-button" target="_blank">Upgrade Pro</a>
             <?php } ?>

           </div>
           <div class="cf7b-settings-row">
             <input type="radio" name="cf7b_action_after_submit" value="3" class="cf7b_action_after_submit" <?php echo ($action_type == 3) ? 'checked' : ''; ?>>
             <label>Custom Text</label>
           </div>
           <div class="cf7b-settings-row">
             <input type="radio" name="cf7b_action_after_submit" value="4" class="cf7b_action_after_submit" <?php echo ($action_type == 4) ? 'checked' : ''; ?>>
             <label>Use custom URL</label>
           </div>

           <div class="cf7b-settings-row">
             <div class="cf7b-pages cf7b-radio-action-div <?php echo ($action_type != 1) ? 'cf7b-hidden' : ''; ?>">
               <select name="cf7b_aftersubmit_page">
                 <option value="0">- Select Page -</option>
                 <?php
                  foreach ( $settings['all_pages'] as $page ) {
                  ?>
                    <option value="<?php echo intval($page->ID); ?>" <?php echo ($action_type == 1 && $action_value == $page->ID) ? 'selected' : '' ?>><?php echo esc_html($page->post_title); ?></option>
                  <?php
                  }
                 ?>
               </select>
             </div>
             <div class="cf7b-posts cf7b-radio-action-div <?php echo ($action_type != 2) ? 'cf7b-hidden' : ''; ?>">
               <select name="cf7b_aftersubmit_post">
                 <option value="0">- Select Post -</option>
                 <?php
                 foreach ( $settings['all_posts'] as $post ) {
                   ?>
                   <option value="<?php echo intval($post->ID); ?>" <?php echo ($action_type == 2 && $action_value == $post->ID) ? 'selected' : '' ?>><?php echo esc_html($post->post_title); ?></option>
                   <?php
                 }
                 ?>
               </select>
             </div>
             <div class="cf7b-text cf7b-radio-action-div <?php echo ($action_type != 3) ? 'cf7b-hidden' : ''; ?>">
               <?php
               $textarea_text =  ($action_type == 3) ? $action_value : '';
               $settings = array( 'textarea_name' => 'cf7b_aftersubmit_text' );
               wp_editor( $textarea_text, 'cf7b_aftersubmit_text', $settings );
               ?>
             </div>
             <div class="cf7b-custom cf7b-radio-action-div <?php echo ($action_type != 4) ? 'cf7b-hidden' : ''; ?>">
              <input type="url" name="cf7b_aftersubmit_custom" value="<?php echo ($action_type == 4) ? esc_url($action_value) : ''?>">
             </div>
           </div>

         </div>

      </div>
    </div>
    <?php
  }
}