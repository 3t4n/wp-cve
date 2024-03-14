<?php

namespace Ikana\EmbedVideoThumbnail;

use Ikana\EmbedVideoThumbnail\Dto\VideoData;
use Ikana\EmbedVideoThumbnail\Provider\Dailymotion;
use Ikana\EmbedVideoThumbnail\Provider\Facebook;
use Ikana\EmbedVideoThumbnail\Provider\ProviderInterface;
use Ikana\EmbedVideoThumbnail\Provider\Vimeo;
use Ikana\EmbedVideoThumbnail\Provider\Youtube;

class EmbedVideoThumbnail
{
    /**
     * @var  string
     */
    private $pluginBasename;

    /**
     * @var string
     */
    private $pluginURL;

    /**
     * @var object
     */
    private $wpdb;

    /**
     * @var ProviderInterface[]
     */
    private $providers = [];

    /**
     * @var MobileDetect
     */
    private $mobileDetect;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $css;

    public function __construct($wpdb)
    {
        $this->pluginBasename = IKANAWEB_EVT_BASENAME;
        $this->pluginURL = IKANAWEB_EVT_URL;
        $this->wpdb = $wpdb;
        $this->options = $this->getOptions();
        $this->mobileDetect = new MobileDetect();
    }

    public function boot()
    {
        add_filter("plugin_action_links_$this->pluginBasename", [$this, 'addSettingsLink']);
        add_action('after_setup_theme', [$this, 'init']);
    }

    public function init()
    {
        if (!$this->isBootable()) {
            return;
        }
        $this->registerProviders();
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_filter('the_content', [$this, 'replaceContent'], 11);
        add_filter('ikevt_video_to_thumbnail', [$this, 'replaceUrl'], 11);
        add_action('widgets_init', [$this, 'loadWidget']);
        $this->replaceInAcfFields();
    }

    public function isBootable()
    {
        global $post;

        return
            $this->isEnabled()
            && $this->isDeviceEnabled()
            && $this->isPostEnabled($post);
    }

    public function isEnabled()
    {
        return !empty($this->options['global']['enable']);
    }

    public function isDeviceEnabled()
    {
        return
            (
                !empty($this->options['device']['desktop']['enable'])
                && !$this->mobileDetect->isMobile()
                && !$this->mobileDetect->isTablet()
            ) ||
            (
                !empty($this->options['device']['tablet']['enable'])
                && $this->mobileDetect->isMobile()
                && $this->mobileDetect->isTablet()
            ) ||
            (
                !empty($this->options['device']['mobile']['enable'])
                && $this->mobileDetect->isMobile()
                && !$this->mobileDetect->isTablet()
            ) ||
            (
                !empty($this->options['device']['amp']['enable'])
                && \function_exists('is_amp')
                && is_amp()
            );
    }

    public function addSettingsLink(array $links)
    {
        $links[] = '<a href="tools.php?page=ikanaweb_evt_options">' . __('Settings') . '</a>';

        return $links;
    }

    public function enqueueScripts()
    {
        wp_register_script('ikn-evt-js-main', $this->pluginURL . '/assets/js/main.js', ['jquery']);
        wp_enqueue_script('ikn-evt-js-main');
        wp_register_style('ikn-evt-css-main', $this->pluginURL . '/assets/css/main.css');
        wp_enqueue_style('ikn-evt-css-main');

        if ($this->isInternetExplorer()) {
            wp_add_inline_style('ikn-evt-css-main', $this->getInlineCss());
        }
    }

    public function loadWidget()
    {
        register_widget(Widget::class);
    }

    public function registerProviders()
    {
        $providers = $this->getDefaultProviders();
        $extensions = apply_filters('ikevt_extension_providers', []);

        $providers = array_merge($providers, $extensions);

        foreach ($providers as $provider) {
            if (!isset($this->options[$provider->getName()])
                || !empty($this->options[$provider->getName()]['enable'])
            ) {
                $this->addProvider($provider);
            }
        }

        return $this;
    }

    public function replaceContent($content)
    {
        foreach ($this->getProviders() as $provider) {
            $data = $provider->parseContent($content);

            foreach ($data as $toReplace => $info) {
                $fakeHrefReplacer = md5($toReplace);
                $content = str_replace(
                    [
                        'href="' . $toReplace,
                        $toReplace,
                        'href="' . $fakeHrefReplacer,
                    ],
                    [
                        'href="' . $fakeHrefReplacer,
                        $this->renderView($info),
                        'href="' . $toReplace,
                    ],
                    $content
                );
            }
        }

        return $content;
    }

