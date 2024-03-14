var _l10iq = _l10iq || [];

function L10iHubSpot(_ioq) {
  var ioq = _ioq;
  var io = _ioq.io;

  this.init = function init() {
    io('log', "l10iHubSpotTracker.init()");

    io('addCallback', 'ctaClickAlter', this.ctaClickAlterCallback, this);
    io('addCallback', 'formSubmitAlter', this.formSubmitAlterCallback, this);
    io('addCallback', 'commentSubmitAlter', this.commentSubmitAlterCallback, this);
    
    // add vtk to hidden field on HubSpot forms
      // don't do this here as form has likely not loaded yet
    //jQuery(".hs-form input[name='l10i_vtk']").val(io('get', 'vtk'));
    
    var rf = io('getCookie', 'hsrecentfields');

    if (rf) {
      rf = decodeURIComponent(rf);
      // sometimes hsrecentfields not proper json format, so use try catch
      try {
        rf = jQuery.parseJSON(rf);
      }
      catch(e) {
        rf = false;
      }
    }

    if (rf) {
      try {
        rf['hs_context'] = jQuery.parseJSON(rf['hs_context']);
      }
      catch(e) {
        // do nothing
      }

      var count = 0;
        var data = {};
      for (var i in rf) {
        if (rf.hasOwnProperty(i) && rf[i] != 'hs_context') {
            data[i] = rf[i];

           count ++;
        }
      }
      if (count > 0) {
          io('set', 'visitor.hubspot', data);
      }
      
      io('set', 'ext.hubspot.hs_context', rf['hs_context']);
      io('saveVar', 'ext', 'hubspot');
    }
  };

  this.ctaClickAlterCallback = function (click, data, $obj, event) {
    click['hubspotutk'] = io('getCookie', 'hubspotutk');

    var href = $obj.attr('cta_dest_link') || '';  // used for HubSpot CTAs
    if (href) {
        click['href'] = href;
    }
  };
  
  this.formSubmitAlterCallback = function (submit, data, $obj, event) {
      submit['hubspotutk'] = io('getCookie', 'hubspotutk');
	  // check if a HubSpot form
	  if (!$obj.hasClass('hs-form')) {
		return;
	  }
	  // add vtk to hidden field on HubSpot forms
	  jQuery(".hs-form input[name='l10i_vtk']").val(io('get', 'vtk'));

      submit['type'] = 'hubspot';
	  var id = $obj.attr('id');
      submit['fid'] = id.replace('hsForm_', '');
  };
  
  this.commentSubmitAlterCallback = function (submit, data, $obj, event) {
      submit['hubspotutk'] = io('getCookie', 'hubspotutk');
  };

  _l10iq.push(['addCallback', 'domReady', this.init, this]);
}

_l10iq.push(['providePlugin', 'hubspot', L10iHubSpot, {}]);


