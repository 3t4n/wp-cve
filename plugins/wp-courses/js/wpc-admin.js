/*****************************************
***************** General ****************
*****************************************/

function fixIframeSize() {
    var video = jQuery('.wpc-video-wrapper iframe');
    jQuery.each(video, function (key, val) {
        var w = jQuery(this).parent().width();
        var h = w * 0.5625;
        jQuery(this).width(w);
        jQuery(this).height(h);
    });
}

jQuery(document).ready(function ($) {
    fixIframeSize();
    $(document).ready(function () {
        $('.wpc-course-multiselect, .wpc-single-lesson-course-multiselect, .wpc-teacher-multiselect').select2();
    });
});

jQuery(window).resize(function () {
    fixIframeSize();
});

function wpcLessonTableData() {
    var $lessonRows = jQuery('.wpc-admin-lesson-list li');
    var posts = [];
    $lessonRows.each(function (key, value) {
        var dataID = jQuery(this).attr('data-id');
        var postType = jQuery(this).attr('data-post-type');
        var courseID = jQuery(this).attr('data-course-id');
        posts.push({
            'postID': dataID,
            'courseID': courseID,
            'menuOrder': key,
            'postType': postType,
        });
    });
    return posts;
}

function wpcShowAjaxIcon() {
    $saveIconWrapper = jQuery('#wpc-ajax-save');

    if ($saveIconWrapper.is(':hidden')) {
        $saveIcon = $saveIconWrapper.children();
        $saveIcon.removeClass();
        $saveIcon.addClass('fa fa-spin fa-spinner');
        $saveIconWrapper.fadeIn();
    }
}

function wpcHideAjaxIcon() {
    $saveIconWrapper = jQuery('#wpc-ajax-save');

    if ($saveIconWrapper.is(':visible')) {
        $saveIcon = $saveIconWrapper.children();
        $saveIcon.removeClass();
        $saveIcon.addClass('fa fa-check');
    }
    
    $saveIconWrapper.fadeOut();
}

jQuery(document).ready(function ($) {
    $(".wpc-admin-options-menu li").click(function () {
        var id = $(this).attr('data-elem-id');
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#" + id).offset().top - 40
        }, 1000);
    });

    $("body").on("click", ".wpc-admin-notice button.notice-dismiss", function () {
        var data = {
            'security': wpc_ajax.nonce,
            'action': 'wpc_admin_notice_dismiss',
        }
        
        jQuery.post(ajaxurl, data, function (response) {});
    });
});

// Sticky

function WPCSticky(sidebarSelector, args = {}) {

    var sidebar = jQuery(sidebarSelector);
    var parentContainer = sidebar.parent();
    var siblingContainer = sidebar.siblings();

    jQuery(document).on('scroll', function () {
        var scrollY = window.scrollY + args.offsetTop;
        var sidebarY = sidebar.offset().top;
        var siblingY = siblingContainer.offset().top;

        sidebar.css('position', 'relative');

        if (sidebarY < scrollY || sidebarY > siblingY) {
            sidebar.css({
                'position': 'relative',
                'top': scrollY < siblingY ? 0 + 'px' : scrollY - siblingY + 'px',
            });
        }

    });

}

jQuery(document).ready(function ($) {
    if (jQuery('#wpc-sticky-sidebar').length) {
        WPCSticky('#wpc-sticky-sidebar', {
            offsetTop: 40,
        });
    }
});



/*****************************************
***************** PMPro *****************
*****************************************/

jQuery(document).ready(function ($) {
    function wpc_pmpro_meta_box() {
        var $all_checkboxes = $('#pmpro_page_meta .selectit input[type="checkbox"]');

        var $radio_buttons = $('#wpc-lesson-restriction-container input[type="radio"]').not(':hidden');

        $.each($all_checkboxes, function (key, val) {
            if ($(this).prop('checked') == true) {
                $('#wpc-membership-radio').prop('checked', 'checked');
                $('#wpc-lesson-restriction-container').css({
                    'pointer-events': 'none',
                });
                $radio_buttons.prop('disabled', true);
                $('#wpc-lesson-restriction-overlay').css('display', 'block');
                return false;
            } else {
                var checked = '';
                $.each($radio_buttons, function (key, val) {
                    if ($(this).prop('checked') == true) {
                        checked = true;
                        return false;
                    } else {
                        checked = false;
                    }
                });

                if (checked == false) {
                    $('#wpc-none-radio').prop('checked', 'checked');
                }

                $('#wpc-lesson-restriction-container').css({
                    'pointer-events': 'all',
                });

                $radio_buttons.prop('disabled', false);
                $('#wpc-lesson-restriction-overlay').css('display', 'none');
            }
        });
    }
    $('#pmpro_page_meta .selectit input').click(function () {
        wpc_pmpro_meta_box();
    });
});



