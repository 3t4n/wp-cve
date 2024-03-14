<?php

// Don't run this file directly.
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

function wyp_plugins_admin_footer(){
    global $hook_suffix;
    if ('plugins.php' == $hook_suffix && !defined('DOING_AJAX')) {
        wyp_add_deactivation_feedback_dialog_box();
    }
}

add_action('admin_footer', 'wyp_plugins_admin_footer');


function wyp_add_deactivation_feedback_dialog_box(){

    // Getting basename
    if(defined("YP_PRO_DIRECTORY")){
        $basename  = 'waspthemes-yellow-pencil/yellow-pencil.php';
        $slug = "yellow-pencil-pro";
    }else{
        $basename  = 'yellow-pencil-visual-theme-customizer/yellow-pencil.php';
        $slug = "yellow-pencil-visual-theme-customizer";
    }

    // Reason Array
    $reasons = array(

        array(
        'id' => 'NO_LONGER_NEED',
        'text' => "I no longer need the plugin",
        'input_type' => '',
        'input_placeholder' => '',
        'notice' => ''
        ),

        array(
        'id' => 'I_FOUND_A_BETTER_PLUGIN',
        'text' => "I found a better plugin",
        'input_type' => 'textfield',
        'input_placeholder' => 'Please share which plugin',
        'notice' => ''
        ),

        array(
        'id' => 'COMPLEX',
        'text' => "I don't know how to use it",
        'input_type' => '',
        'input_placeholder' => '',
        'notice' => "Please take a look at the <a target='_blank' href='https://yellowpencil.waspthemes.com/documentation/'>plugin documentation</a>."
        ),

        array(
        'id' => 'BROKE_MY_WEBSITE',
        'text' => "The plugin broke my site",
        'input_type' => '',
        'input_placeholder' => '',
        'notice' => "No worry!, you can reset all changes from <a href='".admin_url('themes.php?page=yellow-pencil-changes')."'>this page</a>."
        ),

        array(
        'id' => 'NOT_WORKING',
        'text' => "I couldn't get the plugin to work",
        'input_type' => '',
        'input_placeholder' => '',
        'notice' => 'Make sure you clean the browser cache after making changes also If you are using a cache plugin, don\'t forget to clear the cache from the cache plugin.'
        ),

        array(
        'id' => 'TEMPORARY_DEACTIVATION',
        'text' => "It's a temporary deactivation",
        'input_type' => '',
        'input_placeholder' => '',
        'notice' => ''
        ),

        array(
        'id' => 'OTHER',
        'text' => 'Other',
        'input_type' => 'textarea',
        'input_placeholder' => 'Please share the reason',
        'notice' => ''
        )

    );

    // HTML Empty List
    $reasons_list_items_html = '';

    // Loop
    foreach ($reasons as $reason) {

        $list_item_classes = 'wyp-feedback-modal-reason' . (!empty($reason['input_type']) ? ' has-input' : '' ) . (!empty($reason['notice']) ? ' has-notice' : '' );

        $reasons_list_items_html .= '<li class="'.$list_item_classes.'" data-input-type="'.$reason['input_type'].'" data-input-placeholder="'.$reason['input_placeholder'].'" data-notice="'.$reason['notice'].'">
        <label>
            <span>
            <input type="radio" name="selected-reason" value="'.$reason['id'].'"/>
            </span>
            <span>'.$reason['text'].'</span>
        </label>
        </li>';

    }

    ?>

    <script type="text/javascript">
        (function ($) {
        var modalHtml =
            '<div class="wyp-feedback-modal wyp-feedback-modal-deactivation-feedback" data-site="<?php $siteURL = wp_parse_url(get_home_url()); echo $siteURL["host"]; ?>" data-code="<?php echo get_option("yp_purchase_code"); ?>">'
            + ' <div class="wyp-feedback-modal-dialog">'
            + '     <div class="wyp-feedback-modal-header">'
            + '     <h2>Quick Feedback</h2>'
            + '     </div>'
            + '     <div class="wyp-feedback-modal-body">'
            + '     <p>If you have a moment, please share why you are deactivating YellowPencil:</p>'
            + '     <div class="wyp-feedback-modal-panel active">'
            +          '<ul>' + <?php echo wp_json_encode($reasons_list_items_html); ?> + '</ul>'
            + '     </div>'
            + '     </div>'
            + '     <div class="wyp-feedback-modal-footer">'
            + '     <a href="#" class="wyp-feedback-modal-button-deactivate">Submit & Deactivate</a>'
            + '     <a href="#" class="wyp-feedback-modal-button-skip">Skip & Deactivate</a>'
            + '     </div>'
            + ' </div>'
            + '</div>',
            wyp_modal = $(modalHtml),
            wyp_deactivate_link = $('#the-list .active[data-plugin="<?php echo $basename; ?>"] .deactivate a'),
            selectedReasonID = false;

        // stop of no deactivate link
        if(wyp_deactivate_link.length == 0){
            return false;
        }

        // Append modal
        wyp_modal.appendTo($('body'));

        wyp_setup_feedback_modal_events();

        function wyp_setup_feedback_modal_events() {

            // Deactivate click
            wyp_deactivate_link.click(function (evt){

                evt.preventDefault();
                /* Display the dialog box.*/
                wyp_feedback_reset();
                wyp_modal.addClass('active');
                $('body').addClass('has-wyp-feedback-modal');

            });

            // Input update
            wyp_modal.on('input propertychange', '.wyp-feedback-modal-reason-input input', function () {

                if (!wyp_get_feedback_reason('OTHER')) {
                    return;
                }

            });

            // blur input
            wyp_modal.on('blur', '.wyp-feedback-modal-reason-input input', function () {

                var wyp_user_reason = $(this);
                setTimeout(function () {

                    if (!wyp_get_feedback_reason('OTHER')) {
                        return;
                    }

                }, 150);

            });

            // Button click
            wyp_modal.on('click', '.wyp-feedback-modal-footer a', function (evt) {

                evt.preventDefault();

                // Disable
                if ($(this).hasClass('disabled')) {
                    return;
                }

                // Variables
                var _parent = $(this).parents('.wyp-feedback-modal:first'),
                    _this = $(this);

                // This allows?
                if (_this.hasClass('allow-deactivate')) {

                    var wyp_feedback_radio = wyp_modal.find('input[type="radio"]:checked');

                    /* If no selected reason, just deactivate the plugin. */
                    if (0 === wyp_feedback_radio.length) {

                        <?php if(get_option("yp_purchase_code") != FALSE){ ?>

                            // Start processing
                            _parent.find('.wyp-feedback-modal-footer a').addClass('disabled');
                            _parent.find('.wyp-feedback-modal-footer .wyp-feedback-modal-button-deactivate').text('Processing' + '...');

                            // deactivate
                            $.get("https://waspthemes.com/yellow-pencil/auto-update/", {
                                action: "deactivate",
                                site: wyp_modal.attr("data-site"),
                                purchase_code: wyp_modal.attr("data-code")
                            }).fail(function(){
                                alert("Something went wrong. Please try again.");
                            }).success(function(){
                                window.location.href = wyp_deactivate_link.attr('href');
                                return;
                            });

                        <?php }else{ ?>

                            window.location.href = wyp_deactivate_link.attr('href');
                            return;

                        <?php } ?>

                    }

                    // Variables
                    var selected_reason = wyp_feedback_radio.parents('li:first'),
                        input = selected_reason.find('textarea, input[type="text"]'),
                        userReason = (0 !== input.length) ? input.val().trim() : '';

                    // Stop here
                    if (wyp_get_feedback_reason('OTHER') && '' === userReason) {
                        return;
                    }

                    // Send data
                    $.ajax({

                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            'action': 'wyp_submit_uninstall_reason_action',
                            'plugin': '<?php echo $basename; ?>',
                            'reason_id': wyp_feedback_radio.val(),
                            'reason_info': userReason,
                            'wyp_feedback_ajax_nonce': '<?php echo wp_create_nonce('wyp_feedback_ajax_nonce'); ?>'
                        },

                        // Start processing
                        beforeSend: function () {
                            _parent.find('.wyp-feedback-modal-footer a').addClass('disabled');
                            _parent.find('.wyp-feedback-modal-footer .wyp-feedback-modal-button-deactivate').text('Processing' + '...');
                        },

                        /* Do not show the dialog box, deactivate the plugin. */
                        complete: function (message) {

                            <?php if(get_option("yp_purchase_code") != FALSE){ ?>

                                // deactivate
                                $.get("https://waspthemes.com/yellow-pencil/auto-update/", {
                                    action: "deactivate",
                                    site: wyp_modal.attr("data-site"),
                                    purchase_code: wyp_modal.attr("data-code")
                                }).fail(function(){
                                    alert("Something went wrong. Please try again.");
                                }).success(function(){
                                    window.location.href = wyp_deactivate_link.attr('href');
                                    return;
                                });

                            <?php }else{ ?>

                                window.location.href = wyp_deactivate_link.attr('href');
                                return;

                            <?php } ?>

                        }

                    });

                // Deactive button
                } else if (_this.hasClass('wyp-feedback-modal-button-deactivate')) {

                    /* Change the Deactivate button's text and show the reasons panel. */
                    _parent.find('.wyp-feedback-modal-button-deactivate').addClass('allow-deactivate');
                    wyp_show_feedback_modal();

                // Skip button
                } else if (_this.hasClass('wyp-feedback-modal-button-skip')) {

                    <?php if(get_option("yp_purchase_code") != FALSE){ ?>

                        _this.append("<i class='wyp-deactivating-icon'></i>");

                        // deactivate
                        $.get("https://waspthemes.com/yellow-pencil/auto-update/", {
                            action: "deactivate",
                            site: wyp_modal.attr("data-site"),
                            purchase_code: wyp_modal.attr("data-code")
                        }).fail(function(){
                            alert("Something went wrong. Please try again.");
                        }).success(function(){
                            window.location.href = wyp_deactivate_link.attr('href');
                            return;
                        });

                    <?php }else{ ?>

                        window.location.href = wyp_deactivate_link.attr('href');
                        return;

                    <?php } ?>

                }

            });


            // Reason radio click
            wyp_modal.on('click', 'input[type="radio"]', function () {

                // This
                var wyp_selected_reason_option = $(this);

                /* If the selection has not changed, do not proceed. */
                if (selectedReasonID === wyp_selected_reason_option.val()){
                    return;
                }

                // Getting data
                selectedReasonID = wyp_selected_reason_option.val();
                var _parent = $(this).parents('li:first');

                wyp_modal.find('.wyp-feedback-modal-reason-input').remove();
                wyp_modal.find('.wyp-feedback-modal-reason-notice').remove();
                wyp_modal.find('.wyp-feedback-modal-button-deactivate').removeAttr( 'disabled' );

                if (_parent.hasClass('has-input')) {

                    var reasonInputHtml = '<div class="wyp-feedback-modal-reason-input">' + (('textfield' === _parent.data('input-type')) ? '<input type="text" />' : '<textarea rows="2"></textarea>') + '</div>';
                    _parent.append($(reasonInputHtml));
                    _parent.find('input, textarea').attr('placeholder', _parent.data('input-placeholder')).focus();

                }

                if (_parent.hasClass('has-notice')) {

                    var reasonInputHtml = '<div class="wyp-feedback-modal-reason-notice"><p>' + _parent.data('notice') + '</p></div>';
                    _parent.append($(reasonInputHtml));

                }

            });

            /* If the user has clicked outside the window, cancel it. */
            wyp_modal.on('click', function (evt) {

                var target = $(evt.target);

                /* If the user has clicked anywhere in the modal dialog, just return. */
                if (target.hasClass('wyp-feedback-modal-body') || target.hasClass('wyp-feedback-modal-footer') || target.hasClass("wyp-feedback-modal-header")) {
                    return;
                }

                /* If the user has not clicked the close button and the clicked element is inside the modal dialog, just return. */
                if ((target.parents('.wyp-feedback-modal-body').length > 0 || target.parents('.wyp-feedback-modal-footer').length > 0) || target.parents('.wyp-feedback-modal-header').length > 0) {
                    return;
                }

                /* Close the modal dialog */
                wyp_modal.removeClass('active');
                $('body').removeClass('has-wyp-feedback-modal');
                return false;

            });

        }

        /* Get the selected radio input element.*/
        function wyp_get_feedback_reason(reasonID) {
            return (reasonID == wyp_modal.find('input[type="radio"]:checked').val());
        }

        function wyp_feedback_reset() {

            selectedReasonID = false;

            /* Uncheck all radio buttons.*/
            wyp_modal.find('input[type="radio"]').prop('checked', false);

            /* Remove all input fields ( textfield, textarea ).*/
            wyp_modal.find('.wyp-feedback-modal-reason-input').remove();
            wyp_modal.find('.wyp-feedback-modal-reason-notice').remove();
            var deactivate_button = wyp_modal.find('.wyp-feedback-modal-button-deactivate');
            deactivate_button.addClass('allow-deactivate');
            wyp_show_feedback_modal();

        }

        function wyp_show_feedback_modal() {
            wyp_modal.find('.wyp-feedback-modal-panel').addClass('active');
        }

        })(jQuery);
    </script>
    <?php
}


