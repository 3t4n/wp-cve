<?php

namespace Wicked_Folders;

/**
 * Represents a dynamically-generated date folder.
 */
class Date_Dynamic_Folder extends Dynamic_Folder {

    private $year   = false;
    private $month  = false;
    private $day    = false;

    public function __construct( $args = array() ) {
        parent::__construct( $args );
    }

    public function pre_get_posts( $query ) {

        $this->parse_id();

        if ( $this->year ) {
            $query->set( 'year', $this->year );
        }

        if ( $this->month ) {
            $query->set( 'monthnum', $this->month );
        }

        if ( $this->day ) {
            $query->set( 'day', $this->day );
        }

    }

    /**
     * Parses the folder's ID to determine the year, month, and day that the
     * folder should filter by.
     */
    private function parse_id() {

        $date = substr( $this->id, 13 );

        $date = explode( '_', $date );

        if ( isset( $date[0] ) ) {
            $this->year = $date[0];
        }

        if ( isset( $date[1] ) ) {
            $this->month = $date[1];
        }

        if ( isset( $date[2] ) ) {
            $this->day = $date[2];
        }

    }

}
