<?php

/**
 * Fired when admin page will loaded.
 *
 * This class executes when plugin admin page interface executes.
 *
 * @since      1.0.0
 * @package    Multidots Advance Menu Manager
 * @subpackage advance-menu-manager/includes/classes
 * @author     Multidots Solutions Pvt. Ltd. <info@multidots.com>
 */
require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
$admin_interface = new DSAMM_Admin_Interface();
$admin_interface->dsamm_menu_container_print();
// Load all the nav menu interface functions
function dsamm_accordion_sections_own( $screen, $context, $object )
{
    include plugin_dir_path( __FILE__ ) . 'include/popup_add_menu_content.php';
}

/**
 * 
 */
function wpml_wp_get_nav_menus()
{
    $allMenus = wp_get_nav_menus();
    $menusCurrentLang = [];
    if ( !empty($allMenus) ) {
        foreach ( $allMenus as $menu ) {
            $translatedMenuID = apply_filters(
                'wpml_object_id',
                $menu->term_id,
                'nav_menu',
                false
            );
            if ( $translatedMenuID === $menu->term_id ) {
                $menusCurrentLang[$translatedMenuID] = $menu;
            }
        }
    }
    return array_values( $menusCurrentLang );
}

/**
 * Displays a metabox for the custom links menu item.
 *
 * @since 3.0.0
 *
 * @global int        $_nav_menu_placeholder
 * @global int|string $nav_menu_selected_id
 */
function dsamm_nav_menu_item_link_meta_box_own()
{
    global  $_nav_menu_placeholder, $nav_menu_selected_id ;
    $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1 );
    // phpcs:ignore
    ?>
    <input type="hidden" name="menu" id="nav-menu-meta-object-id" value="<?php 
    echo  esc_attr( $nav_menu_selected_id ) ;
    ?>" />
    <div class="customlinkdiv" id="customlinkdiv">
        <input type="hidden" value="custom" name="menu-item[<?php 
    echo  esc_attr( $_nav_menu_placeholder ) ;
    ?>][menu-item-type]" />
        <p id="menu-item-url-wrap">
            <label class="howto" for="custom-menu-item-url">
                <span><?php 
    esc_html_e( 'URL', 'advance-menu-manager' );
    ?></span>
                <input id="custom-menu-item-url" name="menu-item[<?php 
    echo  esc_attr( $_nav_menu_placeholder ) ;
    ?>][menu-item-url]" type="text" class="code menu-item-textbox" value="http://" />
            </label>
        </p>
        <p id="menu-item-name-wrap">
            <label class="howto" for="custom-menu-item-name">
                <span><?php 
    esc_html_e( 'Link Text', 'advance-menu-manager' );
    ?></span>
                <input id="custom-menu-item-name" name="menu-item[<?php 
    echo  esc_attr( $_nav_menu_placeholder ) ;
    ?>][menu-item-title]" type="text" class="regular-text menu-item-textbox input-with-default-title" title="<?php 
    esc_attr_e( 'Menu Item', 'advance-menu-manager' );
    ?>" />
            </label>
        </p>

        <p class="button-controls">
            <span class="add-to-menu">
                <input type="submit"<?php 
    wp_nav_menu_disabled_check( $nav_menu_selected_id );
    ?> class="button-secondary submit-add-to-menu right" value="<?php 
    esc_attr_e( 'Add to Menu', 'advance-menu-manager' );
    ?>" name="add-custom-menu-item" id="submit-customlinkdiv" />
                <span class="spinner"></span>
            </span>
        </p>
    </div><!-- /.customlinkdiv -->
    <?php 
}

/**
 * Displays a metabox for a post type menu item.
 *
 * @since 3.0.0
 *
 * @global int        $_nav_menu_placeholder
 * @global int|string $nav_menu_selected_id
 *
 * @param string $object Not used.
 * @param string $post_type The post type object.
 */
