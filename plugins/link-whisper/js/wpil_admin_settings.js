"use strict";

(function ($)
{
    
    var hasChangedLanguage = false;


    jQuery('.wpil-setting-multiselect').select2();

    $('.settings-carrot').on('click', openCloseSettings);
    function openCloseSettings(){
        var $setting = $(this),
            active = $setting.hasClass('active');
        if(active){
            $setting.removeClass('active');
            $(this).closest('tr').find('.setting-control').css({'height': '0px', 'overflow': 'hidden'});
            $('.setting-control-container').css({'display': 'none'});
        }else{
            $setting.addClass('active');
            $(this).closest('tr').find('.setting-control').css('height', 'initial');
            $('.setting-control-container').css({'display': 'table-cell'});
        }
    }
    
    $('#wpil-selected-language').on('change', updateDisplayedIgnoreWordList);
    function updateDisplayedIgnoreWordList(){

        var wordLists = $('#wpil-available-language-word-lists').val(),
            selectedLang = $('#wpil-selected-language').val();
        if(!wordLists){
            return;
        }

        if(!hasChangedLanguage){
            var str1 = $('#wpil-currently-selected-language-confirm-text-1').val();
            var str2 = $('#wpil-currently-selected-language-confirm-text-2').val();
            var text = (str1 + '\n\n' + str2);

            wpil_swal({
                title: 'Notice:',
                text: (text) ? text: 'Changing Link Whisper\'s language will replace the current Words to be Ignored with a new list of words. \n\n If you\'ve added any words to the Words to be Ignored area, this will erase them.',
                icon: 'info',
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                }).then((replace) => {
                  if (replace) {
                        wordLists = JSON.parse(wordLists);
                        if(wordLists[selectedLang]){
                            $('#ignore_words_textarea').val(wordLists[selectedLang].join('\n'));
                            $('#wpil-currently-selected-language').val(selectedLang);
                            hasChangedLanguage = true;
                        }
                  } else {
                    $('#wpil-selected-language').val($('#wpil-currently-selected-language').val());
                  }
                });
        }else{
            wordLists = JSON.parse(wordLists);
            if(wordLists[selectedLang]){
                $('#ignore_words_textarea').val(wordLists[selectedLang].join('\n'));
                $('#wpil-currently-selected-language').val(selectedLang);
            }
        }
    }

    // supplies base64ed data for the 
    $(document).on('submit', '#frmSaveSettings', function(){
        var ignoreWords = $('#ignore_words_textarea').val();
        if(ignoreWords){
            $('#ignore_words').val(Base64.encode(ignoreWords));
        }
    });

    $(document).on('change', 'input[name="wpil_show_all_links"]', function(){
        var checkbox = $(this);
        wpil_swal({
            title: 'Notice:',
            text: 'After changing this setting, you are required to click "Run a Link Scan" reports on the links report page in order to see the correct link counts.',
            icon: 'info',
            buttons: ['Cancel', 'I Understand'],
        }).then((replace) => {
            if (!replace) {
                checkbox.prop('checked', !checkbox.prop('checked'));
            } else {
                $('#frmSaveSettings').submit();
            }
        });
    });

    $(document).on('change', 'input[name="wpil_content_formatting_level"]', function(){
        var level = $(this).val();
        $('.wpil-content-formatting-text').css({'display': 'none'});
        $('.wpil-content-formatting-text.wpil-format-'+level).css({'display': 'inline-block'});
    });

    $(document).on('change', 'input[name="wpil_delete_all_data"]', function(){
        var checkbox = $(this);

        // don't show the warning message if the user is turning off the data delete
        if(!checkbox.is(':checked')){
            return;
        }

        var wrapper = document.createElement('div');
        var message = $('.wpil-delete-all-data-message').val();
        $(wrapper).append(message);

        wpil_swal({
            title: 'Notice:',
            content: wrapper,
            icon: 'info',
            buttons: ['Cancel', 'I Understand'],
        }).then((replace) => {
            if (!replace) {
                checkbox.prop('checked', !checkbox.prop('checked'));
            } else {
                $('#frmSaveSettings').submit();
            }
        });
    });

    /**
    *
    *  Base64 encode / decode
    *  http://www.webtoolkit.info
    *
    **/
    var Base64 = {

        // private property
        _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="

        // public method for encoding
        , encode: function (input)
        {
            var output = "";
            var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            var i = 0;

            input = Base64._utf8_encode(input);

            while (i < input.length)
            {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2))
                {
                    enc3 = enc4 = 64;
                }
                else if (isNaN(chr3))
                {
                    enc4 = 64;
                }

                output = output +
                    this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                    this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
            } // Whend 

            return output;
        } // End Function encode 


        // public method for decoding
        ,decode: function (input)
        {
            var output = "";
            var chr1, chr2, chr3;
            var enc1, enc2, enc3, enc4;
            var i = 0;

            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
            while (i < input.length)
            {
                enc1 = this._keyStr.indexOf(input.charAt(i++));
                enc2 = this._keyStr.indexOf(input.charAt(i++));
                enc3 = this._keyStr.indexOf(input.charAt(i++));
                enc4 = this._keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64)
                {
                    output = output + String.fromCharCode(chr2);
                }

                if (enc4 != 64)
                {
                    output = output + String.fromCharCode(chr3);
                }

            } // Whend 

            output = Base64._utf8_decode(output);

            return output;
        } // End Function decode 


        // private method for UTF-8 encoding
        ,_utf8_encode: function (string)
        {
            var utftext = "";
            string = string.replace(/\r\n/g, "\n");

            for (var n = 0; n < string.length; n++)
            {
                var c = string.charCodeAt(n);

                if (c < 128)
                {
                    utftext += String.fromCharCode(c);
                }
                else if ((c > 127) && (c < 2048))
                {
                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);
                }
                else
                {
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }

            } // Next n 

            return utftext;
        } // End Function _utf8_encode 

        // private method for UTF-8 decoding
        ,_utf8_decode: function (utftext)
        {
            var string = "";
            var i = 0;
            var c, c1, c2, c3;
            c = c1 = c2 = 0;

            while (i < utftext.length)
            {
                c = utftext.charCodeAt(i);

                if (c < 128)
                {
                    string += String.fromCharCode(c);
                    i++;
                }
                else if ((c > 191) && (c < 224))
                {
                    c2 = utftext.charCodeAt(i + 1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                }
                else
                {
                    c2 = utftext.charCodeAt(i + 1);
                    c3 = utftext.charCodeAt(i + 2);
                    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }

            } // Whend 

            return string;
        } // End Function _utf8_decode 

    }

})(jQuery);
