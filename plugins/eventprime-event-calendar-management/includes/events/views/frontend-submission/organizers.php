<?php
/**
 * View: Frontend Event Submission
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/frontend-submission/organizers.php
 *
 */
?>
<?php $selected_organizer = isset($args->event) && !empty($args->event->em_organizer) ? maybe_unserialize($args->event->em_organizer): array();?>

<?php if(isset($args->fes_event_organizer) && !empty($args->fes_event_organizer)):?>
    <?php 
    $organizers_text = ep_global_settings_button_title('Organizers');
    $organizer_text = ep_global_settings_button_title('Organizer');?>
    <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1">
        <div class="ep-fes-section-title ep-fs-5 ep-fw-bold ep-mb-3">
            <?php echo esc_html( $organizers_text );?>
            <?php if(isset($args->fes_event_organizer_req) && !empty($args->fes_event_organizer_req)):?>
                <span class="required">*</span>
            <?php endif;?>
        </div>
        
        <div class="ep-form-row ep-form-group ep-mb-3">
            <div class="ep-box-col-12">
                <select name="em_organizer[]" id="ep_organizer" class="ep-form-input ep-input-select ep-form-control ep-fes-multiselect" multiple>
                    <?php
                    if ( ! empty( $args->event_organizers ) ):
                        foreach ($args->event_organizers as $event_organizer):?>
                            <option value="<?php echo esc_attr($event_organizer->id); ?>" <?php if(!empty($selected_organizer) && in_array($event_organizer->id, $selected_organizer)){ echo 'selected';}?>><?php echo esc_attr($event_organizer->name); ?></option><?php
                        endforeach;
                    endif;?>
                </select>
            </div>
        </div>
        <?php if(isset($args->fes_new_event_organizer) && !empty($args->fes_new_event_organizer)):?>
            <div class="ep-form-row ep-box-row ep-mt-2">
                <a href="javascript:void(0);" class="ep-fes-add-new ep-mt-2" id="ep-fes-add-event-organizer" onclick="fes_add_new_organizer_show(this)"><?php echo esc_html__('Add New Event', 'eventprime-event-calendar-management').' '.esc_html( $organizer_text );?></a>
                <input type="hidden" name="new_organizer" id="ep_new_organizer" value="0"/>
            </div>   
            <div class="ep-form-row ep-box-row ep-mt-2" id="ep-fes-add-event-organizer-child" style="display:none;">
                <a href="javascript:void(0);" onclick="fes_add_new_organizer_hide(this)"><?php esc_html_e('Hide Details', 'eventprime-event-calendar-management');?></a>
                <div class="ep-form-row ep-form-group ep-mb-3">
                    <label for="ep_new_organizer_name" class="ep-form-label">
                        <?php esc_html_e( 'Name', 'eventprime-event-calendar-management' );?>
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="new_organizer_name" id="ep_new_organizer_name" class="ep-form-input ep-input-text ep-form-control" value="" />
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3 ep-organizer-admin-phone">
                    <label for="em_organizer_phones" class="ep-form-label">
                        <?php esc_html_e( 'Phone', 'eventprime-event-calendar-management' ); ?>
                    </label>
                    <div class="ep-organizers-phone">
                        <div class="ep-input-btn-wrap ep-d-flex ep-org-phone ep-org-data-field">
                            <input type="text" class="ep-org-data-input ep-form-input ep-input-text ep-form-control" name="em_organizer_phones[]" placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>">
                            <button type="button" class="ep-org-add-more ep-btn ep-btn-outline-primary ep-px-4 ep-ml-2" data-input="phone" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>" >
                                +
                            </button>
                        </div>
                    </div>
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3 ep-organizer-admin-email">
                    <label for="em_organizer_emails">
                        <?php esc_html_e( 'Email', 'eventprime-event-calendar-management' ); ?>
                    </label>
                    <div class="ep-organizers-email">
                        <div class="ep-input-btn-wrap ep-d-flex ep-org-email ep-org-data-field">
                            <input type="email" class="ep-org-data-input ep-form-input ep-input-text ep-form-control" name="em_organizer_emails[]" placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>">
                            <button type="button" class="ep-org-add-more ep-btn ep-btn-outline-primary ep-px-4 ep-ml-2" data-input="email" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>">
                                +
                            </button>
                    </div>
                    </div>
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3 ep-organizer-admin-website">
                    <label for="em_organizer_websites">
                        <?php esc_html_e( 'Website', 'eventprime-event-calendar-management' ); ?>
                    </label>
                    <div class="ep-organizers-website">
                        <div class="ep-input-btn-wrap ep-d-flex ep-org-website ep-org-data-field">
                            <input type="text" class="ep-org-data-input ep-form-input ep-input-text ep-form-control" name="em_organizer_websites[]" placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>">
                            <button type="button" class="ep-org-add-more ep-btn ep-btn-outline-primary ep-px-4 ep-ml-2" data-input="website" title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>">
                                +
                            </button>
                   </div>
                    </div>
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                    <label for="ep_organizer_image" class="ep-form-label">
                        <?php echo esc_html( $organizer_text ) . ' '. esc_html__('Image', 'eventprime-event-calendar-management');?>
                        
                    </label>
                    <input type="file" name="org_attachment" id="ep-org-featured-file" onchange="upload_file_media(this)" accept="image/png, image/jpeg">
                    <input type="hidden" name="org_attachment_id" id="org_attachment_id" class="ep-hidden-attachment-id">
                        
                </div>
                <div class="ep-form-row ep-form-group ep-mb-3">
                    <label for="ep_new_event_organizer_description" class="ep-form-label">
                        <?php esc_html_e( 'Description', 'eventprime-event-calendar-management' );?>
                    </label>
                    <?php
                    $content = '';
                    $settings = array( 
                        'editor_height' => 100,
                        'textarea_rows' => 20
                    );
                    wp_editor( $content, 'new_event_organizer_description', $settings );
                    ?>
                </div>
                <?php $social_links = ep_social_sharing_fields();
                foreach( $social_links as $key => $links) { 
                    $sl = ( ! empty( $em_social_links[$key] ) ? $em_social_links[$key] : '' );?>
                    <div class="ep-form-row ep-form-group ep-mb-3 ep-type-admin-social">
                        <label class="ep-form-label">
                            <label><?php echo $links; ?></label>
                        </label>
                        <input type="text" class="ep-form-control ep-org-data-input" value="<?php echo esc_attr($sl);?>" name="em_social_links[<?php echo $key;?>]" placeholder="<?php echo $links; ?>" >
                        
                    </div><?php
                }?>
            </div>
        <?php endif;?>
    </div>
<?php endif;?>