/*****************************************
************ Requirements Logic **********
*****************************************/

jQuery(document).ready(function ($) {

    // Dropdown: Views, Completes, Scores
    $(document).on('change', '.wpc-requirement-action', function () {

        var value = $(this).val();

        if (value == 'scores') {

            $(this).siblings('.wpc-requirement-type').children('option[value="any-quiz"]').attr('selected', 'selected');
            $(this).parent().children('.wpc-requirement-courses-select').hide();
            $(this).parent().children('.wpc-percent').show();
            $(this).parent().children('.wpc-percent-label').show();

            $(this).parent().children('.wpc-requirement-times').show();
            $(this).parent().children('.wpc-times-label').show();

            $(this).siblings('.wpc-requirement-type').children('option[value="any-course"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-course"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-lesson"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-lesson"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-module"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-module"]').hide();

            $(this).parent().children('.wpc-requirement-lesson-select').hide();

        } else {

            $(this).siblings('.wpc-requirement-type').children('option[value="any-course"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-course"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-lesson"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-lesson"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-module"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-module"]').show();

        }

        var requirementType = jQuery(this).parent().children('.wpc-requirement-type').val();
        var requirementAction = jQuery(this).val();

        var $requirementPercent = jQuery(this).parent().children('.wpc-percent');
        var $percentLabel = jQuery(this).parent().children('.wpc-percent-label');

        if (requirementType == 'specific-quiz' || requirementType == 'any-quiz') {
            if (requirementAction == 'completes' || requirementAction == 'views') {
                $requirementPercent.val(0);
                $requirementPercent.hide();
                $percentLabel.hide();
            } else {
                $requirementPercent.val(0);
                $requirementPercent.show();
                $percentLabel.show();
            }
        }

    });

    // Dropdown: Any Course, A Specific Course etc.
    $(document).on('change', '.wpc-requirement-type', function () {

        var requirementType = jQuery(this).val();
        var requirementAction = jQuery(this).parent().children('.wpc-requirement-action').val();

        var $requirementTimes = jQuery(this).parent().children('.wpc-requirement-times');
        var $requirementPercent = jQuery(this).parent().children('.wpc-percent');

        var $timesLabel = jQuery(this).parent().children('.wpc-times-label');
        var $percentLabel = jQuery(this).parent().children('.wpc-percent-label');

        var $requirementCoursesSelect = jQuery(this).parent().children('.wpc-requirement-courses-select');
        var $requirementLessonSelect = jQuery(this).parent().children('.wpc-requirement-lesson-select');

        $requirementLessonSelect.hide();

        if (requirementType == 'specific-lesson' || requirementType == 'any-lesson') {
            $requirementPercent.val(0);
            $requirementPercent.hide();
            $percentLabel.hide();
        } else {
            $requirementPercent.show();
            $percentLabel.show();
        }

        if (requirementType == 'specific-course' || requirementType == 'specific-lesson' || requirementType == 'specific-module' || requirementType == 'specific-quiz') {
            $requirementCoursesSelect.show();
            $requirementCoursesSelect.val('none');
            $timesLabel.hide();
            $requirementTimes.hide();
        } else {
            $requirementCoursesSelect.hide();
            $timesLabel.show();
            $requirementTimes.show();
        }

        if (requirementType == 'specific-quiz' || requirementType == 'any-quiz') {
            if (requirementAction == 'completes' || requirementAction == 'views') {
                $requirementPercent.val(0);
                $requirementPercent.hide();
                $percentLabel.hide();
            } else if (requirementAction == 'scores') {
                $requirementPercent.val(0);
                $requirementPercent.show();
                $percentLabel.show();
            }
        }

    });

    // Add color picker to all inputs that have 'wpc-options-color-field' class (settings page) - could be moved to general part?
    $(".wpc-options-color-field").spectrum({
        showAlpha: false,
        showInput: true,
        preferredFormat: "hex",
    });

    // Admin submenu display logic - could be moved to general part?
    $(document).on('click', '.wpc-submenu-toggle', function (e) {
        $(this).siblings().children('.wpc-submenu').fadeOut('fast');
        $(this).children('.wpc-submenu').fadeToggle('fast');
        $(this).toggleClass('wpc-menu-item-active');
        $(this).siblings().removeClass('wpc-menu-item-active');
    });

    // Hide submenu on click outside of submenu - could be moved to general part?
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.wpc-submenu, .wpc-submenu-toggle, .wpc-submenu-toggle a').length) {
            $('.wpc-submenu').fadeOut('fast');
            $('.wpc-submenu-toggle').removeClass('wpc-menu-item-active');
        }
    });

    $('.wpc-nav-tab').click(function (e) {
        $('.wpc-nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.wpc-tab-content').hide();
        $('.wpc-tab-content').eq($(this).index()).fadeIn('fast');
        e.preventDefault();
    });

});



