<?php 
$menuSheet = ''; if($menu_blur_option == true){ $menuSheet = 'af2_blurred'; }
$menuScontent = ''; if($show_sidebar == false){ $menuScontent = 'no_sidebar'; }
?>
<div class="af2_menu_sheet <?php _e($menuSheet); ?>">
    <div class="af2_menu_sheet_content <?php _e($menuScontent); ?>">
        <?php include $menu_custom_template; ?>
    </div>
    
    <?php if($show_sidebar == true) include FNSF_AF2_MENU_SIDEBAR; ?>
</div>

<?php if($menu_blur_option == true) { ?>
    <div class="af2_decide_pro">
        <div class="af2_decide_pro_div">
            <h1><?php _e('This function is only included in Funnelforms Pro!', 'funnelforms-free'); ?></h1>
            <h1 style="display: none;"><?php _e('Choose Pro', 'funnelforms-free'); ?></h1>
            <h5 style="
    text-align: center;
    margin-top: 10px;
    margin-bottom: 50px;
    white-space: break-spaces;
    max-width: 60%;
"><?php _e('Note: The Funnelforms Free and Pro version are two different plugins. You will receive the download link after purchasing the Pro version and then you can upload the plugin to your WordPress website.', 'funnelforms-free') ?></h5>
            <a class="af2_btn_link" target="_blank" href="https://www.funnelforms.io/gopro">
                <div class="af2_btn af2_btn_primary"><?php _e('Upgrade to Pro Version', 'funnelforms-free'); ?></div>
            </a>
        </div>
    </div>
<?php }; ?>