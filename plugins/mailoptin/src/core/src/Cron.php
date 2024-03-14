<?php

namespace MailOptin\Core;

use Carbon\Carbon;
use MailOptin\Core\Repositories\EmailCampaignRepository;
use MailOptin\Libsodium\LibsodiumSettingsPage;
use PAnD as PAnD;
use stdClass;

class Cron
{
    public function __construct()
    {
        add_action('init', [$this, 'create_recurring_schedule']);

        add_action('mo_daily_recurring_job', [$this, 'cleanup_old_leadbank_data']);

        add_action('mailoptin_admin_notices', function () {
            add_action('admin_notices', [$this, 'catch_late_email_digest_event_notice']);
        });
    }

    public function create_recurring_schedule()
    {
        if ( ! wp_next_scheduled('mo_hourly_recurring_job')) {
            // we are adding 10 mins to give room for timestamp/hourly checking to be correct.
            $tz = Carbon::now('UTC')->endOfHour()->addMinute(10)->timestamp;

            wp_schedule_event($tz, 'hourly', 'mo_hourly_recurring_job');
        }

        if ( ! wp_next_scheduled('mo_daily_recurring_job')) {
            wp_schedule_event(time(), 'daily', 'mo_daily_recurring_job');
        }
    }

    public function catch_late_email_digest_event_notice()
    {
        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

        if ( ! PAnD::is_admin_notice_active('catch_late_email_digest_event-7')) {
            return;
        }

        $digest_campaigns = EmailCampaignRepository::get_by_email_campaign_type(EmailCampaignRepository::POSTS_EMAIL_DIGEST, 1);

        if ( ! is_array($digest_campaigns) || empty($digest_campaigns)) return;

        $cron = wp_get_scheduled_event('mo_hourly_recurring_job');

        if ( ! $cron) return;

        if ( ! $this->is_late(wp_get_scheduled_event('mo_hourly_recurring_job'))) return;

        printf(
            '<div id="mailoptin-crontrol-late-message" data-dismissible="catch_late_email_digest_event-7" class="notice notice-warning is-dismissible"><p>%4$s %1$s</p><p><a target="_blank" href="%2$s">%3$s</a></p></div>',
            /* translators: %s: Help page URL. */
            esc_html__('One or more MailOptin\'s cron events have missed their schedule. This might cause your scheduled emails and tasks to stop working.', 'mailoptin'),
            'https://mailoptin.io/article/fix-cron-events-missing-schedules/',
            esc_html__('Learn more', 'mailoptin'),
            '<strong>' . esc_html__('Important:', 'mailoptin') . '</strong>'
        );
    }

    /**
     * Determines whether an event is late.
     *
     * An event which has missed its schedule by more than 10 minutes is considered late.
     *
     * @param stdClass $event The event.
     *
     * @return bool Whether the event is late.
     */
    public function is_late(stdClass $event)
    {
        $until = ($event->timestamp - time());

        return ($until < (0 - (10 * MINUTE_IN_SECONDS)));
    }

    /**
     * @return bool|int|void
     */
    public function cleanup_old_leadbank_data()
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

        if (class_exists('MailOptin\Libsodium\LibsodiumSettingsPage') && LibsodiumSettingsPage::mo_once_active()) return;

        global $wpdb;

        $table = $wpdb->prefix . Core::conversions_table_name;

        return $wpdb->query(
            "DELETE FROM $table WHERE DATEDIFF(NOW(), date_added) >= 90"
        );
    }

    /**
     * Singleton.
     *
     * @return Cron
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}