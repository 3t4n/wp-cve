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

namespace WP_Meteor\Blocker\FirstInteraction;

use WP_Meteor\Blocker\Event;
/**
 * Provide Import and Export of the settings of the plugin
 */
class UltimateReorder extends Base
{
    public $adminPriority = -1;
    public $priority = 99;
    public $tab = 'ultimate';
    public $title = 'Maximum available speed';
    public $description = ""; //"Delays script loading to 2 seconds";
    public $disabledInUltimateMode = false;
    public $defaultEnabled = false;

    public $pattern = [['.*', '']];

    public function initialize()
    {
        parent::initialize();
        \add_filter(WPMETEOR_TEXTDOMAIN . '-frontend-rewrite', [$this, 'frontend_rewrite'], $this->priority, 2);
    }

    public function backend_display_settings()
    {
        echo '<div id="' . $this->id . '" class="ultimate"
                    data-prefix="' . $this->id . '" 
                    data-title="' . $this->title . '"></div>';
    }

    public function backend_save_settings($sanitized, $settings)
    {
        // $exists = isset($sanitized[$this->id]['enabled']);
        $merged = array_merge($settings[$this->id], $sanitized[$this->id] ?: []);
        $merged['enabled'] = true;
        $sanitized[$this->id] = $merged;
        return $sanitized;
    }

    /* triggered from wpmeteor_load_settings */
    public function load_settings($settings)
    {
        $settings[$this->id] = isset($settings[$this->id])
            ? $settings[$this->id]
            : ['enabled' => true];

        $settings[$this->id]['id'] = $this->id;
        $settings[$this->id]['delay'] = isset($settings[$this->id]['delay']) ? (int) $settings[$this->id]['delay'] : 0;
        // $settings[$this->id]['after'] = 'REORDER';
        $settings[$this->id]['description'] = $this->description;
        // var_dump($settings); exit;
        return $settings;
    }

