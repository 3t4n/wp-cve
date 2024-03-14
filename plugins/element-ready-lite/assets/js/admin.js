;(function($) {

    'use strict';
    String.prototype.is_email=function(){
        return (/^[\w-\.]+\@[\w\.-]+\.[a-z]{2,4}$/.test(this)); 
    }
    var dash_tabs = {
        active: 0
    };
   
    if (typeof element_ready_obj !== 'undefined') {
        dash_tabs.active = parseInt(element_ready_obj.active)
    }

    if ($("#element-ready-adpage-tabs").length != 0) {
        $('#element-ready-adpage-tabs').tabs(dash_tabs);
    }
    var currentNoticeContent = $('.element-ready-admin-notice-remote .notice-content').html();

    if (localStorage.admin_notice_remote_dismissed) {
        if (JSON.parse(localStorage.admin_notice_remote_dismissed).content === currentNoticeContent)
            $('.element-ready-admin-notice-remote').addClass('hidden');
    }


    $(document).on('click', '.element-ready-admin-notice-remote .notice-dismiss', function() {
        // create object with date the notice was dismissed and the content of the notice at the time of dismissal
        var noticeData = {
            date: new Date(),
            content: currentNoticeContent
        };
        localStorage.admin_notice_remote_dismissed = JSON.stringify(noticeData);
    });

    var x, i, j, l, ll, selElmnt, a, b, c;

    x = document.getElementsByClassName("element-ready-custom-select");
    l = x.length;
    for (i = 0; i < l; i++) {
        selElmnt = x[i].getElementsByTagName("select")[0];
        ll = selElmnt.length;

        a = document.createElement("DIV");
        a.setAttribute("class", "select-selected");
        a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        x[i].appendChild(a);

        b = document.createElement("DIV");
        b.setAttribute("class", "select-items select-hide");
        for (j = 1; j < ll; j++) {

            c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.addEventListener("click", function(e) {

                var y, i, k, s, h, sl, yl;
                s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                sl = s.length;
                h = this.parentNode.previousSibling;
                for (i = 0; i < sl; i++) {
                    if (s.options[i].innerHTML == this.innerHTML) {
                        s.selectedIndex = i;
                        h.innerHTML = this.innerHTML;
                        y = this.parentNode.getElementsByClassName("same-as-selected");
                        yl = y.length;
                        for (k = 0; k < yl; k++) {
                            y[k].removeAttribute("class");
                        }
                        this.setAttribute("class", "same-as-selected");
                        break;
                    }
                }
                h.click();
            });
            b.appendChild(c);
        }
        x[i].appendChild(b);
        a.addEventListener("click", function(e) {

            e.stopPropagation();
            element_ready_closeAllSelect(this);
            this.nextSibling.classList.toggle("select-hide");
            this.classList.toggle("select-arrow-active");
        });
    }

    function element_ready_closeAllSelect(elmnt) {

        var x, y, i, xl, yl, arrNo = [];
        x = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        xl = x.length;
        yl = y.length;
        for (i = 0; i < yl; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i)
            } else {
                y[i].classList.remove("select-arrow-active");
            }
        }
        for (i = 0; i < xl; i++) {
            if (arrNo.indexOf(i)) {
                x[i].classList.add("select-hide");
            }
        }
    }
    document.addEventListener("click", element_ready_closeAllSelect);

    /*----------------------
        PRO MODAL
    -----------------------*/
    var modal_action = $('.element-ready-dash-modal-open-btn label');
    var modal = $('#element-ready-modal-body');
    modal_action.on('click', function() {
        modal.show();
    });
    $('.element-ready-modal-close').on('click', function() {
        modal.fadeOut();
    });

    /* --------------------
    * filter component & module
    ---------------------*/

    $('#element-ready-widgets-search').on('input', function() {
        var ele_val = $(this).val().toLowerCase();
        var that_widgets = null;
        var filter_col = $('.element-ready-component-row .element-ready-col');

        $.each(filter_col, function() {
            var that_widgets = $(this);
            that_widgets.toggle(that_widgets.find('strong').text().toLowerCase().indexOf(ele_val) > -1);

        });
    });

    $('#element-ready-modules-search').on('input', function() {
        var ele_val = $(this).val().toLowerCase();
        var that_widgets = null;
        var filter_col = $('.element-ready-modules-row .element-ready-col');

        $.each(filter_col, function() {
            var that_widgets = $(this);
            that_widgets.toggle(that_widgets.find('strong').text().toLowerCase().indexOf(ele_val) > -1);
        });

    });
  

    $('#element-ready-modules-button-sbt').on('click',function(e){
    
        e.preventDefault();
        $(this).addClass('element-raedy-button-laoding');
        er_modules_form_submits(null,$(this));
    });

    $(document).off().on('click','input.element-ready-modules-all-js-enable', function() {
         
        let filter_col = $('.element-ready-modules-row .element-ready-col input');
        var is_checked = false;
        if ($(this).prop("checked")) {
            is_checked = true;
        }else{
            is_checked = false;
        }
     
        $.each(filter_col, function() {
            let that_widgets = $(this);
            if (!that_widgets.attr('readonly')) {
                that_widgets.prop('checked', is_checked)
            }
        });

    });

    $('#quomodo-components-all-enable').on('click', function() {
      
        let filter_col = $('.element-ready-component-row .element-ready-col input');
        var is_checked = false;
        if ($(this).prop("checked")) {
            is_checked = true;
        }else{
            is_checked = false;
        }
        $.each(filter_col, function() {

            let that_widgets = $(this);
            if (!that_widgets.attr('readonly')) {
                that_widgets.prop('checked', is_checked)
            }

        });

      

    });

    $('button.er-lite-widgets-submit').on('click',function(e){

        e.preventDefault();
        $(this).addClass('element-raedy-button-laoding');
        er_compononet_form_submits(null,$(this));
    });


    $('.element-ready-pro-connects').on('click', function(evt) {


        evt.stopPropagation();
        evt.preventDefault();

        if (typeof element_ready_obj !== 'undefined') {
            $.ajax({
                url: element_ready_obj.rest_url + 'element-ready-pro/v1/activate',
                method: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("X-Element-Ready-Pro-Signature", "element-ready");
                    xhr.setRequestHeader("x_user_id", element_ready_obj.user_id);
                },
                success: function(data) {

                    $('.element-ready-active-msg').remove();
                    if (data.data.code == 200) {
                        var txt = document.createElement("h2");

                        txt.innerHTML = data.data.msg;
                        $(txt).css({ 'color': '#3467ff' });
                        $(txt).addClass('element-ready-active-msg');
                        $('.element-ready-pro-connect').prepend(txt);
                    }
                    if (data.data.status == 403) {
                        $(txt).css({ 'color': '#c81a03e1' });
                        $('.element-ready-pro-connect').prepend('connection fail, please provide valid license');
                    }
                    setTimeout("location.reload(true);", 2000);
                },
                error: function(jqXHR, exception) {

                    $('.element-ready-active-msg').remove();
                    var txt = document.createElement("h2");

                    txt.innerHTML = 'You may provide invide license key';
                    $(txt).css({ 'color': '#3467ff' });
                    $(txt).addClass('element-ready-active-msg');
                    $('.element-ready-pro-connect').prepend(txt);
                }
            });
        }
    });

    $('.element-ready-pro-disconnects').on('click', function(evt) {

        evt.stopPropagation();
        evt.preventDefault();

        if (typeof element_ready_obj !== 'undefined') {
            $.ajax({
                url: element_ready_obj.rest_url + 'element-ready-pro/v1/deactivate',
                method: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("X-Element-Ready-Pro-Signature", "element-ready");
                    xhr.setRequestHeader("x_user_id", element_ready_obj.user_id);
                },
                success: function(data) {

                    $('.element-ready-active-msg').remove();
                    var txt = document.createElement("h2");
                    $(txt).addClass('element-ready-active-msg');
                    txt.innerHTML = data.data.msg;

                    $(txt).css({ 'color': '#3467ff' });

                    if (data.data.code == 200) {
                        $('.element-ready-pro-connect').prepend(txt);
                    }

                    setTimeout("location.reload(true);", 2000);

                },
                error: function(jqXHR, exception) {
                    $('.element-ready-active-msg').remove();
                    var txt = document.createElement("h2");
                    $(txt).addClass('element-ready-active-msg');
                    txt.innerHTML = 'Server error';
                    $(txt).css({ 'color': '#3467ff' });
                    $(txt).addClass('element-ready-active-msg');
                    $('.element-ready-pro-connect').prepend(txt);
                }
            });
        }
    });
  
    function er_compononet_form_submits(button = null,this_button = null){

        let form_data = $('form#element-ready-admin-component-form').serialize();
  
        $.ajax({
            url: element_ready_obj.ajax_url,
            method: 'POST',
            data : form_data,
            success: function(response) {
              
               $('body').append('<div class="show element-ready--lite-snackbar" id="element-ready--lite-snackbar">'+response.data+'</div>');  
              
                        setTimeout(function(){ 
                            $('.element-ready--lite-snackbar').remove();
                            this_button.removeClass('element-raedy-button-laoding');
                        }, 3000);
         
            },
            error: function(jqXHR, exception) {

                $('body').append('<div class="show element-ready--lite-snackbar" id="element-ready--lite-snackbar">Error</div>');  
              
               setTimeout(function(){ 
                   $('.element-ready--lite-snackbar').remove();
                   this_button.removeClass('element-raedy-button-laoding');
                }, 3000);
            }
        });
    }

    
    function er_modules_form_submits(button = null, this_button = null){

        let form_data = $('form.element-ready-modules-action').serialize();
        
        $.ajax({
            url: element_ready_obj.ajax_url,
            method: 'POST',
            data : form_data,
            success: function(response) {
              
                $('body').append('<div class="show element-ready--lite-snackbar" id="element-ready--lite-snackbar">'+response.data+'</div>');  
                setTimeout(function(){ 
                    $('.element-ready--lite-snackbar').remove();
                }, 2000);
                this_button.removeClass('element-raedy-button-laoding');
            },
            fail :(function() {

                
            }),
            error: function(jqXHR, exception) {

                $('body').append('<div class="show element-ready--lite-snackbar" id="element-ready--lite-snackbar">Error</div>');  
              
               setTimeout(function(){ 
                   $('.element-ready--lite-snackbar').remove();
                }, 3000);
            }
        });
    }
  
    function er_ready_getCookie(cname) {

        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function er_ready_allStorage() {

        var values = [],
            keys = Object.keys(localStorage),
            i = keys.length;

        while (i--) {
            var new_obk = {};
            new_obk.k = keys[i];
            new_obk.v = localStorage.getItem(keys[i]);
            values.push(new_obk);
        }

        return JSON.stringify(values);
    }

    function er_ready_get_All_Cookie() {
        let all = [];
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');

        for (var i = 0; i < ca.length; i++) {

            var new_obks = {};
            new_obks.k = ca[i].split('=')[0];
            new_obks.v = ca[i].split('=')[1];
            all.push(new_obks);
        }

        return JSON.stringify(all);
    }

    $(document).ready(function() {

        if ($('.elements-ready-video-popup').length) {
            $('.elements-ready-video-popup').magnificPopup({ type: 'iframe' });
        }

    });

})(jQuery);

