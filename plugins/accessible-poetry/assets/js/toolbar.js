
function acp_createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/; SameSite=Lax";
}

function acp_readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function acp_eraseCookie(name) {
    acp_createCookie(name, "", -1);
}



jQuery(document).ready(function ($) {
    
    setTimeout(function () {
        $('#acwp-toolbar-btn-wrap').addClass('show');
    }, 250);
    
    // Hide text sizer
    // const hideTextSizer = acwp_attr.hide_fontsize;

    // Should we use cookies?
    const noCookies = acwp_attr.nocookies;
    
    // get line height option
    const noLineHeight = acwp_attr.fontsizer_nolineheight;
    
    // get our custom tags if there's
    const customtags_option = acwp_attr.fontsizer_customtags;
    const customTags = customtags_option !== '' ? customtags_option : 'p,h1,h2,h3,h4,h5,h6,label';

    // exclude
    const customexlcude_option = acwp_attr.fontsize_excludetags;
    
    // Increase font size
    const IncreseFont = (toggle) => {

        // set the font size distance
        let newSize = acwp_attr.fontsizer_max != '' ? parseFloat( acwp_attr.fontsizer_max ) / 100 : 1.6;

        // get increase checkbox
        let incCheckbox = document.getElementById('acwp-toggler-incfont');

        // get decrease checkbox
        let decCheckbox = document.getElementById('acwp-toggler-decfont');

        if( decCheckbox.checked == true )
            decCheckbox.checked = false;

        if( toggle ){
            // Add toolbar class
            jQuery('#acwp-toolbar').addClass('incresed');
                        
            // Change font size by tags
            jQuery(customTags).not(customexlcude_option).each(function () {
                
                // get current font size
                let fontSize = jQuery(this).css('font-size');
                
                // set default font size as data attr
                if( !jQuery(this).data('acwp-fontsize') )
                    jQuery(this).attr('data-acwp-fontsize', fontSize.substring(0, fontSize.length - 2));
                
                // change the size
                jQuery(this).css('font-size', parseInt(fontSize) * newSize + 'px');

                if( noLineHeight !== 'yes' ){
                    
                    // get the default line height
                    let lineHeight = jQuery(this).css('line-height');
                    
                    // set default line height as data attr
                    if( !jQuery(this).data('acwp-lineheight') )
                        jQuery(this).attr('data-acwp-lineheight', lineHeight.substring(0, lineHeight.length - 2));
                    
                    // change the size
                    jQuery(this).css('line-height', 'normal');
                }
            });
        } else {
            if( incCheckbox.checked !== true ) {
            
                // Add toolbar class
                jQuery('#acwp-toolbar').addClass('incresed');
                
                // Change font size by tags
                jQuery(customTags).not(customexlcude_option).each(function () {
                    
                    // get current font size
                    let fontSize = jQuery(this).css('font-size');
                    
                    // set default font size as data attr
                    if( !jQuery(this).data('acwp-fontsize') )
                        jQuery(this).attr('data-acwp-fontsize', fontSize.substring(0, fontSize.length - 2));
                    
                    // change the size
                    jQuery(this).css('font-size', parseInt(fontSize) * newSize + 'px');
    
                    if( noLineHeight !== 'yes' ){
                        
                        // get the default line height
                        let lineHeight = jQuery(this).css('line-height');
                        
                        // set default line height as data attr
                        if( !jQuery(this).data('acwp-lineheight') )
                            jQuery(this).attr('data-acwp-lineheight', lineHeight.substring(0, lineHeight.length - 2));
                        
                        // change the size
                        jQuery(this).css('line-height', 'normal');
                    }
                });
            } else {
                // Add toolbar class
                jQuery('#acwp-toolbar').removeClass('incresed');
                // change size by tag
                jQuery(customTags).not(customexlcude_option).each(function () {
                    
                    // get default size
                    let fontSize = jQuery(this).data('acwp-fontsize');
                    
                    // change the size
                    jQuery(this).css('font-size', parseInt(fontSize) + 'px');
                    
                    // set line height
                    if(noLineHeight !== 'yes'){
                        let lineHeight = jQuery(this).data('acwp-lineheight');
                        jQuery(this).css('line-height', parseInt(lineHeight) + 'px');
                    }
                });
            }
        }
    }

    // Decrease font size
    const DecreaseFont = (toggle) => {
        // set the new size
        let newSize = (acwp_attr.fontsizer_min !== '') ? parseFloat(acwp_attr.fontsizer_min) / 100 : 0.8;
        
        // get increase checkbox
        let incfontCheckbox = document.getElementById('acwp-toggler-incfont');

        // toggle increase checkbox
        if( incfontCheckbox.checked == true ) {
            incfontCheckbox.checked = false;
        }
        if( toggle ){
            jQuery(customTags).each(function () {
                let fontSize = jQuery(this).css('font-size');

                if( !jQuery(this).data('acwp-fontsize') )
                    jQuery(this).attr('data-acwp-fontsize', fontSize.substring(0, fontSize.length - 2));
                
                jQuery(this).css('font-size', parseInt(fontSize) * newSize + 'px');

                if(noLineHeight !== 'yes'){
                    let lineHeight = jQuery(this).css('line-height');
                    if( !jQuery(this).data('acwp-lineheight') )
                        jQuery(this).attr('data-acwp-lineheight', lineHeight.substring(0, lineHeight.length - 2));
                    jQuery(this).css('line-height', 'normal');
                }
            });
        } else {
            // decrease checkbox
            var checkbox = document.getElementById('acwp-toggler-decfont');
                    
            if( checkbox.checked !== true ) {
                jQuery(customTags).each(function () {
                    let fontSize = jQuery(this).css('font-size');

                    if( !jQuery(this).data('acwp-fontsize') )
                        jQuery(this).attr('data-acwp-fontsize', fontSize.substring(0, fontSize.length - 2));
                    jQuery(this).css('font-size', parseInt(fontSize) * newSize + 'px');

                    if(noLineHeight !== 'yes'){
                        let lineHeight = jQuery(this).css('line-height');
                        if( !jQuery(this).data('acwp-lineheight') )
                            jQuery(this).attr('data-acwp-lineheight', lineHeight.substring(0, lineHeight.length - 2));
                        jQuery(this).css('line-height', 'normal');
                    }
                });
            }
            else {
                jQuery(customTags).each(function () {
                    let fontSize = jQuery(this).data('acwp-fontsize');
                    jQuery(this).css('font-size', parseInt(fontSize) + 'px');

                    if(noLineHeight !== 'yes'){
                        let lineHeight = jQuery(this).data('acwp-lineheight');
                        jQuery(this).css('line-height', parseInt(lineHeight) + 'px');
                    }
                });
            }
        }
        
    }
    
    // Toggle contrast
    const ToggleContrast = (toggle) => {
        
        // Get checkbox data
        var checkbox = document.getElementById('acwp-toggler-contrast');

        // Get excluded items
        var exclude = acwp_attr.contrast_exclude;

        if( toggle ){
            $('body').addClass('acwp-contrast');

            if( $('body').hasClass('acwp-contrast-js') ) {
                
                // imgs
                jQuery('body *').not(exclude).each(function () {
                    if( this.style.backgroundImage != '' )
                        jQuery(this).attr('data-acwp-bgimage', this.style.backgroundImage);
                    this.style.backgroundImage = 'none';
                });
                
                // bgs
                jQuery('body *').not(exclude).each(function () {
                    if( this.style.backgroundColor != '' )
                        jQuery(this).attr('data-acwp-bgcolor', this.style.backgroundColor);
                    this.style.backgroundColor = 'black';
                });
                // txt
                jQuery('body *').not(exclude).each(function () {
                    if( this.tagName == 'A' || this.tagName == 'BUTTON' || this.tagName == 'LABEL' ){
                        if( this.style.color != '' )
                            jQuery(this).not(exclude).attr('data-acwp-lnkcolor', this.style.color);
                        this.style.color = 'yellow';
                    }
                    else {
                        if( this.style.color != '' )
                            jQuery(this).not(exclude).attr('data-acwp-txtcolor', this.style.color);
                        this.style.color = 'white';
                    }
                });
            }
        } else {
            // If its checked and body doesnt have the class
            if( checkbox.checked !== true && !$('body').hasClass('acwp-contrast')) {
                $('body').addClass('acwp-contrast');

                if( $('body').hasClass('acwp-contrast-js') ) {
                    
                    // imgs
                    jQuery('body *').not(exclude).each(function () {
                        if( this.style.backgroundImage != '' )
                            jQuery(this).attr('data-acwp-bgimage', this.style.backgroundImage);
                        this.style.backgroundImage = 'none';
                    });
                    
                    // bgs
                    jQuery('body *').not(exclude).each(function () {
                        if( this.style.backgroundColor != '' )
                            jQuery(this).attr('data-acwp-bgcolor', this.style.backgroundColor);
                        this.style.backgroundColor = 'black';
                    });
                    // txt
                    jQuery('body *').not(exclude).each(function () {
                        if( this.tagName == 'A' || this.tagName == 'BUTTON' || this.tagName == 'LABEL' ){
                            if( this.style.color != '' )
                                jQuery(this).not(exclude).attr('data-acwp-lnkcolor', this.style.color);
                            this.style.color = 'yellow';
                        }
                        else {
                            if( this.style.color != '' )
                                jQuery(this).not(exclude).attr('data-acwp-txtcolor', this.style.color);
                            this.style.color = 'white';
                        }
                    });
                }
            }
            else {
                // imgs
                jQuery('body *').not(exclude).each(function () {
                    if( this.style.backgroundImage != '' )
                        this.style.backgroundImage = '';
                });
                jQuery('body [data-acwp-bgimage]').not(exclude).each(function () {
                    let bg = jQuery(this).attr('data-acwp-bgimage');
                    if( bg != '' )
                        this.style.backgroundImage = bg;
                });
                // bgs
                jQuery('body *').not(exclude).each(function () {
                    if( this.style.backgroundColor != '' )
                        this.style.backgroundColor = '';
                });
                jQuery('body [data-acwp-bgcolor]').not(exclude).each(function () {
                    let bg = jQuery(this).attr('data-acwp-bgcolor');
                    if( bg != '' )
                        this.style.backgroundColor = bg;
                });

                // txt
                jQuery('body *').not(exclude).each(function () {
                    if( this.tagName == 'a' || this.tagName == 'button' || this.tagName == 'label' ) {
                        let clr = jQuery(this).not(exclude).attr('data-acwp-lnkcolor');
                        if( clr != '' )
                            this.style.color = clr;
                        if( this.style.color != '' )
                            this.style.color = '';
                    }
                    else {
                        let clr = jQuery(this).not(exclude).attr('data-acwp-txtcolor');

                        if( this.style.color != '' )
                            this.style.color = '';
                        if( clr && clr != '' )
                            this.style.color = clr;
                    }
                });
                document.body.classList.remove('acwp-contrast');
            }
        }
        

        
    }
    
    if( noCookies !== 'yes' ){
        const stored_keyboard = acp_readCookie('keyboard');
        const stored_animations = acp_readCookie('animations');
        const stored_contrast = acp_readCookie('contrast');
        const stored_incfont = acp_readCookie('incfont');
        const stored_decfont = acp_readCookie('decfont');
        const stored_readable = acp_readCookie('readable');
        const stored_marktitles = acp_readCookie('marktitles');
        const stored_underline = acp_readCookie('underline');

        if( stored_keyboard === 'yes' ){
            var checkbox = document.getElementById('acwp-toggler-keyboard');
            checkbox.checked = true;
            $('body').addClass('acwp-keyboard');
        }
        if( stored_readable === 'yes' ){
            var checkbox = document.getElementById('acwp-toggler-readable');
            checkbox.checked = true;
            $('body').addClass('acwp-readable');
        }
        if( stored_marktitles === 'yes' ){
            var checkbox = document.getElementById('acwp-toggler-marktitles');
            checkbox.checked = true;
            $('body').addClass('acwp-marktitles');
        }
        if( stored_underline === 'yes' ){
            var checkbox = document.getElementById('acwp-toggler-underline');
            checkbox.checked = true;
            $('body').addClass('acwp-underline');
        }
        if( stored_animations === 'yes' ){
            var checkbox = document.getElementById('acwp-toggler-animations');
            checkbox.checked = true;
            $('body').addClass('acwp-animations');
        }
        if( stored_incfont === 'yes' ){
            var checkbox = document.getElementById('acwp-toggler-incfont');
            checkbox.checked = true;
            $('body').addClass('acwp-incfont');
            IncreseFont(true);
        }
        if( stored_decfont === 'yes' ){
            var checkbox = document.getElementById('acwp-toggler-decfont');
            checkbox.checked = true;
            $('body').addClass('acwp-decfont');
            DecreaseFont(true);
        }
        if( stored_contrast === 'yes' ){
            var checkbox = document.getElementById('acwp-toggler-contrast');
            checkbox.checked = true;
            ToggleContrast(true);
        }
    }
    
    $('.acwp-toggler label').each(function(){
        $(this).click(function(e){ 
            if( e.target.tagName === 'LABEL' ){
                const name = $(this).data('name');
                
                if( name !== 'contrast' ){

                    // get hidden input
                    var checkbox = document.getElementById('acwp-toggler-' + name);

                    // Toggle body class
                    if( checkbox.checked !== true && !$('body').hasClass( 'acwp-' + name ) ){
                        $('body').addClass('acwp-' + name);
                    } else {
                        $('body').removeClass('acwp-' + name);
                    }
                    
                    if( name === 'incfont' ){
                        IncreseFont();
                    }
                    else if( name === 'decfont' ){
                        DecreaseFont();
                    }
                } 
                else if( name === 'contrast' ){
                    ToggleContrast();
                }
                
                if( noCookies !== 'yes' ){
                    const itemCookie = acp_readCookie( name );
                    if( itemCookie )
                        acp_eraseCookie( name );
                    else
                        acp_createCookie( name, 'yes', 1 );
                }
                
            }
            
        });
    });
    
    // ?
    jQuery( "#acwp-toolbar .acwp-toggler label" ).keypress(function( event ) {
        if ( event.which == 13 ) {
            event.preventDefault();
            jQuery(this).click();
        }
    });

    if( acwp_attr.no_btn_drage !== 'yes' ){
        jQuery( "#acwp-toolbar-btn-wrap" ).on('mousedown', function (e) {
            e.preventDefault();
            window.my_dragging = {};
            my_dragging.pageX0 = e.pageX;
            my_dragging.pageY0 = e.pageY;
            my_dragging.elem = this;
            my_dragging.offset0 = jQuery(this).offset();
            function handle_dragging(e){
                var top = my_dragging.offset0.top + (e.pageY - my_dragging.pageY0);
                $(my_dragging.elem)
                    .offset({top: top});
            }
            function handle_mouseup(e){
                jQuery('body')
                    .off('mousemove', handle_dragging)
                    .off('mouseup', handle_mouseup);
            }
            jQuery('body')
                .on('mouseup', handle_mouseup)
                .on('mousemove', handle_dragging);
        });
    }

    $('#acwp-toolbar-btn').click(function(){
        $('#acwp-toolbar-btn-wrap').removeClass('show');
        $('.acwp-toolbar').addClass('acwp-toolbar-active');
        setTimeout(function(){
            $('.acwp-toolbar').addClass('acwp-toolbar-show');
        }, 100);
    });
    $('#acwp-close-toolbar').click(function(){
        $('#acwp-toolbar-btn-wrap').addClass('show');
        $('.acwp-toolbar').removeClass('acwp-toolbar-show');
        setTimeout(function(){
            $('.acwp-toolbar').removeClass('acwp-toolbar-active');
        }, 500);
    });
});