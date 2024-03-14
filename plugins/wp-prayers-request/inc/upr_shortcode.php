<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/



if ( ! defined('ABSPATH')) {
    die('Nope, not accessing this');
} // Exit if accessed directly
//defines the functionality for the location shortcode


class upr_shortcode
{

    //on initialize
    public function __construct()
    {
        if ( ! is_admin())  //Don't register if it's admin panel
        {
            add_action('init', array($this, 'register_upr_shortcodes'));
            
        } //shortcodes
    }

    //location shortcode
    public function register_upr_shortcodes()
    {
    
    	
        add_shortcode('upr_form', array($this, 'upr_form_shortcode_output'));
         
        add_shortcode('upr_list_prayers', array($this, 'upr_list_prayers_shortcode_output'));
        /* die('dead'); */
        
        
    }

    public function upr_website_email($sender)
    {
        //$prayer_email_user = unserialize(get_option('prayer_email_user'));
        $prayer_email_user =  get_option('prayer_email_user');
        $sitename = strtolower($_SERVER['SERVER_NAME']);
        if (substr($sitename, 0, 4) == 'www.') {
        $sitename = substr($sitename, 4);
        }
        $illegal_chars_username = array('(', ')', '<', '>', ',', ';', ':', '\\', '"', '[', ']', '@', "'", ' ');
        $username = str_replace($illegal_chars_username, "", get_option('blogname'));
        $sender_emailuser = (isset($prayer_email_user) and ! empty($prayer_email_user)) ? $prayer_email_user : $username.'@'.$sitename;
        $sender_email = $sender_emailuser;

        return $sender_email;
    }

    public function upr_website_name($name)
    {
        //$prayer_email_from = unserialize(get_option('prayer_email_from'));
        $prayer_email_from= get_option('prayer_email_from');
        $site_name = (isset($prayer_email_from) and ! empty($prayer_email_from)) ? $prayer_email_from : get_option('blogname');

        return $site_name;
    }

  function custom_mails($args)
  {


    $copy_to = get_option('prayer_admin_email_cc');

    $copy_to = explode(',', $copy_to);
    foreach ($copy_to as $cc_email) {
      if ( ! empty($cc_email)) {
        if (is_array($args['headers']))
        {

          $args['headers'][] = 'cc: '.$cc_email;
        }
        else
        {

          $args['headers'] .= 'cc: '.$cc_email."\r\n";
        }
      }
    }
    return $args;
  }

