<?php
/**
 * View: Single Performer - Detail
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/single-performer/detail.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-10">
    <div class="ep-single-box-info">
        <div class="ep-single-box-content">
            <div class="ep-single-box-title-info">
                <h3 class="ep-single-box-title ep-performer-name" title="<?php echo esc_attr( $args->performer->name ); ?>">
                    <?php echo esc_html( $args->performer->name ); ?>
                </h3>
                <?php if ( isset( $args->performer->em_role ) ){ ?>   
                    <p class="ep-single-box-designation"><?php echo esc_html( $args->performer->em_role ); ?></p><?php
                } ?>
                <ul class="ep-single-box-details-meta ep-mx-0 ep-my-2 ep-p-0">
                    <?php if ( ! empty( $args->performer->em_performer_emails ) ) {?>
                        <li>
                            <div class="ep-details-box-icon ep-pr-2">
                                <?php $image_url = EP_BASE_URL . 'includes/assets/images/email-icon.png';?>
                                <img src="<?php echo esc_url( $image_url );?>" width="30" />
                            </div>
                            <div class="ep-details-box-value"><?php
                                foreach ( $args->performer->em_performer_emails as $key => $val ) {
                                    $args->performer->em_performer_emails[$key] = '<a href="mailto:' . $val . '">' . htmlentities($val) . '</a>';
                                }
                                echo implode( ', ', $args->performer->em_performer_emails );?>
                            </div>
                        </li>
                    <?php } ?>
                    
                    <?php if ( ! empty( $args->performer->em_performer_phones ) ) {?>
                        <li>
                            <div class="ep-details-box-icon ep-pr-2">
                                <?php $image_url = EP_BASE_URL . 'includes/assets/images/phone-icon.png';?>
                                <img src="<?php echo esc_url( $image_url );?>" width="30" />
                            </div>
                            <div class="ep-details-box-value"><?php
                                echo implode( ', ', $args->performer->em_performer_phones );?>
                            </div>
                        </li>
                    <?php } ?>
                    <?php if ( ! empty( $args->performer->em_performer_websites ) ) {?>
                        <li>
                            <div class="ep-details-box-icon ep-pr-2">
                                <?php $image_url = EP_BASE_URL . 'includes/assets/images/website-icon.png';?>
                                <img src="<?php echo esc_url( $image_url );?>" width="30" />
                            </div>
                            <div class="ep-details-box-value"><?php
                                foreach ( $args->performer->em_performer_websites as $key => $val ) {
                                    if ( ! empty( $val ) ) {
                                        $args->performer->em_performer_websites[$key] = '<a href="' . $val . '" target="_blank">' . htmlentities($val) . '</a>';
                                    }
                                }
                                echo implode( ', ', $args->performer->em_performer_websites );?>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <?php if ( ! empty( $args->performer->em_social_links ) ) { ?>
                <div class="ep-single-box-social">
                    <?php if ( isset( $args->performer->em_social_links['facebook'] ) ) { ?>
                        <a href="<?php echo esc_url( $args->performer->em_social_links['facebook'] ); ?>" target="_blank" title="<?php echo esc_attr('Facebook'); ?>" class="ep-facebook-f"> 
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/facebook-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php
                    }
                    if ( isset( $args->performer->em_social_links['instagram'] ) ) {?>
                        <a href="<?php echo esc_url( $args->performer->em_social_links['instagram'] ); ?>" target="_blank" title="<?php echo esc_attr('Instagram'); ?>" class="ep-instagram">
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/instagram-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php
                    }
                    if ( isset( $args->performer->em_social_links['linkedin'] ) ) {?>
                        <a href="<?php echo esc_url( $args->performer->em_social_links['linkedin'] ); ?>" target="_blank" title="<?php echo esc_attr('Linkedin'); ?>" class="ep-linkedin"> 
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/linkedin-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php
                    }
                    if ( isset( $args->performer->em_social_links['twitter'] ) ) {?>
                        <a href="<?php echo esc_url( $args->performer->em_social_links['twitter'] ); ?>" target="_blank" title="<?php echo esc_attr('Twitter'); ?>" class="ep-twitter">
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/twitter-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php 
                    }
                    if ( isset( $args->performer->em_social_links['youtube'] ) ) {?>
                        <a href="<?php echo esc_url( $args->performer->em_social_links['youtube'] ); ?>" target="_blank" title="<?php echo esc_attr('Youtube'); ?>" class="ep-youtube">
                            <?php $image_url = EP_BASE_URL . 'includes/assets/images/youtube-icon.png';?>
                            <img src="<?php echo esc_url( $image_url );?>" width="30" />
                        </a><?php 
                    }?>
                </div><?php 
            }?>  

            <div class="ep-single-box-summery ep-single-box-desc">
                <?php
                if ( isset( $args->performer->description ) && $args->performer->description !== '' ) {
                    $content = apply_filters('ep_performer_description', $args->performer->description);
                    echo $content;
                    
                } else {
                    esc_html_e( 'No description available', 'eventprime-event-calendar-management' );
                }?>
            </div>
            <!-- single perfomer gallery images -->
            <?php if ( is_array( $args->performer->em_performer_gallery ) && count( $args->performer->em_performer_gallery ) > 1 ) { ?>
                <div class="em_photo_gallery em-single-perfomer-photo-gallery" >
                    <div class="kf-row-heading">
                        <span class="kf-row-title">
                            <?php esc_html_e( 'Gallery', 'eventprime-event-calendar-management' ); ?>
                        </span>
                    </div>
                    <div id="ep_perfomer_gal_thumbs" class="ep-d-inline-flex ep-flex-wrap ep-mb-4">
                        <?php if(get_post_thumbnail_id($args->performer->id)):?>
                        <a href="javascript:void(0);" rel="gal" class="ep_open_gal_modal ep-rounded-1 ep-mr-2 ep-mb-2" ep-modal-open="ep-perfomer-gal-modal">
                            <?php echo wp_get_attachment_image( get_post_thumbnail_id($args->performer->id), array(50, 50),["class" => "ep-rounded-1","alt"=>"Gallery Image"] ); ?>
                        </a>
                        <?php endif;?>
                        <?php foreach ( $args->performer->em_performer_gallery as $id ) { ?>
                            <a href="javascript:void(0);" rel="gal" class="ep_open_gal_modal ep-rounded-1 ep-mr-2 ep-mb-2" ep-modal-open="ep-perfomer-gal-modal">
                                <?php echo wp_get_attachment_image( $id, array(50, 50),["class" => "ep-rounded-1","alt"=>"Gallery Image"] ); ?>
                            </a>
                        <?php } ?>
                    </div><?php
                    if( ! empty( $args->performer->em_performer_gallery ) && count( $args->performer->em_performer_gallery ) > 0 ) {?>
                        <div class="ep_perfomer_gallery_modal_container ep-modal ep-modal-view" id="ep-perfomer-gallery-modal"  ep-modal="ep-perfomer-gal-modal" style="display: none;" >
                            <div class="ep-modal-overlay" ep-modal-close="ep-perfomer-gal-modal"></div>
                            <div class="ep-modal-wrap ep-modal-lg">
                                <div class="ep-modal-content">
                                    <div class="ep-modal-titlebar ep-d-flex ep-items-center ep-py-2">
                                        <div class="ep-modal-title ep-px-3 ep-fs-5 ep-my-2">
                                            <?php esc_html_e( 'Gallery', 'eventprime-event-calendar-management' ); ?> 
                                        </div>
                                        <span class="ep-modal-close" id="ep_performer_gallery_modal_close" ep-modal-close="ep-perfomer-gal-modal"><span class="material-icons-outlined">close</span></span>
                                    </div>
                                    <div class="ep-modal-body">
                                        <ul class="ep-rslides" id="ep_perfomer_gal_modal">
                                            <?php if(get_post_thumbnail_id($args->performer->id)):
                                                $url = wp_get_attachment_url( get_post_thumbnail_id($args->performer->id), 'large' )?>
                                                <li>
                                                    <img src="<?php echo esc_url( $url ); ?>" >
                                                </li>
                                            <?php endif;?>
                                            <?php foreach ( $args->performer->em_performer_gallery as $id ) {
                                                $url = wp_get_attachment_url( $id, 'large' )?>
                                                <li>
                                                    <img src="<?php echo esc_url( $url ); ?>" >
                                                </li><?php 
                                            }?>
                                        </ul>
                                        <div class="ep-single-event-nav"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div><?php 
            } ?>

            <?php do_action( 'ep_performer_view_after_detail' );?>
        </div>
    </div>
</div>