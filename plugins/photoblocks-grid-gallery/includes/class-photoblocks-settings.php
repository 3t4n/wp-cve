<?php

/**
 * PhotoBlocks settings class
 *
 * @link       https://machothemes.com
 * @since      1.0.0
 *
 * @package    Photoblocks
 * @subpackage Photoblocks/includes
 */
class Photoblocks_Settings
{
    public  $fields ;
    public function __construct()
    {
        $this->setup_fields();
    }
    
    /**
     * List post types
     *
     * @since    1.0.14
     */
    public function get_post_types()
    {
        $types = array();
        foreach ( get_post_types( array(
            'publicly_queryable' => true,
        ), 'objects' ) as $t ) {
            $types[$t->label] = $t->name;
        }
        return $types;
    }
    
    /**
     * List taxonomies
     *
     * @since    1.0.14
     */
    public function get_taxonomies()
    {
        $types = array();
        if ( is_admin() ) {
            foreach ( get_taxonomies( array(
                'publicly_queryable' => true,
            ), 'objects' ) as $taxonomy => $t ) {
                foreach ( get_terms( $taxonomy, array(
                    'hide_empty' => false,
                ) ) as $c ) {
                    $types[$c->term_id] = $c->name;
                }
            }
        }
        return $types;
    }
    
    /**
     * List default values
     *
     * @since    1.0.0
     */
    public function default_values()
    {
        $values = array();
        foreach ( $this->fields as $section ) {
            foreach ( $section as $group ) {
                foreach ( $group['fields'] as $code => $data ) {
                    if ( array_key_exists( 'default', $data ) ) {
                        $values[$code] = $data['default'];
                    }
                }
            }
        }
        return $values;
    }
    
    /**
     * Add field
     *
     * @since    1.0.0
     */
    private function add_field(
        $section,
        $group,
        $name,
        $code,
        $type,
        $extra
    )
    {
        if ( !isset( $this->fields[$section] ) ) {
            $this->fields[$section] = array();
        }
        if ( !isset( $this->fields[$section][$group] ) ) {
            $this->fields[$section][$group] = array(
                'name' => $group,
            );
        }
        if ( !isset( $this->fields[$section][$group]['fields'] ) ) {
            $this->fields[$section][$group]['fields'] = array();
        }
        $this->fields[$section][$group]['fields'][$code] = array(
            'name'         => $name,
            'code'         => $code,
            'type'         => $type,
            'description'  => '',
            'css_classes'  => '',
            'onchange'     => '',
            'show_if'      => '',
            'render'       => true,
            'premium'      => array(),
            'premium_only' => false,
            'min_plan'     => 'free',
        );
        foreach ( $extra as $key => $value ) {
            $this->fields[$section][$group]['fields'][$code][$key] = $value;
        }
        //if (photob_fs()->is_not_paying())
        foreach ( $this->fields[$section][$group]['fields'][$code]['premium'] as $v => $min_plan ) {
            // $v -> fancybox
            // $plans -> array('ultimate')
            $show_premium = true;
            
            if ( photob_fs()->is_plan_or_trial( $min_plan ) ) {
                $show_premium = false;
                break;
            }
            
            
            if ( $show_premium ) {
                if ( $type == 'select' ) {
                    $this->fields[$section][$group]['fields'][$code]['values'][$v] .= '  » premium';
                }
                if ( $type == 'hover_effect' ) {
                    $this->fields[$section][$group]['fields'][$code]['values'][$v]['name'] .= '  » premium';
                }
            }
        
        }
        return $this->fields[$section][$group]['fields'][$code];
    }
    
