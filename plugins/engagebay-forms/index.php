<?php
/*
Plugin Name: EngageBay Forms
Plugin URI: https://wordpress.org/plugins/engagebay-forms
Description: EngageBay Forms is the simplest and quickest way to build simple, stylish and responsive forms. Capture leads, grow email lists and dramatically improve conversions using our forms.
Version: 1.9.2
Author: EngageBay
Author URI: https://www.engagebay.com
Developer: EngageBay
Developer URL: https://www.engagebay.com
License: GPL2
*/
if (!defined('ABSPATH')) {
    exit('You are not allowed to access this file directly.');
}

function engagebay_add_new_menu_items()
{
    add_menu_page('EngageBay', 'EngageBay', 'administrator', 'engage-bay', 'engagebay_options_page', plugins_url('engagebay-forms/images/icon.png'), 0);
}

// Register style sheet.
add_action('wp_enqueue_styles', 'engagebay_css');
/**
 * Register style sheet.
 */
function engagebay_custom_js()
{
    wp_enqueue_script('custom_script', plugins_url('/js/pubnub.js', __FILE__), array('jquery'));
}

function engagebay_css()
{
    wp_enqueue_style('engagebay-marketing-software', plugins_url('/css/style.css', __FILE__));
}

add_action('wp', 'engagebay_landing_page_setpup', 5, 0);
function engagebay_landing_page_setpup()
{
    if (!is_admin()) {
        global $post;
        $landing_page = get_post_meta($post->ID, 'engagebay_landing_page', true);
        if ($landing_page != '') {
            $domain = (sanitize_text_field(get_option('engagebay_domain')));
            $email = (sanitize_email(get_option('engagebay_email')));
            $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
            if ($domain != '' && $email != '' && $rest_api != '') {
                $request = wp_remote_get('https://' . $domain . '.engagebay.com/landingpage/'.$landing_page);
                echo $response = wp_remote_retrieve_body($request);
                die();
            }
        }
    }
}

add_action( 'plugins_loaded', 'engagebay_plugin_override' );
function engagebay_plugin_override() {
  if(isset($_GET['code'])) {
    require_once 'vendor/autoload.php';
  
    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
      'clientId'                => '765095083631-uo5n5br03hl68ora8oohooeu6j0n1dkv.apps.googleusercontent.com',    // The client ID assigned to you by the provider
      'clientSecret'            => 'zAqrJN80mWUKhD5soIZk95u6',   // The client password assigned to you by the provider
      'redirectUri'             => 'https://app.engagebay.com/oauth2callback',
      'urlAuthorize'            => 'https://accounts.google.com/o/oauth2/auth',
      'urlAccessToken'          => 'https://www.googleapis.com/oauth2/v4/token',
      'urlResourceOwnerDetails' => 'https://www.googleapis.com/oauth2/v3/userinfo',
      'scopes' => 'email,profile'
    ]);
    
    try {    
      // Try to get an access token using the authorization code grant.
      $accessToken = $provider->getAccessToken('authorization_code', [
          'code' => $_GET['code']
      ]);
    
      $resourceOwner = $provider->getResourceOwner($accessToken);
    
      $user = $resourceOwner->toArray();
    
      $api_url = 'https://app.engagebay.com//dev/api/panel/api';
      $response = wp_remote_get($api_url,
                 array('timeout' => 40,
                        'method' => 'GET',
                    'sslverify' => true,
                    'headers' => array('Authorization' => 'EngageBayDevAPI!@#', 'ebwhitelist' => true, 'Accept' => 'application/json;ver=1.0', 'Content-Type' => 'application/json; charset=UTF-8', 'email' => $user['email']),
                 ));
  
  
      if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
          $result = json_decode($response['body'], false, 512, JSON_BIGINT_AS_STRING);
      } else {
          $result = json_decode($response['body'], false);
      }
  
      if($result && $result->rest_API_Key) {
        if (get_option('engagebay_rest_api') !== false) {
          update_option('engagebay_rest_api', $result->rest_API_Key);
        } else {
            add_option('engagebay_rest_api', $result->rest_API_Key);
        }
  
        $domain = explode(".",$result->version_url)[0];
        $domain = str_replace("https://", "", $domain);
  
        if (get_option('engagebay_domain') !== false) {
            update_option('engagebay_domain', $domain);
        } else {
            add_option('engagebay_domain', $domain);
        }
  
        if (get_option('engagebay_js_api') !== false) {
            update_option('engagebay_js_api', $result->js_API_Key);
        } else {
            add_option('engagebay_js_api', $result->js_API_Key);
        }
  
        if (get_option('engagebay_email') !== false) {
            update_option('engagebay_email', $user['email']);
        } else {
            add_option('engagebay_email', $user['email']);
        }
      }
    
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    
      // Failed to get the access token or user details.
      exit($e->getMessage());
    
    }
  }
}


function engagebay_options_page()
{
    engagebay_css(); ?>
        <div class="wrap">
          <?php
          $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    if ($rest_api) {
        ?>
       <div id="engagebaywrapper" class="textaligncenter">
<?php echo "<img src='".plugins_url('/images/engagebay.png', __FILE__)."'  title='Engage Bay logo' class='logo'/>"; ?> </div> 
<?php
    } ?>      
        <?php
            //we check if the page is visited by click on the tabs or on the menu button.
            //then we get the active tab.
            $active_tab = 'home';
    if (isset($_GET['tab'])) {
        if ($_GET['tab'] == 'home') {
            $active_tab = 'home';
        } elseif ($_GET['tab'] == 'web-pop') {
            $active_tab = 'web-pop';
        } elseif ($_GET['tab'] == 'forms') {
            $active_tab = 'forms';
        } elseif ($_GET['tab'] == 'landing-pages') {
            $active_tab = 'landing-pages';
        } elseif ($_GET['tab'] == 'email-templates') {
            $active_tab = 'email-templates';
        } elseif ($_GET['tab'] == 'support') {
            $active_tab = 'support';
        } else {
            $active_tab = 'settings';
        }
    } ?>
        <?php $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    if ($rest_api) {
        ?>
        <!-- wordpress provides the styling for tabs. -->
        <h2 class="nav-tab-wrapper">
            <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
            <!--<a href="?page=engage-bay&tab=home" class="nav-tab <?php if ($active_tab == 'home') {
            echo 'nav-tab-active';
        } ?> "><?php _e('Home', 'sandbox'); ?></a>-->

            <a href="?page=engage-bay&tab=web-pop" class="nav-tab <?php if ($active_tab == 'web-pop') {
            echo 'nav-tab-active';
        } ?>"><?php _e('Popup Forms', 'sandbox'); ?></a>
            <a href="?page=engage-bay&tab=forms" class="nav-tab <?php if ($active_tab == 'forms') {
            echo 'nav-tab-active';
        } ?>"><?php _e('Inline Forms', 'sandbox'); ?></a>
            <a href="?page=engage-bay&tab=landing-pages" class="nav-tab <?php if ($active_tab == 'landing-pages') {
            echo 'nav-tab-active';
        } ?>"><?php _e('Landing Pages', 'sandbox'); ?></a>
            <a href="?page=engage-bay&tab=email-templates" class="nav-tab <?php if ($active_tab == 'email-templates') {
            echo 'nav-tab-active';
        } ?>"><?php _e('Email Templates', 'sandbox'); ?></a>
             <a href="?page=engage-bay&tab=settings" class="nav-tab <?php if ($active_tab == 'settings') {
            echo 'nav-tab-active';
        } ?>"><?php _e('Settings', 'sandbox'); ?></a>
              <a href="?page=engage-bay&tab=support" class="nav-tab <?php if ($active_tab == 'support') {
            echo 'nav-tab-active';
        } ?> "><?php _e('Help', 'sandbox'); ?></a>
        </h2>
       
            <?php
    }
    do_settings_sections('engage-bay'); ?>          
    </div>
    <?php
}

