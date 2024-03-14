<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( isset( $_POST['updatePostLayout'] ) ) {
    if ( ! isset( $_POST['cab_post_layput_nonce_field'] ) 
        || ! wp_verify_nonce( $_POST['cab_post_layput_nonce_field'], 'cab_post_layput_action' ) ) {
        print 'Sorry, your nonce did not verify.';
        exit;
    } else {
        $cab_post_layout    = isset( $_POST['cab_post_layout'] ) ? sanitize_text_field( $_POST['cab_post_layout'] ) : 'classic';
        $hmcabNotiMessage   = update_option( 'cab_post_layout', $cab_post_layout );
    }
}

$cab_post_layout = get_option('cab_post_layout') ? get_option('cab_post_layout') : 'classic';
?>
<div id="wph-wrap-all" class="wrap hmcab-settings-page">

    <div class="settings-banner">
        <h2><i class="fa fa-user" aria-hidden="true"></i>&nbsp;<?php _e('Select Author Box', HMCABW_TXT_DOMAIN); ?></h2>
    </div>

    <?php 
    if ( $hmcabNotiMessage ) {
        $this->hmcab_display_notification('success', 'Your information updated successfully.');
        echo '<br>';
    } 
    ?>

    <div class="hmcab-wrap">

        <div class="hmcab_personal_wrap hmcab_personal_help" style="width: 75%; float: left;">
            
            <div class="tab-social">
                
                <form name="wpre-table" role="form" class="form-horizontal" method="post" action="" id="hmcabw-template-settings-form">
                <?php wp_nonce_field( 'cab_post_layput_action', 'cab_post_layput_nonce_field' ); ?>

                <table class="form-table" id="cab-post-layout-table">
                    <tr>
                        <th scope="row">
                            <input type="radio" name="cab_post_layout" id="cab_post_layout_classic" value="classic" <?php if ( 'classic' === $cab_post_layout ) { echo 'checked'; } ?>>
                        </th>
                        <td>
                            <label for="cab_post_layout_classic" class="cab_post_layout_lbl">
                                <img src="<?php echo esc_url( HMCABW_ASSETS . 'img/post-layout/classic.jpg' ); ?>"  alt="...">
                            </label>
                        </td>
                        <th scope="row">
                            <input type="radio" name="cab_post_layout" id="cab_post_layout_simple" value="simple" <?php if ( 'simple' === $cab_post_layout ) { echo 'checked'; } ?>>
                        </th>
                        <td>
                            <label for="cab_post_layout_simple" class="cab_post_layout_lbl">
                                <img src="<?php echo esc_url( HMCABW_ASSETS . 'img/post-layout/simple.jpg' ); ?>"  alt="...">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <input type="radio" name="cab_post_layout" id="cab_post_layout_left_aligned" value="left-aligned" <?php if ( 'left-aligned' === $cab_post_layout ) { echo 'checked'; } ?>>
                        </th>
                        <td>
                            <label for="cab_post_layout_left_aligned" class="cab_post_layout_lbl">
                                <img src="<?php echo esc_url( HMCABW_ASSETS . 'img/post-layout/left-aligned.jpg' ); ?>"  alt="...">
                            </label>
                        </td>
                        <th scope="row">
                            <input type="radio" name="cab_post_layout" id="cab_post_layout_centered" value="centered" <?php if ( 'centered' === $cab_post_layout ) { echo 'checked'; } ?>>
                        </th>
                        <td>
                            <label for="cab_post_layout_centered" class="cab_post_layout_lbl">
                                <img src="<?php echo esc_url( HMCABW_ASSETS . 'img/post-layout/centered.jpg' ); ?>"  alt="...">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <input type="radio" name="cab_post_layout" id="cab_post_layout_mango" value="mango" <?php if ( 'mango' === $cab_post_layout ) { echo 'checked'; } ?>>
                        </th>
                        <td>
                            <label for="cab_post_layout_mango" class="cab_post_layout_lbl">
                                <img src="<?php echo esc_url( HMCABW_ASSETS . 'img/post-layout/mango.jpg' ); ?>"  alt="...">
                            </label>
                        </td>
                    </tr>
                </table>
                <hr>
                <p class="submit">
                    <button id="updatePostLayout" name="updatePostLayout" class="button button-primary hmcab-button">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;<?php _e('Save Settings', HMCABW_TXT_DOMAIN); ?>
                    </button>
                </p>

                </form>

            </div>

        </div>

        <?php $this->load_admin_sidebar(); ?>

    </div>

</div>