function dsamm_nav_menu_item_post_type_meta_box_own( $object, $post_type )
{
    $admin_interface = new DSAMM_Admin_Interface();
    global  $_nav_menu_placeholder, $nav_menu_selected_id ;
    global  $gloable_all_author_array ;
    global  $gloable_all_template_array ;
    global  $gloable_all_category_array ;
    $post_type_name = $post_type['args']->name;
    // Paginate browsing for large numbers of post objects.
    //post per page dynamic
    $post_per_page = get_option( 'amm_' . $post_type_name );
    if ( empty($post_per_page) ) {
        $post_per_page = get_option( 'amm_post_perpage_default' );
    }
    if ( empty($post_per_page) ) {
        $post_per_page = '50';
    }
    $per_page = (int) $post_per_page;
    $pagenum = ( isset( $_REQUEST[$post_type_name . '-tab'] ) && isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1 );
    // phpcs:ignore
    $offset = ( 0 < $pagenum ? $per_page * ($pagenum - 1) : 0 );
    $args = array(
        'offset'                 => $offset,
        'order'                  => 'ASC',
        'orderby'                => 'title',
        'posts_per_page'         => $per_page,
        'post_type'              => $post_type_name,
        'suppress_filters'       => false,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
    );
    if ( isset( $post_type['args']->_default_query ) ) {
        $args = array_merge( $args, (array) $post_type['args']->_default_query );
    }
    // @todo transient caching of these results with proper invalidation on updating of a post of this type
    $get_posts = new WP_Query();
    $posts = $get_posts->query( $args );
    $get_posts_for_count = new WP_Query( $args );
    $total_page_count = $get_posts_for_count->found_posts;
    $db_fields = false;
    if ( is_post_type_hierarchical( $post_type_name ) ) {
        $db_fields = array(
            'parent' => 'post_parent',
            'id'     => 'ID',
        );
    }
    $walker = new DSAMM_Walker_Nav_Menu_Checklist( $db_fields );
    $current_tab = 'all';
    
    if ( isset( $_REQUEST[$post_type_name . '-tab'] ) && in_array( $_REQUEST[$post_type_name . '-tab'], array( 'all', 'search' ) ) ) {
        // phpcs:ignore
        $current_tab = sanitize_text_field( $_REQUEST[$post_type_name . '-tab'] );
        // phpcs:ignore
    }
    
    $removed_args = array(
        'action',
        'customlink-tab',
        'edit-menu-item',
        'menu-item',
        'page-tab',
        '_wpnonce'
    );
    $editor_id = 'amm_post_' . $post_type_name;
    ?>
    <input type="hidden" name="menu" id="nav-menu-meta-object-id" value="<?php 
    echo  esc_attr( $nav_menu_selected_id ) ;
    ?>" />
    <div id="posttype-<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>" class="posttypediv amm_item_main_wrapper" amm_editor_selector="<?php 
    echo  esc_attr( $editor_id ) ;
    ?>">
        <?php 
    // add new page/post html
    ?>
        <div class="add_mew_item_wrapper amm_deactive">
            <div class="add_item_inner_main">
                <div class="amm_title_header_wrapper"><strong><?php 
    esc_html_e( 'Add new', 'advance-menu-manager' );
    ?> <?php 
    echo  esc_html( $post_type_name ) ;
    ?></strong> </div>
                <div class="add_item_row_wrapper">
                    <div class="add_item_details_left">
                        <div class="row">
                            <input type="hidden" class="add_item_post_type" value="<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>">
                            <span class="amm_item_title_add"><?php 
    esc_html_e( 'Title', 'advance-menu-manager' );
    ?></span>
                            <input type="text" name="post_title" class="amm_item_title" value="">
                        </div>
                        <div class="row">
                            <span class="amm_item_title_add"><?php 
    esc_html_e( 'Slug', 'advance-menu-manager' );
    ?></span>
                            <input type="text" name="post_name" class="amm_item_slug" value="">
                        </div>
                    </div>
                    <div class="add_item_details_left">
                        <div class="row padding_left">
                            <span class="amm_item_title_add"><?php 
    esc_html_e( 'Author', 'advance-menu-manager' );
    ?></span>
                            <select name="post_author" class="amm_select amm_item_author" >
                                <?php 
    foreach ( $gloable_all_author_array as $data_post_author ) {
        echo  '<option value="' . esc_attr( $data_post_author->data->ID ) . '">' . esc_html( $data_post_author->data->display_name ) . '</option>' ;
    }
    ?>
                            </select>
                        </div>
                        <?php 
    
    if ( 'page' === $post_type_name ) {
        ?>
                            <div class="row padding_left">
                                <span class="amm_item_title_add"><?php 
        esc_html_e( 'Template', 'advance-menu-manager' );
        ?></span>
                                <select name="page_template" class="amm_select amm_item_template">
                                    <option value="default"><?php 
        esc_html_e( 'Default Template', 'advance-menu-manager' );
        ?></option>
                                    <?php 
        foreach ( $gloable_all_template_array as $template_name => $template_filename ) {
            echo  '<option value="' . esc_attr( $template_filename ) . '">' . esc_html( $template_name ) . '</option>' ;
        }
        ?>
                                </select>
                            </div>
                        <?php 
    } else {
        
        if ( 'post' === $post_type_name ) {
            ?>
                            <div class="row padding_left">
                                <span class="amm_item_title_add category-select-checkbox"><?php 
            esc_html_e( 'Category', 'advance-menu-manager' );
            ?></span>
                                <ul class="amm_select category-select-box">
                                    <?php 
            foreach ( $gloable_all_category_array as $cate_id => $data_cat ) {
                echo  '<li><input type="checkbox" class="set_new_category" name="amm_item_category[]" value="' . esc_attr( $cate_id ) . '" > ' . esc_html( $data_cat ) . '</li>' ;
            }
            ?>
                                </ul>
                            </div>
                        <?php 
        }
    
    }
    
    ?>
                    </div>
                </div>
                <div class="amm_item_content_wrapper">
                    <span class="amm_item_title"><?php 
    esc_html_e( 'Content', 'advance-menu-manager' );
    ?></span><br>
                    <?php 
    $text_editor_buttons = array(
        "buttons" => 'strong,em,italic,b-quote,img,close,del,ins,ul,li,ol,code,spell,more,fullscreen',
    );
    $tinymce_buttons = array(
        'toolbar1' => 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,wp_more,spellchecker,dfw,',
        'toolbar2' => "formatselect,underline,alignjustify,forecolor,removeformat,outdent,indent",
        'toolbar3' => "",
        'toolbar4' => "",
    );
    $settings = array(
        'textarea_name' => 'amm_post_content_add',
        'media_buttons' => false,
        'editor_class'  => 'amm-post-page-content',
        'tinymce'       => $tinymce_buttons,
        'quicktags'     => false,
        'textarea_rows' => 20,
    );
    wp_editor( '', $editor_id, $settings );
    ?>
                </div>
                <div class="add_item_submit_row_wrapper">
                    <button type="button" class="button-secondary amm_menu_add_cancel"><?php 
    esc_html_e( 'Cancel', 'advance-menu-manager' );
    ?></button>
                    <button type="button" class="button-primary amm_submit_post_for_add_item amm_page_edit"><?php 
    echo  esc_html__( 'Add new', 'advance-menu-manager' ) . ' ' . esc_html( $post_type_name ) ;
    ?></button>
                </div>
            </div>
        </div><?php 
    // end new page/post html
    ?>
        <div class="amm_header_main">
            <div class="new_item_add_wrapper">
                <span class="list-controls">
                    <a href="<?php 
    echo  esc_url( add_query_arg( array(
        $post_type_name . '-tab' => 'all',
        'selectall'              => 1,
    ), remove_query_arg( $removed_args ) ) ) ;
    ?>#posttype-<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>" class="amm-check-opt select-all"><?php 
    esc_html_e( 'Select All', 'advance-menu-manager' );
    ?></a>
                    <a style="display:none;" href="<?php 
    echo  esc_url( add_query_arg( array(
        $post_type_name . '-tab' => 'all',
        'selectall'              => 1,
    ), remove_query_arg( $removed_args ) ) ) ;
    ?>#posttype-<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>" class="amm-check-opt deselect-all"><?php 
    esc_html_e( 'Deselect All', 'advance-menu-manager' );
    ?></a>
                </span>
                <span class="allready-menu-item"><input type="checkbox" name='curent-menu_item' class="curent-menu_item" value="" data-selector="<?php 
    echo  esc_attr( $post_type_name ) . "checklist" ;
    ?>"/><?php 
    esc_html_e( 'Hide existing menu items', 'advance-menu-manager' );
    ?></span>
                <?php 
    $newpage_lable = 'Add New ' . $post_type_name;
    if ( 'page' !== $post_type_name && 'post' !== $post_type_name ) {
        if ( !empty($post_type['args']->label) ) {
            $newpage_lable = 'Add New ' . $post_type['args']->label;
        }
    }
    ?>
                <span class="page-title-action"><?php 
    echo  esc_html( $newpage_lable ) ;
    ?></span>
            </div>
            <div class="menu_item_search_wrapper"><input type="text" class="menu_item_search" value="" placeholder="<?php 
    esc_attr_e( 'Search menu item', 'advance-menu-manager' );
    ?>..."/></div>
        </div>

        <div id="<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>-all" class="tabs-panel tabs-panel-view-all <?php 
    echo  ( 'all' === $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' ) ;
    ?> md_popup_main_wrapper">

            <?php 
    $filter_class_name = '';
    if ( 'page' === $post_type_name ) {
        $filter_class_name = 'md_page_filter';
    }
    ?>
            <div class="amm_menu_item_main_content_wrapper">
                <?php 
    $args['walker'] = $walker;
    /*
     * If we're dealing with pages, let's put a checkbox for the front
     * page at the top of the list.
     */
    
    if ( 'page' === $post_type_name ) {
        $front_page = ( 'page' === get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0 );
        
        if ( !empty($front_page) ) {
            $front_page_obj = get_post( $front_page );
            $front_page_obj->front_or_home = true;
            array_unshift( $posts, $front_page_obj );
        } else {
            $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ? intval( $_nav_menu_placeholder ) - 1 : -1 );
            // phpcs:ignore
        }
    
    }
    
    $post_type = get_post_type_object( $post_type_name );
    $archive_link = get_post_type_archive_link( $post_type_name );
    
    if ( $post_type->has_archive ) {
        $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ? intval( $_nav_menu_placeholder ) - 1 : -1 );
        // phpcs:ignore
        array_unshift( $posts, (object) array(
            'ID'           => 0,
            'object_id'    => $_nav_menu_placeholder,
            'object'       => $post_type_name,
            'post_content' => '',
            'post_excerpt' => '',
            'post_title'   => $post_type->labels->archives,
            'post_type'    => 'nav_menu_item',
            'type'         => 'post_type_archive',
            'url'          => get_post_type_archive_link( $post_type_name ),
        ) );
    }
    
    /**
     * Filter the posts displayed in the 'View All' tab of the current
     * post type's menu items meta box.
     *
     * The dynamic portion of the hook name, `$post_type_name`, refers
     * to the slug of the current post type.
     *
     * @since 3.2.0
     *
     * @see WP_Query::query()
     *
     * @param array  $posts     The posts for the current post type.
     * @param array  $args      An array of WP_Query arguments.
     * @param object $post_type The current post type object for this menu item meta box.
     */
    $posts = apply_filters(
        "nav_menu_items_{$post_type_name}",
        $posts,
        $args,
        $post_type
    );
    $checkbox_items = walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $posts ), 0, (object) $args );
    if ( 'all' === $current_tab && !empty($_REQUEST['selectall']) ) {
        // phpcs:ignore
        $checkbox_items = preg_replace( '/(type=(.)checkbox(\\2))/', '$1 checked=$2checked$2', $checkbox_items );
    }
    $filter_author = array();
    $filter_template = array();
    $filter_publish_date = array();
    $filter_cate = array();
    $template_name_array = array();
    $templates_all = get_page_templates();
    foreach ( $templates_all as $template_name => $template_filename ) {
        $template_name_array[$template_filename] = $template_filename;
    }
    foreach ( $posts as $post_data ) {
        if ( isset( $post_data->post_author ) ) {
            $filter_author[] = $post_data->post_author;
        }
        
        if ( 'page' === $post_data->post_type ) {
            $tamplate_name = get_post_meta( $post_data->ID, '_wp_page_template', true );
            $template_name_key = array_search( $tamplate_name, $template_name_array, true );
            if ( !empty($template_name_key) ) {
                $tamplate_name = $template_name_key;
            }
            
            if ( !empty($tamplate_name) ) {
                $filter_template[] = $tamplate_name;
            } else {
                $filter_template[] = 'default';
            }
        
        } else {
            $category_detail = get_the_category( $post_data->ID );
            
            if ( !empty($category_detail) && count( $category_detail ) > 0 ) {
                $cate_array = array();
                foreach ( $category_detail as $cd ) {
                    $filter_cate[] = $cd->cat_name;
                }
            }
        
        }
    
    }
    if ( count( $filter_author ) > 1 ) {
        $filter_author = array_unique( $filter_author );
    }
    if ( count( $filter_template ) > 1 ) {
        $filter_template = array_unique( $filter_template );
    }
    if ( count( $filter_cate ) > 1 ) {
        $filter_cate = array_unique( $filter_cate );
    }
    $post_data_calss = '';
    if ( 'page' !== $post_type_name ) {
        $post_data_calss = 'post-data-show';
    }
    ?>
                <div class="menu_item_filter_header amm_popup_header_wrapper <?php 
    echo  esc_attr( $post_data_calss ) ;
    ?>" amm-filter-selector="<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>checklist">
                    <span class="item_ID md_walker" ><?php 
    esc_html_e( 'Item ID', 'advance-menu-manager' );
    ?></span>
                    <span class="title md_walker" amm-filter-title="all" ><strong><?php 
    esc_html_e( 'Title', 'advance-menu-manager' );
    ?></strong></span>
                    <span class="item_slug md_walker" ><?php 
    esc_html_e( 'Item Slug', 'advance-menu-manager' );
    ?></span>
                    <?php 
    echo  '<span class="author md_walker" amm-filter-author="all">' ;
    
    if ( count( $filter_author ) > 1 ) {
        echo  '<select class="filter_data" data-filter="author">' ;
        echo  '<option class="" value="all"> Select Author </option>' ;
        foreach ( $filter_author as $author_id ) {
            echo  '<option value="' . esc_attr( $author_id ) . '">' . esc_html( get_the_author_meta( 'display_name', $author_id ) ) . '</option>' ;
        }
        echo  '</select>' ;
    } else {
        echo  '<strong>' . esc_html__( 'Author', 'advance-menu-manager' ) . '</strong>' ;
    }
    
    echo  '</span>' ;
    
    if ( 'page' === $post_type_name ) {
        echo  '<span class="template-list md_walker" amm-filter-template-list="all">' ;
        
        if ( count( $filter_template ) > 1 ) {
            echo  '<select class="filter_data" data-filter="template-list">' ;
            echo  '<option class="" value="all">Select template</option>' ;
            echo  '<option value="default">Default Template</option>' ;
            foreach ( $templates_all as $template_name => $display_template_name ) {
                echo  '<option class="' . esc_attr( $template_name ) . '" value="' . esc_attr( $display_template_name ) . '">' . esc_html( $template_name ) . '</option>' ;
            }
            echo  '</select>' ;
        } else {
            echo  '<strong>' . esc_html__( 'Page Template', 'advance-menu-manager' ) . '</strong>' ;
        }
        
        echo  '</span>' ;
    } else {
        echo  '<span class="category-list md_walker" amm-filter-category-list="all">' ;
        
        if ( count( $filter_cate ) >= 1 ) {
            echo  '<select class="filter_data" data-filter="category-list">' ;
            echo  '<option class="" value="all">Select category</option>' ;
            foreach ( $filter_cate as $filter_catedata ) {
                echo  '<option class="' . esc_attr( $filter_catedata ) . '" value="' . esc_attr( $filter_catedata ) . '">' . esc_html( $filter_catedata ) . '</option>' ;
            }
            echo  '</select>' ;
        } else {
            echo  '<strong>' . esc_html__( 'Category', 'advance-menu-manager' ) . '</strong>' ;
        }
        
        echo  '</span>' ;
    }
    
    ?>
                    <span class="publish_date md_walker"><strong><?php 
    esc_html_e( 'Publish Date', 'advance-menu-manager' );
    ?></strong></span>
                    <span class="menu_item_existing_display_status" amm-filter-category-list="show"></span>
                </div>
                <ul amm_menu_query='page' amm_page_count="<?php 
    echo  esc_attr( $total_page_count ) ;
    ?>" amm_post_type="<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>" id="<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>checklist" data-wp-lists="list:<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>" class="categorychecklist form-no-clear amm_popup_header <?php 
    echo  esc_attr( $filter_class_name ) . ' ' . esc_attr( $post_data_calss ) ;
    ?>" amm_post_per_page = '<?php 
    echo  esc_attr( $post_per_page ) ;
    ?>' >
                    <?php 
    
    if ( !empty($checkbox_items) ) {
        ?>
                    <?php 
        echo  wp_kses( $checkbox_items, $admin_interface->dsamm_allowed_html_tags() ) ;
        ?>
                    <?php 
    } else {
        echo  '<li class="no_record"> No items. </li>' ;
    }
    
    ?>
                </ul>
            </div>
        </div><!-- /.tabs-panel -->
        <p class="button-controls">
            <span class="add-to-menu">
                <input type="submit"<?php 
    wp_nav_menu_disabled_check( $nav_menu_selected_id );
    ?> class="button-secondary submit-add-to-menu right" value="<?php 
    esc_attr_e( 'Add to Menu', 'advance-menu-manager' );
    ?>" name="add-post-type-menu-item" id="<?php 
    echo  esc_attr( 'submit-posttype-' . $post_type_name ) ;
    ?>" />
                <span class="spinner"></span>
            </span>
            <span class="add-menu-item-pagelinks" amm-pagination="<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>checklist"></span>
        </p>
        <p class="amm_list_of_page">
            <?php 
    $list_of_page_no = get_option( 'amm_post_perpage_option' );
    if ( empty($list_of_page_no) ) {
        $list_of_page_no = '10,50,100,200';
    }
    $amm_post_perpage_array = explode( ',', $list_of_page_no );
    
    if ( count( $amm_post_perpage_array ) > 0 ) {
        ?>
                <label><?php 
        esc_html_e( 'Show items per page', 'advance-menu-manager' );
        ?></label>
                <select name="amm_post_perpage_w" class="amm_post_perpage" data_post_per_page="amm_<?php 
        echo  esc_attr( $post_type_name ) ;
        ?>" data_pagination="<?php 
        echo  esc_attr( $post_type_name ) ;
        ?>checklist">
                    <?php 
        foreach ( $amm_post_perpage_array as $data ) {
            
            if ( $post_per_page === $data ) {
                echo  '<option value="' . esc_attr( $data ) . '" selected>' . esc_html( $data ) . '</option>' ;
            } else {
                echo  '<option value="' . esc_attr( $data ) . '">' . esc_html( $data ) . '</option>' ;
            }
        
        }
        ?>
                </select>
            <?php 
    }
    
    ?>
        </p>
    </div><!-- /.posttypediv -->
    <?php 
}

