<fieldset class="bulk">

    <legend><?php echo __('Areas', 'freeworld-html5-map'); ?></legend>
    <ul>
        <?php
            foreach($states as $s_id => $vals) { ?>

                <li><input type="checkbox" name="bulk_states[<?php echo $s_id; ?>]" id="bulk_state_<?php echo $s_id; ?>" value="1" data-sid="<?php echo $s_id; ?>" autocomplete="off" /><label for="bulk_state_<?php echo $s_id; ?>"><?php echo preg_replace('/^\s?<!--\s*?(.+?)\s*?-->\s?$/', '\1', $vals['name']); ?></label></li>

        <?php } ?>
    </ul>

</fieldset>


<fieldset class="bulk">

    <legend><?php echo __('Bulk editing', 'freeworld-html5-map'); ?></legend>

    <fieldset>
        <legend>
            <input type="checkbox" name="bulks[names]" value="1" id="bulk_names" />
            <label for="bulk_names"><?php echo __('Display options', 'freeworld-html5-map'); ?></label>
        </legend>

        <p>
            <span class="title"><?php echo __('Hide popup name:', 'freeworld-html5-map'); ?> </span>

            <label><input type="radio" name="bulk_options[names][_hide_name]" value="" checked="checked" /> <?php echo __('do not change', 'freeworld-html5-map'); ?></label>
            <label><input type="radio" name="bulk_options[names][_hide_name]" value="1" /> <?php echo __('hide', 'freeworld-html5-map'); ?></label>
            <label><input type="radio" name="bulk_options[names][_hide_name]" value="0" /> <?php echo __('show', 'freeworld-html5-map'); ?></label><br>
        </p>
        <p>
            <span class="title"><?php echo __('Hide area:', 'freeworld-html5-map'); ?> </span>
            <label><input type="radio" name="bulk_options[names][_hide_area]" value="" checked="checked" /> <?php echo __('do not change', 'freeworld-html5-map'); ?></label>
            <label><input type="radio" name="bulk_options[names][_hide_area]" value="1" /> <?php echo __('hide', 'freeworld-html5-map'); ?></label>
            <label><input type="radio" name="bulk_options[names][_hide_area]" value="0" /> <?php echo __('show', 'freeworld-html5-map'); ?></label><br>
        </p>

    </fieldset>

    <fieldset>
        <legend>
            <input type="checkbox" name="bulks[click]" value="1" id="bulk_click" />
            <label for="bulk_click"><?php echo __('Click/tap events', 'freeworld-html5-map'); ?></label>
        </legend>

        <p class="stateinfo">
            <span><?php echo __('What to do when the area is clicked:', 'freeworld-html5-map'); ?></span><br />
            <label><input type="radio" name="bulk_options[click][URLswitch]" id="nBulk" value="nill" checked autocomplete="off">&nbsp;<?php echo __('Nothing', 'freeworld-html5-map'); ?></label> <span class="tipsy-q" original-title="<?php esc_attr_e('Do not react on mouse clicks', 'freeworld-html5-map'); ?>">[?]</span>&nbsp;&nbsp;&nbsp;
            <label><input type="radio" name="bulk_options[click][URLswitch]" id="uBulk" value="url" autocomplete="off">&nbsp;<?php echo __('Open a URL', 'freeworld-html5-map'); ?></label> <span class="tipsy-q" original-title="<?php esc_attr_e('A click on this area opens a specified URL', 'freeworld-html5-map'); ?>">[?]</span>&nbsp;&nbsp;&nbsp;
            <label><input type="radio" name="bulk_options[click][URLswitch]" id="mBulk" value="more" autocomplete="off">&nbsp;<?php echo __('Show more info', 'freeworld-html5-map'); ?></label> <span class="tipsy-q" original-title="<?php esc_attr_e('Displays a side-panel with additional information (contacts, addresses etc.)', 'freeworld-html5-map'); ?>">[?]</span>&nbsp;&nbsp;&nbsp;
        </p>

        <div id="stateURLBulk" style="display: none">
            <span class="title"><?php echo __('URL:', 'freeworld-html5-map'); ?> </span><input style="width: 240px;" class="" type="text" name="bulk_options[click][link]" />
            <span class="tipsy-q" original-title="<?php esc_attr_e('The landing page URL', 'freeworld-html5-map'); ?>">[?]</span>&nbsp;&nbsp;&nbsp;
            <label>
                <input type="hidden" name="bulk_options[click][isNewWindow]" value="0" />
                <input type="checkbox" name="bulk_options[click][isNewWindow]" value="1" /> <?php echo __('Open url in a new window', 'freeworld-html5-map'); ?>
            </label></br>
        </div>

        <div id="stateDescrBulk" style="display: none">
            <span class="title"><?php echo __('Description:', 'freeworld-html5-map'); ?> <span class="tipsy-q" original-title="<?php esc_attr_e('The description is displayed to the right of the map and contains contacts or some other additional information', 'freeworld-html5-map'); ?>">[?]</span> </span>
            <?php wp_editor("", "bulk_options_descr", array("textarea_name" => "bulk_options[click][descr]")); ?>
            </br>
        </div>

    </fieldset>

    <fieldset>
        <legend>
            <input type="checkbox" name="bulks[tooltip]" value="1" id="bulk_tooltip" />
            <label for="bulk_tooltip"><?php echo __('Tooltips', 'freeworld-html5-map'); ?></label>
        </legend>

        <p>
            <span class="title"><?php echo __('Info for tooltip balloon:', 'freeworld-html5-map'); ?> <span class="tipsy-q" original-title="<?php esc_attr_e('Info for tooltip balloon', 'freeworld-html5-map'); ?>">[?]</span> </span>
            <?php freeworld_html5map_plugin_wp_editor_for_tooltip('', 'bulk_options[tooltip][comment]', 'comment'); ?>
        </p>

    </fieldset>

    <fieldset>
        <legend>
            <input type="checkbox" name="bulks[kolor]" value="1" id="bulk_color" />
            <label for="bulk_color"><?php echo __('Colors', 'freeworld-html5-map'); ?></label>
        </legend>

        <div>

            <div>
                <span class="title"><?php echo __('Area color:', 'freeworld-html5-map'); ?> </span><input class="color colorSimple" type="text" name="bulk_options[color][color_map]" value="#7798BA" />
                <span class="tipsy-q" original-title='<?php echo __('The color of an area.', 'freeworld-html5-map'); ?>'>[?]</span><div class="fm-colorpicker"></div>
            </div>
            <div style="clear: both"></div>

            <div>
                <span class="title"><?php echo __('Area hover color:', 'freeworld-html5-map'); ?> </span><input class="color colorOver" type="text" name="bulk_options[color][color_map_over]" value="#366CA3" />
                <span class="tipsy-q" original-title='<?php echo __('The color of an area when the mouse cursor is over it.', 'freeworld-html5-map'); ?>'>[?]</span><div class="fm-colorpicker"></div>
            </div>

        </div>

    </fieldset>

