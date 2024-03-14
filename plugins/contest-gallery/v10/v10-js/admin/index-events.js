jQuery(document).ready(function($){

    var $wpBodyContent = jQuery('#wpbody-content');
    var $cg_main_container = jQuery('#cg_main_container');
    var $cgGoTopOptions = jQuery('#cgGoTopOptions');

    cgJsClassAdmin.index.functions.cgSetVersionForUrlJs($('#cgGetVersionForUrlJs').val());

    cgJsClassAdmin.index.vars.$wpBodyContent = $wpBodyContent;
    cgJsClassAdmin.index.vars.$cg_main_container = $cg_main_container;
    cgJsClassAdmin.index.vars.$cgGoTopOptions = $cgGoTopOptions;
    cgJsClassAdmin.index.vars.windowHeight = $(window).height();
    cgJsClassAdmin.index.functions.resize(cgJsClassAdmin.index.vars.$wpBodyContent,cgJsClassAdmin.index.vars.$cg_main_container);

    $( window ).resize(function(e) {
        cgJsClassAdmin.index.vars.windowHeight = $(window).height();
        cgJsClassAdmin.index.functions.resize(cgJsClassAdmin.index.vars.$wpBodyContent,cgJsClassAdmin.index.vars.$cg_main_container);
    });

    // if user click on wordpress collapse menu
    $( document ).on('click','#collapse-menu',function (e) {
        cgJsClassAdmin.index.vars.windowHeight = $(window).height();
        cgJsClassAdmin.index.functions.resize(cgJsClassAdmin.index.vars.$wpBodyContent,cgJsClassAdmin.index.vars.$cg_main_container);
    });

        // since 25.12.2020, simple version check, no localStorage or IndexedDB check anymore
    //cgJsClassAdmin.index.vars.cgVersionLocalStorageName = 'cgVersionLocalStorage'+'/'+location.hostname+location.pathname;

    cgJsClassAdmin.index.functions.checkIfIsIE();

    // since 25.12.2020, simple version check, no localStorage or IndexedDB check anymore
    //cgJsClassAdmin.index.indexeddb.init();

    cgJsClassAdmin.index.vars.wpVersion = cgJsClassAdmin.index.functions.getWpVersionAsInteger();

    if(location.search.indexOf('index.php&')>-1){
        var searchToReplace = location.search.replace('index.php&','index.php#');
        window.history.replaceState(null, null, searchToReplace);
    }

    if(location.hash!='' && location.hash!='#' && location.hash!='#main'){// then must be browser reload by user

        var cgBackendHashVal = cgJsClassAdmin.index.functions.cgLoadBackendLoader();

        var formPostData = new FormData();
        formPostData.append('action', 'post_contest_gallery_action_ajax');
        formPostData.append('cgBackendHash',cgBackendHashVal);

        cgJsClassAdmin.index.functions.cgLoadBackendAjax('?page='+cgJsClassAdmin.index.functions.cgGetVersionForUrlJs()+'/index.php&'+location.hash.split('#')[1],formPostData);

    }else{// then must be main menu load

        var cgBackendHashVal = cgJsClassAdmin.index.functions.cgLoadBackendLoader();

        var formPostData = new FormData();
        formPostData.append('action', 'post_contest_gallery_action_ajax');
        formPostData.append('cgBackendHash',cgBackendHashVal);

        if(location.search.indexOf('option_id') >= 0 && location.search.indexOf('index.php&') >= 0){
            cgJsClassAdmin.gallery.vars.isHashJustChanged = true;
            cgJsClassAdmin.index.functions.cgLoadBackendAjax('?page='+cgJsClassAdmin.index.functions.cgGetVersionForUrlJs()+'/index.php&'+location.search.split('index.php&')[1],formPostData);
        }else{
            cgJsClassAdmin.index.functions.cgLoadBackendAjax('?page='+cgJsClassAdmin.index.functions.cgGetVersionForUrlJs()+'/index.php',formPostData);
        }

    }

    window.onhashchange = function() {
        if(cgJsClassAdmin.gallery.vars.isHashJustChanged){
            cgJsClassAdmin.gallery.vars.isHashJustChanged = false;
            return;
        }else{
            var formPostData = new FormData();
            formPostData.append('action', 'post_contest_gallery_action_ajax');
            formPostData.append('cgBackendHash',$('#cgBackendHash').val());
            if(location.hash.split('#')[1]){
                cgJsClassAdmin.index.functions.cgLoadBackendAjax('?page='+cgJsClassAdmin.index.functions.cgGetVersionForUrlJs()+'/index.php&'+location.hash.split('#')[1],formPostData);
            }else{
                cgJsClassAdmin.index.functions.cgLoadBackendAjax('?page='+cgJsClassAdmin.index.functions.cgGetVersionForUrlJs()+'/index.php',formPostData);
            }
        }
    };

    $(document).on('submit','.cg_load_backend_submit',function (e) {
        e.preventDefault();
        $(window).scrollTop(0);
        cgJsClassAdmin.index.functions.cgLoadBackend($(this),$(this).hasClass('cg_load_backend_submit_save_data'));

    });

    $(document).on('click','.cg_load_backend_link',function (e) {
        e.preventDefault();
        cgJsClassAdmin.index.functions.cgLoadBackend($(this));

    });


});