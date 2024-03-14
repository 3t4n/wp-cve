<?php

if ( !defined( 'ABSPATH' ) ) {
    die;
}
/*
* Declaring Class
*/
class AEH_Main
{
    public  $settings ;
    public  $external_settings ;
    public function __construct()
    {
        $this->settings = AEH_Settings::get_instance();
    }
    
    public function add_review_request()
    {
        $review_request_time = get_option( 'review_request_time' );
        
        if ( $review_request_time ) {
            $current_time = time();
            if ( $current_time > $review_request_time ) {
                echo  '<div class="notice notice-success is-dismissible">
				<p>
					<img style="float:left;margin-right:27px;width: 50px;padding: 0.25em;" src="' . AEH_URL . 'assests/images/AddExpiresHeaders.png">
					<strong>
						Hi there! You\'ve been using Add Expires Headers and Optimized Minify Plugin. We hope it\'s been helpful. Would you mind rating it 5-stars to help spread the word?
					</strong>	
				</p>
				<p>
					<a class="button button-primary" target="_blank" href="https://wordpress.org/support/plugin/add-expires-headers/reviews/?rate=5#rate-response>" data-reason="am_now">
						<strong>Ok, you deserve it</strong>
					</a>
					<a class="button-secondary aeh-dismiss-maybelater" data-reason="maybe_later">
						Nope, maybe later
					</a>
					<a class="button-secondary aeh-dismiss-alreadydid" data-reason="already_did">
						I already did
					</a>
				</p>
			</div>' ;
            }
        } else {
            update_option( 'review_request_time', strtotime( date( 'd-m-Y H:i:s' ) . "+ 48 hours" ) );
        }
    
    }
    
    public function hide_review_request()
    {
        $nonce = $_REQUEST['security'];
        if ( wp_verify_nonce( $nonce, 'maybelater-nonce' ) ) {
            update_option( 'review_request_time', strtotime( date( 'd-m-Y H:i:s' ) . "+ 120 hours" ) );
        }
        if ( wp_verify_nonce( $nonce, 'alreadydid-nonce' ) ) {
            update_option( 'review_request_time', strtotime( date( 'd-m-Y H:i:s' ) . "+ 4800 hours" ) );
        }
    }
    
    public function remove_settings()
    {
        $this->delete_from_htaccess();
        if ( get_option( 'aeh_scanned_urls' ) ) {
            delete_option( 'aeh_scanned_urls' );
        }
        if ( get_option( 'aeh_extracted_urls' ) ) {
            delete_option( 'aeh_extracted_urls' );
        }
        if ( get_option( 'aeh_expires_headers_external_cache_settings' ) ) {
            delete_option( 'aeh_expires_headers_external_cache_settings' );
        }
        if ( get_option( 'aeh_expires_headers_advance_settings' ) ) {
            delete_option( 'aeh_expires_headers_advance_settings' );
        }
        if ( get_option( 'aeh_expires_headers_minify_settings' ) ) {
            delete_option( 'aeh_expires_headers_minify_settings' );
        }
        if ( get_option( 'aeh_expires_headers_settings' ) ) {
            delete_option( 'aeh_expires_headers_settings' );
        }
        $files = glob( AEH_DIR . 'external-cache/*' );
        if ( $files ) {
            foreach ( $files as $file ) {
                if ( is_file( $file ) ) {
                    unlink( $file );
                }
            }
        }
    }
    
    /*checking for mod_expires module on server */
    public function aeh_is_mod_not_loaded( $mod, $default = false )
    {
        global  $is_apache ;
        
        if ( $is_apache ) {
            
            if ( function_exists( 'apache_get_modules' ) ) {
                $mods = apache_get_modules();
                
                if ( in_array( $mod, $mods ) ) {
                    return false;
                } else {
                    return 'Add Expires Headers requires the ' . $mod . ' module to be enabled on the server.';
                }
            
            } elseif ( function_exists( 'phpinfo' ) && false === strpos( ini_get( 'disable_functions' ), 'phpinfo' ) ) {
                ob_start();
                phpinfo( 8 );
                $phpinfo = ob_get_clean();
                
                if ( false !== strpos( $phpinfo, $mod ) ) {
                    return false;
                } else {
                    return 'Add Expires Headers requires the ' . $mod . ' module to be enabled on the server.';
                }
            
            }
        
        } else {
            return false;
        }
    
    }
    