add_action('admin_menu', 'engagebay_add_new_menu_items');

add_action('admin_init', 'engagebay_display_options');
function engagebay_display_options()
{
    add_settings_section('home', '', 'engagebay_display_header_options_content', 'engage-bay');

    //here we display the sections and options in the settings page based on the active tab
    if (isset($_GET['tab'])) {
        if ($_GET['tab'] == 'home') {
            add_settings_section('header_logo', '', 'engagebay_dashboard_page', 'engage-bay', 'home');
        } elseif ($_GET['tab'] == 'web-pop') {
            add_settings_section('advertising_code', '', 'engagebay_webpoups_page', 'engage-bay', 'home');
        } elseif ($_GET['tab'] == 'forms') {
            add_settings_section('advertising_code', '', 'engagebay_forms_page', 'engage-bay', 'home');
        } elseif ($_GET['tab'] == 'landing-pages') {
            add_settings_section('advertising_code', '', 'engagebay_landing_page', 'engage-bay', 'home');
        } elseif ($_GET['tab'] == 'email-templates') {
            add_settings_section('advertising_code', '', 'engagebay_email_page', 'engage-bay', 'home');
        } elseif ($_GET['tab'] == 'support') {
            add_settings_section('advertising_code', '', 'engagebay_support_page', 'engage-bay', 'home');
        } else {
            if ($_GET['tab'] == 'register') {
                add_settings_section('advertising_code', '', 'engagebay_registration_page', 'engage-bay', 'home');
            } else {
                add_settings_section('advertising_code', '', 'engagebay_settings_page', 'engage-bay', 'home');
            }
        }
    } else {
        add_settings_section('header_logo', '', 'engagebay_dashboard_page', 'engage-bay', 'home');
    }
}

function engagebay_display_header_options_content()
{
    //echo "The header of the theme";
}

function engagebay_dashboard_page()
{
    engagebay_css();
    $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    $domain = (sanitize_text_field(get_option('engagebay_domain')));
    $email = (sanitize_email(get_option('engagebay_email')));
    if (empty($email)) {
        wp_redirect(engagebay_settings_page());
    } else {
        ?>
<div id="features">
<a href="admin.php?page=engage-bay&tab=web-pop" id="boxm" >
<div class="box">
  <div class="right stripline">
   <div class="header"><?php echo "<img src='".plugins_url('/images/popup-forms.svg', __FILE__)."' width='100px' height='100px' title='Popup Forms'/>"; ?> </div>
   <h2 class="heading">
    Popup Forms</h2>
   <p>Engage web visitors and capture leads using attractive web popups.</p>
   <span class="anchor more">More</span>
  </div>
</div></a>
<a href="admin.php?page=engage-bay&tab=forms" id="boxm">
<div class="box">
  <div class="right stripline">
    <div class="header">
	<?php echo "<img src='".plugins_url('/images/inline-forms.svg', __FILE__)."' width='100px' height='100px' title='Inline Forms'/>"; ?> </div>
    <div class="left">
    </div>
    <h2 class="heading">Inline Forms</h2>
   <p>Embed beautiful forms into your web pages and capture leads.</p>
   <span class="anchor more">More</span>
   </div> 
 </div></a>
<a href="admin.php?page=engage-bay&tab=landing-pages" id="boxm"> 
   <div class="box">
   <div class="right stripline">
   <div class="header"><?php echo "<img src='".plugins_url('/images/landing-pages.svg', __FILE__)."' width='100px' height='100px' title='Landing Pages'/>"; ?> </div>
   <div class="left">
   </div>
   <h2 class="heading">Landing Pages</h2>
   <p>Boost conversions using responsive & attention-grabbing landing pages.</p>
   <span class="anchor more">More</span>
 </div>
</div> </a>
<a href="admin.php?page=engage-bay&tab=email-templates" id="boxm">
  <div class="box">
   <div class="right stripline">
    <div class="header"><?php echo "<img src='".plugins_url('/images/email-templates.svg', __FILE__)."' width='100px' height='100px' title='Email Templates'/>"; ?></div>
    <div class="left">
    </div>
    <h2 class="heading">Email Templates</h2>
    <p>Design beautiful email templates to nurture and convert into customers.</p>
    <span class="anchor more">More</span>
   </div>
</div> </a>

</div>
<?php
    }
}

function engagebay_forms_page()
{
    $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    $domain = (sanitize_text_field(get_option('engagebay_domain')));
    $email = (sanitize_email(get_option('engagebay_email')));
    $password = (sanitize_text_field(get_option('engagebay_password')));
    $js_api = (sanitize_text_field(get_option('engagebay_js_api')));
    add_thickbox();
    if (empty($email)) {
        wp_redirect(engagebay_settings_page());
    } else {
        ?>
    <?php $api_url = 'https://app.engagebay.com/dev/api/panel/forms';
        $response = wp_remote_get($api_url,
               array('timeout' => 40,
                    'method' => 'GET',
                  'sslverify' => true,
                  'headers' => array('Authorization' => $rest_api, 'ebwhitelist' => true, 'Accept' => 'application/json;ver=1.0', 'Content-Type' => 'application/json; charset=UTF-8'),
               ));
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            //echo "Something went wrong: $error_message";?>
    <div class="data min-height-60vh row center">
                      <div class="board-pad-display col-md-9">
                            <div class="board-display open">
                                 <div class="board-display-bg forms"></div>
                                 <div class="board-btm-display">
                                    <div class="board-pad-text">
                                     Capture leads and dramatically improve your conversions by using our beautiful forms. Our forms are simple to create, and they are stylish and responsive.  <br>
                                    </div>
                                    
                                      <div class="">
                                        <div class="">
                                            <p class="ab-pad-top-20 sm-mr-20 ab-inline-block">
                                            <form action="https://<?php echo $domain; ?>.engagebay.com/user-login#forms" method="post" target="_blank">
                                              <input type="hidden" name="command" value="login" >
                                              <input type="hidden" name="email" value="<?php echo $email; ?>" >
                                              <input type="hidden" name="password" value="<?php echo $password; ?>" >
                                              <input type="submit" value="Create Inline Forms" >
                                            </form>
                                          </p>
                                          </div>
                                      </div>
                                    
                                 </div>
                            </div>  
                        </div>
      </div>
     
  <?php
        } else {
            //print_r($response['body']);
     if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
         $result = json_decode($response['body'], true, 512, JSON_BIGINT_AS_STRING);
     } else {
         $result = json_decode($response['body'], true);
     }
            if (!$result) {
                ?>
     <div class="data min-height-60vh row center">
                      <div class="board-pad-display col-md-9">
                            <div class="board-display open">
                                 <div class="board-display-bg forms"></div>
                                 <div class="board-btm-display">
                                    <div class="board-pad-text">
                                      Capture leads and dramatically improve your conversions by using our beautiful forms. Our forms are simple to create, and they are stylish and responsive.  <br>
                                    </div>
                                    
                                      <div class="">
                                        <div class="">
                                            <p class="ab-pad-top-20 sm-mr-20 ab-inline-block">
                                            <form action="https://<?php echo $domain; ?>.engagebay.com/user-login#forms" method="post" target="_blank">
                                              <input type="hidden" name="command" value="login" >
                                              <input type="hidden" name="email" value="<?php echo $email; ?>" >
                                              <input type="hidden" name="password" value="<?php echo $password; ?>" >
                                              <input type="submit" class="btn btn-space btn-warning" value="Create Inline Forms" >
                                            </form>
                                          </p>
                                          </div>
                                      </div>
                                    
                                 </div>
                            </div>  
                        </div>
      </div>
     <?php
            } else {
                ?>
     <div class="mainLeftbox col-md-12">
       <div class="">
          <h1 class="wp-heading-inline">
            <div class="float_l">
            Inline Forms
             </div>
             <div class="float_r">
            <a href="https://<?php echo $domain; ?>.engagebay.com/home#form-add" target="_blank" class="btn"  return false;">Create Inline Forms</a>
			<a href="javascript:void(0)" target="_blank" class="page-refresh" onClick="window.location.href=window.location.href" ><?php echo "<img src='".plugins_url('/images/refresh-icon.png', __FILE__)."' title='Refresh'/>"; ?> </a>
            </div>
          </h1>
        </div>
		<div class="table-view">
    
                   <?php
                   $i = 1;
                //print_r($result);
                foreach ($result as $k => $v) {
                    if (isset($v['thumbnail']) && $v['thumbnail']) {
                        $thumbnail = $v['thumbnail'];
                    } else {
                        $thumbnail = plugins_url('/images/pictures.png', __FILE__);
                    } ?>
                    <div class="table-row">
                      <div>
						<h2 class="heading"><?php echo $v['name']; ?></h2>
						<span>Created on <?php echo date('d-m-Y', $v['created_time']); ?></span>
					  </div>                      
                      <?php
                     $url_form = 'https://app.engagebay.com/form-preview?token='.$v['owner_id'].'-'.$v['id']; ?>
                      <div class="inside">
                          <a href="<?php echo $url_form; ?>"  target="_blank"><?php echo "<img src='".plugins_url('/images/preview.svg', __FILE__)."' title='Preview' width='25px' />"; ?> </a>
                          <a href="https://<?php echo $domain; ?>.engagebay.com/home#forms/<?php echo $v['id']; ?>/edit" target="_blank" ><?php echo "<img src='".plugins_url('/images/edit.svg', __FILE__)."' title='Edit' width='18px'/>"; ?> </a>
                      </div>
                    </div>
                  <?php ++$i;
                } ?>
        </div>
		</div>
         <div class="mainrightbox">
          <div class="postbox">
             <h3><span>Inline Forms</span></h3>
           <div class="inside">
              <div class="video-trigger">
                <p>Capture leads and dramatically improve your conversions by using our beautiful forms. Our forms are simple to create, and they are stylish and responsive. By displaying the right message at the right place, these forms can help you significantly grow your subscriber list and capture the most relevant and useful leads on your site across any device your visitor is using. The forms are completely customizable, so they can easily slot into your current website theme and style.</p>
              </div>
            </div>
          </div>  
         <div class="postbox">
            <h3><span>Help</span></h3>
            <div class="inside">
              <div class="video-trigger">
                <p>Watch our quick tour:</p>
                <iframe src="https://www.youtube.com/embed/rOKsN8qZlUA?autoplay=1&rel=0" width="100%" height="200px"> 
                </iframe>
              </div>
            </div>
          </div>
       </div>
    <?php
            } ?>
     
     <?php echo '</pre>';
        }
    }
}

