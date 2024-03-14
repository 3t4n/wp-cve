<?php
/**
 * Performer Settings panel html.
 */

defined( 'ABSPATH' ) || exit;
$em_performer_phones   = get_post_meta( $post->ID, 'em_performer_phones', true );
$em_performer_emails   = get_post_meta( $post->ID, 'em_performer_emails', true );
$em_performer_websites = get_post_meta( $post->ID, 'em_performer_websites', true );
$em_is_featured        = get_post_meta( $post->ID, 'em_is_featured', true );
?>
<div id="ep_performer_personal_data" class="panel ep_performer_options_panel">

    <div class="ep-box-wrap ep-my-3">
    <div class="ep-meta-box-section">
  
        <div class="ep-box-row ep-meta-box-data ep-performers-phone">
     
            <?php if( empty( $em_performer_phones ) || count( $em_performer_phones ) == 0 ) {?>

                <label class=" ep-box-col-12 ep-meta-box-phone ep-form-label">
                      <?php esc_html_e('Phone', 'eventprime-event-calendar-management'); ?>
                    </label>
                <div class="ep-box-col-12 ep-per-phone ep-per-data-field-input">
                    <input type="tel" class="ep-per-data-input ep-mr-2" name="em_performer_phones[]" placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>"><button type="button" class="ep-per-add-more button button-primary" 
                        data-input="phone" 
                        title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                        data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                        data-placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>" >
                        +
                    </button>
                    </div>
          
              
              
              <?php
            } else{
                foreach( $em_performer_phones as $pkey => $phone ) {?>
                    <div class="ep-box-col-12 ep-per-phone ep-per-data-field">
                        <input type="tel" class="ep-per-data-input ep-mr-2" name="em_performer_phones[]" 
                            placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>"
                            value="<?php echo esc_attr( $phone );?>"><?php if( $pkey == 0 ) {?><button type="button" class="ep-per-add-more button button-primary" 
                                data-input="phone" 
                                title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                                data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                                data-placeholder="<?php echo esc_attr('Phone', 'eventprime-event-calendar-management');?>" >
                                +
                            </button><?php
                        }else{?>
                            <button type="button" class="ep-per-remove button button-primary" data-input="phone" 
                                title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>">
                                -
                            </button><?php
                        }?>
                    </div><?php
                }
            }?>
        </div>
    </div>
            
    <div class="ep-meta-box-section ep-mt-3">
       
        <div class="ep-box-row ep-meta-box-data ep-performers-email">
             <div class="ep-box-col-12 ep-meta-box-data ep-meta-box-email">
            <?php esc_html_e('Email', 'eventprime-event-calendar-management'); ?>
        </div>
            
            <?php if( empty( $em_performer_emails ) || count( $em_performer_emails ) == 0 ) {?>
                <div class="ep-box-col-12 ep-per-email ep-per-data-field">
                    <input type="email" class="ep-per-data-input ep-mr-2" name="em_performer_emails[]" placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>"><button type="button" class="ep-per-add-more button button-primary" 
                        data-input="email" 
                        title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                        data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                        data-placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>" >
                        +
                    </button>
                </div><?php
            } else{
                foreach( $em_performer_emails as $pkey => $email ) {?>
                    <div class="ep-box-col-12 ep-per-email ep-per-data-field">
                        <input type="email" class="ep-per-data-input ep-mr-2" name="em_performer_emails[]" 
                            placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>"
                            value="<?php echo esc_attr( $email );?>"><?php if( $pkey == 0 ) {?><button type="button" class="ep-per-add-more button button-primary" 
                                data-input="email" 
                                title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                                data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                                data-placeholder="<?php echo esc_attr('Email', 'eventprime-event-calendar-management');?>" >
                                +
                            </button><?php
                        } else{?>
                            <button type="button" class="ep-per-remove button button-primary" data-input="email" 
                                title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>">
                                -
                            </button><?php
                        }?>
                    </div><?php
                }
            }?>
        </div>
    </div>
            
    <div class="ep-meta-box-section ep-mt-3">
        <div class="ep-box-row ep-meta-box-data ep-performers-website">
        
        <div class="ep-box-col-12 ep-meta-box-website">
            <?php esc_html_e('Website', 'eventprime-event-calendar-management'); ?>
        </div>
 
            <?php if( empty( $em_performer_websites ) || count( $em_performer_websites ) == 0 ) {?>
                <div class="ep-box-col-12 ep-per-website ep-per-data-field">
                    <input type="url" class="ep-per-data-input ep-mr-2" name="em_performer_websites[]" placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>"><button type="button" class="ep-per-add-more button button-primary" 
                        data-input="website" 
                        title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                        data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                        data-placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>" >
                        +
                    </button>
                </div><?php
            }else{
                foreach( $em_performer_websites as $pkey => $website ) {?>
                    <div class="ep-box-col-12 ep-per-website ep-per-data-field">
                        <input type="url" class="ep-per-data-input ep-mr-2" name="em_performer_websites[]" 
                            placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>"
                            value="<?php echo esc_attr( $website );?>"><?php if( $pkey == 0 ) {?>
                            <button type="button" class="ep-per-add-more button button-primary" 
                                data-input="website" 
                                title="<?php echo esc_attr('Add More', 'eventprime-event-calendar-management');?>" 
                                data-remove_title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>"
                                data-placeholder="<?php echo esc_attr('Website', 'eventprime-event-calendar-management');?>" >
                                +
                            </button><?php
                        } else{?>
                            <button type="button" class="ep-per-remove button button-primary" data-input="website" 
                                title="<?php echo esc_attr('Remove', 'eventprime-event-calendar-management');?>">
                                -
                            </button><?php
                        }?>
                    </div><?php
                }
            }?>
     
        
        </div>
    </div>
            
            
    <div class="ep-meta-box-section ep-mt-3 ep-mb-3">
        <div class="ep-box-row ep-meta-box-data">
            <div class="ep-box-col-12">
            <label class="ep-performer-featured">
                <input type="checkbox" name="em_is_featured" id="em_is_featured" value="1" <?php if( $em_is_featured == 1 ) { echo 'checked="checked"'; }?> >
                <?php esc_html_e('Featured', 'eventprime-event-calendar-management'); ?>
            </label>
            </div>
        </div>
    </div>
        </div>
</div>