<?php
defined( 'ABSPATH' ) || exit;

class EventM_Organizers_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_organizer_scripts' ) );
        // add and edit form fields
		add_action('em_event_organizer_add_form_fields', array($this, 'add_event_organizer_fields') );
		add_action('em_event_organizer_edit_form_fields', array($this, 'edit_event_organizer_fields'), 10);
        // save event organizer
        add_action( 'created_em_event_organizer', array( $this, 'em_create_event_organizer_data') );
        // edit event organizer
		add_action( 'edited_em_event_organizer', array( $this, 'em_create_event_organizer_data') );
        // add custom column
		add_filter( 'manage_edit-em_event_organizer_columns', array( $this, 'add_event_organizer_custom_columns' ) );
		add_filter( 'manage_em_event_organizer_custom_column', array( $this, 'add_event_organizer_custom_column' ), 10, 3 );
        // sorting for ID column
        add_filter( 'manage_edit-em_event_organizer_sortable_columns', array( $this, 'add_event_organizer_sortable_custom_columns' ) );
        add_filter( 'pre_get_terms', array( $this, 'add_event_organizer_sortable_columns_callback' ) );
	}

    /**
	 * Add meta field to form
	 */
	public function add_event_organizer_fields() { ?>
        <div class="form-field ep-organizer-admin-phone">
            <label for="em_organizer_phones">
                <?php esc_html_e( 'Phone', 'eventprime-event-calendar-management' ); ?>
            </label>
            <div class="ep-organizers-phone">
                <span class="ep-org-phone ep-org-data-field">
                    <input type="text" class="ep-org-data-input" name="em_organizer_phones[]" placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>">
                    <button type="button" class="ep-org-add-more button button-primary" data-input="phone" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                        +
                    </button>
                </span>
                <p class="emnote emeditor">
                    <?php esc_html_e( "Add the Organizer's phone numbers.", 'eventprime-event-calendar-management' ); ?>
                </p>
            </div>
        </div>
        <div class="form-field ep-organizer-admin-email">
            <label for="em_organizer_emails">
                <?php esc_html_e( 'Email', 'eventprime-event-calendar-management' ); ?>
            </label>
            <div class="ep-organizers-email">
                <span class="ep-org-email ep-org-data-field">
                    <input type="email" name="em_organizer_emails[]" placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>">
                    <button type="button" class="ep-org-add-more button button-primary" data-input="email" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>">
                        +
                    </button>
                </span>
                <p class="emnote emeditor">
                    <?php esc_html_e( "Add the Organizer's email addresses.", 'eventprime-event-calendar-management' ); ?>
                </p>
            </div>
        </div>
        <div class="form-field ep-organizer-admin-website">
            <label for="em_organizer_websites">
                <?php esc_html_e( 'Website', 'eventprime-event-calendar-management' ); ?>
            </label>
            <div class="ep-organizers-website">
                <span class="ep-org-website ep-org-data-field">
                    <input type="text" name="em_organizer_websites[]" placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>">
                    <button type="button" class="ep-org-add-more button button-primary" data-input="website" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>">
                        +
                    </button>
                </span>
                <p class="emnote emeditor">
                    <?php esc_html_e( "Add the Organizer's website URLs.", 'eventprime-event-calendar-management' ); ?>
                </p>
            </div>
        </div>

        <div class="form-field ep-organizer-admin-image-wrap">
            <label><?php esc_html_e( 'Image', 'eventprime-event-calendar-management' ); ?></label>
            <div id="ep-organizer-admin-image"></div>
            <div>
                <input type="hidden" id="ep_organizer_image_id" name="em_image_id" />
                <button type="button" class="upload_image_button button"><?php echo esc_attr( 'Upload/Add image', 'eventprime-event-calendar-management' ); ?></button>
                <p class="emnote emeditor">
                    <?php esc_html_e( 'Image or icon of the Event Organizer.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </div>
        </div>
        
        <?php $social_links = ep_social_sharing_fields();
        foreach( $social_links as $key => $links) { ?>
            <div class="form-field ep-organizer-admin-social">
                <label for="<?php echo esc_attr($key);?>" >
                    <?php echo $links;?>
                </label>
                <input type="text" name="em_social_links[<?php echo $key;?>]" placeholder="<?php echo sprintf( __( 'https://www.%s.com/XYZ/', 'eventprime-event-calendar-management' ), strtolower( $links ) ); ?>">
                <p class="emnote emeditor">
                    <?php echo sprintf( __( 'Enter %s URL of the Organizer, if available. Eg.:https://www.%s.com/XYZ/', 'eventprime-event-calendar-management' ), $links, strtolower( $links ) ); ?>
                </p>
            </div>
            <?php
        }?>

        <div class="form-field ep-organizer-admin-featured">
            <label for="is_featured">
				<?php esc_html_e( 'Featured', 'eventprime-event-calendar-management' ); ?>
                <label class="ep-toggle-btn">
                    <input type="checkbox" id="is_featured" name="em_is_featured" />
                    <span class="ep-toogle-slider round"></span>
                </label>
                <p class="emnote emeditor">
                    <?php esc_html_e( 'Check if you want to make this organizer featured.', 'eventprime-event-calendar-management' ); ?>
                </p>
            </label>
        </div><?php
	}

    /**
	 * Edit meta fields to form
     *
     * @param mixed $term Term
	 */
    public function edit_event_organizer_fields( $term ) {
	    $em_organizer_phones = get_term_meta( $term->term_id, 'em_organizer_phones', true );
	    $em_organizer_emails = get_term_meta( $term->term_id, 'em_organizer_emails', true );
        $em_organizer_websites = get_term_meta( $term->term_id, 'em_organizer_websites', true );
        $em_social_links = (array)get_term_meta( $term->term_id, 'em_social_links', true );
	    $em_image_id = get_term_meta( $term->term_id, 'em_image_id', true );
	    $image = '';
        if($em_image_id) {
            $image = wp_get_attachment_image_url( $em_image_id );
        }
	    $em_is_featured = get_term_meta( $term->term_id, 'em_is_featured', true );?>
        <tr class="form-field ep-type-admin-phone">
            <th scope="row">
                <label><?php esc_html_e( 'Phone', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div class="ep-organizers-phone">
                    <?php if( ! empty( $em_organizer_phones ) && count( $em_organizer_phones ) > 0 ) {
                        $p = 0; 
                        foreach( $em_organizer_phones as $phones ) { ?>
                            <span class="ep-org-phone ep-org-data-field">
                                <input type="text" class="ep-org-data-input" value="<?php echo esc_attr($phones);?>" name="em_organizer_phones[]" placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>">
                                <?php if( $p == 0 ) { ?>
                                    <button type="button" class="ep-org-add-more button button-primary" data-input="phone" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                        +
                                    </button><?php
                                } else{ ?>
                                    <button type="button" class="ep-org-remove button button-primary" data-input="phone" title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                        -
                                    </button><?php
                                }?>
                            </span>
                            <p class="emnote description">
                                <?php esc_html_e( "Add the Organizer's phone numbers.", 'eventprime-event-calendar-management' ); ?>
                            </p><?php
                            $p++;
                        }
                    } else{?>
                        <span class="ep-org-phone ep-org-data-field">
                            <input type="text" class="ep-org-data-input" value="" name="em_organizer_phones[]" placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>">
                                <button type="button" class="ep-org-add-more button button-primary" data-input="phone" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                    +
                                </button>
                        </span>
                        <p class="emnote description">
                            <?php esc_html_e( "Add the Organizer's phone numbers.", 'eventprime-event-calendar-management' ); ?>
                        </p><?php
                    }?>
                </div>
            </td>
        </tr>
        <tr class="form-field ep-type-admin-email">
            <th scope="row">
                <label><?php esc_html_e( 'Email', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div class="ep-organizers-email">
                    <?php if( ! empty( $em_organizer_emails ) && count( $em_organizer_emails ) > 0 ) {
                        $p = 0; 
                        foreach( $em_organizer_emails as $emails ) { ?>
                            <span class="ep-org-email ep-org-data-field">
                                <input type="text" class="ep-org-data-input" value="<?php echo esc_attr($emails);?>" name="em_organizer_emails[]" placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>">
                                <?php if( $p == 0 ) { ?>
                                    <button type="button" class="ep-org-add-more button button-primary" data-input="email" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                        +
                                    </button><?php
                                } else{ ?>
                                    <button type="button" class="ep-org-remove button button-primary" data-input="email" title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                        -
                                    </button><?php
                                }?>
                            </span>
                            <p class="emnote description">
                                <?php esc_html_e( "Add the Organizer's email addresses.", 'eventprime-event-calendar-management' ); ?>
                            </p><?php
                            $p++;
                        }
                    } else{?>
                        <span class="ep-org-email ep-org-data-field">
                            <input type="text" class="ep-org-data-input" value="" name="em_organizer_emails[]" placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>">
                                <button type="button" class="ep-org-add-more button button-primary" data-input="email" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                    +
                                </button>
                        </span>
                        <p class="emnote description">
                            <?php esc_html_e( "Add the Organizer's email addresses.", 'eventprime-event-calendar-management' ); ?>
                        </p><?php
                    }?>
                </div>
            </td>
        </tr>
        <tr class="form-field ep-type-admin-website">
            <th scope="row">
                <label><?php esc_html_e( 'Website', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div class="ep-organizers-website">
                    <?php if( ! empty( $em_organizer_websites ) && count( $em_organizer_websites ) > 0 ) {
                        $p = 0; 
                        foreach( $em_organizer_websites as $websites ) { ?>
                            <span class="ep-org-website ep-org-data-field">
                                <input type="text" class="ep-org-data-input" value="<?php echo esc_attr($websites);?>" name="em_organizer_websites[]" placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>">
                                <?php if( $p == 0 ) { ?>
                                    <button type="button" class="ep-org-add-more button button-primary" data-input="website" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                        +
                                    </button><?php
                                } else{ ?>
                                    <button type="button" class="ep-org-remove button button-primary" data-input="website" title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                        -
                                    </button><?php
                                }?>
                            </span>
                            <p class="emnote description">
                                <?php esc_html_e( "Add the Organizer's website URLs.", 'eventprime-event-calendar-management' ); ?>
                            </p><?php
                            $p++;
                        }
                    } else{?>
                        <span class="ep-org-website ep-org-data-field">
                            <input type="text" class="ep-org-data-input" value="" name="em_organizer_websites[]" placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>">
                                <button type="button" class="ep-org-add-more button button-primary" data-input="website" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                    +
                                </button>
                        </span>
                        <p class="emnote description">
                            <?php esc_html_e( "Add the Organizer's website URLs.", 'eventprime-event-calendar-management' ); ?>
                        </p><?php
                    }?>
                </div>
            </td>
        </tr>
        
        <tr class="form-field ep-type-admin-image-wrap">
            <th scope="row">
                <label><?php esc_html_e( 'Image', 'eventprime-event-calendar-management' ); ?></label>
            </th>
            <td>
                <div id="ep-organizer-admin-image" style="float: left; margin-right: 10px;">
                    <?php if( ! empty( $image ) ) {?>
                        <i class="remove_image_button dashicons dashicons-trash ep-text-danger"></i>
                        <img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /><?php
                    }?>
                </div>
                <div style="line-height: 60px;">
                    <input type="hidden" id="ep_organizer_image_id" name="em_image_id" value="<?php echo esc_attr( $em_image_id ); ?>" />
                    <button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'eventprime-event-calendar-management' ); ?></button>
                    <p class="description">
                        <?php esc_html_e( 'Image or icon of the Event Organizer.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </div>
            </td>
        </tr>
        <?php $social_links = ep_social_sharing_fields();
        foreach( $social_links as $key => $links) { 
            $sl = ( ! empty( $em_social_links[$key] ) ? $em_social_links[$key] : '' );?>
            <tr class="form-field ep-type-admin-social">
                <th scope="row">
                    <label><?php echo esc_html( $links ); ?></label>
                </th>
                <td>
                    <input type="text" class="ep-org-data-social-input" value="<?php echo esc_attr( $sl );?>" name="em_social_links[<?php echo esc_attr( $key );?>]" placeholder="<?php echo esc_attr( $links ); ?>" >
                    <p class="description">
                        <?php echo sprintf( __( 'Enter %s link', 'eventprime-event-calendar-management' ), $links ); ?>
                    </p>
                </td>
            </tr><?php
        }?>

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
                        <?php esc_html_e( 'Check if you want to make this organizer featured.', 'eventprime-event-calendar-management' ); ?>
                    </p>
                </div>
            </td>
        </tr><?php
    }

    /**
     * Enqueue organizers scripts
     */

    public function enqueue_admin_organizer_scripts() {
	    wp_enqueue_media();

	    wp_enqueue_script(
		    'em-organizer-admin-custom-js',
		    EP_BASE_URL . '/includes/organizers/assets/js/em-organizer-admin-custom.js',
		    false, EVENTPRIME_VERSION
        );

        wp_localize_script(
            'em-organizer-admin-custom-js', 
            'em_organizer_object', 
            array(
                'media_title'  => esc_html__( 'Choose Image', 'eventprime-event-calendar-management' ),
                'media_button' => esc_html__( 'Use image', 'eventprime-event-calendar-management' ),
                'max_field_warning' => esc_html__( 'Maximum limit reached for adding', 'eventprime-event-calendar-management' )
            )
        );
        wp_enqueue_style(
            'em-organizer-admin-custom-css',
            EP_BASE_URL . '/includes/organizers/assets/css/em-organizer-admin-custom.css',
            false, EVENTPRIME_VERSION
        );
        wp_enqueue_style( 'ep-toast-css' );
        wp_enqueue_script( 'ep-toast-js' );
        wp_enqueue_script( 'ep-toast-message-js' );
    }

    /**
     * Create organizer meta data
     *
     * @param int $term_id
     */
    public function em_create_event_organizer_data( $term_id ) {
        $em_organizer_phones = $em_organizer_emails = $em_organizer_websites = array();
        // check for valid phone number
        if( isset( $_POST['em_organizer_phones'] ) && count( $_POST['em_organizer_phones'] ) > 0 ) {
            foreach( $_POST['em_organizer_phones'] as $phone ) {
                if( ! empty( $phone ) ) {
                    $phone_no = is_valid_phone( sanitize_text_field( $phone ) );
                    if( $phone_no ) {
                        $em_organizer_phones[] = $phone;
                    }
                } 
            }
        }
        // check for valid email
        if( isset( $_POST['em_organizer_emails'] ) && count( $_POST['em_organizer_emails'] ) > 0 ) {
            foreach( $_POST['em_organizer_emails'] as $email ) {
                if( ! empty( $email ) ) {
                    $email = sanitize_email( $email );
                    if( ! empty( $email ) && filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                        $em_organizer_emails[] = $email;
                    }
                } 
            }
        }
        // check for valid website URL
        if( isset( $_POST['em_organizer_websites'] ) && count( $_POST['em_organizer_websites'] ) > 0 ) {
            foreach( $_POST['em_organizer_websites'] as $website ) {
                if( ! empty( $website ) ) {
                    $site_url_valid = is_valid_site_url( sanitize_text_field( $website ) );
                    if( $site_url_valid ) {
                        $em_organizer_websites[] = $website;
                    }
                } 
            }
        }
        $em_organizer_phones   = ( ! empty( $em_organizer_phones ) ? $em_organizer_phones : '' );
        $em_organizer_emails   = ( ! empty( $em_organizer_emails ) ? $em_organizer_emails : '' );
        $em_organizer_websites = ( ! empty( $em_organizer_websites ) ? $em_organizer_websites : '' );
        $em_image_id           = isset( $_POST['em_image_id'] ) ? $_POST['em_image_id'] : '';
        $em_is_featured        = isset( $_POST['em_is_featured'] ) ? 1 : '0';
        $em_social_links       = isset( $_POST['em_social_links'] ) ? $_POST['em_social_links'] : '';
        
        update_term_meta( $term_id, 'em_organizer_phones', $em_organizer_phones );
        update_term_meta( $term_id, 'em_organizer_emails', $em_organizer_emails );
        update_term_meta( $term_id, 'em_organizer_websites', $em_organizer_websites );
        update_term_meta( $term_id, 'em_image_id', $em_image_id );
        update_term_meta( $term_id, 'em_is_featured', $em_is_featured );
        update_term_meta( $term_id, 'em_social_links', $em_social_links );
        if ( ! metadata_exists( 'term', $term_id, 'em_status' ) ) {
            update_term_meta( $term_id, 'em_status', 1 );
        }
    }

    /**
     * Custom column added to organizer admin
     *
     * @param mixed $columns Columns array.
     * @return array
     */
    public function add_event_organizer_custom_columns( $columns ) {
        $new_columns = array();

	    if ( isset( $columns['cb'] ) ) {
		    $new_columns['cb'] = $columns['cb'];
		    unset( $columns['cb'] );
	    }

        $new_columns['id'] = esc_html__( 'ID', 'eventprime-event-calendar-management' );

        $new_columns['image_id'] = __( 'Image', 'eventprime-event-calendar-management' );

        $columns = array_merge( $new_columns, $columns );

	    $columns['phone'] = __( 'Phone', 'eventprime-event-calendar-management' );

	    $columns['email'] = __( 'Email', 'eventprime-event-calendar-management' );

        // rename Count to Events
        $columns['posts'] = esc_html__( 'Events', 'eventprime-event-calendar-management' );

	    return $columns;
    }

    /**
     * Custom column value added to organizer admin
     *
     * @param string $columns Column HTML output.
     * @param string $column Column name.
     * @param int    $id Term ID.
     *
     * @return string
     */
    public function add_event_organizer_custom_column( $columns, $column, $id ) {
        if( 'id' === $column ) {
            $columns .= '<span class="id-block">'.esc_html( $id ).'</span>';
        }

        if( 'image_id' === $column ) {
            $image_id = get_term_meta( $id, 'em_image_id', true );
            if( $image_id ) {
                $image = wp_get_attachment_thumb_url( $image_id );
                if( $image ) {
	                $image    = str_replace( ' ', '%20', $image );
	                $columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Image', 'eventprime-event-calendar-management' ) . '" class="wp-post-image" height="48" width="48" />';
                }
            }
        }

        if( 'phone' === $column ) {
            $em_organizer_phones = get_term_meta( $id, 'em_organizer_phones', true );
            if( $em_organizer_phones ) {
                $columns .= '<span class="phone-block">'.implode(',', $em_organizer_phones).'</span>';
            }
        }

	    if( 'email' === $column ) {
            $em_organizer_emails = get_term_meta( $id, 'em_organizer_emails', true );
            if( $em_organizer_emails ) {
                $columns .= '<span class="email-block">'.implode(',', $em_organizer_emails).'</span>';
            }
        }

        return $columns;
    }

    /**
     * Add sorting on the ID column
     * 
     * @param mixed $columns Columns array.
     * @return array
     */
    public function add_event_organizer_sortable_custom_columns( $columns ) {
        add_filter('pre_get_terms', 'callback_filter_terms_clauses');
        $columns['id'] = 'id';
        return $columns;
    }

    // callbak for sorting
    public function add_event_organizer_sortable_columns_callback( $term_query ) {
        global $pagenow;
        if( ! is_admin() ) return $term_query;

        if( is_admin() && $pagenow == 'edit-tags.php' && ( ! isset( $_GET['orderby'] ) || $_GET['orderby'] == 'id' ) ) {
            $term_query->query_vars['orderby'] = 'term_id';
            $term_query->query_vars['order'] = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : "DESC";
        }
        return $term_query;
    }
}

new EventM_Organizers_Admin();