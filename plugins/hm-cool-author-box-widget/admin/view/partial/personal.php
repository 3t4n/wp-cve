<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//print_r( $cabPersonalSettings );
foreach ( $cabPersonalSettings as $option_name => $option_value ) {
    if ( isset( $cabPersonalSettings[$option_name] ) ) {
        ${"" . $option_name}  = $option_value;
    }
}
?>
<form name="hmcabw_general_settings_form" role="form" class="form-horizontal" method="post" action="" id="hmcabw-general-settings-form">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label><?php _e('Name', HMCABW_TXT_DOMAIN); ?></label>
            </th>
            <td>
                <input type="text" name="hmcabw_author_name" class="regular-text" value="<?php esc_attr_e( $hmcabw_author_name ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Title', HMCABW_TXT_DOMAIN); ?></label>
            </th>
            <td>
                <input type="text" name="hmcabw_author_title" class="regular-text" value="<?php esc_attr_e( $hmcabw_author_title ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Email', HMCABW_TXT_DOMAIN); ?></label>
            </th>
            <td>
                <input name="hmcabw_author_email" type="text" class="regular-text" value="<?php esc_attr_e( $hmcabw_author_email ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Website', HMCABW_TXT_DOMAIN); ?></label>
            </th>
            <td>
                <input name="hmcabw_author_website" type="text" class="regular-text" value="<?php esc_attr_e( $hmcabw_author_website ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Biographical Info', HMCABW_TXT_DOMAIN); ?></label>
            </th>
            <td>
                <div style="width:700px;">
                <?php
                wp_editor( wp_kses_post( $hmcabw_biographical_info ), 'hmcabw_biographical_info', array( 'media_buttons' => false, 'textarea_rows' => '10' ) );
                ?>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Author Image Type', HMCABW_TXT_DOMAIN); ?></label>
            </th>
            <td>
                <input type="radio" name="hmcabw_author_image_selection" id="hmcabw_author_image_selection_gravatar" value="gravatar" <?php if( $hmcabw_author_image_selection != "upload_image") { echo 'checked'; } ?>>
                <label for="hmcabw_author_image_selection_gravatar"><span></span><?php _e('Gravatar', HMCABW_TXT_DOMAIN); ?></label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="hmcabw_author_image_selection" id="hmcabw_author_image_selection_img" value="upload_image" <?php if( $hmcabw_author_image_selection == "upload_image") { echo 'checked'; } ?>>
                <label for="hmcabw_author_image_selection_img"><span></span><?php _e('Upload Image', HMCABW_TXT_DOMAIN); ?></label>
            </td>
        </tr>
        <tr class="hmcabw_photograph" id="hmcabw_photograph_th">
            <th scope="row">
                <label><?php _e('Upload Image', HMCABW_TXT_DOMAIN); ?></label>
            </th>
            <td>
                <input type="hidden" name="hmcabw_photograph" id="hmcabw_photograph" value="<?php esc_attr_e( $hmcabw_photograph ); ?>" class="regular-text" />
                <input type='button' class="button-primary" value="<?php _e('Select Photograph', HMCABW_TXT_DOMAIN); ?>" id="hmcabw-media-manager"/>
                <br><br>
                <?php
                $hmcabwImage = '';
                if ( intval( $hmcabw_photograph ) > 0 ) {
                    $hmcabwImage = wp_get_attachment_image( $hmcabw_photograph, 'thumbnail', false, array( 'id' => 'hmcabw-preview-image' ) );
                }
                ?>
                <div id="hmcabw-preview-image"><?php echo $hmcabwImage; ?></div>
            </td>
        </tr>
    </table>
    <hr>
    <p class="submit">
        <button id="updatePersonalSettings" name="updatePersonalSettings" class="button button-primary hmcab-button">
            <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;<?php _e('Save Settings', HMCABW_TXT_DOMAIN); ?>
        </button>
    </p>
</form>