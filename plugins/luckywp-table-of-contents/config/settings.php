<?php

use luckywp\tableOfContents\admin\controllers\SettingsController;
use luckywp\tableOfContents\admin\widgets\fontSizeField\FontSizeField;
use luckywp\tableOfContents\admin\widgets\skipHeadingLevelField\SkipHeadingLevelField;
use luckywp\tableOfContents\admin\widgets\widthField\WidthField;
use luckywp\tableOfContents\core\admin\helpers\AdminHtml;
use luckywp\tableOfContents\admin\widgets\PostTypes;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\Html;
use luckywp\tableOfContents\plugin\Settings;

return [

    // Основные настройки
    'general' => [
        'label' => esc_html__('General', 'luckywp-table-of-contents'),
        'sections' => [
            'main' => [
                'fields' => [

                    'min' => [
                        'label' => esc_html__('Minimal Count of Headings', 'luckywp-table-of-contents'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_SMALL,
                            ],
                        ],
                        'default' => 2,
                        'desc' => esc_html__('If the count of headings in the post is less, then table of contents is not displayed.', 'luckywp-table-of-contents'),
                    ],

                    'depth' => [
                        'label' => esc_html__('Depth', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->depthsList,
                        ],
                        'default' => 6,
                    ],

                    'hierarchical' => [
                        'label' => esc_html__('Hierarchical View', 'luckywp-table-of-contents'),
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Enable', 'luckywp-table-of-contents'),
                            ],
                        ],
                        'default' => true,
                    ],

                    'numeration' => [
                        'label' => esc_html__('Numeration', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->numerationsList,
                            'selectOptions' => [
                                'class' => 'js-lwptocNumerationSelect',
                            ],
                        ],
                        'default' => 'decimalnested',
                    ],

                    'numerationSuffix' => [
                        'label' => esc_html__('Numeration Suffix', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->getNumerationSuffixsList(),
                            'selectOptions' => [
                                'class' => 'js-lwptocNumerationSuffixSelect',
                            ],
                        ],
                        'default' => 'none',
                        'desc' => sprintf(
                        /* translators: %s: 1.3) */
                            esc_html__('The symbol will be added after numeration. For example, %s', 'luckywp-table-of-contents'),
                            '<code>1.3)</code>'
                        ),
                    ],
                ],
            ],
            'header' => [
                'title' => esc_html__('Header', 'luckywp-table-of-contents'),
                'fields' => [

                    'title' => [
                        'label' => esc_html__('Title', 'luckywp-table-of-contents'),
                        'widget' => 'textInput',
                        'default' => __('Contents', 'luckywp-table-of-contents'),
                    ],

                    'toggle' => [
                        'label' => esc_html__('Toggle Show/Hide', 'luckywp-table-of-contents'),
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Enable', 'luckywp-table-of-contents'),
                                'class' => 'js-lwptocToggleCheckbox',
                            ],
                        ],
                        'default' => true,
                    ],

                    'labelShow' => [
                        'label' => esc_html__('Label Show', 'luckywp-table-of-contents'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'class' => 'js-lwptocToggleEl'
                            ],
                        ],
                        'default' => __('show', 'luckywp-table-of-contents'),
                    ],

                    'labelHide' => [
                        'label' => esc_html__('Label Hide', 'luckywp-table-of-contents'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'class' => 'js-lwptocToggleEl'
                            ],
                        ],
                        'default' => __('hide', 'luckywp-table-of-contents'),
                    ],

                    'hideItems' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('By default, items of contents will be hidden', 'luckywp-table-of-contents'),
                                'class' => 'js-lwptocToggleEl',
                            ],
                        ],
                        'default' => false,
                    ],
                ],
            ],
            'behavior' => [
                'title' => esc_html__('Behavior', 'luckywp-table-of-contents'),
                'fields' => [

                    'smoothScroll' => [
                        'label' => esc_html__('Smooth Scroll', 'luckywp-table-of-contents'),
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Enable', 'luckywp-table-of-contents'),
                                'class' => 'js-lwptocSmoothScrollCheckbox',
                            ],
                        ],
                        'default' => true,
                    ],

                    'smoothScrollOffset' => [
                        'label' => esc_html__('Scroll Offset Top', 'luckywp-table-of-contents'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_SMALL,
                                'class' => 'js-lwptocSmoothScrollIOffsetInput'
                            ],
                            'after' => ' px',
                        ],
                        'default' => 24,
                    ],
                ],
            ],
        ],
    ],

    // Внешний вид
    'appearance' => [
        'label' => esc_html__('Appearance', 'luckywp-table-of-contents'),
        'sections' => [
            'main' => [
                'fields' => [

                    'width' => [
                        'label' => esc_html__('Width', 'luckywp-table-of-contents'),
                        'widget' => function ($field) {
                            echo WidthField::widget([
                                'name' => $field['name'],
                                'value' => Core::$plugin->settings->getValue($field['group'], $field['id'], 'auto', false),
                            ]);
                        },
                        'sanitizeCallback' => [Settings::class, 'sanitizeWidth'],
                        'default' => 'auto',
                    ],

                    'float' => [
                        'label' => esc_html__('Float', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->floatsList,
                        ],
                        'default' => 'none',
                    ],

                    'titleFontSize' => [
                        'label' => esc_html__('Title Font Size', 'luckywp-table-of-contents'),
                        'widget' => function ($field) {
                            echo FontSizeField::widget([
                                'name' => $field['name'],
                                'value' => Core::$plugin->settings->getValue($field['group'], $field['id'], 'default', false),
                                'defaultSize' => 100,
                            ]);
                        },
                        'sanitizeCallback' => [Settings::class, 'sanitizeFontSize'],
                        'default' => 'default',
                    ],

                    'titleFontWeight' => [
                        'label' => esc_html__('Title Font Weight', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->fontWeightsList,
                        ],
                        'default' => 'bold',
                    ],

                    'itemsFontSize' => [
                        'label' => esc_html__('Items Font Size', 'luckywp-table-of-contents'),
                        'widget' => function ($field) {
                            echo FontSizeField::widget([
                                'name' => $field['name'],
                                'value' => Core::$plugin->settings->getValue($field['group'], $field['id'], 'default', false),
                                'defaultSize' => 90,
                            ]);
                        },
                        'sanitizeCallback' => [Settings::class, 'sanitizeFontSize'],
                        'default' => '90%',
                    ],

                    'colorScheme' => [
                        'label' => esc_html__('Color Scheme', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->colorSchemesList,
                        ],
                        'default' => 'light',
                    ],
                ],
            ],
            'overrideColors' => [
                'title' => esc_html__('Override Color Scheme Colors', 'luckywp-table-of-contents'),
                'fields' => [

                    'backgroundColor' => [
                        'widget' => 'color',
                        'label' => esc_html__('Background Color', 'luckywp-table-of-contents'),
                    ],

                    'borderColor' => [
                        'widget' => 'color',
                        'label' => esc_html__('Border Color', 'luckywp-table-of-contents'),
                    ],

                    'titleColor' => [
                        'widget' => 'color',
                        'label' => esc_html__('Title Color', 'luckywp-table-of-contents'),
                    ],

                    'linkColor' => [
                        'widget' => 'color',
                        'label' => esc_html__('Link Color', 'luckywp-table-of-contents'),
                    ],

                    'hoverLinkColor' => [
                        'widget' => 'color',
                        'label' => esc_html__('Hover Link Color', 'luckywp-table-of-contents'),
                    ],

                    'visitedLinkColor' => [
                        'widget' => 'color',
                        'label' => esc_html__('Visited Link Color', 'luckywp-table-of-contents'),
                    ],
                ],
            ],
        ],
    ],

    // Автоматическая вставка
    'autoInsert' => [
        'label' => esc_html__('Auto Insert', 'luckywp-table-of-contents'),
        'sections' => [
            'main' => [
                'fields' => [

                    'enable' => [
                        'label' => esc_html__('Auto Insert Table of Contents', 'luckywp-table-of-contents'),
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Enable', 'luckywp-table-of-contents'),
                                'class' => 'js-lwptocAutoInsertEnableCheckbox',
                            ],
                        ],
                        'default' => false,
                    ],

                    'position' => [
                        'label' => esc_html__('Position', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->positionsList,
                            'selectOptions' => [
                                'class' => 'js-lwptocAutoInsertEl',
                            ],
                        ],
                        'default' => 'beforefirstheading',
                    ],

                    'postTypes' => [
                        'label' => esc_html__('Post Types', 'luckywp-table-of-contents'),
                        'widget' => function ($field) {
                            echo PostTypes::widget([
                                'field' => $field,
                                'containerOptions' => [
                                    'class' => 'js-lwptocAutoInsertEl',
                                ],
                            ]);
                        },
                        'default' => ['post'],
                    ],
                ],
            ],
        ],
    ],

    // Обработка заголовков
    'processingHeadings' => [
        'label' => esc_html__('Processing Headings', 'luckywp-table-of-contents'),
        'sections' => [
            'main' => [
                'fields' => [
                    'postTypes' => [
                        'label' => esc_html__('Always for Post Types', 'luckywp-table-of-contents'),
                        'widget' => function ($field) {
                            echo PostTypes::widget([
                                'field' => $field,
                            ]);
                        },
                    ],
                ],
            ],
        ],
    ],

    // Прочее
    'misc' => [
        'label' => esc_html__('Misc.', 'luckywp-table-of-contents'),
        'sections' => [
            'skipHeading' => [
                'title' => esc_html__('Skip Headings', 'luckywp-table-of-contents'),
                'fields' => [
                    'skipHeadingLevel' => [
                        'label' => esc_html__('By Level', 'luckywp-table-of-contents'),
                        'widget' => function ($field) {
                            echo SkipHeadingLevelField::widget([
                                'name' => $field['name'],
                                'value' => Core::$plugin->settings->getValue($field['group'], $field['id'], [], false),
                            ]);
                        },
                        'sanitizeCallback' => [Settings::class, 'sanitizeSkipHeadingLevel'],
                    ],
                    'skipHeadingText' => [
                        'label' => esc_html__('By Text', 'luckywp-table-of-contents'),
                        'widget' => function ($field) {
                            $value = Core::$plugin->settings->getValue($field['group'], $field['id'], '', false);
                            echo Html::textarea($field['name'], Core::$plugin->skipHeadingTextToMultipleString((string)$value), [
                                'class' => 'regular-text',
                                'rows' => 5,
                            ]);
                            echo '<p class="description">';
                            echo esc_html__('Specify headings (one per line) to be excluded from the table of contents.', 'luckywp-table-of-contents');
                            echo '<br>';
                            echo sprintf(
                            /* translators: %s: Asterisk symbol (*) */
                                esc_html__('Use an asterisk %s as a wildcard to match any text.', 'luckywp-table-of-contents'),
                                '<code>*</code>'
                            );
                            echo '</p>';
                        },
                        'sanitizeCallback' => function ($value) {
                            return Core::$plugin->skipHeadingTextMultipleStringToString((string)$value);
                        },
                    ],
                ],
            ],
            'hash' => [
                /* translators: Here, hash is a unique tracking string that is added to the URL after a # character */
                'title' => esc_html__('Hash', 'luckywp-table-of-contents'),
                'fields' => [

                    'hashFormat' => [
                        /* translators: Here, hash is a unique tracking string that is added to the URL after a # character */
                        'label' => esc_html__('Hash Format', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->getHashFormatsList(),
                        ],
                        'default' => 'asheading',
                    ],

                    'hashConvertToLowercase' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Convert to lowercase', 'luckywp-table-of-contents'),
                            ],
                        ],
                        'default' => false,
                    ],

                    'hashReplaceUnderlinesToDashes' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => sprintf(
                                /* translators: 1: _ 2: - */
                                    esc_html__('Replace underscores %1$s with dashes %2$s', 'luckywp-table-of-contents'),
                                    '<code>_</code>',
                                    '<code>-</code>'
                                ),
                            ],
                        ],
                        'default' => false,
                    ],
                ],
            ],
            'seo' => [
                'title' => esc_html__('SEO', 'luckywp-table-of-contents'),
                'fields' => [

                    'wrapNoindex' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => sprintf(
                                /* translators: %s: <!--noindex--> */
                                    esc_html__('Wrap table of contents with %s tag', 'luckywp-table-of-contents'),
                                    '<code>&lt;!--noindex--&gt;</code>'
                                ),
                            ],
                        ],
                        'default' => false,
                    ],

                    'useNofollow' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => sprintf(
                                /* translators: %s: rel="nofollow" */
                                    esc_html__('Use %s for links', 'luckywp-table-of-contents'),
                                    '<code>rel="nofollow"</code>'
                                ),
                            ],
                        ],
                        'default' => false,
                    ],
                ],
            ],
            'otherSettings' => [
                'title' => esc_html__('Other Settings', 'luckywp-table-of-contents'),
                'fields' => [
                    'showMetaboxPostTypes' => [
                        'label' => esc_html__('Show Panel "Table of Contents" in Post Types', 'luckywp-table-of-contents'),
                        'widget' => function ($field) {
                            echo PostTypes::widget([
                                'field' => $field,
                            ]);
                        },
                    ],
                    'listMarkupTags' => [
                        /* translators: User select DIV or UL/LI */
                        'label' => esc_html__('List Markup Tags', 'luckywp-table-of-contents'),
                        'widget' => 'select',
                        'params' => [
                            'items' => Core::$plugin->getListMarkupTagsList(),
                        ],
                        'default' => 'div',
                    ],
                    'containerClass' => [
                        'label' => esc_html__('Additional CSS Class(es)', 'luckywp-table-of-contents'),
                        'widget' => 'textInput',
                        'desc' => esc_html__('Separate multiple classes with spaces.', 'luckywp-table-of-contents'),
                    ],
                ],
            ],
        ],
    ],

    'plugins' => [
        'label' => esc_html__('LuckyWP Plugins', 'luckywp-table-of-contents'),
        'callback' => [SettingsController::getInstance(), 'plugins'],
    ],
];
