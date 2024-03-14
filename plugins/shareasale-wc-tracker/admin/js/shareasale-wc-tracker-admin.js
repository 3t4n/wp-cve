jQuery(document).ready(function() {

  jQuery(document).on('submit', '#generate-datafeed, #generate-datafeed-results > form', function(e) {
    e.preventDefault();
    /*
    I have to do this datastring because WP 'SSH SFTP Updater Support' plugin's JS code below is broken...
    if(typeof(Storage)!=="undefined" && localStorage.privateKeyFile) {
      jQuery("#private_key").val(localStorage.privateKeyFile);
    }
    is setting the fields' values to string 'undefined'...
    */
    var datastring = jQuery(this).find('input[value!="undefined"]').serialize();
    jQuery('#tracker-options-save').prop('disabled', true);

    jQuery.post( ajaxurl, datastring, function(data) {
      jQuery('#generate-datafeed-results').html(data);
      window.scrollTo(0,200);
      jQuery('#tracker-options-save').prop('disabled', false);
      if(jQuery('#setting-error-datafeed-success').length){        
        jQuery('.shareasale-wc-tracker-datafeeds-table').find('tbody > tr').first().fadeOut().fadeIn();
      }
    });
  });

  jQuery('#reconciliation-setting').click(function(){
    if(!this.checked){
      jQuery('#api-token').prop('disabled', true);
      jQuery('#api-secret').prop('disabled', true);
    }else if(this.checked){
      jQuery('#api-token').prop('disabled', false);
      jQuery('#api-secret').prop('disabled', false);
    }
  });

  jQuery('#analytics-setting').click(function(){
    if(!this.checked){
      jQuery('#analytics-passkey').prop('disabled', true);
    }else if(this.checked){
      jQuery('#analytics-passkey').prop('disabled', false);
    }
  });

  jQuery('#ftp-upload').click(function(){
    if(!this.checked){
      jQuery('#ftp-username').prop('disabled', true);
      jQuery('#ftp-password').prop('disabled', true);
    }else if(this.checked){
      jQuery('#ftp-username').prop('disabled', false);
      jQuery('#ftp-password').prop('disabled', false);
    }
  });

  jQuery(document).on('click', '.shareasale-wc-tracker-datafeeds-error-count', function(e){
    e.preventDefault();
    jQuery(this).siblings('.shareasale-wc-tracker-datafeeds-error-message').toggleClass('shareasale-wc-tracker-datafeeds-error-message-hidden');
  });

  jQuery('#xtype').on('change', function() {
    if(this.value == 'user_defined'){
      jQuery('#xtype-hidden').prop('type','text');
    }else{
      jQuery('#xtype-hidden').prop('type','hidden');
    }
  });

  jQuery('#category-exclusions').on('change', function() {
      var count = jQuery("#category-exclusions :selected").length;
      if(count == 0){
        jQuery('#category-exclusions-hidden').val(1);
      }
  });
});