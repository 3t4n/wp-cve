/********************************************************************
 * @plugin     ModernPolls
 * @file       resources/asstes/js/modern-polls-backend.js
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

var global_poll_id = 0;
var global_poll_aid = 0;
var global_poll_aid_votes = 0;
var count_poll_answer_new = 0;
var count_poll_answer = 3;

jQuery(document).ready(function () {

    /* list */


    jQuery('.mpp-add_answer').on('click', function (event) {
        event.preventDefault();
        mpp_addAnswer();
    });

    jQuery(document).on('click', '.mpp-remove_answer', function (event) {
        event.preventDefault();

        if (jQuery('#mpp_answers .mpp-remove_answer').length < 2) {

        } else {

            var myID = jQuery(this).data('id');
            jQuery(this).closest('.mpp-answer_row').remove();
            if (jQuery('#mpp-answer_error_' + myID).length > 0) {
                jQuery('#mpp-answer_error_' + myID).remove();
            }
            mpp_reorderAnswers();
        }
    });

    jQuery(document).on('click', '.mpp-postbox_title', function (event) {
        jQuery(this).next('.mpp-postbox_inside').slideToggle();
    });

    jQuery(document).on('change', '#mpp-expire', function (event) {
        jQuery('#mpp-expireTime').toggle();
    });

    jQuery(document).on('change', '#showResultNever', function (event) {
        if (jQuery(this).prop('checked')) {
            jQuery('#showResultBefore').prop('disabled', true);
            jQuery('#showResultBefore').prop('checked', false);
            jQuery('#showResultAfter').prop('disabled', true);
            jQuery('#showResultAfter').prop('checked', false);
        } else {
            jQuery('#showResultBefore').prop('disabled', false);
            jQuery('#showResultAfter').prop('disabled', false);
        }
    });

    jQuery(document).on('change', '#showResultBefore', function (event) {
        if (jQuery(this).prop('checked')) {
            jQuery('#showResultAfter').prop('disabled', true);
            jQuery('#showResultAfter').prop('checked', true);
        } else {
            jQuery('#showResultAfter').prop('disabled', false);
        }
    });

    jQuery(document).on('change', '#mpp-multipleSelect', function (event) {
        if (parseInt(jQuery('#mpp-multipleSelect').val()) == 1) {
            jQuery('#mpp-multiple').attr('disabled', false);
        } else {
            jQuery('#mpp-multiple').val(1);
            jQuery('#mpp-multiple').attr('disabled', true);
        }
    });

    jQuery(document).on('click', '.mpp-nav_link', function (event) {
        jQuery('.mpp-active').each(function () {
            jQuery(this).removeClass('mpp-active');
        });
        jQuery('.mpp-tab_pane_fade.mpp-tab_pane_show').removeClass('mpp-tab_pane_show');
        jQuery(this).addClass('mpp-active');
        jQuery('#mpp-' + jQuery(this).data('href')).addClass('mpp-active mpp-tab_pane_show');
        if (jQuery(this).data('href') == 'pie_chart') {
            jQuery(document).trigger('drawPieChart');
        }
    });
});

function mpp_reorderAnswers() {
    var multiple = jQuery('#mpp-multiple');
    var selected = multiple.val();
    var previous_size = jQuery('> option', multiple).size();

    //console.log('prev size: '+previous_size);
    multiple.empty();

    jQuery('#mpp_answers .mpp-answer_id').each(function (i) {
        jQuery(this).text(i + 1);
        jQuery(multiple).append('<option value="' + (i + 1) + '">' + (i + 1) + '</option>');
    })

    if (selected > 1) {
        var current_size = jQuery('> option', multiple).size();
        if (selected <= current_size)
            jQuery('> option', multiple).eq(selected - 1).attr('selected', 'selected');
        else if (selected == previous_size)
            jQuery('> option', multiple).eq(current_size - 1).attr('selected', 'selected');
    }
}

function mpp_addAnswer() {
    var templateAnswer = jQuery('#templateAnswer').html();

    var answersNum = jQuery('.mpp-answer_row').length;

    var newAnswer = jQuery(templateAnswer);
    newAnswer.attr('id', 'mpp-answer_' + answersNum);
    newAnswer.find('.mpp-answer_id').text(answersNum);

    jQuery('#mpp_answers').append(newAnswer);
    mpp_reorderAnswers();
}


