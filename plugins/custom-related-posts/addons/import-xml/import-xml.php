<?php

class CRP_Import_Xml extends CRP_Addon {

    private $data_cache = array();
    private $from_cache = array();
    private $to_cache = array();

    public function __construct( $name = 'import-xml' ) {
        parent::__construct( $name );

        // Actions
        add_action( 'admin_menu', array( $this, 'import_menu' ) );
        add_action( 'admin_menu', array( $this, 'import_manual_menu' ) );
    }

    public function import_menu() {
        add_submenu_page( '', __( 'Import XML', 'custom-related-posts' ), __( 'Import XML', 'custom-related-posts' ), 'manage_options', 'crp_import_xml', array( $this, 'import_page' ) );
    }

    public function import_page() {
        if ( !current_user_can('manage_options') ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        require( $this->addonDir. '/templates/before_importing.php' );
    }

    public function import_manual_menu() {
        add_submenu_page( '', __( 'Import XML', 'custom-related-posts' ), __( 'Import XML', 'custom-related-posts' ), 'manage_options', 'crp_import_xml_manual', array( $this, 'import_manual_page' ) );
    }

    public function import_manual_page() {
        if ( !wp_verify_nonce( $_POST['crp_import_xml'], 'crp_import_xml' ) ) {
            die( 'Invalid nonce.' );
        }

        $relations = simplexml_load_file( $_FILES['xml']['tmp_name'] );

        echo '<h2>Relations Imported</h2>';

        $post_ids = array();

        $i = 0;
        foreach( $relations as $xml_relation ) {
            $base_id = isset( $xml_relation->attributes()->from ) ? intval( (string) $xml_relation->attributes()->from ) : 0;
            $target_id = isset( $xml_relation->attributes()->to ) ? intval( (string) $xml_relation->attributes()->to ) : 0;

            $from = isset( $xml_relation->attributes()->both ) && (string) $xml_relation->attributes()->both == 'true' ? true : false;
            $to = true;

            $base = get_post( $base_id );
            $target = get_post( $target_id );

            if( $base_id !== $target_id && is_object( $base ) && is_object( $target ) ) {
                $post_ids[] = $base_id;
                $post_ids[] = $target_id;

                $base_data = $this->get_data( $base );
                $target_data = $this->get_data( $target );

                // Current Relations
                $base_relations_from = $this->get_from( $base_id );
                $base_relations_to = $this->get_to( $base_id );
                $target_relations_from = $this->get_from( $target_id );
                $target_relations_to = $this->get_to( $target_id );

                if( $from ) {
                    $base_relations_from[$target_id] = $target_data;
                    $target_relations_to[$base_id] = $base_data;

                    $this->update_to( $target_id, $target_relations_to );
                    $this->update_from( $base_id, $base_relations_from );
                }

                if( $to ) {
                    $base_relations_to[$target_id] = $target_data;
                    $target_relations_from[$base_id] = $base_data;

                    $this->update_to( $base_id, $base_relations_to );
                    $this->update_from( $target_id, $target_relations_from );
                }

                $i++;
            }
        }

        $post_ids = array_unique( $post_ids );

        foreach( $post_ids as $post_id ) {

            if( isset( $this->from_cache[$post_id] ) ) {
                CustomRelatedPosts::get()->helper( 'relations' )->update_from( $post_id, $this->from_cache[$post_id] );
            }

            if( isset( $this->to_cache[$post_id] ) ) {
                CustomRelatedPosts::get()->helper( 'relations' )->update_to( $post_id, $this->to_cache[$post_id] );
            }
        }

        if( $i == 0  ) {
            echo 'No relations found';
        } else {
            echo 'Imported ' . $i . ' relations';
        }
    }

    private function get_data( $post )
    {
        if( !isset( $this->data_cache[$post->ID] ) ) {
            $this->data_cache[$post->ID] = CustomRelatedPosts::get()->relation_data( $post );
        }
        return $this->data_cache[$post->ID];
    }

    private function get_from( $post_id )
    {
        if( !isset( $this->from_cache[$post_id] ) ) {
            $this->from_cache[$post_id] = CustomRelatedPosts::get()->relations_from( $post_id );
        }
        return $this->from_cache[$post_id];
    }

    private function get_to( $post_id )
    {
        if( !isset( $this->to_cache[$post_id] ) ) {
            $this->to_cache[$post_id] = CustomRelatedPosts::get()->relations_to( $post_id );
        }
        return $this->to_cache[$post_id];
    }

    private function update_from( $post_id, $relations )
    {
        $this->from_cache[$post_id] = $relations;
    }

    private function update_to( $post_id, $relations )
    {
        $this->to_cache[$post_id] = $relations;
    }
}

CustomRelatedPosts::loaded_addon( 'import-xml', new CRP_Import_Xml() );