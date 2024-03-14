<?php

namespace Ikana\EmbedVideoThumbnail;

class PluginReview
{
    /**
     * @var
     */
    private $slug;

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $timeLimit;

    /**
     * @var
     */
    private $reviewUrl;

    /**
     * PluginReview constructor.
     *
     * @param $args
     * @param mixed $slug
     * @param mixed $name
     * @param mixed $reviewUrl
     * @param mixed $timeLimit
     */
    public function __construct($slug, $name, $reviewUrl, $timeLimit = 604800)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->reviewUrl = $reviewUrl;
        $this->timeLimit = $timeLimit;

        add_action('admin_init', [$this, 'init']);
        add_action('admin_init', [$this, 'setNoticeChoice'], 5);
    }

    public function init()
    {
        if (false !== get_site_option($this->slug . '-no-review')) {
            return;
        }

        $activationDate = get_site_option($this->slug . '-activation-date');

        if (false === $activationDate) {
            add_site_option($this->slug . '-activation-date', time());
        }

        if (time() - $activationDate >= $this->timeLimit) {
            add_action('admin_notices', [$this, 'showAdminNotice']);
        }
    }

    public function showAdminNotice()
    {
        $reviewUrl = $this->reviewUrl;
        $laterUrl = wp_nonce_url(admin_url('?tools.php?page=ikanaweb_evt_options&later_review=1'), $this->slug . '_later_review');
        $dismissUrl = wp_nonce_url(admin_url('?tools.php?page=ikanaweb_evt_options&no_review=1'), $this->slug . '_no_review');

        echo '<div class="notice">';
        echo '<p>';
        echo sprintf(
            __("Merci d'utiliser notre extension <strong>%s</strong>, est ce que vous l'aimez ?<br/>Laissez nous un avis pour nous dire ce que vous en pensez !", $this->slug),
            $this->name
        );
        echo '</p>';
        echo sprintf('<p><a href="%s" target="_blank">' . __('Donner mon avis', $this->slug) . '</a></p>', $reviewUrl);
        echo sprintf('<p><a href="%s">' . __('Peut être plus tard', $this->slug) . '</a></p>', $laterUrl);
        echo sprintf('<p><a href="%s">' . __("C'est déjà fait", $this->slug) . '</a></p>', $dismissUrl);
        echo '</div>';
    }

    public function setNoticeChoice()
    {
        if (!is_admin()
            || !isset($_GET['_wpnonce'])
            || !current_user_can('manage_options')
        ) {
            return;
        }

        if (!empty($_GET['later_review'])
            && wp_verify_nonce($_GET['_wpnonce'], $this->slug . '_later_review')
        ) {
            update_site_option($this->slug . '-activation-date', time());
        }

        if (!empty($_GET['no_review'])
            && wp_verify_nonce($_GET['_wpnonce'], $this->slug . '_no_review')
        ) {
            add_site_option($this->slug . '-no-review', true);
        }
    }
}
