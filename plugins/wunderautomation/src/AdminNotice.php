<?php

namespace WunderAuto;

/**
 * Class AdminNotice
 */
class AdminNotice
{
    /**
     * @var AdminNotice
     */
    private static $instance;

    /**
     * @var array<string, array<string, \stdClass>>
     */
    protected $notices;

    /**
     * Constructor
     */
    public function __construct()
    {
        self::$instance = $this;
    }

    /**
     * @return AdminNotice
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('init', $this, 'init', 9);
        $loader->addAction('admin_notices', $this, 'printNotices');
    }

    /**
     * Initializes variables
     *
     * @return void
     */
    public function init()
    {
        $default_notices = ['error' => [], 'warning' => [], 'success' => [], 'info' => []];
        $this->notices   = array_merge($default_notices, get_option('wunderauto-notices', []));
    }

    /**
     * Queues up a message to be displayed to the user
     *
     * @param string $message     The text to show the user
     * @param string $type        'update' for a success or notification message, or 'error' for an error message
     * @param bool   $persist     Should the notice continue to be shown until activiely dismissed?
     * @param bool   $dismissible User dismissible?
     * @param string $id          Optional message id
     *
     * @return void
     */
    public function addNotice($message, $type = 'info', $persist = true, $dismissible = true, $id = '')
    {
        if ($id === '') {
            $id = md5($message);
        }

        if (isset($this->notices[$type][$id])) {
            return;
        }

        $this->notices[$type][$id] = (object)[
            'message'     => (string)$message,
            'persist'     => $persist,
            'dismissable' => $dismissible,
        ];

        update_option('wunderauto-notices', $this->notices);
    }

    /**
     * Displays updates and errors
     *
     * @return void
     */
    public function printNotices()
    {
        $updated = false;
        foreach ($this->notices as $type => $notices) {
            foreach ($notices as $id => $notice) {
                $classes = [
                    'notice',
                    'notice-' . $type,
                ];

                $classes[] = $notice->dismissable ? 'is-dismissible' : '';
                $classes   = array_map(function ($el) {
                    return sanitize_html_class($el);
                }, $classes);
                $class     = join(' ', $classes);
                $message   = $notice->message;

                if (!$notice->persist) {
                    unset($this->notices[$type][$id]);
                    $updated = true;
                }

                require(WUNDERAUTO_BASE . '/admin/notices/notice.php');
            }
        }
        update_option('wunderauto-notices', $this->notices);
    }

    /**
     * Dismiss a notice by ID
     *
     * @param string $dismissedID
     *
     * @return void
     */
    public function dismiss($dismissedID)
    {
        foreach ($this->notices as $type => $notice) {
            unset($this->notices[$type][$dismissedID]);
        }
        update_option('wunderauto-notices', $this->notices);
    }
}
