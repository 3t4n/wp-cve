<?php
/**
 * View: Frontend Event Submission
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/frontend-submission/form.php
 *
 */
?>
<div id="ep-frontend-event-submission-section" class="emagic">
    <?php
    if( isset( $args->event ) && isset( $args->event->em_user ) ) {
        if( $args->event->em_user != get_current_user_id() ) {?>
            <div class="ep-alert ep-alert-warning ep-mt-3 ep-fs-6">
                <?php esc_html_e( 'Event does not exists.', 'eventprime-event-calendar-management' );?>
            </div><?php
            exit();
        }
    }
    // check if login required
    if( ! empty( $args->login_required ) ) {?>
        <div class="ep-login-header">
            <h3 class="em_form_heading">
                <?php echo esc_html( $args->ues_login_message );?>
            </h3>
        </div><?php
        echo do_shortcode( '[em_login redirect="reload"]' );
    } else{
        // check if user role restricted
        $hasUserRestriction = 0;
        $frontend_submission_roles = (array) ep_get_global_settings( 'frontend_submission_roles' );
        if( ! empty( $frontend_submission_roles ) ) {
            $user = wp_get_current_user();
            foreach ( $user->roles as $key => $value ) {
                if( ! in_array( $value, $frontend_submission_roles ) ) {
                    $hasUserRestriction = 1;
                    break;
                }
            }
        } 
        if( ! empty( $hasUserRestriction ) ) {?>
            <div class="ep-login-header">
                <h3 class="em_form_heading">
                    <?php echo esc_html( $args->ues_restricted_submission_message );?>
                </h3>
            </div><?php
        } else{?>
            <?php do_action( 'ep_add_loader_section' );?>
            <form class="ep-frontend-event-form" id="ep_frontend_event_form" method="post">
                <div class="ep-fes-hidden-fields">
                    <input type="hidden" name="user_id" value="<?php echo get_current_user_id();?>"/>
                    <?php if( ! empty( $args->event_id ) ) {?>
                        <input type="hidden" name="event_id" value="<?php echo $args->event_id;?>"/>
                    <?php } ?>
                </div>
                
                <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1">
                    <div class="ep-form-row ep-box-row ep-mt-2">
                        <div class="ep-box-col-12">
                            <label for="name" class="ep-form-label">
                                <?php esc_html_e( 'Event Name', 'eventprime-event-calendar-management' );?>
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="em_name" required id="ep_name" class="ep-form-input ep-input-text ep-form-control" value="<?php echo isset($args->event) ? $args->event->name : '';?>" />
                        </div>
                    </div>
                    <?php /// if(isset($args->fes_event_text_color) && !empty($args->fes_event_text_color)):?>
                        <!-- 
                        <div class="ep-form-row ep-box-row ep-mt-2">
                            <div class="ep-box-col-12">
                            <label for="name" class="ep-form-label">
                                <?php esc_html_e('Event Text Color', 'eventprime-event-calendar-management');?>
                            </label>
                            <input data-jscolor="{}" type="text" name="em_event_text_color" id="ep_event_text_color" class="ep-form-input ep-input-text ep-form-control" value="" />
                            </div>
                        </div>-->
                    <?php // endif;?>
                    <div class="ep-form-row ep-box-row ep-mt-2">
                        <div class="ep-box-col-12">
                            <label for="event_description" class="ep-form-label">
                                <?php esc_html_e( 'Description', 'eventprime-event-calendar-management' ); ?>
                            </label><?php
                            $content = isset($args->event) ? $args->event->description : '';
                            $settings = array(
                                'editor_height' => 100,
                                'textarea_rows' => 20
                            );
                            wp_editor($content, 'em_descriptions', $settings);?>
                        </div>
                    </div>

                    <?php if( isset( $args->fes_event_featured_image ) && ! empty( $args->fes_event_featured_image ) ):?>
                        <div class="ep-form-row ep-box-row ep-mt-4">
                            <div class="ep-box-col-12">
                                <label for="ep-fes-featured-file" class="ep-form-label">
                                    <?php esc_html_e( 'Featured Image', 'eventprime-event-calendar-management' );?>
                                </label>
                                <?php if( ! empty( $args->fes_allow_media_library ) ) {?>
                                    <button type="button" class="fes_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'eventprime-event-calendar-management' ); ?></button><?php
                                } else{?>
                                    <input type="file" name="featured_img" id="ep-fes-featured-file" onchange="upload_file_media(this)" accept="image/png, image/jpeg"/><?php
                                }?>
                                <input type="hidden" name="attachment_id" id="attachment_id"class="ep-hidden-attachment-id" value="<?php echo isset( $args->event ) && isset( $args->event->_thumbnail_id ) ? $args->event->_thumbnail_id : '';?>"/>
                            </div>
                            <?php if( isset( $args->event->image_url ) && ! empty( $args->event->image_url ) ) {?>
                                <div class="ep-edit-event-image">
                                    <img src="<?php echo esc_url( $args->event->image_url );?>">
                                </div><?php 
                            } else{?>
                                <div class="ep-edit-event-image"></div><?php
                            } ?>
                        </div>
                    <?php endif;?>
                </div>
                
                <?php
                // Load date time template
                ep_get_template_part( 'events/frontend-submission/date_time', null, $args );

                // Load tickets template
                ep_get_template_part( 'events/frontend-submission/tickets', null, $args );
                
                // Load event types template
                ep_get_template_part( 'events/frontend-submission/event_types', null, $args );

                // Load venues template
                ep_get_template_part( 'events/frontend-submission/venues', null, $args );

                // Load performers template
                ep_get_template_part( 'events/frontend-submission/performers', null, $args );

                // Load performers template
                ep_get_template_part( 'events/frontend-submission/organizers', null, $args );

                // Load more options template
                //ep_get_template_part( 'events/frontend-submission/more_options', null, $args );
                ?>
                
                <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1">
                    <div class="ep-form-row ep-mt-2 ep-register-btn-section">
                        <?php wp_nonce_field( 'ep-frontend-event', 'ep-frontend-event-nonce' ); ?>
                        <button type="submit" class="ep-btn ep-btn-dark ep-frontend-event-form-submit" name="ep_frontend_event" value="<?php esc_html_e( 'Submit', 'eventprime-event-calendar-management' );?>" >
                            <?php 
                            if( ! empty( $args->event ) ) {
                                esc_html_e( 'Update Event', 'eventprime-event-calendar-management' );
                            } else{
                                esc_html_e( 'Submit Event', 'eventprime-event-calendar-management' );
                            }?>
                        </button>
                        <input type="hidden" name="redirect" value="" />
                        <span class="spinner"></span>
                    </div>
                </div>
            </form><?php
        }
    }?>
</div>