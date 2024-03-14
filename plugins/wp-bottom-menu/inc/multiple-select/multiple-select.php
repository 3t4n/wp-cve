<?php

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Multiselect option for WP Customizer
 *
 * @param $wp_customize
 */

/**
 * Multiple select customize control class.
 */
class Customize_Control_Multiple_Select extends WP_Customize_Control {

    /**
     * The type of customize control being rendered.
     */
    public $type = 'multiple-select';

    /**
     * Displays the multiple select on the customize screen.
     */
    public function render_content() {

        if ( empty( $this->choices ) ) {
            return;
        }
        ?>

        <label>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <select <?php $this->link(); ?> class="wp-bottom-menu-select2" multiple="multiple" style="width: 100%">
                <?php
                foreach ( $this->choices as $value => $label ) {
                    $selected = ( in_array( $this->value(), $this->choices ) ) ? selected( 1, 1, false ) : '';
                    echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
                }
                ?>
            </select>
        </label>
    <?php

    }

}

function wpbm_get_user_roles() {

    global $wp_roles;
    $roles = [ 'all' => esc_html__( 'All Visitors', 'wp-bottom-menu' ) ];

    // check roles
    if ( ! isset( $wp_roles ) ){
        $wp_roles = new WP_Roles();
    }

    // add roles in array
    foreach( $wp_roles->get_names() as $key => $role ){
        $roles[$key] = $role;
    }

    // return roles
    return $roles;

}