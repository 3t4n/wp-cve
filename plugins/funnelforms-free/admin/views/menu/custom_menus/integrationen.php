<div class="af2_integrations_wrapper af2_card_table">
    <?php foreach($af2_custom_contents as $af2_custom_content) { ?>
        <?php if($af2_custom_content['active'] == -1) { ?>
            <div class="af2_card invisible">
                <div class="af2_card_block">
                </div>
            </div>
        <?php } else { 
                $intCont = '';
                if($af2_custom_content['active'] == 0 ){ $intCont = 'inactive'; }
                $intMode = '';
                if($af2_custom_content['active'] == 0){  $intMode = __('In development', 'funnelforms-free');   }

            ?>
            <div class="af2_card <?php _e($intCont); ?>">
                <div class="af2_card_block">
                    <h4><?php _e($af2_custom_content['name']); ?></h4>
                    <p><?php _e($af2_custom_content['type']); ?> <?php (_e($intMode)); ?></p>
                </div>
            </div>

        <?php }; ?>
    <?php }; ?>
</div>