    /* main function which updates lines to .htaccess file according to plugin settings */
    public function write_to_htaccess()
    {
        if ( $this->delete_from_htaccess() == -1 ) {
            return -1;
        }
        $htaccess = ABSPATH . '.htaccess';
        $siteurl = explode( '/', get_option( 'siteurl' ) );
        
        if ( isset( $siteurl[3] ) ) {
            $dir = '/' . $siteurl[3] . '/';
        } else {
            $dir = '/';
        }
        
        
        if ( !($f = @fopen( $htaccess, 'a+' )) ) {
            @chmod( $htaccess, 0644 );
            if ( !($f = @fopen( $htaccess, 'a+' )) ) {
                return -1;
            }
        }
        
        @ini_set( 'auto_detect_line_endings', true );
        $ht = explode( PHP_EOL, implode( '', file( $htaccess ) ) );
        //parse each line of file into array
        $rules = $this->getrules();
        if ( $rules == -1 ) {
            return -1;
        }
        $rulesarray = explode( PHP_EOL, $rules );
        $contents = array_merge( $rulesarray, $ht );
        if ( !($f = @fopen( $htaccess, 'w+' )) ) {
            return -1;
        }
        $blank = false;
        foreach ( $contents as $insertline ) {
            
            if ( trim( $insertline ) == '' ) {
                if ( $blank == false ) {
                    fwrite( $f, PHP_EOL . trim( $insertline ) );
                }
                $blank = true;
            } else {
                $blank = false;
                fwrite( $f, PHP_EOL . trim( $insertline ) );
            }
        
        }
        @fclose( $f );
        return 1;
    }
    
    /* getting lines to be added in .htaccess files base on settings */
    private function getrules()
    {
        @ini_set( 'auto_detect_line_endings', true );
        
        if ( strstr( strtolower( filter_var( $_SERVER['SERVER_SOFTWARE'], FILTER_SANITIZE_STRING ) ), 'apache' ) ) {
            $aiowps_server = 'apache';
        } else {
            
            if ( strstr( strtolower( filter_var( $_SERVER['SERVER_SOFTWARE'], FILTER_SANITIZE_STRING ) ), 'nginx' ) ) {
                $aiowps_server = 'nginx';
            } else {
                
                if ( strstr( strtolower( filter_var( $_SERVER['SERVER_SOFTWARE'], FILTER_SANITIZE_STRING ) ), 'litespeed' ) ) {
                    $aiowps_server = 'litespeed';
                } else {
                    return -1;
                }
            
            }
        
        }
        
        $rules = '';
        $aeh_expires_headers_settings = get_option( 'aeh_expires_headers_settings' );
        $rules .= '<IfModule mod_expires.c>' . PHP_EOL;
        $rules .= 'ExpiresActive on' . PHP_EOL;
        $general_settings = $this->settings->expires_headers_general_settings;
        foreach ( $general_settings as $key => $value ) {
            $rules .= "#" . $key . PHP_EOL;
            
            if ( isset( $aeh_expires_headers_settings['general'][$key] ) ) {
                $type_key = 'expires_headers_' . $key . '_types';
                foreach ( $aeh_expires_headers_settings[$key] as $key1 => $value1 ) {
                    
                    if ( isset( $aeh_expires_headers_settings[$key][$key1] ) ) {
                        $expiryDays = ( isset( $aeh_expires_headers_settings['expires_days'][$key] ) && !empty($aeh_expires_headers_settings['expires_days'][$key]) ? $aeh_expires_headers_settings['expires_days'][$key] : 30 );
                        $rules .= 'ExpiresByType ' . $key . '/' . $key1 . ' A' . $expiryDays * 86400 . PHP_EOL;
                    }
                
                }
            }
        
        }
        $rules .= '</IfModule>' . PHP_EOL;
        if ( $rules != '' ) {
            $rules = "# BEGIN Add Expires Headers Plugin" . PHP_EOL . $rules . "# END Add Expires Headers Plugin" . PHP_EOL;
        }
        return $rules;
    }
    
    /* delete previous plugin lines from file */
    private function delete_from_htaccess( $section = 'Add Expires Headers Plugin' )
    {
        $htaccess = ABSPATH . '.htaccess';
        @ini_set( 'auto_detect_line_endings', true );
        
        if ( !file_exists( $htaccess ) ) {
            $ht = @fopen( $htaccess, 'a+' );
            @fclose( $ht );
        }
        
        $ht_contents = explode( PHP_EOL, implode( '', file( $htaccess ) ) );
        
        if ( $ht_contents ) {
            $state = true;
            
            if ( !($f = @fopen( $htaccess, 'w+' )) ) {
                @chmod( $htaccess, 0644 );
                if ( !($f = @fopen( $htaccess, 'w+' )) ) {
                    return -1;
                }
            }
            
            foreach ( $ht_contents as $n => $markerline ) {
                if ( strpos( $markerline, '# BEGIN ' . $section ) !== false ) {
                    $state = false;
                }
                if ( $state == true ) {
                    fwrite( $f, trim( $markerline ) . PHP_EOL );
                }
                if ( strpos( $markerline, '# END ' . $section ) !== false ) {
                    $state = true;
                }
            }
            @fclose( $f );
            return 1;
        }
        
        return 1;
    }

}