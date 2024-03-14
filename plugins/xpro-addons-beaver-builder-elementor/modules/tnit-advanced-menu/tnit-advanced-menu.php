<?php
/**
 * @class TNITAdvancedMenuModuleLite
 */

class TNITAdvancedMenuModuleLite extends FLBuilderModule {

    /**
     *
     * @method __construct
     */
    public function __construct()
    {
        parent::__construct(array(
            'name'            => __( 'Menu', 'xpro-bb-addons' ),
            'description' 	  => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
            'group'           => XPRO_Plugins_Helper::$branding_modules,
            'category'        => XPRO_Plugins_Helper::$creative_modules,
            'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-advanced-menu/',
            'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-advanced-menu/',
            'icon'            => 'icon.svg',
            'editor_export'   => true,
            'enabled'         => true,
            'partial_refresh' => true,
        ));
    }


    /**
     * @method _get_pages
     * @param $settings
     */
    public static function _get_pages()
    {
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $get_pages = get_pages($args);

        $fields = array(
            'type' 	=> 'select',
            'label' => __( 'Page', 'xpro-bb-addons' ),
            'help'	=> __( 'Select a WordPress page that you created in the admin under Pages > Add New.', 'xpro-bb-addons' ),
        );

        if( $get_pages )
        {
            foreach( $get_pages as $key => $page )
            {
                if( 0 == $key ) {
                    $fields['default'] = $page->post_name;
                }

                $pages[ $page->ID ] = $page->post_title;
            }

            $fields['options'] = $pages;
        }
        else {
            $fields['options'] = array(
                '' => __( 'No Pages Found', 'xpro-bb-addons' ),
            );
        }

        return $fields;
    }


    /**
     * @method _get_posts
     * @param $settings
     */
    public static function _get_posts()
    {
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'post',
            'post_status' => 'publish'
        );
        $get_posts = get_posts($args);

        $fields = array(
            'type' 	=> 'select',
            'label' => __( 'Post', 'xpro-bb-addons' ),
            'help'	=> __( 'Select a WordPress post that you created in the admin under Posts > Add New.', 'xpro-bb-addons' ),
        );

        if( $get_posts )
        {
            foreach( $get_posts as $key => $post )
            {
                if( 0 == $key )
                {
                    $fields['default'] = $post->post_name;
                }

                $posts[ $post->ID ] = $post->post_title;
            }

            $fields['options'] = $posts;
        }
        else
        {
            $fields['options'] = array(
                '' => __( 'No Posts Found', 'xpro-bb-addons' ),
            );
        }

