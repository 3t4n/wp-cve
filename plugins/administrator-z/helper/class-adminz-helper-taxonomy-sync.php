<?php
/**
Tự tạo và đồng bộ dữ liệu giữa post type và term taxonomy
Setup khác:
    Tool->adminz-> bật thumbnail support cho taxonomy của bạn.
*/


namespace Adminz\Helper;
class ADMINZ_Helper_Taxonomy_Sync {
	public $taxname = "";
    public $post_type = '';
    public $get_post_type_object = '';
    public $term_meta_key = '';
    public $term_metakey_thumbnail = 'thumbnail_id';

    function __construct() {

    }

    function init() {
        // Chỉ chạy sau khi init để lấy post type registered từ chỗ khác
        add_action( 'init', function () {
            if ( !$this->taxname ) {
                return;
            }

            if ( !$this->post_type ) {
                return;
            }

            $this->get_post_type_object = $this->get_post_type_object();
            if ( !$this->get_post_type_object ) {
                return;
            }

            $this->term_meta_key = $this->get_term_meta_key();

            // For single post actions
            add_action( 'save_post_' . $this->post_type, [ $this, 'update_term_by_post_type' ], 10, 1 );
            add_action( 'trashed_post', [ $this, 'delete_term_by_post_type' ], 10, 1 );

            // for term actions
            add_action( 'edited_' . $this->taxname, [ $this, 'update_post_type_by_term' ], 10, 2 );
            add_action( 'pre_delete_term', [ $this, 'delete_post_type_by_term' ], 10, 2 );

            // Admin columns
            $this->add_admin_column_terms();
            $this->add_admin_column_posts();

            // acf term
            $this->add_acf_fields();

        } );
    }

    function get_post_type_object() {
        return get_post_type_object( $this->post_type );
    }

    function get_term_meta_key(){
        return $this->post_type . "_post_id";
    }

    function update_term_by_post_type( $post_id ) {
        remove_action( 'edited_' . $this->taxname, [ $this, 'update_post_type_by_term' ], 10 );
        remove_action( 'pre_delete_term', [ $this, 'delete_post_type_by_term' ], 10 );
        // if ( !empty( get_post_meta( $post_id, 'check_if_run_once' ) ) ){
        //  return;
        // }

        $post = get_post( $post_id );
        // search by old slug
        $term = $this->get_terms( $post_id );

        if ( $post->post_status == 'publish' ) {
            if ( $term ) {
                $termid = $term->term_id;
                // update
                $term_return = wp_update_term(
                    $termid,   // the term 
                    $this->taxname, // the taxonomy
                    array(
                        'name'        => $post->post_title,
                        'description' => $post->post_excerpt,
                        'slug'        => sanitize_title( $post->post_title ),
                    )
                );
                // auto renew slug
                remove_action( 'save_post_' . $this->post_type, [ $this, 'update_term_by_post_type' ], 10 );
                wp_update_post( array(
                    'ID'        => $post_id,
                    'post_name' => sanitize_title( $post->post_title ),
                ) );
                add_action( 'save_post_' . $this->post_type, [ $this, 'update_term_by_post_type' ], 10, 1 );
            } else {
                
                // create
                $term_return = wp_insert_term(
                    $post->post_title,   // the term 
                    $this->taxname, // the taxonomy
                    array(
                        'description' => $post->post_excerpt,
                        'slug'        => sanitize_title( $post->post_name ),
                    )
                );
                if ( is_wp_error( $term_return ) ) {
                    if ( isset( $term_return->error_data['term_exists'] ) ) {
                        $termid = $term_return->error_data['term_exists'];
                    }
                } else {
                    $termid = $term_return['term_taxonomy_id'];
                    update_term_meta( $termid, $this->term_meta_key, $post_id );
                }
            }

            // update parent            
            if( $parent_post_id = $post->post_parent){      
                if($parent_term = $this->get_term($parent_post_id)){
                    if($parent_term_id = $parent_term->term_id){
                        wp_update_term( $termid, $this->taxname, [ 'parent' => $parent_term_id ] );
                    }
                }
                
            }

            // update meta keys
            // Có thể phải save 2 lần
            update_term_meta( $termid, $this->term_metakey_thumbnail, get_post_thumbnail_id($post_id) );
            // update_term_meta( $termid, $this->taxname, get_the_ID() );

        } else {
            if ( $term ) {
                // delete
                remove_action( 'pre_delete_term', [ $this, 'delete_post_type_by_term' ], 10 );
                wp_delete_term( $term->term_id, $this->taxname );
                add_action( 'pre_delete_term', [ $this, 'delete_post_type_by_term' ], 10, 2 );
            }
        }
        add_action( 'edited_' . $this->taxname, [ $this, 'update_post_type_by_term' ], 10, 2 );
        add_action( 'pre_delete_term', [ $this, 'delete_post_type_by_term' ], 10, 2 );
    }

    function delete_term_by_post_type( $post_id ) {
        // if ( !empty( get_post_meta( $post_id, 'check_if_run_once' ) ) ) {
        //  return;
        // }

        // search by old slug
        $post = get_post( $post_id );
        if ( $post->post_type !== $this->post_type ) {
            return;
        }

        if ( $term = $this->get_terms( $post_id ) ) {
            $term_id = $termid = $term->term_id;
            wp_delete_term( $termid, $this->taxname );
        }
    }

