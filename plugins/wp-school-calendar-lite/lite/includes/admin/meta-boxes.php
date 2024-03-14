<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Lite_Meta_Boxes {

    private static $_instance = NULL;
    private static $saved_meta_boxes = false;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
        add_action( 'save_post',      array( $this, 'save_meta_boxes' ), 1, 2 );
        add_action( 'wpsc_additional_date_meta_box', array( $this, 'add_recurring_settings' ) );
    }

    /**
     * retrieve singleton class instance
     * @return instance reference to plugin
     */
    public static function instance() {
        if ( NULL === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Meta boxes initialization
     * 
     * @since 1.0
     */
    public function add_meta_boxes() {
        add_meta_box( 'wpsc-notes', __( 'Additional Notes', 'wp-school-calendar' ), array( $this, 'add_notes_meta_box' ), 'important_date', 'normal', 'high' );
    }
    
    public function add_notes_meta_box( $post, $box ) {
        wp_nonce_field( 'wpsc_lite_save_metabox', 'wpsc_lite_metabox_nonce' );
        ?>
        <div class="wpsc-upgrade-panel">
            <div class="wpsc-upgrade-panel__description"><?php echo __( "Please upgrade to the PRO plan to unlock these features.", 'wp-school-calendar' ) ?></div>
            <div class="wpsc-upgrade-panel__button"><a href="<?php echo wpsc_fs()->get_trial_url() ?>"><?php echo __( 'Upgrade to Pro', 'wp-school-calendar' ) ?></a></div>
        </div>
        <?php
    }
    
    public function add_recurring_settings( $post ) {
        ?>
        <div class="wpsc-upgrade-panel" style="border-top:1px solid #ccc;margin-top:20px;padding-top:20px;">
            <div class="wpsc-upgrade-panel__description"><strong><?php echo __( 'Did You Know', 'wp-school-calendar' ) ?></strong></div>
            <div class="wpsc-upgrade-panel__description"><?php echo __( 'You can create recurring important date using WP School Calendar Pro', 'wp-school-calendar' ) ?></div>
            <div class="wpsc-upgrade-panel__button"><a href="<?php echo wpsc_fs()->get_trial_url() ?>"><?php echo __( 'Upgrade to Pro', 'wp-school-calendar' ) ?></a></div>
        </div>
        <?php
    }
    
    public function save_meta_boxes( $post_id, $post ) {
        if ( empty( $post_id ) || empty( $post ) || self::$saved_meta_boxes ) {
            return;
        }

        // Dont' save meta boxes for revisions or autosaves
        if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events
        if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
            return;
        }

        // Check user has permission to edit
        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        self::$saved_meta_boxes = true;
        
        // Check the nonce
        if ( empty( $_POST['wpsc_lite_metabox_nonce'] ) || !wp_verify_nonce( $_POST['wpsc_lite_metabox_nonce'], 'wpsc_lite_save_metabox' ) ) {
            return;
        }
        
        $_additional_notes = array( 
            'notes'           => '', 
            'readmore_url'    => '',
            'readmore_title'  => '',
            'readmore_target' => '',
            'readmore_rel'    => ''
        );
        
        update_post_meta( $post_id, '_additional_notes', $_additional_notes );
        update_post_meta( $post_id, '_enable_recurring', 'N' );
    }
    
}

WP_School_Calendar_Lite_Meta_Boxes::instance();