        return $fields;
    }


    /**
     * @method _get_categories
     * @param $settings
     */
    public static function _get_categories()
    {
        $get_categories = get_terms( 'category', array( 'hide_empty' => false ) );
        $fields = array(
            'type' 	=> 'select',
            'label' => __( 'Category', 'xpro-bb-addons' ),
            'help'	=> __( 'Select a WordPress category that you created in the admin under Posts > Categories.', 'xpro-bb-addons' ),
        );

        if( $get_categories )
        {
            foreach( $get_categories as $key => $category )
            {
                if( 0 == $key )
                {
                    $fields['default'] = $category->slug;
                }

                $categories[ $category->term_id ] = $category->name;
            }

            $fields['options'] = $categories;
        }
        else
        {
            $fields['options'] = array(
                '' => __( 'No Categories Found', 'xpro-bb-addons' ),
            );
        }

        return $fields;
    }

    /**
     * Function that renders Menu Icon/Image
     *
     * @method render_menu_icon_image
     */
    public function render_menu_icon_image( $i, $pos )
    {
        $settings  = $this->settings;
        $menu_item = $settings->menu_items[$i];

        /**
         * Get photo data
         *
         * @variable $logo
         */
        if ( !empty( $menu_item->photo ) )
        {
            $photo = FLBuilderPhoto::get_attachment_data( $menu_item->photo );

            // get src
            $src = $this->settings->photo_src;
            $alt = '';

            // get alt
            if( !empty( $photo->alt ) ) {
                $alt = htmlspecialchars( $photo->alt );
            }
            else if( !empty( $photo->description ) ) {
                $alt = htmlspecialchars( $photo->description );
            }
            else if( !empty( $photo->caption ) ) {
                $alt = htmlspecialchars( $photo->caption );
            }
            else if( !empty( $photo->title ) ) {
                $alt = htmlspecialchars( $photo->title );
            }

            // get classes
            $photo_classes = array( 'tnit-menu-img' );

            if ( is_object( $photo ) )
            {
                $photo_classes[] = 'wp-image-' . $photo->id;

                if ( isset( $photo->sizes ) )
                {
                    foreach ( $photo->sizes as $key => $size )
                    {
                        if ( $size->url == $menu_item->photo_src ) {
                            $photo_classes[] = 'size-' . $key;
                            break;
                        }
                    }
                }
            }

            $photo_classes = implode( ' ', $photo_classes );
        }


        /**
         * Get icon/photo data
         *
         * @variable $output
         */

        if ( $settings->menu_icon_image_position == $pos ) {

            if ( 'none' != $menu_item->image_type )
            {
                $output = '<small class="tnit-menu-item-icon tnit-menu-item-icon-'.$settings->menu_icon_image_position.'">';

                if ( $menu_item->image_type == 'icon' && !empty( $menu_item->icon ) )
                {
                    $output .= '<i class="' . $menu_item->icon . '" aria-hidden="true"></i>';
                }

                if ( $menu_item->image_type == 'photo' && !empty( $menu_item->photo ) )
                {
                    $output .= '<img src="' . $src . '" class="' . $photo_classes . '" alt="' . $alt . '">';
                }

                $output .= '</small>';

                return $output;
            }
        }
    }


    /**
     * @method render_menu_item_link
     * @param $settings
     */
    public function render_menu_item_link($i)
    {
        $settings = $this->settings;
        $menu_item = $settings->menu_items[$i];

        /**
         * Get IDs according to the menu item type
         */
        if( $menu_item->menu_item_type == 'page' ){
            $menu_item_id = $menu_item->menu_item_page;
        }
        elseif( $menu_item->menu_item_type == 'post' ){
            $menu_item_id = $menu_item->menu_item_post;
        }
        elseif( $menu_item->menu_item_type == 'category' ){
            $menu_item_id = $menu_item->menu_item_category;
            $category = get_term_by( 'id', $menu_item_id, 'category' );
        }

        // Title attribute
        $menu_item_title_attr = ( $menu_item->menu_title_attribute != '' ) ? ' title="'.$menu_item->menu_title_attribute.'"' : '';

        // Relation attribute
        $menu_item_rel_attr = ( $menu_item->link_nofollow ) ? ' rel="nofollow"' : '';

        if( $menu_item->menu_item_type == 'page' || $menu_item->menu_item_type == 'post' )
        {
            $output = '<span class="tnit-menu-text">';

            // render icon before
            $output .= $this->render_menu_icon_image( $i, 'before' );

            // menu item text
            $output .= '<a href="'.esc_url( get_permalink( $menu_item_id ) ).'" target="'.$menu_item->menu_link_target.'"'.$menu_item_title_attr . $menu_item_rel_attr .'>';
            $output .= ( $menu_item->custom_label != '' ) ? esc_html($menu_item->custom_label) : get_the_title( $menu_item_id );
            //$output .= get_the_title( $menu_item_id );
            $output .= '</a>';

            // render icon after
            $output .= $this->render_menu_icon_image( $i, 'after' );

            // Menu Dropdown Icons for Large Screen
            $output .= ( $menu_item->add_submenu ) ? '<span class="tnit-advance-menu-dropdown-toggle"><i class="fas fa-chevron-down"></i></span>' : '';

            $output .= '</span>';
        }
        elseif( $menu_item->menu_item_type == 'category' )
        {
            $output = '<span class="tnit-menu-text">';
            // render icon before
            $output .= $this->render_menu_icon_image( $i, 'before' );

            // menu item text
            $output .= '<a href="'.esc_url( get_category_link( $menu_item_id ) ).'" target="'.$menu_item->menu_link_target.'"'.$menu_item_title_attr . $menu_item_rel_attr .'>';
            $output .= ( $menu_item->custom_label != '' ) ? esc_html($menu_item->custom_label) : $category->name;
            //$output .= $category->name;
            $output .= '</a>';

            // render icon after
            $output .= $this->render_menu_icon_image( $i, 'after' );

            // Menu Dropdown Icons for Large Screen
            $output .= ( $menu_item->add_submenu ) ? '<span class="tnit-advance-menu-dropdown-toggle"><i class="fas fa-chevron-down"></i></span>' : '';

            $output .= '</span>';
        }
        elseif( $menu_item->menu_item_type == 'custom-link' )
        {
            $output = '<span class="tnit-menu-text">';

            // render icon before
            $output .= $this->render_menu_icon_image( $i, 'before' );

            // menu item text
            $output .= '<a href="'.esc_url( $menu_item->menu_item_custom_link ).'" target="'.$menu_item->menu_link_target.'"'.$menu_item_title_attr . $menu_item_rel_attr .'>';
            $output .= $menu_item->custom_label;
            $output .= '</a>';

            // render icon after
            $output .= $this->render_menu_icon_image( $i, 'after' );

            // Menu Dropdown Icons for Large Screen
            $output .= ( $menu_item->add_submenu ) ? '<span class="tnit-advance-menu-dropdown-toggle"><i class="fas fa-chevron-down"></i></span>' : '';

            $output .= '</span>';
        }

        return $output;
    }

    /**
     * Function that renders Submenu Icon/Image
     *
     * @method render_submenu_icon_image
     */
    public function render_submenu_icon_image( $i, $j, $pos )
    {
        $settings  = $this->settings;
        $menu_item = $settings->menu_items[$i];

        $submenu_item_json 	= $menu_item->submenu_items[$j];
        $submenu_item 		= json_decode($submenu_item_json);

        /**
         * Get photo data
         *
         * @variable $logo
         */
        if ( !empty( $submenu_item->photo ) )
        {
            $photo = FLBuilderPhoto::get_attachment_data( $submenu_item->photo );

            // get src
            $src = $this->settings->photo_src;
            $alt = '';

            // get alt
            if( !empty( $photo->alt ) ) {
                $alt = htmlspecialchars( $photo->alt );
            }
            else if( !empty( $photo->description ) ) {
                $alt = htmlspecialchars( $photo->description );
            }
            else if( !empty( $photo->caption ) ) {
                $alt = htmlspecialchars( $photo->caption );
            }
            else if( !empty( $photo->title ) ) {
                $alt = htmlspecialchars( $photo->title );
            }

            // get classes
            $photo_classes = array( 'tnit-submenu-img' );

            if ( is_object( $photo ) )
            {
                $photo_classes[] = 'wp-image-' . $photo->id;

                if ( isset( $photo->sizes ) )
                {
                    foreach ( $photo->sizes as $key => $size )
                    {
                        if ( $size->url == $submenu_item->photo_src ) {
                            $photo_classes[] = 'size-' . $key;
                            break;
                        }
                    }
                }
            }

            $photo_classes = implode( ' ', $photo_classes );
        }


        /**
         * Get icon/photo data
         *
         * @variable $output
         */

        if ( $settings->submenu_icon_image_position == $pos ) {

            if ( 'none' != $submenu_item->image_type )
            {
                $output = '<small class="tnit-submenu-item-icon tnit-submenu-item-icon-'.$settings->submenu_icon_image_position.'">';

                if ( $submenu_item->image_type == 'icon' && !empty( $submenu_item->icon ) )
                {
                    $output .= '<i class="' . $submenu_item->icon . '" aria-hidden="true"></i>';
                }

                if ( $submenu_item->image_type == 'photo' && !empty( $submenu_item->photo ) )
                {
                    $output .= '<img src="' . $src . '" class="' . $photo_classes . '" alt="' . $alt . '">';
                }

                $output .= '</small>';

                return $output;
            }
        }
    }


    /**
     * @method render_menu_item_link
     * @param $settings
     */
    public function render_submenu_item_link($i, $j)
    {
        $settings = $this->settings;
        $menu_item = $settings->menu_items[$i];

        $submenu_item_json = $menu_item->submenu_items[$j];
        $submenu_item = json_decode($submenu_item_json);

        /**
         * Get IDs according to the menu item type
         */
        if( $submenu_item->submenu_item_type == 'page' ){
            $submenu_item_id = $submenu_item->submenu_item_page;
        }
        elseif( $submenu_item->submenu_item_type == 'post' ){
            $submenu_item_id = $submenu_item->submenu_item_post;
        }
        elseif( $submenu_item->submenu_item_type == 'category' ){
            $submenu_item_id = $submenu_item->submenu_item_category;
            $category = get_term_by( 'id', $submenu_item_id, 'category' );
        }

        // Title attribute
        $submenu_item_title_attr = ( $submenu_item->submenu_title_attribute != '' ) ? ' title="'.$submenu_item->submenu_title_attribute.'"' : '';

        // Relation attribute
        $submenu_item_rel_attr = ( $submenu_item->link_nofollow ) ? ' rel="nofollow"' : '';

        if( $submenu_item->submenu_item_type == 'page' || $submenu_item->submenu_item_type == 'post' )
        {
            $output = '<span class="tnit-submenu-text">';

            // render icon before
            $output .= $this->render_submenu_icon_image( $i, $j, 'before' );

            // menu item text
            $output .= '<a href="'.esc_url( get_permalink( $submenu_item_id ) ).'" target="'.$submenu_item->submenu_link_target.'"'.$submenu_item_title_attr . $submenu_item_rel_attr .'>';
            $output .= ( $submenu_item->custom_label != '' ) ? esc_html($submenu_item->custom_label) : get_the_title( $submenu_item_id );
            $output .= '</a>';
            //$output .= get_the_title( $submenu_item_id );

            // render icon after
            $output .= $this->render_submenu_icon_image( $i, $j, 'after' );

            $output .= '</span>';
        }
        elseif( $submenu_item->submenu_item_type == 'category' )
        {
            $output = '<span class="tnit-submenu-text">';
            // render icon before
            $output .= $this->render_submenu_icon_image( $i, $j, 'before' );

            // menu item text
            $output .= '<a href="'.esc_url( get_category_link( $submenu_item_id ) ).'" target="'.$submenu_item->submenu_link_target.'"'.$submenu_item_title_attr . $submenu_item_rel_attr .'>';
            $output .= ( $submenu_item->custom_label != '' ) ? esc_html($submenu_item->custom_label) : $category->name;
            $output .= '</a>';
            //$output .= $category->name;

            // render icon after
            $output .= $this->render_submenu_icon_image( $i, $j, 'after' );

            $output .= '</span>';
        }
        elseif( $submenu_item->submenu_item_type == 'custom-link' )
        {
            $output = '<span class="tnit-submenu-text">';
            // render icon before
            $output .= $this->render_submenu_icon_image( $i, $j, 'before' );

            // menu item text
            $output .= '<a href="'.esc_url( $submenu_item->submenu_item_custom_link ).'" target="'.$submenu_item->submenu_link_target.'"'.$submenu_item_title_attr . $submenu_item_rel_attr .'>';
            $output .= $submenu_item->custom_label;
            $output .= '</a>';

            // render icon after
            $output .= $this->render_submenu_icon_image( $i, $j, 'after' );

            $output .= '</span>';
        }

        return $output;
    }

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'TNITAdvancedMenuModuleLite', array(
    'general'      => array(
        'title'         => __( 'General', 'xpro-bb-addons' ),
        'sections'      => array(
            'menu_general'       => array(
                'title'     => '',
                'fields'    => array(
                    'menu_layout' => array(
                        'type'          => 'button-group',
                        'label'         => __( 'Layout', 'xpro-bb-addons' ),
                        'default'       => 'horizontal',
                        'options'       => array(
                            'horizontal' 	=> __( 'Horizontal', 'xpro-bb-addons' ),
                            'vertical'  	=> __( 'Vertical', 'xpro-bb-addons' ),
                            'accordion'  	=> __( 'Accordion', 'xpro-bb-addons' ),
                        ),
                        'toggle'        => array(
                            'horizontal'   => array(
                                'fields' 	=> array('menu_hover_style','submenu_hover_style','menu_margin','submenu_dropdown_effect','menu_wrapper_align'),
                                'sections' 	=> array('submenu_outer'),
                                'tabs' 	=> array('responsive'),
                            ),
                            'vertical'   => array(
                                'fields' 	=> array('submenu_hover_style','submenu_dropdown_effect','menu_wrapper_width','menu_wrapper_align','menu_align'),
                                'sections' 	=> array('submenu_outer'),
                                'tabs' 	=> array('responsive'),
                            ),
                            'accordion'   => array(
                                'fields' 	=> array('acc_menu_align'),
                            ),
                        ),
                    ),
                    'menu_wrapper_align' => array(
                        'type'    => 'align',
                        'label'   => 'Alignment',
                        'default' => 'left',
                    ),
                    'menu_wrapper_width' => array(
                        'type'         	=> 'unit',
                        'label'        	=> 'Width',
                        'units'	       	=> array('px'),
                        'slider' 		=> true,
                        'responsive'    => true,
                        'placeholder'   => '300',
                        'preview'       => array(
                            'type'          => 'css',
                            'rules'           => array(
                                array(
                                    'selector'     => '.tnit-advance-vertical-menu',
                                    'property'     => 'width',
                                    'unit'          => 'px'
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'main_menu'       => array(
                'title'     => __( 'Main Menu', 'xpro-bb-addons' ),
                'fields'    => array(
                    'menu_items' => array(
                        'type'          => 'form',
                        'label'         => __('Menu Item', 'xpro-bb-addons'),
                        'form'          => 'menu_form',
                        'preview_text'  => 'custom_label',
                        'multiple'  	=> true,
                    ),
                ),
            ),
        ),
    ),
    'menu'      => array(
        'title'         => __( 'Menu', 'xpro-bb-addons' ),
        'sections'      => array(
            'general'       => array(
                'title'     => '',
                'fields'    => array(
                    'menu_hover_style' => array(
                        'type'          => 'select',
                        'label'         => __( 'Hover Style', 'xpro-bb-addons' ),
                        'default'       => '1',
                        'options'       => array(
                            '1' 	=> __( 'Style 1', 'xpro-bb-addons' ),
                            '2'  	=> __( 'Style 2', 'xpro-bb-addons' ),
                            '3'  	=> __( 'Style 3', 'xpro-bb-addons' ),
                            '4'  	=> __( 'Style 4', 'xpro-bb-addons' ),
                            '5'  	=> __( 'Style 5', 'xpro-bb-addons' ),
                        ),
                        'toggle'        => array(
                            '2'   => array(
                                'fields' 	=> array('menu_line_color'),
                            ),
                            '3'   => array(
                                'fields' 	=> array('menu_line_color'),
                            ),
                            '4'   => array(
                                'fields' 	=> array('menu_line_color'),
                            ),
                            '5'   => array(
                                'fields' 	=> array('menu_line_color'),
                            ),
                        ),
                    ),
                    'menu_align' => array(
                        'type'    => 'align',
                        'label'   => 'Menu Alignment',
                        'default' => 'left',
                    ),
                    'acc_menu_align' => array(
                        'type'    => 'align',
                        'label'   => 'Menu Alignment',
                        'default' => 'left',
                        'responsive'    => true,
                    ),
                    'menu_padding' => array(
                        'type'        => 'dimension',
                        'label'         => __( 'Links Padding', 'xpro-bb-addons' ),
                        'units'      	=> array('px'),
                        'responsive'    => true,
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span',
                            'property'      => 'padding',
                            'unit'          => 'px'
                        ),
                    ),
                    'menu_margin' => array(
                        'type'        => 'dimension',
                        'label'         => __( 'Links Margin', 'xpro-bb-addons' ),
                        'units'      	=> array('px'),
                        'responsive'    => true,
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span',
                            'property'      => 'margin',
                            'unit'          => 'px'
                        ),
                    ),
                ),
            ),
            'menu_color'       => array(
                'title'     => __( 'Color Settings', 'xpro-bb-addons' ),
                'collapsed'     => true,
                'fields'    => array(
                    'menu_color' => array(
                        'type'          => 'color',
                        'label'         => __( 'Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span',
                            'property'      => 'color',
                        ),
                    ),
                    'menu_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Hover Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li:hover > span',
                            'property'      => 'color',
                        ),
                    ),
                    'menu_bg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span',
                            'property'      => 'background-color',
                        ),
                    ),
                    'menu_hbg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Hover Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li:hover > span',
                            'property'      => 'background-color',
                        ),
                    ),
                    'menu_line_color' => array(
                        'type'          => 'color',
                        'label'         => __( 'Before/After Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-horizontal-menu-style-2 > ul > li > span::before,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-horizontal-menu-style-2 > ul > li > span::after,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-horizontal-menu-style-3 > ul > li > span::before,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-horizontal-menu-style-3 > ul > li > span::after,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-horizontal-menu-style-4 > ul > li > span::before,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-horizontal-menu-style-5 > ul > li > span::before',
                            'property'      => 'background-color',
                        ),
                    ),
                ),
            ),
            'menu_icon_image'       => array(
                'title'         => 'Icon / Image',
                'collapsed' 	=> true,
                'fields'        => array(
                    'menu_icon_image_position'    => array(
                        'type'        	=> 'button-group',
                        'label'       	=> __( 'Icon/Image Position', 'xpro-bb-addons' ),
                        'default' 		=> 'before',
                        'options' 		=> array(
                            'before' 	=> __( 'Before Text', 'xpro-bb-addons' ),
                            'after'  	=> __( 'After Text', 'xpro-bb-addons' ),
                        ),
                    ),
                    'menu_icon_image_size' => array(
                        'type'         	=> 'unit',
                        'label'        	=> 'Icon/Image Size',
                        'units'	       	=> array('px'),
                        'slider' 		=> true,
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'rules'           => array(
                                array(
                                    'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span > .tnit-menu-item-icon',
                                    'property'     => 'font-size',
                                    'unit'          => 'px'
                                ),
                                array(
                                    'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span > .tnit-menu-item-icon > img',
                                    'property'     => 'width',
                                    'unit'          => 'px'
                                ),
                            ),
                        ),
                    ),
                    'menu_icon_color'	=> array(
                        'type'       => 'color',
                        'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
                        'show_reset' => true,
                        'show_alpha' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span > .tnit-menu-item-icon',
                            'property'      => 'color',
                        ),
                    ),
                    'menu_icon_hvr_color'	=> array(
                        'type'       => 'color',
                        'label'      => __( 'Icon Hover Color', 'xpro-bb-addons' ),
                        'show_reset' => true,
                        'show_alpha' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li:hover > span > .tnit-menu-item-icon',
                            'property'      => 'color',
                        ),
                    ),
                    'menu_icon_image_spacing' => array(
                        'type'         	=> 'unit',
                        'label'        	=> __( 'Icon/Image Spacing', 'xpro-bb-addons' ),
                        'units'	       	=> array('px'),
                        'slider' 		=> true,
                        'responsive' => true,
                        'help' 		 	=> __( 'Spacing between text and icon/image.', 'xpro-bb-addons' ),
                        'preview'       => array(
                            'type'          => 'css',
                            'rules'           => array(
                                array(
                                    'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span > .tnit-menu-item-icon-before',
                                    'property'     => 'margin-right',
                                    'unit'          => 'px'
                                ),
                                array(
                                    'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span > .tnit-menu-item-icon-after',
                                    'property'     => 'margin-left',
                                    'unit'          => 'px'
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'menu_typo'       => array(
                'title'     => __( 'Typography', 'xpro-bb-addons' ),
                'collapsed'     => true,
                'fields'    => array(
                    'menu_typography' => array(
                        'type'       => 'typography',
                        'label'      => 'Typography',
                        'responsive'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span',
                        ),
                    ),
                ),
            ),
            'menu_border'       => array(
                'title'     => __( 'Border Settings', 'xpro-bb-addons' ),
                'collapsed'     => true,
                'fields'    => array(
                    'menu_border' => array(
                        'type'       => 'border',
                        'label'         => __( 'Border', 'xpro-bb-addons' ),
                        'responsive'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span',
                        ),
                    ),
                    'menu_border_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Border Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li:hover > span',
                            'property'      => 'border-color',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'submenu'      => array(
        'title'         => __( 'Sub Menu', 'xpro-bb-addons' ),
        'sections'      => array(
            'general'       => array(
                'title'     => '',
                'fields'    => array(
                    'submenu_hover_style' => array(
                        'type'          => 'select',
                        'label'         => __( 'Hover Style', 'xpro-bb-addons' ),
                        'default'       => '1',
                        'options'       => array(
                            '1' 	=> __( 'Style 1', 'xpro-bb-addons' ),
                            '2'  	=> __( 'Style 2', 'xpro-bb-addons' ),
                            '3'  	=> __( 'Style 3', 'xpro-bb-addons' ),
                            '4'  	=> __( 'Style 4', 'xpro-bb-addons' ),
                            '5'  	=> __( 'Style 5', 'xpro-bb-addons' ),
                        ),
                    ),
                    'submenu_dropdown_effect' => array(
                        'type'          => 'select',
                        'label'         => __( 'Dropdown Effect', 'xpro-bb-addons' ),
                        'default'       => '1',
                        'options'       => array(
                            '1' 	=> __( 'Effect 1', 'xpro-bb-addons' ),
                            '2'  	=> __( 'Effect 2', 'xpro-bb-addons' ),
                            '3'  	=> __( 'Effect 3', 'xpro-bb-addons' ),
                            '4'  	=> __( 'Effect 4', 'xpro-bb-addons' ),
                            '5'  	=> __( 'Effect 5', 'xpro-bb-addons' ),
                        ),
                    ),
                    'submenu_padding' => array(
                        'type'        => 'dimension',
                        'label'         => __( 'Links Padding', 'xpro-bb-addons' ),
                        'units'      	=> array('px'),
                        'responsive'    => true,
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span',
                            'property'      => 'padding',
                            'unit'          => 'px',
                        ),
                    ),
                ),
            ),
            'submenu_color'       => array(
                'title'     => __( 'Color Settings', 'xpro-bb-addons' ),
                'collapsed'     => true,
                'fields'    => array(
                    'submenu_color' => array(
                        'type'          => 'color',
                        'label'         => __( 'Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span',
                            'property'      => 'color',
                        ),
                    ),
                    'submenu_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Hover Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li:hover > span',
                            'property'      => 'color',
                        ),
                    ),
                    'submenu_bg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span',
                            'property'      => 'background-color',
                        ),
                    ),
                    'submenu_hbg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Hover Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav.tnit-advance-accordion-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li:hover > span,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-1 > li:hover > span,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-2 > li > span:before,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-3 > li > span:before,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-4 > li > span:before,
												nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-5 > li > span:before',
                            'property'      => 'background-color',
                        ),
                    ),
                ),
            ),
            'submenu_icon_image'       => array(
                'title'         => 'Icon / Image',
                'collapsed' 	=> true,
                'fields'        => array(
                    'submenu_icon_image_position'    => array(
                        'type'        	=> 'button-group',
                        'label'       	=> __( 'Icon/Image Position', 'xpro-bb-addons' ),
                        'default' 		=> 'before',
                        'options' 		=> array(
                            'before' 	=> __( 'Before Text', 'xpro-bb-addons' ),
                            'after'  	=> __( 'After Text', 'xpro-bb-addons' ),
                        ),
                    ),
                    'submenu_icon_image_size' => array(
                        'type'         	=> 'unit',
                        'label'        	=> 'Icon/Image Size',
                        'units'	       	=> array('px'),
                        'slider' 		=> true,
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'rules'           => array(
                                array(
                                    'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon',
                                    'property'     => 'font-size',
                                    'unit'          => 'px'
                                ),
                                array(
                                    'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon > img',
                                    'property'     => 'width',
                                    'unit'          => 'px'
                                ),
                            ),
                        ),
                    ),
                    'submenu_icon_color'	=> array(
                        'type'       => 'color',
                        'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
                        'show_reset' => true,
                        'show_alpha' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon',
                            'property'      => 'color',
                        ),
                    ),
                    'submenu_icon_hvr_color'	=> array(
                        'type'       => 'color',
                        'label'      => __( 'Icon Hover Color', 'xpro-bb-addons' ),
                        'show_reset' => true,
                        'show_alpha' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li:hover > span > .tnit-submenu-item-icon',
                            'property'      => 'color',
                        ),
                    ),
                    'submenu_icon_image_spacing' => array(
                        'type'         	=> 'unit',
                        'label'        	=> __( 'Icon/Image Spacing', 'xpro-bb-addons' ),
                        'units'	       	=> array('px'),
                        'slider' 		=> true,
                        'responsive' => true,
                        'help' 		 	=> __( 'Spacing between text and icon/image.', 'xpro-bb-addons' ),
                        'preview'       => array(
                            'type'          => 'css',
                            'rules'           => array(
                                array(
                                    'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon-before',
                                    'property'     => 'margin-right',
                                    'unit'          => 'px'
                                ),
                                array(
                                    'selector'     => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon-after',
                                    'property'     => 'margin-left',
                                    'unit'          => 'px'
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'submenu_typo'       => array(
                'title'     => __( 'Typography', 'xpro-bb-addons' ),
                'collapsed'     => true,
                'fields'    => array(
                    'submenu_typography' => array(
                        'type'       => 'typography',
                        'label'      => 'Typography',
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span',
                        ),
                    ),
                ),
            ),
            'submenu_border'       => array(
                'title'     => __( 'Border Settings', 'xpro-bb-addons' ),
                'collapsed'     => true,
                'fields'    => array(
                    'submenu_border' => array(
                        'type'       => 'border',
                        'label'         => __( 'Link Border', 'xpro-bb-addons' ),
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span',
                        ),
                    ),
                    'submenu_border_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Border Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => 'nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li:hover > span',
                            'property'      => 'border-color',
                        ),
                    ),
                ),
            ),
            'submenu_outer'       => array(
                'title'     => __( 'Outer Box', 'xpro-bb-addons' ),
                'collapsed'     => true,
                'fields'    => array(
                    'submenu_outer_bg_type' => array(
                        'type'          => 'button-group',
                        'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                        'default'       => 'color',
                        'options'       => array(
                            'color' 		=> __( 'Color', 'xpro-bb-addons' ),
                            'gradient'  	=> __( 'Gradient', 'xpro-bb-addons' ),
                            'photo'  		=> __( 'Photo', 'xpro-bb-addons' ),
                        ),
                        'toggle'        => array(
                            'color'   => array(
                                'fields' 	=> array( 'submenu_outer_bg_color' ),
                            ),
                            'gradient'   => array(
                                'fields' 	=> array( 'submenu_outer_bg_gradient' ),
                            ),
                            'photo'   => array(
                                'fields' 	=> array( 'submenu_outer_bg_photo', 'submenu_outer_bg_overlay', 'submenu_outer_bg_position', 'submenu_outer_bg_repeat', 'submenu_outer_bg_size' ),
                            ),
                        ),
                    ),
                    'submenu_outer_bg_color'	=> array(
                        'type'       => 'color',
                        'label'      => __( 'Background Color', 'xpro-bb-addons' ),
                        'show_reset' => true,
                        'show_alpha' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-horizontal-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-color,.tnit-advance-vertical-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-color',
                            'property'      => 'background-color'
                        ),
                    ),
                    'submenu_outer_bg_gradient' => array(
                        'type'    	=> 'gradient',
                        'label'   	=> __( 'Background Gradient', 'xpro-bb-addons' ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-horizontal-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-gradient,.tnit-advance-vertical-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-gradient',
                            'property'      => 'background-image'
                        ),
                    ),
                    'submenu_outer_bg_photo' => array(
                        'type'          => 'photo',
                        'label'         => __('Background Photo', 'xpro-bb-addons'),
                        'show_remove'   => false,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-horizontal-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-photo,.tnit-advance-vertical-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-photo',
                            'property'      => 'background-image'
                        ),
                    ),
                    'submenu_outer_bg_position'     => array(
                        'type'      => 'select',
                        'label'     => __('Background Position', 'xpro-bb-addons'),
                        'default'   => 'center center',
                        'options'   => array(
                            'left top'		=> __('Left Top', 'xpro-bb-addons'),
                            'left center'	=> __('Left Center', 'xpro-bb-addons'),
                            'left bottom'	=> __('Left Bottom', 'xpro-bb-addons'),
                            'center top'	=> __('Center Top', 'xpro-bb-addons'),
                            'center center'	=> __('Center Center', 'xpro-bb-addons'),
                            'center bottom'	=> __('Center Bottom', 'xpro-bb-addons'),
                            'right top'		=> __('Right Top', 'xpro-bb-addons'),
                            'right center'	=> __('Right Center', 'xpro-bb-addons'),
                            'right bottom'	=> __('Right Bottom', 'xpro-bb-addons'),
                        ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-horizontal-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-photo,.tnit-advance-vertical-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-photo',
                            'property'      => 'background-position'
                        ),
                    ),
                    'submenu_outer_bg_repeat'     => array(
                        'type'      => 'button-group',
                        'label'     => __('Background Repeat', 'xpro-bb-addons'),
                        'default'   => 'no-repeat',
                        'options'   => array(
                            'repeat'		=> __('Repeat', 'xpro-bb-addons'),
                            'no-repeat'		=> __('No-Repeat', 'xpro-bb-addons'),
                        ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-horizontal-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-photo,.tnit-advance-vertical-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-photo',
                            'property'      => 'background-repeat'
                        ),
                    ),
                    'submenu_outer_bg_size'     => array(
                        'type'      => 'button-group',
                        'label'     => __('Background Size', 'xpro-bb-addons'),
                        'default'   => 'cover',
                        'options'   => array(
                            'cover'			=> __('Cover', 'xpro-bb-addons'),
                            'contain'		=> __('Contain', 'xpro-bb-addons'),
                        ),
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-horizontal-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-photo,.tnit-advance-vertical-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu-bg-photo',
                            'property'      => 'background-size'
                        ),
                    ),
                    'submenu_outer_border' => array(
                        'type'       => 'border',
                        'label'         => __( 'Outer Border', 'xpro-bb-addons' ),
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-horizontal-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu,.tnit-advance-vertical-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'responsive'      => array(
        'title'         => __( 'Responsive', 'xpro-bb-addons' ),
        'sections'      => array(
            'general'       => array(
                'title'     => '',
                'fields'    => array(
                    'responsive_breakpoint' => array(
                        'type'          => 'select',
                        'label'         => __( 'Hamburger Show on', 'xpro-bb-addons' ),
                        'default'       => 'responsive',
                        'options'       => array(
                            'all'      => __( 'All Devices', 'xpro-bb-addons' ),
                            'medium'      => __( 'Tablet & Mobile', 'xpro-bb-addons' ),
                            'responsive'      => __( 'Mobile Only', 'xpro-bb-addons' ),
                        ),
                        'toggle'        => array(
                            'medium'   => array(
                                'sections' 	=> array( 'menu_general' ),
                                'tabs' 	=> array( 'menu','submenu' ),
                            ),
                            'responsive'   => array(
                                'sections' 	=> array( 'menu_general' ),
                                'tabs' 	=> array( 'menu','submenu' ),
                            ),
                        ),
                    ),
                    'responsive_layout' => array(
                        'type'          => 'select',
                        'label'         => __( 'Responsive Layout', 'xpro-bb-addons' ),
                        'default'       => 'accordion',
                        'options'       => array(
                            'accordion'      => __( 'Accordion', 'xpro-bb-addons' ),
                            'reveal-right'      => __( 'Reveal Right', 'xpro-bb-addons' ),
                            'reveal-left'      => __( 'Reveal Left', 'xpro-bb-addons' ),
                            'none'      => __( 'None', 'xpro-bb-addons' ),
                        ),
                        'toggle'        => array(
                            'reveal-right'   => array(
                                'fields' 	=> array('hamburger_width'),
                            ),
                            'reveal-left'   => array(
                                'fields' 	=> array('hamburger_width'),
                            ),
                        ),
                    ),
                    'responsive_preview'	=> array(
                        'type'			=> 'button',
                        'label'			=> __('Preview Layout', 'xpro-bb-addons'),
                        'class'			=> 'tnit-responsive-preview',
                    ),
                    'hamburger_width' => array(
                        'type'         	=> 'unit',
                        'label'        	=> __('Width','xpro-bb-addons'),
                        'placeholder'   => '350',
                        'units'	       	=> array('px'),
                        'slider' 		=> true,
                        'responsive' => true,
                    ),
                    'hamburger_outer_bg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Outer Backgroound', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_outer_padding' => array(
                        'type'        => 'dimension',
                        'label'       => __('Outer Padding','xpro-bb-addons'),
                        'units'       => array('px'),
                        'slider' 		=> true,
                        'responsive'  => 'true',
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand',
                            'property'      => 'padding',
                            'unit'          => 'px'
                        ),
                    ),
                    'hamburger_button_border' => array(
                        'type'       => 'border',
                        'label'      => __('Outer Border','xpro-bb-addons'),
                        'responsive' => 'true',
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand',
                        ),
                    ),
                ),
            ),
            'hamburger_button'       => array(
                'title'     => __('Hamburger Button','xpro-bb-addons'),
                'collapsed'     => true,
                'fields'    => array(
                    'hamburger_button_align' => array(
                        'type'    => 'align',
                        'label'   => 'Button Alignment',
                        'default' => 'right',
                    ),
                    'hamburger_button_text' => array(
                        'type'          => 'text',
                        'label'         => __( 'Label', 'xpro-bb-addons' ),
                        'default'       => 'Menu',
                        'placeholder'   => __( 'Menu', 'xpro-bb-addons' ),
                    ),
                    'hamburger_button_typo' => array(
                        'type'       => 'typography',
                        'label'      => 'Typography',
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-text',
                        ),
                    ),
                    'hamburger_button_icon' => array(
                        'type'          => 'icon',
                        'label'         => __( 'Icon Field', 'fl-builder' ),
                        'show_remove'   => true,
                        'default'       => 'fa fa-bars'
                    ),
                    'hamburger_button_icon_position'    => array(
                        'type'        	=> 'button-group',
                        'label'       	=> __( 'Icon Position', 'xpro-bb-addons' ),
                        'default' 		=> 'before',
                        'options' 		=> array(
                            'before'   		=> __( 'Before Text', 'xpro-bb-addons' ),
                            'after' 	 		=> __( 'After Text', 'xpro-bb-addons' ),
                        ),
                    ),
                    'hamburger_button_icon_size' => array(
                        'type'         => 'unit',
                        'label'        => 'Icon Size',
                        'units'          => array( 'px'),
                        'placeholder'   => __( '30', 'xpro-bb-addons' ),
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper > i',
                            'property'      => 'font-size',
                            'unit'          => 'px'
                        ),
                    ),
                    'hamburger_button_icon_space' => array(
                        'type'         => 'unit',
                        'label'        => 'Icon Space',
                        'units'          => array( 'px'),
                        'placeholder'   => __( '10', 'xpro-bb-addons' ),
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'rules'           => array(
                                array(
                                    'selector'      => '.tnit-advance-menu-toggle-icon-after',
                                    'property'      => 'margin-left',
                                    'unit'          => 'px'
                                ),
                                array(
                                    'selector'      => '.tnit-advance-menu-toggle-icon-before',
                                    'property'      => 'margin-right',
                                    'unit'          => 'px'
                                ),
                            ),
                        ),
                    ),
                    'hamburger_button_color' => array(
                        'type'          => 'color',
                        'label'         => __( 'Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper',
                            'property'      => 'color',
                        ),
                    ),
                    'hamburger_button_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Hover Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper:hover',
                            'property'      => 'color',
                        ),
                    ),
                    'hamburger_button_bg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_button_hbg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper:hover',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_button_border' => array(
                        'type'       => 'border',
                        'label'      => __('Border','xpro-bb-addons'),
                        'responsive' => 'true',
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper',
                        ),
                    ),
                    'hamburger_button_hborder' => array(
                        'type'          => 'color',
                        'label'         => __( 'Border Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper:hover',
                            'property'      => 'border-color',
                        ),
                    ),
                    'hamburger_button_padding' => array(
                        'type'        => 'dimension',
                        'label'       => __('Padding','xpro-bb-addons'),
                        'units'       => array('px'),
                        'responsive'  => 'true',
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper',
                            'property'      => 'padding',
                            'unit'          => 'px'
                        ),
                    ),
                    'hamburger_button_margin' => array(
                        'type'        => 'dimension',
                        'label'       => __('Margin','xpro-bb-addons'),
                        'units'       => array('px'),
                        'responsive'  => 'true',
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-toggle-wrapper',
                            'property'      => 'margin',
                            'unit'          => 'px'
                        ),
                    ),
                ),
            ),
            'hamburger_close'       => array(
                'title'         => __('Close Button', 'xpro-bb-addons'),
                'collapsed' 	=> true,
                'fields'        => array(
                    'hamburger_close_icon_size' => array(
                        'type'         	=> 'unit',
                        'label'        	=> __( 'Icon Size', 'xpro-bb-addons' ),
                        'units'	       	=> array( 'px' ),
                        'placeholder' 	=> '20',
                        'slider' 		=> true,
                    ),
                    'hamburger_close_icon_color' => array(
                        'type'          => 'color',
                        'label'         => __( 'Icon Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-close',
                            'property'      => 'color',
                        ),
                    ),
                    'hamburger_close_icon_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Icon Hover Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-close:hover',
                            'property'      => 'color',
                        ),
                    ),
                    'hamburger_close_btn_bg_color'  => array(
                        'type'          => 'color',
                        'label'         => __( 'Icon Background', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-close',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_close_btn_bg_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Icon Background Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-close:hover',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_close_btn_border'    => array(
                        'type'       => 'border',
                        'label'      => __('Button Border', 'xpro-bb-addons'),
                        'responsive' => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-close',
                        ),
                    ),
                    'hamburger_close_btn_bcolor'    => array(
                        'type'          => 'color',
                        'label'         => __( 'Button Border Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-close:hover',
                            'property'      => 'border-color',
                        ),
                    ),
                    'hamburger_button_margin' => array(
                        'type'        => 'dimension',
                        'label'       => __('Margin','xpro-bb-addons'),
                        'units'       => array('px'),
                        'responsive'  => 'true',
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-advance-menu-close',
                            'property'      => 'margin',
                            'unit'          => 'px'
                        ),
                    ),
                )
            ),
            'hamburger_menu'       => array(
                'title'     => __('Hamburger Menu','xpro-bb-addons'),
                'collapsed'     => true,
                'fields'    => array(
                    'hamburger_menu_align' => array(
                        'type'    => 'align',
                        'label'   => 'Menu Alignment',
                        'default' => 'left',
                    ),
                    'hamburger_menu_color' => array(
                        'type'          => 'color',
                        'label'         => __( 'Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li > span',
                            'property'      => 'color',
                        ),
                    ),
                    'hamburger_menu_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Hover Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li:hover > span',
                            'property'      => 'color',
                        ),
                    ),
                    'hamburger_menu_bg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li > span',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_menu_hbg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li:hover > span',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_menu_typography' => array(
                        'type'       => 'typography',
                        'label'      => 'Typography',
                        'responsive' => 'true',
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li > span',
                        ),
                    ),
                    'hamburger_menu_border' => array(
                        'type'       => 'border',
                        'label'         => __( 'Border', 'xpro-bb-addons' ),
                        'responsive' => 'true',
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li > span',
                        ),
                    ),
                    'hamburger_menu_hborder' => array(
                        'type'          => 'color',
                        'label'         => __( 'Border Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li > span',
                            'property'      => 'border-color',
                        ),
                    ),
                    'hamburger_menu_padding' => array(
                        'type'        => 'dimension',
                        'label'       => __('Padding','xpro-bb-addons'),
                        'units'       => array('px'),
                        'responsive'  => 'true',
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li > span',
                            'property'      => 'padding',
                            'unit'          => 'px'
                        ),
                    ),
                    'hamburger_menu_margin' => array(
                        'type'        => 'dimension',
                        'label'       => __('Margin','xpro-bb-addons'),
                        'units'       => array('px'),
                        'responsive'  => 'true',
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand > ul > li > span',
                            'property'      => 'margin',
                            'unit'          => 'px'
                        ),
                    ),
                ),
            ),
            'hamburger_submenu'       => array(
                'title'     => __('Hamburger Submenu','xpro-bb-addons'),
                'collapsed'     => true,
                'fields'    => array(
                    'hamburger_submenu_color' => array(
                        'type'          => 'color',
                        'label'         => __( 'Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span',
                            'property'      => 'color',
                        ),
                    ),
                    'hamburger_submenu_hcolor' => array(
                        'type'          => 'color',
                        'label'         => __( 'Hover Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li:hover > span',
                            'property'      => 'color',
                        ),
                    ),
                    'hamburger_submenu_bg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_submenu_hbg' => array(
                        'type'          => 'color',
                        'label'         => __( 'Background Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li:hover > span',
                            'property'      => 'background-color',
                        ),
                    ),
                    'hamburger_submenu_typography' => array(
                        'type'       => 'typography',
                        'label'      => 'Typography',
                        'responsive' => 'true',
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span',
                        ),
                    ),
                    'hamburger_submenu_border' => array(
                        'type'       => 'border',
                        'label'         => __( 'Border', 'xpro-bb-addons' ),
                        'responsive' => 'true',
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span',
                        ),
                    ),
                    'hamburger_submenu_hborder' => array(
                        'type'          => 'color',
                        'label'         => __( 'Border Hover', 'xpro-bb-addons' ),
                        'show_reset'    => true,
                        'show_alpha'    => true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li:hover > span',
                            'property'      => 'border-color',
                        ),
                    ),
                    'hamburger_submenu_padding' => array(
                        'type'        => 'dimension',
                        'label'       => __('Padding','xpro-bb-addons'),
                        'units'       => array('px'),
                        'responsive'  => 'true',
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span',
                            'property'      => 'padding',
                            'unit'          => 'px'
                        ),
                    ),
                    'hamburger_submenu_margin' => array(
                        'type'        => 'dimension',
                        'label'       => __('Margin','xpro-bb-addons'),
                        'units'       => array('px'),
                        'responsive'  => 'true',
                        'slider' 		=> true,
                        'preview'       => array(
                            'type'          => 'css',
                            'selector'      => '.tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span',
                            'property'      => 'margin',
                            'unit'          => 'px'
                        ),
                    ),
                ),
            ),
        ),
    ),
) );

