/**
 * Premmerce multicurrency plugin page actions
 */
jQuery(function ($) {
    function PremmerceMulticurrencyAdmin() {
        this.deleteButton = $('a.premmerce-currency-delete');
        this.currencyForms = $('#add-currency, #update-currency');
        this.mainCurrencyRadio = $('input.is-main-checkbox[type=radio]');
        this.recalculateButton = $('#recalculate-prices');
        this.recalculateInfo = $('#recalculation-percentage');
        this.countriesSelector = $('#currency-countries');
        this.currencyCodeSelector = $('#currency-code');
        this.countriesListSelector = $('#currency-countries-list');
        this.countriesListExcludeLabel = $('.currency-countries-list-except-text');
        this.countriesListIncludeLabel = $('.currency-countries-list-include-text');
        this.nextUpdateSecondsSpan = $('.premmerce-multicurrency-next-update-info__countdown');
        this.currencyRateInput = $('#currency-rate');
        this.invertedCurrencyRateInput = $('#currency-rate-inv');
        this.getRatesButton = $('#get-rates');
        this.currencyUpdaterSelector = $('#currency-updater');
        this.editedCurrencySpan = $('.premmerce-multicurrency-edited-currency-code');
        this.getCurrencyRateSpinner = $('.premmerce-get-currency-rate-spinner');
        this.updatersStatusesSpans = $('.premmerce-multicurrency-updater-status');
        this.ratesUpdateEnabledCheckbox = $('#premmerce_multicurrency_rates_update_enabled');
        this.ratesUpdateIntervalInput = $('[name=premmerce_multicurrency_rates_updater_frequency]');
        this.editedCurrencyCode = this.currencyCodeSelector.length ? this.currencyCodeSelector.val() : currencies.editedCurrencyCode;
        this.secBefore = 0;
        this.countriesExistValue = this.countriesSelector.val();
        this.countriesListExistValue = this.countriesListSelector.val();
        this.productsTotal = 0;
        this.recalculateProgressBar = '[data-recalculate-progress-bar]';
        this.nonces = currencies.nonces;
        this.messages = currencies.messages;
        this.isPremium = currencies.isPremium;
        this.redirectPath = '';

        var that = this;

        this.editedCurrencySpan.text(this.editedCurrencyCode);


        PremmerceMulticurrencyAdmin.prototype.changeCurrenciesListState = function () {

            switch (that.countriesSelector.val()) {
                case 'none':
                case 'all':
                    that.countriesListSelector.val('');
                    that.countriesListSelector.parent('.form-field').addClass('hidden');
                    break;

                case 'except':
                    that.countriesListSelector.parent('.form-field').removeClass('hidden');
                    that.countriesListExcludeLabel.show();
                    that.countriesListIncludeLabel.hide();
                    that.setCurrenciesList();
                    break;
                case 'include':
                    that.countriesListSelector.parent('.form-field').removeClass('hidden');
                    that.countriesListExcludeLabel.hide();
                    that.countriesListIncludeLabel.show();
                    that.setCurrenciesList();
            }
            //reinitialize select2
            that.countriesListSelector.select2('destroy').select2();

        };
        PremmerceMulticurrencyAdmin.prototype.setCurrenciesList = function () {


            that.countriesListSelector.val('');
            if (that.countriesListExistValue != null && that.countriesExistValue != null) {
                if (that.countriesSelector.val() === that.countriesExistValue) {
                    that.countriesListSelector.val(that.countriesListExistValue);
                }
            }
            else if (that.countriesSelector.val() === 'include') {
                var selectedCurrencyCode = (that.currencyCodeSelector.length > 0) ? $('#currency-code').val() : '';
                var countries = Object.keys(currencies.countriesAndCurrenciesCodes).indexOf(selectedCurrencyCode) ? currencies.countriesAndCurrenciesCodes[selectedCurrencyCode] : that.countriesListExistValue;
                that.countriesListSelector.val(countries)
            }
        };
        PremmerceMulticurrencyAdmin.prototype.changeMainCurrency = function () {

            $.ajax({
                url: ajaxurl,
                method: 'post',
                data: {
                    action: 'premmerceChangeShopCurrency',
                    currency_id: $('.is-main-checkbox:checked').val(),
                    premmerceNonce: that.nonces.changeShopCurrency
                },
                success: function (response) {
                    if ('changedOk' === response.status) {
                        if(that.isPremium){
                            that.nonces.changeShopCurrency = response.nextChangeNonce;
                            if (that.redirectPath === '') {
                                that.redirectPath = response.redirectPath;
                            }
                            that.updateProgressBar(0);
                            that.sendRecalculateAjax();
                        }
                        else{
                            location.reload();
                        }
                    }
                }
            });

        };
        PremmerceMulticurrencyAdmin.prototype.calculatePercentage = function (productsLeft) {

            var productsPercent;

            if (that.productsTotal === 0) {
                that.productsTotal = productsLeft + 10;
            }
            if (productsLeft === 0) {
                productsPercent = 100;
            }
            else {
                productsPercent = parseInt(productsLeft) / parseInt(that.productsTotal);
                productsPercent = 100 - (productsPercent.toFixed(2) * 100);
            }
            return productsPercent;
        };
        PremmerceMulticurrencyAdmin.prototype.scrollToTop = function () {
            $("html, body").animate({scrollTop: 0}, "slow");

        };
        PremmerceMulticurrencyAdmin.prototype.updateProgressBar = function (percent) {
            if (0 == percent) {

                that.scrollToTop();
            }
            $(that.recalculateProgressBar).progressbar({
                value: percent
            });
            var text = that.messages.progress + ' ' + percent + '%';
            if (percent === 100) {
                text = that.messages.done;
            }
            that.recalculateInfo.text(text);
        };
        PremmerceMulticurrencyAdmin.prototype.sendRecalculateAjax = function () {

            $.ajax({
                url: ajaxurl,
                method: 'post',
                data: {
                    action: 'premmerceRecalculatePrices',
                    premmerceNonce: that.nonces.recalculateProductsPrices
                },
                success: function (response) {



                    var recalculationPercent = that.calculatePercentage(response.productsLeft);
                    that.updateProgressBar(recalculationPercent);
                    if (response.productsLeft > 0) {
                        that.nonces.recalculatePrices = response.nextRecalculateNonce;
                        that.sendRecalculateAjax();
                    }
                    else {
                        setTimeout(function () {
                            window.location = that.redirectPath;
                        }, 500);
                    }
                    return response.productsLeft;
                }
            });

        };

        PremmerceMulticurrencyAdmin.prototype.countdownTimer = function () {
            var seconds = that.secBefore;
            var minuteInSeconds = 60;
            var hourInSeconds = minuteInSeconds * 60;
            var dayInSeconds = hourInSeconds * 24;
            var monthInSeconds = dayInSeconds * 30;
            var timeBefore = '';
            if (seconds > 0) {


                timeBefore = ('0' + String(seconds % minuteInSeconds)).slice(-2);
                if (seconds >= minuteInSeconds) {


                    timeBefore = String(Math.floor(seconds % hourInSeconds / minuteInSeconds)) + ':' + timeBefore;
                    if (seconds >= hourInSeconds) {

                        timeBefore = String(Math.floor(seconds % dayInSeconds / hourInSeconds)) + ':' + timeBefore;
                        if(seconds >= dayInSeconds){
                            timeBefore = String(Math.floor(seconds % monthInSeconds / dayInSeconds)) + ' days ' + timeBefore;
                        }

                    }
                }

                else {
                    timeBefore = '00:' + timeBefore;
                }
            }
            that.secBefore--;

            that.nextUpdateSecondsSpan.text(timeBefore).parents('.premmerce-multicurrency-next-update-info').show();
            console.log(that.nextUpdateSecondsSpan.parents('.premmerce-multicurrency-next-update-info'));
        };


        PremmerceMulticurrencyAdmin.prototype.updateServicesStatuses = function () {
            if (that.updatersStatusesSpans.length === 0) {
                return;
            }

            $.ajax({
                url: ajaxurl,
                method: 'get',
                data: {
                    action: 'premmerceGetUpdatersStatuses',
                    premmerceNonce: that.nonces.premmerceGetUpdatersStatuses,
                },
                beforeSend: function () {
                    that.updatersStatusesSpans.addClass('fa-spin');
                },

                success(response) {
                    that.updatersStatusesSpans.each(function () {
                        var classToAdd = response[$(this).data('updater-id')] ? 'fa-check' : 'fa-exclamation-triangle';
                        var statusMessage = response[$(this).data('updater-id')] ? that.messages.updaterAvailable : that.messages.updaterUnavailable;
                        $(this).removeClass('fa-refresh fa-spin').addClass(classToAdd).siblings('.premmerce-multicurrency-updater-status-message').text(statusMessage);
                    });
                }
            });
        };


        PremmerceMulticurrencyAdmin.prototype.changeUpdateFrequencyInputState = function () {
            if (that.ratesUpdateEnabledCheckbox.length > 0) {
                $(that.ratesUpdateEnabledCheckbox).is(':checked') ? $(that.ratesUpdateIntervalInput).removeAttr('disabled') : $(that.ratesUpdateIntervalInput).attr('disabled', 'disabled');
            }
        };


        this.checkAvailableUpdatersForCurrency = function () {

            $.ajax({
                url: ajaxurl,
                method: 'get',
                data: {
                    action: 'premmerceGetUpdatersForCurrency',
                    currencyCode: that.editedCurrencyCode,
                    premmerceNonce: that.nonces.getUpdaters
                },
                success: function (response) {
                    $.each(that.currencyUpdaterSelector.children('option[value!=""]'), function (index, value) {
                        var option = $(value);
                        if (response.indexOf(option.val()) < 0) {
                            option.addClass('updater-excluded');
                        }
                        else {
                            option.removeClass('updater-excluded');
                        }
                    });
                }

            });

        };

        this.fillReversedExchangeRatesField = function (target) {

            var inputToGet = that.currencyRateInput;

            var inputToFill = that.invertedCurrencyRateInput;
            if (target && $(target).is(that.invertedCurrencyRateInput)) {

                inputToGet = that.invertedCurrencyRateInput;
                inputToFill = that.currencyRateInput;
            }
            var rate = parseFloat(inputToGet.val());

            if (rate) {
                var invertedRate = parseFloat(parseFloat(1 / rate).toFixed(7));
                inputToFill.val(invertedRate);
            }
        };

        this.getRatesForCurrency = function () {
            $.ajax({
                url: ajaxurl,
                method: 'get',
                data: {
                    action: 'premmerceGetCurrencyRate',
                    currencyCode: that.editedCurrencyCode,
                    updaterId: that.currencyUpdaterSelector.val(),
                    premmerceNonce: that.nonces.getCurrencyRate
                },
                beforeSend: function () {
                    that.getCurrencyRateSpinner.addClass('fa-spin');
                },

                success: function (response) {
                    that.getCurrencyRateSpinner.removeClass('fa-spin');
                    if (typeof response.rate !== 'undefined') {
                        that.currencyRateInput.val(response.rate);
                        that.fillReversedExchangeRatesField();
                    }
                    else {
                        alert(response.message);
                    }
                }
            });
        };

        this.prepareInvalidMessage = function (text) {
            return '<span class="premmerce-multicurrency-invalid-message">' + text + '</span>';
        };


        this.deleteButton.on('click', function () {
            return confirm(currencies.messages.deleteMessage);
        });


        this.getRatesButton.on('click', function () {

            $.validator.addMethod(
                'updaterSelected',
                function (value, element) {
                    return ($(element).val());
                },
                that.prepareInvalidMessage(that.messages.noUpdaterSelected)
            );

            that.currencyUpdaterSelector.rules('add', {
                updaterSelected: ''
            });
            if (that.currencyUpdaterSelector.valid()) {


                that.getRatesForCurrency();
            }

            that.currencyUpdaterSelector.rules('remove', 'updaterSelected');

        });


        this.countriesListSelector.select2();


        this.countriesSelector.on('change', function () {
            that.changeCurrenciesListState();
        });


        this.currencyCodeSelector.on('change', function () {
            that.editedCurrencyCode = that.currencyCodeSelector.val();
            that.editedCurrencySpan.text(that.editedCurrencyCode);
            that.countriesSelector.trigger('change');
            that.checkAvailableUpdatersForCurrency();
        });


        this.recalculateButton.on('click', function (e) {
            e.preventDefault();
            that.updateProgressBar(0);
            that.sendRecalculateAjax();
        });

        this.currencyRateInput.on('input', function (e) {
            that.fillReversedExchangeRatesField(e.target);
        });


        this.invertedCurrencyRateInput.on('input', function (e) {
            that.fillReversedExchangeRatesField(e.target);
        });

        $('button.notice-dismiss').on('click', function () {
            var $messageSpan = $(this).parents('.notice').find('.premmerce-multicurrency-updater-notice');
            if($messageSpan.length > 0){
                var updaterId = $messageSpan.data('updater-id');

                $.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'premmerceDismissUpdaterMessage',
                        updaterId: updaterId,
                        premmerceNonce: that.nonces.dismissUpdaterMessage
                    },
                    method: 'post'
                });
            }
        });

        $.validator.addMethod(
            'minStrict',
            function (value, el, param) {
                return value > param;
            },
            that.prepareInvalidMessage(that.messages.rateMoreThanZero)
        );


        $.validator.addMethod(
            'checkUpdater',
            function (value, el, invalidClassName) {
                if (!value) {
                    return true;
                }

                return !$(el).find('option[value=' + value + ']').hasClass(invalidClassName);
            },
            that.prepareInvalidMessage(that.messages.noCurrencyInUpdater));

        this.currencyForms.each(function () {

            $(this).validate({
                lang: premmerceMulticurrencyData.language,
                rules: {
                    'currency-rate': {
                        required: true,
                        minStrict: 0,
                        number: true
                    },

                    'currency-updater': {
                        checkUpdater: 'updater-excluded'
                    }

                },
                highlight: function (element) {
                    $(element).parent('.form-field').addClass('form-invalid');
                },
                unhighlight: function (element) {
                    $(element).parent('.form-field').removeClass('form-invalid');
                },
                submitHandler: function (form) {
                    form.submit();
                    //prevent form from submitting more than once
                    setTimeout(function () {
                        form.reset();
                    });
                }
            });

        });

        this.mainCurrencyRadio.on('change', function () {

            var confirmationMessage = that.isPremium ? that.messages.changeMainCurrencyMessage : that.messages.changeMainCurrencyMessageFree;

                if(confirm(confirmationMessage)){

                    var newMainCurrency = $('input.is-main-checkbox[type=radio]:checked').val();
                    $('#new-currency-code').val(newMainCurrency);
                    that.changeMainCurrency();

                }
        });

        this.ratesUpdateEnabledCheckbox.change(this.changeUpdateFrequencyInputState);

        this.changeCurrenciesListState();

        this.updateServicesStatuses();

        this.changeUpdateFrequencyInputState();

        if (this.nextUpdateSecondsSpan.length > 0 && $.isNumeric(this.nextUpdateSecondsSpan.text())) {

            that.secBefore = parseInt(that.nextUpdateSecondsSpan.text());
            setInterval(this.countdownTimer, 1000);
        }
        if (this.currencyCodeSelector.length > 0) {

            this.checkAvailableUpdatersForCurrency();
        }
        if (this.invertedCurrencyRateInput.length > 0) {

            this.fillReversedExchangeRatesField();
        }
    }

    new PremmerceMulticurrencyAdmin();
});