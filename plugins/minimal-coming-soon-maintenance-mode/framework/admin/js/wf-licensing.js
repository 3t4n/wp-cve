function wf_csmm_licensing_verify_licence_ajax(prefix, license_key, button) {

  data = window['wf_licensing_' + prefix];
  if (!data) {
    alert('Licensing data is missing. Please reload the page and try again.');
    return;
  }

  jQuery(button).addClass('loading');

  jQuery
    .post(
      ajaxurl,
      {
        action: 'wf_licensing_' + prefix + '_validate',
        license_key: license_key,
        _ajax_nonce: data.nonce,
        _rand: Math.floor(Math.random() * 9999) + 1
      },
      function(response) {
        if (data.debug) {
          console.log('Validate license, first try: ', response);
        }
        if (response.success) {
          location.reload();
        } else {
          wf_csmm_licensing_verify_licence_ajax_alternative(data.prefix, license_key, button);
        }
      }
    )
    .fail(function() {
      alert('Undocumented error. Please reload the page and try again.');
    })
    .always(function() {
      jQuery(button).removeClass('loading');
    });
} // wf_csmm_licensing_verify_licence_ajax

function wf_csmm_licensing_deactivate_licence_ajax(prefix, license_key, button) {
  data = window['wf_licensing_' + prefix];
  if (!data) {
    alert('Licensing data is missing. Please reload the page and try again.');
    return;
  }

  jQuery(button).addClass('loading');

  jQuery
    .post(
      ajaxurl,
      {
        action: 'wf_licensing_' + prefix + '_deactivate',
        license_key: license_key,
        _ajax_nonce: data.nonce,
        _rand: Math.floor(Math.random() * 9999) + 1
      },
      function(response) {
        if (data.debug) {
          console.log('Deactivate license, first try: ', response);
        }
        if (response.success) {
          location.reload();
        } else {
            wf_csmm_licensing_deactivate_licence_ajax_alternative(data.prefix, license_key, button);
        }
      }
    )
    .fail(function() {
      alert('Undocumented error. Please reload the page and try again.');
    })
    .always(function() {
      jQuery(button).removeClass('loading');
    });
} // wf_csmm_licensing_deactivate_licence_ajax

function wf_csmm_licensing_deactivate_licence_ajax_alternative(prefix, licence_key) {
  console.log('deactivate alternative');
}

function wf_csmm_licensing_verify_licence_ajax_alternative(prefix, licence_key) {
  data = window['wf_licensing_' + prefix];
  if (!data) {
    alert('Licensing data is missing. Please reload the page and try again.');
    return;
  }
  data.request_data.license_key = licence_key;

  jQuery
    .get(data.licensing_endpoint, data.request_data, function(response) {
      if (data.debug) {
        console.log('Validate license, second try: ', response);
      }
      if (response.data) {
        jQuery
          .post(
            ajaxurl,
            {
              action: 'wf_licensing_' + prefix + '_save',
              license_key: licence_key,
              success: response.success,
              data: response.data,
              _ajax_nonce: data.nonce
            },
            function(response) {
              if (response.success) {
                location.reload();
              } else {
                alert('Undocumented error. Please reload the page and try again.');
              }
            }
          )
          .fail(function() {
            alert('Undocumented error. Please reload the page and try again');
          })
          .always(function() {
            jQuery(button).removeClass('loading');
          });

        /*
        if (!response.success && response.data) {
          alert(response.data);
        }
        */
      }
    })
    .fail(function() {
      alert('Undocumented error. Please reload the page and try again.');
    })
    .always(function() {
      jQuery(button).removeClass('loading');
    });
} // wf_csmm_licensing_verify_licence_ajax_alternative
