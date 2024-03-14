<?php
if (!defined('ABSPATH')) {
    exit;
}

global $post, $action;

$postObject = get_post_type_object('automation-workflow');
$canPublish = !is_null($postObject) ? current_user_can($postObject->cap->publish_posts) : false;
$active     = get_post_meta($post->ID, 'active', true);
$nonce      = wp_create_nonce('wunderautomation_edit_page_nonce_' . 'automation-workflow');
$utm        = '?utm_source=dashboard&utm_medium=workfloweditor&utm_campaign=installed_users';

?>
<script>
    // Allow other plugins to add mixins before we start our app
    var appMixins = [];
    var triggerMixins = [];
    var stepsMixins = [];
</script>

<div id="workflow-editor-app">

    <input type="hidden" name="wunderautomation_save_post" value="edit_page"/>
    <input type="hidden" name="wunderautomation_save_post_nonce" value="<?php esc_attr_e($nonce);?>"/>
    <div class="submitbox" id="submitpost">

        <div style="display:none;">
            <?php submit_button(__('Save'), 'wunderauto', 'save'); ?>
        </div>
        <table>
            <tr>
                <td>
                    <div>
                        <label><?php _e('Status:', 'wunderauto') ?></label>
                        <select name="active">
                            <option value="active" <?php esc_attr_e($active == 'active' ? 'selected' : '')?>>
                                <?php esc_html_e('Active', 'wunderauto');?>
                            </option>
                            <option value="disabled" <?php esc_attr_e($active == 'disabled' ? 'selected' : '')?>>
                                <?php esc_html_e('Disabled', 'wunderauto');?>
                            </option>
                        </select>
                </td>
            </tr>

            <?php if ($post->post_status !== 'auto-draft') :?>
                <tr>
                    <td>
                        <div>
                            <br>
                            <?php printf(
                                __('<b>Modified:</b><br> %s %s', 'wunderauto'),
                                get_the_modified_date(),
                                get_the_modified_time()
                            );
                            ?>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>

        </table>

        <div id="major-publishing-actions">
            <div id="delete-action">
                <?php
                if (current_user_can("delete_post", $post->ID)) :
                    if (EMPTY_TRASH_DAYS < 1) {
                        $delete_text = __('Delete Permanently', 'wunderauto');
                    } else {
                        $delete_text = __('Move to Trash', 'wunderauto');
                    }
                    $link = get_delete_post_link($post->ID);
                    $link = is_string($link) ? $link : '';
                    ?>
                    <a class="submitdelete deletion" href="<?php esc_attr_e($link, 'wunderauto')?>">
                        <?php esc_html_e($delete_text);?>
                    </a>
                <?php endif ?>
            </div>

            <div id="publishing-action">
                <span class="spinner"></span>
                <input name="original_publish"
                       type="hidden" id="original_publish"
                       value="<?php esc_html_e('Save', 'wunderauto') ?>" />
                <input name="save" type="submit" class="button button-primary" id="publish"
                       value="<?php esc_html_e('Save', 'wunderauto') ?>"
                       @click="save()"/>
                <input name="workflow" type="hidden" v-model="formData"/>
            </div>
            <div class="clear"></div>
        </div>
        <br>
        <a href="<?php echo esc_url(wa_make_link('/docs-category/wunderautomation/', $utm))?>"
           target="_blank">Help? Find the docs here.</a>
    </div>

    <teleport to="#triggersmetabox">
        <triggers ref="trigger"></triggers>
    </teleport>

    <teleport to="#stepsmetabox">
        <steps ref="steps"></steps>
    </teleport>

    <teleport to="#schedulemetabox">
        <schedule ref="schedule"></schedule>
    </teleport>

    <teleport to="#optionsmetabox">
        <options ref="options"></options>
    </teleport>
</div>

<?php require_once __DIR__ . '/components/sharedstate.php';?>
