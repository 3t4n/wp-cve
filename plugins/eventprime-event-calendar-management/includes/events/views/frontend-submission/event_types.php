<?php
/**
 * View: Frontend Event Submission
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/frontend-submission/event_types.php
 *
 */
?>
<?php $selected_type = isset($args->event) && !empty($args->event->em_event_type) ? esc_attr($args->event->em_event_type): '';?>
<?php if(isset($args->fes_event_type) && !empty($args->fes_event_type)):?>
    <?php $event_type_text = ep_global_settings_button_title('Event-Type'); ?>
    <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1">
        <div class="ep-fes-section-title ep-fs-5 ep-fw-bold ep-mb-3">
            <?php echo esc_html( $event_type_text );?>
            <?php if(isset($args->fes_event_type_req) && !empty($args->fes_event_type_req)):?>
                <span class="required">*</span>
            <?php endif;?>
        </div>
        
        <div class="ep-form-row ep-form-group ep-mb-3">
            <select name="em_event_type" id="ep_event_type" class="ep-form-input ep-input-select ep-form-control" onchange="fes_event_type_changed(this);">
                <option value=""><?php echo esc_html__('Select', 'eventprime-event-calendar-management') . ' '. esc_html( $event_type_text );?></option>
                <?php if( ! empty( $args->event_types ) ):
                    foreach($args->event_types as $event_type):?>
                        <option value="<?php echo esc_attr($event_type->id);?>" <?php selected($selected_type,$event_type->id);?>><?php echo esc_attr($event_type->name);?></option><?php 
                    endforeach;
                endif;?>
                <?php if(isset($args->fes_new_event_type) && !empty($args->fes_new_event_type)):?>
                    <option value="new_event_type"><?php echo esc_html__( 'Add New', 'eventprime-event-calendar-management' ) . ' ' . esc_html( $event_type_text );?></option>
                <?php endif;?>
            </select>
        </div>

        <?php if(isset($args->fes_new_event_type) && !empty($args->fes_new_event_type)):?>
            <div class="ep-form-row-child" id="ep_add_new_event_types_child" style="display:none;">
                        <div class="ep-form-row ep-form-group ep-mb-3">
                           
                                <label for="ep_new_event_type_name" class="ep-form-label">
                                    <?php esc_html_e('Name', 'eventprime-event-calendar-management'); ?>
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="new_event_type_name" id="ep_new_event_type_name" class="ep-form-input ep-input-text ep-form-control" value="" />
                            
                        </div>
                        <div class="ep-form-row ep-form-group ep-mb-3">
                            <label for="ep_new_event_type_background_color" class="ep-form-label">
                                <?php esc_html_e('Background Color', 'eventprime-event-calendar-management');?>
                                <span class="required">*</span>
                            </label>
                            <input data-jscolor="{}" type="text" name="new_event_type_background_color" id="ep_new_event_type_background_color" class="ep-form-input ep-input-text ep-form-control" value="#FF5599" />
                        </div>
                        <div class="ep-form-row ep-form-group ep-mb-3">
                          
                                <label for="ep_new_event_type_text_color" class="ep-form-label">
                                    <?php esc_html_e('Text Color', 'eventprime-event-calendar-management'); ?>
                                </label>
                                <input data-jscolor="{}" type="text" name="new_event_type_text_color" id="ep_new_event_type_text_color" class="ep-form-input ep-input-text ep-form-control"  value="#43CDFF"/>
                            
                        </div>
                
                <div class="ep-form-row ep-form-group ep-mb-3">
                    
                                <label for="ep_new_event_type_age_group" class="ep-form-label">
                                    <?php esc_html_e('Age Group', 'eventprime-event-calendar-management'); ?>
                                </label>
                                <select name="new_event_type_age_group" id="ep_new_event_type_age_group" onchange="fes_age_group_changed(this)" class="ep-form-input ep-input-select ep-form-control">
                                    <?php foreach ($args->ages_groups as $key => $group): ?>
                                        <option value="<?php echo esc_attr($key); ?>"><?php echo $group; ?></option>
                                    <?php endforeach; ?>
                                </select>
                         
                </div>
                <div class="form-field" id="ep-type-admin-age-group-child" style="display:none;">
                    <div class="ep-age-bar-fields">
                        <input type="text" class="" id="ep-new_event_type_custom_group" name="new_event_type_custom_group" readonly style="border:0; color:#f6931f; font-weight:bold;">

                        <div id="ep-custom-group-range"></div>
                    </div>
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                    <label for="ep_new_event_type_description" class="ep-form-label">
                        <?php esc_html_e('Special Instructions', 'eventprime-event-calendar-management');?>
                    </label>
                    <?php
                    $content = '';
                    $settings = array( 
                        'editor_height' => 100,
                        'textarea_rows' => 20
                    );
                    wp_editor( $content, 'new_event_type_description', $settings );
                    ?>
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                    
                        <label for="ep_new_event_type_text_color" class="ep-form-label">
                            <?php esc_html_e('Image', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <input type="file" name="event_type_featured_img" id="ep-fes-featured-file" onchange="upload_file_media(this)" accept="image/png, image/jpeg"/>
                        <input type="hidden" name="event_type_image_id" id="event_type_image_id" class="ep-hidden-attachment-id"/>
                                        
                    
                </div>
            </div>
        <?php endif;?>
    </div>
<?php endif;?>