<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <?php /**
     * Class: wpe_Model_Shortcode
     * @author Flipper Code <hello@flippercode.com>
     * @version  2.0.0
     * @package  Maps
     */
    if ( ! class_exists('wpe_Model_Shortcode')) {
        /**
         * Shortcode model to display output on frontend.
         * @package  Maps
         * @author Flipper Code <hello@flippercode.com>
         */
        class wpe_Model_Shortcode extends FlipperCode_WPE_Model_Base
        {
            /**
             * Validations on prayers.
             * @var array
             */
			// public $validations = array(
			//	  'prayer_author_name' => array('req' => 'Please enter name.'),
            //    'prayer_author_email' => array('email' => 'Please enter valid email address.'),
            //    'prayer_messages' => array('req' => 'Please enter message.'),
            //  );
			
            /**
             * Intialize Shortcode object.
             */
            function __construct()
            {
                $this->table = WPE_TBL_PRAYER;
                $this->table_users = WPE_TBL_PRAYER_USERS;
                $this->unique = 'prayer_id';
            }

            /**
             * Add or Edit Operation.
             */
            public function save()
            {
                $entityID = '';
                if (isset($_REQUEST['_wpnonce'])) {
                    $nonce = sanitize_text_field($_REQUEST['_wpnonce']);
                }
                if ( !isset( $nonce ) || ! wp_verify_nonce($nonce, 'wpgmp-nonce')) {
                    die('Cheating...');
                }
                if (!empty(htmlspecialchars($_POST['honeypot']))) {echo '<script>window.history.replaceState( null, null, window.location.href );</script>';unset($_POST);}
                $option = unserialize(get_option('_wpe_prayer_engine_settings'));
                //  var_dump($_POST);
				$option['wpe_captcha']=(isset( $option['wpe_captcha'] ) and ! empty( $option['wpe_captcha'] )) ? $option['wpe_captcha'] : '';
                if (($option['wpe_captcha'] == 'true') || ($option['wpe_captcha'] != 'true')) {
						$num_found1= preg_match_all('/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/', $_POST['prayer_author_name'], $results1, PREG_PATTERN_ORDER);
						if (empty($_POST['prayer_author_name']) || !empty($num_found1) || strlen($_POST['prayer_author_name'])>'20') {$this->errors['prayer_author_name'] = __('Enter Name', WPE_TEXT_DOMAIN);}
	                    if($option['wpe_hide_email'] !=='true'){
                        if (!filter_var($_POST['prayer_author_email'], FILTER_VALIDATE_EMAIL)) {$this->errors['prayer_author_email'] = __('Email', WPE_TEXT_DOMAIN);}}
						$num_found= preg_match_all('/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/', $_POST['prayer_messages'], $results, PREG_PATTERN_ORDER);
						if (empty($_POST['prayer_messages']) || !empty($num_found)) {$this->errors['prayer_messages'] = __('Prayer Request', WPE_TEXT_DOMAIN);}
                            $lang=get_bloginfo("language");
                            $length_count = CountString($_POST['prayer_messages']);
                            $arr = array("en-US", "es-MX" , "fr-CA" , "pl_PL","es-ES","es-VE","ru-RU","sr-RS","es-EC","nl-NL");
                            if(in_array($lang, $arr) and $length_count< 3) {$this->errors['prayer_messages'] = __('Prayer Request',WPE_TEXT_DOMAIN);}
                            $this->verify($_POST);
                    if (isset($_POST['captcha_code'])) {
                        if (empty($_SESSION['captcha_code']) || strcasecmp($_SESSION['captcha_code'],
                                $_POST['captcha_code']) != 0) {
                            $this->errors['captcha_code'] = __('Please enter correct captcha value.', WPE_TEXT_DOMAIN);
                        }
                    }
					
                    if (is_array($this->errors) and ! empty($this->errors)) {
                        $this->throw_errors();
                    }
                    if (isset($_POST['entityID'])) {
                        $entityID = intval(sanitize_text_field($_POST['entityID']));
                    }
                    if (isset($_POST['prayer_messages'])) {
                        $data['prayer_messages'] = sanitize_textarea_field(stripslashes_deep( wp_encode_emoji($_POST['prayer_messages'])));
                    }

                    $data['prayer_title'] = $_SERVER['REMOTE_ADDR'];
                    $data['prayer_author'] = get_current_user_id();
                    $data['prayer_status'] = 'approved';
                    $lxt_options = get_option('_wpe_prayer_engine_settings');
                    $lxt_options = unserialize($lxt_options);
                    if ( ! empty($lxt_options) && array_key_exists('wpe_disapprove_prayer_default', $lxt_options)) {
                        $data['prayer_status'] = (filter_var($lxt_options['wpe_disapprove_prayer_default'],
                            FILTER_VALIDATE_BOOLEAN)) ? 'pending' : 'approved';
                    }
					if (isset($_POST['prayer_public'])) {$data['prayer_status'] = 'private';}
                    $data['prayer_author_email'] = (isset($_POST['prayer_author_email'])) ? sanitize_text_field($_POST['prayer_author_email']) : '';
                    $data['prayer_author_name'] = sanitize_text_field($_POST['prayer_author_name']);
                    if (isset($_POST['prayer_notify']) && ! empty($_POST['prayer_notify'])) {$data['prayer_lastname'] ='*';}

                    if (isset($_POST['prayer_country'])) {
                        $data['prayer_country'] = sanitize_text_field($_POST['prayer_country']);
                    }

                    if (isset($_POST['prayer_category'])) {
                        $categorykey = sanitize_text_field($_POST['prayer_category']);
                        $categorylist = (isset($lxt_options['wpe_categorylist']) and ! empty($lxt_options['wpe_categorylist'])) ? $lxt_options['wpe_categorylist'] : 'Deliverance,Generational Healing,Inner Healing,Physical Healing,Protection,Relationships,Salvation,Spiritual Healing';

                        $select_category = explode(",", $categorylist);
                        $data['prayer_category'] = $select_category[$categorykey];
                    }

                    $data['prayer_time'] = date('Y-m-d H:i:s');
                    $data['request_type'] = sanitize_text_field($_POST['request_type']);
                    if ($entityID > 0) {
                        $where[$this->unique] = $entityID;
                    } else {
                        $where = '';
                    }
                    $result = FlipperCode_Database::insert_or_update($this->table, $data, $where);
                    if (false === $result) {
                        $response['error'] = __('Something went wrong. Please try again.', WPE_TEXT_DOMAIN);
                    } elseif ($entityID > 0) {
                        $response['success'] = __('Prayer updated successfully', WPE_TEXT_DOMAIN);
                    } else {
                        $settings = unserialize(get_option('_wpe_prayer_engine_settings'));
                        if (isset($settings['wpe_send_email'])&& $settings['wpe_send_email'] == 'true'&& $option['wpe_hide_email'] !=='true') {
                            $headers = array('Content-Type: text/html; charset=UTF-8');
                            if ($data['prayer_author_email'] != '') {
                                $to = $data['prayer_author_email'];
                            } elseif ($data['prayer_author'] != '') {
                                $prayer_author_info = get_userdata($data['prayer_author']);
                                $to = $prayer_author_info->user_email;
                            }

                            // if(!empty($to))	{
                            // 	$subject = ($data['request_type']=="prayer_request") ? 'Prayer Request Submitted Successfully' : 'Praise Report Submitted Successfully';
                            // 	$body = 'Hello, <br> <p>Your ';
                            // 	$body .= ($data['request_type']=="prayer_request") ? 'prayer request' : 'praise report';
                            // 	$body .=' has been sent successfully with below details :</p>';
                            // 	$body .= '<b>Name :</b> '.$data['prayer_author_name'].'<br>';
                            // 	if($data['prayer_author_email']!='')
                            // 		$body .= '<b>Email :</b> '.$data['prayer_author_email'].'<br>';
                            // 	if($data['prayer_title']!='')
                            // 		$body .= '<b>Prayer Title :</b> '.$data['prayer_title'].'<br>';
                            // 	$body .= '<b>Prayer Message :</b> '.$data['prayer_messages'].'<br>';
                            // 	$body .= '<br>Thanks.';
                            // 	wp_mail( $to, $subject, $body, $headers );
                            // }
                            if ( ! empty($to)) {
                                $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                                add_filter('wp_mail_from', array($this, 'website_email'));
                                add_filter('wp_mail_from_name', array($this, 'website_name'));
                                //return $response['success']=$this->website_name('');
                                $body = '';
                                if ($data['request_type'] == 'prayer_request') {
                                    $subject = (isset($email_settings['wpe_email_req_subject']) and ! empty($email_settings['wpe_email_req_subject'])) ? $email_settings['wpe_email_req_subject'] : 'Prayer request confirmation';
                                    if (isset($email_settings['wpe_email_req_messages']) AND ! empty($email_settings['wpe_email_req_messages'])) {
                                        $body = stripslashes($email_settings['wpe_email_req_messages']);
                                        $body = str_replace(array(
                                            '{prayer_author_name}',
											'{prayer_messages}',
                                        ), array(
                                            $data['prayer_author_name'],
											$data['prayer_messages'],
                                        ), $body);
                                    } else {
                                        $body = 'Hello '.$data['prayer_author_name'].', <br> <p>Thank you for submitting your ';
                                        $body .= 'prayer request';
                                        $body .= '. We welcome all requests and we delight in lifting you and your requests up to God in prayer. God Bless you, and remember, God knows the prayers that are coming and hears them even before they are spoken.</p>';
										$body .= '<b>Request :</b> '.$data['prayer_messages'].'<br>';
										$body .= '<br>Blessings,<br/ >Prayer Team</p>';
                                        $link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
										$body .= '<br>'.$link1;
									}
                                } else {
                                    $subject = (isset($email_settings['wpe_email_praise_subject']) and ! empty($email_settings['wpe_email_praise_subject'])) ? $email_settings['wpe_email_praise_subject'] : 'Praise report confirmation';
                                    if (isset($email_settings['wpe_email_praise_messages']) AND ! empty($email_settings['wpe_email_praise_messages'])) {
                                        $body = stripslashes($email_settings['wpe_email_praise_messages']);
                                        $body = str_replace(array(
                                            '{prayer_author_name}',
											'{prayer_messages}',
                                        ), array(
                                            $data['prayer_author_name'],
											$data['prayer_messages'],
                                        ), $body);
                                    } else {
                                        $body = 'Hello '.$data['prayer_author_name'].', <br> <p>Thank you for submitting your ';
                                        $body .= 'praise report';
                                        $body .= '. We welcome all requests and we delight in lifting you and your requests up to God in prayer. God Bless you, and remember, God knows the prayers that are coming and hears them even before they are spoken.</p>';
										$body .= '<b>Praise :</b> '.$data['prayer_messages'].'<br>';
										$body .= '<br>Blessings,<br/ >Prayer Team</p>';
                                        $link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
										$body .= '<br>'.$link1;
									}
                                }
                                wp_mail($to, $subject, $body, $headers);
                            }
                        }
                        if (isset($settings['wpe_send_admin_email'])&&$settings['wpe_send_admin_email'] == 'true') {
                            if ($settings['wpe_send_email'] == 'false') {
                                add_filter('wp_mail_from', array($this, 'website_email'));
                            }
                            add_filter('wp_mail_from_name', array($this, 'website_name'));
                            $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                            $headers = array('Content-Type: text/html; charset=UTF-8');
                            $to_admin = (isset($email_settings['prayer_req_admin_email']) and ! empty($email_settings['prayer_req_admin_email'])) ? $email_settings['prayer_req_admin_email'] : get_option('admin_email');
							$to_cc1='';if(isset($email_settings['wpe_email_cc'])){$to_cc1=$email_settings['wpe_email_cc'];}
                            $to_cc = $to_admin.','.$to_cc1;
                            $to = $to_cc;
							if($data['request_type']=="prayer_request"){$request_type=__('Prayer Request', WPE_TEXT_DOMAIN);} else {$request_type=__('Praise Report', WPE_TEXT_DOMAIN);}
                            $subject = (isset($email_settings['wpe_email_admin_subject']) and ! empty($email_settings['wpe_email_admin_subject'])) ? $email_settings['wpe_email_admin_subject'] : 'New {request_type} received';
                            $subject = str_replace(array(
                                '{request_type}',
                            ), array(
                                $request_type,
                            ), $subject);
                            if (isset($email_settings['wpe_email_admin_messages']) AND ! empty($email_settings['wpe_email_admin_messages'])) {
                                $body = stripslashes($email_settings['wpe_email_admin_messages']);
                                $body = str_replace(array(
                                    '{prayer_author_name}',
                                    '{prayer_author_email}',
                                    '{prayer_messages}',
                                    '{request_type}',
                                ), array(
                                    $data['prayer_author_name'],
                                    $data['prayer_author_email'],           
                                    $data['prayer_messages'],
                                    $request_type,
                                ), $body);
                            } else {
                                $subject = ($data['request_type'] == "prayer_request") ? 'New Prayer Request Received To Moderate' : 'New Praise Report Received To Moderate';
                                $body = 'Hello, <br> <p>You have received a new ';
                                $body .= ($data['request_type'] == "prayer_request") ? 'prayer request' : 'praise report';
                                $body .= ' to moderate with following details :</p>';
                                $body .= '<b>Name :</b> '.$data['prayer_author_name'].'<br>';
                                if ($data['prayer_author_email'] != '') {
                                    $body .= '<b>Email :</b> '.$data['prayer_author_email'].'<br>';
                                }
                                $body .= '<b>Request :</b> '.$data['prayer_messages'].'<br>';
                                $body .= '<br>Thank you';
                                $link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
								$body .= '<br>'.$link1;
                            }
                            wp_mail($to, $subject, $body, $headers);
                        }
                            $settings = unserialize(get_option('_wpe_prayer_engine_settings'));
                            if (isset($settings['wpe_thankyou']) AND ! empty($settings['wpe_thankyou'])) {
                                $response['success'] = stripslashes($settings['wpe_thankyou']);
                            } else {
                                $response['success'] = __('Thank you. Your form has been received.', WPE_TEXT_DOMAIN);}

                        // Insert user table log on form success

                        global $wpdb;
                        $user_data['prayer_id'] = $wpdb->insert_id;
                        $user_data['user_id'] = get_current_user_id();
                        $user_data['user_ip'] = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
                        $user_data['prayer_time'] = $data['prayer_time'];

                        $result = FlipperCode_Database::insert_or_update($this->table_users, $user_data, $where);
						echo '<script>window.history.replaceState( null, null, window.location.href );</script>';unset($_POST);
						


                    }

                }
                if (isset($_REQUEST['g-recaptcha-response']) && ! empty($_REQUEST['g-recaptcha-response'])) {
                //
                // {
                //       $captcha = $_POST['g-recaptcha-response'];
                //     } else {
                //       $captcha = false;
                //     }
                //
                //     if (!$captcha) {
                //       echo "error";
                //     } else {
                  //  }
                    //your site secret key
                    $option = unserialize(get_option('_wpe_prayer_engine_settings'));
                    $secret = $option['wpe_prayer_secret_key'];//get_option('upr_prayer_secret');
                    
                    //recaptcha_response=$Post['recaptcha_response'];

                    $token= sanitize_text_field($_POST['token']);
                    $action =  sanitize_text_field($_POST['submit']);


                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
                    

                    curl_close($curl);
                    $responseData = json_decode($curlData, true);
//
                    // if (isset($_POST['g-recaptcha-response'])) {
                    //     $captcha = $_POST['g-recaptcha-response'];
                    // } else {
                    //     $captcha = false;
                    // }
                    //
                    // if (!$captcha) {
                    //     //Do something with error
                    // } else {
                    //   $option = unserialize(get_option('_wpe_prayer_engine_settings'));
                    //     $secret   = $option['wpe_prayer_secret_key'];
                    //     $response = file_get_contents(
                    //         "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']
                    //     );
                    //     // use json_decode to extract json response
                    //     $response = json_decode($response);
                    //
                    //     if ($response->success === false) {
                    //       echo "couldn't verify recaptcha";
                    //     }
                    // }
                    // if ($response->success==true && $response->score <= 0.5) {
                    // }

                        if ( ! $responseData['success'] == 'true') {
                        $response['error'] = __('Please enter correct captcha value.');

                    } else {                        
                        $this->verify($_POST);
                        if (isset($_POST['captcha_code'])) {
                            if (empty($_SESSION['captcha_code']) || strcasecmp($_SESSION['captcha_code'],
                                    $_POST['captcha_code']) != 0) {
                                $this->errors['captcha_code'] = __('Please enter correct captcha value.',
                                    WPE_TEXT_DOMAIN);
                            }
                        }

                        if (is_array($this->errors) and ! empty($this->errors)) {
                            $this->throw_errors();
                        }
                        if (isset($_POST['entityID'])) {
                            $entityID = intval(sanitize_text_field($_POST['entityID']));
                        }
                        if (isset($_POST['prayer_messages'])) {
                            $data['prayer_messages'] = sanitize_textarea_field($_POST['prayer_messages']);
                        }
                        $data['prayer_title'] = $_SERVER['REMOTE_ADDR'];
                        $data['prayer_author'] = get_current_user_id();
                        $data['prayer_status'] = 'approved';
                        $lxt_options = get_option('_wpe_prayer_engine_settings');
                        $lxt_options = unserialize($lxt_options);
                        if ( ! empty($lxt_options) && array_key_exists('wpe_disapprove_prayer_default', $lxt_options)) {
                            $data['prayer_status'] = (filter_var($lxt_options['wpe_disapprove_prayer_default'],
                                FILTER_VALIDATE_BOOLEAN)) ? 'pending' : 'approved';
                        }
                        $data['prayer_author_email'] = (isset($_POST['prayer_author_email'])) ? sanitize_email($_POST['prayer_author_email']) : '';
                        $data['prayer_author_name'] = sanitize_text_field($_POST['prayer_author_name']);

                        if (isset($_POST['prayer_country'])) {
                            $data['prayer_country'] = sanitize_text_field($_POST['prayer_country']);
                        }

                        if (isset($_POST['prayer_category'])) {
                            $categorykey = sanitize_text_field($_POST['prayer_category']);
                            $categorylist = (isset($lxt_options['wpe_categorylist']) and ! empty($lxt_options['wpe_categorylist'])) ? $lxt_options['wpe_categorylist'] : 'Deliverance,Generational Healing,Inner Healing,Physical Healing,Protection,Relationships,Salvation,Spiritual Healing';

                            $select_category = explode(",", $categorylist);
                            $data['prayer_category'] = $select_category[$categorykey];
                        }

                        $data['prayer_time'] = date('Y-m-d H:i:s');
                        $data['request_type'] = sanitize_text_field($_POST['request_type']);
                        if ($entityID > 0) {
                            $where[$this->unique] = $entityID;
                        } else {
                            $where = '';
                        }
                        $result = FlipperCode_Database::insert_or_update($this->table, $data, $where);
                        if (false === $result) {
                            $response['error'] = __('Something went wrong. Please try again.', WPE_TEXT_DOMAIN);
                        } elseif ($entityID > 0) {
                            $response['success'] = __('Prayer updated successfully', WPE_TEXT_DOMAIN);
                        } else {
                            $settings = unserialize(get_option('_wpe_prayer_engine_settings'));
                            if ($settings['wpe_send_email'] == 'true' && $option['wpe_hide_email'] !=='true') {
                                $headers = array('Content-Type: text/html; charset=UTF-8');
                                if ($data['prayer_author_email'] != '') {
                                    $to = $data['prayer_author_email'];
                                } elseif ($data['prayer_author'] != '') {
                                    $prayer_author_info = get_userdata($data['prayer_author']);
                                    $to = $prayer_author_info->user_email;
                                }
                                // if(!empty($to))	{
                                // 	$subject = ($data['request_type']=="prayer_request") ? 'Prayer Request Submitted Successfully' : 'Praise Report Submitted Successfully';
                                // 	$body = 'Hello, <br> <p>Your ';
                                // 	$body .= ($data['request_type']=="prayer_request") ? 'prayer request' : 'praise report';
                                // 	$body .=' has been sent successfully with below details :</p>';
                                // 	$body .= '<b>Name :</b> '.$data['prayer_author_name'].'<br>';
                                // 	if($data['prayer_author_email']!='')
                                // 		$body .= '<b>Email :</b> '.$data['prayer_author_email'].'<br>';
                                // 	if($data['prayer_title']!='')
                                // 		$body .= '<b>Prayer Title :</b> '.$data['prayer_title'].'<br>';
                                // 	$body .= '<b>Prayer Message :</b> '.$data['prayer_messages'].'<br>';
                                // 	$body .= '<br>Thanks.';
                                // 	wp_mail( $to, $subject, $body, $headers );
                                // }
                                if ( ! empty($to)) {
                                    $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                                    add_filter('wp_mail_from', array($this, 'website_email'));
                                    add_filter('wp_mail_from_name', array($this, 'website_name'));
                                    //return $response['success']=$this->website_name('');
                                    $body = '';
                                    if ($data['request_type'] == 'prayer_request') {
                                        $subject = (isset($email_settings['wpe_email_req_subject']) and ! empty($email_settings['wpe_email_req_subject'])) ? $email_settings['wpe_email_req_subject'] : 'Prayer request confirmation';
                                        if (isset($email_settings['wpe_email_req_messages']) AND ! empty($email_settings['wpe_email_req_messages'])) {
                                            $body = stripslashes($email_settings['wpe_email_req_messages']);
                                            $body = str_replace(array(
                                                '{prayer_author_name}',
                                            ), array(
                                                $data['prayer_author_name'],
                                            ), $body);
                                        } else {
                                            $body = 'Hello '.$data['prayer_author_name'].', <br> <p>Thank you for submitting your ';
                                            $body .= 'prayer request';
                                            $body .= '. We welcome all requests and we delight in lifting you and your requests up to God in prayer. God Bless you, and remember, God knows the prayers that are coming and hears them even before they are spoken.<br /><br />Blessings,<br/ >Prayer Team</p>';
                                            $link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
                                            $body .= '<br>'.$link1;
                                        }
                                    } else {
                                        $subject = (isset($email_settings['wpe_email_praise_subject']) and ! empty($email_settings['wpe_email_praise_subject'])) ? $email_settings['wpe_email_praise_subject'] : 'Praise report confirmation';
                                        if (isset($email_settings['wpe_email_praise_messages']) AND ! empty($email_settings['wpe_email_praise_messages'])) {
                                            $body = stripslashes($email_settings['wpe_email_praise_messages']);
                                            $body = str_replace(array(
                                                '{prayer_author_name}',
                                            ), array(
                                                $data['prayer_author_name'],
                                            ), $body);
                                        } else {
                                            $body = 'Hello '.$data['prayer_author_name'].', <br> <p>Thank you for submitting your ';
                                            $body .= 'praise report';
                                            $body .= '. We welcome all requests and we delight in lifting you and your requests up to God in prayer. God Bless you, and remember, God knows the prayers that are coming and hears them even before they are spoken.<br /><br />Blessings,<br/ >Prayer Team</p>';
                                            $link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
                                            $body .= '<br>'.$link1;
                                        }
                                    }
                                    wp_mail($to, $subject, $body, $headers);
                                }
                            }
                            if ($settings['wpe_send_admin_email'] == 'true') {
                                if ($settings['wpe_send_email'] == 'false') {
                                    add_filter('wp_mail_from', array($this, 'website_email'));
                                }
                                add_filter('wp_mail_from_name', array($this, 'website_name'));
                                $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                                $headers = array('Content-Type: text/html; charset=UTF-8');
                                $to_admin = (isset($email_settings['prayer_req_admin_email']) and ! empty($email_settings['prayer_req_admin_email'])) ? $email_settings['prayer_req_admin_email'] : get_option('admin_email');
								$to_cc1='';if(isset($email_settings['wpe_email_cc'])){$to_cc1=$email_settings['wpe_email_cc'];}
								$to_cc = $to_admin.','.$to_cc1;
								if($data['request_type']=="prayer_request"){$request_type=__('Prayer Request', WPE_TEXT_DOMAIN);} else {$request_type=__('Praise Report', WPE_TEXT_DOMAIN);}
                                $subject = (isset($email_settings['wpe_email_admin_subject']) and ! empty($email_settings['wpe_email_admin_subject'])) ? $email_settings['wpe_email_admin_subject'] : 'New {request_type} received';
                                $subject = str_replace(array(
                                    '{request_type}',
                                ), array(
                                    $request_type,
                                ), $subject);
                                if (isset($email_settings['wpe_email_admin_messages']) AND ! empty($email_settings['wpe_email_admin_messages'])) {
                                    $body = stripslashes($email_settings['wpe_email_admin_messages']);
                                    $body = str_replace(array(
                                        '{prayer_author_name}',
                                        '{prayer_author_email}',
                                        '{prayer_messages}',
                                        '{request_type}',
                                    ), array(
                                        $data['prayer_author_name'],
                                        $data['prayer_author_email'],
                                        $data['prayer_messages'],
                                        $request_type,
                                    ), $body);
                                } else {
                                    $subject = ($data['request_type'] == "prayer_request") ? 'New Prayer Request Received To Moderate' : 'New Praise Report Received To Moderate';
                                    $body = 'Hello, <br> <p>You have received a new ';
                                    $body .= ($data['request_type'] == "prayer_request") ? 'prayer request' : 'praise report';
                                    $body .= ' to moderate with following details :</p>';
                                    $body .= '<b>Name :</b> '.$data['prayer_author_name'].'<br>';
                                    if ($data['prayer_author_email'] != '') {
                                        $body .= '<b>Email :</b> '.$data['prayer_author_email'].'<br>';
                                    }
                                    $body .= '<b>Request :</b> '.$data['prayer_messages'].'<br>';
                                    $body .= '<br>Thank you';
                                    $link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
									$body .= '<br>'.$link1;
                                }
                                wp_mail($email_settings['prayer_req_admin_email'], $subject, $body, $headers);
                            }
                            $settings = unserialize(get_option('_wpe_prayer_engine_settings'));
                            if (isset($settings['wpe_thankyou']) AND ! empty($settings['wpe_thankyou'])) {
                                $response['success'] = stripslashes($settings['wpe_thankyou']);
                            } else {
                                $response['success'] = __('Thank you. Your form has been received.', WPE_TEXT_DOMAIN);}
                        }
                    }
                }

                return $response;
            }

            public function website_email($sender)
            {
                $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                $sitename = strtolower($_SERVER['SERVER_NAME']);
                if (substr($sitename, 0, 4) == 'www.') {
                    $sitename = substr($sitename, 4);
                }
                $illegal_chars_username = array('(', ')', '<', '>', ',', ';', ':', '\\', '"', '[', ']', '@', "'", ' ');
                $username = str_replace($illegal_chars_username, "", get_option('blogname'));
                $sender_emailuser = (isset($email_settings['wpe_email_user']) and ! empty($email_settings['wpe_email_user'])) ? $email_settings['wpe_email_user'] : $username.'@'.$sitename;
                $sender_email = $sender_emailuser;

                return $sender_email;
            }

            public function website_name($name)
            {
                $email_settings = unserialize(get_option('_wpe_prayer_engine_email_settings'));
                $site_name = (isset($email_settings['wpe_email_from']) and ! empty($email_settings['wpe_email_from'])) ? $email_settings['wpe_email_from'] : get_option('blogname');

                return $site_name;
            }
        }
	    function Clearstring(string $string)
	    {
		    $string = strtolower($string);
		    $string = preg_replace('/[,.!;?]/',"",$string);
		    return $string;
	    }

	    function CountString($string){
		    $textsplit = preg_split("/\s/",$string);
		    $wordsArray = [];
		    $array= [];
		    $counter = 0;
		    foreach($textsplit as $word){
			    try{
				    $word = Clearstring($word);
				    if(in_array($word,$array))
				    {
					    if(in_array($wordsArray[$word],$wordsArray))
					    {
						    $wordsArray[$word] +=1;
					    }
				    }
				    else{
					    array_push($array,$word);
					    $counter =0;
					    $wordsArray[$word] = $counter;
					    if(in_array($wordsArray[$word],$wordsArray))
					    {
						    $wordsArray[$word] +=1;
					    }
				    }
			    }catch(Exception $ex){
				    echo "*".$ex;
			    }
		    }
		    $countwords = count($wordsArray);
		    return $countwords;
	    }
    }

    ?>
</head>
<body>
</body>
</html>