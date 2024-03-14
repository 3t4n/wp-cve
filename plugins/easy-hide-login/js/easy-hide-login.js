jQuery(document).ready(function ($) {
  $('#wpwrap').on('click open focus', '.open-upsell', function(e) {
    e.preventDefault();
    feature = $(this).data('feature');
    if (!feature) {
      feature = $(this).parent('label').attr('for');
    }
    if (!feature) {
      feature = $(this).parent().data('feature');
    }
    $(this).blur();
    open_upsell(feature);

    return false;
  });

  $('#wpwrap').on('click', '.open-pro-dialog', function (e) {
    e.preventDefault();
    $(this).blur();

    pro_feature = $(this).data('pro-feature');
    if (!pro_feature) {
      pro_feature = $(this).parent('label').attr('for');
    }
    open_upsell(pro_feature);

    return false;
  });

  $('#loginlockdown-pro-dialog').dialog({
    dialogClass: 'wp-dialog loginlockdown-pro-dialog',
    modal: true,
    resizable: false,
    width: 850,
    height: 'auto',
    show: 'fade',
    hide: 'fade',
    close: function (event, ui) {},
    open: function (event, ui) {
      $(this).siblings().find('span.ui-dialog-title').html('WP Login Lockdown PRO is here!');
      easy_hide_login_fix_dialog_close(event, ui);
    },
    autoOpen: false,
    closeOnEscape: true,
  });

  function clean_feature(feature) {
    feature = feature || 'ehl-unknown';
    feature = feature.toLowerCase();
    feature = feature.replace(' ', '-');

    return feature;
  }

  function open_upsell(feature) {
    feature = clean_feature(feature);

    $('#loginlockdown-pro-dialog').dialog('open');

    $('#loginlockdown-pro-table .button-buy').each(function (ind, el) {
      tmp = $(el).data('href-org');
      tmp = tmp.replace('pricing-table', feature);
      tmp = tmp.replace('ehl-ehl-', 'ehl-');
      $(el).attr('href', tmp);
    });
  } // open_upsell

  if (window.localStorage.getItem('easy_hide_login_upsell_shown') != 'true') {
    open_upsell('welcome');

    window.localStorage.setItem('easy_hide_login_upsell_shown', 'true');
    window.localStorage.setItem('easy_hide_login_upsell_shown_timestamp', new Date().getTime());
  }

  if (window.location.hash == '#get-pro') {
    open_upsell('ehl-url-hash');
    window.location.hash = '';
  }

  $('.install-wp301').on('click',function(e){
    e.preventDefault();

    if (!confirm('The free WP 301 Redirects plugin will be installed & activated from the official WordPress repository. Click OK to proceed.')) {
      return false;
    }

    jQuery('body').append('<div style="width:550px;height:450px; position:fixed;top:10%;left:50%;margin-left:-275px; color:#444; background-color: #fbfbfb;border:1px solid #DDD; border-radius:4px;box-shadow: 0px 0px 0px 4000px rgba(0, 0, 0, 0.85);z-index: 9999999;"><iframe src="' + easy_hide_login_vars.wp301_install_url + '" style="width:100%;height:100%;border:none;" /></div>');
    jQuery('#wpwrap').css('pointer-events', 'none');

    e.preventDefault();
    return false;
  });

  function easy_hide_login_fix_dialog_close(event, ui) {
    jQuery('.ui-widget-overlay').bind('click', function () {
      jQuery('#' + event.target.id).dialog('close');
    });
  } // easy_hide_login_fix_dialog_close

  $('#wpwrap').on('change', 'select', function(e) {
    option_class = $('#' + $(this).attr('id') + ' :selected').attr('class');
    if(option_class == 'pro-option'){
        option_text = $('#' + $(this).attr('id') + ' :selected').text();
        value = $('#' + $(this).attr('id') + ' :selected').attr('value');
        $(this).val('builtin');
        $(this).trigger('change');
        open_upsell($(this).attr('id') + '-' + value);
        $('.show_if_' + $(this).attr('id')).hide();
    }
  });

  $('#login_slug').on('change keyup blur', function(){
    $('#login_url').html(easy_hide_login_vars.site_url + '?' + $(this).val());
    $('#login_url').attr('href', easy_hide_login_vars.site_url + '?' + $(this).val());
  });
});
