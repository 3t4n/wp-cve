<?php namespace Hurrytimer;
global $post_id;
?>
<table class="form-table hidden mode-settings" data-for="hurrytModeEvergreen">

    <tr class="form-field">
        <td>
            <label><?php _e( 'Ends after', "hurrytimer" ) ?></label>
        </td>
        <td>
            <div class="hurrytimer-field-duration">

                <label>
                    <?php _e( "Days", "hurrytimer" ) ?>

                    <input type="number"
                           class="hurrytimer-duration"
                           name="duration[]"
                           min="0"
                           data-index="0"
                           value="<?php echo $campaign->duration[ 0 ] ?>">
                </label>
                <label>
                    <?php _e( "Hours", "hurrytimer" ) ?>
                    <input type="number"
                           class="hurrytimer-duration"
                           name="duration[]"
                           min="0"
                           data-index="1"
                           value="<?php echo $campaign->duration[ 1 ] ?>"
                    >

                </label>
                <label>
                    <?php _e( "Minutes", "hurrytimer" ) ?>
                    <input type="number"
                           class="hurrytimer-duration"
                           name="duration[]"
                           min="0"
                           data-index="2"
                           value="<?php echo $campaign->duration[ 2 ] ?>"
                    >

                </label>
                <label>
                    <?php _e( "Seconds", "hurrytimer" ) ?>
                    <input type="number"
                           class="hurrytimer-duration"
                           name="duration[]"
                           data-index="3"
                           value="<?php echo $campaign->duration[ 3 ] ?>"
                    >

                </label>
            </div>
        </td>
    </tr>
    <tr class="form-field">
        <td>
            <label><?php _e( 'Detection methods', "hurrytimer" ) ?></label>
        </td>
        <td>
            <div>
                <label class="hurryt-mr-2">
                    <input class="hurryt-m-0" type="checkbox" name="detection_methods[]"
                           value="<?php echo C::DETECTION_METHOD_COOKIE ?>"
                        <?php echo in_array( C::DETECTION_METHOD_COOKIE,
                            $campaign->detectionMethods ) ? 'checked' : '' ?>
                    >
                    <?php _e( "Cookie", "hurrytimer" ) ?>
                </label>
                <label class="hurryt-mr-2">
                    <input class="hurryt-m-0" type="checkbox" name="detection_methods[]"
                           value="<?php echo C::DETECTION_METHOD_IP ?>"
                        <?php echo in_array( C::DETECTION_METHOD_IP,
                            $campaign->detectionMethods ) ? 'checked' : '' ?>
                    >
                    <?php _e( "IP address", "hurrytimer" ) ?>

                </label>
                <label id="hurrytUserSessionWrap" for="hurrytUserSessionMethod">
                    <input 
                   
                        id="hurrytUserSessionMethod"
                     class="hurryt-mx-0
                     <?php 
        // removeIf(pro)
                     echo 'disabled style="pointer-events:none"';
        // endRemoveIf(pro)

                     ?>
                     
                     " type="checkbox" name="detection_methods[]"
                           value="<?php echo C::DETECTION_METHOD_USER_SESSION ?>"
                        <?php echo in_array( C::DETECTION_METHOD_USER_SESSION,
                            $campaign->detectionMethods ) ? 'checked' : '' ?>
                    >
                    <?php _e( "User Session", "hurrytimer" ) ?> <span
                            title="This is recommended for campaigns restricted to logged-in users."
                            class="hurryt-icon" data-icon="help" style="vertical-align: middle;"></span>

                </label>
            </div>
            <?php // removeIf(pro) ?>
            <p class="description hidden" style="margin-top: 10px;" id="hurrytUserSessionUpgradeNotice">
            <span class="dashicons dashicons-lock" style="color:#828282"></span>
                <b>User Session</b> method is a pro feature. <a
                    href="http://hurrytimer.com/#pricing?utm_source=plugin&utm_medium=user_sess&utm_campaign=upgrade">Upgrade
                now</a>.
            </p>
            <?php // endRemoveIf(pro) ?>
        </td>
    </tr>
    <tr class="form-field">
        <td><label for="active"><?php _e( "Restart when expired", "hurrytimer" ) ?></label></td>
        <td>
            <select name="restart" id="hurrytimer-evergreen-restart"
                    class="hurryt-w-full">
                <option value="<?php echo C::RESTART_NONE ?>" <?php echo selected( $campaign->restart,
                    C::RESTART_NONE ) ?>>
                    <?php _e( "None", "hurrytimer" ) ?>
                </option>
                <option value="<?php echo C::RESTART_IMMEDIATELY ?>" <?php echo selected( $campaign->restart,
                    C::RESTART_IMMEDIATELY ) ?>>
                    <?php _e( "Restart immediately", "hurrytimer" ) ?>
                </option>
                <option value="<?php echo C::RESTART_AFTER_RELOAD ?>" <?php echo selected( $campaign->restart,
                    C::RESTART_AFTER_RELOAD ) ?>>
                    <?php _e( "Restart at the next visit", "hurrytimer" ) ?>
                </option>
                <option value="<?php echo C::RESTART_AFTER_DURATION ?>" <?php echo selected( $campaign->restart,
                    C::RESTART_AFTER_DURATION ) ?>>
                    <?php _e( "Restart after a specific time...", "hurrytimer" ) ?>
                </option>
            </select>
            <?php // removeIf(pro) ?>
            <p class="description hidden" style="margin-top: 10px;" id="hurrytimer-restart-after-feature-unlock">
                <b><span class="dashicons dashicons-lock" style="color:#828282"></span> Restart After a Specific Time</b> is a pro feature. <a
                    href="http://hurrytimer.com/#pricing?utm_source=plugin&utm_medium=restart_after&utm_campaign=upgrade">Upgrade
                now</a>.
            </p>
            <?php // endRemoveIf(pro) ?>

        </td>
    </tr>
    <tr class="form-field" id="hurrytimer-evergreen-restart-duration"
    
    <?php // removeIf(pro) ?>
     style="opacity: .5; pointer-events:none;"
     <?php //endRemoveIf(pro) ?>
     >
        <td>
            <label><?php _e( 'Restart after', "hurrytimer" ) ?></label>
        </td>
        <td>
            <div class="hurrytimer-field-duration">
                <label>
                    <?php _e( "Days", "hurrytimer" ) ?>

                    <input type="number"
                           class="hurrytimer-duration"
                           name="restart_duration[days]"
                           min="0"
                           value="<?php echo $campaign->restartDuration[ 'days' ] ?>">
                </label>
                <label>
                    <?php _e( "Hours", "hurrytimer" ) ?>
                    <input type="number"
                           class="hurrytimer-duration"
                           name="restart_duration[hours]"
                           min="0"
                           value="<?php echo $campaign->restartDuration[ 'hours' ] ?>"
                    >

                </label>
                <label>
                    <?php _e( "Minutes", "hurrytimer" ) ?>
                    <input type="number"
                           class="hurrytimer-duration"
                           name="restart_duration[minutes]"
                           min="0"
                           value="<?php echo $campaign->restartDuration[ 'minutes' ] ?>"
                    >

                </label>
                <label>
                    <?php _e( "Seconds", "hurrytimer" ) ?>
                    <input type="number"
                           class="hurrytimer-duration"
                           name="restart_duration[seconds]"
                           value="<?php echo $campaign->restartDuration[ 'seconds' ] ?>"
                    >

                </label>
            </div>
        </td>
    </tr>

    <tr class="form-field">
        <td><label for="active"><?php _e( "Reset on page reload", "hurrytimer" ) ?></label></td>
        <td>
            <?php Utils\Form::toggle( 'reload_reset',
                $campaign->reloadReset,
                'hurrytimer-reload-reset' ); ?>

            <p class="description">This will force visitor timer to reset even if it is not yet expired.</p>
        </td>
    </tr>
    <?php if ( $post_id !== null ): ?>
        <tr class="form-field">
            <td><label for="active"><?php _e( "Reset countdown now", "hurrytimer" ) ?>
                </label></td>
            <td>
                <div>
                    <button type="button" data-id="<?php echo $post_id ?>"
                            data-cookie="<?php echo Cookie_Detection::cookieName( $post_id ) ?>"
                            data-url="<?php echo $resetCampaignCurrentAdminUrl ?>" class="button button-default"
                            id="hurrytResetCurrent">Only for me
                    </button>
                    &nbsp;
                    <button type="button" data-url="<?php echo $resetCampaignAllVisitorsUrl ?>"
                            class="button button-default" id="hurrytResetAll">For all visitors...
                    </button>
                </div>
            </td>
        </tr>
    <?php endif; ?>
</table>