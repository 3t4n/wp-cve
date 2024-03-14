<?php
/**
 * View: Single Organizer - Detail
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/organizers/single-organizer/detail.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-10">
    <div class="ep-single-box-info">
        <div class="ep-single-box-content">
            <div class="ep-single-box-title-info">
                <h3 class="ep-single-box-title ep-organizer-name" title="<?php echo esc_attr( $args->organizer->name ); ?>">
                    <?php echo esc_html( $args->organizer->name ); ?>
                </h3>
                <ul class="ep-single-box-details-meta ep-mx-0 ep-my-2 ep-p-0">
                    <li> 
                        <div class="ep-details-box-icon ep-pr-2">
                        <?php $image_url = EP_BASE_URL . 'includes/assets/images/email-icon.png';?>
                        <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </div>
                        <div class="ep-details-box-value">
                        <?php 
                        if ( ! empty( $args->organizer->em_organizer_emails ) && count( $args->organizer->em_organizer_emails ) > 0 && ! empty( $args->organizer->em_organizer_emails[0] ) ) { 
                            foreach( $args->organizer->em_organizer_emails as $key => $val ) {
                                $args->organizer->em_organizer_emails[$key] = '<a href="mailto:'.$val.'">'.htmlentities( $val ).'</a>';
                            }
                            echo implode( ', ', $args->organizer->em_organizer_emails ); 
                        } else {
                            esc_html_e( 'Not Available', 'eventprime-event-calendar-management' );
                        } ?>
                        </div>
                    </li>
                    <li>
                        <div class="ep-details-box-icon ep-pr-2">
                        <?php $image_url = EP_BASE_URL . 'includes/assets/images/phone-icon.png';?>
                        <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </div>
                        <div class="ep-details-box-value">
                        <?php if ( ! empty( $args->organizer->em_organizer_phones ) && count( $args->organizer->em_organizer_phones ) > 0  && ! empty( $args->organizer->em_organizer_phones[0] ) ) {
                            echo implode( ', ', $args->organizer->em_organizer_phones ); 
                        }else {
                            esc_html_e( 'Not Available', 'eventprime-event-calendar-management' );
                        } ?>
                        </div>
                    </li>

                    <li>
                        <div class="ep-details-box-icon ep-pr-2">
                        <?php $image_url = EP_BASE_URL . 'includes/assets/images/website-icon.png';?>
                        <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </div>
                        <div class="ep-details-box-value">
                        <?php if ( ! empty( $args->organizer->em_organizer_websites ) && count( $args->organizer->em_organizer_websites ) > 0 && ! empty( $args->organizer->em_organizer_websites[0] ) ) { 
                            foreach( $args->organizer->em_organizer_websites as $key => $val ) {
                                if( ! empty( $val ) ){
                                    $args->organizer->em_organizer_websites[$key] = '<a href="'.$val.'" target="_blank">'.htmlentities( $val ).'</a>';
                                }
                            }
                            echo implode( ', ', $args->organizer->em_organizer_websites ); 
                        } else {
                            esc_html_e( 'Not Available', 'eventprime-event-calendar-management' ); 
                        }?>
                        </div>
                    </li>
                </ul>
            </div>

            <?php if ( ! empty( $args->organizer->em_social_links ) ){ ?>
                <div class="ep-single-box-social"><?php
                    if( ! empty( $args->organizer->em_social_links['facebook'] ) ){ ?>
                        <a href="<?php echo esc_url( $args->organizer->em_social_links['facebook'] );?>" target="_blank" title="<?php echo esc_attr( 'Facebook' );?>" class="ep-facebook-f"> 
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/facebook-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php
                    }
                    if( ! empty( $args->organizer->em_social_links['instagram'] ) ){ ?>
                        <a href="<?php echo esc_url( $args->organizer->em_social_links['instagram'] );?>" target="_blank" title="<?php echo esc_attr( 'Instagram' );?>" class="ep-instagram">
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/instagram-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php
                    }
                    if( ! empty( $args->organizer->em_social_links['linkedin'] ) ) { ?>
                        <a href="<?php echo esc_url( $args->organizer->em_social_links['linkedin'] );?>" target="_blank" title="<?php echo esc_attr( 'Linkedin' );?>" class="ep-twitter"> 
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/linkedin-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php
                    }
                    if( ! empty( $args->organizer->em_social_links['twitter'] ) ){ ?>
                        <a href="<?php echo esc_url( $args->organizer->em_social_links['twitter'] );?>" target="_blank" title="<?php echo esc_attr( 'Twitter' );?>" class="ep-twitter">
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/twitter-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php
                    }
                    if ( ! empty( $args->organizer->em_social_links['youtube'] ) ) {?>
                        <a href="<?php echo esc_url( $args->organizer->em_social_links['youtube'] ); ?>" target="_blank" title="<?php echo esc_attr('Youtube'); ?>" class="ep-youtube">
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/youtube-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php 
                    }?>
                </div><?php
            } ?>  
            
            <div class="ep-single-box-summery ep-single-box-desc">
                <?php if ( isset( $args->organizer->description ) && $args->organizer->description !== '' ) {
                    echo wpautop( wp_kses_post( $args->organizer->description ) );
                } else{
                    esc_html_e( 'No description available', 'eventprime-event-calendar-management' );
                }?>
            </div>

            <?php do_action( 'ep_organizer_view_after_detail' );?>
        </div>
    </div>
</div>