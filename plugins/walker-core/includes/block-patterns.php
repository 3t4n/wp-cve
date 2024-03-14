<?php

/**
 * Registering patterns categories for walker_core
 * @since 1.0.0
 * @package walker_core
 */
function walker_core_register_pattern_category()
{
    $wc_current_theme = wp_get_theme()->get('Name');
    if ($wc_current_theme == "BlockVerse") {
        $block_pattern_categories = array(
            'walkercore-patterns' => array('label' => __('Blockverse Pro Patterns', 'walker-core')),
        );
    } elseif ($wc_current_theme == "Blockpage") {
        $block_pattern_categories = array(
            'blockpage-newsletters' => array('label' => __('Blockpage: Newsletters', 'walker-core')),
            'blockpage-hours' => array('label' => __('Blockpage: Working Hours', 'walker-core')),
            'blockpage-events' => array('label' => __('Blockpage: Events List', 'walker-core')),
        );
    } else {
        $block_pattern_categories = array(
            'wnt-patterns' => array('label' => __('WNT Pro Patterns', 'walker-core')),
        );
    }



    $block_pattern_categories = apply_filters('walker_core_block_pattern_categories', $block_pattern_categories);

    foreach ($block_pattern_categories as $name => $properties) {
        if (!WP_Block_Pattern_Categories_Registry::get_instance()->is_registered($name)) {
            register_block_pattern_category($name, $properties); // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_pattern_category
        }
    }
}
add_action('init', 'walker_core_register_pattern_category');
/**
 * source: https://github.com/WordPress/wordpress-develop/blob/6.1/src/wp-includes/block-patterns.php#L198-L336
 * Register any patterns that the active theme may provide under its
 * `./patterns/` directory. Each pattern is defined as a PHP file and defines
 * its metadata using plugin-style headers. The minimum required definition is:
 *
 *     /**
 *      * Title: My Pattern
 *      * Slug: my-theme/my-pattern
 *      *
 *
 * The output of the PHP source corresponds to the content of the pattern, e.g.:
 *
 *     <main><p><?php echo "Hello"; ?></p></main>
 *
 * If applicable, this will collect from both parent and child theme.
 *
 * Other settable fields include:
 *
 *   - Description
 *   - Viewport Width
 *   - Categories       (comma-separated values)
 *   - Keywords         (comma-separated values)
 *   - Block Types      (comma-separated values)
 *   - Post Types       (comma-separated values)
 *   - Inserter         (yes/no)
 *
 * @since 6.0.0
 * @access private
 */
function _walker_core_register_plugin_block_patterns()
{
    $default_headers = array(
        'title'         => 'Title',
        'slug'          => 'Slug',
        'description'   => 'Description',
        'viewportWidth' => 'Viewport Width',
        'categories'    => 'Categories',
        'keywords'      => 'Keywords',
        'blockTypes'    => 'Block Types',
        'postTypes'     => 'Post Types',
        'inserter'      => 'Inserter',
    );

    /*
	 * Register patterns for the active theme. If the theme is a child theme,
	 * let it override any patterns from the parent theme that shares the same slug.
	 */
    $dirpath = plugin_dir_path(__DIR__) . '/patterns/';
    if (!is_dir($dirpath) || !is_readable($dirpath)) {
        return;
    }

    if (file_exists($dirpath)) {
        $files = glob($dirpath . '*.php');
        if ($files) {
            foreach ($files as $file) {
                $pattern_data = get_file_data($file, $default_headers);

                if (empty($pattern_data['slug'])) {
                    _doing_it_wrong(
                        '_walker_core_register_plugin_block_patterns',
                        sprintf(
                            /* translators: %s: file name. */
                            __('Could not register file "%s" as a block pattern ("Slug" field missing)'),
                            $file
                        ),
                        '6.0.0'
                    );
                    continue;
                }

                if (!preg_match('/^[A-z0-9\/_-]+$/', $pattern_data['slug'])) {
                    _doing_it_wrong(
                        '_walker_core_register_plugin_block_patterns',
                        sprintf(
                            /* translators: %1s: file name; %2s: slug value found. */
                            __('Could not register file "%1$s" as a block pattern (invalid slug "%2$s")'),
                            $file,
                            $pattern_data['slug']
                        ),
                        '6.0.0'
                    );
                }

                if (WP_Block_Patterns_Registry::get_instance()->is_registered($pattern_data['slug'])) {
                    continue;
                }

                // Title is a required property.
                if (!$pattern_data['title']) {
                    _doing_it_wrong(
                        '_walker_core_register_plugin_block_patterns',
                        sprintf(
                            /* translators: %1s: file name; %2s: slug value found. */
                            __('Could not register file "%s" as a block pattern ("Title" field missing)'),
                            $file
                        ),
                        '6.0.0'
                    );
                    continue;
                }

                // For properties of type array, parse data as comma-separated.
                foreach (array('categories', 'keywords', 'blockTypes', 'postTypes') as $property) {
                    if (!empty($pattern_data[$property])) {
                        $pattern_data[$property] = array_filter(
                            preg_split(
                                '/[\s,]+/',
                                (string) $pattern_data[$property]
                            )
                        );
                    } else {
                        unset($pattern_data[$property]);
                    }
                }

                // Parse properties of type int.
                foreach (array('viewportWidth') as $property) {
                    if (!empty($pattern_data[$property])) {
                        $pattern_data[$property] = (int) $pattern_data[$property];
                    } else {
                        unset($pattern_data[$property]);
                    }
                }

                // Parse properties of type bool.
                foreach (array('inserter') as $property) {
                    if (!empty($pattern_data[$property])) {
                        $pattern_data[$property] = in_array(
                            strtolower($pattern_data[$property]),
                            array('yes', 'true'),
                            true
                        );
                    } else {
                        unset($pattern_data[$property]);
                    }
                }

                // Translate the pattern metadata.
                $text_domain = 'walker-core';
                //phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText, WordPress.WP.I18n.NonSingularStringLiteralContext, WordPress.WP.I18n.NonSingularStringLiteralDomain, WordPress.WP.I18n.LowLevelTranslationFunction
                $pattern_data['title'] = translate_with_gettext_context($pattern_data['title'], 'Pattern title', $text_domain);
                if (!empty($pattern_data['description'])) {
                    //phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText, WordPress.WP.I18n.NonSingularStringLiteralContext, WordPress.WP.I18n.NonSingularStringLiteralDomain, WordPress.WP.I18n.LowLevelTranslationFunction
                    $pattern_data['description'] = translate_with_gettext_context($pattern_data['description'], 'Pattern description', $text_domain);
                }

                // The actual pattern content is the output of the file.
                ob_start();
                include $file;
                $pattern_data['content'] = ob_get_clean();
                if (!$pattern_data['content']) {
                    continue;
                }

                register_block_pattern($pattern_data['slug'], $pattern_data);
            }
        }
    }
}
add_action('init', '_walker_core_register_plugin_block_patterns');
