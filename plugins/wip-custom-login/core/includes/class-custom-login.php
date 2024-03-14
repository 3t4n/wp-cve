<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if( !class_exists( 'wip_custom_login_class' ) ) {

	class wip_custom_login_class {
	
		/**
		 * Constructor
		 */
		 
		public function __construct( $fields = array() ) {

			add_action('login_headertext', array(&$this, 'logo_title'));
			add_filter('login_headerurl', array(&$this, 'logo_url'));
			add_action('login_enqueue_scripts', array(&$this, 'admin_style') );

		}

		/**
		 * Logo title
		 */
		 
		public function logo_title() {
		
			return get_bloginfo('name') . " | " . get_bloginfo('description') ;

		}
		
		/**
		 * Logo url
		 */
		 
		public function logo_url() {
			return home_url();
		}
		
		/**
		 * Admin style
		 */
		 
		public function admin_style() { ?>

			<style type="text/css">
                    
                html {
                    background:none !important;
					margin-top: 0 !important;
					height:auto;
                }
                
                body.login {
					
					<?php if ( wip_custom_login_setting('wip_custom_login_background_image')) : ?>
					   
						background-image: url('<?php echo esc_html(wip_custom_login_setting('wip_custom_login_background_image')); ?>');
						background-repeat: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_background_repeat', 'repeat'));?>;
						background-repeat: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_background_position', ''));?>;
						background-repeat: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_background_attachment', 'normal'));?>;
						
					<?php endif; ?>
					
                    background-color: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_background_color', '#f1f1f1'));?>;
					padding-top:150px;
					height:auto;

				}

                <?php 
					if ( 
						wip_custom_login_setting('wip_custom_login_loginbox_position') && 
						wip_custom_login_setting('wip_custom_login_loginbox_position') <> 'center'
					) :
				?>
				   
					body.login div#login {
						position:relative;
						float: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_position', 'left'));?>;
						margin:auto;
						<?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_position', 'left'));?> : 50px;
					}
	
					body.login .language-switcher {
						position:relative;
						clear:both;
						float: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_position', 'left'));?>;
						margin:0 auto;
						<?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_position', 'left'));?> : 50px;
						padding: 0 40px;
						width:<?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_width', '350px'));?>;
					}
	
					body.login .language-switcher form#language-switcher {
						margin-top:0;
					}
	
                <?php endif; ?>

                body.login div#login h1 a {

					<?php if ( wip_custom_login_setting('wip_custom_login_logo_url')) : ?>
					   
						background-image: url('<?php echo esc_html(wip_custom_login_setting('wip_custom_login_logo_url')); ?>');
						-webkit-background-size: inherit;
						background-size: inherit ;
						width:<?php echo esc_html(wip_custom_login_setting('wip_custom_login_logo_width', '140px'));?>;
						height:<?php echo esc_html(wip_custom_login_setting('wip_custom_login_logo_height', '140px'));?>
						
					<?php endif; ?>

			    }
                
                body.login  div#login {
		            margin-top:0;
                    margin-bottom:0; 
                    padding:0;
                    clear: both;
                    border: 1px solid <?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_border', '#d2d2d2'));?>;
                    margin-bottom: 24px;
                    text-align: left;
                    border-collapse: separate;
                    padding: 42px 40px;
                    background: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_background', '#fff'));?>;
                    position: relative;
                    display: block;
                    -webkit-border-radius: 6px;
                    -moz-border-radius: 6px;
                    border-radius: 6px;
                    width:<?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_width', '350px'));?>;
			    }
                
                body.login .jetpack-sso-or span {
                    background: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_background', '#fff'));?>;
			    }
                
                body.login .jetpack-sso-or:before {
                    border-color: <?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_border', '#d2d2d2'));?>;
			    }
                
                body.login div#login #backtoblog, 
                body.login div#login #nav {
                    text-align:center;
                }
                
				body.login #loginform #jetpack-sso-wrap a.jetpack-sso.button,    
				body.login #loginform #jetpack-sso-wrap a.jetpack-sso.button:hover {
					color:#fff;
				}
				
                body.login .jetpack-sso-or span,
			    body.login #loginform #jetpack-sso-wrap a,    
                body.login #loginform #jetpack-sso-wrap p,    
                body.login div#login #backtoblog a, 
                body.login div#login #nav a,
                body.login div#login label ,
                body.login div#login p {
                    color:<?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_textcolor', '#333'));?>;
                }

                body.login #loginform #jetpack-sso-wrap a:hover,    
                body.login div#login #backtoblog a:hover, 
                body.login div#login #nav a:hover{
                    color:<?php echo esc_html(wip_custom_login_setting('wip_custom_login_loginbox_linkcolor_hover', '#008ec2'));?>;
                }
                    
                body.login div#login p.message {
                    color:#333;
                }
                    
                body.login div#login form {
                    border: none;
                    background: none;
                    -webkit-box-shadow: none;
                    box-shadow: none;
                    padding:0;
                    margin-top:0;
                }
                
                body.login div#login label {
                    font-size:0;
                }
                
                body.login div#login form .input, 
                body.login div#login input[type="text"] {
                    padding:8px 16px;
                    font-size:14px;
                }
                    
                body.login div#login input#user_login {
                    margin-bottom:0;
                }
                    
                body.login div#login #backtoblog {
                    margin: 8px 0 16px 0;
                }
                    
                body.login div#login p.forgetmenot {
                    line-height:30px;
                }
                
                body.login div#login p.forgetmenot label {
                    font-size: inherit;
                }

                body.login #loginform #jetpack-sso-wrap a,    
                body.login #loginform #jetpack-sso-wrap p,    
                body.login div#login #backtoblog, 
                body.login div#login p#reg_passmail,
                body.login div#login p#registerform,
                body.login div#login #nav ,
                body.login div#login p.message,
                body.login div#login .forgetmenot,
                body.login div#login form .input, 
                body.login div#login input[type="text"] ,
                body.login div#login input[type="submit"] {
                    font-family:<?php echo esc_html(wip_custom_login_setting('wip_custom_login_font', 'Montserrat'));?>
                }

                body.login #loginform #jetpack-sso-wrap a,    
                body.login #loginform #jetpack-sso-wrap p,    
                body.login div#login p#reg_passmail,
                body.login div#login p#registerform,
                body.login div#login #registerform p.submit ,
                body.login div#login #lostpasswordform p.submit {
                    text-align:center;
                }
                
                body.login div#login #lostpasswordform p.submit {
                    margin-top:30px;
                }
                
                body.login div#login #registerform p.submit input ,
                body.login div#login #lostpasswordform p.submit input {
                    float:none;
                }

				@media screen and (min-width : 0px) and (max-width : 320px) {	

					body.login {
						padding-top:25px;
					}

					body.login  div#login {
						width:250px;
					}
					
				}

				@media screen and (min-width : 321px) and (max-width : 479px) {	

					body.login {
						padding-top:50px;
					}

					body.login  div#login {
						width:275px;
					}
					
				}

            </style>
		
        <?php
		
		}

	}

	new wip_custom_login_class();

}

?>