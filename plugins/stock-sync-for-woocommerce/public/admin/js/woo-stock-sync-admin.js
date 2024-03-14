jQuery(document).ready(function($){
  $( document.body ).trigger( 'wc-enhanced-select-init' );

  $( '.wss-tip' ).tipTip( {
    'attribute': 'data-tip',
    'fadeIn': 50,
    'fadeOut': 50,
    'delay': 200
  } );

  if ($('#wss-api-check-app').length > 0) {
    var wssApiCheck = new Vue( {
      el: '#wss-api-check-app',
      data: {
        title: 'test',
        checks: [
          {
            id: 'format',
            title: 'Credentials are formatted correctly',
          },
          {
            id: 'url',
            title: 'URL is accessible',
          },
          {
            id: 'rest_api',
            title: 'REST API is accessible',
          },
          {
            id: 'credentials',
            title: 'API key and secret are correct',
          },
          {
            id: 'stock_sync',
            title: 'Stock Sync is installed',
          },
          {
            id: 'privileges',
            title: 'API user has correct privileges',
          },
        ],
        statuses: {
          'format': 'waiting',
          'url': 'waiting',
          'rest_api': 'waiting',
          'credentials': 'waiting',
          'stock_sync': 'waiting',
          'privileges': 'waiting',
        },
        errors: {
          'format': [],
          'url': [],
          'rest_api': [],
          'credentials': [],
          'stock_sync': [],
          'privileges': [],
        },
        url: null,
        apiKey: null,
        apiSecret: null,
        runIndex: 0,
        ajax: null,
        allGood: false,
      },
      computed: {
      },
      methods: {
        checkApi: function(url, apiKey, apiSecret) {
          this.url = url;
          this.apiKey = apiKey;
          this.apiSecret = apiSecret;
          this.runIndex = 0;
          this.allGood = false;
  
          this.clearStatus();
          this.runChecks();
        },
        runChecks: function() {
          var self = this;
  
          var check = self.checks[this.runIndex];
  
          // Abort current AJAX request if it's running
          if (this.ajax) {
            this.ajax.abort();
          }
  
          this.ajax = $.ajax( {
            type: 'post',
            url: ajaxurl,
            data: {
              'action': 'wss_api_check',
              'type': check.id,
              'url': this.url,
              'key': this.apiKey,
              'secret': this.apiSecret,
              'security': woo_stock_sync.nonces.api_check
            },
            dataType: 'json',
            beforeSend: function() {
              self.statuses[check.id] = 'pending';
            },
            success: function(response) {
              if (response.ok) {
                var status = 'ok'; 
                self.errors[check.id] = [];
              } else {
                var status = 'error';
                self.errors[check.id] = response.errors;
              }
  
              self.statuses[check.id] = status;
  
              // If all good move on to the next test
              if (status == 'ok') {
                self.runIndex += 1;
                
                if (self.runIndex < self.checks.length) {
                  self.runChecks();
                } else {
                  self.allGood = true;
                }
              }
            },
            error: function( jqXHR, textStatus, errorThrown ) {
              console.log( jqXHR, textStatus, errorThrown );
              alert( jqXHR.status + " " + jqXHR.responseText + " " + textStatus + " " + errorThrown );
            },
            complete: function() {
            }
          } );
        },
        clearStatus: function() {
          for (var key in this.statuses) {
            this.statuses[key] = 'waiting';
            this.errors[key] = [];
          }
        },
      },
      created: function() {
      },
      mounted: function() {
      }
    } );
  }

  var wooStockSyncSettings = {
    init: function() {
      this.triggerCredentialCheck();
      this.triggerSettingsUpdate();
      this.addSite();
    },

    /**
     * Add site
     */
    addSite: function() {
      $( 'table.wss-credentials-table tbody tr.hidden' ).hide();

      if ( $( 'table.wss-credentials-table tr.hidden' ).length == 0 ) {
        $( 'a.wss-add-site' ).addClass( 'disabled' );
      }

      $( 'a.wss-add-site' ).click( function( e ) {
        e.preventDefault();

        var nextRow = $( 'table.wss-credentials-table tr.hidden' );

        if ( nextRow.length > 0 ) {
          $( 'table.wss-credentials-table tr.hidden:eq(0)' ).show().removeClass( 'hidden' );

          if ( $( 'table.wss-credentials-table tr.hidden' ).length == 0 ) {
            $( 'a.wss-add-site' ).addClass( 'disabled' );
          }
        }
      } );
    },

    /**
     * Save form when changing certain settings
     */
    triggerSettingsUpdate: function() {
      $( 'select#woo_stock_sync_role' ).change( function( e ) {
        var form = $( this ).closest( 'form' );

        $( 'button[name="save"]', form ).click();
      } );
    },

    triggerCredentialCheck: function() {
      var self = this;

      $( document ).on( 'click', 'a.woo-stock-sync-check-credentials', function(e) {
        e.preventDefault();
        self.checkCredentials( $( this ).closest( 'tr' ) );
      } );
    },

    checkCredentials: function( row ) {
      var url = $('input.woo-stock-sync-url', row).val();
      var key = $('input.woo-stock-sync-api-key', row).val();
      var secret = $('input.woo-stock-sync-api-secret', row).val();

      // Open modal
      var dialog = $('#wss-api-check-dialog' ).dialog({
        width: 500,
        dialogClass: 'wss-api-check-dialog-container',
        title: 'API Check',
        closeOnEscape: true,
        resizable: false,
        draggable: false,
        modal: true,
        open: function(event, ui) { 
          $('.ui-widget-overlay').bind('click', function() { 
            $("#wss-api-check-dialog").dialog('close'); 
          }); 
        }
      });
      wssApiCheck.checkApi(url, key, secret);

      return;
    },
  };

  wooStockSyncSettings.init();
});
