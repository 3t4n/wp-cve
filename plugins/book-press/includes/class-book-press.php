<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wordpress.org/plugins/book-press
 * @since      1.0.0
 *
 * @package    Book_Press
 * @subpackage Book_Press/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Book_Press
 * @subpackage Book_Press/includes
 * @author     Md Kabir Uddin <bd.kabiruddin@gmail.com>
 */
class Book_Press
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Book_Press_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected  $plugin_name ;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected  $version ;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        
        if ( defined( 'BOOK_PRESS_VERSION' ) ) {
            $this->version = BOOK_PRESS_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        
        $this->plugin_name = 'book-press';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Book_Press_Loader. Orchestrates the hooks of the plugin.
     * - Book_Press_i18n. Defines internationalization functionality.
     * - Book_Press_Admin. Defines all hooks for the admin area.
     * - Book_Press_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-book-press-loader.php';
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-book-press-widget.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-book-press-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-book-press-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-book-press-public.php';
        $this->loader = new Book_Press_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Book_Press_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Book_Press_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Book_Press_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    public function licence()
    {
        if ( function_exists( 'book_press_fs' ) ) {
            
            if ( book_press_fs()->is_not_paying() ) {
                return array(
                    'type'  => 'free',
                    'count' => 1,
                );
            } else {
                return array(
                    'type'  => 'pro',
                    'count' => -1,
                );
            }
        
        }
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Book_Press_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'editor_styles' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'book_press_admin_menu' );
        $this->loader->add_action( 'init', $plugin_admin, 'book_press_book_init' );
        $this->loader->add_action(
            'init',
            $plugin_admin,
            'book_press_book_taxonomies',
            0
        );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'disable_new_posts' );
        $this->loader->add_filter( 'parent_file', $plugin_admin, 'book_press_set_current_menu' );
        $this->loader->add_filter( 'manage_book_posts_columns', $plugin_admin, 'set_custom_edit_book_columns' );
        $this->loader->add_action(
            'manage_book_posts_custom_column',
            $plugin_admin,
            'custom_book_column',
            10,
            2
        );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'book_press_add_meta_box' );
        $this->loader->add_action(
            'delete_post',
            $plugin_admin,
            'action_delete_post',
            10,
            1
        );
        $this->loader->add_action(
            'wp_trash_post',
            $plugin_admin,
            'action_wp_trash_post',
            10,
            1
        );
        $this->loader->add_action(
            'restrict_manage_posts',
            $plugin_admin,
            'filter_books_by_genres',
            10,
            2
        );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'hide_post_editor' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'book_press_register_meta_boxes' );
        $this->loader->add_action( 'save_post', $plugin_admin, 'book_press_save_meta_box' );
        $this->loader->add_action( 'save_post', $plugin_admin, 'background_color_save' );
        $this->loader->add_action( 'pre_get_posts', $plugin_admin, 'query_set_only_book' );
        $this->loader->add_action( 'views_edit-book', $plugin_admin, 'views_filter_for_own_posts' );
        $this->loader->add_action( 'edit_form_top', $plugin_admin, 'top_form_edit' );
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'book_limit_admin_notice__error' );
        $this->loader->add_action( 'do_meta_boxes', $plugin_admin, 'book_press_remove_meta_boxes' );
        $this->loader->add_filter( 'wp_ajax_add_book_section', $plugin_admin, 'add_book_section' );
        $this->loader->add_action( 'wp_ajax_nopriv_add_book_section', $plugin_admin, 'add_book_section' );
        $this->loader->add_filter( 'wp_ajax_add_book_element', $plugin_admin, 'add_book_element' );
        $this->loader->add_action( 'wp_ajax_nopriv_add_book_element', $plugin_admin, 'add_book_element' );
        $this->loader->add_filter( 'wp_ajax_inline_update_el_meta', $plugin_admin, 'inline_update_el_meta' );
        $this->loader->add_action( 'wp_ajax_nopriv_inline_update_el_meta', $plugin_admin, 'inline_update_el_meta' );
        $this->loader->add_filter( 'wp_ajax_update_element_page_unmber', $plugin_admin, 'update_element_page_unmber' );
        $this->loader->add_action( 'wp_ajax_nopriv_update_element_page_unmber', $plugin_admin, 'update_element_page_unmber' );
        $this->loader->add_filter( 'wp_ajax_delete_book_section_element', $plugin_admin, 'delete_book_section_element' );
        $this->loader->add_action( 'wp_ajax_nopriv_delete_book_section_element', $plugin_admin, 'delete_book_section_element' );
        $this->loader->add_filter( 'wp_ajax_update_menu_order', $plugin_admin, 'update_menu_order' );
        $this->loader->add_action( 'wp_ajax_nopriv_update_menu_order', $plugin_admin, 'update_menu_order' );
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Book_Press_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_filter( 'single_template', $plugin_public, 'book_custom_template' );
        add_shortcode( 'book', array( $plugin_public, 'book_shortcode_func' ) );
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Book_Press_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }
    
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
    
    /**
     * Get the publishing capability for BookPress admin
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function book_press_get_publish_cap()
    {
        return apply_filters( 'book_press_publish_cap', 'publish_posts' );
    }
    
    /**
     * @return string
     */
    public function book_press_get_book_id( $post_id )
    {
        if ( !$post_id ) {
            return 0;
        }
        $book_id = 0;
        $section_id = 0;
        $element_id = 0;
        $check = get_post_meta( $post_id, 'type', true );
        
        if ( $check === 'book' ) {
            $book_id = $post_id;
        } else {
            
            if ( get_post( $post_id )->post_parent ) {
                $parent_id = get_post( get_post( $post_id )->post_parent )->ID;
                $check = get_post_meta( $parent_id, 'type', true );
                
                if ( $check === 'book' ) {
                    $book_id = $parent_id;
                } else {
                    
                    if ( get_post( $parent_id )->post_parent ) {
                        $parent_id2 = get_post( get_post( $parent_id )->post_parent )->ID;
                        $check = get_post_meta( $parent_id2, 'type', true );
                        if ( $check === 'book' ) {
                            $book_id = $parent_id2;
                        }
                    }
                
                }
            
            }
        
        }
        
        return $book_id;
    }
    
    /**
     * @param int $number
     * @return string
     */
    public function book_press_number_to_roman( $number )
    {
        $map = array(
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1,
        );
        $returnValue = '';
        while ( $number > 0 ) {
            foreach ( $map as $roman => $int ) {
                
                if ( $number >= $int ) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            
            }
        }
        return $returnValue;
    }
    
    public function get_pages_content( $element )
    {
        $post_content = wpautop( $element['Content'] );
        
        if ( get_post_meta( $element['ID'], 'paragraph_togeather', true ) ) {
            preg_match_all( '/(<table[^>]*>(?:.|\\n)*?<\\/table>)|<img\\s+[^>]*>|<p.*?>[^<>]*<\\/p>|<h.*?>[^<>]*<\\/h.*?>|[^\\s]+/', $post_content, $post_content_out );
        } else {
            preg_match_all( '/(<table[^>]*>(?:.|\\n)*?<\\/table>)|<img\\s+[^>]*>|<h.*?>[^<>]*<\\/h.*?>|[^\\s]+/', $post_content, $post_content_out );
        }
        
        $post_content = $post_content_out;
        $pages_content = array();
        $page_content_count = array();
        $page_count = 1;
        $page_word_count = 0;
        
        if ( isset( $element['ID'] ) ) {
            
            if ( get_post_meta( $element['ID'], 'page_break', true ) ) {
                $page_break = get_post_meta( $element['ID'], 'page_break', true );
            } else {
                $page_break = 250;
            }
        
        } else {
            $page_break = 250;
        }
        
        if ( !get_post_meta( $element['ID'], 'pagination', true ) ) {
            $page_break = 250;
        }
        if ( isset( $post_content[0] ) && $post_content[0] ) {
            foreach ( $post_content[0] as $value ) {
                
                if ( $page_word_count + count( preg_split(
                    "/\\s+/",
                    strip_tags( $value ),
                    0,
                    PREG_SPLIT_NO_EMPTY
                ) ) < $page_break ) {
                    $page_word_count += count( preg_split(
                        "/\\s+/",
                        strip_tags( $value ),
                        0,
                        PREG_SPLIT_NO_EMPTY
                    ) );
                    if ( !isset( $pages_content[$page_count] ) ) {
                        $pages_content[$page_count] = null;
                    }
                    $pages_content[$page_count] .= ' ' . $value;
                    $page_content_count[$page_count] = $page_word_count;
                } else {
                    $page_count++;
                    $pages_content[$page_count] = ' ' . $value;
                    $page_content_count[$page_count] = $page_word_count;
                    $page_word_count = count( preg_split(
                        "/\\s+/",
                        strip_tags( $value ),
                        0,
                        PREG_SPLIT_NO_EMPTY
                    ) );
                }
            
            }
        }
        $new_pages_cont = array();
        if ( $pages_content ) {
            foreach ( $pages_content as $key => $pages_content ) {
                $new_pages_cont[$key] = array(
                    'Content'    => wpautop( $pages_content ),
                    'Word Count' => $page_content_count[$key],
                );
            }
        }
        return $new_pages_cont;
    }
    
    public function get_book_new( $book_id )
    {
        $book_id = $this->book_press_get_book_id( $book_id );
        $args = array(
            'post_parent' => $book_id,
            'post_type'   => 'book',
            'numberposts' => -1,
            'post_status' => 'any',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
        );
        $sections = get_children( $args );
        
        if ( !empty($sections) ) {
            $book['Sections'] = array();
            foreach ( $sections as $section_key => $section ) {
                $book['Sections'][$section->post_title]['Name'] = $section->post_title;
                $args = array(
                    'post_parent' => $section->ID,
                    'post_type'   => 'book',
                    'numberposts' => -1,
                    'post_status' => 'any',
                    'orderby'     => 'menu_order',
                    'order'       => 'ASC',
                );
                $elements = get_children( $args );
                
                if ( !empty($elements) ) {
                    $element_count = 0;
                    foreach ( $elements as $element_key => $element ) {
                        if ( $element->post_title === 'Table of Contents' ) {
                            $element->post_content = $this->get_raw_book_toc( $book_id );
                        }
                        if ( $element->post_title === 'Index' ) {
                            $element->post_content = '';
                        }
                        $book['Sections'][$section->post_title]['Elements'][$element->post_title]['Name'] = $element->post_title;
                        if ( !empty($element->post_content) ) {
                            $book['Sections'][$section->post_title]['Elements'][$element->post_title] = array(
                                'ID'      => $element->ID,
                                'Name'    => $element->post_title,
                                'Content' => array( wpautop( do_shortcode( preg_replace( '/<!--(.|\\s)*?-->/', '', $element->post_content ) ) ) ),
                                'Meta'    => get_post_meta( $element->ID ),
                            );
                        }
                    }
                }
            
            }
            return $book;
        }
    
    }
    
    public function get_raw_book( $book_id )
    {
        $book_id = $this->book_press_get_book_id( $book_id );
        $args = array(
            'post_parent' => $book_id,
            'post_type'   => 'book',
            'numberposts' => -1,
            'post_status' => 'any',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
        );
        $sections = get_children( $args );
        
        if ( !empty($sections) ) {
            $book['Sections'] = array();
            foreach ( $sections as $section_key => $section ) {
                $book['Sections'][$section->post_title]['Name'] = $section->post_title;
                $args = array(
                    'post_parent' => $section->ID,
                    'post_type'   => 'book',
                    'numberposts' => -1,
                    'post_status' => 'any',
                    'orderby'     => 'menu_order',
                    'order'       => 'ASC',
                );
                $elements = get_children( $args );
                
                if ( !empty($elements) ) {
                    $element_count = 0;
                    foreach ( $elements as $element_key => $element ) {
                        if ( $element->post_title === 'Table of Contents' || $element->post_title === 'Index' ) {
                            $element->post_content = 'Test';
                        }
                        $web_print = get_post_meta( $element->ID, 'web_print', true );
                        
                        if ( $web_print ) {
                            $book['Sections'][$section->post_title]['Elements'][$element->post_title]['Name'] = $element->post_title;
                            //if(!empty($element->post_content)){
                            $book['Sections'][$section->post_title]['Elements'][$element->post_title] = array(
                                'ID'      => $element->ID,
                                'Name'    => $element->post_title,
                                'Content' => preg_replace( '/<!--(.|\\s)*?-->/', '', $element->post_content ),
                                'Meta'    => get_post_meta( $element->ID ),
                            );
                            //}
                        }
                    
                    }
                }
            
            }
            return $book;
        }
    
    }
    
    public function get_raw_book_index( $book_id )
    {
        $raw_book = $this->get_raw_book( $book_id );
        $page_number = 1;
        $index_array = array();
        if ( isset( $raw_book['Sections'] ) && $raw_book['Sections'] ) {
            foreach ( $raw_book['Sections'] as $key => $section ) {
                if ( isset( $section['Elements'] ) && $section['Elements'] ) {
                    foreach ( $section['Elements'] as $key => $element ) {
                        
                        if ( isset( $element['Content'] ) && $element['Content'] ) {
                            $pages_content = $this->get_pages_content( $element );
                            if ( $section['Name'] === 'Body Matter' || $section['Name'] === 'End Matter' ) {
                                if ( $pages_content ) {
                                    foreach ( $pages_content as $key_page => $value ) {
                                        preg_match_all( '/\\[index.*?\\]*\\[\\/index.*?\\]/', $value['Content'], $out_index );
                                        if ( $out_index[0] ) {
                                            foreach ( $out_index[0] as $keyx => $out_index_sing ) {
                                                $dat = '<span class="index_index">' . ucwords( strip_tags( do_shortcode( $out_index_sing ) ) ) . '</span>';
                                                $index_array[$dat][$page_number] = $page_number;
                                            }
                                        }
                                        if ( $section['Name'] === 'Body Matter' ) {
                                            $page_number++;
                                        }
                                    }
                                }
                            }
                        }
                    
                    }
                }
            }
        }
        ksort( $index_array );
        $html = '';
        $html .= (string) '<h3><center>Index</center></h3>';
        $html .= '<p>';
        foreach ( $index_array as $key => $value ) {
            $html .= '<div class="sing_index">' . $key . ' - <span class="indx_page">' . implode( ', ', $value ) . '</span></div>';
        }
        $html .= '</p>';
        return $html;
    }
    
    public function get_raw_book_with_index( $book_id )
    {
        $raw_book = $this->get_raw_book( $book_id );
        $raw_book['Sections']['End Matter']['Elements']['Index']['Content'] = $this->get_raw_book_index( $book_id );
        return $raw_book;
    }
    
    public function get_book_toc_object( $book_id )
    {
        $html = '';
        $raw_book = $this->get_raw_book_with_index( $book_id );
        if ( isset( $raw_book['Sections'] ) && $raw_book['Sections'] ) {
            foreach ( $raw_book['Sections'] as $key_section => $section ) {
                if ( isset( $section['Elements'] ) && $section['Elements'] ) {
                    foreach ( $section['Elements'] as $key_element => $element ) {
                        if ( isset( $element['Name'] ) && $element['Name'] ) {
                            if ( $element['Name'] === 'Table of Contents' ) {
                                return $element;
                            }
                        }
                    }
                }
            }
        }
        return $html;
    }
    
    public function get_raw_book_toc( $book_id )
    {
        $toc_object = $this->get_book_toc_object( $book_id );
        $toc_format = 'format1';
        if ( isset( $toc_object['Meta'] ) && $toc_object['Meta'] ) {
            if ( isset( $toc_object['Meta']['toc_format'] ) && $toc_object['Meta']['toc_format'] ) {
                if ( isset( $toc_object['Meta']['toc_format']['0'] ) && $toc_object['Meta']['toc_format']['0'] ) {
                    $toc_format = $toc_object['Meta']['toc_format']['0'];
                }
            }
        }
        $raw_book = $this->get_raw_book_with_index( $book_id );
        $index_array = array();
        $html = '';
        $html .= (string) '<h3><center>Table of Contents</center></h3>';
        $sec_key = 1;
        if ( isset( $raw_book['Sections'] ) && $raw_book['Sections'] ) {
            foreach ( $raw_book['Sections'] as $key_section => $section ) {
                $html .= "\n";
                $el_key = 1;
                if ( isset( $section['Elements'] ) && $section['Elements'] ) {
                    foreach ( $section['Elements'] as $key_element => $element ) {
                        $toc = '';
                        $numbering = '';
                        $web_print = '';
                        if ( isset( $element['Meta'] ) ) {
                            if ( isset( $element['Meta']['toc'] ) ) {
                                if ( isset( $element['Meta']['toc']['0'] ) ) {
                                    $toc = $element['Meta']['toc']['0'];
                                }
                            }
                        }
                        if ( isset( $element['Meta'] ) ) {
                            if ( isset( $element['Meta']['numbering'] ) ) {
                                if ( isset( $element['Meta']['numbering']['0'] ) ) {
                                    $numbering = $element['Meta']['numbering']['0'];
                                }
                            }
                        }
                        if ( isset( $element['Meta'] ) ) {
                            if ( isset( $element['Meta']['web_print'] ) ) {
                                if ( isset( $element['Meta']['web_print']['0'] ) ) {
                                    $web_print = $element['Meta']['web_print']['0'];
                                }
                            }
                        }
                        if ( $web_print === 'true' || $web_print === 'on' ) {
                            
                            if ( $toc && ($toc === 'true' || $toc === 'on') ) {
                                
                                if ( isset( $element['Content'] ) ) {
                                    
                                    if ( $toc_format === 'format1' ) {
                                        $html .= (string) ('<p><a class="' . $toc_format . ' num-' . $numbering . '" data-elid="' . $element['ID'] . '" data-target-page="' . $key_element . '" href="#"><span class="toc_page_n">1 - 1  -</span> <u> - ' . $key_element . '  </u></a></p>');
                                    } else {
                                        $html .= (string) ('<p><a class="' . $toc_format . ' num-' . $numbering . '" data-elid="' . $element['ID'] . '" data-target-page="' . $key_element . '" href="#"><u>' . $key_element . '  </u>  <span class="toc_page_n"> - 1 - 1</span></a></p>');
                                    }
                                    
                                    $html .= "\n";
                                }
                                
                                $el_key++;
                            }
                        
                        }
                    }
                }
                $sec_key++;
            }
        }
        return $html;
    }

}