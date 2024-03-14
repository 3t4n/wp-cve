<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Frontend;
/**
 * Enqueue stuff on the frontend
 */
class Rewrite extends Base
{
    public $priority = PHP_INT_MAX;
    public $injected = false;

    /**
     * Initialize the class.
     *
     * @return void
     */
    public function register()
    {
        \add_action('template_redirect', [$this, 'buffer_start'], $this->priority);
        \add_filter('autoptimize_filter_js_exclude', function ($exclude) {
            return $exclude . ", data-wpmeteor";
        }, 2);
        \add_filter('sgo_javascript_combine_excluded_inline_content', function ($excluded_inline_content) {
            $excluded_inline_content[] = "wpmeteor";
            return $excluded_inline_content;
        });
    }

    public function buffer_start()
    {
        ob_start([$this, "rewrite"]);
    }

    public function rewrite($buffer)
    {
        foreach(headers_list() as $header) {
            if (preg_match('/^content\-type/i', $header) && !preg_match('/^content\-type\s*:\s*text\/html/i', $header)) {
                $this->canRewrite = false;
                break;
            }
        }

        if (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php') {
            $this->canRewrite = false;
        }

        if (!$this->canRewrite) {
            return $buffer;
        }

        if ($this->injected) {
            return \apply_filters(WPMETEOR_TEXTDOMAIN . '-frontend-rewrite', $buffer, \wpmeteor_get_settings());
        }

        /* settings */
        $_wpmeteor = \apply_filters(WPMETEOR_TEXTDOMAIN . '-frontend-adjust-wpmeteor', [], \wpmeteor_get_settings());
        $_wpmeteor['v'] = WPMETEOR_VERSION;
        $_wpmeteor['rest_url'] = get_rest_url();
        /* /settings */

        /* blocker */
        if (isset($_SERVER['QUERY_STRING']) && preg_match('/wpmeteordebug/', $_SERVER['QUERY_STRING'])) {
            $script = file_get_contents(__DIR__ . '/../assets/js/public/public-debug.js');
            $script = preg_replace('/\/\/# sourceMappingURL=public-debug.js.map/', '//# sourceMappingURL=' . \plugins_url('assets/js/public/public-debug.js.map', WPMETEOR_PLUGIN_ABSOLUTE), $script);
        } else {
            $script = file_get_contents(__DIR__ . '/../assets/js/public/public.js');
            $script = preg_replace('/\/\/# sourceMappingURL=public.js.map/', '', $script);
        }
        /* /blocker */

        /* ie redirect */
        $ieredirect = file_get_contents(__DIR__ . '/../assets/js/public/ie-redirect.js');
        $ieredirect = preg_replace('/\/\/# sourceMappingURL=ie-redirect\.js\.map/', '', $ieredirect);
        /* /ie redirect */

        $EXTRA = defined('WPMETEOR_EXTRA_ATTRS') ? constant('WPMETEOR_EXTRA_ATTRS') : '';

        $tag = "<script data-wpmeteor-nooptimize=\"true\" {$EXTRA}>";
        $comment = apply_filters('wpmeteor_comment', "<!-- Optimized with WP Meteor v" . WPMETEOR_VERSION . " - https://wordpress.org/plugins/wp-meteor/ -->");
        $inject = "{$comment}{$tag}var _wpmeteor=" . json_encode($_wpmeteor) . ";{$ieredirect}</script>{$tag}{$script}</script>";
        $buffer = preg_replace('/(<script\b)/i', "{$inject}$1", $buffer, 1);
        $this->injected = true;
        return \apply_filters(WPMETEOR_TEXTDOMAIN . '-frontend-rewrite', $buffer, \wpmeteor_get_settings());
    }
}