function engagebay_landing_page()
{
    $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    $domain = (sanitize_text_field(get_option('engagebay_domain')));
    $email = (sanitize_email(get_option('engagebay_email')));
    $password = (sanitize_text_field(get_option('engagebay_password')));
    if (empty($email)) {
        wp_redirect(engagebay_settings_page());
    } else {
        ?>
  <?php $api_url = 'https://app.engagebay.com/dev/api/panel/landingPage';
        $response = wp_remote_get($api_url,
               array('timeout' => 40,
                      'method' => 'GET',
                  'sslverify' => true,
                  'headers' => array('Authorization' => $rest_api, 'ebwhitelist' => true, 'Accept' => 'application/json;ver=1.0', 'Content-Type' => 'application/json; charset=UTF-8'),
               ));
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            echo "Something went wrong: $error_message"; ?>
     <div class="data min-height-60vh row center">
    <div class="board-pad-display col-md-9">
      <div class="board-display open">
        <div class="board-display-bg landingpage"></div>
        <div class="board-btm-display">
            <div class="board-pad-text">
              Choose from our wide selection of landing page templates to create the one which fits your needs in no time.  <br>
            </div>
            <div class="row">
              <div class="col-xs-6 col-xs-offset-3">
                  <p class="ab-pad-top-20 sm-mr-20 ab-inline-block">
                <form action="https://<?php echo $domain; ?>.engagebay.com/user-login#landingpages" method="post" target="_blank">
                      <input type="hidden" name="command" value="login" >
                      <input type="hidden" name="email" value="<?php echo $email; ?>" >
                      <input type="hidden" name="password" value="<?php echo $password; ?>" >
                      <input type="submit" class="btn btn-space btn-warning" value="Create Landing Page" >
                </form>
                </p>
                </div>
            </div>    
        </div>
      </div>  
    </div>
  </div>
  <?php
        } else {
            //print_r(wp_remote_retrieve_body($response));
            if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
                $result = json_decode($response['body'], false, 512, JSON_BIGINT_AS_STRING);
            } else {
                $result = json_decode($response['body'], false);
            }
            if (!$result) {
                ?>
     <div class="data min-height-60vh row center">
    <div class="board-pad-display col-md-9">
      <div class="board-display open">
        <div class="board-display-bg landingpage"></div>
        <div class="board-btm-display">
            <div class="board-pad-text">
              Choose from our wide selection of landing page templates to create the one which fits your needs in no time. <br>
            </div>
            <div class="">
              <div class="">
                  <p class="ab-pad-top-20 sm-mr-20 ab-inline-block">
                  <form action="https://<?php echo $domain; ?>.engagebay.com/user-login#landingpages" method="post" target="_blank">
                      <input type="hidden" name="command" value="login" >
                      <input type="hidden" name="email" value="<?php echo $email; ?>" >
                      <input type="hidden" name="password" value="<?php echo $password; ?>" >
                      <input type="submit" class="btn btn-space btn-warning" value="Create Landing Page" >
                </form>
                </p>
                </div>
            </div>    
        </div>
      </div>  
    </div>
  </div>
     <?php
            } else {
                ?>
      
     <div class="mainLeftbox col-md-12">
      <div class="">
          <h1 class="wp-heading-inline">
             <div class="float_l">
            Landing Page
            </div>
            <div class="float_r">
            <a href="https://<?php echo $domain; ?>.engagebay.com/home#add-landingpage" target="_blank" class="btn"  return false;">Create Landing Page</a>
            <a href="javascript:void(0)" target="_blank" class="page-refresh" onClick="window.location.href=window.location.href"><?php echo "<img src='".plugins_url('/images/refresh-icon.png', __FILE__)."' title='Refresh'/>"; ?></a>
            </div>
          </h1>
      </div>
	  <div class="table-view">
      <?php
                   $i = 1;
                //print_r($result);
                foreach ($result as $k => $v) {
                    if ($v->thumbnail) {
                        $thumbnail = $v->thumbnail;
                    } else {
                        $thumbnail = plugins_url('/images/pictures.png', __FILE__);
                    } ?>
                    <div class="table-row">
                      <div>
						<h2 class="heading"><?php echo ucfirst($v->name); ?> </h2>
						<span>Created on <?php echo date('d-m-Y', $v->created_time); ?></span>
					  </div>
                      <div class="inside">
                          <a href="https://<?php echo $domain; ?>.engagebay.com/landingpage/<?php echo $v->id; ?>" target="_blank" class="thickbox" id="preview"><?php echo "<img src='".plugins_url('/images/preview.svg', __FILE__)."' title='Preview' width='25px'/>"; ?> </a> 
                     <a href="https://<?php echo $domain; ?>.engagebay.com/home#landingpage/<?php echo $v->id; ?>" target="_blank"><?php echo "<img src='".plugins_url('/images/edit.svg', __FILE__)."' title='Edit' width='18px'/>"; ?> </a>   
                      </div>
                    </div>
                  <?php ++$i;
                } ?>
        </div>
		</div>
        <div class="mainrightbox">
          <div class="postbox">
             <h3><span>Landing Page</span></h3>
          <div class="inside">
              <div class="video-trigger">
                <p>Choose from our wide selection of landing page templates to create the one which fits your needs in no time. Or, if you prefer to create your own from scratch, build it in less than two minutes using our simple to use and straightforward landing page builder. Customize and optimize the page further to maximize visitor engagement, lead capture and conversion to customers.</p>
              </div>
            </div>
          </div>  
         <div class="postbox">
            <h3><span>Help</span></h3>
            <div class="inside">
               <div class="video-trigger">
                <p>Watch our quick tour :</p>
                <iframe width="100%" height="200" src="https://www.youtube.com/embed/9zSJT1rkHVk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
              </div>
            </div>
          </div>
       </div>
    <?php
            } ?>
     
     
     <?php echo '</pre>';
        }
    }
}

