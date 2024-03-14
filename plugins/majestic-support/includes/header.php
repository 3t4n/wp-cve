<?php

if (!defined('ABSPATH'))
    die('Restricted Access');
if (majesticsupport::$_config['show_header'] != 1)
    return false;
$isUserStaff = false;
if (in_array('agent', majesticsupport::$_active_addons)) {
    $isUserStaff = MJTC_includer::MJTC_getModel('agent')->isUserStaff();
}
$div = '';
$headertitle = '';
$editid = MJTC_request::MJTC_getVar('majesticsupportid');
$isnew = ($editid == null) ? true : false;
$array[] = array('link' => majesticsupport::makeUrl(array('mjsmod' => 'majesticsupport', 'mjslay' => 'controlpanel')), 'text' => esc_html(__('Control Panel', 'majestic-support')));
$module = MJTC_request::MJTC_getVar('mjsmod', null, 'majesticsupport');
$layout = MJTC_request::MJTC_getVar('mjslay', null);

//Layout variy for Staff Member and User
if ($isUserStaff) {
    $linkname = 'staff';
    $myticket = 'staffmyticket';
    $addticket = 'staffaddticket';
    $announcements = 'staffannouncements';
    $downloads = 'staffdownloads';
    $adddownload = 'adddownload';
    $faqs = 'stafffaqs';
    $addfaq = 'addfaq';
    $addcategory = 'addcategory';
    $categories = 'stafflistarticles';
    $addarticle = 'addarticle';
    $articles = 'stafflistarticles';
    $addannouncement = 'addannouncement';
    $login = 'login';
} else {
    $linkname = 'user';
    $myticket = 'myticket';
    $addticket = 'addticket';
    $categories = 'userknowledgebase';
    $announcements = 'announcements';
    $downloads = 'downloads';
    $faqs = 'faqs';
    $login = 'login';
}
$flage = true;
if (majesticsupport::$_config['tplink_home_' . $linkname] == 1) {
    $linkarray[] = array(
        'class' => 'mjtc-support-homeclass',
        'link' => majesticsupport::makeUrl(array('mjsmod' => 'majesticsupport', 'mjslay' => 'controlpanel')),
        'title' => esc_html(__('Dashboard', 'majestic-support')),
        'mjsmod' => '',
        'imgtitle' => 'Dashboard-icon',
    );
    $flage = false;
}
if (majesticsupport::$_config['tplink_openticket_' . $linkname] == 1) {
    $module = $isUserStaff ? 'agent' : 'ticket';
    $linkarray[] = array(
        'class' => 'mjtc-support-openticketclass',
        'link' => majesticsupport::makeUrl(array('mjsmod' => $module, 'mjslay' => $addticket)),
        'title' => esc_html(__('Submit Ticket', 'majestic-support')),
        'mjsmod' => 'ticket',
        'imgtitle' => 'Submit Ticket',
    );
    $flage = false;
}
if (majesticsupport::$_config['tplink_tickets_' . $linkname] == 1) {
    $module = $isUserStaff ? 'agent' : 'ticket';
    $linkarray[] = array(
        'class' => 'mjtc-support-myticket',
        'link' => majesticsupport::makeUrl(array('mjsmod' => $module, 'mjslay' => $myticket)),
        'title' => esc_html(__('My Tickets', 'majestic-support')),
        'mjsmod' => 'ticket',
        'imgtitle' => 'My Tickets',
    );
    $flage = false;
}

if (majesticsupport::$_config['tplink_login_logout_' . $linkname] == 1) {
    $loginval = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('set_login_link');
    $loginlink = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('login_link');
    if ($loginval == 3){
        $hreflink = wp_login_url();
    }
    else if ($loginval == 2 && $loginlink != "") {
        $hreflink = $loginlink;
    } else {
        $hreflink = majesticsupport::makeUrl(array('mjsmod' => 'majesticsupport', 'mjslay' => 'login'));
    }
    if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
        $title = esc_html(__('Login', 'majestic-support'));
    } else {
        $title = esc_html(__('Log out', 'majestic-support'));
        $hreflink = wp_logout_url(majesticsupport::makeUrl(array('mjsmod' => 'majesticsupport', 'mjslay' => 'controlpanel')));

        if (isset($_COOKIE['majesticsupport-socialmedia']) && !empty($_COOKIE['majesticsupport-socialmedia'])) {
            switch ($_COOKIE['majesticsupport-socialmedia']) {
                case 'facebook':
                    $hreflink = majesticsupport::makeUrl(array('mjsmod' => 'sociallogin', 'task' => 'logout', 'action' => 'mstask', 'media' => 'facebook', 'mspageid' => majesticsupport::getPageid()));
                    break;
                case 'linkedin':
                    $hreflink = majesticsupport::makeUrl(array('mjsmod' => 'sociallogin', 'task' => 'logout', 'action' => 'mstask', 'media' => 'linkedin', 'mspageid' => majesticsupport::getPageid()));
                    break;
                default:
                    $hreflink =  $hreflink = wp_logout_url(majesticsupport::makeUrl(array('mjsmod' => 'majesticsupport', 'mjslay' => 'controlpanel')));
                    break;
            }
        }

    }
    $linkarray[] = array(
        'class' => 'mjtc-support-loginlogoutclass',
        'link' => $hreflink,
        'title' => $title,
        'mjsmod' => 'ticket',
        'imgtitle' => 'Login',
    );
    $flage = false;
}

