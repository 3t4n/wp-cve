<?php
/**
 * Created by PhpStorm.
 * User: MYN
 * Date: 5/9/2019
 * Time: 8:57 AM
 */
namespace DataPeen\FaceAuth;
class Config
{
	/**
	 * option name to save setting for this plugin (for plugin that only need one custom post to store all settings)
	 */
	const OPTION_NAME = 'face_factor_o_name';

    const TRANSIENT_TOKEN = 'datapeen_face_factor_transient_token';
	const COMMON_OPTION_NAME = 'face_factor_o_name_common';//this is the option name to store what shared between users (like the secret token key)
    const NAME = 'DataPeen Face Auth';
    const MENU_NAME = 'Face Auth';
    const SLUG = 'dp_face_auth';
    const TEXT_DOMAIN = 'dp_face_auth';

    const FACE_RECOGNIZE_URL = "https://datapeen.com/face-auth/api/v1/face-recognize/";
    const SITE_VERIFY_URL = "https://datapeen.com/face-auth/api/v1/verify-site/";
    const ADD_FACE_URL = "https://datapeen.com/face-auth/api/v1/add-face/";
    const REMOVE_FACE_URL = "https://datapeen.com/face-auth/api/v1/face-remove/";
    const GET_FACES_URL = "https://datapeen.com/face-auth/api/v1/get-face/";

}
