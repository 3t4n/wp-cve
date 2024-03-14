<?php

function sixs_register_settings()
{
    add_option('sixs_option_name', '');

    register_setting('sixs_options_group', 'sixs_xml_autosave_option_name', 'sixs_callback');
    register_setting('sixs_options_group', 'sixs_caption_1_option_name', 'sixs_callback');
    register_setting('sixs_options_group', 'sixs_caption_2_option_name', 'sixs_callback');
    register_setting('sixs_options_group', 'sixs_title_1_option_name', 'sixs_callback');
    register_setting('sixs_options_group', 'sixs_title_2_option_name', 'sixs_callback');
    register_setting('sixs_options_group', 'sixs_posttypes_option_name', 'sixs_callback');

    add_option('sixs_xml_autosave_option_name', '1');
    add_option('sixs_title_1_option_name', array('content'));
    add_option('sixs_caption_1_option_name', array('excerpt'));
    add_option('sixs_posttypes_option_name', array('post', 'attachment'));

}

add_action('admin_init', 'sixs_register_settings');

function sixs_register_options_page()
{
    add_options_page('Simple XML Image Sitemap', 'Simple XML Image Sitemap', 'manage_options', 'plugin_sixs', 'sixs_options_page');
}

add_action('admin_menu', 'sixs_register_options_page');

function sixs_options_page()
{
    ?>
    
    <div>
        <h2><?= __('Plugin Settings for Simple XML Image Sitemap', 'simple-xml-image-sitemap')?></h2>
        <form method="post" action="options.php">
            <?php settings_fields('sixs_options_group'); ?>

            <div style="background:#fff; border: 1px; padding: 5px;">
                <b><?=__('Available Post & Image Fields:', 'simple-xml-image-sitemap')?></b>
                <ul>
                    <li><code>excerpt</code> (<?=__('Default for Caption', 'simple-xml-image-sitemap')?>)</li>
                    <li><code>content</code> (<?=__('Default for Title', 'simple-xml-image-sitemap')?>)</li>
                    <li><code>title</code></li>
                    <li><code>description</code></li>
                    <li><code>alt tag</code></li>
                </ul>
            </div>
            
            <?php
            $dropdown_options = ['excerpt', 'content', 'title', 'alt_tag', 'description'];
            $postypes = get_post_types();
            $current_caption_1 = get_option('sixs_caption_1_option_name');
            $current_caption_2 = get_option('sixs_caption_2_option_name');
            $current_title_1 = get_option('sixs_title_1_option_name');
            $current_title_2 = get_option('sixs_title_2_option_name');
            $current_ptype = get_option('sixs_posttypes_option_name');

            //echo 'current:</br>';
            //var_dump($current_ptype);
            ?>
            <table>
                <tr valign="top">
                    <th scope="row"><label for="sixs_xml_autosave_option_name"><?=__('Autosave:', 'simple-xml-image-sitemap')?></label></th>
                    <td>
                        <input name="sixs_xml_autosave_option_name" type="checkbox"
                               value="1" <?php checked('1', get_option('sixs_xml_autosave_option_name')); ?> />
                        <?=__('Sitemap (XML File) will be updated automatically with saving post after editing.', 'simple-xml-image-sitemap')?>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sixs_caption_1_option_name"><?=__('Caption', 'simple-xml-image-sitemap')?> 1:</label></th>
                    <td>
                        <select name="sixs_caption_1_option_name[]" id="sixs_caption_1_option_name">
                            <?php
                            foreach ($dropdown_options as $doption) {
                                $selected = (($doption === $current_caption_1[0]) ? 'selected' : '');
                                ?>
                                <option value="<?= $doption ?>" <?php echo $selected; ?>><?= $doption ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>

                    <th scope="row"><label for="sixs_caption_2_option_name"><?=__('Caption', 'simple-xml-image-sitemap')?> 2:</label></th>
                    <td>
                        <select name="sixs_caption_2_option_name[]" id="sixs_caption_2_option_name">
                            <?php
                            foreach ($dropdown_options as $doption) {
                                $selected = (($doption === $current_caption_2[0]) ? 'selected' : '');
                                ?>
                                <option value="<?= $doption ?>" <?php echo $selected; ?>><?= $doption ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <i>(<?= __('If Caption is empty', 'simple-xml-image-sitemap')?>)</i>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sixs_title_1_option_name"><?=__('Title', 'simple-xml-image-sitemap')?> 1:</label></th>
                    <td>
                        <select name="sixs_title_1_option_name[]" id="sixs_title_1_option_name">
                            <?php
                            foreach ($dropdown_options as $doption) {
                                $selected = (($doption === $current_title_1[0]) ? 'selected' : '');
                                ?>
                                <option value="<?= $doption ?>" <?php echo $selected; ?>><?= $doption ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>

                    <th scope="row"><label for="sixs_title_2_option_name"><?=__('Title', 'simple-xml-image-sitemap')?> 2:</label></th>
                    <td>
                        <select name="sixs_title_2_option_name[]" id="sixs_title_2_option_name">
                            <?php
                            foreach ($dropdown_options as $doption) {
                                $selected = (($doption === $current_title_2[0]) ? 'selected' : '');
                                ?>
                                <option value="<?= $doption ?>" <?php echo $selected; ?>><?= $doption ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <i>(<?= __('If Title is empty', 'simple-xml-image-sitemap')?>)</i>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="sixs_posttypes_option_name">Post Types</label></th>
                    <td>
                        <select name="sixs_posttypes_option_name[]" id="sixs_posttypes_option_name" multiple>
                            <?php
                            foreach ($postypes as $ptype) {
                                $selected = (in_array($ptype, $current_ptype) ? 'selected' : '');
                                ?>
                                <option value="<?= $ptype ?>" <?php echo $selected; //selected( get_option( 'sixs_xml_dropdown_option_name' ), $doption ); ?>><?= $ptype ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>

            </table>

            <?php
            wp_nonce_field('sixs_plugin_settings', 'nonce');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

