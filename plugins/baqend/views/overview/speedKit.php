<?php if ( $stats ): ?>
    <div class="box-wrap">
        <h3>
            <i class="iqon-rocket iqon-orange iqon-large"></i>
            <span class="box-heading"><?php _e( 'Speed Kit', 'baqend' ) ?></span>

            <span class="box-toggle">
                <label for="form-speed-kit-toggle" id="form-speed-kit-toggle-label">
                    <span class="statusText" <?php if ( $exceeded && $speed_kit ): ?>hidden<?php endif; ?>>
                        <?php _e( 'Speed Kit is currently', 'baqend' ) ?>
                    </span>
                    <span class="exceededText"
                          <?php if ( ! $exceeded || ( $exceeded && ! $speed_kit ) ): ?>hidden<?php endif; ?>>
                        <?php _e( 'Speed Kit free limit is', 'baqend' ) ?>
                    </span>

                    <span class="exceeded"
                          <?php if ( ! $exceeded || ( $exceeded && ! $speed_kit ) ): ?>hidden<?php endif; ?>>
                        <?php _e( 'exceeded', 'baqend' ) ?>
                    </span>
                    <span class="enabled"
                          <?php if ( $exceeded || ( ! $exceeded && ! $speed_kit ) ): ?>hidden<?php endif; ?>>
                        <?php _e( 'enabled', 'baqend' ) ?>
                    </span>
                    <span class="disabled" <?php if ( $speed_kit ): ?>hidden<?php endif; ?>>
                        <?php _e( 'disabled', 'baqend' ) ?>
                    </span>
                </label>
                <input id="form-speed-kit-toggle" type="checkbox" name="enable"
                       class="toggle <?php if ( $exceeded ): ?>exceeded<?php endif; ?>"
                       <?php if ( $speed_kit ): ?>checked<?php endif; ?>>
            </span>
        </h3>

        <div class="box-row">
            <section class="box box-intro">
                <p><?php _e( 'With Speed Kit, you can improve the performance of your currently hosted WordPress blog within seconds!', 'baqend' ) ?></p>
            </section>

            <?php if ( ! $exceeded ): ?>
                <section class="box">
                    <h4><? _e( 'Outgoing Data', 'baqend' ) ?></h4>
                    <span class="statistics-number"><?php echo traffic_format( $stats->traffic_now ); ?></span>

                    <div class="box-meter">
                        <meter class="baqend" min="0" max="<?php echo $stats->traffic_max; ?>" optimum="0"
                               low="<?php echo round( $stats->traffic_max * 3 / 4 ); ?>"
                               high="<?php echo $stats->traffic_max - 1; ?>" value="<?php echo $stats->traffic_now; ?>"
                               title="GB">
                            <?php echo $stats->traffic_percent; ?>
                        </meter>
                        <span class="statistics-max"><?php echo traffic_format( $stats->traffic_max ); ?></span>
                    </div>
                </section>

                <section class="box">
                    <h4><? _e( 'HTTP Requests', 'baqend' ) ?></h4>
                    <span class="statistics-number"><?php echo requests_format( $stats->requests_now ); ?></span>

                    <div class="box-meter">
                        <meter class="baqend" min="0" max="<?php echo $stats->requests_max; ?>" optimum="0"
                               low="<?php echo round( $stats->requests_max * 3 / 4 ); ?>"
                               high="<?php echo $stats->requests_max - 1; ?>"
                               value="<?php echo $stats->requests_now; ?>" title="Requests">
                            <?php echo $stats->requests_percent; ?>
                        </meter>
                        <span class="statistics-max"><?php echo requests_format( $stats->requests_max ); ?></span>
                    </div>
                </section>
            <?php else: ?>
                <section class="box"></section>
            <?php endif; ?>

            <section class="box">
                <h4><? _e( 'Current Plan', 'baqend' ) ?></h4>
                <?php if ( $stats->is_free_trial() ): ?>
                    <span class="statistics-info">
                        <?php if ( $exceeded ) {
                            echo sprintf( __( 'Your free trial is exceeded. Speed Kit is no longer active.', 'baqend' ) );
                        } else {
                            echo sprintf( __( 'Your free trial will be exceeded after %s days.', 'baqend' ), $stats->trial_duration );
                        } ?>
                    </span>
                <?php elseif ( $stats->is_free() ): ?>
                    <span class="statistics-info">
                        <?php if ( $exceeded ) {
                            echo sprintf( __( 'Your free plan is exceeded. Speed Kit is no longer active.', 'baqend' ) );
                        } else {
                            echo sprintf( __( 'Your free plan will be exceeded after %s requests or %s of traffic.', 'baqend' ), requests_format( $stats->requests_max ), traffic_format( $stats->traffic_max ) );
                        } ?>
                    </span>
                <?php else: ?>
                    <span class="statistics-number"><?php echo currency_format( $stats->price_now ); ?></span>
                <?php endif; ?>

                <div class="box-meter">
                    <?php if ( $stats->is_free_trial() ): ?>
                        <meter class="baqend" min="0" max="<?php echo $stats->trial_duration ?>" optimum="0"
                               low="<?php echo floor($stats->trial_duration * 0.3) ?>"
                               high="<?php echo floor($stats->trial_duration * 0.8) ?>"
                               value="<?php echo $stats->trial_duration - $stats->remaining_days; ?>"
                               title="Days left">
                        </meter>
                        <span
                            class="statistics-max"><?php echo sprintf( __( '%s days', 'baqend' ), $stats->trial_duration - $stats->remaining_days ); ?></span>
                    <?php elseif ( $stats->is_free() ): ?>
                        <meter class="baqend" min="0" max="3" optimum="0" low="1" high="2" value="3" title="€"></meter>
                        <span class="statistics-max"><?php _e( 'free', 'baqend' ); ?></span>
                    <?php elseif ( $stats->is_unlimited() ): ?>
                        <span class="statistics-max"><?php _e( 'unlimited', 'baqend' ); ?></span>
                    <?php else: ?>
                        <meter class="baqend" min="0" max="<?php echo $stats->price_max; ?>" optimum="0"
                               low="<?php echo round( $stats->price_max * 3 / 4 ); ?>"
                               high="<?php echo $stats->price_max - 1; ?>" value="<?php echo $stats->price_now; ?>"
                               title="€">
                            <?php echo $stats->price_percent; ?>
                        </meter>
                        <span class="statistics-max"><?php echo currency_format( $stats->price_max ); ?></span>
                    <?php endif; ?>
                </div>

                <?php if ( $stats->has_next_plan() ): ?>
                    <p>
                        <small>
                            <?php echo sprintf( __( 'Your new plan starts on %s at %s', 'baqend' ), $stats->next_plan_date->format( get_option( 'date_format' ) ), $stats->next_plan_date->format( get_option( 'time_format' ) ) ); ?>
                        </small>
                    </p>
                <?php endif; ?>
            </section>
        </div>
    </div>
<?php endif; ?>