/*****************************************
******* Lesson Sorting and Modules *******
*****************************************/

function wpcLoadLessonSortingList(courseID) {
    let data = {
        'security': wpc_ajax.nonce,
        'action': 'wpc_admin_html_lesson_nav',
        'course_id': courseID,
    }

    UI_Components.loader('#wpc-lesson-sorting-container');
    jQuery('#wpc-lesson-sorting-container').hide();
    jQuery('#wpc-lesson-sorting-container').fadeIn();

    jQuery.post(ajaxurl, data, function (response) {
        jQuery('#wpc-add-module').show();

        jQuery('#wpc-lesson-sorting-container').hide();
        jQuery('#wpc-lesson-sorting-container').html(response);
        jQuery('#wpc-lesson-sorting-container').fadeIn();

        jQuery(".wpc-admin-lesson-list").sortable({
            axis: 'y',
            update: function (event, ui) {
                let data = {
                    'action': 'order_lessons',
                    'posts': wpcLessonTableData(),
                    'security': wpc_ajax.nonce
                };
                wpcShowAjaxIcon();
                jQuery.post(ajaxurl, data, function (response) {
                    wpcHideAjaxIcon();
                });
            }
        });
    });

}

jQuery(".wpc-lesson-sorting-course-select").prop("selectedIndex", 0);

function wpcInitLessonSorting(courseID) {
    /********** HIDE LESSON LIST IF NO COURSES EXIST **********/

    if (!courseID || courseID === 'null') {
        $('.wpc-flex-container:nth-child(1)').hide();
    }

    /********** LOAD LESSON LIST ON PAGE LOAD **********/

    wpcLoadLessonSortingList(courseID);

    /********** LOAD LESSON LIST ON SELECT **********/

    jQuery('.wpc-lesson-sorting-course-select').change(function () {
        jQuery('#wpc-add-module').hide();
        courseID = jQuery(this).val();
        wpcLoadLessonSortingList(jQuery(this).val());

    });

    /********** DELETE MODULE **********/

    jQuery(document).on('click', '.wpc-delete-module', function () {

        var clicked = jQuery(this);
        clicked.parent().remove();

        var data = {
            'security': wpc_ajax.nonce,
            'action': 'delete_module',
            'module_id': clicked.parent().data('id'),
            'course_id': clicked.parent().data('course-id'),
            'posts': wpcLessonTableData(),
        }

        wpcShowAjaxIcon();

        jQuery.post(ajaxurl, data, function (response) {
            // remove module from list
            wpcHideAjaxIcon();
        });

    });

    /********** RENAME MODULE **********/

    var typingTimer;
    var doneTypingInterval = 1000;

    jQuery(document).on('keyup', '.wpc-module-title-input', function () {
        clicked = jQuery(this);
        clearTimeout(typingTimer);
        if (clicked.val()) {
            typingTimer = setTimeout(wpcDoneModuleTyping, doneTypingInterval);
        }
    });

    //finshed typing... Save
    function wpcDoneModuleTyping() {

        var data = {
            'security': wpc_ajax.nonce,
            'action': 'rename_module',
            'module_id': clicked.parent().data('id'),
            'module_title': clicked.val(),
        }

        wpcShowAjaxIcon();

        jQuery.post(ajaxurl, data, function (response) {
            wpcHideAjaxIcon();
        });
    }

    /********** ADD MODULE **********/

    jQuery('#wpc-add-module').click(function () {

        var data = {
            'security': wpc_ajax.nonce,
            'action': 'add_module',
            'course_id': courseID,
            'posts': wpcLessonTableData(),
        }

        wpcShowAjaxIcon();

        jQuery.post(ajaxurl, data, function (response) {
            // Add the module.
            jQuery('.wpc-admin-lesson-list').prepend('<li data-id="' + response + '" data-post-type="wpc-module" data-course-id="' + courseID + '" class="ui-sortable-handle wpc-nav-list-header"><i class="fa fa-bars wpc-grab"></i><input type="text" placeholder="Module Name" class="wpc-module-title-input"><button type="button" class="wpc-delete-module wpc-btn wpc-btn-icon"><i class="fa fa-trash"></i></button></li>');
            wpcHideAjaxIcon();
        });

    });
}