// Calculate Total Votes
function check_totalvotes() {
    temp_vote_count = 0;
    jQuery(document).ready(function (jQuery) {
        jQuery("#poll_answers tr td input[size=4]").each(function (i) {
            if (isNaN(jQuery(this).val())) {
                temp_vote_count += 0;
            } else {
                temp_vote_count += parseInt(jQuery(this).val());
            }
        });
        jQuery('#pollq_totalvotes').val(temp_vote_count);
    });
}


// Delete Poll
function delete_poll(poll_id, poll_confirm, nonce) {
    delete_poll_confirm = confirm(poll_confirm);
    if (delete_poll_confirm) {
        global_poll_id = poll_id;
        jQuery(document).ready(function (jQuery) {
            jQuery.ajax({
                type: 'POST',
                url: modernPollsBackendL10n.admin_ajax_url,
                data: 'do=' + modernPollsBackendL10n.text_delete_poll + '&pollq_id=' + poll_id + '&action=polls-admin&_ajax_nonce=' + nonce,
                cache: false,
                success: function (data) {
                    jQuery('#message').html(data);
                    jQuery('#message').show();
                    jQuery('#poll-' + global_poll_id).remove();
                }
            });
        });
    }
}

// Delete Poll Logs
function delete_poll_logs(poll_confirm, nonce) {
    delete_poll_logs_confirm = confirm(poll_confirm);
    if (delete_poll_logs_confirm) {
        jQuery(document).ready(function (jQuery) {
            if (jQuery('#delete_logs_yes').is(':checked')) {
                jQuery.ajax({
                    type: 'POST',
                    url: modernPollsBackendL10n.admin_ajax_url,
                    data: 'do=' + modernPollsBackendL10n.text_delete_all_logs + '&delete_logs_yes=yes&action=polls-admin&_ajax_nonce=' + nonce,
                    cache: false,
                    success: function (data) {
                        jQuery('#message').html(data);
                        jQuery('#message').show();
                        jQuery('#poll_logs').html(modernPollsBackendL10n.text_no_poll_logs);
                    }
                });
            } else {
                alert(modernPollsBackendL10n.text_checkbox_delete_all_logs);
            }
        });
    }
}

// Delete Individual Poll Logs
function delete_this_poll_logs(poll_id, poll_confirm, nonce) {
    delete_poll_logs_confirm = confirm(poll_confirm);
    if (delete_poll_logs_confirm) {
        jQuery(document).ready(function (jQuery) {
            if (jQuery('#delete_logs_yes').is(':checked')) {
                global_poll_id = poll_id;
                jQuery.ajax({
                    type: 'POST',
                    url: modernPollsBackendL10n.admin_ajax_url,
                    data: 'do=' + modernPollsBackendL10n.text_delete_poll_logs + '&pollq_id=' + poll_id + '&delete_logs_yes=yes&action=polls-admin&_ajax_nonce=' + nonce,
                    cache: false,
                    success: function (data) {
                        jQuery('#message').html(data);
                        jQuery('#message').show();
                        jQuery('#poll_logs').html(modernPollsBackendL10n.text_no_poll_logs);
                        jQuery('#poll_logs_display').hide();
                        jQuery('#poll_logs_display_none').show();
                    }
                });
            } else {
                alert(modernPollsBackendL10n.text_checkbox_delete_poll_logs);
            }
        });
    }
}

// Delete Poll Answer
function delete_poll_ans(poll_id, poll_aid, poll_aid_vote, poll_confirm, nonce) {
    delete_poll_ans_confirm = confirm(poll_confirm);
    if (delete_poll_ans_confirm) {
        global_poll_id = poll_id;
        global_poll_aid = poll_aid;
        global_poll_aid_votes = poll_aid_vote;
        temp_vote_count = 0;
        jQuery(document).ready(function (jQuery) {
            jQuery.ajax({
                type: 'POST',
                url: modernPollsBackendL10n.admin_ajax_url,
                data: 'do=' + modernPollsBackendL10n.text_delete_poll_ans + '&pollq_id=' + poll_id + '&polla_aid=' + poll_aid + '&action=polls-admin&_ajax_nonce=' + nonce,
                cache: false,
                success: function (data) {
                    jQuery('#message').html(data);
                    jQuery('#message').show();
                    jQuery('#poll_total_votes').html((parseInt(jQuery('#poll_total_votes').html()) - parseInt(global_poll_aid_votes)));
                    jQuery('#pollq_totalvotes').val(temp_vote_count);
                    jQuery('#poll-answer-' + global_poll_aid).remove();
                    check_totalvotes();
                    mpp_reorderAnswers();
                }
            });
        });
    }
}

