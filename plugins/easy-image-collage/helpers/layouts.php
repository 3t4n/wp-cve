<?php

class EIC_Layouts {

    private $layouts;

    public function __construct()
    {
        $this->layouts();
    }

    public function draw_layouts( $controls = false )
    {
        $output = '';
        foreach( $this->layouts as $name => $layout ) {
	        $layout['name'] = $name;
            $output .= $this->draw_layout( $layout, false, $controls );
        }

        return $output;
    }

    public function draw_layout( $layout, $grid, $controls = false )
    {
        $grid_id = $grid ? $grid->ID() : 0;

        $output = '<div class="eic-frame eic-frame-' . $grid_id . ' eic-frame-' . $layout['name'] . '" data-layout-name="' . $layout['name'] . '"';
        if( $grid ) {
            $output .= ' data-orig-width="' . $grid->width() . '"';
            $output .= ' data-orig-border="' . $grid->border_width() . '"';
            $output .= ' data-ratio="' . $grid->ratio() . '"';
        }
        $output .= '>';
        $output .= $this->draw_block( $layout, $grid, $controls );
        $output .= '</div>';
        return $output;
    }

    private function draw_block( $block, $grid = false, $controls = false )
    {
        if( $block['type'] == 'img' ) {
            $output = '<div class="eic-image eic-image-' . $block['id'] . '"';
            if( $grid ) {
                $image = $grid->image( $block['id'] );

                if( $image ) {
                    $output .= ' data-size-x="' . $image['size_x'] . '"';
                    $output .= ' data-size-y="' . $image['size_y'] . '"';
                    $output .= ' data-pos-x="' . $image['pos_x'] . '"';
                    $output .= ' data-pos-y="' . $image['pos_y'] . '"';
                    $output .= '>';

                    $image_post = get_post( $image['attachment_id'] );

                    if( isset( $image['custom_link'] ) && $image['custom_link'] !== '' ) {
                        $new_tab = !$image['custom_link_new_tab'] || $image['custom_link_new_tab'] === 'false' ? '' : ' target="_blank"';
                        $nofollow = !$image['custom_link_nofollow'] || $image['custom_link_nofollow'] === 'false' ? '' : ' rel="nofollow"';

                        $output .= '<a href="' . esc_attr( $image['custom_link'] ) . '" title="' . esc_attr( $image_post->post_title ) . '" class="eic-image-custom-link"' . $new_tab . $nofollow . '></a>';
                    } elseif( EasyImageCollage::option( 'clickable_images', '0' ) == '1' ) {
                        $class = EasyImageCollage::option( 'lightbox_class', '' );
                        $rel = EasyImageCollage::option( 'lightbox_rel', 'lightbox' );
                        $target = EasyImageCollage::option( 'clickable_images_new_tab', '0' ) == '1' ? ' target="_blank"' : '';

                        $output .= '<a href="' . $image['attachment_url'] . '" rel="' . esc_attr( $rel ) . '" title="' . esc_attr( $image_post->post_title ) . '" class="eic-image-link ' . esc_attr( $class ) . '"' . $target . '></a>';
                    }

                    // Pinterest Button
                    if( !$controls && EasyImageCollage::option( 'pinterest_enable', '0' ) == '1' ) {
                        $pin_image = $image['attachment_url'];

                        $image_caption = isset( $image['custom_caption'] ) ? $image['custom_caption'] : '';
                        $image_title = $image_post->post_title;
                        $image_alt = get_post_meta( $image['attachment_id'], '_wp_attachment_image_alt', true );

                        $pin_description = EasyImageCollage::option( 'pinterest_description', '%title%' );
                        $pin_description = str_ireplace( '%title%', $image_title, $pin_description );
                        $pin_description = str_ireplace( '%alt%', $image_alt, $pin_description );
                        $pin_description = str_ireplace( '%caption%', $image_caption, $pin_description );

                        switch( EasyImageCollage::option( 'pinterest_style', 'default' ) ) {
                            case 'red':
                                $pin_style = ' data-pin-color="red"';
                                break;
                            case 'white':
                                $pin_style = ' data-pin-color="white"';
                                break;
                            case 'round':
                                $pin_style = ' data-pin-shape="round"';
                                break;
                            default:
                                $pin_style = '';
                        }

                        switch( EasyImageCollage::option( 'pinterest_size', 'default' ) ) {
                            case 'large':
                                $pin_size = ' data-pin-tall="true"';
                                break;
                            default:
                                $pin_size = '';
                        }

                        $output .= apply_filters( 'eic_layouts_output_pin_button', '<a data-pin-do="buttonPin" href="https://www.pinterest.com/pin/create/button/?media=' . rawurlencode( $pin_image ) . '&description=' . rawurlencode( $pin_description ) . '"' . $pin_style . $pin_size . '></a>', $image );
                    }

                    // Captions
                    if( !$controls && EasyImageCollage::is_addon_active( 'captions' ) ) {
                        if( isset( $image['custom_caption'] ) && $image['custom_caption'] ) {
                            $hover = EasyImageCollage::option( 'captions_hover_only', '1' ) == '1' ? ' eic-image-caption-hover' : '';
                            $output .= '<span class="eic-image-caption' . $hover . '">' . $image['custom_caption'] . '</span>';
                        }
                    }

                } else {
                    $output .= '>';
                }
            } else {
                $output .= '>';
            }

            if( $controls ) {
                if( EasyImageCollage::is_premium_active() ) {
                    $output .= '<div class="eic-image-size">';
                    $output .= '<span class="eic-image-width">0</span> x <span class="eic-image-height">0</span>';
                    $output .= '</div>';
                }
                $output .= '<div class="eic-image-controls">';
                $output .= '<div class="eic-image-control eic-image-control-image" onclick="event.preventDefault(); EasyImageCollage.btnImage(' . $block['id'] . ')"><i class="fa fa-picture-o"></i></div>';
                $output .= '<div class="eic-image-control eic-image-control-manipulate" onclick="event.preventDefault(); EasyImageCollage.btnManipulate(' . $block['id'] . ')"><i class="fa fa-wrench"></i></div>';
                $output .= '<div class="eic-image-control eic-image-control-link" onclick="event.preventDefault(); EasyImageCollage.btnLink(' . $block['id'] . ')"><i class="fa fa-link"></i></div>';
                $output .= '<div class="eic-image-control eic-image-control-caption" onclick="event.preventDefault(); EasyImageCollage.btnCaption(' . $block['id'] . ')"><i class="fa fa-font"></i></div>';
                $output .= '</div>';
            }

            $output .= '</div>';

            return $output;
        } else {
	        if( $grid && isset( $block['id'] ) ) {
		        $pos = $grid->divider_adjust( $block['id'] );

		        if( $pos ) {
			        $block['pos'] = $pos;
		        }
	        }

            $percentage1 = str_replace( ',', '.', $block['pos'] * 100 );
            $percentage2 = str_replace( ',', '.', 100 - $percentage1 );

            if( $block['type'] == 'row' ) {
                $style1 = 'top: 0; left: 0; right: 0; bottom: ' . $percentage1 . '%; height: ' . $percentage1 . '%;';
                $style2 = 'bottom: 0; left: 0; right: 0; top: ' . $percentage1 . '%; height: ' . $percentage2 . '%;';
            } else {
                $style1 = 'top: 0; bottom: 0; left: 0; right: ' . $percentage1 . '%; width: ' . $percentage1 . '%;';
                $style2 = 'top: 0; bottom: 0; right: 0; left: ' . $percentage1 . '%; width: ' . $percentage2 . '%;';
            }

            $output = '<div class="eic-' . $block['type'] . 's">';
            $output .= '<div class="eic-' . $block['type'] . ' eic-child-1" style="' . $style1 . '">';
            $output .= $this->draw_block( $block['children'][0], $grid, $controls );
            $output .= '</div>';

	        if( $controls ) {
		        if( $block['type'] == 'row' ) {
			        $divider_style = 'left: 10%; right: 0; top: ' . $percentage1 . '%; height: 4px; width: 80%; margin-top: -2px; cursor: row-resize;';
		        } else {
			        $divider_style = 'top: 10%; bottom: 0; left: ' . $percentage1 . '%; height: 80%; width: 4px; margin-left: -2px; cursor: col-resize;';
		        }
		        $output .= '<div class="eic-divider eic-divider-' . $block['type'] . ' eic-divider-' . $block['id'] . '" style="' . $divider_style . '" data-divider-type="' . $block['type'] . '" data-divider-id="' . $block['id'] . '"></div>';
	        }

            $output .= '<div class="eic-' . $block['type'] . ' eic-child-2" style="' . $style2 . '">';
            $output .= $this->draw_block( $block['children'][1], $grid, $controls );
            $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
    }

    public function draw_layout_frontend( $layout, $grid )
    {
        $grid_id = $grid ? $grid->ID() : 0;

        $output = '<div class="eic-frame eic-frame-' . $grid_id . ' eic-frame-' . $layout['name'] . '" data-layout-name="' . $layout['name'] . '"';
        $output .= ' data-orig-width="' . $grid->width() . '"';
        $output .= ' data-orig-border="' . $grid->border_width() . '"';
        $output .= ' data-ratio="' . $grid->ratio() . '"';
        $output .= '>';
        $output .= $this->draw_block_frontend( $layout, $grid );
        $output .= '</div>';
        return $output;
    }

    private function draw_block_frontend( $block, $grid )
    {
        if( $block['type'] == 'img' ) {
            $output = '<div class="eic-image eic-image-' . $block['id'] . '"';
            $image = $grid->image( $block['id'] );

            if( $image ) {
                $output .= ' data-size-x="' . $image['size_x'] . '"';
                $output .= ' data-size-y="' . $image['size_y'] . '"';
                $output .= ' data-pos-x="' . $image['pos_x'] . '"';
                $output .= ' data-pos-y="' . $image['pos_y'] . '"';
                $output .= '>';

                $image_post = get_post( $image['attachment_id'] );
                
                // Get thumbnail to output.
                $url = $image['attachment_url'];

                $width = intval( $image['size_x'] );
                $height = intval( $image['size_y'] );
                $ratio = $width / $height;

                $thumb = wp_get_attachment_image_src( $image['attachment_id'], array( $width, $height ) );

                if( $thumb ) {
                    $full_file_name = get_attached_file( $image['attachment_id'] );
                    $path = str_ireplace( wp_basename( $full_file_name ), '', $full_file_name );
                
                    $thumb_url = $thumb[0];
                    $thumb_file = $path . wp_basename( $thumb_url );

                    // Try path first for performance reasons, fall back on URL.
                    @list( $thumb_width, $thumb_height ) = getimagesize( $thumb_file );

                    if ( !$thumb_width || !$thumb_height ) {
                        @list( $thumb_width, $thumb_height ) = getimagesize( $thumb_url );
                    }

                    if( $thumb_width && $thumb_height ) {
                        $thumb_ratio = $thumb_width / $thumb_height;

                        if( abs( $thumb_ratio - $ratio ) < 0.05 ) {
                            $url = $thumb_url; // Only use the thumbnail if the ratios match
                        }
                    }
                }

                // Output image.
                $img_style = 'width: ' . $width . 'px !important;';
                $img_style .= 'height: ' . $height . 'px !important;';
                $img_style .= 'max-width: none !important;';
                $img_style .= 'max-height: none !important;';
                $img_style .= 'position: absolute !important;';
                $img_style .= 'left: ' . $image['pos_x'] . 'px !important;';
                $img_style .= 'top: ' . $image['pos_y'] . 'px !important;';
                $img_style .= 'padding: 0 !important;';
                $img_style .= 'margin: 0 !important;';
                $img_style .= 'border: none !important;';

                $image_caption = isset( $image['custom_caption'] ) ? $image['custom_caption'] : '';
                $image_title = $image_post->post_title;
                $image_alt = get_post_meta( $image['attachment_id'], '_wp_attachment_image_alt', true );
                
                $title = $image_caption ? $image_caption : $image_title;
                $alt = $image_alt ? $image_alt : ( $image_caption ? $image_caption : $image_title );

                $output .= apply_filters( 'eic_layouts_output_image', '<img src="' . $url . '" style="' . $img_style . '" title="' . esc_attr( $title ) . '" alt="' . esc_attr( $alt ) . '" />', $image );

                // Other image options.
                if( isset( $image['custom_link'] ) && $image['custom_link'] !== '' ) {
                    $new_tab = !$image['custom_link_new_tab'] || $image['custom_link_new_tab'] === 'false' ? '' : ' target="_blank"';
                    $nofollow = !$image['custom_link_nofollow'] || $image['custom_link_nofollow'] === 'false' ? '' : ' rel="nofollow"';

                    $output .= '<a href="' . esc_attr( $image['custom_link'] ) . '" title="' . esc_attr( $title ) . '" class="eic-image-custom-link"' . $new_tab . $nofollow . '></a>';
                } elseif( EasyImageCollage::option( 'clickable_images', '0' ) == '1' ) {
                    $class = EasyImageCollage::option( 'lightbox_class', '' );
                    $rel = EasyImageCollage::option( 'lightbox_rel', 'lightbox' );
                    $target = EasyImageCollage::option( 'clickable_images_new_tab', '0' ) == '1' ? ' target="_blank"' : '';

                    $output .= '<a href="' . $image['attachment_url'] . '" rel="' . esc_attr( $rel ) . '" title="' . esc_attr( $title ) . '" class="eic-image-link ' . esc_attr( $class ) . '"' . $target . '></a>';
                }

                // Pinterest Button
                if( EasyImageCollage::option( 'pinterest_enable', '0' ) == '1' ) {
                    $pin_image = $image['attachment_url'];

                    $pin_description = EasyImageCollage::option( 'pinterest_description', '%title%' );
                    $pin_description = str_ireplace( '%title%', $image_title, $pin_description );
                    $pin_description = str_ireplace( '%alt%', $image_alt, $pin_description );
                    $pin_description = str_ireplace( '%caption%', $image_caption, $pin_description );
                    $pin_description = esc_attr( $pin_description );

                    switch( EasyImageCollage::option( 'pinterest_style', 'default' ) ) {
                        case 'red':
                            $pin_style = ' data-pin-color="red"';
                            break;
                        case 'white':
                            $pin_style = ' data-pin-color="white"';
                            break;
                        case 'round':
                            $pin_style = ' data-pin-shape="round"';
                            break;
                        default:
                            $pin_style = '';
                    }

                    switch( EasyImageCollage::option( 'pinterest_size', 'default' ) ) {
                        case 'large':
                            $pin_size = ' data-pin-tall="true"';
                            break;
                        default:
                            $pin_size = '';
                    }

                    $output .= apply_filters( 'eic_layouts_output_pin_button', '<a data-pin-do="buttonPin" href="https://www.pinterest.com/pin/create/button/?media=' . rawurlencode( $pin_image ) . '&description=' . rawurlencode( $pin_description ) . '"' . $pin_style . $pin_size . '></a>', $image );
                }

                // Captions
                if( EasyImageCollage::is_addon_active( 'captions' ) ) {
                    if( isset( $image['custom_caption'] ) && $image['custom_caption'] ) {
                        $hover = EasyImageCollage::option( 'captions_hover_only', '1' ) == '1' ? ' eic-image-caption-hover' : '';
                        $output .= '<span class="eic-image-caption' . $hover . '">' . $image['custom_caption'] . '</span>';
                    }
                }

            } else {
                $output .= '>';
            }

            $output .= '</div>';

            return $output;
        } else {
	        if( isset( $block['id'] ) ) {
		        $pos = $grid->divider_adjust( $block['id'] );

		        if( $pos ) {
			        $block['pos'] = $pos;
		        }
	        }

            $percentage1 = str_replace( ',', '.', $block['pos'] * 100 );
            $percentage2 = str_replace( ',', '.', 100 - $percentage1 );

            if( $block['type'] == 'row' ) {
                $style1 = 'top: 0; left: 0; right: 0; bottom: ' . $percentage1 . '%; height: ' . $percentage1 . '%;';
                $style2 = 'bottom: 0; left: 0; right: 0; top: ' . $percentage1 . '%; height: ' . $percentage2 . '%;';
            } else {
                $style1 = 'top: 0; bottom: 0; left: 0; right: ' . $percentage1 . '%; width: ' . $percentage1 . '%;';
                $style2 = 'top: 0; bottom: 0; right: 0; left: ' . $percentage1 . '%; width: ' . $percentage2 . '%;';
            }

            $output = '<div class="eic-' . $block['type'] . 's">';
            $output .= '<div class="eic-' . $block['type'] . ' eic-child-1" style="' . $style1 . '">';
            $output .= $this->draw_block_frontend( $block['children'][0], $grid );
            $output .= '</div>';

            $output .= '<div class="eic-' . $block['type'] . ' eic-child-2" style="' . $style2 . '">';
            $output .= $this->draw_block_frontend( $block['children'][1], $grid );
            $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
    }

	public function get( $name )
	{
		return isset( $this->layouts[ $name ] ) ? $this->layouts[ $name ] : false;
	}

    private function layouts()
    {
        $this->layouts = array(
            'square' => array(
                'type' => 'img',
                'id' => 0
            ),
            '4-squares' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 2,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '2-col' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'img',
                        'id' => 1
                    ),
                ),
            ),
            '2-row' => array(
                'type' => 'row',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'img',
                        'id' => 1
                    ),
                ),
            ),
            '2-row-bottom-2-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-top-2-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 2
                    ),
                ),
            ),
            '2-col-right-2-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-left-2-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 2
                    ),
                ),
            ),
            '3-row' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                ),
            ),
            '3-col' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                ),
            ),
            '4-row' => array(
                'type' => 'row',
                'pos' => 0.25,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '4-col' => array(
                'type' => 'col',
                'pos' => 0.25,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-bottom-3-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-top-3-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-col-right-3-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-left-3-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '4-squares-odd-left' => array(
                'type' => 'row',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.66666,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'id' => 2,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '4-squares-odd-right' => array(
                'type' => 'row',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.66666,
                        'id' => 2,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '4-squares-odd-bottom' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.66666,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'id' => 2,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '4-squares-odd-top' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.66666,
                        'id' => 2,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '3-row-first-2-col' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                        ),
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 2,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '3-row-second-2-col' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '3-row-third-2-col' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '3-col-first-2-row' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                        ),
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 2,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '3-col-second-2-row' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '3-col-third-2-row' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-bottom-2-col-right-2-row' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-top-2-col-right-2-row' => array(
                'type' => 'row',
                'pos' => 0.66666,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-row-bottom-2-col-left-2-row' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-top-2-col-left-2-row' => array(
                'type' => 'row',
                'pos' => 0.66666,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 0
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-col-right-2-row-bottom-2-col' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-left-2-row-bottom-2-col' => array(
                'type' => 'col',
                'pos' => 0.66666,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-col-right-2-row-top-2-col' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-left-2-row-top-2-col' => array(
                'type' => 'col',
                'pos' => 0.66666,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 0
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-row-3-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'id' => 3,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'id' => 4,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 4
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 5
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-3-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'id' => 3,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 4,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 4
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 5
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '9-squares' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'id' => 0,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'id' => 1,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'id' => 2,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'id' => 3,
                        'children' => array(
                            array(
                                'type' => 'row',
                                'pos' => 0.33333,
                                'id' => 4,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                    array(
                                        'type' => 'row',
                                        'pos' => 0.5,
                                        'id' => 5,
                                        'children' => array(
                                            array(
                                                'type' => 'img',
                                                'id' => 4
                                            ),
                                            array(
                                                'type' => 'img',
                                                'id' => 5
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.33333,
                                'id' => 6,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 6
                                    ),
                                    array(
                                        'type' => 'row',
                                        'pos' => 0.5,
                                        'id' => 7,
                                        'children' => array(
                                            array(
                                                'type' => 'img',
                                                'id' => 7
                                            ),
                                            array(
                                                'type' => 'img',
                                                'id' => 8
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}