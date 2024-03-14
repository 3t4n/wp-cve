<?php
/*
Plugin Name: Image Captcha
Version: 1.2
Description: Image captcha for login page and comments.
Author: Captcha Soft
Author URI: mailto:wp.captcha.soft@gmail.com
*/

/*  Copyright 2014  Captcha Soft  (email: wp.captcha.soft@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class ImageCaptcha {
  public $images;
  public $loginFormRequired;
  public $registerFormRequired;
  public $lostpasswordFormRequired;
  public $banTime = 1800;
  public $maxErrors = 3;

  public function __construct()
  {
    if(!session_id())
      session_start();

    require 'images.php';
    $this->images = image_captcha_names();

    $this->loginFormRequired = get_option('image-captcha-loginFormRequired')==1 ? true : false;
    $this->registerFormRequired = get_option('image-captcha-registerFormRequired')==1 ? true : false;
    $this->lostpasswordFormRequired = get_option('image-captcha-lostpasswordFormRequired')==1 ? true : false;
    $this->commentFormRequired = get_option('image-captcha-commentFormRequired')==1 ? true : false;
    $this->banByIp = get_option('image-captcha-banByIp')==1 ? true : false;
    if( get_option( 'image-captcha-customImages' ) ) {
       $this->custom_images = get_option('image-captcha-customImages');
    }

    add_action('login_form', array($this, 'filter_login_form'));
    add_action('register_form', array($this, 'filter_register_form'));
    add_action('lostpassword_form', array($this, 'filter_lostpassword_form'));
    add_action('init', array($this, 'action_init'));
    add_action('wp_enqueue_scripts', array($this, 'action_enqueue_scripts'));
    add_action('login_enqueue_scripts', array($this, 'action_login_enqueue_scripts'), 1);
    add_action('admin_menu', array($this, 'action_admin_menu'));
    add_action('plugins_loaded', array($this, 'action_plugins_loaded'));

    add_filter('preprocess_comment', array($this, 'filter_preprocess_comment'));

    add_action('comment_form', array($this, 'show_recaptcha_in_comments'));
    add_filter('authenticate', array($this, 'filter_authenticate'), 10, 3);
  }

  public function show_recaptcha_in_comments()
  {
    if(!$this->commentFormRequired)
      return;

    echo $this->field();
  }

  public function action_init()
  {
    if(isset($_GET['act']) && $_GET['act'] == 'refresh-image-captcha') {
      echo $this->image();
      die;
    }
  }

  public function action_plugins_loaded()
  {
    load_plugin_textdomain('image-captcha', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
  }

  public function action_enqueue_scripts()
  {
    wp_enqueue_script('jquery');
    wp_enqueue_script('image-captcha', plugins_url().'/image-captcha/assets/image-captcha.js');
  }

  public function action_login_enqueue_scripts()
  {
    wp_enqueue_script('jquery');
    wp_enqueue_script('image-captcha', plugins_url().'/image-captcha/assets/image-captcha.js');
  }

  public function filter_preprocess_comment($commentdata)
  {
    if(!$this->commentFormRequired)
      return $commentdata;

    $result = $this->validate();
    if($result != -1) {
      wp_die($result);
    }

    return $commentdata;
  }

  public function filter_authenticate($user, $username, $password)
  {
    if(!$this->loginFormRequired)
      return;

    if(!empty($username) && !empty($password)) {
      $result = $this->validate();
      if($result != -1) {
        remove_action('authenticate', 'wp_authenticate_username_password', 20);
        return new WP_Error('denied', $result);
      }
      return null;
    }
  }

  public function filter_login_form()
  {
    if(!$this->loginFormRequired)
      return;

    echo $this->field();
  }

  public function filter_register_form()
  {
    if(!$this->registerFormRequired)
      return;

    echo $this->field();
  }

  public function filter_lostpassword_form()
  {
    if(!$this->lostpasswordFormRequired)
      return;

    echo $this->field();
  }

  public function action_admin_menu()
  {
    add_menu_page('image-captcha', 'Image Captcha', 'manage_options', 'image-captcha', array($this, 'options'));
  }

  public function options()
  {
  	define( 'UPLOADS', 'wp-content/plugins/image-captcha/'.'images' ); 
    if(!current_user_can('manage_options'))  {
      wp_die(__('You do not have sufficient permissions to access this page.', 'image-captcha'));
    }

    if( isset($_POST['fileup_nonce']) ) {
		if( wp_verify_nonce( $_POST['fileup_nonce'], 'image-captcha-AddImage' ) ){
			if ( ! function_exists( 'wp_handle_upload' ) ) 
				require_once( ABSPATH . 'wp-admin/includes/file.php' );

			$file = &$_FILES['image-captcha-AddImage'];
			$overrides = array( 'test_form' => false );

			$movefile = wp_handle_upload( $file, $overrides );

			if ( $movefile && empty($movefile['error']) ) {
				$custom_img = array();
				if(isset($this->custom_images))
					array_push($custom_img, $this->custom_images);
				array_push($custom_img, array('image' => $_FILES['image-captcha-AddImage']['name'],'answers' => array($_REQUEST['image-captcha-addKeyRus'], $_REQUEST['image-captcha-addKeyEn'])));
				update_option('image-captcha-customImages', $custom_img);
			}
		}
	}

    if(count($_POST) && empty($_REQUEST['image-captcha-addKeyRus'])) {
	   	if(isset($_POST['image-captcha-loginFormRequired']))
	    	update_option('image-captcha-loginFormRequired', $_POST['image-captcha-loginFormRequired']);
	    else
	    	update_option('image-captcha-loginFormRequired', 0);
	    if(isset($_POST['image-captcha-registerFormRequired']))
	    	update_option('image-captcha-registerFormRequired', $_POST['image-captcha-registerFormRequired']);
	    else
	    	update_option('image-captcha-registerFormRequired', 0);
	    if(isset($_POST['image-captcha-lostpasswordFormRequired']))
	    	update_option('image-captcha-lostpasswordFormRequired', $_POST['image-captcha-lostpasswordFormRequired']);
	    else
	    	update_option('image-captcha-lostpasswordFormRequired', 0);
	    if(isset($_POST['image-captcha-commentFormRequired']))
	    	update_option('image-captcha-commentFormRequired', $_POST['image-captcha-commentFormRequired']);
	    else
	    	update_option('image-captcha-commentFormRequired', 0);
	    if(isset($_POST['image-captcha-banByIp']))
	    	update_option('image-captcha-banByIp', $_POST['image-captcha-banByIp']);
	    else
	    	update_option('image-captcha-banByIp', 0);
    }

    echo '
	    <div class="wrap" style="display: inline-table;">
	      <h2>Image Captcha</h2>
	      <form action="" method="POST" enctype="multipart/form-data">
	        <table class="form-table">
	          <tbody>
	          	<tr>
		            <th scope="row">'.__('Settings', 'image-captcha').'</th>
		            <td>
		                <label for="image-captcha-loginFormRequired">
		                  <input '.(get_option('image-captcha-loginFormRequired')==1 ? 'checked="checked"' : '0').' type="checkbox" value="1" id="image-captcha-loginFormRequired" name="image-captcha-loginFormRequired">
		                  '.__('Add CAPTCHA to Login form', 'image-captcha').'
		                </label>
		                <br>
		                <label for="image-captcha-registerFormRequired">
		                  <input '.(get_option('image-captcha-registerFormRequired')==1 ? 'checked="checked"' : '0').' type="checkbox" value="1" id="image-captcha-registerFormRequired" name="image-captcha-registerFormRequired">
		                  '.__('Add CAPTCHA to Register form', 'image-captcha').'
		                </label>
		                <br>
		                <label for="image-captcha-lostpasswordFormRequired">
		                  <input '.(get_option('image-captcha-lostpasswordFormRequired')==1 ? 'checked="checked"' : '0').' type="checkbox" value="1" id="image-captcha-lostpasswordFormRequired" name="image-captcha-lostpasswordFormRequired">
		                  '.__('Add CAPTCHA to Lost Password form', 'image-captcha').'
		                </label>
		                <br>
		                <label for="image-captcha-commentFormRequired">
		                  <input '.(get_option('image-captcha-commentFormRequired')==1 ? 'checked="checked"' : '0').' type="checkbox" value="1" id="image-captcha-commentFormRequired" name="image-captcha-commentFormRequired">
		                  '.__('Add CAPTCHA to Comments Form', 'image-captcha').'
		                </label>
		                <br>
		                <label for="image-captcha-banByIp">
		                  <input '.(get_option('image-captcha-banByIp')==1 ? 'checked="checked"' : '0').' type="checkbox" value="1" id="image-captcha-banByIp" name="image-captcha-banByIp">
		                  '.__('Block user by IP address after 3 unsuccessful attempts', 'image-captcha').'
		                </label>
		            </td>
	            </tr>
	          </tbody>
	        </table>
	        <p class="submit"><input type="submit" value="'.__('Save', 'image-captcha').'" class="button button-primary" id="submit" name="submit"></p>
	      </form>
	    </div>
	    <div class="wrap" style="display: inline-table; height: 251px;">
	    	<h2>Add Custom Images</h2>
	    	<form enctype="multipart/form-data" action="" method="POST">
	    	<table class="form-table">
	          	<tbody>
	          		<tr>
	          			<th scope="row">'.__('Add key(name) for image in Russian language.', 'image-captcha').'</th>
	          			<td>
	          				<label for="image-captcha-addKeyRus">
				              <input type="text" id="image-captcha-addKeyRus" name="image-captcha-addKeyRus" required>
				            </label>
				        </td>
	          		</tr>
	          		<tr>
	          			<th scope="row">'.__('Add key(name) for image in English language.', 'image-captcha').'</th>
				        <td>
				            <label for="image-captcha-addKeyEn">
				              <input type="text" id="image-captcha-addKeyEn" name="image-captcha-addKeyEn" required>
				            </label>
	          			</td>
	          		</tr>
				</tbody>
	        </table>
	        ' . wp_nonce_field( 'image-captcha-AddImage', 'fileup_nonce' ) . '
			<input name="image-captcha-AddImage" type="file" required />
			<input type="submit" value="Загрузить файл" />
			</form>
	    </div>
    ';
  }

  public function validate()
  {
    if($this->banByIp) {
      if($_SESSION['errors_count'] >= $this->maxErrors) {
        $ip = $this->ip();
        $ban_ips = $this->ban_ips();

        if(!isset($ban_ips[$ip])) {
          $ban_ips[$ip] = array(
            'time'=>time()
          );
          update_option('image-captcha-ban-ips', $ban_ips);
        }

        $time = time() - $ban_ips[$ip]['time'];

        if($time <= $this->banTime) {
          return __("<strong>ERROR</strong>: Your ip is temporarily blocked.", 'image-captcha');
        } else {
          unset($ban_ips[$ip]);
          update_option('image-captcha-ban-ips', $ban_ips);
          $_SESSION['errors_count'] = 0;
        }
      }
    }

    if(empty($_POST['image-captcha-input'])) {
      return __("<strong>ERROR</strong>: Enter object in the image.", 'image-captcha');
    }

    $alt_imgs = get_option( 'image-captcha-customImages' );
    $input = strtolower($_POST['image-captcha-input']);
    $id = $_SESSION['image-captcha-id'];
    $image = $this->images[$id];
    if(array_search($input, $image['answers']) === false || array_search($input, $alt_imgs['answers']) === false) {
      if($this->banByIp) {
        if(empty($_SESSION['errors_count'])) {
          $_SESSION['errors_count'] = 1;
        } else {
          $_SESSION['errors_count']++;
        }
      }

      return __("<strong>ERROR</strong>: Invalid object in the image.", 'image-captcha');
    }

    $_SESSION['errors_count'] = 0;

    return -1;
  }

  public function field()
  {
    return '
    <style type="text/css">
      .comment-image-captcha {
        margin-bottom: 20px;
      }
      .comment-image-captcha table {
        border-width: 0;
        border: 0;
        padding: 0;
        margin: 0;
      }
      .comment-image-captcha td {
        border-width: 0;
        vertical-align: middle;
        padding: 0;
        margin: 0;
        background-color: transparent !important;
      }
      .comment-image-captcha div {
        margin: 0;
      }
      .image-captcha {
        display: none;
      }
      #image-captcha-input {
        width: 100% !important;
        box-sizing: border-box;
        height: auto !important;;
      }
      #image-captcha-refresh {
        width: 20px;
        height: 20px;
        cursor: pointer;
        position: relative;
      }
      #image-captcha-block{
        margin-right: 10px;
        background-color: #ffffff;
      }
      #image-captcha-refresh img {
        width: 20px;
      }
    </style>
    <div class="comment-image-captcha">
      <table style="width: 100%;" class="image-captcha">
        <tr>
          <td style="width: 77px; vertical-align: bottom;">
            <div id="image-captcha-block" style="border: 1px solid #DDDDDD; width: 75px; height: 75px;">'.$this->image().'</div>
          </td>
          <td style="vertical-align: bottom;">
            <table style="width: 100%">
              <tr>
                <td colspan="2"><label for="image-captcha-input">'.__('Object in the image', 'image-captcha').'</label></td>
              </tr>
              <tr>
                <td style="vertical-align: bottom;">
                  <input style="margin-bottom: 0px;" type="text" class="input" id="image-captcha-input" name="image-captcha-input">
                </td>
                <td style="vertical-align: middle; width: 35px; text-align: center;">
                  <span id="image-captcha-refresh"><img src="'.plugins_url().'/image-captcha/assets/refresh.png"></span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
    <!--<div class="comment-image-captcha">
      <table class="image-captcha">
          <tr>
            <td style="width: 77px; vertical-align: bottom">

            </td>
            <td style="vertical-align: bottom;">
              <label for="image-captcha-input">'.__('Object in the image', 'image-captcha').'</label>
              <table style="display: block;">
                <tr>
                  <td><input style="margin-bottom: 0px;" type="text" value="" class="input" id="image-captcha-input" name="image-captcha-input"></td>
                  <td><div id="image-captcha-refresh"><img src="'.plugins_url().'/image-captcha/assets/refresh.png"></div></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>-->';
  }

  public function image()
  {
    //$flag = rand(0, 1);
    if( ! empty( $this->custom_images ) ) {
    	$captcha_images = array_merge_recursive($this->images,$this->custom_images);
    } else {
    	$captcha_images = $this->images;
    }
    $id = rand(0, count($captcha_images)-1);
    $image = $captcha_images[$id];
    $_SESSION['image-captcha-id'] = $id;
    return '<img style="padding: 5px; max-width: 65px; max-height: 65px;" src="'.plugins_url().'/image-captcha/images/'. $image['image'] . '">';
  }

  public function ban_ips()
  {
    $ips = get_option('image-captcha-ban-ips');
    if(!is_array($ips))
      $ips = array();

    return $ips;
  }

  public function ip()
  {
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';

    return $ipaddress;
  }
}
add_filter( 'option_uploads_use_yearmonth_folders', '__return_false', 100 );
$ImageCaptcha = new ImageCaptcha();