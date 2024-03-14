<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Meta_Boxes {

    private static $_instance = NULL;
    private static $saved_meta_boxes = false;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
        add_action( 'save_post',      array( $this, 'save_meta_boxes' ), 1, 2 );
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
        add_meta_box( 'wpsc-date',     __( 'Date Settings', 'wp-school-calendar' ), array( $this, 'add_date_meta_box' ), 'important_date', 'normal', 'high' );
        add_meta_box( 'wpsc-category', __( 'Category', 'wp-school-calendar' ), array( $this, 'add_category_meta_box' ), 'important_date', 'normal', 'high' );
    }
    
    /**
     * Add date metabox
     * 
     * @since 1.0
     * 
     * @param WP_Post $post WP_Post object
     * @param type $box
     */
    public function add_date_meta_box( $post, $box ) {
        $start_date      = get_post_meta( $post->ID, '_start_date', true );
        $end_date        = get_post_meta( $post->ID, '_end_date', true );
        $exclude_weekend = get_post_meta( $post->ID, '_exclude_weekend', true );
        
        $start_date      = empty( $start_date ) ? date( 'm/d/Y' ) : date( 'm/d/Y', strtotime( $start_date ) );
        $end_date        = empty( $end_date ) ? $start_date : date( 'm/d/Y', strtotime( $end_date ) );
        $exclude_weekend = empty( $exclude_weekend ) ? '' : $exclude_weekend;
        
        wp_nonce_field( 'wpsc_save_important_date', 'important_date_nonce' );
        ?>
        <table class="form-table">
            <tr valign="top">
				<th scope="row" class="titledesc"><?php echo esc_html__( 'Start Date', 'wp-school-calendar' ) ?></th>
				<td class="forminp">
                    <input type="text" id="start-datepicker" class="wpsc-regular-text" name="start_date" value="<?php echo esc_attr( $start_date ) ?>">
                    <p class="description"><?php printf( __( 'Format: mm/dd/yy. Example: %s', 'wp-school-calendar' ), date( 'm/d/Y' ) ) ?></p>
				</td>
			</tr>
            <tr valign="top">
				<th scope="row" class="titledesc"><?php echo esc_html__( 'End Date', 'wp-school-calendar' ) ?></th>
				<td class="forminp">
                    <input type="text" id="end-datepicker" class="wpsc-regular-text" name="end_date" value="<?php echo esc_attr( $end_date ) ?>">
                    <p class="description"><?php printf( __( 'Format: mm/dd/yy. Example: %s', 'wp-school-calendar' ), date( 'm/d/Y' ) ) ?></p>
				</td>
			</tr>
            <tr valign="top">
				<th scope="row" class="titledesc"><?php echo esc_html__( 'Weekend Options', 'wp-school-calendar' ) ?></th>
				<td class="forminp">
                    <label><input type="checkbox" name="exclude_weekend" value="Y"<?php checked( 'Y', $exclude_weekend ) ?>> <?php echo __( 'Exclude Weekend Dates', 'wp-school-calendar' ) ?></label>
				</td>
			</tr>
        </table>
        <?php
        do_action( 'wpsc_additional_date_meta_box', $post );
    }
    
    /**
     * Add category metabox
     * 
     * @since 1.0
     * 
     * @param WP_Post $post WP_Post object
     * @param type $box
     */
    public function add_category_meta_box( $post, $box ) {
        $category_options = wpsc_get_categories();
        
        $category_id = get_post_meta( $post->ID, '_category_id', true );
        $category_id = empty( $category_id ) ? wpsc_settings_value( 'default_category' ) : $category_id;
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row" class="titledesc"><?php echo esc_html__( 'Select Category', 'wp-school-calendar' ) ?></th>
                <td class="forminp">
                    <select id="wpsc-category-select" name="category_id" class="">
                        <?php foreach ( $category_options as $option ): ?>
                        <option value="<?php echo $option['category_id'] ?>"<?php echo selected( $option['category_id'], $category_id ) ?>><?php echo $option['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save metabox
     * 
     * @since 1.0
     * 
     * @param int $post_id Post ID
     * @param WP_Post $post WP_Post object
     */
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
        if ( empty( $_POST['important_date_nonce'] ) || !wp_verify_nonce( $_POST['important_date_nonce'], 'wpsc_save_important_date' ) ) {
            return;
        }
        
        if ( isset( $_POST['start_date'] ) ) {
            $_POST['start_date'] = wpsc_check_valid_date( $_POST['start_date'] ) ? $_POST['start_date'] : date( 'm/d/Y' );
            $start_date = date_format( date_create_from_format( 'm/d/Y', $_POST['start_date'] ), 'Y-m-d' );
            update_post_meta( $post_id, '_start_date', $start_date );
        }
        
        if ( isset( $_POST['end_date'] ) ) {
            if ( empty( $_POST['end_date'] ) ) {
                $_POST['end_date'] = $_POST['start_date'];
            }
            
            $_POST['end_date'] = wpsc_check_valid_date( $_POST['end_date'] ) ? $_POST['end_date'] : date( 'm/d/Y' );
            $end_date = date_format( date_create_from_format( 'm/d/Y', $_POST['end_date'] ), 'Y-m-d' );
            
            if ( strtotime( $end_date ) < strtotime( $start_date ) ) {
                $end_date = $start_date;
            }
            
            update_post_meta( $post_id, '_end_date', $end_date );
        }
        
        if ( isset( $_POST['exclude_weekend'] ) ) {
            update_post_meta( $post_id, '_exclude_weekend', 'Y' );
        } else {
            update_post_meta( $post_id, '_exclude_weekend', 'N' );
        }
        
        if ( isset( $_POST['category_id'] ) ) {
            update_post_meta( $post_id, '_category_id', intval( $_POST['category_id'] ) );
        }
    }
}

WP_School_Calendar_Meta_Boxes::instance();