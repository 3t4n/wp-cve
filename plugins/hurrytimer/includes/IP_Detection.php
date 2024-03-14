<?php

namespace Hurrytimer;

use Exception;
use Hurrytimer\Utils\Helpers;

class IP_Detection
{
    /**
     * Evergreen IP entries table name.
     *
     * @var string
     */
    private $table;

    /**
     * @var int $campaignId
     */
    protected $campaignId;

    public function __construct( $campaignId )
    {
        global $wpdb;

        $this->table      = "{$wpdb->prefix}hurrytimer_evergreen";
        $this->campaignId = $campaignId;
    }


    /**
     * Find IP log.
     *
     * @return array|null
     */
    public function getCurrentUserEndDate()
    {
        global $wpdb;

        $ipAddress = $this->fetchCurrentUserIpAddress();

        try {
            $sql = $wpdb->prepare(
                "SELECT id, client_expires_at FROM {$this->table}
                WHERE countdown_id = %d
                AND client_ip_address = %s",
                $this->campaignId,
                $ipAddress
            );

            /**
             * @var object{id, client_expires_at} $result
             */
            $found = $wpdb->get_row( $sql );

            if ( !$found ) {
                $found = $this->getCurrentUserEndDateLegacy( $this->campaignId, $ipAddress );
            }
            if ( !$found ) {
                return null;
            }

            return [ $found->id, $found->client_expires_at ];
        } catch ( Exception $e ) {
            return null;
        }
    }

    public function fetchCurrentUserIpAddress()
    {
        return Helpers::ip_address();
    }

    public function getCurrentUserResetToken()
    {
        global $wpdb;

        $ipAddress = $this->fetchCurrentUserIpAddress();

        try {
            $sql = $wpdb->prepare(
                "SELECT id, reset_token FROM {$this->table}
                WHERE countdown_id = %d
                AND client_ip_address = %s",
                $this->campaignId,
                $ipAddress
            );

            $found = $wpdb->get_row( $sql );
            if ( !$found ) {
                $found = $this->getCurrentUserEndDateLegacy( $this->campaignId, $ipAddress );
            }

            if ( empty( $found ) || !is_object( $found ) ) {
                return false;
            }

            return [ $found->id, $found->reset_token ];

        } catch ( Exception $e ) {
            return false;
        }
    }


    /**
     * This provide compatibility for prior versions.
     *
     * @param int $campaignId
     * @param string $ipAddress
     *
     * @return array|null
     *
     */
    private function getCurrentUserEndDateLegacy( $campaignId, $ipAddress )
    {
        $_ipAddress = sanitize_key( $ipAddress );

        $transient = sprintf( 'ht_cdt_%d_%s', $campaignId, $_ipAddress );

        $endDateTS = get_transient( $transient );

        if ( !$endDateTS ) {
            return null;
        }
        $created = $this->create( $campaignId, $endDateTS );

        if ( $created ) {
            // Clear transients.
            delete_transient( $transient );
            delete_transient( "{$transient}_status" );

            return $this->getCurrentUserEndDate();
        }

        return null;
    }

    /**
     * Create a new IP log.
     *
     * @param int $campaignId
     * @param int $endDateTS
     * @return bool
     */
    public function create( $campaignId, $endDateTS )
    {
        global $wpdb;

        // Auto-destroy after one month.
        $destroy_at = Helpers::date_later( MONTH_IN_SECONDS );

        $result = $wpdb->insert(
            $this->table,
            [
                'countdown_id'      => $campaignId,
                'client_ip_address' => $this->fetchCurrentUserIpAddress(),
                'client_expires_at' => $endDateTS,
                'destroy_at'        => $destroy_at
            ]
        );

        return $result !== false;
    }

    /**
     *
     * Update client expiration time.
     *
     * @param $id
     * @param $client_expires_at
     *
     * @return false|int
     */
    public function update( $id, $client_expires_at )
    {
        global $wpdb;

        $destroy_at = Helpers::date_later( MONTH_IN_SECONDS );

        return $wpdb->update(
            $this->table,
            compact( 'client_expires_at', 'destroy_at' ),
            compact( 'id' )
        );
    }

    public function updateOrCreate( $id, $endDateTS )
    {
        if ( empty( $id ) ) {
            return $this->create( $this->campaignId, $endDateTS );
        } else {
            $this->update( $id, $endDateTS );
        }
    }

    public function updateCurrentUserResetToken( $id, $reset_token )
    {
        global $wpdb;

        if ( empty( $id ) ) {
            return false;
        }
        return $wpdb->update(
            $this->table,
            compact( 'reset_token' ),
            compact( 'id' )
        );
    }

    /**
     * Delete given countdown and IP entry.
     *
     * @param int $campaignId
     * @param bool $currentUser
     * @return void
     */
    public function forget( $campaignId, $currentUser = false )
    {
        global $wpdb;
        $where = [ 'countdown_id' => $campaignId ];
        if ( $currentUser ) {
            $where[ 'client_ip_address' ] = $this->fetchCurrentUserIpAddress();
        }
        $wpdb->delete( $this->table, $where );
    }

    function forgetAll( $currentUser = false )
    {
        global $wpdb;
        if ( $currentUser ) {
            $where[ 'client_ip_address' ] = $this->fetchCurrentUserIpAddress();
            $wpdb->delete( $this->table, $where );
        } else {
            $wpdb->query( "delete from {$this->table}" );
        }
      
    }

}
