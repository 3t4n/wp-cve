<?php
/**
 * Admin Custom MetaBox
 *
 * To use, run the following within the page output of the admin page you want to output the metabox...
 *
 * // Get screen id
 * $screen = get_current_screen();
 *
 * // Init metabox class
 * $metabox = new MetaBoxController($screen->id);
 *
 * // Add the metabox to the system for later output (use add() multiple times for more than one)
 * $metabox->add(array(
 *      'id' => 'sheet',
 *      'title' => esc_html__('Sign-up Sheet', 'fdsus'),
 *      'order' => 10,
 *      'options' => array(
 *          'label'    => 'Display Label',
 *          'name'     => 'field_name',
 *          'type'     => 'text', // Field type
 *          'note'     => 'Optional note',
 *          'options'  => array(), // optional array for select and multi-checbox/radio type fields
 *          'order'    => 10, // sort order
 *          'class'    => 'some-class' // adds class to surrounding <tr> element
 *          'disabled' => false, // mark input field as disabled
 *          'value'    => '' // optional value that would override the default get_option() value pulled in this class
 *      ),
 *      'aria-labelledby' => 'my-label-id', // Default is '<name>-label' so only need to set this to change it or set to `false` to unset the aria-labelledby
 *      'aria-describedby' => 'my-description-id', // Default is not set
 *      'note'    => '<span id="my-description-id">' . esc_html__('Some description', 'fdsus') . '</span>',
 *      'pro'     => true
 * ));
 *
 * // Output the metaboxes (use only ONCE)
 * $metabox->output();
 *
 * Tips:
 *  - make sure to wrap your output with the CSS class `metabox-holder` somewhere in a parent element
 *  - make sure to include this JS on the page `wp_enqueue_script( 'post' );`
 */

namespace FDSUS\Controller\Admin;

class MetaBox
{
    public $screenId;
    public $id = '';
    public $title = '';
    public $args = array();

    /**
     * Construct
     *
     * @param int $screenId
     */
    public function __construct($screenId)
    {
        $this->screenId = $screenId;
        add_action('add_meta_boxes_' . $screenId, array($this, 'addFromAction'));
    }

    /**
     * Output
     */
    public function output()
    {
        do_meta_boxes($this->screenId, 'normal', '');
    }

    /**
     * Add
     *
     * @param array $args ['id', 'title', 'options']
     */
    public function add($args)
    {
        do_action('add_meta_boxes_' . $this->screenId, $args);
    }

    /**
     * Add from action
     *
     * @param array $args
     */
    public function addFromAction($args)
    {
        /**
         * @var int|bool $id
         * @var string   $title
         * @var array    $options
         */
        extract(
            shortcode_atts(
                array(
                    'id'      => '',
                    'title'   => '',
                    'options' => array(),
                ), $args
            )
        );

        add_meta_box(
            $id,
            $title,
            array($this, 'content'),
            $this->screenId,
            'normal',
            'default',
            array('options' => $options)
        );
    }

    /**
     * Content
     *
     * @param string $noidea unused
     * @param array  $callbackData
     */
    public function content($noidea, $callbackData)
    {
        if (!empty($callbackData['args'])
            && !empty($callbackData['args']['options'])
            && !is_array($callbackData['args']['options'])) {
            return;
        }
        ?>
        <table class="form-table">
            <?php
            foreach ($callbackData['args']['options'] as $o) :
                if (!isset($o['label'])) {
                    $o['label'] = isset($o[0]) ? $o[0] : null;
                }
                if (!isset($o['note'])) {
                    $o['note'] = isset($o[3]) ? $o[3] : null;
                }
                $thRowspan = !empty($o['th-rowspan']) ? ' rowspan="' . esc_attr($o['th-rowspan']) . '" ' : '';
                $labelId = !empty($o['name']) ? ' id="' . esc_attr($o['name']) . '-label"' : '';
                ?>
                <tr<?php if (!empty($o['class'])) echo ' class="' . esc_attr($o['class']) . '"'; ?>>
                    <?php if ($o['label'] !== false): ?>
                        <th scope="row"<?php echo $thRowspan ?>>
                            <span <?php echo $labelId ?>>
                            <?php echo wp_kses_post($o['label']); ?></span>:
                        </th>
                    <?php endif; ?>
                    <td>
                        <?php $this->displayFieldByType($o); ?>
                        <?php if (!empty($o['note'])) :?>
                            <span class="description"><?php echo wp_kses_post($o['note']); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }

