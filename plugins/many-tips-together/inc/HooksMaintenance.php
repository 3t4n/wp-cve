<?php
/**
 * Maintenance hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;
 
class HooksMaintenance {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct() {
        if( ADTW()->getop('maintenance_mode_enable') ) {
            // BACKEND MAINTENANCE
			if( ADTW()->getop('maintenance_mode_backend') ) {
                add_action( 
                    'admin_head', 
                    [$this, 'do_maintenance'] 
                );
			}		
			// FRONTEND MAINTENANCE
			else {
				add_action( 
                    'admin_head', 
                    [$this, 'do_maintenance'] 
                );
				add_action( 
                    'wp', 
                    [$this, 'do_maintenance'] 
                );
			}
		}
	}


	/**
	 * Build Html and die() with response 503
	 * 
	 */
	public function do_maintenance() {
		$level = ADTW()->getop('maintenance_mode_level') 
                ? ADTW()->getop('maintenance_mode_level') 
                : 'administrator';
        
        $roles = [
            'administrator' => 'manage_options',
            'editor' => 'delete_others_pages',
            'author' => 'edit_published_posts',
            'contributor' => 'delete_posts',
            'subscriber'  => 'read'
        ];
		if( isset($roles[$level]) && !current_user_can( $roles[$level] ) ) {
            // DEFAULTS
            $default_title = get_bloginfo( 'name' ) . esc_html__( ' | Maintenance Mode', 'mtt' );
            $default_line1 = sprintf(
                '<b>%s</b><br> %s',
                get_bloginfo( 'name' ),
                get_bloginfo( 'description' )
            );
			// BROWSER TITLE
			$title = 
					ADTW()->getop('maintenance_mode_title')
					? ADTW()->getop('maintenance_mode_title') 
					: $default_title;

			// IMAGES
			$custom_stripes = ADTW_URL . '/assets/images/pattern.png';
			$custom_bg = ADTW_URL . '/assets/images/kub-locked.png';

			// LINE 0
			$siteName = 
                    ADTW()->getop('maintenance_mode_line0')
					? ADTW()->getop('maintenance_mode_line0') 
					: esc_html__( 'Site in maintenance', 'mtt' );

			// LINE 1
			$line1Text = 
                    ADTW()->getop('maintenance_mode_line1') 
					? ADTW()->getop('maintenance_mode_line1') 
					: $default_line1;

			// LINE 2
			$line2Text = 
					ADTW()->getop('maintenance_mode_line2') 
					? ADTW()->getop('maintenance_mode_line2') 
					: get_bloginfo( 'url' );
            $line2Text = strtok($line2Text, '?');
            $line2Text = untrailingslashit($line2Text );
            $line2Text = str_replace( 'http://', '', $line2Text );
            $line2Text = str_replace( 'https://', '', $line2Text );


			// HTML BACKGROUND
            $bgcolor =  
					ADTW()->getop('maintenance_mode_bg_color') 
					? ADTW()->getop('maintenance_mode_bg_color')
                    : '';

            if( $bgcolor !== '' )
				$bgcolor = "background-color: $bgcolor ;";

			$stripes = 
					ADTW()->getop('maintenance_mode_html_img')['url'] 
					? ADTW()->getop('maintenance_mode_html_img')['url']
                    : '';

            if( $stripes !== '' )
				$stripes = "background-image:url($stripes) repeat;";
			elseif( $bgcolor === '' )
				$stripes = "background:url($custom_stripes) repeat;";

			// BOX ("body") BACKGROUND
			$box_bg = 
					ADTW()->getop('maintenance_mode_body_img')['url']
					? ADTW()->getop('maintenance_mode_body_img')['url'] : '';

			$box_shadow = '-webkit-border-radius: 23px; border-radius: 23px; -moz-box-shadow: 5px 5px 8px #DCDCDC; -webkit-box-shadow: 5px 5px 8px #DCDCDC; box-shadow: 5px 5px 8px #DCDCDC;';

			if( '' != $box_bg )
				$box_bg = 'background:url(' . $box_bg . ') no-repeat;';
			else
				$box_bg = 'background: rgba(51, 102, 153, 0.75) url(' . $custom_bg . ') no-repeat 30px 30px;';

			// CUSTOM CSS
			$extraCss = 
                ADTW()->getop('maintenance_mode_extra_css') 
                ? ADTW()->getop('maintenance_mode_extra_css') : '';

			// CSS of this file
			$msg = "<style type='text/css'>
            *{padding:0;margin:0}
            html { border: 0; $stripes $bgcolor}
            body{
                border:0;width:900px;max-width:900px;height:560px;
                font-family:'Myriad Pro',Arial,Helvetica,sans-serif;
                margin: 0 auto; $box_bg $box_shadow
            }
            #header {height:397px;margin-bottom:-200px}
            #wrapper {width:467px;margin:80px auto}
            h1 {padding-top:180px;color:#fff;font-size:2em;font-weight:bold;text-align:center;white-space:nowrap;text-shadow: 0.1em 0.1em 0.2em black;border-bottom:0px}
            h2 {color:#fff;font-size:12px;letter-spacing: 0.1em;font-weight:bold;text-align:center;text-shadow: 0.1em 0.1em 1.2em black;margin-top:.5em}
            #when, .textwidget {color:#000; font-size:1.2em;text-align:center;margin-top:1.5em;}
            a {color: #fff}
            a:hover {color: #000}
            $extraCss
            </style>";

            // html of this file
            $msg .= " <div id='wrapper'>
                <div id='header' class='blank'>
                    <h1>{$siteName}</h1>
                </div>
                <div id='when'>
                    {$line1Text}
                    <h2><a href='//{$line2Text}'>{$line2Text}</a></h2>
                </div>
            </div>";
			wp_die( $msg, $title, array( 'response' => 503 ) );
		}
	}


}