  //shortcode display
    public function upr_form_shortcode_output($atts,$tag,$content = '')
    {
	if (isset($_REQUEST['prayer_messages'])) {$prayermessage= sanitize_textarea_field($_REQUEST['prayer_messages']);}
	if (isset($_REQUEST['prayer_author_name'])) {$prayername= sanitize_text_field($_REQUEST['prayer_author_name']);}
	if (isset($_REQUEST['prayer_author_email'])) {$prayeremail= sanitize_email($_REQUEST['prayer_author_email']);}
	$praybutton = array();$praybutton['pray_t'] = __('Pray','wp-prayers-request');$praybutton1=$praybutton['pray_t'];
      $upr_prayer_hide_captcha = get_option('upr_prayer_hide_captcha');
      //die($upr_prayer_hide_captcha);
        if ((isset($_REQUEST['g-recaptcha-response']) && ! empty($_REQUEST['g-recaptcha-response'])) || $upr_prayer_hide_captcha !=1) {
            //your site secret key
            if($upr_prayer_hide_captcha==1) {
              $secret = get_option('upr_prayer_secret');

              $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_REQUEST['g-recaptcha-response'];
              $curl = curl_init();
              curl_setopt($curl, CURLOPT_URL, $url);
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($curl, CURLOPT_TIMEOUT, 15);
              curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
              curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
              $curlData = curl_exec($curl);

              curl_close($curl);

              $responseData = json_decode($curlData, true);
            }else{
              $responseData['success'] =true;
            }
            if ( $responseData['success'] != 'true') {
                 //$message = '<div class="col-md-11 alert-success" style="color:#F00">Sorry, the re-Captcha security key could not be verified. Please enter a valid security key.</div>';

            } else {
                if (isset($_REQUEST['save_prayer_data'])) {

                    // Create post object
                    $message = "";$message1 = "";$message2 = "";$message3 = "";
                    if (isset($_REQUEST['captcha_code'])) {
                        if ($_REQUEST['captcha_code'] != $_SESSION['captcha_code']) {
                            $message = '<div class="col-md-11 alert-message" style="color:#F00">Please enter correct captcha value.</div>';
                        }
                    }
                        $num_found1= preg_match_all('/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/', $_REQUEST['prayer_author_name'], $results, PREG_PATTERN_ORDER);
						if (empty($_REQUEST['prayer_author_name']) || !empty($num_found1)|| strlen($_REQUEST['prayer_author_name'])>'20') {$message1 = '<div class="col-md-11 alert-message">'.__('This field is required','wp-prayers-request').': '.__('Name','wp-prayers-request').'</div>';}
						$num_found= preg_match_all('/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/', $_REQUEST['prayer_messages'], $results, PREG_PATTERN_ORDER);
						if (empty($_REQUEST['prayer_messages']) || !empty($num_found) ) {$message2 = '<div class="col-md-11 alert-message">'.__('This field is required','wp-prayers-request').': '.__('Prayer Request','wp-prayers-request').'</div>';}
						$upr_prayer_req_email = get_option('upr_prayer_req_email');
						if($upr_prayer_req_email == 0) {
						if (!filter_var($_POST['prayer_author_email'], FILTER_VALIDATE_EMAIL)) {$message3 = '<div class="col-md-11 alert-message">'.__('This field is required','wp-prayers-request').': '.__('Email','wp-prayers-request').'</div>';}
	                    }
						$message = $message1.$message2.$message3;
						if (empty($message)) {
                        $post_status = 'publish';
                        $upr_prayer_default_status_pending = get_option('upr_prayer_default_status_pending');
                        if ($upr_prayer_default_status_pending == 1) {
                            $post_status = 'pending';
                        }
						if (isset($_REQUEST['prayer_public'])) {$post_status = 'private';}
						$upr_prayer_send_email = get_option('upr_prayer_send_email');
                        $prayer_post = array(
                            'post_title' => sanitize_text_field(implode(' ', array_slice(explode(' ', $_REQUEST['prayer_messages']), 0, 3))),
                            'post_content' => sanitize_textarea_field($_REQUEST['prayer_messages']),
                            'post_type' => 'prayers',
                            'post_status' => $post_status,
                        );
                        // Insert the post into the database
                        $prayer_id = wp_insert_post($prayer_post);
                        $praylink = get_permalink($prayer_id);
                        $upr_prayer_send_email = get_option('upr_prayer_send_email');
                        $upr_prayer_send_admin_email = get_option('upr_prayer_send_admin_email');
                        if ($prayer_id) {
                            if (isset($_REQUEST['prayer_author_category'])) {
                                $category = $_REQUEST['prayer_author_category'];
                                wp_set_post_terms($prayer_id, array($category), 'prayertypes', false);
                            }
                            update_post_meta($prayer_id, 'prayers_name', sanitize_text_field($_REQUEST['prayer_author_name']));
                            update_post_meta($prayer_id, 'prayers_email', sanitize_email($_REQUEST['prayer_author_email']));
                            if (isset($_REQUEST['prayer_author_country'])) {
                                update_post_meta($prayer_id, 'prayers_country', sanitize_text_field($_REQUEST['prayer_author_country']));
                            }
                            $to = sanitize_email($_REQUEST['prayer_author_email']);

                           $prayer_email_from = get_option('prayer_email_from');
                           $prayer_email_user = get_option('prayer_email_user');




                            $headers = 'MIME-Version: 1.0'."\r\n";
                            $headers .= 'Content-Type: text/html; charset=UTF-8'."\r\n";
							$upr_prayer_req_email = get_option('upr_prayer_req_email');
                            if ($upr_prayer_send_email == 1 && $upr_prayer_req_email == 0) {
                                if ( ! empty($_REQUEST['prayer_author_email'])) {
                                    add_filter('wp_mail_from', array($this, 'upr_website_email'));
                                    add_filter('wp_mail_from_name', array($this, 'upr_website_name'));
                                    $body = '';
                                    $prayer_email_req_subject = get_option('prayer_email_req_subject');
                                    if ( ! empty($prayer_email_req_subject)) {
                                        $subject = $prayer_email_req_subject;
                                    } else {
                                        $subject = 'Prayer request confirmation';
                                    }
                                    $prayer_email_req_messages = get_option('prayer_email_req_messages');
                                    if ( ! empty($prayer_email_req_messages)) {
                                        $body = nl2br($prayer_email_req_messages);
                                        $body = str_replace(array('{prayer_author_name}','{prayer_messages}',),
                                            array(sanitize_text_field($_REQUEST['prayer_author_name']),sanitize_textarea_field(stripslashes_deep($_REQUEST['prayer_messages']))), $body);
										
                                    } else {
                                        $body = 'Hello '.sanitize_text_field($_REQUEST['prayer_author_name']).', <br> <p>Thank you for submitting your prayer request. ';
                                        $body .= 'We welcome all requests and we delight in lifting you and your requests up to God in prayer. ';
                                        $body .= 'God Bless you, and remember, God knows the prayers that are coming and hears them even before they are spoken.<br /><br />';
										$body .= '<b>Request :</b> '.sanitize_textarea_field($_REQUEST['prayer_messages']).'<br>';
                                       
                                        $body .= '<br />Blessings,<br/ >Prayer Team';
										$link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
										$body .= '<br>'.$link1;							
                                    }

                                    wp_mail($to, $subject, $body, $headers);

                                }
                            }
                            
                            if ($upr_prayer_send_admin_email == 1) {
                                //$upr_prayer_req_admin_email = get_option('upr_prayer_req_admin_email');
                                $upr_prayer_req_admin_email = get_option('prayer_req_admin_email');

                                if ( isset($upr_prayer_req_admin_email) && $upr_prayer_req_admin_email!='') {
                                    $to_admin = $upr_prayer_req_admin_email;
                                } else {
                                    $to_admin = get_option('admin_email');
                                }
                                if ( ! empty($to_admin)) {
                                    add_filter('wp_mail_from', array($this, 'upr_website_email'));
                                    add_filter('wp_mail_from_name', array($this, 'upr_website_name'));
                                    add_filter('wp_mail',array($this,'custom_mails'), 10,1);
                                    /*$copy_to = get_option('prayer_admin_email_cc');
                                    $copy_to = explode(',', $copy_to);
                                    foreach ($copy_to as $email) {
                                        if ( ! empty($email)) {
                                            $headers .= '\r\n'.'cc: '.$email;
                                        }
                                    }*/
                                    $body = '';
                                    $prayer_email_admin_subject = get_option('prayer_email_admin_subject');
                                    if ( ! empty($prayer_email_admin_subject)) {
                                        $subject = $prayer_email_admin_subject;
                                    } else {
                                        $subject = 'New {request_type} received';
                                    }
                                    $subject = str_replace(array('{request_type}'), array('Prayer Request'), $subject);
                                    //todo:: $data array missing
//							$prayer_author_info = get_userdata($data['prayer_author']);
//							$to = $prayer_author_info->user_email;
                                    $prayer_email_admin_messages = get_option('prayer_email_admin_messages');

                                    if ( ! empty($prayer_email_admin_messages)) {
                                        $body = nl2br($prayer_email_admin_messages);
                                        $body = str_replace(array(
                                            '{prayer_author_name}',
                                            '{prayer_author_email}',
											'{prayer_title}',
                                            '{prayer_messages}',
                                            '{prayer_author_info}',
                                            '{request_type}',
                                        ), array(
                                            sanitize_text_field($_REQUEST['prayer_author_name']),
                                            sanitize_email($_REQUEST['prayer_author_email']),
                                            implode(' ', array_slice(explode(' ', sanitize_text_field(stripslashes_deep($_REQUEST['prayer_messages']))), 0, 3)),
											sanitize_textarea_field(stripslashes_deep($_REQUEST['prayer_messages'])),
                                            sanitize_text_field($_REQUEST['prayer_author_name']),
                                            'Prayer Request',
                                        ), $body);
										
                                    } else {
                                        $prayer_email_admin_subject = get_option('prayer_email_admin_subject');
                                        if ( ! empty($prayer_email_admin_subject)) {
                                            $subject = $prayer_email_admin_subject;
                                        } else {
                                            $subject = 'New Prayer Request Received To Moderate';
                                        }
                                        // $headers  .= 'MIME-Version: 1.0' . "\r\n";
                                        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                                        $body = 'Hello, <br> <p>You have received a new ';
                                        $body .= 'prayer request';
                                        $body .= ' to moderate with following details :</p>';
                                        $body .= '<b>Name :</b> '.sanitize_text_field($_REQUEST['prayer_author_name']).'<br>';
                                        //todo:: $data array missing
										if ( ! empty($_REQUEST['prayer_author_email'])) {
										$body .= '<b>Email :</b> '.sanitize_email($_REQUEST['prayer_author_email']).'<br>';}
                                     
                                        $body .= '<b>Request :</b> '.sanitize_textarea_field(stripslashes_deep($_REQUEST['prayer_messages'])).'<br>';
                                        //$body .= '<a href="'.$praylink.'">'.$praylink.'</a><br>';
                                        $body .= '<br>Thank you';
										$link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
										$body .= '<br>'.$link1;
                                    }
                                    wp_mail($to_admin, $subject, $body, $headers);
                                }
                            }
                            $upr_prayer_thankyou = get_option('upr_prayer_thankyou');
                            if ( ! empty($upr_prayer_thankyou)) {
                            $message = '<div class="col-md-11 alert-success">'.$upr_prayer_thankyou.'</div>';} else {
                            $message = '<div class="col-md-11 alert-success">'.__('Thank you. Your form has been received','wp-prayers-request').'</div>';}
							echo '<script>window.history.replaceState( null, null, window.location.href );</script>';$prayername="";$prayeremail="";$prayermessage="";
                        }
                    }
                    unset($_REQUEST);
                }
            }

        } else {
           // $message = '<div class="col-md-11 alert-success" style="color:#F00">Invalid captcha</div>';
        }
        ob_start();?>
        <form id="form_gc" method="post" action="">
            <div class="wpgmp-frontend lxt-kewal">
                <?php 
                $showform = false;
                $upr_show_prayer_category = get_option('upr_show_prayer_category');
                $upr_prayer_show_country = get_option('upr_prayer_show_country');
				$upr_prayer_share = get_option('upr_prayer_share');
				$upr_prayer_req_email = get_option('upr_prayer_req_email');
                $upr_prayer_hide_captcha = get_option('upr_prayer_hide_captcha');
                if(!isset($upr_prayer_hide_captcha) || $upr_prayer_hide_captcha =='' ) $upr_prayer_hide_captcha=0;
                $current_url = get_permalink(get_the_ID());
                if (isset($message)) {
                    echo $message;echo '<script>window.history.replaceState( null, null, window.location.href );</script>';
                }
                $upr_login_not_required_request = get_option('upr_login_not_required_request');
                if ($upr_login_not_required_request != 1) {
                    if (is_user_logged_in()) {
                        $showform = true;
                    }
                } else {
                    $showform = true;
                }
                if ($showform) { ?>
                    <div class="form-group ">
                        <div class="col-md-3"><label for="prayer_author_name"><?php _e('Name',
                                    'wp-prayers-request'); ?></label><span class="inline-star">*</span></div>
                        <div class="col-md-8"><input name="prayer_author_name" id="prayer_author_name"
                    class="form-control" type="text" value="<?php if (isset($prayername)) {echo htmlspecialchars($prayername);}?>"
                            <p class="help-block" id="prayer_author_name_error" maxlength="20"></p></div>
                    </div>
				<?php if ($upr_prayer_req_email == 0): ?>	
                    <div class="form-group ">
                        <div class="col-md-3"><label for="prayer_author_email"><?php _e('Email',
                                    'wp-prayers-request'); ?></label><span class="inline-star">*</span></div>
                        <div class="col-md-8"><input name="prayer_author_email" id="prayer_author_email" maxlength="30"
                        class="form-control" type="email" value="<?php if (isset($prayeremail)) {echo htmlspecialchars($prayeremail);}?>">
                            <p class="help-block" id="prayer_author_email_error"><?php _e('This field is required',
                                    'wp-prayers-request') ?></p></div>
                    </div>
					<?php endif; ?>
                <?php if ($upr_show_prayer_category == 1): ?>
                    <div class="form-group ">
                        <?php
                        $args = array(
                            'option_none_value' => '-1',
                            'orderby' => 'ID',
                            'order' => 'ASC',
                            'show_count' => 0,
                            'hide_empty' => 0,
                            'child_of' => 0,
                            'echo' => 1,
                            'selected' => 0,
                            'hierarchical' => 0,
                            'name' => 'prayer_author_category',
                            'depth' => 0,
                            'tab_index' => 0,
                            'taxonomy' => 'prayertypes',
                            'hide_if_empty' => false,
                            'value_field' => 'term_id',
                        ); ?>
                        <div class="col-md-3"><label for="prayer_author_category"><?php _e('Category',
                                    'wp-prayers-request') ?></label></div>
                        <div class="col-md-8"><?php wp_dropdown_categories($args); ?></div>
                    </div>
                <?php endif;
                if ($upr_prayer_show_country == 1):
                $select_country = array(
					"US"=> "United States",
                    "AF"=> "Afghanistan",
					"AL"=> "Albania",
					"DZ"=> "Algeria",
					"AS"=> "American Samoa",
					"AD"=> "Andorra",
					"AO"=> "Angola",
					"AI"=> "Anguilla",
					"AQ"=> "Antarctica",
					"AG"=> "Antigua & Barbuda",
					"AR"=> "Argentina",
					"AM"=> "Armenia",
					"AW"=> "Aruba",
					"AU"=> "Australia",
					"AT"=> "Austria",
					"AZ"=> "Azerbaijan",
					"BS"=> "Bahamas",
					"BH"=> "Bahrain",
					"BD"=> "Bangladesh",
					"BB"=> "Barbados",
					"BY"=> "Belarus",
					"BE"=> "Belgium",
					"BZ"=> "Belize",
					"BJ"=> "Benin",
					"BM"=> "Bermuda",
					"BT"=> "Bhutan",
					"BO"=> "Bolivia",
					"BQ"=> "Bonaire",
					"BA"=> "Bosnia & Herzegovina",
					"BW"=> "Botswana",
					"BV"=> "Bouvet Island",
					"BR"=> "Brazil",
					"IO"=> "British Indian Ocean",
					"BN"=> "Brunei Darussalam",
					"BG"=> "Bulgaria",
					"BF"=> "Burkina Faso",
					"BI"=> "Burundi",
					"KH"=> "Cambodia",
					"CM"=> "Cameroon",
					"CA"=> "Canada",
					"CV"=> "Cape Verde",
					"KY"=> "Cayman Islands",
					"CF"=> "Central African Rep",
					"TD"=> "Chad",
					"CL"=> "Chile",
					"CN"=> "China",
					"CX"=> "Christmas Island",
					"CC"=> "Cocos Islands",
					"CO"=> "Colombia",
					"KM"=> "Comoros",
					"CG"=> "Congo",
					"CD"=> "Democratic Rep Congo",
					"CK"=> "Cook Islands",
					"CR"=> "Costa Rica",
					"HR"=> "Croatia",
					"CU"=> "Cuba",
					"CW"=> "Curacao",
					"CY"=> "Cyprus",
					"CZ"=> "Czech Republic",
					"CI"=> "Cote d'Ivoire",
					"DK"=> "Denmark",
					"DJ"=> "Djibouti",
					"DM"=> "Dominica",
					"DO"=> "Dominican Republic",
					"EC"=> "Ecuador",
					"EG"=> "Egypt",
					"SV"=> "El Salvador",
					"GQ"=> "Equatorial Guinea",
					"ER"=> "Eritrea",
					"EE"=> "Estonia",
					"ET"=> "Ethiopia",
					"FK"=> "Falkland Islands",
					"FO"=> "Faroe Islands",
					"FJ"=> "Fiji",
					"FI"=> "Finland",
					"FR"=> "France",
					"GF"=> "French Guiana",
					"PF"=> "French Polynesia",
					"GA"=> "Gabon",
					"GM"=> "Gambia",
					"GE"=> "Georgia",
					"DE"=> "Germany",
					"GH"=> "Ghana",
					"GI"=> "Gibraltar",
					"GR"=> "Greece",
					"GL"=> "Greenland",
					"GD"=> "Grenada",
					"GP"=> "Guadeloupe",
					"GU"=> "Guam",
					"GT"=> "Guatemala",
					"GG"=> "Guernsey",
					"GN"=> "Guinea",
					"GW"=> "Guinea-Bissau",
					"GY"=> "Guyana",
					"HT"=> "Haiti",
					"VA"=> "Holy See",
					"HN"=> "Honduras",
					"HK"=> "Hong Kong",
					"HU"=> "Hungary",
					"IS"=> "Iceland",
					"IN"=> "India",
					"ID"=> "Indonesia",
					"IR"=> "Iran",
					"IQ"=> "Iraq",
					"IE"=> "Ireland",
					"IM"=> "Isle of Man",
					"IL"=> "Israel",
					"IT"=> "Italy",
					"JM"=> "Jamaica",
					"JP"=> "Japan",
					"JE"=> "Jersey",
					"JO"=> "Jordan",
					"KZ"=> "Kazakhstan",
					"KE"=> "Kenya",
					"KI"=> "Kiribati",
					"KP"=> "North Korea",
					"KR"=> "South Korea",
					"KW"=> "Kuwait",
					"KG"=> "Kyrgyzstan",
					"LA"=> "Lao",
					"LV"=> "Latvia",
					"LB"=> "Lebanon",
					"LS"=> "Lesotho",
					"LR"=> "Liberia",
					"LY"=> "Libya",
					"LI"=> "Liechtenstein",
					"LT"=> "Lithuania",
					"LU"=> "Luxembourg",
					"MO"=> "Macao",
					"MK"=> "Macedonia",
					"MG"=> "Madagascar",
					"MW"=> "Malawi",
					"MY"=> "Malaysia",
					"MV"=> "Maldives",
					"ML"=> "Mali",
					"MT"=> "Malta",
					"MH"=> "Marshall Islands",
					"MQ"=> "Martinique",
					"MR"=> "Mauritania",
					"MU"=> "Mauritius",
					"YT"=> "Mayotte",
					"MX"=> "Mexico",
					"FM"=> "Micronesia",
					"MD"=> "Moldova",
					"MC"=> "Monaco",
					"MN"=> "Mongolia",
					"ME"=> "Montenegro",
					"MS"=> "Montserrat",
					"MA"=> "Morocco",
					"MZ"=> "Mozambique",
					"MM"=> "Myanmar",
					"NA"=> "Namibia",
					"NR"=> "Nauru",
					"NP"=> "Nepal",
					"NL"=> "Netherlands",
					"NC"=> "New Caledonia",
					"NZ"=> "New Zealand",
					"NI"=> "Nicaragua",
					"NE"=> "Niger",
					"NG"=> "Nigeria",
					"NU"=> "Niue",
					"NF"=> "Norfolk Island",
					"NO"=> "Norway",
					"OM"=> "Oman",
					"PK"=> "Pakistan",
					"PW"=> "Palau",
					"PS"=> "Palestine",
					"PA"=> "Panama",
					"PG"=> "Papua New Guinea",
					"PY"=> "Paraguay",
					"PE"=> "Peru",
					"PH"=> "Philippines",
					"PN"=> "Pitcairn",
					"PL"=> "Poland",
					"PT"=> "Portugal",
					"PR"=> "Puerto Rico",
					"QA"=> "Qatar",
					"RO"=> "Romania",
					"RU"=> "Russian Federation",
					"RW"=> "Rwanda",
					"RE"=> "Reunion",
					"BL"=> "Saint Barthelemy",
					"SH"=> "Saint Helena",
					"KN"=> "Saint Kitts & Nevis",
					"LC"=> "Saint Lucia",
					"MF"=> "Saint Martin",
					"PM"=> "Saint Pierre",
					"VC"=> "Saint Vincent",
					"WS"=> "Samoa",
					"SM"=> "San Marino",
					"ST"=> "Sao Tome & Principe",
					"SA"=> "Saudi Arabia",
					"SN"=> "Senegal",
					"RS"=> "Serbia",
					"SC"=> "Seychelles",
					"SL"=> "Sierra Leone",
					"SG"=> "Singapore",
					"SX"=> "Sint Maarten",
					"SK"=> "Slovakia",
					"SI"=> "Slovenia",
					"SB"=> "Solomon Islands",
					"SO"=> "Somalia",
					"ZA"=> "South Africa",
					"SS"=> "South Sudan",
					"ES"=> "Spain",
					"LK"=> "Sri Lanka",
					"SD"=> "Sudan",
					"SR"=> "Suriname",
					"SJ"=> "Svalbard & Jan Mayen",
					"SZ"=> "Swaziland",
					"SE"=> "Sweden",
					"CH"=> "Switzerland",
					"SY"=> "Syrian Arab Republic",
					"TW"=> "Taiwan",
					"TJ"=> "Tajikistan",
					"TZ"=> "Tanzania",
					"TH"=> "Thailand",
					"TL"=> "Timor-Leste",
					"TG"=> "Togo",
					"TK"=> "Tokelau",
					"TO"=> "Tonga",
					"TT"=> "Trinidad and Tobago",
					"TN"=> "Tunisia",
					"TR"=> "Turkey",
					"TM"=> "Turkmenistan",
					"TC"=> "Turks/Caicos Islands",
					"TV"=> "Tuvalu",
					"UG"=> "Uganda",
					"UA"=> "Ukraine",
					"AE"=> "United Arab Emirates",
					"GB"=> "United Kingdom",
					"UY"=> "Uruguay",
					"UZ"=> "Uzbekistan",
					"VU"=> "Vanuatu",
					"VE"=> "Venezuela",
					"VN"=> "Viet Nam",
					"VG"=> "British Virgin",
					"VI"=> "US Virgin Islands",
					"WF"=> "Wallis and Futuna",
					"EH"=> "Western Sahara",
					"YE"=> "Yemen",
					"ZM"=> "Zambia",
					"ZW"=> "Zimbabwe",
                );
                ?>
                    <div class="form-group ">
                        <div class="col-md-3"><label for="prayer_author_country"><?php _e('Country',
                                    'wp-prayers-request') ?></label></div>
                        <div class="col-md-8">
                            <select name="prayer_author_country" class="form-control">
                                <?php
                                foreach ($select_country as $key => $val) {
                                    echo '<option value="'.esc_html($val).'">'.__($val, 'wp-prayers-request').'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                    <div class="form-group ">
                        <div class="col-md-3"><label for="prayer_messages"><?php _e('Prayer Request',
                                    'wp-prayers-request') ?></label><span class="inline-star">*</span></div>
                        <div class="col-md-8"><textarea rows="5" name="prayer_messages" id="prayer_messages"
                        class="form-control" maxlength="500"></textarea>
                            <p class="help-block" id="prayer_messages_error"><?php _e('This field is required',
                                    'wp-prayers-request') ?></p></div>
                    </div>
					<?php if ($upr_prayer_share == 1): ?>

                    <div class="form-group ">
                        
                        <div class="col-md-8"><input name="prayer_public" id="prayer_public"
                    class="form-control" type="checkbox" value="prayer_public"><?php _e(' ')._e('Do not share this request',
                                    'wp-prayers-request') ?>
             
                    </div>
					<?php endif; ?>
                <?php if ($upr_prayer_hide_captcha == 0): ?>
                    <!--<div class="form-group ">-->
                    <!--   	<div class="col-md-3"><label for="prayer_captcha"><?php _e('Captcha',
                        'wp-prayers-request') ?></label><span class="inline-star">*</span></div>-->
                    <!--       <div class="col-md-8">-->
                    <!--           <div class="col-md-3">-->
                    <!--           <img src="<?php echo plugin_dir_url(__FILE__).'/captcha.php?rand='.rand() ?>" id="captchaimg"></div>-->
                    <!--           <div class="col-md-9">-->
                    <!--           <input type="text" name="captcha_code" id="captcha_code" class="form-control" autocomplete="off" /></div>-->
                    <!--           <p class="help-block" id="captcha_code_error"></p>                    -->
                    <!--       </div>                -->
                    <!--</div>-->
                    <!--	<div class="form-group ">-->
                    <!--	<div class="col-md-3">&nbsp;</div>-->
                    <!--    <div class="col-md-8">-->
                    <!--    	<p class="help-block"><?php _e("Enter here captcha. Can't read the image?",
                        'wp-prayers-request') ?> <?php _e("Click",
                        'wp-prayers-request') ?> <a href="javascript: refreshCaptcha();"><?php _e("HERE",
                        'wp-prayers-request') ?></a> <?php _e("to refresh", 'wp-prayers-request') ?>.</p>            	</div>-->
                    <!--</div> -->

                <?php endif; ?>
                <?php

                $upr_prayer_sitekey = get_option('upr_prayer_sitekey');
                ?>
                <?php if ($upr_prayer_hide_captcha == 1): ?>
                    <script src='https://www.google.com/recaptcha/api.js'></script>
                    <div class="form-group ">
                       
                        <div class="col-md-8">
                            <div class="g-recaptcha col-md-6" data-sitekey="<?php echo esc_html($upr_prayer_sitekey) ?>"></div>
                        </div>
                    </div>

                <?php endif; ?>

                    <div class="form-group ">

                        <div class="col-md-12"><input name="save_prayer_data" id="save_prayer_data"
                                                      class="btn btn-primary" value="<?php _e("Submit", 'wp-prayers-request') ?>"
                                                      type="submit"></div>
                    </div>
                <?php } else { ?>
                    <p><?php _e('Sorry, you have to login first to request a prayer.', 'wp-prayers-request'); ?>
                        . <?php _e('Click to', 'wp-prayers-request'); ?> <a
                                href="<?php echo wp_login_url($current_url) ?>"><?php _e('login', 'wp-prayers-request'); ?></a>.
                    </p>
                    
                <?php } ?>
            </div>
        </form>
        <?php if ($upr_prayer_hide_captcha == 1): ?>
        <script>
            jQuery(document).ready(function ($) {


                $('#form_gc').submit(function (event) {

                    //event.preventDefault();

                    var googleResponse = jQuery('#g-recaptcha-response').val();
                    if (!googleResponse) {


                        alert("Please verify reCaptcha.");
                        return false;

                    }

                    else {
                        return true;
                    }

                });

            });
			 var anyname = "<?php echo $praybutton1; ?>";
        </script>
    <?php endif; ?>
        <?php

        return ob_get_clean();
    }

    public function upr_list_prayers_shortcode_output($atts,$tag, $content = '')
    {
       //get the global wp_simple_locations class
        global $prayers;
        

        //build default arguments
        $arguments = shortcode_atts(array('prayer_id' => '', 'number_of_prayers' => -1), $atts, $tag);
 
        //uses the main output function of the location class
        $html = upr_get_prayers_output($arguments);
/* die('dead'); */
        return $html;
    }

}

function upr_comments($comment, $args, $depth)
{
    //echo "<pre>"; print_r($comment);
    $upr_allow_comments_prayer_request = get_option('upr_allow_comments_prayer_request');
    if ('div' === $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <!-- <?php //echo $tag ?><?php //comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php //comment_ID() ?>"> -->
    <?php if ('div' != $args['style']) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
<?php endif; ?>
    <div class="comment-author vcard">
        <?php if ($args['avatar_size'] != 0) {
            echo get_avatar($comment, $args['avatar_size']);
        } ?>
        <?php printf(__('<cite class="fn">%s</cite> <span class="says">'.__('says', 'wp-prayers-request').':</span>'),
            get_comment_author_link()); ?>
    </div>
    <?php if ($comment->comment_approved == '0') : ?>
    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.'); ?></em>
    <br/>
<?php endif; ?>

    <div class="comment-meta commentmetadata"><a
                href="<?php echo esc_html(get_comment_link($comment->comment_ID)); ?>">
            <?php
            /* translators: 1: date, 2: time */
			$upr_ago= get_option('upr_ago');
			if($upr_ago==1){printf(__('ago','wp-prayers-request').' '.human_time_diff( get_comment_time( 'U' ), current_time('U') )
			); }else{printf(human_time_diff( get_comment_time( 'U' ), current_time('U') ).' '.__('ago','wp-prayers-request')
			);}?></a><?php edit_comment_link(__('(Edit)'), '  ', '');
        ?>
    </div>
    <?php comment_text(); ?>
    <?php
    $registration = get_option('comment_registration');
    if ($upr_allow_comments_prayer_request == 1) {
        if (comments_open($comment->comment_post_ID)) {
            if (is_user_logged_in()) {
                echo '<div class="reply"><a class="comment-reply-link pray-replay" id="'.esc_html($comment->comment_ID).'">'.__('Reply',
                        'wp-prayers-request').'</a></div>';
            } else {
                if ($registration == 1):
                    echo '<div class="reply"><a href="'.wp_login_url($current_url).'" id="'.esc_url($current_url).'">'.__('Log in to Reply',
                            'wp-prayers-request').'</a></div>';
                else:
                    echo '<div class="reply"><a class="comment-reply-link pray-replay" id="'.esc_html($comment->comment_ID).'">'.__('Reply',
                            'wp-prayers-request').'</a></div>';
                endif;
            }
        }
    }
    ?>
    <div id="reply_<?php comment_ID() ?>" class="comment-respond" style="display:none">
        <form action="/action_page.php" method="post" class="comment-form">
            <p class="comment-form-comment">
                <label for="comment"><?php _e('Comment', 'wp-prayers-request') ?> <a id="<?php comment_ID() ?>"
                                                                          class="cancelcomment"><?php _e('Reply',
                            'wp-prayers-request') ?></a></label>
            <p><textarea id="pray_reply_<?php comment_ID() ?>" name="comment" cols="45" rows="8" required
                         minlength="15"></textarea></p>
            <?php if ( ! is_user_logged_in()) { ?>
                <p class="comment-form-author">
                    <label for="author"><?php _e('Name', 'wp-prayers-request') ?></label>
                    <input id="author_<?php comment_ID() ?>" name="author" type="text" value="" size="30"></p>
                <p><label for="author"><?php _e('Email', 'wp-prayers-request') ?> </label>
                    <input id="email_<?php comment_ID() ?>" name="email" type="email" value="" size="30"></p>
                <p><label for="author"><?php _e('Website', 'wp-prayers-request') ?></label>
                    <input id="url_<?php comment_ID() ?>" name="url" type="url" value="" size="30"></p>
            <?php } ?>
            <p class="form-submit">
                <input name="submit" id="<?php comment_ID() ?>" class="submit prayresponsreply"
                       value="<?php _e('Submit', 'wp-prayers-request') ?>" type="button">
                <input name="comment_post_ID" value="<?php echo esc_html($comment->comment_post_ID); ?>"
                       id="comment_post_ID_<?php comment_ID() ?>" type="hidden">
                <input name="comment_parent" id="comment_parent_<?php comment_ID() ?>" value="<?php comment_ID() ?>"
                       type="hidden"></p>
        </form>
		<?php
?>
    </div>
    <?php if ('div' != $args['style']) : ?>
    </div>
<?php endif;
}

//main function for displaying locations (used for our shortcodes and widgets)
function upr_get_prayers_output($arguments = '')
{

    global $wp_query;
    
    wp_reset_query();
    $current_page = (get_query_var('p')) ? get_query_var('p') : 1;

    //default args
    $default_args = array(
    
        'prayer_id' => '',
        'number_of_prayers' => 5,
    );

    //update default args if we passed in new args
    if ( ! empty($arguments) && is_array($arguments)) {
        //go through each supplied argument
        foreach ($arguments as $arg_key => $arg_val) {
            //if this argument exists in our default argument, update its value
            if (array_key_exists($arg_key, $default_args)) {
                $default_args[$arg_key] = $arg_val;
            }
        }
    }

    //find locations
    $upr_no_prayer_per_page = get_option('upr_no_prayer_per_page');

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
   
    if ($upr_no_prayer_per_page < 0 || $upr_no_prayer_per_page == '') {
        $upr_no_prayer_per_page = 10;
    }
    
     
    $upr_prayer_fetch_req_from = get_option('upr_prayer_fetch_req_from');
    if ($upr_prayer_fetch_req_from != 'all') {
        $prayers_args = array(
            'post_type' => 'prayers',
            'posts_per_page' => $upr_no_prayer_per_page,
            'post_status' => 'publish',
            'paged' => $paged,
            'order' => 'DESC',
            'date_query' => array(
                'after' => date('Y-m-d', strtotime('-'.$upr_prayer_fetch_req_from.' days')),
            ),
        );
    } else {
        $prayers_args = array(
            'post_type' => 'prayers',
            'posts_per_page' => $upr_no_prayer_per_page,
            'post_status' => 'publish',
            'paged' => $paged,
            'order' => 'DESC',
        );
    }

    //output
    $html = '';
    $upr_show_do_pray = true;
    $prayers = get_posts($prayers_args);
    //if we have locations
    if ($prayers) {
        $current_url = get_permalink(get_the_ID());
        $html .= '<input type="hidden" id="current_url" value="'.$current_url.'">';
        $html .= '<input type="hidden" id="admin-ajax" value="'.admin_url('admin-ajax.php').'" />';
        $html .= '<div class="wsl_prayer_engine">';
        $html .= '<div class="wsl_prayer_enginelist">';
        $html .= '<ul>';
        //foreach location
        $registration = get_option('comment_registration');
        global $current_user;
        wp_get_current_user();
        if ( ! empty($_SERVER['HTTP_CLIENT_IP'])) {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (is_user_logged_in()) {
            $uid = $current_user->ID;
            $user_id = $current_user->display_name;
        } else {
            $uid = $current_user->ID;
            //$user_id = $ip;
        }
        $upr_hide_prayer_button = get_option('upr_hide_prayer_button');
        $upr_prayer_login_required_prayer = get_option('upr_login_not_required_request');
        $upr_hide_prayer_count = get_option('upr_hide_prayer_count');
        $upr_display_username = get_option('upr_display_username_on_prayer_listing');
        $upr_allow_comments_prayer_request = get_option('upr_allow_comments_prayer_request');
        $upr_pray_prayed_button_ip = get_option('upr_pray_prayed_button_ip');
        $upr_time_interval_pray_prayed_button = get_option('upr_time_interval_pray_prayed_button');
		$upr_ago= get_option('upr_ago');
        if ($upr_prayer_login_required_prayer != 1) {
            if (is_user_logged_in()) {
                $upr_show_do_pray = true;
            }
        } else {
            $upr_show_do_pray = true;
        }
        
        
        foreach ($prayers as $prayer) {
            
            
            $wp_prayer_id = $prayer->ID;
            $wp_prayer_title = get_the_title($wp_prayer_id);
            //$prayer_date = get_the_date(get_option('date_format'), $wp_prayer_id);
            $wp_prayer_thumbnail = get_the_post_thumbnail($wp_prayer_id, 'thumbnail');
            //$wp_prayer_content = apply_filters('the_content', $prayer->post_content);
            $wp_prayer_content = $prayer->post_content;
            if ($upr_ago==1){$prayer_date=__('ago','wp-prayers-request').' '.human_time_diff( get_the_time('U', $wp_prayer_id), current_time('U') );}
			else {$prayer_date=human_time_diff( get_the_time('U', $wp_prayer_id), current_time('U') ).' '.__('ago','wp-prayers-request');}
            if ( ! empty($wp_prayer_content)) {
                $wp_prayer_content = strip_shortcodes($wp_prayer_content);
            }
            $wp_prayer_permalink = get_permalink($wp_prayer_id);
            $wp_prayers_name = get_post_meta($wp_prayer_id, 'prayers_name', true);
            $wp_prayers_email = get_post_meta($wp_prayer_id, 'prayers_email', true);
            $wp_prayers_website = get_post_meta($wp_prayer_id, 'prayers_website', true);
            $wp_prayers_count = 0;
            if (get_post_meta($wp_prayer_id, 'prayers_count', true) > 0) {
                $wp_prayers_count = get_post_meta($wp_prayer_id, 'prayers_count', true);
            }

            // prayer performed
            //echo strtotime('90 days');
            if(isset($user_id)){$args1 = array(
                'post_type' => 'prayers_performed',
                'meta_query' => array(
                    array(
                        'key' => 'prayer_id',
                        'value' => $wp_prayer_id,
                    ),
                    array(
                        'key' => 'user_id',
                        'value' => $user_id,
                    ),
                ),
                'posts_per_page' => -1,
                'suppress_filters' => false,
            );}
            $args2 = array();
            /*if($upr_pray_prayed_button_ip==1){
                echo $postdate = date('Y-m-d', strtotime("+$upr_time_interval_pray_prayed_button sec"));
                $args2 = array(
                    'date_query' => array(
                        'column' => 'post_date',
                        'after' => $postdate
                    ),
                );
            }*/
            if(isset($args1)){$args = array_merge($args1, $args2);}
            //echo "<pre>"; print_r($args); exit;

            if(isset($args)){$prayer_performed = get_posts($args);}
            // end prayer performed

            $html = apply_filters('prayers_before_main_content', $html);
            $html .= '<li>';
            $html .= '<div class="wsl_prayer_left">';
            $html .= '<div class="wsl_prayer_right">';
            if ($upr_hide_prayer_button != 1) {
                if ($upr_show_do_pray):
                    /*if(count($prayer_performed)>0){
                        $html .= '<input name="do_pray" class="prayed" id="do_pray_'.$wp_prayer_id.'" value="'.__('Prayed','wp-prayers-request').'" type="submit">';
                    } else {
                        $html .= '<input name="do_pray" class="prayed" id="do_pray_'.$wp_prayer_id.'" onclick="do_pray('.$wp_prayer_id.','.$upr_time_interval_pray_prayed_button.','.$uid.');"
                        value="'.__('Pray','wp-prayers-request').'" type="submit">';
                    }*/
                    $html .= '<input name="do_pray" class="prayed" id="do_pray_'.$wp_prayer_id.'" onclick="do_pray('.$wp_prayer_id.','.$upr_time_interval_pray_prayed_button.','.$uid.');" 
							value="'.__('Pray', 'wp-prayers-request').'" type="submit">';
                endif;
            }
            $html .= '</div>';
            $html .= '<h5><a href="'.$wp_prayer_permalink.'"></a></h5>';
            $html .= $wp_prayer_content;
            if ($upr_display_username == 1) {
                $author_display_name = '';
                $submittedby = '';
                $author_id = $prayer->post_author;
                $author_display_name = get_post_meta($wp_prayer_id, 'prayers_name', true);
				$firstname = strtok($author_display_name, ' ');
                $submittedby = ucwords($firstname).' | ';
                $html .= '<div class="postmeta">'.$submittedby.__($prayer_date).' | ';
            } else {
                $html .= '<div class="postmeta">'.__($prayer_date).' | ';
            }
            if ($upr_hide_prayer_count == 0):
                $html .= __('Prayed',
                        'wp-prayers-request').' '.'<span id="prayer_count_'.$wp_prayer_id.'">'.$wp_prayers_count.'</span> '.__('time(s)',
                        'wp-prayers-request').'';
            endif;
            $html .= '</div>';
            $html .= '</div>';
            if ($upr_allow_comments_prayer_request == 1) {
                if (comments_open($wp_prayer_id)) {
                    if (is_user_logged_in()) {
                        $html .= '<div class="reply"><a class="comment-reply-link pray-replay" id="'.$wp_prayer_id.'">'.__('Reply',
                                'wp-prayers-request').'</a></div>';
                    } else {
                        if ($registration == 1):
                            $html .= '<div class="reply"><a href="'.wp_login_url($current_url).'">'.__('Log in to Reply',
                                    'wp-prayers-request').'</a></div>';
                        else:
                            $html .= '<div class="reply"><a class="comment-reply-link pray-replay" id="'.$wp_prayer_id.'">'.__('Reply',
                                    'wp-prayers-request').'</a></div>';
                        endif;
                    }
                }
            }
            $html .= '<span id="reply_'.$wp_prayer_id.'" style="display:none">
					<form action="" method="post">
					<label for="author">'.__('Comment',
                    'wp-prayers-request').' <a id="'.$wp_prayer_id.'" class="cancelcomment">'.__('Cancel', 'wp-prayers-request').'</a></label>
					<p><textarea name="pray_reply" id="pray_reply_'.$wp_prayer_id.'"></textarea></p>
					<input id="prayer_id" name="prayer_id" value="'.$wp_prayer_id.'" type="hidden">';
            if ( ! is_user_logged_in()) {
                $html .= '<p class="comment-form-author"><label for="author">'.__('Name',
                        'wp-prayers-request').'</label><input id="author_'.$wp_prayer_id.'" name="author" type="text" value="" size="30"></p><label for="author">'.__('Email',
                        'wp-prayers-request').' </label><input id="email_'.$wp_prayer_id.'" name="email" type="text" value="" size="30"></p>
					<label for="author">'.__('Website',
                        'wp-prayers-request').'</label><input id="url_'.$wp_prayer_id.'" name="url" type="text" value="" size="30"></p>';
            }
            $html .= '<input class="prayresponse" id="'.$wp_prayer_id.'" name="pray_response" value="'.__('Send',
                    'wp-prayers-request').'" type="button"></form></span>';
            $comments = get_comments(array('post_id' => $wp_prayer_id, 'status' => 'approve'));
            if ($comments) :
                $html .= '<div style=" text-align:right; width:100%;"><a id="'.$wp_prayer_id.'" class="show_hide">'.__('Show/Hide',
                        'wp-prayers-request').'</a></div>';
                ob_start();
                wp_list_comments(array('reverse_top_level' => false, 'callback' => 'upr_comments'), $comments);
                $variable = ob_get_clean();
                $html .= '<ol class="commentlist" id="commentlist_'.$wp_prayer_id.'">'.$variable.'</ol>';
            endif;

            $html .= '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="cf"></div>';
    }
    $total_prayers = get_posts(array('post_type' => 'prayers', 'posts_per_page' => -1, 'post_status' => 'publish','date_query' => array(
                'after' => date('Y-m-d', strtotime('-'.$upr_prayer_fetch_req_from.' days')))));
    $max_num_pages = ceil(sizeof($total_prayers) / $upr_no_prayer_per_page);
	if (empty($current_url)) {$current_url='';}
    $pagination_args = array(
        'base' => $current_url.'%_%',
        'format' => 'page/%#%',
        'total' => $max_num_pages,
        'current' => $paged,
        'show_all' => false,
        'end_size' => 1,
        'mid_size' => 2,
        'prev_next' => true,
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'type' => 'plain',
        'add_args' => false,
        'add_fragment' => '',
    );
    $paginate_links = paginate_links($pagination_args);
    if ($paginate_links) {
        $html .= "<div align='center'><nav class='custom-pagination'>";
        $html .= "<span class='page-numbers page-num'>".__('Page ', 'wp-prayers-request').$paged.__(' of ',
                'wp-prayers-request').$max_num_pages."</span> ";
        $html .= $paginate_links;
        $html .= "</nav></div>";
    }

    return $html;
}

?>