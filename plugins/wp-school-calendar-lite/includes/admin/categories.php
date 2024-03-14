<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Categories {

    private static $_instance = NULL;
    public $action = NULL;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
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
     * Add Categories menu
     * 
     * @since 1.0
     */
    public function admin_menu() {
        add_submenu_page( 'edit.php?post_type=school_calendar', __( 'Categories', 'wp-school-calendar' ), __( 'Categories', 'wp-school-calendar' ), 'manage_options', 'wpsc-category', array( $this, 'admin_page' ) );
    }
    
    /**
     * Add Categories page
     * 
     * @since 1.0
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html__( 'Categories', 'wp-school-calendar' );?></h1>
            <hr class="wp-header-end">
            <?php 
            if ( ! empty( $_GET['edit'] ) ) {
                $this->edit();
            } else {
                $this->table();
            }
            ?>
        </div>
        <?php
    }
    
    /**
     * Display category edit form
     * 
     * @since 1.0
     */
    private function edit() {
        $category = wpsc_get_category( intval( $_REQUEST['edit'] ) );
		?>
		<form method="post" action="<?php echo admin_url( 'edit.php?post_type=school_calendar&page=wpsc-category&edit=' . intval( $_REQUEST['edit'] ) ); ?>">
			<?php wp_nonce_field( 'category_edit-' . $category['category_id'] ); ?>
            <input name="edit_category[category_id]" type="hidden" value="<?php echo esc_attr( $category['category_id'] ); ?>" />
			<table class="form-table">
                <tr>
					<th scope="row"><label for="name"><?php echo esc_html__( 'Name', 'wp-school-calendar' ); ?></label></th>
					<td>
                        <input id="name" type="text" name="edit_category[name]" value="<?php echo esc_attr( $category['name'] ) ?>" class="regular-text">
					</td>
				</tr>
                <tr>
                    <th scope="row"><label for="bgcolor"><?php echo esc_html__( 'Background Color', 'wp-school-calendar' ); ?></label></th>
                    <td>
                        <input id="bgcolor" type="text" name="edit_category[bgcolor]" value="<?php echo esc_attr( $category['bgcolor'] ) ?>" class="color-picker">
                    </td>
				</tr>
			</table>
			<p class="submit"><input type="submit" class="button-primary" name="save_category" value="<?php echo esc_attr__( 'Save Changes', 'wp-school-calendar' ); ?>" /></p>
		</form>
		<?php
	}
    
    /**
     * Display table list of important date categories
     * 
     * @since 1.0
     */
    private function table() {
        $categories = wpsc_get_categories();
        $default_category = wpsc_settings_value( 'default_category' );
		?>
        <div id="col-container">
			<div id="col-right">
				<div class="col-wrap">
					<h3><?php echo esc_html__( 'Available Categories', 'wp-school-calendar' ); ?></h3>
                    <form method="get">
                        <?php wp_nonce_field( 'category_action' ); ?>
                        <input type="hidden" name="post_type" value="school_calendar">
                        <input type="hidden" name="page" value="wpsc-category">
                        <div class="tablenav top">
                            <div class="alignleft actions bulkactions">
                                <select name="action">
                                    <option value="-1"><?php echo esc_html__( 'Bulk Action', 'wp-school-calendar' ) ?></option>
                                    <option value="delete-selected"><?php echo esc_html__( 'Delete', 'wp-school-calendar' ) ?></option>
                                </select>
                                <input type="submit" class="button action" value="<?php echo esc_attr__( 'Apply', 'wp-school-calendar' ) ?>">
                                <input type="submit" name="save_order" class="button action" value="<?php echo esc_attr__( 'Save Order', 'wp-school-calendar' ) ?>">
                            </div>
                        </div>
                        <table class="wp-list-table widefat plugins">
                            <thead>
                                <tr>
                                    <td class="manage-column column-cb check-column"><input id="cb-select-all" type="checkbox"></td>
                                    <td class="wpsc-sortable">&nbsp;</td>
                                    <th scope="col" class="manage-column column-name column-primary"><?php echo esc_html__( 'Name', 'wp-school-calendar' ); ?></th>
                                    <th scope="col" class="manage-column column-bgcolor"><?php echo esc_html__( 'Background Color', 'wp-school-calendar' ); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td class="manage-column column-cb check-column"><input id="cb-select-all" type="checkbox"></td>
                                    <td class="wpsc-sortable">&nbsp;</td>
                                    <th scope="col" class="manage-column column-name column-primary"><?php echo esc_html__( 'Name', 'wp-school-calendar' ); ?></th>
                                    <th scope="col" class="manage-column column-bgcolor"><?php echo esc_html__( 'Background Color', 'wp-school-calendar' ); ?></th>
                                </tr>
                            </tfoot>
                            <tbody id="the-list" class="wpsc-list-table">	
                                <?php foreach ( $categories as $category ): ?>
                                <tr class="inactive">
                                    <th scope="row" class="check-column">
                                        <?php if ( intval( $default_category ) !== intval( $category['category_id'] ) ): ?>
                                        <input type="checkbox" name="category[]" value="<?php echo intval( $category['category_id'] ) ?>">
                                        <?php endif ?>
                                    </th>
                                    <td class="wpsc-sortable">
                                        <span class="wpsc-sortable-handle"></span>
                                        <input type="hidden" name="cat_id[]" value="<?php echo intval( $category['category_id'] ) ?>">
                                    </td>
                                    <td class="plugin-title column-name">
                                        <strong><?php echo $category['name'] ?></strong>
                                        <div class="row-actions">
                                            <span class="edit"><a href="<?php echo admin_url( 'edit.php?post_type=school_calendar&amp;page=wpsc-category&edit=' . intval( $category['category_id'] ) ); ?>"><?php _e( 'Edit', 'wp-school-calendar' ); ?></a></span>
                                            <?php if ( intval( $default_category ) !== intval( $category['category_id'] ) ): ?>
                                            <span class="delete"> | <a href="<?php echo wp_nonce_url( admin_url( 'edit.php?post_type=school_calendar&page=wpsc-category&delete=' . intval( $category['category_id'] ) ), 'category_delete-' . $category['category_id'] ); ?>"><?php _e( 'Delete', 'wp-school-calendar' ); ?></a></span> 
                                            <?php endif ?>
                                        </div>
                                    </td>
                                    <td class="column-bgcolor"><span class="wpsc-color-bar" style="background:<?php echo esc_attr( $category['bgcolor'] ) ?>;"></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
			</div>
			<!-- /col-right -->
			<div id="col-left">
				<div class="col-wrap">
					<div class="form-wrap">
						<h3><?php echo esc_html__( 'Add Category', 'wp-school-calendar' ); ?></h3>
						<form method="post" action="<?php echo admin_url( 'edit.php?post_type=school_calendar&page=wpsc-category' ) ?>">
                            <?php wp_nonce_field( 'category_create' ); ?>	
                            <div class="form-field form-required">
								<label for="name"><?php echo esc_html__( 'Name', 'wp-school-calendar' ); ?></label>
								<input name="new_category[name]" id="name" type="text" value="" aria-required="true" />
							</div>
                            <div class="form-field form-required">
                                <label for="bgcolor"><?php echo esc_html__( 'Background Color', 'wp-school-calendar' ); ?></label> 
                                <input name="new_category[bgcolor]" id="bgcolor" type="text" value="" class="color-picker" />
							</div>
							<p class="submit">
								<input type="submit" class="button button-primary" name="add_new_category" id="submit" value="<?php echo esc_attr__( 'Add Category', 'wp-school-calendar' ); ?>" />
							</p>
						</form>
					</div>
				</div>
			</div>
			<!-- /col-left -->
        </div>
		<?php
	}
    
    /**
     * Do admin actions
     * 
     * @since 1.0
     */
    public function admin_init() {
        $sendback = remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'action', 'category', 'add_new', 'cat_id' ), wp_get_referer() );
        
        if ( isset( $_REQUEST['page'] ) && 'wpsc-category' === $_REQUEST['page'] ) {
            if ( ! empty( $_POST['add_new_category'] ) ) {
                $result = $this->process_add_category();
                
                if ( is_wp_error( $result ) ) {
                    echo '<div class="error"><p>' . wp_kses_post( $result->get_error_message() ) . '</p></div>';
                } else {
                    wp_redirect( $sendback );
                    exit;
                }
            } elseif ( ! empty( $_GET['delete'] ) ) {
                $result = $this->process_delete_category();
                wp_redirect( $sendback );
                exit;
            } elseif ( ! empty( $_POST['save_category'] ) ) {
                $result = $this->process_save_category();
                
                if ( is_wp_error( $result ) ) {
                    echo '<div class="error"><p>' . wp_kses_post( $result->get_error_message() ) . '</p></div>';
                } else {
                    wp_redirect( $sendback );
                    exit;
                }
            } elseif ( ! empty( $_GET['save_order'] ) ) {
                $result = $this->process_save_order_category();
                wp_redirect( $sendback );
                exit;
            } elseif ( ! empty( $_GET['action'] ) ) {
                if ( 'delete-selected' === $_GET['action'] ) {
                    $result = $this->process_bulk_delete_category();
                }
                wp_redirect( $sendback );
                exit;
            }
        }
    }
    
    /**
     * Process add new category
     * 
     * @since 1.0
     */
    public function process_add_category() {
        $args = $_POST['new_category'];
        check_admin_referer( 'category_create' );
        
        if ( empty( $args['name'] ) || empty( $args['bgcolor'] ) ) {
            return new WP_Error( 'error_category', esc_html__( 'Please, provide a category name and background color.', 'wp-school-calendar' ), array( 'status' => 400 ) );
        }
        
        $args = array(
            'name'    => sanitize_text_field( $args['name'] ),
            'bgcolor' => sanitize_text_field( $args['bgcolor'] ),
        );
        
        wpsc_add_new_category( $args );
    }
    
    /**
     * Process delete category
     * 
     * @since 1.0
     */
    public function process_delete_category() {
        $category_id = intval( $_GET['delete'] );
        check_admin_referer( 'category_delete-' . $category_id );
        
        $default_category = wpsc_settings_value( 'default_category' );
        
        if ( intval( $default_category ) === $category_id ) {
            return;
        }
        
        wpsc_delete_category( $category_id );
        wpsc_reorder_categories();
    }
    
    /**
     * Process save category
     * 
     * @since 1.0
     */
    public function process_save_category() {
        $args = $_POST['edit_category'];
        check_admin_referer( 'category_edit-' . $args['category_id'] );
        
        if ( empty( $args['name'] ) || empty( $args['bgcolor'] ) ) {
            return new WP_Error( 'error_category', esc_html__( 'Please, provide a category name and background color.', 'wp-school-calendar' ), array( 'status' => 400 ) );
        }
        
        $args = array(
            'category_id' => intval( $args['category_id'] ),
            'name'        => sanitize_text_field( $args['name'] ),
            'bgcolor'     => sanitize_text_field( $args['bgcolor'] ),
        );
        
        wpsc_save_category( $args );
    }
    
    /**
     * Process bulk delete categories
     * 
     * @since 1.0
     */
    public function process_bulk_delete_category() {
        $category_ids = $_GET['category'];
        check_admin_referer( 'category_action' );
        
        $default_category = wpsc_settings_value( 'default_category' );
        
        foreach ( $category_ids as $category_id ) {
            if ( intval( $default_category ) !== intval( $category_id ) ) {
                wpsc_delete_category( intval( $category_id ) );
            }
        }
        
        wpsc_reorder_categories();
    }
    
    public function process_save_order_category() {
        $category_ids = $_GET['cat_id'];
        check_admin_referer( 'category_action' );
        
        foreach ( $category_ids as $order => $category_id ) {
            wpsc_save_category_order( $category_id, $order );
        }
    }
}

WP_School_Calendar_Categories::instance();