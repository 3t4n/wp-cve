<?php
/**
 * Plugin Name: Goracash
 * Plugin URI: http://www.goracash.com
 * Description: Plugin for Goracash content's integration
 * Version: 1.1
 * Author: David Patiashvili
 * Author URI: https://www.patiashvili.fr
 * Text Domain: goracash
 * Domain Path: /languages/
 * Licence: GPL3+
 *
 * Goracash is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Goracash is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with Goracash.
 */

if (!class_exists('Goracash_Plugin')) {

    class Goracash_Plugin
    {
        public function __construct()
        {
            # if submodule
            if (file_exists(plugin_dir_path( __FILE__ ) . 'includes/goracash-api-php-client/autoload.php')) {
                include_once plugin_dir_path( __FILE__ ) . 'includes/goracash-api-php-client/autoload.php';
            }
            # if composer
            else if (file_exists(plugin_dir_path( __FILE__ ) . 'vendor/autoload.php')) {
                include_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
            }
            else {
                throw new Exception('Not found depedencies from composer or submodule');
            }
            include_once plugin_dir_path( __FILE__ ) . 'includes/iframe.php';
            include_once plugin_dir_path( __FILE__ ) . 'includes/banner.php';

            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_settings'));
            add_action('admin_enqueue_scripts', function() {
                wp_register_style('goracash_admin_bootstrap_css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', false, '3.3.5');
                wp_register_style('goracash_admin_fontaweome_css', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', false, '4.4.0');
                wp_register_style('goracash_admin_css', plugin_dir_url( __FILE__ ) . 'css/admin.css', false, '0.1');

                wp_register_script('goracash_admin_js', plugin_dir_url( __FILE__ ) . 'js/admin.js', array(), '0.1');

                wp_enqueue_style('goracash_admin_bootstrap_css');
                wp_enqueue_style('goracash_admin_fontaweome_css');
                wp_enqueue_style('goracash_admin_css');
                wp_enqueue_script('goracash_admin_js');
            });
            add_action('plugins_loaded', function() {
                load_plugin_textdomain('goracash', false, basename(dirname(__FILE__)) . '/languages/');
            });

            new Goracash_Iframe();
            new Goracash_Banner();
        }

        public function register_settings()
        {
            register_setting('goracash_settings', 'goracash_idw');
            register_setting('goracash_settings', 'goracash_client_id');
            register_setting('goracash_settings', 'goracash_client_secret');
            register_setting('goracash_settings', 'goracash_ads_thematic');
            register_setting('goracash_settings', 'goracash_ads_advertiser');
            register_setting('goracash_settings', 'goracash_ads_default_lang');
            register_setting('goracash_settings', 'goracash_ads_default_market');
            register_setting('goracash_settings', 'goracash_ads_popexit');
            register_setting('goracash_settings', 'goracash_ads_top_bar');
            register_setting('goracash_settings', 'goracash_ads_force_ssl');
        }

        public function add_admin_menu()
        {
            add_menu_page('Goracash', 'Goracash', 'manage_options', 'goracash_settings', array($this, 'settings_html'), 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAYAAAA71pVKAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo1Mzc2RTYzNDNENDYxMUU1QTJBRkM0QjkxNzEyNEY3MiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo1Mzc2RTYzNTNENDYxMUU1QTJBRkM0QjkxNzEyNEY3MiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjUzNzZFNjMyM0Q0NjExRTVBMkFGQzRCOTE3MTI0RjcyIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjUzNzZFNjMzM0Q0NjExRTVBMkFGQzRCOTE3MTI0RjcyIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+dtKclwAAAtdJREFUeNqcU2tIk2EUfr7v29y+zc2t0mbOkUwZVpZ2QaKyC9hlSWHmtFoGEUUlGYXZhX5oEJQY0R8jKqMfBeUgEazAojBrq8TwUkm6i6uRbk7d5pq7+PZtUKj0qwcOL4f3fTjPOc95KUII/he86Ulk6GeK72lT6a/O9+vDw0PzARLmL1Ba2dV5rfH52iY6Qe6e/p76U9nX8mTfSG3NtaDNnETx+YhTpYFJTMKU34+w3QaKZe1zTlRVS3X77/xlR8kTL5/vGFiaSr5pkoijREsixjYyA6MjZLyhnphzNcR56VxdlBMNasrrkfwoK+wIfOnOYJetAHu1HmaPD329vRjx+SCRJiAnJxvZ6WrA+RP2A0WQ7N5XITt47AbtN77JD1oGMhhWBPbIyRjRYR4ACQXhuX8LbfrdqNqwBoW6EnxyjSK1oREew4Oa0KBFxVSuyy2aaH+1iZeiQnJVNRYkK6DRaLDE2oe1UhZUihJymxlbc1finvEjFIuzkCbgC/1dnW4afH6QawACiQQWqxWNBkNsFu63r4E1G2DRFqNy0AmVrR836+pgt9kwsWgpwv19eTxBZtZHmpMc4fphvOM4X12D/u8/cLa0DM4jeky6vDgqjYMoEuLq8FCk3Ybxrk4EQsEEnnD5qjeCRVkd/vbXK5Q9HTCajDhVfhynh4exs0AH/ScTZDIZ2PJKgGZAcaoEoy4EROLBmM+/2l9tcRze+4wWx0N19xGYJdl4ZzLhQ3c3QtzEo2SFUolEuRwL1WpMXahAfP52Hf54NtZQf+Zb+lxiWZ1JfM+bZ9gc5CIQPScDxHn5InEcKmkhoRCPmr7bvubGMlftpSvh7zaFaO1GiDcXIC5dE5M6+bUH3mYDaJG4df7123uYufNc1OyPEXEOKbxNjw/4XjwrCNutahAIaaHAzyQrP0sKdj2UFuvvg2EiM3b7X4iMueeQQIAjs35aJh+bff9bgAEA2AlhbZ3e9vMAAAAASUVORK5CYII=', 81);
            add_submenu_page('goracash_settings', __('Settings', 'goracash'), __('Settings', 'goracash'), 'manage_options', 'goracash_settings', array($this, 'settings_html'));
        }

        public function get_value($key, $array, $default)
        {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        }

        public function get_dropdown($values, $value)
        {
            $content = '';
            foreach ($values as $key => $label) {
                $content .= sprintf('<option value="%s" %s>%s</option>',
                    $key,
                    $key == $value ? 'selected="selected"' : '',
                    $label
                );
            }
            return $content;
        }

        public function settings_html()
        {
            if (!get_option('goracash_idw')) {
                printf('<div class="alert alert-warning"><strong>%s</strong>: %s <a href="https://account.goracash.com/my/tokens">%s</a></div>',
                    __('Warning', 'goracash'),
                    __('Your affiliate ID is missing.', 'goracash'),
                    __('Learn more', 'goracash')
                );
            }

            $this->check_api_credentials();

            ob_start(); submit_button(); $button = ob_get_clean();
            ob_start(); settings_fields('goracash_settings'); $settings = ob_get_clean();

            printf('<h1>%s</h1>
                <form method="post" action="options.php">
                    %s
                    <h3 class="title">%s</h3>
                    <p>%s <a href="https://account.goracash.com/signup?utm_source=wordpress">%s</a></p>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th><label for="goracash_idw">%s</label></th>
                                <td>
                                    <input type="text" name="goracash_idw" id="goracash_idw" value="%s" class="regular-text" size="4" style="width: 50px;" maxlength="4" />
                                    <span class="description">%s</span>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="goracash_client_id">%s</label></th>
                                <td>
                                    <input type="text" name="goracash_client_id" id="goracash_client_id" value="%s" class="regular-text" />
                                    <span class="description">%s <a href="https://account.goracash.com/my/tokens">%s</a></span>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="goracash_client_secret">%s</label></th>
                                <td>
                                    <input type="text" name="goracash_client_secret" id="goracash_client_secret" value="%s" class="regular-text" />
                                    <span class="description">%s <a href="https://account.goracash.com/my/tokens">%s</a></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="title">%s</h3>
                    <p>%s</p>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th><label for="goracash_ads_thematic">%s</label></th>
                                <td>
                                    <select name="goracash_ads_thematic" id="goracash_ads_thematic">
                                        %s
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="goracash_ads_advertiser">%s</label></th>
                                <td>
                                    <select name="goracash_ads_advertiser" id="goracash_ads_advertiser">
                                        %s
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="goracash_ads_default_lang">%s</label></th>
                                <td>
                                    <select name="goracash_ads_default_lang" id="goracash_ads_default_lang">
                                        %s
                                    </select>
                                    <span class="description">%s</span>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="goracash_ads_default_market">%s</label></th>
                                <td>
                                    <select name="goracash_ads_default_market" id="goracash_ads_default_market">
                                        %s
                                    </select>
                                    <span class="description">%s</span>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="goracash_ads_force_ssl">%s</label></th>
                                <td>
                                    <input type="checkbox" name="goracash_ads_force_ssl" id="goracash_ads_force_ssl" value="1" %s />
                                    <span class="description">%s</span>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="goracash_ads_popexit">%s</label></th>
                                <td>
                                    <input type="checkbox" name="goracash_ads_popexit" id="goracash_ads_popexit" value="1" %s />
                                    <span class="description">%s</span>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="goracash_ads_top_bar">%s</label></th>
                                <td>
                                    <input type="checkbox" name="goracash_ads_top_bar" id="goracash_ads_top_bar" value="1" %s />
                                    <span class="description">%s</span>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>%s</td>
                            </tr>
                        </tbody>
                    </table>
                </form>',
                get_admin_page_title(),
                $settings,
                __('General settings', 'goracash'),
                __('To use our different tools, you need a Goracash account.', 'goracash'),
                __('Create a free account', 'goracash'),
                __('Identifier', 'goracash'),
                get_option('goracash_idw'),
                __('This ID is 4 digits and are located at the top right of your affiliate interface', 'goracash'),
                __('Your API client ID', 'goracash'),
                get_option('goracash_client_id'),
                __('This identifier is generated in your affiliate interface.', 'goracash'),
                __('Learn more', 'goracash'),
                __('Your API secret Key', 'goracash'),
                get_option('goracash_client_secret'),
                __('This information is provided to you in your affiliate interface.', 'goracash'),
                __('Learn more', 'goracash'),
                __('Content settings', 'goracash'),
                __('This section allows you to specify the default settings to all content you use, they can be overloaded by the individual on.', 'goracash'),
                __('Thematic', 'goracash'),
                $this->get_dropdown(Goracash_Banner::get_thematics(), get_option('goracash_ads_thematic')),
                __('Advertiser', 'goracash'),
                $this->get_dropdown(Goracash_Banner::get_advertisers(), get_option('goracash_ads_advertiser')),
                __('Default language', 'goracash'),
                $this->get_dropdown(Goracash_Banner::get_langs(), get_option('goracash_ads_default_lang')),
                __('This language will be used in case we do not detect the language of the user.', 'goracash'),
                __('Default market', 'goracash'),
                $this->get_dropdown(Goracash_Banner::get_markets(), get_option('goracash_ads_default_market')),
                __('This market will be used in case we did not detect the location of the user.', 'goracash'),
                __('Forcing SSL', 'goracash'),
                get_option('goracash_ads_force_ssl') ? 'checked="checked"' : '',
                __('This option allows you to force all request in safe mode.', 'goracash'),
                __('Enable Pop-Exit', 'goracash'),
                get_option('goracash_ads_popexit') ? 'checked="checked"' : '',
                __('This option enables our popexit on your entire site.', 'goracash'),
                __('Enable Top-Bar', 'goracash'),
                get_option('goracash_ads_top_bar') ? 'checked="checked"' : '',
                __('This option enables our top bar on your entire site.', 'goracash'),
                $button
            );
        }

        public function check_api_credentials($required = false)
        {
            $client_id = get_option('goracash_client_id');
            $client_secret = get_option('goracash_client_secret');
            if (!$client_id || !$client_secret) {
                if ($required) {
                    printf('<div class="alert alert-danger"><strong>%s</strong>: %s <a href="https://account.goracash.com/my/tokens">%s</a></div>',
                        __('Error', 'goracash'),
                        __('To access these statistics please enter your API credentials.', 'goracash'),
                        __('Learn more', 'goracash')
                    );
                    return false;
                }
                printf('<div class="alert alert-warning"><strong>%s</strong>: %s <a href="https://account.goracash.com/my/tokens">%s</a></div>',
                    __('Warning', 'goracash'),
                    __('API credentials are missing.', 'goracash'),
                    __('Learn more', 'goracash')
                );
                return false;
            }

            $client = new \Goracash\Client();
            $client->setClientId($client_id);
            $client->setClientSecret($client_secret);

            try {
                $client->authenticate();
            }
            catch (Exception $e) {
                printf('<div class="alert alert-danger"><strong>%s</strong>: %s (%s)</div>',
                    __('Error', 'goracash'),
                    __('An error occured when validating your authentication information.', 'goracash'),
                    $e->getMessage()
                );
                return false;
            }
        }

    }

}

new Goracash_Plugin();