    /**
     * Get a setting value from a gallery and check if the
     * gallery value is valid based on the current user plan
     *
     * @since    1.0.0
     */
    public function get( $gallery, $field )
    {
        $saved_value = null;
        if ( isset( $gallery[$field] ) ) {
            $saved_value = $gallery[$field];
        }
        //print "\n\nsaved $field $saved_value\n";
        foreach ( $this->fields as $section ) {
            foreach ( $section as $group ) {
                foreach ( $group['fields'] as $code => $data ) {
                    
                    if ( $code == $field ) {
                        // are there premium values for this field?
                        //print "check $code ($saved_value)\n";
                        if ( !array_key_exists( 'premium', $data ) ) {
                            return $saved_value;
                        }
                        // is this a free feature?
                        if ( !$data['premium_only'] ) {
                            //print "return premium_only\n";
                            return $saved_value;
                        }
                        
                        if ( $data['premium_only'] ) {
                            $min_plan = $data['min_plan'];
                            //print "min plan is $min_plan\n";
                            
                            if ( photob_fs()->is_plan_or_trial( $min_plan ) ) {
                                //print "plan it's ok $saved_value\n";
                                return $saved_value;
                            } else {
                                //print "returning default value\n";
                                return ( isset( $data['default'] ) ? $data['default'] : '' );
                            }
                        
                        }
                        
                        // is the saved value a premium value?
                        
                        if ( array_key_exists( $saved_value, $data['premium'] ) ) {
                            //print "there's a premium value\n";
                            $min_plan = $data['premium'][$saved_value];
                            //print "min plan is $min_plan\n";
                            // is the current plan enough to use the saved value?
                            
                            if ( photob_fs()->is_plan_or_trial( $min_plan ) ) {
                                //print "value $saved_value can be used\n";
                                return $saved_value;
                            } else {
                                //print "value $saved_value can't be used\n";
                                return $data['default'];
                            }
                        
                        } else {
                            return $saved_value;
                        }
                        
                        break;
                    }
                
                }
            }
        }
        return $saved_value;
    }
    
