<?php

namespace filr;

/**
 * Admin Meta Class
 */
class FILR_Meta
{
    /**
     * Contains instance or null
     *
     * @var object|null
     */
    private static  $instance = null ;
    /**
     * Returns instance of FILR_Meta.
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
     * Constructor for FILR_Meta.
     */
    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
        add_action( 'save_post', array( $this, 'save_metaboxes' ) );
        add_action( 'before_delete_post', array( $this, 'clean_files' ) );
    }
    
    /**
     * Adds the meta box container.
     *
     * @param string $post_type array of post types.
     *
     * @return void
     */
    public function add_metaboxes( string $post_type )
    {
        add_meta_box(
            'file-download',
            esc_html__( 'File Download', 'filr' ),
            array( $this, 'render_file_download' ),
            'filr',
            'normal',
            'high'
        );
        add_meta_box(
            'file-upload',
            esc_html__( 'File Upload', 'filr' ),
            array( $this, 'render_file_upload' ),
            'filr',
            'normal',
            'high'
        );
        add_meta_box(
            'file-management',
            esc_html__( 'File Management', 'filr' ),
            array( $this, 'render_management_options' ),
            'filr',
            'side'
        );
        add_meta_box(
            'file-advanced',
            esc_html__( 'Advanced Options', 'filr' ),
            array( $this, 'render_advanced_options' ),
            'filr',
            'side'
        );
        add_meta_box(
            'file-user',
            esc_html__( 'User Options', 'filr' ),
            array( $this, 'render_user_options' ),
            'filr',
            'side'
        );
    }
    
    /**
     * Render file download metabox.
     *
     * @param \WP_POST $post The post object.
     */
    public function render_file_download( \WP_POST $post )
    {
        $download_url = get_post_meta( $post->ID, 'file-download', true );
        $is_folder = get_post_meta( $post->ID, 'is-folder', true );
        $secure_url = FILR_Filesystem::get_secure_url( $post->ID );
        if ( $secure_url ) {
            $download_url = $secure_url;
        }
        
        if ( $is_folder ) {
            ?>
			<div class="filr-meta">
			<p><?php 
            esc_html_e( "Folders don't have a download link", 'filr' );
            ?></p>
			</div>
			<?php 
            return;
        }
        
        ?>
		<?php 
        
        if ( $download_url ) {
            ?>
			<span class="filr-download-link"><code><?php 
            echo  esc_url( $download_url ) ;
            ?></code></span>
		<?php 
        } else {
            ?>
			<span class="filr-download-link"><?php 
            esc_html_e( 'You have not added any files yet.', 'filr' );
            ?></span>
		<?php 
        }
        
        ?>

		<?php 
    }
    
    /**
     * Render Meta Box content.
     *
     * @param \WP_POST $post The post object.
     */
    public function render_file_upload( \WP_POST $post )
    {
        $is_folder = get_post_meta( $post->ID, 'is-folder', true );
        $is_external = get_post_meta( $post->ID, 'is-external', true );
        $external_source = get_post_meta( $post->ID, 'external-source', true );
        
        if ( $is_folder ) {
            ?>
			<div class="filr-meta">
			<p><?php 
            esc_html_e( "Folders don't have an upload option", 'filr' );
            ?></p>
			</div>
			<?php 
            return;
        }
        
        
        if ( $is_external ) {
            ?>
			<div class="filr-meta">
				<p>
					<label for="external-source"><?php 
            esc_html_e( 'External Source', 'filr' );
            ?></label>
					<input id="external-source" aria-labelledby="external-source" name="external-source" placeholder="<?php 
            echo  get_bloginfo( 'url' ) ;
            ?>/sample-file.png" type="url" value="<?php 
            echo  esc_url( $external_source ) ;
            ?>">
				</p>
			</div>
			<?php 
            return;
        }
        
        ?>
	<div class="filr-meta">
			<input id="file-upload" aria-label="<?php 
        esc_html_e( 'File Upload', 'filr' );
        ?>" name="file-upload" type="file" data-fileuploader-limit="1">
		</div>
		<div class="filr-meta filr-admin">
				<p><small><?php 
        esc_html_e( 'With FILR Pro you can upload multiple files and automatically zip them.', 'filr' );
        ?></small></p>
		</div>
			<?php 
    }
    
    /**
     * Render file management metabox.
     *
     * @param \WP_POST $post The post object.
     */
    public function render_management_options( \WP_POST $post )
    {
        ?>
			<div class="filr-meta filr-admin">
				<p><small><?php 
        esc_html_e( 'With FILR Pro you can create folders and attach files to it.', 'filr' );
        ?></small></p>
			</div>
			<?php 
    }
    
    /**
     * Render file options metabox.
     *
     * @param \WP_POST $post The post object.
     */
    public function render_advanced_options( \WP_POST $post )
    {
        $is_folder = get_post_meta( $post->ID, 'is-folder', true );
        
        if ( $is_folder ) {
            ?>
			<div class="filr-meta">
			<p><?php 
            esc_html_e( "Folders don't have advanced options", 'filr' );
            ?></p>
			</div>
			<?php 
            return;
        }
        
        ?>
			<div class="filr-meta filr-admin">
				<p><small><?php 
        echo  esc_html_e( 'With FILR Pro you can activate encryption, expire your files by date or number of downloads.', 'filr' ) ;
        ?></small></p>
			</div>
			<?php 
    }
    
    /**
     * Render user options metabox.
     *
     * @param \WP_POST $post The post object.
     */
    public function render_user_options( \WP_POST $post )
    {
        ?>
			<div class="filr-meta filr-admin">
				<p><small><?php 
        esc_html_e( 'With FILR Pro you can restrict files by user email or user role.', 'filr' );
        ?></small></p>
			</div>
			<?php 
    }
    
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_metaboxes( int $post_id ) : int
    {
        // Check if our nonce is set.
        if ( !isset( $_POST['filr_nonce_check_value'] ) ) {
            return $post_id;
        }
        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['filr_nonce_check_value'], 'filr_nonce_check' ) ) {
            return $post_id;
        }
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
        // check if files in upload, otherwise delete download link.
        $upload = get_post_meta( $post_id, 'file-upload', true );
        if ( isset( $upload ) && !empty($upload) ) {
            if ( count( $upload['files'] ) < 1 ) {
                delete_post_meta( $post_id, 'file-download' );
            }
        }
        return $post_id;
    }
    
    /**
     * Clean filesystem when post deleted.
     *
     * @param int $file_id current post id.
     *
     * @return void
     */
    public function clean_files( int $file_id )
    {
        global  $post_type ;
        if ( 'filr' != $post_type ) {
            return;
        }
        global  $wp_filesystem ;
        if ( !function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( is_null( $wp_filesystem ) ) {
            WP_Filesystem();
        }
        $uploads_directory = wp_upload_dir();
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        $file_directory = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . DIRECTORY_SEPARATOR . $file_id;
        if ( file_exists( $file_directory ) ) {
            $wp_filesystem->delete( $file_directory, true );
        }
    }

}