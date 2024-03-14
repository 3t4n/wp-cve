<?php
$menuD = '';
if(get_option('af2_dark_mode') == 1){
    $menuD = 'af2_darkmode';
} ?>
<div class="af2_menu_wrapper af2_wrapper <?php _e($menuD); ?>">
    <div class="af2_wrapper_darkmode_support"></div>
    <?php if( $menu_type == 'builder' ) { ?>
        <?php include FNSF_AF2_MENU_TYPE_BUILDER_VIEW; ?>
    <?php } else { ?>
        
    <div class="af2_menu_header af2_bg_primary">
        <div class="af2_menu_header_image_wrapper">
            <img class="af2_menu_header_image" src="<?php  _e(plugins_url('/res/images/logo-white.png', AF2F_PLUGIN)); ?>">
        </div>
    </div>
    <div class="af2_menu_content">
        <?php include FNSF_AF2_MENU_HEADLINE_SNIPPET; ?>
        
        <!--  Getting Table Content  -->
        <?php if( $menu_type == 'table' ) { ?>
            <?php include FNSF_AF2_MENU_TYPE_TABLE_VIEW; ?>
        <?php }; ?>

        <!--  Getting Custom Content  -->
        <?php if( $menu_type == 'custom' ) { ?>
            <?php include FNSF_AF2_MENU_TYPE_CUSTOM_VIEW; ?>
        <?php }; ?>
    </div>

    <?php }; ?>
</div>
<?php if(isset($menu_action_button_add_post) || isset($menu_action_button_copy_posts) || isset($menu_action_button_delete_posts)) include FNSF_AF2_MENU_ACTION_BUTTONS_SNIPPET; ?>
<?php include FNSF_AF2_MENU_HEROIC_BUTTON; ?>