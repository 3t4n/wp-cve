/*************************************************************************************************
 * Accessibility Font Resizer
 * 
 * Project URL : https://github.com/SatelliteWP/accessibility-font-resizer
 * 
 * By SatelliteWP - 2019
 * https://www.SatelliteWP.som
 * 
 ************************************************************************************************/

/**
 * When document is ready
 */
jQuery(document).ready(function($) {
    
    // Direct call to normal size
    $('#afr_normal, .afr_normal, #afr-normal, .arf-normal, .afr-normal').click(function(e) {
        if (afr_debug) { console.log('AFR - Click normal size'); }
        afr(afr_sizes['n']);
        e.preventDefault();
    });

    // Direct call to large size
    $('#afr_large, .afr_large, #afr-large, .afr-large').click(function(e) {
        if (afr_debug) { console.log('AFR - Click large size'); }
        afr(afr_sizes['l']);
        e.preventDefault();
    });

    // Direct call to very large size
    $('#afr_xlarge, .afr_xlarge, #afr-xlarge, .afr-xlarge').click(function(e) {
        if (afr_debug) { console.log('AFR - Click very large size'); }
        afr(afr_sizes['xl']);
        e.preventDefault();
    });

    // setup
    afr(afr_sizes['n'], true);
    
    // Get previously set size
    var s = afr_gc('afr_size');

    // Set size if previously set
    if (s) afr(s);				
});

/**
 * Change font size (or  setup)
 * 
 * @param {int} size Size in percentage
 * @param {boolean} setup Setup time ?
 */
function afr(size, setup) {
    setup = (typeof setup == 'undefined') ? false : setup;

    if (typeof size == 'undefined') return;

    var el = jQuery('body'), tags;

    for ( var i = 0 ; i < afr_elems.length ; i++ ) {
        tags = el.find( afr_elems[ i ] );
        for ( var j = 0 ; j < tags.length ; j++ ) {

            if (setup) {
                var ofs = jQuery(tags[ j ]).css('font-size');
                jQuery(tags[ j ]).attr('data-swp-font-size', ofs);
                if (afr_debug) console.log('Original font size: ' + ofs);
            }
            else {
                var sfs = jQuery(tags[ j ]).attr('data-swp-font-size');
                var new_size = parseFloat(sfs) * size / 100;
                var old_size = jQuery(tags[ j ]).css('font-size');
                jQuery(tags[ j ]).css('font-size', new_size + "px");

                if (afr_debug) console.log('change from: ' + old_size + '. To: ' + new_size);

                afr_sc('afr_size', size , afr_days);
            }
        }
    }
    return 1;
}

/**
 * Set cookie value
 * 
 * @param {string} cname Cookie name
 * @param {string} cvalue Cookie value
 * @param {int} expdays Expiration in days
 */
function afr_sc(cname, cvalue, expdays) {
  var d = new Date();
  d.setTime(d.getTime() + (expdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

/**
 * Get cookie value
 * 
 * @param {string} cname Cookie name
 * 
 * @returns {string} Value
 */
function afr_gc(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return null;
}