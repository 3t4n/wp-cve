<?php

namespace MinuteMedia\Ovp;

class Constants {

    /* Defaults */
    const PLUGIN_VERSION                = '1.6.4';
    const PLUGIN_PACKAGE_NAME           = 'wp-mm-videos';
    const PLUGIN_PREFIX                 = 'mm-video';
    const ENDPOINT_EMBED                = 'https://vms-players.minutemediaservices.com';
    const ENDPOINT_VIDEO_RECEIVER       = 'https://video-receiver-%s.minutemediaservices.com';
    const ENDPOINT_VIDEO_PLAYERS        = 'https://vms-players-service-%s.minutemediaservices.com';
    const ENDPOINT_IMAGES               = 'https://images2.minutemediacdn.com/image/upload/c_scale,f_auto,w_300/mm/vms/video_thumb'; // For cloudinary (production)
    const ENDPOINT_OAUTH                = 'https://minutemedia.auth0.com';
    const ENDPOINT_OAUTH_PATH           = '/oauth/token';
    const MM_DEFAULT_PLAYER_ID          = '01dnpe2h9bmdjdd233';
    const MM_ACCESS_TOKEN_EXPIRATION    = 20 * 24 * 60 * 60; // 20 days
    const MM_IAB_CATEGORIES_EXPIRATION  = 30 * 60; // 30 minutes

    /* Parameter Keys */
    const OPT_PLUGIN_VERSION            = 'mm_plugin_version';
    const OPT_CLIENT_ID                 = 'mm_client_id';
    const OPT_CLIENT_SECRET             = 'mm_client_secret';
    const OPT_TENANT_ID                 = 'mm_tenant_id';
    const OPT_PROPERTY_ID               = 'mm_property_id';
    const OPT_PLAYER_ID                 = 'mm_player_id';
    const OPT_ACCESS_TOKEN              = 'mm_access_token';
    const OPT_ACCESS_TOKEN_PLAYERS      = 'mm_access_token_players';
    const OPT_ENABLE_FEATURED_VIDEO     = 'mm_enable_featured_video';
    const OPT_ENABLE_VIDEO_UPLOAD       = 'mm_enable_video_upload';

    /* Misc */
    const HTTP_METHOD_GET               = 'GET';
    const HTTP_METHOD_POST              = 'POST';
    const HTTP_METHOD_PUT               = 'PUT';
    const HTTP_METHOD_PATCH             = 'PATCH';
    const HTTP_METHOD_DELETE            = 'DELETE';
    const HTTP_METHOD_HEAD              = 'HEAD';
    const HTTP_METHOD_OPTIONS           = 'OPTIONS';
    const HTTP_METHOD_CONNECT           = 'CONNECT';
    const HTTP_METHOD_TRACE             = 'TRACE';
}
