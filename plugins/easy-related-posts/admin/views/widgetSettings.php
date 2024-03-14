<?php
/**
 * Represents the view for the widget settings.
 *
 * @package   Easy_Related_Posts
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
?>
<input type="hidden" name="erp_meta_box_nonce"
       value="<?php echo wp_create_nonce('erp_meta_box_nonce'); ?>" />
<table class="erp-wid-table">
    <tr>
        <td style="text-align: right;"><label for="<?php echo $widgetInstance->get_field_id('title'); ?>"><?php _e('Title:'); ?></label></td>
        <td>
            <input class=""
                   id="<?php echo $widgetInstance->get_field_id('title'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('title'); ?>"
                   type="text" value="<?php echo esc_attr($options['title']); ?>" 
                   data-tooltip 
                   title="This is the title that is displayed above related posts widget"/>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;"><label for="<?php echo $widgetInstance->get_field_id('numberOfPostsToDisplay'); ?>"><?php _e('Number of posts to show:'); ?></label></td>
        <td><input class="" size="3pt"
                   id="<?php echo $widgetInstance->get_field_id('numberOfPostsToDisplay'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('numberOfPostsToDisplay'); ?>"
                   type="number"
                   value="<?php echo esc_attr($options['numberOfPostsToDisplay']); ?>" 
                   data-tooltip 
                   title="This is the number of posts that will be displayed (if there are enough of course). Please use integers to populate this field"/></td>
    </tr>
    <tr>
        <td style="text-align: right;"><label style="" for="<?php echo $widgetInstance->get_field_id('offset'); ?>"><?php _e('Offset:'); ?></label></td>
        <td>
            <input class="" size="3pt"
                   id="<?php echo $widgetInstance->get_field_id('offset'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('offset'); ?>"
                   type="number" value="<?php echo esc_attr($options['offset']); ?>" 
                   data-tooltip 
                   title=" you set this field in an integer x above zero then the first x related posts will not be displayed. Please use only positive integer numbers or 0 if you don’t want any offset to occur"/>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;"><label for="<?php echo $widgetInstance->get_field_id('fetchBy'); ?>"><?php echo 'Rate posts by: '; ?> </label></td>
        <td>
            <select class=""
                    id="<?php echo $widgetInstance->get_field_id('fetchBy'); ?>"
                    name="<?php echo $widgetInstance->get_field_name('fetchBy'); ?>"
                    data-tooltip 
                    title="This is a critical options for Easy Related Posts. Upon this is based the way Easy Related Posts builds the relations between your posts and affects the result that will be displayed in the end user.

                    Easy Related Posts uses a intuitive algorithm to rate the relations between the posts in your site (you can read more in how it works page). Two main parameters in this algorithm are post categories and tags. This option defines the weight that these two parameters will have in rating. So when you choose only Categories then any post tags will be ignored when it comes to rating, if you choose Categories first, then tags then post categories will have more weight than tags etc.

                    Consider which taxonomy you use the most, which one describes the best your posts and choose the right option for you">
                        <?php
                        foreach (erpDefaults::$fetchByOptions as $k => $v) {
                            $valLow = strtolower(str_replace(',', '', str_replace(' ', '_', $v)));
                            ?>
                    <option value="<?php echo $valLow; ?>" <?php selected($valLow, $options['fetchBy']); ?>><?php echo $v; ?></option>
                    <?php
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="text-align: right;"><label for="<?php echo $widgetInstance->get_field_id('hideIfNoPosts'); ?>"><?php _e('Hide if no posts to show:'); ?></label></td>
        <td>
            <input class="erp_wid_opt5"
                   id="<?php echo $widgetInstance->get_field_id('hideIfNoPosts'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('hideIfNoPosts'); ?>"
                   type="checkbox" <?php echo checked($options['hideIfNoPosts']); ?> 
                   data-tooltip 
                   title="When this option is checked the widget wont display anything when there are no related posts. If this isn’t checked an empty widget will appear with the message “No related posts found”."/>
        </td>
    </tr>
</table>
<hr>
<table class="erp-wid-table">
    <tr>
        <td colspan="2" style="text-align: center">
            <strong>Content</strong>
        </td>
    </tr>
    <tr>
        <td><label for="<?php echo $widgetInstance->get_field_id('content'); ?>"><?php echo 'Content to display: '; ?></label></td>
        <td>
            <select class="" id="<?php echo $widgetInstance->get_field_id('content'); ?>"
                    name="<?php echo $widgetInstance->get_field_name('content'); ?>"
                    data-tooltip 
                    title="From here you can choose the content for each related posts that will be displayed in the front-end. You have 7 options so you can choose exactly the content to display.
                    Please note that templates may override this option and not all options are suitable for all templates. In example a template that is build to display post titles as a list may not give the more elegant appearance if you choose to display thumbnails also. So make the choice taking into account the chosen template and the options it provides you.">
                        <?php
                        foreach (erpDefaults::$contentPositioningOptions as $key => $value) {
                            $o = strtolower(str_replace(',', '', str_replace(' ', '-', $value)));
                            ?>
                    <option
                        value="<?php echo $o; ?>"
                        <?php selected(implode('-', (array) $options['content']), $o); ?>>
                            <?php echo $value; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center">
            <strong>Thumbnail</strong>
        </td>
    </tr>
    <tr>
        <td><label for="<?php echo $widgetInstance->get_field_id('cropThumbnail'); ?>"><?php _e('Crop thumbnail:'); ?></label></td>
        <td>
            <input class=""
                   id="<?php echo $widgetInstance->get_field_id('cropThumbnail'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('cropThumbnail'); ?>"
                   type="checkbox" <?php echo checked($options['cropThumbnail']); ?> 
                   data-tooltip 
                   title="Use this  option if you want the thumbnail to be cropped.

                   Setting the width in the next option, to some value above zero and the height to zero will result in hard cropped thumbnail. Setting both values above zero will result in soft cropped, more artistic, thumbnail.

                   If both height and width are above zero, then thumbnail will be soft cropped"/>
        </td>
    </tr>
    <tr>
        <td><label style="margin-left: 1%;" for="<?php echo $widgetInstance->get_field_id('thumbnailHeight'); ?>"><?php _e('Height:'); ?></label></td>
        <td>
            <input class="" size="3pt"
                   id="<?php echo $widgetInstance->get_field_id('thumbnailHeight'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('thumbnailHeight'); ?>"
                   type="number"
                   value="<?php echo esc_attr($options['thumbnailHeight']); ?>" 
                   data-tooltip 
                   title="Here you can set the height of the thumbnail that will be displayed in related post content.If you set both height and width to a value above zero then the thumbnail will be soft cropped.

                   Please set this as low as possible to prevent slow page loading from big images and use only positive integers to populate the field."/>
        </td>
    </tr>
    <tr>
        <td><label style="" for="<?php echo $widgetInstance->get_field_id('thumbnailWidth'); ?>"><?php _e('Width:'); ?></label></td>
        <td>
            <input class="erp_wid_opt2" size="3pt"
                   id="<?php echo $widgetInstance->get_field_id('thumbnailWidth'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('thumbnailWidth'); ?>"
                   type="number"
                   value="<?php echo esc_attr($options['thumbnailWidth']); ?>" 
                   data-tooltip 
                   title="Here you can set the width of the thumbnail that will be displayed in related post content.If height is set to zero then this will result  in an image scaling.
                   Please set this as low as possible to prevent slow page loading from big images and use only positive integers to populate the field"/> 
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center">
            <strong>Title</strong>
        </td>
    </tr>
    <tr>
        <td><label for="<?php echo $widgetInstance->get_field_id('postTitleFontSize'); ?>">Post title size:</label></td>
        <td>
            <input size="1pt" class=""
                   id="<?php echo $widgetInstance->get_field_id('postTitleFontSize'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('postTitleFontSize'); ?>"
                   type="number"
                   value="<?php echo esc_attr($options['postTitleFontSize']); ?>" 
                   data-tooltip 
                   title="Given an integer above zero will set the title font size in this value."/>px
        </td>
    </tr>
    <tr>
        <td><label for="<?php echo $widgetInstance->get_field_id('postTitleColor'); ?>">Post title color: </label></td>
        <td>
            <input class="wp-color-picker-field" data-default-color="#ffffff"
                   size="3pt"
                   id="<?php echo $widgetInstance->get_field_id('postTitleColor'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('postTitleColor'); ?>"
                   type="text"
                   value="<?php echo esc_attr($options['postTitleColor']); ?>" 
                   data-tooltip 
                   title="Set the colour if the post title. Default color is white. If white is selected your themes default color for h4 heading, will be used as related posts title color"/>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center">
            <strong>Excerpt</strong>
        </td>
    </tr>
    <tr>
        <td><label for="<?php echo $widgetInstance->get_field_id('excFontSize'); ?>">Excerpt text size:</label>

        </td>
        <td>
            <input size="1pt" class="zero-for-theme"
                   id="<?php echo $widgetInstance->get_field_id('excFontSize'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('excFontSize'); ?>"
                   type="number"
                   value="<?php echo esc_attr($options['excFontSize']); ?>" 
                   data-tooltip 
                   title="Given an integer above zero will set the title font size in this value"/>px
        </td>
    </tr>
    <tr>
        <td><label
                for="<?php echo $widgetInstance->get_field_id('excColor'); ?>">Excerpt text color: </label></td>
        <td>
            <input class="wp-color-picker-field" data-default-color="#ffffff"
                   size="3pt"
                   id="<?php echo $widgetInstance->get_field_id('excColor'); ?>"
                   name="<?php echo $widgetInstance->get_field_name('excColor'); ?>"
                   type="text" value="<?php echo esc_attr($options['excColor']); ?>" 
                   data-tooltip 
                   title="Set the colour if the post excerpt. Default color is white. If white is selected your themes default color for paragraphs, will be used as related posts title color"/>
        </td>
    </tr>
</table>
<hr>
<table class="erp-wid-table">
    <tr>
        <td colspan="2" style="text-align: center">
            <strong>Themes</strong>
        </td>
    </tr>
    <tr>
        <?php
        erpPaths::requireOnce(erpPaths::$VPluginThemeFactory);
        VPluginThemeFactory::registerThemeInPathRecursive(erpPaths::getAbsPath(erpPaths::$widgetThemesFolder));
        $templates = VPluginThemeFactory::getThemesNames();
        ?>
        <td><label for="<?php echo $widgetInstance->get_field_id('dsplLayout'); ?>">Theme :</label></td>
        <td>
            <select class="dsplLayout"
                    data-widinst="<?php echo $widgetInstance->get_field_id('dsplLayout'); ?>"
                    name="<?php echo $widgetInstance->get_field_name('dsplLayout'); ?>"
                    id="<?php echo $widgetInstance->get_field_id('dsplLayout'); ?>"
                    data-tooltip 
                    title="From the dropdown you can define the appearance of the plugin in the widget area. When a theme is selected the additional options will show up bellow theme selection dropdown">
                        <?php
                        foreach ($templates as $key => $val) {
                            echo '<option value="' . $val . '"' . selected($options['dsplLayout'], $val, FALSE) . '>' . $val . '</option>';
                        }
                        ?>
            </select>
        </td>
    </tr>
</table>
<p class="wid-inst-<?php echo $widgetInstance->get_field_id('dsplLayout'); ?>" style="border: 1px solid lightblue;padding: 10px;border-radius: 5px;">
    <span style="position: relative; top: -21px; float: left; background-color: white;"> Theme options </span><br>
    <?php
    foreach ($templates as $key => $value) {
        $temp = VPluginThemeFactory::getThemeByName($value);
        echo '<span class="templateSettings" data-template="' . $value . '" hidden="hidden">';
        $temp->setOptions($options);
        echo $temp->renderSettings($widgetInstance);
        echo '</span>';
    }
    ?>

</p>
<script type="text/javascript">
    var templateRoot = "<?php echo erpPaths::getAbsPath(erpPaths::$widgetThemesFolder); ?>";
    jQuery(document).ready(function($) {

        jQuery('#<?php echo $widgetInstance->get_field_id("postTitleColor"); ?>').wpColorPicker();
        jQuery('#<?php echo $widgetInstance->get_field_id("excColor"); ?>').wpColorPicker();

    });
</script>