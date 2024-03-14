<?php

//phpcs:ignore
/**
 * Fired when admin page menu revision ajax called.
 *
 *
 * @since      1.0.0
 * @package    Multidots Advance Menu Manager
 * @subpackage advance-menu-manager/includes/classes
 * @author     Multidots Solutions Pvt. Ltd. <info@multidots.com>
 */
class DSAMM_Revision_Ajax_Action
{
    function __construct()
    {
    }
    
    /**
     * 
     * Menu item edit hook action
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_menu_edit_action_method_own()
    {
        check_ajax_referer( 'dsamm_ajax_value_nonce', 'security' );
        $return_data = array();
        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_id = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
        $post_slug = filter_input( INPUT_POST, 'post_slug', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_title = filter_input( INPUT_POST, 'post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_author = filter_input( INPUT_POST, 'post_author', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_category_old = filter_input( INPUT_POST, 'post_category_old', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_category_new = filter_input( INPUT_POST, 'post_category_new', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_template = filter_input( INPUT_POST, 'post_template', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( 'my_action_for_popup_menu_item_edit' === $action && !empty($post_id) && !empty($post_slug) ) {
            $update_my_post = array(
                'ID'          => $post_id,
                'post_title'  => $post_title,
                'post_author' => $post_author,
                'post_name'   => $post_slug,
            );
            wp_update_post( $update_my_post );
            
            if ( !empty($_POST['post_category']) ) {
                $old_cat_id_array = array_map( 'intval', explode( ',', $post_category_old ) );
                $post_category_new = ( isset( $post_category_new ) ? $post_category_new : '' );
                parse_str( $post_category_new, $new_cat_id_array );
                $new_cat_id_array = array_map( 'intval', $new_cat_id_array['amm_post_set_category'] );
                $cat_update = '';
                //update category
                wp_remove_object_terms( $post_id, $old_cat_id_array, 'category' );
                wp_set_object_terms( $post_id, $new_cat_id_array, 'category' );
                $cate_name = '';
                $i = 1;
                
                if ( !empty($new_cat_id_array) ) {
                    foreach ( $new_cat_id_array as $cate_id ) {
                        
                        if ( $i === 1 ) {
                            $cate_name .= get_cat_name( $cate_id );
                        } else {
                            $cate_name .= ',' . get_cat_name( $cate_id );
                        }
                        
                        $i++;
                    }
                    $return_data['cat_id'] = implode( ',', $new_cat_id_array );
                } else {
                    $return_data['cat_id'] = "-";
                }
                
                $return_data['cat_name'] = $cate_name;
                $return_data['post'] = 'true';
            } else {
                
                if ( !empty($post_template) ) {
                    $return_data['page'] = 'true';
                    update_post_meta( $post_id, '_wp_page_template', $post_template );
                }
            
            }
            
            $get_current_post = get_post( $post_id );
            $return_data['post_slug'] = $get_current_post->post_name;
            $return_data['sucess'] = esc_html__( 'Item has been successfully updated.', 'advance-menu-manager' );
        } else {
            $return_data['error'] = esc_html__( 'Please try again later.', 'advance-menu-manager' );
        }
        
        echo  json_encode( $return_data ) ;
        exit;
    }
    
    /**
     * 
     * Add new menu item ajax action
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_add_new_post_action_method_own()
    {
        check_ajax_referer( 'dsamm_ajax_value_nonce', 'security' );
        $return_data = array();
        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_title = filter_input( INPUT_POST, 'post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_author = filter_input( INPUT_POST, 'post_author', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_slug = filter_input( INPUT_POST, 'post_slug', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $item_post_type = filter_input( INPUT_POST, 'item_post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $item_content = filter_input( INPUT_POST, 'item_content', FILTER_SANITIZE_SPECIAL_CHARS );
        $item_content = html_entity_decode( $item_content );
        $post_template = filter_input( INPUT_POST, 'post_template', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_category = filter_input(
            INPUT_POST,
            'post_category',
            FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            FILTER_REQUIRE_ARRAY
        );
        
        if ( 'my_action_for_popup_add_new_post' == $action && !empty($post_title) && !empty($post_author) ) {
            $add_new_post = array(
                'post_title'   => $post_title,
                'post_name'    => $post_slug,
                'post_status'  => 'publish',
                'post_author'  => $post_author,
                'post_type'    => $item_post_type,
                'post_content' => $item_content,
            );
            
            if ( 'page' == $item_post_type ) {
                $new_post_id = wp_insert_post( $add_new_post );
                $return_data['page'] = 'true';
                update_post_meta( $new_post_id, '_wp_page_template', $post_template );
            } else {
                $new_cat_id_array = ( isset( $_POST['post_category'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['post_category'] ) ) : array() );
                $add_new_post = array_merge( $add_new_post, array(
                    'post_category' => $new_cat_id_array,
                ) );
                $new_post_id = wp_insert_post( $add_new_post );
                $return_data['post'] = 'true';
            }
            
            
            if ( is_int( $new_post_id ) && $new_post_id > 0 ) {
                $return_data['new_post_html'] = DSAMM_Revision_Ajax_Action::dsamm_add_to_menu_html_own( $new_post_id );
                $return_data['sucess'] = esc_html__( ' has been added successfully.', 'advance-menu-manager' );
            } else {
                $return_data['error'] = esc_html__( 'Please try again later.', 'advance-menu-manager' );
            }
        
        } else {
            $return_data['error'] = esc_html__( 'Please try again later.', 'advance-menu-manager' );
        }
        
        echo  json_encode( $return_data ) ;
        exit;
    }
    
    /**
     * 
     * AMM search texonomy term ajax action
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_my_action_for_amm_taxonomy_search()
    {
        $texo = filter_input( INPUT_POST, 'texo', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $search_text = filter_input( INPUT_POST, 'search_text', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $db_fields = false;
        if ( is_taxonomy_hierarchical( $texo ) ) {
            $db_fields = array(
                'parent' => 'parent',
                'id'     => 'term_id',
            );
        }
        $walker = new DSAMM_Walker_Nav_Menu_Checklist( $db_fields );
        $args = array(
            'taxonomy'   => array( $texo ),
            'order'      => 'ASC',
            'orderby'    => 'name',
            'hide_empty' => false,
            'fields'     => 'all',
            'name__like' => $search_text,
        );
        $terms = get_terms( $args );
        $args['walker'] = $walker;
        $t_html = walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $terms ), 0, (object) $args );
        
        if ( !empty($t_html) ) {
            $sucess = true;
        } else {
            $t_html = '<li class="no_record">' . esc_html__( 'No Record found', 'advance-menu-manager' ) . '</li>';
            $sucess = false;
        }
        
        $return_data = array();
        $return_data['html'] = $t_html;
        $return_data['sucess'] = $sucess;
        echo  json_encode( $return_data ) ;
        exit;
    }
    
    /**
     * 
     * AMM edit menu fornt end ajax action
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_popup_menu_item_edit_frontend()
    {
        check_ajax_referer( 'dsamm_ajax_value_nonce', 'security' );
        $return_data = array();
        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_id = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( 'my_action_for_popup_menu_item_edit_front_end' == $action && !empty($post_id) ) {
            $gloable_all_author_array = array();
            $gloable_all_template_array = array();
            $gloable_all_category_array = array();
            $item = get_post( $post_id );
            //set all author
            $allUsers = get_users( 'orderby=ID&order=ASC' );
            foreach ( $allUsers as $currentUser ) {
                if ( !in_array( 'subscriber', $currentUser->roles ) ) {
                    $gloable_all_author_array[] = $currentUser;
                }
            }
            // set all template
            $get_templates_all = get_page_templates();
            foreach ( $get_templates_all as $template_name => $template_filename ) {
                $gloable_all_template_array[$template_name] = $template_filename;
            }
            // set all category
            $all_category = get_categories( 'orderby=name&hide_empty=0' );
            foreach ( $all_category as $cat_data ) {
                $gloable_all_category_array[$cat_data->cat_ID] = $cat_data->cat_name;
            }
            $html = '';
            $cate_array = array();
            $cate_id_array = array();
            $current_post_category = '';
            $html .= '<div id="Amp_amp_update_wrapper" class="amp_update_new_item_wrapper">
						<div class="amp_update_item_inner_main">
							<div class="amm_title_header_wrapper"><strong>Edit  ' . $item->post_type . '</strong> </div>
							<div class="amp_update_item_row_wrapper">							
								<div class="amp_update_item_details_left">
									<div class="row">										
										<span class="amp_item_title_amp_update">Title</span>
										<input type="text" name="post_title" class="amp_item_title" value="' . esc_attr( $item->post_title ) . '">
									</div>
									<div class="row">
										<span class="amp_item_title_amp_update">Slug</span>
										<input type="text" name="post_name" class="amp_item_slug" value="' . esc_attr( $item->post_name ) . '">
									</div>
								</div>
								<div class="amp_update_item_details_left">
									<div class="row">
										<span class="amp_item_title_amp_update">Author</span>
										<select name="post_author" class="amp_select amp_item_author">';
            $post_author_name = get_the_author_meta( 'display_name', $item->post_author );
            foreach ( $gloable_all_author_array as $data_post_author ) {
                
                if ( $post_author_name == $data_post_author->data->display_name ) {
                    $html .= '<option value="' . $data_post_author->data->ID . '" selected>' . $data_post_author->data->display_name . '</option>';
                } else {
                    $html .= '<option value="' . $data_post_author->data->ID . '">' . $data_post_author->data->display_name . '</option>';
                }
            
            }
            $html .= '
										</select>
									</div>';
            
            if ( 'page' == $item->post_type ) {
                $html .= '<div class="row">
													<span class="amp_item_title_amp_update">Template</span>
													<select name="page_template" class="amp_select amp_item_template">';
                $tamplate_name_edit = get_post_meta( $item->ID, '_wp_page_template', true );
                $html .= '<option value="default">Default Template</option>';
                foreach ( $gloable_all_template_array as $template_name => $template_filename ) {
                    
                    if ( $tamplate_name_edit == $template_filename ) {
                        $html .= '<option value="' . $template_filename . '" selected>' . $template_name . '</option>';
                    } else {
                        $html .= '<option value="' . $template_filename . '">' . $template_name . '</option>';
                    }
                
                }
                $html .= '</select>
										</div>';
            } else {
                
                if ( 'post' == $item->post_type ) {
                    $category_detail = get_the_category( $item->ID );
                    if ( !empty($category_detail) && count( $category_detail ) > 0 ) {
                        foreach ( $category_detail as $cd ) {
                            $cate_array[] = $cd->cat_name;
                            $cate_id_array[] = $cd->cat_ID;
                        }
                    }
                    $html .= '<div class="row">
													<input type="hidden" class="old_category" value="' . implode( ',', $cate_id_array ) . '">
													<span class="amp_item_title_amp_update category-select-checkbox">Category</span>
													<ul class="amp_select">';
                    foreach ( $gloable_all_category_array as $cate_id => $data_cat ) {
                        $sleceted_id = '';
                        $sleceted_id = ( in_array( $data_cat, $cate_array ) ? "checked" : "" );
                        $html .= '<li><input type="checkbox" class="set_new_category" name="amp_item_category[]" value="' . $cate_id . '" ' . $sleceted_id . '> ' . $data_cat . '</li>';
                    }
                    $html .= '</ul>
										</div>';
                }
            
            }
            
            $html .= '
								</div>
							</div>';
            $html .= '<div class="amm_item_content_wrapper"><span class="amm_item_title">Content</span><p class="item_content">';
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
                'wpautop'           => true,
                'media_buttons'     => false,
                'textarea_name'     => 'amm_post_content_add',
                'textarea_rows'     => 20,
                'tabindex'          => '',
                'tabfocus_elements' => ':prev,:next',
                'editor_css'        => 'amm-post-page-content',
                'editor_class'      => '',
                'teeny'             => false,
                'dfw'               => false,
                'tinymce'           => false,
                'quicktags'         => false,
            );
            $wp_ediotr_id = 'amm_item_edit_content_123';
            $html .= '<textarea name="amm_item_edit_content" id="' . $wp_ediotr_id . '">' . $item->post_content . '</textarea>';
            $html .= '</p></div>';
            $html .= '<div class="amp_update_item_submit_row_wrapper">
								<button type="button" class="button-secondary amp_menu_amp_update_cancel">Cancel</button>';
            
            if ( 'page' == $item->post_type ) {
                $html .= '<button id="' . $item->ID . '" type="button" class="button-primary amp_submit_post_for_front_amp_update_item amp_page_update">Update</button>';
            } else {
                $html .= '<button id="' . $item->ID . '" type="button" class="button-primary amp_submit_post_for_front_amp_update_item">Update</button>';
            }
            
            $html .= '
							</div>
						</div>
					</div>';
            $return_data['post_html'] = $html;
            $return_data['wp_editor_selector'] = $wp_ediotr_id;
            $return_data['sucess'] = esc_html__( 'Item has added successfully.', 'advance-menu-manager' );
        } else {
            $return_data['error'] = esc_html__( 'Please try again later.', 'advance-menu-manager' );
        }
        
        echo  json_encode( $return_data ) ;
        exit;
    }
    
    /**
     * 
     * AMM edit menu forntend edit ajax action submited
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_main_popup_fontend_menu_item_edit_submit_action_own()
    {
        check_ajax_referer( 'dsamm_ajax_value_nonce', 'security' );
        $return_data = array();
        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_id = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_title = filter_input( INPUT_POST, 'post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_author = filter_input( INPUT_POST, 'post_author', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_slug = filter_input( INPUT_POST, 'post_slug', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_content = filter_input( INPUT_POST, 'post_content', FILTER_SANITIZE_SPECIAL_CHARS );
        $post_content = html_entity_decode( $post_content );
        $post_template = filter_input( INPUT_POST, 'post_template', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_category_old = filter_input( INPUT_POST, 'post_category_old', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_category_new = filter_input( INPUT_POST, 'post_category_new', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( 'my_action_for_main_popup_fontend_menu_item_edit_submit' == $action && !empty($post_id) && !empty($post_title) ) {
            
            if ( !empty($post_content) ) {
                $update_my_post = array(
                    'ID'           => $post_id,
                    'post_title'   => $post_title,
                    'post_author'  => $post_author,
                    'post_name'    => $post_slug,
                    'post_content' => $post_content,
                );
            } else {
                $update_my_post = array(
                    'ID'          => $post_id,
                    'post_title'  => $post_title,
                    'post_author' => $post_author,
                    'post_name'   => $post_slug,
                );
            }
            
            wp_update_post( $update_my_post );
            
            if ( !empty($_POST['post_category']) ) {
                $post_category_old = ( isset( $post_category_old ) ? $post_category_old : '' );
                $old_cat_id_array = array_map( 'intval', explode( ',', $post_category_old ) );
                $post_category_new = ( isset( $post_category_new ) ? $post_category_new : array() );
                parse_str( $post_category_new, $new_cat_id_array );
                $new_cat_id_array = array_map( 'intval', $new_cat_id_array['amp_item_category'] );
                //update category
                wp_remove_object_terms( $post_id, $old_cat_id_array, 'category' );
                wp_set_object_terms( $post_id, $new_cat_id_array, 'category' );
            } else {
                if ( !empty($post_template) ) {
                    update_post_meta( $post_id, '_wp_page_template', $post_template );
                }
            }
            
            $return_data['sucess'] = "<b>" . $post_title . "</b>" . esc_html__( ' has been updated successfully.', 'advance-menu-manager' );
        } else {
            $return_data['error'] = esc_html__( 'Please try again later.', 'advance-menu-manager' );
        }
        
        echo  json_encode( $return_data ) ;
        exit;
    }
    
    /**
     * 
     * New item menu html
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_add_to_menu_html_own( $post_ID = null )
    {
        $output = '';
        
        if ( !empty($post_ID) ) {
            global  $_nav_menu_placeholder, $nav_menu_selected_id ;
            $gloable_all_author_array = array();
            $gloable_all_template_array = array();
            $gloable_all_category_array = array();
            //set all author globaly
            $allUsers = get_users( 'orderby=ID&order=ASC' );
            foreach ( $allUsers as $currentUser ) {
                if ( !in_array( 'subscriber', $currentUser->roles ) ) {
                    $gloable_all_author_array[] = $currentUser;
                }
            }
            // set all template globaly
            $get_templates_all = get_page_templates();
            foreach ( $get_templates_all as $template_name => $template_filename ) {
                $gloable_all_template_array[$template_name] = $template_filename;
            }
            // set all category by globaly
            $all_category = get_categories( 'orderby=name&hide_empty=0' );
            foreach ( $all_category as $cat_data ) {
                $gloable_all_category_array[$cat_data->cat_ID] = $cat_data->cat_name;
            }
            $cate_array = array();
            $cate_id_array = array();
            $current_post_category = '';
            $item = get_post( $post_ID );
            $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ? intval( $_nav_menu_placeholder ) - 1 : $item->ID );
            //phpcs:ignore
            $possible_object_id = ( isset( $item->post_type ) && 'nav_menu_item' == $item->post_type ? $item->ID : $_nav_menu_placeholder );
            $possible_db_id = ( !empty($item->ID) && 0 < $possible_object_id ? (int) $item->ID : 0 );
            $output .= '<li class="">';
            $output .= '<span class="item_ID md_walker" >';
            $output .= '<label class="menu-item-title">';
            $output .= '<input type="checkbox" class="menu-item-checkbox';
            $output .= '" name="menu-item[' . $possible_object_id . '][menu-item-object-id]" value="' . esc_attr( $item->ID ) . '" /> ';
            $output .= '</label>';
            $output .= '<i class="menu_item_edit" data-amm_menu_item_id="' . $item->ID . '" title="Edit this">&nbsp;</i>';
            // Menu item hidden fields
            $output .= '<input type="hidden" class="menu-item-db-id" name="menu-item[' . $possible_object_id . '][menu-item-db-id]" value="' . $possible_db_id . '" />';
            $output .= '<input type="hidden" class="menu-item-object" name="menu-item[' . $possible_object_id . '][menu-item-object]" value="' . esc_attr( $item->post_type ) . '" />';
            $output .= '<input type="hidden" class="menu-item-parent-id" name="menu-item[' . $possible_object_id . '][menu-item-parent-id]" value="' . esc_attr( $item->post_parent ) . '" />';
            $output .= '<input type="hidden" class="menu-item-type" name="menu-item[' . $possible_object_id . '][menu-item-type]" value="' . esc_attr( 'post_type' ) . '" />';
            $output .= '<input type="hidden" class="menu-item-title" name="menu-item[' . $possible_object_id . '][menu-item-title]" value="' . esc_attr( $item->post_title ) . '" />';
            $output .= '<input type="hidden" class="menu-item-url" name="menu-item[' . $possible_object_id . '][menu-item-url]" value="' . esc_attr( $item->guid ) . '" />';
            $output .= '<input type="hidden" class="menu-item-target" name="menu-item[' . $possible_object_id . '][menu-item-target]" value="' . esc_attr( $item->target ) . '" />';
            $output .= '<input type="hidden" class="menu-item-attr_title" name="menu-item[' . $possible_object_id . '][menu-item-attr_title]" value="' . esc_attr( $item->attr_title ) . '" />';
            $item_class = ( !empty($item->classes) ? implode( ' ', $item->classes ) : '' );
            $output .= '<input type="hidden" class="menu-item-classes" name="menu-item[' . $possible_object_id . '][menu-item-classes]" value="' . esc_attr( $item_class ) . '" />';
            $output .= '<input type="hidden" class="menu-item-xfn" name="menu-item[' . $possible_object_id . '][menu-item-xfn]" value="' . esc_attr( $item->xfn ) . '" />';
            $output .= $item->ID . '</span>';
            $output .= '<span class="title md_walker" title="' . esc_attr( $item->post_title ) . '"> <strong>';
            $output .= ( strlen( esc_html( $item->post_title ) ) > 35 ? substr( esc_html( $item->post_title ), 0, 35 ) . '...' : esc_html( $item->post_title ) );
            $output .= '</strong> </span>';
            // display page/post custom post type menu item.
            // display page/post custom post type menu item.
            $output .= '<span class="item_slug md_walker" title="' . $item->post_name . '">';
            $output .= ( strlen( $item->post_name ) > 20 ? substr( $item->post_name, 0, 20 ) . '...' : $item->post_name );
            $output .= '</span>';
            $post_author_name = get_the_author_meta( 'display_name', $item->post_author );
            $output .= '<span class="author md_walker">' . $post_author_name . '</span>';
            
            if ( 'page' == $item->post_type ) {
                $output .= '<span class="template-list md_walker">';
                $tamplate_name = get_post_meta( $item->ID, '_wp_page_template', true );
                
                if ( 'default' == $tamplate_name || empty($tamplate_name) ) {
                    $output .= 'default';
                } else {
                    $template_name_key = array_search( $tamplate_name, $gloable_all_template_array );
                    
                    if ( !empty($template_name_key) ) {
                        $tamplate_name = $template_name_key;
                    } else {
                        $tamplate_name = 'default';
                    }
                    
                    $output .= $tamplate_name;
                }
                
                $output .= '</span>';
            } else {
                $category_detail = get_the_category( $item->ID );
                $output .= '<span class="category-list md_walker">';
                
                if ( !empty($category_detail) && count( $category_detail ) > 0 ) {
                    foreach ( $category_detail as $cd ) {
                        $cate_array[] = $cd->cat_name;
                        $cate_id_array[] = $cd->cat_ID;
                    }
                    $current_post_category = implode( ',', $cate_array );
                    $output .= $current_post_category;
                } else {
                    $output .= '-';
                }
                
                $output .= '</span>';
            }
            
            $output .= '<span class="publish_date md_walker">' . get_the_date( '', $item->ID ) . '</span>';
            $output .= '<div class="menu_item_edit_div amm_hide">';
            $output .= '<div class="menu_item_edit_div_wrapper">';
            $output .= '<div class="inline-edit-col-left">
	 							 		<div class="edit_row">
					 						<span class="amm_edit_title">Title</span>
											<input type="text" name="post_title" class="amm_post_title" value="' . esc_attr( $item->post_title ) . '">
										</div>
										<div class="edit_row">
											<span class="amm_edit_title">Slug</span>
											<input type="text" name="post_name" class="amm_edit_slug" value="' . esc_attr( $item->post_name ) . '">
										</div>
								   </div>';
            $output .= '<div class="inline-edit-col-right">
	 				 					<div class="edit_row">
	 										<span class="amm_edit_title">Author</span>
	 										<select name="post_author" class="amm_edit_select amm_edit_post_author" >';
            foreach ( $gloable_all_author_array as $data_post_author ) {
                
                if ( $post_author_name == $data_post_author->data->display_name ) {
                    $output .= '<option value="' . $data_post_author->data->ID . '" selected>' . $data_post_author->data->display_name . '</option>';
                } else {
                    $output .= '<option value="' . $data_post_author->data->ID . '">' . $data_post_author->data->display_name . '</option>';
                }
            
            }
            $output .= '</select>
										</div>';
            
            if ( 'page' == $item->post_type ) {
                $output .= '<div class="edit_row">
													<span class="amm_edit_title">Template</span>
													<select name="page_template" class="amm_edit_select amm_edit_post_template">';
                $tamplate_name_edit = get_post_meta( $item->ID, '_wp_page_template', true );
                $output .= '<option value="default">Default Template</option>';
                if ( 'default' != $tamplate_name_edit ) {
                    foreach ( $gloable_all_template_array as $template_name => $template_filename ) {
                        
                        if ( $tamplate_name_edit == $template_filename ) {
                            $output .= '<option value="' . $template_filename . '" selected>' . $template_name . '</option>';
                        } else {
                            $output .= '<option value="' . $template_filename . '">' . $template_name . '</option>';
                        }
                    
                    }
                }
                $output .= '</select>
												</div>';
            } else {
                $output .= '<div class="edit_row">
													<span class="amm_edit_title">Category</span>';
                $output .= '<input type="hidden" class="old_category" value="' . implode( ',', $cate_id_array ) . '">';
                $output .= '<span class="amm_edit_post_category">';
                foreach ( $gloable_all_category_array as $cate_id => $data_cat ) {
                    $sleceted_id = '';
                    //$sleceted_id = (in_array($data_cat,$cate_array)) ? " checked='checked'" : " ";
                    $output .= '<p><input type="checkbox" class="set_new_category" name="amm_post_set_category[]" value="' . $cate_id . '" ' . $sleceted_id . ' /> ' . $data_cat . '</p>';
                }
                $output .= '</span>
							    				</div>';
            }
            
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<div class="submit_edit_post">
				 			<button type="button" class="button-secondary amm_menu_edit_cancel alignleft">Cancel</button>';
            
            if ( 'page' == $item->post_type ) {
                $output .= '<button id="' . $item->ID . '" type="button" class="button-primary amm_submit_post_for_edit amm_page_edit">Update</button>';
            } else {
                $output .= '<button id="' . $item->ID . '" type="button" class="button-primary amm_submit_post_for_edit">Update</button>';
            }
            
            $output .= '	</div>';
            $output .= '</div>';
            $output .= '</li>';
        }
        
        return $output;
    }
    
    /**
     * 
     * Add new item filter in menu
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_add_new_menu_item_html_own()
    {
        $return_data = array();
        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $page_no = filter_input( INPUT_POST, 'page_no', FILTER_SANITIZE_NUMBER_INT );
        $post_type = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $amm_menu_query = filter_input( INPUT_POST, 'amm_menu_query', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( 'my_action_for_add_new_menu_item_html' == $action && !empty($page_no) && !empty($post_type) ) {
            global  $_nav_menu_placeholder, $nav_menu_selected_id ;
            //post per page as post wise
            $post_per_page = get_option( 'amm_' . $post_type );
            if ( empty($post_per_page) ) {
                $post_per_page = get_option( 'amm_post_perpage_default' );
            }
            
            if ( empty($amm_menu_query) ) {
                $page_html = '';
                $post_type_name = $post_type;
                $per_page = $post_per_page;
                $args = array(
                    'paged'                  => $page_no,
                    'order'                  => 'ASC',
                    'orderby'                => 'title',
                    'posts_per_page'         => $per_page,
                    'post_type'              => $post_type_name,
                    'suppress_filters'       => false,
                    'update_post_term_cache' => false,
                    'update_post_meta_cache' => false,
                );
                // @todo transient caching of these results with proper invalidation on updating of a post of this type
                $get_posts = new WP_Query();
                $posts = $get_posts->query( $args );
                
                if ( !$get_posts->post_count ) {
                    $page_html .= '<li class="no_record">' . esc_html__( 'No Record found', 'advance-menu-manager' ) . '</li>';
                } else {
                    $db_fields = false;
                    if ( is_post_type_hierarchical( $post_type_name ) ) {
                        $db_fields = array(
                            'parent' => 'post_parent',
                            'id'     => 'ID',
                        );
                    }
                    //$walker = new Walker_Nav_Menu_Checklist( $db_fields );
                    $walker = new DSAMM_Walker_Nav_Menu_Checklist( $db_fields );
                    $args['walker'] = $walker;
                    /*
                     * If we're dealing with pages, let's put a checkbox for the front
                     * page at the top of the list.
                     */
                    
