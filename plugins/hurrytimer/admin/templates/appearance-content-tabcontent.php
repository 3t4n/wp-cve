<?php namespace Hurrytimer; ?>
<div id="hurrytimer-styling-content-tab" class="hurrytimer-subtabcontent active">
        <div class="hurrytimer-style-control-group hurrytimer-accordion-item active">
            <div class="hurrytimer-style-control-title hurrytimer-accordion-heading">
                <h3>Visibility</h3>
            </div>
            <div class="hurrytimer-style-control-fields hurrytimer-accordion-content">
                <!-- Fiels -->
                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Show days', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <?php Utils\Form::toggle(
                            'days_visibility',
                            $campaign->daysVisibility,
                            'hurrytimer-days-visibility'
                        ); ?>
                    </div>
                </div>
                <!-- Field -->
                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Show hours', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <?php Utils\Form::toggle(
                            'hours_visibility',
                            $campaign->hoursVisibility,
                            'hurrytimer-hours-visibility'
                        ); ?>
                    </div>
                </div>

                <!-- field -->
                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Show minutes', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <?php Utils\Form::toggle('minutes_visibility',
                         $campaign->minutesVisibility,
                            'hurrytimer-minutes-visibility'); ?>
                    </div>
                </div>

                <!-- field -->
                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Show seconds', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <?php Utils\Form::toggle('seconds_visibility',
                             $campaign->secondsVisibility,
                            'hurrytimer-seconds-visibility'); ?>
                    </div>
                </div>


                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label" for="hurrytimer-label-visibility">
                        <?php _e('Show labels', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <?php Utils\Form::toggle('label_visibility',
                             $campaign->labelVisibility,
                             'hurrytimer-label-visibility'); ?>
                    </div>
                </div>
                <div class="hurrytimer-style-control-field ">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Show block separator', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <?php Utils\Form::toggle('block_separator_visibility',
                            $campaign->blockSeparatorVisibility, 'yes',
                            'hurrytimer-block-separator-visibility'); ?>

                    </div>
                </div>
               

               
            </div>
        </div>
        <div class="hurrytimer-style-control-group hurrytimer-accordion-item">
            <div class="hurrytimer-style-control-title hurrytimer-accordion-heading">
                <h3>Custom Timer Labels</h3>
            </div>
            <div class="hurrytimer-style-control-fields hurrytimer-accordion-content">
                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Days', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <input
                            type="text"
                            name="labels[days]"
                            value="<?php echo $campaign->labels['days'] ?>">
                    </div>
                </div>
                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Hours', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <input
                            type="text"
                            name="labels[hours]"
                            value="<?php echo $campaign->labels['hours'] ?>">
                    </div>
                </div>
                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Minutes', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <input
                            type="text"
                            name="labels[minutes]"
                            value="<?php echo $campaign->labels['minutes'] ?>">
                    </div>
                </div>
                <div class="hurrytimer-style-control-field">
                    <div class="hurrytimer-style-control-label">
                        <?php _e('Seconds', "hurrytimer") ?>
                    </div>
                    <div class="hurrytimer-style-control-input">
                        <input
                            type="text"
                            name="labels[seconds]"
                            value="<?php echo $campaign->labels['seconds'] ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>