    /**
     * Setup fields for options and features
     *
     * @since    1.0.0
     */
    private function setup_fields()
    {
        $this->fields = array();
        $this->fields['gallery'] = array();
        $this->fields['lightbox'] = array();
        $this->fields['captions'] = array();
        $this->fields['filters'] = array();
        $this->fields['customisations'] = array();
        /*
         * Gallery / Advanced
         */
        $this->add_field(
            'gallery',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Gallery name', 'photoblocks' ),
            'name',
            'text',
            array(
            'description' => esc_html__( "Friendly name for this gallery, it won't be shown on your site", 'photoblocks' ),
            'default'     => '',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Gallery width', 'photoblocks' ),
            'width',
            'text',
            array(
            'description' => esc_html__( 'Enter a value in px or %', 'photoblocks' ),
            'default'     => '100%',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Loading effect', 'photoblocks' ),
            'loading_effect',
            'select',
            array(
            'description'     => esc_html__( 'Effect of the blocks for when they become visible', 'photoblocks' ),
            'values'          => array(
            'fade'            => esc_html__( 'Fade in', 'photoblocks' ),
            'pop'             => esc_html__( 'Pop', 'photoblocks' ),
            'slideFromTop'    => esc_html__( 'Slide from top', 'photoblocks' ),
            'slideFromBottom' => esc_html__( 'Slide from bottom', 'photoblocks' ),
            'slideFromLeft'   => esc_html__( 'Slide from left', 'photoblocks' ),
            'elastic'         => esc_html__( 'Elastic', 'photoblocks' ),
            'elastic2'        => esc_html__( 'Elastic 2', 'photoblocks' ),
            'wobble'          => esc_html__( 'Wobble', 'photoblocks' ),
            'deal'            => esc_html__( 'Deal', 'photoblocks' ),
            'stretch'         => esc_html__( 'Stretch', 'photoblocks' ),
        ),
            'premium'         => array(
            'pop'             => 'ultimate',
            'slideFromTop'    => 'ultimate',
            'slideFromBottom' => 'ultimate',
            'slideFromLeft'   => 'ultimate',
            'elastic'         => 'ultimate',
            'elastic2'        => 'ultimate',
            'wobble'          => 'ultimate',
            'deal'            => 'ultimate',
            'stretch'         => 'ultimate',
        ),
            'default'         => 'fade',
            'default_premium' => 'pop',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Shuffle', 'photoblocks' ),
            'shuffle',
            'toggle',
            array(
            'description' => esc_html__( 'Shuffle images at every page reload', 'photoblocks' ),
            'default'     => '0',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Padding', 'photoblocks' ),
            'padding',
            'number',
            array(
            'description' => '',
            'default'     => '10',
            'render'      => false,
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Columns', 'photoblocks' ),
            'columns',
            'number',
            array(
            'description' => '',
            'default'     => '4',
            'render'      => false,
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Mobile layout', 'photoblocks' ),
            'mobile_layout',
            'mobile_layout',
            array(
            'description'  => '',
            'premium_only' => true,
            'min_plan'     => 'ultimate',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Disable under browser width', 'photoblocks' ),
            'disable_below',
            'number',
            array(
            'mu'      => 'px',
            'default' => 320,
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Blocks', 'photoblocks' ),
            esc_html__( 'Background color', 'photoblocks' ),
            'block_background_color',
            'color',
            array(
            'description' => '',
            'default'     => 'transparent',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Blocks', 'photoblocks' ),
            esc_html__( 'Blur', 'photoblocks' ),
            'hover_blur',
            'toggle',
            array(
            'description' => esc_html__( 'Blur images on mouse hover', 'photoblocks' ),
            'default'     => '1',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Blocks', 'photoblocks' ),
            esc_html__( 'Lift effect', 'photoblocks' ),
            'hover_lift',
            'toggle',
            array(
            'description' => esc_html__( 'Lift up images on mouse hover', 'photoblocks' ),
            'default'     => '1',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Blocks', 'photoblocks' ),
            esc_html__( 'Border size', 'photoblocks' ),
            'border_size',
            'number',
            array(
            'description' => '',
            'default'     => '0',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Blocks', 'photoblocks' ),
            esc_html__( 'Border color', 'photoblocks' ),
            'border_color',
            'color',
            array(
            'description' => '',
            'default'     => '#333333',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Images', 'photoblocks' ),
            esc_html__( 'Vertical alignment', 'photoblocks' ),
            'image_alignment_v',
            'select',
            array(
            'values'  => array(
            'top'    => esc_html__( 'Top', 'photoblocks' ),
            'center' => esc_html__( 'Middle', 'photoblocks' ),
            'bottom' => esc_html__( 'Bottom', 'photoblocks' ),
        ),
            'default' => 'center',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Images', 'photoblocks' ),
            esc_html__( 'Horizontal alignment', 'photoblocks' ),
            'image_alignment_h',
            'select',
            array(
            'values'  => array(
            'left'   => esc_html__( 'Left', 'photoblocks' ),
            'center' => esc_html__( 'Center', 'photoblocks' ),
            'right'  => esc_html__( 'Right', 'photoblocks' ),
        ),
            'default' => 'center',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Images', 'photoblocks' ),
            esc_html__( 'Round corners', 'photoblocks' ),
            'border_radius',
            'number',
            array(
            'description' => esc_html__( 'Radius of the corners in pixels', 'photoblocks' ),
            'default'     => 0,
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Advanced', 'photoblocks' ),
            esc_html__( 'Image size', 'photoblocks' ),
            'image_size',
            'select',
            array(
            'description' => wp_kses_post( __( 'Image size. <strong>Remember to regenerate thumbnails if needed.</strong>', 'photoblocks' ) ),
            'values'      => array(),
            'default'     => 'large',
        )
        );
        foreach ( PhotoBlocks_Utils::list_thumbnail_sizes() as $s => $item ) {
            $this->fields['gallery']['Advanced']['fields']['image_size']['values'][$s] = $s . ' (' . $item[0] . 'x' . $item[1] . ')';
        }
        $this->add_field(
            'gallery',
            esc_html__( 'Advanced', 'photoblocks' ),
            esc_html__( 'Lazy loading', 'photoblocks' ),
            'lazy',
            'toggle',
            array(
            'default'      => '0',
            'premium_only' => true,
            'min_plan'     => 'ultimate',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Advanced', 'photoblocks' ),
            esc_html__( 'Compress HTML', 'photoblocks' ),
            'compress_html',
            'toggle',
            array(
            'default' => '1',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Advanced', 'photoblocks' ),
            esc_html__( 'Additional image CSS class', 'photoblocks' ),
            'image_class',
            'text',
            array(
            'default' => '',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Advanced', 'photoblocks' ),
            esc_html__( 'Additional link CSS class', 'photoblocks' ),
            'link_class',
            'text',
            array(
            'default' => '',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Advanced', 'photoblocks' ),
            esc_html__( 'Use image relative paths', 'photoblocks' ),
            'relative_path',
            'toggle',
            array(
            'default'     => '',
            'description' => esc_html__( 'If images are not being loaded, try activating this setting', 'photoblocks' ),
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Advanced', 'photoblocks' ),
            esc_html__( 'Replace host name', 'photoblocks' ),
            'host_name',
            'text',
            array(
            'default'      => '',
            'description'  => esc_html__( 'Enter the url of your CDN (e.g. cdn.yourdomain.com)', 'photoblocks' ),
            'premium_only' => true,
            'min_plan'     => 'ultimate',
        )
        );
        /*
         * Gallery / Social
         */
        $this->add_field(
            'gallery',
            'Social',
            '',
            'social_help',
            'help_social',
            array()
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Social', 'photoblocks' ),
            esc_html__( 'Facebook sharing', 'photoblocks' ),
            'sharing_facebook',
            'toggle',
            array(
            'default' => '0',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Social', 'photoblocks' ),
            esc_html__( 'Pinterest sharing', 'photoblocks' ),
            'sharing_pinterest',
            'toggle',
            array(
            'default' => '0',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Social', 'photoblocks' ),
            esc_html__( 'Twitter sharing', 'photoblocks' ),
            'sharing_twitter',
            'toggle',
            array(
            'default' => '0',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Social', 'photoblocks' ),
            esc_html__( 'Houzz sharing', 'photoblocks' ),
            'sharing_houzz',
            'toggle',
            array(
            'default' => '0',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Social', 'photoblocks' ),
            esc_html__( 'Google+ sharing', 'photoblocks' ),
            'sharing_google',
            'toggle',
            array(
            'default' => '0',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Social', 'photoblocks' ),
            esc_html__( 'Vertical alignment', 'photoblocks' ),
            'social_position_v',
            'select',
            array(
            'values'  => array(
            'top'    => esc_html__( 'Top', 'photoblocks' ),
            'middle' => esc_html__( 'Middle', 'photoblocks' ),
            'bottom' => esc_html__( 'Bottom', 'photoblocks' ),
        ),
            'default' => 'bottom',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Social', 'photoblocks' ),
            esc_html__( 'Horizontal alignment', 'photoblocks' ),
            'social_position_h',
            'select',
            array(
            'values'  => array(
            'left'   => esc_html__( 'Left', 'photoblocks' ),
            'center' => esc_html__( 'Center', 'photoblocks' ),
            'right'  => esc_html__( 'Right', 'photoblocks' ),
        ),
            'default' => 'center',
        )
        );
        $this->add_field(
            'gallery',
            esc_html__( 'Social', 'photoblocks' ),
            esc_html__( 'Icon size', 'photoblocks' ),
            'social_icon_size',
            'number',
            array(
            'default' => '14',
        )
        );
        /*
         * Lightbox / Aspect
         */
        $lightbox = $this->add_field(
            'lightbox',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Lightbox', 'photoblocks' ),
            'lightbox',
            'select',
            array(
            'values'          => array(
            'magnific'      => esc_html__( 'Enable Magnific Popup lightbox', 'photoblocks' ),
            'fancybox'      => esc_html__( 'Enable FancyBox lightbox', 'photoblocks' ),
            'link_to_image' => esc_html__( 'Link images (use this for external lightboxes)', 'photoblocks' ),
            'none'          => esc_html__( "Don't use a lightbox and don't link the images", 'photoblocks' ),
        ),
            'premium'         => array(
            'fancybox' => 'basic',
        ),
            'default'         => 'magnific',
            'default_premium' => 'fancybox',
            'description'     => esc_html__( 'Choose whether to show a larger version of the images when users click on them', 'photoblocks' ),
        )
        );
        $lightbox = $this->add_field(
            'lightbox',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Lightbox caption field', 'photoblocks' ),
            'lightbox_caption_field',
            'select',
            array(
            'values'      => array(
            'none'              => esc_html__( 'None', 'photoblocks' ),
            'title'             => esc_html__( 'Title', 'photoblocks' ),
            'description'       => esc_html__( 'Description', 'photoblocks' ),
            'title_description' => esc_html__( 'Title + Description', 'photoblocks' ),
        ),
            'default'     => 'description',
            'description' => esc_html__( 'Text field to use for the lightbox caption', 'photoblocks' ),
        )
        );
        $this->add_field(
            'lightbox',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Mobile Lightbox', 'photoblocks' ),
            'lightbox_mobile',
            'select',
            array(
            'values'          => array(
            'magnific'      => esc_html__( 'Enable Magnific Popup lightbox', 'photoblocks' ),
            'fancybox'      => esc_html__( 'Enable FancyBox lightbox', 'photoblocks' ),
            'link_to_image' => esc_html__( 'Link images (use this for external lightboxes)', 'photoblocks' ),
            'none'          => esc_html__( "Don't use a lightbox and don't link the images", 'photoblocks' ),
        ),
            'premium'         => array(
            'fancybox' => 'basic',
        ),
            'default'         => 'magnific',
            'default_premium' => 'fancybox',
            'description'     => esc_html__( 'Same as above but for mobile devices', 'photoblocks' ),
        )
        );
        $this->add_field(
            'lightbox',
            esc_html__( 'Aspect', 'photoblocks' ),
            esc_html__( 'Background color', 'photoblocks' ),
            'lightbox_bg_color',
            'color',
            array(
            'default' => 'rgba(0, 0, 0, 0.75)',
        )
        );
        $this->add_field(
            'lightbox',
            esc_html__( 'Aspect', 'photoblocks' ),
            esc_html__( 'Animation effect', 'photoblocks' ),
            'fancybox_animation',
            'select',
            array(
            'values'  => array(
            'false'       => esc_html__( 'None', 'photoblocks' ),
            'zoom'        => esc_html__( 'Zoom', 'photoblocks' ),
            'fade'        => esc_html__( 'Fade', 'photoblocks' ),
            'zoom-in-out' => esc_html__( 'Zoom in and out', 'photoblocks' ),
        ),
            'default' => 'zoom',
            'show_if' => 'lightbox || lightbox_mobile == fancybox',
        )
        );
        $this->add_field(
            'lightbox',
            esc_html__( 'Aspect', 'photoblocks' ),
            esc_html__( 'Transition effect', 'photoblocks' ),
            'fancybox_transition',
            'select',
            array(
            'values'  => array(
            'false'       => esc_html__( 'None', 'photoblocks' ),
            'fade'        => esc_html__( 'Fade', 'photoblocks' ),
            'slide'       => esc_html__( 'Slide', 'photoblocks' ),
            'circular'    => esc_html__( 'Circular', 'photoblocks' ),
            'tube'        => esc_html__( 'Tube', 'photoblocks' ),
            'zoom-in-out' => esc_html__( 'Zoom in and out', 'photoblocks' ),
            'rotate'      => esc_html__( 'Rotate', 'photoblocks' ),
        ),
            'default' => 'slide',
            'show_if' => 'lightbox || lightbox_mobile == fancybox',
        )
        );
        $this->add_field(
            'lightbox',
            esc_html__( 'Aspect', 'photoblocks' ),
            esc_html__( 'Image size', 'photoblocks' ),
            'lightbox_image_size',
            'select',
            array(
            'values'  => array(),
            'default' => '',
        )
        );
        foreach ( PhotoBlocks_Utils::list_thumbnail_sizes() as $s => $item ) {
            $this->fields['lightbox']['Aspect']['fields']['lightbox_image_size']['values'][$s] = $s . ' (' . $item[0] . 'x' . $item[1] . ')';
        }
        $this->fields['lightbox']['Aspect']['fields']['lightbox_image_size']['values']['full'] = 'Full (original)';
        /**
         * Captions
         */
        $this->add_field(
            'captions',
            esc_html__( 'Hover', 'photoblocks' ),
            esc_html__( 'Hover effect', 'photoblocks' ),
            'caption_effect',
            'hover_effect',
            array(
            'values'          => array(
            'hidden'   => array(
            'name' => 'Hidden',
        ),
            'fade'     => array(
            'name' => 'Fade',
        ),
            'sticky'   => array(
            'name'        => 'Sticky',
            'title'       => 'left|bottom',
            'description' => 'left|bottom',
            'social'      => 'center|middle',
        ),
            'label'    => array(
            'name'        => 'Label',
            'title'       => 'left|bottom',
            'description' => 'center|middle',
            'social'      => 'center|middle',
        ),
            'moresco'  => array(
            'name'        => 'Moresco',
            'title'       => 'center|middle',
            'description' => 'center|bottom',
            'social'      => 'center|bottom',
        ),
            'quadro'   => array(
            'name'        => 'Quadro',
            'title'       => 'center|middle',
            'description' => 'center|middle',
            'social'      => 'center|bottom',
        ),
            'focus'    => array(
            'name'        => 'Focus',
            'title'       => 'center|middle',
            'description' => 'center|middle',
            'social'      => 'center|bottom',
        ),
            'liney'    => array(
            'name'        => 'Liney',
            'title'       => 'right|top',
            'description' => 'right|bottom',
            'social'      => 'center|bottom',
        ),
            'dream'    => array(
            'name'        => 'Dream',
            'title'       => 'center|middle',
            'description' => 'center|middle',
            'social'      => 'center|bottom',
        ),
            'cinema'   => array(
            'name'        => 'Cinema',
            'title'       => 'center|middle',
            'description' => 'center|middle',
            'social'      => 'center|bottom',
        ),
            'stanley'  => array(
            'name'        => 'Stanley',
            'title'       => 'center|middle',
            'description' => 'center|middle',
            'social'      => 'center|bottom',
        ),
            'frack'    => array(
            'name'        => 'Frack',
            'title'       => 'center|middle',
            'description' => 'center|middle',
            'social'      => 'center|bottom',
        ),
            'break'    => array(
            'name'        => 'Break',
            'title'       => 'left|top',
            'description' => 'left|top',
            'social'      => 'center|bottom',
        ),
            'space'    => array(
            'name'        => 'Space',
            'title'       => 'center|middle',
            'description' => 'center|bottom',
            'social'      => 'center|bottom',
        ),
            'new-york' => array(
            'name'        => 'New York',
            'title'       => 'center|top',
            'description' => 'center|top',
            'social'      => 'center|bottom',
        ),
            'africa'   => array(
            'name'        => 'Africa',
            'title'       => 'center|top',
            'description' => 'center|top',
            'social'      => 'center|bottom',
        ),
            'window'   => array(
            'name'        => 'Window',
            'title'       => 'center|middle',
            'description' => 'center|middle',
            'social'      => 'center|bottom',
        ),
            'mirto'    => array(
            'name'        => 'Mirto',
            'title'       => 'center|top',
            'description' => 'center|bottom',
            'social'      => 'center|bottom',
        ),
        ),
            'premium'         => array(
            'moresco'  => 'ultimate',
            'quadro'   => 'ultimate',
            'label'    => 'ultimate',
            'focus'    => 'ultimate',
            'liney'    => 'ultimate',
            'dream'    => 'ultimate',
            'cinema'   => 'ultimate',
            'stanley'  => 'ultimate',
            'frack'    => 'ultimate',
            'break'    => 'ultimate',
            'space'    => 'ultimate',
            'new-york' => 'ultimate',
            'africa'   => 'ultimate',
            'window'   => 'ultimate',
            'mirto'    => 'ultimate',
        ),
            'default'         => 'fade',
            'default_premium' => 'moresco',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Google Fonts API key', 'photoblocks' ),
            'google_font_key',
            'text',
            array(
            'default'      => '',
            'description'  => wp_kses_post( __( "If you want to use Google Fonts enter you API key here. If you don't have one, create it <a href='https://support.google.com/cloud/answer/6158862' target='_blank'>here</a><br>After you paste the API key, save the gallery and reload the page to let changes take effect.", 'photoblocks' ) ),
            'premium_only' => true,
            'min_plan'     => 'ultimate',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Title', 'photoblocks' ),
            esc_html__( 'Vertical alignment', 'photoblocks' ),
            'caption_title_position_v',
            'select',
            array(
            'values'  => array(
            'top'    => esc_html__( 'Top', 'photoblocks' ),
            'middle' => esc_html__( 'Middle', 'photoblocks' ),
            'bottom' => esc_html__( 'Bottom', 'photoblocks' ),
        ),
            'default' => 'middle',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Title', 'photoblocks' ),
            esc_html__( 'Horizontal alignment', 'photoblocks' ),
            'caption_title_position_h',
            'select',
            array(
            'values'  => array(
            'left'   => esc_html__( 'Left', 'photoblocks' ),
            'center' => esc_html__( 'Center', 'photoblocks' ),
            'right'  => esc_html__( 'Right', 'photoblocks' ),
        ),
            'default' => 'center',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Title', 'photoblocks' ),
            esc_html__( 'Color', 'photoblocks' ),
            'caption_title_color',
            'color',
            array(
            'default' => '#fff',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Title', 'photoblocks' ),
            esc_html__( 'Size', 'photoblocks' ),
            'caption_title_size',
            'number',
            array(
            'default' => 20,
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Title', 'photoblocks' ),
            esc_html__( 'Font', 'photoblocks' ),
            'caption_title_font',
            'select',
            array(
            'values'       => array(
            '' => 'Theme default',
        ),
            'description'  => esc_html__( 'Insert your Google Fonts API key to enable Google Fonts', 'photoblocks' ),
            'css_classes'  => 'js-load-fonts js-chosen',
            'default'      => '',
            'premium_only' => true,
            'min_plan'     => 'ultimate',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Description', 'photoblocks' ),
            esc_html__( 'Vertical alignment', 'photoblocks' ),
            'caption_description_position_v',
            'select',
            array(
            'values'  => array(
            'top'    => esc_html__( 'Top', 'photoblocks' ),
            'middle' => esc_html__( 'Middle', 'photoblocks' ),
            'bottom' => esc_html__( 'Bottom', 'photoblocks' ),
        ),
            'default' => 'middle',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Description', 'photoblocks' ),
            esc_html__( 'Horizontal alignment', 'photoblocks' ),
            'caption_description_position_h',
            'select',
            array(
            'values'  => array(
            'left'   => esc_html__( 'Left', 'photoblocks' ),
            'center' => esc_html__( 'Center', 'photoblocks' ),
            'right'  => esc_html__( 'Right', 'photoblocks' ),
        ),
            'default' => 'center',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Description', 'photoblocks' ),
            esc_html__( 'Color', 'photoblocks' ),
            'caption_description_color',
            'color',
            array(
            'default' => 'rgba(255, 255, 255, .7)',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Description', 'photoblocks' ),
            esc_html__( 'Size', 'photoblocks' ),
            'caption_description_size',
            'number',
            array(
            'default' => 14,
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Description', 'photoblocks' ),
            esc_html__( 'Font', 'photoblocks' ),
            'caption_description_font',
            'select',
            array(
            'values'       => array(
            '' => 'Theme default',
        ),
            'description'  => esc_html__( 'Insert your Google Fonts API key to enable Google Fonts', 'photoblocks' ),
            'css_classes'  => 'js-load-fonts',
            'default'      => '',
            'premium_only' => true,
            'min_plan'     => 'ultimate',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Background', 'photoblocks' ),
            esc_html__( 'Color', 'photoblocks' ),
            'caption_background_color',
            'color',
            array(
            'default' => 'rgba(0, 0, 0, .75)',
        )
        );
        $this->add_field(
            'captions',
            esc_html__( 'Background', 'photoblocks' ),
            esc_html__( 'Always show overlay', 'photoblocks' ),
            'caption_background_show_empty',
            'toggle',
            array(
            'description' => esc_html__( 'Show overlays even without captions', 'photoblocks' ),
            'default'     => '0',
        )
        );
        $this->add_field(
            'customisations',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Custom CSS', 'photoblocks' ),
            'custom_css',
            'textarea',
            array(
            'description' => wp_kses_post( __( 'Write here your the custom CSS for this gallery. Write CSS code without <style></style> tags.', 'photoblocks' ) ),
            'default'     => '',
        )
        );
        $this->add_field(
            'customisations',
            esc_html__( 'General', 'photoblocks' ),
            '',
            'custom_css_help',
            'help_custom_css',
            array()
        );
        $this->add_field(
            'customisations',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Event: before gallery', 'photoblocks' ),
            'custom_event_before',
            'textarea',
            array(
            'description' => esc_html__( 'JavaScript code to run before the plugin starts building the gallery. Write CSS code without <script></script> tags.', 'photoblocks' ),
            'default'     => '',
        )
        );
        $this->add_field(
            'customisations',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Event: refreshed gallery', 'photoblocks' ),
            'custom_event_refresh',
            'textarea',
            array(
            'description' => wp_kses_post( __( 'JavaScript code to run after the plugin refreshed the gallery. Write CSS code without <script></script> tags.', 'photoblocks' ) ),
            'default'     => '',
        )
        );
        $this->add_field(
            'customisations',
            esc_html__( 'General', 'photoblocks' ),
            esc_html__( 'Event: after gallery', 'photoblocks' ),
            'custom_event_after',
            'textarea',
            array(
            'description' => wp_kses_post( __( 'JavaScript code to run after the plugin complete building the gallery. Write CSS code without <script></script> tags.', 'photoblocks' ) ),
            'default'     => '',
        )
        );
        /*$this->add_field("customisations", "General", "Filter for blocks", "custom_blocks_filter", "textarea", array(
        			"description" => "",
        			"default" => "",
        			"premium" => array()
        		));
        		$this->add_field("customisations", "General", "Filter for settings", "custom_blocks_filter", "textarea", array(
        			"description" => "",
        			"default" => "",
        			"premium" => array()
        		));*/
        $show_filters_claim = false;
        $show_filters_claim = true;
        if ( $show_filters_claim ) {
            $this->add_field(
                'filters',
                'Filters',
                '',
                '',
                'filters_claim',
                array()
            );
        }
    }

}