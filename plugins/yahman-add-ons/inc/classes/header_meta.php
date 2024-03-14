<?php
defined( 'ABSPATH' ) || exit;

/**
 * Register a meta box using a class.
 */
class YAHMAN_ADDONS_ADD_META_TAGS {


    

    public function __construct() {
    	if ( is_admin() ) {
    		add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
    		add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
    	}

    }


     

    public function init_metabox() {
    	add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
    	add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }


     

    public function add_metabox() {
    	add_meta_box(
    		'ya_description',
    		__( 'Meta description', 'yahman-add-ons' ),
    		array( $this, 'render_metabox' )
    	);

    }


     

    public function render_metabox( $post ) {
        
    	wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );
    	?>
    	<textarea name="ya_description" id="ya_description" placeholder="<?php esc_attr_e( 'Description' , 'yahman-add-ons' ); ?>" style="width:100%;height:90px;"><?php echo esc_attr( get_post_meta($post->ID, 'ya_description', true) ); ?></textarea>
    	<?php
    }

    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
        
    	$nonce_name   = isset( $_POST['custom_nonce'] ) ? $_POST['custom_nonce'] : '';
    	$nonce_action = 'custom_nonce_action';

        
    	if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
    		return;
    	}

        
    	if ( ! current_user_can( 'edit_post', $post_id ) ) {
    		return;
    	}

        
    	if ( wp_is_post_autosave( $post_id ) ) {
    		return;
    	}

        
    	if ( wp_is_post_revision( $post_id ) ) {
    		return;
    	}



    	if(!empty($_POST['ya_description'])){
    		update_post_meta($post_id, 'ya_description', $_POST['ya_description'] );
    	}else{
    		delete_post_meta($post_id, 'ya_description');
    	}

    }
}


