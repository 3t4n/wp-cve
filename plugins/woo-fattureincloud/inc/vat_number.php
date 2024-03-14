<?php
/*
 *
 * valori nei campi del checkout
 *
 */
if ( ! is_admin() ) {


function billing_fields_woofc( $fields ) 
{


    global $woocommerce;
    $country = $woocommerce->customer->get_billing_country();

    if ($country !== 'IT') {
    
        $initaliasi = false;
    
    } else {

        $initaliasi = true;
        
    }


    $fields['billing_cod_fisc'] = array(
    'label'       => __('Fiscal Code', 'woo-fattureincloud'),
    'placeholder' => __('Here Fiscal Code', 'woo-fattureincloud'),
    'required'    => $initaliasi,
    'priority'    => 120,
    'class'       => array('form-row-wide'),
    );

    $fields['billing_partita_iva'] = array(
    'label'       => __('Vat Number', 'woo-fattureincloud'),
    'placeholder' => __('Vat Number Here', 'woo-fattureincloud'),
    'required'    => false,
    'priority'    => 130,
    'class'       => array('form-row-wide'),
    );

    $fields['billing_pec_email'] = array(
        'label'       => __('Email Pec', 'woo-fattureincloud'),
        'placeholder' => __('For Electronic Billing', 'woo-fattureincloud'),
        'required'    => false,
        'type'        => 'text',
        'priority'    => 140,
        'class'       => array('form-row-wide'),

    );

    $fields['billing_codice_destinatario'] = array(
    'label'       => __('Recipient Code', 'woo-fattureincloud'),
    'placeholder' => __('For Electronic Billing', 'woo-fattureincloud'),
    'required'    => false,
    'type'        => 'text',
    'priority'    => 150,
    'class'       => array('form-row-wide'),

    );

    return $fields;
}

}

/*
 *
 * valori modificabili dell'ordine
 *
 * */

function admin_billing_field( $fields ) 
{
    $fields['cod_fisc'] = array(
    'label' => __('Fiscal Code', 'woo-fattureincloud'),
    'wrapper_class' => 'form-field-wide',
    'show' => true,
    );

    $fields['partita_iva'] = array(
    'label' => __('Vat Number', 'woo-fattureincloud'),
    'wrapper_class' => 'form-field-wide',
    'show' => true,
    );

    $fields['pec_email'] = array(
    'label' => __('Email Pec', 'woo-fattureincloud'),
    'wrapper_class' => 'form-field-wide',
    'show' => true,
    );

    $fields['codice_destinatario'] = array(
    'label' => __('Recipient Code', 'woo-fattureincloud'),
    'wrapper_class' => 'form-field-wide',
    'show' => true,
    );

    return $fields;

}

#######################################
# Add meta data customer custom field
#######################################

add_filter('woocommerce_customer_meta_fields', 'wordpress_user_account_billing_cod_fisc_field');
function wordpress_user_account_billing_cod_fisc_field( $fields ) {
    $fields['billing']['fields']['billing_cod_fisc'] = array(
        'label'       => __('Fiscal Code', 'woocommerce'),
        'description' => __('', 'woocommerce')
    );
    return $fields;
}

add_filter('woocommerce_customer_meta_fields', 'wordpress_user_account_billing_partita_iva_field');
function wordpress_user_account_billing_partita_iva_field( $fields ) {
    $fields['billing']['fields']['billing_partita_iva'] = array(
        'label'       => __('Vat Number', 'woocommerce'),
        'description' => __('', 'woocommerce')
    );
    return $fields;
}

add_filter('woocommerce_customer_meta_fields', 'wordpress_user_account_billing_pec_email_field');
function wordpress_user_account_billing_pec_email_field( $fields ) {
    $fields['billing']['fields']['billing_pec_email'] = array(
        'label'       => __('Email Pec', 'woocommerce'),
        'description' => __('', 'woocommerce')
    );
    return $fields;
}

add_filter('woocommerce_customer_meta_fields', 'wordpress_user_account_billing_codice_destinatario_field');
function wordpress_user_account_billing_codice_destinatario_field( $fields ) {
    $fields['billing']['fields']['billing_codice_destinatario'] = array(
        'label'       => __('Recipient Code', 'woocommerce'),
        'description' => __('', 'woocommerce')
    );
    return $fields;
}

######################################################################################
// Add the custom field my account page Account details "billing_cod_fisc"

add_action( 'woocommerce_edit_account_form', 'add_billing_cod_fisc_to_edit_account_form' );
function add_billing_cod_fisc_to_edit_account_form() {
    $user = wp_get_current_user();
    ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_cod_fisc"><?php _e( 'Fiscal Code', 'woocommerce' ); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_cod_fisc" id="billing_cod_fisc" value="<?php echo esc_attr( $user->billing_cod_fisc ); ?>" />
    </p>
    <?php
}

// Save the custom field 'billing_cod_fisc' 
add_action( 'woocommerce_save_account_details', 'save_billing_cod_fisc_account_details', 12, 1 );
function save_billing_cod_fisc_account_details( $user_id ) {
    // For Favorite color
    if( isset( $_POST['billing_cod_fisc'] ) )
        update_user_meta( $user_id, 'billing_cod_fisc', sanitize_text_field( $_POST['billing_cod_fisc'] ) );

    // For Billing email
    /*
    if( isset( $_POST['account_email'] ) )
        update_user_meta( $user_id, 'billing_email', sanitize_text_field( $_POST['account_email'] ) );
        */
}

#######################################################################################
// Add the custom field my account page Account details "billing_partita_iva"

