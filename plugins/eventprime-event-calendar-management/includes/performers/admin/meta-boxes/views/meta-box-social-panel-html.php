<?php
/**
 * Performer Settings panel html.
 */

defined( 'ABSPATH' ) || exit;
$em_social_links = (array)get_post_meta( $post->ID, 'em_social_links', true );
?>
<div id="ep_performer_social_data" class="panel ep_performer_options_panel">
    <div class="ep-box-wrap ep-my-3">
        <div class="ep-box-row">
            <?php $social_links = ep_social_sharing_fields();
            foreach( $social_links as $key => $links) { ?>
                <div class="ep-box-col-12 ep-mb-3 ep-meta-box-section">
                    <div class="ep-box-row">
                        <div class="ep-box-col-6">
                            <div class="ep-meta-box-title">
                                <?php echo esc_attr( $links ); ?>
                            </div>
                            <div class="ep-meta-box-data">
                                <input class="ep-form-control"  type="text" name="em_social_links[<?php echo esc_attr( $key ); ?>]" 
                                    placeholder="<?php echo sprintf( __( 'https://www.%s.com/XYZ/', 'eventprime-event-calendar-management' ), strtolower( $links ) ); ?>"
                                    value="<?php echo isset( $em_social_links[$key] ) ? esc_attr( $em_social_links[$key] ) : ''; ?>"
                                    >
                                <p class="emnote emeditor">
                                    <?php echo sprintf( __( 'Enter %s URL of the Performer, if available. Eg.:https://www.%s.com/XYZ/', 'eventprime-event-calendar-management' ), $links, strtolower( $links ) ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div><?php
            }?>
        </div>
    </div>
</div>