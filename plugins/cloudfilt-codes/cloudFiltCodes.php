<?php

/*
    Plugin Name: CloudFilt Codes
    Description: Prevent & block bad bots. This plugin inserts in your website the necessary codes for your web security https://cloudfilt.com/. Bot traffic, Spam Submissions, Tor traffic, Web Scraping, Web Fraud, Business logic, Vulnerability scanning, DDoS...
    Version: 1.0.13 
    Author: CloudFilt    Author URI: https://cloudfilt.com/
    Text Domain: cloudFiltCodes
    License: GPL2

    CloudFilt Codes is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    any later version.

    CloudFilt Codes is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CloudFilt Codes. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

class CloudFiltCodes {
    private $formHandler;
    private $fieldsPrefix = 'cfc_';
    private $fieldsSettings = array(
        'key_front' => 'Public key',
        'key_back' => 'Private key'
    );
    private $showMessage = 0;
    private $apiKey;
    private $apiSecret;
    private $siteId = 0;
    private $error = array();
    private $isConnected = null;
    private $curlUrl = 'https://api.cloudfilt.com/checkcms/wordpress.php';

    public function __construct() {
        add_action('admin_init', [$this, 'initForm']);
        add_action('admin_menu', [$this, 'createTabs']);

        $this->addCodes();
    }

    public function initForm() {
        include 'includes/cloudFiltCodesFormHandler.php';

        $this->formHandler = new CloudFiltCodesFormHandler($this->fieldsPrefix);
        $isValid = $this->formHandler->handleForm($_POST);

        if(!is_null($isValid)) {
            if($isValid === true) {
                $this->validateForm();
                $this->showMessage = 1;
            } else {
                $this->error = $this->formHandler->getError();
                $this->showMessage = -1;
            }
        }
    }

    public function createTabs() {
        add_menu_page(
            'CloudFilt Codes',
            'CloudFilt',
            'manage_options',
            'cloudfilt_codes',
            [$this, 'renderAdmin'],
            plugin_dir_url(__FILE__) . 'img/cloudFiltIcon.png'
        );
    }

    public function renderAdmin() {
        include('view/admin.php');
    }

    public function validateForm() {
        foreach($this->fieldsSettings as $fieldName => $fieldLabel) {
            update_option($this->fieldsPrefix . $fieldName, $this->formHandler->getParam($this->fieldsPrefix . $fieldName));
        }
        update_option($this->fieldsPrefix . 'restrict' , $_POST['restrict']);

        update_option($this->fieldsPrefix . 'exclude_options' , $_POST['exclude_options']);
    }

    public function addCodes() {
        add_action('admin_head', [$this, 'addCloudFiltCSS']);

        $this->apiKey = get_option($this->fieldsPrefix . 'key_front');
        $this->apiSecret = get_option($this->fieldsPrefix . 'key_back');
        $this->siteId = get_option($this->fieldsPrefix . 'site_id');

        if(!empty($this->apiKey) && !empty($this->apiSecret) && !empty($this->siteId)) {
            add_action('wp_head', [$this, 'addFrontendHTMLCode']);
            add_action('admin_head', [$this, 'addFrontendHTMLCode']);
            add_action('init', [$this, 'addBackendPHPCode']);
        }
    }

    public function checkIsConnected() {
        $response = wp_remote_post(
            $this->curlUrl,
            array(
                'method'      => 'POST',
                'timeout'     => 30,
                'redirection' => 5,
                'httpversion' => '1.1',
                'sslverify'   => false,
                'blocking'    => true,
                'headers'     => array(),
                'body'        => array(
                    'key_front' => $this->apiKey,
                    'key_back'  => $this->apiSecret
                ),
                'cookies'     => array(),
                'user-agent'  => 'plugin-wordpress'
            )
        );

        if(is_wp_error($response)) {
            return false;
        }

        if($response != 'error' && ((isset($response['response']) && $response['response'] == '200') || $response['response']['code'] == '200')) {
            if(isset($response['body'])) {
                $body = json_decode($response['body']);

                if(json_last_error() == JSON_ERROR_NONE && $body->status === 'OK') {
                    update_option($this->fieldsPrefix . 'site_id', $body->site);
                    return true;
                }
            }

            return false;
        } else {
            return false;
        }
    }

    public function addFrontendHTMLCode() {
      $found = 0;
      if( is_user_logged_in() ) {

        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;
        $exRoles = get_option($this->fieldsPrefix . 'exclude_options', true);
        $restict = get_option($this->fieldsPrefix . 'restrict', true);
        if($restict  == 'on' and !empty($exRoles['roles'])){
          foreach($roles as $role){
            if(in_array(ucfirst($role), $exRoles['roles'])){
                $found = 1;
                break;
            }
          }
        }
      }

      if($found == 0){
        echo '
                <!-- CloudFilt.com -->
                <script async src="https://srv' . $this->siteId . '.cloudfilt.com/analyz.js?render=' . $this->apiKey . '"></script>
            ';
      }
    }

    public function addBackendPHPCode() {
		$found = 0;
        function getUserIP43_CF() {
            $keys = ["REMOTE_ADDR", "HTTP_CLIENT_IP", "HTTP_X_FORWARDED_FOR", "HTTP_X_FORWARDED", "HTTP_FORWARDED_FOR", "HTTP_FORWARDED"];

            foreach ($keys as $key) {
                if (
                    isset($_SERVER[$key]) and
                    (filter_var($_SERVER[$key], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || filter_var($_SERVER[$key], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)))
                {
                    return $_SERVER[$key];
                }
            }

            return 'UNKNOW';
        }

        if( is_user_logged_in() ) {
          $user = wp_get_current_user();
          $roles = ( array ) $user->roles;
          $exRoles = get_option($this->fieldsPrefix . 'exclude_options', true);
          $restict = get_option($this->fieldsPrefix . 'restrict', true);
          if($restict  == 'on' and !empty($exRoles['roles'])){
            foreach($roles as $role){
              if(in_array(ucfirst($role), $exRoles['roles'])){
                  $found = 1;
                  break;
              }
            }
          }
        }

        if($found == 0){
          $ipCF43_CF = getUserIP43_CF();

          if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
              $link20_CF = "https";
          }else{
              $link20_CF = "http";
          }

          $link20_CF .= "://";
          $link20_CF .= $_SERVER['HTTP_HOST'];
          $link20_CF .= $_SERVER['REQUEST_URI'];

          $response = wp_remote_post(
              'https://api' . $this->siteId . '.cloudfilt.com/phpcurl',
              array(
                  'method'      => 'POST',
                  'timeout'     => 1,
                  'redirection' => 5,
                  'httpversion' => '1.1',
                  'blocking'    => true,
                  'sslverify'   => false,
                  'headers'     => array(),
                  'body'        => array(
                          'ip'        => $ipCF43_CF,
                          'KEY'       => $this->apiSecret,
                          'URL'       => $link20_CF
                  ),
                  'cookies'     => array(),
                  'user-agent'  => 'plugin-wordpress'
              )
          );

          if(is_wp_error($response)) {
              return 'error';
          }

          if($response != 'error' && ((isset($response['response']) && $response['response'] == '200') || $response['response']['code'] == '200')) {
              if(isset($response['body'])) {
                  $server67_CF = $response['body'];
              } else {
                  return false;
              }
          } else {
              return false;
          }

          if ($server67_CF != "OK" and !empty($server67_CF)) {
              header("Location: https://cloudfilt.com/stop-$ipCF43_CF-" . $this->apiKey, true, 307);
              echo "<SCRIPT LANGUAGE='JavaScript'>document.location.href='https://cloudfilt.com/stop-".$ipCF43_CF."-" . $this->apiKey . "'</SCRIPT>";
              die;
          }
        }
    }

    function addCloudFiltCSS() {
        echo '
            <style type="text/css">
                .cloudFiltPlugin {
                    text-align: center;
                }

                .cloudFiltPlugin__wrapper {
                    max-width: 500px;
                    margin: auto;
                }

                .cloudFiltPlugin__wrapper a {
                    color: #ff4081;
                }

                .cloudFiltPlugin__head {
                    margin-bottom: 30px;
                }

                .cloudFiltPlugin__head__logo img {
                    width: 300px;
                }

                .cloudFiltPlugin__alert {
                    color: white;
                    text-align: left;
                    padding: 15px 20px;
                    border-radius: 4px;
                    margin-bottom: 20px;
                }

                .cloudFiltPlugin__alert--danger {
                    background-color: #FF5252;
                }
                .cloudFiltPlugin__alert--danger a{
                    color: white;
                    font-weight: bold;
                }

                .cloudFiltPlugin__alert--success {
                    background-color: #4CAF50;
                }

                .cloudFiltPlugin__alert--info {
                    background-color: #4038A0;
                }

                .cloudFiltPlugin__alert--warning {
                    background-color: #FF4081;
                }

                .cloudFiltPlugin__form {
                    padding: 30px;
                    background-color: white;
                    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .12), 0 1px 5px 0 rgba(0, 0, 0, .2);
                }

                .cloudFiltPlugin__form input {
                    display: block;
                    width: 100%;
                    text-align: center;
                    margin: 20px 0;
                    padding: 10px 15px;
                    height: 40px;
                }

                .cloudFiltPlugin__form__button {
                    color: white;
                    letter-spacing: 1px;
                    font-size: 12px;
                    font-family: \'Lato\', sans-serif;
                    text-transform: uppercase;
                    border: 1px solid #8e24aa;
                    background-image: -webkit-linear-gradient(45deg, #8e24aa, #8e24aa, #ff6e40);
                    background-image:    -moz-linear-gradient(45deg, #8e24aa, #8e24aa, #ff6e40);
                    background-image:      -o-linear-gradient(45deg, #8e24aa, #8e24aa, #ff6e40);
                    background-image:         linear-gradient(45deg, #8e24aa, #8e24aa, #ff6e40);
                    background-size: 200%;
                    background-position: 75%;
                    transition: background-position 1s;
                    cursor: pointer;
                }

                .cloudFiltPlugin__form__button:hover {
                    background-position: 0;
                }

                .select2-container--default .select2-search--inline .select2-search__field {
                  text-align: center;
                }

                .select2-container .select2-selection--multiple {
                   min-height: 40px;
                }

                .toggle-check-input {
                  width: 1px;
                  height: 1px;
                  position: absolute;
                }

                .toggle-check-text {
                  display: inline-block;
                  position: relative;
                  text-transform: uppercase;
                  background: #CCC;
                  padding: 0.25em 0.5em 0.25em 2em;
                  border-radius: 1em;
                  min-width: 2em;
                  color: #FFF;
                  cursor: pointer;
                  transition: background-color 0.15s;
                }

                .toggle-check-text:after {
                  content: " ";
                  display: block;
                  background: #FFF;
                  width: 1.1em;
                  height: 1.1em;
                  border-radius: 1em;
                  position: absolute;
                  left: 0.3em;
                  top: 0.25em;
                  transition: left 0.15s, margin-left 0.15s;
                }

                .toggle-check-text:before {
                  content: "No";
                }

                .toggle-check-input:checked ~ .toggle-check-text {
                  background: #8ad869;
                  padding-left: 0.5em;
                  padding-right: 2em;
                }

                .toggle-check-input:checked ~ .toggle-check-text:before {
                  content: "Yes";
                }

                .toggle-check-input:checked ~ .toggle-check-text:after {
                  left: 100%;
                  margin-left: -1.4em;
                }
            </style>
        ';
    }
}

$cloudFiltCodes = new CloudFiltCodes();

add_action( 'admin_enqueue_scripts', 'cloudFilt_enqueue_scripts' );
function cloudFilt_enqueue_scripts() {
  //get current admin page
  $current_screen = get_current_screen();

  if ( strpos($current_screen->base, 'cloudfilt_codes') === false) {
    return;
  } else {
    wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
    //enqueue scripts
    wp_enqueue_script('materialize_js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
  }
}//end function
?>