// Called after the user has submitted his reason for deactivating the plugin.
function wyp_submit_uninstall_reason_action(){

    // checks if use can delete plugins
    if(!current_user_can("delete_plugins")){
        wp_die("No authorization");
    }

    // Verify nonce
    if (!wp_verify_nonce($_REQUEST['wyp_feedback_ajax_nonce'], 'wyp_feedback_ajax_nonce')) {
        wp_die('Security issue');
    }

    $reason_id = isset($_REQUEST['reason_id']) ? stripcslashes(esc_html($_REQUEST['reason_id'])) : '';
    $basename  = isset($_REQUEST['plugin']) ? stripcslashes(esc_html($_REQUEST['plugin'])) : '';
    if (empty($reason_id) || empty($basename)) {
        exit;
    }

    $reason_info = isset($_REQUEST['reason_info']) ? stripcslashes(esc_html($_REQUEST['reason_info'])) : '';
    if (!empty($reason_info)) {
        $reason_info = substr($reason_info, 0, 255);
    }

    $theme_name = get_stylesheet();

    // Plugin options
    $options = array(
        'product' => $basename,
        'reason_id' => $reason_id,
        'reason_info' => $reason_info,
        'theme_name' => $theme_name
    );

    /* send data */
    $raw_response = wp_remote_post('https://waspthemes.com/yellow-pencil/feedback/', array(
        'method' => 'POST',
        'body' => $options,
        'timeout' => 15,
    ));

    // Success
    if (!is_wp_error($raw_response) && 200 == wp_remote_retrieve_response_code($raw_response)) {
        echo 'done';

    // Fail
    } else {
        $error_msg = $raw_response->get_error_code().': '.$raw_response->get_error_message();
        echo $error_msg;
    }

    exit;

}

add_action('wp_ajax_wyp_submit_uninstall_reason_action', 'wyp_submit_uninstall_reason_action');
