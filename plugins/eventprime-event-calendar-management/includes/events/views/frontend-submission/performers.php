<?php
/**
 * View: Frontend Event Submission
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/frontend-submission/performers.php
 *
 */
?>
<?php $selected_performers = isset($args->event) && !empty($args->event->em_performer) ? maybe_unserialize($args->event->em_performer): array();?>
<?php if(isset($args->fes_event_performer) && !empty($args->fes_event_performer)):?>
    <?php 
    $performers_text = ep_global_settings_button_title('Performers');
    $performer_text = ep_global_settings_button_title('Performer');?>
    <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1">
        <div class="ep-fes-section-title ep-fs-5 ep-fw-bold ep-mb-3">
            <?php echo esc_html( $performers_text );?>
            <?php if(isset($args->fes_event_performer_req) && !empty($args->fes_event_performer_req)):?>
                <span class="required">*</span>
            <?php endif;?>
        </div>
        
        <div class="ep-form-row ep-box-row">
            <div class="ep-box-col-12">
                <select name="em_performer[]" id="ep_performer" class="ep-form-input ep-input-select ep-form-control ep-fes-multiselect" multiple>
                    <?php if( ! empty( $args->event_performers ) ):
                        foreach($args->event_performers as $event_performer):?>
                            <option value="<?php echo esc_attr($event_performer->ID);?>" <?php if(!empty($selected_performers) && in_array($event_performer->ID, $selected_performers)){ echo 'selected';}?>><?php echo esc_attr($event_performer->post_title);?></option><?php 
                        endforeach;
                    endif;?>
                </select>
            </div>
        </div>
        <?php if(isset($args->fes_new_event_performer) && !empty($args->fes_new_event_performer)):?>
            <div class="ep-form-row ep-box-row">
                <a href="javascript:void(0);" class="ep-fes-add-new ep-mt-2" id="ep-fes-add-event-performer" onclick="fes_add_new_performer_show(this)"><?php echo esc_html__('Add New Event', 'eventprime-event-calendar-management') . ' '.esc_html( $performer_text );?></a>
                <input type="hidden" name="new_performer" id="ep_new_performer" value="0"/>
            </div>
            <div class="ep-form-row ep-box-row" id="ep-fes-add-event-perfomer-child" style="display:none;">
                <a href="javascript:void(0);" onclick="fes_add_new_performer_hide(this)" class="ep-mt-2 ep-mb-3"><?php esc_html_e('Hide Details', 'eventprime-event-calendar-management');?></a>
                <div class="ep-form-row ep-form-group ep-mb-3">
                    <label for="ep_new_performer_name" class="ep-form-label">
                        <?php esc_html_e('Name', 'eventprime-event-calendar-management');?>
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="new_performer_name" id="ep_new_performer_name" class="ep-form-input ep-input-text ep-form-control" value="" />
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                    <label for="ep_new_performer_description" class="ep-form-label">
                        <?php esc_html_e('Description', 'eventprime-event-calendar-management');?>
                    </label>
                    <?php
                    $content = '';
                    $settings = array( 
                        'editor_height' => 100,
                        'textarea_rows' => 20
                    );
                    wp_editor( $content, 'new_performer_description', $settings );
                    ?>
                </div>
                <div class="ep-form-row ep-form-group ep-mb-2">
                    <label for="ep_performer_type" class="ep-form-label ep-mb-3">
                        <?php echo esc_html( $performer_text ).' '.esc_html__('Type', 'eventprime-event-calendar-management');?>
                        <span class="required">*</span>
                    </label>
              
                     <div class="ep-form-check ep-form-check-inline ep-mb-3 ep-mt-3">
                         <input type="radio" name="new_performer_type" value="person" checked="checked" class="ep-form-check-input">
                        <label class="ep-form-check-label" for="<?php esc_html_e('Person', 'eventprime-event-calendar-management');?>">
                          <?php esc_html_e('Person', 'eventprime-event-calendar-management');?>                       
                        </label>
                     </div>
                     
                        <div class="ep-form-check ep-form-check-inline ep-mb-2">
                            <input type="radio" name="new_performer_type" value="group" class="ep-form-check-input">
                            <label class="ep-form-check-label" for="<?php esc_html_e('Group', 'eventprime-event-calendar-management');?>">
                            <?php esc_html_e('Group', 'eventprime-event-calendar-management');?>                     
                           </label>
                         </div>
                </div>
                
                <div class="ep-form-row ep-form-group ep-mb-3">
                    <label for="ep_new_performer_role" class="ep-form-label">
                        <?php esc_html_e('Role', 'eventprime-event-calendar-management');?>
                    </label>
                    <input type="text" name="new_performer_role" id="ep_new_performer_role" class="ep-form-input ep-input-text ep-form-control" value="" />
                </div>
                
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label class=" ep-form-label">
                            <?php esc_html_e('Phone', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <div class="ep-performers-phone">
                         <div class="ep-input-btn-wrap ep-d-flex">
                            <input type="tel" class="ep-per-data-input ep-form-input ep-input-text ep-form-control" name="em_performer_phones[]" placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>">
                            <button type="button" class="ep-per-add-more ep-btn ep-btn-outline-primary ep-px-4 ep-ml-2" 
                                data-input="phone" 
                                title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                                data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                                data-placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>" >
                                +
                             </button>
                      </div>
                    </div>
                    
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label class="ep-meta-box-phone ep-form-label">
                            <?php esc_html_e('Email', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <div class="ep-performers-email">
                        <div class="ep-input-btn-wrap ep-d-flex">
                            <input type="email" class="ep-per-data-input ep-form-input ep-input-text ep-form-control" name="em_performer_emails[]" placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>">
                            <button type="button" class="ep-per-add-more ep-btn ep-btn-outline-primary ep-px-4 ep-ml-2" 
                                data-input="email" 
                                title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                                data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                                data-placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>" >
                                +
                            </button>
                        </div>
                     </div>
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                        <label class="ep-meta-box-phone ep-form-label">
                            <?php esc_html_e('Website', 'eventprime-event-calendar-management'); ?>
                        </label>
                        <div class="ep-performers-website">
                        <div class="ep-input-btn-wrap ep-d-flex">
                            <input type="url" class="ep-per-data-input ep-form-input ep-input-text ep-form-control" name="em_performer_websites[]" placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>">
                            <button type="button" class="ep-per-add-more ep-btn ep-btn-outline-primary ep-px-4 ep-ml-2" 
                                data-input="website" 
                                title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                                data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                                data-placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>" >
                                +
                            </button>
                        </div>
                </div>
                </div>
                <?php $social_links = ep_social_sharing_fields();
                foreach( $social_links as $key => $links) { ?>
                    <div class="ep-form-row ep-form-group ep-mb-3">
                                <label class="ep-meta-box-title">
                                    <?php echo $links; ?>
                               </label>
                           
                                    <input class="ep-form-control"  type="text" name="em_social_links[<?php echo $key; ?>]" 
                                        placeholder="<?php echo esc_attr($links); ?>"
                                        value="">
                                
                            
                    </div>
                    <?php
                }?>
                 <div class="ep-form-row ep-form-group ep-mb-3">
                    <label for="ep_performer_image" class="ep-form-label">
                        <?php echo esc_html( $performer_text ) . ' '. esc_html__('Image', 'eventprime-event-calendar-management');?>
                        
                    </label>
                    <input type="file" name="performer_attachment" id="ep-performer-featured-file" onchange="upload_file_media(this)" accept="image/png, image/jpeg">
                    <input type="hidden" name="performer_attachment_id" id="performer_attachment_id" class="ep-hidden-attachment-id">
                        
                </div>
            </div>
        <?php endif;?>
    </div>
<?php endif;?>