$extramargin = '';
$displayhidden = '';
if ($flage)
    $displayhidden = 'display:none;';
$div .= '
		<div id="ms-header-main-wrapper" style="' . esc_attr($displayhidden) . '">';
$div .= '<div id="ms-header" class="' . esc_attr($extramargin) . '" >';
$div .= '<div id="ms-tabs-wrp" class="" >';
if (isset($linkarray))
    foreach ($linkarray as $link) {
	    $id='';
        if(in_array('multiform', majesticsupport::$_active_addons) && majesticsupport::$_config['show_multiform_popup'] == 1){
            if($link['class'] == "mjtc-support-openticketclass"){ $id="id=multiformpopup";}
        }
        $div .= '<div class="ms-header-tab ' . esc_attr($link['class']) . '"><a '.esc_attr($id).' class="mjtc-cp-menu-link" href="' . esc_url($link['link']) . '">' . esc_html($link['title']) . '</a></div>';
    }

$div .= '</div></div>
        <div class="mjtc-transparent-header"></div>
    </div>';
echo wp_kses($div, MJTC_ALLOWED_TAGS);
?>
<?php if(in_array('multiform', majesticsupport::$_active_addons)){ ?>
<div id="multiformpopupblack" style="display:none;"></div>
<div id="multiformpopup" class="" style="display:none;">
    <!-- Select User Popup -->
    <div class="ms-multiformpopup-header">
        <div class="multiformpopup-header-text">
            <?php echo esc_html(__('Select Form','majestic-support')); ?>
        </div>
        <div class="multiformpopup-header-close-img">
            <img src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/close-icon-white.png">
        </div>
    </div>
    <div id="records">
        <div id="records-inner">
            <div class="mjtc-staff-searc-desc">
                <?php echo esc_html(__('No Record Found','majestic-support')); ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php
$majesticsupport_js ="
    jQuery(document).ready(function ($) {

        jQuery('a#multiformpopup').click(function (e) {
            e.preventDefault();
            var url = jQuery('a#multiformpopup').prop('href');
            jQuery('div#multiformpopupblack').show();
            var ajaxurl = '". esc_url(admin_url('admin-ajax.php'))."';
            jQuery.post(ajaxurl, {action: 'mjsupport_ajax',mjsmod: 'multiform', task: 'getmultiformlistajax', url: url, '_wpnonce':'". esc_attr(wp_create_nonce("get-multi-form-list-ajax"))."'}, function(data) {
                if (data) {
                    jQuery('div#records').html('');
                    jQuery('div#records').html(data);
                }
            });
            jQuery('div#multiformpopup').slideDown('slow');
        });

        jQuery('div#multiformpopupblack , div.multiformpopup-header-close-img').click(function(e) {
            jQuery('div#multiformpopup').slideUp('slow', function() {
                jQuery('div#multiformpopupblack').hide();
            });
        });
    });

    function MJTC_makeFormSelected(divelement) {
        jQuery('div.mjtc-support-multiform-row').removeClass('selected');
        jQuery(divelement).addClass('selected');
    }

    function MJTC_makeMultiFormUrl(id) {
        var oldUrl = jQuery('a.mjtc-multiformpopup-link').attr('id'); // Get current url
        var opt = '?';
        var found = oldUrl.search('&');
        if (found > 0) {
            opt = '&';
        }
        var found = oldUrl.search('[\?\]');
        if (found > 0) {
            opt = '&';
        }
        var newUrl = oldUrl + opt + 'formid=' + id; // Create new url
        window.location.href = newUrl;
    }

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  
