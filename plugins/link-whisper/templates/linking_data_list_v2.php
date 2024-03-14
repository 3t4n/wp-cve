<div class="wpil_notice" id="wpil_message" <?php if(empty($phrase_groups) || 'post' !== $post->type){ echo 'style="display: none;"'; } ?>>
<?php if( empty( get_option(WPIL_PREMIUM_NOTICE_DISMISSED, '') ) ){ ?>
    <div id="lw_banner">
        <img class="close" src="<?php echo esc_url(WP_INTERNAL_LINKING_PLUGIN_URL . 'images/icon_delete.png'); ?>">
        <div class="title"><?php _e('Upgrade to Link Whisper Premium', 'wpil'); ?></div>
        <div class="features">
            <div><?php _e('+ Add internal links with a single click!', 'wpil'); ?></div>
            <div><?php _e('+ Get inbound internal link suggestions from the reports screen.', 'wpil'); ?></div>
            <div><?php _e('+ Add inbound internal links directly from reports with a single click.', 'wpil'); ?></div>
            <div><?php _e('+ Customize the anchor text by clicking the words you want.', 'wpil'); ?></div>
            <div><?php _e('+ No more copying and pasting links, just click and done!', 'wpil'); ?></div>
            <div><?php _e('+ Save hours of time and gain more control over your internal links.', 'wpil'); ?></div>
        </div>
        <a href="<?php echo esc_url(WPIL_STORE_URL); ?>" target="blank"><?php _e('Get Link Whisper Premium', 'wpil'); ?></a>
    </div>
<?php } ?>
</div>
<div class="best_keywords outbound">
<?=Wpil_Base::showVersion()?>
    <p>
        <div style="margin-bottom: 15px;">
            <input type="hidden" class="wpil-suggestion-input wpil-suggestions-can-be-regenerated" value="0" data-suggestion-input-initial-value="0">
            <br>
            <?php if(!empty($categories)){ ?>
            <input style="margin-bottom: -5px;" type="checkbox" name="same_category" id="field_same_category" class="wpil-suggestion-input" data-suggestion-input-initial-value="<?php echo !empty($same_category) ? 1: 0;?>" <?=(isset($same_category) && !empty($same_category)) ? 'checked' : ''?>> <label for="field_same_category"><?php _e('Only Show Link Suggestions in the Same Category as This Post', 'wpil'); ?></label>
            <br>
            <div class="same_category-aux wpil-aux">
                <select multiple name="wpil_selected_category" class="wpil-suggestion-input wpil-suggestion-multiselect" data-suggestion-input-initial-value="<?php echo implode(',', $selected_categories);?>" style="width: 400px;">
                    <?php foreach ($categories as $cat){ ?>
                        <option value="<?php echo $cat->term_taxonomy_id; ?>" <?php echo (in_array($cat->term_taxonomy_id, $selected_categories, true) || empty($selected_categories))?'selected':''; ?>><?php esc_html_e($cat->name)?></option>
                    <?php } ?>
                </select>
                <br>
                <br>
            </div>
            <br class="same_category-aux wpil-aux">
                <?php if(!empty($same_category)){ ?>
                <style>
                    .best_keywords .same_category-aux{
                        display: inline-block;
                    }
                </style>
                <?php } ?>
            <?php } ?>
            <?php if(!empty($tags)){ ?>
            <input type="checkbox" name="same_tag" id="field_same_tag" class="wpil-suggestion-input" data-suggestion-input-initial-value="<?php echo !empty($same_tag) ? 1: 0;?>"  <?=!empty($same_tag) ? 'checked' : ''?>> <label for="field_same_tag"><?php _e('Only Show Link Suggestions with the Same Tag as This Post', 'wpil'); ?></label>
            <br>
            <div class="same_tag-aux wpil-aux">
                <select multiple name="wpil_selected_tag" class="wpil-suggestion-input wpil-suggestion-multiselect" data-suggestion-input-initial-value="<?php echo implode(',', $selected_tags);?>" style="width: 400px;">
                    <?php foreach ($tags as $tag){ ?>
                        <option value="<?php echo $tag->term_taxonomy_id; ?>" <?php echo (in_array($tag->term_taxonomy_id, $selected_tags, true))?'selected':''; ?>><?php esc_html_e($tag->name)?></option>
                    <?php } ?>
                </select>
                <br>
                <br>
            </div>
            <br class="same_tag-aux wpil-aux">
                <?php if(!empty($same_tag)){ ?>
                <style>
                    .best_keywords .same_tag-aux{
                        display: inline-block;
                    }
                </style>
                <?php } ?>
            <?php } ?>
            <br />
            <br />
            <button id="wpil-regenerate-suggestions" class="button disabled" disabled><?php _e('Regenerate Suggestions', 'wpil'); ?></button>
            <?php if(!empty($select_post_types)){ ?>
            <style>
                .best_keywords .select_post_types-aux{
                    display: inline-block;
                }
            </style>
            <?php } ?>
            <script>
                jQuery('.wpil-suggestion-multiselect').select2();
            </script>
        </div>
        <a href="<?=esc_url($post->getLinks()->export)?>" target="_blank">Export data for support</a><br>
        <a href="<?=esc_url($post->getLinks()->excel_export)?>" target="_blank">Export Post Data to Excel</a><br>
    </p>
    <?php require WP_INTERNAL_LINKING_PLUGIN_DIR . 'templates/table_suggestions.php'; ?>
</div>
<div class="wpil_notice" id="wpil_message" style="text-align: center; margin-top: 20px; <?php if(empty($phrases)){ echo 'display: none;'; } ?>">
    <p><?php _e('Tip: Link Whisper Premium automatically adds the links to the post content. All you have to do is select the links you want to add and click the "Add Links" button!', 'wpil'); ?></p>
    <p><a href="<?php echo esc_url(WPIL_STORE_URL); ?>" target="blank"><?php _e('Read more about upgrading!', 'wpil'); ?></a></p>
</div>

