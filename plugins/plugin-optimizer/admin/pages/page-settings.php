<?php

$should_alphabetize = get_option("po_should_alphabetize_menu");

$checked = $should_alphabetize ? ' checked="checked"' : '';

?>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header("Settings", "settings"); ?>
    
    <div class="sos-content">
        <div id="settings_general" class="">
                    
            <div class="row align-items-center">
            
                <div class="col-2">
                    <span>Recreate the menu</span>
                </div>
                
                <div class="col-3">
                    <a href="<?php echo admin_url( 'admin.php?page=plugin_optimizer_settings&po_original_menu=get&redirect_to=' . urlencode( sospo_mu_plugin()->current_full_url ) ) ?>">
                        <button class="po_green_button">Go!</button>
                    </a>
                </div>
                
            </div>
            
            <div class="row align-items-center">
            
                <div class="col-12">
                    For Support please contact us here 👉 <a href="https://pluginoptimizer.com/support/">https://pluginoptimizer.com/support/</a>
                </div>
                
            </div>
            
        </div>
    </div>
    
</div>
