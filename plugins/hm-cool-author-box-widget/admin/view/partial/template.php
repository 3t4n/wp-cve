<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//print_r( $hmcabwTempSettings );
foreach ( $hmcabwTempSettings as $option_name => $option_value ) {
    if ( isset( $hmcabwTempSettings[$option_name] ) ) {
        ${"" . $option_name} = $option_value;
    }
}
?>
<form name="wpre-table" role="form" class="form-horizontal" method="post" action="" id="hmcabw-template-settings-form">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Template Color', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td colspan="3">
                <div class="hmcabw-template-selector">
                    <?php 
for ( $i = 0 ;  $i < 7 ;  $i++ ) {
    ?>
                        <div class="hmcabw-template-item">
                            <input type="radio" name="hmcabw_select_template" id="<?php 
    printf( 'hmcabw_select_template_%d', $i );
    ?>" value="<?php 
    printf( 'temp_%d', $i );
    ?>" <?php 
    if ( $hmcabw_select_template === "temp_" . $i ) {
        echo  'checked' ;
    }
    ?>>
                            <label for="<?php 
    printf( 'hmcabw_select_template_%d', $i );
    ?>" class="hmcabw-template-<?php 
    esc_attr_e( $i );
    ?>"></label>
                        </div>
                        <?php 
}
?>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="hmcabw_display_title"><?php 
_e( 'Display Title', 'hm-cool-author-box-widget' );
?></label>
            </th>
            <td colspan="3">
                <input type="checkbox" id="hmcabw_display_title" name="hmcabw_display_title" value="1" <?php 
if ( $hmcabw_display_title ) {
    echo  'checked' ;
}
?>>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="hmcabw_display_email"><?php 
_e( 'Display Email', 'hm-cool-author-box-widget' );
?></label>
            </th>
            <td colspan="3">
                <input type="checkbox" name="hmcabw_display_email" id="hmcabw_display_email" value="1" <?php 
if ( $hmcabw_display_email == "1" ) {
    echo  'checked' ;
}
?>>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="hmcabw_display_web"><?php 
_e( 'Display Website', 'hm-cool-author-box-widget' );
?></label>
            </th>
            <td colspan="3">
                <input type="checkbox" name="hmcabw_display_web" id="hmcabw_display_web" value="1" <?php 
if ( $hmcabw_display_web == "1" ) {
    echo  'checked' ;
}
?>>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Photo / Icon Shape', 'hm-cool-author-box-widget' );
?></label>
            </th>
            <td colspan="3">
                <input type="radio" name="hmcabw_icon_shape" id="hmcabw_icon_shape_square" value="square" <?php 
if ( $hmcabw_icon_shape == 'square' ) {
    echo  'checked' ;
}
?>>
                <label for="hmcabw_icon_shape_square"><span></span><?php 
_e( 'Square', 'hm-cool-author-box-widget' );
?></label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="hmcabw_icon_shape" id="hmcabw_icon_shape_rounded" value="rounded" <?php 
if ( $hmcabw_icon_shape == 'rounded' ) {
    echo  'checked' ;
}
?>>
                <label for="hmcabw_icon_shape_rounded"><span></span><?php 
_e( 'Rounded', 'hm-cool-author-box-widget' );
?></label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="hmcabw_icon_shape" id="hmcabw_icon_shape_circle" value="circle" <?php 
if ( $hmcabw_icon_shape == 'circle' ) {
    echo  'checked' ;
}
?>>
                <label for="hmcabw_icon_shape_circle"><span></span><?php 
_e( 'Circle', 'hm-cool-author-box-widget' );
?></label>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Photo Width', 'hm-cool-author-box-widget' );
?></label>
            </th>
            <td colspan="3">
                <input type="number" name="hmcabw_photo_width" min="80" max="160" step="1" value="<?php 
esc_attr_e( $hmcabw_photo_width );
?>" style="width:100px;">
                <code><i><?php 
_e( 'px', 'hm-cool-author-box-widget' );
?></i></code>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Photo Animation', HMCABW_TXT_DOMAIN );
?>:</label>
            </th>
            <td colspan="3">
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
            <th colspan="2" style="color: #5E24DD;">
                <hr><?php 
_e( 'Post Author Box', 'hm-cool-author-box-widget' );
?><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label for="hmcabw_display_in_post_page_post"><?php 
_e( 'Hide Author Box', 'hm-cool-author-box-widget' );
?></label>
            </th>
            <td colspan="3">
                <input type="checkbox" name="hmcabw_display_in_post_page" id="hmcabw_display_in_post_page_post" <?php 
if ( $hmcabw_display_in_post_page ) {
    echo  'checked' ;
}
?>>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Author Box Position', 'hm-cool-author-box-widget' );
?></label>
            </th>
            <td colspan="3">
                <input type="radio" name="hmcabw_display_selection" id="hmcabw_display_selection_top" value="top" <?php 
if ( $hmcabw_display_selection == "top" ) {
    echo  'checked' ;
}
?>>
                <label for="hmcabw_display_selection_top"><span></span><?php 
_e( 'Top of Content', 'hm-cool-author-box-widget' );
?></label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="hmcabw_display_selection" id="hmcabw_display_selection_bottom" value="bottom" <?php 
if ( $hmcabw_display_selection == "bottom" ) {
    echo  'checked' ;
}
?>>
                <label for="hmcabw_display_selection_bottom"><span></span><?php 
_e( 'Bottom of Content', 'hm-cool-author-box-widget' );
?></label>
            </td>
        </tr>
        <tr>
            <th colspan="2" style="color: #5E24DD;">
                <hr><?php 
_e( 'Widget Author Box', 'hm-cool-author-box-widget' );
?><hr>
            </th>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Content Alignment', 'hm-cool-author-box-widget' );
?></label>
            </th>
            <td colspan="3">
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
                <label for="cab_hide_banner"><?php 
_e( 'Hide Profile Banner', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <input type="checkbox" name="cab_hide_banner" class="cab_hide_banner" id="cab_hide_banner" value="1" <?php 
echo  ( $cab_hide_banner ? 'checked' : '' ) ;
?> >
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php 
_e( 'Profile Banner Upload', HMCABW_TXT_DOMAIN );
?></label>
            </th>
            <td>
                <input type="hidden" name="cab_profile_banner" id="hmcabw_photograph" value="<?php 
esc_attr_e( $cab_profile_banner );
?>" class="regular-text" />
                <input type='button' class="button-primary hmcabw-media-manager" value="<?php 
esc_attr_e( 'Select a banner', HMCABW_TXT_DOMAIN );
?>" id="hmcabw-media-manager" data-image-type="full" />
                <br><br>
                <?php 
$cab_profile_banner_img = '';
if ( intval( $cab_profile_banner ) > 0 ) {
    $cab_profile_banner_img = wp_get_attachment_image(
        $cab_profile_banner,
        'full',
        false,
        array(
        'id' => 'wpsd-form-banner-preview-image',
    )
    );
}
?>
                <div id="hmcabw-preview-image" class="cab-profile-banner-preview-image">
                    <?php 
echo  $cab_profile_banner_img ;
?>
                </div>
            </td>
        </tr>
    </table>
    <hr>
    <p class="submit">
        <button id="updateTempSettings" name="updateTempSettings" class="button button-primary hmcab-button">
            <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;<?php 
_e( 'Save Settings', HMCABW_TXT_DOMAIN );
?>
        </button>
    </p>
</form>