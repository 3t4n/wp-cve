jQuery(function ($) {
    $(document).ready(function () {

        //------------globals vars--------------------
        let isSyncing = false;
        let isNewAccount = false;
        let isThumbsAjax = false;
        let preventedSizes = '';
        let sirvJsCompressedSizes = {};
        //--------------------------------------------

        function Validator(){};
        Validator.prototype.invalidValidate = function(fieldValue, validatorFunc, limitValue=null, callback=null){
            if(validatorFunc(fieldValue, limitValue)){
                if(!!callback) callback();
                return true;
            }

            return false;
        }

        Validator.prototype.minLenght = function(fieldValue, limitValue){
            return fieldValue.length <= limitValue;
        }

        Validator.prototype.maxLenght = function(fieldValue, limitValue){
            return fieldValue.length >= limitValue;
        }

        Validator.prototype.lenghtBetween = function(fieldValue, limitValue){
            return fieldValue.length < limitValue.min || fieldValue.length > limitValue.max;
        }

        Validator.prototype.empty = function(fieldValue){
            return fieldValue === '';
        }

        Validator.prototype.email = function(fieldValue){
            //let regex = /^((?!\.)[\w-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/i;
            //let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/i;
            let regex = /^[a-z0-9!#$%&'"*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'"*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i;
            return !regex.test(fieldValue);
        }

        Validator.prototype.name = function(fieldValue){
            let regex = /^[a-zA-Z ,.'-]+$/;
            return !regex.test(fieldValue);
        }

        Validator.prototype.accountSuffix = function(fieldValue){
            let regex = /^.*-(srv[0-9]*|direct|cdn)$/i;
            return regex.test(fieldValue);
        }

        Validator.prototype.upperCase = function(fieldValue){
            let regex = /[A-Z]+/;
            return regex.test(fieldValue);
        }

        Validator.prototype.equalString = function(fieldValue, stringsArr){
            let result = [];
            result = stringsArr.filter(function(str){return fieldValue.indexOf(str) !== -1});
            return result.length > 0;
        }


        //add scrollable menu for help tab
        $(window).on('scroll', function () {
            if ($(window).scrollTop() > 170 && $(window).width() > 767) {
                $('.sirv-help-menu').addClass('sirv-help-menu-fixed');
                $('.sirv-help-data').addClass('sirv-help-data-margin');
            } else {
                $('.sirv-help-menu').removeClass('sirv-help-menu-fixed');
                $('.sirv-help-data').removeClass('sirv-help-data-margin');
            }
        });


        // Add smooth scrolling on all links inside the navbar
        $(".sirv-help-menu a").on('click', function (event) {
            // Make sure this.hash has a value before overriding default behavior
            if (this.hash !== "") {
                // Prevent default anchor click behavior
                event.preventDefault();

                // Store hash
                var hash = this.hash;

                // Using jQuery's animate() method to add smooth page scroll
                // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 800, function () {

                    // Add hash (#) to URL when done scrolling (default click behavior)
                    window.location.hash = hash;
                });
            } // End if
        });


        $('.nav-tab-wrapper > a').on('click', function (e) {
            changeTab(e, $(this));
        });
        $(".sirv-show-settings-tab").on("click", function (e) {
            changeTab(e, $(".nav-tab-sirv-settings"));
        });

        $('.sirv-show-sync-tab').on('click', function (e) {
            changeTab(e, $('.nav-tab-sirv-cache'));
        });


        function changeTab(e, $object) {
            if (!!e) e.preventDefault();
            $('.sirv-tab-content').removeClass('sirv-tab-content-active');
            $('.nav-tab-wrapper > a').removeClass('nav-tab-active');
            $('.sirv-tab-content' + $object.attr('href')).addClass('sirv-tab-content-active');
            $object.addClass("nav-tab-active").trigger("blur");
            $('#active_tab').val($object.attr('href'));
            let hash = $object.attr('data-link');
            window.location.hash = hash;

            document.dispatchEvent(new CustomEvent('options_tab_changed', {
                detail: {
                    hash: hash
                }
            }));
        }


        function getTabFromUrlHash(){
            let urlHash = window.location.hash.replace('#', '');
            urlHash = urlHash.replace('sirv-', '');
            urlHash = urlHash == 'undefined' ? 'settings' : urlHash;
            if (!!urlHash) changeTab(undefined, $('.nav-tab-sirv-' + urlHash));
        }


        $('.sirv-toogle-pass').on('click', tooglePassVisibility);
        function tooglePassVisibility() {
            let $passInput = $('.sirv-pass');
            let $toggleIcon = $('.sirv-toogle-pass span.dashicons');
            let inputType = $passInput.attr('type');

            if (inputType == 'password') {
                $passInput.attr('type', 'text');
                $toggleIcon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            } else {
                $passInput.attr('type', 'password');
                $toggleIcon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }
            $passInput.trigger('focus');
        }


        $('.sirv-pass').on('keyup', function (e) {
            if (e.keyCode == 13) {
                $('.sirv-init').trigger('click');
            }
        });

        $("input[name=SIRV_OTP_TOKEN]").on('input', sirvOtpFire);
        function sirvOtpFire(){
            let otpToken = $(this).val();
            if(otpToken.length == 6){
                sirvOtp();
            }
        }


        $('.sirv-init').on('click', sirvInitAccount);
        function sirvInitAccount(){
            hideMessage('sirv-init-account', true);

            let name = $('input[name=SIRV_NAME]').val().trim().split(' ');

            let data = {};
            data['action'] = 'sirv_init_account';
            data['email'] = $('input[name=SIRV_EMAIL]').val().trim();
            data['pass'] = $('input[name=SIRV_PASSWORD]').val().trim();
            data['firstName'] = name[0] || '';
            data['lastName'] = name[1] || '';
            data['accountName'] = $('input[name=SIRV_ACCOUNT_NAME]').val().trim();
            data['isNewAccount'] = $('input[name=SIRV_ACCOUNT_NAME]').is(':visible') ? 1 : 0;


            let validator = new Validator();

            if(validator.invalidValidate(data['email'], validator.empty)){
                showMessage('.sirv-error', 'Please specify email.', 'sirv-init-account');
                return;
            }

            if(validator.invalidValidate(data['pass'], validator.empty)){
                showMessage('.sirv-error', 'Please specify password.', 'sirv-init-account');
                return;
            }

            if(!!data['isNewAccount']){
                if(validator.invalidValidate(data['email'], validator.email)){
                    showMessage('.sirv-error', 'Please enter correct email.', 'sirv-init-account');
                    return;
                }

                let restirictedEmailDomains = ['mail.ru'];
                if(validator.invalidValidate(data['email'], validator.equalString, restirictedEmailDomains)){
                    showMessage('.sirv-error', 'Please use a company email address (not '+ restirictedEmailDomains.join(', ') +')', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['pass'], validator.upperCase)){
                    showMessage('.sirv-error', 'Uppercase symbols does not permitted to use in the password.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['pass'], validator.lenghtBetween, {min: 8, max: 64})){
                    showMessage('.sirv-error', 'Choose a password at least 8 characters long and less than 64 characters.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['firstName'], validator.empty)){
                    showMessage('.sirv-error', 'Please specify your first name.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['firstName'], validator.name)){
                    showMessage('.sirv-error', 'Please enter correct first name.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['firstName'], validator.lenghtBetween, {min: 2, max: 35})){
                    showMessage('.sirv-error', 'First name must be 2-35 characters.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['lastName'], validator.empty)){
                    showMessage('.sirv-error', 'Please specify your last name.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['lastName'], validator.name)){
                    showMessage('.sirv-error', 'Please enter correct last name.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['lastName'], validator.lenghtBetween, {min: 2, max: 35})){
                    showMessage('.sirv-error', 'Last name must be 2-35 characters.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['accountName'], validator.empty)){
                    showMessage('.sirv-error', 'Please specify account name.', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['accountName'], validator.lenghtBetween, {min: 6, max: 30})){
                    showMessage('.sirv-error', 'Account name must be 6-30 characters. It may contain letters, numbers or hyphens (no spaces).', 'sirv-init-account');
                    return;
                }

                if(validator.invalidValidate(data['accountName'], validator.accountSuffix)){
                    let accName = data['accountName'];
                    showMessage('.sirv-error', 'Account name <b>' + accName + '</b> is not permitted. You could try <b>'+ accName.replace('-', '') +'</b> instead.', 'sirv-init-account');
                    return;
                }
            }

            $.ajax({
                url: ajaxurl,
                data: data,
                type: 'POST',
                dataType: "json",
                beforeSend: function (){
                    $('.sirv-connect-account-wrapper').addClass('sirv-loading');
                },
            }).done(function (res) {
                //debug
                //console.log(res);

                $('.sirv-connect-account-wrapper').removeClass('sirv-loading');

                if(!!res && !!res.error){
                showMessage('.sirv-error', res.error, 'sirv-init-account');
                }else if(!!res && !!res.isOtpToken){
                    showOtpInput();
                }else if(!!res && !!res.allow_users){
                    showUsersList(res);
                }

            }).fail(function (jqXHR, status, error) {
                $('.sirv-connect-account-wrapper').removeClass('sirv-loading');
                console.log("Error during ajax request: " + error);
                showMessage('.sirv-error', "Error during ajax request: " + error, 'sirv-init-account');
            });
        }


        function showOtpInput(){
            let $button = $("input.sirv-login-action");

            $(".sirv-otp-code").show();
            $("input[name=SIRV_OTP_TOKEN]").focus();
            $(".sirv-field").hide();
            $button.removeClass("sirv-init");
            $button.addClass("sirv-otp");
            $button.val("Verify");
            $button.off("click");
            $button.on("click", sirvOtp);
        }


        function showUsersList(res){
            let $button = $("input.sirv-login-action");
            $button.removeClass('sirv-init');
            $button.removeClass("sirv-otp");
            $button.addClass('sirv-log-in');
            $button.val("Connect account");
            $button.off('click');
            $button.on('click', sirvLogin);

            $(".sirv-otp-code").hide();
            $('.sirv-select').show();
            $('.sirv-field').hide();

            //$('select[name="sirv_account"]').append('<option value="none" disabled>Choose...</option>');

            for(let i in res.allow_users){
                $('select[name="sirv_account"]').append('<option value="' + res.allow_users[i].token + '">' + res.allow_users[i].alias + '</option>');
            }

            if(!!res.deny_users && res.deny_users.length > 0){
                $('select[name="sirv_account"]').append('<option value="none" disabled>---------------------------------</option>');
                $('select[name="sirv_account"]').append('<option value="none" disabled>Accounts with an insufficient role</option>');
                for (let i in res.deny_users) {
                    $('select[name="sirv_account"]').append('<option value="' + res.deny_users[i].token + '" disabled>' + res.deny_users[i].alias + ' ('+ res.deny_users[i].role +')</option>');
                }
            }

            if (res.allow_users.length == 1) {
                $('select[name="sirv_account"] option').last().prop('selected', 'true').closest('select').trigger('change');
                $('.sirv-log-in').trigger('click');
                $('.sirv-log-in').prop('disabled', true);
            }
        }


        function sirvOtp(){
            hideMessage("sirv-init-account", true);

            let data = {};

            data['action'] = 'sirv_get_users_list';
            data["email"] = $("input[name=SIRV_EMAIL]").val().trim();
            data["pass"] = $("input[name=SIRV_PASSWORD]").val().trim();
            data["otpToken"] = $("input[name=SIRV_OTP_TOKEN]").val().trim();

            if(data.otpToken.length < 6){
                showMessage('.sirv-error', 'Not enough symbols', 'sirv-init-account');
                return;
            }

            if (data.otpToken.length > 6) {
                showMessage('.sirv-error', 'To many symbols', 'sirv-init-account');
                return;
            }

            if (!Number.isInteger(+data.otpToken)){
                showMessage('.sirv-error', 'Incorrect value. You can use only integers.', 'sirv-init-account');
                return;
            }

            $.ajax({
                url: ajaxurl,
                data: data,
                type: 'POST',
                dataType: "json",
                beforeSend: function(){
                    hideMessage("sirv-init-account", true);
                    $('.sirv-connect-account-wrapper').addClass('sirv-loading');
                },
            }).done(function (res) {
                //debug
                //console.log(res);

                $('.sirv-connect-account-wrapper').removeClass('sirv-loading');

                if( !!res && !!res.error ){
                    showMessage('.sirv-error', res.error, 'sirv-init-account');
                }else if(!!res && !!res.allow_users){
                    showUsersList(res);
                }
            }).fail(function (jqXHR, status, error) {
                $('.sirv-connect-account-wrapper').removeClass('sirv-loading');
                console.log("Error during ajax request: " + error);
                showMessage('.sirv-error', "Error during ajax request: " + error, 'sirv-init-account');
            });
        }


        function sirvLogin() {
            hideMessage("sirv-init-account", true);

            const selectedValue= $('select[name="sirv_account"]').val();
            const selectedText = $('select[name=sirv_account] option:selected').text();

            let data = {};

            data['action'] = 'sirv_setup_credentials';
            data['email'] = $('input[name=SIRV_EMAIL]').val();
            data['sirv_account'] = selectedValue;

            if( selectedValue == 'none'){
                showMessage('.sirv-error', "Please choose account first", 'sirv-init-account');
                return;
            }

            $(this).prop('disabled', true);
            $("select[name=sirv_account]").prop("disabled", true);
            $(".sirv-load-acc-spinner").css({ display: "flex" });
            $(".sirv-loading-acc-name").text(selectedText);

            $.ajax({
                url: ajaxurl,
                data: data,
                type: 'POST',
                dataType: "json",
                beforeSend: function(){
                    $('.sirv-connect-account-wrapper').addClass('sirv-loading');
                },
            }).done(function (res) {
                //debug
                //console.log(res);

                $('.sirv-connect-account-wrapper').removeClass('sirv-loading');

                if( !!res && !!res.error ){
                    showMessage('.sirv-error', res.error, 'sirv-init-account');
                }

                window.location.href = window.location.href.replace(/\#.*/i, '');

            }).fail(function (jqXHR, status, error) {
                $('.sirv-connect-account-wrapper').removeClass('sirv-loading');
                console.log("Error during ajax request: " + error);
                showMessage('.sirv-error', "Error during ajax request: " + error, 'sirv-init-account');
            });
        }

        //type: ok, error, warning, info
        function showMessage(selector, message, msg_id, type='error'){
            $(selector).append(`<div id="${msg_id}" class="sirv-message ${type}-message">${message}</div>`);
        }


        function hideMessage(selector, removeAll=false) {
            if(removeAll){
                $('#' + selector).parent().empty();
            }else{
                $('#' + selector).remove();
            }
        }

        $('.sirv-disconnect').on('click', disconnectAccount);
        function disconnectAccount(event){
            event.preventDefault();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_disconnect',
                    _ajax_nonce: sirv_options_data.ajaxnonce,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function(){
                    hideMessage("sirv-init-account", removeAll = true);
                    $('.sirv-connect-account-wrapper').addClass('sirv-loading');

                },
            }).done(function (res){
                //debug
                //console.log(res);

                if(!!res.error){
                    showMessage('.sirv-error', res.error, 'sirv-init-account');
                }

                $('.sirv-connect-account-wrapper').removeClass('sirv-loading');

                if (!!res && !!res.disconnected) {
                    window.location.href = window.location.href.replace(/\#.*/i, '');
                }

            }).fail(function(jqXHR, status, error){
                $('.sirv-connect-account-wrapper').removeClass('sirv-loading');
                console.log("Error during ajax request: " + error);
                showMessage(".sirv-error", error, "sirv-init-account");
            });
        }


        function continueBtn(){
            console.log("continueBtn run");
        }


        $(".sirv-clear-cache").on("click", clearCache);
        function clearCache(e){
            e.preventDefault();

            let type = $(this).attr('data-type');

            if(type == 'all' || type == 'synced'){
                sirvUIShowConfirmDialog(
                    "Sirv plugin cache",
                    "The plugin keeps track of which images have been copied to Sirv. Clearing the cache will mean slightly slower loading of images next time they are requested.",
                    deleteCache,
                    [type],
                );
            }else{
                deleteCache(type);
            }
        }


        function deleteCache(cacheType){
            const spinnerSelector = getClearCacheSpinnerSelector(cacheType);

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_clear_cache',
                    clean_cache_type: cacheType,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    hideMessage('sirv-sync-message');
                    $('.sync-errors').hide();
                    $(spinnerSelector).show();
                }
            }).done(function (data) {
                //debug
                //console.log(data);

                updateCacheInfo(data);
                showMessage('.sirv-sync-messages', getMessage(cacheType), 'sirv-sync-message', 'ok');
                $(spinnerSelector).hide();

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-sync-message');
                $(spinnerSelector).hide();
            });
        }


        function getClearCacheSpinnerSelector(type){
            let cssSelector = '';

            switch (type) {
                case 'synced':
                    cssSelector = ".sirv-synced-clear-cache-action .sirv-traffic-loading-ico";
                    break;

                case 'failed':
                    cssSelector = ".sirv-failed-clear-cache-action .sirv-traffic-loading-ico";
                    break;

                case 'garbage':
                    cssSelector = '.sirv-discontinued-images span.sirv-traffic-loading-ico';
                    break;

                default:
                    break;
            }

            return cssSelector;
        }


        function getMessage(type){
            const sMessage = 'Synced images have been deleted from Sirv cache';
            const fMessage = 'Failed images have been deleted from Sirv cache';
            const aMessage = 'Sirv cache has been cleared';
            const gMessage = 'Old images cleared from Sirv plugin cache';
            let message = '';

            switch (type) {
                case 'synced':
                    message = sMessage;
                    break;
                case 'failed':
                    message = fMessage;
                    break;
                case 'all':
                    message = aMessage;
                    break;
                case 'garbage':
                    message = gMessage;
                    break;
                default:
                    break;
            }

            return message;
        }


        function updateCacheActionItems(data){
            if(!!data){
                let isSynced  = data.SYNCED.count*1 > 0 ? true : false;
                let isFailed  = data.FAILED.count*1 > 0 ? true : false;
                let isGarbage = data.garbage_count*1 > 0 ? true : false;

                $('.sirv-old-cache-count').text(data.garbage_count);

                if (isGarbage){
                    $('.sirv-discontinued-images').show();
                }else{
                    $('.sirv-discontinued-images').hide();
                }

                if(isSynced){
                    $(".sirv-synced-clear-cache-action").show();
                }else{
                    $(".sirv-synced-clear-cache-action").hide();
                }

                if(isFailed){
                    $(".sirv-failed-clear-cache-action").show();
                }else{
                    $(".sirv-failed-clear-cache-action").hide();
                }
            }
        }


        function updateCacheInfo(data){
            if(!!data){
                let isGarbage = data.garbage_count*1 > 0 ? true : false;
                if(data.q*1 > data.total_count*1){
                    data.q = isGarbage
                        ? data.q*1 - data.garbage_count*1 > data.total_count
                            ? data.total_count
                            : data.q * 1 - data.garbage_count * 1
                        : data.total_count;
                }

                $('.sirv-progress-data__complited--text').html(data.q_s);
                $('.sirv-progress-data__complited--size').html(data.size_s);
                $('.sirv-progress-data__queued--text').html(data.queued_s);
                $('.sirv-progress-data__failed--text').html(data.FAILED.count_s);

                $('.sirv-progress__text--percents').html(data.progress + '%');
                $('.sirv-progress__text--complited span').html(data.q_s + ' out of ' + data.total_count_s);

                $('.sirv-progress__bar--line-complited').css('width', data.progress_complited + '%');
                $('.sirv-progress__bar--line-queued').css('width', data.progress_queued + '%');
                $('.sirv-progress__bar--line-failed').css('width', data.progress_failed + '%');

                if(data.FAILED.count*1 > 0){
                    $('.failed-images-block').show();
                    $('.failed-images-block a').show();
                }else{
                    $('.failed-images-block').hide();
                }

                if ( (data.q*1 + data.FAILED.count*1) == data.total_count*1) {
                    if (data.FAILED.count * 1 == 0) {
                        //manageElement('input[name=sirv-sync-images]', disableFlag = true, text = '100% synced', button = true);
                        setButtonSyncState('syncedAll');
                    } else {
                        //manageElement('input[name=sirv-sync-images]', disableFlag = true, text = 'Synced', button = true);
                        setButtonSyncState("synced");
                    }
                }else{
                    //manageElement('input[name=sirv-sync-images]', disableFlag = false, text = 'Sync images', button = true);
                    setButtonSyncState("sync");
                }

                updateCacheActionItems(data);
            }

        }


        //send the message from plugin to support@sirv.com
        $('#send-email-to-sirv').on('click', function () {
            //fields
            let name = $('#sirv-writer-name').val();
            let contactEmail = $('#sirv-writer-contact-email').val();
            //let priority = $('#sirv-priority').val();
            let summary = $('#sirv-summary').val();
            let messageText = $('#sirv-text').val();

            //messages;
            let proccessingSendMessage = '<span class="sirv-traffic-loading-ico sirv-no-lmargin"></span> Sending message. This may take some time...';
            let messageSent = 'Your message has been sent.';
            let ajaxError = 'Error during AJAX request. Please try to send the message again or use the <a target="_blank" href="https://sirv.com/contact/">Sirv contact form here</a> Error: <br/>';
            let sendingError = 'Something went wrong. The most likely reason is that Sendmail is not installed/configured. Please try to send the message again or use the <a target="_blank" href="https://sirv.com/contact/">Sirv contact form here</a>';
            //form messages
            let emptyFields = '<span style="color: red;">Please fill form fields.</span>';
            let incorrectEmail = '<span style="color: red;">Email is incorrect. Please check email field.</span>';

            let generatedViaWP = '\n\n\n---\nThis message was sent from the Sirv WordPress plugin form at ' + document.location.hostname;


            let formMessages = '';

            if (summary == '' || messageText == '' || name == '' || contactEmail == '') {
                formMessages += emptyFields + '<br />';
            }

            if (contactEmail.match(/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/i) == null && contactEmail != '') {
                formMessages += incorrectEmail + '<br />';
            }

            if (formMessages != '') {
                $('.sirv-show-result').html(formMessages);
                return false;
            }

            $.post(ajaxurl, {
                action: 'sirv_send_message',
                name: name,
                emailFrom: contactEmail,
                //priority: priority + ' (via WP)',
                summary: summary,
                text: 'Contact name: ' + name + '\n' + 'Contact Email: ' + contactEmail + '\n\n' + messageText + generatedViaWP,
                beforeSend: function () {
                    $('.sirv-show-result').html(proccessingSendMessage);
                }
            }).done(function (data) {
                //debug
                //console.log(data);

                if (data == '1') {
                    $('.sirv-show-result').html(messageSent);
                } else {
                    $('.sirv-show-result').html(sendingError);
                }

                //clear contact form fields
                $('#sirv-writer-name').val('');
                $('#sirv-writer-contact-email').val('');
                $('#sirv-summary').val('');
                $('#sirv-text').val('');

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                $('.sirv-show-result').html(ajaxError + error);
            });

        });


        //sanitize folder name on sirv
        $('#sirv-save-options').on('submit', function () {
            let folderOnSirv = $("[name='SIRV_FOLDER']").val();
            let sanitizedFolderOnSirv = folderOnSirv == '' ? 'WP_SirvMediaLibrary' : folderOnSirv.replace(/^[\/]*(.*?)[\/]*$/ig, '$1');
            $("[name='SIRV_FOLDER']").val(sanitizedFolderOnSirv);

            return true;
        });


        $('input[name=SIRV_USE_SIRV_RESPONSIVE]').on('change', showResponsiveWarning);
        function showResponsiveWarning(){
            let checked = $(this).val();
            let $placeholderWrap = $('.sirv-hide-placeholder');
            let $msg = $('.sirv-responsive-msg');

            if(checked == '1'){
                $msg.slideDown();
                $placeholderWrap.show();
            }else{
                $msg.slideUp();
                $placeholderWrap.hide();
            }
        }


        $('.sirv-option-edit').on('click', optionEdit);
        function optionEdit(e){
            e.preventDefault();

            $p = $(this).parent();
            $p.hide();
            $p.next('p').show();
        }


        $('.tst').on('click', tst);
        function tst() {
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_tst',
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {

                },
            }).done(function (data) {
                //debug
                console.log(data);

            }).fail(function (jqXHR, status, error) {
                console.error("Error during ajax request: " + error);
            });
        }


        $(".sirv-sync-images").on("click", initializeMassSync);
        function initializeMassSync(){
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_initialize_process_sync_images',
                    sirv_initialize_sync: true,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    isSyncing = true;
                    $('.sirv-sync-messages').empty();
                    //manageElement('input[name=sirv-sync-images]', disableFlag = true, text = 'Syncing', button = true);
                    setButtonSyncState('syncing');
                    $('.sirv-progress__bar--line-complited').addClass('sirv-progress-bar-animated');
                    $('.sirv-queue').html('Processing (1/3): calculating folders...');
                    $('.sirv-processing-message').show();
                    $('.sync-errors').hide();
                    $(".sirv-synced-clear-cache-action").hide();
                    $(".sirv-failed-clear-cache-action").hide();
                    $('.failed-images-block').hide();
                    $('.failed-images-block a').hide();
                },
            }).done(function (data) {
                //debug
                //console.log(data);
                if (!!data && data.folders_calc_finished){
                    $('.sirv-queue').html('Processing (2/3): calculating images in queue...');
                    massSyncImages();
                }else{
                    showMessage('.sirv-sync-messages', "Please reload this page and try again.", 'sirv-sync-message', 'warning');
                    $('.sirv-processing-message').hide();
                    $('.sirv-progress__bar--line-complited').removeClass('sirv-progress-bar-animated');
                    //manageElement('input[name=sirv-sync-images]', disableFlag = false, text = 'Sync images', button = true);
                    setButtonSyncState("syncing");
                    isSyncing = false;
                }

            }).fail(function (jqXHR, status, error) {
                console.error("Error during ajax request: " + error);
                showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-sync-message');
                showMessage('.sirv-sync-messages', "Please reload this page and try again.", 'sirv-sync-message', 'warning');
                $('.sirv-processing-message').hide();
                $('.sirv-progress__bar--line-complited').removeClass('sirv-progress-bar-animated');
                //manageElement('input[name=sirv-sync-images]', disableFlag = false, text = 'Sync images', button = true);
                setButtonSyncState("syncing");
                isSyncing = false;
            });
        }


       //$('.sirv-sync-images').on('click', massSyncImages);
        function massSyncImages(){
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_process_sync_images',
                    sirv_sync_uncached_images: true,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function(){
                    //code here
                },
            }).done(function(data){
                //debug
                //console.log(data);

                if (!!data) {
                    $('.sirv-progress-data__complited--text').html(data.q_s);
                    $('.sirv-progress-data__complited--size').html(data.size_s);
                    $('.sirv-progress-data__queued--text').html(data.queued_s);
                    $('.sirv-progress-data__failed--text').html(data.FAILED.count_s);

                    $('.sirv-progress__text--percents').html(data.progress + '%');
                    $('.sirv-progress__text--complited span').html(data.q_s + ' out of ' + data.total_count_s);

                    $('.sirv-progress__bar--line-complited').css('width', data.progress_complited + '%');
                    $('.sirv-progress__bar--line-queued').css('width', data.progress_queued + '%');
                    $('.sirv-progress__bar--line-failed').css('width', data.progress_failed + '%');

                    //let processing_q = data.total_count * 1 - data.q * 1 - data.FAILED.count * 1;
                    //$('.sirv-queue').html('Images in queue: ' + processing_q);
                    $('.sirv-queue').html('Processing (3/3): syncing images...');

                    if(!!data && !isSyncing){
                        setButtonSyncState('sync');
                        $('.sirv-processing-message').hide();
                        $('.sirv-progress__bar--line-complited').removeClass('sirv-progress-bar-animated');
                        updateCacheActionItems(data);
                        return;
                    }

                    if (data.FAILED.count) {
                        $('.failed-images-block').show();
                        $('.failed-images-block a').show();
                    }

                    if (!!data.status && data.status.isStopSync){
                        manageElement('input[name=sirv-sync-images]', disableFlag = true, text = 'Can\'t sync', button = true);
                        showMessage('.sirv-sync-messages', data.status.errorMsg, 'sirv-sync-message');
                        $('.sirv-processing-message').hide();
                        $('.sirv-progress__bar--line-complited').removeClass('sirv-progress-bar-animated');
                        return;
                    }

                    if ((data.q * 1 + data.FAILED.count * 1 ) < data.total_count * 1) {
                        massSyncImages();
                    } else {
                        $('.sirv-progress__bar--line-complited').removeClass('sirv-progress-bar-animated');
                        $('.sirv-processing-message').hide();
                        if (data.FAILED.count * 1 == 0){
                            //manageElement('input[name=sirv-sync-images]', disableFlag = true, text = '100% synced', button = true);
                            setButtonSyncState("syncedAll");
                        }else{
                            //manageElement('input[name=sirv-sync-images]', disableFlag = true, text = 'Synced', button = true);
                            setButtonSyncState("synced");
                        }
                        isSyncing = false;
                        updateCacheActionItems(data);
                    }
                }
            }).fail(function(jqXHR, status, error){
                console.error("status: ", status);
                console.error("Error message: " + error);
                console.error("http code", `${jqXHR.status} ${jqXHR.statusText}`);

                showAjaxErrorMessage(jqXHR, status, error, '.sirv-sync-messages', 'sirv-sync-message');
                showMessage('.sirv-sync-messages', "Please reload this page and try again.", 'sirv-sync-message', 'warning');
                $('.sirv-processing-message').hide();
                $('.sirv-progress__bar--line-complited').removeClass('sirv-progress-bar-animated');
                //manageElement('input[name=sirv-sync-images]', disableFlag = false, text = 'Sync images', button = true);
                setButtonSyncState("sync");
                isSyncing = false;
            });
        }


        function showAjaxErrorMessage(jqXHR, status, error, selector, selectorId) {
            const errorTitle = `<b>Error during ajax request</b>`;

            let errorText = !!error
                ? `<p>Error message: "${error}"</p>`
                : `<p>Error message: Unknown error. If this continues, please inform the <a target="_blank" rel="noopener" href="https://sirv.com/help/support/#support">Sirv support team</a></p>\n`;

            let httpCodeText = "";

            if(jqXHR.status !== 200){
                httpCodeText = `<p>HTTP CODE: ${jqXHR.status} ${jqXHR.statusText}</p>`;
            }

            showMessage(selector, `${errorTitle}<br>${errorText}${httpCodeText}`, selectorId);
        }


        function setButtonSyncState(state){
            //state: sync, syncing, syncedAll
            //manageElement('input[name=sirv-sync-images]', disableFlag = state, text = 'Syncing', button = true);

            const buttonSelector = ".sirv-sync-images";
            const $syncButtonEl = $(buttonSelector);

            $syncButtonEl.off('click');

            switch (state) {
                case 'sync':
                    $(buttonSelector).on("click", initializeMassSync);
                    manageElement(buttonSelector, disableFlag = false, text = 'Sync items', button = true);
                    break;
                case 'syncing':
                    $(buttonSelector).on("click", stopMassSync);
                    manageElement(buttonSelector, disableFlag = false, text = 'Stop sync', button = true);
                    break;

                case 'stoping':
                    manageElement(buttonSelector, disableFlag = true, text = 'Stoping', button = true);
                    break;
                case 'synced':
                    manageElement(buttonSelector, disableFlag = true, text = 'Synced', button = true);
                    break;
                case 'syncedAll':
                    //$(buttonSelector).on("click", stopMassSync);
                    manageElement(buttonSelector, disableFlag = true, text = '100% synced', button = true);
                    break;
            }
        }

        //issue when last items syncing in the last porion and we press stop button. In this case all items will be synced and we need show 100% synced
        function stopMassSync(){
            isSyncing = false;
            $(".sirv-queue").html("Processing: stopping sync process...");
            setButtonSyncState('stoping');
        }


        function get_formated_num(num){
            return new Intl.NumberFormat('en-IN').format(num);
        }


        $('.sirv-get-error-data').on('click', getErrorData);
        function getErrorData(e){
            e.preventDefault();
            e.stopPropagation();


            let $link = $(this);
            let reportType = $link.attr('data-type');
            let errorId = $link.attr('data-error-id');
            let $ajaxAnimation = $(this).next();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_get_error_data',
                    error_id: errorId,
                    report_type: reportType,
                },
                type: 'POST',
                dataType: 'text',
                beforeSend: function () {
                    $ajaxAnimation.show();
                    $link.text('Generating...');
                },
            }).done(function (data) {
                //debug
                //console.log(data);

                $ajaxAnimation.hide();

                if (!!data) {
                    if(reportType == 'html'){
                        $link.text('Open HTML report');
                        $link.attr('href', data);
                        $link.attr('target', '_blank');
                        $('.sirv-get-error-data:contains(Open HTML report)').off('click', getErrorData);
                        window.open($link.attr('href'));
                    }else{
                        $link.text('CSV downloaded');
                        let filename = 'failed_images_error_'+ errorId + '.csv';
                        $('.sirv-get-error-data:contains(CSV downloaded)').off('click', getErrorData);
                        let a = document.createElement('a');
                        /* let url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'failed_images_list.txt';
                        a.style.display = 'none';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url); */
                        a.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data));
                        a.setAttribute('download', filename);

                        a.style.display = 'none';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }

                }
            }).fail(function (jqXHR, status, error) {
                $ajaxAnimation.hide();
                $link.text('An error occurred');
                console.error("Error during ajax request: " + error);
                showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-get-failed-message');
            });
        }

        $('.failed-images-block a').on('click', getErrorsData);
        function getErrorsData(e){
            e.preventDefault();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_get_errors_info',
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    $('.failed-images-block span.sirv-traffic-loading-ico').show();
                    $('.failed-images-block a').hide();
                    $('.sirv-fetch-errors').empty();
                    $('.sirv-get-error-data').off('click', getErrorData);
                },
            }).done(function (data) {
                //debug
                //console.log(data);

                if (!!data) {
                    let documentFragment = $(document.createDocumentFragment());
                    for (let i in data) {
                        if(data[i]['count'] > 0){
                            let solution = !!data[i]['error_desc'] ? 'Solution: ' + data[i]['error_desc'] : '';
                            documentFragment.append('<tr><td><span class="sirv-error-title">' + i + '</span><br><span class="sirv-error-solution">'+ solution + '</span></td><td>' + data[i]['count'] + '</td>'+
                            '<td><a class="sirv-get-error-data" data-type="html" data-error-id="' + data[i]['error_id'] + '" href="#">Generate HTML report</a>' +
                            ' <span class="sirv-traffic-loading-ico sirv-get-fimages-list" style="display:none;"></span><br/>' +
                            '<a class="sirv-get-error-data" data-type="csv" data-error-id="' + data[i]['error_id'] + '" href="#">Download CSV</a>' +
                            ' <span class="sirv-traffic-loading-ico sirv-get-fimages-list" style="display:none;"></span>' +
                            '</td></tr>');
                        }
                    }

                    $('.sirv-fetch-errors').append(documentFragment);
                    $('.sirv-get-error-data').on('click', getErrorData);
                    $('.failed-images-block span.sirv-traffic-loading-ico').hide();
                    $('.sync-errors').show();
                }
            }).fail(function (jqXHR, status, error) {
                console.error("Error during ajax request: " + error);
                showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-get-failed-message');
            });
        }


        $('.sirv-crop-radio').on('change', onCropChange);
        function onCropChange(e){
            e.preventDefault();
            e.stopPropagation();

            let $dataObj = $('#sirv-crop-sizes');
            let data = JSON.parse($dataObj.val());
            let $rObj = $(this);
            if(!!data){
                data[$rObj.attr('name')] = $rObj.val();
                $dataObj.val(JSON.stringify(data));
            }
        }

        $(".sirv-thumb-size").on("change", onThumbSizeChange);
        function onThumbSizeChange(e) {
            e.preventDefault();
            e.stopPropagation();


            let $preventedSizesEl = $("#sirv-prevented-sizes-hidden");
            let preventedSizes = JSON.parse($preventedSizesEl.val());
            let $changedRadio = $(this);
            let isCheckedDelete = $changedRadio.val() == 'delete';
            if (isCheckedDelete && !!preventedSizes) {
                preventedSizes[$changedRadio.attr("data-name")] = $changedRadio.attr("data-size");
            }else{
                delete preventedSizes[$changedRadio.attr("data-name")];
            }

            let curPreventedSizesStr = JSON.stringify(preventedSizes);
            if(isPreventedSizesChanged(curPreventedSizesStr)){
                $(".sirv-save-prevented-sizes-wrap").fadeIn();
            }else{
                $(".sirv-save-prevented-sizes-wrap").fadeOut();
            }

            $preventedSizesEl.val(curPreventedSizesStr);
        }

        $(".sirv-thumb-size-delete-all").on("change", onSelectUnselectAll);
        function onSelectUnselectAll(e) {
            e.preventDefault();
            e.stopPropagation();

            let isChecked = $(this).is(':checked');
            let $thumbs = $(".sirv-thumb-size");
            let $preventedSizesEl = $("#sirv-prevented-sizes-hidden");
            let preventedSizes = JSON.parse($preventedSizesEl.val());

            $.each($thumbs, function(index, element){
                let $elem = $(element);
                if(isChecked){
                    if($elem.val() == 'delete')
                    $elem.prop("checked", true);
                    preventedSizes[$elem.attr("data-name")] = $elem.attr("data-size");
                }else{
                    if($elem.val() == 'keep')
                    $(element).prop("checked", true);
                    preventedSizes = {};
                }
            });

            let curPreventedSizesStr = JSON.stringify(preventedSizes);
            if (isPreventedSizesChanged(curPreventedSizesStr)) {
                $(".sirv-save-prevented-sizes-wrap").fadeIn();
            } else {
                $(".sirv-save-prevented-sizes-wrap").fadeOut();
            }

            $preventedSizesEl.val(curPreventedSizesStr);
        }


        function isPreventedSizesChanged(curPreventedSizes){
            return curPreventedSizes.length !== getStoredPreventedSizes().length;
        }


        function getStoredPreventedSizes(){
            return preventedSizes;
        }


        function storePreventedSizesOnLoad(){
            preventedSizes = $("#sirv-prevented-sizes-hidden").val();
        }


        $('.sirv-delete-wp-thumbs, .sirv-regenerate-wp-thumbs').on('click', manageWPThumbs);
        function manageWPThumbs(event, $curBt = null) {
            let $curButton = $curBt || $(this);

            let curPreventedSizes = $("#sirv-prevented-sizes-hidden").val();
            if(isPreventedSizesChanged(curPreventedSizes)){
                hideMessage("sirv-thumbs-message");
                showMessage('.sirv-thumb-messages', 'Please save your updated settings before trying to '+ $curButton.attr('data-type') +' operation.', 'sirv-thumbs-message', 'warning');
                return false;
            }

            let preventedSizesStr = $("#sirv-prevented-sizes-hidden").val();
            let preventedSizesObj = JSON.parse(preventedSizesStr);

            if($curButton.attr('data-type') === 'delete'){
                if(Object.keys(preventedSizesObj).length === 0){
                    hideMessage("sirv-thumbs-message");
                    showMessage('.sirv-thumb-messages', 'No thumbnails available to delete. Choose which thumbs should be deleted.', 'sirv-thumbs-message', 'warning');
                    return false;
                }
            }

            if ($curButton.attr("data-type") === "regenerate"){
                let sizesCount = $(".sirv-thumbs-sizes .sirv-crop-row__checkboxes").length;
                console.log(sizesCount);
                console.log(Object.keys(preventedSizesObj).length);
                if(Object.keys(preventedSizesObj).length === sizesCount){
                    hideMessage("sirv-thumbs-message");
                    showMessage('.sirv-thumb-messages', 'No thumbnails available to regenerate. Choose which thumbs should be created.', 'sirv-thumbs-message', 'warning');
                    return false;
                }
            }

            $(".sirv-progress-wrapper").show();

            if (!isThumbsAjax) {
                isThumbsAjax = true;
                $(".sirv-thumbs-progressbar").addClass("sirv-progress-bar-animated");
                $curButton.attr('data-pause', 'false');
                $curButton.prop('disabled', false);

                thumbsProcessing({type: $curButton.attr('data-type')});
            } else {
                $curButton.attr('data-pause', 'true');
                $curButton.prop("disabled", true);
                $curButton.val("Pausing...");
                return;
            }
        }

        $(".sirv-thumbs-continue-processing").on('click', continueThumbsProcessing);
        function continueThumbsProcessing(){
            let type = $(this).attr('data-type');
            let $curButton = type == 'delete' ? $('.sirv-delete-wp-thumbs') : $('.sirv-regenerate-wp-thumbs');
            $(".sirv-processing-thumb-images-msg").hide();
            manageWPThumbs(null, $curButton);
        }


        $(".sirv-save-prevented-sizes").on('click', savePreventedSizes);
        function savePreventedSizes(e){
            e.preventDefault();

            $button = $(this);

            let preventedSizesStr = $("#sirv-prevented-sizes-hidden").val();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_save_prevented_sizes',
                    sizes: preventedSizesStr,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function() {
                    hideMessage("sirv-thumbs-message");

                    $button.val('Saving...');
                    $button.prop('disabled', true);
                }
            }).done(function (data) {
                //debug
                //console.log(data);

                if(data.status == 'saved'){
                    storePreventedSizesOnLoad();
                    $button.parent().hide();
                    $button.val("Save updated settings");
                    $button.prop("disabled", false);
                    showMessage('.sirv-thumb-messages', 'Thumbnail settings saved. Now you can delete or regenerate thumbnails.', 'sirv-thumbs-message', 'ok');
                }


            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                showMessage('.sirv-thumb-messages', "Error during ajax request: " + error, 'sirv-thumbs-message');
                $button.val("Save updated settings");
                $button.prop("disabled", false);
            });
        }


        $('.sirv-thumbs-cancel-processing').on('click', cancelThumbProcessing);
        function cancelThumbProcessing(){
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_cancel_thumbs_process'
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function() {
                    hideMessage("sirv-thumbs-message");
                    $('.sirv-thumbs-continue-processing').prop('disabled', true);
                    $(".sirv-thumbs-cancel-processing").prop("disabled", true);
                    $(".sirv-thumbs-cancel-processing").val("Canceling...");
                }
            }).done(function (data) {
                //debug
                //console.log(data);

                if(data.status == 'canceled'){
                    showMessage('.sirv-thumb-messages', 'Operation '+ data.type +' was canceled', 'sirv-thumbs-message', 'ok');
                    $(".sirv-processing-thumb-images-msg").hide();

                    $(".sirv-regenerate-wp-thumbs").prop('disabled', false);
                    $(".sirv-delete-wp-thumbs").prop("disabled", false);
                    $('.sirv-progress-wrapper').hide();

                    set_default_thumbs_progress_data();
                }


            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                showMessage('.sirv-thumb-messages', "Error during ajax request: " + error, 'sirv-thumbs-message');
            });
        }


        function set_default_thumbs_progress_data() {
            $(".sirv-thumbs-progress-percents").text(0);
            $(".sirv-thumbs-processed_ids").text(0);
            $(".sirv-thumbs-processed-files-count").text(0);
            $(".sirv-thumbs-progressbar").css("width", "0%");
        }


        function thumbsProcessing(data){
            let $curButton = data.type == 'delete' ? $('.sirv-delete-wp-thumbs') : $('.sirv-regenerate-wp-thumbs');
            let type = $curButton.attr("data-type");
            let isPause = $curButton.attr("data-pause") == "true";

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_thumbs_process',
                    type: type,
                    pause: isPause,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    hideMessage("sirv-thumbs-message");
                    if(!isPause) $curButton.val("Pause");

                    let operationedTxt = type == 'delete'? 'Deleted' : 'Regenerated';

                    $(".sirv-thumbs-operation-type").text(operationedTxt);

                    if(type == 'delete'){
                        $(".sirv-regenerate-wp-thumbs").prop('disabled', true);
                    }else{
                        $(".sirv-delete-wp-thumbs").prop("disabled", true);
                    }

                }
            }).done(function (data) {
                //debug
                //console.log(data);

                if(data.status == 'processing'){
                    $(".sirv-thumbs-progress-percents").text(data.percent_finished);
                    $(".sirv-thumbs-processed_ids").text(data.processed_ids);
                    $(".sirv-thumbs-cached_ids").text(data.ids_count);
                    $(".sirv-thumbs-processed-files-count").text(data.files_count);
                    $(".sirv-thumbs-progressbar").css('width', data.percent_finished + '%');
                    thumbsProcessing(data);
                }

                if(data.status == 'pause'){
                    $curButton.val('Continue');
                    $curButton.prop("disabled", false);
                    $(".sirv-thumbs-progressbar").removeClass("sirv-progress-bar-animated");

                    isThumbsAjax = false;
                }

                if(data.status == 'finished'){
                    $(".sirv-thumbs-progressbar").removeClass("sirv-progress-bar-animated");
                    $(".sirv-regenerate-wp-thumbs").prop('disabled', false);
                    $(".sirv-regenerate-wp-thumbs").val('Regenerate thumbnails');
                    $(".sirv-delete-wp-thumbs").prop("disabled", false);
                    $(".sirv-delete-wp-thumbs").val('Delete thumbnails');
                    $(".sirv-progress-wrapper").hide();

                    set_default_thumbs_progress_data();

                    let operationedTxt = data.type == 'delete' ? 'deleted ' : 'regenerated ';
                    let sizeTxt = data.type == 'delete' ? ' with total size '+ getFormatedFileSize(data.files_size): '';
                    let thumbsTxt = data.type == 'regenerate' ? ' (any existing thumbnails were skipped)' : '';

                    showMessage('.sirv-thumb-messages', 'Completed: '+ data.files_count +' thumbnails have been ' + operationedTxt  + sizeTxt + thumbsTxt, 'sirv-thumbs-message', 'ok');

                    isThumbsAjax = false;
                }

            }).fail(function (jqXHR, status, error) {
                $(".sirv-thumbs-progressbar").removeClass("sirv-progress-bar-animated");
                isThumbsAjax = false;

                console.log("Error during ajax request: " + error);
                showMessage('.sirv-thumb-messages', "Error during ajax request: " + error, 'sirv-thumbs-message');

            });
        }


        $('.sirv-hide-show-a').on('click', toogleBlockVisivility);
        function toogleBlockVisivility(e){
            e.preventDefault();
            e.stopPropagation();

            let showMsgTxt = $(this).attr('data-show-msg');
            let hideMsgTxt = $(this).attr('data-hide-msg');
            let showIconCls = $(this).attr('data-icon-show') || '';
            let hideIconCls = $(this).attr('data-icon-hide') || '';
            let status = $(this).attr('data-status') == 'true';

            let showMsg = showIconCls ? '<span class="'+ showIconCls +'"></span>' + showMsgTxt : showMsgTxt;
            let hideMsg = hideIconCls ? '<span class="'+ hideIconCls +'"></span>' + hideMsgTxt : hideMsgTxt;

            let selector = $(this).attr('data-selector');

            if (status){
                $(selector).slideUp();
                $(this).html(showMsg);
            }else{
                $(selector).slideDown();
                $(this).html(hideMsg)
            }

            $(this).attr('data-status', !status);
        }

        $('.sirv-clear-view-cache').on('click', emptyViewCache);
        function emptyViewCache(){
            let type = $('input:radio[name=empty-view-cache]:checked').val();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_empty_view_cache',
                    type: type,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    //hideMessage('sirv-sync-message');
                    $('.sirv-clear-view-cache').siblings('span.sirv-show-empty-view-result').text('');
                    $('.sirv-clear-view-cache').siblings('span.sirv-show-empty-view-result').hide();
                    $('.sirv-clear-view-cache').siblings('span.sirv-traffic-loading-ico').show();
                }
            }).done(function (data) {
                //debug
                //console.log(data);

                //showMessage('.sirv-sync-messages', getMessage(type), 'sirv-sync-message', 'ok');
                $('.sirv-clear-view-cache').siblings('span.sirv-traffic-loading-ico').hide();

                if(!!data){
                    if (!!data.result && Number.isInteger(data.result)){
                        let msg = (data.result / 2) + ' items deleted';
                        $('.sirv-clear-view-cache').siblings('span.sirv-show-empty-view-result').text(msg);
                        $('.sirv-clear-view-cache').siblings('span.sirv-show-empty-view-result').show();

                        setTimeout(function () { $('.sirv-clear-view-cache').siblings('span.sirv-show-empty-view-result').hide();}, 3000);
                    }

                    if (!!data.cache_data && typeof data.cache_data == 'object'){
                        let cacheData = data.cache_data;

                        for(let type in cacheData){
                            $('.empty-view-cache-'+ type).text(cacheData[type]);
                        }
                    }

                }

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                //showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-sync-message');
                $('.sirv-clear-view-cache').siblings('span.sirv-traffic-loading-ico').hide();
                $('.sirv-clear-view-cache').siblings('span.sirv-show-empty-view-result').text("Error during ajax request: " + error);
                $('.sirv-clear-view-cache').siblings('span.sirv-show-empty-view-result').show();
            });
        }


        $('.sirv-clear-view-cache-table').on('click', emptyViewCacheTable);
        function emptyViewCacheTable(){

            let type = $('input:radio[name=empty-view-cache-table]:checked').val();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_empty_view_cache',
                    type: type,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    $('.sirv-clear-view-cache-table').siblings('span.sirv-show-empty-view-result').text('');
                    $('.sirv-clear-view-cache-table').siblings('span.sirv-show-empty-view-result').hide();
                    $('.sirv-clear-view-cache-table').siblings('span.sirv-traffic-loading-ico').show();
                }
            }).done(function (data) {
                //debug
                //console.log(data);

                //showMessage('.sirv-sync-messages', getMessage(type), 'sirv-sync-message', 'ok');
                $('.sirv-clear-view-cache-table').siblings('span.sirv-traffic-loading-ico').hide();

                if(!!data){
                    if (!!data.result && Number.isInteger(data.result)){
                        let msg = (data.result / 2) + ' items deleted';
                        $('.sirv-clear-view-cache-table').siblings('span.sirv-show-empty-view-result').text(msg);
                        $('.sirv-clear-view-cache-table').siblings('span.sirv-show-empty-view-result').show();

                        setTimeout(function () { $('.sirv-clear-view-cache-table').siblings('span.sirv-show-empty-view-result').hide();}, 3000);
                    }

                    if (!!data.cache_data && typeof data.cache_data == 'object'){
                        let cacheData = data.cache_data;

                        withoutCache = (cacheData['empty'] * 1) + (cacheData['missing'] * 1);
                        withCache = (cacheData['all'] * 1) - withoutCache;

                        $('.empty-view-cache-table-with_prods').text(withCache);
                        $('.empty-view-cache-table-without_prods').text(withoutCache);
                    }

                }

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                //showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-sync-message');
                $('.sirv-clear-view-cache-table').siblings('span.sirv-traffic-loading-ico').hide();
                $('.sirv-clear-view-cache-table').siblings('span.sirv-show-empty-view-result').text("Error during ajax request: " + error);
                $('.sirv-clear-view-cache-table').siblings('span.sirv-show-empty-view-result').show();
            });
        }


        $('.sync-css').on('click', findCssImagesPrepare);
        function findCssImagesPrepare(){
            let custom_path = $('.sirv-custom-backcss-path-rb:checked').val() == 'custom' ? $('#sirv-custom-backcss-path-text').val() : '';
            let isCustom = $('.sirv-custom-backcss-path-rb:checked').val() == 'custom' ? true : false;
            let isEmptyCustomPath = $('#sirv-custom-backcss-path-text').val().length > 0 ? false : true;

            if(isCustom && isEmptyCustomPath){
                $('.sync-css').siblings('span.sirv-show-empty-view-result').text('Please fill custom path.');
                $('.sync-css').siblings('span.sirv-show-empty-view-result').show();

                return;
            }
            $.ajax({
                url: ajaxurl,
                data: {
                    "action": 'sirv_css_images_prepare_process',
                    "custom_path": custom_path
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    $('textarea[name=SIRV_CSS_BACKGROUND_IMAGES]').val();
                    $('.sync-css').siblings('span.sirv-traffic-loading-ico').show();
                    $('.sync-css').siblings('span.sirv-show-empty-view-result').text('Preparing process...');
                    $('.sync-css').siblings('span.sirv-show-empty-view-result').show();

                    $('.sirv-css-sync-data-img-count').text('Processing...');
                    $('.sirv-skipped-images-wrap').hide();
                    $('.sirv-css-sync-data-img-count-skipped').text('');
                    //$('.sirv-show-skip-data-list').hide();
                    $('.sirv-skip-images-list').hide();
                }
            }).done(function (response) {
                //debug
                //console.log(response);

                if(!!response){
                    updateCssSyncData(response);

                    if(!!response.error){
                        $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                        $('.sync-css').siblings('span.sirv-show-empty-view-result').text(response.error);
                        $('textarea[name=SIRV_CSS_BACKGROUND_IMAGES]').val();
                    }else{
                        cssImagesStartProcess();
                        setTimeout(cssImagesProcessing, 1000);

                    }
                }

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                //showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-sync-message');
                $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                $('.sync-css').siblings('span.sirv-show-empty-view-result').text("Error during ajax request: " + error);
                $('.sync-css').siblings('span.sirv-show-empty-view-result').show();
            });
        }


        function cssImagesStartProcess(){
            $.ajax({
                url: ajaxurl,
                data: {
                    "action": 'sirv_css_images_proccess',
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    $('.sync-css').siblings('span.sirv-traffic-loading-ico').show();
                    $('.sync-css').siblings('span.sirv-show-empty-view-result').text('Starting process...');
                    $('.sync-css').siblings('span.sirv-show-empty-view-result').show();
                }
            }).done(function (response) {
                //debug
                //console.log(response);

                if(!!response){
                    if (!!response.error){
                        $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                        $('.sync-css').siblings('span.sirv-show-empty-view-result').text(response.error);

                        //updateCssSyncData(response);

                        //setTimeout(function () { $('.sync-css').siblings('span.sirv-show-empty-view-result').hide();}, 3000);
                    }else{
                        /* $('.sync-css').siblings('span.sirv-show-empty-view-result').text(response.msg);
                        updateCssSyncData(response);

                        if(response.status == 'sync'){
                            setTimeout(cssImagesProcessing, 1000);
                        }else{
                            $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                            cssImagesUpdateFrontCssData();
                        } */
                    }

                    //setTimeout(function () { $('.sync-css').siblings('span.sirv-show-empty-view-result').hide();}, 3000);
                }

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                //showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-sync-message');
                $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                $('.sync-css').siblings('span.sirv-show-empty-view-result').text("Error during ajax request: " + error);
                $('.sync-css').siblings('span.sirv-show-empty-view-result').show();
            });
        }


        function cssImagesProcessing(){
            $.ajax({
                url: ajaxurl,
                data: {
                    "action": 'sirv_css_images_processing',
                },
                type: 'POST',
                dataType: "json",
                /*  beforeSend: function () {
                } */
            }).done(function (response) {
                //debug
                //console.log(response);

                if(!!response){
                    if (!!response.error){
                        $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                        $('.sync-css').siblings('span.sirv-show-empty-view-result').text(response.error);

                        updateCssSyncData(response);
                    }else{
                        $('.sync-css').siblings('span.sirv-show-empty-view-result').text(response.msg);
                        updateCssSyncData(response);

                        if(response.status == 'sync'){
                            setTimeout(cssImagesProcessing, 500);
                        }else{
                            $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                            cssImagesUpdateFrontCssData();
                        }
                    }
                }

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                //showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-sync-message');
                $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                $('.sync-css').siblings('span.sirv-show-empty-view-result').text("Error during ajax request: " + error);
                $('.sync-css').siblings('span.sirv-show-empty-view-result').show();
            });
        }


        function cssImagesUpdateFrontCssData(){
            $.ajax({
                url: ajaxurl,
                data: {
                    "action": 'sirv_css_images_get_data',
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function () {
                    /* $('.sync-css').siblings('span.sirv-traffic-loading-ico').show();
                    $('.sync-css').siblings('span.sirv-show-empty-view-result').text('Loading css data...');
                    $('.sync-css').siblings('span.sirv-show-empty-view-result').show(); */
                }
            }).done(function (response) {
                //debug
                //console.log(response);

                $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();

                if(!!response && !!response.css_data){
                    //$('.sync-css').siblings('span.sirv-show-empty-view-result').text("Css data loaded.");

                    $('textarea[name=SIRV_CSS_BACKGROUND_IMAGES]').val(response.css_data);
                    $('.sirv-save-css-code').prop('disabled', true);
                    $('.sirv-css-sync-bg-img-txtarea-wrap textarea').one('input', enableSaveCssCodeButton);
                    //$('.sirv-css-sync-bg-img-txtarea-wrap').slideUp();
                    $('.sirv-hide-show-a[data-selector=".sirv-css-sync-bg-img-txtarea-wrap"]').click();
                }

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
                //showMessage('.sirv-sync-messages', "Error during ajax request: " + error, 'sirv-sync-message');
                $('.sync-css').siblings('span.sirv-traffic-loading-ico').hide();
                $('.sync-css').siblings('span.sirv-show-empty-view-result').text("Error during ajax request: " + error);
                $('.sync-css').siblings('span.sirv-show-empty-view-result').show();
            });
        }


        $('.sirv-custom-backcss-path-rb').on('change', function(){
            let activeValue = $(this).val();

            if(activeValue == 'theme'){
                //$('.sirv-custom-backcss-path-text-wrap').slideUp();
                $('.sirv-custom-backcss-path-text-tr').hide();
            }else{
                //$('.sirv-custom-backcss-path-text-wrap').slideDown();
                $('.sirv-custom-backcss-path-text-tr').show();
                addInputCssPathPadding();
                $('#sirv-custom-backcss-path-text').focus();
            }
        });

        $('.sirv-css-sync-bg-img-txtarea-wrap textarea').one('input', enableSaveCssCodeButton);
        function enableSaveCssCodeButton(){
            $('.sirv-save-css-code').prop('disabled', false);
        }


        function updateCssSyncData(data){
            //console.log(data);
            let imgData = getCSSImgsData(data);
            $('.sirv-css-sync-data-theme').text(data.theme);
            $('.sirv-css-sync-data-date').text(data.last_sync_str);
            $('.sirv-css-sync-data-domain').text(data.img_domain);
            renderCSSImgsData(imgData, data.skipped_images);
            //console.log(new Date(+data.last_sync * 1000).toString());
            /* if(!!data.error){
                $('.sirv-css-sync-data-img-count').text(data.error);
                return;
            } */
            //$('.sirv-css-sync-data-img-count').text(!!data.img_count ? data.img_count : 0);
        }


        function renderCSSImgsData(data, skippedImages){
            $('.sirv-css-sync-data-img-count').text(data.sync_data);
            if(!!data.skip_data){
                let skippedFilesStr = getSkippedImagesAsString(skippedImages);
                $('.sirv-css-sync-data-img-count-skipped').html(data.skip_data);
                $('.sirv-skipped-images-wrap').show();
                //$('.sirv-show-skip-data-list').show();
                $('textarea.sirv-skip-images-list').val(skippedFilesStr);
            }
        }


        function getCSSImgsData(data){
            let cssCount = +data.css_files_count;
            let syncedImagesCount = +data.img_count;
            let skippedImagesCount = data.skipped_images.length;

            let imgData = {"sync_data" : '', "skip_data" : ''};

            let $msg = {
                'no_css' : 'No CSS files found',
                'one_css' : ' CSS file',
                'few_css' : ' CSS files',
                'one_synced' : ' image synced',
                'few_synced' : ' images synced',
                'one_skipped' : ' image skipped',
                'few_skipped' : ' images skipped',
            };

            if (cssCount == 0){
                imgData.sync_data = $msg.no_css;
                return imgData;
            }

            let cssText = cssCount > 1 ? cssCount + $msg.few_css : cssCount + $msg.one_css;
            let syncText = syncedImagesCount > 1 || syncedImagesCount == 0 ? syncedImagesCount + $msg.few_synced : syncedImagesCount + $msg.one_synced;
            let skippedText = skippedImagesCount > 1 || skippedImagesCount == 0 ? skippedImagesCount + $msg.few_skipped : skippedImagesCount + $msg.one_skipped;

            let syncFullText =  syncText + ', from ' + cssText;
            let skippedFullText = skippedImagesCount > 0 ? skippedText : '';

            imgData.sync_data = syncFullText;
            imgData.skip_data = skippedFullText;

            return imgData;
        }


        function getSkippedImagesAsString(sImages){
            return sImages.join('\n');
        }


        function isJsonString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

        $('.sirv-stat-refresh').on('click', refreshStats);
        function refreshStats(e){
            e.preventDefault();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_refresh_stats',
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function (){
                    $('.sirv-stats-messages').empty();
                    $('.sirv-stats-container').addClass('sirv-loading');
                },
            }).done(function (data) {
                //debug
                //console.log(data);

                $('.sirv-stats-container').removeClass('sirv-loading');
                if (!!data) {
                    window.abc = data.traffic.traffic;
                    $('.sirv-stat-last-update').html(data.lastUpdate);
                    renderStorage(data.storage);
                    renderTransfer(data.traffic);
                    renderApiUsage(data.limits);
                }
            }).fail(function (jqXHR, status, error) {
                console.error("Error during ajax request: " + error);
                showMessage('.sirv-stats-messages', "Error during ajax request: " + error, 'sirv-get-failed-message');
                $('.sirv-stats-container').removeClass('sirv-loading');
            });
        }


        function renderStorage(storage){
            if(!!storage){
                $('.sirv-allowance').html(storage.allowance_text);
                $('.sirv-st-used').html(storage.used_text + '<span> (' + storage.used_percent + '%)</span>');
                $('.sirv-st-available').html(storage.available_text + '<span> (' + storage.available_percent + '%)</span>');
                $('.sirv-st-files').html(storage.files);
            }
        }


        function renderTransfer(traffic){
            if(!!traffic){
                $('.sirv-trf-month').html(traffic.allowance_text);

                let traffics = sortTraffic(traffic.traffic);

                let trafficFragment = $(document.createDocumentFragment());


                for (let i in traffics) {
                    let tr = $('<tr class="small-padding">' +
                                '<th><label>' + traffics[i].month + '</label></th>'+
                                '<td><span>' + traffics[i].size_text + '</span></td>'+
                                '<td>'+
                                    '<div class="sirv-progress-bar-holder"><div class="sirv-progress-bar"><div>'+
                                        '<div style = "width:' + traffics[i].percent_reverse + '%;"></div>' +
                                    '</div></div></div>'+
                                '</td>'+
                            '</tr>'
                            );
                    trafficFragment.append(tr);
                }

                let $trafficRows = $('.traffic-wrapper').children();
                for (let i = 1; i < $trafficRows.length; i++) {
                    $trafficRows[i].remove();
                }

                $($trafficRows[0]).after(trafficFragment);
            }
        }


        function sortTraffic(traffic){
            let keys = Object.keys(traffic);
            let tmpArr = [];

            for (let index in keys) {
                let key = keys[index];
                traffic[key]['month'] = key;
                tmpArr.push(traffic[key]);
            }

            tmpArr.sort(function (a, b) {
                return a.order - b.order
            })

            return tmpArr;
        }


        function renderApiUsage(limits){

            if(!!limits){
                let apiFragment = $(document.createDocumentFragment());
                let apiTypes = Object.keys(limits);

                for (let i in apiTypes) {
                    let limit = limits[apiTypes[i]];

                    let count = limit.count > 0 ? limit.count : '-';
                    let used = limit.count > 0 ? ' (' + limit.used + ')' : '';
                    //let reset = limit.count > 0 ? limit.reset_str + ' <span class="sirv-limits-reset-local">(' + renderTime(limit.reset_timestamp) + ')</span>' : '-';
                    let reset = limit.count > 0 ? limit.count_reset_str + ' <span class="sirv-grey">(' + limit.reset_str + ')</span>' : '-';
                    let isLimitReached = limit.count >=limit.limit ? 'style="color: red;"' : '';

                    let limitRow = $('<tr '+ isLimitReached +'>\n<td>' +
                                    limit.type + '</td>\n<td>' +
                                    limit.limit + '</td>\n<td>'+
                        count + used +'</td>\n<td><span class="sirv-limits-reset" data-timestamp="'+ limit.reset_timestamp+'" >' + reset + '</span></td></tr>\n');
                    apiFragment.append(limitRow);
                }
                $('.sirv-api-usage-content').empty();
                $('.sirv-api-usage-content').append(apiFragment);

            }
        }


        function setCorrectTime(){
            let $times = $('.sirv-limits-reset');
            $.each($times, function(index, elem){
                let el = $(elem);
                let timestamp = el.attr('data-timestamp');
                el.html(el.text() + ' <span class="sirv-limits-reset-local">(' + renderTime(timestamp) + ')</span>');
            });
        }


        function renderTime(timestamp){
            let jsTimeStamp = timestamp * 1000;
            let d = new Date(jsTimeStamp);

            let h = d.getHours();
            let m = d.getMinutes();
            let s = d.getSeconds();
            return zeroInTime(h) + ':' + zeroInTime(m) + ':' + zeroInTime(s) + ' ' + convertTimeZoneOffset(d.getTimezoneOffset());
        }


        function zeroInTime(t){
            let ct = t < 10 ? '0' + t : t;
            return ct;
        }


        function convertTimeZoneOffset(offset){
            let timeOffset = Math.abs(offset);
            timeOffset = timeOffset > 0 ? timeOffset / 60 : timeOffset;

            return '+0' + timeOffset + ':00';
        }


        function ajaxRequest(ajaxurl, data, type = 'POST', async = true, trafficData, key, value) {
            $.ajax({
                url: ajaxurl,
                data: data,
                type: type,
                async: async
            }).done(function (response) {
                //console.log(response);

                if (response !== '' && isJsonString(response)) {
                    let json_obj = JSON.parse(response);
                    trafficData.push({
                        size: calcTraffic(json_obj),
                        date: value[2],
                        order: value[3]
                    });
                } else {
                    console.error('Server returned non JSON Trafic data');
                    console.info('Response dump:', response);
                    trafficData.length = 13;
                    $('.sirv-tf-loading-error').html("Error during ajax request: Fetch data failed");
                    $('.sirv-traffic-loading').hide();
                }

            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error + status);
                //hack to check that data is not fetched
                trafficData.length = 13;
                if (error) {
                    $('.sirv-tf-loading-error').html("Error during ajax request: " + error);
                } else {
                    $('.sirv-tf-loading-error').html("Error during ajax request: Fetch data failed");
                }
                $('.sirv-traffic-loading').hide();
            });
        }


        function getFormatedFileSize(bytes) {
            let negativeFlag = false;
            let position = 0;
            let units = [" Bytes", " KB", " MB", " GB", " TB"];

            bytes = parseInt(bytes);

            if (bytes < 0) {
                bytes = Math.abs(bytes);
                negativeFlag = true;
            }

            while (bytes >= 1000 && (bytes / 1000) >= 1) {
                bytes /= 1000;
                position++;
            }

            if (negativeFlag) bytes *= -1;

            bytes = bytes % 1 == 0 ? bytes : bytes.toFixed(2);

            return bytes + units[position];

        }


        //modify form action before send settings data
        $("form#sirv-save-options").on('submit', sendSettings);
        function sendSettings(e){
            let activeTab = $('.nav-tab-active').attr('href');

            let $form = $("form#sirv-save-options");
            let action = $form.attr('action');

            if (!!activeTab) action += activeTab;

            $form.attr('action', action);

            return true;
        }


        function manageElement(selector, disableFlag, text, button, css, html) {
            if (disableFlag !== 'none') {
                $(selector).prop('disabled', disableFlag);
            }
            if (typeof text !== 'undefined' && text !== 'none') {
                if (typeof button !== 'undefined' && button !== 'none') {
                    $(selector).val(text);
                } else {
                    $(selector).html(text);
                }

            }
            if (typeof css !== 'undefined' && css !== 'none') {
                $(selector).css(css);
            }
            if (typeof html !== 'undefined' && html !== 'none') {
                $(selector).html(html);
            }
        }


        function setProfiles(){
            manageSelect('#sirv-shortcodes-profiles', '#sirv-shortcodes-profiles-val', false);
            manageSelect('#sirv-cdn-profiles', '#sirv-cdn-profiles-val', false);
            manageSelect('#sirv-woo-product-profiles', '#sirv-woo-product-profiles-val', false);
            manageSelect('#sirv-woo-category-profiles', '#sirv-woo-category-profiles-val', false);
            manageSelect('#sirv-woo-product-mobile-profiles', '#sirv-woo-product-mobile-profiles-val', false);
            //not profile
            manageSelect('#sirv-woo-product-ttl', '#sirv-woo-product-ttl-val', false);
        }


        $('#sirv-shortcodes-profiles').on('change', function () {
            manageSelect('#sirv-shortcodes-profiles', '#sirv-shortcodes-profiles-val', true);
        });
        $('#sirv-cdn-profiles').on('change', function () {
            manageSelect('#sirv-cdn-profiles', '#sirv-cdn-profiles-val', true);
        });

        $('#sirv-woo-product-profiles').on('change', function () {
            manageSelect('#sirv-woo-product-profiles', '#sirv-woo-product-profiles-val', true);
        });

        $('#sirv-woo-category-profiles').on('change', function () {
            manageSelect('#sirv-woo-category-profiles', '#sirv-woo-category-profiles-val', true);
        });

        $('#sirv-woo-product-mobile-profiles').on('change', function () {
            manageSelect('#sirv-woo-product-mobile-profiles', '#sirv-woo-product-mobile-profiles-val', true);
        });

        $('#sirv-woo-product-ttl').on('change', function () {
            manageSelect('#sirv-woo-product-ttl', '#sirv-woo-product-ttl-val', true);
        });


        function manageSelect(selector, valueElem, onChange = false) {
            let $valElem = $(valueElem);

            if (onChange) {
                $valElem.val($(selector + ' option:selected').val());
            } else {
                $(selector + " option[value='" + $valElem.val() + "']").prop('selected', true);
            }

        }



        $('.debug-button').on('click', function () {
            let data = {}
            data['action'] = 'sirv_debug';

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                beforeSend: function () {
                    console.log('-----------------------------------DEBUG START-----------------------------------')
                }
            }).done(function (response) {
                console.log(response);
                console.log('-----------------------------------DEBUG END-----------------------------------')
            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error + ' ' + status);
                console.log('-----------------------------------DEBUG END-----------------------------------')
            });
        });

        //$('.sirv-switch').on('change', function () {
        $('.sirv-switch-acc-login').on('click', switchAccLogin);
        function switchAccLogin(e){

            e.preventDefault();
            e.stopPropagation();
            $(this).trigger( "blur");

            isNewAccount = !isNewAccount;

            let passText = isNewAccount ? 'Choose password' : 'Password';
            let buttonText = isNewAccount ? 'Create account' : 'Connect account';
            let accText = isNewAccount ? 'Choose account name' : 'Account name';
            let accLabelText = isNewAccount ? 'Already have an account?' : 'Don\'t have an account?';
            let linkText = isNewAccount ? 'Sign in' : 'Create account';

            $('.sirv-pass-field').text(passText);
            $('.sirv-acc-field').text(accText);
            $('.sirv-init').val(buttonText);
            $('.sirv-acc-label').text(accLabelText);
            $('.sirv-switch-acc-login').text(linkText);

            let $hide = $('.sirv-block-hide');
            let $visible = $('.sirv-block-visible');
            $hide.addClass('sirv-block-visible').removeClass('sirv-block-hide');
            $visible.addClass('sirv-block-hide').removeClass('sirv-block-visible');
        }


        $('.storage-size-test').on('click', getImagesStorageSize);
        function getImagesStorageSize(){
            $.ajax({
                url: ajaxurl,
                data: {action: 'sirv_images_storage_size'},
                type: 'POST',
                dataType: "json",
                beforeSend: function (){
                    $('.v-time').text('calc...');
                    $('.v-size').text('calc...');
                    $('.v-count').text('calc...');
                },
            }).done(function (res) {
                //debug
                //console.log(res);

                $('.v-time').text(res.microtime + ' ms ( '+ res.time + ' sec )');
                $('.v-size').text(res.size);
                $('.v-count').text(res.count);


            }).fail(function (jqXHR, status, error) {
                console.log("Error during ajax request: " + error);
            });
        }

        $(document).on('options_tab_changed', onOptionsTabChanged);
        function onOptionsTabChanged(event){
            if(!!event.detail.hash && event.detail.hash == 'cache'){
                addInputCssPathPadding();
            }
        }

        function addInputCssPathPadding(){
            let $constPath = $('.sirv-input-const-text');
            if(!!$constPath.length){
                let $cssPathInput = $('#sirv-custom-backcss-path-text');
                let constPathClientRect = $constPath[0].getBoundingClientRect();
                let constPathWidth = constPathClientRect['width'];

                $cssPathInput.css('padding-left', constPathWidth);
            }
        }


        $('input[name=sirv-woo-pin-video], input[name=sirv-woo-pin-spin], input[name=sirv-woo-pin-image], input[name=sirv-woo-pin-model]').on('click', updatePinData);
        $('#sirv-woo-pin-input-template').on('input', updatePinData);
        function updatePinData(){
            let video = $('input[name=sirv-woo-pin-video]:checked').val();
            let spin = $('input[name=sirv-woo-pin-spin]:checked').val();
            let image = $('input[name=sirv-woo-pin-image]:checked').val();
            let model = $("input[name=sirv-woo-pin-model]:checked").val();
            let image_template = $('#sirv-woo-pin-input-template').val();

            if(image !== 'no'){
                $('.sirv-woo-pin-input-wrapper').show();
            }else{
                $('.sirv-woo-pin-input-wrapper').hide();
            }

            $('#sirv-woo-pin-gallery').val(JSON.stringify({'video': video, 'spin': spin, 'image': image, 'model': model, 'image_template' : image_template}));
        }


        $('input[name=sirv-woo-cat-content-images], input[name=sirv-woo-cat-content-videos], input[name=sirv-woo-cat-content-spins]').on('click', updateCatContentData);
        function updateCatContentData(){
            const video = $('input[name=sirv-woo-cat-content-videos]').is(':checked') ? 'yes' : 'no';
            const spin = $('input[name=sirv-woo-cat-content-spins]').is(':checked') ? 'yes' : 'no';
            const image = $('input[name=sirv-woo-cat-content-images]').is(':checked') ? 'yes' : 'no';

            $('#sirv-woo-cat-content-hidden').val(JSON.stringify({'video': video, 'spin': spin, 'image': image}));
        }

        $('input[name=sirv_woo_cat_swap_method_bullets], input[name=sirv_woo_cat_swap_method_arrows]').on('click', updateCatSwapData);
        function updateCatSwapData(){
            const bullets = $('input[name=sirv_woo_cat_swap_method_bullets]').is(':checked') ? 'yes' : 'no';
            const arrows = $('input[name=sirv_woo_cat_swap_method_arrows]').is(':checked') ? 'yes' : 'no';

            $("#sirv-woo-cat-swap-method-hidden").val(JSON.stringify({ arrows: arrows, bullets: bullets }));
        }

        $("input[name=SIRV_WOO_CAT_ITEMS]").on('change', manageWooCatItemsState);
        function manageWooCatItemsState() {
            wooCatItemsState($(this).val());
        }


        function wooCatItemsState(activeItem){
            const $sirv_woo_cat_content_id = $('#sirv_woo_cat_content_id');
            const $sirv_woo_cat_source_id = $("#sirv_woo_cat_source_id");
            const $sirv_woo_cat_swap_method_id = $('#sirv_woo_cat_swap_method_id');
            const $sirv_woo_cat_zoom_on_hover_id = $('#sirv_woo_cat_zoom_on_hover_id');
            switch (activeItem * 1) {
                case 1:
                    $sirv_woo_cat_content_id.show();
                    $sirv_woo_cat_source_id.show();
                    $sirv_woo_cat_swap_method_id.hide();
                    $sirv_woo_cat_zoom_on_hover_id.show();
                    break;
                case 2:
                    $sirv_woo_cat_content_id.hide();
                    $sirv_woo_cat_source_id.show();
                    $sirv_woo_cat_swap_method_id.hide();
                    $sirv_woo_cat_zoom_on_hover_id.hide();
                    break;
                case 3:
                case 4:
                case 1000:
                    $sirv_woo_cat_content_id.show();
                    $sirv_woo_cat_source_id.show();
                    $sirv_woo_cat_swap_method_id.show();
                    $sirv_woo_cat_zoom_on_hover_id.show();
                    break;

                default:
                    break;
            }
        }


        function initializeWooCatItemsState(){
            activeItem = $("input[name=SIRV_WOO_CAT_ITEMS]:checked").val() || 1;
            wooCatItemsState(activeItem);
        }

        //-----------------------smv order----------------------------
        $("#sirv-smv-order-items").sortable({
            items: "> li:not(:last)",
            cursor: "move",
            scrollSensitivity: 40,
            opacity: 1,
            scroll: false,
            placeholder: "sirv-smv-order-sortable-placeholder",
            stop: function (event, ui) {
                recalcSmvOrderData();
            },
        });

        $(".sirv-smv-order-item-add").on("click", showOrderSelect);
        function showOrderSelect(e) {
            $(".sirv-smv-order-select").show();
        }

        $(".sirv-smv-order-select-item").on("click", function (e) {
            e.stopPropagation();
            const type = $(this).attr("data-item-type");
            const text = $(this).text();

            addOrderItem(type, text);
            $(".sirv-smv-order-select").hide();
        });

        function addOrderItem(type, text) {
            const $addItem = $(".sirv-smv-order-item-add");

            const itemHTML = `
        <li class="sirv-smv-order-item sirv-smv-order-item-changeble sirv-no-select-text" data-item-type="${type}">
            <div class="sirv-smv-order-item-dots">⠿</div>
            <div class="sirv-smv-order-item-title"><span>${text}</span></div>
            <div class="sirv-smv-order-item-delete"><span class="dashicons dashicons-trash"></span></div>
        </li>`;

            $addItem.before(itemHTML);
            recalcSmvOrderData();
        }

        $(document).on("click", ".sirv-smv-order-item-delete", deleteOrderItem);
        function deleteOrderItem(e) {
            const $item = $(this).parent();
            $item.remove();

            recalcSmvOrderData();
        }

        function recalcSmvOrderData() {
            data = [];
            $(".sirv-smv-order-item-changeble").each(function () {
                data.push($(this).attr("data-item-type"));
            });

            $("#sirv-woo-smv-content-order").val(JSON.stringify(data));
        }
        //--------------------END smv order---------------------------


        $('input[name=SIRV_FOLDER]').on('input', showWarningOnFolderChange);
        function showWarningOnFolderChange() {
            $('.sirv-warning-on-folder-change').fadeIn(800);
        }


        $("input[name=SIRV_HTTP_AUTH_CHECK]").on('change', onAuthCheckChange);
        function onAuthCheckChange(){
            let isChecked = $("input[name=SIRV_HTTP_AUTH_CHECK]").is(':checked');
            let $authCredBlock = $(".sirv-http-auth-credentials");

            if(isChecked){
                $authCredBlock.fadeIn();
            }else{
                $authCredBlock.fadeOut();
            }
        }


        //-----------------------sirv js modules--------------------------------
        function debounce(func, timeout = 1000){
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }


        const debauncedGetSirvJsCompressedSize = debounce(getSirvJsCompressedSize);

        $("input[name=sirv_js_module]").on("change", manageSirvJsModulesLoad);
        function manageSirvJsModulesLoad(){
            const $optionStore = $("#sirv-js-modules-store");
            const $all = $("#all-js-modules-switch");
            const $modules = $("input[name=sirv_js_module]");
            const allModules = ['lazyimage','zoom','spin','hotspots','video', 'gallery', 'model'];

            let isNotAllActiveModules = false;

            let activeModules = [];

            function manageAllState($selector, activeModules){
                let isSelectorChecked = $selector.is(":checked");

                if(activeModules.length == allModules.length && !isSelectorChecked){
                    $selector.prop('checked', true);
                    $selector.prop('disabled', true);
                }

                if (activeModules.length < allModules.length && isSelectorChecked) {
                    $selector.prop("checked", false);
                    $selector.prop("disabled", false);
                }

            }

            $modules.each(function(){
                if ($(this).is(":checked")){
                    activeModules.push($(this).attr('data-module'));
                }
            });


            if(activeModules.length === 0){
                isNotAllActiveModules = true;
                hideMessage("js-modules-warning", true);
                showMessage('.js-modules-messages', 'Please select at least one feature', 'js-modules-warning', 'warning');
            }else{
                hideMessage("js-modules-warning");
            }

            manageAllState($all, activeModules);

            let urlModulesStr = activeModules.join(",");
            setSirvJsCompressedSize(urlModulesStr);

            const modulesStr = isNotAllActiveModules ? allModules.join(",") : activeModules.join(",");
            $optionStore.val(modulesStr);

        }


        $("#all-js-modules-switch").on("change", manageSirvJsModulesLoadAll);
        function manageSirvJsModulesLoadAll(){
            const $modules = $("input[name=sirv_js_module]");
            let isChecked = $(this).is(':checked');

            if(isChecked){
                $(this).prop('disabled', true);
                $modules.each(function () {
                    $(this).prop("checked", isChecked);
                });
            }
            manageSirvJsModulesLoad();
        }


        function setSirvJsCompressedSize(modules){
            if(!!!modules){
                $(".sirv-compressed-js-val").text("0 Kb");
                return;
            }
            if (!!sirvJsCompressedSizes[modules]){
                $(".sirv-compressed-js-val").text(getSirvJsBudleSizeStr(sirvJsCompressedSizes[modules]));
            }else{
                $(".sirv-compressed-js-spinner").show();
                $(".sirv-compressed-js-val").text("Calculating...");
                //debounce
                debauncedGetSirvJsCompressedSize(modules);
            }

        }


        function getSirvJsBudleSizeStr(sizes){
            return `${sizes.compressed_s} (unzipped ${sizes.uncompressed_s})`;
        }


        function getSirvJsCompressedSize(modules){
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_get_js_module_size',
                    modules: modules,
                    _ajax_nonce: sirv_options_data.ajaxnonce,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function (){
                  //code here
                },
            }).done(function (res) {
                //debug
                //console.log(res);

                $(".sirv-compressed-js-spinner").hide();

                if(!!res.error){
                    $(".sirv-compressed-js-val").text("Error: " + error);
                }else{
                    $(".sirv-compressed-js-val").text(`${res.compressed_s} (unzipped ${res.uncompressed_s})`);
                    sirvJsCompressedSizes[modules] = {compressed_s: res.compressed_s, uncompressed_s: res.uncompressed_s};
                }




            }).fail(function (jqXHR, status, error) {
                $(".sirv-compressed-js-spinner").hide();

                console.log("Error during ajax request: " + error);
                $(".sirv-compressed-js-val").text('Error: ' + error);
            });
        }

        //manage SIRV_ENABLE_CDN option change
        $("input[name=SIRV_ENABLE_CDN]").on("change", manageEnableCDNOption);
        function manageEnableCDNOption(){
            const status = $(this).val();
            const isEnabled = status === '1' ? true : false;

            const $parseImages = $("input[name=SIRV_PARSE_STATIC_IMAGES]");
            const $parseVideos = $("input[name=SIRV_PARSE_VIDEOS]");

            if(isEnabled){
                $("input[name=SIRV_PARSE_STATIC_IMAGES][value=1]").prop('disabled', false);
                $("input[name=SIRV_PARSE_VIDEOS][value=on]").prop('disabled', false);
            }else{
                $("input[name=SIRV_PARSE_STATIC_IMAGES][value=1]").prop('disabled', true);
                $("input[name=SIRV_PARSE_STATIC_IMAGES][value=2]").prop('checked', true);
                $("input[name=SIRV_PARSE_VIDEOS][value=on]").prop('disabled', true);
                $("input[name=SIRV_PARSE_VIDEOS][value=off]").prop('checked', true);
            }
        }


        //migrate woo additional variation images data
        $(".sirv-migrate-wai-data").on('click', migrateWAIData);
        function migrateWAIData(){
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'sirv_migrate_wai_data',
                    _ajax_nonce: sirv_options_data.ajaxnonce,
                },
                type: 'POST',
                dataType: "json",
                beforeSend: function (){
                    $(".sirv-wai-bar-line-complited").addClass("sirv-progress-bar-animated");
                    $(".sirv-migrate-wai-data").prop('disabled', true);
                    $(".sirv-migrate-wai-data").text('Migrating...');
                },
            }).done(function (res) {
                //debug
                //console.log(res);

                if(res.error){
                    $('.sirv-wai-bar-line-complited').removeClass('sirv-progress-bar-animated');
                    updateWAIProcessingStatus(res);
                    showMessage('.sirv-migrate-wai-data-messages', res.error, 'sirv-migrate-wai-data-message', 'error');
                    return;
                }

                if(res.synced_percent == 100){
                    $('.sirv-wai-bar-line-complited').removeClass('sirv-progress-bar-animated');
                    $block = $('.migrate-woo-additional-images-wrapper');
                    $block.empty();
                    $block.append(
                        `<span class="sirv-option-responsive-text">If you use the WooCommerce Additional Variation Images plugin, you can migrate images from that plugin into Sirv. You don\'t need that plugin if you use Sirv.</span>
                        <p>All images have been migrated. You may wish to uninstall the WooCommerce Additional Variation Images plugin.</p>`
                    );
                }else{
                    updateWAIProcessingStatus(res);
                    migrateWAIData();
                }
            }).fail(function (jqXHR, status, error) {
                $('.sirv-wai-bar-line-complited').removeClass('sirv-progress-bar-animated');
                $(".sirv-migrate-wai-data").prop("disabled", false);
                $(".sirv-migrate-wai-data").text("Migrate");

                showMessage('.sirv-migrate-wai-data-messages', error, 'sirv-migrate-wai-data-message', 'error');

                console.error("Error during ajax request: " + error);
            });
        }


        function updateWAIProcessingStatus(data){
            //messages .sirv-migrate-wai-data-messages
            //button .sirv-migrate-wai-data
            //percent .sirv-wai-progress-text-persents
            //count .sirv-wai-progress-text-complited
            //bar .sirv-wai-bar-line-complited

            $(".sirv-wai-progress-text-persents").text(data.synced_percent_text);
            $(".sirv-wai-progress-text-complited span").text(`${data.synced} out of ${data.all}`);
            $(".sirv-wai-bar-line-complited").css('width', data.synced_percent_text);
        }

        //-----------------------END sirv js modules------------------


        $(".sirv-scrollbox").on("scroll", scrollShadows);
        function scrollShadows(){
            const content = document.querySelector(".sirv-scrollbox");
            const wrapper = document.querySelector(".sirv-scrollbox-parent");
            const shadowTop = document.querySelector(".sirv-shadow-top");
            const shadowBottom = document.querySelector(".sirv-shadow-bottom");

            let contentScrollHeight = content.scrollHeight - wrapper.offsetHeight;

            let currentScroll = $(this).scrollTop() / contentScrollHeight;
            shadowTop.style.opacity = currentScroll;
            shadowBottom.style.opacity = 1 - currentScroll;
        }


        //-----------------------initialization-----------------------
        setProfiles();
        getTabFromUrlHash();
        //setCorrectTime();
        //showResponsiveWarning();
        addInputCssPathPadding();
        storePreventedSizesOnLoad();
        onAuthCheckChange();
        initializeWooCatItemsState();

    }); //domready end
});
