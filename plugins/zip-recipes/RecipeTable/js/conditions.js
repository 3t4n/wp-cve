jQuery(document).ready(function ($) {

    check_conditions();
$("input").change(function (e) {
    check_conditions();
});

$("select").change(function (e) {
    check_conditions();
});

$("textarea").change(function (e) {
    check_conditions();
});

/*conditional fields*/
function check_conditions() {
    var value;
    var showIfConditionMet = true;

    $(".condition-check").each(function (e) {
        var question = 'zrdn_' + $(this).data("condition-question");
        var condition_type = 'AND';

        if (question == undefined) return;

        var condition_answer = $(this).data("condition-answer");

        //remove required attribute of child, and set a class.
        var input = $(this).find('input[type=checkbox]');
        if (!input.length) {
            input = $(this).find('input');
        }
        if (!input.length) {
            input = $(this).find('textarea');
        }
        if (!input.length) {
            input = $(this).find('select');
        }

        if (input.length && input[0].hasAttribute('required')) {
            input.addClass('is-required');
        }

        //cast into string
        condition_answer += "";

        if (condition_answer.indexOf('NOT ') !== -1) {
            condition_answer = condition_answer.replace('NOT ', '');
            showIfConditionMet = false;
        } else {
            showIfConditionMet = true;
        }
        var condition_answers = [];
        if (condition_answer.indexOf(' OR ') !== -1) {
            condition_answers = condition_answer.split(' OR ');
            condition_type = 'OR';
        } else {
            condition_answers = [condition_answer];
        }

        var container = $(this);
        var conditionMet = false;
        condition_answers.forEach(function (condition_answer) {
            value = get_input_value(question);

            if ($('select[name=' + question + ']').length) {
                value = Array($('select[name=' + question + ']').val());
            }

            if ($("input[name='" + question + "[" + condition_answer + "]" + "']").length){
                if ($("input[name='" + question + "[" + condition_answer + "]" + "']").is(':checked')) {
                    conditionMet = true;
                    value = [];
                } else {
                    conditionMet = false;
                    value = [];
                }

                if ( $(this).prop('disabled') ){
                    conditionMet = false;
                }
            }

            if (showIfConditionMet) {

                //check if the index of the value is the condition, or, if the value is the condition
                if (conditionMet || value.indexOf(condition_answer) != -1 || (value == condition_answer)) {

                    container.removeClass("hidden");
                    //remove required attribute of child, and set a class.
                    if (input.hasClass('is-required')) input.prop('required', true);
                    //prevent further checks if it's an or statement
                    if (condition_type === 'OR') conditionMet = true;

                } else {
                    container.addClass("hidden");
                    if (input.hasClass('is-required')) input.prop('required', false);
                    //prevent further checks if it's an or statement
                    if (condition_type === 'OR') return;
                }
            } else {

                if (conditionMet || value.indexOf(condition_answer) != -1 || (value == condition_answer)) {
                    container.addClass("hidden");
                    if (input.hasClass('is-required')) input.prop('required', false);

                } else {
                    container.removeClass("hidden");
                    if (input.hasClass('is-required')) input.prop('required', true);
                }
            }
        });

    });
}


/*
get checkbox values, array proof.
*/

function get_input_value(fieldName) {

    if ($('input[name^=' + fieldName + ']').attr('type') == 'text') {
        return $('input[name^=' + fieldName + ']').val();
    } else {
        var checked_boxes = [];
        $('input[name^=' + fieldName + ']:checked').each(function () {
            checked_boxes[checked_boxes.length] = $(this).val();
        });
        return checked_boxes;
    }
}

});