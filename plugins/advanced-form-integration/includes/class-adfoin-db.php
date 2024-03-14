<?php

class Advanced_Form_Integration_DB {

    /*
    * The table
    */
    public $table;

    /*
    * The WordPress DB instace
    */
    public $db;

    public function insert( $data ) {
        return $this->db->insert( $this->table, $data );
    }

    public function update( $data, $id ) {
        return $this->db->update( $this->table, $data, array( 'id' => $id ) );
    }

    public function delete( $id = '' ) {
        return $this->db->delete( $this->table, array( 'id' => $id ) );
    }

    public function get_results( $sql ) {
        return $this->db->get_results( $sql, 'ARRAY_A' );
    }

    public function get_var( $sql ) {
        return $this->db->get_var( $sql );
    }

    public function get_row( $sql ) {
        return $this->db->get_row( $sql, 'ARRAY_A' );
    }
}

new Advanced_Form_Integration_DB();