<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
if (defined('CCPUZ_DEBUG'))
    ccpuz_log(">ccpuz_get_crossword_mce_from id = $post_id");
?>
<div class="tw-bs">
    <div class="form-horizontal">
        <fieldset>

            <div class="control-group">
                <label class="control-label" for="select01">Select Method</label>
                <div class="controls">
                    <select id="crossword_method" name="crossword_method">
                        <option value="url" <?php echo ( get_post_meta($post_id, 'crossword_method', true) == 'url' ? ' selected ' : '' ); ?> >URL</option>
                        <option value="local" <?php echo ( get_post_meta($post_id, 'crossword_method', true) == 'local' ? ' selected ' : '' ); ?> >Local File</option>
                    </select>
                </div>
            </div>

            <div class="control-group ccpuz_url_class">
                <label class="control-label" for="fileInput">URL Upload</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" name="ccpuz_url_upload_field" id="ccpuz_url_upload_field" value="<?php echo get_post_meta($post_id, 'ccpuz_url_upload_field', true); ?>"/>
                </div>
            </div>

            <div class="control-group ccpuz_file_class">

                <label class="control-label" for="fileInput">HTML File</label>
                <div class="controls">
                    <input class="input-file" id="ccpuz_html_file" name="ccpuz_html_file" type="file"/>
                </div>

                <label class="control-label" for="fileInput">JS File</label>
                <div class="controls">
                    <input class="input-file" id="ccpuz_js_file" name="ccpuz_js_file" type="file">
                </div>

            </div>

            <div class="control-group ccpuz_preview_class" style="display: none">
                <div class="ccpuz_progress">
                    <i class="dashicons dashicons-update dashicons-spin ccpuz_update_icon" style="font-size:38px;height:38px;width:38px;animation:dashicons-spin 1.5s infinite;animation-timing-function:linear;"></i>
                    <p>Loading preview, please wait...</p>                    
                </div>
                <div class="ccpuz_crossword"></div>
            </div>

        </fieldset>
    </div>
</div>
