<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class urlStatsController {

    protected $db;
    protected $urls;
    protected $calcId;
    public function __construct( int $calcId ) {
        global $wpdb;
        $this->db     = $wpdb;
        $this->calcId = $calcId;
        $array        = $this->db->get_var( $this->db->prepare( "SELECT urlStatsArray FROM {$this->db->prefix}df_scc_forms WHERE id=%d", $calcId ) );

        if ( empty( $array ) ) {
            $array = '[]';
        }
        $this->urls = json_decode( $array, 1 );
    }
    public function __destruct() {
        // $this->db->close();
    }

    public function update( string $url ) {
        if ( in_array( $url, array_keys( $this->urls ) ) ) {
            ++$this->urls[ $url ];
        } else {
            $this->urls[$url] = 1;
        }

        return $this->save( json_encode( $this->urls, JSON_UNESCAPED_UNICODE ) );
    }

    private function save( string $urls ) {
        $query  = $this->db->prepare( "UPDATE `{$this->db->prefix}df_scc_forms` SET `urlStatsArray` =%s WHERE `{$this->db->prefix}df_scc_forms`.`id` = %d", [ $urls, $this->calcId ] );
        $status = $this->db->query( $query );

        return $status;
    }
}
