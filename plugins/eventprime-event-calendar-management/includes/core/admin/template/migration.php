<?php defined( 'ABSPATH' ) || exit;?>

<div class="emagic">
    <div class="ep-migration-box-wrap ep-box-wrap ep-mt-5">
        <div class="ep-box-row ep-items-start ep-g-4 ep-mt-5">
            <div class="ep-box-col-1">&nbsp;</div>
            <div class="ep-box-col-7 ep-ext-card">
                <div class="ep-card ep-text-small ep-box-h-100">
                    <div class="ep-card-body">
                        <?php if( get_option( 'ep_db_need_to_run_migration' ) == 1 ) {?>
                            <div class="ep-card-title ep-fs-4 ep-text-center ep-mt-3">
                                <?php esc_html_e( 'Welcome to EventPrime v3!', 'eventprime-event-calendar-management' ); ?>
                            </div>
                            <div class="ep-ext-box-description ep-p-3">
                                <h3><?php esc_html_e( 'Highlights:', 'eventprime-event-calendar-management' ); ?></h3>
                                <ul class="ep-revamp-highlight ep-mb-4">
                                    <li><?php esc_html_e( 'Massive improvements and new features.', 'eventprime-event-calendar-management' ); ?></li>
                                    <li><?php esc_html_e( 'Completely rebuilt dashboard area and frontend.', 'eventprime-event-calendar-management' ); ?></li>
                                </ul>
                                <h3><?php esc_html_e( 'Actions needed to update:', 'eventprime-event-calendar-management' ); ?></h3>
                                <ul class="ep-revamp-highlight ep-mb-4">
                                    <li><?php esc_html_e( 'Click on \'Finish Updating\'.', 'eventprime-event-calendar-management' ); ?></li>
                                    <li><?php esc_html_e( 'Download and re-install extensions from your account on', 'eventprime-event-calendar-management' ); ?> <a href="<?php echo esc_url('https://metagauss.com/my-profile/');?>" target="_blank"><?php echo esc_html( 'Metagauss' );?></a> <?php esc_html_e( 'website', 'eventprime-event-calendar-management' ); ?>.</li>
                                </ul>
                                <h3><?php esc_html_e( 'If you wish to continue using the old version:', 'eventprime-event-calendar-management' ); ?></h3>
                                <ul>
                                    <li><?php esc_html_e( 'Download version 2.8.6 from', 'eventprime-event-calendar-management' ); ?> <a href="<?php echo esc_url( 'https://downloads.wordpress.org/plugin/eventprime-event-calendar-management.2.8.6.zip');?>"><?php esc_html_e( 'here', 'eventprime-event-calendar-management' ); ?></a>.</li>
                                    <li><?php esc_html_e( 'Click \'Cancel\'', 'eventprime-event-calendar-management' ); ?>.</li>
                                    <li><?php esc_html_e( 'Deactivate and delete version 3.x', 'eventprime-event-calendar-management' ); ?>.</li>
                                    <li><?php esc_html_e( 'Install version 2.8.6', 'eventprime-event-calendar-management' ); ?>.</li>
                                    <li><?php esc_html_e( 'Reactivate your EventPrime extensions, if any', 'eventprime-event-calendar-management' ); ?>.</li>
                                </ul>
                            </div>
                            <div class="ep-card-footer ep-d-flex ep-justify-content-between ep-py-2 ep-bg-white ep-no-border">
                                <button name="run_migration" type="button" class="button button-primary ep-open-modal" id="em_cancel_migration_process">
                                    <?php esc_html_e( 'Cancel', 'eventprime-event-calendar-management' );?>
                                </button>
                                <div id="ep_event_migration_run_message"></div>
                                <button name="run_migration" type="button" class="button button-primary ep-open-modal" id="em_start_migration_process">
                                    <?php esc_html_e( 'Finish Updating', 'eventprime-event-calendar-management' );?>
                                </button>
                            </div><?php
                        } else{?>
                            <div class="ep-alert ep-alert-warning ep-mt-3">
                                <?php esc_html_e( 'Your migration is complete and EventPrime was successfully updated to version 3.0.', 'eventprime-event-calendar-management' ); ?>
                            </div><?php
                        }?>
                    </div>
                </div>
            </div>
            <?php if( get_option( 'ep_db_need_to_run_migration' ) == 1 ) {
                $ep_deactivate_extensions_on_migration = ( ! empty( get_option( 'ep_deactivate_extensions_on_migration ') ) ? get_option( 'ep_deactivate_extensions_on_migration' ) : array() );
                $old_exts_lists = ep_old_ext_data();
                if( ! empty( $ep_deactivate_extensions_on_migration ) ) {?>
                    <div class="ep-box-col-3 ep-ext-card">
                        <div class="ep-card ep-text-small ep-box-h-100">
                            <div class="ep-card-body">
                                <div class="ep-card-title ep-fs-4 ep-text-center ep-mt-3">
                                    <?php esc_html_e( 'Important Note', 'eventprime-event-calendar-management' ); ?>
                                </div>
                                <div class="ep-ext-box-description ep-p-3">
                                    <p class="ep-col-desc">
                                        You have <?php echo count( $ep_deactivate_extensions_on_migration );?> EventPrime extensions which are not compatible with version 3.0. They have been deactivated to ensure smooth migration. Once migration is complete, please log into your metagauss.com account to download latest versions of these extensions. All extension data will be retained.
                                    </p>
                                    <ul><?php 
                                        $i = 1;
                                        foreach( $ep_deactivate_extensions_on_migration as $exts ) {
                                            $exp_exts = explode( '/', $exts );
                                            if( ! empty( $exp_exts ) ) {
                                                $ext_file = $exp_exts[1];
                                                if( ! empty( $old_exts_lists[$ext_file] ) ) {
                                                    echo '<li>'.$i . '. ' . $old_exts_lists[$ext_file].'</li>';
                                                    $i++;
                                                }
                                            }
                                        }?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><?php
                }
            }?>
            <div class="ep-box-col-1">&nbsp;</div>
        </div>
    </div>
</div>

<style>
    .ep-ext-box-description ul li{
        list-style: disc;
        margin-left: 20px;
    }
    .ep-revamp-highlight li{
        font-size: 14px;
    }
</style>