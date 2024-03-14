<?php

class Requirements
{
    protected $minPhpVersion;
    protected $minWpVersion;
    protected $file;
    protected $activeWpVersion;

    public function __construct($minPhpVersion, $minWpVersion, $file)
    {
        $this->minPhpVersion = $minPhpVersion;
        $this->minWpVersion  = $minWpVersion;
        $this->file          = $file;
        global $wp_version;

        $this->activeWpVersion = $wp_version;
    }

    public function notCorrect()
    {
        if ($this->notCorrectMinPhpVersion()) {
            return true;
        }

        if ($this->notCorrectMinWpVersion()) {
            return true;
        }

        return false;
    }

    public function notCorrectMinPhpVersion()
    {
        return version_compare(PHP_VERSION, $this->minPhpVersion, '<');
    }

    public function notCorrectMinWpVersion()
    {
        return version_compare($this->activeWpVersion, $this->minWpVersion, '<');
    }

    public function minimumRequirementsNotice()
    {
        $minPhpVersionMessage = '<i>(<b>Admin Options Pages</b> requires a minimum <b>PHP</b> version of <b>' . $this->minPhpVersion . '</b>)</i>';
        $minWpVersionMessage  = '<i>(<b>Admin Options Pages</b> requires a minimum <b>Wordpress</b> version of <b>' . $this->minWpVersion . '</b>)</i>';

        print('<div class="notice notice-error is-dismissible">');
        print('<p>Plugin <b>Admin Options Pages</b> is <b>deactivated</b>.</p>');

        if ($this->notCorrectMinPhpVersion()) {
            vprintf(
                '<p><b>Yikes!</b> You are using <b>PHP</b> version <b>%s</b>. %s</p>',
                [PHP_VERSION, $minPhpVersionMessage]
            );
        }

        if ($this->notCorrectMinWpVersion()) {
            vprintf(
                '<p><b>Yikes!</b> You are using <b>WordPress</b> version <b>%s</b>. %s</p>',
                [$this->activeWpVersion, $minWpVersionMessage]
            );
        }

        print('</div>');
    }

    public function notCorrectAction()
    {
        add_action('admin_notices', [$this, 'minimumRequirementsNotice']);

        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        deactivate_plugins(plugin_basename($this->file));
    }
}