function engagebay_webpoups_page()
{
    $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    $domain = (sanitize_text_field(get_option('engagebay_domain')));
    $email = (sanitize_email(get_option('engagebay_email')));
    $password = (sanitize_text_field(get_option('engagebay_password')));
    add_thickbox();
    if (empty($email)) {
        wp_redirect(engagebay_settings_page());
    } else {
        ?>
  <?php $api_url = 'https://app.engagebay.com/dev/api/panel/leadgrabbers/';
        $response = wp_remote_get($api_url,
               array('timeout' => 40,
                    'method' => 'GET',
                  'sslverify' => true,
                  'headers' => array('Authorization' => $rest_api, 'ebwhitelist' => true, 'Accept' => 'application/json;ver=1.0', 'Content-Type' => 'application/json; charset=UTF-8'),
               ));
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            //echo "Something went wrong: $error_message";?>
    <div class="row center min-height-60vh">
              
                        <div class="board-pad-display col-md-9">
                            <div class="board-display open">
                                 <div class="board-display-bg lead-grabbers"></div>
                                 <div class="board-btm-display">
                                    <div class="board-pad-text">
                                      Popups helps you engage website visitors, capture leads and grow your email lists. Use EngageBay to design different varieties of clean, responsive and conversion-optimized web popups. <br>
                                    </div>
                                    
                                    <div class="">
                                      <div class="">
                                        <div class="">
                                            <p class="ab-pad-top-20 sm-mr-20 ab-inline-block">
                                        <form action="https://<?php echo $domain; ?>.engagebay.com/user-login#lead-grabbers" method="post" target="_blank">
                                            <input type="hidden" name="command" value="login" >
                                            <input type="hidden" name="email" value="<?php echo $email; ?>" >
                                            <input type="hidden" name="password" value="<?php echo $password; ?>" >
                                            <input type="submit" class="btn btn-space btn-warning" value="Create Popup Forms" >
                                      </form>
                                          </p>
                                          </div>
                                      </div>
                                    </div>
                                    
                                 </div>
                            </div>  
                        </div>
            </div>
  <?php
        } else {
            if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
                $result = json_decode($response['body'], false, 512, JSON_BIGINT_AS_STRING);
            } else {
                $result = json_decode($response['body'], false);
            }
            if (!$result) {
                ?>
    <div class="row center data min-height-60vh">
              
                        <div class="board-pad-display col-md-9">
                            <div class="board-display open">
                                 <div class="board-display-bg lead-grabbers"></div>
                                 <div class="board-btm-display">
                                    <div class="board-pad-text">
                                      Popups helps you engage website visitors, capture leads and grow your email lists. Use EngageBay to design different varieties of clean, responsive and conversion-optimized web popups. <br>
                                    </div>
                                    
                                    <div class="">
                                      <div class="">
                                        <div class="">
                                            <p class="ab-pad-top-20 sm-mr-20 ab-inline-block">
                                        <form action="https://<?php echo $domain; ?>.engagebay.com/user-login#lead-grabbers" method="post" target="_blank">
                                            <input type="hidden" name="command" value="login" >
                                            <input type="hidden" name="email" value="<?php echo $email; ?>" >
                                            <input type="hidden" name="password" value="<?php echo $password; ?>" >
                                            <input type="submit" class="btn btn-space btn-warning" value="Create Popup Forms" >
                                      </form>
                                          </p>
                                          </div>
                                      </div>
                                    </div>
                                    
                                 </div>
                            </div>  
                        </div>
            </div>
     <?php
            } else {
                ?>
     <div class="mainLeftbox col-md-12">
       <div class="">
          <h1 class="wp-heading-inline">
            <div class="float_l">
            Popup Forms
             </div>
            <div class="float_r">
            <a href="https://<?php echo $domain; ?>.engagebay.com/home#lead-grabber-themes" target="_blank" class="btn"  return false;">Create Popup Forms</a>
            <a href="javascript:void(0)" target="_blank" class="page-refresh" onClick="window.location.href=window.location.href"><?php echo "<img src='".plugins_url('/images/refresh-icon.png', __FILE__)."' title='Refresh'/>"; ?></a>
          </div>
          </h1>
      </div>
		  <div class="table-view">
		   <?php
                       $i = 1;
                //print_r($result);
                foreach ($result as $k => $v) {
                    if ($v->thumbnail) {
                        $thumbnail = $v->thumbnail;
                    } else {
                        $thumbnail = plugins_url('/images/pictures.png', __FILE__);
                    } ?>
						<div class="table-row">
						  
						  <div>
							<h2 class="heading"><?php echo ucfirst($v->name); ?></h2>
							<span>Created on <?php echo date('d-m-Y', $v->created_time); ?></span>
						  </div>
						  <?php
                         $url_form = 'https://app.engagebay.com/form-preview?token='.$v->owner_id.'-'.$v->id.'&type=rules'; ?>
						  <div class="inside">
							  <a href="<?php echo $url_form; ?>"  target="_blank"><?php echo "<img src='".plugins_url('/images/preview.svg', __FILE__)."' title='Preview' width='25px'/>"; ?> </a> 
						 <a href="https://<?php echo $domain; ?>.engagebay.com/home#lead-grabbers/<?php echo $v->id; ?>/edit" target="_blank" ><?php echo "<img src='".plugins_url('/images/edit.svg', __FILE__)."' title='Edit' width='18px'/>"; ?> </a>
						  </div>
						</div>
					  <?php ++$i;
                } ?>
			</div>
        </div>
        <div class="mainrightbox">
         <div class="postbox">
            <h3><span>Popup Forms</span></h3>
            <div class="inside">
                <div class="video-trigger">
                  <p>Popups helps you engage website visitors, capture leads and grow your email lists. Use EngageBay to design different varieties of clean, responsive and conversion-optimized web popups. Customize where they appear, when they appear, and how they look to create the perfect web popup the way you want. Displaying the right popup at the right time can help significantly grow your subscriber list and boost conversion rates.  Send automated opt-in confirmation emails and autoresponders to welcome the leads after their subscription is confirmed. Send periodic engaging emails to further nurture your leads. Email marketing is the most proven profitable marketing channel returning $38 for every $1 spent.</p>
                </div>
              </div>
         </div>
         <div class="postbox">
            <h3><span>Help</span></h3>
            <div class="inside">
              <div class="video-trigger">
                <p>Watch our quick tour</p>
                <iframe width="100%" height="200" src="https://www.youtube.com/embed/caNeMNDv-58" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
              </div>
            </div>
          </div>
       </div>
    <?php
            } ?>
 
     <?php echo '</pre>';
        }
    }
}

