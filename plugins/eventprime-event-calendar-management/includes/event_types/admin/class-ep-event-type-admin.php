<?php
defined( 'ABSPATH' ) || exit;

class EventM_Event_Types_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_type_scripts' ) );
        // add and edit form fields
        add_action('em_event_type_add_form_fields', array($this, 'add_event_type_fields') );
        add_action('em_event_type_edit_form_fields', array($this, 'edit_event_type_fields'), 10);
        // save event type
        add_action( 'created_em_event_type', array( $this, 'em_create_event_type_data') );
        // edit event type
        add_action( 'edited_em_event_type', array( $this, 'em_create_event_type_data') );
        // add custom column
        add_filter( 'manage_edit-em_event_type_columns', array( $this, 'add_event_type_custom_columns' ) );
        add_filter( 'manage_em_event_type_custom_column', array( $this, 'add_event_type_custom_column' ), 10, 3 );
        // sorting for ID column
        add_filter( 'manage_edit-em_event_type_sortable_columns', array( $this, 'add_event_type_sortable_custom_columns' ) );
        add_filter( 'pre_get_terms', array( $this, 'add_event_type_sortable_columns_callback' ) );

        // add banner
		add_action( 'load-edit-tags.php', function(){
			$screen = get_current_screen();
			if( 'edit-em_event_type' === $screen->id ) {
				add_action( 'after-em_event_type-table', function(){
					do_action( 'ep_add_custom_banner' );
				});
			}
		});
    }

	/**
	 * Add meta field to form
	 */
	public function add_event_type_fields() { 
        $ages_groups = array(
            'all' => esc_html__( 'All', 'eventprime-event-calendar-management' ),
            'parental_guidance' => esc_html__( 'All ages but parental guidance', 'eventprime-event-calendar-management' ),
            'custom_group' => esc_html__(' Custom Age', 'eventprime-event-calendar-management' )
        );?>
        <div class="form-field ep-type-admin-back-color">
            <label for="color">
                <?php esc_html_e( 'Background Color', 'eventprime-event-calendar-management' ); ?>
            </label>
            <input data-jscolor="{}" value="#2271B1" type="text" id="color" name="em_color" />
            <p class="emnote emeditor">
                <?php esc_html_e( 'Background color for events of this type when they appear on the events calendar.', 'eventprime-event-calendar-management' ); ?>
            </p>
        </div>
        <div class="form-field ep-type-admin-text-color">
            <label for="type_text_color">
                <?php esc_html_e( 'Text Color', 'eventprime-event-calendar-management' ); ?>
            </label>
            <input data-jscolor="{}" value="#000000" type="text" id="type_text_color" name="em_type_text_color" />
            <p class="emnote emeditor">
                <?php esc_html_e( 'Text color for events of this type when they appear on the events calendar. Can be overridden for individual events from their respective settings.', 'eventprime-event-calendar-management' ); ?>
            </p>
        </div>
        
        <div class="form-field ep-type-admin-age-group-selector">
            <label for="type_text_color">
                <?php esc_html_e( 'Age Group', 'eventprime-event-calendar-management' ); ?>
            </label>
            <select name="em_age_group" id="ep-event-type-age-group" class="ep-box-w-100">
                <?php foreach( $ages_groups as $key => $group ):?>
                    <option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $group );?></option>
                <?php endforeach;?>
            </select>
            <p class="emnote emeditor">
                <?php esc_html_e( 'Valid age group for the Event. This will be displayed on Event page.', 'eventprime-event-calendar-management' ); ?>
            </p>
        </div>
        <div class="form-field em-type-admin-age-group-custom" style="display:none;">
            <div class="ep-age-bar-fields">
                <input type="text" id="ep-custom-group" name="em_custom_group" readonly style="border:0; color:#f6931f; font-weight:bold;">
                <div id="ep-custom-group-range"></div>
            </div>
        </div>
        <div class="form-field ep-type-admin-image-wrap">
            <label><?php esc_html_e( 'Image', 'eventprime-event-calendar-management' ); ?></label>
            <div id="ep-type-admin-image"></div>
            <div>
                <input type="hidden" id="ep_type_image_id" name="em_image_id" />
                <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'eventprime-event-calendar-management' ); ?></button>
                <!-- <button type="button" class="remove_image_button button"><?php //esc_html_e( 'Remove image', 'eventprime-event-calendar-management' ); ?></button> -->
                <p class="emnote emeditor">
                    <?php esc_html_e( 'Image or icon of the Event Type. Will be displayed on the Event Types directory page.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </div>
        </div>
        <div class="form-field ep-type-admin-featured">
            <label >
                <?php esc_html_e('Featured', 'eventprime-event-calendar-management'); ?>
            </label>
            <label class="ep-toggle-btn">
                <input type="checkbox" id="is_featured" name="em_is_featured" />
                <span class="ep-toogle-slider round"></span>
            </label>
            <p class="emnote emeditor">
                <?php esc_html_e('Check if you want to make this event type featured.', 'eventprime-event-calendar-management'); ?>
            </p>
        </div><?php
	}

	/**
	 * Edit meta fields to form
     *
     * @param mixed $term Term
	 */
    public function edit_event_type_fields( $term ) {
        $ages_groups = array(
            'all' => esc_html__( 'All', 'eventprime-event-calendar-management' ),
            'parental_guidance' => esc_html__( 'All ages but parental guidance', 'eventprime-event-calendar-management' ),
            'custom_group' => esc_html__(' Custom Age', 'eventprime-event-calendar-management' )
        );
        
	    $em_color = get_term_meta( $term->term_id, 'em_color', true );
	    $em_type_text_color = get_term_meta( $term->term_id, 'em_type_text_color', true );
	    $em_image_id = get_term_meta( $term->term_id, 'em_image_id', true );
	    $image = '';
        if( ! empty( $em_image_id ) ) {
            $image = wp_get_attachment_image_url( $em_image_id );
        }
	    $em_is_featured = get_term_meta( $term->term_id, 'em_is_featured', true );
        $em_age_group = get_term_meta( $term->term_id, 'em_age_group', true );
        $custom_group = '';
        if( $em_age_group == 'custom_group' ){
            $custom_group = get_term_meta( $term->term_id, 'em_custom_group', true ); 
        }?>
        <tr class="form-field ep-type-admin-back-color">
            <th scope="row">
                <label><?php esc_html_e( 'Background Color', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <input data-jscolor="{}" value="<?php echo esc_attr($em_color); ?>" type="text" id="color" name="em_color" />
                <p class="description">
                    <?php esc_html_e( 'Background color for events of this type when they appear on the events calendar.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </td>
        </tr>
        <tr class="form-field ep-type-admin-text-color">
            <th scope="row">
                <label><?php esc_html_e( 'Text Color', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <input data-jscolor="{}" value="<?php echo esc_attr($em_type_text_color); ?>" type="text" id="type_text_color" name="em_type_text_color" />
                <p class="description">
                    <?php esc_html_e( 'Text color for events of this type when they appear on the events calendar. Can be overridden for individual events from their respective settings.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </td>
        </tr>
        <tr class="form-field ep-type-admin-age-group-selector">
            <th scope="row">
                <?php esc_html_e( 'Age Group', 'eventprime-event-calendar-management' ); ?>
            </th>
            <td>
                <select name="em_age_group" id="ep-event-type-age-group">
                    <?php foreach( $ages_groups as $key => $group ):?>
                        <option value="<?php echo esc_attr( $key );?>" <?php echo ( $em_age_group == esc_attr( $key ) ) ? 'selected' : '';?>><?php echo esc_html( $group );?></option>
                    <?php endforeach;?>
                </select>
                <div class="form-field em-type-admin-age-group-custom" style="<?php if( $em_age_group != 'custom_group' ) {echo 'display:none;';};?>">
                    <div class="ep-age-bar-fields">
                        <input type="text" id="ep-custom-group" name="em_custom_group" value="<?php echo esc_attr( $custom_group );?>" readonly style="border:0; color:#f6931f; font-weight:bold;">
                        <div id="ep-custom-group-range"></div>
                    </div>
                </div>
                <p class="description">
                    <?php esc_html_e( 'Valid age group for the Event. This will be displayed on Event page.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </td>
        </tr>
        
        <tr class="form-field ep-type-admin-image-wrap">
            <th scope="row">
                <label><?php esc_html_e( 'Image', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div id="ep-type-admin-image" style="float: left; margin-right: 10px;">
                    <span class="ep-event-type-image">
                        <?php if( ! empty( $image ) ) {?>
                            <i class="remove_image_button dashicons dashicons-trash ep-text-danger"></i>
                            <img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /><?php
                        }?>
                    </span>
                </div>
                <div style="line-height: 60px;">
                    <input type="hidden" id="ep_type_image_id" name="em_image_id" value="<?php echo esc_attr( $em_image_id ); ?>" />
                    <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'eventprime-event-calendar-management' ); ?></button>
                    <p class="description">
                        <?php esc_html_e( 'Image or icon of the Event Type. Will be displayed on the Event Types directory page.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </div>
            </td>
        </tr>
        <tr class="form-field ep-type-admin-featured">
            <th scope="row">
                <label><?php esc_html_e( 'Featured', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div class="form-field ep-type-admin-featured">
                    <label class="ep-toggle-btn">
                        <input type="checkbox" id="is_featured" name="em_is_featured" value="<?php echo esc_attr($em_is_featured); ?>" <?php if($em_is_featured == 1){ echo 'checked="checked"'; }?> />
                        <span class="ep-toogle-slider round"></span>
                    </label>
                    <p class="description">
                        <?php esc_html_e( 'Check if you want to make this event type featured.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </div>
            </td>
        </tr><?php
    }

    /**
     * Enqueue event type scripts
     */
    public function enqueue_admin_type_scripts( $hook ) {
        if( isset( $_GET['taxonomy'] ) && 'em_event_type' == sanitize_text_field( $_GET['taxonomy'] ) ) {
            wp_enqueue_media();
            wp_enqueue_style( 'em-admin-jquery-ui' );
            wp_enqueue_script( 'jquery-ui-slider' );
            wp_enqueue_script( 'em-admin-jscolor' );
            wp_enqueue_script(
                'em-type-admin-custom-js',
                EP_BASE_URL . '/includes/event_types/assets/js/em-type-admin-custom.js',
                false, EVENTPRIME_VERSION
            );

            wp_localize_script(
                'em-type-admin-custom-js', 
                'em_type_object', 
                array(
                    'media_title'  => esc_html__('Choose Image', 'eventprime-event-calendar-management'),
                    'media_button' => esc_html__('Use image', 'eventprime-event-calendar-management')  
                )
            );

            wp_enqueue_style(
                'em-type-admin-custom-css',
                EP_BASE_URL . '/includes/event_types/assets/css/em-type-admin-custom.css',
                false, EVENTPRIME_VERSION
            );
        }
    }

    /**
     * Create event type meta data
     *
     * @param int $term_id
     */
    public function em_create_event_type_data( $term_id ) {
        if( isset( $_POST['tax_ID'] ) && ! empty( $_POST['tax_ID'] ) ) return; 
        $color           = isset( $_POST['em_color'] ) ? sanitize_text_field( $_POST['em_color'] ) : '';
	    $type_text_color = isset( $_POST['em_type_text_color'] ) ? sanitize_text_field( $_POST['em_type_text_color'] ) : '';
	    $image_id        = isset( $_POST['em_image_id'] ) ? sanitize_text_field( $_POST['em_image_id'] ) : '';
	    $is_featured     = isset( $_POST['em_is_featured'] ) ? 1 : '0';
        $em_age_group    = isset( $_POST['em_age_group'] ) ? sanitize_text_field( $_POST['em_age_group'] ): 'all';
        $custom_group    = '';
        if( $em_age_group == 'custom_group' ) {
            $custom_group = isset( $_POST['em_custom_group'] ) ? sanitize_text_field( $_POST['em_custom_group'] ) : '';
        }
        update_term_meta( $term_id, 'em_color', $color );
	    update_term_meta( $term_id, 'em_type_text_color', $type_text_color );
	    update_term_meta( $term_id, 'em_image_id', $image_id );
	    update_term_meta( $term_id, 'em_is_featured', $is_featured );
        update_term_meta( $term_id, 'em_age_group', $em_age_group );
        if( ! empty( $custom_group ) ) {
            update_term_meta( $term_id, 'em_custom_group', $custom_group );
        }
        if ( ! metadata_exists( 'term', $term_id, 'em_status' ) ) {
            update_term_meta( $term_id, 'em_status', 1 );
        }
    }

    /**
     * Custom column added to event type admin
     *
     * @param mixed $columns Columns array.
     * @return array
     */
    public function add_event_type_custom_columns( $columns ) {
        $new_columns = array();

	    if ( isset( $columns['cb'] ) ) {
		    $new_columns['cb'] = $columns['cb'];
		    unset( $columns['cb'] );
	    }

        $new_columns['id'] = esc_html__( 'ID', 'eventprime-event-calendar-management' );
        
        $new_columns['image_id'] = esc_html__( 'Image', 'eventprime-event-calendar-management' );

        $columns = array_merge( $new_columns, $columns );

	    $columns['color'] = esc_html__( 'Background', 'eventprime-event-calendar-management' );

	    $columns['type_text_color'] = esc_html__( 'Text', 'eventprime-event-calendar-management' );

        // rename Count to Events
        $columns['posts'] = esc_html__( 'Events', 'eventprime-event-calendar-management' );
        
	    return $columns;
    }

    /**
     * Custom column value added to event type admin
     *
     * @param string $columns Column HTML output.
     * @param string $column Column name.
     * @param int    $id Term ID.
     *
     * @return string
     */
    public function add_event_type_custom_column( $columns, $column, $id ) {
        if( 'id' === $column ) {
            $columns .= '<span class="id-block">'.esc_html( $id ).'</span>';
        }

        if( 'image_id' === $column ) {
            $image_id = get_term_meta( $id, 'em_image_id', true );
            if( $image_id ) {
                $image = wp_get_attachment_thumb_url( $image_id );
	            $image    = str_replace( ' ', '%20', $image );
	            $columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Image', 'eventprime-event-calendar-management' ) . '" class="wp-post-image" height="48" width="48" />';
            }
        }

        if( 'color' === $column ) {
            $color = get_term_meta( $id, 'em_color', true );
            if( $color ) {
                $columns .= '<span class="color-block" style="background-color: '.$color.'"></span>';
            }
        }

	    if( 'type_text_color' === $column ) {
		    $type_text_color = get_term_meta( $id, 'em_type_text_color', true );
		    if( $type_text_color ) {
			    $columns .= '<span class="color-block" style="background-color: '.$type_text_color.'"></span>';
		    }
	    }

	    if ( 'handle' === $column ) {
		    $columns .= '<input type="hidden" name="term_id" value="' . esc_attr( $id ) . '" />';
	    }

        return $columns;
    }

    /**
     * Add sorting on the ID column
     * 
     * @param mixed $columns Columns array.
     * @return array
     */
    public function add_event_type_sortable_custom_columns( $columns ) {
        add_filter('pre_get_terms', 'callback_filter_terms_clauses');
        $columns['id'] = 'id';
        return $columns;
    }

    // callbak for sorting
    public function add_event_type_sortable_columns_callback( $term_query ) {
        global $pagenow;
        if( ! is_admin() ) return $term_query;

        if( is_admin() && $pagenow == 'edit-tags.php' && ( ! isset( $_GET['orderby'] ) || $_GET['orderby'] == 'id' ) ) {
            $term_query->query_vars['orderby'] = 'term_id';
            $term_query->query_vars['order'] = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : "DESC";
        }
        return $term_query;
    }
}

new EventM_Event_Types_Admin();