// unspash

(function($) {

    if ("undefined" != typeof wp && wp.media) {

        var e = wp.media.view.MediaFrame.Select,
            i = (wp.media.controller.Library, wp.media.view.l10n),
            t = wp.media.view.Frame,
            importData = null,
            er_pagination = 1,
            tabTitle = 'Unsplash Photos',
            defaultSearchTerm = '';

        wp.media.view.Element_Ready_AttachmentsBrowser = t.extend({
            tagName: "div",
            id: "element-ready-unsplash",
            className: "er-unsplash-photos-browser element-ready-unsplash",
            initialize: function() {
                er_pagination = 1;
                importData = this;
                element_ready_unplash_load_content('list');


            }
        }), e.prototype.bindHandlers = function() {
            this.on("router:create:browse", this.createRouter, this), this.on("router:render:browse", this.browseRouter, this), this.on("content:create:browse", this.browseContent, this), this.on("content:create:ElementReadygallery", this.ElementReadygallery, this), this.on("content:render:upload", this.uploadContent, this), this.on("toolbar:create:select", this.createSelectToolbar, this)
        }, e.prototype.browseRouter = function(e) {
            var t = {};
            t.upload = {
                    text: i.uploadFilesTitle,
                    priority: 19
                }, t.browse = {
                    text: i.mediaLibraryTitle,
                    priority: 42
                }, t.ElementReadygallery = {
                    text: tabTitle,
                    priority: 62
                },
                e.set(t);

        }, e.prototype.ElementReadygallery = function(e) {
            var t = this.state();
            e.view = new wp.media.view.Element_Ready_AttachmentsBrowser({
                controller: this,
                model: t,
                AttachmentView: t.get("AttachmentView")
            });

        };

    } //endif

    function element_ready_unplash_load_content(type = 'list', q = '', page = 1) {

        var params = { action: 'element_ready_get_unsplash', type: type, page: page };

        if (type == 'search') {
            params['q'] = q;
        }

        var body_str = $.param(params);
        fetch(ermedia.ajaxurl, {
                method: 'POST',
                headers: new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' }),
                body: body_str
            })
            .then(response => response.json())
            .then((tmpl) => {
                var template = wp.template('element-ready-pro-gallary-home');
                importData.$el.html(template({ images: tmpl.data.results }));
            })
            .catch(function(error) {

                console.log(error);
            });

    }


    $(document).on("click", "#er-unsplash-search-ubtn-", function() {
        er_paginatio = 1;
        let that = $(this);
        defaultSearchTerm = that.prev(".er-unsplash-search").val();

        that.prev(".er-unsplash-search").css("background", "#e1e1e1");
        element_ready_unplash_load_content('search', defaultSearchTerm, er_pagination);

    });

    $(document).on('click', '.element-ready-unsplash-remote-image', function() {

        let image_id = $(this).find('img').data('id');
        var params = { action: 'element_ready_get_unsplash', type: 'single', 'id': image_id };

        var body_str = $.param(params);
        fetch(ermedia.ajaxurl, {
                method: 'POST',
                headers: new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' }),
                body: body_str
            })
            .then(response => response.json())
            .then((tmpl) => {

                var template = wp.template('element-ready-pro-gallary-unsplash-single-image');

                importData.$el.html(template({ image_id: image_id, image: tmpl.data }));

            })
            .catch(function(error) {

                console.log(error);
            });

    });

    $(document).on('click', '.element-ready-unsplash-back-btn', function() {
        er_pagination = 1;
        element_ready_unplash_load_content('list');
    });

    $(document).on('click', '.er-unsplash-insert-button', function(e) {

        let inser_btn = $(this);
        let loader = null;
        let image_size = null;
        let image = inser_btn.attr('data-src');
        image_size = inser_btn.prev(".er-unsplash-image-size").val();
        loader = inser_btn.next(".er-loader-img");
        loader.show();
        loader.find(".er-loader-status").text('Downloading image from unsplash');

        let _params = { action: 'save_er_unsplash_media', size: image_size, 'src': 'unsplash', image: image };

        if (ermedia.hasOwnProperty('post_id')) {
            _params['post_id'] = ermedia.post_id.ID;
        }

        let body__str = $.param(_params);
        fetch(ermedia.ajaxurl, {
                method: 'POST',
                headers: new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' }),
                body: body__str
            })
            .then(response => response.json())
            .then((data) => {

                loader.find(".er-loader-status").text('Done');
                importData.model.get("selection").add(data.data.attachmentData)
                importData.model.frame.trigger("library:selection:add")
                let buttons = document.querySelectorAll(".media-toolbar .media-toolbar-primary .media-button-select")
                buttons[buttons.length - 1].click()
                element_ready_unplash_load_content('list');
                er_pagination = 1;
            })
            .catch(function(error) {
                loader.find(".er-loader-status").text('File Download fail ').css({ 'color': 'red' });
                loader.find('img').hide();

            });
  });

    $(document).on('click', '#er-pro-unsplash-next-ubtn-', function() {

        if (defaultSearchTerm.length > 1) {

            element_ready_unplash_load_content('search', defaultSearchTerm, ++er_pagination);
        } else {
            element_ready_unplash_load_content('list', '', ++er_pagination);
        }

    });

    $(document).on('mouseover', '.er-unsplash-photos-browser', function() {
        $('.media-frame-toolbar .media-button-select').prop('disabled', true);
    });
    
   
    $(document).on('click','.element-ready-admin-notice-remote .notice-dismiss',function(){
        $(this).parent('.element-ready-admin-notice-remote').hide();
    });
   
    // feature request form
    $(document).on('click', '#innner-dsh-form-feature-request .submit-button', function(e){
        var form_data = $('#innner-dsh-form-feature-request').serialize();
        var $fullname = $('#innner-dsh-form-feature-request input[name=fullname]');
        var $email = $('#innner-dsh-form-feature-request input[name=email]');
        var $desc = $('#innner-dsh-form-feature-request textarea');
     
        if($fullname.val().length < 2){
            $fullname.focus();
            return;
        }

        if(!$email.val().is_email()){
            $email.focus(); 
            return;
        }
        if($desc.val().length < 2){
            $desc.focus();
            return;
        }

        $.ajax({
            url     : 'https://quomodosoft.com/wp-json/elementsready/v6/feature-request',
            async   : true,
            dataType: 'json',
            type    : 'POST',
            data    : form_data,
        }).done(function(response) {
            if(response.code == 200){
              $('#innner-dsh-form-feature-request').append('<h2 class="er-dsah-form-message">' + response.message + '</h2>');  
              setTimeout(function(){
                $('#innner-dsh-form-feature-request').find('.er-dsah-form-message').remove();
              },5000);
            }
        }).fail(function(xhr, status, error) {
           // Handle Failure
        });

    });

  
})(jQuery);

