<?php

// Include site URL hash in HTML settings to update when site URL changes
$sitehash = base64_encode( admin_url() );

$admin_menu = array(
    'title' => 'Easy Image Collage ' . __('Settings', 'easy-image-collage'),
    'logo'  => EasyImageCollage::get()->coreUrl . '/img/logo.png',
    'menus' => array(
//=-=-=-=-=-=-= DEFAULT STYLE =-=-=-=-=-=-=
        array(
            'title' => __('Default Style', 'easy-image-collage'),
            'name' => 'default_style',
            'icon' => 'font-awesome:fa-picture-o',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Display', 'easy-image-collage'),
                    'name' => 'default_style_display',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'default_style_display',
                            'label' => __('Display Method', 'easy-image-collage'),
                            'items' => array(
                                array(
                                    'value' => 'image',
                                    'label' => __( 'Actual Images', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'background',
                                    'label' => __( 'Background Images (Legacy Mode)', 'easy-image-collage' ),
                                ),
                            ),
                            'default' => array(
                                'image',
                            ),
                            'validation' => 'required',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Defaults', 'easy-image-collage'),
                    'name' => 'default_style_defaults',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'default_style_grid_align',
                            'label' => __('Grid Alignment', 'easy-image-collage'),
                            'items' => array(
                                array(
                                    'value' => 'left',
                                    'label' => __( 'Align', 'easy-image-collage' ) . ': ' . __( 'left', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'center',
                                    'label' => __( 'Align', 'easy-image-collage' ) . ': ' . __( 'center', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'right',
                                    'label' => __( 'Align', 'easy-image-collage' ) . ': ' . __( 'right', 'easy-image-collage' ),
                                ),
                            ),
                            'default' => array(
                                'center',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'default_style_grid_width',
                            'label' => __('Grid Width', 'easy-image-collage'),
                            'min' => '150',
                            'max' => '2000',
                            'step' => '1',
                            'default' => '500',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'default_style_grid_ratio',
                            'label' => __('Grid Ratio', 'easy-image-collage'),
                            'min' => '0.25',
                            'max' => '4',
                            'step' => '0.05',
                            'default' => '1',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'default_style_border_width',
                            'label' => __('Border Width', 'easy-image-collage'),
                            'min' => '0',
                            'max' => '20',
                            'step' => '1',
                            'default' => '4',
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'default_style_border_color',
                            'label' => __('Border Color', 'easy-image-collage'),
                            'default' => '#444444',
                            'format' => 'hex',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= RESPONSIVE =-=-=-=-=-=-=
array(
    'title' => __('Reponsive Layout', 'easy-image-collage'),
    'name' => 'responsive',
    'icon' => 'font-awesome:fa-mobile',
    'controls' => array(
        array(
            'type' => 'section',
            'title' => __('General', 'easy-image-collage'),
            'name' => 'responsive_general',
            'fields' => array(
                array(
                    'type' => 'slider',
                    'name' => 'responsive_breakpoint',
                    'label' => __('Responsive Breakpoint', 'easy-image-collage'),
                    'description' => __( "The width of the collage at which will be switched to the mobile version. Make sure it's not larger than the initial width.", 'easy-image-collage' ),
                    'min' => '10',
                    'max' => '1000',
                    'step' => '1',
                    'default' => '300',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'responsive_layout',
                    'label' => __('Show regular images on mobile', 'easy-image-collage'),
                    'description' => __( 'Prevent a collage from becoming to small by having regular images on mobile. Important: this will only work with "Actual Images" display mode.', 'easy-image-collage' ),
                    'default' => '0',
                ),
            ),
        ),
        array(
            'type' => 'section',
            'title' => __('Captions', 'easy-image-collage'),
            'name' => 'responsive_captions',
            'fields' => array(
                array(
                    'type' => 'toggle',
                    'name' => 'responsive_hide_captions',
                    'label' => __('Hide on mobile', 'easy-image-collage'),
                    'description' => __( 'Prevent captions from blocking the image by hiding them on mobile.', 'easy-image-collage' ),
                    'default' => '0',
                ),
            ),
        ),
    ),
),
//=-=-=-=-=-=-= LIGHTBOX =-=-=-=-=-=-=
        array(
            'title' => __('Lightbox', 'easy-image-collage'),
            'name' => 'lightbox',
            'icon' => 'font-awesome:fa-camera',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'easy-image-collage'),
                    'name' => 'lightbox_general',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'clickable_images',
                            'label' => __('Clickable Images', 'easy-image-collage'),
                            'description' => __( 'Best used in combination with a lightbox plugin.', 'easy-image-collage' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'clickable_images_new_tab',
                            'label' => __('Open in new tab', 'easy-image-collage'),
                            'description' => __( 'Open clickable images in new tab.', 'easy-image-collage' ),
                            'default' => '0',
                            'dependency' => array(
                                'field' => 'clickable_images',
                                'function' => 'vp_dep_boolean',
                            ),
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', 'easy-image-collage'),
                    'name' => 'lightbox_advanced',
                    'fields' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'lightbox_class',
                            'label' => __('Link class', 'easy-image-collage'),
                            'description' => __('Class to be added to the lightbox link.', 'easy-image-collage'),
                            'default' => '',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'lightbox_rel',
                            'label' => __('Link rel', 'easy-image-collage'),
                            'description' => __('Rel value of the lightbox link.', 'easy-image-collage'),
                            'default' => 'lightbox',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= CAPTIONS =-=-=-=-=-=-=
        array(
            'title' => __('Captions', 'easy-image-collage'),
            'name' => 'captions',
            'icon' => 'font-awesome:fa-font',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'captions_premium_not_installed',
                    'label' => 'Easy Image Collage Premium',
                    'description' => __('These features are only available in ', 'easy-image-collage') . ' <a href="http://bootstrapped.ventures/easy-image-collage/" target="_blank">Easy Image Collage Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'eic_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('General', 'easy-image-collage'),
                    'name' => 'captions_general',
                    'fields' => array(
                        array(
                            'type' => 'select',
                            'name' => 'captions_autofill',
                            'label' => __('Autofill Captions', 'easy-image-collage'),
                            'description' => __( 'Automatically fill caption when adding an image to the grid.', 'easy-image-collage' ),
                            'items' => array(
                                array(
                                    'value' => 'disabled',
                                    'label' => __( 'Disabled', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'title',
                                    'label' => __( 'Image title', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'caption',
                                    'label' => __( 'Image caption', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'alt',
                                    'label' => __( 'Image alt', 'easy-image-collage' ),
                                ),
                            ),
                            'default' => array(
                                'disabled',
                            ),
                            'validation' => 'required',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Appearance', 'easy-image-collage'),
                    'name' => 'captions_appearance',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'captions_hover_only',
                            'label' => __('Show on Hover Only', 'easy-image-collage'),
                            'default' => '1',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'captions_location',
                            'label' => __('Location', 'easy-image-collage'),
                            'items' => array(
                                array(
                                    'value' => 'top',
                                    'label' => __( 'Top', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'bottom',
                                    'label' => __( 'Bottom', 'easy-image-collage' ),
                                ),
                            ),
                            'default' => array(
                                'bottom',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'captions_text_alignment',
                            'label' => __('Text Alignment', 'easy-image-collage'),
                            'items' => array(
                                array(
                                    'value' => 'left',
                                    'label' => __( 'Left', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'center',
                                    'label' => __( 'Center', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'right',
                                    'label' => __( 'Right', 'easy-image-collage' ),
                                ),
                            ),
                            'default' => array(
                                'left',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'slider',
                            'name' => 'captions_font_size',
                            'label' => __('Font Size', 'easy-image-collage'),
                            'min' => '6',
                            'max' => '60',
                            'step' => '1',
                            'default' => '12',
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'captions_text_color',
                            'label' => __('Text Color', 'easy-image-collage'),
                            'default' => 'rgba(255,255,255,1)',
                            'format' => 'rgba',
                        ),
                        array(
                            'type' => 'color',
                            'name' => 'captions_background_color',
                            'label' => __('Background Color', 'easy-image-collage'),
                            'default' => 'rgba(0,0,0,0.7)',
                            'format' => 'rgba',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= SOCIAL MEDIA =-=-=-=-=-=-=
        array(
            'title' => __('Social Media', 'easy-image-collage'),
            'name' => 'social',
            'icon' => 'font-awesome:fa-thumbs-o-up',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('Pinterest', 'easy-image-collage'),
                    'name' => 'social_section_pinterest',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'pinterest_enable',
                            'label' => __('Pinterest on Hover', 'easy-image-collage'),
                            'description' => __( 'Show a Pinterest button when hovering over images.', 'easy-image-collage' ),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'pinterest_location',
                            'label' => __('Button Location', 'easy-image-collage'),
                            'items' => array(
                                array(
                                    'value' => 'top_left',
                                    'label' => __( 'Top Left', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'top_right',
                                    'label' => __( 'Top Right', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'bottom_left',
                                    'label' => __( 'Bottom Left', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'bottom_right',
                                    'label' => __( 'Bottom Right', 'easy-image-collage' ),
                                ),
                            ),
                            'default' => array(
                                'top_left',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'pinterest_style',
                            'label' => __('Button Style', 'easy-image-collage'),
                            'items' => array(
                                array(
                                    'value' => 'default',
                                    'label' => __( 'Default', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'red',
                                    'label' => __( 'Red', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'white',
                                    'label' => __( 'White', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'round',
                                    'label' => __( 'Round', 'easy-image-collage' ),
                                ),
                            ),
                            'default' => array(
                                'default',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'pinterest_size',
                            'label' => __('Button Size', 'easy-image-collage'),
                            'items' => array(
                                array(
                                    'value' => 'default',
                                    'label' => __( 'Default', 'easy-image-collage' ),
                                ),
                                array(
                                    'value' => 'large',
                                    'label' => __( 'Large', 'easy-image-collage' ),
                                ),
                            ),
                            'default' => array(
                                'default',
                            ),
                            'validation' => 'required',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'pinterest_description',
                            'label' => __('Description', 'easy-image-collage'),
                            'description' => __('You can use the following placeholders:', 'easy-image-collage') . ' %title% %alt% %caption%',
                            'default' => '%title%',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= CUSTOM LINKS =-=-=-=-=-=-=
        array(
            'title' => __('Custom Links', 'easy-image-collage'),
            'name' => 'custom_links',
            'icon' => 'font-awesome:fa-link',
            'controls' => array(
                array(
                    'type' => 'notebox',
                    'name' => 'custom_links_premium_not_installed',
                    'label' => 'Easy Image Collage Premium',
                    'description' => __('These features are only available in ', 'easy-image-collage') . ' <a href="http://bootstrapped.ventures/easy-image-collage/" target="_blank">Easy Image Collage Premium</a></strong>.',
                    'status' => 'warning',
                    'dependency' => array(
                        'field' => '',
                        'function' => 'eic_admin_premium_not_installed',
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Defaults', 'easy-image-collage'),
                    'name' => 'custom_link_defaults',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'custom_link_new_tab',
                            'label' => __('Open in New Tab', 'easy-image-collage'),
                            'default' => '0',
                        ),
                        array(
                            'type' => 'toggle',
                            'name' => 'custom_link_nofollow',
                            'label' => __('Use Nofollow', 'easy-image-collage'),
                            'default' => '0',
                        ),
                    ),
                ),
            ),
        ),
//=-=-=-=-=-=-= CUSTOM CODE =-=-=-=-=-=-=
        array(
            'title' => __('Custom Code', 'easy-image-collage'),
            'name' => 'custom_code',
            'icon' => 'font-awesome:fa-code',
            'controls' => array(
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_public_css',
                    'label' => __('Public CSS', 'easy-image-collage'),
                    'theme' => 'github',
                    'mode' => 'css',
                ),
            ),
        ),
    ),
);