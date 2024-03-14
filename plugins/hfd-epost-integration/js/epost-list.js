(function ($j) {
  var EpostList = {
    config: {},
    spots: {},
    additonalBlock: $j('#israelpost-additional'),
    isLoading: false,
    selectedCity: null,

    init: function (config) {
		config = config || {};
		this.config = config;
		this.initAdditional();
		this.initAjaxEvent();
		this.initValidation();
		this.renderCities();
		this.observeCityChange();
		this.observeSpotChange();
		this.observeShippingCity();
    },

    initAdditional: function () {
      this.additonalBlock = $j('#israelpost-additional');
      this.shippingInput = this.additonalBlock.siblings('input.shipping_method');

      if( !this.shippingInput.length ){
        return;
      }

      this.initCustomSelect();
      if( !this.shippingInput.is( ':checked' ) ){
        this.additonalBlock.hide();
      }
    },

    observeCityChange: function () {
      var _this = this;
      var select = this.additonalBlock.find('#city-list');
      select.on({
        change: function (e) {
          var city = $j(this).val()
          if (!city) {
            _this.reloadLocations()
            return
          }
          _this.loadSpots(city)
        }
      })

      if (select.find('option:selected').length) {
        select.trigger('change')
      }
    },

    observeSpotChange: function () {
      var _this = this
      this.additonalBlock.find('#spot-list').on({
        change: function (e) {
          var spotId = $j(this).val();
          if (spotId) {
            var spot = _this.spots[spotId];
            var serviceUrl = _this.getConfig('saveSpotInfoUrl');
            var data = {
              action: 'save_pickup',
              spot_info: spot
            };
            _this.showLoader();
            $j.post(serviceUrl, data, function (response) {
              _this.additonalBlock.find('.spot-message').hide();
              _this.hideLoader();
            });
          }
        }
      })
    },

    renderCities: function(){
		var cities = this.config.cities;
		if( cities.length ){
			var select = this.additonalBlock.find( '#city-list' ),
			selected = this.selectedCity || select.data( 'selected' );
			if( select.length ){
				var i, city, option;
				var options = '';
				for( i in cities ){
					city = cities[i];
					//city = city.replace( /\(/g, " )" );
					var nth = 0;
					city = city.replace(/\)/g, function (match, i, original) {
						nth++;
						return (nth === 1) ? "(" : match;
					});
					var nnth = 0;
					city = city.replace(/\(/g, function (match, i, original) {
						nnth++;
						return (nnth === 2) ? ")" : match;
					});
					option = $j( '<option>', { value: city, text: city } );
					if( selected && selected === city ){
						option.attr('selected', 'selected');
					}
					select.append( option );
				}
			}
		}
    },

    loadSpots: function (city) {
      if (this.isLoading) {
        return;
      }

      var serviceUrl = this.getConfig('getSpotsUrl');
      if (!serviceUrl) {
        console.log('Invalid getSpotsUrl');
        return;
      }
      serviceUrl += '&city=' + encodeURIComponent(city);

      var _this = this;
      _this.showLoader();
      $j.get(serviceUrl, function (response) {
        var spots = response,
            locations = {};

        _this.hideLoader();
        if (!Object.keys(spots).length) {
          return _this.reloadLocations(locations)
        }

        _this.spots = spots

        var i, spot;
        for (i in spots) {
          spot = spots[i];
          locations[i] = spot.name + ' - ' + spot.street + ' ' + spot.house + ' - ' + spot.city;
        }

        _this.reloadLocations(locations)
      });
    },

    observeShippingCity: function () {
      var _this = this;
      $j('#billing_city').on({
        change: function (e) {
          if ($j('#ship-to-different-address-checkbox').is(':checked')) {
            return;
          }

          _this.switchCity($j(this).val());
        }
      })

      $j('#shipping_city').on({
        change: function (e) {
          _this.switchCity($j(this).val());
        }
      })
    },

    switchCity: function (city) {
      city = city || '';
      if (!city) {
        return;
      }

      var cityList = this.additonalBlock.find('#city-list');
      var option = cityList.find('option').filter(function () {
        return $j(this).text() === city;
      });

      if (option.length) {
        this.selectedCity = option.val();
        option.prop('selected', true);
        cityList.trigger('change');
      }
    },

    reloadLocations: function (locations) {
      locations = locations || {};
      var spotList = this.additonalBlock.find('#spot-list');

      if (!spotList.length) {
        return;
      }

      spotList.find('option').remove();
      var option,
          cityList = this.additonalBlock.find('#city-list')

      if (!cityList.val()) {
        option = $j('<option>', {
          value: '',
          text: Translator.translate('Select pickup point')
        });
        spotList.append(option);
        return;
      }

      if (!Object.keys(locations).length) {
        option = $j('<option>', {
          value: '',
          text: Translator.translate('There is no pickup point')
        });
        spotList.append(option);
        return;
      }

      var location, i,
      selectedLocation = spotList.data('selected');

      option = $j('<option>', {value: '', text: Translator.translate('Select pickup point')});
      spotList.append(option);
      for (i in locations) {
        location = locations[i];
        option = $j('<option>', {value: i, text: location});
        if (selectedLocation && selectedLocation == i) {
          option.attr('selected', 'selected');
        }

        spotList.append(option);
      }
    },

    initAjaxEvent: function () {
      var _this = this;
      $j(document).ajaxComplete(function (event, xhr, settings) {
        var action = _this.getUrlParameter(settings.url, 'wc-ajax');

        if (action &&
        (action == 'update_shipping_method'
        || action == 'update_order_review'
        || action == 'get_refreshed_fragments'
        )
        ) {
          _this.initAdditional();
          _this.renderCities()
          _this.observeCityChange()
          _this.observeSpotChange()
        }
      });
    },

    getConfig: function (key) {
      if (typeof this.config[key] != 'undefined') {
        return this.config[key];
      }

      return null;
    },

    getUrlParameter: function (url, name) {
      var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);

      return results ? results[1] : null;
    },

    initValidation: function () {
      var form = $j('form.checkout');

      if (!form.length) {
        return;
      }

      var _this = this;
      form.on({
        checkout_place_order: function () {
          var isValid = _this.validate();
          if (!isValid && !$j('html, body').is(':animated')) {
            $j('html, body').animate({
              scrollTop: (_this.additonalBlock.offset().top - 100)
            }, 700);
          }

          return isValid;
        }
      });
    },

    validate: function () {
      var shippingInput = this.shippingInput;
      var spotList = this.additonalBlock.find('#spot-list');
      var isValid = true;

      if (shippingInput.length && shippingInput.is(':checked')) {
        isValid = (spotList.length && spotList.val() != '') ? true : false;
      }

      var message = this.additonalBlock.find('.spot-message');
      if (!isValid) {
        message.show();
      } else {
        message.hide();
      }

      return isValid;
    },

    showLoader: function () {
      this.isLoading = true;
      var block = this.additonalBlock.closest('.cart_totals');

      if (!block.length) {
        block = this.additonalBlock.closest('.shop_table');
      }

      if (!block.length) {
        return;
      }

      block.addClass('processing').block({
        message: null,
        overlayCSS: {
          background: '#fff',
          opacity: 0.6
        }
      });
    },

    hideLoader: function () {
      this.isLoading = false;
      var block = this.additonalBlock.closest('.cart_totals');

      if (!block.length) {
        block = this.additonalBlock.closest('.shop_table');
      }

      if (!block.length) {
        return;
      }

      block.removeClass('processing').unblock();
    },

    initCustomSelect: function () {
      if ($j.fn.select2) {
        this.additonalBlock.find('select').select2();
      }
    }
  }

  window.EpostList = EpostList;
})(jQuery);