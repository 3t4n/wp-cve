<form method="post" action="options.php">
<?php settings_fields( 'flattr-settings-group' ); ?>

<h3><?php _e('Basic settings');?></h3>

<table class="form-table">
<tr>
    <th scope="row"><label for="flattr_uid"><?php _e('Flattr Username'); ?></label></th>
    <td>
        <input id="flattr_uid" name="flattr_uid" type="text" value="<?php echo(esc_attr(get_option('flattr_uid'))); ?>" />
        <span class="description"><?php _e('The Flattr account to which the buttons will be assigned.'); ?></span>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_atags"><?php _e('Additional Flattr tags for your posts'); ?></label></th>
    <td>
        <input id="flattr_atags" name="flattr_atags" type="text" value="<?php echo(esc_attr(get_option('flattr_atags', 'blog'))); ?>" />
        <span class="description"><?php _e("Comma separated list of additional tags to use in Flattr buttons"); ?></span>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_cat"><?php _e('Default category for your posts'); ?></label></th>
    <td>
        <select id="flattr_cat" name="flattr_cat">
            <?php
                foreach (Flattr::getCategories() as $category)
                {
                    printf('<option value="%1$s" %2$s>%1$s</option>',
                            $category,
                            ($category == get_option('flattr_cat') ? 'selected' : '')
                    );
                }
            ?>
        </select>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_lng"><?php _e('Default language for your posts'); ?></label></th>
    <td>
        <select id="flattr_lng" name="flattr_lng">
            <?php
                foreach (Flattr::getLanguages() as $languageCode => $language)
                {
                    printf('<option value="%s" %s>%s</option>',
                            $languageCode,
                            ($languageCode == get_option('flattr_lng') ? 'selected' : ''),
                            $language
                    );
                }
            ?>
        </select>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_hide"><?php _e('Hide my posts from listings on Flattr.com'); ?></label></th>
    <td>
        <input <?php if (get_option('flattr_hide', 'false') == 'true') { echo(' checked="checked"'); } ?> type="checkbox" id="flattr_hide" name="flattr_hide" value="true" />
        <span class="description"><?php _e("If your content could be considered offensive then you're encouraged to hide it."); ?></span>
    </td>
</tr>
</table>

<h3><?php _e('Advanced settings');?></h3>

<h4>Flattrable content</h4>

<table class="form-table">
<tr>
    <th scope="row"><?php _e('Post Types'); ?></th>
    <td>
        <ul>
        <?php
            $types = get_post_types();
            $flattr_post_types = (array)get_option('flattr_post_types', array());
            foreach ($types as $type) {
                $selected = (is_array($flattr_post_types) && in_array($type, $flattr_post_types))? " checked" : "";
                $id = 'flattr_post_types_' . esc_attr($type);
                echo "<li><label><input name=\"flattr_post_types[]\" value=\"$type\" type=\"checkbox\"$selected/>&nbsp;$type</label></li>";
            }
        ?></ul>
        <span class="description"><?php _e('Only the selected post types are made flattrable.'); ?></span>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_global_button"><?php _e('Make frontpage flattrable'); ?></label></th>
    <td>
        <input id="flattr_global_button" name="flattr_global_button" type="checkbox" <?php if(get_option('flattr_global_button', false)) {echo "checked";}?> />
    </td>
</tr>
</table>

<h4>User specific buttons</h4>

<table class="form-table">
<tr>
    <th scope="row"><label for="user_based_flattr_buttons"><?php _e('Enable user specific buttons'); ?></label></th>
    <td>
        <input type="checkbox" id="user_based_flattr_buttons" name="user_based_flattr_buttons"<?php echo get_option('user_based_flattr_buttons')?" checked":"";?> />
        <span class="description"><?php _e("If you tick this box, every user of the blog will have the chance to register its own Flattr buttons. Buttons will then be linked to post authors and only display if the user completed plugin setup."); ?></span>
    </td>