                    if ( 'page' == $post_type_name ) {
                        $front_page = ( 'page' == get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0 );
                        
                        if ( !empty($front_page) ) {
                            $front_page_obj = get_post( $front_page );
                            $front_page_obj->front_or_home = true;
                            array_unshift( $posts, $front_page_obj );
                        } else {
                            
                            if ( $page_no <= 1 ) {
                                $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ? intval( $_nav_menu_placeholder ) - 1 : -1 );
                                //phpcs:ignore
                            }
                        
                        }
                    
                    }
                    
                    $post_type = get_post_type_object( $post_type_name );
                    $archive_link = get_post_type_archive_link( $post_type_name );
                    
                    if ( $post_type->has_archive ) {
                        $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ? intval( $_nav_menu_placeholder ) - 1 : -1 );
                        //phpcs:ignore
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
                    $page_html .= $checkbox_items;
                }
            
            } else {
                //amm_menu_query * @param string $taxonomy The taxonomy object.
                global  $nav_menu_selected_id ;
                $taxonomy_name = $post_type;
                $per_page = $post_per_page;
                $pagenum = ( isset( $_REQUEST['page_no'] ) ? absint( $_REQUEST['page_no'] ) : 1 );
                $offset = ( 0 < $pagenum ? $per_page * ($pagenum - 1) : 0 );
                $args = array(
                    'child_of'     => 0,
                    'exclude'      => '',
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
                    $page_html .= '<li class="no_record">' . esc_html__( 'No Record found', 'advance-menu-manager' ) . '</li>';
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
                $args['walker'] = $walker;
                $page_html .= walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $terms ), 0, (object) $args );
            }
            
            $return_data['sucess'] = $page_html;
        } else {
            $return_data['error'] = esc_html__( 'Please try again later.', 'advance-menu-manager' );
        }
        
        echo  json_encode( $return_data ) ;
        exit;
    }
    
    /**
     * 
     * Add new item filter in menu
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_add_new_menu_item_html_filter_own()
    {
        check_ajax_referer( 'dsamm_ajax_value_nonce', 'security' );
        $return_data = array();
        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $post_type = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $page_no = filter_input( INPUT_POST, 'page_no', FILTER_SANITIZE_NUMBER_INT );
        $filter_author = filter_input( INPUT_POST, 'filter_author', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $filter_template = filter_input( INPUT_POST, 'filter_template', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $filter_category = filter_input( INPUT_POST, 'filter_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $filter_textbox = filter_input( INPUT_POST, 'filter_textbox', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $filter_menu_item = filter_input( INPUT_POST, 'filter_menu_item', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $amm_menu_query = filter_input( INPUT_POST, 'amm_menu_query', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( 'my_action_for_add_new_menu_item_html_filter' == $action && !empty($page_no) && !empty($post_type) ) {
            global  $_nav_menu_placeholder, $nav_menu_selected_id ;
            $total_page_count = 0;
            $page_html = '';
            $post_per_page = get_option( 'amm_' . $post_type );
            if ( empty($post_per_page) ) {
                $post_per_page = get_option( 'amm_post_perpage_default' );
            }
            
            if ( empty($amm_menu_query) ) {
                $post_type_name = $post_type;
                $per_page = $post_per_page;
                $args = array(
                    'paged'                  => $page_no,
                    'order'                  => 'ASC',
                    'orderby'                => 'title',
                    'posts_per_page'         => $per_page,
                    'post_type'              => $post_type_name,
                    'suppress_filters'       => false,
                    'update_post_term_cache' => false,
                    'update_post_meta_cache' => false,
                    'post_status'            => 'publish',
                );
                if ( !empty($filter_author) && 'all' != $filter_author ) {
                    $args['author'] = $filter_author;
                }
                
                if ( 'page' == $post_type_name ) {
                    if ( !empty($filter_template) && 'all' != $filter_template ) {
                        $args['meta_query'] = array( array(
                            'key'     => '_wp_page_template',
                            'value'   => $filter_template,
                            'compare' => '=',
                        ) );
                    }
                } else {
                    if ( !empty($filter_category) && 'all' != $filter_category ) {
                        $args['category_name'] = $filter_category;
                    }
                }
                
                if ( !empty($filter_textbox) ) {
                    $args['s'] = $filter_textbox;
                }
                
                if ( $filter_menu_item == 'on' ) {
                    $curent_menu_id = array();
                    $recently_edited = absint( get_user_option( 'nav_menu_recently_edited', get_current_user_id() ) );
                    $menu_items = wp_get_nav_menu_items( $recently_edited );
                    for ( $amm = 0 ;  $amm < count( $menu_items ) ;  $amm++ ) {
                        $curent_menu_id[] = $menu_items[$amm]->object_id;
                    }
                    $args['post__not_in'] = $curent_menu_id;
                }
                
                $removed_args = array(
                    'action',
                    'customlink-tab',
                    'edit-menu-item',
                    'menu-item',
                    'page-tab',
                    '_wpnonce'
                );
                // @todo transient caching of these results with proper invalidation on updating of a post of this type
                $get_posts = new WP_Query();
                $posts = $get_posts->query( $args );
                $get_posts_for_count = new WP_Query( $args );
                $total_page_count = $get_posts_for_count->found_posts;
                
                if ( !$get_posts->post_count ) {
                    $page_html .= '<li class="no_record">' . esc_html__( 'No Record found', 'advance-menu-manager' ) . '</li>';
                } else {
                    $db_fields = false;
                    if ( is_post_type_hierarchical( $post_type_name ) ) {
                        $db_fields = array(
                            'parent' => 'post_parent',
                            'id'     => 'ID',
                        );
                    }
                    //$walker = new Walker_Nav_Menu_Checklist( $db_fields );
                    $walker = new DSAMM_Walker_Nav_Menu_Checklist( $db_fields );
                    $args['walker'] = $walker;
                    /*
                     * If we're dealing with pages, let's put a checkbox for the front
                     * page at the top of the list.
                     */
                    
                    if ( 'page' == $post_type_name ) {
                        $front_page = ( 'page' == get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0 );
                        
                        if ( !empty($front_page) ) {
                            $front_page_obj = get_post( $front_page );
                            $front_page_obj->front_or_home = true;
                            array_unshift( $posts, $front_page_obj );
                        } else {
                            $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ? intval( $_nav_menu_placeholder ) - 1 : -1 );
                            //phpcs:ignore
                        }
                    
                    }
                    
                    $post_type = get_post_type_object( $post_type_name );
                    $archive_link = get_post_type_archive_link( $post_type_name );
                    
                    if ( $post_type->has_archive ) {
                        $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ? intval( $_nav_menu_placeholder ) - 1 : -1 );
                        //phpcs:ignore
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
                    
                    $posts = apply_filters(
                        "nav_menu_items_{$post_type_name}",
                        $posts,
                        $args,
                        $post_type
                    );
                    $checkbox_items = walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $posts ), 0, (object) $args );
                    $page_html .= $checkbox_items;
                }
            
            } else {
                $taxonomy_name = $post_type;
                $per_page = $post_per_page;
                $pagenum = ( isset( $_REQUEST['page_no'] ) ? absint( $_REQUEST['page_no'] ) : 1 );
                $offset = ( 0 < $pagenum ? $per_page * ($pagenum - 1) : 0 );
                $terms = get_terms( $taxonomy_name, array(
                    'orderby'    => 'count',
                    'hide_empty' => 0,
                    'number'     => $per_page,
                    'offset'     => $offset,
                ) );
                
                if ( !$terms || is_wp_error( $terms ) ) {
                    $page_html .= '<li class="no_record">' . esc_html__( 'No Record found', 'advance-menu-manager' ) . '</li>';
                    return;
                }
                
                $num_pages = ceil( wp_count_terms( $taxonomy_name, array_merge( $terms, array(
                    'number' => '',
                    'offset' => '',
                ) ) ) / $per_page );
                $total_page_count = wp_count_terms( $taxonomy_name, array_merge( $terms, array(
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
                $args['walker'] = $walker;
                $page_html .= walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $terms ), 0, (object) $args );
            }
            
            $return_data['sucess'] = $page_html;
            $return_data['total_page'] = $total_page_count;
        } else {
            $return_data['error'] = esc_html__( 'Please try again later.', 'advance-menu-manager' );
        }
        
        echo  json_encode( $return_data ) ;
        exit;
    }
    
    /**
     * Pagination post per page feature
     * 
     * @version		1.0.2
     * @author      theDotstore
     */
    public static function dsamm_add_pagination_post_per_page_limit_method()
    {
        $return_data = array();
        $action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $amm_option_key = filter_input( INPUT_POST, 'amm_option_key', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $page_per_post = filter_input( INPUT_POST, 'page_per_post', FILTER_SANITIZE_NUMBER_INT );
        
        if ( 'my_action_for_add_pagination_limit' == $action && !empty($amm_option_key) ) {
            update_option( $amm_option_key, $page_per_post );
            $return_data['sucess'] = esc_html__( 'ok', 'advance-menu-manager' );
        } else {
            $return_data['error'] = esc_html__( 'Please try again later.', 'advance-menu-manager' );
        }
        
        echo  json_encode( $return_data ) ;
        exit;
    }

}