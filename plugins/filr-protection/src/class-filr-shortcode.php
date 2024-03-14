<?php

namespace filr;

/**
 * Shortcode Class
 */
class FILR_Shortcode
{
    /**
     * Contains instance or null
     *
     * @var object|null
     */
    private static  $instance = null ;
    /**
     * Returns instance of FILR_Shortcode.
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
     * Constructor for FILR_Shortcode.
     */
    public function __construct()
    {
        add_shortcode( 'filr', array( $this, 'add_shortcode' ) );
        add_action( 'wp_footer', array( $this, 'add_dynamic_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'add_public_scripts' ) );
    }
    
    /**
     * Decrease remaining downloads in post meta.
     */
    public function decrease_downloads()
    {
    }
    
    /**
     * Add public scripts.
     *
     * @return void
     */
    public function add_public_scripts()
    {
        
        if ( shortcode_exists( 'filr' ) ) {
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
            $settings = wp_parse_args( get_option( 'filr_shortcode' ), FILR_Admin::get_defaults( 'filr_shortcode' ) );
            wp_enqueue_style(
                'filr-datatable-css',
                FILR_URL . '/assets/datatables.min.css',
                false,
                'all'
            );
            wp_enqueue_script(
                'filr-datatables-js',
                FILR_URL . '/assets/datatables.min.js',
                array( 'jquery' ),
                false,
                true
            );
            wp_enqueue_script(
                'filr-datatables-responsive-js',
                FILR_URL . '/assets/datatables.responsive.min.js',
                array( 'jquery' ),
                false,
                true
            );
            if ( 'on' === $settings['filr_activate_preview'] ) {
                wp_enqueue_script(
                    'filr-imagepreview',
                    FILR_URL . '/assets/imagepreview.min.js',
                    array( 'jquery' ),
                    false,
                    true
                );
            }
            wp_enqueue_script(
                'filr-public',
                FILR_URL . '/assets/filr-public' . $suffix . '.js',
                array( 'jquery' ),
                FILR_VERSION,
                true
            );
            $empty_list = __( 'There are currently no files in this library.', 'filr' );
            if ( isset( $settings['filr_empty_list'] ) && !empty($settings['filr_empty_list']) ) {
                $empty_list = $settings['filr_empty_list'];
            }
            // Seach and pagination.
            $search = true;
            if ( 'on' === $settings['filr_deactivate_search'] ) {
                $search = false;
            }
            $pagination = true;
            if ( 'on' === $settings['filr_deactivate_pagination'] ) {
                $pagination = false;
            }
            if ( empty($settings['filr_default_number_rows']) ) {
                $settings['filr_default_number_rows'] = 10;
            }
            $datatable_translations = array(
                'no_files'            => $empty_list,
                'count_files'         => esc_html__( 'Show _START_ to _END_ from _TOTAL_ files', 'filr' ),
                'show_files'          => esc_html__( 'Showing 0 to 0 of 0 files', 'filr' ),
                'filtered_files'      => esc_html__( '(filtered from _MAX_ total files)', 'filr' ),
                'available_files'     => esc_html__( 'Show _MENU_ files', 'filr' ),
                'loading_files'       => esc_html__( 'Loading..', 'filr' ),
                'search_files'        => esc_html__( 'Search..', 'filr' ),
                'no_files_found'      => esc_html__( 'No files found.', 'filr' ),
                'first_page_files'    => esc_html__( 'First page', 'filr' ),
                'last_page_files'     => esc_html__( 'Last page', 'filr' ),
                'next_page_files'     => esc_html__( 'Next page', 'filr' ),
                'previous_page_files' => esc_html__( 'Previous page', 'filr' ),
                'open_folder_text'    => esc_html__( 'Open Folder', 'filr' ),
                'close_folder_text'   => esc_html__( 'Close Folder', 'filr' ),
            );
            wp_localize_script( 'filr-public', 'filr_shortcode', array(
                'ajax_url'            => admin_url( 'admin-ajax.php' ),
                'nonce'               => wp_create_nonce( 'filr-decrease-nonce' ),
                'translations'        => $datatable_translations,
                'search'              => $search,
                'pagination'          => $pagination,
                'use_preview'         => $settings['filr_activate_preview'],
                'default_sort'        => $settings['filr_sort_rows_default'],
                'default_sort_type'   => $settings['filr_sort_rows_default_type'],
                'default_number_rows' => $settings['filr_default_number_rows'],
            ) );
        }
    
    }
    
    /**
     * Register a shortcode for filr table display.
     *
     * @param array $atts array of possible attributes.
     *
     * @return string
     */
    public function add_shortcode( array $atts ) : string
    {
        $settings = wp_parse_args( get_option( 'filr_shortcode' ), FILR_Admin::get_defaults( 'filr_shortcode' ) );
        if ( isset( $atts['list'] ) ) {
            $atts['library'] = $atts['list'];
        }
        if ( !isset( $atts['library'] ) ) {
            return '';
        }
        $args = array(
            'post_type'      => 'filr',
            'posts_per_page' => -1,
            'tax_query'      => array( array(
            'taxonomy' => 'filr-lists',
            'field'    => 'slug',
            'terms'    => $atts['library'],
            'operator' => 'IN',
        ) ),
            'post_status'    => 'publish',
        );
        // Show only files from uploader.
        $filter = '';
        
        if ( isset( $atts['filter'] ) && 'uploader' === $atts['filter'] ) {
            $filter = $atts['filter'];
            $user = wp_get_current_user();
            $args = array(
                'post_type'      => 'filr',
                'posts_per_page' => -1,
                'tax_query'      => array( array(
                'taxonomy' => 'filr-lists',
                'field'    => 'slug',
                'terms'    => $atts['library'],
                'operator' => 'IN',
            ) ),
                'meta_query'     => array( array(
                'key'   => 'restrict-user',
                'value' => $user->user_email,
            ) ),
                'post_status'    => 'publish',
            );
        }
        
        $args = apply_filters( 'filr_library_args', $args, $filter );
        $files = get_posts( $args );
        // publish date title.
        $use_publish_date = false;
        
        if ( isset( $settings['filr_show_publish_instead_of_last_modified_date'] ) && !empty($settings['filr_show_publish_instead_of_last_modified_date']) && 'on' === $settings['filr_show_publish_instead_of_last_modified_date'] ) {
            add_filter( 'filr_date_title', function ( $title ) {
                return __( 'Published', 'filr' );
            } );
            $use_publish_date = true;
        }
        
        // Buold the dynamic header and rows.
        $header_data = apply_filters( 'filr_header_columns', array(
            'file'      => array(
            'title' => esc_html__( 'File', 'filr' ),
            'hide'  => 'off',
        ),
            'version'   => array(
            'title' => esc_html__( 'Version', 'filr' ),
            'hide'  => 'off',
        ),
            'user'      => array(
            'title' => esc_html__( 'Uploaded by', 'filr' ),
            'hide'  => 'off',
        ),
            'size'      => array(
            'title' => esc_html__( 'Size', 'filr' ),
            'hide'  => $settings['filr_hide_size_row'],
        ),
            'type'      => array(
            'title' => esc_html__( 'Type', 'filr' ),
            'hide'  => $settings['filr_hide_type_row'],
        ),
            'remaining' => array(
            'title' => esc_html__( 'Remaining', 'filr' ),
            'hide'  => $settings['filr_hide_remaining_row'],
        ),
            'expire'    => array(
            'title' => esc_html__( 'Expires', 'filr' ),
            'hide'  => $settings['filr_hide_expires_row'],
        ),
            'date'      => array(
            'title' => apply_filters( 'filr_date_title', esc_html__( 'Last Modified', 'filr' ) ),
            'hide'  => $settings['filr_hide_date_row'],
        ),
            'download'  => array(
            'title' => esc_html__( 'Download', 'filr' ),
            'hide'  => 'off',
        ),
        ) );
        $headers = explode( '|', $settings['filr_shortcode_sort_rows'] );
        ob_start();
        ?>
        <div class="filr-container">
            <table class="filr <?php 
        echo  esc_html( $atts['library'] ) ;
        ?>"
                   id="filr-library-<?php 
        echo  esc_html( $atts['library'] ) ;
        ?>">
                <thead>
                <tr>
					<?php 
        foreach ( $headers as $th ) {
            ?>
						<?php 
            
            if ( 'off' === $header_data[$th]['hide'] ) {
                ?>
                            <th class="<?php 
                echo  esc_attr( $th ) ;
                ?>"><?php 
                echo  esc_html( $header_data[$th]['title'] ) ;
                ?></th>
						<?php 
            }
            
            ?>
					<?php 
        }
        ?>
                </tr>
                </thead>
                <tbody>
				<?php 
        foreach ( $files as $file ) {
            ?>
					<?php 
            $file_id = $file->ID;
            $link = apply_filters( 'filr_download_link', get_post_meta( $file_id, 'file-download', true ) );
            $upload = get_post_meta( $file_id, 'file-upload', true );
            // Get the title.
            $title = $file->post_title;
            // Get file date.
            $file_date = $file->post_date;
            // Download button.
            $button = apply_filters( 'filr_replace_button_name', $file->post_title );
            if ( isset( $settings['filr_rename_download_button'] ) && !empty($settings['filr_rename_download_button']) ) {
                $button = apply_filters( 'filr_replace_button_name', $settings['filr_rename_download_button'] );
            }
            // if it's a file.
            
            if ( isset( $upload['files'] ) && !empty($upload['files']) ) {
                $file = '<a role="button" aria-label="' . esc_html__( 'Download File', 'filr' ) . '" class="filr-button" href="' . $link . '" download>' . $button . '</a>';
                // if date parameter is set.
                $date = apply_filters( 'filr_date_output', date_format( date_create( $upload['files'][0]['date'] ), $settings['filr_date_format'] ) );
                $size = $upload['files'][0]['size'];
                $extension = $upload['files'][0]['extension'];
                // Publish date instead of last modified date.
                if ( $use_publish_date ) {
                    // Get publish date.
                    
                    if ( !empty($file_date) ) {
                        $publish_date = date_format( date_create( $file_date ), $settings['filr_date_format'] );
                        $date = $publish_date;
                        $timestamp = date_format( date_create( $file_date ), 'Ymd' );
                    }
                
                }
            }
            
            // If nothing matches skip file.
            if ( empty($upload['files']) && !$is_folder && !$is_external && !$link ) {
                continue;
            }
            ?>
					<?php 
            ?>
                        <tr>
							<?php 
            foreach ( $headers as $td ) {
                ?>
								<?php 
                
                if ( 'off' === $header_data[$td]['hide'] ) {
                    ?>
									<?php 
                    
                    if ( 'file' === $td ) {
                        ?>
                                        <td class="title"><?php 
                        echo  esc_html( $title ) ;
                        ?></td>
									<?php 
                    }
                    
                    ?>
									<?php 
                    
                    if ( 'size' === $td ) {
                        ?>
                                        <td class="size"><?php 
                        echo  esc_html( FILR_Filesystem::format_byte_sizes( $size ) ) ;
                        ?></td>
									<?php 
                    }
                    
                    ?>
									<?php 
                    
                    if ( 'type' === $td ) {
                        ?>
                                        <td class="type"><?php 
                        echo  esc_html( strtoupper( $extension ) ) ;
                        ?></td>
									<?php 
                    }
                    
                    ?>
									<?php 
                    
                    if ( 'date' === $td ) {
                        ?>
                                        <td data-order="<?php 
                        echo  esc_html( $timestamp ) ;
                        ?>"
                                            class="date"><?php 
                        echo  esc_html( $date ) ;
                        ?></td>
									<?php 
                    }
                    
                    ?>
									<?php 
                    
                    if ( 'download' === $td ) {
                        ?>
                                        <td class="download"><?php 
                        echo  $file ;
                        ?></td>
									<?php 
                    }
                    
                    ?>
								<?php 
                }
                
                ?>
							<?php 
            }
            ?>
                        </tr>
					<?php 
            ?>
				<?php 
        }
        ?>
                </tbody>
            </table>
        </div>
		<?php 
        return ob_get_clean();
    }
    
    /**
     * Add dynamic styles if filr shortcode exists.
     *
     * @return void
     */
    public function add_dynamic_styles()
    {
        $settings = wp_parse_args( get_option( 'filr_shortcode' ), FILR_Admin::get_defaults( 'filr_shortcode' ) );
        ?>
		<?php 
        
        if ( shortcode_exists( 'filr' ) ) {
            ?>
            <style>
                .filr-container table {
                    width: 100%;
                    border-collapse: collapse;
                    overflow: hidden;
                    border: none;
                }

                .filr-container .dataTables_wrapper {
                    background: #fafafa;
                    padding: 20px;
                    border-radius: <?php 
            echo  esc_html( $settings['filr_table_border_radius'] ) ;
            ?>px;
                }

                .filr-container .dataTables_wrapper .dataTables_filter input {
                    border: 1px solid #000;
                    border-radius: 0;
                    padding: 5px;
                    background-color: white;
                    margin-right: 0;
                }

                .filr-container .dataTables_info, .filr-container .paginate_button {
                    font-size: 0.8em;
                    color: <?php 
            echo  esc_html( $settings['filr_tr_font_color'] ) ;
            ?>;
                }

                .filr-container table, .filr-container table td, .filr-container table th, .filr-container table tr {
                    border: none;
                }

                .filr-container table thead th {
                    background: <?php 
            echo  esc_html( $settings['filr_thead_background'] ) ;
            ?>;
                    color: <?php 
            echo  esc_html( $settings['filr_thead_font_color'] ) ;
            ?>;
                    text-align: left;
                    padding: 20px;
                    border-bottom: solid 1px;
                }

                .filr-container table tbody tr {
                    background: <?php 
            echo  esc_html( $settings['filr_tr_background'] ) ;
            ?>;
                    color: <?php 
            echo  esc_html( $settings['filr_tr_font_color'] ) ;
            ?>;
                }

                .filr-container table tbody tr:nth-child(even) {
                    background-color: <?php 
            echo  esc_html( $settings['filr_tr_even_background'] ) ;
            ?>;
                }

                .filr-container table tbody tr:hover {
                    background-color: <?php 
            echo  esc_html( $settings['filr_tr_hover_background'] ) ;
            ?>;
                    transition: 0.25s;
                }

                .filr-container table tbody td {
                    position: relative;
                    padding: 20px;
                }

                .filr-container table tbody td a.filr-button {
                    background: <?php 
            echo  esc_html( $settings['filr_tr_button_background_color'] ) ;
            ?>;
                    padding: 10px 15px;
                    transition: 0.5s;
                    color: <?php 
            echo  esc_html( $settings['filr_tr_button_color'] ) ;
            ?>;
                    text-align: center;
                    text-decoration: none;
                }

                .filr-container table tbody .filr-folder-button, .filr-back {
                    background: <?php 
            echo  esc_html( $settings['filr_open_folder_button_color'] ) ;
            ?>;
                    padding: 10px 15px;
                    transition: 0.5s;
                    color: <?php 
            echo  esc_html( $settings['filr_tr_button_color'] ) ;
            ?>;
                    text-align: center;
                    text-decoration: none;
                    cursor: pointer;
                }

                .filr-container table tbody .filr-folder-button-close {
                    background: <?php 
            echo  esc_html( $settings['filr_close_folder_button_color'] ) ;
            ?>;
                }

                .filr-container table.dataTable thead .sorting:after {
                    content: "";
                    background: url("<?php 
            echo  esc_url( FILR_URL ) ;
            ?>/assets/filr-sort.svg");
                    height: 15px;
                    width: 15px;
                    display: inline-block;
                    background-repeat: no-repeat;
                    background-size: cover;
                    position: relative;
                    top: 2px;
                    left: 5px;
                    transform: rotate(180deg);
                }

                .filr-container .filr-preview:after {
                    content: "";
                    background: url("<?php 
            echo  esc_url( FILR_URL ) ;
            ?>/assets/filr-preview.svg");
                    height: 20px;
                    width: 20px;
                    display: inline-block;
                    background-repeat: no-repeat;
                    background-size: cover;
                    position: relative;
                    top: 2px;
                    left: 5px;
                }

                .filr-container table.dataTable thead .sorting_asc::after {
                    content: "";
                    background: url("<?php 
            echo  esc_url( FILR_URL ) ;
            ?>/assets/filr-sort.svg");
                    height: 15px;
                    width: 15px;
                    display: inline-block;
                    background-repeat: no-repeat;
                    background-size: cover;
                    position: relative;
                    top: 2px;
                    left: 5px;
                }

                .filr-container table.dataTable thead .sorting_desc:after {
                    content: "";
                    background: url("<?php 
            echo  esc_url( FILR_URL ) ;
            ?>/assets/filr-sort.svg");
                    height: 15px;
                    width: 15px;
                    display: inline-block;
                    background-repeat: no-repeat;
                    background-size: cover;
                    position: relative;
                    top: 2px;
                    left: 5px;
                    transform: rotate(180deg);
                }

                .filr-folder-table caption {
                    background: transparent;
                    color: <?php 
            echo  esc_html( $settings['filr_tr_button_background_color'] ) ;
            ?>;
                    font-size: <?php 
            echo  esc_html( $settings['filr_folders_font_size'] ) ;
            ?>px;
                    font-weight: bold;
                    text-align: left;
                }

                #preview {
                    position: absolute;
                    max-width: 300px;
                    border: 1px solid<?php 
            echo  esc_html( $settings['filr_thead_font_color'] ) ;
            ?>;
                    background: #fff;
                    display: none;
                }

            </style>
		<?php 
        }
        
        ?>
		<?php 
    }

}