FLBuilder::register_settings_form('menu_form', array(
    'title' => __('Add Menu Item', 'xpro-bb-addons'),
    'tabs'  => array(
        'general'      => array(
            'title'         => __( 'General', 'xpro-bb-addons' ),
            'sections'      => array(
                'general'       => array(
                    'title'         => '',
                    'fields'        => array(
                        'menu_item_type' => array(
                            'type'    => 'select',
                            'label'   => __( 'Menu Item Type', 'xpro-bb-addons' ),
                            'default' => 'page',
                            'options' => array(
                                'page' 			=> __( 'Page', 'xpro-bb-addons' ),
                                'post' 			=> __( 'Post', 'xpro-bb-addons' ),
                                'category' 		=> __( 'Category', 'xpro-bb-addons' ),
                                'custom-link' 	=> __( 'Custom Link', 'xpro-bb-addons' ),
                            ),
                            'toggle'  => array(
                                'page' => array(
                                    'fields' => array( 'menu_item_page', 'custom_label' ),
                                ),
                                'post' => array(
                                    'fields' => array( 'menu_item_post', 'custom_label' ),
                                ),
                                'category' => array(
                                    'fields' => array( 'menu_item_category', 'custom_label' ),
                                ),
                                'custom-link' => array(
                                    'fields' => array( 'custom_label', 'menu_item_custom_link' ),
                                ),
                            ),
                        ),
                        'menu_item_page' 		=> TNITAdvancedMenuModuleLite::_get_pages(),
                        'menu_item_post' 		=> TNITAdvancedMenuModuleLite::_get_posts(),
                        'menu_item_category' 	=> TNITAdvancedMenuModuleLite::_get_categories(),
                        'custom_label' => array(
                            'type'          => 'text',
                            'label'         => __( 'Custom Label', 'xpro-bb-addons' ),
                            'help' 		 	=> __( 'Navigation custom label', 'xpro-bb-addons' ),
                            'connections' 	=> array( 'string', 'html' ),
                        ),
                        'menu_item_custom_link' => array(
                            'type'          => 'link',
                            'label'         => __( 'URL', 'xpro-bb-addons' ),
                        ),
                    )
                ),
                'menu_advanced_properties'       => array(
                    'title'         => __('Advanced Properties', 'xpro-bb-addons'),
                    'fields'        => array(
                        'menu_link_target'   => array(
                            'type'          => 'button-group',
                            'label'         => __('Link Target', 'xpro-bb-addons'),
                            'default'       => '_self',
                            'options'       => array(
                                '_self'         => __('Same Window', 'xpro-bb-addons'),
                                '_blank'        => __('New Window', 'xpro-bb-addons'),
                            ),
                        ),
                        'link_nofollow'   => array(
                            'type'          => 'button-group',
                            'label'         => __('Link Nofollow', 'xpro-bb-addons'),
                            'default'       => '0',
                            'options'       => array(
                                '0' 	=> __('No', 'xpro-bb-addons'),
                                '1'     => __('Yes', 'xpro-bb-addons'),
                            ),
                        ),
                        'menu_title_attribute' => array(
                            'type'     => 'text',
                            'label'    => __( 'Title Attribute', 'xpro-bb-addons' ),
                        ),
                        'menu_classes' => array(
                            'type'     => 'text',
                            'label'    => __( 'CSS Classes', 'xpro-bb-addons' ),
                        ),
                    )
                ),
            ),
        ),
        'icon-image' 	=> array(
            'title' 	  => __('Icon / Image', 'xpro-bb-addons'),
            'sections'    => array(
                'icon_image' 	=> array(
                    'title'  	=> '',
                    'fields' 	=> array(
                        'image_type'    => array(
                            'type'        	=> 'button-group',
                            'label'       	=> __( 'Image Type', 'xpro-bb-addons' ),
                            'default' 		=> 'none',
                            'options' 		=> array(
                                'none'   		=> __( 'None', 'xpro-bb-addons' ),
                                'icon' 	 		=> __( 'Icon', 'xpro-bb-addons' ),
                                'photo'  		=> __( 'Photo', 'xpro-bb-addons' ),
                            ),
                            'toggle'  => array(
                                'icon'  => array(
                                    'sections' 	=> array( 'icon_basic'),
                                ),
                                'photo' => array(
                                    'sections' 	=> array( 'img_basic'),
                                ),
                            ),
                        ),
                    ),
                ),
                'icon_basic' 	=> array(
                    'title'  	=> __( 'Icon Basics', 'xpro-bb-addons' ),
                    'fields' 	=> array(
                        'icon'    => array(
                            'type'        	=> 'icon',
                            'label'       	=> __( 'Choose Icon', 'xpro-bb-addons' ),
                            'default' 		=> 'fas fa-globe',
                            'show_remove'   => true,
                        ),
                    ),
                ),
                'img_basic' 	=> array(
                    'title'  	=> __( 'Image Basic', 'xpro-bb-addons' ),
                    'fields' 	=> array(
                        'photo'    => array(
                            'type'        	=> 'photo',
                            'label'       	=> __( 'Photo', 'xpro-bb-addons' ),
                            'show_remove'   => true,
                        ),
                    ),
                ),
            ),
        ),
        'submenu'      => array(
            'title'         => __('Submenu', 'xpro-bb-addons'),
            'sections'      => array(
                'submenu'       => array(
                    'title'         => '',
                    'fields'        => array(
                        'add_submenu' => array(
                            'type'    => 'button-group',
                            'label'   => __( 'Submenu', 'xpro-bb-addons' ),
                            'default' => '0',
                            'options' => array(
                                '0' 	=> __( 'No', 'xpro-bb-addons' ),
                                '1' 	=> __( 'Yes', 'xpro-bb-addons' ),
                            ),
                            'toggle'  => array(
                                '1' => array(
                                    'fields' => array( 'submenu_items' ),
                                ),
                            ),
                        ),
                        'submenu_items' => array(
                            'type'          => 'form',
                            'label'         => __('Submenu Item', 'xpro-bb-addons'),
                            'form'          => 'submenu_form',
                            'preview_text'  => 'custom_label',
                            'multiple'  	=> true,
                        ),
                    ),
                ),
            ),
        ),
    )
));

