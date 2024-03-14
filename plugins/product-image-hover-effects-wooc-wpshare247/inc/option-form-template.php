<div id="wle-libs-admin" class="wrap wle-options">
    <div id="poststuff" class="basic-admin wle-options-area">
        <div class="postbox-container">
            <div class="meta-box-sortables ui-sortable">
                <div class="postbox remove-border">
                    <div id="welcome-panel_bk">
                        <div class="welcome-panel-content">
                            <h2 class="hndle ui-sortable-handle fsz-tt"><?php _e("Product Image Hover Effects WOOC - WPSHARE247", WS247_PIEW_TEXTDOMAIN); ?></h2>
                            <div class="content">
                                <div class="inside">
                                	<?php $lang_code = get_locale(); ?>
                                    <form method="post" action="options.php" class="form-theme-options">
                                        <?php settings_fields( Ws247_piew::FIELDS_GROUP ); ?>
                                        <?php do_settings_sections( Ws247_piew::FIELDS_GROUP ); ?>
                                        <div class="in-form">
                                        	<?php 
											require_once WS247_PIEW_PLUGIN_INC_DIR . '/tabs/tab_pro_general.php'; 
											?>
                                            <?php submit_button(); ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>