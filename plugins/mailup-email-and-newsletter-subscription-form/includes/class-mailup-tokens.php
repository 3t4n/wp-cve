<?php

declare(strict_types=1);

/**
 * Fired during plugin activation.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.2.6
 *
 * @author     Your Name <email@example.com>
 */
class Mailup_Tokens
{
    protected $access_token;

    protected $refresh_token;

    public function __construct()
    {
        $tokens = $this->getTokens();

        if ($tokens) {
            $this->access_token = $tokens->access_token;
            $this->refresh_token = $tokens->refresh_token;
        }
    }

    public function hasTokens()
    {
        if ($this->refresh_token && $this->access_token) {
            return true;
        }

        return false;
    }

    public function get_access_token()
    {
        return $this->access_token;
    }

    public function get_refresh_token()
    {
        return $this->refresh_token;
    }

    public static function setTokens($value, $option = []): void
    {
        $tokens = json_decode($value);
        $mailup = Mailup::MAILUP_NAME();
        $tokens->expires_in = null;
        $tokens->state = null;

        if (!isset($option)) {
            $option['tokens'] = (array) $tokens;

            add_option($mailup, $option);
        } else {
            $option['tokens'] = (array) $tokens;
            update_option($mailup, $option);
        }
    }

    public function getTokens()
    {
        $options = Mailup_Model::get_option();

        if (isset($options['tokens'])) {
            return (object) $options['tokens'];
        }

        return null;
    }

    // public function setTokens($value)
    // {
    //     $tokens = json_decode($value);
    //     unset($tokens->expires_in);
    //     unset($tokens->state);

    //     if (!$this->options) {
    //         $this->options = array();
    //         $this->options['tokens'] = $tokens;
    //         add_option('mailup', serialize($obj));
    //     } else {
    //         $this->options['tokens'] = $tokens;
    //         update_option('mailup', serialize($this->options));
    //     }

    //     $this->access_token = $tokens->access_token;
    //     $this->refresh_token = $tokens->refresh_token;
    // }
}
