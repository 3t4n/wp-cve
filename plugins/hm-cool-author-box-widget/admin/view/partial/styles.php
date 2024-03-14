<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//print_r($hmcabStylesPost);
foreach ( $hmcabStylesPost as $option_name => $option_value ) {
    if ( isset( $hmcabStylesPost[$option_name] ) ) {
        ${"" . $option_name} = $option_value;
    }
}
?>
<form name="hmcab_styles_post_form" role="form" class="form-horizontal" method="post" action="" id="hmcab-styles-post-form">
    <table class="hmcab-styles-post-settings-table" width="600px">
        <!-- Container -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Container', HMCABW_TXT_DOMAIN );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Border Color', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Border Width', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Background Color', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Border Radius', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
        </tr>
        <!-- Image -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Image', HMCABW_TXT_DOMAIN );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Border Color', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Border Width', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
        </tr>
        <!-- Name -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Name', HMCABW_TXT_DOMAIN );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Font Color', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Size', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
        </tr>
        <!-- Title -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Title', HMCABW_TXT_DOMAIN );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Font Color', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Size', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
        </tr>
        <!-- Description -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Description / Biographical Info', HMCABW_TXT_DOMAIN );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Font Color', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Size', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <input type="number" class="small-text" min="11" max="50" name="cab_post_desc_font_size" id="cab_post_desc_font_size" value="<?php 
esc_attr_e( $cab_post_desc_font_size );
?>">
                <code>px</code>
            </td>
        </tr>
        <!-- Email -->
        <tr>
            <th scope="row" colspan="4">
                <hr><span><?php 
_e( 'Email / Website', HMCABW_TXT_DOMAIN );
?></span><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Font Color', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
            <th scope="row">
                <label><?php 
_e( 'Font Size', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <?php 
?>
                    <span><?php 
echo  '<a href="' . hcabw_fs()->get_upgrade_url() . '">' . __( 'Upgrade to Professional!', HMCABW_TXT_DOMAIN ) . '</a>' ;
?></span>
                    <?php 
?>
            </td>
        </tr>
    </table>
    <hr>
    <p class="submit">
        <button id="updateStylesPost" name="updateStylesPost" class="button button-primary hmcab-button">
            <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;<?php 
_e( 'Save Settings', HMCABW_TXT_DOMAIN );
?>
        </button>
    </p>
</form>