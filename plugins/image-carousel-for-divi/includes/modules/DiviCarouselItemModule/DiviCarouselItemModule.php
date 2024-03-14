<?php
namespace WPT_Divi_Carousel_Images_Modules\DiviCarouselItemModule;

use ET_Builder_Module;
use ET_Builder_Element;

/**
 * Full width divi module.
 */
class DiviCarouselItemModule extends ET_Builder_Module
{
    public $child_title_fallback_var = 'alt';

    public $child_title_var = 'admin_label';

    public $name = 'Image Carousel Item';

    public $slug = 'et_pb_wptools_carousel_image_item';

    public $type = 'child';

    public $vb_support = 'on';

    protected $container;

    protected $module_credits = [
        'module_uri' => 'https://wptools.app',
        'author'     => 'WP Tools',
        'author_uri' => 'https://wptools.app',
    ];

    /**
     * Constructor
     */
    public function __construct($container)
    {
        $this->container = $container;
        parent::__construct();
    }

    /**
     * Advanced fields.
     */
    public function get_advanced_fields_config()
    {
        return [
            'border'                => false,
            'borders'               => false,
            'text'                  => false,
            'box_shadow'            => false,
            'filters'               => false,
            'animation'             => false,
            'text_shadow'           => false,
            'max_width'             => false,
            'margin_padding'        => false,
            'custom_margin_padding' => false,
            'background'            => false,
            'fonts'                 => false,
            'link_options'          => false,
        ];
    }

    /**
     * fields
     */
    public function get_fields()
    {
        $fields = [];

        $fields['src'] = [
            'label'              => esc_html__('Image', 'et_builder'),
            'type'               => 'upload',
            'option_category'    => 'basic_option',
            'upload_button_text' => esc_attr__('Upload an image', 'et_builder'),
            'choose_text'        => esc_attr__('Choose an Image', 'et_builder'),
            'update_text'        => esc_attr__('Set As Image', 'et_builder'),
            'hide_metadata'      => true,
            'description'        => esc_html__('Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder'),
            'toggle_slug'        => 'main_content',
            'dynamic_content'    => 'image',
        ];

        $fields['url'] = [
            'label'       => esc_html__('Image URL', 'et_builder'),
            'type'        => 'text',
            'description' => esc_html__('Set the URL of the image if you want the carousel item to be clickable.', 'et_builder'),
            'toggle_slug' => 'main_content',
            'default'     => '',
        ];

        $fields['url_open'] = [
            'label'       => esc_html__('Open URL In?', 'et_builder'),
            'type'        => 'select',
            'options'     => [
                '_blank' => 'New Window',
                '_same'  => 'Same Window',
            ],
            'toggle_slug' => 'main_content',
            'description' => esc_html__('Open the image URL in either new window or the same window.', 'et_builder'),
            'default'     => '_blank',
        ];

        $fields['alt'] = [
            'label'           => esc_html__('Image Alternative Text', 'et_builder'),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => esc_html__('This defines the HTML ALT text. A short description of your image can be placed here. Leave blank to get alt text from database', 'et_builder'),
            'toggle_slug'     => 'main_content',
        ];

        $fields['admin_label'] = [
            'label'       => __('Admin Label', 'et_builder'),
            'type'        => 'text',
            'toggle_slug' => 'main_content',
            'description' => 'This will change the label of the module in the builder for easy identification.',
            'default'     => 'Image Carousel Item',
        ];

        return $fields;
    }

    /**
     * modals toggles
     */
    public function get_settings_modal_toggles()
    {
        return [
            'general' => [
                'toggles' => [
                    'main_content' => esc_html__('Image', 'et_builder'),
                ],
            ],
        ];
    }

    /**
     * init
     */
    public function init()
    {
    }

    /**
     * Renderer
     */
    public function render(
        $unprocessed_props,
        $content = null,
        $render_slug
    ) {
        $module_classes = $this->module_classname($render_slug);
        $module_class   = trim(ET_Builder_Element::add_module_order_class('', $render_slug));

        $defaults = [
            'src'      => '',
            'alt'      => '',
            'url'      => '',
            'url_open' => '_blank',
        ];

        $props = wp_parse_args($unprocessed_props, $defaults);

        if (!$props['src']) {
            return '';
        }

        $props['url'] = trim($props['url']);

        if ($props['url_open'] == '_same') {
            $props['url_open'] = '';
        }

        $main_selector = 'section.' . $module_class;

        ob_start();
        require $this->container['dir'] . '/resources/views/wptools-divi-carousel-image-item.php';
        return ob_get_clean();
    }

    protected function _render_module_wrapper(
        $output = '',
        $render_slug = ''
    ) {
        return $output;
    }
}
