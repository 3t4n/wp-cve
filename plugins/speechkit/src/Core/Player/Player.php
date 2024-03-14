<?php

declare(strict_types=1);

namespace Beyondwords\Wordpress\Core\Player;

use Beyondwords\Wordpress\Component\Post\PostMetaUtils;
use Beyondwords\Wordpress\Component\Settings\PlayerUI\PlayerUI;
use Beyondwords\Wordpress\Component\Settings\PlayerUI\PlayerStyle;
use Beyondwords\Wordpress\Component\Settings\PlayerVersion\PlayerVersion;
use Beyondwords\Wordpress\Component\Settings\SettingsUtils;
use Beyondwords\Wordpress\Core\Environment;
use Beyondwords\Wordpress\Core\CoreUtils;
use Symfony\Component\DomCrawler\Crawler;

/**
 * The "Latest" BeyondWords Player.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 **/
class Player
{
    /**
     * Init.
     */
    public function init()
    {
        // Actions
        add_action('init', array($this, 'registerShortcodes'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

        // Filters
        add_filter('the_content', array($this, 'autoPrependPlayer'), 1000000);
        add_filter('newsstand_the_content', array($this, 'autoPrependPlayer'));
    }

    /**
     * Register shortcodes.
     *
     * @since 4.2.0
     */
    public function registerShortcodes()
    {
        add_shortcode('beyondwords_player', array($this, 'playerShortcode'));
    }

    /**
     * HTML output for the BeyondWords player shortcode.
     *
     * @since 4.2.0
     *
     * @param array $atts Shortcode attributes.
     *
     * @return string
     */
    public function playerShortcode()
    {
        return $this->playerHtml();
    }

    /**
     * Auto-prepends the BeyondWords player to WordPress content.
     *
     * @since 3.0.0
     * @since 4.2.0 Renamed from addPlayerToContent to autoPrependPlayer.
     * @since 4.2.0 Perform hasCustomPlayer() check here.
     *
     * @param string $content WordPress content.
     *
     * @return string
     */
    public function autoPrependPlayer($content)
    {
        if ($this->hasCustomPlayer($content)) {
            return $content;
        }

        return $this->playerHtml() . $content;
    }

    /**
     * Player HTML.
     *
     * Displays JS SDK variant of the BeyondWords audio player, for both
     * AMP and non-AMP content.
     *
     * @param WP_Post $post WordPress Post.
     *
     * @since 3.0.0
     * @since 3.1.0 Added _doing_it_wrong deprecation warnings
     *
     * @return string
     */
    public function playerHtml($post = false)
    {
        if (! ($post instanceof \WP_Post)) {
            $post = get_post($post);
        }

        if (! $post) {
            return '';
        }

        if (! $this->isPlayerEnabled($post)) {
            return '';
        }

        $projectId = PostMetaUtils::getProjectId($post->ID);

        if (! $projectId) {
            return '';
        }

        $contentId = PostMetaUtils::getContentId($post->ID);

        if (! $contentId) {
            return '';
        }

        // AMP or JS Player?
        if ($this->useAmpPlayer()) {
            $html = $this->ampPlayerHtml($post->ID, $projectId, $contentId);
        } else {
            $html = $this->jsPlayerHtml($post->ID, $projectId, $contentId);
        }

        /**
         * Filters the HTML of the BeyondWords Player.
         *
         * @since 4.0.0
         * @since 4.3.0 Applied to both AMP and no-AMP content.
         *
         * @param string $html      The HTML for the JS audio player. The audio player JavaScript may
         *                          fail to locate the target element if you remove or replace the
         *                          default contents of this parameter.
         * @param int    $postId    WordPress post ID.
         * @param int    $projectId BeyondWords project ID.
         * @param int    $contentId BeyondWords content ID.
         */
        $html = apply_filters('beyondwords_player_html', $html, $post->ID, $projectId, $contentId);

        /**
         * Filters the HTML of the BeyondWords player.
         *
         * Scheduled for removal in v5.0
         *
         * @deprecated 4.3.0 Replaced with beyondwords_player_html.
         *
         * @since 3.3.3
         * @since 4.3.0 Applied to both AMP and no-AMP content.
         *
         * @param string $html      The HTML for the JS audio player. The audio player JavaScript may
         *                          fail to locate the target element if you remove or replace the
         *                          default contents of this parameter.
         * @param int    $postId    WordPress post ID.
         * @param int    $projectId BeyondWords project ID.
         * @param int    $contentId BeyondWords content ID.
         */
        $html = apply_filters('beyondwords_js_player_html', $html, $post->ID, $projectId, $contentId);

        return $html;
    }

    /**
     * Has custom player?
     *
     * Checks the post content to see whether a custom player has been added.
     *
     * @since 3.2.0
     * @since 4.2.0 Pass $content as a parameter, check for [beyondwords_player] shortcode
     * @since 4.2.4 Check $content is a string
     *
     * @param string $content WordPress content.
     *
     * @return boolean
     */
    public function hasCustomPlayer($content)
    {
        if (! is_string($content)) {
            return false;
        }

        if (strpos($content, '[beyondwords_player]') !== false) {
            return true;
        }

        $crawler = new Crawler($content);

        return count($crawler->filterXPath('//div[@data-beyondwords-player="true"]')) > 0;
    }

    /**
     * JS Player HTML.
     *
     * Displays the HTML required for the JS player.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param int $postId    WordPress Post ID.
     * @param int $projectId BeyondWords Project ID.
     * @param int $contentId BeyondWords Content ID.
     *
     * @since 3.0.0
     * @since 3.1.0 Added speechkit_js_player_html filter
     * @since 4.2.0 Remove hasCustomPlayer() check from here.
     *
     * @return string
     */
    public function jsPlayerHtml($postId, $projectId, $contentId)
    {
        $html = '<div data-beyondwords-player="true" contenteditable="false"></div>';

        return $html;
    }

    /**
     * AMP Player HTML.
     *
     * Displays the HTML required for the AMP player.
     *
     * @param int $postId    WordPress Post ID.
     * @param int $projectId BeyondWords Project ID.
     * @param int $contentId BeyondWords Content ID.
     *
     * @since 3.0.0
     * @since 3.1.0 Added speechkit_amp_player_html filter
     *
     * @return string
     */
    public function ampPlayerHtml($postId, $projectId, $contentId)
    {
        $src = sprintf(Environment::getAmpPlayerUrl(), $projectId, $contentId);

        // Turn on output buffering
        ob_start();

        ?>
        <amp-iframe
            frameborder="0"
            height="43"
            layout="responsive"
            sandbox="allow-scripts allow-same-origin allow-popups"
            scrolling="no"
            src="<?php echo esc_url($src); ?>"
            width="295"
        >
            <amp-img
                height="150"
                layout="responsive"
                placeholder
                src="<?php echo esc_url(Environment::getAmpImgUrl()); ?>"
                width="643"
            ></amp-img>
        </amp-iframe>
        <?php

        $html = ob_get_clean();

        /**
         * Filters the HTML of the BeyondWords AMP audio player.
         *
         * This filter is scheduled to be removed in v5.0.
         *
         * @since 3.3.3
         *
         * @deprecated 4.3.0 beyondwords_player_html is now applied to AMP and non-AMP content.
         * @see Beyondwords\Wordpress\Core\Player\Player::playerHtml()
         *
         * @param string $html       The HTML for the AMP audio player.
         * @param int    $post_id    WordPress Post ID.
         * @param int    $project_id BeyondWords Project ID.
         * @param int    $contentId  BeyondWords Content ID.
         */
        $html = apply_filters('beyondwords_amp_player_html', $html, $postId, $projectId, $contentId);

        return $html;
    }

    /**
     * Should we show the BeyondWords audio player?
     *
     * We DO NOT want to show the player if:
     * 1. BeyondWords has been disabled in our plugin settings.
     * 2. The current post type has not been selected in our plugin settings.
     * 3. The current post has specifically been disabled from processing.
     *
     * The return value of this can be overriden with the WordPress
     * "beyondwords_post_player_enabled" filter.
     *
     * @param int|WP_Post (Optional) Post ID or WP_Post object. Default is global $post.
     *
     * @since 3.0.0
     * @since 3.3.4 Accept int|WP_Post as method parameter.
     * @since 4.0.0 Check beyondwords_player_ui custom field.
     *
     * @return bool
     **/
    public function isPlayerEnabled($post = null)
    {
        $post = get_post($post);

        if (! ($post instanceof \WP_Post)) {
            return false;
        }

        // Assume we can show the player
        $enabled = true;

        // Has 'Display Player' been unchecked?
        if (PostMetaUtils::getDisabled($post->ID)) {
            $enabled = false;
        }

        // Is the player ui enabled in plugin settings?
        if ($enabled) {
            $enabled = get_option('beyondwords_player_ui', PlayerUI::ENABLED) === PlayerUI::ENABLED;
        }

        /**
         * Filters the enabled/disabled (shown/hidden) status of the player for each post.
         *
         * Scheduled for removal in plugin version 5.0.0.
         *
         * @since 3.3.3
         *
         * @deprecated 4.3.0 Instead, return an empty string in the `beyondwords_player_html` filter to hide the player.
         *                   Alternatively, you can set `published` to `false` in the BeyondWords dashboard.
         *
         * @param boolean $enabled   Is the player enabled (shown) for this post?
         * @param int     $post_id    WordPress post ID.
         */
        $enabled = apply_filters('beyondwords_post_player_enabled', $enabled, $post->ID);

        return $enabled;
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 3.0.0
     *
     * @return void
     */
    public function enqueueScripts()
    {
        if (! is_singular()) {
            return;
        }

        if (get_option('beyondwords_player_ui', PlayerUI::ENABLED) === PlayerUI::DISABLED) {
            return;
        }

        // JS SDK Player inline script, filtered by $this->scriptLoaderTag()
        add_filter('script_loader_tag', array($this, 'scriptLoaderTag'), 10, 3);

        wp_enqueue_script(
            'beyondwords-sdk',
            Environment::getJsSdkUrl(),
            array(),
            null,
            true
        );
    }

    /**
     * Use the AMP player?
     *
     * There are multiple AMP plugins for WordPress, so multiple checks are performed.
     *
     * @since 3.0.7
     *
     * @return bool
     */
    public function useAmpPlayer()
    {
        // https://amp-wp.org/reference/function/amp_is_request/
        if (function_exists('amp_is_request')) {
            return \amp_is_request();
        }

        // https://ampforwp.com/tutorials/article/detect-amp-page-function/
        if (function_exists('ampforwp_is_amp_endpoint')) {
            return \ampforwp_is_amp_endpoint();
        }

        // https://amp-wp.org/reference/function/is_amp_endpoint/
        if (function_exists('is_amp_endpoint')) {
            return \is_amp_endpoint();
        }

        return false;
    }

    /**
     * Filters the HTML script tag of an enqueued script.
     *
     * @param string $tag    The <script> tag for the enqueued script.
     * @param string $handle The script's registered handle.
     * @param string $src    The script's source URL.
     *
     * @since 3.0.0
     * @since 4.0.0 Updated Player SDK and added `beyondwords_player_script_onload` filter
     *
     * @see https://developer.wordpress.org/reference/hooks/script_loader_tag/
     * @see https://stackoverflow.com/a/59594789
     *
     * @return string
     */
    public function scriptLoaderTag($tag, $handle, $src)
    {
        if ($handle === 'beyondwords-sdk') :
            if (! $this->usePlayerJsSdk()) {
                return '';
            }

            $post     = get_post();
            $params   = $this->jsPlayerParams($post);
            $playerUI = get_option('beyondwords_player_ui', PlayerUI::ENABLED);

            $paramsJson = wp_json_encode($params, JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES);

            if ($playerUI === PlayerUI::HEADLESS) {
                // Headless instantiates a player without a target
                $onload = 'new BeyondWords.Player(' . $paramsJson . ');';
            } else {
                // Standard mode instantiates player(s) with every div[data-beyondwords-player] as the target(s)
                $onload = <<<EOD
                    document.querySelectorAll("div[data-beyondwords-player]").forEach(function(el) {
                        new BeyondWords.Player({
                            ...$paramsJson,
                            target: el
                        });
                    });
                    EOD;
            }

            // strip newlines to prevent "invalid character" errors
            $onload = str_replace(array("\r", "\n"), '', $onload);

            // limit whitespace to 1 space for legibility
            $onload = preg_replace('/\s+/', ' ', $onload);

            /**
             * Filters the onload attribute of the BeyondWords Player script.
             *
             * Note that the strings should be in double quotes, because the output
             * of this is run through esc_js() before it is output into the DOM.
             *
             * @link https://developer.wordpress.org/reference/functions/esc_js/
             *
             * Also note that to support multiple players on one page, the
             * default script uses `document.querySelectorAll() to target all
             * instances of `div[data-beyondwords-player]` in the HTML source.
             * If this approach is removed then multiple occurrences of the
             * BeyondWords player in one page may not work as expected.
             *
             * @link https://github.com/beyondwords-io/player/blob/main/doc/getting-started.md#how-to-configure-it
             *
             * @since 4.0.0
             *
             * @param string $script The string value of the onload script.
             * @param array  $params The SDK params for the current post, including
             *                       `projectId` and `contentId`.
             */
            $onload = apply_filters('beyondwords_player_script_onload', $onload, $params);

            ob_start();

            if ($playerUI === PlayerUI::ENABLED || $playerUI === PlayerUI::HEADLESS) :
                ?>
                <script
                    data-beyondwords-sdk="true"
                    async
                    defer
                    src="<?php echo esc_url($src); ?>"
                    onload='<?php echo esc_js($onload); ?>'
                ></script>
                <?php
            endif;

            return ob_get_clean();
        endif;

        return $tag;
    }

    /**
     * JavaScript SDK parameters.
     *
     * Note that the default return value for this method is an associative array, but
     * the HTML output will be forced to an object due to `wp_json_encode($params, JSON_FORCE_OBJECT)`
     * in `Player::scriptLoaderTag()`.
     *
     * @since 3.1.0
     * @since 4.0.0 Use new JS SDK params format.
     *
     * @param WP_Post $post WordPress Post.
     *
     * @return array
     */
    public function jsPlayerParams($post)
    {
        if (!($post instanceof \WP_Post)) {
            return [];
        }

        $projectId   = PostMetaUtils::getProjectId($post->ID);
        $contentId   = PostMetaUtils::getContentId($post->ID);
        $playerStyle = PostMetaUtils::getPlayerStyle($post->ID);

        $params = [
            'projectId'   => is_numeric($projectId) ? (int)$projectId : $projectId,
            'contentId'   => is_numeric($contentId) ? (int)$contentId : $contentId,
            'playerStyle' => $playerStyle,
        ];

        $playerUI = get_option('beyondwords_player_ui', PlayerUI::ENABLED);

        if ($playerUI === PlayerUI::HEADLESS) {
            $params['showUserInterface'] = false;
        }

        /**
         * Use legacy JS SDK params if player version setting is "0": "Legacy"
         */
        if (SettingsUtils::useLegacyPlayer()) {
            $params = $this->convertLatestToLegacyParams($params);
        }

        /**
         * Filters the BeyondWords JavaScript SDK parameters.
         *
         * @since 4.0.0
         *
         * @param array $params The default JS SDK params.
         * @param int   $postId The Post ID.
         */
        $params = apply_filters('beyondwords_player_sdk_params', $params, $post->ID);

        return $params;
    }

    /**
     * Convert latest JS SDK params into legacy format.
     *
     * @since 4.0.0
     *
     * @see https://docs.beyondwords.io/docs/javascript-sdk-automatic-player
     *
     * @param array $latestParams Latest JS SDK params
     *
     * @return array Legacy JS SDK params
     */
    public function convertLatestToLegacyParams($latestParams)
    {
        $skBackend = Environment::getBackendUrl();
        $skBackendApi = Environment::getApiUrl();

        $legacyParams = [
            'projectId' => $latestParams['projectId'],
            'podcastId' => $latestParams['contentId'],
        ];

        if ($latestParams['playerStyle'] = 'large') {
            $legacyParams['playerType'] = 'manual';
        }

        if (strlen($skBackend)) {
            $legacyParams['skBackend'] = esc_url($skBackend);
        }

        if (is_admin()) {
            $legacyParams['processingStatus'] = true;

            if (strlen($skBackendApi)) {
                $legacyParams['skBackendApi'] = esc_url($skBackendApi);
            }
        }

        return $legacyParams;
    }

    /**
     * Use Player JS SDK?
     *
     * @since 3.0.7
     *
     * @return string
     */
    public function usePlayerJsSdk()
    {
        // AMP requests don't use the Player JS SDK
        if ($this->useAmpPlayer()) {
            return false;
        }

        // Both Gutenberg/Classic editors have their own player scripts
        if (CoreUtils::isGutenbergPage() || CoreUtils::isEditScreen()) {
            return false;
        }

        // Disable audio player in Preview, because we have not sent updates to BeyondWords API yet
        if (function_exists('is_preview') && is_preview()) {
            return false;
        }

        $post = get_post();

        if (! $post) {
            return false;
        }

        $projectId = PostMetaUtils::getProjectId($post->ID);
        if (! $projectId) {
            return false;
        }

        $contentId = PostMetaUtils::getContentId($post->ID);
        if (! $contentId) {
            return false;
        }

        return true;
    }
}
