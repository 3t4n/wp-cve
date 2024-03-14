var cgJsClassAdmin = cgJsClassAdmin || {};
cgJsClassAdmin.index = {};

cgJsClassAdmin.index.vars = {
    // since 25.12.2020, simple version check, no localStorage or IndexedDB check anymore
    //cgVersionLocalStorageName: '',
    isIE: false,
    isShortcodeIntervalDatetpickerLoaded: false,
    wpVersion: '',
    wpVersionForTinyMCE: 480, // as integer (4.8)
    cgVersion: 0, // cgVersionCurrent will be set after first backend load
    cgVersionForUrlJs: '', // cgVersionForUrlJs will be set after first backend load,
    isOptionsAreaLoaded: '',
    isCreateUploadAreaLoaded: '',
    cgOptionsJson: null,
    isShortcodeIntervalConfActive: {}
};

cgJsClassAdmin.index.functions = {
    resize: function ($wpBodyContent,$cg_main_container) {
        if($wpBodyContent.width()>=1000){
            var marginLeft=($wpBodyContent.width()-1000)/2;
            if($wpBodyContent.width()>1200){
                marginLeft = marginLeft - 35;
            }
            $cg_main_container.css('width','');
            $cg_main_container.css('margin-left',marginLeft+'px');
        }else if($wpBodyContent.width()<1000 && $wpBodyContent.width()>=790){
            $cg_main_container.width($wpBodyContent.width());
            $cg_main_container.css('margin-left','5px');
        }else if($wpBodyContent.width()<790){
            $cg_main_container.width(790);
            $cg_main_container.css('margin-left','0');
        }
    },
    load: function () {

    },
    cgMainContainerEmpty: function ($cg_main_container) {
        $cg_main_container.find('*').each(function (){
            if(jQuery(this).hasClass('cg_do_not_remove_when_main_empty') ||
                jQuery(this).find('.cg_do_not_remove_when_main_empty').length ||
                jQuery(this).closest('.cg_do_not_remove_when_main_empty').length
            ){

            }else{
                jQuery(this).remove();
            }
        });
    },
    cgLoadBackendLoader: function (isShowNoLoader,isDoNotEmptyContent) {

        var $cg_main_container = jQuery('#cg_main_container');

        // do this first, it will be removed when empty
        var cgBackendHashVal = $cg_main_container.find('#cgBackendHash').val();

        if(!isDoNotEmptyContent){
            $cg_main_container.find('*').each(function (){
                if(jQuery(this).hasClass('cg_do_not_remove_when_ajax_load_ignore')){
                    jQuery(this).remove();
                }else if(jQuery(this).hasClass('cg_do_not_remove_when_ajax_load') ||
                    jQuery(this).find('.cg_do_not_remove_when_ajax_load').length ||
                    jQuery(this).closest('.cg_do_not_remove_when_ajax_load').length
                ){

                }else{
                    jQuery(this).remove();
                }
            });
        }

        if(!$cg_main_container.find('#cgBackendLoader').length){
            var $cgBackendLoader = "<div id='cgBackendLoader' class='cg-skeleton-box-container'>"+
                "<div class='cg-skeleton-box' style='height:50px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "<div class='cg-skeleton-box' style='height:100px;'></div>"+
                "</div>";
            $cg_main_container.append($cgBackendLoader);
        }

        return cgBackendHashVal;
    },
    cgLoadBackend: function ($formLinkObject,isSaveData,isShowNoLoader,isDoNotEmptyContent,isCopyGallery) {

        var cg_picture_id_to_scroll = 0;

        if($formLinkObject.hasClass('cg_load_backend_link_back_to_gallery') && jQuery('#cgVotes').length){
            var cg_picture_id_to_scroll = jQuery('#cg_picture_id').val();
        }

        if($formLinkObject.hasClass('cg_load_backend_link_back_to_gallery') && jQuery('#cgShowCommentsPicture').length){
            var cg_picture_id_to_scroll = jQuery('#cg_picture_id').val();
        }

        var urlString = '';

        var GET_Data;

        var submitMessage = '';

        // clone because the original will be removed on loader load
        //var $formLinkObject = $formLinkObject.clone();

        if($formLinkObject.is('form')){
            if($formLinkObject.attr('data-cg-submit-message')){
                submitMessage = $formLinkObject.attr('data-cg-submit-message');
            }
            GET_Data = $formLinkObject.attr('action');
        }else{// then must be link
            GET_Data = $formLinkObject.attr('href');
        }

        // hide and move form object because the container will be emptied
     //   $formLinkObject.addClass('cg_hide');
        //jQuery('body').append($formLinkObject);

        GET_Data = GET_Data.replace(/(\r\n|\n|\r)/gm, "");// replace linebreaks
        GET_Data = GET_Data.replace(/\s/g, "");// replace empty space

        if(GET_Data){
            urlString = GET_Data;
        }
        var form = $formLinkObject.get(0);

        var formPostData;

        if($formLinkObject.is('form')){
            formPostData = new FormData(form);
        }else{// then must be link
            formPostData = new FormData();
        }

        formPostData.append('action', 'post_contest_gallery_action_ajax');

        if(!isCopyGallery){
            // execute here, because all content will be removed
            var cgBackendHashVal = cgJsClassAdmin.index.functions.cgLoadBackendLoader(isShowNoLoader,isDoNotEmptyContent);
        }else{
            var $cg_main_container = jQuery('#cg_main_container');
            var cgBackendHashVal = $cg_main_container.find('#cgBackendHash').val();
        }

        formPostData.append('cgBackendHash',cgBackendHashVal);

        // remove hash from string if exists
        if(urlString.indexOf('#')>=0){
            urlString = urlString.split('#')[0];
        }

        if(!isSaveData){
            cgJsClassAdmin.gallery.vars.isHashJustChanged = true;
            if(urlString.split('?page='+cgJsClassAdmin.index.functions.cgGetVersionForUrlJs()+'/index.php&')[1]){
                location.hash = '#'+urlString.split('?page='+cgJsClassAdmin.index.functions.cgGetVersionForUrlJs()+'/index.php&')[1];
            }else{
                location.hash = '';
            }
            //var newUrlForHistory = location.protocol + '//' + location.host + location.pathname + location.search + location.hash;
            //window.location.href = newUrlForHistory;
        }
        // window.history.replaceState({'action':urlString},'',location.pathname+location.hash);
        cgJsClassAdmin.index.functions.cgLoadBackendAjax(urlString,formPostData,$formLinkObject,submitMessage,cg_picture_id_to_scroll);
    },
    cgGetVersionForUrlJs: function (){
        return cgJsClassAdmin.index.vars.cgVersionForUrlJs;
    },
    cgSetVersionForUrlJs: function (cgVersionForUrlJs){
        cgJsClassAdmin.index.vars.cgVersionForUrlJs = cgVersionForUrlJs;
    },
    isInvalidNonce: function ($,response){
        if(response.indexOf('cg_nonce_invalid')>-1){
            // This prevents the page from scrolling down to where it was previously.
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
            // This is needed if the user scrolls down during page load and you want to make sure the page is scrolled to the top once it's fully loaded. This has Cross-browser support.
            window.scrollTo(0,0);
            jQuery('body').addClass('cg_pointer_events_none cg_overflow_y_hidden');
            $('#cg_main_container').prepend('<p id="cgNewGalleryVersionDetected">WP nonce security token not set or not valid anymore. You will be forwarded...</p>');
            var version = response.split('###cg_version###')[1];
            setTimeout(function (){
                location.href = '?page='+version+'/index.php';
            },5000);
            return true;
        }
        return false;
    },
    setCgNonce: function ($){

        var version = cgJsClassAdmin.index.functions.cgGetVersionForUrlJs();
        var cg_nonce = $('#cg_nonce').val();
        var $cg_main_container = $('#cg_main_container');

        $cg_main_container.find('a[href*="page='+version+'/index.php"]').each(function (){
            var href = $(this).attr('href');
            if(href.indexOf('cg_nonce=')===-1){
                var hrefWithNonce = href+'&cg_nonce='+cg_nonce;
                $(this).attr('href',hrefWithNonce);
            }
        });

        $cg_main_container.find('form').each(function (){
            if($(this).find('#cgNonce').length){
                $(this).find('#cgNonce').replaceWith($('<input type="hidden" id="cgNonce" name="cg_nonce" value="'+cg_nonce+'" />'))
            }else{
                $(this).prepend($('<input type="hidden" id="cgNonce" name="cg_nonce" value="'+cg_nonce+'" />'))
            }
            var action = $(this).attr('action');
            if(action.indexOf('cg_nonce=')===-1){
                var hrefWithNonce = action+'&cg_nonce='+cg_nonce;
                $(this).attr('action',hrefWithNonce);
            }
        });
    },
    newVersionReload: function () {

        var $cg_main_container = jQuery('#cg_main_container');

        $cg_main_container.prepend('<p id="cgNewGalleryVersionDetected">New Contest Gallery version detected. Page needs to be reloaded. <br>Reload will be initiated ...</p>');

        jQuery('body').get(0).scrollIntoView();
        jQuery('body').addClass('cg_pointer_events_none cg_overflow_y_hidden');

        setTimeout(function () {
            // set always this as backup! Before set in indexedDB
            // since 25.12.2020, simple version check, no localStorage or IndexedDB check anymore
            //localStorage.setItem(cgJsClassAdmin.index.vars.cgVersionLocalStorageName, cgVersionCurrent);
            //cgJsClassAdmin.index.indexeddb.setAdminData(cgVersionCurrent,true);
            location.reload();
        },4000);
    },
    versionToLowForTinymce: '<p class="cg-version-to-low-for-tinymce">WordPress version 4.8 and higher is required to display this textarea as modern TinyMCE editor</p>',
    getWpVersionAsInteger: function () {

        var wpVersion = jQuery('#cgWordPressVersion').val();
        var wpVersionInt = parseInt(wpVersion.replace('.','').replace('.','').replace('.','').replace('.','').replace('.','').replace('-RC1',''));
        if(wpVersionInt.toString().length==1){// then must be version like 5. Add further 00;
            wpVersionInt = wpVersionInt*100;
        }else if(wpVersionInt.toString().length==2){// then must be version like 5-5. Add further 0;
            wpVersionInt = parseInt(parseInt(wpVersionInt)*10);
        }

        return wpVersionInt;

    },
    initializeEditor: function(id){
  /*      console.trace();
        debugger*/

        if(cgJsClassAdmin.index.functions.getWpVersionAsInteger()>=cgJsClassAdmin.index.vars.wpVersionForTinyMCE){// then tinymce can be initialized

            if(wp.editor.hasOwnProperty('remove') && wp.editor.hasOwnProperty('initialize')){
            wp.editor.remove(id);
            wp.editor.initialize(id, {
                tinymce: true,
                quicktags: true
            });
            }else{
                wp.oldEditor.remove(id);
                wp.oldEditor.initialize(id, {
                    tinymce: true,
                    quicktags: true
                });
            }

        }else{
           // setTimeout(function () {
                jQuery(cgJsClassAdmin.index.functions.versionToLowForTinymce).insertAfter('#'+id);// have to be id, does not work with object!
               // debugger
           // },100);
        }

        setTimeout(function (){
            jQuery('.wp-editor-wrap').find('iframe').css('height','100px');
        },100);

    },
    setEditors: function($, $textareas){

        if(cgJsClassAdmin.index.functions.getWpVersionAsInteger()>=cgJsClassAdmin.index.vars.wpVersionForTinyMCE){// then tinymce can be initialized

            var i = 0;
            $textareas.each(function () {// do only for visible first
                var $element = $(this);
                setTimeout(function (){
                    cgJsClassAdmin.index.functions.initializeEditor($element.attr('id'));
                },i)
                i++;
            });

            console.log('textareas');
            console.log(i);

        }else{// let textarea as textarea and show message

            $textareas.each(function () {// do only for visible first

                $(cgJsClassAdmin.index.functions.versionToLowForTinymce).insertAfter('#'+$(this).attr('id'));// have to be id, does not work with object!

            });

        }

        /*        setTimeout(function (){
                    jQuery('.wp-editor-wrap').find('iframe').css('height','100px');
                },100);*/

    },
    checkIfIsIE: function () {

        try{

            // checks if edge or ie !

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))  // If Internet Explorer, return version number
            {
                cgJsClassAdmin.index.vars.isIE = true;
            }

        }catch(e){

        }

    },
    noteIfIsIE: function () {

        if(cgJsClassAdmin.index.vars.isIE){
            jQuery('#cgIeWarning').remove();
            jQuery('#cg_main_container').prepend('<p id="cgIeWarning" style="width:100%;text-align:center;">' +
                '<b>You are using Internet Explorer which will not be supported anymore by Microsoft.</b><br>' +
                'For proper backend functionality please use latest versions of currently supported browsers:<br>' +
                'Chrome, Firefox, Edge, Opera' +
                '</p>');
        }

    },
    correctBrowserHistoryState: function () {

        history.replaceState && history.replaceState(
            null, '', location.pathname + location.search.replace(/[\?&]cg_go_to=[^&]+/, '').replace(/^&/, '?')
        );

    },
    cgGoTo: function ($cgTranslationOther,timeout,isDoNotScrollTo,scrollOffset) {

        if(!scrollOffset){
            scrollOffset = 300;
        }

        if(!timeout){
            timeout = 0;
        }

        setTimeout(function (){
              //  console.log('1');
            //    console.trace();
            if(!isDoNotScrollTo){
                jQuery('html, body').animate({
                    scrollTop: $cgTranslationOther.offset().top - scrollOffset+'px'
                }, 0, function () {
                    //   jQuery('body,html').addClass('cg_no_scroll');
                    //    alert(3);
/*                    if(isRemoveScroll){
                        jQuery('body,html').addClass('cg_no_scroll');
                    }*/
                });
            }
            $cgTranslationOther.addClass('cg_blink');
            setTimeout(function (){
                   // jQuery('body,html').removeClass('cg_no_scroll');
                $cgTranslationOther.removeClass('cg_blink');
/*                if(isRemoveScroll){
                    setTimeout(function (){
                        jQuery('html, body').animate({
                            scrollTop: $cgTranslationOther.offset().top - 300+'px'
                        }, 0, function () {
                            //   jQuery('body,html').addClass('cg_no_scroll');
                            //    alert(3);
                            jQuery('body,html').removeClass('cg_no_scroll');
                        });
                    },1000);
                }*/
            },2000);
        },timeout);

    }
};
