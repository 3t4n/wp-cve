var thfaqf_public = (function($, window, document){
    'use strict';

    function initialize_thfaqf(){
        setup_faq_accordion();
        setup_share_icons();
        activate_faq_group_url_search();
    }

    function setup_faq_accordion(){
        var open_multiple = thfaqf_public_var.open_multiple_faqs;
        var faq_wrapper = $('.thfaqf-faq-list');

        open_first_faq_tab(faq_wrapper);

        $('.thfaqf-faq-item-title').click(function(){
            if(open_multiple != 'yes'){
                var wrapper = $(this).closest('.thfaqf-faq-list');
                close_all_other_faq_tabs($(this), wrapper);
            }
            toggle_faq_tab($(this));
        });
    }

    function close_all_other_faq_tabs(elm, wrapper){
        var faq_item = elm.closest('.thfaqf-faq-item');
        var other_faq_items = faq_item.siblings('.thfaqf-faq-item');
        other_faq_items.find('.thfaqf-faq-item-content').slideUp('fast');
        other_faq_items.removeClass("thfaqf-active");
    }

    function toggle_faq_tab(elm){
        elm.next().slideToggle('fast');
        elm.closest('.thfaqf-faq-item').toggleClass("thfaqf-active");
    }

    function open_faq_tab(elm){
        elm.find('.thfaqf-faq-item-content').slideDown();
        if(!elm.hasClass("thfaqf-active")){
            elm.addClass("thfaqf-active");
        }
    }

    function open_first_faq_tab(wrapper){
        wrapper.each(function() {
            var active_item = $(this).find('.thfaqf-faq-item.thfaqf-active');
            open_faq_tab(active_item);
        });
    }

    function setup_share_icons(){
        $('a.thfaqf-share-icon').click(function(e){
            e.preventDefault(); 
            var url = $(this).attr('data-url'); 
            window.open(url, 'new-window','width=600, height=400');
        });
    }

    function like_dislike_option(elm){
        Like_and_dislike_option($(elm),event);
    }

    function Like_and_dislike_option(click,evnt){
        var wrapper = click.closest('.th-like-wrapper'),
            id = click.data('user_id');  

        if(id<1){
            confirm('Please login to like or dislike FAQs.') == true ? '': evnt.preventDefault(evnt);
        }else{
            evnt.preventDefault(evnt)
        } 
 
        var dataset = click.data();
        var data = [];

        for(var key in dataset){
            if (dataset.hasOwnProperty(key)) {
                data.push({name:key, value:dataset[key]});
            }
        }

        jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : thfaqf_public_var.ajax_url,
            data : data,
 
            beforeSend : function(){
            },
            success: function(response){

                if(response.verify_nonce){
                    $(wrapper).html(response.verify_nonce);
                }else {
                    var like_count = Object.keys(response.like_user_ids).length,
                        dislike_count = Object.keys(response.dislike_user_ids) !== 'undefined'? Object.keys(response.dislike_user_ids).length : '',
                        like_array = $.map(response.like_user_ids, function(value, index) {return [value];}),
                        dislike_array = $.map(response.dislike_user_ids, function(value, index) {return [value];}),
                        like_status = jQuery.inArray(response.current_user_id,like_array) !== -1,
                        dislike_status = jQuery.inArray(response.current_user_id,dislike_array) !== -1;

                    $(wrapper).find('.thfaq-like-count').html(like_count);
                    $(wrapper).find('.thfaq-dislike-count').html(dislike_count);
                    dislike_status == true ? click.find('.thfaq-icomoon').css('color','black'):click.closest('.th-like-wrapper').find('.thfaq-thums-down .thfaq-icomoon').css('color','white');
                    like_status == true ? click.find('.thfaq-icomoon').css('color','black'):click.closest('.th-like-wrapper').find('.thfaq-thums-up .thfaq-icomoon').css('color','white');
                }
            },

            fail: function() {
               alert('fail');
            },
        });
    }

    function faq_search_option(elm){
        var wrapper = $(elm).closest('.thfaqf-faq-list'),
            filter = $(elm).val().trim().toLowerCase(),
            visible =  wrapper.find('.thfaqf-faq-item:visible').addClass('thfaqf-search'),
            faq_item = wrapper.find('.thfaqf-faq-item.thfaqf-search');

        for(var i = 0; i< faq_item.length; i++){
            var each_faq =  $(faq_item[i]),
                title = $(each_faq).find('.thfaqf-faq-item-title').text(), 
                title_html = $(each_faq).find('.thfaqf-faq-item-title').html(),
                content = $(each_faq).find('.thfaqf-faq-item-content').text();
            (title.toString().toLowerCase().indexOf(filter) > -1 || content.toString().toLowerCase().indexOf(filter) > -1 ) ? $(each_faq).show() : $(each_faq).hide();          
        }
    }

    function submit_faq_comments(elm){
        preppare_faq_comments($(elm),event);
    }

    function preppare_faq_comments(elm,event){
        setTimeout(function(){ $(".thfaqf-error-submt,.thfaqf-success-submt").fadeOut(1000); },4000);
        var clicks = $(elm),
            click = clicks.closest('.thfaqf-comment-wrapper');
        event.preventDefault(event);
        var form  =  clicks.closest('form'),
            Usercmnt = $(form).serializeArray(),
            name = click.find('.thfaqf-uname').val(),
            comment = click.find('.thfaqf-ucomment').val();

        jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : thfaqf_public_var.ajax_url,
            data : Usercmnt,
            beforeSend : function(){

            },
            success: function(response){
                click.find('.threq-name').html(response.name);
                click.find('.threq-comment').html(response.comment);
                click.find('.thfaqf-comment-validetion').html(response);
                
                if(name && comment){
                    click.find('.thfaqf-comment-box').val('');
                }

                if(response.verify_nonce)
                    click.find('.thfaqf-comment-validetion').html(response.verify_nonce);
            },
            fail: function() {
               alert('fail');
            },
        });

    }

    function add_new_comment(elm){
        var click = $(elm);
        var wrapper = click.closest('.thfaqf-faq-item-content');
        $(wrapper).find('.thfaq-enable-comment-box').toggleClass('thfaqf-hide');
    }

    function faq_tab(elm,tab_name){
        var wrapper = $(elm).closest('.thfaqf-layout-wrapper'),
            items = wrapper.find('.thfaqf-tabcontent-wrapper');
        wrapper.find('.thfaqf-tablinks').removeClass('active');    
        for (var i = 0; i < items.length; i++) {
           $(items[i]).css('display','none');
        }
        wrapper.find('.'+tab_name).css('display','block');
        $(elm).addClass('active');
    }

    function preppare_pagination(elm,load_page){
        faq_pagination(elm,load_page,event);
    }

    function faq_pagination(elm,load_page,event){
        event.preventDefault(event);
        if(load_page == 'next_page'){
            const last = $(elm).siblings().children().last();
            if (!last.hasClass('current')) {
                const current = $(elm).siblings().children('.current'),
                    closest = $(elm).closest('.thfaqf-pagination'),
                    nextItem = current.parent().next().children(); 
                current.text() == $(elm).data('page_count') ? $(elm).addClass('thfaqf-div-none'): '';
                current.removeClass('current');
                (nextItem.parent().hasClass('thfaqf-hidden-no')) ? 
                nextItem.parent().next().children().addClass('current').trigger("click") 
                : nextItem.addClass('current');

                if(nextItem.parent().hasClass('thfaqf-div-none')){
                    current.parent().addClass('thfaqf-div-none');
                    nextItem.parent().removeClass('thfaqf-div-none');
                }
                if(closest.find('.thfaqf-prev-page').data('number') == 1) {
                    closest.find().removeClass('thfaqf-div-none')
                }
                nextItem.trigger("click");   
            } 
        }else{
            const first = $(elm).siblings().children().first();
            if (!first.hasClass('current')){
                const current = $(elm).siblings().children('.current'),
                    prevItem = current.parent().prev();
                current.removeClass('current');
                if(prevItem.hasClass('thfaqf-hidden-no')){
                    prevItem.addClass('thfaqf-div-none'); 
                    prevItem.prev().removeClass('thfaqf-div-none');
                    prevItem.prev().children().addClass('current').trigger("click");
                }else{
                    prevItem.children().addClass('current');
                }

                if(prevItem.hasClass('thfaqf-div-none')&& !prevItem.hasClass('thfaqf-hidden-no')){
                    current.parent().addClass('thfaqf-div-none');
                    prevItem.removeClass('thfaqf-div-none'); 
                }
                prevItem.children().trigger( "click");
            }
        }

    }

    function showPage(page,pageSize,faq_items){
        $(faq_items).addClass('thfaqf-div-none');
        $(faq_items).each(function(n){
            if (n >= pageSize * (page - 1) && n < pageSize * page){
                $(this).removeClass('thfaqf-div-none');
            }
        });        
    }

    function thfaq_page_number(elm){
        $(elm).text() >'1' ? $(elm).closest('.thfaqf-pagination').find('.thfaqf-prev-page').removeClass('thfaqf-hidden-no') : '';
        var closest = $(elm).closest('.thfaqf-faq-list'),
            count = closest.find('.thfaqf-count-faq-number').val(),
            faq_items = closest.find('.thfaqf-faq-item');
        
        event.preventDefault(event);
        $(elm).closest('.thfaqf-faq-list').find(".thfaqf-pnumber").removeClass("current");
        $(elm).addClass("current");
        showPage(parseInt($(elm).text()),count,faq_items); 
    }

    function activate_faq_group_url_search(){
        var searchParams =  new URLSearchParams(window.location.search);
        var category = searchParams.get('category');
        var qstn_tab = searchParams.get('qstn_tab');
        var elm_categry = document.querySelector(".thfaqf-tablinks-"+category);
        var tab_name = 'thfaqf-tab-id_'+category;
        if(category){
            faq_tab(elm_categry,tab_name);
        }
        if(qstn_tab){
            activate_faq_qstn_url(qstn_tab);
        }
    }

    function activate_faq_qstn_url(qstn_tab){
        var elm_qstn = $("#thfaqf-faq-item-"+qstn_tab);
        open_faq_tab(elm_qstn);
    }

    /***----- INIT -----***/
    initialize_thfaqf();

    return {
        likeDislike : like_dislike_option,
        FaqSearch   : faq_search_option,
        SubmitNewComment: submit_faq_comments,
        postFaqComment  : add_new_comment,
        TabClick : faq_tab,
        FaqPagination : preppare_pagination,
        ThfaqEachPageNumber : thfaq_page_number,
    }

}(window.jQuery, window, document));

function likeDislikeOption(elm){
    thfaqf_public.likeDislike(elm);
}

function faq_search_option(elm){
    thfaqf_public.FaqSearch(elm);
}

function submitFaqfComment(elm){
    thfaqf_public.SubmitNewComment(elm);
}

function clickFaqComment(elm){
    thfaqf_public.postFaqComment(elm);
}

function FaqTabOnClick(elm,tab_name){
    thfaqf_public.TabClick(elm,tab_name);
}

function ThfaqPagination(elm,load_page){
    thfaqf_public.FaqPagination(elm,load_page);
}

function ThfaqEachPage(elm,load_page){
    thfaqf_public.ThfaqEachPageNumber(elm);
}












