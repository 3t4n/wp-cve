<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link		https://wordpress.org/plugins/book-press
 * @since		1.0.0
 *
 * @package	Book_Press
 * @subpackage Book_Press/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package	Book_Press
 * @subpackage Book_Press/admin
 * @author	 Md Kabir Uddin <bd.kabiruddin@gmail.com>
 */
class Book_Press_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since	1.0.0
     * @access	private
     * @var		string	$plugin_name	The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since	1.0.0
     * @access	private
     * @var		string	$version	The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since	1.0.0
     * @param		string	$plugin_name		The name of this plugin.
     * @param		string	$version	The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since	1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Book_Press_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Book_Press_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/book-press-admin.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
    }
    
    public function editor_styles()
    {
        global  $pagenow ;
        if ( 'post.php' === $pagenow && isset( $_GET['post'] ) && 'book' === get_post_type( $_GET['post'] ) ) {
            add_editor_style(
                plugin_dir_url( __FILE__ ) . 'css/book-press-editor-style.css?',
                array(),
                $this->version,
                'all'
            );
        }
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since	1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Book_Press_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Book_Press_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/book-press-admin.js',
            array( 'jquery', 'wp-color-picker' ),
            $this->version,
            false
        );
        global  $pagenow ;
        if ( 'post.php' === $pagenow && isset( $_GET['post'] ) && 'book' === get_post_type( $_GET['post'] ) ) {
            wp_enqueue_script( 'jquery-ui-dialog' );
        }
    }
    
    /**
     * UI Page handler
     *
     * @return void
     */
    public function book_press_page_index()
    {
        include dirname( __FILE__ ) . '/partials/book-press-admin-display.php';
    }
    
    public function book_press_page_help()
    {
        include dirname( __FILE__ ) . '/partials/book-press-admin-help.php';
    }
    
    /**
     * Get the admin menu position
     *
     * @return int the position of the menu
     */
    public function book_press_menu_position()
    {
        return apply_filters( 'book_press_menu_position', 48 );
    }
    
    /**
     * Add menu items
     *
     * @return void
     */
    public function book_press_admin_menu()
    {
        $plugin = new Book_Press();
        $capability = $plugin->book_press_get_publish_cap();
        
        if ( book_press_fs()->is_not_paying() ) {
            add_menu_page(
                __( 'BookPress', 'book-press' ),
                __( 'BookPress', 'book-press' ),
                $capability,
                'book-press',
                array( $this, 'book_press_page_index' ),
                'dashicons-book-alt',
                $this->book_press_menu_position()
            );
        } else {
            add_menu_page(
                __( 'BookPress Pro', 'book-press' ),
                __( 'BookPress Pro', 'book-press' ),
                $capability,
                'book-press',
                array( $this, 'book_press_page_index' ),
                'dashicons-book-alt',
                $this->book_press_menu_position()
            );
        }
        
        add_submenu_page(
            'book-press',
            __( 'Add New', 'book-press' ),
            __( 'Add New', 'book-press' ),
            $capability,
            'post-new.php?post_type=book'
        );
        add_submenu_page(
            'book-press',
            __( 'Genres', 'book-press' ),
            __( 'Genres', 'book-press' ),
            'manage_categories',
            'edit-tags.php?taxonomy=genre&post_type=book'
        );
        add_submenu_page(
            'book-press',
            __( 'Account Settings', 'book-press' ),
            __( 'Account Settings', 'book-press' ),
            $capability,
            'book-press-setting',
            array( $this, 'book_press_page_index' )
        );
    }
    
    /**
     * Register a book post type.
     *
     * @link http://codex.wordpress.org/Function_Reference/register_post_type
     */
    public function book_press_book_init()
    {
        $labels = array(
            'name'               => _x( 'Books', 'Post Type General Name', 'book-press' ),
            'singular_name'      => _x( 'Book', 'Post Type Singular Name', 'book-press' ),
            'menu_name'          => __( 'Book', 'book-press' ),
            'parent_item_colon'  => __( 'Parent Book', 'book-press' ),
            'all_items'          => __( 'All Books', 'book-press' ),
            'view_item'          => __( 'View Book', 'book-press' ),
            'add_new_item'       => __( 'Add Book', 'book-press' ),
            'add_new'            => __( 'Add New', 'book-press' ),
            'edit_item'          => __( 'Edit Book', 'book-press' ),
            'update_item'        => __( 'Update Book', 'book-press' ),
            'search_items'       => __( 'Search Book', 'book-press' ),
            'not_found'          => __( 'Not book found', 'book-press' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'book-press' ),
        );
        $rewrite = array(
            'slug'       => 'book',
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        );
        $args = array(
            'labels'              => $labels,
            'supports'            => array(
            'title',
            'editor',
            'thumbnail',
            'revisions',
            'page-attributes'
        ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => 'book-press',
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-portfolio',
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_in_rest'        => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
            'taxonomies'          => array( 'genre' ),
        );
        register_post_type( 'book', $args );
        $labels = array(
            'name'               => _x( 'Progress', 'Post Type General Name', 'progress-press' ),
            'singular_name'      => _x( 'Progress', 'Post Type Singular Name', 'progress-press' ),
            'menu_name'          => __( 'Progress', 'progress-press' ),
            'parent_item_colon'  => __( 'Parent Progress', 'progress-press' ),
            'all_items'          => __( 'All Progress', 'progress-press' ),
            'view_item'          => __( 'View Progress', 'progress-press' ),
            'add_new_item'       => __( 'Add Progress', 'progress-press' ),
            'add_new'            => __( 'Add New', 'progress-press' ),
            'edit_item'          => __( 'Edit Progress', 'progress-press' ),
            'update_item'        => __( 'Update Progress', 'progress-press' ),
            'search_items'       => __( 'Search Progress', 'progress-press' ),
            'not_found'          => __( 'Not progress found', 'progress-press' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'progress-press' ),
        );
        $rewrite = array(
            'slug'       => 'progress',
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        );
        $args = array(
            'labels'              => $labels,
            'supports'            => array( 'title' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => 'book-press',
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-portfolio',
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_in_rest'        => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
        );
        register_post_type( 'progress', $args );
    }
    
    // create two taxonomies, genres and writers for the post type "book"
    public function book_press_book_taxonomies()
    {
        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
            'name'              => _x( 'Genres', 'taxonomy general name', 'book-press' ),
            'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'book-press' ),
            'search_items'      => __( 'Search Genres', 'book-press' ),
            'all_items'         => __( 'All Genres', 'book-press' ),
            'parent_item'       => __( 'Parent Genre', 'book-press' ),
            'parent_item_colon' => __( 'Parent Genre:', 'book-press' ),
            'edit_item'         => __( 'Edit Genre', 'book-press' ),
            'update_item'       => __( 'Update Genre', 'book-press' ),
            'add_new_item'      => __( 'Add New Genre', 'book-press' ),
            'new_item_name'     => __( 'New Genre Name', 'book-press' ),
            'menu_name'         => __( 'Genre', 'book-press' ),
        );
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_menu'      => 'book-press',
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array(
            'slug' => 'genre',
        ),
        );
        register_taxonomy( 'genre', array( 'book' ), $args );
    }
    
    function disable_new_posts()
    {
        // Hide sidebar link
        global  $submenu ;
        foreach ( $submenu['book-press'] as $key => $value ) {
            if ( $value[0] === 'All Progress' ) {
                unset( $submenu['book-press'][$key] );
            }
        }
        // Hide link on listing page
        if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'progress' ) {
            echo  '<style type="text/css">
			#favorite-actions, .add-new-h2, .tablenav, a.page-title-action { display:none; }
			</style>' ;
        }
    }
    
    /**
     * book press set current menu
     *
     */
    public function book_press_set_current_menu( $parent_file )
    {
        global  $submenu_file, $current_screen, $pagenow ;
        # Set the submenu as active/current while anywhere in your Custom Post Type (book)
        
        if ( $current_screen->post_type == 'book' ) {
            if ( $pagenow == 'edit-tags.php' ) {
                $submenu_file = 'edit-tags.php?taxonomy=genre&post_type=' . $current_screen->post_type;
            }
            $parent_file = 'book-press';
        }
        
        return $parent_file;
    }
    
    /**
     * Add the custom columns to the book post type
     *
     */
    public function set_custom_edit_book_columns( $columns )
    {
        $columns['title'] = __( 'Book Title', 'book-press' );
        $columns['word_count'] = __( 'Word Count', 'book-press' );
        $columns['chapters'] = __( 'Chapters', 'book-press' );
        $columns['shortcode'] = __( 'Shortcode', 'book-press' );
        return $columns;
    }
    
    /**
     * Add the custom columns to the book post type
     *
     */
    public function _pro_set_custom_edit_book_columns( $columns )
    {
        $columns['progress'] = __( 'Progress', 'book-press' );
        return $columns;
    }
    
    /**
     * Add the data to the custom columns for the book post type
     *
     */
    public function custom_book_column( $column, $post_id )
    {
        switch ( $column ) {
            case 'word_count':
                echo  $this->get_book_word_count( $post_id ) ;
                break;
            case 'chapters':
                echo  get_post_meta( $post_id, 'chapter_count', true ) ;
                break;
            case 'shortcode':
                echo  '[book id=' . $post_id . ']' ;
                break;
        }
    }
    
    /**
     * Add the data to the custom columns for the book post type
     *
     */
    public function _pro_custom_book_column( $column, $post_id )
    {
        $progress = get_post_meta( $post_id, 'progress', true );
        
        if ( !get_post( $progress ) ) {
            $progressargs = array(
                'post_title'  => 'Progress of ' . $post_id,
                'post_type'   => 'progress',
                'post_status' => 'draft',
                'post_author' => get_current_user_id(),
            );
            $progress_id = wp_insert_post( $progressargs );
            update_post_meta( $post_id, 'progress', $progress_id );
            update_post_meta( $progress_id, 'progress', $post_id );
            update_post_meta( $progress_id, 'book_pro_a_start_date', explode( ' ', get_post( $post_id )->post_date )[0] );
        }
        
        switch ( $column ) {
            case 'progress':
                $progress = get_post_meta( $post_id, 'progress', true );
                if ( get_post( $progress ) ) {
                    
                    if ( $progress ) {
                        if ( get_post( $progress )->post_status === 'draft' ) {
                            echo  '<a href="' . get_edit_post_link( $progress ) . '">Setup</a>' ;
                        }
                        if ( get_post( $progress )->post_status === 'publish' ) {
                            echo  '<a href="' . get_edit_post_link( $progress ) . '">View</a>' ;
                        }
                    }
                
                }
                break;
        }
    }
    
    /**
     * Adds the meta box container.
     */
    public function book_press_add_meta_box( $post_type )
    {
        $initialized_genres = get_option( 'initialized_genres' );
        
        if ( !$initialized_genres ) {
            $genres = '{"0":{"genre":"Fiction","childs":{"0":{"genre":"Adventure novel","childs":{}},"1":{"genre":"Children\'s literature","childs":{}},"2":{"genre":"Young adult fiction","childs":{}},"3":{"genre":"Education fiction","childs":{}},"4":{"genre":"Erotic fiction","childs":{}},"5":{"genre":"Experimental fiction","childs":{}},"6":{"genre":"Graphic novel","childs":{}},"7":{"genre":"Historical fiction","childs":{"0":{"genre":"Historical romance","childs":{}},"1":{"genre":"Historical Mystery","childs":{}},"2":{"genre":"Holocaust Novels","childs":{}},"3":{"genre":"Plantation tradition","childs":{}},"4":{"genre":"Prehistoric fiction","childs":{}},"5":{"genre":"Regency novel","childs":{}},"6":{"genre":"Contradiction","childs":{}}}},"8":{"genre":"Literary fiction","childs":{}},"9":{"genre":"Literary nonsense","childs":{}},"10":{"genre":"Mathematical fiction","childs":{}},"11":{"genre":"Metafiction","childs":{}},"12":{"genre":"Nonfiction novel","childs":{"0":{"genre":"Bildungsroman","childs":{}},"1":{"genre":"Biographical novel","childs":{}},"2":{"genre":"Slave narrative","childs":{}}}},"13":{"genre":"Occupational Fiction","childs":{"0":{"genre":"Hollywood novel","childs":{}},"1":{"genre":"Lab lit","childs":{}},"2":{"genre":"Legal thriller","childs":{}},"3":{"genre":"Medical fiction","childs":{}},"4":{"genre":"Musical fiction","childs":{}},"5":{"genre":"Sports fiction","childs":{}}}},"14":{"genre":"Philosophical fiction","childs":{"0":{"genre":"Existentialist fiction","childs":{}},"1":{"genre":"Novel of ideas","childs":{}},"2":{"genre":"Platonic Dialogues","childs":{}}}},"15":{"genre":"Political fiction","childs":{"0":{"genre":"Political satire","childs":{}}}},"16":{"genre":"Pulp fiction","childs":{}},"17":{"genre":"Quantum fiction","childs":{}},"18":{"genre":"Religious fiction","childs":{"0":{"genre":"Christian fiction","childs":{}},"1":{"genre":"Islamic fiction","childs":{}},"2":{"genre":"Jewish fiction[3]","childs":{}}}},"19":{"genre":"Saga","childs":{"0":{"genre":"Family saga","childs":{}}}},"20":{"genre":"Speculative fiction","childs":{"0":{"genre":"Fantasy","childs":{}},"1":{"genre":"Horror","childs":{}},"2":{"genre":"Science fiction","childs":{}}}},"21":{"genre":"Suspense fiction","childs":{"0":{"genre":"Crime fiction","childs":{}},"1":{"genre":"Detective fiction","childs":{}},"2":{"genre":"Gong\'an fiction","childs":{}},"3":{"genre":"Mystery fiction","childs":{}}}},"22":{"genre":"Thriller","childs":{"0":{"genre":"Mystery fiction","childs":{}},"1":{"genre":"Legal thriller","childs":{}},"2":{"genre":"Medical thriller","childs":{}},"3":{"genre":"Political thriller","childs":{}},"4":{"genre":"Psychological thriller","childs":{}},"5":{"genre":"Techno-thriller","childs":{}}}},"23":{"genre":"Tragedy","childs":{}},"24":{"genre":"Urban fiction","childs":{}},"25":{"genre":"Westerns","childs":{}},"26":{"genre":"Women\'s fiction","childs":{"0":{"genre":"Class S","childs":{}},"1":{"genre":"Femslash","childs":{}},"2":{"genre":"Matron literature","childs":{}},"3":{"genre":"Romance novel","childs":{}},"4":{"genre":"Yaoi","childs":{}},"5":{"genre":"Yuri","childs":{}}}},"27":{"genre":"Workplace tell-all","childs":{}},"28":{"genre":"General cross-genre","childs":{"0":{"genre":"Historical romance","childs":{}},"1":{"genre":"Juvenile fantasy","childs":{}},"2":{"genre":"LGBT pulp fiction","childs":{}},"3":{"genre":"Paranormal romance","childs":{}},"4":{"genre":"Romantic fantasy","childs":{}},"5":{"genre":"Tragicomedy","childs":{}}}}}},"1":{"genre":"Non-Fiction","childs":{"0":{"genre":"Autograph","childs":{}},"1":{"genre":"Biography","childs":{}},"2":{"genre":"Commentary","childs":{}},"3":{"genre":"Creative nonfiction","childs":{}},"4":{"genre":"Critique","childs":{}},"5":{"genre":"Cult literature","childs":{}},"6":{"genre":"Diaries and journals","childs":{}},"7":{"genre":"Didactic","childs":{}},"8":{"genre":"Erotic literature","childs":{}},"9":{"genre":"Essay, treatise","childs":{}},"10":{"genre":"History","childs":{}},"11":{"genre":"Lament","childs":{}},"12":{"genre":"Law","childs":{}},"13":{"genre":"Letter","childs":{}},"14":{"genre":"Manuscript","childs":{}},"15":{"genre":"Philosophy","childs":{}},"16":{"genre":"Poetry","childs":{}},"17":{"genre":"Religious text","childs":{}},"18":{"genre":"Scientific writing","childs":{}},"19":{"genre":"Testament","childs":{}},"20":{"genre":"True crime","childs":{}}}}}';
            $genres = json_decode( $genres );
            foreach ( $genres as $key => $genre ) {
                
                if ( !term_exists( $genre->genre, 'genre' ) ) {
                    $genre_id_1 = wp_insert_term( $genre->genre, 'genre', $args = array() );
                    if ( $genre->childs ) {
                        foreach ( $genre->childs as $key => $genre ) {
                            
                            if ( !term_exists( $genre->genre, 'genre' ) ) {
                                $genre_id_2 = wp_insert_term( $genre->genre, 'genre', $args = array(
                                    'parent' => $genre_id_1['term_id'],
                                ) );
                                if ( $genre->childs ) {
                                    foreach ( $genre->childs as $key => $genre ) {
                                        if ( !term_exists( $genre->genre, 'genre' ) ) {
                                            $genre_id_3 = wp_insert_term( $genre->genre, 'genre', $args = array(
                                                'parent' => $genre_id_2['term_id'],
                                            ) );
                                        }
                                    }
                                }
                            }
                        
                        }
                    }
                }
            
            }
            update_option( 'initialized_genres', time() );
        }
        
        $Book_Press = new Book_Press();
        $licence = $Book_Press->licence();
        $args = array(
            'post_type'  => 'book',
            'meta_query' => array( array(
            'key'   => 'type',
            'value' => array( 'book' ),
        ) ),
        );
        $query = new WP_Query( $args );
        if ( $licence['type'] === 'free' && $query->post_count > 1 ) {
            
            if ( $post_type === 'book' ) {
                add_action( 'edit_form_after_title', function () {
                    echo  '<h3 style="text-align:center;line-height: 24px;min-height: 25px;margin-top: 5px;padding: 25px 10px;color: #dc3232;">
						    You required Pro version, Or you can keep single book.
						    </h3>' ;
                } );
                return null;
            }
        
        }
        
        if ( in_array( $post_type, array( 'book' ) ) ) {
            global  $post ;
            $post_id = $post->ID;
            $book_type = get_post_meta( $post_id, 'type', true );
            $progress = get_post_meta( $post_id, 'progress', true );
            
            if ( !$progress ) {
                $progress = array(
                    'post_title'  => 'Progress of ' . $post_id,
                    'post_type'   => 'progress',
                    'post_status' => 'draft',
                    'post_author' => get_current_user_id(),
                );
                $progress_id = wp_insert_post( $progress );
                update_post_meta( $progress_id, 'progress', $post_id );
                update_post_meta( $post_id, 'progress', $progress_id );
                update_post_meta( $progress_id, 'book_pro_a_start_date', explode( ' ', get_post( $post_id )->post_date )[0] );
            }
            
            /// check
            
            if ( $post->post_status === 'auto-draft' || $book_type === 'book' ) {
                
                if ( $post->post_title === 'Auto Draft' || !$post->post_title ) {
                    $update_post = array(
                        'ID'          => $post_id,
                        'post_title'  => 'Untitled Book',
                        'post_status' => 'publish',
                    );
                    wp_update_post( $update_post );
                }
                
                add_meta_box(
                    'book-main-meta-box',
                    __( 'Book Sections', 'textdomain' ),
                    array( $this, 'render_meta_box_content' ),
                    $post_type,
                    'normal',
                    'high'
                );
                update_post_meta( $post_id, 'type', 'book' );
                
                if ( !$post->post_parent && $book_type !== 'book' ) {
                    $sections = array(
                        'Cover Matter' => array( 'Cover Image', 'Summary', 'Testimonials' ),
                        'Front Matter' => array(
                        'Copyright',
                        'Biography of Author',
                        'Dedication',
                        'Title Page',
                        'Table of Contents'
                    ),
                        'Body Matter'  => array( 'Chapter 01 - The Begining' ),
                        'End Matter'   => array( 'Index' ),
                    );
                    foreach ( $sections as $key => $section ) {
                        $new_section = array(
                            'post_title'  => wp_strip_all_tags( $key ),
                            'post_type'   => 'book',
                            'post_status' => 'publish',
                            'post_author' => get_current_user_id(),
                            'post_parent' => $post_id,
                        );
                        $section_id = wp_insert_post( $new_section );
                        update_post_meta( $section_id, 'type', 'section' );
                        $elements = $section;
                        if ( $section_id ) {
                            foreach ( $elements as $key => $element ) {
                                $new_element = array(
                                    'post_title'  => wp_strip_all_tags( $element ),
                                    'post_type'   => 'book',
                                    'post_status' => 'publish',
                                    'post_author' => get_current_user_id(),
                                    'post_parent' => $section_id,
                                );
                                $element_id = wp_insert_post( $new_element );
                                update_post_meta( $element_id, 'type', 'element' );
                            }
                        }
                    }
                }
            
            }
        
        }
    
    }
    
    /**
     * Adds the meta box container.
     */
    public function _pro_book_press_add_meta_box( $post_type )
    {
        add_meta_box(
            'progress-main-meta-box',
            __( 'Progress Tracking Setting and Reports', 'textdomain' ),
            array( $this, '_pro_render_meta_box_content' ),
            'progress',
            'normal',
            'high'
        );
    }
    
    public function add_book_section()
    {
        $post_ID = $_POST['post_ID'];
        $name = $_POST['name'];
        // Create post object
        $section = array(
            'post_title'  => wp_strip_all_tags( $_POST['name'] ),
            'post_type'   => 'book',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_parent' => $post_ID,
        );
        // Insert the post into the database
        $post_id = wp_insert_post( $section );
        update_post_meta( $post_id, 'type', 'section' );
        $args = array(
            'post_parent' => $post_ID,
            'post_type'   => 'book',
            'numberposts' => -1,
            'post_status' => 'any',
        );
        $children = get_children( $args );
        
        if ( $children ) {
            $update_post = array(
                'ID'         => $post_id,
                'menu_order' => count( $children ) - 1,
            );
            wp_update_post( $update_post );
            foreach ( $children as $key => $value ) {
                $value->edit_link = get_edit_post_link( $value->ID );
                echo  json_encode( $value ) ;
                die;
            }
        }
        
        die;
    }
    
    public function add_book_element()
    {
        $post_ID = $_POST['post_ID'];
        $name = $_POST['name'];
        // Create post object
        $element = array(
            'post_title'  => wp_strip_all_tags( $_POST['name'] ),
            'post_type'   => 'book',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'post_parent' => $post_ID,
        );
        // Insert the post into the database
        $element_id = wp_insert_post( $element );
        update_post_meta( $element_id, 'type', 'element' );
        $args = array(
            'post_parent' => $post_ID,
            'post_type'   => 'book',
            'numberposts' => -1,
            'post_status' => 'any',
        );
        $children = get_children( $args );
        
        if ( $children ) {
            $update_post = array(
                'ID'         => $element_id,
                'menu_order' => count( $children ) - 1,
            );
            wp_update_post( $update_post );
            foreach ( $children as $key => $value ) {
                $value->edit_link = get_edit_post_link( $value->ID );
                echo  json_encode( $value ) ;
                die;
            }
        }
        
        die;
    }
    
    public function delete_book_section_element()
    {
        $post_ID = $_POST['post_ID'];
        wp_delete_post( $post_ID );
        echo  $post_ID ;
        die;
    }
    
    public function update_menu_order()
    {
        global  $wpdb ;
        $post_IDs = $_POST['post_IDs'];
        $post_IDs = explode( ',', $post_IDs );
        $post_IDs_parent = $_POST['post_IDs_parent'];
        foreach ( $post_IDs as $key => $value ) {
            $my_post = array(
                'ID'          => $value,
                'menu_order'  => $key,
                'post_parent' => $post_IDs_parent,
            );
            $wpdb->update( $wpdb->posts, array(
                'menu_order'  => $key,
                'post_parent' => $post_IDs_parent,
            ), array(
                'ID' => $value,
            ) );
        }
        die;
    }
    
    function filter_books_by_genres( $post_type, $which )
    {
        // Apply this only on a specific post type
        if ( 'book' !== $post_type ) {
            return;
        }
        // A list of taxonomy slugs to filter by
        $taxonomies = array( 'genre' );
        foreach ( $taxonomies as $taxonomy_slug ) {
            // Retrieve taxonomy data
            $taxonomy_obj = get_taxonomy( $taxonomy_slug );
            $taxonomy_name = $taxonomy_obj->labels->name;
            // Retrieve taxonomy terms
            $terms = get_terms( $taxonomy_slug );
            // Display filter HTML
            echo  "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>" ;
            echo  '<option value="">' . sprintf( esc_html__( 'Show All %s', 'text_domain' ), $taxonomy_name ) . '</option>' ;
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    $term->slug,
                    ( isset( $_GET[$taxonomy_slug] ) && $_GET[$taxonomy_slug] == $term->slug ? ' selected="selected"' : '' ),
                    $term->name,
                    $term->count
                );
            }
            echo  '</select>' ;
        }
    }
    
    // define the delete_post callback
    function action_wp_trash_post( $post_id )
    {
        $args = array(
            'post_parent' => $post_id,
            'post_type'   => 'book',
            'numberposts' => -1,
            'post_status' => 'any',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
        );
        $childrens = get_children( $args );
        if ( $childrens ) {
            foreach ( $childrens as $key => $value ) {
                wp_trash_post( $value->ID );
            }
        }
    }
    
    // define the delete_post callback
    function action_delete_post( $post_id )
    {
        $args = array(
            'post_parent' => $post_id,
            'post_type'   => 'book',
            'numberposts' => -1,
            'post_status' => 'any',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
        );
        $childrens = get_children( $args );
        if ( $childrens ) {
            foreach ( $childrens as $key => $value ) {
                wp_delete_post( $value->ID );
            }
        }
    }
    
    function hide_post_editor()
    {
        $screen = $_SERVER['REQUEST_URI'];
        if ( $screen === '/wp-admin/post-new.php?post_type=book' ) {
            remove_post_type_support( 'book', 'editor' );
        }
        
        if ( isset( $_GET['post'] ) ) {
            $post_id = $_GET['post'];
            if ( !isset( $post_id ) ) {
                return;
            }
            $args = array(
                'post_parent' => $post_id,
                'post_type'   => 'book',
                'numberposts' => -1,
                'post_status' => 'any',
                'orderby'     => 'menu_order',
                'order'       => 'ASC',
            );
            $sections = get_children( $args );
            if ( $sections ) {
                remove_post_type_support( 'book', 'editor' );
            }
            if ( get_post( $post_id ) ) {
                if ( get_post( $post_id )->post_title === 'Table of Contents' ) {
                    remove_post_type_support( 'book', 'editor' );
                }
            }
        }
    
    }
    
    public function query_set_only_book( $wp_query )
    {
        $screen = $_SERVER['REQUEST_URI'];
        
        if ( strpos( $screen, '/wp-admin/edit.php?post_type=book' ) !== false ) {
            global  $typenow ;
            
            if ( 'book' === $typenow ) {
                $wp_query->set( 'meta_query', array( array(
                    'key'   => 'type',
                    'value' => 'book',
                ) ) );
                return;
            }
        
        }
    
    }
    
    public function views_filter_for_own_posts( $views )
    {
        unset( $views['mine'] );
        $new_views = array(
            'all'     => __( 'All' ),
            'publish' => __( 'Published' ),
            'future'  => __( 'Scheduled' ),
            'draft'   => __( 'Draft' ),
            'pending' => __( 'Pending' ),
            'private' => __( 'Private' ),
            'trash'   => __( 'Trash' ),
        );
        foreach ( $new_views as $view => $name ) {
            $query = array(
                'meta_query' => array( array(
                'key'   => 'type',
                'value' => 'book',
            ) ),
                'post_type'  => 'book',
            );
            
            if ( $view == 'all' ) {
                $query['all_posts'] = 1;
                $class = ( get_query_var( 'all_posts' ) == 1 || get_query_var( 'post_status' ) == '' ? ' class="current"' : '' );
                $url_query_var = 'all_posts=1';
            } else {
                $query['post_status'] = $view;
                $class = ( get_query_var( 'post_status' ) == $view ? ' class="current"' : '' );
                $url_query_var = 'post_status=' . $view;
            }
            
            $result = new WP_Query( $query );
            
            if ( $result->found_posts > 0 ) {
                if ( $name === 'Draft' ) {
                    if ( $result->found_posts > 1 ) {
                        $name = 'Drafts';
                    }
                }
                $views[$view] = sprintf( '<a href="%s"' . $class . '>' . __( $name ) . ' <span class="count">(%d)</span></a>', admin_url( 'edit.php?post_type=book&' . $url_query_var ), $result->found_posts );
            } else {
                unset( $views[$view] );
            }
        
        }
        return $views;
    }
    
    public function book_press_register_meta_boxes()
    {
        global  $post ;
        if ( get_post_meta( $post->ID, 'type', true ) === 'element' ) {
            add_meta_box(
                'elementoption',
                __( 'Element Options', 'textdomain' ),
                array( $this, 'render_meta_box_content_element' ),
                'book',
                'side',
                'high'
            );
        }
        if ( get_post_meta( $post->ID, 'type', true ) === 'book' ) {
            add_meta_box(
                'page_background_color',
                __( 'Style Book Page', 'textdomain' ),
                array( $this, 'render_meta_box_page_backgroundcolor' ),
                'book',
                'normal',
                'low'
            );
        }
        if ( get_post_meta( $post->ID, 'type', true ) === 'book' ) {
            add_meta_box(
                'pagination_option',
                __( 'Pagination Options', 'textdomain' ),
                array( $this, 'render_meta_box_content_pagination' ),
                'book',
                'normal',
                'high'
            );
        }
        if ( get_post( $post->ID )->post_title === 'Table of Contents' ) {
            add_meta_box(
                'toc_option',
                __( 'Table of Contents Option', 'textdomain' ),
                array( $this, 'render_meta_box_toc_element' ),
                'book',
                'normal',
                'high'
            );
        }
    }
    
    public function book_press_save_meta_box( $post_id )
    {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        
        if ( get_post( $post_id )->post_type === 'book' ) {
            $toc_format = ( isset( $_POST['toc_format'] ) ? $_POST['toc_format'] : null );
            if ( $toc_format ) {
                update_post_meta( $post_id, 'toc_format', $toc_format );
            }
            $web_print = ( isset( $_POST['web_print'] ) ? $_POST['web_print'] : null );
            $toc = ( isset( $_POST['toc'] ) ? $_POST['toc'] : null );
            $word_count = ( isset( $_POST['word_count'] ) ? $_POST['word_count'] : null );
            $numbering = ( isset( $_POST['numbering'] ) ? $_POST['numbering'] : null );
            $pagination = ( isset( $_POST['pagination'] ) ? 'on' : '' );
            $page_break = ( isset( $_POST['page_break'] ) ? $_POST['page_break'] : '' );
            $paragraph_togeather = ( isset( $_POST['paragraph_togeather'] ) ? $_POST['paragraph_togeather'] : '' );
            $ajax_pagination = ( isset( $_POST['ajax_pagination'] ) ? $_POST['ajax_pagination'] : '' );
            $ajax_pages_type = ( isset( $_POST['ajax_pages_type'] ) ? $_POST['ajax_pages_type'] : '' );
            $ajax_prev_text = ( isset( $_POST['ajax_prev_text'] ) ? $_POST['ajax_prev_text'] : '' );
            $ajax_next_text = ( isset( $_POST['ajax_next_text'] ) ? $_POST['ajax_next_text'] : '' );
            $pagination_location_y = ( isset( $_POST['pagination_location_y'] ) ? $_POST['pagination_location_y'] : 'top' );
            $pagination_location_x = ( isset( $_POST['pagination_location_x'] ) ? $_POST['pagination_location_x'] : 'left' );
            update_post_meta( $post_id, 'web_print', $web_print );
            update_post_meta( $post_id, 'toc', $toc );
            update_post_meta( $post_id, 'word_count', $word_count );
            update_post_meta( $post_id, 'numbering', $numbering );
            update_post_meta( $post_id, 'pagination', $pagination );
            update_post_meta( $post_id, 'page_break', $page_break );
            update_post_meta( $post_id, 'paragraph_togeather', $paragraph_togeather );
            update_post_meta( $post_id, 'ajax_pagination', $ajax_pagination );
            update_post_meta( $post_id, 'ajax_pages_type', $ajax_pages_type );
            update_post_meta( $post_id, 'ajax_prev_text', $ajax_prev_text );
            update_post_meta( $post_id, 'ajax_next_text', $ajax_next_text );
            update_post_meta( $post_id, 'pagination_location_x', $pagination_location_x );
            update_post_meta( $post_id, 'pagination_location_y', $pagination_location_y );
            $plugin = new Book_Press();
            $book_id = $plugin->book_press_get_book_id( $post_id );
            $total_word = ( get_post_meta( $book_id, 'total_word', true ) ? get_post_meta( $book_id, 'total_word', true ) : array() );
            $total_word[date( 'Y-m-d' )] = $this->get_book_word_count( $book_id );
            update_post_meta( $book_id, 'total_word', $total_word );
            $arrContextOptions = array(
                "ssl" => array(
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ),
            );
            $args = array(
                'post_parent' => $book_id,
                'post_type'   => 'book',
                'numberposts' => -1,
                'post_status' => 'any',
                'orderby'     => 'menu_order',
                'order'       => 'ASC',
            );
            $sections = get_children( $args );
            foreach ( $sections as $key => $section ) {
                
                if ( $section->post_title === 'Body Matter' ) {
                    $args = array(
                        'post_parent' => $section->ID,
                        'post_type'   => 'book',
                        'numberposts' => -1,
                        'post_status' => 'any',
                        'orderby'     => 'menu_order',
                        'order'       => 'ASC',
                    );
                    $elements = get_children( $args );
                    update_post_meta( $book_id, 'chapter_count', count( $elements ) );
                }
            
            }
        }
    
    }
    
    public function render_meta_box_toc_element( $post )
    {
        $post_id = $post->ID;
        $toc_format = get_post_meta( $post_id, 'toc_format', true );
        ?>
		<p>You can choose one of the following formate for the Table of Contents</p>
		<input type="radio" value="format1" name="toc_format" <?php 
        if ( isset( $toc_format ) ) {
            if ( $toc_format === 'format1' ) {
                ?> checked <?php 
            }
        }
        ?> /> 1 - Element Title<br>
		<input type="radio" value="format2" name="toc_format" <?php 
        if ( isset( $toc_format ) ) {
            if ( $toc_format === 'format2' ) {
                ?> checked <?php 
            }
        }
        ?> /> Element Title .................... 01<br>
		<?php 
    }
    
    public function render_meta_box_content_element( $post )
    {
        $post_id = $post->ID;
        $web_print = get_post_meta( $post_id, 'web_print', true );
        $toc = get_post_meta( $post_id, 'toc', true );
        $word_count = get_post_meta( $post_id, 'word_count', true );
        $numbering = get_post_meta( $post_id, 'numbering', true );
        ?>
		<input type="checkbox" id="web_print" <?php 
        if ( isset( $web_print ) ) {
            ?> checked <?php 
        }
        ?> name="web_print" /> Visible in Web Pages and Print<br>
		<input type="checkbox" id="toc" <?php 
        if ( isset( $toc ) ) {
            ?> checked <?php 
        }
        ?> name="toc" /> Include in Table of Contents<br>
		<input type="checkbox" id="word_count" <?php 
        if ( isset( $word_count ) ) {
            ?> checked <?php 
        }
        ?> name="word_count" /> Include in Word Count<br>
		<input type="checkbox" id="numbering" <?php 
        if ( isset( $numbering ) ) {
            ?> checked <?php 
        }
        ?> name="numbering" /> Include in Page Numbering<br>
		<?php 
    }
    
    public function render_meta_box_content_pagination( $post )
    {
        $post_id = $post->ID;
        $pagination = get_post_meta( $post_id, 'pagination', true );
        $page_break = get_post_meta( $post_id, 'page_break', true );
        $paragraph_togeather = get_post_meta( $post_id, 'paragraph_togeather', true );
        $ajax_pagination = get_post_meta( $post_id, 'ajax_pagination', true );
        $ajax_pages_type = get_post_meta( $post_id, 'ajax_pages_type', true );
        $ajax_prev_text = get_post_meta( $post_id, 'ajax_prev_text', true );
        $ajax_next_text = get_post_meta( $post_id, 'ajax_next_text', true );
        $pagination_location_y = ( get_post_meta( $post_id, 'pagination_location_y', true ) ? get_post_meta( $post_id, 'pagination_location_y', true ) : 'top' );
        $pagination_location_x = ( get_post_meta( $post_id, 'pagination_location_x', true ) ? get_post_meta( $post_id, 'pagination_location_x', true ) : 'left' );
        ?>
		<input type="checkbox" <?php 
        if ( $pagination ) {
            ?> checked <?php 
        }
        ?>  name="pagination" /> Switch On (Pagination allows you to move forward and back to different pages)<br>
		<h4><br>Pagination Type</h4>
		<input type="radio" <?php 
        
        if ( isset( $ajax_pages_type ) ) {
            if ( $ajax_pages_type == 'numbers' ) {
                ?> checked <?php 
            }
        } else {
            ?> checked <?php 
        }
        
        ?>  name="ajax_pages_type" value="numbers"> Pages Numbers
		<input type="radio" <?php 
        if ( isset( $ajax_pages_type ) ) {
            if ( $ajax_pages_type == 'buttons' ) {
                ?> checked <?php 
            }
        }
        ?>  name="ajax_pages_type" value="buttons"> Prev/Next Buttons
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><br>
					<h4>Prev Button Text</h4>
					<input type="text" name="ajax_prev_text" value="<?php 
        
        if ( isset( $ajax_prev_text ) ) {
            echo  $ajax_prev_text ;
        } else {
            ?><< Prev<?php 
        }
        
        ?>">
				</td>
				<td><br>
					<h4>Next Button Text</h4>
					<input type="text" name="ajax_next_text" value="<?php 
        
        if ( isset( $ajax_next_text ) ) {
            echo  $ajax_next_text ;
        } else {
            ?>Next >><?php 
        }
        
        ?>">
				</td>
			</tr>
		</table>
		<h4><br>Page Number Location</h4>
		<input type="radio" name="pagination_location_y" <?php 
        
        if ( isset( $pagination_location_y ) ) {
            if ( $pagination_location_y == 'top' ) {
                ?> checked <?php 
            }
        } else {
            ?> checked <?php 
        }
        
        ?> value="top"> Top
		<input type="radio" name="pagination_location_y" <?php 
        if ( isset( $pagination_location_y ) ) {
            if ( $pagination_location_y == 'bottom' ) {
                ?> checked <?php 
            }
        }
        ?> value="bottom"> Bottom
		<br>
		<input type="radio" name="pagination_location_x" <?php 
        if ( isset( $pagination_location_x ) ) {
            if ( $pagination_location_x == 'left' ) {
                ?> checked <?php 
            }
        }
        ?> value="left"> Left
		<input type="radio" name="pagination_location_x" <?php 
        if ( isset( $pagination_location_x ) ) {
            if ( $pagination_location_x == 'center' ) {
                ?> checked <?php 
            }
        }
        ?> value="center"> Center
		<input type="radio" name="pagination_location_x" <?php 
        if ( isset( $pagination_location_x ) ) {
            if ( $pagination_location_x == 'right' ) {
                ?> checked <?php 
            }
        }
        ?> value="right"> Right
		<?php 
    }
    
    public function render_meta_box_page_backgroundcolor( $post )
    {
        $bd_style = array(
            'dotted',
            'dashed',
            'solid',
            'double',
            'groove',
            'ridge',
            'inset',
            'outset',
            'none',
            'hidden'
        );
        $book_fonts = array(
            'Arial',
            'Arial Narrow',
            'sans-serif',
            'Helvetica',
            'Verdana',
            'Trebuchet MS',
            'Gill Sans',
            'Noto Sans',
            'Optima'
        );
        $post_id = $post->ID;
        $book_style = get_post_meta( $post_id, 'book', true );
        
        if ( isset( $book_style['page']['bg_color'] ) ) {
            $book_bg_color = $book_style['page']['bg_color'];
        } else {
            $book_bg_color = '#fff';
        }
        
        
        if ( isset( $book_style['page']['font_color'] ) ) {
            $book_font_color = $book_style['page']['font_color'];
        } else {
            $book_font_color = '###FF0000';
        }
        
        
        if ( isset( $book_style['page']['font'] ) ) {
            $font_name = $book_style['page']['font'];
        } else {
            $font_name = 'sans-serif';
        }
        
        
        if ( isset( $book_style['page']['font_size'] ) ) {
            $font_size = $book_style['page']['font_size'];
        } else {
            $font_size = '22';
        }
        
        
        if ( isset( $book_style['border']['border'] ) ) {
            $border_type = $book_style['page']['border'];
        } else {
            //$border_type = '';
        }
        
        
        if ( isset( $book_style['border']['style'] ) ) {
            $page_border_style = $book_style['border']['style'];
        } else {
            //$page_border_style = '';
        }
        
        
        if ( isset( $book_style['border']['weight'] ) ) {
            $page_border_weight = $book_style['border']['weight'];
        } else {
            //$page_border_weight = '';
        }
        
        
        if ( isset( $book_style['border']['radius'] ) ) {
            $page_border_radius = $book_style['border']['radius'];
        } else {
            //$page_border_radius = '';
        }
        
        
        if ( isset( $book_style['page']['navigation_color'] ) ) {
            $navigation_color = $book_style['page']['navigation_color'];
        } else {
            //navigation_color = '';
        }
        
        ?>
		<table>
				<thead>
						<tr>
							<th>Select Background color</th>
							<th></th>
							</tr>
							</thead>
							<tbody>
								<tr>
									<th> Background  Color:</th>
									<td></td>
									<td><input type="text" value="<?php 
        echo  $book_bg_color ;
        ?>" name="book[page][bg_color]" class="treechart-color-field"></td>
								</tr>
								
							</tbody>
						</table>

						<table class="table" border="0" width="100%">

						<tbody>
					<tr>
						<td colspan="2" class="higlighted">
						<h1>Text Options</h1>	
						</td>
					</tr>
					<tr>
						<td style="padding: 0px;" width="400">
							<table  class="table" border="0" width="400">
								<tbody>
									<tr>
										<th width="150" align="left">
										Name Font
										</th> 
										<td>
										<select name="book[page][font]">
										
        								<?php 
        foreach ( $book_fonts as $key => $value ) {
            ?>
								<option <?php 
            if ( $font_name == $value ) {
                echo  'selected' ;
            }
            ?> 
								value="<?php 
            echo  $value ;
            ?>">
								<?php 
            echo  ucfirst( $value ) ;
            ?>	
								</option>
									<?php 
        }
        ?>

										</select>
										</td>
									</tr>
									<tr>
										<th  align="left">
										 Font Size
										</th>
										<td>
										<select name="book[page][font_size]">
											<?php 
        for ( $i = 10 ;  $i < 25 ;  $i++ ) {
            ?>
												<option <?php 
            if ( $font_size == $i . 'px' ) {
                echo  'selected' ;
            }
            ?> value="<?php 
            echo  $i ;
            ?>px"><?php 
            echo  $i ;
            ?>px</option>
												<?php 
        }
        ?>
											
										</select>
										</td>
									</tr>
									<tr>
										<th  align="left">
										 Font Color
										</th>
										<td>
										<input value="<?php 
        echo  $book_font_color ;
        ?>"  type="text" name="book[page][font_color]" class="treechart-color-field">
										
										</td>
									</tr>
								</tbody>
							</table>

						</td>
						<td style="padding: 0px;">
						</td>
					</tr>
<!--
					<tr>
						<td colspan="2" class="higlighted">
						<h1>Page Border Styling</h1>	
						</td>
					</tr>
					<tr>
						<td style="padding: 0px;" width="400">
							<table  class="table" border="0" width="400">
								<tbody>
									<tr>
						<th width="150" align="left">
						Border Style
						</th>
						<td>
						<select name="book[border][style]">

						</select>
						</td>
					</tr>
					<tr>
						<th  align="left">
						Border Weight
						</th>
						<td>
						<select name="book[border][weight]">

							
						</select>
						</td>
					</tr>
					<tr>
						<th  align="left">
						Corner Radius
						</th>
						<td>
						<select name="book[border][radius]">

							
						</select>
						</td>
					</tr>


-->

					<tr>
						<th  align="left">
						Page Navigation Backgroud Color
						</th>
							<td>
							<input value="<?php 
        echo  $navigation_color ;
        ?>"  type="text" name="book[page][navigation_color]" class="treechart-color-field">
										
								</td>
							</tr>
					</tbody>
					</table>
							
						</td>
						<td style="padding: 0px;">
						</td>
					</tr>
				</tbody>
			</table>
		
		<?php 
    }
    
    public function render_meta_box_content()
    {
        $plugin = new Book_Press();
        global  $post ;
        $post_id = $post->ID;
        $args = array(
            'post_parent' => $post_id,
            'post_type'   => 'book',
            'numberposts' => -1,
            'post_status' => 'any',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
        );
        $sections = get_children( $args );
        ?>
		<div id="add-section" title="Add Section" style="display: none;">
			<form>
				<fieldset>
					<label for="name">Section Name</label>
					<br>
					<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all">
					<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
				</fieldset>
			</form>
		</div>
		<div id="add-element" title="Add Element" style="display: none;">
			<form>
				<fieldset>
					<label for="name">Element Name</label>
					<br>
					<input type="text" name="element_name" id="element_name" class="text ui-widget-content ui-corner-all">

					<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
				</fieldset>
			</form>
		</div>
		<div id="delete-section-element" title="Are you sure?" style="display: none;">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure to delete the entire section? Articles inside this section will be deleted too!</p>
		</div>
		<ul class="sections">
			<?php 
        foreach ( $sections as $key => $section ) {
            ?>
				<li class="section clear" data-id="<?php 
            echo  $section->ID ;
            ?>">
					<div class="section-inner">
						<a href="<?php 
            echo  get_edit_post_link( $section->ID ) ;
            ?>"><?php 
            echo  $section->post_title ;
            ?></a>
						<span class="btngroup">
							<button type="button" class="add-book-element">
								<span class="dashicons dashicons-plus-alt"></span>
							</button>
							<button type="button" class="drag">
								<span class="dashicons dashicons-menu"></span>
							</button>
							<button type="button" class="delete">
								<span class="dashicons dashicons-trash"></span>
							</button>
						</span>
					</div>
					<?php 
            $elements = $section;
            $args = array(
                'post_parent' => $section->ID,
                'post_type'   => 'book',
                'numberposts' => -1,
                'post_status' => 'any',
                'orderby'     => 'menu_order',
                'order'       => 'ASC',
            );
            $elements = get_children( $args );
            $totpage = 1;
            
            if ( $elements ) {
                ?> <ul class="elements clear"> <?php 
                foreach ( $elements as $key => $element ) {
                    $post_id = $element->ID;
                    $web_print = get_post_meta( $post_id, 'web_print', true );
                    $word_count = get_post_meta( $post_id, 'word_count', true );
                    $toc = get_post_meta( $post_id, 'toc', true );
                    $page_break = get_post_meta( $post_id, 'page_break', true );
                    $wordcount = preg_split(
                        "/\\s+/",
                        strip_tags( $element->post_content ),
                        0,
                        PREG_SPLIT_NO_EMPTY
                    );
                    $vvv = 0;
                    foreach ( $wordcount as $keys => $value ) {
                        if ( $value != ' ' && $value != '&nbsp;' && !empty($value) ) {
                            $vvv++;
                        }
                    }
                    $wordcount = $vvv;
                    
                    if ( $page_break > 0 ) {
                        $numberofpage = ceil( $wordcount / $page_break );
                    } else {
                        $numberofpage = 1;
                    }
                    
                    ?>
							<li class="element clear" data-id="<?php 
                    echo  $element->ID ;
                    ?>">
								<table width="100%">
									<tr>
										<td width="10">
											<div style="width: 200px;">
												<a href="<?php 
                    echo  get_edit_post_link( $element->ID ) ;
                    ?>"><?php 
                    echo  $element->post_title ;
                    ?></a>
											</div>
										</td>
										<td width="10">
											<div style="width: 150px;">
											<input class="inline-update" data-name="web_print" data-elid="<?php 
                    echo  $element->ID ;
                    ?>" type="checkbox" <?php 
                    if ( isset( $web_print ) ) {
                        if ( $web_print === 'true' || $web_print === 'on' ) {
                            ?> checked <?php 
                        }
                    }
                    ?>> Printing
										</div>
										</td>
										<td width="10">
											<div style="width: 150px;">
											Word Count - <?php 
                    
                    if ( isset( $word_count ) ) {
                        echo  $this->get_element_word_count( $element->ID ) ;
                    } else {
                        ?> Off <?php 
                    }
                    
                    ?>
										</div>
										</td>
										<td width="10">
											<div style="width: 150px;">
											<?php 
                    $start = ( get_post_meta( $element->ID, 'start', true ) ? get_post_meta( $element->ID, 'start', true ) : 0 );
                    $end = ( get_post_meta( $element->ID, 'end', true ) ? get_post_meta( $element->ID, 'end', true ) : 0 );
                    
                    if ( $section->post_title !== 'Cover Matter' ) {
                        
                        if ( $section->post_title === 'Front Matter' ) {
                            echo  'Page # - ' . $start . '-' . $end ;
                        } else {
                            echo  'Page # - ' . $start . '-' . $end ;
                        }
                    
                    } else {
                        echo  'Page # - Off' ;
                    }
                    
                    ?>
										</div>
											</td>
											<td width="100">


												<input class="inline-update" data-name="toc" data-elid="<?php 
                    echo  $element->ID ;
                    ?>" type="checkbox" <?php 
                    if ( isset( $toc ) ) {
                        if ( $toc === 'true' || $toc === 'on' ) {
                            ?> checked <?php 
                        }
                    }
                    ?>> TOC

											</td>
											<td width="10">
											<div style="width: 150px; float: right;">

												<span class="btngroup">
													<button type="button" class="drag">
														<span class="dashicons dashicons-menu"></span>
													</button>
													<button type="button" class="delete">
														<span class="dashicons dashicons-trash"></span>
													</button>
												</span>
											</div>
											</td>
										</tr>
									</table>
								</li>
								<?php 
                    $totpage += $numberofpage;
                }
                ?>
						</ul>
						<?php 
            }
            
            ?>
				</li>
				<?php 
        }
        ?>
			</ul>
			<button type="button" class="add-book-section button button-primary button-large">Add Section</button>
			<?php 
    }
    
    public function top_form_edit( $post )
    {
        $plugin = new Book_Press();
        $book_id = $plugin->book_press_get_book_id( $post->ID );
        if ( $post->post_parent ) {
            if ( 'book' == $post->post_type ) {
                echo  "<a style='padding: 4px 10px; display: inline-block; position: relative;  text-decoration: none;\tborder: none;\tborder: 1px solid #ccc;\tborder-radius: 2px;\tbackground: #f7f7f7;\ttext-shadow: none;\tfont-weight: 600;\tfont-size: 13px;\tline-height: normal;\tcolor: #0073aa;\tcursor: pointer;\toutline: 0;' href='" . get_edit_post_link( $book_id ) . "' id='my-custom-header-link'>< Back to Book page</a>" ;
            }
        }
    }
    
    public function book_limit_admin_notice__error()
    {
        $Book_Press = new Book_Press();
        $licence = $Book_Press->licence();
        $args = array(
            'post_type'  => 'book',
            'meta_query' => array( array(
            'key'   => 'type',
            'value' => array( 'book' ),
        ) ),
        );
        $query = new WP_Query( $args );
        if ( $licence['type'] === 'free' && $query->post_count >= 1 ) {
            
            if ( !function_exists( 'run_book_press_pro' ) ) {
                ?>
					<div class="notice notice-error">
						<p><?php 
                _e( 'Prolific Author? Want to add more books? <a href="https://bookpress.net/">Click here</a> to upgrade to the Pro version and publish unlimited books!', 'book-press' );
                ?></p>
					</div>
					<?php 
            }
        
        }
        
        if ( $licence['type'] === 'free' && $query->post_count > 1 ) {
            $screen = $_SERVER['REQUEST_URI'];
            
            if ( $screen === '/wp-admin/post-new.php?post_type=book' ) {
                if ( get_the_ID() ) {
                    wp_delete_post( get_the_ID(), true );
                }
                die;
            }
        
        }
    
    }
    
    public function book_press_remove_meta_boxes()
    {
        global  $post ;
        
        if ( $post ) {
            if ( get_post_meta( $post->ID, 'type', true ) !== 'book' ) {
                remove_meta_box( 'genrediv', 'book', 'side' );
            }
            if ( get_post_meta( $post->ID, 'type', true ) === 'book' ) {
                remove_meta_box( 'postimagediv', 'book', 'side' );
            }
            remove_meta_box( 'pageparentdiv', 'book', 'side' );
        }
    
    }
    
    public function inline_update_el_meta()
    {
        update_post_meta( $_POST['id'], $_POST['meta'], $_POST['value'] );
        die;
    }
    
    public function update_element_page_unmber()
    {
        update_post_meta( $_POST['id'], 'start', $_POST['start'] );
        update_post_meta( $_POST['id'], 'end', $_POST['end'] );
        die;
    }
    
    public function dolly_css()
    {
        if ( get_post_type() === 'progress' ) {
            echo  "\r\n\t\t<style type='text/css'>\r\n\t\ta.page-title-action { display:none; }\r\n\t\t</style>\r\n\t\t" ;
        }
    }
    
    // Function to get all the dates in given range
    public function getDatesFromRange( $start, $end, $format = 'Y-m-d' )
    {
        // Declare an empty array
        $array = array();
        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval( 'P1D' );
        $realEnd = new DateTime( $end );
        $realEnd->add( $interval );
        $period = new DatePeriod( new DateTime( $start ), $interval, $realEnd );
        // Use loop to store date into array
        foreach ( $period as $date ) {
            $array[] = $date->format( $format );
        }
        // Return the array elements
        return $array;
    }
    
    public function _pro_render_meta_box_content()
    {
        global  $post ;
        $post_id = $post->ID;
        $book_pro_enable_progress = get_post_meta( $post_id, 'book_pro_enable_progress', true );
        $book_pro_app_words = get_post_meta( $post_id, 'book_pro_app_words', true );
        $book_pro_t_start_date = get_post_meta( $post_id, 'book_pro_t_start_date', true );
        $book_pro_t_end_date = get_post_meta( $post_id, 'book_pro_t_end_date', true );
        $book_pro_a_start_date = get_post_meta( $post_id, 'book_pro_a_start_date', true );
        $book_pro_a_end_date = get_post_meta( $post_id, 'book_pro_a_end_date', true );
        $book_pro_count_start_hour = get_post_meta( $post_id, 'book_pro_count_start_hour', true );
        $book_pro_count_start_day = get_post_meta( $post_id, 'book_pro_count_start_day', true );
        $book_pro_book_comp = get_post_meta( $post_id, 'book_pro_book_comp', true );
        $book_pro_app_words = str_replace( ',', '', $book_pro_app_words );
        $book_pro_todat_date = date( 'Y-m-d' );
        $week_chek_diff = abs( strtotime( $book_pro_todat_date ) - strtotime( $book_pro_t_start_date ) ) / 60 / 60 / 24 / 7 + 1;
        $target_word = floor( $book_pro_app_words / (abs( strtotime( $book_pro_t_end_date ) - strtotime( $book_pro_t_start_date ) ) / 60 / 60 / 24 / 7) );
        $target_word_total = $week_chek_diff * $target_word;
        $book_id = get_post_meta( $post_id, 'progress', true );
        $curren_word_count = get_post_meta( $book_id, 'total_word_count', true );
        $book_pro_progress = get_post_meta( $post_id, 'book_pro_progress' );
        
        if ( count( $book_pro_progress ) > 0 ) {
            $last_week_word = $book_pro_progress[count( $book_pro_progress ) - 1]['word'];
        } else {
            $last_week_word = 0;
        }
        
        $date_names = array(
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        );
        $alldatesT = $this->getDatesFromRange( $book_pro_t_start_date, $book_pro_t_end_date );
        $alldatesTdy = $this->getDatesFromRange( $book_pro_t_start_date, $book_pro_todat_date );
        $targetT = floor( $book_pro_app_words / count( $alldatesT ) );
        $datesUsable = array();
        foreach ( $alldatesT as $key => $date ) {
            $datesUsable[$date] = 0;
        }
        $total_word = ( get_post_meta( $book_id, 'total_word', true ) ? get_post_meta( $book_id, 'total_word', true ) : array() );
        $total_word[date( 'Y-m-d' )] = $this->get_book_word_count( $book_id );
        $progress_dates = $progress_dates_old = array_merge( $datesUsable, $total_word );
        foreach ( $progress_dates as $key => $value ) {
            $prevDate = date( 'Y-m-d', strtotime( '-1 day', strtotime( $key ) ) );
            
            if ( $value === 0 ) {
                $progress_dates[$key] = $progress_dates[$prevDate];
                $progress_dates_old[$key] = $progress_dates[$prevDate];
            }
        
        }
        $total = 0;
        foreach ( $progress_dates as $key => $value ) {
            $prevDate = date( 'Y-m-d', strtotime( '-1 day', strtotime( $key ) ) );
            $progress = $value - $progress_dates_old[$prevDate];
            $progress_dates[$key] = array(
                'day'      => $date_names[date( 'w', strtotime( '-1 day', strtotime( $key ) ) )],
                'target'   => $targetT,
                'progress' => $progress,
                'total'    => $value,
            );
        }
        $weekc = 0;
        foreach ( $progress_dates as $key => $progress_date ) {
            $progress_dates[$key]['week'] = $weekc;
            foreach ( $date_names as $key => $date_name ) {
                if ( $date_name == $book_pro_count_start_day ) {
                    
                    if ( $key == 0 ) {
                        $revd = 6;
                    } else {
                        $revd = $key - 1;
                    }
                
                }
            }
            if ( $progress_date['day'] == $date_names[$revd] ) {
                $weekc++;
            }
        }
        $weeks = array();
        foreach ( $progress_dates as $key => $progress_date ) {
            $weeks[$progress_date['week']][$key] = $progress_date;
        }
        //print_r($weeks);
        $weeksFinal = array();
        $total_target_upto_today = 0;
        foreach ( $weeks as $keyw => $dates ) {
            $wprogress = 0;
            $targetT = 0;
            $s = 0;
            $se = array();
            foreach ( $dates as $keyd => $date ) {
                if ( $s === 0 ) {
                    $se['start'] = $keyd;
                }
                if ( $s === count( $dates ) - 1 ) {
                    $se['end'] = $keyd;
                }
                $wprogress += $date['progress'];
                $targetT += $date['target'];
                if ( date( 'Y-m-d' ) >= $keyd ) {
                    $total_target_upto_today += $date['target'];
                }
                $s++;
            }
            $weeksFinal[$keyw] = array(
                'target'   => $targetT,
                'progress' => $wprogress,
                'start'    => date( 'F j, Y', strtotime( $se['start'] ) ),
                'end'      => date( 'F j, Y', strtotime( $se['end'] ) ),
            );
        }
        ?>
		<p>Book: <?php 
        echo  get_post( get_post_meta( $post->ID, 'progress', true ) )->post_title ;
        ?>
			&nbsp; &nbsp; &nbsp; &nbsp;<input type="checkbox" name="book_pro_enable_progress" <?php 
        if ( $book_pro_enable_progress ) {
            echo  "checked" ;
        }
        ?>> Enable Progress Tracking
		</p>
		<div style="border: 1px solid #f1f1f1; padding: 10px;">
			<h3 style="margin-top: 0px;">What are your targets?</h3>
			<p>Approximately how long do you want the book to be?
				<select name="book_pro_app_words">

					<option <?php 
        if ( $book_pro_app_words === '500' ) {
            echo  "selected" ;
        }
        ?>>500</option>
					<option <?php 
        if ( $book_pro_app_words === '1000' ) {
            echo  "selected" ;
        }
        ?>>1,000</option>
					<option <?php 
        if ( $book_pro_app_words === '5000' ) {
            echo  "selected" ;
        }
        ?>>5,000</option>
					<option <?php 
        if ( $book_pro_app_words === '10000' ) {
            echo  "selected" ;
        }
        ?>>10,000</option>
					<option <?php 
        if ( $book_pro_app_words === '20000' ) {
            echo  "selected" ;
        }
        ?>>20,000</option>
					<option <?php 
        if ( $book_pro_app_words === '30000' ) {
            echo  "selected" ;
        }
        ?>>30,000</option>
					<option <?php 
        if ( $book_pro_app_words === '40000' ) {
            echo  "selected" ;
        }
        ?>>40,000</option>
					<option <?php 
        if ( $book_pro_app_words === '50000' ) {
            echo  "selected" ;
        }
        ?>>50,000</option>
					<option <?php 
        if ( $book_pro_app_words === '60000' ) {
            echo  "selected" ;
        }
        ?>>60,000</option>
					<option <?php 
        if ( $book_pro_app_words === '70000' ) {
            echo  "selected" ;
        }
        ?>>70,000</option>
					<option <?php 
        if ( $book_pro_app_words === '80000' ) {
            echo  "selected" ;
        }
        ?>>80,000</option>
					<option <?php 
        if ( $book_pro_app_words === '90000' ) {
            echo  "selected" ;
        }
        ?>>90,000</option>
					<option <?php 
        if ( $book_pro_app_words === '100000' ) {
            echo  "selected" ;
        }
        ?>>100,000</option>
					<option <?php 
        if ( $book_pro_app_words === '110000' ) {
            echo  "selected" ;
        }
        ?>>110,000</option>
					<option <?php 
        if ( $book_pro_app_words === '120000' ) {
            echo  "selected" ;
        }
        ?>>120,000</option>
					<option <?php 
        if ( $book_pro_app_words === '130000' ) {
            echo  "selected" ;
        }
        ?>>130,000</option>
					<option <?php 
        if ( $book_pro_app_words === '140000' ) {
            echo  "selected" ;
        }
        ?>>140,000</option>
					<option <?php 
        if ( $book_pro_app_words === '150000' ) {
            echo  "selected" ;
        }
        ?>>150,000</option>
					<option <?php 
        if ( $book_pro_app_words === '160000' ) {
            echo  "selected" ;
        }
        ?>>160,000</option>
					<option <?php 
        if ( $book_pro_app_words === '170000' ) {
            echo  "selected" ;
        }
        ?>>170,000</option>
					<option <?php 
        if ( $book_pro_app_words === '180000' ) {
            echo  "selected" ;
        }
        ?>>180,000</option>
					<option <?php 
        if ( $book_pro_app_words === '190000' ) {
            echo  "selected" ;
        }
        ?>>190,000</option>
				</select> words
			</p>
			<table width="100%">
				<tr>
					<td>Target Start Date</td>
					<td><input type="date" name="book_pro_t_start_date" value="<?php 
        if ( $book_pro_t_start_date ) {
            echo  $book_pro_t_start_date ;
        }
        ?>"></td>
					<td>Actual Start Date</td>
					<td><input type="date" name="book_pro_a_start_date" value="<?php 
        if ( $book_pro_a_start_date ) {
            echo  $book_pro_a_start_date ;
        }
        ?>"></td>
				</tr>
				<tr>
					<td>Target End Date</td>
					<td><input type="date" name="book_pro_t_end_date" value="<?php 
        if ( $book_pro_t_end_date ) {
            echo  $book_pro_t_end_date ;
        }
        ?>"></td>
					<td>Actual End Date</td>
					<td><input type="date" name="book_pro_a_end_date" value="<?php 
        if ( $book_pro_a_end_date ) {
            echo  $book_pro_a_end_date ;
        }
        ?>"></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>Today's Date</td>
					<td><input type="date" readonly="readonly" name="book_pro_todat_date" value="<?php 
        if ( $book_pro_todat_date ) {
            echo  $book_pro_todat_date ;
        }
        ?>"></td>
				</tr>
			</table>
		</div>
		<div style="border: 1px solid #f1f1f1; padding: 10px; margin-top: 15px;">
			<h3 style="margin-top: 0px;">Recording Settings</h3>
			<p>Record word count at
				<select name="book_pro_count_start_hour">
					<option <?php 
        if ( $book_pro_count_start_hour === '12.00 am' ) {
            echo  "selected" ;
        }
        ?>>12.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '01.00 am' ) {
            echo  "selected" ;
        }
        ?>>01.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '02.00 am' ) {
            echo  "selected" ;
        }
        ?>>02.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '03.00 am' ) {
            echo  "selected" ;
        }
        ?>>03.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '04.00 am' ) {
            echo  "selected" ;
        }
        ?>>04.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '05.00 am' ) {
            echo  "selected" ;
        }
        ?>>05.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '06.00 am' ) {
            echo  "selected" ;
        }
        ?>>06.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '07.00 am' ) {
            echo  "selected" ;
        }
        ?>>07.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '08.00 am' ) {
            echo  "selected" ;
        }
        ?>>08.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '09.00 am' ) {
            echo  "selected" ;
        }
        ?>>09.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '10.00 am' ) {
            echo  "selected" ;
        }
        ?>>10.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '11.00 am' ) {
            echo  "selected" ;
        }
        ?>>11.00 am</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '12.00 pm' ) {
            echo  "selected" ;
        }
        ?>>12.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '01.00 pm' ) {
            echo  "selected" ;
        }
        ?>>01.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '02.00 pm' ) {
            echo  "selected" ;
        }
        ?>>02.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '03.00 pm' ) {
            echo  "selected" ;
        }
        ?>>03.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '04.00 pm' ) {
            echo  "selected" ;
        }
        ?>>04.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '05.00 pm' ) {
            echo  "selected" ;
        }
        ?>>05.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '06.00 pm' ) {
            echo  "selected" ;
        }
        ?>>06.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '07.00 pm' ) {
            echo  "selected" ;
        }
        ?>>07.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '08.00 pm' ) {
            echo  "selected" ;
        }
        ?>>08.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '09.00 pm' ) {
            echo  "selected" ;
        }
        ?>>09.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '10.00 pm' ) {
            echo  "selected" ;
        }
        ?>>10.00 pm</option>
					<option <?php 
        if ( $book_pro_count_start_hour === '11.00 pm' ) {
            echo  "selected" ;
        }
        ?>>11.00 pm</option>
				</select>
				every
				<select name="book_pro_count_start_day">
					<option <?php 
        if ( $book_pro_count_start_day === 'Sunday' ) {
            echo  "selected" ;
        }
        ?>>Sunday</option>
					<option <?php 
        if ( $book_pro_count_start_day === 'Monday' ) {
            echo  "selected" ;
        }
        ?>>Monday</option>
					<option <?php 
        if ( $book_pro_count_start_day === 'Tuesday' ) {
            echo  "selected" ;
        }
        ?>>Tuesday</option>
					<option <?php 
        if ( $book_pro_count_start_day === 'Wednesday' ) {
            echo  "selected" ;
        }
        ?>>Wednesday</option>
					<option <?php 
        if ( $book_pro_count_start_day === 'Thursday' ) {
            echo  "selected" ;
        }
        ?>>Thursday</option>
					<option <?php 
        if ( $book_pro_count_start_day === 'Friday' ) {
            echo  "selected" ;
        }
        ?>>Friday</option>
					<option <?php 
        if ( $book_pro_count_start_day === 'Saturday' ) {
            echo  "selected" ;
        }
        ?>>Saturday</option>
				</select>
			</p>
			<p><input type="checkbox" name="book_pro_book_comp" <?php 
        if ( $book_pro_book_comp ) {
            echo  "checked" ;
        }
        ?>> Book is completed - base figures on 'Actual End Date' and stop recording</p>
		</div>

		<?php 
        $tptg = end( get_post_meta( $book_id, 'total_word', true ) ) / $total_target_upto_today * 100;
        $acwpw = floor( end( get_post_meta( $book_id, 'total_word', true ) ) / count( $alldatesTdy ) * 7 );
        if ( $tptg > 100 ) {
            $tptg = 100;
        }
        ?>

		<div style="border: 1px solid #f1f1f1; padding: 10px; margin-top: 15px;">
			<h3 style="margin-top: 0px;">Weekly Targets</h3>
			<p>Based on what you told us in the section above</p>
			<div style="border: 1px solid #d5d5d5; padding: 10px;">
				<p>Your target number of words per week is 
					<?php 
        echo  number_format( $target_word ) ;
        ?>, Your actual number per week is 
					<?php 
        echo  floor( end( get_post_meta( $book_id, 'total_word', true ) ) / count( $alldatesTdy ) * 7 ) ;
        ?>
					.<br>You are achiveing 
					<?php 
        echo  floor( $acwpw / end( get_post_meta( $book_id, 'total_word', true ) ) * 100 ) ;
        ?>% 
					of your target words per week
			</p>
			</div>
			<div style="border: 1px solid #d5d5d5; padding: 10px; margin-top: 15px;">
				<table width="100%">
					<tr>
						<td width="250">Target Progress to Goal</td>
						<td>

							<div style="background: #bee7f6; width: 100%; height: 5px;">
								<div style="background: #2bb3e3; height: 5px; width: 
								<?php 
        echo  $tptg ;
        ?>%;"></div>
							</div>
						</td>
						<td width="250" align="center">
							<?php 
        echo  end( get_post_meta( $book_id, 'total_word', true ) ) ;
        ?>
							 / 
							<?php 
        echo  $total_target_upto_today ;
        ?>
							</td>
					</tr>
					<tr>
						<td>Actual Progress to Goal</td>
						<td>

							<div style="background: #bee7f6; width: 100%; height: 5px;">
								<div style="background: #2bb3e3; height: 5px; width: <?php 
        echo  end( get_post_meta( $book_id, 'total_word', true ) ) / str_replace( ',', '', get_post_meta( $post_id, 'book_pro_app_words', true ) ) * 100 ;
        ?>%;"></div>
							</div>
						</td>
						<td align="center">
							<?php 
        echo  end( get_post_meta( $book_id, 'total_word', true ) ) ;
        ?>
							 / 
							<?php 
        echo  get_post_meta( $post_id, 'book_pro_app_words', true ) ;
        ?> 

							
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div style="border: 1px solid #f1f1f1; padding: 10px; margin-top: 15px;">
			<h3 style="margin-top: 0px;">Weekly Progress</h3>
			<table width="700" cellpadding="4" cellspacing="0">
				<tr style="background: #d5d5d5;">
					<td>Date</td>
					<td>Cumulative Target<br>By Week</td>
					<td>Actual Words<br>Per Week</td>
					<td>Total Words<br>Writen</td>
				</tr>

				<?php 
        $book_pro_progress = get_post_meta( $post_id, 'book_pro_progress' );
        $dates = array();
        $targets = array();
        $writtens = array();
        $words = array();
        //print_r($weeksFinal);
        $toralw = 0;
        $totargs = 0;
        foreach ( $weeksFinal as $key => $week ) {
            $toralw += $week['progress'];
            $totargs += $week['target'];
            array_push( $dates, $week['end'] );
            array_push( $targets, $totargs );
            array_push( $writtens, $week['progress'] );
            array_push( $words, $toralw );
            ?>
					<tr>
						<td><?php 
            echo  $week['start'] ;
            ?> to <?php 
            echo  $week['end'] ;
            ?> </td>
						<td><?php 
            echo  $totargs ;
            ?></td>
						<td><?php 
            echo  $week['progress'] ;
            ?></td>
						<td><?php 
            echo  $toralw ;
            ?></td>
					</tr>
					<?php 
        }
        ?>
			</table>
			<br>
			<div class="cart">
				<div style="width:700px;border: 1px solid #d5d5d5; padding: 15px;">
					<canvas id="canvas"></canvas>
				</div>
				<script type="text/javascript" src="https://www.chartjs.org/dist/2.7.3/Chart.bundle.js"></script>
				<script>
					window.onload = function() {
						var ctx = document.getElementById('canvas').getContext('2d');
						window.myLine = Chart.Line(ctx, {
							data: {
								labels: <?php 
        echo  json_encode( $dates ) ;
        ?>,
								datasets: [{
									label: 'Cumulative Target By Week',
									borderColor: '#a4a4a4',
									backgroundColor: '#a4a4a4',
									fill: false,
									data: <?php 
        echo  json_encode( $targets ) ;
        ?>,
									yAxisID: 'y-axis-1',
								}, {
									label: 'Actual Words Per Week',
									borderColor: '#ee7b2d',
									backgroundColor: '#ee7b2d',
									fill: false,
									data: <?php 
        echo  json_encode( $writtens ) ;
        ?>,
									yAxisID: 'y-axis-1'
								}, {
									label: 'Total Words Writen',
									borderColor: '#2d61bc',
									backgroundColor: '#2d61bc',
									fill: false,
									data: <?php 
        echo  json_encode( $words ) ;
        ?>,
									yAxisID: 'y-axis-1'
								}]
							},
							options: {
								responsive: true,
								legend: {
									position: 'bottom',
								},
								hoverMode: 'index',
								stacked: true,
								title: {
									display: false,
									text: ''
								},
								scales: {
									yAxes: [{
										type: 'linear',
										display: true,
										position: 'left',
										id: 'y-axis-1',
										gridLines: {
											drawOnChartArea: true,
										},
										ticks: {
											beginAtZero: true,
											userCallback: function(label, index, labels) {
												if (Math.floor(label) === label) {
													return label;
												}
											},
										}
									}],
								}
							}
						});
					};
				</script>
			</div>
		</div>
		<?php 
    }
    
    public function _pro_book_press_save_meta_box( $post_id )
    {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        $book_pro_enable_progress = ( isset( $_POST['book_pro_enable_progress'] ) ? $_POST['book_pro_enable_progress'] : null );
        $book_pro_app_words = ( isset( $_POST['book_pro_app_words'] ) ? $_POST['book_pro_app_words'] : null );
        $book_pro_t_start_date = ( isset( $_POST['book_pro_t_start_date'] ) ? $_POST['book_pro_t_start_date'] : null );
        $book_pro_t_end_date = ( isset( $_POST['book_pro_t_end_date'] ) ? $_POST['book_pro_t_end_date'] : null );
        $book_pro_a_start_date = ( isset( $_POST['book_pro_a_start_date'] ) ? $_POST['book_pro_a_start_date'] : null );
        $book_pro_count_start_hour = ( isset( $_POST['book_pro_count_start_hour'] ) ? $_POST['book_pro_count_start_hour'] : null );
        $book_pro_count_start_day = ( isset( $_POST['book_pro_count_start_day'] ) ? $_POST['book_pro_count_start_day'] : null );
        $book_pro_book_comp = ( isset( $_POST['book_pro_book_comp'] ) ? $_POST['book_pro_book_comp'] : null );
        $book_pro_a_end_date = ( isset( $_POST['book_pro_a_end_date'] ) ? $_POST['book_pro_a_end_date'] : null );
        if ( $book_pro_a_end_date ) {
            update_post_meta( $post_id, 'book_pro_a_end_date', $book_pro_a_end_date );
        }
        if ( $book_pro_enable_progress ) {
            update_post_meta( $post_id, 'book_pro_enable_progress', $book_pro_enable_progress );
        }
        if ( $book_pro_app_words ) {
            update_post_meta( $post_id, 'book_pro_app_words', $book_pro_app_words );
        }
        if ( $book_pro_a_start_date ) {
            update_post_meta( $post_id, 'book_pro_a_start_date', $book_pro_a_start_date );
        }
        if ( $book_pro_t_start_date ) {
            update_post_meta( $post_id, 'book_pro_t_start_date', $book_pro_t_start_date );
        }
        if ( $book_pro_t_end_date ) {
            update_post_meta( $post_id, 'book_pro_t_end_date', $book_pro_t_end_date );
        }
        if ( $book_pro_count_start_hour ) {
            update_post_meta( $post_id, 'book_pro_count_start_hour', $book_pro_count_start_hour );
        }
        if ( $book_pro_count_start_day ) {
            update_post_meta( $post_id, 'book_pro_count_start_day', $book_pro_count_start_day );
        }
        if ( $book_pro_book_comp ) {
            update_post_meta( $post_id, 'book_pro_book_comp', $book_pro_book_comp );
        }
    }
    
    public function background_color_save( $post_id )
    {
        
        if ( isset( $_POST['book'] ) ) {
            $bookbackground = $_POST['book'];
            // print_r($bookbackground);
            // exit();
            $bookbackground = update_post_meta( $post_id, 'book', $bookbackground );
        }
    
    }
    
    public function get_book_word_count( $book_id )
    {
        $word_count = 0;
        $elements = $this->get_book_elements( $book_id );
        if ( $elements ) {
            foreach ( $elements as $key => $element ) {
                $word_count_element = $this->get_element_word_count( $element->ID );
                $word_count += $word_count_element;
            }
        }
        return $word_count;
    }
    
    public function get_element_word_count( $element_id )
    {
        $element = get_post( $element_id );
        $words = preg_split(
            "/\\s+/",
            strip_tags( $element->post_content ),
            0,
            PREG_SPLIT_NO_EMPTY
        );
        $word_count = 0;
        if ( get_post_meta( $element_id, 'word_count', true ) ) {
            foreach ( $words as $keys => $value ) {
                if ( $value != ' ' && $value != '&nbsp;' && !empty($value) ) {
                    $word_count++;
                }
            }
        }
        return $word_count;
    }
    
    public function get_book_sections( $book_id )
    {
        global  $wpdb ;
        $querystr = "\r\n\t\t\tSELECT {$wpdb->posts}.* \r\n\t\t\tFROM {$wpdb->posts}, {$wpdb->postmeta}\r\n\t\t\tWHERE {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \r\n\t\t\tAND {$wpdb->postmeta}.meta_key = 'type' \r\n\t\t\tAND {$wpdb->postmeta}.meta_value = 'section' \r\n\t\t\tAND {$wpdb->posts}.post_status = 'publish'\r\n\t\t\tAND {$wpdb->posts}.post_parent = {$book_id}\r\n\t\t\tAND {$wpdb->posts}.post_type = 'book'\r\n\t\t\tAND {$wpdb->posts}.post_date < NOW()\r\n\t\t\tORDER BY {$wpdb->posts}.post_date DESC\r\n\t\t";
        return $wpdb->get_results( $querystr, OBJECT );
    }
    
    public function get_sections_elements( $section_id )
    {
        global  $wpdb ;
        $querystr = "\r\n\t\t\tSELECT {$wpdb->posts}.* \r\n\t\t\tFROM {$wpdb->posts}, {$wpdb->postmeta}\r\n\t\t\tWHERE {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \r\n\t\t\tAND {$wpdb->postmeta}.meta_key = 'type' \r\n\t\t\tAND {$wpdb->postmeta}.meta_value = 'element' \r\n\t\t\tAND {$wpdb->posts}.post_status = 'publish'\r\n\t\t\tAND {$wpdb->posts}.post_parent = {$section_id}\r\n\t\t\tAND {$wpdb->posts}.post_type = 'book'\r\n\t\t\tAND {$wpdb->posts}.post_date < NOW()\r\n\t\t\tORDER BY {$wpdb->posts}.post_date DESC\r\n\t\t";
        return $wpdb->get_results( $querystr, OBJECT );
    }
    
    public function get_book_elements( $book_id )
    {
        $allElements = array();
        $sections = $this->get_book_sections( $book_id );
        if ( $sections ) {
            foreach ( $sections as $key => $section ) {
                $elements = $this->get_sections_elements( $section->ID );
                if ( $elements ) {
                    foreach ( $elements as $key => $element ) {
                        array_push( $allElements, $element );
                    }
                }
            }
        }
        return $allElements;
    }
    
    public function book_press_task_function()
    {
        $args = array(
            'post_type'  => 'book',
            'meta_query' => array( array(
            'key'   => 'type',
            'value' => array( 'book' ),
        ) ),
        );
        $query = new WP_Query( $args );
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $value ) {
                sleep( 5 );
                $post_id = $value->ID;
                $progress = get_post_meta( $post_id, 'progress', true );
                
                if ( get_post( $progress ) && get_post_meta( $progress, 'book_pro_enable_progress', true ) && !get_post_meta( $progress, 'book_pro_book_comp', true ) ) {
                    $dayhuman = array(
                        'Sunday',
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday',
                        'Saturday'
                    );
                    $dayr = date( 'w' );
                    $today = $dayhuman[$dayr];
                    $time_ho_m_a = date( 'h.00 a' );
                    $today_check = get_post_meta( $progress, 'book_pro_count_start_day', true );
                    $time_ho_m_a_check = get_post_meta( $progress, 'book_pro_count_start_hour', true );
                    
                    if ( $today === $today_check && $time_ho_m_a === $time_ho_m_a_check ) {
                        $book_pro_progress = get_post_meta( $progress, 'book_pro_progress' );
                        $chek = array();
                        foreach ( $book_pro_progress as $key => $value ) {
                            array_push( $chek, $value['date'] );
                        }
                        $book_pro_app_words = str_replace( ',', '', get_post_meta( $progress, 'book_pro_app_words', true ) );
                        $book_pro_t_start_date = get_post_meta( $progress, 'book_pro_t_start_date', true );
                        $book_pro_t_end_date = get_post_meta( $progress, 'book_pro_t_end_date', true );
                        $target_word = floor( $book_pro_app_words / (abs( strtotime( $book_pro_t_end_date ) - strtotime( $book_pro_t_start_date ) ) / 60 / 60 / 24 / 7) );
                        $curren_word_count = get_post_meta( $post_id, 'total_word_count', true );
                        
                        if ( count( $book_pro_progress ) > 0 ) {
                            $last_week_word_count = $book_pro_progress[count( $book_pro_progress ) - 1]['word'];
                            $written = $curren_word_count - $last_week_word_count;
                        } else {
                            $written = get_post_meta( $post_id, 'total_word_count', true );
                        }
                        
                        
                        if ( !in_array( date( 'Y-m-d' ), $chek ) ) {
                            $data = array(
                                'date'    => date( 'Y-m-d' ),
                                'target'  => $target_word,
                                'written' => $written,
                                'word'    => get_post_meta( $post_id, 'total_word_count', true ),
                            );
                            add_post_meta( $progress, 'book_pro_progress', $data );
                        } else {
                        }
                    
                    }
                
                }
            
            }
        }
    }

}