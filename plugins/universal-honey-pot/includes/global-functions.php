<?php
/**
 * The global functions for this plugin
 * 
 * @since    1.0.0
 */

function get_universal_honey_pot_supported_plugins(){
    $supported_plugins = array(
        'contact-form-7/wp-contact-form-7.php' => array(
            'name' => __( 'Contact Form 7', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/contact-form-7.svg',
        ),
        'elementor-pro/elementor-pro.php' => array(
            'name' => __( 'Elementor Pro', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/elementor-pro.svg',
        ),
        'formidable/formidable.php' => array(
            'name' => __( 'Formidable Forms', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/formidable-forms.png',
        ),
        'forminator/forminator.php' => array(
            'name' => __( 'Forminator', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/forminator.png',
        ),
        'Divi' => array(
            'name' => __( 'Divi', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/divi.png',
            'is_theme' => true,
        ),
        'wpforms-lite/wpforms.php' => array(
            'name' => __( 'WPForms', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/wp-forms.svg',
        ),
        'fluentform/fluentform.php' => array(
            'name' => __( 'Fluent Form', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/fluent-form.png',
            'comming_soon' => true,
        ),
        'jetpack/jetpack.php' => array(
            'name' => __( 'Jetpack', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/jetpack.svg',
            'comming_soon' => true,
        ),
        'gravityforms/gravityforms.php' => array(
            'name' => __( 'Gravity Forms', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/gravity-forms.png',
            'comming_soon' => true,
        ),
        'everest-forms/everest-forms.php' => array(
            'name' => __( 'Everest Forms', 'universal-honey-pot' ),
            'img'  => UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/everest-forms.svg',
            'comming_soon' => true,
        ),
    );
    return apply_filters( 'universal_honey_pot/supported_plugins', $supported_plugins );
}

/**
 * get_universal_honey_pot_fields
 *
 * @return void
 */
function get_universal_honey_pot_fields(){
    $blog_name = get_bloginfo( 'name' );
    $hash = get_universal_honey_pot_hash();
    $array = array(
        'website'   => array(
            'label' => _x( 'Website', 'label', 'universal-honey-pot' ),
            'type'  => 'url',
        ),
        'firstname' => array(
            'label' => _x( 'First Name', 'label', 'universal-honey-pot' ),
            'type'  => 'text',
        ),
        'lastname'  => array(
            'label' => _x( 'Last Name', 'label', 'universal-honey-pot' ),
            'type'  => 'text',
        ),
        'email'    => array(
            'label' => _x( 'Email', 'label', 'universal-honey-pot' ),
            'type'  => 'email',
        ),
        $hash      => array(
            'label' =>  $blog_name,
            'type'  => 'text',
        ),
    );
    return apply_filters( 'universal_honey_pot/fields', $array );
}

/**
 * get_universal_honey_pot_hash
 *
 * @return void
 */
function get_universal_honey_pot_hash(){
    $salt = apply_filters( 'universal_honey_pot/salt', 'C99;d$QF>m6=zJnAO[VkHM})s-MSgVL(p<[ [aEpc]}>29SIn#7b}D[DRJvfY-cB' );
    if(defined('SECURE_AUTH_COOKIE') ){
        $phrase = SECURE_AUTH_COOKIE . $salt;
    } else {
        $phrase = get_bloginfo( 'url' ) . $salt;
    }
    $hash   = hash( 'sha256', $phrase );
    $hash   = substr( $hash, 0, 30 );
    return $hash;
}

/**
 * update_universal_honey_pot_counter
 *
 * @return void
 */
function update_universal_honey_pot_counter(){
    $counter = (int) get_option( 'universal_honey_pot_counter', 0 );
    $counter++;
    update_option( 'universal_honey_pot_counter', (int) $counter );
    return $counter;
}

/**
 * get_universal_honey_pot_inputs_html
 *
 * @return void
 */
function get_universal_honey_pot_inputs_html( $args = array() ){
    $hash                 = get_universal_honey_pot_hash();
    $hash_without_numbers = preg_replace( '/[0-9]+/', '', $hash );

    $honey_pot = '<style type="text/css">.'. $hash_without_numbers .' { position: absolute !important; left: -9999vw !important; }</style>';

    $honey_pot .= '<div class="'. $hash_without_numbers .'">';

    foreach( get_universal_honey_pot_fields() as $name => $data ) {
        $input_id = $name . '-' . $hash_without_numbers;
        $honey_pot .= '<label for="'. $input_id .'">'. $data['label'] .'</label>';
        $honey_pot .= '<input type="'. $data['type'] .'" name="'. $name .'" value="" autocomplete="off" tabindex="-1" id="'. $input_id .'"  />';
    }

    $honey_pot .= '</div>';

    return $honey_pot;
}