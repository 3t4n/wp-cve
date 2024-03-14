(function() {
  'use strict';

  var globals = typeof global === 'undefined' ? self : global;
  if (typeof globals.require === 'function') return;

  var modules = {};
  var cache = {};
  var aliases = {};
  var has = {}.hasOwnProperty;

  var expRe = /^\.\.?(\/|$)/;
  var expand = function(root, name) {
    var results = [], part;
    var parts = (expRe.test(name) ? root + '/' + name : name).split('/');
    for (var i = 0, length = parts.length; i < length; i++) {
      part = parts[i];
      if (part === '..') {
        results.pop();
      } else if (part !== '.' && part !== '') {
        results.push(part);
      }
    }
    return results.join('/');
  };

  var dirname = function(path) {
    return path.split('/').slice(0, -1).join('/');
  };

  var localRequire = function(path) {
    return function expanded(name) {
      var absolute = expand(dirname(path), name);
      return globals.require(absolute, path);
    };
  };

  var initModule = function(name, definition) {
    var hot = hmr && hmr.createHot(name);
    var module = {id: name, exports: {}, hot: hot};
    cache[name] = module;
    definition(module.exports, localRequire(name), module);
    return module.exports;
  };

  var expandAlias = function(name) {
    var val = aliases[name];
    return (val && name !== val) ? expandAlias(val) : name;
  };

  var _resolve = function(name, dep) {
    return expandAlias(expand(dirname(name), dep));
  };

  var require = function(name, loaderPath) {
    if (loaderPath == null) loaderPath = '/';
    var path = expandAlias(name);

    if (has.call(cache, path)) return cache[path].exports;
    if (has.call(modules, path)) return initModule(path, modules[path]);

    throw new Error("Cannot find module '" + name + "' from '" + loaderPath + "'");
  };

  require.alias = function(from, to) {
    aliases[to] = from;
  };

  var extRe = /\.[^.\/]+$/;
  var indexRe = /\/index(\.[^\/]+)?$/;
  var addExtensions = function(bundle) {
    if (extRe.test(bundle)) {
      var alias = bundle.replace(extRe, '');
      if (!has.call(aliases, alias) || aliases[alias].replace(extRe, '') === alias + '/index') {
        aliases[alias] = bundle;
      }
    }

    if (indexRe.test(bundle)) {
      var iAlias = bundle.replace(indexRe, '');
      if (!has.call(aliases, iAlias)) {
        aliases[iAlias] = bundle;
      }
    }
  };

  require.register = require.define = function(bundle, fn) {
    if (bundle && typeof bundle === 'object') {
      for (var key in bundle) {
        if (has.call(bundle, key)) {
          require.register(key, bundle[key]);
        }
      }
    } else {
      modules[bundle] = fn;
      delete cache[bundle];
      addExtensions(bundle);
    }
  };

  require.list = function() {
    var list = [];
    for (var item in modules) {
      if (has.call(modules, item)) {
        list.push(item);
      }
    }
    return list;
  };

  var hmr = globals._hmr && new globals._hmr(_resolve, require, modules, cache);
  require._cache = cache;
  require.hmr = hmr && hmr.wrap;
  require.brunch = true;
  globals.require = require;
})();

