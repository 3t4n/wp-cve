<?php $same_category = !empty(get_user_meta(get_current_user_id(), 'wpil_same_category_selected', true)) ? '&same_category=true': ''; ?>
<div data-wpil-ajax-container="" data-wpil-ajax-container-url="<?=esc_url(admin_url('admin.php?post_id=' . $post_id . '&page=link_whisper&type=outbound_suggestions_ajax'.(!empty($term_id)?'&term_id='.$term_id:'').(!empty($user->ID) ? '&nonce='.wp_create_nonce($user->ID .'wpil_suggestion_nonce') : '')) . $same_category)?>" class="wpil_keywords_list wpil_styles">
    <div class="progress_panel loader">
        <div class="progress_count" style="width: 100%"><?php _e('Processing Link Suggestions', 'wpil');?></div>
    </div>
    <div class="wpil-process-loading-error-message">
        <p><?php _e('The suggestions are taking longer than normal, so there might have been an error.', 'wpil'); ?></p>
        <p><?php _e('If you don\'t see any progress in the next 2 minutes, please try reloading the page and re-starting the process.', 'wpil'); ?></p>
    </div>
</div>
