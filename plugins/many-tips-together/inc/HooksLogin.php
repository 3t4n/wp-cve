<?php
/**
 * Login hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksLogin {
    /**
     * Check options and dispatch hooks
     * 
     * @param  array $options
     * @return void
     */
    public function __construct() {
        # REDIRECT LOGIN
        if ( ADTW()->getop('login_redirect_enable')
            && ADTW()->getop('login_redirect_url') )
        {
            add_filter( 
                'login_redirect', 
                [$this, 'login_redirect'] , 
                10, 3 
            );
        }
        # REDIRECT LOGOUT
        if ( ADTW()->getop('logout_redirect_enable')
            && ADTW()->getop('logout_redirect_url') )
        {
            add_action( 
                'wp_logout', 
                [$this, 'logout_redirect'] 
            );
        }
        
        # CUSTOM MESSAGE FOR LOGIN ERRORS
        if ( ADTW()->getop('loginpage_errors') ) {
            add_filter( 
                'login_errors', 
                [$this, 'error_msg'] 
            );
        }
        
        # DISABLE SHAKING
        if ( ADTW()->getop('loginpage_disable_shaking') ) {
            add_filter( 
                'shake_error_codes', 
                '__return_empty_array', 
                15 
            );
        }

        # CUSTOM URL FOR LOGO
        if ( ADTW()->getop('loginpage_logo_url') ) {
            add_filter( 
                'login_headerurl', 
                [$this, 'logo_link'], 
                15 
            );
        }

        # REMOVE WP CSS
        if ( ADTW()->getop('loginpage_remove_css') ) {
            add_action( 
                'login_init', 
                [$this, 'remove_css'] 
            );
        }
        
        # ALL ACTIONS FOR CSS
        add_action( 
            'login_head', 
            [$this, 'login_css'], 
            999 
        );
        
        # ADD JS
        if ( ADTW()->getop('loginpage_extra_js') ) {
            add_action( 
                'login_footer', 
                [$this, 'login_js'], 
                999 
            );
        }

        # ADD JS INPUT FIELDS
        if ( ADTW()->getop('loginpage_labels_hide') ) {
            add_action( 
                'login_footer', 
                [$this, 'login_js_placeholders'], 
                999 
            );
        }

        # ADD HTML
        if ( ADTW()->getop('loginpage_extra_html') ) {
            add_action( 
                'login_header', 
                [$this, 'login_html'], 
                999 
            );
        }
    }


    /**
     * Redirect on login
     * 
     * @param type $redirect_to
     * @param type $request
     * @param type $user
     * @return string URL
     */
    public function login_redirect( $redirect_to, $request, $user ) {
        return ADTW()->getop('login_redirect_url');
    }


    /**
     * Redirect on logout
     * 
     * @return action Do redirect
     */
    public function logout_redirect() {
        wp_redirect( ADTW()->getop('logout_redirect_url') );
        die();
    }


    /**
     * Custom Alt for logo
     * 
     * @return type
     */
    public function logo_title() {
        return ADTW()->getop('loginpage_logo_tooltip');
    }


    /**
     * Custom link for logo
     * 
     * @return string URL
     */
     public function logo_link() {
       return ADTW()->getop('loginpage_logo_url');
    }


    /**
     * Custom error message on login error
     * 
     * @return string
     */
    public function error_msg() {
        $errorMsg = esc_html( stripslashes( ADTW()->getop('loginpage_errors_txt') ) );
        return $errorMsg;
    }



    /**
     * Remove all WP default styles
     * http://wordpress.stackexchange.com/a/113556/12615
     * 
     * @return array Empty
     */
    public function remove_css() {
        add_filter( 
            'style_loader_tag', 
            '__return_null' 
        );
    }

    private function _doHeight($height) {
        return sprintf(
            'height: %1$s; background-size: auto %1$s;',
            $height
        );
    }
    /**
     * Styles for the login page
     */
    function login_css() {
        // LOGO
        $logo_height  = !empty(ADTW()->getop('loginpage_logo_height')['height']) 
            ? $this->_doHeight(ADTW()->getop('loginpage_logo_height')['height']) : '';
        
        $logo_img     = !empty(ADTW()->getop('loginpage_logo_img')['url'])
            ? 'background-image:url(' 
				. ADTW()->getop('loginpage_logo_img')['url'] 
				. ') !important;' 
			: '';
        
        $div_login_h1 = (!empty($logo_height)||!empty($logo_img)) 
				? '#login h1 a { margin:0px;width:auto; ' 
				. $logo_height 
				. $logo_img 
				. '; } '
				. "\r\n" . "\r\n"
				: '';        
                    
        // FORM CONTAINER (width)
        $frm_width    = !empty(ADTW()->getop('loginpage_form_dimensions')['width']) 
            ? 'width: ' . ADTW()->getop('loginpage_form_dimensions')['width'] . ';' : '';
        
        $div_login = !empty($frm_width) ? '#login{' . $frm_width . '} '  . "\r\n" . "\r\n" : '';
        

        // FORM TAG
        $frm_height    = !empty(ADTW()->getop('loginpage_form_dimensions')['height']) 
            ? 'height: ' . ADTW()->getop('loginpage_form_dimensions')['height'] . ';' : '';
        
        $frm_margintop    = !empty(ADTW()->getop('loginpage_form_margintop')['height']) 
            ? 'margin-top: ' . ADTW()->getop('loginpage_form_margintop')['height'] . ';' : '';
        
        $frm_rounded   = !empty(ADTW()->getop('loginpage_form_rounded')['width']) 
            ? '-webkit-border-radius:' 
				. ADTW()->getop('loginpage_form_rounded')['width'] 
				. ';border-radius:' 
				. ADTW()->getop('loginpage_form_rounded')['width']
				. ';' 
			: '';
        
        $frm_border    = ADTW()->getop('loginpage_form_border') 
            ? 'border:0px;' : '';
        
        $frm_bg        = !empty(ADTW()->getop('loginpage_form_bg_img')['url']) 
            ? 'background: url(' 
				. ADTW()->getop('loginpage_form_bg_img')['url'] 
				. ') no-repeat;' 
			: '';
        
        $frm_color     = 
            ADTW()->getop('loginpage_form_bg_color') 
            ? 'background-color: ' . ADTW()->getop('loginpage_form_bg_color') . ';' : '';

        $frm_labels = 
            ADTW()->getop('loginpage_labels_hide') 
            ? ' label[for="user_pass"], label[for="user_login"] {display:none !important} .login form .input { font-size: 16px } ' : '';

        $frm_pw = 
            ADTW()->getop('loginpage_pw_hide') 
            ? ' #nav {display:none !important} ' : '';

        $div_loginform = (!empty($frm_border)||!empty($frm_height)||!empty($frm_margintop)||!empty($frm_rounded)||!empty($frm_bg)||!empty($frm_color)||!empty($frm_labels)||!empty($frm_pw)) 
			? '#loginform {' 
            . $frm_border . $frm_height . $frm_margintop
            . $frm_rounded . $frm_bg . $frm_color 
            . '; margin-left:0} '
			. "\r\n" . $frm_labels . $frm_pw . "\r\n"
			: ''; // margin-left Force full width

           
        
        // BODY
        $body_color     = 
                ADTW()->getop('loginpage_body_color') 
            ? 'background-color:' . ADTW()->getop('loginpage_body_color') . ';' : '';
        
        $body_position   = '';
        if( ADTW()->getop('loginpage_body_position') ) {
            if( 'empty' != ADTW()->getop('loginpage_body_position') )
                $body_position = str_replace( 
						'_', 
						' ', 
						ADTW()->getop('loginpage_body_position') 
				) . ' ';
			 else
				 $body_position = '';
        }
		
        $body_repeat     = '';
        if( ADTW()->getop('loginpage_body_repeat' ) ) {
            if( 'empty' != ADTW()->getop('loginpage_body_repeat') )
                $body_repeat = ADTW()->getop('loginpage_body_repeat') . ' ';
			else
				$body_repeat = '';
        }
		
        $body_attachment     = '';
        if( ADTW()->getop('loginpage_body_attachment') ) {
            if( 'empty' != ADTW()->getop('loginpage_body_attachment') ) 
                $body_attachment = ADTW()->getop('loginpage_body_repeat') . ' ';
			else
				$body_attachment = '';
        }
		
		
        $body_img        = !empty(ADTW()->getop('loginpage_body_img')['url'])
            ? 'url(' . ADTW()->getop('loginpage_body_img')['url'] . ')' : '';
 
		
        $css_img         = (!empty($body_position)||!empty($body_repeat)||!empty($body_attachment)||!empty($body_img))  
            ? 'background:' 
				. $body_position 
				. $body_repeat 
				. $body_attachment 
				. $body_img 
				. ';' 
			: '';

        $htmlbody = (!empty($css_img)||!empty($body_color))
				? 'body,body.login{height:100%;' . $css_img . $body_color . '} '  . "\r\n" . "\r\n"
				: '';


        // BACK TO BLOG
        $p_backtoblog        = ADTW()->getop('loginpage_backsite_hide') 
            ? ' p#backtoblog{display:none !important;} '  . "\r\n" . "\r\n"
			: '';


        // LANGUAGES
        $select_languages        = ADTW()->getop('loginpage_hide_languages') 
            ? ' div.language-switcher{display:none !important;} '  . "\r\n" . "\r\n"
			: '';


        // EXTRA CSS
        $extra_css = ADTW()->getop('loginpage_extra_css')
                ? ADTW()->getop('loginpage_extra_css')  . "\r\n" . "\r\n"
                : '';
        
        
        // ufs... PRINT OUR STUFF
		if (!empty($div_login_h1)||!empty($div_login)||!empty($div_loginform)||!empty($htmlbody)||!empty($p_backtoblog)||!empty($extra_css)) 
		{
			echo '
	<style type="text/css">'
					. $div_login_h1
					. $div_login
					. $div_loginform
					. $htmlbody
					. $p_backtoblog
                    . $select_languages
					. $extra_css
					. '</style>'
					. "\r\n";
		}
	}

    public function login_js() {
        printf(
            '<script>%s</script>',
            ADTW()->getop('loginpage_extra_js')
        );
    }
    
    public function login_js_placeholders() {
        $u = esc_html__('Username or Email address');
        $p = esc_html__('Password');
        echo "<script>
        jQuery(document).ready( $=>{ 
            $('#user_pass').attr('placeholder', '$p');
            $('#user_login').attr('placeholder', '$u');});
        </script>";
    }
    
    public function login_html() {
        echo ADTW()->getop('loginpage_extra_html');
    }
    
}