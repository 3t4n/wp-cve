<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Admin_Feature_Pointers {
	private static $pointers = array();
	private static $dismissed = array();
	
	private static function define_pointers() {
		// define the pointers for v2.10.0
		$version = '2100';
		self::add_pointer( $version
			, 'newjokers'
			, 'Multiple jokers'
			, 'You can now give players more multipliers (formerly known as jokers) for your pool.'
			, '.toplevel_page_footballpool-options #number_of_jokers'
		);
		// define the pointers for v2.8.0
		$version = '280';
		self::add_pointer( $version
			, 'flexboxlayout'
			, 'New layout for the matches table'
			, 'The matches table and the prediction form can now have a flexbox structure that is easier to style for small mobile devices. You can find the new layout under the Pool Layout Options.'
			, '.toplevel_page_footballpool-options span.fp-icon-navicon'
		);
		// define the pointers for v2.7.1
		$version = '271';
		self::add_pointer( $version
			, 'adminsearch'
			, 'Basic search in the admin'
			, 'All admin pages now have a simple search or filter on the main page.'
			, '.football-pool_page_footballpool-games #match_type_search'
		);
		// define the pointers for v2.7.0
		$version = '270';
		self::add_pointer( $version
			, 'simplecalc'
			, 'Simple calculation method'
			, 'Choose a simplified calculation method in the options to shorten the calculation times, but with downside of not being able to use historic data.'
			, '#toplevel_page_footballpool-options'
		);
		// define the pointers for v2.4.0
		$version = '240';
		self::add_pointer( $version
			, 'recalc'
			, 'Important'
			, 'If you are upgrading the Football Pool plugin to version 2.4.0 (from a lower version) you have to do a full recalculation. If it is a first install, you can ignore this message.'
			, '#adminmenu'
			, 'left'
			, 'top'
			, '<br><br><a href="admin.php?page=footballpool-options" onclick="FootballPoolAdmin.calculate(); return false;">recalculate now</a>.'
		);
		self::add_pointer( $version
			, 'redirecturl'
			, 'Page after registration'
			, 'You can set the page where users must be redirected to after registration (and first time login).'
			, '#redirect_url_after_login'
		);
		self::add_pointer( $version
			, 'jokermultiplier'
			, 'Joker multiplier'
			, 'Alter the default multiplier for the joker.'
			, '#joker_multiplier'
		);
		// define the pointers for v2.3.0
		$version = '230';
		self::add_pointer( $version
			, 'listingphotos'
			, 'Extra layout options'
			, 'Show photo\'s and/or info about your teams and venues in the listing on the teams and venues pages.'
			, '.toplevel_page_footballpool-options #listing_show_team_thumb'
		);
		self::add_pointer( $version
			, 'shortcode'
			, 'New shortcodes'
			, 'New shortcodes to display the score of a single user, to display the predictions for a match or question and to display a table of matches. Use the button in the toolbar to include them:'
			, '#wp-content-editor-container'
			, 'middle', 'middle'
			, sprintf( ' <img alt="" src="%sadmin/tinymce/footballpool-tinymce-16.png">', FOOTBALLPOOL_ASSETS_URL )
		);
		self::add_pointer( $version
			, 'rankinglog'
			, 'Ranking changes'
			, 'The plugin keeps track of changes in the data that might affect the ranking. The changes are displayed in the log and this log is used for the new smart recalculation of the score table.'
			, '.football-pool_page_footballpool-rankings #log-head'
		);
		self::add_pointer( $version
			, 'keepdata'
			, 'Keep data'
			, 'Keep your data in the database when deactivating the plugin.'
			, '#keep_data_on_uninstall'
		);
		self::add_pointer( $version
			, 'pointstournament'
			, 'Tournament / competition ranking'
			, 'Change the points for wins and draws if your sport doesn\'t use the 3/1 point rule.'
			, '.toplevel_page_footballpool-options #team_points_win'
		);
		self::add_pointer( $version
			, 'disablejokers'
			, 'Disable jokers'
			, 'You can completely disable jokers if you don\'t want to use them.'
			, '.toplevel_page_footballpool-options #number_of_jokers'
		);
		self::add_pointer( $version
			, 'goaldiffbonus'
			, 'Goal difference bonus'
			, 'A new scoring option: reward a player with a bonus if the correct difference in goals is predicted.'
			, '.toplevel_page_footballpool-options #diffpoints'
		);
		self::add_pointer( $version
			, 'linkedquestions'
			, 'Linked questions'
			, 'You can now link questions directly to a match.'
			, '.football-pool_page_footballpool-bonus #match_id'
		);
	}
	
	public static function init() {
		// only for admins
		if ( ! current_user_can( FOOTBALLPOOL_ADMIN_BASE_CAPABILITY ) ) {
			return;
		}
		
		// array of pointers the user already clicked away
		self::$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		// define the pointers
		self::define_pointers();
		
		$active_pointers = false;
		foreach ( self::$pointers as $pointer => $pointer_definition ) {
			if ( $pointer_definition['active'] ) {
				$active_pointers = true;
				break;
			}
		}
		
		if ( $active_pointers ) {
			add_action( 'admin_print_footer_scripts', array( __CLASS__, 'insert_pointers_script' ) );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_style( 'wp-pointer' );
		}
	}
	
	private static function add_pointer( $version, $feature, $title, $content, $anchor_id
								, $edge = 'top', $align = 'left', $unescaped_content = '' ) {
		$feature = "fp{$version}_{$feature}";
		$plugin_version = explode( '.', FOOTBALLPOOL_DB_VERSION );
		$plugin_version = "{$plugin_version[0]}{$plugin_version[1]}";
		
		self::$pointers[$feature] = array(
										'content' => sprintf( '<h3>%s</h3><p>%s%s</p>'
															, esc_attr( $title )
															, esc_attr( $content )
															, $unescaped_content
													),
										'anchor_id' => $anchor_id,
										'edge' => $edge,
										'align' => $align,
										// not active if the user already clicked the feature pointer
										// and if plugin version is not in the same release (version X.Y)
										'active' => ( ! in_array( $feature, self::$dismissed ) 
														 && strpos( $feature, "fp{$plugin_version}" ) !== false ),
									);
	}
	
	public static function insert_pointers_script() {
		echo '<script>';
		echo 'jQuery( document ).ready( function() { if ( typeof( jQuery().pointer ) != "undefined" ) { ';
		foreach( self::$pointers as $pointer => $pointer_definition ) {
			if ( $pointer_definition['active'] ) {
				printf(	"jQuery( '%s' ).pointer( { content: '%s', position: { edge: '%s', align: '%s' }, close: function() { jQuery.post( ajaxurl, { pointer: '%s', action: 'dismiss-wp-pointer' } ) } } ).pointer( 'open' );"
						, $pointer_definition['anchor_id']
						, $pointer_definition['content']
						, $pointer_definition['edge']
						, $pointer_definition['align']
						, $pointer
				);
			}
		}
		echo ' } } );</script>';
	}
	
}