    public function frontend_rewrite($buffer, $settings)
    {
        // Fast Velocity Minify Delay JS compatibility
        /*
        if (is_plugin_active('fast-velocity-minify/fvm.php')) {
            $buffer = preg_replace('/\s+type=([\'"])fvm-script-delay\1/i', ' type=\'text/javascript\'', $buffer);
        }
        */

        /*
        if (is_plugin_active('wp-rocket/wp-rocket.php')) {
            $buffer = preg_replace('/\s+type=([\'"])rocketlazyloadscript\1\s+data-rocket-type=([\'"])text\/javascript\2/i', ' type=\'text/javascript\'', $buffer);
            // type="rocketlazyloadscript" data-rocket-type='text/javascript'
        }*/

        $EXTRA = defined('WPMETEOR_EXTRA_ATTRS') ? constant('WPMETEOR_EXTRA_ATTRS') : '';

        $REPLACEMENTS = [];
        $searchOffset = 0;
        while (preg_match('/<script\b[^>]*?>/is', $buffer, $matches, PREG_OFFSET_CAPTURE, $searchOffset)) {
            $offset = $matches[0][1];
            $searchOffset = $offset + 1;
            if (preg_match('/<\/\s*script>/is', $buffer, $endMatches, PREG_OFFSET_CAPTURE, $matches[0][1])) {
                $len = $endMatches[0][1] - $matches[0][1] + strlen($endMatches[0][0]);
                // $everything = substr($buffer, $matches[0][1], $len);
                $tag = $matches[0][0];
                $closingTag = $endMatches[0][0];

                $hasSrc = preg_match('/\s+src=/i', $tag);
                $hasType = preg_match('/\s+type=/i', $tag);
                $shouldReplace = !$hasType || preg_match('/\s+type=([\'"])((application|text)\/(javascript|ecmascript|html|template)|module)\1/i', $tag);
                $noOptimize = preg_match('/data-wpmeteor-nooptimize="true"/i', $tag);
                if ($shouldReplace && !$hasSrc) {
                    // inline script
                    $content = substr($buffer, $matches[0][1] + strlen($matches[0][0]), $endMatches[0][1] - $matches[0][1] - strlen($matches[0][0]));
                    if (!$noOptimize && apply_filters('wpmeteor_exclude', false, $content)) {
                        $tag = preg_replace('/^<script\b/i', "<script {$EXTRA} data-wpmeteor-nooptimize=\"true\"", $tag);
                    }
                    $replacement = $tag . "WPMETEOR[" . count($REPLACEMENTS) . "]WPMETEOR" . $closingTag;
                    $REPLACEMENTS[] = $content;
                    $buffer = substr_replace($buffer, $replacement, $offset, $len);
                    continue;
                }
            }
        }

        $buffer = preg_replace_callback('/<script\b[^>]*?>/is', function ($matches) use ($EXTRA) {
            list($tag) = $matches;

            $result = $tag;
            if (!preg_match('/\s+data-src=/i', $result)
                && !preg_match('/\s+data-wpmeteor-type=/i', $result) 
                && !preg_match('/\s+data-pmdelayedscript=/i', $result) 
                && !preg_match('/data-wpmeteor-nooptimize="true"/i', $result)
                && !preg_match('/data-rocketlazyloadscript=/i', $result)) {

                $src = preg_match('/\s+src=([\'"])(.*?)\1/i', $result, $matches)
                    ? $matches[2]
                    : null;
                $id = preg_match('/\s+id=([\'"])(.*?)\1/i', $result, $matches)
                    ? $matches[2]
                    : null;
                if (!$src) {
                    // trying to detect src without quotes
                    $src = preg_match('/\s+src=([\/\w\-\.\~\:\[\]\@\!\$\?\&\#\(\)\*\+\,\;\=\%]+)/i', $result, $matches)
                        ? $matches[1]
                        : null;
                }
                $hasType = preg_match('/\s+type=/i', $result);
                $isJavascript = !$hasType
                    || preg_match('/\s+type=([\'"])((application|text)\/(javascript|ecmascript)|module)\1/i', $result)
                    || preg_match('/\s+type=((application|text)\/(javascript|ecmascript)|module)/i', $result);
                if ($isJavascript) {
                    if ($id && apply_filters('wpmeteor_exclude', false, $id)) {
                        return preg_replace('/<script/i', "<script {$EXTRA} ", $result);
                    }
                    if ($src) {
                        if (apply_filters('wpmeteor_exclude', false, $src)) {
                            return preg_replace('/<script/i', "<script {$EXTRA} ", $result);
                        }
                        $result = preg_replace('/\s+src=/i', " data-wpmeteor-src=", $result);
                        // $result = preg_replace('/\s+(async|defer|integrity)\b/i', " data-wpmeteor-\$1", $result);
                    }
                    if ($hasType) {
                        $result = preg_replace('/\s+type=([\'"])module\1/i', " type=\"javascript/blocked\" data-wpmeteor-type=\"module\" ", $result);
                        $result = preg_replace('/\s+type=module\b/i', " type=\"javascript/blocked\" data-wpmeteor-type=\"module\" ", $result);
                        $result = preg_replace('/\s+type=([\'"])(application|text)\/(javascript|ecmascript)\1/i', " type=\"javascript/blocked\" data-wpmeteor-type=\"$2/$3\" ", $result);
                        $result = preg_replace('/\s+type=(application|text)\/(javascript|ecmascript)\b/i', " type=\"javascript/blocked\" data-wpmeteor-type=\"$1/$2\" ", $result);
                    } else {
                        $result = preg_replace('/<script/i', "<script type=\"javascript/blocked\" data-wpmeteor-type=\"text/javascript\" ", $result);
                    }
                    // $result = preg_replace('/<script/i', "<script {$EXTRA} data-wpmeteor-after=\"REORDER\"", $result);
                    $result = preg_replace('/<script/i', "<script {$EXTRA}", $result);
                }
            }
            return $result; // preg_replace('/\s*data-wpmeteor-nooptimize="true"/i', '', $result);
        }, $buffer);

        // we don't rewrite 
        /* $buffer = preg_replace_callback('/<(body|img|iframe|script)\b[^>]*?>/is', function ($matches) { */
        $buffer = preg_replace_callback('/<(html|body|img|iframe)\b[^>]*?>/is', function ($matches) {
            list($result) = $matches;

            // rewrite is called twice, so we don't want to rewrite twice
            if (preg_match('/data-wpmeteor-onload=/', $result)) {
                return $result;
            }

            $result = preg_replace('/\s+onload=/i', sprintf(' onload="window.dispatchEvent(new CustomEvent(\'%s\', { detail: { event: event, target: this } }))" data-wpmeteor-onload=', Event::EVENT_ELEMENT_LOADED), $result);
            $result = preg_replace('/\s+onerror=/i', sprintf(' onerror="window.dispatchEvent(new CustomEvent(\'%s\', { detail: { event: event, target: this }))" data-wpmeteor-onerror=', Event::EVENT_ELEMENT_LOADED), $result);
            return $result;
        }, $buffer);

        /**
         * this should go the last, because there can be images inserted by scripts as with https://wbuac.progresssite.pro/ 
         * effectively breaking JSON
         * covered by test/test.php
         */
        $buffer = preg_replace_callback('/WPMETEOR\[(\d+)\]WPMETEOR/', function ($matches) use (&$REPLACEMENTS) {
            return $REPLACEMENTS[(int)$matches[1]];
        }, $buffer);

        return $buffer;
    }

    public function backend_adjust_wpmeteor($wpmeteor, $settings)
    {
        $wpmeteor['blockers'] = isset($wpmeteor['blockers']) ? $wpmeteor['blockers'] : [];
        $wpmeteor['blockers'][$this->id] = $settings[$this->id];

        if (isset($settings['v']) && version_compare($settings['v'], '2.3.6', '<') && 3 === (int) $settings[$this->id]['delay']) {
            $wpmeteor['blockers'][$this->id]['delay'] = -1;
        }
        return $wpmeteor;
    }

    public function frontend_adjust_wpmeteor($wpmeteor, $settings)
    {
        if (!$settings[$this->id]['enabled']) {
            $wpmeteor['rdelay'] = 0;
        } else {
            if (isset($settings['v']) && version_compare($settings['v'], '2.3.6', '<')) {
                $wpmeteor['rdelay'] = (int) $settings[$this->id]['delay'] === 3
                    ? 86400000 # one day
                    : (int) $settings[$this->id]['delay'] * 1000;
            } else {
                $wpmeteor['rdelay'] = (int) $settings[$this->id]['delay'] < 0
                    ? 86400000 # one day
                    : (int) $settings[$this->id]['delay'] * 1000;
            }
        }
        // var_dump($settings); exit;
        $wpmeteor['preload'] = true;
        return $wpmeteor;
    }
}
