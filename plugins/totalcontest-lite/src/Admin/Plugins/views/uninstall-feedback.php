<style type="text/css">
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes modal {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    #<?php echo $product ?>-uninstall-feedback {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: fixed;
        z-index: 999999;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(2px);
    }

    #<?php echo $product ?>-uninstall-feedback.is-open {
        display: flex;
        animation: fadeIn 300ms ease-out forwards;
    }

    #<?php echo $product ?>-uninstall-feedback form {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 500px;
        padding: 30px;
        background: #ffffff;
        box-shadow: 0 1px 9px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        animation: modal 300ms ease-out forwards;
    }

    #<?php echo $product ?>-uninstall-feedback .close {
        position: absolute;
        top: 20px;
        right: 20px;
        line-height: 1;
        background: transparent;
        border: none;
        outline: none;
        font-size: 24px;
        opacity: 0.5;
        cursor: pointer;
    }

    #<?php echo $product ?>-uninstall-feedback .close:hover, #<?php echo $product ?>-uninstall-feedback .close:focus {
        opacity: 1;
    }

    #<?php echo $product ?>-uninstall-feedback h3 {
        margin: 0 0 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #dddddd;
        line-height: 1;
    }

    #<?php echo $product ?>-uninstall-feedback p {
        margin: 0 0 20px;
    }

    #<?php echo $product ?>-uninstall-feedback .choice {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    #<?php echo $product ?>-uninstall-feedback .choice label {
        margin: 0;
        padding: 0;
    }

    #<?php echo $product ?>-uninstall-feedback .choice input {
        margin: 3px 10px 0 0;

    }

    #<?php echo $product ?>-uninstall-feedback .choice input ~ input {
        display: none;
        width: 100%;
        margin-top: 10px;
        margin-left: 25px;
    }

    #<?php echo $product ?>-uninstall-feedback .choice input:checked ~ input {
        display: block;
    }

    #<?php echo $product ?>-uninstall-feedback .footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #dddddd;
    }

    #<?php echo $product ?>-uninstall-feedback .footer .button-primary {
        margin-left: 10px;
    }

    #<?php echo $product ?>-uninstall-feedback .footer .include-email {
        display: flex;
        align-items: flex-start;
        margin-right: auto;
    }

    #<?php echo $product ?>-uninstall-feedback .footer .include-email label small {
        display: block;
        color: #888888;
        font-size: 9px;
    }

    #<?php echo $product ?>-uninstall-feedback .footer .include-email input {
        margin: 2px 10px 0 0;
    }
</style>

<div id="<?php echo $product ?>-uninstall-feedback">
    <form action="">
        <h3>Feedback</h3>
        <p>We’d like to hear why you are going to deactivate our product:</p>
        <button type="button" class="close">&times;</button>
        <div class="choice" tabindex="1">
            <input id="<?php echo $product ?>-cause-01" type="radio" name="feedback[cause]"
                   value="I no longer need the plugin.">
            <label for="<?php echo $product ?>-cause-01">I no longer need the plugin.</label>
        </div>
        <div class="choice" tabindex="2">
            <input id="<?php echo $product ?>-cause-02" type="radio" name="feedback[cause]"
                   value="The plugin broke my website.">
            <label for="<?php echo $product ?>-cause-02">The plugin broke my website.</label>
        </div>
        <div class="choice" tabindex="3">
            <input id="<?php echo $product ?>-cause-03" type="radio" name="feedback[cause]"
                   value="I only needed the plugin for a short period.">
            <label for="<?php echo $product ?>-cause-03">I only needed the plugin for a short period.</label>
        </div>
        <div class="choice" tabindex="4">
            <input id="<?php echo $product ?>-cause-04" type="radio" name="feedback[cause]"
                   value="The plugin suddenly stopped working.">
            <label for="<?php echo $product ?>-cause-04">The plugin suddenly stopped working.</label>
        </div>
        <div class="choice" tabindex="5">
            <input id="<?php echo $product ?>-cause-05" type="radio" name="feedback[cause]"
                   value="I found a better plugin.">
            <label for="<?php echo $product ?>-cause-05">I found a better plugin.</label>
            <input type="text" name="feedback[comment]" placeholder="Plugin name...">
        </div>
        <div class="choice" tabindex="6">
            <input id="<?php echo $product ?>-cause-06" type="radio" name="feedback[cause]"
                   value="It's a temporary deactivation. I'm just debugging an issue.">
            <label for="<?php echo $product ?>-cause-06">It's a temporary deactivation. I'm just debugging an
                issue.</label>
        </div>
        <div class="choice" tabindex="7">
            <input id="<?php echo $product ?>-cause-07" type="radio" name="feedback[cause]" value="Other">
            <label for="<?php echo $product ?>-cause-07">Other</label>
            <input type="text" name="feedback[comment]" placeholder="Reason...">
        </div>

        <div class="footer">
            <div class="include-email">
                <input id="<?php echo $product ?>-include-email" type="checkbox" name="feedback[email]"
                       value="<?php echo esc_attr(get_option('admin_email')); ?>" checked>
                <label for="<?php echo $product ?>-include-email">
                    Include my email
                    <small>It will be used to follow up with you.</small>
                </label>
            </div>
            <button type="button" class="skip button">Skip</button>
            <button type="submit" class="submit button button-primary" disabled>Submit & Deactivate</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var $selector = '#deactivate-<?php echo $product ?>-lite, #deactivate-<?php echo $product ?>-pro, #deactivate-<?php echo $product ?>';
        var $deactivate = jQuery($selector);
        var $modal = jQuery('#<?php echo $product ?>-uninstall-feedback');
        var $form = $modal.find('form');
        var $close = $modal.find('.close');
        var $submit = $modal.find('.submit');
        var $skip = $modal.find('.skip');

        $close.on('click', function () {
            $modal.removeClass('is-open');
        });

        $deactivate.on('click', function () {
            event.preventDefault();
            $modal.addClass('is-open');
        });

        $modal.on('change', 'input[type="radio"]', function () {
            $submit.prop('disabled', false);
        });

        $skip.on('click', function () {
            $deactivate.off('click');
            $deactivate.get(0).click();
        });

        $form.on('submit', function (event) {
            wp.ajax.post(
                jQuery(event.target)
                    .find('input:visible')
                    .serializeArray()
                    .concat({name: 'action', value: 'uninstall_feedback_for_<?php echo $product ?>'}, {name: '_wpnonce', value: '<?php echo esc_js(wp_create_nonce('uninstall')); ?>'})
            );

            event.preventDefault();
            $deactivate.off('click');
            $modal.removeClass('is-open');
            $deactivate.get(0).click();
        });
    });
</script>
