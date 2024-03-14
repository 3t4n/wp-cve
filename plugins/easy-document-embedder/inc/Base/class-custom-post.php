<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Base;

class EDECustomPost
{
    public function ede_register()
    {
        add_action( 'init', array($this,'createCustomPostType') );
        add_filter( 'manage_ede_embedder_posts_columns', array( $this,'ede_columns' ) );
        add_action('manage_ede_embedder_posts_custom_column', array($this,'ede_columns_content'), 10, 2);
    }

    /**
     * create custom post type
     * @method createCustomPostType
     * @param null
     */
    public function createCustomPostType()
    {
        register_post_type( 'ede_embedder', array(
            'labels'    => array(
                'name'                =>  'Easy Embedder',
                'singular_name'       =>  'Easy Embedder',
                'menu_name'           => __( 'Easy Embedder', 'pdfdoc-embedder' ),
                'name_admin_bar'      => __( 'Easy Embedder', 'pdfdoc-embedder' ),
                'parent_item_colon'   => __( 'Parent Document:', 'pdfdoc-embedder' ),
                'all_items'           => __( 'Dashboard', 'pdfdoc-embedder' ),
                'add_new_item'        => __( 'Add New Document', 'pdfdoc-embedder' ),
                'add_new'             => __( 'Add New', 'pdfdoc-embedder' ),
                'new_item'            => __( 'New Document', 'pdfdoc-embedder' ),
                'edit_item'           => __( 'Edit Document', 'pdfdoc-embedder' ),
                'update_item'         => __( 'Update Document', 'pdfdoc-embedder' ),
                'view_item'           => __( 'View Document', 'pdfdoc-embedder' ),
                'search_items'        => __( 'Search Document', 'pdfdoc-embedder' ),
                'not_found'           => __( 'Not found', 'pdfdoc-embedder' ),
                'not_found_in_trash'  => __( 'Not found in Trash', 'pdfdoc-embedder' ),
            ),
            'public'    =>  true,
            'has_archive'   =>  true,
            'menu_icon'  =>  'dashicons-media-document',
            'supports'   => array( 'title'),
            'show_in_rest'  => true,
        ) );
    }

    public function ede_columns($columns)
    {
        $newColumns = array();
        $newColumns['title']    = 'Document Title';
        $newColumns['format']  =   'Format';
        $newColumns['shortcode']    =   'Shortcode';
        $newColumns['date'] = 'Date';
        return $newColumns;
    }

    public function ede_columns_content($column, $post_id)
    {
        switch ($column) {
            case 'format':
                if (get_post_meta( $post_id, 'ede_select_type', true )) {
                    $format = get_post_meta( $post_id, 'ede_select_type', true );
                }
                echo $format;
                break;
            case 'shortcode':
                $shortcode = '<code>[EDE id="'.$post_id.'"]</code>';
                echo $shortcode;
                break;
            default:
                echo "";
                break;
        }
    }
}