function engagebay_email_page()
{
    $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    $domain = (sanitize_text_field(get_option('engagebay_domain')));
    $email = (sanitize_email(get_option('engagebay_email')));
    $password = (sanitize_text_field(get_option('engagebay_password')));
    add_thickbox();
    if (empty($email)) {
        wp_redirect(engagebay_settings_page());
    } else {
        ?>
  <?php $api_url = 'https://app.engagebay.com/dev/api/panel/email-template';
        $response = wp_remote_get($api_url,
               array('timeout' => 40,
                    'method' => 'GET',
                  'sslverify' => false,
                  'headers' => array('Authorization' => $rest_api, 'ebwhitelist' => true, 'Accept' => 'application/json;ver=1.0', 'Content-Type' => 'application/json; charset=UTF-8'),
               ));
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            //echo "Something went wrong: $error_message";?>
     <div class="data min-height-60vh row center">
    <div class="board-pad-display col-md-9">
      <div class="board-display open">
        <div class="board-display-bg"></div>
        <div class="board-btm-display">
            <div class="board-pad-text">
              Design beautiful and engaging emails and run email marketing campaigns at the click of a button.  <br>
            </div>
            <div class="row">
              <div class="">
                  <span class="ab-pad-top-20 ab-inline-block sm-mr-20">
                  
                  <div class="btn-group">
                        <form action="https://<?php echo $domain; ?>.engagebay.com/user-login#email-templates" method="post" target="_blank">
                                            <input type="hidden" name="command" value="login" >
                                            <input type="hidden" name="email" value="<?php echo $email; ?>" >
                                            <input type="hidden" name="password" value="<?php echo $password; ?>" >
                                            <input type="submit" class="btn btn-space btn-warning" value="Create Email Template" >
                  </form>
                      </div>
                  
                </span>
                </div>
            </div>    
        </div>
      </div>  
    </div>
  </div>
  <?php
        } else {
            if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
                $result = json_decode($response['body'], false, 512, JSON_BIGINT_AS_STRING);
            } else {
                $result = json_decode($response['body'], false);
            }
            if (!$result) {
                ?>
     <div class="data min-height-60vh row center">
    <div class="board-pad-display col-md-9">
      <div class="board-display open">
        <div class="board-display-bg"></div>
        <div class="board-btm-display">
            <div class="board-pad-text">
              Design beautiful and engaging emails and run email marketing campaigns at the click of a button.  <br>
            </div>
            <div class="row">
              <div class="">
                  <span class="ab-pad-top-20 ab-inline-block sm-mr-20">
                  
                  <div class="btn-group">
                       <form action="https://<?php echo $domain; ?>.engagebay.com/user-login#email-templates" method="post" target="_blank">
                                            <input type="hidden" name="command" value="login" >
                                            <input type="hidden" name="email" value="<?php echo $email; ?>" >
                                            <input type="hidden" name="password" value="<?php echo $password; ?>" >
                                            <input type="submit" class="btn btn-space btn-warning" value="Create Email Template" >
                  </form>
                      </div>
                  
                </span>
                </div>
            </div>    
        </div>
      </div>  
    </div>
     <?php
            } else {
                ?>
     <div class="mainLeftbox col-md-12">
      <div class="">
          <h1 class="wp-heading-inline">
            <div class="float_l">
            Email Templates
             </div>
             <div class="float_r">
            <a href="https://<?php echo $domain; ?>.engagebay.com/home#add-email-template" target="_blank" class="btn"  return false;">Create Email Templates</a>
            <a href="javascript:void(0)" target="_blank" class="page-refresh" onClick="window.location.href=window.location.href"><?php echo "<img src='".plugins_url('/images/refresh-icon.png', __FILE__)."' title='Refresh'/>"; ?></a>
            </div>
          </h1>
      </div>
	  <div class="table-view">
      <?php
                   $i = 1;
                //print_r($result);
                foreach ($result as $k => $v) {
                    if ($v->thumbnail) {
                        $thumbnail = $v->thumbnail;
                    } else {
                        $thumbnail = plugins_url('/images/pictures.png', __FILE__);
                    } ?>
                    <div class="table-row">
                      <div class="header hard-hide"><img src='<?php echo $thumbnail; ?>' title='Preview'  style="border-radius: 52px;" width='60px'  height='60px'/> </div>
					  <div> 
						<h2 class="heading"><?php echo ucfirst($v->name); ?> </h2>
						<span>Created on <?php echo date('d-m-Y', $v->created_time); ?></span>
					  </div>
                      <div class="inside">
                          <a href="https://<?php echo $domain; ?>.engagebay.com/home#email-template/<?php echo $v->id; ?>" class="" id=""  target="_blank"><?php echo "<img src='".plugins_url('/images/preview.svg', __FILE__)."' title='Preview' width='25px'/>"; ?> </a> 
                     <a href="https://<?php echo $domain; ?>.engagebay.com/home#email-template/<?php echo $v->id; ?>" target="_blank" ><?php echo "<img src='".plugins_url('/images/edit.svg', __FILE__)."' title='Edit' width='18px'/>"; ?> </a>
                      </div>
                    </div>
                  <?php ++$i;
                } ?>
        </div>
		</div>
        <div class="mainrightbox">
        <div class="postbox">
            <h3><span>Email Templates</span></h3>
         <div class="inside">
              <div class="video-trigger">
                <p>Design beautiful and engaging emails and run email marketing campaigns at the click of a button. Our simple to use software helps you effortlessly build emails and measure their performance, making your next email marketing campaign engaging. You will get better click-through rates each time. Our email marketing solution transforms your email results into a marketing channel that continues to deliver a return on investment (ROI), time and time again.</p>
              </div>
            </div>
         </div>
         <div class="postbox">
            <h3><span>Help</span></h3>
            <div class="inside">
              <div class="video-trigger">
                <p>Watch our quick tour:</p>
                <iframe width="290" height="200" src="https://www.youtube.com/embed/SMsO1PfRGw4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
              </div>
            </div>
          </div>
       </div>
    <?php
            } ?>
     
     <?php echo '</pre>';
        }
    }
}