add_action( 'woocommerce_edit_account_form', 'add_billing_partita_iva_to_edit_account_form' );
function add_billing_partita_iva_to_edit_account_form() {
    $user = wp_get_current_user();
    ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_partita_iva"><?php _e( 'Vat Number', 'woocommerce' ); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_partita_iva" id="billing_partita_iva" value="<?php echo esc_attr( $user->billing_partita_iva ); ?>" />
    </p>
    <?php
}

// Save the custom field 'billing_partita_iva' 
add_action( 'woocommerce_save_account_details', 'save_billing_partita_iva_account_details', 12, 1 );
function save_billing_partita_iva_account_details( $user_id ) {
    // For Favorite color
    if( isset( $_POST['billing_partita_iva'] ) )
        update_user_meta( $user_id, 'billing_partita_iva', sanitize_text_field( $_POST['billing_partita_iva'] ) );
}



#######################################################################################
// Add the custom field my account page Account details "billing_pec_email"

add_action( 'woocommerce_edit_account_form', 'add_billing_pec_email_to_edit_account_form' );
function add_billing_pec_email_to_edit_account_form() {
    $user = wp_get_current_user();
    ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_pec_email"><?php _e( 'Email Pec', 'woocommerce' ); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_pec_email" id="billing_pec_email" value="<?php echo esc_attr( $user->billing_pec_email ); ?>" />
    </p>
    <?php
}

// Save the custom field 'billing_pec_email' 
add_action( 'woocommerce_save_account_details', 'save_billing_pec_email_account_details', 12, 1 );
function save_billing_pec_email_account_details( $user_id ) {
    // For Favorite color
    if( isset( $_POST['billing_pec_email'] ) )
        update_user_meta( $user_id, 'billing_pec_email', sanitize_text_field( $_POST['billing_pec_email'] ) );
}

####################################

#######################################################################################
// Add the custom field my account page Account details "billing_codice_destinatario"

add_action( 'woocommerce_edit_account_form', 'add_billing_codice_destinatario_to_edit_account_form' );
function add_billing_codice_destinatario_to_edit_account_form() {
    $user = wp_get_current_user();
    ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="billing_codice_destinatario"><?php _e( 'Recipient Code', 'woocommerce' ); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_codice_destinatario" id="billing_codice_destinatario" value="<?php echo esc_attr( $user->billing_codice_destinatario ); ?>" />
    </p>
    <?php
}

// Save the custom field 'billing_codice_destinatario' 
add_action( 'woocommerce_save_account_details', 'save_billing_codice_destinatario_account_details', 12, 1 );
function save_billing_codice_destinatario_account_details( $user_id ) {
    // For Favorite color
    if( isset( $_POST['billing_codice_destinatario'] ) )
        update_user_meta( $user_id, 'billing_codice_destinatario', sanitize_text_field( $_POST['billing_codice_destinatario'] ) );
}

####################################

function billing_ricevuta_wc_custom_checkout_field() 
{

        echo '<div id="billing_ricevuta_wc_custom_checkout_field">';
    
        woocommerce_form_field(
            'woorichiestaricevuta', array(
            'type'      => 'checkbox',
            'class'     => array('input-checkbox'),
            'label'     => __('tax receipt no invoice', 'woo-fattureincloud'),
            ), WC()->checkout->get_value('woorichiestaricevuta') 
        );
        echo '</div>';
    
}

function custom_checkout_field_update_order_meta( $order_id ) 
{ 
    if (! empty($_POST['woorichiestaricevuta']) ) { 
        update_post_meta($order_id, 'woorichiestaricevuta', sanitize_text_field($_POST['woorichiestaricevuta']));
    }
}


####################################

########################################################################

/*
* Adds custom fields to user profile.
*
*/
########################################################################


function wfic_filter_add_customer_meta_fields( $admin_fields ) {
   
    $admin_fields['billing']['fields']['billing_codice_destinatario'] = array(
        'label' => __('Codice Destinatario', 'woo-fattureincloud'),
        'description' => '',
    );

    $admin_fields['billing']['fields']['billing_pec_email'] = array(
        'label' => __('Email PEC', 'woo-fattureincloud'),
        'description' => '',
    );


    $admin_fields['billing']['fields']['billing_partita_iva'] = array(
        'label' => __('Vat Number', 'woo-fattureincloud'),
        'description' => '',
        
    );

    $admin_fields['billing']['fields']['billing_cod_fisc'] = array(
        'label' => __('Fiscal Code', 'woo-fattureincloud'),
        'description' => '',
    );




    return $admin_fields;
}



#############################################

##################################################
/*
add thank you page field after order
*/

function woo_fic_displayfield_typ($order_id)
{ 
   
    $billing_cod_fisc = get_post_meta($order_id, '_billing_cod_fisc', true);

    if (!empty($billing_cod_fisc) ) { 
        echo '<div class=""woocommerce-order-overview woocommerce-thankyou-order-details order_details"><strong>' . __("Fiscal Code", "woo-fattureincloud") . '</strong> <span class="text"> :' . $billing_cod_fisc . '</span></div>';
    }

    $billing_piva = get_post_meta($order_id, '_billing_partita_iva', true);

    if (!empty($billing_piva) )
        echo '<div><strong>' . __("Vat Number", "woo-fattureincloud") . '</strong> <span class="text"> : ' . $billing_piva . '</span></div>';

    $billing_pec = get_post_meta($order_id, '_billing_pec_email', true);

    if (!empty($billing_pec) )
        echo '<div><strong>' . __( "PEC", "woo-fattureincloud" ) . '</strong> <span class="text"> : ' . $billing_pec . '</span></div>';

    $billing_cod_dest = get_post_meta($order_id, '_billing_codice_destinatario', true);

    if (!empty($billing_cod_dest) )
        echo '<div><strong>' . __("Codice Destinatario", "woo-fattureincloud") . '</strong> <span class="text"> : ' . $billing_cod_dest . '</span></div>';


}