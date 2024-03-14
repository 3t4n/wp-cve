jQuery(document).ready(function ($) {

    var LD_CMS_Admin = {
        searchTimeoutID: null,
        searchPageNum: 1,
        isLoadMore: false,
        init: function () {
            this.loadCourseList();
            this.initSearchField();
            this.loadMoreInit();
        },
        loadCourseList: function () {
            this.doSearch();
        },
        initSearchField: function () {
            $('#ld_cms_search_courses').keyup(function (e) {
                clearTimeout(this.searchTimeoutID);
                this.searchTimeoutID = setTimeout(() => LD_CMS_Admin.triggerSearch(e.target.value), 500);
            });
        },
        triggerSearch: function (str) {
            if (str.length == 0 || str.length >= 3) {
                LD_CMS_Admin.searchPageNum = 1;
                LD_CMS_Admin.isLoadMore = false;
                this.doSearch();
            }
        },
        doSearch: function () {
            $.ajax({
                type: "post",
                url: LDCSAdminVars.ajax_url,
                dataType: "json",
                data: {
                    action: 'cs_ld_search_courses',
                    security: LDCSAdminVars.check_nonce,
                    search_text: $('#ld_cms_search_courses').val(),
                    ld_cms_course_list_page_num: LD_CMS_Admin.searchPageNum,
                },
                beforeSend: function () {
                    $('#ld_cms_course_list_loader').show();
                    if (!LD_CMS_Admin.isLoadMore) {
                        $('#ld_cms_course_list').hide();
                    } else {
                        $('#ld_cms_course_list').show();
                    }
                },
                success: function (response) {

                    if (LD_CMS_Admin.isLoadMore) {
                        $('#ld_cms_course_list > #ld_cms_course_sub_list').append(response.content);
                    } else {
                        $('#ld_cms_course_list').html(response.content);
                    }

                    if (response.next_page == 0) {
                        $('#ld_cms_load_more').hide();
                    } else {
                        LD_CMS_Admin.searchPageNum = response.next_page;
                        $('#ld_cms_load_more').show();
                    }

                    LD_CMS_Admin.refreshCalendarEvents();
                },
                complete: function () {
                    $('#ld_cms_course_list_loader').hide();
                    $('#ld_cms_course_list').show();
                }
            });
        },
        loadMoreInit: function () {
            $(document).on('click', '#ld_cms_load_more', function (e) {
                e.preventDefault();
                LD_CMS_Admin.isLoadMore = true;
                LD_CMS_Admin.doSearch();
            });

        },
        refreshCalendarEvents: function () {
            $(document).on('mouseover', 'div#external-events div.fc-event', function () {
                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    course_id: $.trim($(this).data('course-id')),
                    cid: $.trim($(this).data('cid')),
                    lid: $.trim($(this).data('lid')),
                    course_type: $.trim($(this).data('course-type')),
                    className: $.trim($(this).text()),
                    stick: true // maintain when user navigates (see docs on the renderEvent method)
                });

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0, //  original position after the drag

                });
            });
        }
    };

    LD_CMS_Admin.init();
});