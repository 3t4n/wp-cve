<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hmcabTabCls = '';
$hmcabPage = 'Personal';
$hmcabFaIcon = 'fa-solid fa-user-pen';
if ( $tab === 'social' ) {
    $hmcabPage = 'Social';
    $hmcabFaIcon = 'fa-brands fa-facebook';
}
if ( $tab === 'template' ) {
    $hmcabPage = 'Template';
    $hmcabFaIcon = 'fa fa-pencil';
}
if ( $tab === 'styles' ) {
    $hmcabPage = 'Styles';
    $hmcabFaIcon = 'fa fa-paint-brush';
}
?>
<div id="wph-wrap-all" class="wrap hmcab-settings-page">

    <div class="settings-banner">
        <h2><i class="<?php esc_attr_e( $hmcabFaIcon ); ?>" aria-hidden="true"></i>&nbsp;<?php echo esc_html($hmcabPage) . '&nbsp;' . __('Settings', HMCABW_TXT_DOMAIN); ?></h2>
    </div>

    <?php 
    if ( $hmcabNotiMessage ) {
        $this->hmcab_display_notification('success', 'Your information updated successfully.'); 
    } 
    ?>

    <div class="hmcab-wrap">

        <nav class="nav-tab-wrapper">
            <a href="?page=hmcab-settings" class="nav-tab hmcab-tab <?php if ( $tab == '' ) { ?>hmcab-tab-active<?php } ?>">
                <i class="fa-solid fa-user-pen"></i>&nbsp;<?php _e('Personal', HMCABW_TXT_DOMAIN); ?>
            </a>
            <a href="?page=hmcab-settings&tab=social" class="nav-tab hmcab-tab <?php if ( $tab === 'social' ) { ?>hmcab-tab-active<?php } ?>">
                <i class="fa-brands fa-facebook"></i>&nbsp;<?php _e('Social', HMCABW_TXT_DOMAIN); ?>
            </a>
            <a href="?page=hmcab-settings&tab=template" class="nav-tab hmcab-tab <?php if ( $tab === 'template' ) { ?>hmcab-tab-active<?php } ?>">
                <i class="fa fa-pencil" aria-hidden="true">&nbsp;</i><?php _e('Template', HMCABW_TXT_DOMAIN); ?>
            </a>
            <a href="?page=hmcab-settings&tab=styles" class="nav-tab hmcab-tab <?php if ( $tab === 'styles' ) { ?>hmcab-tab-active<?php } ?>">
                <i class="fa fa-paint-brush" aria-hidden="true"></i>&nbsp;<?php _e('Styles', HMCABW_TXT_DOMAIN); ?>
            </a>
        </nav>

        <div class="hmcab_personal_wrap hmcab_personal_help" style="width: 75%; float: left;">
            
            <div class="tab-social">
                <?php 
                switch ( $tab ) {
                    case 'social':
                        include_once 'partial/social.php';
                        break;

                    case 'template':
                        include_once 'partial/template.php';
                        break;

                    case 'styles':
                        include_once 'partial/styles.php';
                        break;

                    default:
                        include_once 'partial/personal.php';
                        break;
                } 
                ?>
            </div>
        </div>

        <?php $this->load_admin_sidebar(); ?>

    </div>

</div>