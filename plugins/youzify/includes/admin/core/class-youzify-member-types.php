<?php

class Youzify_Admin_Member_Types {

	function __construct() {

        // Save Member Types Fields Data.
        add_action( 'bp_type_inserted', array( $this, 'save_member_types_fields' ), 10 );
        add_action( 'bp_type_updated', array( $this, 'update_member_types_fields' ), 10, 2 );

        // Add Member Types Fields.
        add_action( 'bp_member_type_add_form_fields', array( $this, 'member_types_add_view' ), 20 );
        add_action( 'bp_member_type_edit_form_fields', array( $this, 'member_types_edit_view' ), 20 );
    }


    public function member_types_add_view( $taxonomy ) {

        ?>

        <div class="form-field bp-types-form">
            <label><?php _e( 'Member Type Icon', 'youzify' ); ?></label>
            <div class="ukai_iconPicker" data-icons-type="web_application">
                <div class="ukai_icon_selector"><i class="fas fa-user"></i><span class="ukai_select_icon"><i class="fas fa-sort-down"></i></span></div>
                <input type="hidden" class="ukai-selected-icon" name="youzify_type_icon" value="fas fa-user">
            </div>
            <p class="description"><?php _e( 'Select member type icon.', 'youzify' ); ?></p>
        </div>

        <div class="form-field bp-types-form">
            <label style=" margin-bottom: 10px;"><?php _e( 'Icon Background Left Color', 'youzify' ); ?></label>
            <input type="text" name="youzify_type_bg_left_color" id="colorinput" value="" class="youzify-color-field" />
            <p class="description"><?php _e( 'Directory icon background left color.', 'youzify' ); ?></p>
            <div id="colorpicker"></div>
        </div>

        <div class="form-field bp-types-form">
            <label style=" margin-bottom: 10px;"><?php _e( 'Icon Background Right Color', 'youzify' ); ?></label>
            <input type="text" name="youzify_type_bg_right_color" id="colorinput" value="" class="youzify-color-field" />
            <p class="description"><?php _e( 'Directory icon background right color.', 'youzify' ); ?></p>
            <div id="colorpicker"></div>
        </div>

        <?php

        // Scripts
        $this->member_types_scripts();

    }

    /**
     * Member Type
     */
    public function member_types_edit_view( $term ) {

        // Get Data
        $icon = get_term_meta( $term->term_id, 'youzify_type_icon', true );
        $left_color = get_term_meta( $term->term_id, 'youzify_type_bg_left_color', true );
        $right_color = get_term_meta( $term->term_id, 'youzify_type_bg_right_color', true );

        ?>

        <tr class="form-field bp-types-form">
            <th scope="row"><label><?php _e( 'Member Type Icon', 'youzify' ); ?></label></th>
            <td>
                <div class="ukai_iconPicker" data-icons-type="web_application">
                    <div class="ukai_icon_selector"><i class="<?php echo ! empty( $icon ) ? $icon : 'fas fa-user'; ?>"></i><span class="ukai_select_icon"><i class="fas fa-sort-down"></i></span></div>
                    <input type="hidden" class="ukai-selected-icon" name="youzify_type_icon" value="<?php  echo ! empty( $icon ) ? $icon : 'fas fa-user'; ?>">
                </div>
                <p class="description"><?php _e( 'Select member type icon.', 'youzify' ); ?></p>
            </td>
        </tr>

        <tr class="form-field bp-types-form">
            <th scope="row"><label style=" margin-bottom: 10px;"><?php _e( 'Icon Background Left Color', 'youzify' ); ?></label></th>
            <td><input type="text" name="youzify_type_bg_left_color" id="colorinput" value="<?php echo ! empty( $left_color ) ? $left_color : ''; ?>" class="youzify-color-field" />
            <p class="description"><?php _e( 'Directory icon background left color.', 'youzify' ); ?></p>
            <div id="colorpicker"></div></td>
        </tr>

        <tr class="form-field bp-types-form">
            <th scope="row"><label style=" margin-bottom: 10px;"><?php _e( 'Icon Background Right Color', 'youzify' ); ?></label></th>
            <td><input type="text" name="youzify_type_bg_right_color" id="colorinput" value="<?php echo ! empty( $right_color ) ? $right_color : ''; ?>" class="youzify-color-field" />
            <div id="colorpicker"></div>
            <p class="description"><?php _e( 'Directory icon background right color.', 'youzify' ); ?></p></td>
        </tr>

        <?php

        // Scripts.
        $this->member_types_scripts();

    }

    /**
     * Scripts and Styles
     */
    function member_types_scripts() {

    	// Load Icons
    	wp_enqueue_style( 'youzify-icons', YOUZIFY_ADMIN_ASSETS . 'css/all.min.css', array(), YOUZIFY_VERSION );

    	// Load Icon Picket
	    youzify_iconpicker_scripts();

    	// Load Color Picker
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );

        ?>

        <script type="text/javascript">
            (function( $ ) {
                // Add Color Picker to all inputs that have 'color-field' class
                $( function() {
                    jQuery( '.youzify-color-field' ).wpColorPicker();
                });
            })( jQuery );
        </script>

        <?php

    }
    /**
     * Save Member Types Fields.
     */
    public function save_member_types_fields( $result ) {

        // Save Left Color
        if ( isset( $_POST['youzify_type_bg_left_color'] ) && ! empty( $_POST['youzify_type_bg_left_color'] ) ) {
            add_term_meta( $result['term_id'], 'youzify_type_bg_left_color', sanitize_hex_color( $_POST['youzify_type_bg_left_color'] ), true );
        }

        // Save Right Color
        if ( isset( $_POST['youzify_type_bg_right_color'] ) && ! empty( $_POST['youzify_type_bg_right_color'] ) ) {
            add_term_meta( $result['term_id'], 'youzify_type_bg_right_color', sanitize_hex_color( $_POST['youzify_type_bg_right_color'] ), true );
        }

        // Save Icon
        if ( isset( $_POST['youzify_type_icon'] ) && ! empty( $_POST['youzify_type_icon'] ) ) {
            add_term_meta( $result['term_id'], 'youzify_type_icon', sanitize_text_field( $_POST['youzify_type_icon'] ), true );
        }
    }

	/**
     * Update Member Types Fields.
     */
    function update_member_types_fields( $term_id, $tt_id ) {

        // Save Left Color
        if ( isset( $_POST['youzify_type_bg_left_color'] ) && ! empty( $_POST['youzify_type_bg_left_color'] ) ) {
            update_term_meta( $term_id, 'youzify_type_bg_left_color', sanitize_hex_color( $_POST['youzify_type_bg_left_color'] ) );
        } else {
            delete_term_meta( $term_id, 'youzify_type_bg_left_color' );
        }

        // Save Right Color
        if ( isset( $_POST['youzify_type_bg_right_color'] ) && ! empty( $_POST['youzify_type_bg_right_color'] ) ) {
            update_term_meta( $term_id, 'youzify_type_bg_right_color', sanitize_hex_color( $_POST['youzify_type_bg_right_color'] ) );
        } else {
            delete_term_meta( $term_id, 'youzify_type_bg_right_color' );
        }

        // Save Right Color
        if ( isset( $_POST['youzify_type_icon'] ) && ! empty( $_POST['youzify_type_icon'] ) ) {
            update_term_meta( $term_id, 'youzify_type_icon', sanitize_text_field( $_POST['youzify_type_icon'] ) );
        } else {
            delete_term_meta( $term_id, 'youzify_type_icon' );
        }
    }
}

new Youzify_Admin_Member_Types;