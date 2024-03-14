<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth;

class TokenOption
{
    const TIME_BUFFER_SECONDS = 600;
    private $option_name;
    private $is_testing;
    public function __construct(string $option_name = 'fs_ups_token', bool $is_testing = \false)
    {
        $this->option_name = $option_name;
        $this->is_testing = $is_testing;
    }
    public function get_option_name() : string
    {
        if ($this->is_testing) {
            return $this->option_name . '_testing';
        }
        return $this->option_name;
    }
    public function get()
    {
        $this->clear_wp_cache();
        $token = \json_decode(\get_option($this->get_option_name(), '[]'), \true);
        if ($this->token_is_valid($token)) {
            return $token;
        }
        return [];
    }
    private function clear_wp_cache()
    {
        \wp_cache_delete($this->get_option_name(), 'options');
    }
    private function token_is_valid($token) : bool
    {
        if (empty($token)) {
            return \false;
        }
        if (empty($token['access_token'] ?? '')) {
            return \false;
        }
        if (empty($token['expires_in'] ?? '')) {
            return \false;
        }
        if (empty($token['issued_at'] ?? '')) {
            return \false;
        }
        $payload = $this->get_token_payload($token['access_token']);
        if (empty($payload)) {
            return \false;
        }
        if (empty($payload['DisplayName'] ?? '')) {
            return \false;
        }
        return \true;
    }
    public function set(array $token)
    {
        $this->clear_wp_cache();
        if (!\update_option($this->get_option_name(), \json_encode($token), \false)) {
            $this->clear_wp_cache();
            \update_option($this->get_option_name(), \json_encode($token), \false);
        }
    }
    public function update_issued_at_to_current_time()
    {
        $token = $this->get();
        $token['issued_at'] = (\time() - self::TIME_BUFFER_SECONDS) * 1000;
        $this->set($token);
    }
    public function is_token_expired() : bool
    {
        return $this->get_expires_at() < \time();
    }
    public function get_access_token() : string
    {
        $token = $this->get();
        return $token['access_token'] ?? '';
    }
    public function get_refresh_token() : string
    {
        $token = $this->get();
        return $token['refresh_token'] ?? '';
    }
    public function get_expires_in() : int
    {
        $token = $this->get();
        return (int) $token['expires_in'];
    }
    public function get_issued_at() : int
    {
        $token = $this->get();
        return (int) \round($token['issued_at'] / 1000);
    }
    public function get_expires_at() : int
    {
        $token = $this->get();
        return (int) \round($token['issued_at'] / 1000) + (int) $token['expires_in'];
    }
    public function get_token_payload($access_token = null) : array
    {
        [$header, $payload, $signature] = \explode(".", $access_token ?? $this->get_access_token());
        return \json_decode(\base64_decode($payload), \true);
    }
}