function engagebay_settings_page()
{
    engagebay_css();
    $deprecated = null;
    $autoload = 'no';
    if (isset($_POST['email'])) {
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
    }

    if (isset($email)) {
        $email = sanitize_email($_POST['email']);
        if (isset($_POST['rest_api'])) {
            $rest_api = sanitize_text_field($_POST['rest_api']);
        }
      
        $api_url = 'https://app.engagebay.com/rest/api/login/get-domain';
        $request = wp_remote_post($api_url, array(
                'method' => 'post',
                'body' => array('email' => $email, 'password' => $password, 'source' => 'WORDPRESS'),
                'headers' => array('ebwhitelist' => true)
                 )
        );
        $result = wp_remote_retrieve_body($request);
        if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        } else {
            $result = json_decode($result, true);
        }
        $domain = $result['domain_name'];
        $js_api = $result['api_key']['js_API_Key'];
        $rest_api = $result['api_key']['rest_API_Key'];
        if ($domain == '' && $rest_api == '') {
            $errors = 'Invalid details. Please provide valid details and try again';
        } else {
            if (get_option('engagebay_password') !== false) {
                update_option('engagebay_password', $password);
            } else {
                add_option('engagebay_password', $password, $deprecated, $autoload);
            }

            if (get_option('engagebay_rest_api') !== false) {
                update_option('engagebay_rest_api', $rest_api);
            } else {
                add_option('engagebay_rest_api', $rest_api, $deprecated, $autoload);
            }

            if (get_option('engagebay_domain') !== false) {
                update_option('engagebay_domain', $domain);
            } else {
                add_option('engagebay_domain', $domain, $deprecated, $autoload);
            }

            if (get_option('engagebay_js_api') !== false) {
                update_option('engagebay_js_api', $js_api);
            } else {
                add_option('engagebay_js_api', $js_api, $deprecated, $autoload);
            }

            if (get_option('engagebay_email') !== false) {
                update_option('engagebay_email', $email);
            } else {
                add_option('engagebay_email', $email, $deprecated, $autoload);
            }

            $sucesserrors = 'SucessFully Verified the domain';
        }
    }
    $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    $domain = (sanitize_text_field(get_option('engagebay_domain')));
    $email = (sanitize_email(get_option('engagebay_email')));
    if (isset($_GET['edit'])) {
        $edit = $_GET['edit'];
    } ?>
  <div class="be-wrapper be-login">
    <div class="be-content">
      <div class="main-content container-fluid">
      <?php if ($email == '' || (isset($edit) && $edit == 'domain') || $rest_api == '') {
        ?>
      
        <div class="splash-container">
          <div class="panel">
            <?php if (isset($errors)) {
            echo '<div id="error_message">'.$errors.'<br/></div>';
        } ?>
            <?php if (isset($sucesserrors)) {
            echo '<div id="success_message">'.$sucesserrors.'<br/></div>';
        } ?>
            <div class="panel-heading text-center">
            <?php echo "<img src='".plugins_url('/images/engagebay.png', __FILE__)."' width='170' title='Enage bay logo'/>"; ?> 
              <h4>EngageBay Login</h4>
            </div>
            <div class="panel-body">
              <form name="loginForm" id="loginForm" class="" action="" method="post">
                
                <div class="form-group">
                  <div class="">
                    <input class="form-control" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,20}$" name="email" autocomplete="off" placeholder="Work Email" value="<?php echo $email; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="">
                    <input class="form-control" type="password" name="password"  placeholder="Password" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="xs-pt-20 md-pt-20">
                    <div class="login-submit xs-m-0 md-p-0">
                      <button class="btn btn-success btn-xl" type="submit">LOGIN
                        <?php echo "<img src='".plugins_url('/images/loader.gif', __FILE__)."' 
                       style='display: none;' id='gif' title='Landing Pages'/>"; ?>
                    </div><br/>
                    <div class="btn btn-success btn-xl gsuite-btn-div">
                      <a><img class="gsuite-img" src="https://d2p078bqz5urf7.cloudfront.net/cloud/dev/assets/img/google-favicon.png"/></a>
                      <a class="gsuite-btn-text" href="https://app.engagebay.com/oauth?wordpressPlugin=yes&wordpressPluginURL=<?php echo admin_url('admin.php?page=engage-bay');?>">SIGN IN WITH G SUITE</a>
                    </div>
                  </div>
                </div>
                
              </form>
             <?php
engagebay_custom_js(); ?>
      </div>
          </div>
         <?php if ($email == '') {
            ?>
<div class="splash-footer">
            Forgot <a href="https://app.engagebay.com/forgot-password" target="_blank" class="text-info">Password?</a>
          </div>
  <div class="alert text-center">
              <div>
                  <!-- <div>Don't have an account? <a href="?page=engage-bay&tab=register" ><?php _e('Sign Up', 'sandbox'); ?></a> -->
                  <div>Don't have an account? <a href="https://app.engagebay.com/signup" ><?php _e('Sign Up', 'sandbox'); ?></a></div>
                  </div>                  
               </div> 
   </div>

<?php
        } ?>
        </div>
        </div>

<?php
    } elseif (isset($_GET['tab']) && $_GET['tab'] == 'settings') {
        //$blogdescription = (sanitize_text_field(get_option( "blogdescription" )));
        $siteurl = (esc_url(get_option('siteurl'))); ?>
<div class="splash-inner-container">
          <div class="panel">
          <hr>
  <div class="panel-heading text-center ">
                <h2>EngageBay Account Details</h2>
  </div>
            <hr>
<div class="account-engagebay-label text-center">You are now connected with EngageBay. Need help? <a class="" href="https://www.engagebay.com/support" rel="noopener noreferrer" target="_blank" ><span>Contact us</span></a></div>
<div class="row center">
	
  <div class="col-md-6">
 
    <form name="loginForm" id="loginForm" class="" action="#" method="post">
    <div class="form-group">
    <label for="siteurl">Domain Name</label>
      <div class="">
        <input class="form-control" type="text" name="siteurl" autocomplete="off" disabled="disabled" placeholder="siteurl" value="<?php echo $domain; ?>" required>
      </div>
    </div>
    <div class="form-group">
    <label for="siteurl">Username</label>
      <div class="">
        <input class="form-control" type="text" name="siteurl" autocomplete="off" disabled="disabled" placeholder="siteurl" value="<?php echo $email; ?>" required>
      </div>
<!--      <div><a href="admin.php?page=engage-bay&tab=settings&edit=domain">Click to Edit details</a></div>-->
    </div>
    </form>
  </div>
 <!-- <div class="col-md-6">
  <div class="account-engagebay-label">Need Help?</div> 
  <a class="" href="https://www.engagebay.com/support" rel="noopener noreferrer" target="_blank" ><span>Contact us</span></a>
  </div>-->
</div>
</div>

<?php
    } else {
        wp_redirect(engagebay_options_page());
    } ?>
  </div>
</div>
</div>
<?php
}

function engagebay_registration_page()
{
    $sucesserrors = false;
    $siteurl = (esc_url(get_option('siteurl')));
    $siteurl_parts = explode('/', $siteurl, 4);
    $base_siteurl = $siteurl_parts[0].'//'.$siteurl_parts[2];
    engagebay_css();
    $deprecated = null;
    $autoload = 'no';
    if(isset($_POST['email'])) {
      $email = sanitize_email($_POST['email']);
    }
    
    if (isset($email) && $email) {
        $engagebay_array['email'] = sanitize_email($_POST['email']);
        $engagebay_array['name'] = sanitize_text_field($_POST['name']);
        $engagebay_array['website'] = sanitize_text_field($_POST['website']);
        $engagebay_array['password'] = sanitize_text_field($_POST['password']);
        $engagebay_array['command'] = sanitize_text_field($_POST['command']);
        $engagebay_array['timeZoneId'] = sanitize_text_field($_POST['timeZoneId']);
        $engagebay_array['source'] = 'WORDPRESS';
        //$engagebay_json = json_encode($engagebay_array);
        $api_url = 'https://app.engagebay.com/rest/api/signup/signup-user';
        $request = wp_remote_post($api_url, array(
                                          'method' => 'post',
                                          'timeout' => 40,
                                          'httpversion' => '1.0',
                                          'blocking' => true,
                                          'body' => $engagebay_array,
                                          'headers' => array('ebwhitelist' => true)
              )
    );
        $result = wp_remote_retrieve_body($request);
        $resultString = $result;

        if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        } else {
            $result = json_decode($result, true);
        }
        $domain = '';
        if($result && is_array($result)) {
          $domain = $result['domain_name'];
          $js_api = $result['api_key']['js_API_Key'];
          $rest_api = $result['api_key']['rest_API_Key'];
          $email = sanitize_email($_POST['email']);
          $password = sanitize_text_field($_POST['password']);
        }
        
        if ($domain == '') {
            $errors = $resultString;
        } else {
            if (get_option('engagebay_password') !== false) {
                update_option('engagebay_password', $password);
            } else {
                add_option('engagebay_password', $password, $deprecated, $autoload);
            }

            if (get_option('engagebay_rest_api') !== false) {
                update_option('engagebay_rest_api', $rest_api);
            } else {
                add_option('engagebay_rest_api', $rest_api, $deprecated, $autoload);
            }

            if (get_option('engagebay_domain') !== false) {
                update_option('engagebay_domain', $domain);
            } else {
                add_option('engagebay_domain', $domain, $deprecated, $autoload);
            }

            if (get_option('engagebay_js_api') !== false) {
                update_option('engagebay_js_api', $js_api);
            } else {
                add_option('engagebay_js_api', $js_api, $deprecated, $autoload);
            }

            if (get_option('engagebay_email') !== false) {
                update_option('engagebay_email', $email);
            } else {
                add_option('engagebay_email', $email, $deprecated, $autoload);
            }

            $sucesserrors = 'SucessFully Registered';
        }
    } ?>
