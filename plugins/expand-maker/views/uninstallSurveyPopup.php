<div id="expander-deactivation-survey-popup-container" class="expander-deactivation-survey-popup-container is-dismissible" style="display: none;">
    <div class="expander-deactivation-survey-popup-overlay"></div>

    <div class="expander-deactivation-survey-popup-tbl">
        <div class="expander-deactivation-survey-popup-cel">
            <div class="expander-deactivation-survey-popup-content">

                <div class="expander-deactivation-survey-header">
                    <span class="expander-uninstall-header-title">Quick Feedback</span>
                </div>
                <div class="expander-deactivation-survey-content">
                    <form class="expander-deactivation-survey-content-form">
                    <p class="expander-deactivation-survey-content-p">If you have a moment, please share why you are deactivating Read more:</p>
                    <div class="expander-deactivation-survey-choises-wrapper">
                        
                        <div class="expander-deactivate-feedback-dialog-input-wrapper">
                            <input id="expander-deactivate-feedback-no_longer_needed" class="expander-deactivate-feedback-dialog-input" type="radio" name="expander_reason_key" value="no_longer_needed"><label for="expander-deactivate-feedback-no_longer_needed" class="expander-deactivate-feedback-dialog-label">I no longer need the plugin</label></div>
                        <div class="expander-deactivate-feedback-dialog-input-wrapper">
                            <input id="expander-deactivate-feedback-found_a_better_plugin" class="expander-deactivate-feedback-dialog-input" type="radio" name="expander_reason_key" value="found_a_better_plugin">
                            <label for="expander-deactivate-feedback-found_a_better_plugin" class="expander-deactivate-feedback-dialog-label">I found a better plugin</label>
                            <input class="expander-feedback-text expander-survey-sub-choice" type="text" name="expander_reason_found_a_better_plugin" placeholder="Please share which plugin">
                        </div>
                        <div class="expander-deactivate-feedback-dialog-input-wrapper">
                            <input id="expander-deactivate-feedback-couldnt_get_the_plugin_to_work" class="expander-deactivate-feedback-dialog-input" type="radio" name="expander_reason_key" value="couldnt_get_the_plugin_to_work">
                            <label for="expander-deactivate-feedback-couldnt_get_the_plugin_to_work" class="expander-deactivate-feedback-dialog-label">I couldn't get the plugin to work</label>
                            <div class="expander-feedback-text expander-survey-sub-choice">
                                <?php _e('Write Support here'); ?>
                                <a href="https://wordpress.org/support/plugin/expand-maker/" target="_blank">
                                    <button type="button" id="yrm-report-problem-button" class="yrm-deactivate-button-red pull-right">
                                        <i class="glyphicon glyphicon-alert"></i>Report issue
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="expander-deactivate-feedback-dialog-input-wrapper">
                            <input id="expander-deactivate-feedback-temporary_deactivation" class="expander-deactivate-feedback-dialog-input" type="radio" name="expander_reason_key" value="temporary_deactivation">
                            <label for="expander-deactivate-feedback-temporary_deactivation" class="expander-deactivate-feedback-dialog-label">It's a temporary deactivation</label>
                        </div>
                        <div class="expander-deactivate-feedback-dialog-input-wrapper">
                            <input id="expander-deactivate-feedback-other" class="expander-deactivate-feedback-dialog-input" type="radio" name="expander_reason_key" value="other">
                            <label for="expander-deactivate-feedback-other" class="expander-deactivate-feedback-dialog-label">Other</label>
                            <input class="expander-feedback-text expander-survey-sub-choice" type="text" name="expander_reason_other" placeholder="Please share the reason">
                        </div>
                    </div>
                </div>
                <div class="expander-deactivation-survey-footer">
                    <div class="dialog-buttons-wrapper dialog-lightbox-buttons-wrapper">
                        <button class="expander-survey-btn expander-survey-submit">Submit &amp; Deactivate</button>
                        <button class="expander-survey-btn expander-survey-skip">Skip &amp; Deactivate</button></div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
