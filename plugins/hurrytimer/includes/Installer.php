<?php

namespace Hurrytimer;

use Hurrytimer\Traits\Singleton;


/**
 *
 * @since      2.3.0
 * @package    Hurrytimer
 * @subpackage Hurrytimer/includes
 * @author     Nabil Lemsieh <contact@nabillemsieh.com>
 */
class Installer
{
    use Singleton;

    public function upgrade()
    {

        // Update table structure if available.
        $this->create_or_upgrade_evergreen_timers_tracking_table();

        // Preserve compatibility with versions prior 2.3.0
        $installed_version = get_option( 'hurrytimer_version' );

        // A workaround to find installed version.
        if ( !$installed_version ) {

            $campaigns = get_posts( [ 'post_type' => HURRYT_POST_TYPE, 'post_status' => 'any', 'fields' => 'ids' ] );
            if ( !empty( $campaigns ) ) {
                $installed_version = '2.2.28';
            }
        }

        if ( $installed_version && version_compare( $installed_version, '2.2.28', '<=' ) ) {
            $this->upgrade_2_2_28();
        }

        // Update the current version.
        update_option( 'hurrytimer_version', HURRYT_VERSION );
    }

    /**
     * Should we preserve compatibility with 2.2.28 and prior?
     *
     * @return bool|mixed
     */
    public function should_backward_compat_2_2_28_and_prior()
    {
        if ( version_compare( get_option( 'hurrytimer_version' ), '2.2.28', '<=' ) ) {
            return true;
        }

        return filter_var( get_option( 'hurrytimer_upgraded_2_2_28' ), FILTER_VALIDATE_BOOLEAN );

    }

    /**
     * Upgraded from 2.2.28?
     *
     * @return bool
     */
    public function has_upgraded_from_2_2_28_or_prior()
    {
        return filter_var( get_option( 'hurrytimer_upgraded_2_2_28' ), FILTER_VALIDATE_BOOLEAN );
    }

    /**
     * Backward compatibility with versions <= 2.2.28
     */
    public function upgrade_2_2_28()
    {
        add_option( 'hurrytimer_upgraded_2_2_28', '1' );
    }

    /**
     * Plugin activation hook
     *
     * @since    2.3.0
     */
    public function activate()
    {

        $this->create_or_upgrade_evergreen_timers_tracking_table();

        // Add the current version.
        add_option( 'hurrytimer_version', HURRYT_VERSION );

        // Rebuild css
        try {
           CSS_Builder::get_instance()->generate_css();
        } catch ( \Exception $e ) {

        }

    }

    private function create_or_upgrade_evergreen_timers_tracking_table()
    {

        global $wpdb;

        $table = "{$wpdb->prefix}hurrytimer_evergreen";
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
              id bigint(20) NOT NULL AUTO_INCREMENT,
              countdown_id bigint(20) unsigned NOT NULL,
              client_ip_address varchar(50) NOT NULL,
              expired tinyint(1) unsigned DEFAULT NULL,
              client_expires_at bigint(20) unsigned NOT NULL,
              reset_token varchar(20) NULL,
              destroy_at timestamp NULL DEFAULT NULL,
              PRIMARY KEY (id)
            ) {$charset_collate}";

        try{
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sql );
           }catch(\Exception $e){
                @$wpdb->query($sql);
        }

    }

}
