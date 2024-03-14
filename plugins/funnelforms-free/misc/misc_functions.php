<?php

function fnsf_create_timestamp() {
    $tz = 'Europe/Amsterdam';
    $timestamp = time();
    $dt = new DateTime("now", new DateTimeZone($tz));
    $dt->setTimestamp($timestamp);

    return $dt->format('YmdH');
}

function fnsf_create_timestamp_day() {
    $tz = 'Europe/Amsterdam';
    $timestamp = time();
    $dt = new DateTime("now", new DateTimeZone($tz));
    $dt->setTimestamp($timestamp);

    return $dt->format('Ymd');
}

function fnsf_af_base64UrlEncode($inputStr)
{
    return strtr(base64_encode($inputStr), '+/=', '-_,');
}


function af_base64UrlDecode($inputStr)
{
    return base64_decode(strtr($inputStr, '-_,', '+/='));
}

function af_get_months_array(){
    return array(
        1 => __('January', 'funnelforms-free' ),
        2 => __('February', 'funnelforms-free' ),
        3 => __('March', 'funnelforms-free' ),
        4 => __('April', 'funnelforms-free' ),
        5 => __('May', 'funnelforms-free' ),
        6 => __('June', 'funnelforms-free' ),
        7 => __('July', 'funnelforms-free' ),
        8 => __('August', 'funnelforms-free' ),
        9 => __('September', 'funnelforms-free' ),
        10 => __('October', 'funnelforms-free' ),
        11 => __('November', 'funnelforms-free' ),
        12 => __('December', 'funnelforms-free' )
    );
}

function fnsf_af2_get_post_content( $post ) {
    if(empty($post) || $post == null) return null;
    $post_content = get_post_field( 'post_content', $post );
    if(empty($post_content) || $post_content == null) return null;
    $post_content_array = unserialize(urldecode($post_content));
    $post_content_array = stripslashes_deep($post_content_array);

    return $post_content_array;
}

function fnsf_af2_str_contains( $haystack, $needle ) {
    if(function_exists('str_contains')) return str_contains($haystack, $needle);
    else return strpos($haystack, $needle) !== false;
}

function addToURL( $key, $value, $url) {
    $info = parse_url( $url );
    parse_str( $info['query'], $query );
    return $info['scheme'] . '://' . $info['host'] . $info['path'] . '?' . http_build_query( $query ? array_merge( $query, array($key => $value ) ) : array( $key => $value ) );
}

function fnsf_af2GetAnswersTranslations() {
    $supported_languages = array('de_AT', 'de_CH', 'de_DE', 'en_US', 'es_ES', 'fr_FR', 'it_IT', 'fr_FR');

    $translations = array();

    foreach ($supported_languages as $language) {
        switch_to_locale($language);

        $translated_text = __('[ANSWERS]', 'funnelforms-free');

        array_push($translations, $translated_text);

        restore_previous_locale();
    }

    return $translations;
}