    /**
     * Display field by type
     *
     * @param array|string $o
     * @param string|null  $parentName
     * @param string|null  $value
     */
    public function displayFieldByType($o, $parentName = null, $value = null)
    {
        // Set variables
        if (!isset($o['label'])) {
            $o['label'] = isset($o[0]) ? $o[0] : null;
        }
        if (!isset($o['name'])) {
            $o['name'] = isset($o[1]) ? $o[1] : null;
        }
        if (!empty($parentName)) {
            $o['name'] = $parentName . '[' . $o['name'] . ']';
        }
        if (!isset($o['type'])) {
            $o['type'] = isset($o[2]) ? $o[2] : null;
        }
        if (!isset($o['options'])) {
            $o['options'] = isset($o[4]) ? $o[4] : array();
        }
        $value = (!empty($value)) ? $value : (isset($o['value']) ? $o['value'] : get_option($o['name']));
        $disabled = !empty($o['disabled']) ? ' disabled' : '';
        $ariaLabelledby = isset($o['aria-labelledby']) && $o['aria-labelledby'] === false
            ? ''
            : (!empty($o['aria-labelledby']) ? $o['aria-labelledby'] : $o['name'] . '-label');
        $ariaLabelledby = !empty($ariaLabelledby)
            ? ' aria-labelledby="' . esc_attr($ariaLabelledby) . '" ' : '';
        $ariaDescribedby = !empty($o['aria-describedby'])
            ? ' aria-describedby="' . esc_attr($o['aria-describedby']) . '" ' : '';

        // Output by type
        switch ($o['type']) {
            case 'text':
            case 'number':
                echo '<input type="' . esc_attr($o['type']) . '" id="' . esc_attr($o['name'])
                    . '" name="' . esc_attr($o['name']) . '" value="' . esc_attr($value) . '" size="20"'
                    . $disabled . $ariaLabelledby . $ariaDescribedby . '>';
                break;
            case 'checkbox':
                echo '<input type="checkbox" id="' . esc_attr($o['name']) . '" name="' . esc_attr($o['name'])
                    . '" value="true"'.(($value === 'true') ? ' checked="checked"' : '')
                    . $disabled . $ariaLabelledby . $ariaDescribedby . '>';
                break;
            case 'checkboxes':
                $i = 0;
                foreach ($o['options'] as $k => $v) {
                    $checked = (is_array($value) && in_array($k, $value)) ? ' checked="checked"' : '';
                    echo '<input type="checkbox" name="' . esc_attr($o['name']) . '[]" value="' . esc_attr($k) . '"'
                        . $checked . ' id="' . esc_attr($o['name']) . '-' . $i . '"' . $disabled . '>';
                    echo ' <label for="' . esc_attr($o['name']) . '-' . $i . '">' . esc_html($v) . '</label><br>';
                    $i++;
                }
                break;
            case 'textarea':
                echo '<textarea id="' . esc_attr($o['name']) . '" name="' . esc_attr($o['name'])
                    . '" rows="8" style="width: 100%;"' . $disabled . $ariaLabelledby . $ariaDescribedby
                    . '>' . esc_html($value) . '</textarea>';
                break;
            case 'dropdown':
                echo '<select id="' . esc_attr($o['name']) . '" name="' . esc_attr($o['name']) . '"'
                    . $disabled . $ariaLabelledby . $ariaDescribedby . '>';
                foreach ($o['options'] as $k => $v) {
                    $selected = ($value == $k) ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr($k) . '"' . $selected . '>' . esc_html($v) . '</option>';
                }
                echo '</select>';
                break;
            case 'multiselect':
                echo '<select multiple="multiple" class="chosen-select" id="' . esc_attr($o['name']) . '" name=" '
                    . esc_attr($o['name']) . '[]"' . $disabled . $ariaLabelledby . $ariaDescribedby . '>';
                foreach ($o['options'] as $k => $v) {
                    $selected = (is_array($value) && in_array($k, $value)) ? ' selected="selected"' : '';
                    echo '<option value="' . esc_attr($k) . '"' . $selected . '>' . esc_html($v) . '</option>';
                }
                echo '</select>';
                break;
            case 'button':
                echo sprintf(
                    '<a href="%s" class="button button-secondary %s" id="%s"%s%s>%s</a>',
                    $o['options']['href'],
                    (!empty($o['options']['class'])) ? esc_attr($o['options']['class']) : null,
                    $o['name'],
                    (!empty($o['options']['target'])) ? ' target="' . esc_attr($o['options']['target']) . '"' : null,
                    (!empty($o['options']['onclick'])) ? ' onclick="' . esc_attr($o['options']['onclick']) . '"' : null,
                    $o['label']
                );
                break;
            case 'repeater':
                echo '</td><tr' . (!empty($o['class']) ? ' class="' . esc_attr($o['class']) . '"' : '') . '><td colspan="2"><table class="dls-sus-repeater">';

                echo '<tr>';
                if (!empty($o['options'])) {
                    foreach ($o['options'] AS $k=>$v) {
                        $description = (!empty($v['note'])) ? ' <span class="description">'.$v['note'].'</span>' : null;
                        echo '<th class="' . esc_attr($o['name']) . '_'
                            . esc_attr($k).'"><span id="' . esc_attr($o['name']) . '_' . esc_attr($k) . '-label">'
                            . wp_kses($v['label'] . $description, array(
                                'span' => array(
                                    'class' => array(),
                                    'id' => array(),
                                    'aria-describedby' => array(),
                                )
                            )) . '</a></th>';
                    }
                }
                echo '</tr>';

                if (!empty($value)) {
                    foreach ($value as $val_k => $val_v) {
                        echo '<tr>';
                        foreach ($o['options'] as $k => $v) {
                            echo '<td class="' . esc_attr($o['name']) . '_' . esc_attr($k) . '">';
                            if (empty($v['aria-labelledby'])) {
                                $v['aria-labelledby'] = $o['name'] . '_' . $k . '-label';
                            }
                            if (!isset($value[$val_k][$v['name']])) {
                                $value[$val_k][$v['name']] = null;
                            }
                            $this->displayFieldByType($v, $o['name'] . '[' . $val_k . ']', $value[$val_k][$v['name']]);
                            echo '</td>';
                        }
                        echo '</tr>';
                    }
                }

                echo '<tr>';
                if (!empty($o['options'])) {
                    foreach ($o['options'] as $k => $v) {
                        echo '<td class="' . esc_attr($o['name']) . '_' . esc_attr($k) . '">';
                        if (empty($v['aria-labelledby'])) {
                            $v['aria-labelledby'] = $o['name'] . '_' . $k . '-label';
                        }
                        if (!isset($val_k)) {
                            $val_k = 0;
                        }
                        $this->displayFieldByType($v, $o['name'] . '[' . ($val_k + 1) . ']');
                        echo '</td>';
                    }
                }
                echo '</tr>';
                echo '</table>';
        }
    }
}
