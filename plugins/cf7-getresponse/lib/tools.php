<?php
/*  Copyright 2013-2017 Renzo Johnson (email: renzojohnson at gmail.com)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

    *1: By default the key label for the name must be FNAME
    *2: parse first & last name
    *3: ensure we set first and last name exist
    *4: otherwise user provided just one name
    *5: By default the key label for the name must be FNAME
    *6: check if subscribed
    *bh: email_type
    *aw: double_optin
    *xz: update_existing
    *rd: replace_interests
    *gr: send_welcome
*/



function vcgr_author() {

	$author_pre = 'Contact form 7 Getresponse extension by ';
	$author_name = 'Renzo Johnson';
	$author_url = '//renzojohnson.com';
	$author_title = 'Renzo Johnson - Web Developer';

	$vcgr_author = '<p style="display: none !important">';
  $vcgr_author .= $author_pre;
  $vcgr_author .= '<a href="'.$author_url.'" ';
  $vcgr_author .= 'title="'.$author_title.'" ';
  $vcgr_author .= 'target="_blank">';
  $vcgr_author .= ''.$author_title.'';
  $vcgr_author .= '</a>';
  $vcgr_author .= '</p>'. "\n";

  return $vcgr_author;
}



function vcgr_referer() {

  // $vcgr_referer_url = $THE_REFER=strval(isset($_SERVER['HTTP_REFERER']));

  if(isset($_SERVER['HTTP_REFERER'])) {

    $vcgr_referer_url = $_SERVER['HTTP_REFERER'];

  } else {

    $vcgr_referer_url = 'direct visit';

  }

	$vcgr_referer = '<p style="display: none !important"><span class="wpcf7-form-control-wrap referer-page">';
  $vcgr_referer .= '<input type="hidden" name="referer-page" ';
  $vcgr_referer .= 'value="'.$vcgr_referer_url.'" ';
  $vcgr_referer .= 'size="40" class="wpcf7-form-control wpcf7-text referer-page" aria-invalid="false">';
  $vcgr_referer .= '</span></p>'. "\n";

  return $vcgr_referer;
}



function vcgr_getRefererPage( $form_tag ) {

  if ( $form_tag['name'] == 'referer-page' ) {

    $form_tag['values'][] = $_SERVER['HTTP_REFERER'];

  }

  return $form_tag;

}



if ( !is_admin() ) {

  add_filter( 'wpcf7_form_tag', 'vcgr_getRefererPage' );

}



define( 'VCGR_URL', '//renzojohnson.com/contributions/contact-form-7-getresponse-extension' );
define( 'VCGR_AUTH', '//renzojohnson.com' );
define( 'VCGR_AUTH_COMM', '<!-- campaignmonitor extension by Renzo Johnson -->' );
define( 'VCGR_NAME', 'Getresponse Contact Form 7 Extension' );
define( 'VCGR_SETT', admin_url( 'admin.php?page=wpcf7&post='.vcgr_get_latest_item().'&active-tab=4' ) );
define( 'VCGR_DON', 'https://www.paypal.me/renzojohnson' );


function vcgr_get_latest_item(){
    $args = array(
            'post_type'         => 'wpcf7_contact_form',
            'posts_per_page'    => -1,
            'fields'            => 'ids',
        );
    // Get Highest Value from CF7Forms
    $form = max(get_posts($args));
    $out = '';
    if (!empty($form)) {
        $out .= $form;
    }
    return $out;
}


function wpcf7_form_vcgr_tags() {
  $manager = WPCF7_FormTagsManager::get_instance();
  $form_tags = $manager->get_scanned_tags();
  return $form_tags;
}


function vcgr_mail_tags() {

  $listatags = wpcf7_form_vcgr_tags();
  $tag_submit = array_pop($listatags);
  $tagInfo = '';

    foreach($listatags as $tag){

      $tagInfo .= '<span class="mailtag code used">[' . $tag['name'].']</span>';

    }

  return $tagInfo;

}




