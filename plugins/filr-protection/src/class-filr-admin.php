<?php

namespace filr;

/**
 * Admin Options Class
 */
class FILR_Admin
{
    /**
     * Contains instance or null
     *
     * @var object|null
     */
    private static  $instance = null ;
    /**
     * Returns instance of FILR_Admin.
     *
     * @return object
     */
    public static function get_instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Setting up admin fields
     *
     * @return void
     */
    public function __construct()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );
        add_action( 'init', array( $this, 'register_filr' ) );
        add_action( 'init', array( $this, 'register_filr_lists' ) );
        add_action( 'init', array( $this, 'setup_settings_page' ) );
        add_filter( 'manage_filr_posts_columns', array( $this, 'set_columns' ) );
        add_action(
            'manage_filr_posts_custom_column',
            array( $this, 'set_columns_content' ),
            10,
            2
        );
        add_filter( 'manage_edit-filr-lists_columns', array( $this, 'add_shortcode_column' ) );
        add_filter( 'manage_edit-filr-lists_sortable_columns', array( $this, 'add_shortcode_column' ) );
        add_filter(
            'manage_filr-lists_custom_column',
            array( $this, 'add_shortcode_column_content' ),
            10,
            3
        );
    }
    
    /**
     * Enqueue admin scripts
     *
     * @return void
     */
    public function add_admin_scripts()
    {
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
        wp_enqueue_style(
            'filr-admin',
            FILR_URL . '/assets/filr-admin' . $suffix . '.css',
            array(),
            FILR_VERSION,
            'all'
        );
        wp_enqueue_script(
            'filr-admin',
            FILR_URL . '/assets/filr-admin' . $suffix . '.js',
            array( 'jquery' ),
            FILR_VERSION,
            true
        );
        $file_data = get_post_meta( get_the_id(), 'file-upload', true );
        // Media Uploader.
        wp_enqueue_media();
        // uploader styles and scripts.
        wp_enqueue_style(
            'font-fileuploader',
            FILR_URL . '/assets/font/font-fileuploader.min.css',
            array(),
            false,
            'all'
        );
        wp_enqueue_style(
            'fileuploader',
            FILR_URL . '/assets/jquery.fileuploader.min.css',
            array(),
            false,
            'all'
        );
        wp_enqueue_script(
            'fileuploader',
            FILR_URL . '/assets/jquery.fileuploader.min.js',
            array( 'jquery' ),
            false,
            true
        );
        
        if ( !is_null( get_the_id() ) ) {
            $settings = wp_parse_args( get_option( 'filr_status' ), self::get_defaults( 'filr_status' ) );
            $uploads_directory = wp_upload_dir();
            $filr_dir = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . DIRECTORY_SEPARATOR . get_the_id() . DIRECTORY_SEPARATOR;
            $translations = array( esc_html__( 'Drag and drop files here', 'filr' ), esc_html__( 'or', 'filr' ), __( 'Browse files', 'filr' ) );
            wp_localize_script( 'filr-admin', 'ajax', array(
                'ajax_url'       => admin_url( 'admin-ajax.php' ),
                'post_id'        => get_the_id(),
                'file_data'      => $file_data,
                'uploader_nonce' => wp_create_nonce( 'filr-uploader-nonce' ),
                'filr_dir'       => $filr_dir,
                'translations'   => $translations,
            ) );
        }
    
    }
    
    /**
     * Register a custom post type called "filr".
     *
     * @see get_post_type_labels() for label keys.
     */
    public function register_filr()
    {
        $labels = array(
            'name'                  => _x( 'Filr', 'Post type general name', 'filr' ),
            'singular_name'         => _x( 'File', 'Post type singular name', 'filr' ),
            'menu_name'             => _x( 'Filr', 'Admin Menu text', 'filr' ),
            'name_admin_bar'        => _x( 'File', 'Add New on Toolbar', 'filr' ),
            'add_new'               => __( 'Add New', 'filr' ),
            'add_new_item'          => __( 'Add New File', 'filr' ),
            'new_item'              => __( 'New File', 'filr' ),
            'edit_item'             => __( 'Edit File', 'filr' ),
            'view_item'             => __( 'View File', 'filr' ),
            'all_items'             => __( 'All Files', 'filr' ),
            'search_items'          => __( 'Search Files', 'filr' ),
            'parent_item_colon'     => __( 'Parent Files:', 'filr' ),
            'not_found'             => __( 'No Files found.', 'filr' ),
            'not_found_in_trash'    => __( 'No Files found in Trash.', 'filr' ),
            'archives'              => _x( 'Files archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'filr' ),
            'insert_into_item'      => _x( 'Insert into File', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'filr' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this File', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'filr' ),
            'filter_items_list'     => _x( 'Filter Files list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'filr' ),
            'items_list_navigation' => _x( 'File list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'filr' ),
            'items_list'            => _x( 'Files list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'filr' ),
        );
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'supports'           => array( 'title' ),
            'menu_icon'          => 'dashicons-download',
        );
        register_post_type( 'filr', $args );
    }
    
    /**
     * Register a 'Lists' taxonomy for post type 'filr'.
     *
     * @see register_post_type() for registering post types.
     */
    public function register_filr_lists()
    {
        $labels = array(
            'name'                       => _x( 'Libraries', 'taxonomy general name', 'filr' ),
            'singular_name'              => _x( 'Library', 'taxonomy singular name', 'filr' ),
            'search_items'               => __( 'Search Libraries', 'filr' ),
            'popular_items'              => __( 'Popular Libraries', 'filr' ),
            'all_items'                  => __( 'All Libraries', 'filr' ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit Library', 'filr' ),
            'update_item'                => __( 'Update Library', 'filr' ),
            'add_new_item'               => __( 'Add New Library', 'filr' ),
            'new_item_name'              => __( 'New Library Name', 'filr' ),
            'separate_items_with_commas' => __( 'Separate Libraries with commas', 'filr' ),
            'add_or_remove_items'        => __( 'Add or remove Libraries', 'filr' ),
            'choose_from_most_used'      => __( 'Choose from the most used Libraries', 'filr' ),
            'not_found'                  => __( 'No Libraries found.', 'filr' ),
            'menu_name'                  => __( 'Libraries', 'filr' ),
        );
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => false,
            'public'            => false,
        );
        register_taxonomy( 'filr-lists', 'filr', $args );
    }
    
    /**
     * Add shortcode to columns for filr-lists.
     *
     * @param array $new_columns new columns to add.
     *
     * @return array
     */
    public function add_shortcode_column( array $new_columns ) : array
    {
        return array(
            'cb'        => '<input type="checkbox" />',
            'name'      => esc_html__( 'Name', 'filr' ),
            'slug'      => esc_html__( 'Slug', 'filr' ),
            'posts'     => esc_html__( 'Files', 'filr' ),
            'shortcode' => esc_html__( 'Shortcode', 'filr' ),
        );
    }
    
    /**
     * Add content to shortcode column.
     *
     * @param string $value current value.
     * @param string $name name of column.
     * @param int $id current id.
     *
     * @return string
     */
    public function add_shortcode_column_content( string $value, string $name, int $id ) : string
    {
        $term = get_term( $id, 'filr-lists' );
        return '<code>[filr library="' . esc_html( $term->slug ) . '"]</code>';
    }
    
    /**
     * Set column headers filr post type
     *
     * @param array $columns array of columns.
     *
     * @return array
     */
    public function set_columns( array $columns ) : array
    {
        $columns['download_link'] = esc_html__( 'Download-Link', 'filr' );
        return $columns;
    }
    
    /**
     * Add content to registered columns for filr post type.
     *
     * @param string $column name of the column.
     * @param int $post_id current id.
     *
     * @return void
     */
    public function set_columns_content( string $column, int $post_id )
    {
        switch ( $column ) {
            case 'download_link':
                $is_folder = get_post_meta( $post_id, 'is-folder', true );
                $download_url = get_post_meta( $post_id, 'file-download', true );
                $secure_url = FILR_Filesystem::get_secure_url( $post_id );
                if ( $secure_url ) {
                    $download_url = $secure_url;
                }
                
                if ( $is_folder ) {
                    esc_html_e( "Folders don't have a download link", 'filr' );
                    return;
                }
                
                ?>
                <span class="filr-download-link">
					<code>
					<?php 
                echo  esc_html( $download_url ) ;
                ?>
					</code>
				</span>
				<?php 
                break;
            case 'is_folder':
                $is_folder = get_post_meta( $post_id, 'is-folder', true );
                $assigned_folder = get_post_meta( $post_id, 'assigned-folder', true );
                ?>
                <span>
					<?php 
                
                if ( $is_folder ) {
                    ?>
                        <b><?php 
                    esc_html_e( 'Is a Folder', 'filr' );
                    ?></b><br>
					<?php 
                }
                
                ?>

					<?php 
                
                if ( isset( $assigned_folder ) && !empty($assigned_folder) && !is_null( get_post( $assigned_folder ) ) ) {
                    ?>
						<b><?php 
                    esc_html_e( 'Is assigned to:', 'filr' );
                    ?><b><?php 
                    echo  esc_html( get_the_title( $assigned_folder ) ) ;
                    ?></b>
					<?php 
                }
                
                ?>
				</span>
				<?php 
                break;
            case 'remaining_donwloads':
                $is_folder = get_post_meta( $post_id, 'is-folder', true );
                $expire_download = get_post_meta( $post_id, 'expire-download', true );
                
                if ( $is_folder ) {
                    esc_html_e( "Folders don't have an expiration", 'filr' );
                    return;
                }
                
                if ( empty($expire_download) ) {
                    return;
                }
                ?>
				<?php 
                
                if ( $expire_download < 1 ) {
                    ?>
                <span style="color:#a00;"><?php 
                    echo  esc_html( $expire_download ) ;
                    ?></span>
			<?php 
                } else {
                    ?>
                <span><?php 
                    echo  esc_html( $expire_download ) ;
                    ?></span>
			<?php 
                }
                
                ?>

				<?php 
                break;
            case 'expire_date':
                $is_folder = get_post_meta( $post_id, 'is-folder', true );
                $expire_date = get_post_meta( $post_id, 'expire-date', true );
                
                if ( $is_folder ) {
                    esc_html_e( "Folders don't have an expiration", 'filr' );
                    return;
                }
                
                if ( empty($expire_date) ) {
                    return;
                }
                $settings = wp_parse_args( get_option( 'filr_shortcode' ), self::get_defaults( 'filr_shortcode' ) );
                $expire_date = date_create( $expire_date );
                $today = date_create( 'now' );
                ?>

				<?php 
                
                if ( $today->getTimestamp() >= $expire_date->getTimestamp() ) {
                    ?>
                <span style="color:#a00;"><?php 
                    echo  esc_html( date_format( $expire_date, $settings['filr_date_format'] ) ) ;
                    ?></span>
			<?php 
                } else {
                    ?>
                <span><?php 
                    echo  esc_html( date_format( $expire_date, $settings['filr_date_format'] ) ) ;
                    ?></span>
			<?php 
                }
                
                ?>
				<?php 
                break;
        }
    }
    
    /**
     * Setting up admin fields
     *
     * @return void
     */
    public static function setup_settings_page()
    {
        $settings = new FILR_Settings();
        $mode = wp_parse_args( get_option( 'filr_status' ), self::get_defaults( 'filr_status' ) );
        // check if protection files exists.
        $uploads_directory = wp_upload_dir();
        $directory = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $mode['filr_download_directory'];
        $index_file = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $mode['filr_download_directory'] . DIRECTORY_SEPARATOR . 'index.php';
        $htaccess_file = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $mode['filr_download_directory'] . DIRECTORY_SEPARATOR . '.htaccess';
        // add/remove files based on protection mode.
        switch ( $mode['filr_protection_mode'] ) {
            case 'no-protection':
                if ( file_exists( $index_file ) ) {
                    FILR_Filesystem::delete_index_file();
                }
                if ( file_exists( $htaccess_file ) ) {
                    FILR_Filesystem::delete_htaccess_file();
                }
                break;
            case 'index':
                if ( !file_exists( $index_file ) ) {
                    FILR_Filesystem::create_index_file();
                }
                if ( file_exists( $htaccess_file ) ) {
                    FILR_Filesystem::delete_htaccess_file();
                }
                break;
            case 'htaccess':
                if ( file_exists( $index_file ) ) {
                    FILR_Filesystem::delete_index_file();
                }
                if ( !file_exists( $htaccess_file ) ) {
                    FILR_Filesystem::create_htaccess_file();
                }
                break;
            default:
                if ( file_exists( $index_file ) ) {
                    FILR_Filesystem::delete_index_file();
                }
                if ( file_exists( $htaccess_file ) ) {
                    FILR_Filesystem::delete_htaccess_file();
                }
        }
        $settings->add_section( array(
            'id'    => 'filr_status',
            'title' => esc_html__( 'Status', 'filr' ),
        ) );
        
        if ( file_exists( $directory ) ) {
            $settings->add_field( 'filr_status', array(
                'id'   => 'filr_directory',
                'type' => 'html',
                'name' => __( 'Directory', 'filr' ),
                'desc' => __( 'The directory for your download files <b>exists</b>.', 'filr' ),
            ) );
        } else {
            $settings->add_field( 'filr_status', array(
                'id'   => 'filr_directory',
                'type' => 'html',
                'name' => __( 'Directory', 'filr' ),
                'desc' => __( 'The directory for your download files <b>not</b> exists.<br> Please create <b>wp-content/uploads/' . $mode['filr_download_directory'] . '</b>.', 'filr' ),
            ) );
        }
        
        $settings->add_field( 'filr_status', array(
            'id'      => 'filr_download_directory',
            'type'    => 'text',
            'name'    => __( 'Your downloads directory', 'filr' ),
            'default' => 'filr',
            'premium' => 'premium',
        ) );
        
        if ( file_exists( $index_file ) || file_exists( $htaccess_file ) ) {
            $settings->add_field( 'filr_status', array(
                'id'   => 'filr_protection_status',
                'type' => 'html',
                'name' => __( 'Protection Status', 'filr' ),
                'desc' => __( 'The directory for your download files is <b>protected</b>.', 'filr' ),
            ) );
        } else {
            $settings->add_field( 'filr_status', array(
                'id'   => 'filr_protection_status',
                'type' => 'html',
                'name' => __( 'Protection', 'filr' ),
                'desc' => __( 'The directory for your download files is <b>not</b> protected.', 'filr' ),
            ) );
        }
        
        $settings->add_field( 'filr_status', array(
            'id'      => 'filr_protection_mode',
            'type'    => 'radio',
            'name'    => __( 'Protection Mode', 'filr' ),
            'options' => array(
            'no-protection' => __( 'No Protection' ),
            'index'         => 'index.php',
            'htaccess'      => '.htaccess',
        ),
            'desc'    => __( '<p><b>No protection:</b> every file is free to access.</p><p><b>index.php:</b> Create an index.php file on the root of your uploads directory. This will hide the content of your whole download directory.<p><p><b>.htaccess:</b> Prevents people to browse your uploads directory and return a 403 code (Forbidden Access).</p>', 'filr' ),
        ) );
        $settings->add_field( 'filr_status', array(
            'id'      => 'filr_secure_download_links',
            'type'    => 'toggle',
            'name'    => __( 'Secure download links', 'filr' ),
            'default' => 'off',
            'premium' => 'premium',
        ) );
        $status_settings = wp_parse_args( get_option( 'filr_status' ), self::get_defaults( 'filr_status' ) );
        $sample_url = '<code>' . untrailingslashit( get_bloginfo( 'url' ) ) . '?' . $status_settings['filr_download_directory'] . '=123</code>';
        $settings->add_field( 'filr_status', array(
            'id'   => 'secure_download_links_documentation',
            'type' => 'documentation',
            'name' => '',
            'desc' => sprintf( __( 'Secure download links hide the actual path to the file and show a short URL with the post ID as a parameter. <br>It checks for all security restrictions like user, date-and download limits. Example: %s ' ), $sample_url ),
        ) );
        if ( file_exists( $directory ) ) {
            $settings->add_field( 'filr_status', array(
                'id'   => 'filr_check_directory',
                'type' => 'html',
                'name' => __( 'Check', 'filr' ),
                'desc' => '<a target="_blank" href="' . $uploads_directory['baseurl'] . '/' . $mode['filr_download_directory'] . '">' . __( 'Check directory protection', 'filr' ) . '</a>',
            ) );
        }
        $zip = __( 'Zip extension is deactivated. Please contact your hosting partner to enable it.', 'filr' );
        if ( true === extension_loaded( 'zip' ) ) {
            $zip = __( 'Zip extension is active.', 'filr' );
        }
        $libzip = __( 'libzip extension is deactivated. If you want to use password-protected ZIP files, please contact your hosting partner to enable it.', 'filr' );
        if ( true === extension_loaded( 'libzip' ) ) {
            $libzip = __( 'libzip extension is active.', 'filr' );
        }
        $settings->add_field( 'filr_status', array(
            'id'   => 'filr_check_server',
            'type' => 'documentation',
            'name' => __( 'Server Settings', 'filr' ),
            'desc' => sprintf(
            __( 'Your server has the following configurations:<br><br><b>max_file_uploads:</b> %s<br><b>max_upload_size:</b> %s</br><b>zip:</b> %s</br><b>libzip:</b> %s<br><b>PHP:</b> You are running on: %s', 'filr' ),
            ini_get( 'max_file_uploads' ) . ' ' . __( 'files', 'filr' ),
            ini_get( 'post_max_size' ),
            $zip,
            $libzip,
            phpversion()
        ),
        ) );
        $settings->add_field( 'filr_status', array(
            'id'      => 'filr_uninstall_delete',
            'type'    => 'toggle',
            'name'    => __( 'Delete all options and files on uninstall', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_section( array(
            'id'    => 'filr_shortcode',
            'title' => __( 'Libraries', 'filr' ),
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'   => 'filr_shortcode_docs',
            'type' => 'documentation',
            'name' => __( 'Documentation', 'filr' ),
            'desc' => __( 'A basic shortcode looks like this: <code>[filr library="my-library"]</code>. You can copy them directly from FILR->Libraries in your admin area. You can also use <code>[filr library="my-library" filter="uploader"]</code> to only show files where the e-mail address of the user matches the restrict-user field in a file.', 'filr' ),
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'   => 'filr_shortcode_colors',
            'type' => 'html',
            'name' => '<h3>' . __( 'Colors and Styles', 'filr' ) . '</h3>',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_thead_background',
            'type'    => 'color',
            'name'    => __( 'Table head background', 'filr' ),
            'default' => '#fafafa',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_thead_font_color',
            'type'    => 'color',
            'name'    => __( 'Table head font color', 'filr' ),
            'default' => '#000000',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_tr_background',
            'type'    => 'color',
            'name'    => __( 'Table tr background', 'filr' ),
            'default' => '#FFFFFF',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_tr_even_background',
            'type'    => 'color',
            'name'    => __( 'Table tr even background', 'filr' ),
            'default' => '#f5f5f5',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_tr_hover_background',
            'type'    => 'color',
            'name'    => __( 'Table tr hover background', 'filr' ),
            'default' => '#e3e2e2',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_tr_font_color',
            'type'    => 'color',
            'name'    => __( 'Table tr font color', 'filr' ),
            'default' => '#000000',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_tr_button_background_color',
            'type'    => 'color',
            'name'    => __( 'Button background', 'filr' ),
            'default' => '#7200e5',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_tr_button_color',
            'type'    => 'color',
            'name'    => __( 'Button font color', 'filr' ),
            'default' => '#FFFFFF',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_table_border_radius',
            'type'    => 'number',
            'name'    => __( 'Table border radius', 'filr' ),
            'default' => 5,
            'min'     => 0,
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'   => 'filr_shortcode_table_rows',
            'type' => 'html',
            'name' => '<h3>' . __( 'Rows & Columns', 'filr' ) . '</h3>',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_default_number_rows',
            'type'    => 'number',
            'name'    => __( 'The default number of rows to display in a library', 'filr' ),
            'default' => 10,
            'min'     => 0,
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_sort_rows_default',
            'type'    => 'number',
            'name'    => __( 'The number of the default column to sort the table', 'filr' ),
            'default' => 1,
            'min'     => 0,
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_sort_rows_default_type',
            'type'    => 'select',
            'name'    => __( 'choose the sorting mode used by default', 'filr' ),
            'options' => array(
            'asc'  => __( 'ASC', 'filr' ),
            'desc' => __( 'DESC', 'filr' ),
        ),
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_shortcode_sort_rows',
            'type'    => 'text',
            'name'    => __( 'Sort your columns', 'filr' ),
            'default' => 'file|size|type|date|download',
            'desc'    => __( 'You can use the following rows: <code>file|size|type|date|download</code>.', 'filr' ),
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_hide_name_row',
            'type'    => 'toggle',
            'name'    => __( 'Deactivate name column', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_hide_size_row',
            'type'    => 'toggle',
            'name'    => __( 'Deactivate file size column', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_hide_type_row',
            'type'    => 'toggle',
            'name'    => __( 'Deactivate type column', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_hide_date_row',
            'type'    => 'toggle',
            'name'    => __( 'Deactivate date column', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_show_publish_instead_of_last_modified_date',
            'type'    => 'toggle',
            'name'    => __( 'Show publish date instead of last modified date', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'   => 'filr_shortcode_table_folders',
            'type' => 'html',
            'name' => '<h3>' . __( 'Folders', 'filr' ) . '</h3>',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_folders_font_size',
            'type'    => 'number',
            'name'    => __( 'Font size of folder headline (in px)', 'filr' ),
            'default' => 22,
            'min'     => 0,
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'   => 'filr_shortcode_table_additional',
            'type' => 'html',
            'name' => '<h3>' . __( 'Additional', 'filr' ) . '</h3>',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'   => 'filr_empty_list',
            'type' => 'textarea',
            'name' => __( 'Alternative text when no files available within a library', 'filr' ),
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'   => 'filr_rename_download_button',
            'type' => 'text',
            'name' => __( 'Rename Download Button', 'filr' ),
            'desc' => __( 'Normally Filr shows the name of the file in the download button, here you can change that.', 'filr' ),
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_target_blank_external',
            'type'    => 'toggle',
            'name'    => __( 'Open external files in new tab', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_deactivate_search',
            'type'    => 'toggle',
            'name'    => __( 'Deactivate Search', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_deactivate_pagination',
            'type'    => 'toggle',
            'name'    => __( 'Deactivate Pagination', 'filr' ),
            'default' => 'off',
        ) );
        $settings->add_field( 'filr_shortcode', array(
            'id'      => 'filr_date_format',
            'type'    => 'text',
            'name'    => __( 'Date format', 'filr' ),
            'default' => 'Y/m/d',
            'desc'    => __( 'You have to use a valid PHP date format string.<br> Examples are <code>Y-m-d</code>, <code>d/m/Y</code> or <code>Y-m-d H:i:s</code>' ),
        ) );
        $settings->add_section( array(
            'id'    => 'filr_frontend_uploader',
            'title' => __( 'Frontend Uploader', 'filr' ),
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'   => 'filr_frontend_uploader_docs',
            'type' => 'documentation',
            'name' => __( 'Documentation', 'filr' ),
            'desc' => __( 'This feature is only available in <b>Filr Pro</b>.', 'filr' ),
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'   => 'filr_frontend_uploader_styles',
            'type' => 'html',
            'name' => '<h3>' . __( 'Styles', 'filr' ) . '</h3>',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'      => 'filr_frontend_uploader_headline',
            'type'    => 'text',
            'name'    => __( 'Modify Headline', 'filr' ),
            'value'   => __( 'Drag and drop files here', 'filr' ),
            'premium' => 'premium',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'      => 'filr_frontend_uploader_limiter_text',
            'type'    => 'text',
            'name'    => __( 'Modify Limiter text', 'filr' ),
            'value'   => __( 'or', 'filr' ),
            'premium' => 'premium',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'      => 'filr_frontend_uploader_button_label',
            'type'    => 'text',
            'name'    => __( 'Modify button label', 'filr' ),
            'value'   => __( 'Browse files', 'filr' ),
            'premium' => 'premium',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'   => 'filr_frontend_uploader_additional',
            'type' => 'html',
            'name' => '<h3>' . __( 'Additional', 'filr' ) . '</h3>',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'   => 'filr_frontend_uploader_features',
            'type' => 'html',
            'name' => '<h3>' . __( 'Features', 'filr' ) . '</h3>',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'      => 'filr_frontend_uploader_activate_folders',
            'type'    => 'toggle',
            'name'    => __( 'Allow folder selection', 'filr' ),
            'default' => 'off',
            'premium' => 'premium',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'      => 'filr_frontend_uploader_activate_libraries',
            'type'    => 'toggle',
            'name'    => __( 'Allow library selection', 'filr' ),
            'default' => 'off',
            'premium' => 'premium',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'      => 'filr_frontend_uploader_send_notification',
            'type'    => 'toggle',
            'name'    => __( 'Send notification E-Mail after new file submit', 'filr' ),
            'default' => 'off',
            'premium' => 'premium',
        ) );
        $settings->add_field( 'filr_frontend_uploader', array(
            'id'      => 'filr_frontend_uploader_reload',
            'type'    => 'toggle',
            'name'    => __( 'Reload after file submit', 'filr' ),
            'default' => 'off',
            'premium' => 'premium',
        ) );
    }
    
    /**
     * Return default based on option name.
     *
     * @param string $option_name name of the option.
     *
     * @return array
     */
    public static function get_defaults( string $option_name ) : array
    {
        $settings = array();
        switch ( $option_name ) {
            case 'filr_status':
                $settings = array(
                    'filr_download_directory'               => 'filr',
                    'filr_protection_mode'                  => 'no-protection',
                    'filr_uninstall_delete'                 => 'off',
                    'filr_secure_download_links'            => 'off',
                    'filr_secure_download_links_encryption' => 'off',
                );
                break;
            case 'filr_shortcode':
                $settings = array(
                    'filr_thead_background'           => '#7200e5',
                    'filr_thead_font_color'           => '#FFFFFF',
                    'filr_tr_background'              => '#FFFFFF',
                    'filr_tr_even_background'         => '#f5f5f5',
                    'filr_tr_hover_background'        => '#e3e2e2',
                    'filr_tr_font_color'              => '#000000',
                    'filr_tr_button_background_color' => '#7200e5',
                    'filr_tr_button_color'            => '#FFFFFF',
                    'filr_open_folder_button_color'   => '#7200e5',
                    'filr_close_folder_button_color'  => '#d93535',
                    'filr_table_border_radius'        => 5,
                    'filr_hide_name_row'              => 'off',
                    'filr_hide_size_row'              => 'off',
                    'filr_hide_remaining_row'         => 'off',
                    'filr_hide_expires_row'           => 'off',
                    'filr_show_expired_downloads'     => 'off',
                    'filr_hide_type_row'              => 'off',
                    'filr_hide_date_row'              => 'off',
                    'filr_hide_version_row'           => 'off',
                    'filr_date_format'                => 'Y/m/d',
                    'filr_empty_list'                 => '',
                    'filr_deactivate_search'          => 'off',
                    'filr_deactivate_pagination'      => 'off',
                    'filr_activate_preview'           => 'off',
                    'filr_sort_rows_default'          => 1,
                    'filr_sort_rows_default_type'     => 'asc',
                    'filr_folders_font_size'          => 22,
                    'filr_target_blank_external'      => 'off',
                    'filr_default_number_rows'        => 10,
                    'filr_hide_user_row'              => 'off',
                    'filr_shortcode_sort_rows'        => 'file|size|type|date|download',
                );
                break;
        }
        return $settings;
    }
    
    /**
     * Save the old directory name and rename the directory.
     *
     * @param string $new_value new value to update.
     * @param string $old_value old value from option.
     *
     * @return string
     */
    public function save_old_directory( string $new_value, string $old_value ) : string
    {
        if ( empty($old_value) ) {
            return $new_value;
        }
        return $new_value;
    }

}