// Open Poll
function opening_poll(poll_id, poll_confirm, nonce) {
    open_poll_confirm = confirm(poll_confirm);
    if (open_poll_confirm) {
        global_poll_id = poll_id;
        jQuery(document).ready(function (jQuery) {
            jQuery.ajax({
                type: 'POST',
                url: modernPollsBackendL10n.admin_ajax_url,
                data: 'do=' + modernPollsBackendL10n.text_open_poll + '&pollq_id=' + poll_id + '&action=polls-admin&_ajax_nonce=' + nonce,
                cache: false,
                success: function (data) {
                    jQuery('#message').html(data);
                    jQuery('#message').show();
                    jQuery('#open_poll').hide();
                    jQuery('#close_poll').show();
                }
            });
        });
    }
}

// Close Poll
function closing_poll(poll_id, poll_confirm, nonce) {
    close_poll_confirm = confirm(poll_confirm);
    if (close_poll_confirm) {
        global_poll_id = poll_id;
        jQuery(document).ready(function (jQuery) {
            jQuery.ajax({
                type: 'POST',
                url: modernPollsBackendL10n.admin_ajax_url,
                data: 'do=' + modernPollsBackendL10n.text_close_poll + '&pollq_id=' + poll_id + '&action=polls-admin&_ajax_nonce=' + nonce,
                cache: false,
                success: function (data) {
                    jQuery('#message').html(data);
                    jQuery('#message').show();
                    jQuery('#open_poll').show();
                    jQuery('#close_poll').hide();
                }
            });
        });
    }
}


// Add Poll's Answer In Edit Poll Page
function add_poll_answer_edit() {
    jQuery(document).ready(function (jQuery) {
        jQuery('#poll_answers').append('<tr id="poll-answer-new-' + count_poll_answer_new + '"><th width="20%" scope="row" valign="top"></th><td width="60%"><input type="text" size="50" maxlength="200" name="polla_answers_new[]" />&nbsp;&nbsp;&nbsp;<input type="button" value="' + modernPollsBackendL10n.text_remove_poll_answer + '" onclick="remove_poll_answer_edit(' + count_poll_answer_new + ');" class="button" /></td><td width="20%" align="' + modernPollsBackendL10n.text_direction + '">0 <input type="text" size="4" name="polla_answers_new_votes[]" value="0" onblur="check_totalvotes();" /></td></tr>');
        count_poll_answer_new++;
        mpp_reorderAnswers();
    });
}

// Remove Poll's Answer In Edit Poll Page
function remove_poll_answer_edit(poll_answer_new_id) {
    jQuery(document).ready(function (jQuery) {
        jQuery('#poll-answer-new-' + poll_answer_new_id).remove();
        check_totalvotes();
        mpp_reorderAnswers();
    });
}

// Check Poll Whether It is Multiple Poll Answer
function check_pollq_multiple() {
    jQuery(document).ready(function (jQuery) {
        if (parseInt(jQuery('#pollq_multiple_yes').val()) == 1) {
            jQuery('#pollq_multiple').attr('disabled', false);
        } else {
            jQuery('#pollq_multiple').val(1);
            jQuery('#pollq_multiple').attr('disabled', true);
        }
    });
}

// Show/Hide Poll's Timestamp
function check_polltimestamp() {
    jQuery(document).ready(function (jQuery) {
        if (jQuery('#edit_polltimestamp').is(':checked')) {
            jQuery('#pollq_timestamp').show();
        } else {
            jQuery('#pollq_timestamp').hide();
        }
    });
}

// Show/Hide  Poll's Expiry Date
function check_pollexpiry() {
    jQuery(document).ready(function (jQuery) {
        if (jQuery('#pollq_expiry_no').is(':checked')) {
            jQuery('#pollq_expiry').hide();
        } else {
            jQuery('#pollq_expiry').show();
        }
    });
}