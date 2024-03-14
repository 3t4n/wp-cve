<?php global $title; ?>

<div class="wrap">
  <h1 class="wp-heading-inline"><?php echo $title; ?></h1>
  <hr class="wp-header-end">

  <?php include 'tabs.html.php'; ?>

  <div id="wss-update-app" class="wss-app">
    <div>
      <div class="header">
        <div v-if="status === 'process'" class="processing">
          <h2><?php _e( 'Update in Progress', 'woo-stock-sync' ); ?></h2>
          <p><?php _e( 'Updating sync status may take some time. Please do not close your browser or refresh this page until the process has been completed.', 'woo-stock-sync' ); ?></p>
        </div>

        <div v-if="status === 'completed'" class="completed">
          <h2><?php _e( 'Update Complete!', 'woo-stock-sync' ); ?></h2>
          <p><?php _e( 'Update has been completed successfully.', 'woo-stock-sync' ); ?></p>
          <p><a href="<?php echo $urls['report']; ?>" class="button button-primary"><?php _e( 'View report &raquo;', 'woo-stock-sync' ); ?></a></p>
        </div>

        <div v-if="status === 'pending'" class="pending">
          <h2><?php _e( 'Update Report', 'woo-stock-sync' ); ?></h2>
          <p><?php _e( 'Update report by fetching SKUs and stock quantities from Secondary Inventories.', 'woo-stock-sync' ); ?></p>
          <p><button v-on:click.prevent="startProcess" v-if="status === 'pending'" class="button button-primary"><?php _e( 'Start update', 'woo-stock-sync' ); ?></button></p>
        </div>
      </div>

      <div class="sites">
        <div class="site" v-for="site in sites">
          <div class="site-header">
            <div class="title">{{ site.formatted_url }}</div>
            <div class="status" v-bind:class="site.status"><span class="icon"></span></div>
          </div>

          <div class="content">
            <table class="progress">
              <tr>
                <th><?php _e( 'Time elapsed', 'woo-stock-sync' ); ?></th>
                <td><span v-if="site.status != 'pending'">{{ site.timeElapsed }}</td>
              </tr>

              <tr>
                <th><?php _e( 'Processed', 'woo-stock-sync' ); ?></th>
                <td>{{ site.processedRecords }} / {{ totalRecords }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
var app = new Vue( {
  el: '#wss-update-app',
  data: {
    status: 'pending',
    sites: <?php echo json_encode( array_values( woo_stock_sync_sites() ) ); ?>,
    totalRecords: 0, // Total number of products
  },
  methods: {
    /**
     * Start syncing process
     */
    startProcess: function() {
      if ( this.status !== 'pending' ) {
        return;
      }

      Vue.set( this, 'status', 'process' );
      
      this.processSite( 0, 1 );
      this.runTimers();
    },
    /**
     * Complete whole update
     */
    completeUpdate: function() {
      jQuery.ajax( {
        type: 'post',
        url: woo_stock_sync.ajax_urls.update,
        data: {
          complete: '1',
          security: woo_stock_sync.nonces.update
        },
        dataType: 'json',
        beforeSend: function() {
        },
        success: function( response ) {
        },
        error: function( jqXHR, textStatus, errorThrown ) {
          console.log( jqXHR, textStatus, errorThrown );
          alert( jqXHR.status + " " + jqXHR.responseText + " " + textStatus + " " + errorThrown );
        },
        complete: function() {
        }
      } );
    },
    /**
     * Process single site
     */
    processSite: function( siteIndex, page ) {
      var self = this;

      var site = this.sites[siteIndex];
      site.status = 'process';
      site.processEnded = false;

      if ( ! site.processStarted ) {
        site.processStarted = new Date();
      }

      var limit = <?php echo wss_get_batch_size( 'update' ); ?>;
      var data = {
        page: page,
        site_key: site.key,
        limit: limit,
        security: woo_stock_sync.nonces.update
      };

      jQuery.ajax( {
        type: 'post',
        url: woo_stock_sync.ajax_urls.update,
        data: data,
        dataType: 'json',
        beforeSend: function() {
        },
        success: function( response ) {
          if ( response.status === 'processed' ) {
            if ( ! self.totalRecords ) {
              self.totalRecords = response.total;
            }

            site.processedRecords += response.count;

            if ( response.last_page ) {
              site.status = 'completed';
              site.processEnded = new Date();

              if ( ( siteIndex + 1 ) < self.sites.length ) {
                self.processSite( ( siteIndex + 1 ), 1 );
              } else {
                self.status = 'completed';
                self.completeUpdate();
              }
            } else {
              self.processSite( siteIndex, page + 1 );
            }
          } else if ( response.status === 'error' ) {
            alert( response.errors.join( "\n" ) + "\nAborting..." );
            self.status = 'pending';
            site.status = 'pending';
          } else {
            alert( 'Invalid response' );
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
    /**
     * Run timers
     */
    runTimers: function() {
      var self = this;

      setInterval( function() {
        _.each( self.sites, function( site, i ) {
          if ( ! site.processEnded && site.status == 'process' ) {
            var endTime = new Date();
            var timeDiff = endTime - site.processStarted;
            var formattedTime = new Date( timeDiff ).toISOString().substr( 11, 8 );

            site.timeElapsed = formattedTime;
          }
        } );
      }, 1000 );
    },
  },
  created: function() {
    // Initialize values
    _.each( this.sites, function( site ) {
      Vue.set( site, 'status', 'pending' );
      Vue.set( site, 'processEnded', false );
      Vue.set( site, 'processedRecords', 0 );
      Vue.set( site, 'timeElapsed', '00:00:00' );
    } );
  },
  mounted: function() {
    //this.startProcess();
  }
} );
</script>
