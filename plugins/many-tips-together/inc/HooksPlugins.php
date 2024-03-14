<?php
/**
 * MPlugins hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksPlugins {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct() {

		# DISABLE PLUGIN UPDATE NOTICES
		if( ADTW()->getop('plugins_block_update_notice') ) {
			add_filter(
                'pre_site_transient_update_plugins', 
                '__return_null'
			);
            add_filter( 
                'views_plugins', 
                [$this, 'warn_update_nag_deactivated']
            );
        }

		# DISABLE INACTIVE PLUGIN UPDATE NOTICES
		if( ADTW()->getop('plugins_block_update_inactive_plugins') && !is_multisite() ) {
			add_filter(
                'site_transient_update_plugins', 
                [$this, 'remove_update_nag_for_deactivated']
			);
            add_filter( 
                'views_plugins', 
                [$this, 'warn_update_nag_deactivated']
            );
        }
        
		# DISABLE EMAIL AUTO-UPDATE NOTICES
		if( ADTW()->getop('plugins_block_emails_updates') ) {
			add_filter(
                'auto_plugin_update_send_email', 
                '__return_false'
			);
        }
        
		# FILTER BY 
		if( ADTW()->getop('plugins_live_filter') ) {
            add_action( 
                'admin_print_footer_scripts-plugins.php', 
                [$this, 'printFilterPlugins']
            );
        }

		# ADD LAST UPDATED INFORMATION
		if( ADTW()->getop('plugins_add_last_updated') ) {
			add_filter(
                'plugin_row_meta', 
                [$this, 'lastUpdated'],
                10, 4
			);
        }

        # ALL CSS and JS OPTIONS CHECKED INSIDE
		add_action(
            'admin_head-plugins.php', 
            [$this, 'pluginsCSSJS']
		);
	}

    /**
	 * CSS and JS for Filter By
	 */
	public function printFilterPlugins() {
		wp_register_style( 
				'mtt-filterby', 
				ADTW_URL . '/assets/filter-listings.css', 
				array(), 
				ADTW()->cache('/assets/filter-listings.css')  
		);
		wp_register_script( 
				'mtt-filterby', 
				ADTW_URL . '/assets/filter-plugins.js', 
				array(), 
				ADTW()->cache('/assets/filter-plugins.js')  
		);
		wp_enqueue_style( 'mtt-filterby' );
		wp_enqueue_script( 'mtt-filterby' );
        wp_add_inline_script( 
            'mtt-filterby', 
            'const ADTW = ' . json_encode([
                'html' => $this->_filtersHtml(),
            ]), 
            'before' 
        );
	}

    private function _filtersHtml()
    {
        return sprintf(
            '<div class="mysearch-wrapper">
            <span class="dashicons dashicons-buddicons-forums b5f-icon" 
                title="%1$s">
            </span> 
            <button id="hide-desc" class="button b5f-button" 
                title="%2$s" 
                data-title-hide="%2$s" 
                data-title-show="%3$s">
            %4$s</button> 
            <button id="hide-active" class="button b5f-button b5f-btn-status" 
                title="%6$s" 
                data-title-hide="%5$s" 
                data-title-show="%6$s">
            %7$s</button> 
            <button id="hide-inactive" class="button b5f-button b5f-btn-status" 
                title="%8$s" 
                data-title-hide="%5$s" 
                data-title-show="%8$s">
            %9$s</button>
            <input type="text" id="b5f-plugins-filter" class="mysearch-box" 
                name="focus" value="" placeholder="%10$s" 
                title="%11$s" />
            <button class="close-icon" type="reset"></button>
            </div>',
            'by '.AdminTweaks::NAME,                #1
            esc_html__('Show descriptions', 'mtt'), #2
            esc_html__('Hide descriptions', 'mtt'), #3
            esc_html__('Description', 'mtt'),
            esc_html__('Show all', 'mtt'), #5
            esc_html__('Show active', 'mtt'), #6
            esc_html__('Active', 'mtt'),
            esc_html__('Show inactive', 'mtt'), #9
            esc_html__('Inactive', 'mtt'),
            esc_html__('filter by keyword', 'mtt'),
            esc_html__('enter a string to filter the list', 'mtt'),
        );
    }

    public function warn_update_nag_deactivated( $views ){
        $base = 'UPDATES NOT SHOWING (by Admin Tweaks)';
        $msg = 'some ' . $base;
        if ( ADTW()->getop('plugins_block_update_notice') ) {
            $msg = $base;
        }
        $views['adtw-nag-inactive'] = "<b><small>$msg</small></b>";
        return $views;
    }

	/**
	 * Remove update notice for desactived plugins
	 * Tip via: http://wordpress.stackexchange.com/a/77155/12615
	 * 
	 * @param type $value
	 * @return type
	 */
	public function remove_update_nag_for_deactivated( $value ) {
		if( empty( $value ) || empty( $value->response ) )
			return $value;
		
		foreach( $value->response as $key => $val ) {
			if( !is_plugin_active( $val->plugin ) )
				unset( $value->response[$key] );
		}
		return $value;
	}


	/**
	 * Remove Action Links
	 * 
	 * @return empty
	 */
	public function remove_action_links() {
		return;
	}


	/**
	 * Add Last Updated information to the Meta row (author, plugin url)
	 * 
	 * @param string $plugin_meta
	 * @param type $plugin_file
	 * @return string
	 */
	public function lastUpdated( $plugin_meta, $pluginfile, $plugin_data, $status ) {
		// If Multisite, only show in network admin
		if( is_multisite() && !is_network_admin() )
			return $plugin_meta;
            
		list( $slug ) = explode( '/', $pluginfile );

		$slug_hash = md5( $slug );
		$last_updated = get_transient( "range_plu_{$slug_hash}" );
		if( false === $last_updated )
		{
			$last_updated = $this->get_last_updated( $slug );
			set_transient( "range_plu_{$slug_hash}", $last_updated, 86400 );
		}

		if( $last_updated )
			$plugin_meta['last_updated'] = esc_html__( 'Last Updated', 'mtt' )
					. esc_html( ': ' . $last_updated );

		return $plugin_meta;
	}


	/**
	 * Custom CSS for Plugins page
	 * 
	 * @return string Echo 
	 */
	public function pluginsCSSJS() 
    {    
		$display_count = ADTW()->getop('plugins_my_plugins_count');

		// GENERAL OUTPUT
		$output = '';

		// UPDATE NOTICE
		if( ADTW()->getop('plugins_remove_plugin_notice') )
			$output .= '.update-message{display:none;} ';

		// INACTIVE
		if( ADTW()->getop('plugins_inactive_bg_color') )
			$output .= 'tr.inactive {background-color:' . ADTW()->getop('plugins_inactive_bg_color') . ' !important;}';

		if( !empty($output)  )  {
			echo '<style type="text/css">' . $output . ' </style>' . "\r\n";
        }
        // YOUR PLUGINS COLOR
        if( ADTW()->getop('plugins_my_plugins_bg_color') 
            && ADTW()->getop('plugins_my_plugins_names') 
            && ADTW()->getop('plugins_my_plugins_color') 
        ) {        
            $authors = explode( ',', ADTW()->getop('plugins_my_plugins_names'));
        
            $jq = array( );
            foreach( $authors as $author ) {
                $jq[] = "tr:Contains('{$author}')";
            }
            $jq_ok = implode( ',', $jq );
            $by_author = esc_html__( 'by selected author(s)', 'mtt' );
            ?>
            <script type="text/javascript">
                // https://css-tricks.com/snippets/jquery/make-jquery-contains-case-insensitive/
                jQuery.expr[':'].Contains = function(a, i, m) {
                    return jQuery(a).text().toUpperCase()
                        .indexOf(m[3].toUpperCase()) >= 0;
                };
                jQuery(document).ready(function($) {
                    <?php if( $display_count ): ?>
                        // Display author count
                        var atual = $('.displaying-num').html();
                        $('.displaying-num').html( atual+' : '+$("#the-list").children("<?php echo $jq_ok; ?>").length + ' ' + '<?php echo $by_author; ?>' );
                    <?php endif; ?>
                    
                    // Modify the plugin rows background
                    $("<?php echo $jq_ok; ?>").each(function() {
                        if ($(this).hasClass('inactive'))
                            opac = '0.6';
                        else
                            opac = '1';
                        //$(this).removeClass('inactive');
                        $('td,th', this).css('background-color', '<?php echo ADTW()->getop('plugins_my_plugins_color'); ?>');
                        $(this).css('opacity', opac);
                    });
                });
            </script>
            <?php
		}
	}


	/**
	 * Query WP API
	 * from the plugin http://wordpress.org/plugins/plugin-last-updated/
	 * 
	 * @param type $slug
	 * @return boolean|string
	 */
	private function get_last_updated( $slug )
	{
		$request = wp_remote_post(
            'http://api.wordpress.org/plugins/info/1.0/', array(
			'body' => array(
				'action'	 => 'plugin_information',
				'request'	 => serialize(
                    (object) array(
                        'slug'	 => $slug,
                        'fields' => array( 'last_updated' => true )
                    )
				)
			))
		);
		if( 200 != wp_remote_retrieve_response_code( $request ) )
			return false;

		$response = unserialize( wp_remote_retrieve_body( $request ) );
		// Return an empty but cachable response if the plugin isn't in the .org repo
		if( empty( $response ) )
			return '';
		if( isset( $response->last_updated ) )
			return sanitize_text_field( $response->last_updated );

		return false;
	}

}