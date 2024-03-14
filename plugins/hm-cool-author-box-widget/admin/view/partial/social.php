<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form name="wpre-table" role="form" class="form-horizontal" method="post" action="">
    <div style="width:100%; height:590px; overflow-y:scroll; overflow-x:hidden;">
        <table class="form-table" id="table-admin-social-id">
            <?php
            foreach ( $hmcabSocialNetworks as $hmcabwSocialMedia ) {

                $hmcabwSocialMediaUpper = ucfirst( $hmcabwSocialMedia );
                $hmcabwEnabled          = ( isset( $hmcabwSocialSettings['hmcabw_'.$hmcabwSocialMedia.'_enable'] ) && $hmcabwSocialSettings['hmcabw_'.$hmcabwSocialMedia.'_enable'] == 1 ) ? 1 : 0;
                $hmcabwLink             = isset( $hmcabwSocialSettings['hmcabw_'.$hmcabwSocialMedia.'_link'] ) ? $hmcabwSocialSettings['hmcabw_'.$hmcabwSocialMedia.'_link'] : '';
                ?>
                <tr>
                    <th style="vertical-align: middle; text-align:right;">
                        <label for="hmcabw_<?php esc_attr_e( $hmcabwSocialMedia ); ?>_enable"><?php printf( esc_html('%s', $hmcabwSocialMediaUpper, HMCABW_TXT_DOMAIN), $hmcabwSocialMediaUpper ); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="hmcabw_<?php esc_attr_e( $hmcabwSocialMedia ); ?>_enable" id="hmcabw_<?php esc_attr_e( $hmcabwSocialMedia ); ?>_enable" value="1" <?php echo $hmcabwEnabled ? 'checked' : ''; ?>>
                    </td>
                    <th style="vertical-align: middle; text-align:right;">
                        <label><?php _e('URL', HMCABW_TXT_DOMAIN); ?></label>
                    </th>
                    <td>
                        <input type="text" name="hmcabw_<?php esc_attr_e( $hmcabwSocialMedia ); ?>_link" class="regular-text" value="<?php echo esc_url( $hmcabwLink ); ?>">
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <hr>
    <p class="submit">
        <button id="updateSocialSettings" name="updateSocialSettings" class="button button-primary hmcab-button">
            <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;<?php _e('Save Settings', HMCABW_TXT_DOMAIN); ?>
        </button>
    </p>
</form>