</tr>
<tr>
    <th scope="row"><label for="user_based_flattr_buttons_since_time"><?php _e('Limit to posts after'); ?></label></th>
    <td>
        <input type="text" id="user_based_flattr_buttons_since_time" name="user_based_flattr_buttons_since_time" value="<?php echo (get_option('user_based_flattr_buttons_since_time') == '' ? '' : esc_attr(date('Y-m-d', get_option('user_based_flattr_buttons_since_time')))); ?>" />
        <span class="description"><?php echo 'With this setting you can limit user specific buttons to posts newer than a certain date which helps preventing owner mismatching between the buttons on your sites and things on Flattr.com which disables affected buttons. Leave empty to disable the limit.'; ?></span>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('input[name="user_based_flattr_buttons_since_time"]').datepicker({
                    autoSize: true,
                    constrainInput: false,
                    buttonText: 'Choose date...',
                    dateFormat: jQuery.datepicker.ISO_8601,
                    showOn: 'button'
                });
            });
        </script>
    </td>
</tr>
</table>

<h4>Style</h4>

<table class="form-table">
<tr>
    <th scope="row">Button type</th>
    <td>
        <ul>
            <li>
                <input type="radio" id="flattr_button_style_js"name="flattr_button_style" value="js"<?=(get_option('flattr_button_style')=="js")?" checked":"";?>/>
                <?php Flattr::getInstance()->admin_script(); ?>
                <a class="FlattrButton" href="http://wordpress.org/extend/plugins/flattr/" title="Wordpress Flattr plugin" lang="en_GB"
                    rel="flattr;uid:der_michael;category:software;tags:wordpress,plugin,flattr,rss;<?=get_option('flattr_compact')?"button:compact;":"";?>">
                    Give your readers the opportunity to Flattr your effort. See http://wordpress.org/extend/plugins/flattr/ for details.
                </a>
                <span class="description"><label for="flattr_button_style_js"><?php _e('Dynamic javascript version'); ?></label></span>
            </li>
            <li>
                <input type="radio" name="flattr_button_style" value="image-white"<?=(get_option('flattr_button_style')=="image-white")?" checked":"";?>/>
                <?= Flattr::getStaticImageButton($type='image-white') ?>
                <span class="description"><?php _e('Static green-white image with with HiDPI support using srcset.'); ?></span>
            </li>
            <li>
                <input type="radio" id="flattr_button_style_image" name="flattr_button_style" value="image"<?=(get_option('flattr_button_style')=="image")?" checked":"";?>/>
                <?= Flattr::getStaticImageButton('image') ?>
                <span class="description"><label for="flattr_button_style_image"><?php _e('Static image version'); ?></label></span>
            </li>
            <li>
                <input type="radio" id="flattr_button_style_text" name="flattr_button_style" value="text"<?=(get_option('flattr_button_style')=="text")?" checked":"";?>/>
                <a href="#">Flattr this!</a>
                <span class="description"><label for="flattr_button_style_text"><?php _e('Static text version'); ?></label></span>
            </li>
        </ul>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_compact"><?php _e('Use the compact button'); ?></label></th>
    <td>
        <input <?php if (get_option('flattr_compact', 'false') == 'true') { echo(' checked="checked"'); } ?> type="checkbox" id="flattr_compact" name="flattr_compact" value="true" />
        <span class="description"><?php _e('Only applies to the javascript button type.'); ?></span>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_popout_enabled"><?php _e('Enable the button popout'); ?></label></th>
    <td>
        <input <?php if (get_option('flattr_popout_enabled', 'true')) { echo(' checked="checked"'); } ?> type="checkbox" id="flattr_popout_enabled" name="flattr_popout_enabled" value="true" />
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattrss_custom_image_url">Custom Image URL</label></th>
    <td>
        <input type="text" id="flattrss_custom_image_url" name="flattrss_custom_image_url" size="70" value="<?php echo esc_attr(get_option('flattrss_custom_image_url'));?>"/><br/>
        <?php if ( get_option('flattrss_custom_image_url') != get_bloginfo('wpurl') . '/wp-content/plugins/flattr/img/flattr-badge-large.png') { ?>
        Default Value:<br>
        <input type="text" size="70" value="<?php echo get_bloginfo('wpurl') . '/wp-content/plugins/flattr/img/flattr-badge-large.png';?>" readonly><br />
        <?php } ?>
        <span class="description"><?php _e('Only applies to button on site and in feed if the static (non-white) image type is selected.'); ?></span>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattrss_button_enabled"><?php _e('Insert static image buttons into RSS/Atom feed entries'); ?></label></th>
    <td>
        <input id="flattrss_button_enabled" name="flattrss_button_enabled" type="checkbox" <?php if(get_option('flattrss_button_enabled')) {echo "checked";}?> />
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_aut"><?php _e('Insert Flattr button into posts automagically'); ?></label></th>
    <td>
        <input <?php if (get_option('flattr_aut')) { echo(' checked="checked"'); } ?> type="checkbox" id="flattr_aut" name="flattr_aut" value="on" />
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_aut_page"><?php _e('Insert Flattr button into pages automagically'); ?></label></th>
    <td>
        <input <?php if (get_option('flattr_aut_page')) { echo(' checked="checked"'); } ?> type="checkbox" id="flattr_aut_page" name="flattr_aut_page" value="on" />
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_top"><?php _e("Add button before post's content"); ?></label></th>
    <td>
        <input <?php if (get_option('flattr_top', 'false') == 'true') { echo(' checked="checked"'); } ?> type="checkbox" id="flattr_top" name="flattr_top" value="true" />
    </td>
