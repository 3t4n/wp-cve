<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$cab_title = get_user_meta($user->ID, 'cab_title', true);
$hmcabw_photograph = get_user_meta($user->ID, 'hmcabw_photograph', true);
$hmcabwSocialSettings   = get_user_meta($user->ID, 'cab_user_socials', true);
$cabsScials             = $this->get_social_network();
//echo '<pre>';
//print_r($hmcabwSocialSettings);
?>
<br>
<h2><?php _e('Cool Author Box', HMCABW_TXT_DOMAIN); ?></h2>
<table class="form-table" role="presentation">
<tbody>
    <tr class="user-title-wrap">
        <th>
            <label for="cab-title"><?php _e('Title', HMCABW_TXT_DOMAIN); ?></label>
        </th>
        <td>
            <input type="text" name="cab_title" id="cab-title" value="<?php esc_attr_e( $cab_title ); ?>" class="regular-text code">
        </td>
    </tr>
    <tr class="user-title-wrap">
        <th>
            <label for="cab-title"><?php _e('Profile Picture Upload', HMCABW_TXT_DOMAIN); ?></label>
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
    <tr class="user-title-wrap">
        <th>
            <label for="cab-title"><?php _e('Social Link', HMCABW_TXT_DOMAIN); ?></label>
        </th>
        <td>
            <table width="100%" border="0" cellpadding=0 cellspacing=0>
            <?php
            foreach ( $cabsScials as $hmcabwSocialMedia ) {

                $hmcabwSocialMediaUpper = ucfirst( $hmcabwSocialMedia );
                $hmcabwEnabled          = ( isset( $hmcabwSocialSettings[$hmcabwSocialMedia.'_enable'] ) && $hmcabwSocialSettings[$hmcabwSocialMedia.'_enable'] == 1 ) ? 1 : 0;
                $hmcabwLink             = isset( $hmcabwSocialSettings[$hmcabwSocialMedia.'_link'] ) ? $hmcabwSocialSettings[$hmcabwSocialMedia.'_link'] : '';
                ?>
                <tr>
                    <th style="vertical-align: middle; text-align:left; width:120px; padding: 0 10px;">
                        <label for="hmcabw_<?php esc_attr_e( $hmcabwSocialMedia ); ?>_enable"><?php printf( esc_html('%s', $hmcabwSocialMediaUpper, HMCABW_TXT_DOMAIN), $hmcabwSocialMediaUpper ); ?></label>
                    </th>
                    <td style="width:40px; padding: 0 10px;">
                        <input type="checkbox" name="hmcabw_user_<?php esc_attr_e( $hmcabwSocialMedia ); ?>_enable" id="hmcabw_<?php esc_attr_e( $hmcabwSocialMedia ); ?>_enable" value="1" <?php echo $hmcabwEnabled ? 'checked' : ''; ?>>
                    </td>
                    <th style="vertical-align: middle; text-align:right; width:60px;  padding: 0 10px;">
                        <label><?php _e('URL', HMCABW_TXT_DOMAIN); ?></label>
                    </th>
                    <td style="padding: 2px 10px;">
                        <input type="text" name="hmcabw_user_<?php esc_attr_e( $hmcabwSocialMedia ); ?>_link" class="regular-text" value="<?php echo esc_url( $hmcabwLink ); ?>">
                    </td>
                </tr>
                <?php
            }
            ?>
            </table>
        </td>
    </tr>
</tbody>
</table>