    function update_post_type_by_term( $termid, $taxonomy ) {
        $termobj = get_term( $termid );

        // update term slug
        remove_action( 'edited_' . $this->taxname, [ $this, 'update_post_type_by_term' ], 10 );
        wp_update_term(
            $termobj->term_id,
            $this->taxname,
            array(
                'slug' => sanitize_title( $termobj->name ),
            )
        );

        add_action( 'edited_' . $this->taxname, [ $this, 'update_post_type_by_term' ], 10, 2 );

        // update featured: name, thumbnail
        $featured_id = get_term_meta( $termobj->term_id, $this->taxname, true );

        remove_action( 'save_post_' . $this->post_type, [ $this, 'update_term_by_post_type' ], 10 );
        wp_update_post(
            [ 
                'ID'           => $featured_id,
                'post_title'   => $termobj->name,
                'post_name'    => sanitize_title( $termobj->name ),
                'post_excerpt' => $termobj->description,
            ]
        );
        $term_thumbnail_id = get_term_meta( $termobj->term_id, $this->term_metakey_thumbnail, true );
        set_post_thumbnail( $featured_id, $term_thumbnail_id );
        add_action( 'save_post_' . $this->post_type, [ $this, 'update_term_by_post_type' ], 10, 1 );
    }

    function delete_post_type_by_term( $termid, $taxonomy ) {
        if ( $taxonomy !== $this->taxname ){
            return;
        }

        $featured_id = get_term_meta( $termid, $this->taxname, true );
        wp_trash_post( $featured_id );
    }

    function add_admin_column_terms() {

        add_filter( 'manage_edit-' . $this->post_type . '_tax_columns', function ($columns) {
            $new_columns           = array ( $this->term_meta_key => $this->get_post_type_object()->labels->name );
            $index_to_insert       = 2; // Vị trí thứ 2
            $columns_before_insert = array_slice( $columns, 0, $index_to_insert );
            $columns_after_insert  = array_slice( $columns, $index_to_insert );

            // Kết hợp mảng trước chèn, mảng mới và mảng sau chèn để tạo mảng $columns mới
            $columns = array_merge( $columns_before_insert, $new_columns, $columns_after_insert );
            return $columns;

        } );

        add_filter( 'manage_' . $this->post_type . '_tax_custom_column', function ($content, $column_name, $term_id) {
            if ( $column_name === $this->term_meta_key ) {
                // Hiển thị nội dung tùy chỉnh cho cột mới ở đây
                $post_id = ( get_term_meta( $term_id, $this->term_meta_key, true ) );
                if ( $post_id ) {
                    ?>
                    <a target="blank" href="<?php echo get_edit_post_link( $post_id ) ?>">
                        <?php echo get_the_title( $post_id ); ?>
                    </a>
                    <?php
                } else {
                    ?>
                    <strong style="color: darkred;">
                        Please set a
                        <?php echo $this->get_post_type_object()->labels->name; ?> to sync
                    </strong>
                    <?php
                }
            }
            return $content;
        }, 10, 3 );

    }

    function add_admin_column_posts() {
    }

    function add_acf_fields() {
        if(!function_exists('acf_add_local_field_group')){
            return;
        }

        acf_add_local_field_group( array(
            'key'                   => 'group_64d0bd9e8d4cc',
            'title'                 => $this->get_post_type_object()->labels->name . ' taxonomy',
            'fields'                => array(
                array(
                    'key'               => 'field_64d0bd9eaab96',
                    'label'             => 'Choose ' . $this->get_post_type_object()->labels->name,
                    'name'              => $this->term_meta_key,
                    'aria-label'        => '',
                    'type'              => 'post_object',
                    'instructions'      => '',
                    'required'          => 0,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'post_type'         => array(
                        0 => 'food',
                    ),
                    'post_status'       => '',
                    'taxonomy'          => '',
                    'return_format'     => 'id',
                    'multiple'          => 0,
                    'allow_null'        => 0,
                    'ui'                => 1,
                ),
            ),
            'location'              => array(
                array(
                    array(
                        'param'    => 'taxonomy',
                        'operator' => '==',
                        'value'    => 'food_tax',
                    ),
                ),
            ),
            'menu_order'            => 0,
            'position'              => 'normal',
            'style'                 => 'default',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen'        => '',
            'active'                => true,
            'description'           => '',
            'show_in_rest'          => 0,
        ) );
    }


    // helper
    // lấy term tương ứng theo post id
    // trả về số ít. 
    // trả về sai nếu ko tìm thấy
    function get_terms( $post_id ) {
        $this->term_meta_key = $this->get_term_meta_key();
        $args   = [ 
            'taxonomy'       => $this->taxname,
            'hide_empty'     => false,
            'posts_per_page' => 1,
            'meta_query'     => [ 
                [ 
                    'key'     => $this->term_meta_key,
                    'value'   => $post_id,
                    'compare' => '=',
                ],
            ],
        ];
        $return = get_terms( $args );

        if ( is_wp_error( $return ) ) {
            return false;
        }

        if ( isset( $return[0] ) ) {
            return $return[0];
        }
        return false;
    }

    function get_term( $post_id ) {
        return $this->get_terms( $post_id );
    }

    function get_term_sync( $post_id ) {
        return $this->get_terms( $post_id );
    }

    function get_post($term_object){
        $term_id = $term_object->term_id;
        $post_id = ( get_term_meta( $term_id, $this->term_meta_key, true ) );
        return $post_id;
    }
	
	
}