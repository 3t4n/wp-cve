<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2024 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      adrotate_portable_hash
 Purpose:   Export/import adverts via a portable hash
 Since:		5.8.3
-------------------------------------------------------------*/
function adrotate_portable_hash($action, $data, $what = 'advert') {
	$source = get_option('siteurl');
	if(in_array("aes-128-cbc", openssl_get_cipher_methods())) {
		if($action == 'export') {
			$portable['meta'] = array('type' => $what, 'source' => $source, 'exported' => current_time('timestamp'));
			foreach($data as $key => $value) {
				if(empty($value)) $value = '';
				$advert[$key] = $value;
			}
			$portable['data'] = $advert;
			$portable = serialize($portable);
			return openssl_encrypt($portable, "aes-128-cbc", '983jdnn3idjk02id', false, 'oi1u23kj123hj7jd');
	    }

		if($action == 'import') {
			$data = openssl_decrypt($data, "aes-128-cbc", '983jdnn3idjk02id', false, 'oi1u23kj123hj7jd');
			$data = unserialize($data);
			if(is_array($data)) {
				if(array_key_exists('meta', $data) AND array_key_exists('data', $data)) {
					if($data['meta']['type'] == 'advert' AND $source != $data['meta']['source']) {
						return $data['data'];
					} else if($data['meta']['type'] == 'group') {
						// maybe
					} else if($data['meta']['type'] == 'schedule') {
						// maybe					
					} else {
						adrotate_return('adrotate', 514);
					}
				}
			}
			adrotate_return('adrotate', 513);
	    }
	
	}
}
?>