<fieldset>
    <legend>
        <input type="checkbox" name="bulks[image]" value="1" id="bulk_image" />
        <label for="bulk_image"><?php echo __('Image', 'freeworld-html5-map'); ?></label>
    </legend>

    <p>
        <span class="title"><?php echo __('Image URL:', 'freeworld-html5-map'); ?> </span>
        <input onclick="imageFieldId = this.id; tb_show('Image', 'media-upload.php?type=image&tab=library&TB_iframe=true');" class="" id="bulk_image_url" type="text" name="bulk_options[image][image]"  />
        <span style="font-size: 10px; cursor: pointer;" onclick="clearImage(this)"><?php echo __('clear', 'freeworld-html5-map'); ?></span>
        <span class="tipsy-q" original-title="<?php esc_attr_e('The path to file of the image to display in a popup', 'freeworld-html5-map'); ?>">[?]</span><br />
    </p>

</fieldset>

<fieldset>
    <legend>
        <input type="checkbox" name="bulks[class]" value="1" id="bulk_class" />
        <label for="bulk_class"><?php echo __('CSS class', 'freeworld-html5-map'); ?></label>
    </legend>

    <p>
        <span class="title"><?php echo __('CSS class:', 'freeworld-html5-map'); ?></span>
        <input type="text" class="" name="bulk_options[class][class]">
        <span class="tipsy-q" original-title="<?php esc_attr_e('You can specify several CSS classes separated by space', 'freeworld-html5-map'); ?>">[?]</span>
        <br />
    </p>

</fieldset>

</fieldset>
