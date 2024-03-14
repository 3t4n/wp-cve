jQuery(document).ready(function ($) {
//    $("#post").submit(function (e) {
//        $('.required').each(function () {
//            if ($(this).val == '-1' || $(this).val == '') { // checks if empty or has a predefined string
//                //insert error handling here. eg $(this).addClass('error');
//                console.log('errorrrrrrrrrr');
//                e.preventDefault(); //stop submit event
//            }
//        });
//        e.preventDefault();
//    });

    $('#timefilter').on('change', function (e) {
        if ($("option:selected", this).val() == 'custom_time') {
            $('#custom-picker').show();
        } else {
            $('#custom-picker').hide();
        }
    });



    if ($('#mycred_leaderboard_lesson_all').prop('checked') == true) {
        $('#mycred_leaderboard_lesson_all').hide();
    } else {
        $('#mycred_leaderboard_lesson_all').show();
    }


    if ($('#leaderboard_associated_course').val()) {
        var course_id = $('#leaderboard_associated_course').val();
        if (course_id != 0) {
            show_lesson_options(course_id);
        }

    }


    $('#leaderboard_associated_course').change(function () {
        var course_id = $(this).val();
        if (course_id != 0) {
            show_lesson_options(course_id);
        } else {
            $('#course_selection').html('');
        }
    });


    function show_lesson_options(course_id) {
        var data = {
            'action': 'mycred_select_a_course',
            'course_id': course_id,
            'leaderboard_id': $('#leaderboard_type').data('id')
        };

        jQuery.post(mycred_learndash.ajax_url, data, function (response) {
            $('#course_selection').html(response);
            if ($('#leaderboard_based_on_lessons input[type=radio]:checked').val()) {
                lessons_specific_options($('#leaderboard_based_on_lessons input[type=radio]:checked').val());
            }

            $('#leaderboard_based_on_lessons input[type=radio]').click(function () {
                lessons_specific_options($(this).val());
            });

        });
    }


    function lessons_specific_options(checked_val) {
        if (checked_val == 'lesson_category') {
            $('#leaderboard_based_category_lesson').css("display", "flex");
            $('#leaderboard_based_lessons').css("display", "none");
            select_all_options('#leaderboard_based_category_lesson', 'lessons');

        } else if (checked_val == 'specific_lesson') {
            $('#leaderboard_based_category_lesson').css("display", "none");
            $('#leaderboard_based_lessons').css("display", "flex");
            select_all_options('#leaderboard_based_lessons', 'lessons');
        } else {
            $('#leaderboard_based_category_lesson').css("display", "none");
            $('#leaderboard_based_lessons').css("display", "none");
        }
    }


    if ($('#leaderboard_associated_course_topic').val()) {
        var course_id = $('#leaderboard_associated_course_topic').val();
        if (course_id != 0) {
            lesson_selection_options(course_id);
        } else {
            $('#lesson_topic').html('');
        }
    }

    $('#leaderboard_associated_course_topic').change(function () {
        var course_id = $(this).val();
        if (course_id != 0) {
            lesson_selection_options(course_id);
        } else {
            $('#lesson_topic').html('');
        }
    });

    if ($('#leaderboard_associated_course_quiz').val()) {
        var course_id = $('#leaderboard_associated_course_quiz').val();
        if (course_id != 0) {
            quiz_selection_options(course_id);
        } else {
            $('#lesson_quiz').html('');
        }
    }

    $('#leaderboard_associated_course_quiz').change(function () {
        var course_id = $(this).val();
        if (course_id != 0) {
            quiz_selection_options(course_id);
        } else {
            $('#lesson_quiz').html('');
        }
    });


    function quiz_selection_options(course_id) {
        var data = {
            'action': 'mycred_select_lesson_topic',
            'course_id': course_id,
            'leaderboard_id': $('#leaderboard_type').data('id')
        };

        $.post(mycred_learndash.ajax_url, data, function (response) {
            $('#lesson_quiz').html(response);
            if ($('#learndash_lesson_topic').val() != 0) {
                var lesson_topic_id = $('#learndash_lesson_topic').val();
                show_quiz_options(course_id, lesson_topic_id);
            }
            $('#learndash_lesson_topic').change(function () {
                if ($(this).val() != 0) {
                    var lesson_topic_id = $(this).val();
                    show_quiz_options(course_id, lesson_topic_id);
                }
            });
        });
    }



    function show_quiz_options(course_id, lesson_topic_id) {
        var data = {
            'action': 'mycred_show_quiz',
            'course_id': course_id,
            'lesson_id': lesson_topic_id,
            'leaderboard_id': $('#leaderboard_type').data('id')
        };

        $.post(mycred_learndash.ajax_url, data, function (response) {
            $('#learndash_quiz').html(response);
            if ($('#leaderboard_based_on_quizes input[type=radio]:checked').val()) {
                quiz_specific_options($('#leaderboard_based_on_quizes input[type=radio]:checked').val());
            }
            $('#leaderboard_based_on_quizes input[type=radio]').click(function () {
                quiz_specific_options($(this).val());
            });
        });
    }

    function quiz_specific_options(checked_val) {
        if (checked_val == 'quiz_category') {
            $('#leaderboard_based_quizes_cat').css("display", "flex");
            $('#leaderboard_based_quizes').css("display", "none");
            select_all_options('#leaderboard_based_quizes_cat', 'quizes');
//            select_all_checked('#leaderboard_based_quizes_cat', 'quizes');

        } else if (checked_val == 'specific_quiz') {
            $('#leaderboard_based_quizes_cat').css("display", "none");
            $('#leaderboard_based_quizes').css("display", "flex");
            select_all_options('#leaderboard_based_quizes', 'quizes');
//            select_all_checked('#leaderboard_based_quizes', 'quizes');
        } else {
            $('#leaderboard_based_quizes_cat').css("display", "none");
            $('#leaderboard_based_quizes').css("display", "none");
        }
    }

    function lesson_selection_options(course_id) {
        var data = {
            'action': 'mycred_select_a_lesson',
            'course_id': course_id,
            'leaderboard_id': $('#leaderboard_type').data('id')

        };

        $.post(mycred_learndash.ajax_url, data, function (response) {
            $('#lesson_topic').html(response);
            if ($('#learndash_lesson').val() != 0) {
                var lesson_id = $('#learndash_lesson').val();
                show_topic_options(course_id, lesson_id);
            }
            $('#learndash_lesson').change(function () {
                if ($(this).val() != 0) {
                    var lesson_id = $(this).val();
                    show_topic_options(course_id, lesson_id);
                }
            });
        });
    }




    function show_topic_options(course_id, lesson_id) {
        var data = {
            'action': 'mycred_show_topic',
            'course_id': course_id,
            'lesson_id': lesson_id,
            'leaderboard_id': $('#leaderboard_type').data('id')
        };

        $.post(mycred_learndash.ajax_url, data, function (response) {
            $('#learndash_topic').html(response);

            if ($('#leaderboard_based_on_topics input[type=radio]:checked').val()) {
                topic_specific_options($('#leaderboard_based_on_topics input[type=radio]:checked').val());
            }

            $('#leaderboard_based_on_topics input[type=radio]').click(function () {
                topic_specific_options($(this).val());
            });
        });
    }

    function topic_specific_options(checked_val) {
        if (checked_val == 'topic_category') {
            $('#leaderboard_based_topics_cat').css("display", "flex");
            $('#leaderboard_based_topics').css("display", "none");
            select_all_options('#leaderboard_based_topics_cat', 'topics');
//            select_all_checked('#leaderboard_based_topics_cat', 'topics');

        } else if (checked_val == 'specific_topic') {
            $('#leaderboard_based_topics_cat').css("display", "none");
            $('#leaderboard_based_topics').css("display", "flex");
            select_all_options('#leaderboard_based_topics', 'topics');
//            select_all_checked('#leaderboard_based_topics', 'topics');

        } else {
            $('#leaderboard_based_topics_cat').css("display", "none");
            $('#leaderboard_based_topics').css("display", "none");
        }
    }

    if ($('#show-pagination').prop('checked') == true) {
        $('#pagination-number').show();
    } else {
        $('#pagination-number').hide();
    }

    $('#show-pagination').click(function () {
        if ($('#show-pagination').prop('checked') == true) {
            $('#pagination-number').show();
        } else {
            $('#pagination-number').hide();
        }
    }
    );


    var dateToday = new Date();
    var dates = $("#from, #to").datepicker({
        changeMonth: true,
        maxDate: new Date,
        onSelect: function (selectedDate) {
            var option = this.id == "from" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });

    $('#custom-picker').hide();

    if ($('#timefilter option:selected').val() == 'custom_time') {
        $('#custom-picker').show();
    }


    $('#based_on_lesson').hide();
    $('#based_on_topic').hide();

    $('#leaderboard_type').on('change', function (e) {
        var optionSelected = $("option:selected", this);
        $('#based_type').html();
        if (optionSelected.val() == 'course') {
            course_specific_options();
        } else if (optionSelected.val() == 'lesson') {
            lesson_select_course();
        } else if (optionSelected.val() == 'topic') {
            topic_select_course();
        } else if (optionSelected.val() == 'quiz') {
            quiz_select_course();
        }


    });

    if ($('#leaderboard_type option:selected').val()) {
        var optionSelected = $('#leaderboard_type option:selected').val();
        if (optionSelected == 'course') {
            course_specific_options();
        } else if (optionSelected == 'lesson') {
            lesson_select_course();
        } else if (optionSelected == 'topic') {
            topic_select_course();
        } else if (optionSelected == 'quiz') {
            quiz_select_course();
        }
    }

    function course_specific_options() {
        var data = {
            'action': 'mycred_course_based_options',
            'leaderboard_id': $('#leaderboard_type').data('id'),
        };
        $.post(mycred_learndash.ajax_url, data, function (response) {
            $('#based_type').html(response);

            $('#leaderboard_based_on_course input[type=radio]').click(function () {
                course_specific_selections($(this).val(), 'course');
            });

            if ($('#leaderboard_based_on_course input[type=radio]:checked').val()) {
                course_specific_selections($('#leaderboard_based_on_course input[type=radio]:checked').val(), 'course');
            }

            $('#course_selection').html('');
            $('#based_on_lesson').hide();
            $('#based_on_topic').hide();
            $('#based_on_quiz').hide();

        });
    }

    function course_specific_selections(checked_val) {
        if (checked_val == 'course_category') {
            $('#leaderboard_based_category').css("display", "flex");
            $('#leaderboard_based_courses').css("display", "none");
            select_all_options('#leaderboard_based_category', 'courses');

        } else if (checked_val == 'specific_course') {
            $('#leaderboard_based_category').css("display", "none");
            $('#leaderboard_based_courses').css("display", "flex");
            select_all_options('#leaderboard_based_courses', 'courses');

        } else {
            $('#leaderboard_based_category').css("display", "none");
            $('#leaderboard_based_courses').css("display", "none");
        }
    }

    function select_all_options(id, type) {
        select_all_checked(id, type);
        $('#all_' + type).click(function () {
            select_all_checked(id, type);
        });
    }



    function select_all_checked(id, type) {
        if ($('#all_' + type).prop('checked') == true) {
            $(id).find(':checkbox').each(function () {
                $(id).find('.label-text').hide();
                $(this).prop("checked", true);
                $(this).hide();
            });
        } else {
            $(id).find(':checkbox').each(function () {
                $(id).find('.label-text').show();
//                $(this).prop("checked", false);
                $(this).show();
            });
        }
    }


    function lesson_select_course() {
        $('#based_type').html('');
        $('#based_on_lesson').show();
        $('#based_on_topic').hide();
        $('#based_on_quiz').hide();
    }

    function topic_select_course() {
        $('#based_type').html('');
        $('#based_on_lesson').hide();
        $('#based_on_topic').show();
        $('#based_on_quiz').hide();
    }
    function quiz_select_course() {
        $('#based_type').html('');
        $('#based_on_lesson').hide();
        $('#based_on_topic').hide();
        $('#based_on_quiz').show();
    }


});