<div class="be-wrapper be-login">
    <div class="be-content">
      <div class="main-content container-fluid">
        <div class="splash-container">
          <div class="panel">
            <div class="panel-heading">
              
              <?php echo "<img src='".plugins_url('/images/engagebay.png', __FILE__)."' width='170' title='EngageBay logo'/>"; ?> 
              <h4>
                Get Started for <strong>FREE</strong>
              </h4>
            </div>
           <?php if (isset($sucesserrors) && !$sucesserrors) {
        ?>
              <div class="panel-body">
             <?php
              if (isset($errors) && $errors) {
                  ?>
                 <div class="alert alert-danger">
                  <strong><?php echo $errors; ?> </strong>
                  <?php echo $result; ?>
                </div>
             <?php
              } ?>
              <form id="loginForm" name="loginForm" method="POST">
                
                <input type="hidden" name="command" value="signup">
                <input type="hidden" name="timeZoneId" value="330">
                <input type="hidden" name="referral_user_id" value="">
                <div class="form-group">
                  <div class="">
                    <input class="form-control" type="name" name="name" autocomplete="name" placeholder="Name" title="Name should be between 1-30 characters in length. Both letters and numbers are allowed but it should start with a letter. Cannot contain special characters." minlength="1" maxlength="30" required="" pattern="^[a-zA-Z][a-zA-Z0-9 ]{1,30}$">
                  </div>
                </div>
                <div class="form-group">
                  <div class="position-relative">
                    <!-- <input class="form-control user-email-field" id="register_email" type="email" name="email" required autocapitalize="off" minlength="6" maxlength="50" placeholder="Work Email" oninvalid="set_custom_validate(this);" oninput="reset_custom_validate(this);"  pattern="^.+@((?!gmail.com)(?!yahoo.com)(?!yahoo.in)(?!hotmail.com)(?!fastmail.com).)+\..+$" data-pattern-mismatch-error="Please use your business email address to sign up" value=""> -->
                    <input class="form-control user-email-field" id="register_email" type="email" name="email" required autocapitalize="off" minlength="6" maxlength="50" placeholder="Work Email"  value="">
                    <span id="validEmail" class="validation-result">
                    </span>
                  </div>
                </div>                
                <div class="form-group">
                  <div class="">
                    <input class="form-control" type="url" name="website" autocomplete="url" placeholder="Website URL" value="<?php echo $base_siteurl; ?>"  minlength="3" maxlength="50" required="" pattern="^(https?://)?([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$">
                  </div>
                </div>
                <div class="form-group">
                  <div class="">
                    <input class="form-control" type="password" name="password" pattern=".{4,20}" autocomplete="off" placeholder="Password" title="Enter at least 4 characters." required="">
                  </div>
                </div>
                <div class="form-group">
                  <div class="xs-pt-20 md-pt-20">
                    <div class="login-submit xs-m-0 md-p-0">
                      <button class="btn btn-success btn-xl" type="submit">
                        SIGN UP
                        <?php echo "<img src='".plugins_url('/images/loader.gif', __FILE__)."' 
                       style='display: none;' id='gif' title='Landing Pages'/>"; ?>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>

          </div>
          <div class="splash-footer">
            Forgot <a href="https://app.engagebay.com/forgot-password" target="_blank" class="text-info">Password?</a>
          </div>
          <div class="splash-footer alert">
            Already have an account? <a href="?page=engage-bay"  ><?php _e('Sign In', 'sandbox'); ?></a>
          </div>
           <?php
    } else {
        ?>
            <div class="panel-body">
              <div class="alert alert-success fade in alert-dismissible">
                <strong>Successfully!</strong>  Registered.
            </div>
            </div>
          <?php
             engagebay_refresh();
    } ?>
          
        </div>
      </div>
    </div>
  </div>

<?php
}

function engagebay_support_page()
{
    ?>
<br/>
  <div class="be-wrapper col-md-6 col-xs-offset-3">
      <div class="be-content">
        <div class="main-content container-fluid">
          <div class="splash-container">
            <div class="panel">
              <div class="panel-heading">
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/0oPsBkeoYKI" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php
}

function engagebay_footer()
{
    $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    $domain = (sanitize_text_field(get_option('engagebay_domain')));
    $email = (sanitize_email(get_option('engagebay_email')));
    $js_api = (sanitize_text_field(get_option('engagebay_js_api')));
    if ($js_api) {
        wp_enqueue_script('main-js', 'https://www.engagebay.com', array('jquery'));
        wp_register_script('tracking-js', plugin_dir_url(__FILE__).'js/tracking.js');
        wp_enqueue_script('tracking-js');
        wp_localize_script('tracking-js', 'engagebay_vars', array(
          'js_api' => $js_api,
          'domain' => $domain,
        ));
    }
}
add_action('wp_footer', 'engagebay_footer');

// plugin deactivation
register_deactivation_hook(__FILE__, 'engagebay_deactivate');
function engagebay_deactivate()
{
    delete_option('engagebay_domain');
    delete_option('engagebay_rest_api');
    delete_option('engagebay_email');
    delete_option('engagebay_js_api');
    delete_option('engagebay_password');
}

add_action('admin_notices', 'engagebay_admin_notices');
function engagebay_admin_notices()
{
    $engagebay_email = get_option('engagebay_email');
    if ($engagebay_email == '') {
        if (isset($_GET['page']) && $_GET['page'] != 'engage-bay') {
            echo "<div class='updated'><p>Almost done! <a href='admin.php?page=engage-bay'>Enter your engagebay details </a> and you'll be ready to rock.</p></div>";
        }
    }
}

add_action('wp_dashboard_setup', 'engagebay_dashboard_setup_function');

function engagebay_dashboard_setup_function()
{
    add_meta_box('engagebay_dashboard_widget', 'EngageBay - Free Marketing Plugin', 'engagebay_dashboard_widget_function', 'dashboard', 'side', 'high');
}

function engagebay_dashboard_widget_function()
{
    wp_enqueue_style('engagebay-marketing-software', plugins_url('/css/page.css', __FILE__)); ?>
 <div class="stunning-header stunning-header-bg-violet index-stunning-header">
  <div class="container">
   <div class="xs-pt-20 text-center">
    <?php
      $user_info = get_userdata(1);
    $userloginname = $user_info->user_login;
    $nicename = $user_info->user_nicename; ?>
      <div class="font-size-20 board-pad-text">Hello <span class="text-capitalize"></span><?php echo ucfirst($nicename); ?></div>
      </div>
    <div class="board-pad-text1 font-size-18 xs-pt-10 text-center">What would you like to do today?</div>
    
      <div class="stunning-header-content">
        <div class="col-md-3">
        <div class="service-box">
                  <div class="panel-body">
                    <div id="features">
    <a href="admin.php?page=engage-bay&tab=web-pop" id="boxm" >
    <div class="box">
      <div class="right stripline">
       <div class="header">
	   <?php echo "<img src='".plugins_url('/images/popup-forms.svg', __FILE__)."' width='100px' height='100px' title='Popup Forms'/>"; ?> 
       <h3 class="heading"> Popup Forms</h3>
       </div>
       
      </div>
    </div></a>
    <a href="admin.php?page=engage-bay&tab=forms" id="boxm">
    <div class="box">
      <div class="right stripline">
        <div class="header">
		<?php echo "<img src='".plugins_url('/images/inline-forms.svg', __FILE__)."' width='100px' height='100px' title='Inline Forms'/>"; ?>
        <h3 class="heading">Inline Forms</h3>
        </div>
        
       </div> 
     </div></a>
    <a href="admin.php?page=engage-bay&tab=landing-pages" id="boxm"> 
       <div class="box">
       <div class="right stripline">
       <div class="header"><?php echo "<img src='".plugins_url('/images/landing-pages.svg', __FILE__)."' width='100px' height='100px' title='Landing Pages'/>"; ?> 
       <h3 class="heading">Landing Pages</h3>
       </div>
       
     </div>
    </div> </a>
    <a href="admin.php?page=engage-bay&tab=email-templates" id="boxm">
      <div class="box">
       <div class="right stripline">
        <div class="header"><?php echo "<img src='".plugins_url('/images/email-templates.svg', __FILE__)."' width='100px' height='100px' title='Email Templates'/>"; ?> 
            <h3 class="heading">Email Templates</h3>
         </div>

       </div>
    </div> </a>

    </div>
        </div>
     </div>
  </div>
  </div>
 </div>
</div>
<?php
}
add_action('load-post.php', 'engagebay_page_post_meta_boxes_setup');
add_action('load-post-new.php', 'engagebay_page_add_post_meta_boxes');
add_action('save_post', 'engagebay_page_save_postdata');

