function acwp_hasClass(ele,cls) {
    return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
if( acwp_hasClass(document.getElementsByTagName('body')[0], 'accessiblewp_page_accessiblewp-toolbar') ){
    document.getElementById('acwp_readable_font').onchange = function() {
        var index = this.selectedIndex;
        var inputVal = this.children[index].value;

        if( inputVal == 'custom' )
            document.getElementById('acwp-row-readable-custom').style.display = 'table-row';
        else
            document.getElementById('acwp-row-readable-custom').style.display = 'none';
    }
    
    document.getElementById('acwp_contrast_mode').onchange = function() {
        var index = this.selectedIndex;
        var inputVal = this.children[index].value;

        if( inputVal == 'js' )
            document.getElementById('acwp-row-contrast-exclude').style.display = 'table-row';
        else
            document.getElementById('acwp-row-contrast-exclude').style.display = 'none';
    }
    
    document.getElementById('acwp_contrast_custom').onclick = function() {

        if( this.checked == true ) {
            document.getElementById('acwp-contrast-bgcolor-row').style.display = 'table-row';
            document.getElementById('acwp-contrast-txtcolor-row').style.display = 'table-row';
            document.getElementById('acwp-contrast-linkscolor-row').style.display = 'table-row';
        }
        else {
            document.getElementById('acwp-contrast-bgcolor-row').style.display = 'none';
            document.getElementById('acwp-contrast-txtcolor-row').style.display = 'none';
            document.getElementById('acwp-contrast-linkscolor-row').style.display = 'none';
        }
    }
    if( document.getElementById('acwp_contrast_custom').checked == true ) {
        document.getElementById('acwp-contrast-bgcolor-row').style.display = 'table-row';
        document.getElementById('acwp-contrast-txtcolor-row').style.display = 'table-row';
        document.getElementById('acwp-contrast-linkscolor-row').style.display = 'table-row';
    }
    
    ////
    
    document.getElementById('acwp_custom_color_allow').onclick = function() {

        if( this.checked == true ) {
            document.getElementById('acwp-toolbar-custom-color').style.display = 'table-row';
        }
        else {
            document.getElementById('acwp-toolbar-custom-color').style.display = 'none';
        }
    }
    if( document.getElementById('acwp_custom_color_allow').checked == true ) {
        document.getElementById('acwp-toolbar-custom-color').style.display = 'table-row';
    }
    
    ///

    document.getElementById('acwp_fontsize_customtags').onclick = function() {

        if( this.checked == true ) {
            document.getElementById('acwp-fontsize-tags-row').style.display = 'table-row';
        }
        else {
            document.getElementById('acwp-fontsize-tags-row').style.display = 'none';
        }
    }
    if( document.getElementById('acwp_fontsize_customtags').checked == true ) {
        document.getElementById('acwp-fontsize-tags-row').style.display = 'table-row';
    }
    
    document.getElementById('acwp_fontsize_customexcludetags').onclick = function() {

        if( this.checked == true ) {
            document.getElementById('acwp-fontsize-excludetags-row').style.display = 'table-row';
        }
        else {
            document.getElementById('acwp-fontsize-excludetags-row').style.display = 'none';
        }
    }
    if( document.getElementById('acwp_fontsize_customexcludetags').checked == true ) {
        document.getElementById('acwp-fontsize-excludetags-row').style.display = 'table-row';
    }

    document.getElementById('acwp_titles_customcolors').onclick = function() {

        if( this.checked == true ) {
            document.getElementById('acwp-titles-bg-row').style.display = 'table-row';
            document.getElementById('acwp-titles-txt-row').style.display = 'table-row';
        }
        else {
            document.getElementById('acwp-titles-bg-row').style.display = 'none';
            document.getElementById('acwp-titles-txt-row').style.display = 'none';
        }
    }
    if( document.getElementById('acwp_titles_customcolors').checked == true ) {
        document.getElementById('acwp-titles-bg-row').style.display = 'table-row';
        document.getElementById('acwp-titles-txt-row').style.display = 'table-row';
    }
    

    jQuery(document).ready(function($){
        
        $('#acwp_connect_api').click(function(){
           var token = $('#acwp_toolbar_token').val();
           var email = $('#acwp_toolbar_tokenemail').val();

           $.ajax({
            url: 'https://accessible-wp.com/wp-json/accessiblewp/v1/connect',
            type: 'POST',
            data: jQuery.param({
                'email': email, 
                'token' : token,
                'url': window.location.href,
            }),
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function (response) {
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        action: 'acwp_toolbar_connect_callback', 
                        'success' : true,
                        'res' : response,
                    },
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {
                        if( response['data']['req']['set_active'] === 'true' ){
                            $('#acwp_toolbar_api_status').addClass('acwp-active');
                            $('#acwp_toolbar_api_status > .acwp-indicator-label').text('Active');
                        } else {
                            $('#acwp_toolbar_api_status.acwp-active').removeClass('acwp-active');
                            $('#acwp_toolbar_api_status > .acwp-indicator-label').text('Not active');
                        }
                            console.log(response['data']['req']['set_active']);
                    },
                    error: function () {
                        alert("error 2");
                    }
                });
            },
            error: function () {
                alert("error 1");
            }

            });
        });

        $('#accessible-wp-toolbar .nav-tab').click(function (e) {
            e.preventDefault();
            var tab = $(this).attr('href');

            $('.acwp-tab').each(function () {
                $(this).removeClass('active');
            });

            $(tab).addClass('active');

            $('.nav-tab').each(function () {
                $(this).removeClass('nav-tab-active');
            });

            $(this).addClass('nav-tab-active');
        });

        // Activate wp color picker
        $('.color-field').each(function(){
            $(this).wpColorPicker({
                defaultColor: '#1E7AB9',
                palettes: ['#000', '#454545', '#1E7AB9', '#b91e1e', '#298361'],
            });
        });

        if( $('#acwp_readable_font :selected').val() == 'custom' )
            document.getElementById('acwp-row-readable-custom').style.display = 'table-row';
        
        if( $('#acwp_contrast_mode :selected').val() == 'js' )
            document.getElementById('acwp-row-contrast-exclude').style.display = 'table-row';
    });
}