</tr>
<tr>
    <td colspan="2">You can use <code>&lt;?php the_flattr_permalink() ?&gt;</code> in your template/theme to insert a flattr button</td>
</tr>

</table>

<h4>Metadata</h4>

<table class="form-table">
<tr>
    <th scope="row"><label for="flattrss_relpayment_enabled"><?php _e('Include payment metadata in RSS/Atom feeds'); ?></label></th>
    <td>
        <input id="flattrss_relpayment_enabled" name="flattrss_relpayment_enabled" type="checkbox" <?php if(get_option('flattrss_relpayment_enabled')) {echo "checked";}?> />
        <span class="description"><a href="http://developers.flattr.net/feed/">Flattr feed links</a> will be included in the RSS/Atom feeds to allow feed readers to identify flattrable stuff and make it easy to flattr it.</span>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattr_relpayment_enabled"><?php _e('Include payment metadata in HTML'); ?></label></th>
    <td>
        <input id="flattr_relpayment_enabled" name="flattr_relpayment_enabled" type="checkbox" <?php if(get_option('flattr_relpayment_enabled')) {echo "checked";}?> />
        <span class="description">Rel-payment metadata similar to the <a href="http://developers.flattr.net/feed/">Flattr feed links</a> will be included in HTML pages. This enables eg. browser extensions to identify the Flattr button of a page</span>
    </td>
</tr>
<tr>
    <th scope="row"><label for="flattrss_relpayment_escaping_disabled"><?php _e('Payment metadata in feed not working?'); ?></label></th>
    <td>
        <input id="flattrss_relpayment_escaping_disabled" name="flattrss_relpayment_escaping_disabled" type="checkbox" <?php if(get_option('flattrss_relpayment_escaping_disabled')) {echo "checked";}?> />
        <span class="description">This is a fix for a very small collection of blogs that for some mysterious reason are getting the payment metadata double escaped in their feeds. Until we find out why these blogs has this problem we provide this solution instead to make the experience for these bloggers as good as possible.<br/> <strong>WARNING:</strong> Activating this when metadata isn't broken will break your feeds!</span>
    </td>
</tr>
</table>

<h4>Other</h4>

<table class="form-table">
<tr>
    <th scope="row">Presubmit to Flattr Catalog</th>
    <td>
        <span id="autosubmit" class="inactive">DEACTIVATED</span>
        <p class="description"><?php _e('Only use if you for some reason want to presubmit content to Flattr.com prior to them being flattred.'); ?></p>
    </td>
</tr>
</table>