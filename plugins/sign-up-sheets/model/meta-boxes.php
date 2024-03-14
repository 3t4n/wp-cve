<?php
/**
 * Meta Boxes Model
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Sheet as SheetModel;

class MetaBoxes extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get meta boxes
     *
     * @return array
     */
    public function get()
    {
        $trueFalse = array(
            ''      => esc_html__('Global', 'fdsus'),
            'true'  => esc_html__('True', 'fdsus'),
            'false' => esc_html__('False', 'fdsus'),
        );

        $metaBoxes = array(

            // General
            'general' => array(
                'id'        => SheetModel::POST_TYPE . '-general-meta',
                'title'     => esc_html__('General', 'fdsus'),
                'post_type' => SheetModel::POST_TYPE,
                'context'   => 'normal',
                'priority'  => 'high',
                'fields'    => array(
                    array(
                        'label' => esc_html__('Date', 'fdsus'),
                        'key'   => 'dlssus_date',
                        'type'  => 'datepicker',
                        'order' => 10,
                    ),
                ),
            ),

            // Tasks
            'tasks' => array(
                'id'        => SheetModel::POST_TYPE . '-tasks-meta',
                'title'     => esc_html__('Tasks', 'fdsus'),
                'post_type' => SheetModel::POST_TYPE,
                'context'   => 'normal',
                'priority'  => 'high',
                'fields'    => array(
                    array(
                        'label' => null,
                        'key' => 'dlssus_tasks',
                        'type' => 'repeater',
                        'fields' => array(
                            array(
                                'label' => esc_html__('What', 'fdsus'),
                                'key'   => 'title',
                                'type'  => 'text',
                                'order' => 10
                            ),
                            array(
                                'label' => esc_html__('# of Spots', 'fdsus'),
                                'key'   => 'qty',
                                'type'  => 'text',
                                'order' => 20
                            ),
                            array(
                                'label' => null,
                                'key'   => 'id',
                                'type'  => 'hidden',
                                'order' => 9999
                            ),
                            array(
                                'label' => null,
                                'key'   => 'task_row_type',
                                'type'  => 'hidden',
                                'order' => 9999
                            ),
                        ),
                    ),
                ),
            ),

            // Additional Settings
            'settings' => array(
                'id'        => SheetModel::POST_TYPE . '-settings-meta',
                'title'     => 'Additional Settings',
                'post_type' => SheetModel::POST_TYPE,
                'context'   => 'normal',
                'priority'  => 'low',
                'fields'    => array(
                    array(
                        'label'   => esc_html__('Set Phone as Optional', 'fdsus'),
                        'key'     => Id::PREFIX . '_optional_phone',
                        'type'    => 'select',
                        'options' => $trueFalse,
                        'order'   => 10,
                    ),
                    array(
                        'label'   => esc_html__('Set Address as Optional', 'fdsus'),
                        'key'     => Id::PREFIX . '_optional_address',
                        'type'    => 'select',
                        'options' => $trueFalse,
                        'order'   => 20,
                    ),
                    array(
                        'label'   => esc_html__('Hide Phone Field', 'fdsus'),
                        'key'     => Id::PREFIX . '_hide_phone',
                        'type'    => 'select',
                        'options' => $trueFalse,
                        'order'   => 30,
                    ),
                    array(
                        'label'   => esc_html__('Hide Address Fields', 'fdsus'),
                        'key'     => Id::PREFIX . '_hide_address',
                        'type'    => 'select',
                        'options' => $trueFalse,
                        'order'   => 40,
                    ),
                    array(
                        'label'   => esc_html__('Hide Email Field', 'fdsus'),
                        'key'     => Id::PREFIX . '_hide_email',
                        'type'    => 'select',
                        'options' => $trueFalse,
                        'order'   => 45,
                    ),
                    array(
                        'label'  => esc_html__('Sheet Specific BCC', 'fdsus'),
                        'key'    => Id::PREFIX . '_sheet_bcc',
                        'type'   => 'text',
                        'append' => ' <em>' . esc_html__('Comma-separated list of emails to be copied on confirmations/removals', 'fdsus') . '</em>',
                        'order'  => 50,
                        'wrap_class' => 'dlsmb-field-col-12',
                        'pro'    => true
                    ),
                    array(
                        'label'  => esc_html__('Reminder Schedule', 'fdsus'),
                        'key'    => Id::PREFIX . '_sheet_reminder_days',
                        'type'   => 'text',
                        'append' => ' <br><em>'
                            . esc_html__('Number of days before the date on the sign-up sheet that the email should be sent.  Use whole numbers, for example, to remind one day before use...', 'fdsus')
                            . ' <code>1</code> '
                            . esc_html__('(If this is blank Global setting is used. Global setting in Settings > Sign-up Sheets.)', 'fdsus')
                            . '</em>',
                        'order'  => 60,
                        'wrap_class' => 'dlsmb-field-col-12',
                        'pro'    => true
                    ),
                    array(
                        'label'   => esc_html__('Compact Sign-up Mode', 'fdsus'),
                        'key'     => Id::PREFIX . '_compact_signups',
                        'type'    => 'select',
                        'options' => array(
                            ''      => esc_html__('Global', 'fdsus'),
                            'false' => esc_html__('Disabled', 'fdsus'),
                            'true'  => esc_html__('Enabled', 'fdsus'),
                            'semi'  => esc_html__('Semi-Compact', 'fdsus'),
                        ),
                        'append'  => ' <em>' . esc_html__('Show sign-up spots on one line with just # of open spots and a link to sign-up if open. Semi-Compact will also include the names of those who already signed up (assuming "Front-end Display Names" is not set to "anonymous"', 'fdsus') . '</em>',
                        'order'   => 70,
                        'wrap_class' => 'dlsmb-field-col-12',
                        'pro'     => true
                    ),
                    array(
                        'label'   => esc_html__('Enable Task Checkboxes', 'fdsus'),
                        'key'     => Id::PREFIX . '_use_task_checkboxes',
                        'type'    => 'select',
                        'options' => $trueFalse,
                        'order'   => 80,
                        'pro'     => true
                    ),
                    array(
                        'label'   => esc_html__('Enable task sign-up limit', 'fdsus'),
                        'key'     => Id::PREFIX . '_task_signup_limit',
                        'type'    => 'select',
                        'options' => $trueFalse,
                        'order'   => 90,
                        'pro'     => true
                    ),
                    array(
                        'label'   => esc_html__('Enable contiguous task sign-up limit', 'fdsus'),
                        'key'     => Id::PREFIX . '_contiguous_task_signup_limit',
                        'type'    => 'select',
                        'options' => $trueFalse,
                        'order'   => 100,
                        'pro'     => true
                    ),
                    array(
                        'label'   => esc_html__('Auto-clear Schedule', 'fdsus'),
                        'key'     => 'fdsus_autoclear',
                        'type'    => 'checkboxes',
                        'options' => $this->getDaysOfWeekArray(),
                        'append' => Id::isPro() && !Settings::isAutoclearSignupsAllowed()
                            ? ' <em>' . esc_html__('Auto-clear is not currently allowed globally under "Sign-up Sheets > Settings". Enable it for this setting to take effect.', 'fdsus') . '</em>'
                            : '',
                        'order'   => 110,
                        'wrap_class' => 'dlsmb-field-col-12',
                        'pro'     => true
                    ),
                    array(
                        'label'      => esc_html__('Confirmation Email Message', 'fdsus'),
                        'key'        => Id::PREFIX . '_sheet_email_conf_message',
                        'type'       => 'textarea',
                        'append'     => ' <br><em>' . esc_html__('Global setting in Settings > Sign-up Sheets', 'fdsus') . '</em>',
                        'order'      => 130,
                        'wrap_class' => 'dlsmb-field-col-6',
                        'pro'        => true
                    ),
                    array(
                        'label'      => esc_html__('Reminder Email Message', 'fdsus'),
                        'key'        => Id::PREFIX . '_sheet_email_message',
                        'type'       => 'textarea',
                        'append'     => ' <br><em>' . esc_html__('Global setting in Settings > Sign-up Sheets', 'fdsus') . '</em>',
                        'order'      => 140,
                        'wrap_class' => 'dlsmb-field-col-6',
                        'pro'        => true
                    ),
                ),
            ),

        );

        /**
         * Filter for sheet meta boxes array
         *
         * @param array $metaBoxes
         *
         * @return array
         * @since 2.2
         */
        $metaBoxes = apply_filters('fdsus_sheet_meta_boxes', $metaBoxes);

        // Include Pro settings
        foreach ($metaBoxes as $key => $metaBox) {
            if (!empty($metaBox['fields']) && is_array($metaBox['fields'])) {
                foreach ($metaBox['fields'] as $subKey => $subField) {
                    if (!empty($subField['pro'])) {
                        if (!Id::isPro()) {
                            $metaBoxes[$key]['fields'][$subKey]['label'] = '<span class="dls-sus-pro" title="Pro Feature">Pro</span> ' . $metaBoxes[$key]['fields'][$subKey]['label'];
                            $metaBoxes[$key]['fields'][$subKey]['key'] = 'pro_feature_' . (int)$key . '_' . (int)$subKey;
                        }
                        $metaBoxes[$key]['fields'][$subKey]['disabled'] = !Id::isPro();
                        if (!isset($metaBoxes[$key]['fields'][$subKey]['wrap_class'])) {
                            $metaBoxes[$key]['fields'][$subKey]['wrap_class'] = '';
                        }
                        $metaBoxes[$key]['fields'][$subKey]['wrap_class'] .= Id::isPro() ? '' : ' fdsus-pro-setting';
                    }
                }
            }
        }

        return $metaBoxes;
    }
}
