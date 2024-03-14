<div class="element-ready-admin-dashboard-container wrap">
    <div id="element-ready-adpage-tabs" class="element-ready-adpage-tabs">
        <div class="element-ready-nav-wrapper">
            <ul>
                <li class="element-ready-dashboard element-ready-header-footer">
                    <a href="#element-ready-adpage-tabs-1">
                        <i class="dashicons dashicons-admin-home"></i>
                        <h3 class="element-ready-title"><?php echo esc_html__('Header Footer','element-ready-lite'); ?> </h3>
                        <span><?php echo esc_html__('General settings','element-ready-lite'); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <div id="element-ready-adpage-tabs-1" class="element-ready-adpage-tab-content element-ready-dashboard dashboard">
            <form class="element-ready-components-action quomodo-component-data" action="<?php echo admin_url('admin-post.php') ?>" method="post">
                <div class="quomodo-container-wrapper">
                    <div class="quomodo-row-wrapper">
                        <div class="element-ready-component-form-wrapper components">
                            <div class="element-ready-components-topbar">
                                <div class="element-ready-title">
                                    <h3 class="title"><i class="dashicons dashicons-editor-alignleft"></i> <?php echo esc_html__('General Settings','element-ready-lite'); ?> </h3>
                                </div>
                                <div class="element-ready-savechanges">
                                    <button type="submit" class="element-ready-component-submit button element-ready-submit-btn"><i class="dashicons dashicons-yes"></i> <?php echo esc_html__('Save Change','element-ready-lite'); ?></button>
                                </div>
                            </div>
                            <div class="quomodo-row">
                                <?php $components_settings = $this->components(); ?>
                                <?php if( is_array( $components_settings ) ): ?>
                                <?php foreach($components_settings as $item_key => $item): ?>
                                    <?php if($item['type'] =='switch'): ?>
                                        <div class="element-ready-col quomodo-col-xl-3 quomodo-col-lg-4 quomodo-col-md-6">
                                            <div class="quomodo_switch_common element-ready-common <?php echo esc_attr($item['is_pro']?'element-ready-pro element-ready-dash-modal-open-btn':''); ?>">
                                                <div class="quomodo_sm_switch">
                                                    <strong><?php echo esc_html($item['lavel']); ?>
                                                        <?php if( $item['is_pro'] ): ?>
                                                            <span> <?php echo esc_html__( 'Pro', 'element-ready-lite' ); ?> </span>
                                                        <?php endif; ?>    
                                                    </strong>
                                                    <input <?php echo esc_attr( $item['default']==1?'checked':'' ); ?> name="element-ready-hf-options[<?php echo esc_attr($item_key); ?>]" class="quomodo_switch <?php echo esc_attr($item_key); ?>" id="element-ready-components-<?php echo esc_attr($item_key); ?>" type="checkbox">
                                                    <label <?php echo esc_attr( $item['is_pro']?'readonly disable':'' ); ?> for="element-ready-components-<?php echo esc_attr($item_key); ?>"></label>
                                               </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($item['type'] =='select'): ?>
                                        <div class="element-ready-col quomodo-col-xl-3 quomodo-col-lg-4 quomodo-col-md-6">
                                            <div class="element-ready-data">
                                                <strong><?php echo esc_html($item['lavel']); ?>
                                                    <?php if( $item['is_pro'] ): ?>
                                                        <span> <?php echo esc_html__( 'Pro', 'element-ready-lite' ); ?> </span>
                                                    <?php endif; ?>    
                                                </strong>
                                                <div class="element-ready-custom-select er-template-select" style="width:250px;display:block">
                                                    <select name="element-ready-hf-options[<?php echo esc_attr($item_key); ?>]" id="element-ready-components-<?php echo esc_attr($item_key); ?>">
                                                        <?php if(isset($item['options'])): ?>
                                                            <?php foreach($item['options'] as $sel_key => $select_option): ?>
                                                                <option <?php echo esc_attr( $item['default']==$sel_key?'selected':'' ); ?>  value="<?php echo esc_attr($sel_key); ?>"> <?php echo esc_html($select_option); ?><option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <label for="element-ready-components-<?php echo esc_attr($sel_key); ?>"></label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="element_ready_hf_options">
                        <?php echo wp_nonce_field('element-ready-hf-components', '_element_ready_hf_components'); ?>
                    </div>
                </div> <!-- container end -->
            </form>
        </div>
     </div>
</div>
