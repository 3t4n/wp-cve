<?php

namespace AOP\App\Options\Fields;

use AOP\App\Plugin;
use AOP\Lib\Illuminate\Support\Collection;

class Image
{
    use TraitGeneral;

    /**
     * @var mixed
     */
    private $pageName;

    /**
     * @var mixed
     */
    private $settingsName;

    /**
     * @var string
     */
    private $sectionName;

    /**
     * @var string
     */
    private $fieldLabel;

    /**
     * @var mixed|string
     */
    private $extensions;

    /**
     * @var mixed|string
     */
    private $classAttribute;

    /**
     * @var mixed|string
     */
    private $description;

    /**
     * @var bool|mixed|void
     */
    private $optionValue;

    /**
     * Image constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->pageName     = $args['page_name'];
        $this->settingsName = $args['field_name'];
        $this->sectionName  = $this->settingsName . '_section';

        $this->fieldLabel     = isset($args['field_label']) ? stripslashes($args['field_label']) : '';
        $this->extensions     = $args['extensions'];
        $this->classAttribute = isset($args['class_attribute']) ? $args['class_attribute'] : '';
        $this->description    = isset($args['description']) ? $args['description'] : '';

        $this->optionValue = get_option($this->settingsName);

        add_action('admin_enqueue_scripts', function () {
            return $this->enqueueScriptsAndStyles();
        });

        add_action('admin_init', function () {
            $this->optionsSettingsInit();
        });
    }

    private function optionsSettingsInit()
    {
        register_setting(
            $this->pageName,
            $this->settingsName,
            [
                'type' => 'string',
                'group' => $this->pageName,
                'sanitize_callback' => function ($value) {
                    return $this->optionCallback($value);
                }
            ]
        );

        add_settings_section(
            $this->sectionName,
            '',
            '',
            $this->pageName
        );

        add_settings_field(
            $this->settingsName,
            $this->fieldLabel,
            function () {
                $this->displayCallback();
            },
            $this->pageName,
            $this->sectionName,
            ['class' => $this->classAttribute]
        );
    }

    /**
     * @param $value
     *
     * @return mixed|void
     */
    private function optionCallback($value)
    {
        if (has_filter(Plugin::PREFIX_ . 'sanitize_option_' . $this->settingsName)) {
            return apply_filters(Plugin::PREFIX_ . 'sanitize_option_' . $this->settingsName, $value);
        }

        if (get_post_meta($value)['_wp_attachment_metadata'] || get_post_meta($value)['_wp_attached_file']) {
            return $value;
        }
    }

    /**
     * @return string
     */
    private function mediaTypes()
    {
        $selectedExtensions = Collection::make(Collection::make($this->extensions)->get('types'))
            ->keys()
            ->all();

        $list = Collection::make([
            'ico' => 'image/x-icon',
            'gif' => 'image/gif',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'svg' => 'image/svg+xml'
        ]);

        $mediaTypesArray = $list->only($selectedExtensions)
            ->flip()
            ->keys()
            ->all();

        return implode(', ', $mediaTypesArray);
    }

    private function enqueueScriptsAndStyles()
    {
        wp_enqueue_media();

        wp_enqueue_script(
            Plugin::PREFIX . 'media-js',
            Plugin::assetsUrl() . 'js/' . Plugin::PREFIX . 'media.js',
            [],
            Plugin::VERSION,
            'all'
        );

        wp_enqueue_style(
            Plugin::PREFIX . 'media-css',
            Plugin::assetsUrl() . 'css/' . Plugin::PREFIX . 'media.css',
            true,
            Plugin::VERSION,
            'all'
        );
    }

    private function displayCallback()
    {
        printf('<div class="%s">', Plugin::PREFIX . 'media-wrapper');

        printf(
            '<div class="%s"><div class="%s" style="background-image:url(%s);"></div></div>',
            Plugin::PREFIX . 'image-preview-wrapper hidden',
            Plugin::PREFIX . 'image-preview-box',
            wp_get_attachment_thumb_url($this->optionValue)
        );

        printf(
            '<div class="%s"><input type="button" class="%s" value="%s" extention="%s"/></div>',
            Plugin::PREFIX . 'upload-image',
            'button ' . Plugin::PREFIX . 'upload-image__button',
            __('Upload'),
            $this->mediaTypes()
        );

        printf(
            '<input type="hidden" name="%s" value="%d" class="%s"/>',
            $this->settingsName,
            $this->optionValue,
            Plugin::PREFIX . 'media-attachment'
        );

        printf(
            '<div class="%s"><a href="%s" class="%s"/>%s</a></div>',
            Plugin::PREFIX . 'delete-image hidden',
            'javascript:;',
            Plugin::PREFIX . 'delete-image__link',
            __('Delete')
        );

        print('</div>');

        print($this->description($this->description));
    }
}
