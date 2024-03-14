<?php

namespace FluentSupport\App\Hooks\Handlers;
use FluentSupport\App\Models\Meta;


class ReCaptchaHandler
{

    public static function validateRecaptcha($token,$secret = null,$recaptchaVersion = null)
    {

        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

        if(!$secret){
            $reCaptchaSettingsData = Meta::where('object_type', '_fs_recaptcha_settings')->first();
            $settings = maybe_unserialize($reCaptchaSettingsData->value, []);
            $recaptchaVersion = $settings["reCaptcha_version"];
            $secret = $settings['secretKey'];
        }

        $response = wp_remote_post($verifyUrl, [
            'body'   => [
                'secret'   => $secret,
                'response' => $token
            ],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $result = json_decode(wp_remote_retrieve_body($response), true);

        if ('recaptcha_v3' === $recaptchaVersion) {
            $score = $result['score'] ?? 0;
            $checkScore = apply_filters('fluent_support/recaptcha_v3_ref_score', 0.5);

            return $score >= $checkScore;
        }

        return $result['success'];
    }
}
