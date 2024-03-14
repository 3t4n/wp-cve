<?php
/**
 * Appearance hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;


class HooksAppearance {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @return void
	 */
	public function __construct() {

        // HELP TAB
		if( ADTW()->getop('appearance_hide_help_tab') ) {
            add_action( 
                'admin_head', 
                [$this, 'adminHead'], 
                999
            );
        }
		// SCREEN TAB
		if( ADTW()->getop('appearance_hide_screen_options_tab') ) {
            add_filter( 
                'screen_options_show_screen', 
                '__return_false'
            );
        }
        
        // DASHBOARD HELP TEXTS
		if ( ADTW()->getop('appearance_help_texts_enable') ) {
            $roles = ADTW()->getop('appearance_help_texts_roles');
			$ucan = empty($roles) 
                    ? true 
					: ADTW()->current_user_has_role_array($roles);
			if ($ucan) {
				add_action( 
                    'admin_print_scripts', 
                    [$this, 'printScripts'], 
                    5 
                );
			}
		}

		// ADMIN NOTICES
		if( ADTW()->getop('admin_notice_header_settings_enable') 
            || ADTW()->getop('admin_notice_header_allpages_enable') ) {
			add_action( 
                'admin_notices', 
                [$this, 'adminNotices'], 
                1 
            );
        }

        // FOOTER HIDE
        if ( ADTW()->getop('admin_notice_footer_hide') ) {
            add_filter( 
                'admin_print_styles', 
                [$this, 'footerHide'] 
            );
        }

		// FOOTER MESSAGES
		if( !ADTW()->getop('admin_notice_footer_hide') && ADTW()->getop('admin_notice_footer_message_enable') ) {
			add_filter( 
                'admin_footer_text', 
                [$this, 'footerLeft'], 
                999999 
            );
			add_filter( 
                'update_footer', 
                [$this, 'footerRight'], 
                999999 
            );
		}    

		// GLOBAL CSS
		if ( ADTW()->getop('admin_global_css') && trim(ADTW()->getop('admin_global_css')) !== '' ) {
			add_action( 
                'admin_head', 
                [$this, 'adminCSS'] 
            );
        }
	}

    /**
     * Remove Help Tabs
     *
     * @return void
     */
	public function adminHead() {
        get_current_screen()->remove_help_tabs();
    }

	/**
	 * Stylesheet for hiding dashboard help elements
	 */
	public function printScripts() {
		wp_register_style( 
				'mtt-hide-help', 
				ADTW_URL . '/assets/hide-help.css', 
				[], 
				ADTW()->cache('/assets/hide-help.css') 
		);
		wp_enqueue_style( 'mtt-hide-help' );
	}

    /**
     * Admin Notices for all pages and for settings page
     * custom text provided by options
     *
     * @return void
     */
	public function adminNotices() {
		global $current_screen;
		if( !empty( ADTW()->ops['admin_notice_header_settings_enable'] ) 
			&& 'options-general' == $current_screen->parent_base 
		)
		{
			printf (
                '<div  class="updated">%s</div>',
                ADTW()->ops['admin_notice_header_settings_text']
            );
		}

		// enable general notice
		if ( !empty(ADTW()->ops['admin_notice_header_allpages_enable']) ) {
			$ucan = 
					empty( ADTW()->ops['admin_notice_header_allpages_roles'] ) 
					? true 
					: ADTW()->current_user_has_role_array( ADTW()->ops['admin_notice_header_allpages_roles'] );
			if ( $ucan ) {
				echo '<div  class="updated">' . ADTW()->ops['admin_notice_header_allpages_text'] . '</div>';
			}
		}		
	}

	
	/**
	 * Hide footer
	 */
	public function footerHide() {
		echo '<style type="text/css">#wpfooter { display: none; }</style>';
	}


	/**
	 * Print custom text at Footer Left
	 * 
	 * @param string $default_text
	 * @return string
	 */
	public function footerLeft( $default_text ) {
        $slashes = stripslashes( ADTW()->ops['admin_notice_footer_message_left'] );
		return html_entity_decode( $slashes );
	}


	/**
	 * Print custom text at Footer Right
	 * 
	 * @param string $default_text
	 * @return string
	 */
	public function footerRight( $default_text ) {
        $slashes = stripslashes( ADTW()->ops['admin_notice_footer_message_right'] );
		return html_entity_decode( $slashes );
	}


	/**
	 * Print custom CSS in all Admin pages
	 */
	public function adminCSS() {
		print '<style type="text/css">' . ADTW()->ops['admin_global_css'] . '</style>';;

	}
}