FLBuilder::register_settings_form('submenu_form', array(
    'title' => __('Add Submenu Item', 'xpro-bb-addons'),
    'tabs'  => array(
        'general'      => array(
            'title'         => __('General', 'xpro-bb-addons'),
            'sections'      => array(
                'general'       => array(
                    'title'         => __('General', 'xpro-bb-addons'),
                    'fields'        => array(
                        'submenu_item_type' => array(
                            'type'    => 'select',
                            'label'   => __( 'Submenu Item Type', 'xpro-bb-addons' ),
                            'default' => 'page',
                            'options' => array(
                                'page' 			=> __( 'Page', 'xpro-bb-addons' ),
                                'post' 			=> __( 'Post', 'xpro-bb-addons' ),
                                'category' 		=> __( 'Category', 'xpro-bb-addons' ),
                                'custom-link' 	=> __( 'Custom Link', 'xpro-bb-addons' ),
                            ),
                            'toggle'  => array(
                                'page' => array(
                                    'fields' => array( 'submenu_item_page', 'custom_label' ),
                                ),
                                'post' => array(
                                    'fields' => array( 'submenu_item_post', 'custom_label' ),
                                ),
                                'category' => array(
                                    'fields' => array( 'submenu_item_category', 'custom_label' ),
                                ),
                                'custom-link' => array(
                                    'fields' => array( 'custom_label', 'submenu_item_custom_link' ),
                                ),
                            ),
                        ),
                        'submenu_item_page' 	=> TNITAdvancedMenuModuleLite::_get_pages(),
                        'submenu_item_post' 	=> TNITAdvancedMenuModuleLite::_get_posts(),
                        'submenu_item_category' => TNITAdvancedMenuModuleLite::_get_categories(),
                        'custom_label' => array(
                            'type'          => 'text',
                            'label'         => __( 'Custom Label', 'xpro-bb-addons' ),
                            'help' 		 	=> __( 'Navigation custom label', 'xpro-bb-addons' ),
                            'connections' 	=> array( 'string', 'html' ),
                        ),
                        'submenu_item_custom_link' => array(
                            'type'          => 'link',
                            'label'         => __( 'URL', 'xpro-bb-addons' ),
                        ),
                    )
                ),
                'submenu_advanced_properties'       => array(
                    'title'         => __('Advanced Properties', 'xpro-bb-addons'),
                    'fields'        => array(
                        'submenu_link_target'   => array(
                            'type'          => 'button-group',
                            'label'         => __('Link Target', 'xpro-bb-addons'),
                            'default'       => '_self',
                            'options'       => array(
                                '_self'         => __('Same Window', 'xpro-bb-addons'),
                                '_blank'        => __('New Window', 'xpro-bb-addons'),
                            ),
                        ),
                        'link_nofollow'   => array(
                            'type'          => 'button-group',
                            'label'         => __('Link Nofollow', 'xpro-bb-addons'),
                            'default'       => '0',
                            'options'       => array(
                                '0' 	=> __('No', 'xpro-bb-addons'),
                                '1'     => __('Yes', 'xpro-bb-addons'),
                            ),
                        ),
                        'submenu_title_attribute' => array(
                            'type'     => 'text',
                            'label'    => __( 'Title Attribute', 'xpro-bb-addons' ),
                        ),
                        'submenu_classes' => array(
                            'type'     => 'text',
                            'label'    => __( 'CSS Classes', 'xpro-bb-addons' ),
                        ),
                    )
                ),
            )
        ),
        'icon-image' 	=> array(
            'title' 	  => __('Icon / Image', 'xpro-bb-addons'),
            'sections'    => array(
                'icon_image' 	=> array(
                    'title'  	=> '',
                    'fields' 	=> array(
                        'image_type'    => array(
                            'type'        	=> 'button-group',
                            'label'       	=> __( 'Image Type', 'xpro-bb-addons' ),
                            'default' 		=> 'none',
                            'options' 		=> array(
                                'none'   		=> __( 'None', 'xpro-bb-addons' ),
                                'icon' 	 		=> __( 'Icon', 'xpro-bb-addons' ),
                                'photo'  		=> __( 'Photo', 'xpro-bb-addons' ),
                            ),
                            'toggle'  => array(
                                'icon'  => array(
                                    'sections' 	=> array( 'icon_basic'),
                                ),
                                'photo' => array(
                                    'sections' 	=> array( 'img_basic'),
                                ),
                            ),
                        ),
                    ),
                ),
                'icon_basic' 	=> array(
                    'title'  	=> __( 'Icon Basics', 'xpro-bb-addons' ),
                    'fields' 	=> array(
                        'icon'    => array(
                            'type'        	=> 'icon',
                            'label'       	=> __( 'Choose Icon', 'xpro-bb-addons' ),
                            'default' 		=> 'fas fa-globe',
                            'show_remove'   => true,
                        ),
                    ),
                ),
                'img_basic' 	=> array(
                    'title'  	=> __( 'Image Basic', 'xpro-bb-addons' ),
                    'fields' 	=> array(
                        'photo'    => array(
                            'type'        	=> 'photo',
                            'label'       	=> __( 'Photo', 'xpro-bb-addons' ),
                            'show_remove'   => true,
                        ),
                    ),
                ),
            ),
        ),
    )
));