    public function uninstall()
    {
        if (is_dir(IKANAWEB_EVT_IMAGE_PATH)) {
            Utils::recursiveRemoveDirectory(IKANAWEB_EVT_IMAGE_PATH);
        }
    }

    public function getOptions()
    {
        global $ikanaweb_evt;

        $ikanaweb_evt['template']['container_class'] = 'ikn-evt-frame';

        $data = [];

        foreach ($ikanaweb_evt as $key => $value) {
            $path = explode('--', $key);

            $arr = [];
            $tmp = &$arr;
            foreach ($path as $segment) {
                $tmp[$segment] = [];
                $tmp = &$tmp[$segment];
            }
            $tmp = $value;
            $data = array_merge_recursive($data, $arr);
        }

        return $data;
    }

    public function renderView(VideoData $data)
    {
        $templateVars = [
            'id' => $data->getId(),
            'source' => $data->getSource(),
            'embed-url' => $data->getUrl(),
            'title' => $data->getTitle(),
            'container_class' => $this->options['template']['container_class'],
            'thumb' => $data->getThumbnail(),
            'css' => $this->getViewCss(),
            'alt' => $data->getId(),
        ];

        if (!empty($templateVars['title'])) {
            $templateVars['alt'] = htmlspecialchars($templateVars['title'], ENT_QUOTES, 'UTF-8');
        }

        //handle IE
        if ($this->isInternetExplorer()) {
            ob_start();
            include 'Template/ie.php';

            return ob_get_clean();
        }

        ob_start();
        include 'Template/srcdoc.php';
        $templateVars['srcdoc'] = str_replace('"', '', ob_get_clean());

        ob_start();
        include 'Template/iframe.php';

        return ob_get_clean();
    }

    /**
     * @return ProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }

    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @return ProviderInterface[]
     */
    public function getDefaultProviders()
    {
        return [
            new Youtube($this->options),
            new Vimeo($this->options),
            new Dailymotion($this->options),
            new Facebook($this->options),
        ];
    }

    public function isPostEnabled($post)
    {
        if (empty($post)) {
            return true;
        }

        $postTypeEnabled = empty($this->options['global']['post_type'])
            || !empty($this->options['global']['post_type'][$post->post_type]);
        $disabledPosts = [];
        if (!empty($this->options['global']['exclude_posts'])) {
            $disabledPosts = explode("\n", $this->options['global']['exclude_posts']);
        }

        $postIDEnabled = !\in_array($post->ID, $disabledPosts, true);

        return $postTypeEnabled && $postIDEnabled;
    }

    private function replaceInAcfFields()
    {
        if (empty($this->options['global']['acf_fields'])) {
            return;
        }

        $acfFields = array_filter(explode("\n", $this->options['global']['acf_fields']));

        foreach ($acfFields as $field) {
//            add_filter("acf/format_value/name=$field", [$this, 'acfFormatValue'], 12, 3);
        }
    }

    public function replaceUrl($url)
    {
        $url = html_entity_decode($url, ENT_QUOTES, 'UTF-8');
        return $this->replaceContent($url);
    }

    public function acfFormatValue($value, $post_id, $field)
    {
        return $this->replaceContent($value);
    }

    private function getViewCss()
    {
        if (null !== $this->css) {
            return $this->css;
        }

        $css = file_get_contents($this->pluginURL . '/assets/css/iframe.css');
        $css .= $this->getInlineCss();

        $this->css = $css;

        return $this->css;
    }

    private function isInternetExplorer()
    {
        $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');

        return preg_match('~MSIE|Internet Explorer~i', $ua)
            || (false !== strpos($ua, 'Trident/7.0') && false !== strpos($ua, 'rv:11.0'));
    }

    private function getInlineCss()
    {
        $style = '';
        foreach ($this->getProviders() as $provider) {
            $buttonImage = $this->options[$provider->getName()]['embed']['playbutton']['url'];
            if (empty($buttonImage)) {
                $buttonImage = IKANAWEB_EVT_URL . '/assets/images/play-default.png';
            }
            $style .= '.' . $this->options['template']['container_class'] . '[data-source="' . $provider->getName() . "\"] 
            .ikn-evt-play-button {background:url('" . $buttonImage . "') no-repeat;}";
        }

        return $style;
    }
}
