<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
$wpsf_metabox_details = get_post_meta( $post->ID, '_wpsf_metabox_details', true );
wp_nonce_field( 'wpsf_metabox_nonce', 'wpsf_metabox_nonce_field' );
?>
<div class="wpsf-field-wrap">
    <label><?php esc_html_e( 'Enable', 'wp-subscription-forms' ); ?></label>
    <div class="wpsf-field">
        <input type="checkbox" name="wpsf_metabox_details[enable_popup]" <?php echo (!empty( $wpsf_metabox_details['enable_popup'] )) ? 'checked="checked"' : ''; ?> value="1"/>
    </div>
</div>
<div class="wpsf-field-wrap wpsf-elem-block">
    <label><?php esc_html_e( 'Delay', 'wp-subscription-forms' ); ?></label>
    <div class="">
        <input type="number" name="wpsf_metabox_details[delay]" min="0" value="<?php echo (!empty( $wpsf_metabox_details['delay'] )) ? intval( $wpsf_metabox_details['delay'] ) : ''; ?>"/>
        <p class="description"><?php esc_html_e( 'Please enter the delay time for popup display in seconds. Please leave empty or enter 0 to display with the page load.', 'wp-subscription-forms' ); ?></p>
    </div>
</div>
<div class="wpsf-field-wrap wpsf-elem-block">
    <label><?php esc_html_e( 'Subscription Form', 'wp-subscription-forms' ) ?></label>
    <div class="">
        <select name="wpsf_metabox_details[form_alias]">
            <option value=""><?php esc_html_e( "Choose Form", 'wp-subscription-forms' ); ?></option>
            <?php
            $selected_form_alias = (!empty( $wpsf_metabox_details['form_alias'] )) ? $wpsf_metabox_details['form_alias'] : '';
            global $wpdb;
            $form_table = WPSF_FORM_TABLE;
            $forms = $wpdb->get_results( "select * from $form_table order by form_title asc" );
            if ( !empty( $forms ) ) {
                foreach ( $forms as $form ) {
                    ?>
                    <option value="<?php echo esc_attr( $form->form_alias ); ?>" <?php selected( $selected_form_alias, $form->form_alias ); ?>><?php echo esc_attr( $form->form_title ); ?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>
</div>

