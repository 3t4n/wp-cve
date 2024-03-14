var IsraelPost = {
    pickerButton: null,
    spotInfo: null,
    spotId: null,
    spots: null,
    dialog: null,
    validateMessage: null,

    init: function () {
        var _this = this;
        var popupWith = $j(window).width();
        if (popupWith <= 750) {
            popupWith = '100%';
        } else {
            popupWith = '750px';
        }

        this.dialog = $j('#israelpost-modal').dialog({
            title: Translator.translate('Select a collection point'),
            autoOpen: false,
            width: popupWith,
            draggable: false,
            close: function () {
                IsraelPostMap.closeInfobox();
                IsraelPostCommon.overlay.hide();
            }
        });

        $j(window).on({
            resize: function () {
                popupWith = $j(window).width();
                if (popupWith <= 750) {
                    popupWith = '100%';
                } else {
                    popupWith = '750px';
                }
                _this.dialog.dialog('option', 'width', popupWith);
            }
        })

        this.validateMessage = $j('<div class="spot-message">'+ Translator.translate('Please choose pickup branch') +'</div>');

        if ($j('#israelpost-spot-id').length) {
            this.spotId = $j('#israelpost-spot-id');
        } else {
            this.spotId = $j('<input type="hidden" id="israelpost-spot-id" value="" />');
            this.renderSpotId();
        }

        IsraelPostCommon.overlay.on({
            click: function () {
                _this.closeModal();
            }
        });

        this.initPickerButton();
        IsraelPostMap.init();
    },

    initPickerButton: function () {
        var _this = this;
        this.pickerButton = $j('#israelpost-additional .spot-picker');
        this.spotInfo = $j('#israelpost-additional .spot-detail');
        this.pickerButton.on({
            click: function (e) {
                _this.showPickerPopup(e);
            }
        });
    },

    showPickerPopup: function (event) {
        event.preventDefault();
        var _this = this;
        if (this.spots == null) {
            IsraelPostCommon.showLoader();
            var serviceUrl = IsraelPostCommon.getConfig('getSpotsUrl');
            if (serviceUrl) {
                $j.get(serviceUrl, function (response) {
                    _this.spots = response;
                    IsraelPostMap.pinMarkers(_this.spots);
                    IsraelPostCommon.hideLoader();
                    _this.openModal();
                });
            } else {
                console.log('Invalid getSpotsUrl');
            }
        } else {
            _this.openModal();
        }
        return false;
    },

    saveSpotInfo: function (spot) {
        var _this = this;
        var serviceUrl = IsraelPostCommon.getConfig('saveSpotInfoUrl');
        var data = {
            action: 'save_pickup',
            spot_info: spot
        };
        IsraelPostCommon.showLoader();
        $j.post(serviceUrl, data, function (response) {
            _this.pickerButton.text(Translator.translate('Change pickup branch'));
            IsraelPostCommon.hideLoader();
        });
    },

    renderSpotInfo: function (html) {
        this.spotInfo.html(html);
    },

    renderSpotId: function (spotId) {
        spotId = spotId || null;
        this.spotId.val(spotId);
        this.spotId.prependTo(this.spotInfo);
    },

    openModal: function () {
        this.dialog.dialog('open');
        IsraelPostCommon.overlay.show();
        IsraelPostMap.resize();
    },
    
    closeModal: function () {
        this.dialog.dialog('close');
        IsraelPostCommon.overlay.hide();
    },

    validate: function () {
        var shippingInput = IsraelPostCommon.shippingInput;
        var isValid = true;
        if (shippingInput.length && shippingInput.is(':checked')) {
            isValid = this.spotId.val() ? true : false;
        }

        if (!isValid && !this.validateMessage.is(':visible')) {
            this.validateMessage.appendTo(this.spotInfo);
        } else if (isValid) {
            this.validateMessage.remove();
        }

        return isValid;
    },
	
	destroy: function(){	
		this.pickerButton = null;
		this.spotInfo = null;
		this.spotId = null;
		this.spots = null;
		this.dialog = null;
		this.validateMessage = null;
	}
};