/**
 * Displays a metabox for a taxonomy menu item.
 *
 * @since 3.0.0
 *
 * @global int|string $nav_menu_selected_id
 *
 * @param string $object Not used.
 * @param string $taxonomy The taxonomy object.
 */
function dsamm_nav_menu_item_taxonomy_meta_box_own( $object, $taxonomy )
{
    global  $nav_menu_selected_id ;
    $taxonomy_name = $taxonomy['args']->name;
    // Paginate browsing for large numbers of objects.
    $post_per_page = get_option( 'amm_' . $taxonomy_name );
    if ( empty($post_per_page) ) {
        $post_per_page = get_option( 'amm_post_perpage_default' );
    }
    if ( empty($post_per_page) ) {
        $post_per_page = '50';
    }
    $per_page = (int) $post_per_page;
    $pagenum = ( isset( $_REQUEST[$taxonomy_name . '-tab'] ) && isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1 );
    // phpcs:ignore
    $offset = ( 0 < $pagenum ? $per_page * ($pagenum - 1) : 0 );
    $args = array(
        'child_of'     => 0,
        'hide_empty'   => false,
        'hierarchical' => 1,
        'include'      => '',
        'number'       => $per_page,
        'offset'       => $offset,
        'order'        => 'ASC',
        'orderby'      => 'name',
        'pad_counts'   => false,
    );
    $terms = get_terms( $taxonomy_name, $args );
    
    if ( !$terms || is_wp_error( $terms ) ) {
        echo  '<p>' . esc_html__( 'No items.', 'advance-menu-manager' ) . '</p>' ;
        return;
    }
    
    $num_pages = ceil( wp_count_terms( $taxonomy_name, array_merge( $args, array(
        'number' => '',
        'offset' => '',
    ) ) ) / $per_page );
    $total_page_count = wp_count_terms( $taxonomy_name, array_merge( $args, array(
        'number' => '',
        'offset' => '',
    ) ) );
    $post_type_name = $taxonomy_name;
    $db_fields = false;
    if ( is_taxonomy_hierarchical( $taxonomy_name ) ) {
        $db_fields = array(
            'parent' => 'parent',
            'id'     => 'term_id',
        );
    }
    $walker = new DSAMM_Walker_Nav_Menu_Checklist( $db_fields );
    $removed_args = array(
        'action',
        'customlink-tab',
        'edit-menu-item',
        'menu-item',
        'page-tab',
        '_wpnonce'
    );
    ?>
    <div id="taxonomy-<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>" class="taxonomydiv amm_item_main_wrapper">
        <div id="tabs-panel-<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>-all" class="tabs-panel tabs-panel-view-all tabs-panel-active md_popup_main_wrapper">
            <div class="amm_header_main">
                <div class="new_item_add_wrapper">
                    <span class="list-controls amm_taxonomy"> 
                        <a href="<?php 
    echo  esc_url( add_query_arg( array(
        $taxonomy_name . '-tab' => 'all',
        'selectall'             => 1,
    ), remove_query_arg( $removed_args ) ) ) ;
    ?>#taxonomy-<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>" class="amm-check-opt select-all"><?php 
    esc_html_e( 'Select All', 'advance-menu-manager' );
    ?></a>
                        <a style="display:none;" href="<?php 
    echo  esc_url( add_query_arg( array(
        $taxonomy_name . '-tab' => 'all',
        'selectall'             => 1,
    ), remove_query_arg( $removed_args ) ) ) ;
    ?>#taxonomy-<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>" class="amm-check-opt deselect-all"><?php 
    esc_html_e( 'Deselect All', 'advance-menu-manager' );
    ?></a>
                    </span>
                </div>
                <div class="amm_taxonomy_search_wrapper">
                    <input type="text" data-taxonomy="<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>" class="amm_taxonomy_search" value="" placeholder="Search menu item..">
                </div>
            </div>
            <div class="menu_item_filter_header amm_popup_header_wrapper taxonomy_item_list" amm-filter-selector="<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>checklist">
                <span class="title md_walker" amm-filter-title="all" ><strong><?php 
    esc_html_e( 'Title', 'advance-menu-manager' );
    ?></strong></span>
                <span class="taxonomy_slug md_walker"><strong><?php 
    esc_html_e( 'Slug', 'advance-menu-manager' );
    ?></strong></span>
                <span class="taxomomy_content md_walker"><strong><?php 
    esc_html_e( 'Description', 'advance-menu-manager' );
    ?></strong></span>
            </div>

            <ul amm_menu_query='taxonomy' amm_page_count="<?php 
    echo  esc_attr( $total_page_count ) ;
    ?>" amm_post_type="<?php 
    echo  esc_attr( $post_type_name ) ;
    ?>" id="<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>checklist" data-wp-lists="list:<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>" class="categorychecklist form-no-clear amm_popup_header taxonomy_item_list" amm_post_per_page = '<?php 
    echo  esc_attr( $post_per_page ) ;
    ?>'>
                <?php 
    $args['walker'] = $walker;
    echo  walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $terms ), 0, (object) $args ) ;
    ?>
            </ul>
        </div><!-- /.tabs-panel -->
        <p class="button-controls">
            <span class="add-to-menu">
                <input type="submit"<?php 
    wp_nav_menu_disabled_check( $nav_menu_selected_id );
    ?> class="button-secondary submit-add-to-menu right" value="<?php 
    esc_attr_e( 'Add to Menu', 'advance-menu-manager' );
    ?>" name="add-taxonomy-menu-item" id="<?php 
    echo  esc_attr( 'submit-taxonomy-' . $taxonomy_name ) ;
    ?>" />
                <span class="spinner"></span>
            </span>
            <span class="add-menu-item-pagelinks" amm-pagination="<?php 
    echo  esc_attr( $taxonomy_name ) ;
    ?>checklist"></span>
        </p>
        <p class="amm_list_of_page">
            <?php 
    $list_of_page_no = get_option( 'amm_post_perpage_option' );
    if ( empty($list_of_page_no) ) {
        $list_of_page_no = '10,50,100,200';
    }
    $amm_post_perpage_array = explode( ',', $list_of_page_no );
    
    if ( count( $amm_post_perpage_array ) > 0 ) {
        ?>
                <label><?php 
        esc_html_e( 'Show items per page', 'advance-menu-manager' );
        ?></label>
                <select name="amm_post_perpage_w" class="amm_post_perpage" data_post_per_page="amm_<?php 
        echo  esc_attr( $taxonomy_name ) ;
        ?>" data_pagination = "<?php 
        echo  esc_attr( $taxonomy_name ) ;
        ?>checklist">
                    <?php 
        foreach ( $amm_post_perpage_array as $data ) {
            
            if ( $post_per_page === $data ) {
                echo  '<option value="' . esc_attr( $data ) . '" selected>' . esc_html( $data ) . '</option>' ;
            } else {
                echo  '<option value="' . esc_attr( $data ) . '">' . esc_html( $data ) . '</option>' ;
            }
        
        }
        ?>
                </select>
            <?php 
    }
    
    ?>
        </p>
    </div><!-- /.taxonomydiv -->
    <?php 
}
