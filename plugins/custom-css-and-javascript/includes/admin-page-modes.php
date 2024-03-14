<?php defined('ABSPATH') || exit; ?>

<div class="wrap">
    <h3>Custom CSS</h3>
    <div>
        <div id="hm_custom_code_editor" style="margin-top: 15px;">
            <div class="hm_custom_code_editor_inner">

				<?php include( __DIR__ . '/banners.php' ) ?>

                <h4 style="margin: 0; margin-bottom: 5px;">Revisions:</h4>
                <button class="wpz-custom-css-js-button-secondary wpz-custom-css-js-button-small hm-custom-css-js-delete-revisions-btn">Delete All</button>
                <ul id="hm_custom_css_js_revisions">
                </ul>

            </div>
        </div>
        <div style="float: right; padding-left: 10px; margin-top: 3px; white-space: nowrap; font-style: italic;">
            <a href="https://codemirror.net/" target="_blank">CodeMirror</a> code editor
        </div>
        <button type="button" class="wpz-custom-css-js-button-secondary wpz-custom-css-js-button-small hm-custom-css-js-save-btn" style="margin-top: 15px;" disabled="disabled">Saved</button>
        <button type="button" class="wpz-custom-css-js-button-secondary wpz-custom-css-js-button-small hm-custom-css-js-publish-btn" style="margin-top: 15px; margin-right: 10px;" disabled="disabled">Save &amp; Publish</button>
        <label style="margin-top: 15px; white-space: nowrap;">
			<span id="hm-custom-css-js-minify-cb-javascript">
            <input type="checkbox" class="hm-custom-css-js-minify-cb"
				<?php
				echo (get_option( 'hm_custom_javascript_minify', true ) ? ' checked="checked"' : '')
				?> /> Minify output
			</span>
			<span id="hm-custom-css-js-minify-cb-css">
			<input type="checkbox" class="hm-custom-css-js-minify-cb"
				<?php
				echo (get_option( 'hm_custom_css_minify', true ) ? ' checked="checked"' : '')
				?> /> Minify output
			</span>
        </label>
    </div>
    <div style="clear: both; margin-bottom: 20px;"></div>

</div>