(function() {
var global = typeof window === 'undefined' ? this : window;
var __makeRelativeRequire = function(require, mappings, pref) {
  var none = {};
  var tryReq = function(name, pref) {
    var val;
    try {
      val = require(pref + '/node_modules/' + name);
      return val;
    } catch (e) {
      if (e.toString().indexOf('Cannot find module') === -1) {
        throw e;
      }

      if (pref.indexOf('node_modules') !== -1) {
        var s = pref.split('/');
        var i = s.lastIndexOf('node_modules');
        var newPref = s.slice(0, i).join('/');
        return tryReq(name, newPref);
      }
    }
    return none;
  };
  return function(name) {
    if (name in mappings) name = mappings[name];
    if (!name) return;
    if (name[0] !== '.' && pref) {
      var val = tryReq(name, pref);
      if (val !== none) return val;
    }
    return require(name);
  }
};
require.register("js/admin-refresh-box-status.js", function(exports, require, module) {
'use strict'
var $

exports.init = function (jQuery) {
  $ = jQuery

  $(document).on('click', '.bpost-refresh-box-status-action', function () {
    var $refresh_button = $('.bpost-refresh-box-status-action')
    $refresh_button.attr('disabled', true)

    var $status_span = $('#bpost-order-meta-status')
    $status_span.html('<i>Refreshing status...</i>')

    var xhr = $.ajax({
      url: window.bpost_order_data.url,
      method: 'GET',
      dataType: 'json'
    })

    xhr.done(function successHandler (result) {
      $status_span.html(result[window.bpost_order_data.order_id])
    })

    xhr.fail(function errorHandler (jqXHR, textStatus, errorThrown) {
      console.error(jqXHR, textStatus, errorThrown)
      $status_span.html('<i>Error during the refreshing of the status</i>')
    })

    xhr.always(function () {
      $refresh_button.attr('disabled', false)
    })
  })
}

});

;require.register("js/form-filler.js", function(exports, require, module) {
function FormFiller($checkout_form) {
    if (undefined === $checkout_form) {
        throw Error('A checkout item must be specified')
    }
    this.$checkout_form = $checkout_form
    this.fragmentStorageHiddenFields = document.createDocumentFragment()
}

FormFiller.prototype.createOrFillFields = function (data) {
    for (const formIndex in data) {
        if (!data.hasOwnProperty(formIndex)) {
            continue
        }
        try {
            this.fillFieldIfAvailable(formIndex, data[formIndex])
        } catch (e) {
            console.warn(e)
        }
    }

    this.$checkout_form.find('#ship-to-different-address input').trigger("change")
    this.publishHiddenFields()
}

FormFiller.prototype.createHiddenField = function (name, value) {
    const input = document.createElement('input')
    input.setAttribute('type', 'text')
    input.setAttribute('name', name)
    input.setAttribute('value', value)
    this.fragmentStorageHiddenFields.appendChild(input)
    console.log('Hidden field "' + name + '" = "' + value + '" created')
    return input
}

FormFiller.prototype.retrieveField = function (name) {
    return this.$checkout_form.find('[name=' + name + ']')
}

FormFiller.prototype.isFieldPresent = function (name) {
    return this.retrieveField(name).length === 1
}

FormFiller.prototype.fillFieldIfAvailable = function (name, value) {
    var $fieldFound = this.retrieveField(name)
    if ($fieldFound.length > 1) {
        throw new Error('Input "' + name + '" duplicated')
    }
    if ($fieldFound.length === 0) {
        this.createHiddenField(name, value)
    }

    if ($fieldFound.is("[type='checkbox']")) {
        $fieldFound.prop('checked', true)
        console.log('Field "'+name+'" checked')
    /*} else if ($fieldFound.is('select')) {
        $fieldFound.remove()
        this.createHiddenField(name, value)
        console.log('Field "'+name+'" = "'+value+'" updated')
     */
    } else {
        $fieldFound.val(value).change()
        console.log('Field "'+name+'" = "'+value+'" updated')
    }
}

FormFiller.prototype.publishHiddenFields = function () {
    this.$checkout_form.get(0).appendChild(this.fragmentStorageHiddenFields)
}

// @todo remove it
FormFiller.prototype.updateShipping = function (data) {
    this.$checkout_form.get(0).appendChild(this.fragmentStorageHiddenFields)
}

// @todo remove it
FormFiller.prototype.fillShippingOptions = function (data) {
    const $shippingInfo = $('#bpost_shipping_info')
    if ($shippingInfo.length === 0) {
        return
    }
    const infoText =
        $shippingInfo.val(
            "<b>" + "Shipping method : " + "</b>" + data.bpost_delivery_method
            // @todo chercher à retirer le "as from" et fixer le nouveau prix
        )
}

module.exports = FormFiller

});

;require.register("js/gmap-loader.js", function(exports, require, module) {
function GMapLoader (api_key, address, map_element, signed_in) {
  this.api_key = api_key
  this.address = address
  this.map_element = map_element
  this.signed_in = signed_in
}

GMapLoader.prototype.process_reverse = function () {
  geocoder = new google.maps.Geocoder()
  var that = this
  geocoder.geocode({ 'address': this.address }, this.build_map.bind(that))
}

GMapLoader.prototype.build_options = function (location) {
  return {
    zoom: 17,
    center: location,
    disableDefaultUI: true,
    navigationControl: false
  }
}

GMapLoader.prototype.build_map = function (results, status) {
  if (status !== google.maps.GeocoderStatus.OK) {
    console.error('Non geolocalised address: ' + this.address)
    return
  }

  var location = results[0].geometry.location

  var myOptions = this.build_options(location)

  var map = new google.maps.Map(this.map_element, myOptions)

  new google.maps.Marker({
    position: location,
    map: map
  })
}

GMapLoader.prototype.loadGoogleMaps = function (callback_name) {
  if (!this.api_key) {
    console.warn('No api key provided, google maps wont work')
    return
  }
  var googleMaps = document.createElement('script')
  googleMaps.type = 'text/javascript'
  googleMaps.src = 'https://maps.googleapis.com/maps/api/js?key=' + this.api_key
      + '&signed_in=' + this.signed_in
      + '&callback=' + callback_name
  document.body.appendChild(googleMaps)
}

module.exports = GMapLoader

// document.getElementById('map')

});

;require.register("js/open-shm.js", function(exports, require, module) {
'use strict'
var $;

function preparePopup() {
    console.log('preparePopup')

    const $formContent = $.grep($('form.checkout').find('input, select'), filter_form_input_select)
    $.ajax({
        url: window.bpost_after_checkout.url, // WC()->api_request_url( 'shm-loader' )
        method: 'POST',
        data: $formContent,
        success: function (response) {
            console.log(response)
            openPopup(response)
        },
        dataType: 'json'
    })
}

function filter_form_input_select(form_item) {
    if (!form_item.name) {
        return false
    }

    if (form_item.name.indexOf('_name') !== -1) {
        return true
    }

    if (form_item.name.indexOf('_company') !== -1) {
        return true
    }

    if (form_item.name.indexOf('_email') !== -1) {
        return true
    }

    if (form_item.name.indexOf('_phone') !== -1) {
        return true
    }

    if (form_item.name.indexOf('payment_method') !== -1 && jQuery(form_item).is(':checked')) {
        return true
    }

    return form_item.name.indexOf('different_address') !== -1 && jQuery(form_item).is(':checked')
}

function openPopup(apiShmLoaderResponse) {
    const bpost_data = apiShmLoaderResponse.bpost_data
    const shipping = apiShmLoaderResponse.shipping_address

    const shm_params = {
        integrationType: 'POPUP',
        forceIntegrationType: 'true', // To force the popup display on mobile devices
        parameters: {
            accountId: bpost_data.account_id,
            orderReference: bpost_data.order_reference,
            checksum: bpost_data.hash,
            additionalCustomerReference: bpost_data.additional_customer_ref,
            lang: bpost_data.language,
            orderTotalPrice: bpost_data.sub_total,
            deliveryMethodOverrides: bpost_data.delivery_method_overrides,
            orderWeight: bpost_data.sub_weight,
            extra: bpost_data.extra,
            confirmUrl: bpost_data.callback_url + 'confirm', // check RESULT_SHM_CALLBACK.* constants into WC_BPost_Shipping_Shm_Callback_Controller
            errorUrl: bpost_data.callback_url + 'error',
            cancelUrl: bpost_data.callback_url + 'cancel',

            customerFirstName: shipping.first_name,
            customerLastName: shipping.last_name,
            customerStreet: shipping.address,
            customerStreetNumber: shipping.street_number,
            customerBox: shipping.street_box,
            customerCity: shipping.city,
            customerPostalCode: shipping.post_code,
            customerCountry: shipping.country_code,
            customerEmail: shipping.email,
            customerPhoneNumber: shipping.phone_number,
            customerCompany: shipping.company,

            action: 'START',
        },
        closeCallback: on_shm_close,
    };

    SHM.open(shm_params);
}

function trigger_update_checkout() {
    $(document.body).trigger('update_checkout')
}

/**
 * Called with SHM.close(shm_data)
 * @param data
 */
function on_shm_close(data) {
    const init_overlay = tb_click // it's an alias to understand what tb_click does

    init_overlay()

    if (!data.status) {
        console.error('error status from shm callback')
        trigger_update_checkout()
        remove_overlay()
        return
    }

    delete data.status

    const $checkoutForm = $('form.checkout')
    const FormFiller = require('js/form-filler')
    const formFiller = new FormFiller($checkoutForm)
    formFiller.createOrFillFields(data)

    if (!formFiller.isFieldPresent('bpost_shm_already_called')) {
        formFiller.createHiddenField('bpost_shm_already_called', 1)
        formFiller.publishHiddenFields()
    }
    //$checkoutForm.submit()
    trigger_update_checkout()

    remove_overlay()
}

exports.init = function (jQuery) {
    $ = jQuery

    console.log('open-shm.init()')
    $(document).on('click', '.js-bpost-shipping-options-modal', function (e) {
        e.preventDefault();
        console.log('click click')
        preparePopup()
    })

    console.log('open-shm.init() loaded')
}

});

;require.register("js/shm-close.js", function(exports, require, module) {
'use strict'

// Close the SHM and send data to WP ; {closeCallback: on_shm_close}
exports.close_shm = function (SHM, shm_data) {
  if (!SHM || !shm_data) {
    return
  }

  SHM.close(shm_data)
}

});

;require.register("___globals___", function(exports, require, module) {
  
});})();require('___globals___');

