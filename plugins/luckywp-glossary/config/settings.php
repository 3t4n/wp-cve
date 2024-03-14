<?php

use luckywp\glossary\admin\widgets\AccessRoles;
use luckywp\glossary\admin\widgets\PremiumBadge;
use luckywp\glossary\admin\widgets\typePostsCheckboxList\TypePostsCheckboxList;
use luckywp\glossary\admin\wp\PremiumSettings;
use luckywp\glossary\core\Core;
use luckywp\glossary\core\wp\RewriteRules;

return [

    // Основные настройки
    'general' => [
        'label' => esc_html__('General', 'luckywp-glossary'),
        'sections' => [

            // Общее
            'general' => [
                'title' => '',
                'fields' => [

                    // Страница архива
                    'archive_page' => [
                        'label' => esc_html__('Archive Page', 'luckywp-glossary'),
                        'widget' => 'pages',
                        'sanitizeCallback' => function ($value) {
                            RewriteRules::flushAfterReload();
                            return (int)$value;
                        },
                    ],

                    // Стуктура URL термина
                    'term_structure' => [
                        'label' => esc_html__('Term Permalink', 'luckywp-glossary'),
                        'widget' => 'textInput',
                        'params' => [
                            'before' => function () {
                                return '<code>' . get_option('home') . '/</code>';
                            },
                            'after' => function () {
                                global $wp_rewrite;
                                return $wp_rewrite->use_trailing_slashes ? '<code>/</code>' : '';
                            },
                            'inputOptions' => [
                                'class' => 'code',
                                'placeholder' => Core::$plugin->route->getPermalinksConfig('term_structure', 'default'),
                            ],
                        ],
                        'desc' => esc_html__('Available tags', 'luckywp-glossary') . ': <b>%archive%</b>, <b>%term%</b>.',
                        'sanitizeCallback' => function ($value) {
                            return untrailingslashit($value);
                        },
                    ],
                ],
            ],

            // Внешний вид
            'appearance' => [
                'title' => '<span class="lwpglsColorMuted">' . esc_html__('Appearance', 'luckywp-glossary') . '</span>' . PremiumBadge::widget(),
                'fields' => [
                    'columns' => [
                        'label' => '<span class="lwpglsColorMuted">' . esc_html__('Columns', 'luckywp-glossary') . '</span>',
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                1 => 1,
                                2 => 2,
                                3 => 3,
                                4 => 4,
                                5 => 5,
                            ],
                        ],
                        'default' => 2,
                        'sanitizeCallback' => function () {
                            return 2;
                        },
                    ],
                    'group_symbol_logic' => [
                        'label' => '<span class="lwpglsColorMuted">' . esc_html__('Group Symbol Logic', 'luckywp-glossary') . '</span>',
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'firstSymbol' => esc_html__('First symbol', 'luckywp-glossary'),
                                'firstLetterOrNumber' => esc_html__('First letter or number', 'luckywp-glossary'),
                            ],
                        ],
                        'default' => 'firstSymbol',
                        'sanitizeCallback' => function () {
                            return 'firstSymbol';
                        },
                    ],
                    'hide_synonyms_on_archive_page' => [
                        'label' => '',
                        'widget' => [PremiumSettings::class, 'checkbox'],
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Hide synonyms on glossary archive page', 'luckywp-glossary'),
                            ],
                        ],
                        'default' => false,
                    ],
                    'synonyms_place' => [
                        'label' => '<span class="lwpglsColorMuted">' . esc_html__('Synonyms on the term page', 'luckywp-glossary') . '</span>',
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'no' => esc_html__('Do not show', 'luckywp-glossary'),
                                'top' => esc_html__('Before content', 'luckywp-glossary'),
                                'bottom' => esc_html__('After content', 'luckywp-glossary'),
                            ],
                        ],
                        'default' => 'top',
                        'sanitizeCallback' => function () {
                            return 'top';
                        },
                    ],
                ],
            ],

            // Ссылки
            'links' => [
                'title' => '<span class="lwpglsColorMuted">' . esc_html__('Automatic links placement to terms', 'luckywp-glossary') . '</span> ' . PremiumBadge::widget(),
                'fields' => [
                    'post_types_for_links' => [
                        'label' => '<span class="lwpglsColorMuted">' . esc_html__('Posts types for links placement', 'luckywp-glossary') . '</span>',
                        'widget' => 'widget',
                        'params' => [
                            'class' => TypePostsCheckboxList::class,
                            'fake' => true,
                        ],
                        'desc' => esc_html__('Links to the terms pages that are mentioned in posts will be added to the content of the selected posts.', 'luckywp-glossary'),
                    ],
                    'one_link_per_term' => [
                        'label' => '',
                        'widget' => [PremiumSettings::class, 'checkbox'],
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Place links only to the first occurrence of the term', 'luckywp-glossary'),
                            ],
                        ],
                        'default' => false,
                    ],
                ],
            ],

            // Доступы
            'access' => [
                'title' => '<span class="lwpglsColorMuted">' . esc_html__('Access settings', 'luckywp-glossary') . '</span> ' . PremiumBadge::widget(),
                'fields' => [
                    'access_roles' => [
                        'label' => '<span class="lwpglsColorMuted">' . esc_html__('Full access to terms management', 'luckywp-glossary') . '</span>',
                        'widget' => function ($field) {
                            echo AccessRoles::widget([
                                'field' => $field,
                                'fake' => true,
                            ]);
                        },
                        'default' => ['administrator'],
                    ],
                ],
            ],
        ],
    ],

    // Прочее
    'misc' => [
        'label' => esc_html__('Misc.', 'luckywp-glossary'),
        'sections' => [
            'main' => [
                'fields' => [
                    'no_check_terms_archive_shortcode' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => sprintf(
                                /* translators: %s: [lwpglsTermsArchive] */
                                    esc_html__('Don\'t check shortcode %s on glossary archive page', 'luckywp-glossary'),
                                    '<code>[lwpglsTermsArchive]</code>'
                                ),
                            ],
                        ],
                        'default' => true,
                    ],
                ],
            ],
        ],
    ],
];
