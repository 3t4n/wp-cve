<?php
/**
 * Meta Boxes
 *
 * A class to easily add custom meta boxes and fields programmatically
 *
 * Requires WordPress 3.5+
 *
 * NOTE: To add a taxonomy field, the actual taxonomy needs to be added outside
 * of this class as well as removing the default taxonomy metabox.  Taxonomies
 * through this class can be added to make use of radio buttons instead of
 * checkboxes (other features may be added in the future.
 *
 * @version 1.0.0.5
 * @author Fetch Designs & Liventus, Inc.
 * @copyright 2013-2023 Fetch Designs <https://www.fetchdesigns.com/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace FDSUS\Lib\Dls\MetaBoxes;

use WP_Post;

class MetaBoxes
{

    /**
     * Meta Box Definition
     *
     * @var array $meta_box
     */
    public $prefix = 'dlsmb';
    public $version = '1.0.0.5';
    private $meta_box;
    public static $addTimeDateJqueryDone = false;

    /**
     * Initialize Class
     *
     * @param array $meta_box
     */
    public function __construct($meta_box)
    {
        // Clean Input
        $defaults = array(
            'id' => null, // HTML 'id' attribute of the edit screen section
            'title' => ' ',
            'post_type' => 'page',
            'limit_ids' => false, // array of post IDs to which the meta box is limited or false if ignored
            'context' => 'normal', // ('normal', 'advanced', or 'side')
            'priority' => 'default', // ('high', 'core', 'default' or 'low')
            'fields' => array( // the value of this field is not set as a default, just included for reference
                'label' => null,
                'key' => null,
                'type' => 'text',
                'map_api_key' => null,
                'style' => null,
                'wrap_class' => null, // appended to class attribute string on parent wrapping element
                'class' => null, // appended to class attribute string (ex: 'chosen-select' on a multiselect type)
                'append' => null, // append after field
                'show_column' => false, // show in admin column grid
                'args' => array(), // arguments to pass to related function (like wp_editor)
                'disabled' => false, // if field is disabled for editing
                'box_label_override' => null, // overrides the label on a single checkbox
                'fields' => array(), // used for sub fields in repeater
                'order' => 0, // used to sort the fields when output
            ),
        );
        $this->meta_box = array_intersect_key($meta_box + $defaults, $defaults);

        add_action('add_meta_boxes', array(&$this, 'addMetaBoxes'), 10, 2);
        add_action('save_post', array(&$this, 'saveDetails'), 10, 2);
        add_action('wp_ajax_dlsmb_image_data', array(&$this, 'ajaxImageData'));
        add_action('admin_enqueue_scripts', array(&$this, 'adminEnqueueScripts'), 10);
        add_filter('media_upload_tabs', array($this, 'removeMediaLibraryTab'));
        add_filter('manage_posts_columns', array(&$this, 'postsColumns'), 10, 2);
        add_action('manage_posts_custom_column', array(&$this, 'populatePostsColumns'), 10, 2);
        add_action('bulk_edit_custom_box', array(&$this, 'addToBulkQuickEditCustomBox'), 10, 2);
        add_action('quick_edit_custom_box', array(&$this, 'addToBulkQuickEditCustomBox'), 10, 2);
        add_action('admin_footer-edit.php', array(&$this, 'quickEditJS'));
        add_action('wp_ajax_'.$this->prefix.'_save_bulk_edit', array(&$this, 'saveBulkEdit'));
        add_action('admin_head', array(&$this, 'addTimeDateJquery'));
    }

    /**
     * Filter the time jQuery UI time boxes to allow for 12 or 24 hour clocks.
     */
    public function addTimeDateJquery()
    {
        if ( ! self::$addTimeDateJqueryDone ) {
            $timePicker24hours = false;
            $dateTimePicker24hours = false;

            $dateTimePicker24hours = apply_filters('dlsmb_datetimepicker_args', $dateTimePicker24hours);
            $timePicker24hours = apply_filters('dlsmb_timepicker_args', $timePicker24hours);

            $timePickerFormat = $timePicker24hours ? 'HH:mm' : 'hh:mm tt';
            $dateTimePickerFormat = $dateTimePicker24hours ? 'HH:mm' : 'hh:mm tt';

            ?>
            <script type="text/javascript">
                (function ($) {
                    $(document).ready(function () {
                        $('body').on('focus', '.dlsmb-timepicker', function () {
                            $(this).timepicker({
                                timeFormat: '<?php echo $timePickerFormat; ?>',
                                controlType: 'select',
                                oneLine: true
                            });
                        });

                        // Datetimepicker
                        $('body').on('focus', '.dlsmb-datetimepicker', function () {
                            $(this).datetimepicker({
                                dateFormat: "yy-mm-dd",
                                timeFormat: "<?php echo $dateTimePickerFormat; ?>",
                                controlType: 'select',
                                oneLine: true
                            });
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }
        self::$addTimeDateJqueryDone = true;
    }

    /**
     * Admin Init to actually add the meta box
     *
     * @param string $postType
     * @param WP_Post $post
     */
    public function addMetaBoxes($postType, $post)
    {
        if ($postType != $this->meta_box['post_type']) return;

        if (!empty($this->meta_box['limit_ids'])
            && is_array($this->meta_box['limit_ids'])
            && (in_array($post->ID, $this->meta_box['limit_ids'])
                || in_array($post->name, $this->meta_box['limit_ids'])
            )
        ) {
            return;
        }

        add_meta_box(
            $this->meta_box['id'],
            $this->meta_box['title'],
            array($this, 'displayFields'),
            $this->meta_box['post_type'],
            $this->meta_box['context'],
            $this->meta_box['priority']
        );
    }

    /**
     * Sort by order
     *
     * @param array $a
     * @param array $b
     *
     * @return int
     */
    public function sortByOrder($a, $b)
    {
        if (!isset($a['order'])) {
            $a['order'] = 0;
        }
        if (!isset($b['order'])) {
            $b['order'] = 0;
        }
        $result = 0;
        if ($a['order'] > $b['order']) {
            $result = 1;
        } else {
            if ($a['order'] < $b['order']) {
                $result = -1;
            }
        }
        return $result;
    }

    /**
     * Display the fields in the edit pages
     */
    public function displayFields()
    {
        global $post;
        $custom = get_post_custom($post->ID);

        usort($this->meta_box['fields'], array(&$this, 'sortByOrder'));

        foreach ($this->meta_box['fields'] as $field) {
            $value = (isset($custom[$field['key']])) ? $custom[$field['key']] : null;

            /**
             * Filter a metabox field value
             *
             * @since 0.5
             *
             * @param null|bool $value Value of field
             * @param int $field Meta field
             * @param int $post_id Post ID
             * @param array $meta_box Current meta box data
             */
            $filtered_field_value = apply_filters($this->prefix . '_display_meta_field_value', $value, $field, $post->ID, $this->meta_box);

            if (isset($filtered_field_value['value'])) $value = $filtered_field_value['value'];
            if (isset($filtered_field_value['field'])) $field = $filtered_field_value['field'];

            if ($field['type'] == 'taxonomy') {
                $this->_displayTaxonomy($post, $field['key'], $field['input_type']);
            } elseif (in_array($field['type'], array('checkboxes', 'multiselect'))) {
                $this->_displayField($field, $value);
            } elseif (isset($value[0])) {
                $this->_displayField($field, $value[0]);
            } else {
                $this->_displayField($field, $value);
            }
        }
        reset($this->meta_box);
    }

    /**
     * @param $post
     * @param $taxonomy
     * @param string $input_type
     */
    private function _displayTaxonomy($post, $taxonomy, $input_type = 'checkbox')
    {

        //Set up the taxonomy object and get terms
        $tax = get_taxonomy($taxonomy);
        $terms = get_terms($taxonomy, array('hide_empty' => 0));

        //Name of the form
        $name = 'tax_input[' . $taxonomy . ']';

        //Get current and popular terms
        $popular = get_terms($taxonomy, array(
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 10,
            'hierarchical' => false
        ));
        $postterms = get_the_terms($post->ID, $taxonomy);
        $current = ($postterms ? array_pop($postterms) : false);
        $current = ($current ? $current->term_id : 0);
        ?>

        <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

            <!-- Display tabs-->
            <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
                <li class="tabs">
                    <a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a>
                </li>
                <li class="hide-if-no-js">
                    <a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php esc_html_e('Most Used', 'fdsus'); ?></a>
                </li>
            </ul>

            <!-- Display taxonomy terms -->
            <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
                <ul id="<?php echo $taxonomy; ?>checklist"
                    class="list:<?php echo $taxonomy ?> categorychecklist form-no-clear">
                    <?php foreach ($terms as $term) {
                        $id = $taxonomy . '-' . $term->term_id;
                        echo "<li id='$id'><label class='selectit'>";
                        echo "<input type='$input_type' id='in-$id' name='{$name}'"
                            . checked($current, $term->term_id, false)
                            . "value='$term->term_id' />$term->name<br />";
                        echo "</label></li>";
                    } ?>
                </ul>
            </div>

            <!-- Display popular taxonomy terms -->
            <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel"
                 style="display: none;">
                <ul id="<?php echo $taxonomy; ?>checklist-pop"
                    class="categorychecklist form-no-clear">
                    <?php foreach ($popular as $term) {
                        $id = 'popular-' . $taxonomy . '-' . $term->term_id;
                        echo "<li id='$id'><label class='selectit'>";
                        echo "<input type='$input_type' id='in-$id'"
                            . checked($current, $term->term_id, false)
                            . "value='$term->term_id' />$term->name<br />";
                        echo "</label></li>";
                    } ?>
                </ul>
            </div>

        </div>
        <?php
    }

    /**
     * Save details when data is updated
     *
     * @param int $post_id
     * @param WP_Post $post
     *
     * @return mixed
     */
    public function saveDetails($post_id, $post)
    {
        if (!isset($post->ID)) return $post_id;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
        if (wp_is_post_revision($post_id)) return;
        if (!in_array($post->post_type, (array)$this->meta_box['post_type'])) return $post_id;

        foreach ($this->meta_box['fields'] as $field) {
            $value = null;

            // Remove blanks from repeater
            if ($field['type'] == 'repeater' && isset($_POST[$field['key']])) {
                usort($field['fields'], array(&$this, 'sortByOrder'));
                foreach ($_POST[$field['key']] as $key => $value) {
                    $has_value = false;
                    foreach ($value as $v) {
                        if (isset($v) && $v != '') {
                            $has_value = true;
                            break;
                        }
                    }
                    if ($has_value === false) unset($_POST[$field['key']][$key]);
                }
                reset($_POST[$field['key']]);
            }

            if (isset($_POST[$field['key']])) $value = $_POST[$field['key']];
            $value = (isset($value)) ? $value : null;
            $meta_key = $field['key'];
            $meta_value = $value;

            /**
             * Filter whether to update metadata of a specific DLSMB field
             *
             * Returning a non-null value will effectively short-circuit loop
             * of the foreach.
             *
             * @since 0.5
             *
             * @param null|bool $check Whether to allow updating metadata.
             * @param int $post_id Post ID.
             * @param string $meta_key Meta key.
             * @param mixed $meta_value Meta value. Must be serializable if non-scalar.
             */
            $check = apply_filters('dlsmb_update_post_metadata', null, $post->ID, $meta_key, $meta_value);
            if (null !== $check) {
                continue;
            }

            $value = apply_filters('dlsmb_values_before_save', $value, $field['key'], $post);
            $this->_saveMeta($post->ID, $value, $field);
        }
        reset($this->meta_box);

        do_action('dlsmb_save_details_after', $this->meta_box);
    }

    /**
     * Save meta
     *
     * @param int   $postId
     * @param null  $value
     * @param array $field
     */
    private function _saveMeta($postId, $value, $field)
    {
        // Convert display date format to db date Ymd
        if ($field['type'] === 'datepicker') {
            if (!empty($value) && $value != '0000-00-00') {
                $value = date('Ymd', strtotime($value));
            }
        }

        if (!array_key_exists($field['key'], $_POST)) {
            // TODO needed to remove for saving things like checkboxes which won't be in post - need to fix for bulk edit
            $inlineEdit = isset($_POST['_inline_edit']) ? $_POST['_inline_edit'] : null ;
            if (in_array($field['type'], array('checkbox', 'checkboxes'))
                && wp_verify_nonce($inlineEdit, 'inlineeditnonce') == false
            ) {
                delete_post_meta($postId, $field['key']);
            }
            return;
        }
        // TODO needed to remove for saving things like checkboxes which won't be in post - need to fix for bulk edit

        if (in_array($field['type'], array('checkboxes', 'multiselect'))) {
            // Overwrite all values if changed
            $currValue = get_post_meta($postId, $field['key']);
            if ($currValue != $value) {
                delete_post_meta($postId, $field['key']);
                foreach ((array)$value as $v) {
                    add_post_meta($postId, $field['key'], $v);
                }
            }
            return;
        }

        update_post_meta($postId, $field['key'], $value);
    }

    /**
     * Echo image data in JSON format (for use with AJAX)
     */
    public function ajaxImageData()
    {
        $options = array(
            'attachment_id' => null,
        );
        $options = array_merge($options, $_GET);

        $return = array();

        if(empty($options['attachment_id'])) die(0);
        $image = wp_get_attachment_image_src(intval($_GET['attachment_id']), 'thumbnail');

        $return = array(
            'id' => intval($_GET['attachment_id']),
            'preview' => $image[0],
            'preview_height' => $image[1],
            'preview_width' => $image[2]
        );

        echo json_encode($return);
        die(0);
    }

    /**
     * Remove media library tab
     *
     * @param $tabs
     * @return mixed
     */
    public function removeMediaLibraryTab($tabs)
    {
        if (isset($_REQUEST['dlsmb']) && $_REQUEST['dlsmb'] == 'yes') {
            unset($tabs['type_url']);
            return $tabs;
        }
        return $tabs;
    }

    /**
     * Admin Enqueue Scripts
     */
    public function adminEnqueueScripts()
    {
        wp_enqueue_media();

        // Datepicker (check in case older WP version since it didn't have jquery-ui-datepicker)
        if ( ! wp_script_is( 'jquery-ui-datepicker', 'registered' ) ) {
            wp_enqueue_script(
                'jquery-ui-datepicker',
                plugins_url( 'assets/jquery.ui.datepicker.min.js', __FILE__ ),
                array( 'jquery-ui-core' ),
                '1.8.9',
                1
            );
        }
        wp_enqueue_script( 'jquery-ui-datepicker' );

        // Enqueue jQuery UI CSS that matches UI version
        global $wp_scripts;
        $ui_core = ( isset( $wp_scripts->registered['jquery-ui-core'] ) ) ? $wp_scripts->registered['jquery-ui-core'] : null;
        if (
            isset( $wp_scripts->registered['jquery-ui-core'] )
            && ! empty( $ui_core->ver )
            && $url = ( is_ssl() ? 'https' : 'http' ) . '://ajax.googleapis.com/ajax/libs/jqueryui/' . $ui_core->ver . '/themes/smoothness/jquery-ui.css'
        ) {
            wp_enqueue_style( $this->prefix . '-jquery-ui', $url, array(), $ui_core->ver );
        }

        // Timepicker
        wp_enqueue_script(
            $this->prefix . '-timepicker',
            plugins_url( 'assets/jquery.ui.timepicker-addon.js', __FILE__ ),
            array( 'jquery-ui-datepicker' ),
            '1.5.3',
            true
        );
        wp_enqueue_style(
            $this->prefix . '-timepicker',
            plugins_url( 'assets/jquery.ui.timepicker-addon.css', __FILE__ ),
            array(),
            '1.5.3'
        );

        // Chosen
        wp_enqueue_script(
            $this->prefix . '-chosen',
            plugins_url( 'assets/chosen/chosen.jquery.min.js', __FILE__ ),
            array( 'jquery' ),
            '1.4.2',
            true
        );
        wp_enqueue_style(
            $this->prefix . '-chosen',
            plugins_url( 'assets/chosen/chosen.min.css', __FILE__ ),
            array(),
            '1.4.2'
        );

        // General
        wp_enqueue_script(
            $this->prefix . '-main',
            plugins_url( 'assets/admin.js', __FILE__ ),
            array( 'jquery', 'jquery-ui-sortable', 'media-upload', $this->prefix . '-timepicker' ),
            $this->version,
            true
        );
        wp_enqueue_style(
            $this->prefix . '-style',
            plugins_url( 'assets/style.css', __FILE__ ),
            array( $this->prefix . '-timepicker' ),
            $this->version
        );
    }

    public function postsColumns($columns, $postType)
    {
        if ($postType == $this->meta_box['post_type']) {
            foreach ($this->meta_box['fields'] as $field) {
                if (empty($field['show_column']) || $field['show_column'] !== true) continue;
                $columns[$field['key']] = $field['label'];
            }
            reset($this->meta_box);
        }
        return $columns;
    }

    public function populatePostsColumns($column_name, $post_id)
    {
        foreach ($this->meta_box['fields'] as $field) {
            if (empty($field['show_column']) || $field['show_column'] !== true) continue;
            if ($column_name != $field['key']) continue;

            // Set column output
            $text = null;
            $values = get_post_meta($post_id, $field['key']);


            switch ($field['type']) {
                case 'multiselect':
                    $text = implode(', ', $values) . '<span class="dlsmb-qe-value">' . json_encode($values, JSON_FORCE_OBJECT) . '</span>';
                    break;
                default:
                    $text = '<span class="dlsmb-qe-value">' . implode(', ', $values) . '</span>';
            }

            /**
             * Filter the visible displayed information for this column cell
             *
             * @since 1.0.0
             *
             * @param string $display_output The actual content that will be displayed to users
             * @param string $column_name
             * @param int $post_id
             * @param array $field The meta field
             * @param array $values The result of get_post_meta() for this field
             */
            $display = apply_filters(
                $this->prefix . '_column_display',
                implode(', ', $values),
                $column_name,
                $post_id,
                $field,
                $values
            );

            /**
             * Filter the quick edit value for this column cell
             *
             * @since 1.0.0
             *
             * @param string $display_output The current quick edit value
             * @param string $column_name
             * @param int $post_id
             * @param array $field The meta field
             * @param array $values The result of get_post_meta() for this field
             */
            $qe_value = apply_filters(
                $this->prefix . '_column_qe_value',
                json_encode($values),
                $column_name,
                $post_id,
                $field,
                $values
            );

            $output = $display . '<span class="dlsmb-qe-value">' . $qe_value . '</span>';

            echo sprintf('<div id="%s-%s">%s</div>',
                $column_name,
                $post_id,
                $output
            );
        }
        reset($this->meta_box);
    }

    public function addToBulkQuickEditCustomBox($column_name, $postType)
    {
        if ($postType == $this->meta_box['post_type']) {
            $field_count = count($this->meta_box['fields']);
            $i = 0;
            foreach ($this->meta_box['fields'] as $field) {
                if (empty($field['show_column']) || $field['show_column'] !== true) continue;
                if ($column_name != $field['key']) continue;
                $i++;

                if ($i === 1) {
                    ?>
                    <div class="dlsmb-field dlsmb-field-type-<?php echo $field['type'] ?> dlsmb-field-key-<?php echo $field['type'] ?>-<?php echo $field['key'] ?>">
                    <fieldset class="inline-edit-col-right">
                    <div class="inline-edit-col">
                    <div class="inline-edit-group">
                    <?php
                }
                ?>
                <label class="inline-edit-status">
                <span class="title"><?php echo $field['label']; ?></span>
                <?php

                $this->_displayInput($field, null);
                ?>
                </label>

                <?php
            }
            reset($this->meta_box);
            if ($i > 0) {
                ?>
                </div><!-- .inline-edit-group -->
                </div><!-- .inline-edit-col -->
                </fieldset>
                </div>
                <!-- .dlsmb-field -->
                <?php
            }
        }
    }

    /**
     * Display field
     *
     * @param array $field
     * @param string $value
     * @param string $repeaterKey
     * @param int $repeaterCount
     */
    private function _displayField($field, $value, $repeaterKey = null, $repeaterCount = 0)
    {

        echo '<div class="dlsmb-field dlsmb-field-type-' . $field['type']
            . ' dlsmb-field-key-' . $field['type'] . '-' . $field['key']
            . (!empty($field['wrap_class']) ? ' ' . $field['wrap_class'] : '')
            . '">';

        $id = $this->prefix . '-field-' . $field['type'] . '-' . $this->_repeaterNameAsId($repeaterKey) . '-' . $repeaterCount . '-' . $field['key'];
        if (!empty($field['label'])) echo '<label for="' . $id . '" class="dlsmb-main-label">' . $field['label'] . '</label>';

        $this->_displayInput($field, $value, $repeaterKey, $repeaterCount);

        if (!empty($field['append'])) echo $field['append'];

        echo '</div>';

    }

    /**
     * Display actual input, select, textarea, etc field
     *
     * @param $field
     * @param $value
     * @param null $repeaterKey
     * @param int $repeaterCount
     *
     * @todo finish adding disabled to all field types
     */
    private function _displayInput($field, $value, $repeaterKey = null, $repeaterCount = 0)
    {
        $field_name = (is_null($repeaterKey)) ? $field['key'] : $repeaterKey . '[' . $repeaterCount . '][' . $field['key'] . ']';
        $value = apply_filters('dlsmb_values_before_render',$value, $field_name, get_post());
        $id = $this->prefix . '-field-' . $field['type'] . '-' . $this->_repeaterNameAsId($repeaterKey) . '-' . $repeaterCount . '-' . $field['key'];

        if (empty($field['class'])) {
            $field['class'] = null;
        }

        switch ($field['type']) {

            case 'textarea':
                echo sprintf('<textarea class="dlsmb-field-element" name="%1$s" id="%2$s" style="%3$s" %4$s %5$s>%6$s</textarea>',
                    $field_name,
                    $id,
                    'width: 100%; ' . (!empty($field['style']) ? ' ' . $field['style'] : null),
                    !empty($field['rows']) ? ' rows="' . $field['rows'] . '"' : null,
                    !empty($field['disabled']) ? ' disabled="disabled"' :  null,
                    esc_html($value)
                );
                break;

            case 'wysiwyg':
                $field['args']['textarea_name'] = $field_name;
                $field['args']['editor_class'] = 'dlsmb-field-element';
                wp_editor($value, $id, $field['args']);
                break;

            case 'radio':
                foreach ($field['options'] as $opt_key => $opt_val) {
                    $checked = ($value === $opt_val) ? ' checked="checked"' : null;
                    echo '<input type="radio" name="' . $field_name . '" value="' . $opt_val . '" class="dlsmb-field-element" id="' . $id . '-' . $opt_key . '"' . $checked . '> <label for="' . $id . '-' . $opt_key . '">' . $opt_val . '</label><br />';
                }
                break;

            case 'checkbox':
                $checked = ($value === 'true') ? ' checked="checked"' : null;
                $boxLabel = empty($field['box_label_override']) ? esc_html__('True', 'fdsus') : $field['box_label_override'];
                echo '<input type="checkbox" name="' . $field_name . '" value="true" class="dlsmb-field-element" id="' . $id . '"' . $checked . '> <label for="' . $id . '" class="dlsmb-checkbox">' . $boxLabel . '</label><br />';
                break;

            case 'checkboxes':
                foreach ($field['options'] as $opt_key => $opt_val) {
                    echo sprintf(
                        '<label for="%4$s"><input type="checkbox" name="%1$s[]" value="%2$s" class="dlsmb-field-element" id="%4$s" %5$s> %3$s</label><br />',
                        $field_name,
                        $opt_key,
                        $opt_val,
                        sanitize_key($id . '-' . $opt_key),
                        in_array((string)$opt_key, (array)$value, true) ? ' checked="checked"' : null
                    );
                }
                break;

            case 'dropdown':
            case 'select':
                echo sprintf('<select name="%1$s" id="%2$s" class="dlsmb-field-element %3$s" %4$s data-placeholder="%5$s">',
                    $field_name,
                    $id,
                    $field['class'],
                    !empty($field['disabled']) ? ' disabled="disabled"' : null,
                    $value
                );
                foreach ($field['options'] as $opt_key => $opt_val) {
                    $checked = ($value === (string)$opt_key) ? ' selected="selected"' : null;
                    echo '<option value="' . $opt_key . '" ' . $checked . '>' . $opt_val . '</option>';
                }
                echo '</select>';
                break;

            case 'multiselect':
                echo '<select name="' . $field_name . '[]" id="' . $field['key'] . '" class="dlsmb-field-element ' . $field['class'] . '" multiple="multiple">';
                foreach ($field['options'] as $opt_key => $opt_val) {
                    $checked = (in_array((string)$opt_key, (array)$value, true)) ? ' selected="selected"' : null;
                    echo '<option value="' . $opt_key . '" ' . $checked . '>' . $opt_val . '</option>';
                }
                echo '</select>';
                break;

            case 'image':
                if (!empty($value)) $image = wp_get_attachment_image_src($value, 'thumbnail');
                $image_style = (!empty($image)) ? 'style="width: ' . $image[1] . 'px;"' : null;
                $image_output = (!empty($image)) ? '<img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="" />' : null;
                echo '
                    <div class="dlsmb-image-wrap" ' . $image_style . '>
                        <div class="dlsmb-preview-image">
                            <div class="dlsmb-image-hover">
                                <ul>
                                    <li class="dlsmb-image-hover-icon dlsmb-image-hover-edit"><a href="#" title="' . esc_html__('Edit', 'fdsus') . '"></a></li>
                                    <li class="dlsmb-image-hover-icon dlsmb-image-hover-remove"><a href="#" title="' . esc_html__('Remove', 'fdsus') . '"></a></li>
                                </ul>
                            </div>
                            ' . $image_output . '
                        </div>
                        <input type="hidden" id="' . $id . '" name="' . $field_name . '" class="dlsmb-input-image" value="' . $value . '" />
                        <input type="button" class="button dlsmb-add-image" value="Add Image" />
                        <img class="dlsmb-ajax-loading" alt="" src="' . admin_url('images/wpspin_light.gif') . '">
                    </div>
                ';
                break;

            case 'repeater':
                $value = (array)maybe_unserialize($value);
                $blank_row = array();

                echo PHP_EOL . PHP_EOL . '<table><thead><tr>' . PHP_EOL;

                // Head
                usort($field['fields'], array(&$this, 'sortByOrder'));
                foreach ($field['fields'] as $f) {
                    echo '<th class="dlsmb-repeater-cell-' . esc_attr($field['key'] . '-' . $f['key']) . '">' . $f['label'] . '</th>' . PHP_EOL;
                    $blank_row[$f['key']] = null;
                }
                reset($field['fields']);
                if (!in_array($blank_row, $value)) $value[] = $blank_row;
                $value[] = $blank_row; // for template

                echo '<th class="dlsmb-js-add-remove"></th>';

                echo PHP_EOL . '</tr></thead><tbody>' . PHP_EOL;

                // Body
                if (!empty($value) && is_array($value)) {
                    $i = 0;
                    foreach ($value as $k => $v) {

                        /**
                         * Filter whether to override the output of repeater row
                         *
                         * Returning a non-null value will effectively short-circuit loop
                         * of the foreach.
                         *
                         * @since 1.0
                         *
                         * @param null|bool $check Whether to allow updating metadata.
                         * @param array $field
                         * @param array $v
                         * @param int $i
                         *
                         * @return null|mixed
                         */
                        $check = apply_filters($this->prefix . '_override_repeater_row', null, $field, $v, $i);
                        if (null !== $check) {
                            $i++;
                            continue;
                        }

                        $row_key = ($repeaterCount === 'X' || ($i + 1) == count($value)) ? 'X' : $i;
                        $repeaterKeyAppend = (is_null($repeaterKey)) ? $field['key'] : '[' . $row_key . '][' . $field['key'] . ']';
                        $repeaterKeyNew = $repeaterKey . $repeaterKeyAppend;
                        $blankClass = (($i + 1) == count($value)) ? " dlsmb-blank-repeater" : null;

                        echo sprintf('<tr class="%s %s" id="%s">',
                            'dlsmb-repeater-' . $field['key'] . '-row',
                            $blankClass,
                            'dlsmb-repeater-' . $this->_repeaterNameAsId($repeaterKeyNew) . '-row-' . $row_key . '"'
                        );
                        echo PHP_EOL;

                        // Fields
                        foreach ($field['fields'] AS $f) {
                            echo '<td class="dlsmb-repeater-cell-' . esc_attr($field['key'] . '-' . $f['key']) . '">' . PHP_EOL;
                            $this->_displayField($f, (isset($v[$f['key']]) ? $v[$f['key']] : null), $repeaterKeyNew, $row_key);
                            echo '</td>' . PHP_EOL;
                        }

                        echo '<td class="dlsmb-js-add-remove">' . $this->_repeaterActions($field) . '</td>' . PHP_EOL;
                        echo '</tr>' . PHP_EOL;
                        reset($field['fields']);
                        $i++;
                    }
                }

                echo '</tbody></table>' . PHP_EOL . PHP_EOL;
                break;

            case 'datepicker':
                // NOTE: Display format is 'Y-m-d', Database format is 'Ymd'
                if (!empty($value)) $value = date('Y-m-d', strtotime($value));
                echo '<input type="text" id="' . $id . '" name="' . $field_name . '" value="' . $value . '" class="dlsmb-field-element dlsmb-datepicker"' . (!empty($field['style']) ? ' style="' . $field['style'] . '"' : null) . ' />';
                break;

            case 'timepicker':
                if (!empty($value)) $value = date('h:i a', strtotime($value));
                echo '<input type="text" id="' . $id . '" name="' . $field_name . '" value="' . $value . '" class="dlsmb-field-element dlsmb-timepicker"' . (!empty($field['style']) ? ' style="' . $field['style'] . '"' : null) . ' />';
                break;

            case 'datetimepicker':
                if (!empty($value)) {
                    $value = date('Y-m-d h:i a', $value);
                }elseif ($value == 0) {
                    $value = '';
                }

                echo '<input type="text" id="' . $id . '" name="' . $field_name . '" value="' . $value . '" class="dlsmb-field-element dlsmb-datetimepicker"' . (!empty($field['style']) ? ' style="'.$field['style'].'"' : null) . ' />';
                break;

            case 'timezone':
                $current_offset = get_option('gmt_offset');
                $tzstring = $value;
                // Remove old Etc mappings. Fallback to gmt_offset.
                if (false !== strpos($tzstring, 'Etc/GMT'))
                    $tzstring = '';
                // Create a UTC+- zone if no timezone string exists
                if (empty($tzstring)) {
                    $check_zone_info = false;
                    if (0 == $current_offset)
                        $tzstring = 'UTC+0';
                    elseif ($current_offset < 0)
                        $tzstring = 'UTC' . $current_offset;
                    else
                        $tzstring = 'UTC+' . $current_offset;
                }
                echo '<select id="' . $id . '" name="' . $field_name . '" class="dlsmb-field-element dlsmb-timezone"' . (!empty($field['style']) ? ' style="' . $field['style'] . '"' : null) . '>';
                echo wp_timezone_choice($tzstring);
                echo '</select>';
                break;

            case 'text':
            case 'hidden':
            case 'password':
                echo sprintf(
                    '<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="dlsmb-field-element" %5$s %6$s />',
                    $field['type'],
                    $id,
                    $field_name,
                    esc_html($value),
                    !empty($field['style']) ? ' style="' . $field['style'] . '"' : null,
                    !empty($field['disabled']) ? ' disabled="disabled"' : null
                );
                break;

            case 'map':
                $value = (array)maybe_unserialize($value);
                $value['address'] = isset($value['address']) ? $value['address'] : '';
                $value['lat'] = isset($value['lat']) ? $value['lat'] : '';
                $value['long'] = isset($value['long']) ? $value['long'] : '';
                if (empty($field['map_api_key'])) {
                    $error_class = 'map-not-loaded';
                    $msg = '<p>' .esc_html__('Map not loaded. Check your api key.', 'fdsus') . '</p>';
                } else {
                    $error_class = '';
                    $msg = '';
                }
                echo '<input type="hidden" id="dlsmb-map-address" name="' . $field_name . '[address]" value="' . esc_attr($value['address']) . '">';
                echo '<input type="hidden" id="dlsmb-map-lat" name="' . $field_name . '[lat]" value="' . esc_attr($value['lat']) . '">';
                echo '<input type="hidden" id="dlsmb-map-long" name="' . $field_name . '[long]" value="' . esc_attr($value['long']) . '">';
                echo '<input id="searchInput" class="controls" type="text" placeholder="Enter a location">
                      <div id="map" class="' . $error_class . '">'. $msg .'</div>
                      <ul id="geoData">
                          <li>Full Address: <span id="location">' . $value['address'] . '</span></li>
                          <li>Latitude: <span id="lat">' . $value['lat'] . '</span></li>
                          <li>Longitude: <span id="lon">' . $value['long'] . '</span></li>
                      </ul>';
                if ($field['map_api_key']) {
                    $this->addMapJs($field['map_api_key']);
                }
                break;

            default:
                echo '<input type="text" id="' . $id . '" name="' . $field_name . '" value="' . esc_html($value) . '" class="dlsmb-field-element" ' . (!empty($field['style']) ? ' style="' . $field['style'] . '"' : null) . ' />';

        }
    }

    /**
     * Get repeater actions output
     *
     * @param array $field
     *
     * @return string
     */
    private function _repeaterActions($field)
    {
        $actions = array(
            'add' => array(
                'title' => esc_html__('Add Row', 'fdsus'),
                'icon' => 'dashicons dashicons-plus-alt',
            ),
            'remove' => array(
                'title' => esc_html__('Delete Row', 'fdsus'),
                'icon' => 'dashicons dashicons-trash',
            ),
        );

        /**
         * Filter the repeater actions
         *
         * @since 1.0
         *
         * @param array $actions
         * @param array $field
         */
        $actions = apply_filters($this->prefix . '_repeater_actions', $actions, $field);

        if (!is_array($actions) || empty($actions)) return '';

        $out = '';
        foreach ($actions as $actionCode => $actionDetail) {
            $out .= sprintf(
                '<a href="#" class="dlsmb-icon dlsmb-js-%s" title="%s"><i class="%s"></i></a>' . PHP_EOL,
                $actionCode,
                $actionDetail['title'],
                $actionDetail['icon']
            );
        }

        return $out;
    }

    /**
     * @param string $name
     * @return mixed|string
     */
    private function _repeaterNameAsId($name)
    {
        $name = is_null($name) ? '' : $name;
        $asId = str_replace('][', '-', $name);
        $asId = str_replace('[', '-', $asId);
        $asId = str_replace(']', '-', $asId);
        $asId = rtrim($asId, '-');

        return $asId;
    }

    /**
     * Register/Enqueue map js
     *
     * @param string $key
     */
    public function addMapJs($key)
    {
        wp_register_script(
            $this->prefix . '-map-js',
            plugins_url('assets/google-map.js', __FILE__),
            array('jquery'),
            '',
            false
        );
        wp_localize_script($this->prefix . '-map-js', 'dlsmbGoogleApiKey', $key);
        wp_enqueue_script($this->prefix . '-map-js');
    }

    /**
     * Populate quick edit values
     */
    public function quickEditJS()
    {
        // Skip if no quick edit fields
        $has_quick_edit_field = false;
        foreach ($this->meta_box['fields'] as $field) {
            if (empty($field['show_column']) || $field['show_column'] !== true) continue;
            $has_quick_edit_field = true;
        }
        if (!$has_quick_edit_field) return;
        if (get_post_type() != $this->meta_box['post_type']) return;
        ?>

        <!-- Edit Scripts: <?php echo $this->meta_box['id']; ?> -->
        <script type="text/javascript">
            (function ($) {

                function padZero(inNumber){
                    if( isNaN(inNumber) ) return;

                    if(inNumber<10){
                        outNumber = '0' + inNumber.toString();
                    }else{
                        outNumber = inNumber;
                    }
                    return outNumber
                }

                function timeConverter(UNIX_timestamp){
                    var a = new Date(UNIX_timestamp * 1000);
                    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    var year = a.getFullYear();
                    var month = padZero(a.getMonth() + 1);
                    var date =  padZero(a.getDate());
                    var hour = padZero(a.getHours());
                    var min = padZero(a.getMinutes());
                    var sec = padZero(a.getSeconds());
                    var time =  year + '-' + month + '-' + date + ' ' + hour + ':' + min  ;
                    return time;
                }


                // QUICK EDIT

                // Create a copy of the WP inline edit post function
                var wp_inline_edit = inlineEditPost.edit;
                // Then overwrite function with custom code
                inlineEditPost.edit = function (id) {

                    // "call" the original WP edit function (we don't want to leave WordPress hanging)
                    wp_inline_edit.apply(this, arguments);

                    // Get post ID
                    var post_id = 0;
                    if (typeof( id ) == 'object')
                        post_id = parseInt(this.getId(id));

                    if (post_id > 0) {

                        // define the edit row
                        var edit_row = $('#edit-' + post_id);

                        <?php
                        foreach ($this->meta_box['fields'] as $field):
                            if (empty($field['show_column']) || $field['show_column'] !== true) continue;
                            ?>
                            var <?php echo $field['key'] ?> = $.parseJSON($('#<?php echo $field['key']; ?>-' + post_id + ' SPAN.dlsmb-qe-value').text());
                            <?php
                            // Set value
                            switch ($field['type']) {

                                case 'radio':
                                case 'checkbox':
                                case 'checkboxes':
                                case 'select':
                                   ?>
                                    edit_row.find('select[name="<?php echo $field['key']; ?>"]').val(<?php echo $field['key'] ?>[0]);
                                    <?php
                                    break;
                                case 'image':
                                case 'repeater':
                                case 'datepicker':
                                case 'timepicker':
                                case 'timezone':
                                case 'hidden':
                                    // @todo finish
                                    break;

                                case 'datetimepicker':
                                    ?>
                                    edit_row.find('input[name="<?php echo $field['key']; ?>"]').val(<?php echo $field['key'] ?>[0]);
                                    <?php
                                    break;
                                case 'multiselect':
                                    // @todo Fix "chosen" on quick edit
                                    ?>
                                    jQuery.each(<?php echo $field['key'] ?>, function (k, v) {
                                        edit_row.find('SELECT#dlslot_venue_id option[value="' + v + '"]').prop('selected', true).trigger('chosen:updated');
                                    });
                                    <?php
                                    break;

                                case 'textarea':
                                    ?>
                                    edit_row.find('textarea[name="<?php echo $field['key']; ?>"]').val(<?php echo $field['key'] ?>[0]);
                                    <?php
                                    break;

                                case 'text':
                                default:
                                    ?>
                                    edit_row.find('input[name="<?php echo $field['key']; ?>"]').val(<?php echo $field['key'] ?>);
                                    <?php
                                    break;
                                    }
                                endforeach;
                                reset($this->meta_box['fields']);
                                ?>

                            }

                };

                // BATCH EDIT
                //TODO
                $('body').on('click', '#bulk_edit', function () {

                    // define the bulk edit row
                    var bulk_row = $('#bulk-edit');

                    // get the selected post ids that are being edited
                    var post_ids = new Array();
                    bulk_row.find('#bulk-titles').children().each(function () {
                        post_ids.push($(this).attr('id').replace(/^(ttle)/i, ''));
                    });

                    // Get meta field values
                    <?php
                    foreach ($this->meta_box['fields'] as $field):
                        if (empty($field['show_column']) || $field['show_column'] !== true) continue;

                        // Set value
                        switch ($field['type']) {

                            case 'radio':
                            case 'checkbox':
                            case 'checkboxes':
                            case 'image':
                            case 'repeater':
                            case 'datepicker':
                            case 'timepicker':
                                // @todo finish
                                break;

                            case 'select':
                            case 'multiselect':
                            case 'timezone':
                                ?>
                                var <?php echo $field['key']; ?> = bulk_row.find('select[name="<?php echo $field['key']; ?>"]').val();
                                <?php
                                break;

                            case 'textarea':
                                ?>
                                var <?php echo $field['key']; ?> = bulk_row.find('textarea[name="<?php echo $field['key']; ?>"]').val();
                                <?php
                                break;

                            case 'text':
                            default:
                                ?>
                                var <?php echo $field['key']; ?> = bulk_row.find('input[name="<?php echo $field['key']; ?>"]').val();
                                <?php
                                break;
                        }
                    endforeach;
                    reset($this->meta_box['fields']);
                    ?>

                    // Save data
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        async: true,
                        cache: false,
                        data: {
                            action: '<?php echo $this->prefix ?>_save_bulk_edit',
                            post_ids: post_ids
                            <?php
                            foreach ($this->meta_box['fields'] as $field):
                                if (empty($field['show_column']) || $field['show_column'] !== true) continue;
                                echo ", {$field['key']} : {$field['key']}\n";
                            endforeach;
                            reset($this->meta_box['fields']);
                            ?>
                        }
                    });

                });

            })(jQuery);
        </script>
        <?php
    }

    /**
     * Save bulk edit
     */
    public function saveBulkEdit()
    {
        $post_ids = (isset($_POST['post_ids']) && !empty($_POST['post_ids'])) ? $_POST['post_ids'] : array();
        if (empty($post_ids) || !is_array($post_ids)) return;

        foreach ($post_ids as $post_id) {
            if (get_post_type($post_id) != $this->meta_box['post_type']) continue;
            foreach ($this->meta_box['fields'] as $field) {
                if (empty($field['show_column']) || $field['show_column'] !== true) continue;
                $value = (!empty($_POST[$field['key']])) ? $_POST[$field['key']] : null;
                $this->_saveMeta($post_id, $value, $field);
            }
        }
    }

}
