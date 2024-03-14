<?php

declare(strict_types=1);

/**
 * Setting: Preselect (labelled as "Preselect ‘Generate audio’")
 *
 * @package Beyondwords\Wordpress
 * @author  Stuart McAlpine <stu@beyondwords.io>
 * @since   3.0.0
 */

namespace Beyondwords\Wordpress\Component\Settings\Preselect;

use Beyondwords\Wordpress\Component\Settings\SettingsUtils;

/**
 * Preselect setup
 *
 * @since 3.0.0
 */
class Preselect
{
    public const DEFAULT_PRESELECT = [
        'post' => '1',
        'page' => '1',
    ];

    /**
     * Init.
     *
     * @since 4.0.0
     */
    public function init()
    {
        add_action('admin_init', array($this, 'registerSetting'));
        add_action('admin_init', array($this, 'addSettingsField'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    /**
     * Init setting.
     *
     * @since  3.0.0
     *
     * @return void
     */
    public function registerSetting()
    {
        if (! SettingsUtils::hasApiSettings()) {
            return;
        }

        register_setting(
            'beyondwords',
            'beyondwords_preselect',
            [
                'default' => Preselect::DEFAULT_PRESELECT,
            ]
        );
    }

    /**
     * Init setting.
     *
     * @since  3.0.0
     *
     * @return void
     */
    public function addSettingsField()
    {
        add_settings_field(
            'beyondwords-preselect',
            __('Preselect ‘Generate audio’', 'speechkit'),
            array($this, 'render'),
            'beyondwords',
            'generate-audio'
        );
    }

    /**
     * Render setting field.
     *
     * @since 3.0.0
     *
     * @return void
     **/
    public function render()
    {
        $postTypes = SettingsUtils::getCompatiblePostTypes();

        if (! is_array($postTypes) || count($postTypes) === 0) :
            ?>
            <p class="description">
                <?php
                _e(
                    'No compatible post types found. This plugin will only work with post types that support custom fields.', // phpcs:ignore Generic.Files.LineLength.TooLong
                    'speechkit'
                );
                ?>
            </p>
            <?php
            return;
        endif;

        foreach ($postTypes as $name) :
            $postType = get_post_type_object($name);
            ?>
            <div class="beyondwords-setting--preselect--post-type">
                <label>
                    <input
                        type="checkbox"
                        name="beyondwords_preselect[<?php echo esc_attr($postType->name); ?>]"
                        value="1"
                        <?php checked($this->postTypeIsSelected($postType), true); ?>
                    />
                    <?php echo esc_html($postType->label); ?>
                </label>
                <?php $this->renderTaxonomyFields($postType); ?>
            </div>
            <?php
        endforeach;
    }

    /**
     * Get the taxonomy fields, as a hierarchical list of nested checkboxes.
     *
     * @since 3.0.0
     *
     * @return void
     **/
    public function renderTaxonomyFields($postType)
    {
        $taxonomies = get_object_taxonomies($postType->name, 'objects');

        if ($taxonomies) {
            ?>
            <div class="beyondwords-setting--preselect--taxonomy" style="margin: 0.5rem 0;">
                <?php
                foreach ($taxonomies as $taxonomy) {
                    // Ignore the "Format" taxonomy (aside, etc)
                    if ($taxonomy->name === 'post_format') {
                        continue;
                    }
                    // todo enable for custom taxonomies, and add tests for them
                    if ($taxonomy->name !== 'category') {
                        continue;
                    }
                    ?>
                    <h4 style="margin: 0.5rem 0 0.5rem 1.5rem;"><?php echo esc_html($taxonomy->label); ?></h4>
                    <?php
                    $this->renderTaxonomyTerms($postType, $taxonomy);
                }
                ?>
            </div>
            <?php
        }
    }

    /**
     * Get the taxonomy terms, as a hierarchical list of nested checkboxes.
     *
     * @since 3.0.0
     *
     * @return void
     **/
    public function renderTaxonomyTerms($postType, $taxonomy, $parent = 0)
    {
        $terms = get_terms([
            'taxonomy'   => $taxonomy->name,
            'hide_empty' => false,
            'parent'     => $parent,
        ]);

        if ($terms) {
            ?>
            <ul style="margin: 0; padding: 0; list-style:none;">
                <?php
                foreach ($terms as $term) :
                    $inputName = sprintf(
                        "beyondwords_preselect[%s][%s][]",
                        $postType->name,
                        $taxonomy->name
                    );
                    ?>
                    <li class="beyondwords-setting--preselect--term" style="margin: 0.5rem 0 0 1.5rem;">
                        <label>
                            <input
                                type="checkbox"
                                name="<?php echo esc_attr($inputName); ?>"
                                value="<?php echo esc_attr($term->term_id); ?>"
                                <?php checked($this->termIsSelected($postType, $taxonomy, $term), true) ?>
                            />
                            <?php echo esc_html($term->name); ?>
                        </label>
                        <?php $this->renderTaxonomyTerms($postType, $taxonomy, $term->term_id); ?>
                    </li>
                    <?php
                endforeach;
                ?>
                </ul>
            <?php
        }
    }

    public function postTypeIsSelected($postType)
    {
        $preselect = get_option('beyondwords_preselect');

        if (! is_array($preselect)) {
            return false;
        }

        return array_key_exists($postType->name, $preselect) && $preselect[$postType->name] === '1';
    }

    public function taxonomyIsSelected($postType, $taxonomy)
    {
        $preselect = get_option('beyondwords_preselect');

        if (! is_array($preselect)) {
            return false;
        }

        if (! isset($preselect[$postType->name]) || ! is_array($preselect[$postType->name])) {
            return false;
        }

        return in_array($taxonomy->name, $preselect[$postType->name]);
    }

    public function termIsSelected($postType, $taxonomy, $term)
    {
        $preselect = get_option('beyondwords_preselect');

        if (! is_array($preselect)) {
            return false;
        }

        if (! isset($preselect[$postType->name]) || ! is_array($preselect[$postType->name])) {
            return false;
        }

        if (! isset($preselect[$postType->name][$taxonomy->name]) || ! is_array($preselect[$postType->name][$taxonomy->name])) { // phpcs:ignore Generic.Files.LineLength.TooLong
            return false;
        }

        return in_array($term->term_id, $preselect[$postType->name][$taxonomy->name]);
    }

    /**
     * Register the component scripts.
     *
     * @since  3.0.0
     *
     * @param string $hook Page hook
     *
     * @return void
     */
    public function enqueueScripts($hook)
    {
        if ($hook === 'settings_page_beyondwords') {
            wp_enqueue_script(
                'beyondwords-settings--preselect-settings',
                BEYONDWORDS__PLUGIN_URI . 'src/Component/Settings/Preselect/settings.js',
                ['jquery', 'underscore'],
                BEYONDWORDS__PLUGIN_VERSION,
                true
            );
        }

        if ($hook === 'post.php' || $hook === 'post-new.php') {
            wp_register_script(
                'beyondwords-settings--preselect-post',
                BEYONDWORDS__PLUGIN_URI . 'src/Component/Settings/Preselect/post.js',
                ['jquery', 'underscore'],
                BEYONDWORDS__PLUGIN_VERSION,
                true
            );

            // Localize the script with new data
            $data = [
                'postType'  => get_post_type(),
                'preselect' => get_option('beyondwords_preselect'),
            ];

            wp_localize_script('beyondwords-settings--preselect-post', 'beyondwords', $data);

            wp_enqueue_script('beyondwords-settings--preselect-post');
        }
    }
}
