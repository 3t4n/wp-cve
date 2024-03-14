<?php

class EIC_Shortcode_Button {

    public function __construct()
    {
        add_action( 'media_buttons',  array( $this, 'add_shortcode_button' ) );
        add_action( 'admin_footer',  array( $this, 'add_modal_content' ) );
    }

    public function add_shortcode_button( $editor_id )
    {
        $screen = get_current_screen();

        if( $screen->base == 'post' ) {
            $title = __( 'Add Image Collage', 'easy-image-collage' );

            echo '<a href="#" id="eic-button" class="button" data-editor="content" title="' . $title . '">' . $title . '</a>';
        }
    }

    public function add_modal_content()
    {
        $screen = get_current_screen();

        if( $screen->base == 'post' ) {
            $post = get_post();
            $grid_ids = $this->get_grids_in_content( $post->post_content );

            $grids = array();
            $grid_custom_layouts = array();

            foreach( $grid_ids as $grid_id ) {
                $grid = new EIC_Grid( $grid_id );
                $grids[$grid_id] = $grid->get_data();

                if( $grid->layout() ) {
                    $grid_custom_layouts[ $grid->layout_name() ] = $grid->layout();
                }
            }

            include( EasyImageCollage::get()->coreDir . '/helpers/modal.php' );

            wp_localize_script( 'eic_admin', 'eic_admin_grids', $grids );
            wp_localize_script( 'eic_admin', 'eic_default_grid', array(
                'id' => 0,
                'layout' => 'square',
                'images' => array(),
                'properties' => array(
                    'align' => EasyImageCollage::option( 'default_style_grid_align', 'center' ),
                    'width' => intval( EasyImageCollage::option( 'default_style_grid_width', 500 ) ),
                    'ratio' => floatval( EasyImageCollage::option( 'default_style_grid_ratio', 1 ) ),
                    'borderWidth' => intval( EasyImageCollage::option( 'default_style_border_width', 4 ) ),
                    'borderColor' => EasyImageCollage::option( 'default_style_border_color', '#444444' ),
                ),
            ) );
        }
    }

    public function get_grids_in_content( $content )
    {
        preg_match_all("/\[easy-image-collage([^\]]*)/i", $content, $shortcodes);

        $grid_ids = array();
        foreach( $shortcodes[1] as $shortcode_options )
        {
            preg_match("/id=\"?'?(\d+)/i", $shortcode_options, $id);

            $grid_ids[] = intval( $id[1] );
        }

        return $grid_ids;
    }
}