function engagebay_page_post_meta_boxes_setup()
{
    add_action('add_meta_boxes', 'engagebay_page_add_post_meta_boxes');
}

function engagebay_page_add_post_meta_boxes()
{
    add_meta_box('engagebay_page_section', __('Engagebay Section', 'engagebay_page_metabox'), 'engagebay_page_post_box', 'page', 'advanced', 'high');
}

function engagebay_page_save_postdata($post_id)
{
    if (isset($_POST['engagebay_landing_page'])) {
        $landing_page = sanitize_text_field($_POST['engagebay_landing_page']);
        update_post_meta($post_id, 'engagebay_landing_page', $landing_page);
    }
}

function engagebay_page_post_box($post)
{
    echo '<style>';
    echo '#page-list label{width:50%;float:left}';
    echo '</style>';
    echo "<ul id='page-list'>";
    $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
    $domain = (sanitize_text_field(get_option('engagebay_domain')));
    $email = (sanitize_email(get_option('engagebay_email')));
    $api_url = 'https://app.engagebay.com/dev/api/panel/landingPage';
    $response = wp_remote_get($api_url,
               array('timeout' => 40,
                      'method' => 'GET',
                  'sslverify' => true,
                  'headers' => array('Authorization' => $rest_api, 'ebwhitelist' => true, 'Accept' => 'application/json;ver=1.0', 'Content-Type' => 'application/json; charset=UTF-8'),
               ));


    if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
        $result = json_decode($response['body'], false, 512, JSON_BIGINT_AS_STRING);
    } else {
        $result = json_decode($response['body'], false);
    }
    $data = array();
    if (is_array($result) && count($result) > 0) {
        echo '<li>';
        echo '<label for="landing_page">'.__('Landing Page :');
        echo '</label> ';
        $landing_page = get_post_meta($post->ID, 'engagebay_landing_page', true);
        echo '<select id="engagebay_landing_page" autocomplete="off" name="engagebay_landing_page">';
        echo '<option value="">Select</option>';
        foreach ($result as $k => $v) {
            if ($landing_page == $v->id) {
                echo '<option value="'.$v->id.'" selected >'.$v->name.'</option>';
            } else {
                echo '<option value="'.$v->id.'"  >'.$v->name.'</option>';
            }
        }
        echo '</select>';
        echo '</li>';
    } else {
        echo '<li>';
        echo '<label for="engagebay_landing_page">'.__('Landing Page :');
        echo '</label> ';
        echo '<select id="engagebay_landing_page" autocomplete="off" name="engagebay_landing_page" disabled>';
        echo '<option value="">No Landing Pages.</option>';
        echo '</select>';
        echo '</li>';
    }
    echo '</ul>';
}
add_action('admin_head', 'engagebay_button');
function engagebay_button()
{
    global $typenow;
    // check user permissions
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    // verify the post type
    if (!in_array($typenow, array('post', 'page'))) {
        return;
    }
    // check if WYSIWYG is enabled
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'engagebay_add_tinymce_plugin');
        add_filter('mce_buttons', 'engagebay_register_button');
    }
}

function engagebay_add_tinymce_plugin($plugin_array)
{
    $plugin_array['engagebay_button'] = plugins_url('/js/engagebay.js', __FILE__); // CHANGE THE BUTTON SCRIPT HERE
    return $plugin_array;
}

function engagebay_register_button($buttons)
{
    array_push($buttons, 'engagebay_button');

    return $buttons;
}

add_shortcode('engagebay', 'engagebay');
function engagebay($atts, $content, $tag)
{
    if (isset($atts['id'])) {
        $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
        $domain = (sanitize_text_field(get_option('engagebay_domain')));
        $email = (sanitize_email(get_option('engagebay_email')));
    }
}
add_action('init', 'engagebay_list_enagebay_form', 5, 0);
function engagebay_list_enagebay_form()
{
    if (isset($_GET['engagebay_list_form']) == 1) {
        $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
        $domain = (sanitize_text_field(get_option('engagebay_domain')));
        $email = (sanitize_email(get_option('engagebay_email')));
        if ($domain != '' && $email != '' && $rest_api != '') {
            $api_url = 'https://app.engagebay.com/dev/api/panel/forms';
            $response = wp_remote_get($api_url,
               array('timeout' => 40,
                    'method' => 'GET',
                  'sslverify' => true,
                  'headers' => array('Authorization' => $rest_api, 'ebwhitelist' => true, 'Accept' => 'application/json;ver=1.0', 'Content-Type' => 'application/json; charset=UTF-8'),
               ));
            if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
                $result = json_decode($response['body'], false, 512, JSON_BIGINT_AS_STRING);
            } else {
                $result = json_decode($response['body'], false);
            }

            $data = array();
            if (count($result) > 0) {
                foreach ($result as $k => $v) {
                    $tmp = array();
                    $tmp['text'] = $v->name;
                    $tmp['value'] = $v->id;
                    $data[] = $tmp;
                }
            }
            echo json_encode($data);
        }
        die();
    }
}

add_shortcode('engagebayform', 'engagebayform');
function engagebayform($atts, $content, $tag)
{
    if (isset($atts['id'])) {
        $rest_api = (sanitize_text_field(get_option('engagebay_rest_api')));
        $domain = (sanitize_text_field(get_option('engagebay_domain')));
        $email = (sanitize_email(get_option('engagebay_email')));
        if ($domain != '' && $email != '' && $rest_api != '') {
            $api_url = 'https://app.engagebay.com/dev/api/panel/forms/'.$atts['id'];
            $response = wp_remote_get($api_url,
               array('timeout' => 40,
                    'method' => 'GET',
                  'sslverify' => true,
                  'headers' => array('Authorization' => $rest_api, 'ebwhitelist' => true, 'Accept' => 'application/json;ver=1.0', 'Content-Type' => 'application/json; charset=UTF-8'),
               ));
            $result = json_decode($response['body'], false);
            $id = $result->id;
            $result = '<div class="engage-hub-form-embed" id="eh_form_'.$id.'" data-id="'.$id.'"> </div>';
            //$result = $result->formHtml;
            return $result;
        }
    }
}

add_action('wp_enqueue_scripts', 'engagebay_refresh');

function engagebay_refresh()
{
    if (isset($_POST['name'])) {
        wp_enqueue_script('engagebay_refresh', plugins_url('js/refresh.js', __FILE__));
    }
}

add_action('admin_enqueue_scripts', 'engagebay_customjs');
function engagebay_customjs()
{
    // wp_enqueue_script('customjs', plugins_url('/js/custom.js', __FILE__), array('jquery'));
}




?>
